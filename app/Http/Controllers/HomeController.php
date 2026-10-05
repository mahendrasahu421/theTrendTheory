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
use App\Models\NewsletterSubscriber;
use App\Models\Blog;
use App\Mail\NewsletterWelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
            // 1. FETCH MOST PURCHASED (BEST SELLERS) - Shuffled
            // ─────────────────────────────────────────────────────────
            $mostPurchasedRaw = Product::active()
                ->with(['productImages', 'media', 'variants'])
                ->where('total_sold', '>', 0)
                ->inRandomOrder()
                ->limit(10)
                ->get();

            if ($mostPurchasedRaw->isEmpty()) {
                $mostPurchasedRaw = Product::active()
                    ->with(['productImages', 'media', 'variants'])
                    ->where('is_featured', true)
                    ->inRandomOrder()
                    ->limit(10)
                    ->get();
            }

            // ─────────────────────────────────────────────────────────
            // 2. FETCH MEN'S PRODUCTS - Shuffled
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
                ->inRandomOrder()
                ->limit(8)
                ->get();

            // ─────────────────────────────────────────────────────────
            // 3. FETCH WOMEN'S PRODUCTS - Shuffled
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
                ->inRandomOrder()
                ->limit(8)
                ->get();

            // ─────────────────────────────────────────────────────────
            // 4. FETCH NEW ARRIVALS - Shuffled
            // ─────────────────────────────────────────────────────────
            $newArrivalsRaw = Product::active()
                ->with(['productImages', 'media', 'variants'])
                ->where('is_new', true)
                ->inRandomOrder()
                ->limit(8)
                ->get();

            $fillWithLatestActive = function ($products, int $limit) {
                if ($products->count() >= $limit) {
                    return $products->shuffle()->take($limit)->values();
                }

                $existingIds = $products->pluck('id')->all();
                $fillers = Product::active()
                    ->with(['category', 'productImages', 'media', 'variants'])
                    ->when(!empty($existingIds), fn($q) => $q->whereNotIn('id', $existingIds))
                    ->inRandomOrder()
                    ->limit($limit - $products->count())
                    ->get();

                return $products->concat($fillers)->shuffle()->take($limit)->values();
            };

            $mostPurchasedRaw = $fillWithLatestActive($mostPurchasedRaw, 10);
            $newArrivalsRaw = $fillWithLatestActive($newArrivalsRaw, 8);


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
                'title' => SiteSetting::get('site_name', 'THE TREND THEORY'),
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

                'trendingStories' => (function () {
                    // Fetch latest published news added from backend
                    $newsItems = Blog::published()
                        ->where('type', 'news')
                        ->latest('published_at')
                        ->latest('created_at')
                        ->take(3)
                        ->get();

                    // If no news items found, fallback to any published blogs
                    if ($newsItems->isEmpty()) {
                        $newsItems = Blog::published()
                            ->latest('published_at')
                            ->latest('created_at')
                            ->take(3)
                            ->get();
                    }

                    if ($newsItems->isNotEmpty()) {
                        return $newsItems->map(function ($item) {
                            return [
                                'id' => $item->id,
                                'title' => $item->title,
                                'slug' => $item->slug,
                                'url' => route('news.show', $item->slug),
                                'caption' => $item->summary ?? \Illuminate\Support\Str::limit(strip_tags($item->content), 140),
                                'image_url' => $item->image_url ?? asset('images/placeholder-story.jpg'),
                                'author_name' => $item->author_name ?? 'THE TREND THEORY Editorial',
                                'category' => $item->category ?? 'Fashion Trends',
                                'read_time' => $item->read_time ?? '3 min read',
                                'formatted_date' => $item->published_at ? $item->published_at->format('F d Y') : $item->created_at->format('F d Y'),
                                'full_content' => $item->content,
                            ];
                        })->toArray();
                    }

                    // Fallback to TrendingStory model
                    return TrendingStory::active(3)->map(function ($story) {
                        return [
                            'id' => $story->id,
                            'title' => $story->title,
                            'slug' => \Illuminate\Support\Str::slug($story->title),
                            'url' => route('news.index'),
                            'caption' => $story->caption,
                            'image_url' => $story->image_url,
                            'author_name' => $story->username ?? 'The Trend Theory',
                            'category' => 'Streetwear News',
                            'read_time' => '3 min read',
                            'formatted_date' => $story->created_at ? $story->created_at->format('F d Y') : date('F d Y'),
                            'full_content' => $story->caption,
                        ];
                    })->toArray();
                })(),

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

                // ─────────────────────────────────────────────────────────
                // INSTAGRAM SHOPPABLE REELS
                // ─────────────────────────────────────────────────────────
                'instagramReels' => (function () {
                    $activeProducts = Product::active()->take(6)->get();
                    if ($activeProducts->isEmpty()) {
                        return [];
                    }

                    $reels = [
                        [
                            'id' => 1,
                            'title' => 'Monochrome Velvet Drop',
                            'video_url' => 'https://assets.mixkit.co/videos/preview/mixkit-fashion-model-posing-in-neon-light-39878-large.mp4',
                            'poster' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=600&auto=format&fit=crop&q=80',
                            'username' => '@thetrendtheory',
                            'product_ids' => [$activeProducts->first()->id ?? 1],
                        ],
                        [
                            'id' => 2,
                            'title' => 'Warehouse Styling & Heavy Drapes',
                            'video_url' => 'https://assets.mixkit.co/videos/preview/mixkit-young-man-walking-down-the-street-40915-large.mp4',
                            'poster' => 'https://images.unsplash.com/photo-1552374196-1ab2a1c593e8?w=600&auto=format&fit=crop&q=80',
                            'username' => '@kabir.vibe',
                            'product_ids' => [($activeProducts->get(1) ?? $activeProducts->first())->id],
                        ],
                        [
                            'id' => 3,
                            'title' => 'Streetwear Rotation ft. Hustle',
                            'video_url' => 'https://assets.mixkit.co/videos/preview/mixkit-girl-in-a-leather-jacket-in-the-city-at-night-41584-large.mp4',
                            'poster' => 'https://images.unsplash.com/photo-1509631179647-0177331693ae?w=600&auto=format&fit=crop&q=80',
                            'username' => '@ananya.fits',
                            'product_ids' => [
                                ($activeProducts->get(1) ?? $activeProducts->first())->id,
                                ($activeProducts->get(2) ?? $activeProducts->first())->id,
                            ],
                        ],
                        [
                            'id' => 4,
                            'title' => 'Summer Crop & Relaxed Shorts',
                            'video_url' => 'https://assets.mixkit.co/videos/preview/mixkit-young-woman-posing-for-the-camera-in-a-studio-41416-large.mp4',
                            'poster' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=600&auto=format&fit=crop&q=80',
                            'username' => '@rohan.street',
                            'product_ids' => [
                                ($activeProducts->get(0) ?? $activeProducts->first())->id,
                                ($activeProducts->get(3) ?? $activeProducts->first())->id,
                            ],
                        ],
                        [
                            'id' => 5,
                            'title' => 'Oversized Tee Rotation ft. Baggy Denim',
                            'video_url' => 'https://assets.mixkit.co/videos/preview/mixkit-stylish-man-in-sunglasses-posing-outside-41590-large.mp4',
                            'poster' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&auto=format&fit=crop&q=80',
                            'username' => '@thetrendtheory',
                            'product_ids' => [
                                ($activeProducts->get(4) ?? $activeProducts->first())->id,
                                ($activeProducts->get(1) ?? $activeProducts->first())->id,
                            ],
                        ],
                        [
                            'id' => 6,
                            'title' => 'Retro Colorblock & Jersey Drip',
                            'video_url' => 'https://assets.mixkit.co/videos/preview/mixkit-man-dancing-under-the-rain-41275-large.mp4',
                            'poster' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=600&auto=format&fit=crop&q=80',
                            'username' => '@thetrend.in',
                            'product_ids' => [
                                ($activeProducts->get(5) ?? $activeProducts->first())->id,
                                ($activeProducts->get(2) ?? $activeProducts->first())->id,
                            ],
                        ],
                    ];

                    $productMap = $activeProducts->keyBy('id');

                    return array_map(function ($item) use ($productMap, $activeProducts) {
                        $products = [];
                        foreach ($item['product_ids'] as $pid) {
                            $prod = $productMap->get($pid) ?? $activeProducts->first();
                            if ($prod) {
                                $origPrice = $prod->display_original_price ?? ($prod->price > 0 ? round($prod->price * 1.35) : 1499);
                                $hasDiscount = $origPrice > $prod->price;
                                $discountPct = $hasDiscount ? round((($origPrice - $prod->price) / $origPrice) * 100) . '% OFF' : '33% OFF';

                                // Multiple gallery images for quick-buy sheet top strip
                                $galleryImages = [];
                                if ($prod->relationLoaded('productImages') && $prod->productImages->isNotEmpty()) {
                                    $galleryImages = $prod->productImages->pluck('url')->filter()->take(4)->values()->toArray();
                                } elseif ($prod->relationLoaded('media') && $prod->media->isNotEmpty()) {
                                    $galleryImages = $prod->media->take(4)->map(fn($m) => $m->getUrl())->filter()->values()->toArray();
                                }
                                if (empty($galleryImages)) {
                                    $galleryImages = array_values(array_filter([
                                        $prod->card_image,
                                        $prod->main_image,
                                        $prod->image_url,
                                    ]));
                                }
                                if (count($galleryImages) < 2) {
                                    $galleryImages = [
                                        $prod->card_image ?? 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=500&auto=format&fit=crop&q=80',
                                        'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=500&auto=format&fit=crop&q=80',
                                        'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?w=500&auto=format&fit=crop&q=80',
                                    ];
                                }

                                // Sizes
                                $sizes = [];
                                if ($prod->relationLoaded('sizes') && $prod->sizes->isNotEmpty()) {
                                    $sizes = $prod->sizes->pluck('name')->toArray();
                                }
                                if (empty($sizes)) {
                                    $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
                                }

                                $products[] = [
                                    'id' => $prod->id,
                                    'name' => $prod->name,
                                    'slug' => $prod->slug,
                                    'url' => route('product.show', $prod->slug),
                                    'price' => '₹ ' . number_format($prod->price),
                                    'price_raw' => $prod->price,
                                    'original_price' => $origPrice ? '₹ ' . number_format($origPrice) : null,
                                    'discount_percent' => $discountPct,
                                    'image' => $prod->card_image,
                                    'gallery_images' => array_values($galleryImages),
                                    'sizes' => $sizes,
                                    'has_discount' => $hasDiscount,
                                ];
                            }
                        }
                        $item['products'] = $products;
                        $item['likes'] = 13 + (($item['id'] * 7) % 35);
                        return $item;
                    }, $reels);
                })(),
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
                'name' => 'Customer Reviews — THE TREND THEORY',
                'itemListElement' => $schemaItems,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        $seoData = [
            'meta_title' => SiteSetting::get('meta_title', 'THE TREND THEORY | Premium Fashion Store India'),
            'meta_description' => SiteSetting::get('meta_description', 'Shop latest men & women fashion.'),
            'og_image' => SiteSetting::get('og_image', asset('images/og-default.jpg')),
            'canonical' => url('/'),
            'reviewSchema' => $reviewSchema,
            'schema' => json_encode([
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => 'WebSite',
                        'name' => SiteSetting::get('site_name', 'THE TREND THEORY'),
                        'url' => url('/'),
                        'potentialAction' => [
                            '@type' => 'SearchAction',
                            'target' => url('/search') . '?q={search_term_string}',
                            'query-input' => 'required name=search_term_string',
                        ],
                    ],
                    [
                        '@type' => 'ClothingStore',
                        'name' => SiteSetting::get('site_name', 'THE TREND THEORY'),
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

    /**
     * Handle newsletter subscription request (GET or POST).
     */
    public function subscribe(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect('/#footer')->with('newsletter_info', 'Enter your email below to join THE TREND THEORY newsletter.');
        }

        $validated = $request->validate([
            'email' => ['required', 'email:filter', 'max:191'],
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email'    => 'Please enter a valid email address.',
            'email.max'      => 'Email address is too long.',
        ]);

        $email = strtolower(trim($validated['email']));

        $subscriber = NewsletterSubscriber::where('email', $email)->first();

        if ($subscriber) {
            if ($subscriber->is_active) {
                $message = 'You are already subscribed to THE TREND THEORY newsletter!';
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'status'  => 'already_subscribed',
                        'message' => $message,
                    ]);
                }
                return redirect()->back()->with('newsletter_info', $message);
            }

            // Reactivate subscriber
            $subscriber->update([
                'is_active'       => true,
                'unsubscribed_at' => null,
                'ip_address'      => $request->ip(),
                'user_agent'      => substr((string) $request->userAgent(), 0, 500),
            ]);

            // Dispatch welcome back email
            try {
                Mail::to($email)->send(new NewsletterWelcomeMail($subscriber, 'TREND10'));
            } catch (\Throwable $e) {
                Log::warning('Newsletter welcome email dispatch error: ' . $e->getMessage());
            }

            $message = 'Welcome back! Your subscription has been reactivated. Check your inbox for your 10% welcome coupon (TREND10).';
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'status'  => 'reactivated',
                    'title'   => 'Welcome Back! 🎉',
                    'message' => $message,
                    'coupon'  => 'TREND10',
                    'email'   => $email,
                ]);
            }
            return redirect()->back()
                ->with('newsletter_success', $message)
                ->with('newsletter_coupon', 'TREND10');
        }

        // Create new subscriber
        $subscriber = NewsletterSubscriber::create([
            'email'      => $email,
            'is_active'  => true,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
            'source'     => $request->input('source', 'footer'),
        ]);

        // Dispatch welcome email with coupon and details
        try {
            Mail::to($email)->send(new NewsletterWelcomeMail($subscriber, 'TREND10'));
        } catch (\Throwable $e) {
            Log::warning('Newsletter welcome email dispatch error: ' . $e->getMessage());
        }

        $message = 'Thank you for subscribing! Your exclusive 10% discount code (TREND10) and welcome perks have been dispatched to ' . $email . '.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'status'  => 'subscribed',
                'title'   => 'Welcome to the Inner Circle! 🔥',
                'message' => $message,
                'coupon'  => 'TREND10',
                'email'   => $email,
            ]);
        }

        return redirect()->back()
            ->with('newsletter_success', $message)
            ->with('newsletter_coupon', 'TREND10');
    }
}
