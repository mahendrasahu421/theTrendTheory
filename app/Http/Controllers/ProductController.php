<?php
// app/Http/Controllers/ProductController.php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function quickView(Product $product)
    {
        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
            ], 404);
        }

        $product->load([
            'productImages' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order'),
            'media' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order'),
            'variants' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'),
            'variants.size',
        ]);

        $gallery = collect()
            ->merge($product->productImages->map(fn ($image) => $image->getImageUrl(900, 1100)))
            ->merge($product->media->map(fn ($media) => $media->getImageUrl(900, 1100)))
            ->push($product->image_url)
            ->push($product->main_image)
            ->push($product->card_image)
            ->filter()
            ->unique()
            ->values();

        if ($gallery->isEmpty()) {
            $gallery->push(asset('images/placeholder-product.jpg'));
        }

        $sizes = $product->variants
            ->filter(fn ($variant) => is_null($variant->stock) || $variant->stock > 0)
            ->map(fn ($variant) => optional($variant->size)->name ?: $variant->size)
            ->filter()
            ->unique()
            ->values();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'url' => route('product.show', $product->slug),
                'price' => (float) $product->price,
                'original_price' => $product->original_price ? (float) $product->original_price : null,
                'discount_percent' => $product->discount_percent,
                'image' => $gallery->first(),
                'image_url' => $gallery->first(),
                'gallery' => $gallery,
                'sizes' => $sizes,
                'stock_status' => $product->stock_status,
                'is_in_stock' => $product->stock_status !== 'out_of_stock',
                'short_description' => $product->short_description,
                'front_image' => $product->front_image,
                'back_image' => $product->back_image,
                'available_print_sides' => $product->available_print_sides ?: 'both',
            ],
        ]);
    }

    public function show(Request $request, string $slug, ?string $colorSlug = null)
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

        $selectedColor = $this->selectedColorName($product, $colorSlug ?: $request->query('color'));

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

        $colorGroupId = $product->parent_product_id ?: $product->id;
        $linkedColorProducts = Product::with(['images', 'media', 'productImages'])
            ->where('is_active', true)
            ->where(function ($query) use ($colorGroupId) {
                $query->where('id', $colorGroupId)
                    ->orWhere('parent_product_id', $colorGroupId);
            })
            ->orderBy('id')
            ->get();

        // Log user activity
        \App\Helpers\ActivityLogger::log('product_viewed', "Viewed: {$product->name}", [
            'product_id'    => $product->id,
            'product_name'  => $product->name,
            'price'         => (float) $product->price,
            'slug'          => $product->slug,
            'category'      => $product->category?->name,
        ]);

        return view('froentend.product.show', compact('product', 'relatedProducts', 'recentlyViewedProducts', 'linkedColorProducts', 'selectedColor'));
    }

    private function selectedColorName(Product $product, ?string $colorSlug): ?string
    {
        if (!$colorSlug) {
            return null;
        }

        $colors = $product->variants
            ->pluck('color')
            ->merge($product->productImages->pluck('color.name'))
            ->filter()
            ->unique()
            ->values();

        $normalizedColorSlug = Str::slug($colorSlug);

        return $colors->first(fn ($color) => Str::slug($color) === $normalizedColorSlug) ?: null;
    }
}
