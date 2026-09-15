<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\InventoryLog;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $category = $request->query('category');
        $search = $request->query('search');
        $sort = $request->query('sort', 'stock_asc');

        $query = Product::query()->with('category');

        // Filter by Stock Status
        if ($status === 'out_of_stock') {
            $query->where('stock', '<=', 0);
        } elseif ($status === 'low_stock') {
            $query->where('stock', '>', 0)
                ->where(function ($q) {
                    $q->whereColumn('stock', '<=', 'low_stock_alert')
                        ->orWhere('stock', '<=', 10);
                });
        } elseif ($status === 'in_stock') {
            $query->where('stock', '>', 10);
        }

        // Filter by Category
        if ($category) {
            $query->where('category_id', $category);
        }

        // Search Query
        if ($search) {
            $s = trim($search);
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('sku', 'like', "%{$s}%")
                    ->orWhere('color_name', 'like', "%{$s}%");
            });
        }

        // Dynamic Sorting
        switch ($sort) {
            case 'stock_desc':
                $query->orderBy('stock', 'desc');
                break;
            case 'sold_desc':
                $query->orderBy('total_sold', 'desc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'stock_asc':
            default:
                $query->orderBy('stock', 'asc')->orderBy('total_sold', 'desc');
                break;
        }

        $products = $query->paginate(20)->withQueryString();

        // ── AJAX DataTable Response ──
        if ($request->ajax() || $request->wantsJson() || $request->has('ajax')) {
            return response()->json([
                'success' => true,
                'table_html' => view('admin.inventory.partials.table_rows', compact('products'))->render(),
                'pagination_html' => view('admin.inventory.partials.pagination', compact('products'))->render(),
                'showing_text' => 'Showing <strong>' . ($products->firstItem() ?? 1) . '</strong> to <strong>' . ($products->lastItem() ?? $products->count()) . '</strong> of <strong>' . $products->total() . '</strong> inventory items',
                'total' => $products->total(),
            ]);
        }

        // ── Inventory Summary KPIs ──
        $totalProductsCount = Product::count();
        $totalStockUnits = (int) Product::sum('stock');
        $totalStockValuation = (float) Product::select(DB::raw('SUM(stock * COALESCE(cost_price, price * 0.5)) as val'))->value('val') ?? 0;
        
        $lowStockCount = Product::where('stock', '>', 0)
            ->where(function ($q) {
                $q->whereColumn('stock', '<=', 'low_stock_alert')
                    ->orWhere('stock', '<=', 10);
            })->count();

        $outOfStockCount = Product::where('stock', '<=', 0)->count();

        $categories = Category::where('is_active', true)->select('id', 'name')->orderBy('name')->get();

        // Recent Stock Adjustment Logs
        $recentLogs = InventoryLog::with(['product', 'user'])->latest()->limit(8)->get();

        return view('admin.inventory.index', compact(
            'products',
            'status',
            'category',
            'search',
            'sort',
            'totalProductsCount',
            'totalStockUnits',
            'totalStockValuation',
            'lowStockCount',
            'outOfStockCount',
            'categories',
            'recentLogs'
        ));
    }

    /**
     * AJAX Update Stock for a Product
     */
    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'action' => 'required|in:set,add,subtract',
            'amount' => 'required|integer',
            'reason' => 'nullable|string|max:255',
        ]);

        $before = $product->stock;
        $amount = (int) $request->amount;

        if ($request->action === 'set') {
            $after = max(0, $amount);
        } elseif ($request->action === 'add') {
            $after = $before + $amount;
        } elseif ($request->action === 'subtract') {
            $after = max(0, $before - $amount);
        } else {
            $after = $before;
        }

        $product->update(['stock' => $after]);

        // Record into inventory log
        InventoryLog::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'type' => $after >= $before ? 'restock' : 'adjustment',
            'quantity' => abs($after - $before),
            'stock_before' => $before,
            'stock_after' => $after,
            'reason' => $request->reason ?: 'Manual stock update via Inventory Manager',
        ]);

        return response()->json([
            'success' => true,
            'product_id' => $product->id,
            'stock' => $product->stock,
            'message' => "Stock updated to {$product->stock} units.",
        ]);
    }

    /**
     * Update Low Stock Alert Threshold
     */
    public function updateThreshold(Request $request, Product $product)
    {
        $request->validate([
            'low_stock_alert' => 'required|integer|min:1|max:1000',
        ]);

        $product->update(['low_stock_alert' => $request->low_stock_alert]);

        return response()->json([
            'success' => true,
            'message' => "Alert threshold updated to {$product->low_stock_alert} units.",
        ]);
    }
}
