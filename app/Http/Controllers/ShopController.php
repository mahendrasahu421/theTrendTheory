<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    // ── /shop — all products ───────────────────────────────
    public function index(Request $request)
    {

        $query = Product::where('is_active', true)->with('category');
        $this->applyFilters($query, $request);
        $products = $query->paginate(12)->withQueryString();
        $categories = Category::active()->whereNull('parent_id')->orderBy('sort_order')->get();

        return view('froentend.shop.index', [
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => null,
            'subCategories' => collect(),
            'meta_title' => 'Shop All — ' . SiteSetting::get('site_name', 'The Trend Theory'),
            'meta_description' => 'Shop latest men & women fashion online.',
            'canonical' => url('/shop'),
        ]);
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
        //    → Show subcategory cards (landing page)
        if ($category->children->count() > 0 && !$request->filled('sub')) {

            // Count products per subcategory for display
            $subCategories = $category->children->map(function ($sub) {
                $sub->product_count = Product::where('is_active', true)
                    ->where('category_id', $sub->id)
                    ->count();
                return $sub;
            });

            // Also get all products of this parent for "Shop All" section
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
                'canonical' => url('/shop/' . $slug),
                'og_image' => $category->image_url ?? null,
            ]);
        }

        // ── CASE 2: Child category (T-Shirts, Jeans etc)
        //    → Show products directly with filters
        $query = Product::where('is_active', true)
            ->where('category_id', $category->id)
            ->with('category');

        $this->applyFilters($query, $request);
        $products = $query->paginate(12)->withQueryString();

        // Siblings for filter pills
        $siblings = collect();
        if ($category->parent_id) {
            $parent = Category::find($category->parent_id);
            $siblings = Category::where('parent_id', $category->parent_id)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        }

        return view('froentend.shop.index', [
            'products' => $products,
            'categories' => Category::active()->whereNull('parent_id')->orderBy('sort_order')->get(),
            'currentCategory' => $category,
            'parentCategory' => $category->parent_id ? Category::find($category->parent_id) : null,
            'subCategories' => $siblings,
            'meta_title' => $category->seo_title,
            'meta_description' => $category->seo_description,
            'canonical' => url('/shop/' . $slug),
        ]);
    }

    // ── /shop/new-arrivals ─────────────────────────────────
    public function collection(Request $request, string $slug)
    {
        $slug = $this->normalizeCollectionSlug($slug);

        return match ($slug) {
            'new-arrivals' => $this->newArrivals($request),
            'sale' => $this->sale($request),
            'best-sellers' => $this->bestSellers($request),
            default => $this->categoryOrFallbackCollection($request, $slug),
        };
    }

    public function newArrivals(Request $request)
    {
        $products = Product::where('is_active', true)
            ->where('is_new', true)
            ->with('category')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('froentend.shop.index', [
            'products' => $products,
            'categories' => Category::active()->whereNull('parent_id')->orderBy('sort_order')->get(),
            'currentCategory' => null,
            'subCategories' => collect(),
            'pageHeading' => 'New Arrivals',
            'meta_title' => 'New Arrivals — ' . SiteSetting::get('site_name', 'The Trend Theory'),
            'meta_description' => 'Shop fresh new arrivals. New styles added every week.',
            'canonical' => url('/shop/new-arrivals'),
        ]);
    }

    // ── /search ────────────────────────────────────────────
    public function sale(Request $request)
    {
        $query = Product::where('is_active', true)
            ->where(function ($q) {
                $q->where('is_on_sale', true)
                    ->orWhereRaw('original_price > price');
            })
            ->with('category');

        $this->applyFilters($query, $request);
        $products = $query->paginate(12)->withQueryString();

        return view('froentend.shop.index', [
            'products' => $products,
            'categories' => Category::active()->whereNull('parent_id')->orderBy('sort_order')->get(),
            'currentCategory' => null,
            'subCategories' => collect(),
            'pageHeading' => 'Sale',
            'meta_title' => 'Sale - ' . SiteSetting::get('site_name', 'The Trend Theory'),
            'meta_description' => 'Shop discounted fashion styles and limited-time offers.',
            'canonical' => url('/collections/sale'),
        ]);
    }

    public function bestSellers(Request $request)
    {
        $query = Product::where('is_active', true)->with('category');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sq) use ($q) {
                $sq->where('name', 'like', '%' . $q . '%')
                    ->orWhere('description', 'like', '%' . $q . '%');
            });
        }

        switch ($request->get('sort', 'popular')) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'latest':
                $query->latest();
                break;
            default:
                $query->orderByDesc('total_sold')->orderByDesc('is_featured')->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        return view('froentend.shop.index', [
            'products' => $products,
            'categories' => Category::active()->whereNull('parent_id')->orderBy('sort_order')->get(),
            'currentCategory' => null,
            'subCategories' => collect(),
            'pageHeading' => 'Best Sellers',
            'meta_title' => 'Best Sellers - ' . SiteSetting::get('site_name', 'The Trend Theory'),
            'meta_description' => 'Shop customer-favourite fashion picks.',
            'canonical' => url('/collections/best-sellers'),
        ]);
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
            ->with('category');

        $this->applyFilters($query, $request);
        $products = $query->paginate(12)->withQueryString();

        return response()->view('froentend.shop.index', [
            'products' => $products,
            'categories' => Category::active()->whereNull('parent_id')->orderBy('sort_order')->get(),
            'currentCategory' => null,
            'subCategories' => collect(),
            'pageHeading' => $heading,
            'meta_title' => $heading . ' - ' . SiteSetting::get('site_name', 'The Trend Theory'),
            'meta_description' => 'Shop ' . $heading . ' at The Trend Theory.',
            'canonical' => url('/collections/' . $slug),
        ], 200);
    }

    public function search(Request $request)
    {
        $q = $request->get('q', '');
        $products = collect();

        if (strlen($q) >= 2) {
            $products = Product::where('is_active', true)
                ->where(function ($query) use ($q) {
                    $query->where('name', 'like', '%' . $q . '%')
                        ->orWhere('description', 'like', '%' . $q . '%')
                        ->orWhere('sku', 'like', '%' . $q . '%');
                })
                ->with('category')
                ->latest()
                ->paginate(12)
                ->withQueryString();
        }

        return view('froentend.shop.search', [
            'products' => $products,
            'query' => $q,
            'meta_title' => 'Search: ' . $q . ' — ' . SiteSetting::get('site_name', 'The Trend Theory'),
            'meta_description' => 'Search results for "' . $q . '"',
            'canonical' => url('/search?q=' . urlencode($q)),
        ]);
    }

    // ── Helper: apply sort/filter ──────────────────────────
    private function applyFilters($query, $request)
    {
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sq) use ($q) {
                $sq->where('name', 'like', '%' . $q . '%')
                    ->orWhere('description', 'like', '%' . $q . '%');
            });
        }
        switch ($request->get('sort', 'latest')) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderByDesc('total_sold');
                break;
            default:
                $query->latest();
                break;
        }
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
}
