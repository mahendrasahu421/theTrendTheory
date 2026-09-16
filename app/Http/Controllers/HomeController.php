<?php
// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\HeroSlide;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\TrendingStory;
use App\Models\SiteSetting;
use App\Models\Media;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        $data = (function () {

            // Get ALL gallery media (both images and videos) for slider
            $galleryMedia = Media::whereIn('collection', ['gallery', 'video_section'])
                ->where('model_type', 'App\Models\Gallery')
                ->orderBy('sort_order')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($media) {
                    $isVideo = str_starts_with($media->mime_type, 'video/');
                    return [
                        'id' => $media->id,
                        'title' => $media->alt_text ?? 'Untitled',
                        'subtitle' => $media->subtitle,
                        'button_link' => $media->button_link,
                        'url' => $media->url,
                        'thumb_url' => $media->thumb_url ?? ($isVideo ? $media->url . '?tr=iv-1' : $media->url),
                        'type' => $isVideo ? 'video' : 'image',
                        'mime_type' => $media->mime_type,
                        'sort_order' => $media->sort_order,
                        'is_primary' => $media->is_primary,
                    ];
                });

            // Gallery media drives the top homepage hero.
            $heroVideo = $galleryMedia->where('type', 'video')->first();

            // ─────────────────────────────────────────────────────────
            // 1. FETCH MOST PURCHASED (BEST SELLERS)
            // ─────────────────────────────────────────────────────────
            $mostPurchasedRaw = Product::active()
                ->with(['productImages', 'media', 'variants'])
                ->where('total_sold', '>', 0)
                ->orderByDesc('total_sold')
                ->limit(10)
                ->get();

            if ($mostPurchasedRaw->isEmpty()) {
                $mostPurchasedRaw = Product::active()
                    ->with(['productImages', 'media', 'variants'])
                    ->where('is_featured', true)
                    ->orderByDesc('created_at')
                    ->limit(10)
                    ->get();
            }

            // ─────────────────────────────────────────────────────────
            // 2. FETCH MEN'S PRODUCTS
            // ─────────────────────────────────────────────────────────
            $menCategory = Category::active()
                ->where(function ($q) {
                    $q->whereIn('slug', ['men', 'mens'])
                        ->orWhereIn(\DB::raw('LOWER(name)'), ['men', "men's", 'mens', "men collection", "men's collection"]);
                })
                ->orderByRaw("CASE WHEN slug IN ('men', 'mens') THEN 0 ELSE 1 END")
                ->orderBy('sort_order')
                ->first();
            $menCategoryIds = [];
            if ($menCategory) {
                $menCategoryIds = Category::where('id', $menCategory->id)
                    ->orWhere('parent_id', $menCategory->id)
                    ->where('is_active', true)
                    ->pluck('id')
                    ->toArray();
            }

            $mensProductsRaw = Product::whereIn('category_id', $menCategoryIds)
                ->active()
                ->with(['category', 'productImages', 'media', 'variants'])
                ->orderByDesc('created_at')
                ->limit(8)
                ->get();

            // ─────────────────────────────────────────────────────────
            // 3. FETCH WOMEN'S PRODUCTS
            // ─────────────────────────────────────────────────────────
            $womenCategory = Category::active()
                ->where(function ($q) {
                    $q->whereIn('slug', ['women', 'womens', 'womes'])
                        ->orWhereIn(\DB::raw('LOWER(name)'), ['women', "women's", 'womens', "women collection", "women's collection"]);
                })
                ->orderByRaw("CASE WHEN slug IN ('women', 'womens', 'womes') THEN 0 ELSE 1 END")
                ->orderBy('sort_order')
                ->first();
            $womenCategoryIds = [];
            if ($womenCategory) {
                $womenCategoryIds = Category::where('id', $womenCategory->id)
                    ->orWhere('parent_id', $womenCategory->id)
                    ->where('is_active', true)
                    ->pluck('id')
                    ->toArray();
            }

            $womensProductsRaw = Product::whereIn('category_id', $womenCategoryIds)
                ->active()
                ->with(['category', 'productImages', 'media', 'variants'])
                ->orderByDesc('created_at')
                ->limit(8)
                ->get();

            // ─────────────────────────────────────────────────────────
            // 4. FETCH NEW ARRIVALS
            // ─────────────────────────────────────────────────────────
            $newArrivalsRaw = Product::active()
                ->with(['productImages', 'media', 'variants'])
                ->where('is_new', true)
                ->orderByDesc('created_at')
                ->limit(8)
                ->get();

            $fillWithLatestActive = function ($products, int $limit) {
                if ($products->count() >= $limit) {
                    return $products->take($limit)->values();
                }

                $existingIds = $products->pluck('id')->all();
                $fillers = Product::active()
                    ->with(['category', 'productImages', 'media', 'variants'])
                    ->when(!empty($existingIds), fn($q) => $q->whereNotIn('id', $existingIds))
                    ->orderByDesc('created_at')
                    ->limit($limit - $products->count())
                    ->get();

                return $products->concat($fillers)->take($limit)->values();
            };

            $mostPurchasedRaw = $fillWithLatestActive($mostPurchasedRaw, 10);
            $newArrivalsRaw = $fillWithLatestActive($newArrivalsRaw, 8);

            // ─────────────────────────────────────────────────────────
            // 5. LOG FOR DEBUG
            // ─────────────────────────────────────────────────────────
            Log::info('Homepage Products Summary', [
                'most_purchased_count' => $mostPurchasedRaw->count(),
                'men_products_count' => $mensProductsRaw->count(),
                'women_products_count' => $womensProductsRaw->count(),
                'new_arrivals_count' => $newArrivalsRaw->count(),
                'gallery_media_count' => $galleryMedia->count()
            ]);

            $heroSlides = HeroSlide::active()->ordered()->get()->map(function ($slide) {
                return [
                    'id' => $slide->id,
                    'title' => $slide->title,
                    'subtitle' => $slide->subtitle,
                    'media_type' => $slide->media_type,
                    'image' => $slide->image_url,
                    'mobile_image' => $slide->mobile_image_url,
                    'alt_text' => $slide->alt_text,
                    'button_text' => $slide->button_text,
                    'button_link' => $slide->button_link,
                    'sort_order' => $slide->sort_order,
                ];
            });

            $heroMediaSlides = $galleryMedia
                ->sortByDesc('is_primary')
                ->values()
                ->map(function ($media) {
                    return [
                        'id'         => $media['id'] ?? null,
                        'title'      => $media['title'] ?? 'NEW FASHION COLLECTION',
                        'subtitle'   => $media['subtitle'] ?: SiteSetting::get('site_tagline', 'Unleash Your Inner Style'),
                        'media_type' => $media['type'] ?? 'image',
                        'image'      => $media['url'],
                        'mobile_image' => $media['url'],
                        'alt_text'   => $media['title'] ?? 'Hero media',
                        'button_text'=> 'SHOP NOW',
                        'button_link'=> $media['button_link'] ?: route('shop.index'),
                        'sort_order' => $media['sort_order'] ?? 0,
                    ];
                });

            // NOTE: Section 1 (full-screen gallery hero) = Gallery Media only.
            //       Section 2 (card slider)              = Hero Slides only.
            // These two are intentionally kept separate — no fallback between them.

            $heroPrimary = $heroMediaSlides->first();

            // ─────────────────────────────────────────────────────────
            // 6. RETURN DATA
            // ─────────────────────────────────────────────────────────
            return [
                'settings' => SiteSetting::getAll(),
                'galleryMedia' => $galleryMedia->toArray(),
                'heroVideo' => $heroVideo,
                'heroPrimary' => $heroPrimary,
                'heroMediaSlides' => $heroMediaSlides->toArray(),
                'heroSlides' => $heroSlides->toArray(),
                'title' => SiteSetting::get('site_name', 'Vayu'),
                'titleContent' => SiteSetting::get('site_tagline', 'Fashion That Speaks Without Saying a Word'),
                'categories' => Category::homeCategories()->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'description' => $category->description,
                        'image' => $category->image,
                        'image_url' => $category->image_url,
                        'banner_image_url' => $category->banner_image_url,
                        'sort_order' => $category->sort_order,
                    ];
                })->toArray(),
                'menCategory' => $menCategory ? $menCategory->toArrayForCache() : null,
                'womenCategory' => $womenCategory ? $womenCategory->toArrayForCache() : null,

                'mostPurchased' => $mostPurchasedRaw->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'price' => $product->price,
                        'original_price' => $product->display_original_price,
                        'has_discount' => $product->has_discount,
                        'discount_percentage' => $product->discount_percent,
                        'stock' => $product->stock,
                        'stock_status' => $product->stock_status,
                        'sold_count' => $product->total_sold,
                        'formatted_sold' => $product->total_sold > 0 ? number_format($product->total_sold) . ' sold' : 'New',
                        'avg_rating' => $product->avg_rating,
                        'image' => $product->image,
                        'image_url' => $product->card_image,
                        'card_image_url' => $product->card_image,
                        'all_images_list' => $product->all_images_list,
                        'is_new' => $product->is_new,
                        'is_featured' => $product->is_featured,
                        'is_trending' => $product->is_trending,
                        'is_on_sale' => $product->is_on_sale,
                    ];
                })->toArray(),

                'mensProducts' => $mensProductsRaw->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'price' => $product->price,
                        'original_price' => $product->display_original_price,
                        'has_discount' => $product->has_discount,
                        'discount_percentage' => $product->discount_percent,
                        'stock' => $product->stock,
                        'stock_status' => $product->stock_status,
                        'image' => $product->image,
                        'image_url' => $product->card_image,
                        'card_image_url' => $product->card_image,
                        'all_images_list' => $product->all_images_list,
                        'is_new' => $product->is_new,
                        'is_featured' => $product->is_featured,
                        'is_trending' => $product->is_trending,
                        'is_on_sale' => $product->is_on_sale,
                        'sizes' => $product->sizes,
                        'colors' => $product->colors,
                        'category_name' => $product->category ? $product->category->name : null,
                        'category_slug' => $product->category ? $product->category->slug : null,
                    ];
                })->toArray(),

                'womensProducts' => $womensProductsRaw->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'price' => $product->price,
                        'original_price' => $product->display_original_price,
                        'has_discount' => $product->has_discount,
                        'discount_percentage' => $product->discount_percent,
                        'stock' => $product->stock,
                        'stock_status' => $product->stock_status,
                        'image' => $product->image,
                        'image_url' => $product->card_image,
                        'card_image_url' => $product->card_image,
                        'all_images_list' => $product->all_images_list,
                        'is_new' => $product->is_new,
                        'is_featured' => $product->is_featured,
                        'is_trending' => $product->is_trending,
                        'is_on_sale' => $product->is_on_sale,
                        'sizes' => $product->sizes,
                        'colors' => $product->colors,
                        'category_name' => $product->category ? $product->category->name : null,
                        'category_slug' => $product->category ? $product->category->slug : null,
                    ];
                })->toArray(),

                'newArrivals' => $newArrivalsRaw->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'price' => $product->price,
                        'original_price' => $product->display_original_price,
                        'has_discount' => $product->has_discount,
                        'discount_percentage' => $product->discount_percent,
                        'stock' => $product->stock,
                        'stock_status' => $product->stock_status,
                        'image' => $product->image,
                        'image_url' => $product->card_image,
                        'card_image_url' => $product->card_image,
                        'all_images_list' => $product->all_images_list,
                        'is_new' => $product->is_new,
                        'is_featured' => $product->is_featured,
                        'is_trending' => $product->is_trending,
                        'is_on_sale' => $product->is_on_sale,
                        'sizes' => $product->sizes,
                        'colors' => $product->colors,
                    ];
                })->toArray(),

                'trendingStories' => TrendingStory::active(3)->map(function ($story) {
                    return [
                        'id' => $story->id,
                        'caption' => $story->caption,
                        'caption_highlight' => $story->caption_highlight,
                        'image_url' => $story->image_url,
                        'username' => $story->username,
                        'user_avatar_url' => $story->user_avatar_url,
                        'badge_text' => $story->badge_text,
                        'badge_type' => $story->badge_type,
                        'duration' => $story->duration,
                        'likes' => $story->likes,
                        'formatted_likes' => $story->formatted_likes,
                        'views' => $story->views,
                    ];
                })->toArray(),

                'reviews' => Review::featured(3)->map(function ($review) {
                    return [
                        'id' => $review->id,
                        'reviewer_name' => $review->reviewer_name,
                        'reviewer_image_url' => $review->reviewer_image_url,
                        'rating' => $review->rating,
                        'title' => $review->title,
                        'comment' => $review->comment,
                        'product_tag' => $review->product_tag,
                        'likes' => $review->likes,
                        'is_verified' => $review->is_verified,
                        'is_approved' => $review->is_approved,
                        'is_featured' => $review->is_featured,
                        'review_media' => $review->review_media,
                        'created_at' => $review->created_at,
                    ];
                })->toArray(),
            ];
        })();

        // ─────────────────────────────────────────────────────────
        // SCHEMA AND SEO DATA
        // ─────────────────────────────────────────────────────────
        $reviews = $data['reviews'];
        $reviewSchema = null;

        if (count($reviews) > 0) {
            $schemaItems = [];
            foreach ($reviews as $i => $review) {
                $schemaItems[] = [
                    '@type' => 'Review',
                    'position' => $i + 1,
                    'reviewRating' => [
                        '@type' => 'Rating',
                        'ratingValue' => (string) ($review['rating'] ?? 5),
                        'bestRating' => '5',
                    ],
                    'name' => $review['title'] ?? 'Customer Review',
                    'author' => [
                        '@type' => 'Person',
                        'name' => $review['reviewer_name'] ?? 'Verified Customer',
                    ],
                    'reviewBody' => $review['comment'] ?? '',
                    'datePublished' => isset($review['created_at']) ? date('Y-m-d', strtotime($review['created_at'])) : date('Y-m-d'),
                    'itemReviewed' => [
                        '@type' => 'Product',
                        'name' => $review['product_tag'] ?? 'Fashion Product',
                    ],
                ];
            }

            $reviewSchema = json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'ItemList',
                'name' => 'Customer Reviews — Vayu',
                'itemListElement' => $schemaItems,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        $seoData = [
            'meta_title' => SiteSetting::get('meta_title', 'Vayu | Premium Fashion Store India'),
            'meta_description' => SiteSetting::get('meta_description', 'Shop latest men & women fashion.'),
            'og_image' => SiteSetting::get('og_image', asset('images/og-default.jpg')),
            'canonical' => url('/'),
            'reviewSchema' => $reviewSchema,
            'schema' => json_encode([
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => 'WebSite',
                        'name' => SiteSetting::get('site_name', 'Vayu'),
                        'url' => url('/'),
                        'potentialAction' => [
                            '@type' => 'SearchAction',
                            'target' => url('/search') . '?q={search_term_string}',
                            'query-input' => 'required name=search_term_string',
                        ],
                    ],
                    [
                        '@type' => 'ClothingStore',
                        'name' => SiteSetting::get('site_name', 'Vayu'),
                        'url' => url('/'),
                        'logo' => asset('images/logo.png'),
                        'sameAs' => [
                            'https://www.instagram.com/thetrendtheory',
                            'https://www.facebook.com/thetrendtheory',
                        ],
                        'address' => [
                            '@type' => 'PostalAddress',
                            'addressLocality' => SiteSetting::get('address', 'India'),
                            'addressCountry' => 'IN',
                        ],
                    ],
                ],
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        ];

        return view('froentend.home', array_merge($data, $seoData));
    }
}
