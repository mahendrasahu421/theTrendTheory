<?php
// app/Http/Controllers/Admin/CouponController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponRule;
use App\Models\CouponUsage;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    public function index()
    {
        return view('admin.coupons.index');
    }

    public function ajax(Request $request)
    {
        $q = Coupon::with('rules')->withCount('usage as used_count');

        if ($request->filled('search')) {
            $q->where('code', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('type')) {
            $q->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $q->where('is_active', (bool) (int) $request->status);
        }

        $result = $q->latest()->paginate(
            (int) $request->get('per_page', 15),
            ['*'],
            'page',
            (int) $request->get('page', 1)
        );

        return response()->json([
            'data' => $result->map(fn($c) => [
                'id' => $c->id,
                'code' => $c->code,
                'description' => $c->description,
                'type' => $c->type,
                'value' => $c->value,
                'is_active' => (bool) $c->is_active,
                'used_count' => $c->used_count,
                'usage_limit' => $c->rules?->usage_limit_total,
                'min_order' => $c->rules?->min_order_amount,
                'max_discount' => $c->rules?->max_discount_amount,
                'valid_until' => $c->rules?->valid_until
                    ? \Carbon\Carbon::parse($c->rules->valid_until)->format('d M Y')
                    : null,
                'is_expired' => $c->rules?->valid_until
                    ? now()->gt($c->rules->valid_until)
                    : false,
                'edit_url' => route('admin.coupons.edit', $c),
            ]),
            'total' => $result->total(),
            'per_page' => $result->perPage(),
            'current_page' => $result->currentPage(),
            'last_page' => $result->lastPage(),
            'from' => $result->firstItem() ?? 0,
            'to' => $result->lastItem() ?? 0,
        ]);
    }

    public function toggle(Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);
        return response()->json(['success' => true]);
    }

    public function create()
    {
        $products = \App\Models\Product::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'price']);

        $categories = \App\Models\Category::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.coupons.form', compact('products', 'categories'));
    }

    public function store(Request $request)
{
    $request->validate([
        'code' => 'required|string|max:50|unique:coupons,code',
        'type' => 'required|in:percent,flat',
        'value' => 'required|numeric|min:0',
        'description' => 'nullable|string|max:255',
        'min_order_amount' => 'nullable|numeric|min:0',
        'max_discount_amount' => 'nullable|numeric|min:0',
        'usage_limit_total' => 'nullable|integer|min:1',
        'usage_limit_per_user' => 'nullable|integer|min:1',
        'valid_from' => 'nullable|date',
        'valid_until' => 'nullable|date|after:valid_from',
        'products' => 'nullable|array',
        'products.*' => 'exists:products,id',
        'categories' => 'nullable|array',
        'categories.*' => 'exists:categories,id',
    ]);
    
    DB::beginTransaction();
    
    try {
        // Create coupon
        $coupon = Coupon::create([
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'value' => $request->value,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true),
        ]);
        
        // Create rules
        CouponRule::create([
            'coupon_id' => $coupon->id,
            'min_order_amount' => $request->min_order_amount ?? 0,
            'max_discount_amount' => $request->max_discount_amount,
            'usage_limit_total' => $request->usage_limit_total,
            'usage_limit_per_user' => $request->usage_limit_per_user ?? 1,
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until,
        ]);
        
        // Handle products - convert string to array if needed
        $products = $request->input('products', []);
        if (is_string($products)) {
            $products = array_filter(explode(',', $products));
        }
        $products = array_map('intval', $products);
        
        // Handle categories - convert string to array if needed
        $categories = $request->input('categories', []);
        if (is_string($categories)) {
            $categories = array_filter(explode(',', $categories));
        }
        $categories = array_map('intval', $categories);
        
        // Attach products
        if (!empty($products)) {
            $coupon->products()->attach($products);
        }
        
        // Attach categories
        if (!empty($categories)) {
            $coupon->categories()->attach($categories);
        }
        
        DB::commit();
        
        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon "' . $coupon->code . '" created!');
            
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Failed to create coupon: ' . $e->getMessage()]);
    }
}

    public function edit(Coupon $coupon)
    {
        $coupon->load(['rules', 'products', 'categories', 'usage' => fn($q) => $q->with('user', 'order')->latest()->limit(10)]);

        // Get all active products and categories
        $products = \App\Models\Product::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'price']);

        $categories = \App\Models\Category::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.coupons.form', compact('coupon', 'products', 'categories'));
    }

   public function update(Request $request, Coupon $coupon)
{
    $request->validate([
        'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
        'type' => 'required|in:percent,flat',
        'value' => 'required|numeric|min:0',
        'description' => 'nullable|string|max:255',
        'min_order_amount' => 'nullable|numeric|min:0',
        'max_discount_amount' => 'nullable|numeric|min:0',
        'usage_limit_total' => 'nullable|integer|min:1',
        'usage_limit_per_user' => 'nullable|integer|min:1',
        'valid_from' => 'nullable|date',
        'valid_until' => 'nullable|date|after:valid_from',
        'products' => 'nullable|array',  // Expects array
        'products.*' => 'exists:products,id',
        'categories' => 'nullable|array', // Expects array
        'categories.*' => 'exists:categories,id',
    ]);

    DB::beginTransaction();
    
    try {
        // Update coupon
        $coupon->update([
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'value' => $request->value,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
        ]);
        
        // Update rules
        $coupon->rules()->updateOrCreate(
            ['coupon_id' => $coupon->id],
            [
                'min_order_amount' => $request->min_order_amount ?? 0,
                'max_discount_amount' => $request->max_discount_amount,
                'usage_limit_total' => $request->usage_limit_total,
                'usage_limit_per_user' => $request->usage_limit_per_user ?? 1,
                'valid_from' => $request->valid_from,
                'valid_until' => $request->valid_until,
            ]
        );
        
        // Sync products - this expects an array of IDs
        $products = $request->input('products', []);
        
        // If products is a string (comma-separated), convert to array
        if (is_string($products)) {
            $products = array_filter(explode(',', $products));
        }
        
        // Sync categories - this expects an array of IDs
        $categories = $request->input('categories', []);
        
        // If categories is a string (comma-separated), convert to array
        if (is_string($categories)) {
            $categories = array_filter(explode(',', $categories));
        }
        
        // Convert to integers
        $products = array_map('intval', $products);
        $categories = array_map('intval', $categories);
        
        // Sync relationships
        $coupon->products()->sync($products);
        $coupon->categories()->sync($categories);
        
        DB::commit();
        
        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon "' . $coupon->code . '" updated!');
            
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Failed to update coupon: ' . $e->getMessage()]);
    }
}

    public function destroy(Coupon $coupon)
    {
        $coupon->update(['is_active' => false]);
        return back()->with('success', 'Coupon deactivated.');
    }
}