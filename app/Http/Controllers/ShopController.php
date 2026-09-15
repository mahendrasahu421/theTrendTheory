<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Media;
use App\Models\Size;
use App\Models\Color;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    // ── /shop — all products ───────────────────────────────
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)->with(['category', 'variants', 'productImages', 'media']);
        $this->applyFilters($query, $request);
        $products = $query->paginate(12)->withQueryString();
        
        $filterData = $this->getFilterMetadata($request);

        return $this->renderShopView($request, array_merge($filterData, [
            'products' => $products,
            'currentCategory' => null,
            'subCategories' => collect(),
            'pageHeading' => 'ALL STREETWEAR DROPS',
            'pageDescription' => 'Explore the complete luxury streetwear collection',
            'meta_title' => 'Shop Streetwear Drops — ' . SiteSetting::get('site_name', 'Vayu'),
            'meta_description' => 'Shop latest luxury oversized tees, hoodies, cargos and streetwear online.',
            'canonical' => url('/shop'),
        ]));
    }

    // ── /shop/{slug} — category click ─────────────────────
    public function category(Request $request, string $slug)
    {
        $slug = $this->normalizeCategorySlug($slug);

        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->with([
                'children' => function ($q) {
                    $q->where('is_active', true)->orderBy('sort_order');
                }
            ])
            ->firstOrFail();

        // ── CASE 1: Parent category with children
        if ($category->children->count() > 0 && !$request->filled('sub') && !$request->filled('filter')) {
            $subCategories = $category->children->map(function ($sub) {
                $sub->product_count = Product::where('is_active', true)
                    ->where('category_id', $sub->id)
                    ->count();
                return $sub;
            });

            $allCategoryIds = $category->children->pluck('id')->push($category->id);
            $featuredProducts = Product::where('is_active', true)
                ->whereIn('category_id', $allCategoryIds)
                ->orderByDesc('total_sold')
                ->limit(8)
                ->get();

            return view('froentend.shop.category-landing', [
                'category' => $category,
                'subCategories' => $subCategories,
                'featuredProducts' => $featuredProducts,
                'meta_title' => $category->seo_title,
                'meta_description' => $category->seo_description,
                'canonical' => url(($request->is('collection/*') ? '/collection/' : '/shop/') . $slug),
                'og_image' => $category->image_url ?? null,
            ]);
        }

        // ── CASE 2: Category page with full filters
        $catIds = [$category->id];
        if ($category->children->count() > 0) {
            $catIds = array_merge($catIds, $category->children->pluck('id')->toArray());
        }

        $query = Product::where('is_active', true)
            ->whereIn('category_id', $catIds)
            ->with(['category', 'variants', 'productImages', 'media']);

        $this->applyFilters($query, $request);
        $products = $query->paginate(12)->withQueryString();

        $siblings = collect();
        if ($category->parent_id) {
            $siblings = Category::where('parent_id', $category->parent_id)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        }

        $filterData = $this->getFilterMetadata($request);

        return $this->renderShopView($request, array_merge($filterData, [
            'products' => $products,
            'currentCategory' => $category,
            'parentCategory' => $category->parent_id ? Category::find($category->parent_id) : null,
            'subCategories' => $siblings,
            'pageHeading' => strtoupper($category->name),
            'pageDescription' => $category->description ?? 'Explore premium ' . $category->name . ' collection.',
            'meta_title' => $category->seo_title ?: $category->name . ' — ' . SiteSetting::get('site_name', 'Vayu'),
            'meta_description' => $category->seo_description ?: 'Shop ' . $category->name . ' online at Vayu.',
            'canonical' => url(($request->is('collection/*') ? '/collection/' : '/shop/') . $slug),
        ]));
    }

    // ── /shop/new-arrivals ─────────────────────────────────
    public function collection(Request $request, string $slug)
    {
        $slug = $this->normalizeCollectionSlug($slug);

        return match ($slug) {
            'new-arrivals' => $this->newArrivals($request),
            'sale' => $this->sale($request),
            'best-sellers' => $this->bestSellers($request),
            default => $this->customMediaCollection($request, $slug) ?? $this->categoryOrFallbackCollection($request, $slug),
        };
    }

    public function newArrivals(Request $request)
    {
        $query = Product::where('is_active', true)
            ->where('is_new', true)
            ->with(['category', 'variants', 'productImages', 'media']);

        $this->applyFilters($query, $request);
        $products = $query->paginate(12)->withQueryString();
        $filterData = $this->getFilterMetadata($request);

        return $this->renderShopView($request, array_merge($filterData, [
            'products' => $products,
            'currentCategory' => null,
            'subCategories' => collect(),
            'pageHeading' => 'NEW DROPS',
            'pageDescription' => 'Fresh streetwear drops added this week',
            'meta_title' => 'New Arrivals — ' . SiteSetting::get('site_name', 'Vayu'),
            'meta_description' => 'Shop fresh new arrivals. New styles added every week.',
            'canonical' => url('/shop/new-arrivals'),
        ]));
    }

    public function sale(Request $request)
    {
        $query = Product::where('is_active', true)
            ->where(function ($q) {
                $q->where('is_on_sale', true)
                    ->orWhereRaw('original_price > price');
            })
            ->with(['category', 'variants', 'productImages', 'media']);

        $this->applyFilters($query, $request);
        $products = $query->paginate(12)->withQueryString();
        $filterData = $this->getFilterMetadata($request);

        return $this->renderShopView($request, array_merge($filterData, [
            'products' => $products,
            'currentCategory' => null,
            'subCategories' => collect(),
            'pageHeading' => 'LIMITED TIME SALE',
            'pageDescription' => 'Exclusive discounts and markdown prices across streetwear',
            'meta_title' => 'Sale - ' . SiteSetting::get('site_name', 'Vayu'),
            'meta_description' => 'Shop discounted fashion styles and limited-time offers.',
            'canonical' => url('/collections/sale'),
        ]));
    }

    public function bestSellers(Request $request)
    {
        $query = Product::where('is_active', true)
            ->where(function ($q) {
                $q->where('total_sold', '>', 0)
                    ->orWhere('is_featured', true);
            })
            ->with(['category', 'variants', 'productImages', 'media']);

        $this->applyFilters($query, $request);
        $products = $query->paginate(12)->withQueryString();
        $filterData = $this->getFilterMetadata($request);

        return $this->renderShopView($request, array_merge($filterData, [
            'products' => $products,
            'currentCategory' => null,
            'subCategories' => collect(),
            'pageHeading' => 'BEST SELLERS',
            'pageDescription' => 'Most loved pieces trending across the street culture',
            'meta_title' => 'Best Sellers - ' . SiteSetting::get('site_name', 'Vayu'),
            'meta_description' => 'Shop customer-favourite fashion picks.',
            'canonical' => url('/collections/best-sellers'),
        ]));
    }

    private function categoryOrFallbackCollection(Request $request, string $slug)
    {
        $exists = Category::where('slug', $slug)->where('is_active', true)->exists();
        if ($exists) {
            return $this->category($request, $slug);
        }

        $heading = str($slug)->replace('-', ' ')->title()->toString();
        $searchTerm = str_replace('-', ' ', $slug);
        $query = Product::where('is_active', true)
            ->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%')
                    ->orWhere('meta_keywords', 'like', '%' . $searchTerm . '%');
            })
            ->with(['category', 'variants', 'productImages', 'media']);

        $this->applyFilters($query, $request);
        $products = $query->paginate(12)->withQueryString();
        $filterData = $this->getFilterMetadata($request);

        return $this->renderShopView($request, array_merge($filterData, [
            'products' => $products,
            'currentCategory' => null,
            'subCategories' => collect(),
            'pageHeading' => $heading,
            'pageDescription' => 'Curated collection for ' . $heading,
            'meta_title' => $heading . ' - ' . SiteSetting::get('site_name', 'Vayu'),
            'meta_description' => 'Shop ' . $heading . ' at Vayu.',
            'canonical' => url(($request->is('collection/*') ? '/collection/' : '/collections/') . $slug),
        ]), 200);
    }

    private function customMediaCollection(Request $request, string $slug)
    {
        $collectionMedia = Media::where('model_type', 'App\Models\Gallery')
            ->whereIn('collection', ['gallery', 'video_section'])
            ->with('products')
            ->get()
            ->first(fn ($media) => $this->galleryMediaSlug($media) === $slug);

        if (!$collectionMedia || $collectionMedia->products->isEmpty()) {
            return null;
        }

        $query = $collectionMedia->products()
            ->where('is_active', true)
            ->with(['category', 'variants', 'productImages', 'media']);

        $this->applyFilters($query, $request);
        $products = $query->paginate(12)->withQueryString();
        $heading = $collectionMedia->alt_text ?: str($slug)->replace('-', ' ')->title()->toString();
        $filterData = $this->getFilterMetadata($request);

        return $this->renderShopView($request, array_merge($filterData, [
            'products' => $products,
            'currentCategory' => null,
            'subCategories' => collect(),
            'pageHeading' => $heading,
            'pageDescription' => $collectionMedia->subtitle ?: 'Discover the latest styles',
            'meta_title' => $heading . ' - ' . SiteSetting::get('site_name', 'Vayu'),
            'meta_description' => $collectionMedia->subtitle ?: 'Shop ' . $heading . ' at Vayu.',
            'canonical' => url('/collection/' . $slug),
        ]), 200);
    }

    private function galleryMediaSlug(Media $media): string
    {
        $path = trim((string) parse_url($media->button_link ?? '', PHP_URL_PATH), '/');

        if ($path !== '') {
            $parts = explode('/', $path);
            return $this->normalizeCollectionSlug(end($parts));
        }

        $slug = str($media->alt_text ?? '')->slug()->toString();
        $slug = preg_replace('/-(collection|collections)$/', '', $slug);

        return $this->normalizeCollectionSlug($slug ?: '');
    }

    public function search(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $query = Product::where('is_active', true)->with(['category', 'variants', 'productImages', 'media']);

        if (strlen($q) >= 2) {
            $query->where(function ($query) use ($q) {
                $query->where('name', 'like', '%' . $q . '%')
                    ->orWhere('description', 'like', '%' . $q . '%')
                    ->orWhere('fabric', 'like', '%' . $q . '%')
                    ->orWhere('fit', 'like', '%' . $q . '%')
                    ->orWhere('sku', 'like', '%' . $q . '%');
            });
        } elseif (!$request->hasAny(['category', 'min_price', 'max_price', 'size', 'color', 'fit', 'fabric'])) {
            $query->whereRaw('1 = 0');
        }

        $this->applyFilters($query, $request);
        $products = $query->paginate(12)->withQueryString();
        $heading = $q !== '' ? 'Search: ' . $q : 'Search Results';
        $filterData = $this->getFilterMetadata($request);

        return $this->renderShopView($request, array_merge($filterData, [
            'products' => $products,
            'query' => $q,
            'currentCategory' => null,
            'subCategories' => collect(),
            'pageHeading' => $heading,
            'pageDescription' => $q !== '' ? 'Search results for "' . $q . '"' : 'Enter a search term or use filters to discover products',
            'meta_title' => 'Search: ' . $q . ' — ' . SiteSetting::get('site_name', 'Vayu'),
            'meta_description' => 'Search results for "' . $q . '"',
            'canonical' => url('/search?q=' . urlencode($q)),
        ]));
    }

    // ── Helper: apply sort/filter ──────────────────────────
    private function applyFilters($query, $request)
    {
        // 1. Search Query
        if ($request->filled('q') && !$request->routeIs('shop.search')) {
            $q = trim($request->q);
            $query->where(function ($sq) use ($q) {
                $sq->where('products.name', 'like', '%' . $q . '%')
                    ->orWhere('products.description', 'like', '%' . $q . '%')
                    ->orWhere('products.fabric', 'like', '%' . $q . '%')
                    ->orWhere('products.fit', 'like', '%' . $q . '%')
                    ->orWhere('products.sku', 'like', '%' . $q . '%');
            });
        }

        // 2. Category Filter (slugs or IDs)
        if ($request->filled('category')) {
            $catInput = $request->category;
            $catArray = is_array($catInput) ? $catInput : explode(',', $catInput);
            $query->whereHas('category', function ($cq) use ($catArray) {
                $cq->whereIn('slug', $catArray)->orWhereIn('id', $catArray);
            });
        }

        // 3. Price Range Filter
        if ($request->filled('min_price')) {
            $query->where('products.price', '>=', (float) $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('products.price', '<=', (float) $request->max_price);
        }

        // 4. Size Filter (via product variants)
        if ($request->filled('size')) {
            $sizeInput = $request->size;
            $sizeArray = is_array($sizeInput) ? $sizeInput : explode(',', $sizeInput);
            $query->whereHas('variants', function ($vq) use ($sizeArray) {
                $vq->whereIn('size', $sizeArray)->where('stock', '>', 0);
            });
        }

        // 5. Color Filter (via product variants)
        if ($request->filled('color')) {
            $colorInput = $request->color;
            $colorArray = is_array($colorInput) ? $colorInput : explode(',', $colorInput);
            $query->whereHas('variants', function ($vq) use ($colorArray) {
                $vq->whereIn('color', $colorArray);
            });
        }

        // 6. Fit Filter
        if ($request->filled('fit')) {
            $fitInput = $request->fit;
            $fitArray = is_array($fitInput) ? $fitInput : explode(',', $fitInput);
            $query->where(function ($fq) use ($fitArray) {
                foreach ($fitArray as $f) {
                    $fq->orWhere('products.fit', 'like', '%' . trim($f) . '%');
                }
            });
        }

        // 7. Fabric / Weight Filter
        if ($request->filled('fabric')) {
            $fabricInput = $request->fabric;
            $fabricArray = is_array($fabricInput) ? $fabricInput : explode(',', $fabricInput);
            $query->where(function ($fq) use ($fabricArray) {
                foreach ($fabricArray as $fab) {
                    $fq->orWhere('products.fabric', 'like', '%' . trim($fab) . '%');
                }
            });
        }

        // 8. In Stock Filter
        if ($request->boolean('in_stock')) {
            $query->where('products.stock', '>', 0);
        }

        // 9. On Sale Filter
        if ($request->boolean('on_sale')) {
            $query->where(function ($sq) {
                $sq->where('products.is_on_sale', true)
                   ->orWhereRaw('products.original_price > products.price');
            });
        }

        // 10. Sorting
        switch ($request->get('sort', 'latest')) {
            case 'price_low':
                $query->orderBy('products.price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('products.price', 'desc');
                break;
            case 'popular':
                $query->orderByDesc('products.total_sold')->orderByDesc('products.is_featured');
                break;
            case 'discount':
                $query->orderByRaw('(products.original_price - products.price) DESC');
                break;
            case 'rating':
                $query->orderByDesc('products.total_sold');
                break;
            case 'latest':
            default:
                $query->orderByDesc('products.created_at');
                break;
        }
    }

    // ── Helper: get Filter Metadata for Sidebar ────────────
    private function getFilterMetadata(Request $request): array
    {
        $categories = Category::active()
            ->whereNull('parent_id')
            ->withCount(['products' => function ($q) {
                $q->where('is_active', true);
            }])
            ->orderBy('sort_order')
            ->get();

        $allSizes = Size::where('is_active', true)->orderBy('sort_order')->get();
        if ($allSizes->isEmpty()) {
            $allSizes = collect(['S', 'M', 'L', 'XL', 'XXL'])->map(fn($s, $i) => (object)['id' => $i + 1, 'name' => $s]);
        }

        $allColors = Color::where('is_active', true)->get();
        if ($allColors->isEmpty()) {
            $allColors = collect([
                (object)['id' => 1, 'name' => 'Black', 'hex' => '#111111'],
                (object)['id' => 2, 'name' => 'Off White', 'hex' => '#F5F5F0'],
                (object)['id' => 3, 'name' => 'Charcoal', 'hex' => '#2D3748'],
                (object)['id' => 4, 'name' => 'Navy Blue', 'hex' => '#0F2747'],
                (object)['id' => 5, 'name' => 'Olive Green', 'hex' => '#4A5538'],
                (object)['id' => 6, 'name' => 'Burgundy', 'hex' => '#6B1D2F'],
                (object)['id' => 7, 'name' => 'Sage', 'hex' => '#8A9A86'],
                (object)['id' => 8, 'name' => 'Dusty Pink', 'hex' => '#D4A5A5'],
                (object)['id' => 9, 'name' => 'Cobalt', 'hex' => '#1E40AF'],
                (object)['id' => 10, 'name' => 'Beige', 'hex' => '#D2B48C'],
            ]);
        }

        $allFits = [
            'Oversized Fit' => 'Oversized',
            'Drop Shoulder' => 'Drop Shoulder',
            'Boxy Streetwear' => 'Boxy',
            'Relaxed Fit' => 'Relaxed',
            'Regular Fit' => 'Regular',
            'Cropped Fit' => 'Cropped',
            'Wide Leg Baggy' => 'Wide Leg',
        ];

        $allFabrics = [
            '240 GSM Heavy Cotton' => '240 GSM',
            '380 GSM Warm Fleece' => '380 GSM',
            'French Terry Cotton' => 'French Terry',
            'Bio-Washed Cotton' => 'Bio-Washed',
            'Cotton Ripstop Twill' => 'Twill',
            'Rigid Skate Denim' => 'Denim',
        ];

        // Active filters count calculation
        $filterKeys = ['category', 'min_price', 'max_price', 'size', 'color', 'fit', 'fabric', 'in_stock', 'on_sale'];
        $activeFiltersCount = 0;
        foreach ($filterKeys as $key) {
            if ($request->filled($key)) {
                $val = $request->$key;
                if (is_array($val)) {
                    $activeFiltersCount += count($val);
                } elseif (str_contains((string) $val, ',')) {
                    $activeFiltersCount += count(explode(',', (string) $val));
                } else {
                    $activeFiltersCount += 1;
                }
            }
        }

        return [
            'categories' => $categories,
            'allSizes' => $allSizes,
            'allColors' => $allColors,
            'allFits' => $allFits,
            'allFabrics' => $allFabrics,
            'priceMin' => 0,
            'priceMax' => 2499,
            'activeFiltersCount' => $activeFiltersCount,
        ];
    }

    private function normalizeCollectionSlug(string $slug): string
    {
        $aliases = [
            'new-in' => 'new-arrivals',
            'new-arrival' => 'new-arrivals',
            'bestsellers' => 'best-sellers',
            'best-seller' => 'best-sellers',
            'on-sale' => 'sale',
            'mens' => 'men',
            'womens' => 'women',
        ];

        return $aliases[$slug] ?? $slug;
    }

    private function normalizeCategorySlug(string $slug): string
    {
        $aliases = [
            'mens' => 'men',
            'womens' => 'women',
            'womes' => 'women',
        ];

        return $aliases[$slug] ?? $slug;
    }

    protected function renderShopView(Request $request, array $data, int $status = 200)
    {
        if ($request->ajax() || $request->wantsJson() || $request->query('ajax')) {
            $products = $data['products'] ?? null;
            $html = view('froentend.shop.partials.product-cards', ['products' => $products])->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'count' => $products ? $products->count() : 0,
                'currentPage' => $products ? $products->currentPage() : 1,
                'lastPage' => $products ? $products->lastPage() : 1,
                'hasMorePages' => $products ? $products->hasMorePages() : false,
                'nextPageUrl' => $products ? $products->nextPageUrl() : null,
                'total' => $products ? $products->total() : 0,
            ], $status);
        }

        return response()->view('froentend.shop.index', $data, $status);
    }
}


