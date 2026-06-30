<?php
// app/Http/Controllers/ProductController.php
namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::with([
            'category.parent',
            'variants' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'),
            'images' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order'),
            'productImages.color',
            'media' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order'),
            'reviews' => fn ($q) => $q->where('is_active', true)->latest()->limit(10),
        ])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $recentlyViewedIds = collect(session()->get('recently_viewed_products', []))
            ->reject(fn ($id) => (int) $id === (int) $product->id)
            ->values();

        $recentlyViewedProducts = Product::with(['images', 'media', 'variants'])
            ->whereIn('id', $recentlyViewedIds)
            ->where('is_active', true)
            ->get()
            ->sortBy(fn ($item) => $recentlyViewedIds->search($item->id))
            ->values();

        session()->put(
            'recently_viewed_products',
            $recentlyViewedIds->prepend($product->id)->unique()->take(8)->values()->all()
        );

        $relatedProducts = Product::with(['images', 'media', 'variants'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('froentend.product.show', compact('product', 'relatedProducts', 'recentlyViewedProducts'));
    }
}
