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
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $draftProducts = Product::where('is_active', false)->count();
        $lowStockProducts = Product::where('stock', '<=', 10)->where('stock', '>', 0)->count();
        $outOfStockProducts = Product::where('stock', '<=', 0)->count();
        $featuredProducts = Product::where('is_featured', true)->count();

        return view('admin.products.index', compact(
            'categories',
            'totalProducts',
            'activeProducts',
            'draftProducts',
            'lowStockProducts',
            'outOfStockProducts',
            'featuredProducts'
        ));
    }

    // ── AJAX DataTable ───────────────────────────────
    public function ajax(Request $request)
    {
        $isDataTablesRequest = $request->has('draw') || $request->has('start') || $request->has('length');
        $perPageInput = $isDataTablesRequest ? $request->get('length', 10) : $request->get('per_page', 10);
        $perPage = $perPageInput === 'all' || (int) $perPageInput === -1
            ? 5000
            : max(1, min((int) $perPageInput, 5000));
        $page = $isDataTablesRequest
            ? ((int) floor(((int) $request->get('start', 0)) / max(1, $perPage)) + 1)
            : (int) $request->get('page', 1);
        $search = trim($isDataTablesRequest ? data_get($request->input('search'), 'value', '') : $request->get('search', ''));
        $catId = $request->get('category', '');
        $status = $request->get('status', '');
        $sort = $request->get('sort', 'latest');
        $recordsTotal = Product::count();

        $q = Product::with(['category', 'media']);

        if (Schema::hasTable('product_variants')) {
            $q->withCount('variants');
        }

        if ($search) {
            $q->where(function ($qq) use ($search) {
                $qq->where('name', 'like', '%' . $search . '%')
                    ->orWhere('sku', 'like', '%' . $search . '%')
                    ->orWhere('color_name', 'like', '%' . $search . '%');
            });
        }

        if ($catId) {
            $q->where('category_id', $catId);
        }

        if ($status === 'active' || $status === '1') {
            $q->where('is_active', true);
        } elseif ($status === 'inactive' || $status === '0') {
            $q->where('is_active', false);
        } elseif ($status === 'low_stock') {
            $q->where('stock', '<=', 10)->where('stock', '>', 0);
        } elseif ($status === 'out_of_stock') {
            $q->where('stock', '<=', 0);
        } elseif ($status === 'featured') {
            $q->where('is_featured', true);
        }

        if ($isDataTablesRequest && $request->filled('order.0.column')) {
            $columns = [
                0 => 'name',
                2 => 'price',
                3 => 'stock',
                4 => 'total_sold',
                5 => 'is_active',
            ];
            $column = $columns[(int) $request->input('order.0.column')] ?? 'created_at';
            $dir = $request->input('order.0.dir') === 'asc' ? 'asc' : 'desc';
            $q->orderBy($column, $dir);
        } else {
            // Sorting
            switch ($sort) {
                case 'price_asc':
                    $q->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $q->orderBy('price', 'desc');
                    break;
                case 'stock_asc':
                    $q->orderBy('stock', 'asc');
                    break;
                case 'sold_desc':
                    $q->orderBy('total_sold', 'desc');
                    break;
                case 'latest':
                default:
                    $q->latest();
                    break;
            }
        }

        $result = $q->paginate($perPage, ['*'], 'page', $page);

        $data = $result->map(function ($p) {
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
            });

        return response()->json([
            'draw' => (int) $request->get('draw', 0),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $result->total(),
            'data' => $data,
            'total' => $result->total(),
            'per_page' => $result->perPage(),
            'current_page' => $result->currentPage(),
            'last_page' => $result->lastPage(),
            'from' => $result->firstItem() ?? 0,
            'to' => $result->lastItem() ?? 0,
        ]);
    }

    // ── Bulk Actions ──────────────────────────────────
    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if (empty($ids) || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No products selected.'], 422);
        }

        switch ($action) {
            case 'activate':
                Product::whereIn('id', $ids)->update(['is_active' => true]);
                return response()->json(['success' => true, 'message' => count($ids) . ' product(s) activated successfully.']);

            case 'deactivate':
                Product::whereIn('id', $ids)->update(['is_active' => false]);
                return response()->json(['success' => true, 'message' => count($ids) . ' product(s) deactivated successfully.']);

            case 'feature':
                Product::whereIn('id', $ids)->update(['is_featured' => true]);
                return response()->json(['success' => true, 'message' => count($ids) . ' product(s) marked as featured.']);

            case 'unfeature':
                Product::whereIn('id', $ids)->update(['is_featured' => false]);
                return response()->json(['success' => true, 'message' => count($ids) . ' product(s) unfeatured.']);

            case 'delete':
                $count = count($ids);
                Product::whereIn('id', $ids)->delete();
                return response()->json(['success' => true, 'message' => $count . ' product(s) deleted successfully.']);

            default:
                return response()->json(['success' => false, 'message' => 'Invalid bulk action specified.'], 422);
        }
    }

    // ── Export CSV ────────────────────────────────────
    public function export(Request $request)
    {
        $products = Product::with('category')->latest()->get();
        $filename = 'products_catalog_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($products) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel compatibility
            fputs($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['ID', 'Name', 'SKU', 'Category', 'Price (INR)', 'Original Price (INR)', 'Stock', 'Total Sold', 'Status', 'Featured', 'Created At']);

            foreach ($products as $p) {
                fputcsv($handle, [
                    $p->id,
                    $p->name,
                    $p->sku ?: '—',
                    $p->category->name ?? 'Uncategorized',
                    $p->price,
                    $p->original_price ?: '',
                    $p->stock,
                    $p->total_sold ?? 0,
                    $p->is_active ? 'Active' : 'Inactive',
                    $p->is_featured ? 'Yes' : 'No',
                    $p->created_at ? $p->created_at->format('Y-m-d H:i') : '',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
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
        $frontPrintImages = collect();
        $backPrintImages = collect();
        return view('admin.products.form', compact('categories', 'sizes', 'colors', 'linkedProducts', 'tags', 'frontPrintImages', 'backPrintImages'));
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
            'front_image_files.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'back_image_files.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'color_images.*.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'available_print_sides' => 'nullable|string|in:both,front_only,back_only',
        ]);

        $slug = Str::slug($request->name);
        $orig = $slug;
        $n = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $orig . '-' . $n++;
        }

        $frontImage = null;
        $backImage = null;

        $availablePrintSides = in_array($request->available_print_sides, ['both', 'front_only', 'back_only'])
            ? $request->available_print_sides
            : 'both';

        DB::beginTransaction();
        try {
            $sku = $request->filled('sku')
                ? trim($request->sku)
                : $this->calculateNextSku($request->category_id ? (int)$request->category_id : null, $request->name);

            $product = Product::create([
                'name' => $request->name,
                'slug' => $slug,
                'category_id' => $request->category_id,
                'sku' => $sku,
                'parent_product_id' => $request->parent_product_id ?: null,
                'product_type' => $request->parent_product_id ? 'color_variant' : ($request->filled('color_name') ? 'color_variant_parent' : null),
                'color_name' => $request->color_name,
                'color_hex' => $request->color_hex,
                'short_description' => $request->short_description,
                'description' => $this->cleanDescription($request->description),
                'price' => !$hasVariants ? ($request->price ?? 0) : 0,
                'original_price' => !$hasVariants ? ($request->original_price ?? null) : null,
                'cost_price' => !$hasVariants ? ($request->cost_price ?? null) : null,
                'image' => $frontImage ?? null,
                'front_image' => $frontImage,
                'back_image' => $backImage,
                'available_print_sides' => $availablePrintSides,
                'stock' => !$hasVariants ? ($request->stock ?? 0) : 0,
                'has_variants' => $hasVariants,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'og_image' => $request->og_image ?? $frontImage,
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

            $this->storePrintSideImages($request, $product, 'front');
            $this->storePrintSideImages($request, $product, 'back');
            $this->storeColorImages($request, $product);
            $this->syncTagsFromRequest($request, $product);

            if ($request->filled('designated_main_image_name')) {
                $targetMedia = Media::where('model_type', Product::class)
                    ->where('model_id', $product->id)
                    ->where('file_name', $request->designated_main_image_name)
                    ->first();
                if ($targetMedia) {
                    $product->updateQuietly(['image' => $targetMedia->url]);
                }
            } elseif ($request->filled('designated_main_image')) {
                $product->updateQuietly(['image' => $request->designated_main_image]);
            }

            if (empty($product->image)) {
                $firstMedia = Media::where('model_type', Product::class)->where('model_id', $product->id)->first();
                $firstProductImg = ProductImage::where('product_id', $product->id)->whereNull('color_id')->first();
                $fallback = $product->front_image ?: ($firstMedia?->url ?: ($firstProductImg?->url ?: $product->back_image));
                if ($fallback) {
                    $product->updateQuietly(['image' => $fallback]);
                }
            }

            // Auto-set OG Image URL from resolved primary image if not provided
            if (empty($product->og_image)) {
                $ogFallback = $product->image ?: ($product->front_image ?: (Media::where('model_type', Product::class)->where('model_id', $product->id)->first()?->url ?: $product->back_image));
                if ($ogFallback) {
                    if (!filter_var($ogFallback, FILTER_VALIDATE_URL) && !str_starts_with($ogFallback, 'http')) {
                        $ogFallback = url(str_starts_with($ogFallback, '/') ? $ogFallback : '/storage/' . $ogFallback);
                    }
                    $product->updateQuietly(['og_image' => $ogFallback]);
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Product create failed: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return back()
                ->withInput()
                ->with('error', 'Could not create product: ' . $e->getMessage());
        }

        try {
            // Auto-notify registered users about new product drop
            app(\App\Services\NotificationService::class)->notifyNewProduct($product);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('New product notification failed: ' . $e->getMessage(), [
                'product_id' => $product->id,
            ]);
        }

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Product created successfully! You can now manage photos and variants.')
            ->with('open_step', 3);
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

        $product->load(['variants', 'tags', 'media', 'productImages.color']);

        $seenImageUrls = [];
        $images = collect();
        $colorImageUrls = $product->productImages
            ->whereNotNull('color_id')
            ->pluck('url')
            ->filter()
            ->all();

        foreach ($product->productImages->sortByDesc('is_primary')->sortBy('sort_order') as $productImage) {
            if ($productImage->color_id || empty($productImage->url) || isset($seenImageUrls[$productImage->url])) {
                continue;
            }

            $seenImageUrls[$productImage->url] = true;
            $images->push((object) [
                'id' => $productImage->id,
                'source' => 'product_image',
                'source_label' => $productImage->color ? $productImage->color->name : 'General',
                'url' => $productImage->url,
                'thumb_url' => $productImage->thumb_url ?? $productImage->url,
                'alt_text' => $productImage->alt_text,
                'is_primary' => $product->image === $productImage->url || (bool) $productImage->is_primary,
                'color_id' => $productImage->color_id,
                'primary_url' => route('admin.product-images.primary', $productImage),
                'delete_url' => route('admin.product-images.destroy', $productImage),
            ]);
        }

        foreach ($product->media->sortByDesc('is_primary')->sortBy('sort_order') as $media) {
            if (empty($media->url) || isset($seenImageUrls[$media->url]) || in_array($media->url, $colorImageUrls)) {
                continue;
            }

            $seenImageUrls[$media->url] = true;
            $images->push((object) [
                'id' => $media->id,
                'source' => 'media',
                'source_label' => 'Media',
                'url' => $media->url,
                'thumb_url' => $media->thumb_url ?: $media->url,
                'alt_text' => $media->alt_text,
                'is_primary' => $product->image === $media->url || (bool) $media->is_primary,
                'color_id' => null,
                'primary_url' => route('admin.media.primary', $media),
                'delete_url' => route('admin.media.destroy', $media),
            ]);
        }

        if (!empty($product->image) && !isset($seenImageUrls[$product->image]) && !in_array($product->image, $colorImageUrls)) {
            $seenImageUrls[$product->image] = true;
            $images->prepend((object) [
                'id' => 'main',
                'source' => 'direct',
                'source_label' => 'Main Image',
                'url' => $product->image,
                'thumb_url' => $product->image,
                'alt_text' => $product->name,
                'is_primary' => true,
                'color_id' => null,
                'primary_url' => null,
                'delete_url' => null,
            ]);
        }

        if (!empty($product->front_image) && !isset($seenImageUrls[$product->front_image])) {
            $seenImageUrls[$product->front_image] = true;
            $images->push((object) [
                'id' => 'front',
                'source' => 'direct',
                'source_label' => 'Front Print',
                'url' => $product->front_image,
                'thumb_url' => $product->front_image,
                'alt_text' => 'Front print image',
                'is_primary' => $product->image === $product->front_image,
                'color_id' => null,
                'primary_url' => null,
                'delete_url' => null,
            ]);
        }

        if (!empty($product->back_image) && !isset($seenImageUrls[$product->back_image])) {
            $seenImageUrls[$product->back_image] = true;
            $images->push((object) [
                'id' => 'back',
                'source' => 'direct',
                'source_label' => 'Back Print',
                'url' => $product->back_image,
                'thumb_url' => $product->back_image,
                'alt_text' => 'Back print image',
                'is_primary' => $product->image === $product->back_image,
                'color_id' => null,
                'primary_url' => null,
                'delete_url' => null,
            ]);
        }

        // Ensure exactly one image is marked as is_primary if images exist
        if ($images->isNotEmpty() && !$images->contains('is_primary', true)) {
            $images->first()->is_primary = true;
            $product->updateQuietly(['image' => $images->first()->url]);
        }

        $frontPrintImages = $this->printSideImages($product, 'front');
        $backPrintImages = $this->printSideImages($product, 'back');

        return view('admin.products.form', compact('product', 'categories', 'sizes', 'colors', 'images', 'linkedProducts', 'tags', 'frontPrintImages', 'backPrintImages'));
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
            'front_image_files.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'back_image_files.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'color_images.*.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'available_print_sides' => 'nullable|string|in:both,front_only,back_only',
        ]);

        $frontImage = $product->front_image;
        if ($request->boolean('remove_front_image')) {
            $frontImage = null;
        } elseif ($request->hasFile('front_image_file')) {
            try {
                $upload = $this->cloudinary->upload($request->file('front_image_file'), 'products');
                $frontImage = $upload['url'] ?? $frontImage;
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Front image upload error: ' . $e->getMessage());
            }
        } elseif ($request->filled('front_image')) {
            $frontImage = $request->front_image;
        }

        $backImage = $product->back_image;
        if ($request->boolean('remove_back_image')) {
            $backImage = null;
        } elseif ($request->hasFile('back_image_file')) {
            try {
                $upload = $this->cloudinary->upload($request->file('back_image_file'), 'products');
                $backImage = $upload['url'] ?? $backImage;
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Back image upload error: ' . $e->getMessage());
            }
        } elseif ($request->filled('back_image')) {
            $backImage = $request->back_image;
        }

        $availablePrintSides = in_array($request->available_print_sides, ['both', 'front_only', 'back_only'])
            ? $request->available_print_sides
            : ($product->available_print_sides ?? 'both');

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
                'image' => $frontImage ?: ($product->image ?: null),
                'front_image' => $frontImage,
                'back_image' => $backImage,
                'available_print_sides' => $availablePrintSides,
                'stock' => !$hasVariants ? ($request->stock ?? 0) : $product->stock,
                'has_variants' => $hasVariants,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'og_image' => $request->og_image ?? ($frontImage ?: $product->og_image),
                'is_active' => $request->has('is_active') ? $request->boolean('is_active') : (bool) $product->is_active,
                'is_featured' => $request->has('is_featured') ? $request->boolean('is_featured') : (bool) $product->is_featured,
                'is_new' => $request->has('is_new') ? $request->boolean('is_new') : (bool) $product->is_new,
                'is_trending' => $request->has('is_trending') ? $request->boolean('is_trending') : (bool) $product->is_trending,
                'is_on_sale' => $request->has('is_on_sale') ? $request->boolean('is_on_sale') : (bool) $product->is_on_sale,
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

            $this->storePrintSideImages($request, $product, 'front');
            $this->storePrintSideImages($request, $product, 'back');
            $this->storeColorImages($request, $product);
            $this->syncTagsFromRequest($request, $product);

            // Auto-set OG Image URL from resolved primary image if not provided
            if (empty($product->og_image) || empty($request->og_image)) {
                $ogFallback = $request->og_image ?: ($product->image ?: ($product->front_image ?: (Media::where('model_type', Product::class)->where('model_id', $product->id)->first()?->url ?: $product->back_image)));
                if ($ogFallback) {
                    if (!filter_var($ogFallback, FILTER_VALIDATE_URL) && !str_starts_with($ogFallback, 'http')) {
                        $ogFallback = url(str_starts_with($ogFallback, '/') ? $ogFallback : '/storage/' . $ogFallback);
                    }
                    $product->updateQuietly(['og_image' => $ogFallback]);
                }
            }

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
    public function destroy(Request $request, Product $product)
    {
        try {
            DB::beginTransaction();

            // 1. Delete associated variants
            $product->variants()->delete();

            // 2. Delete media and storage files safely
            $mediaItems = Media::where('model_type', Product::class)->where('model_id', $product->id)->get();
            foreach ($mediaItems as $media) {
                try {
                    if ($media->file_id) {
                        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($media->file_id)) {
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($media->file_id);
                        } else {
                            $this->cloudinary->delete($media->file_id);
                        }
                    }
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning('Product media delete error: ' . $e->getMessage());
                }
                $media->delete();
            }

            // 3. Delete product images table entries
            ProductImage::where('product_id', $product->id)->delete();

            // 4. Detach / delete foreign references
            DB::table('coupon_products')->where('product_id', $product->id)->delete();
            DB::table('reviews')->where('product_id', $product->id)->delete();
            if (Schema::hasTable('product_tags')) {
                DB::table('product_tags')->where('product_id', $product->id)->delete();
            }

            // 5. Delete product
            $product->delete();

            DB::commit();

            if ($request->wantsJson() || $request->ajax() || $request->header('Accept') === 'application/json') {
                return response()->json(['success' => true, 'message' => 'Product deleted successfully.']);
            }

            return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Error deleting product: ' . $e->getMessage());

            if ($request->wantsJson() || $request->ajax() || $request->header('Accept') === 'application/json') {
                return response()->json(['success' => false, 'message' => 'Error deleting product: ' . $e->getMessage()], 500);
            }

            return back()->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }

    // ── Helper: sync stock+price+MRP from variants ────────
    private function syncVariants(Product $product): void
    {
        if (!Schema::hasTable('product_variants'))
            return;

        $variantSummary = $product->variants()
            ->where('is_active', true)
            ->selectRaw('COALESCE(SUM(stock), 0) as total_stock, MIN(price) as min_price')
            ->first();

        $primaryVariant = $product->variants()
            ->where('is_active', true)
            ->whereNotNull('price')
            ->orderBy('price')
            ->orderByDesc('original_price')
            ->first();

        $product->updateQuietly([
            'stock' => (int) ($variantSummary->total_stock ?? 0),
            'price' => $variantSummary->min_price ?? $product->price,
            'original_price' => $primaryVariant?->original_price ?: null,
        ]);
    }

    private function variantRows(array $variants, int $productId): array
    {
        $now = now();
        $rows = [];
        $product = Product::find($productId);
        $baseSku = $product?->sku ?: 'PRD';
        $usedSkus = [];

        foreach ($variants as $i => $v) {
            if (($v['price'] ?? '') === '' && ($v['stock'] ?? '') === '') {
                continue;
            }

            $sku = trim((string)($v['sku'] ?? ''));
            if ($sku === '') {
                $colorPart = !empty($v['color']) ? strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $v['color']), 0, 4)) : '';
                $sizePart = !empty($v['size']) ? strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $v['size']), 0, 4)) : '';
                $baseCandidate = trim($baseSku . ($colorPart ? '-' . $colorPart : '') . ($sizePart ? '-' . $sizePart : ''), '-');
                $candidate = $baseCandidate;
                $suffix = 1;
                while (
                    in_array($candidate, $usedSkus, true) ||
                    DB::table('product_variants')->where('sku', $candidate)->where('product_id', '!=', $productId)->exists() ||
                    DB::table('products')->where('sku', $candidate)->where('id', '!=', $productId)->exists()
                ) {
                    $candidate = $baseCandidate . '-' . $suffix;
                    $suffix++;
                }
                $sku = $candidate;
            }
            $usedSkus[] = $sku;

            $row = [
                'product_id' => $productId,
                'size' => $v['size'] ?? null,
                'color' => $v['color'] ?? null,
                'color_hex' => $v['color_hex'] ?? null,
                'sku' => $sku,
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

                $isGeneralImage = empty($color?->id);
                $isDesignatedMain = $isGeneralImage
                    && $request->filled('designated_main_image_name')
                    && $request->input('designated_main_image_name') === $file->getClientOriginalName();

                $isColorPrimary = !ProductImage::where('product_id', $product->id)
                    ->where('color_id', $color?->id)
                    ->exists();

                if ($isColorPrimary || $isDesignatedMain) {
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
                    'is_primary' => $isDesignatedMain ?: $isColorPrimary,
                    'sort_order' => ProductImage::where('product_id', $product->id)
                        ->where('color_id', $color?->id)
                        ->count(),
                ]);

                if ($isGeneralImage && ($isDesignatedMain || !$product->image || !ProductImage::where('product_id', $product->id)->whereNull('color_id')->where('id', '!=', $image->id)->exists())) {
                    $product->updateQuietly(['image' => $url]);
                }
            }
        }
    }

    private function printSideImages(Product $product, string $side)
    {
        $collection = $this->printSideCollection($side);
        $column = $side === 'back' ? 'back_image' : 'front_image';
        $items = collect();
        $seen = [];

        // 1. Gather all media in this collection
        foreach ($product->media->where('collection', $collection)->sortBy('sort_order') as $media) {
            if (empty($media->url) || isset($seen[$media->url])) {
                continue;
            }

            $seen[$media->url] = true;
            $isSideMain = ($product->{$column} === $media->url) || (bool)$media->is_primary;
            $items->push((object) [
                'id' => $media->id,
                'source' => 'media',
                'url' => $media->url,
                'thumb_url' => $media->thumb_url ?: $media->url,
                'label' => $media->alt_text ?: ucfirst($side) . ' image',
                'is_primary' => $isSideMain,
                'collection' => $collection,
            ]);
        }

        // 2. If product column exists and wasn't in media (direct image)
        if (!empty($product->{$column}) && !isset($seen[$product->{$column}])) {
            $seen[$product->{$column}] = true;
            $items->prepend((object) [
                'id' => $side,
                'source' => 'direct',
                'url' => $product->{$column},
                'thumb_url' => $product->{$column},
                'label' => ucfirst($side) . ' main',
                'is_primary' => true,
                'collection' => $collection,
            ]);
        }

        if ($items->isNotEmpty() && !$items->contains('is_primary', true)) {
            $items->first()->is_primary = true;
        }

        return $items;
    }

    private function storePrintSideImages(Request $request, Product $product, string $side): void
    {
        $column = $side === 'back' ? 'back_image' : 'front_image';
        $collection = $this->printSideCollection($side);
        $removeInput = $side === 'back' ? 'remove_back_image' : 'remove_front_image';
        $filesInput = $side === 'back' ? 'back_image_files' : 'front_image_files';
        $legacyFileInput = $side === 'back' ? 'back_image_file' : 'front_image_file';

        $currentMainImage = $request->boolean($removeInput) ? null : $product->{$column};

        if ($request->boolean($removeInput)) {
            Media::where('model_type', Product::class)
                ->where('model_id', $product->id)
                ->where('collection', $collection)
                ->delete();
        }

        $files = $request->file($filesInput, []);
        $files = is_array($files) ? $files : [$files];

        if ($request->hasFile($legacyFileInput)) {
            $files[] = $request->file($legacyFileInput);
        }

        foreach ($files as $file) {
            if (!$file || !$file->isValid()) {
                continue;
            }

            $upload = $this->cloudinary->upload($file, 'products/' . $product->id . '/' . $side);
            $url = $upload['url'] ?? null;
            if (!$url) {
                continue;
            }

            $isDesignatedMain = ($request->filled("designated_{$side}_image_name") && $request->input("designated_{$side}_image_name") === $file->getClientOriginalName());

            $isFirstSideImage = empty($currentMainImage)
                && !Media::where('model_type', Product::class)
                    ->where('model_id', $product->id)
                    ->where('collection', $collection)
                    ->exists();

            if ($isDesignatedMain) {
                Media::where('model_type', Product::class)
                    ->where('model_id', $product->id)
                    ->where('collection', $collection)
                    ->update(['is_primary' => false]);
            }

            Media::create([
                'model_type' => Product::class,
                'model_id' => $product->id,
                'collection' => $collection,
                'file_name' => $file->getClientOriginalName(),
                'file_id' => $upload['public_id'] ?? $upload['fileId'] ?? null,
                'url' => $url,
                'thumb_url' => $url,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'alt_text' => trim($product->name . ' ' . ucfirst($side) . ' print'),
                'is_primary' => $isDesignatedMain ?: $isFirstSideImage,
                'sort_order' => Media::where('model_type', Product::class)
                    ->where('model_id', $product->id)
                    ->where('collection', $collection)
                    ->count(),
            ]);

            if ($isDesignatedMain || empty($currentMainImage)) {
                $currentMainImage = $url;
            }

            if ($request->filled('designated_main_image_name') && $request->input('designated_main_image_name') === $file->getClientOriginalName()) {
                $product->updateQuietly(['image' => $url]);
            }
        }

        if ($currentMainImage !== $product->{$column}) {
            $product->updateQuietly([
                $column => $currentMainImage,
                'image' => $side === 'front' && empty($product->image) ? $currentMainImage : $product->image,
            ]);
            $product->refresh();
        }
    }

    private function printSideCollection(string $side): string
    {
        return $side === 'back' ? 'back_print' : 'front_print';
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
                'file_id' => $result['public_id'] ?? $result['fileId'] ?? null,
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

    // ── Set Main/Primary Image (AJAX) ──────────────────
    public function setMainImage(Request $request, Product $product)
    {
        $url = $request->input('url');
        $source = $request->input('source');
        $imageId = $request->input('image_id');

        if (!$url) {
            return response()->json(['success' => false, 'message' => 'Image URL is required.'], 422);
        }

        $target = $request->input('target', 'main');

        if ($source === 'product_image' && is_numeric($imageId)) {
            $productImage = ProductImage::where('product_id', $product->id)->find($imageId);
            if ($productImage && $productImage->color_id) {
                ProductImage::where('product_id', $product->id)
                    ->where('color_id', $productImage->color_id)
                    ->update(['is_primary' => false]);

                $productImage->update(['is_primary' => true]);

                return response()->json([
                    'success' => true,
                    'message' => 'Color image updated successfully!',
                    'image_url' => $product->image,
                    'main_url' => $product->image,
                    'color_id' => $productImage->color_id,
                ]);
            }
        }

        if ($target === 'front') {
            $product->updateQuietly(['front_image' => $url]);
            if (empty($product->image)) {
                $product->updateQuietly(['image' => $url]);
            }
            Media::where('model_type', Product::class)
                ->where('model_id', $product->id)
                ->where('collection', 'front_print')
                ->update(['is_primary' => false]);
            Media::where('model_type', Product::class)
                ->where('model_id', $product->id)
                ->where('collection', 'front_print')
                ->where('url', $url)
                ->update(['is_primary' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Front side primary image updated!',
                'target' => 'front',
                'image_url' => $url,
            ]);
        }

        if ($target === 'back') {
            $product->updateQuietly(['back_image' => $url]);
            Media::where('model_type', Product::class)
                ->where('model_id', $product->id)
                ->where('collection', 'back_print')
                ->update(['is_primary' => false]);
            Media::where('model_type', Product::class)
                ->where('model_id', $product->id)
                ->where('collection', 'back_print')
                ->where('url', $url)
                ->update(['is_primary' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Back side primary image updated!',
                'target' => 'back',
                'image_url' => $url,
            ]);
        }

        // 1. Update product table image column
        $product->updateQuietly(['image' => $url]);

        // 2. Update Media table
        Media::where('model_type', Product::class)
            ->where('model_id', $product->id)
            ->update(['is_primary' => false]);

        Media::where('model_type', Product::class)
            ->where('model_id', $product->id)
            ->where(function ($q) use ($url, $source, $imageId) {
                if ($source === 'media' && is_numeric($imageId)) {
                    $q->where('id', $imageId)->orWhere('url', $url);
                } else {
                    $q->where('url', $url);
                }
            })
            ->update(['is_primary' => true]);

        // 3. Update ProductImage table
        ProductImage::where('product_id', $product->id)
            ->update(['is_primary' => false]);

        ProductImage::where('product_id', $product->id)
            ->where(function ($q) use ($url, $source, $imageId) {
                if ($source === 'product_image' && is_numeric($imageId)) {
                    $q->where('id', $imageId)->orWhere('url', $url);
                } else {
                    $q->where('url', $url);
                }
            })
            ->update(['is_primary' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Main image updated successfully!',
            'image_url' => $url,
            'main_url' => $url,
        ]);
    }

    // ── Delete Image (AJAX) ────────────────────────────
    public function deleteImage(Request $request, Product $product, ?ProductImage $image = null)
    {
        $url = $request->input('url');
        $source = $request->input('source');
        $imageId = $request->input('image_id');

        // Support legacy route binding /products/{product}/images/{image}
        if ($image && $image->id) {
            $source = 'product_image';
            $imageId = $image->id;
            $url = $url ?: $image->url;
        }

        $wasMain = ($product->image === $url) || ($source === 'direct' && $imageId === 'main');

        // 1. Delete from Media
        if ($source === 'media' && is_numeric($imageId)) {
            $media = Media::where('model_type', Product::class)->where('model_id', $product->id)->find($imageId);
            if ($media) {
                if ($media->is_primary) $wasMain = true;
                try {
                    if ($media->file_id) {
                        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($media->file_id)) {
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($media->file_id);
                        } else {
                            $this->cloudinary->delete($media->file_id);
                        }
                    }
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning('Delete image file error: ' . $e->getMessage());
                }

                ProductImage::where('product_id', $product->id)
                    ->where(function ($q) use ($media) {
                        if ($media->file_id) $q->where('file_id', $media->file_id);
                        if ($media->url) $q->orWhere('url', $media->url);
                    })->delete();

                $media->delete();
            }
        }
        // 2. Delete from ProductImage
        elseif ($source === 'product_image' && is_numeric($imageId)) {
            $pi = ProductImage::where('product_id', $product->id)->find($imageId);
            if ($pi) {
                if ($pi->is_primary) $wasMain = true;
                try {
                    if ($pi->file_id) {
                        $this->cloudinary->delete($pi->file_id);
                    }
                } catch (\Throwable $e) {}

                Media::where('model_type', Product::class)->where('model_id', $product->id)
                    ->where(function ($q) use ($pi) {
                        if ($pi->file_id) $q->where('file_id', $pi->file_id);
                        if ($pi->url) $q->orWhere('url', $pi->url);
                    })->delete();

                $pi->delete();
            }
        }

        // Clean up direct or matching url
        if ($url) {
            if ($product->image === $url) {
                $product->updateQuietly(['image' => null]);
                $wasMain = true;
            }
            if ($product->front_image === $url) {
                $nextFront = Media::where('model_type', Product::class)
                    ->where('model_id', $product->id)
                    ->where('collection', 'front_print')
                    ->where('url', '!=', $url)
                    ->first();
                $product->updateQuietly(['front_image' => $nextFront ? $nextFront->url : null]);
            }
            if ($product->back_image === $url) {
                $nextBack = Media::where('model_type', Product::class)
                    ->where('model_id', $product->id)
                    ->where('collection', 'back_print')
                    ->where('url', '!=', $url)
                    ->first();
                $product->updateQuietly(['back_image' => $nextBack ? $nextBack->url : null]);
            }

            Media::where('model_type', Product::class)->where('model_id', $product->id)->where('url', $url)->delete();
            ProductImage::where('product_id', $product->id)->where('url', $url)->delete();
        }

        // Elect new main image if deleted image was main
        $newMainUrl = null;
        $newMainKey = null;

        if ($wasMain) {
            $nextPi = ProductImage::where('product_id', $product->id)
                ->whereNull('color_id')
                ->orderByDesc('is_primary')
                ->orderBy('sort_order')
                ->first();

            if ($nextPi) {
                $nextPi->update(['is_primary' => true]);
                $product->updateQuietly(['image' => $nextPi->url]);
                $newMainUrl = $nextPi->url;
                $newMainKey = 'product_image_' . $nextPi->id;
            } else {
                $nextMedia = Media::where('model_type', Product::class)
                    ->where('model_id', $product->id)
                    ->orderByDesc('is_primary')
                    ->orderBy('sort_order')
                    ->first();

                if ($nextMedia) {
                    $nextMedia->update(['is_primary' => true]);
                    $product->updateQuietly(['image' => $nextMedia->url]);
                    $newMainUrl = $nextMedia->url;
                    $newMainKey = 'media_' . $nextMedia->id;
                } elseif (!empty($product->front_image)) {
                    $product->updateQuietly(['image' => $product->front_image]);
                    $newMainUrl = $product->front_image;
                    $newMainKey = 'direct_front';
                } elseif (!empty($product->back_image)) {
                    $product->updateQuietly(['image' => $product->back_image]);
                    $newMainUrl = $product->back_image;
                    $newMainKey = 'direct_back';
                } else {
                    $product->updateQuietly(['image' => null]);
                }
            }
        } else {
            $newMainUrl = $product->image;
        }

        $remainingCount = ProductImage::where('product_id', $product->id)->count() +
            Media::where('model_type', Product::class)->where('model_id', $product->id)->count() +
            (!empty($product->image) ? 1 : 0);

        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully.',
            'new_main_url' => $newMainUrl,
            'new_main_key' => $newMainKey,
            'remaining_count' => $remainingCount,
        ]);
    }

    // ── SKU Generation & Validation Helpers ─────────────
    public function generateSku(Request $request)
    {
        $categoryId = $request->input('category_id');
        $productName = $request->input('name', 'Product');
        $productId = $request->input('product_id');

        $sku = $this->calculateNextSku(
            $categoryId ? (int)$categoryId : null,
            $productName,
            $productId ? (int)$productId : null
        );

        return response()->json([
            'success' => true,
            'sku' => $sku,
        ]);
    }

    public function checkSku(Request $request)
    {
        $sku = trim($request->input('sku', ''));
        $productId = $request->input('product_id');

        if ($sku === '') {
            return response()->json(['exists' => false]);
        }

        $existsInProduct = Product::where('sku', $sku)
            ->when($productId, fn($q, $id) => $q->where('id', '!=', $id))
            ->exists();

        $existsInVariant = DB::table('product_variants')
            ->where('sku', $sku)
            ->when($productId, fn($q, $id) => $q->where('product_id', '!=', $id))
            ->exists();

        return response()->json([
            'exists' => $existsInProduct || $existsInVariant,
            'sku' => $sku,
        ]);
    }

    public function calculateNextSku($categoryInput, ?string $productName, ?int $excludeProductId = null): string
    {
        $categoryName = '';
        if (is_numeric($categoryInput) && (int)$categoryInput > 0) {
            $cat = Category::find((int)$categoryInput);
            if ($cat) {
                $categoryName = $cat->name;
            }
        } elseif (is_string($categoryInput) && trim($categoryInput) !== '') {
            $categoryName = trim($categoryInput);
        }

        $cleanCat = preg_replace('/^[-\s]+/', '', $categoryName ?: '');
        $collection = $this->skuToken($cleanCat);

        $name = trim($productName ?? 'Product');
        $lowerName = strtolower($name);

        // If no category was selected or category was generic, try to extract collection from product name
        if (!$collection || in_array($collection, ['TTT', 'ALL', 'PRODUCT'])) {
            if (preg_match('/^([a-z0-9]+)\s+(oversized|tshirt|t-shirt|tee|hoodie|jacket|shirt)/i', $name, $m)) {
                $collection = $this->skuToken($m[1]);
            } else {
                $words = preg_split('/\s+/', $name);
                $firstWord = $this->skuToken($words[0] ?? '');
                if ($firstWord && !in_array($firstWord, ['MEN', 'WOMEN', 'OVERSIZED', 'TSHIRT', 'TEE'])) {
                    $collection = $firstWord;
                }
            }
        }

        if (!$collection) {
            $collection = 'TTT';
        }

        if (str_contains($lowerName, 'oversized') || str_contains($lowerName, 'tshirt') || str_contains($lowerName, 'tee')) {
            $productCode = 'OTS';
        } elseif (str_contains($lowerName, 'hoodie')) {
            $productCode = 'HD';
        } elseif (str_contains($lowerName, 'jacket')) {
            $productCode = 'JK';
        } else {
            $tokens = explode('-', $this->skuToken($name));
            $productCode = implode('-', array_filter(array_slice($tokens, 0, 2))) ?: 'PRD';
        }

        $prefix = $collection . '-' . $productCode;

        // Existing matching SKUs in products and variants
        $existingProductSkus = Product::where('sku', 'like', $prefix . '-%')
            ->when($excludeProductId, fn($q, $id) => $q->where('id', '!=', $id))
            ->pluck('sku')
            ->toArray();

        $existingVariantSkus = DB::table('product_variants')
            ->where('sku', 'like', $prefix . '-%')
            ->when($excludeProductId, fn($q, $id) => $q->where('product_id', '!=', $id))
            ->pluck('sku')
            ->toArray();

        $maxNumber = 0;
        $regex = '/^' . preg_quote($prefix, '/') . '-(\d+)/i';

        foreach (array_merge($existingProductSkus, $existingVariantSkus) as $existingSku) {
            if (preg_match($regex, $existingSku, $matches)) {
                $num = (int) $matches[1];
                if ($num > $maxNumber) {
                    $maxNumber = $num;
                }
            }
        }

        $candidateNumber = $maxNumber + 1;
        $candidateSku = sprintf('%s-%03d', $prefix, $candidateNumber);

        // Strict uniqueness check across products and product_variants
        while (
            Product::where('sku', $candidateSku)->when($excludeProductId, fn($q, $id) => $q->where('id', '!=', $id))->exists()
            || DB::table('product_variants')->where('sku', $candidateSku)->exists()
        ) {
            $candidateNumber++;
            $candidateSku = sprintf('%s-%03d', $prefix, $candidateNumber);
        }

        return $candidateSku;
    }

    private function skuToken(?string $value): string
    {
        $v = strtoupper($value ?? '');
        $v = str_replace('&', ' AND ', $v);
        $v = preg_replace('/[^A-Z0-9]+/i', '-', $v);
        $v = trim($v, '-');
        return substr($v, 0, 18);
    }

    // ── Toggle Product Status ─────────────────────────
    public function toggle(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        return response()->json(['success' => true, 'is_active' => $product->is_active]);
    }
}
