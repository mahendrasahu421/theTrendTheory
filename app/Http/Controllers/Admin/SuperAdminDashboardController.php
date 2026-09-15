<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Product, Order, User, Employee, Notification};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuperAdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // ── Timeframe Filter ──────────────────────────
        $filter = $request->get('filter', 'monthly');
        [$startDate, $endDate, $prevStart, $prevEnd] = $this->getDateRange($filter, $request);

        // ── Top 8 Summary KPI Data ────────────────────
        $totalSalesAllTime    = (float) (Order::where('payment_status', 'paid')->sum('total_amount') ?? 0);
        $totalOrdersCount     = Order::count();
        $totalCustomersCount  = User::where('role', 'customer')->count();
        $totalProductsCount   = Product::count();
        
        $todayRevenue         = (float) (Order::where('payment_status', 'paid')->whereDate('created_at', today())->sum('total_amount') ?? 0);
        $thisWeekRevenue      = (float) (Order::where('payment_status', 'paid')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total_amount') ?? 0);
        $thisMonthRevenue     = (float) (Order::where('payment_status', 'paid')->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('total_amount') ?? 0);

        $pendingOrdersCount   = Order::where('status', 'pending')->count();
        $deliveredOrdersCount = Order::where('status', 'delivered')->count();
        $processingCount      = Order::where('status', 'processing')->count();
        $shippedCount         = Order::where('status', 'shipped')->count();
        $cancelledCount       = Order::where('status', 'cancelled')->count();
        $returnedCount        = Order::where('status', 'returned')->count();

        $lowStockCount        = Product::where('stock', '>', 0)->where('stock', '<=', 5)->count();
        $outOfStockCount      = Product::where('stock', '<=', 0)->count();

        // ── Filtered Period Performance & Growth ───────
        $revenue     = (float) (Order::where('payment_status', 'paid')->whereBetween('created_at', [$startDate, $endDate])->sum('total_amount') ?? 0);
        $prevRevenue = (float) (Order::where('payment_status', 'paid')->whereBetween('created_at', [$prevStart, $prevEnd])->sum('total_amount') ?? 1);
        $revGrowth   = round((($revenue - $prevRevenue) / max($prevRevenue, 1)) * 100);

        $orders     = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $prevOrders = Order::whereBetween('created_at', [$prevStart, $prevEnd])->count() ?: 1;
        $ordGrowth  = round((($orders - $prevOrders) / max($prevOrders, 1)) * 100);

        $newCustomers = User::where('role', 'customer')->whereBetween('created_at', [$startDate, $endDate])->count();
        $repeatRate   = $this->getRepeatRate();
        $ltv          = $totalCustomersCount > 0 ? round(($totalSalesAllTime / max($totalCustomersCount, 1)) * 1.8) : 0;

        $orderCount   = max(Order::where('payment_status', 'paid')->count(), 1);
        $aov          = round($totalSalesAllTime / $orderCount);
        $grossMargin  = 56;
        $ebitda       = 25;

        // ── Sales & Orders Dual Chart Data ────────────
        $chartData = $this->getChartData($filter, $request);

        // ── Category Revenue Breakdown ─────────────────
        $categoryRevenue = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereNull('categories.parent_id')
            ->whereBetween('order_items.created_at', [$startDate, $endDate])
            ->select('categories.name', DB::raw('SUM(order_items.subtotal) as revenue'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('revenue')->get();

        // ── Recent Orders (Latest 8) ──────────────────
        $recentOrders = Order::with('user')
            ->latest()
            ->limit(8)
            ->get();

        // ── Latest Customers (Latest 5) ───────────────
        $latestCustomers = User::where('role', 'customer')
            ->withCount('orders')
            ->latest()
            ->limit(5)
            ->get();

        // ── Top Selling Products (Top 5) ───────────────
        $topProducts = Product::with('category')
            ->where('is_active', true)
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // ── Low Stock Products List (Urgent 5) ─────────
        $lowStockProductsList = Product::with('category')
            ->where('stock', '<=', 5)
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();

        // ── Order Status Breakdown ────────────────────
        $orderStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')->get();

        // ── Recent Notifications Feed ─────────────────
        $recentNotifications = Notification::latest()->limit(5)->get();

        // ── Multi-Vendor Mockup metrics ───────────────
        $totalVendors   = User::where('role', 'vendor')->count();
        $activeVendors  = $totalVendors;
        $vendorEarnings = round($totalSalesAllTime * 0.78);
        $pendingPayouts = round($todayRevenue * 0.70);

        return view('admin.dashboards.super_admin', compact(
            'filter', 'startDate', 'endDate',
            'totalSalesAllTime', 'totalOrdersCount', 'totalCustomersCount', 'totalProductsCount',
            'todayRevenue', 'thisWeekRevenue', 'thisMonthRevenue',
            'pendingOrdersCount', 'deliveredOrdersCount', 'processingCount', 'shippedCount', 'cancelledCount', 'returnedCount',
            'lowStockCount', 'outOfStockCount',
            'revenue', 'prevRevenue', 'revGrowth',
            'orders', 'prevOrders', 'ordGrowth',
            'newCustomers', 'repeatRate', 'ltv', 'aov', 'grossMargin', 'ebitda',
            'chartData', 'categoryRevenue', 'topProducts', 'lowStockProductsList',
            'recentOrders', 'latestCustomers', 'orderStatus', 'recentNotifications',
            'totalVendors', 'activeVendors', 'vendorEarnings', 'pendingPayouts'
        ));
    }

    private function getDateRange(string $filter, Request $request): array
    {
        return match($filter) {
            'daily' => [
                now()->startOfDay(), now()->endOfDay(),
                now()->subDay()->startOfDay(), now()->subDay()->endOfDay(),
            ],
            'weekly' => [
                now()->startOfWeek(), now()->endOfWeek(),
                now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek(),
            ],
            'quarterly' => [
                now()->startOfQuarter(), now()->endOfQuarter(),
                now()->subQuarter()->startOfQuarter(), now()->subQuarter()->endOfQuarter(),
            ],
            'yearly' => [
                now()->startOfYear(), now()->endOfYear(),
                now()->subYear()->startOfYear(), now()->subYear()->endOfYear(),
            ],
            default => [ // monthly
                now()->startOfMonth(), now()->endOfMonth(),
                now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth(),
            ],
        };
    }

    private function getChartData(string $filter, Request $request): array
    {
        $groupBy = match($filter) {
            'daily'     => 'HOUR(created_at)',
            'weekly'    => 'DATE(created_at)',
            'quarterly' => 'MONTH(created_at)',
            'yearly'    => 'MONTH(created_at)',
            default     => 'DATE(created_at)',
        };

        [$start, $end] = $this->getDateRange($filter, $request);

        $data = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->select(
                DB::raw($groupBy.' as period'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        return [
            'labels'  => $data->pluck('period')->toArray(),
            'revenue' => $data->map(fn($d) => round((float)$d->revenue))->toArray(),
            'orders'  => $data->pluck('orders')->toArray(),
        ];
    }

    private function getRepeatRate(): int
    {
        $total  = User::where('role', 'customer')->count();
        $repeat = DB::table('orders')->select('user_id')->whereNotNull('user_id')->groupBy('user_id')->havingRaw('COUNT(*) > 1')->get()->count();
        return $total > 0 ? round(($repeat / $total) * 100) : 0;
    }
}