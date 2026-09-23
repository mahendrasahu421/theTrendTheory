@extends('froentend.layouts.app')

{{-- ══════════════════════════════════════════════════
     ✅ FIX: @push('seo') — title, description, canonical only
════════════════════════════════════════════════════ --}}
@push('seo')
    <title>{{ $meta_title ?? 'THE TREND THEORY — Premium Streetwear & Fashion Store India' }}</title>
    <meta name="description" content="{{ $meta_description ?? 'Shop latest men & women fashion.' }}">
    <link rel="canonical" href="{{ $canonical ?? url('/') }}">
    {{-- Schema JSON-LD --}}
    @isset($schema)
        <script type="application/ld+json">{!! $schema !!}</script>
    @endisset
@endpush

@section('main')

    {{-- SECTION 1: LUXURY CINEMATIC HERO MEDIA SLIDER — fed by Admin › Media Gallery --}}
    @php
        $topHeroSlides = !empty($heroMediaSlides) ? $heroMediaSlides : [];
    @endphp
    @if (count($topHeroSlides))
        <style>
            .video-hero, .hero-media-slider, .hero-media-wrapper {
                background: transparent !important;
            }
            .video-overlay {
                display: none !important;
                background: transparent !important;
            }
            .video-content-container {
                position: absolute !important;
                inset: 0 !important;
                z-index: 5 !important;
                width: 100% !important;
                height: 100% !important;
                display: flex !important;
                align-items: flex-end !important;
                justify-content: center !important;
                padding: 40px 24px 110px !important;
                pointer-events: none !important;
            }
            .video-content {
                max-width: 860px !important;
                width: 100% !important;
                margin: 0 auto !important;
                text-align: center !important;
                color: #ffffff !important;
                pointer-events: auto !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                justify-content: center !important;
            }
            .hero-badge-wrap {
                margin-bottom: 10px !important;
                opacity: 1 !important;
                visibility: visible !important;
                transform: none !important;
            }
            .hero-eyebrow {
                display: inline-flex !important;
                align-items: center !important;
                gap: 8px !important;
                background: rgba(0, 0, 0, 0.4) !important;
                backdrop-filter: blur(14px) !important;
                -webkit-backdrop-filter: blur(14px) !important;
                border: 1px solid rgba(255, 255, 255, 0.35) !important;
                padding: 6px 18px !important;
                border-radius: 999px !important;
                font-size: 11px !important;
                font-weight: 800 !important;
                letter-spacing: 2px !important;
                text-transform: uppercase !important;
                color: #ffffff !important;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25) !important;
            }
            .hero-brand-title {
                font-family: 'Cinzel', -apple-system, BlinkMacSystemFont, serif !important;
                font-size: clamp(34px, 5.5vw, 64px) !important;
                font-weight: 900 !important;
                letter-spacing: 4px !important;
                line-height: 1.1 !important;
                text-transform: uppercase !important;
                color: #ffffff !important;
                opacity: 1 !important;
                visibility: visible !important;
                transform: none !important;
                text-shadow: 0 4px 24px rgba(0, 0, 0, 0.9), 0 2px 8px rgba(0, 0, 0, 0.8) !important;
                margin: 0 0 10px 0 !important;
            }
            .hero-subline {
                font-size: clamp(14px, 1.6vw, 18px) !important;
                font-weight: 600 !important;
                letter-spacing: 2px !important;
                line-height: 1.5 !important;
                text-transform: uppercase !important;
                color: #ffffff !important;
                opacity: 1 !important;
                visibility: visible !important;
                transform: none !important;
                text-shadow: 0 2px 14px rgba(0, 0, 0, 0.9) !important;
                margin: 0 auto 22px !important;
                max-width: 620px !important;
            }
            .hero-buttons {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                gap: 14px !important;
                flex-wrap: wrap !important;
                opacity: 1 !important;
                visibility: visible !important;
                transform: none !important;
            }
            .hero-btn-primary {
                background: #00285a !important;
                color: #ffffff !important;
                padding: 13px 30px !important;
                border-radius: 999px !important;
                font-size: 12px !important;
                font-weight: 800 !important;
                letter-spacing: 1.5px !important;
                text-transform: uppercase !important;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35) !important;
                border: 1.5px solid #00285a !important;
                text-decoration: none !important;
                transition: all 0.25s ease !important;
            }
            .hero-btn-glass {
                background: rgba(0, 0, 0, 0.35) !important;
                color: #ffffff !important;
                padding: 13px 28px !important;
                border-radius: 999px !important;
                font-size: 12px !important;
                font-weight: 800 !important;
                letter-spacing: 1.5px !important;
                text-transform: uppercase !important;
                border: 1.5px solid rgba(255, 255, 255, 0.6) !important;
                backdrop-filter: blur(12px) !important;
                -webkit-backdrop-filter: blur(12px) !important;
                text-decoration: none !important;
                transition: all 0.25s ease !important;
            }
            @media (max-width: 768px) {
                .video-content-container {
                    padding: 30px 18px 85px !important;
                }
                .hero-brand-title {
                    font-size: clamp(26px, 7vw, 42px) !important;
                    letter-spacing: 2px !important;
                }
                .hero-subline {
                    font-size: 12.5px !important;
                    margin-bottom: 18px !important;
                }
                .hero-buttons {
                    width: 100% !important;
                    flex-direction: column !important;
                    gap: 10px !important;
                }
                .hero-btn-primary, .hero-btn-glass {
                    width: 100% !important;
                    padding: 12px 20px !important;
                }
            }
        </style>
        <section class="video-hero hero-media-slider" data-hero-media-slider id="mainHeroSection" aria-label="Hero Spotlight">
            <div class="hero-media-track">
                @foreach ($topHeroSlides as $slide)
                    <article class="hero-media-slide {{ $loop->first ? 'active' : '' }}"
                        aria-hidden="{{ $loop->first ? 'false' : 'true' }}"
                        data-slide-index="{{ $loop->index }}"
                        data-media-type="{{ $slide['media_type'] ?? 'image' }}">
                        
                        <div class="hero-media-wrapper">
                            @if (($slide['media_type'] ?? 'image') === 'video')
                                <video class="hero-video" src="{{ $slide['image'] }}" muted loop playsinline preload="metadata"></video>
                            @else
                                <picture>
                                    <source media="(max-width: 768px)" srcset="{{ $slide['mobile_image'] ?? $slide['image'] }}">
                                    <img class="hero-video hero-image-zoom" src="{{ $slide['image'] }}"
                                        alt="{{ $slide['alt_text'] ?? ($slide['title'] ?? 'Hero media') }}"
                                        loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                        onerror="this.src='{{ asset('images/placeholder-slide.jpg') }}'">
                                </picture>
                            @endif
                            <div class="video-overlay" style="display:none;background:transparent;"></div>
                        </div>

                        <div class="video-content-container">
                            <div class="video-content">
                                

                                {{-- <h1 class="hero-headline hero-brand-title">{{ strtoupper($slide['title'] ?? 'MIDNIGHT REBELS') }}</h1> --}}

                                {{-- @if (!empty($slide['subtitle']))
                                    <p class="hero-subline">{{ $slide['subtitle'] }}</p>
                                @else
                                    <p class="hero-subline">OWN THE NIGHT</p>
                                @endif --}}

                                <div class="hero-buttons">
                                    <a href="{{ $slide['button_link'] ?? route('shop.index') }}" class="hero-btn hero-btn-primary">
                                        <span>{{ $slide['button_text'] ?? 'SHOP NOW' }}</span>
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                    {{-- <a href="{{ route('shop.index') }}" class="hero-btn hero-btn-glass">
                                        <span>SHOP ALL</span>
                                    </a> --}}
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Floating Audio Toggle Button (For video slides) --}}
            <button class="hero-audio-btn" type="button" id="heroAudioToggle" aria-label="Toggle Sound" style="display:none;">
                <i class="bi bi-volume-mute-fill" id="heroAudioIcon"></i>
            </button>

            @if (count($topHeroSlides) > 1)
                {{-- Floating Glass Arrows --}}
                <button class="top-hero-arrow top-hero-arrow-left" type="button" data-hero-prev aria-label="Previous hero slide">
                    <i class="bi bi-arrow-left"></i>
                </button>
                <button class="top-hero-arrow top-hero-arrow-right" type="button" data-hero-next aria-label="Next hero slide">
                    <i class="bi bi-arrow-right"></i>
                </button>

                {{-- Bottom Luxury Nav Bar with Segmented Progress & Counter --}}
                <div class="hero-bottom-bar">
                    <div class="hero-counter">
                        <span class="hero-counter-current" id="heroCounterCurrent">01</span>
                        <span class="hero-counter-sep">/</span>
                        <span class="hero-counter-total">{{ str_pad(count($topHeroSlides), 2, '0', STR_PAD_LEFT) }}</span>
                    </div>

                    <div class="hero-progress-segments" aria-label="Hero slide navigation">
                        @foreach ($topHeroSlides as $slide)
                            <button class="hero-progress-seg {{ $loop->first ? 'active' : '' }}" type="button"
                                data-hero-dot="{{ $loop->index }}" aria-label="Go to slide {{ $loop->iteration }}">
                                <span class="hero-progress-fill"></span>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Scroll Indicator --}}
            <div class="hero-scroll-hint" onclick="document.querySelector('.hero-slider-section')?.scrollIntoView({behavior:'smooth'})" role="button" tabindex="0">
                <span>DISCOVER MORE</span>
                <i class="bi bi-chevron-down"></i>
            </div>
        </section>
    @endif

    {{-- ══ SECTION 2: HERO IMAGE SLIDER ════════════════ --}}
    <section class="hero-slider-section" aria-label="Featured Collections">
        <div class="hero-slider-container">
            <div class="hero-header">
                <h2>{{ $title }}</h2>
                <p>{{ $titleContent }}</p>
            </div>
            <button class="hero-arrow hero-arrow-left" id="heroArrowLeft" aria-label="Previous slide">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="hero-arrow hero-arrow-right" id="heroArrowRight" aria-label="Next slide">
                <i class="bi bi-chevron-right"></i>
            </button>
            <div class="hero-slider-wrapper" id="heroSliderWrapper">
                <div class="hero-slider-track" id="heroSliderTrack">
                    @forelse($heroSlides as $slide)
                        <div class="hero-slide">
                            @if (($slide['media_type'] ?? 'image') === 'video')
                                <video src="{{ $slide['image'] }}" muted loop playsinline preload="metadata"></video>
                            @else
                                <picture>
                                    {{-- Mobile image (max-width 768px) --}}
                                    <source media="(max-width: 768px)" srcset="{{ $slide['mobile_image'] ?? $slide['image'] }}">
                                    {{-- Desktop image --}}
                                    <img src="{{ $slide['image'] }}" alt="{{ $slide['alt_text'] ?? $slide['title'] }}"
                                        loading="{{ $loop->first ? 'eager' : 'lazy' }}" width="1200" height="600"
                                        onerror="this.src='{{ asset('images/placeholder-slide.jpg') }}'">
                                </picture>
                            @endif
                            <div class="slide-overlay">
                                <h3>{{ $slide['title'] }}</h3>
                                @if (!empty($slide['subtitle']))
                                    {{-- <p class="slide-subtitle">{{ $slide['subtitle'] }}</p> --}}
                                @endif
                                <a href="{{ $slide['button_link'] }}" class="btn-shop" target="_blank">
                                    {{ $slide['button_text'] ?? 'SHOP NOW' }}
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="hero-slide">
                            <img src="{{ asset('images/placeholder-slide.jpg') }}" alt="New Collection" loading="eager"
                                width="1200" height="600">
                            <div class="slide-overlay">
                                <h3>NEW COLLECTION</h3>
                                <a href="{{ route('shop.index') }}" class="btn-shop" target="_blank">SHOP NOW</a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    {{-- ══ SECTION 3: CATEGORY CARDS ═══════════════════ --}}
    <section class="gender-section" aria-label="Shop by Category">
        <div class="container">
            <div class="row gender-row g-4 align-items-stretch">
                @forelse($categories as $category)
                    <div class="col-md-6">
                        <div class="gender-card">
                            <img src="{{ $category['image_url'] ?? ($category['image'] ?? asset('images/placeholder-category.jpg')) }}"
                                alt="{{ $category['name'] }} Collection — THE TREND THEORY" loading="lazy" width="600"
                                height="700" onerror="this.src='{{ asset('images/placeholder-category.jpg') }}'">
                            <div class="gender-overlay">
                                <h2>{{ $category['name'] }}</h2>
                                @if (!empty($category['description']))
                                    {{-- <p class="category-tagline">{{ $category['description'] }}</p> --}}
                                @endif
                                <a href="{{ route('shop.category', $category['slug']) }}" class="btn-gender"
                                    target="_blank" aria-label="Explore {{ $category['name'] }} Collection">
                                    EXPLORE
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Categories coming soon!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ══ SECTION 4: MOST PURCHASED ═══════════════════ --}}
    <section class="most-slider-section home-product-carousel" aria-label="Most Purchased Products">
        <div class="container-fluid position-relative px-0">
            <div class="most-slider-header text-center">
                <i class="bi bi-crown-fill" aria-hidden="true"></i>
                <h2>MOST PURCHASED</h2>
                <p>customer favorites · highest units sold</p>
            </div>
            <div class="slider-container-most">
                <button class="most-arrow most-arrow-left" id="mostArrowLeft" aria-label="Previous"><i
                        class="bi bi-chevron-left"></i></button>
                <button class="most-arrow most-arrow-right" id="mostArrowRight" aria-label="Next"><i
                        class="bi bi-chevron-right"></i></button>
                <div class="most-slider-wrapper" id="mostSliderWrapper">
                    <div class="most-slider-track" id="mostSliderTrack">
                        @forelse($mostPurchased as $product)
                            <div class="most-slide" itemscope itemtype="https://schema.org/Product">
                                <meta itemprop="name" content="{{ $product['name'] }}">
                                <meta itemprop="image" content="{{ $product['image_url'] ?? '' }}">
                                <meta itemprop="url" content="{{ route('product.show', $product['slug']) }}">
                                <div class="most-card">
                                    @php
                                        $mostOriginalPrice = $product['original_price'] ?? null;
                                        $mostCurrentPrice = $product['price'] ?? 0;
                                        $mostDiscountPct = $mostOriginalPrice && $mostOriginalPrice > $mostCurrentPrice
                                            ? (int) round((($mostOriginalPrice - $mostCurrentPrice) / $mostOriginalPrice) * 100)
                                            : (int) ($product['discount_percentage'] ?? 0);
                                        $mostImages = $product['all_images_list'] ?? [];
                                        $mostImages = is_array($mostImages) ? array_values(array_unique(array_filter($mostImages))) : [];
                                        $mostFallbackImage = $product['card_image_url'] ?? ($product['image_url'] ?? asset('images/placeholder-product.jpg'));
                                        if (empty($mostImages)) {
                                            $mostImages[] = $mostFallbackImage;
                                        }
                                        $mostPrimaryImage = $mostImages[0] ?? $mostFallbackImage;
                                    @endphp
                                    @if($mostDiscountPct > 0)
                                        <div class="most-badge most-save-badge">SAVE {{ $mostDiscountPct }}%</div>
                                    @endif
                                    <div class="most-badge">🔥
                                        {{ $product['formatted_sold'] ?? ($product['sold_count'] ?? 0) }} sold</div>
                                    <div class="most-img home-card-auto-gallery {{ count($mostImages) > 1 ? 'has-home-gallery' : '' }}" data-home-card-gallery>
                                        <a href="{{ route('product.show', $product['slug']) }}" class="home-card-gallery-link">
                                            @foreach ($mostImages as $idx => $imageUrl)
                                                <img src="{{ $imageUrl }}"
                                                    class="home-card-gallery-img {{ $loop->first ? 'active' : '' }}"
                                                    alt="{{ $product['name'] }} view {{ $idx + 1 }}"
                                                    loading="{{ $loop->first ? 'eager' : 'lazy' }}" width="300" height="380"
                                                    onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                                            @endforeach
                                        </a>

                                        <button
                                            class="wish-btn {{ in_array($product['id'], session('wishlist', [])) ? 'wished' : '' }}"
                                            onclick="toggleWishlist({{ $product['id'] }}, this)" aria-label="Wishlist">
                                            <i
                                                class="bi {{ in_array($product['id'], session('wishlist', [])) ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                        </button>

                                        <div class="img-hover-btn">
                                            <button class="btn-hover-add open-product-slider add-to-cart-btn"
                                                data-product-id="{{ $product['id'] }}"
                                                data-product-name="{{ $product['name'] }}"
                                                data-product-image="{{ $mostPrimaryImage }}"
                                                {{ ($product['stock_status'] ?? 'in_stock') === 'out_of_stock' ? 'disabled' : '' }}>
                                                {{ ($product['stock_status'] ?? 'in_stock') === 'out_of_stock' ? 'OUT OF STOCK' : 'ADD TO CART' }}
                                            </button>
                                        </div>
                                        @if (!empty($product['avg_rating']) && $product['avg_rating'] > 0)
                                            <div class="most-overlay">
                                                <span class="most-rating" itemprop="aggregateRating" itemscope
                                                    itemtype="https://schema.org/AggregateRating">
                                                    <i class="bi bi-star-fill" aria-hidden="true"></i>
                                                    <meta itemprop="ratingValue" content="{{ $product['avg_rating'] }}">
                                                    {{ number_format($product['avg_rating'], 1) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="most-info">
                                        <a href="{{ route('product.show', $product['slug']) }}" target="_blank"
                                            target="_blank" class="product-title-link">
                                            <h3>{{ $product['name'] }}</h3>
                                        </a>
                                        <div class="most-price" itemprop="offers" itemscope
                                            itemtype="https://schema.org/Offer">
                                            <span itemprop="price"
                                                content="{{ $product['price'] }}">₹{{ number_format($product['price']) }}</span>
                                            <meta itemprop="priceCurrency" content="INR">
                                            @if (!empty($product['has_discount']) && $product['has_discount'])
                                                <del
                                                    class="original-price">₹{{ number_format($product['original_price']) }}</del>
                                                <span class="discount-badge">{{ $product['discount_percentage'] ?? 0 }}%
                                                    OFF</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted p-4">Products coming soon!</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══ SECTION 5: MEN'S COLLECTION ═════════════════ --}}
    <section class="collection-slider-section home-product-carousel" aria-label="Men's Collection">
        <div class="container-fluid position-relative px-0">
            <div class="collection-header text-center">
                <h2>MEN'S COLLECTION</h2>
                <p>stylish · comfortable · modern</p>
            </div>
            <div class="slider-container-collection">
                <button class="collection-arrow collection-arrow-left" id="menArrowLeft" aria-label="Previous"><i
                        class="bi bi-chevron-left"></i></button>
                <button class="collection-arrow collection-arrow-right" id="menArrowRight" aria-label="Next"><i
                        class="bi bi-chevron-right"></i></button>
                <div class="collection-slider-wrapper" id="menSliderWrapper">
                    <div class="collection-track" id="menSliderTrack">
                        @forelse($mensProducts as $product)
                            @include('froentend.partials.product-card', [
                                'product' => $product,
                                'type' => 'collection',
                            ])
                        @empty
                            <div class="text-muted p-4 text-center">No men's products yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="collection-footer">
                <a href="{{ route('shop.category', 'men') }}" class="section-btn" target="_blank">VIEW ALL MEN'S <i
                        class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </section>

    {{-- ══ SECTION 6: WOMEN'S COLLECTION ══════════════ --}}
    <section class="collection-slider-section home-product-carousel" aria-label="Women's Collection">
        <div class="container-fluid position-relative px-0">
            <div class="collection-header text-center">
                <h2>WOMEN'S COLLECTION</h2>
                <p>elegant · trendy · timeless</p>
            </div>
            <div class="slider-container-collection">
                <button class="collection-arrow collection-arrow-left" id="womenArrowLeft" aria-label="Previous"><i
                        class="bi bi-chevron-left"></i></button>
                <button class="collection-arrow collection-arrow-right" id="womenArrowRight" aria-label="Next"><i
                        class="bi bi-chevron-right"></i></button>
                <div class="collection-slider-wrapper" id="womenSliderWrapper">
                    <div class="collection-track" id="womenSliderTrack">
                        @forelse($womensProducts as $product)
                            @include('froentend.partials.product-card', [
                                'product' => $product,
                                'type' => 'collection',
                            ])
                        @empty
                            <div class="text-muted p-4 text-center">No women's products yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="collection-footer">
                <a href="{{ route('shop.category', 'women') }}" class="section-btn" target="_blank">VIEW ALL WOMEN'S <i
                        class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </section>

    {{-- ══ SECTION 7: NEW IN ════════════════════════════ --}}
    <section class="collection-slider-section home-product-carousel" aria-label="New Arrivals">
        <div class="container-fluid position-relative px-0">
            <div class="collection-header text-center">
                <h2>NEW IN</h2>
                <p>fresh arrivals · styled on real people</p>
            </div>
            <div class="slider-container-collection">
                <button class="collection-arrow collection-arrow-left" id="newinArrowLeft" aria-label="Previous"><i
                        class="bi bi-chevron-left"></i></button>
                <button class="collection-arrow collection-arrow-right" id="newinArrowRight" aria-label="Next"><i
                        class="bi bi-chevron-right"></i></button>
                <div class="collection-slider-wrapper" id="newinSliderWrapper">
                    <div class="collection-track" id="newinSliderTrack">
                        @forelse($newArrivals as $product)
                            @include('froentend.partials.product-card', [
                                'product' => $product,
                                'type' => 'lifestyle',
                            ])
                        @empty
                            <div class="text-muted p-4 text-center">New arrivals coming soon!</div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="collection-footer">
                <a href="{{ route('shop.new-arrivals') }}" class="section-btn" target="_blank">EXPLORE NEW
                    ARRIVALS <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </section>

    {{-- ══ SECTION 8: OUR STORY ════════════════════════ --}}
    <section class="trend-story-section" aria-label="Our Brand Story">
        <div class="container">
            <div class="story-wrapper">
                <div class="story-image">
                    <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=800"
                        alt="THE TREND THEORY — Streetwear Born in India" loading="lazy" width="800" height="550">
                    <div class="image-overlay"></div>
                </div>
                <div class="story-content">
                    <h2 class="story-heading">
                        <span class="heading-line">THE TREND THEORY</span>
                    </h2>
                    <div class="story-subheading">
                        <span class="subheading-item">OUR</span>
                        <span class="subheading-item">STORY</span>
                    </div>
                    <p class="story-text">
                        <span class="text-highlight">THE TREND THEORY</span> was born from the streets of India.
                        We're not just selling clothes — we're defining a lifestyle. Every stitch, every print,
                        every drop is designed for those who dare to stand out.
                    </p>
                    <div class="values-container">
                        @foreach (['AUTHENTIC', 'BOLD', 'UNIQUE', 'PREMIUM', 'TIMELESS'] as $value)
                            <div class="value-pill">
                                <span class="value-dot" aria-hidden="true">✦</span>
                                <span class="value-name">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══ SECTION 9: TRENDING STORIES ════════════════ --}}
    <section class="trending-stories" aria-label="Trending Styles">
        <div class="container">
            <div class="trending-header">
                <div class="header-left">
                    <span class="trending-tag">TRENDING NOW</span>
                    <h2 class="trending-title">THE VIBE<span class="title-dot">.</span></h2>
                </div>
                <div class="header-right">
                    <p class="header-subtitle">Stories that hit different</p>
                    <div class="header-arrows">
                        <button class="arrow-btn" aria-label="Previous story"><i class="bi bi-arrow-left"></i></button>
                        <button class="arrow-btn" aria-label="Next story"><i class="bi bi-arrow-right"></i></button>
                    </div>
                </div>
            </div>
            <div class="stories-grid">
                @forelse($trendingStories as $story)
                    <div class="story-reel">
                        <div class="reel-media">
                            <img src="{{ $story['image_url'] ?? asset('images/placeholder-story.jpg') }}"
                                alt="{{ $story['caption'] ?? 'Trending Story' }} — THE TREND THEORY" class="reel-img"
                                loading="lazy" width="600" height="750"
                                onerror="this.src='{{ asset('images/placeholder-story.jpg') }}'">
                            <div class="reel-overlay" aria-hidden="true">
                                <div class="reel-top">
                                    <span
                                        class="reel-badge {{ $story['badge_type'] ?? 'default' }}">{{ $story['badge_text'] ?? 'TRENDING' }}</span>
                                    <span class="reel-time">{{ $story['duration'] ?? '0:30' }}</span>
                                </div>
                                <div class="reel-bottom">
                                    <div class="reel-user">
                                        <div class="user-avatar">
                                            <img src="{{ $story['user_avatar_url'] ?? asset('images/default-avatar.jpg') }}"
                                                alt="{{ $story['username'] ?? 'User' }}" loading="lazy" width="40"
                                                height="40"
                                                onerror="this.src='{{ asset('images/default-avatar.jpg') }}'">
                                        </div>
                                        <span class="username">{{ $story['username'] ?? 'thetrendtheory' }}</span>
                                    </div>
                                    <div class="reel-actions">
                                        <button class="reel-action"><i
                                                class="bi bi-heart"></i><span>{{ $story['formatted_likes'] ?? ($story['likes'] ?? 0) }}</span></button>
                                        <button class="reel-action"><i
                                                class="bi bi-play-circle"></i><span>{{ $story['views'] ?? 0 }}</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="reel-caption">
                            <p>
                                @if (!empty($story['caption_highlight']))
                                    <span class="caption-highlight">{{ $story['caption_highlight'] }}</span>
                                @endif
                                {{ $story['caption'] ?? '' }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center py-4">Trending stories coming soon!</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ══ SECTION 10: REVIEWS (THE BUZZ / REAL TALK) ══════════════════════════ --}}
    @isset($reviewSchema)
        <script type="application/ld+json">{!! $reviewSchema !!}</script>
    @endisset

    <section class="trending-reviews buzz-section-luxury py-5 position-relative overflow-hidden" aria-label="Customer Reviews">
        <!-- Ambient Decorative Glows -->
        <div class="buzz-bg-glow-1"></div>
        <div class="buzz-bg-glow-2"></div>
        
        <div class="container position-relative">
            <!-- Section Header -->
            <div class="buzz-header-row mb-5">
                <div class="buzz-header-left">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="buzz-badge-pill">
                            <i class="bi bi-stars text-warning me-1"></i> THE BUZZ
                        </span>
                        <span class="buzz-verified-count-pill d-none d-sm-inline-flex align-items-center gap-1">
                            <i class="bi bi-shield-fill-check text-success"></i> Verified Social Proof
                        </span>
                    </div>
                    <h2 class="buzz-section-title">
                        REAL TALK<span class="buzz-accent-dot">.</span>
                    </h2>
                    <p class="buzz-section-desc">
                        Unfiltered fit feedback, street-style vibes, and genuine reviews from trendsetters across India.
                    </p>
                </div>

                <div class="buzz-header-right">
                    <!-- Rating Summary Pill -->
                    <div class="buzz-rating-summary-card">
                        <div class="buzz-stars-row">
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-half text-warning"></i>
                        </div>
                        <div class="buzz-rating-numbers">
                            <span class="buzz-rating-score">4.8</span>
                            <span class="buzz-rating-max">/ 5.0</span>
                        </div>
                        <div class="buzz-review-tally">
                            2,500+ Happy Customers
                        </div>
                    </div>

                    <a href="{{ url('/reviews') }}" class="buzz-view-all-btn" target="_blank">
                        <span>EXPLORE ALL REVIEWS</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Reviews Grid -->
            <div class="row g-4 justify-content-center">
                @forelse($reviews as $review)
                    <div class="col-lg-4 col-md-6 d-flex">
                        <article class="buzz-luxury-card flex-fill" itemscope itemtype="https://schema.org/Review">
                            <!-- Floating Quote Accent -->
                            <div class="buzz-quote-watermark">
                                <i class="bi bi-quote"></i>
                            </div>

                            <!-- Card Top Bar: User & Verified Badge -->
                            <div class="buzz-card-top">
                                <div class="buzz-user-meta">
                                    <div class="buzz-avatar-wrap">
                                        @if (!empty($review['reviewer_image_url']))
                                            <img src="{{ $review['reviewer_image_url'] }}"
                                                alt="{{ $review['reviewer_name'] ?? 'Customer' }}" 
                                                loading="lazy" width="48" height="48" 
                                                class="buzz-user-avatar"
                                                onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.jpg') }}';">
                                        @else
                                            <div class="buzz-avatar-initials">
                                                {{ strtoupper(substr($review['reviewer_name'] ?? 'C', 0, 2)) }}
                                            </div>
                                        @endif
                                        <span class="buzz-online-badge"></span>
                                    </div>

                                    <div class="buzz-user-details">
                                        <h4 class="buzz-user-name" itemprop="author" itemscope itemtype="https://schema.org/Person">
                                            <span itemprop="name">{{ $review['reviewer_name'] ?? 'Verified Customer' }}</span>
                                        </h4>
                                        <div class="buzz-stars" itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                                            <meta itemprop="ratingValue" content="{{ $review['rating'] ?? 5 }}">
                                            <meta itemprop="bestRating" content="5">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star-fill{{ $i <= ($review['rating'] ?? 5) ? ' text-warning' : ' text-muted opacity-25' }}"
                                                    aria-hidden="true"></i>
                                            @endfor
                                            <span class="buzz-rating-digit ms-1">{{ number_format($review['rating'] ?? 5, 1) }}</span>
                                        </div>
                                    </div>
                                </div>

                                @if (!empty($review['is_verified']) || true)
                                    <span class="buzz-verified-tag" title="Verified Purchase">
                                        <i class="bi bi-patch-check-fill text-success me-1"></i>Verified
                                    </span>
                                @endif
                            </div>

                            <!-- Review Headline / Title (if exists) -->
                            @if (!empty($review['title']))
                                <h5 class="buzz-review-title">"{{ $review['title'] }}"</h5>
                            @endif

                            <!-- Review Body -->
                            <div class="buzz-card-body" itemprop="reviewBody">
                                <p class="buzz-review-quote">"{{ $review['comment'] ?? 'Absolutely love the quality and aesthetic of this piece. Fits true to size and the fabric feels ultra premium.' }}"</p>
                            </div>

                            <!-- Review Customer Photo (if attached) -->
                            @if (!empty($review['review_media']) && count($review['review_media']) > 0)
                                <div class="buzz-media-gallery">
                                    <div class="buzz-media-item">
                                        <img src="{{ $review['review_media'][0] }}?tr=w-500,h-320,q-80,f-webp"
                                            alt="Customer photo by {{ $review['reviewer_name'] ?? 'Customer' }}" 
                                            loading="lazy"
                                            class="buzz-media-img"
                                            onerror="this.onerror=null; this.src='{{ asset('images/placeholder-review.jpg') }}'">
                                        <span class="buzz-media-pill">
                                            <i class="bi bi-camera-fill me-1"></i> Customer Photo
                                        </span>
                                    </div>
                                </div>
                            @endif

                            <!-- Card Footer: Tagged Product & Engagement -->
                            <div class="buzz-card-footer mt-auto">
                                @if (!empty($review['product_tag']))
                                    <div class="buzz-product-pill-wrap">
                                        <a href="{{ route('shop.index') }}?tag={{ Str::slug($review['product_tag']) }}"
                                            class="buzz-product-pill" target="_blank">
                                            <i class="bi bi-tag-fill me-1 text-primary"></i>
                                            <span class="text-truncate" style="max-width: 140px;">{{ $review['product_tag'] }}</span>
                                        </a>
                                    </div>
                                @else
                                    <div class="buzz-product-pill-wrap">
                                        <span class="buzz-product-pill text-muted">
                                            <i class="bi bi-bag-check me-1"></i> Verified Order
                                        </span>
                                    </div>
                                @endif

                                <div class="buzz-footer-stats">
                                    <span class="buzz-stat-item buzz-like-btn" title="Helpful review">
                                        <i class="bi bi-heart-fill text-danger me-1"></i>
                                        <span>{{ number_format($review['likes'] ?? 14) }}</span>
                                    </span>
                                    <time class="buzz-stat-item"
                                        datetime="{{ isset($review['created_at']) ? date('c', strtotime($review['created_at'])) : date('c') }}"
                                        itemprop="datePublished">
                                        <i class="bi bi-clock me-1 text-muted"></i>
                                        {{ isset($review['created_at']) ? \Carbon\Carbon::parse($review['created_at'])->diffForHumans() : 'Recently' }}
                                    </time>
                                </div>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="p-5 rounded-4 bg-white shadow-sm border border-light max-w-500 mx-auto">
                            <i class="bi bi-chat-heart text-muted fs-1 mb-3 d-block"></i>
                            <h5 class="fw-bold text-dark">Be the Trend Pioneer!</h5>
                            <p class="text-muted fs-14 mb-4">No reviews featured yet. Shop your favorite piece and be the first to share your vibe.</p>
                            <a href="{{ route('shop.index') }}" class="section-btn">
                                Browse Collection <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Social Proof / Trust Strip Footer -->
            <div class="buzz-trust-strip mt-5">
                <div class="row g-4 text-center justify-content-center align-items-start">
                    <div class="col-6 col-lg-3">
                        <div class="buzz-trust-item">
                            <div class="buzz-trust-icon">
                                <i class="bi bi-truck"></i>
                            </div>
                            <div class="buzz-trust-info">
                                <h4 class="buzz-trust-val">SHIPPING WITHIN 48 HOURS</h4>
                                <p class="buzz-trust-lbl">Your order will be shipped within 48 hours from the time since order is placed!</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="buzz-trust-item">
                            <div class="buzz-trust-icon">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div class="buzz-trust-info">
                                <h4 class="buzz-trust-val">5% OFF</h4>
                                <p class="buzz-trust-lbl">5% OFF on Pre-paid orders.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="buzz-trust-item">
                            <div class="buzz-trust-icon">
                                <i class="bi bi-globe2"></i>
                            </div>
                            <div class="buzz-trust-info">
                                <h4 class="buzz-trust-val">MADE IN INDIA</h4>
                                <p class="buzz-trust-lbl">Our products are 100% made in India. From raw fabric to the final product!</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="buzz-trust-item">
                            <div class="buzz-trust-icon">
                                <i class="bi bi-bag-heart"></i>
                            </div>
                            <div class="buzz-trust-info">
                                <h4 class="buzz-trust-val">LUXURY FASHION MADE ACCESSIBLE</h4>
                                <p class="buzz-trust-lbl">High-quality clothing at affordable prices</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Custom Scoped Styles for THE BUZZ / REAL TALK Section -->
    <style>
        .buzz-section-luxury {
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 50%, #ffffff 100%);
            padding: 90px 0 80px;
            position: relative;
        }
        .buzz-bg-glow-1 {
            position: absolute;
            top: 5%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(0, 40, 90, 0.04) 0%, transparent 70%);
            pointer-events: none;
            border-radius: 50%;
        }
        .buzz-bg-glow-2 {
            position: absolute;
            bottom: 0%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255, 63, 108, 0.04) 0%, transparent 70%);
            pointer-events: none;
            border-radius: 50%;
        }
        .buzz-header-row {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 24px;
        }
        .buzz-header-left {
            max-width: 620px;
        }
        .buzz-badge-pill {
            display: inline-flex;
            align-items: center;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            padding: 4px 14px;
            border-radius: 999px;
            background: #00285a;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 40, 90, 0.18);
        }
        .buzz-verified-count-pill {
            font-size: 0.75rem;
            font-weight: 600;
            color: #0f172a;
            background: rgba(16, 185, 129, 0.12);
            padding: 4px 12px;
            border-radius: 999px;
            border: 1px solid rgba(16, 185, 129, 0.25);
        }
        .buzz-section-title {
            font-family: 'Cinzel', serif, -apple-system, sans-serif;
            font-size: clamp(2rem, 4vw, 2.75rem);
            font-weight: 900;
            color: #00285a;
            letter-spacing: 0.04em;
            line-height: 1.15;
            margin: 8px 0 10px;
        }
        .buzz-accent-dot {
            color: #ff3f6c;
        }
        .buzz-section-desc {
            font-size: 0.95rem;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 0;
        }
        .buzz-header-right {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .buzz-rating-summary-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 10px 18px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 40, 90, 0.04);
            text-align: center;
        }
        .buzz-stars-row {
            font-size: 0.85rem;
            margin-bottom: 2px;
        }
        .buzz-rating-numbers {
            display: flex;
            align-items: baseline;
            justify-content: center;
            gap: 2px;
        }
        .buzz-rating-score {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
        }
        .buzz-rating-max {
            font-size: 0.78rem;
            color: #94a3b8;
            font-weight: 600;
        }
        .buzz-review-tally {
            font-size: 0.72rem;
            color: #64748b;
            font-weight: 600;
        }
        .buzz-view-all-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #00285a;
            color: #ffffff;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 14px 26px;
            border-radius: 999px;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 10px 24px rgba(0, 40, 90, 0.16);
        }
        .buzz-view-all-btn i {
            transition: transform 0.2s ease;
        }

        /* Luxury Review Card */
        .buzz-luxury-card {
            background: #ffffff;
            border: 1px solid #e8eef5;
            border-radius: 24px;
            padding: 28px;
            position: relative;
            display: flex;
            flex-direction: column;
            box-shadow: 0 10px 30px rgba(0, 40, 90, 0.04);
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
        }
        .buzz-luxury-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3.5px;
            background: linear-gradient(90deg, #00285a 0%, #ff3f6c 50%, #14b8a6 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .buzz-quote-watermark {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 3.5rem;
            color: #f1f5f9;
            line-height: 1;
            pointer-events: none;
            transition: color 0.3s ease;
        }

        /* Card Top User Info */
        .buzz-card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
            position: relative;
            z-index: 1;
        }
        .buzz-user-meta {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .buzz-avatar-wrap {
            position: relative;
            width: 48px;
            height: 48px;
            flex-shrink: 0;
        }
        .buzz-user-avatar {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e2e8f0;
        }
        .buzz-avatar-initials {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: linear-gradient(135deg, #00285a 0%, #1e293b 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ffffff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }
        .buzz-online-badge {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 12px;
            height: 12px;
            background: #10b981;
            border: 2px solid #ffffff;
            border-radius: 50%;
        }
        .buzz-user-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 2px;
        }
        .buzz-stars {
            font-size: 0.78rem;
            display: flex;
            align-items: center;
        }
        .buzz-rating-digit {
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
        }
        .buzz-verified-tag {
            font-size: 0.72rem;
            font-weight: 700;
            color: #047857;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            padding: 4px 10px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
        }

        /* Card Content */
        .buzz-review-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #00285a;
            margin-bottom: 8px;
            line-height: 1.35;
        }
        .buzz-card-body {
            margin-bottom: 18px;
            position: relative;
            z-index: 1;
        }
        .buzz-review-quote {
            font-size: 0.92rem;
            line-height: 1.65;
            color: #334155;
            margin-bottom: 0;
            font-style: normal;
        }

        /* Customer Media */
        .buzz-media-gallery {
            margin-bottom: 18px;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            background: #0f172a;
            max-height: 180px;
        }
        .buzz-media-item {
            position: relative;
            overflow: hidden;
            width: 100%;
            height: 180px;
        }
        .buzz-media-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }
        .buzz-media-pill {
            position: absolute;
            bottom: 10px;
            left: 10px;
            font-size: 0.7rem;
            font-weight: 700;
            color: #ffffff;
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(4px);
            padding: 3px 10px;
            border-radius: 999px;
        }

        /* Card Footer */
        .buzz-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding-top: 14px;
            border-top: 1px solid #f1f5f9;
            position: relative;
            z-index: 1;
        }
        .buzz-product-pill {
            display: inline-flex;
            align-items: center;
            font-size: 0.76rem;
            font-weight: 600;
            color: #00285a;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 5px 12px;
            border-radius: 999px;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .buzz-footer-stats {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 0.75rem;
            color: #64748b;
        }
        .buzz-like-btn {
            cursor: pointer;
            transition: transform 0.15s ease;
        }

        /* Trust Strip */
        .buzz-trust-strip {
            background: #ffffff;
            border: 0;
            border-bottom: 1px solid #d9d9d9;
            border-radius: 0;
            padding: 34px 0 36px;
            box-shadow: none;
        }
        .buzz-trust-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 18px;
            height: 100%;
            padding: 0 16px;
        }
        .buzz-trust-icon {
            width: 78px;
            height: 78px;
            border-radius: 50%;
            background: #ffffff;
            border: 1px solid #dedede;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 34px;
            color: #050505;
            flex-shrink: 0;
        }
        .buzz-trust-info {
            text-align: center;
            max-width: 330px;
        }
        .buzz-trust-val {
            min-height: 44px;
            font-size: 20px;
            font-weight: 900;
            color: #050505;
            margin: 0 0 6px;
            line-height: 1.1;
            letter-spacing: 0;
            text-transform: uppercase;
        }
        .buzz-trust-lbl {
            font-size: 14px;
            color: #050505;
            margin-bottom: 0;
            line-height: 1.65;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .buzz-section-luxury {
                padding: 60px 0 50px;
            }
            .buzz-header-row {
                flex-direction: column;
                align-items: flex-start;
            }
            .buzz-header-right {
                width: 100%;
                justify-content: space-between;
            }
            .buzz-view-all-btn {
                width: 100%;
                justify-content: center;
            }
            .buzz-trust-item {
                flex-direction: column;
                text-align: center;
                gap: 14px;
                padding: 0 8px;
            }
            .buzz-trust-info {
                text-align: center;
            }
            .buzz-trust-icon {
                width: 66px;
                height: 66px;
                font-size: 28px;
            }
            .buzz-trust-val {
                min-height: auto;
                font-size: 15px;
            }
            .buzz-trust-lbl {
                font-size: 12px;
                line-height: 1.45;
            }
        }
    </style>

    {{-- ══════════════════════════════════════════════════
     ADD TO CART AJAX
════════════════════════════════════════════════════ --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // Top gallery hero slider controller
                var heroSlider = document.querySelector('[data-hero-media-slider]');
                if (heroSlider) {
                    var heroSlides = Array.prototype.slice.call(heroSlider.querySelectorAll('.hero-media-slide'));
                    var heroDots = Array.prototype.slice.call(heroSlider.querySelectorAll('[data-hero-dot]'));
                    var heroPrev = heroSlider.querySelector('[data-hero-prev]');
                    var heroNext = heroSlider.querySelector('[data-hero-next]');
                    var heroCounterCurrent = document.getElementById('heroCounterCurrent');
                    var heroAudioToggle = document.getElementById('heroAudioToggle');
                    var heroAudioIcon = document.getElementById('heroAudioIcon');
                    var heroIndex = 0;
                    var heroTimer = null;
                    var isMuted = true;
                    var slideDuration = 8000;

                    function syncHeroMedia() {
                        heroSlides.forEach(function(slide, index) {
                            var video = slide.querySelector('video');
                            if (!video) return;

                            if (index === heroIndex) {
                                video.muted = isMuted;
                                video.play().catch(function() {});
                            } else {
                                video.pause();
                                video.currentTime = 0;
                            }
                        });

                        // Audio button visibility
                        if (heroAudioToggle) {
                            var activeSlide = heroSlides[heroIndex];
                            var hasVideo = activeSlide && activeSlide.dataset.mediaType === 'video';
                            heroAudioToggle.style.display = hasVideo ? 'flex' : 'none';
                        }
                    }

                    if (heroAudioToggle) {
                        heroAudioToggle.addEventListener('click', function() {
                            isMuted = !isMuted;
                            if (heroAudioIcon) {
                                heroAudioIcon.className = isMuted ? 'bi bi-volume-mute-fill' : 'bi bi-volume-up-fill';
                            }
                            var activeVideo = heroSlides[heroIndex]?.querySelector('video');
                            if (activeVideo) activeVideo.muted = isMuted;
                        });
                    }

                    function showHeroSlide(nextIndex) {
                        if (!heroSlides.length) return;
                        heroIndex = (nextIndex + heroSlides.length) % heroSlides.length;

                        heroSlides.forEach(function(slide, index) {
                            var isActive = index === heroIndex;
                            slide.classList.toggle('active', isActive);
                            slide.setAttribute('aria-hidden', isActive ? 'false' : 'true');
                        });

                        heroDots.forEach(function(dot, index) {
                            var isActive = index === heroIndex;
                            dot.classList.toggle('active', isActive);
                            var fill = dot.querySelector('.hero-progress-fill');
                            if (fill) {
                                fill.style.animation = 'none';
                                fill.offsetHeight; // trigger reflow
                                if (isActive) {
                                    fill.style.animation = 'heroProgressFill ' + (slideDuration / 1000) + 's linear forwards';
                                }
                            }
                        });

                        if (heroCounterCurrent) {
                            heroCounterCurrent.textContent = String(heroIndex + 1).padStart(2, '0');
                        }

                        syncHeroMedia();
                    }

                    function startHeroAuto() {
                        if (heroSlides.length < 2) return;
                        clearInterval(heroTimer);
                        heroTimer = setInterval(function() {
                            showHeroSlide(heroIndex + 1);
                        }, slideDuration);
                    }

                    function resetHeroAuto() {
                        clearInterval(heroTimer);
                        startHeroAuto();
                    }

                    if (heroPrev) {
                        heroPrev.addEventListener('click', function() {
                            showHeroSlide(heroIndex - 1);
                            resetHeroAuto();
                        });
                    }

                    if (heroNext) {
                        heroNext.addEventListener('click', function() {
                            showHeroSlide(heroIndex + 1);
                            resetHeroAuto();
                        });
                    }

                    heroDots.forEach(function(dot) {
                        dot.addEventListener('click', function() {
                            showHeroSlide(parseInt(dot.dataset.heroDot, 10) || 0);
                            resetHeroAuto();
                        });
                    });

                    // Touch Swipe Support
                    var touchStartX = 0;
                    var touchEndX = 0;
                    heroSlider.addEventListener('touchstart', function(e) {
                        touchStartX = e.changedTouches[0].screenX;
                    }, { passive: true });

                    heroSlider.addEventListener('touchend', function(e) {
                        touchEndX = e.changedTouches[0].screenX;
                        if (touchStartX - touchEndX > 50) {
                            showHeroSlide(heroIndex + 1);
                            resetHeroAuto();
                        } else if (touchEndX - touchStartX > 50) {
                            showHeroSlide(heroIndex - 1);
                            resetHeroAuto();
                        }
                    }, { passive: true });

                    showHeroSlide(0);
                    startHeroAuto();
                }

                // Change product images only on the card currently being hovered/focused.
                (function initHomeProductImageAutoChange() {
                    var galleries = Array.prototype.slice.call(document.querySelectorAll('.home-product-carousel [data-home-card-gallery]'));
                    if (!galleries.length) return;

                    galleries.forEach(function(gallery) {
                        if (gallery.dataset.homeGalleryInited === 'true') return;

                        var slides = Array.prototype.slice.call(gallery.querySelectorAll('.home-card-gallery-img'));
                        if (slides.length < 2) return;

                        gallery.dataset.homeGalleryInited = 'true';
                        var hoverTimer = null;

                        slides.forEach(function(img) {
                            if (img && img.src) {
                                var preload = new Image();
                                preload.src = img.src;
                            }
                        });

                        var activeIndex = slides.findIndex(function(slide) {
                            return slide.classList.contains('active');
                        });
                        if (activeIndex < 0) activeIndex = 0;

                        function showImage(nextIndex) {
                            activeIndex = (nextIndex + slides.length) % slides.length;
                            slides.forEach(function(slide, idx) {
                                slide.classList.toggle('active', idx === activeIndex);
                            });
                        }

                        function startHoverSlider() {
                            if (hoverTimer) return;

                            hoverTimer = setInterval(function() {
                                if (document.hidden) return;
                                showImage(activeIndex + 1);
                            }, 900);
                        }

                        function stopHoverSlider() {
                            if (!hoverTimer) return;

                            clearInterval(hoverTimer);
                            hoverTimer = null;
                            showImage(0);
                        }

                        gallery.addEventListener('mouseenter', startHoverSlider);
                        gallery.addEventListener('focusin', startHoverSlider);
                        gallery.addEventListener('mouseleave', stopHoverSlider);
                        gallery.addEventListener('focusout', function(e) {
                            if (!gallery.contains(e.relatedTarget)) stopHoverSlider();
                        });

                        gallery.addEventListener('touchstart', function() {
                            if (document.hidden) return;
                            showImage(activeIndex + 1);
                        }, { passive: true });
                    });
                })();

                // Add to cart functionality
                document.querySelectorAll('.add-to-cart-btn:not(.open-product-slider)').forEach(function(btn) {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        if (this.disabled) return;

                        var productId = this.dataset.productId;
                        var productName = this.dataset.productName;
                        var me = this;
                        var orig = me.textContent;

                        if (typeof window.openSlider === 'function') {
                            window.openSlider(productId);
                            return;
                        }

                        me.textContent = 'ADDING...';
                        me.disabled = true;

                        fetch('{{ route('cart.add.item', ':id') }}'.replace(':id', productId), {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': window.tttCsrfToken ? window.tttCsrfToken() : '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({
                                    quantity: 1
                                })
                            })
                            .then(function(r) {
                                return r.json();
                            })
                            .then(function(data) {
                                if (data.success) {
                                    var badge = document.getElementById('cart-count');
                                    if (badge) {
                                        badge.textContent = data.cart_count;
                                        badge.style.display = 'flex';
                                    }
                                    showToast('✓ ' + productName + ' added!', 'success');
                                    me.textContent = 'ADDED';
                                    setTimeout(function() {
                                        me.textContent = orig;
                                        me.disabled = false;
                                    }, 1500);
                                } else if (data.redirect) {
                                    window.location.href = data.redirect;
                                } else {
                                    showToast(data.message || 'Something went wrong!', 'error');
                                    me.textContent = orig;
                                    me.disabled = false;
                                }
                            })
                            .catch(function() {
                                showToast('Connection error. Try again!', 'error');
                                me.textContent = orig;
                                me.disabled = false;
                            });
                    });
                });

                function showToast(msg, type) {
                    var old = document.querySelector('.tt-toast');
                    if (old) old.remove();
                    var t = document.createElement('div');
                    t.className = 'tt-toast';
                    t.textContent = msg;
                    t.style.cssText =
                        'position:fixed;bottom:24px;right:24px;z-index:9999;padding:14px 22px;border-radius:50px;font-weight:600;font-size:14px;color:#fff;box-shadow:0 10px 30px rgba(0,0,0,.2);background:' +
                        (type === 'success' ? '#ff3f6c' : '#dc3545');
                    document.body.appendChild(t);
                    setTimeout(function() {
                        t.style.opacity = '0';
                        t.style.transition = 'opacity 0.3s';
                        setTimeout(function() {
                            t.remove();
                        }, 300);
                    }, 2500);
                }
            });
        </script>
    @endpush

@endsection
