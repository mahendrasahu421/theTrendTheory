<?php
// app/Http/Controllers/Admin/ProductEditorDashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Product, Category};

class ProductEditorDashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::where('is_active', true)->count();
        $lowStock      = Product::where('is_active', true)->where('stock', '<=', 5)->count();

        $myProducts = Product::with('category')
            ->where('is_active', true)
            ->latest()
            ->limit(50)
            ->get();

        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->orderBy('name')
            ->get();

        return view('admin.dashboards.product_editor', compact(
            'totalProducts', 'lowStock', 'myProducts', 'categories'
        ));
    }
}