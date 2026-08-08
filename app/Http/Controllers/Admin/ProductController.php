<?php
// app/Http/Controllers/Admin/ProductController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Product, Category, ProductVariant, ProductImage, Size, Color, Media, Tag};
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProductController extends Controller
{
    protected $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }

    // ── Index ────────────────────────────────────────
    public function index()
    {
        return view('admin.products.index');
    }

    // ── AJAX DataTable ───────────────────────────────
    public function ajax(Request $request)
    {
        $perPageInput = $request->get('per_page', 'all');
        $perPage = $perPageInput === 'all' ? 5000 : max(1, min((int) $perPageInput, 5000));
        $page = (int) $request->get('page', 1);
        $search = trim($request->get('search', ''));
        $catId = $request->get('category', '');
        $status = $request->get('status', '');

        $q = Product::with(['category', 'media']);

        if (Schema::hasTable('product_variants')) {
            $q->withCount('variants');
        }

        if ($search)
            $q->where(
                fn($qq) =>
                $qq->where('name', 'like', '%' . $search . '%')
                    ->orWhere('sku', 'like', '%' . $search . '%')
            );
        if ($catId)
            $q->where('category_id', $catId);
        if ($status !== '')
            $q->where('is_active', (bool) (int) $status);

        $result = $q->latest()->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $result->map(function ($p) {
                $image = $this->productListImage($p);

                $categoryName = '—';
                if ($p->relationLoaded('category') && $p->category) {
                    $categoryName = $p->category->name ?? '—';
                } elseif (isset($p->category) && $p->category) {
                    $categoryName = $p->category->name ?? '—';
                }

                $variantsCount = 0;
                if (isset($p->variants_count) && is_numeric($p->variants_count)) {
                    $variantsCount = (int) $p->variants_count;
                }

                return [
                    'id' => (int) $p->id,
                    'name' => (string) $p->name,
                    'slug' => (string) $p->slug,
                    'sku' => $p->sku !== null && $p->sku !== '' ? (string) $p->sku : '—',
                    'category' => (string) $categoryName,
                    'price' => (float) ($p->price ?? 0),
                    'original_price' => (float) ($p->original_price ?? 0),
                    'cost_price' => (float) ($p->cost_price ?? 0),
                    'stock' => (int) ($p->stock ?? 0),
                    'total_sold' => (int) ($p->total_sold ?? 0),
                    'has_variants' => (bool) ($p->has_variants ?? false),
                    'variants_count' => $variantsCount,
                    'image' => $image,
                    'is_active' => (bool) ($p->is_active ?? false),
                    'is_featured' => (bool) ($p->is_featured ?? false),
                    'is_new' => (bool) ($p->is_new ?? false),
                    'is_trending' => (bool) ($p->is_trending ?? false),
                    'is_on_sale' => (bool) ($p->is_on_sale ?? false),
                    'edit_url' => route('admin.products.edit', $p),
                    'show_url' => route('admin.products.show', $p),
                    'view_url' => route('product.show', $p->slug),
                ];
            }),
            'total' => $result->total(),
            'per_page' => $result->perPage(),
            'current_page' => $result->currentPage(),
            'last_page' => $result->lastPage(),
            'from' => $result->firstItem() ?? 0,
            'to' => $result->lastItem() ?? 0,
        ]);
    }

    // ── Create ───────────────────────────────────────
    public function create()
    {
        $sizes = Size::where('is_active', true)->orderBy('sort_order')->get();
        $colors = Color::where('is_active', true)->orderBy('sort_order')->get();
        $linkedProducts = Product::where('is_active', true)
            ->whereNull('parent_product_id')
            ->orderBy('name')
            ->get(['id', 'name', 'color_name']);
        $categories = Category::where('is_active', true)
            ->orderBy('parent_id')->orderBy('sort_order')->orderBy('name')
            ->get();
        $tags = Tag::orderBy('name')->get();
        return view('admin.products.form', compact('categories', 'sizes', 'colors', 'linkedProducts', 'tags'));
    }

    // ── Store ────────────────────────────────────────
    public function store(Request $request)
    {
        $hasVariants = $request->boolean('has_variants');

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => !$hasVariants ? 'required|numeric|min:0' : 'nullable',
            'stock' => !$hasVariants ? 'required|integer|min:0' : 'nullable',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'parent_product_id' => 'nullable|exists:products,id',
            'color_name' => 'nullable|string|max:100',
            'color_hex' => 'nullable|string|max:20',
            'short_description' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:170',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:tags,id',
            'tag_names' => 'nullable|string|max:500',
            'color_images.*.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $slug = Str::slug($request->name);
        $orig = $slug;
        $n = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $orig . '-' . $n++;
        }

        DB::beginTransaction();
        try {
            $product = Product::create([
                'name' => $request->name,
                'slug' => $slug,
                'category_id' => $request->category_id,
                'sku' => $request->sku ?? null,
                'parent_product_id' => $request->parent_product_id ?: null,
                'product_type' => $request->parent_product_id ? 'color_variant' : ($request->filled('color_name') ? 'color_variant_parent' : null),
                'color_name' => $request->color_name,
                'color_hex' => $request->color_hex,
                'short_description' => $request->short_description,
                'description' => $this->cleanDescription($request->description),
                'price' => !$hasVariants ? ($request->price ?? 0) : 0,
                'original_price' => !$hasVariants ? ($request->original_price ?? null) : null,
                'cost_price' => !$hasVariants ? ($request->cost_price ?? null) : null,
                'stock' => !$hasVariants ? ($request->stock ?? 0) : 0,
                'has_variants' => $hasVariants,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'og_image' => $request->og_image ?? null,
                'is_active' => $request->boolean('is_active', true),
                'is_featured' => $request->boolean('is_featured'),
                'is_new' => $request->boolean('is_new', true),
                'is_trending' => $request->boolean('is_trending'),
                'is_on_sale' => $request->boolean('is_on_sale'),
                'fabric' => $request->fabric,
                'fit' => $request->fit,
                'care_instructions' => $request->care_instructions,
                'weight_grams' => $request->weight_grams,
            ]);

            if ($hasVariants && !empty($request->variants)) {
                $rows = $this->variantRows($request->input('variants', []), $product->id);

                if (!empty($rows)) {
                    DB::table('product_variants')->insert($rows);
                    $this->syncVariants($product);
                }
            }

            $this->storeColorImages($request, $product);
            $this->syncTagsFromRequest($request, $product);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }

        return redirect()->route('admin.products.edit', $product)
            ->with('success', '✓ Product saved! Now upload images.');
    }

    // ── Edit ─────────────────────────────────────────
    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $sizes = Size::where('is_active', true)->orderBy('sort_order')->get();
        $colors = Color::where('is_active', true)->orderBy('sort_order')->get();
        $linkedProducts = Product::where('is_active', true)
            ->whereNull('parent_product_id')
            ->where('id', '!=', $product->id)
            ->orderBy('name')
            ->get(['id', 'name', 'color_name']);
        $tags = Tag::orderBy('name')->get();

        $product->load(['variants', 'tags']);

        $images = $product->images()->orderByDesc('is_primary')->orderBy('sort_order')->get();

        return view('admin.products.form', compact('product', 'categories', 'sizes', 'colors', 'images', 'linkedProducts', 'tags'));
    }

    // ── Update ───────────────────────────────────────
    public function update(Request $request, Product $product)
    {
        $hasVariants = $request->boolean('has_variants');

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'parent_product_id' => 'nullable|exists:products,id|not_in:' . $product->id,
            'color_name' => 'nullable|string|max:100',
            'color_hex' => 'nullable|string|max:20',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:tags,id',
            'tag_names' => 'nullable|string|max:500',
            'color_images.*.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $product->update([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'sku' => $request->sku ?? null,
                'parent_product_id' => $request->parent_product_id ?: null,
                'product_type' => $request->parent_product_id ? 'color_variant' : ($request->filled('color_name') ? 'color_variant_parent' : null),
                'color_name' => $request->color_name,
                'color_hex' => $request->color_hex,
                'short_description' => $request->short_description,
                'description' => $this->cleanDescription($request->description),
                'price' => !$hasVariants ? ($request->price ?? $product->price) : $product->price,
                'original_price' => !$hasVariants ? ($request->original_price ?? null) : $product->original_price,
                'cost_price' => !$hasVariants ? ($request->cost_price ?? null) : $product->cost_price,
                'stock' => !$hasVariants ? ($request->stock ?? 0) : $product->stock,
                'has_variants' => $hasVariants,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'og_image' => $request->og_image ?? $product->og_image,
                'is_active' => $request->boolean('is_active'),
                'is_featured' => $request->boolean('is_featured'),
                'is_new' => $request->boolean('is_new'),
                'is_trending' => $request->boolean('is_trending'),
                'is_on_sale' => $request->boolean('is_on_sale'),
                'fabric' => $request->fabric,
                'fit' => $request->fit,
                'care_instructions' => $request->care_instructions,
                'weight_grams' => $request->weight_grams,
            ]);

            if ($hasVariants) {
                $rows = $this->variantRows($request->input('variants', []), $product->id);
                $existingRows = [];
                $newRows = [];
                $savedIds = [];

                foreach ($rows as $row) {
                    if (!empty($row['id'])) {
                        $savedIds[] = (int) $row['id'];
                        $existingRows[] = $row;
                    } else {
                        unset($row['id']);
                        $newRows[] = $row;
                    }
                }

                if (!empty($savedIds)) {
                    $product->variants()->whereNotIn('id', $savedIds)->delete();
                } else {
                    $product->variants()->delete();
                }

                if (!empty($existingRows)) {
                    DB::table('product_variants')->upsert(
                        $existingRows,
                        ['id'],
                        ['size', 'color', 'color_hex', 'sku', 'price', 'original_price', 'cost_price', 'stock', 'sort_order', 'is_active', 'updated_at']
                    );
                }

                if (!empty($newRows)) {
                    DB::table('product_variants')->insert($newRows);
                }

                $this->syncVariants($product);
            } elseif (!$hasVariants) {
                $product->variants()->delete();
            }

            $this->storeColorImages($request, $product);
            $this->syncTagsFromRequest($request, $product);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }

        return redirect()->route('admin.products.edit', $product)
            ->with('success', '✓ Product updated successfully!');
    }

    // ── Show ──────────────────────────────────────────
    public function show(Product $product)
    {
        $product->load(['category', 'variants']);
        return view('admin.products.show', compact('product'));
    }

    // ── Destroy ──────────────────────────────────────
    public function destroy(Product $product)
    {
        $product->update(['is_active' => false]);
        return back()->with('success', 'Product deactivated.');
    }

    // ── Helper: sync stock+price from variants ────────
    private function syncVariants(Product $product): void
    {
        if (!Schema::hasTable('product_variants'))
            return;

        $variantSummary = $product->variants()
            ->where('is_active', true)
            ->selectRaw('COALESCE(SUM(stock), 0) as total_stock, MIN(price) as min_price')
            ->first();

        $product->updateQuietly([
            'stock' => (int) ($variantSummary->total_stock ?? 0),
            'price' => $variantSummary->min_price ?? $product->price,
        ]);
    }

    private function variantRows(array $variants, int $productId): array
    {
        $now = now();
        $rows = [];

        foreach ($variants as $i => $v) {
            if (($v['price'] ?? '') === '' && ($v['stock'] ?? '') === '') {
                continue;
            }

            $row = [
                'product_id' => $productId,
                'size' => $v['size'] ?? null,
                'color' => $v['color'] ?? null,
                'color_hex' => $v['color_hex'] ?? null,
                'sku' => $v['sku'] ?? null,
                'price' => $v['price'] ?? 0,
                'original_price' => $v['original_price'] ?? null,
                'cost_price' => $v['cost_price'] ?? null,
                'stock' => (int) ($v['stock'] ?? 0),
                'sort_order' => (int) $i,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (!empty($v['id'])) {
                $row['id'] = (int) $v['id'];
            }

            $rows[] = $row;
        }

        return $rows;
    }

    private function storeColorImages(Request $request, Product $product): void
    {
        $colorImageGroups = $request->file('color_images', []);
        if (empty($colorImageGroups)) {
            return;
        }

        foreach ($colorImageGroups as $colorId => $files) {
            $files = is_array($files) ? $files : [$files];
            $color = $colorId ? Color::find($colorId) : null;

            foreach ($files as $file) {
                if (!$file || !$file->isValid()) {
                    continue;
                }

                $upload = $this->cloudinary->upload($file, 'products/' . $product->id);
                $url = $upload['url'] ?? null;
                if (!$url) {
                    continue;
                }

                $isColorPrimary = !ProductImage::where('product_id', $product->id)
                    ->where('color_id', $color?->id)
                    ->exists();

                if ($isColorPrimary) {
                    ProductImage::where('product_id', $product->id)
                        ->where('color_id', $color?->id)
                        ->update(['is_primary' => false]);
                }

                $image = ProductImage::create([
                    'product_id' => $product->id,
                    'color_id' => $color?->id,
                    'file_id' => $upload['public_id'] ?? $upload['fileId'] ?? null,
                    'url' => $url,
                    'alt_text' => trim($product->name . ' ' . ($color?->name ?? '')),
                    'is_primary' => $isColorPrimary,
                    'sort_order' => ProductImage::where('product_id', $product->id)
                        ->where('color_id', $color?->id)
                        ->count(),
                ]);

                if (!$product->image || !ProductImage::where('product_id', $product->id)->where('id', '!=', $image->id)->exists()) {
                    $product->updateQuietly(['image' => $url]);
                }
            }
        }
    }

    private function cleanDescription(?string $description): ?string
    {
        $description = trim((string) $description);
        if ($description === '') {
            return null;
        }

        $description = preg_replace('#<(script|style|iframe|object|embed)\b[^>]*>.*?</\1>#is', '', $description);
        $description = strip_tags($description, '<p><div><br><strong><b><em><i><u><h2><h3><ul><ol><li><blockquote>');
        $description = preg_replace('/\s(?:on\w+|style|class|id)=("[^"]*"|\'[^\']*\'|[^\s>]*)/i', '', $description);
        $description = preg_replace('/javascript\s*:/i', '', $description);

        return trim($description);
    }

    private function syncTagsFromRequest(Request $request, Product $product): void
    {
        if (!Schema::hasTable('tags') || !Schema::hasTable('product_tags')) {
            return;
        }

        $tagIds = collect($request->input('tag_ids', []))
            ->filter()
            ->map(fn($id) => (int) $id)
            ->all();

        $newTagNames = collect(explode(',', (string) $request->input('tag_names', '')))
            ->map(fn($name) => trim($name))
            ->filter()
            ->unique(fn($name) => Str::lower($name));

        foreach ($newTagNames as $name) {
            $slug = Str::slug($name);
            if ($slug === '') {
                continue;
            }

            $tag = Tag::firstOrCreate(
                ['slug' => $slug],
                ['name' => $name]
            );

            $tagIds[] = $tag->id;
        }

        $product->tags()->sync(array_values(array_unique($tagIds)));
    }

    private function productListImage(Product $product): ?string
    {
        if ($product->relationLoaded('media') && $product->media->isNotEmpty()) {
            $media = $product->media->firstWhere('is_primary', true) ?? $product->media->first();
            return $media->thumb_url ?: $media->url;
        }

        if (!empty($product->image)) {
            if (filter_var($product->image, FILTER_VALIDATE_URL)) {
                return $product->image;
            }

            if (str_starts_with($product->image, '/')) {
                return $product->image;
            }

            return asset($product->image);
        }

        return null;
    }

    // ── Upload Image (AJAX) ────────────────────────────
    public function uploadImage(Request $request, Product $product)
    {
        $request->validate(['image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120']);

        try {
            $file = $request->file('image');
            $folder = 'products/' . $product->id;
            $result = $this->cloudinary->upload($file, $folder);


            $isPrimary = $product->images()->count() === 0;

            $image = ProductImage::create([
                'product_id' => $product->id,
                'file_id' => $result['fileId'],
                'url' => $result['url'],
                'alt_text' => $product->name,
                'is_primary' => $isPrimary,
                'sort_order' => $product->images()->max('sort_order') + 1,
            ]);

            if ($isPrimary) {
                $product->updateQuietly(['image' => $result['url']]);
            }

            return response()->json([
                'success' => true,
                'id' => $image->id,
                'url' => $image->url,
                'thumb' => $image->url,
                'is_primary' => $image->is_primary,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    // ── Set Primary Image (AJAX) ──────────────────────
    public function setPrimary(Request $request, Product $product, ProductImage $image)
    {
        $product->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);
        $product->updateQuietly(['image' => $image->url]);
        return response()->json(['success' => true]);
    }

    // ── Delete Image (AJAX) ────────────────────────────
    public function deleteImage(Request $request, Product $product, ProductImage $image)
    {
        try {
            // If you previously stored Cloud public_id in file_id, delete from Cloudinary
            if ($image->file_id) {
                $this->cloudinary->delete($image->file_id);
            }
        } catch (\Exception $e) {
        }

        $wasPrimary = $image->is_primary;
        $image->delete();

        if ($wasPrimary) {
            $next = $product->images()->first();
            if ($next) {
                $next->update(['is_primary' => true]);
                $product->updateQuietly(['image' => $next->url]);
            } else {
                $product->updateQuietly(['image' => null]);
            }
        }

        return response()->json(['success' => true]);
    }

    // ── Toggle Product Status ─────────────────────────
    public function toggle(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        return response()->json(['success' => true, 'is_active' => $product->is_active]);
    }
}
