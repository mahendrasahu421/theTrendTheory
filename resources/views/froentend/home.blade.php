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
            /* Remove animation/zoom effects from hero slides and media */
            .hero-video,
            .hero-image-zoom,
            .hero-media-slide.active .hero-image-zoom,
            .hero-media-slide img,
            .hero-media-slide video {
                transform: translate(-50%, -50%) !important;
                transition: none !important;
                animation: none !important;
            }
            .hero-media-slide {
                position: absolute !important;
                inset: 0 !important;
                opacity: 0 !important;
                visibility: hidden !important;
                transition: opacity 0.7s ease-in-out, visibility 0.7s ease-in-out !important;
                z-index: 1 !important;
            }
            .hero-media-slide.active {
                opacity: 1 !important;
                visibility: visible !important;
                z-index: 2 !important;
            }

            /* Carousel Indicators at Bottom of Hero */
            :root {
                --hero-slide-duration: 5000ms;
            }
            .hero-indicators-container {
                position: absolute !important;
                bottom: 30px !important;
                left: 50% !important;
                transform: translateX(-50%) !important;
                z-index: 20 !important;
                display: flex !important;
                align-items: center !important;
                gap: 12px !important;
                padding: 6px 14px !important;
                background: rgba(0, 0, 0, 0.3) !important;
                backdrop-filter: blur(12px) !important;
                -webkit-backdrop-filter: blur(12px) !important;
                border-radius: 999px !important;
                border: 1px solid rgba(255, 255, 255, 0.2) !important;
            }
            .hero-bullet-btn {
                position: relative !important;
                width: 26px !important;
                height: 26px !important;
                background: transparent !important;
                border: none !important;
                padding: 0 !important;
                cursor: pointer !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                outline: none !important;
                border-radius: 50% !important;
                transition: transform 0.2s ease !important;
            }
            .hero-bullet-btn:hover {
                transform: scale(1.15) !important;
            }
            .hero-bullet-dot {
                width: 6px !important;
                height: 6px !important;
                border-radius: 50% !important;
                background-color: rgba(255, 255, 255, 0.45) !important;
                transition: all 0.3s ease !important;
                z-index: 2 !important;
                pointer-events: none !important;
            }
            .hero-bullet-btn:hover .hero-bullet-dot {
                background-color: rgba(255, 255, 255, 0.85) !important;
            }
            .hero-bullet-btn.active .hero-bullet-dot {
                width: 8px !important;
                height: 8px !important;
                background-color: #ffffff !important;
                box-shadow: 0 0 10px rgba(255, 255, 255, 0.95) !important;
            }
            .hero-bullet-svg {
                position: absolute !important;
                inset: 0 !important;
                width: 100% !important;
                height: 100% !important;
                transform: rotate(-90deg) !important;
                pointer-events: none !important;
                opacity: 0 !important;
                transition: opacity 0.2s ease !important;
            }
            .hero-bullet-btn.active .hero-bullet-svg {
                opacity: 1 !important;
            }
            .hero-bullet-track {
                fill: none !important;
                stroke: rgba(255, 255, 255, 0.2) !important;
                stroke-width: 1.8 !important;
            }
            .hero-bullet-circle {
                fill: none !important;
                stroke: #ffffff !important;
                stroke-width: 2 !important;
                stroke-linecap: round !important;
                stroke-dasharray: 69.12 !important;
                stroke-dashoffset: 69.12 !important;
            }
            .hero-bullet-btn.active.animating .hero-bullet-circle {
                animation: heroCircleProgress var(--hero-slide-duration, 5000ms) linear forwards !important;
            }
            .video-hero:hover .hero-bullet-btn.active.animating .hero-bullet-circle {
                animation-play-state: paused !important;
            }
            @keyframes heroCircleProgress {
                from {
                    stroke-dashoffset: 69.12;
                }
                to {
                    stroke-dashoffset: 0;
                }
            }
            @media (max-width: 768px) {
                .video-hero,
                .hero-media-slider {
                    height: 80vh !important;
                    min-height: 500px !important;
                    max-height: 740px !important;
                    position: relative !important;
                    overflow: hidden !important;
                }
                .hero-media-wrapper,
                .hero-media-slide {
                    height: 100% !important;
                    width: 100% !important;
                    position: absolute !important;
                    inset: 0 !important;
                }
                .hero-video,
                .hero-media-slide img,
                .hero-media-slide video {
                    width: 100% !important;
                    height: 100% !important;
                    object-fit: cover !important;
                    object-position: center top !important;
                    position: absolute !important;
                    top: 50% !important;
                    left: 50% !important;
                    transform: translate(-50%, -50%) !important;
                }
                .video-content-container {
                    padding: 20px 16px 54px !important;
                    display: flex !important;
                    align-items: flex-end !important;
                    justify-content: center !important;
                }
                .hero-buttons {
                    width: 100% !important;
                    display: flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    margin-bottom: 22px !important;
                }
                .hero-btn-primary {
                    background: #ffffff !important;
                    color: #000000 !important;
                    border: none !important;
                    padding: 13px 44px !important;
                    font-size: 13.5px !important;
                    font-weight: 800 !important;
                    letter-spacing: 1.5px !important;
                    text-transform: uppercase !important;
                    border-radius: 4px !important;
                    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.45) !important;
                    width: auto !important;
                    min-width: 180px !important;
                }
                .hero-btn-primary i {
                    display: none !important;
                }
                .hero-indicators-container {
                    bottom: 16px !important;
                    background: transparent !important;
                    border: none !important;
                    backdrop-filter: none !important;
                    -webkit-backdrop-filter: none !important;
                    gap: 8px !important;
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
                                    <img class="hero-video" src="{{ $slide['image'] }}"
                                        alt="{{ $slide['alt_text'] ?? ($slide['title'] ?? 'Hero media') }}"
                                        loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                        onerror="this.src='{{ asset('images/placeholder-slide.jpg') }}'">
                                </picture>
                            @endif
                            {{-- <div class="video-overlay" style="display:none;background:transparent;"></div> --}}
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

                {{-- Carousel Navigation Indicators with Circular Auto-Progress Bullets --}}
                <div class="hero-indicators-container" aria-label="Hero slide navigation">
                    @foreach ($topHeroSlides as $slide)
                        <button class="hero-bullet-btn {{ $loop->first ? 'active' : '' }}" type="button"
                            data-hero-dot="{{ $loop->index }}" aria-label="Go to slide {{ $loop->iteration }}">
                            <svg class="hero-bullet-svg" viewBox="0 0 28 28">
                                <circle class="hero-bullet-track" cx="14" cy="14" r="11"></circle>
                                <circle class="hero-bullet-circle" cx="14" cy="14" r="11"></circle>
                            </svg>
                            <span class="hero-bullet-dot"></span>
                        </button>
                    @endforeach
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
            <div class="most-slider-header text-center mb-5">
                <i class="bi bi-crown-fill" aria-hidden="true"></i>
                <h2>MOST PURCHASED</h2>
                {{-- <p>customer favorites · highest units sold</p> --}}
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
 {{-- ══ SECTION 9.5: INSTAGRAM REELS (SHOP THE LOOK) ════════════════ --}}
    @if(!empty($instagramReels) && count($instagramReels) > 0)
    <section class="reels-section py-5" aria-label="Instagram Reels and Shoppable Videos">
        

        <div class="container-fluid">
            <div class="reels-carousel-container position-relative">
                <!-- Nav Arrow Left -->
                <button class="reels-arrow-btn reels-arrow-prev" data-reels-arrow="prev" aria-label="Scroll left">
                    <i class="bi bi-chevron-left"></i>
                </button>

                <!-- Reels Track -->
                <div class="reels-track" data-reels-track>
                    @foreach($instagramReels as $reelIndex => $reel)
                        <div class="reel-item" data-reel-card data-reel-id="{{ $reel['id'] }}" data-reel-index="{{ $reelIndex }}">
                            <div class="reel-media-box" data-reel-trigger="{{ $reelIndex }}">
                                <video class="reel-video-element"
                                       src="{{ $reel['video_url'] }}"
                                       poster="{{ $reel['poster'] }}"
                                       loop muted playsinline preload="none"></video>

                                <!-- Subtle gradient overlay -->
                                <div class="reel-vignette"></div>

                                <!-- Top Meta (Handle) -->
                                <div class="reel-top-bar">
                                    <span class="reel-creator-handle">{{ $reel['username'] ?? '@thetrendtheory' }}</span>
                                    <button class="reel-sound-toggle" data-reel-sound aria-label="Toggle sound">
                                        <i class="bi bi-volume-mute-fill"></i>
                                    </button>
                                </div>

                                <!-- FLOATING SHOPPABLE PRODUCT PILL (Exact match to screenshot) -->
                                @if(!empty($reel['products']) && count($reel['products']) > 0)
                                    <div class="reel-shoppable-pill" data-reel-pill title="Tap to view featured product">
                                        <div class="reel-pill-thumbs">
                                            @foreach($reel['products'] as $pIdx => $prod)
                                                <img src="{{ $prod['image'] }}"
                                                     alt="{{ $prod['name'] }}"
                                                     class="reel-pill-thumb thumb-{{ $pIdx }}"
                                                     onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';">
                                            @endforeach
                                        </div>
                                        <span class="reel-pill-caret">
                                            <i class="bi bi-chevron-up"></i>
                                        </span>
                                    </div>

                                    <!-- FLOATING PRODUCT QUICK-VIEW CARD (Expands when pill is clicked/hovered) -->
                                    <div class="reel-product-drawer" data-reel-drawer>
                                        <div class="reel-drawer-products">
                                            @foreach($reel['products'] as $prod)
                                                <div class="reel-drawer-product-row">
                                                    <img src="{{ $prod['image'] }}" alt="{{ $prod['name'] }}" class="reel-drawer-img">
                                                    <div class="reel-drawer-info">
                                                        <h4 class="reel-drawer-title">{{ $prod['name'] }}</h4>
                                                        <div class="reel-drawer-price-wrap">
                                                            <span class="reel-drawer-price">{{ $prod['price'] }}</span>
                                                            @if(!empty($prod['original_price']))
                                                                <span class="reel-drawer-strike">{{ $prod['original_price'] }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Nav Arrow Right -->
                <button class="reels-arrow-btn reels-arrow-next" data-reels-arrow="next" aria-label="Scroll right">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Scroll Progress Bar (as seen in reference screenshot) -->
        <div class="container mt-3">
            <div class="reels-progress-wrap">
                <div class="reels-progress-bar" data-reels-progress></div>
            </div>
        </div>
    </section>

    {{-- ══ INSTAGRAM REEL LIGHTBOX MODAL (EXACT SCREENSHOT MATCH) ════════════════ --}}
    <div class="reel-modal-overlay" id="reelLightboxModal" style="display: none;" aria-hidden="true">
        <div class="reel-modal-backdrop" id="reelModalBackdrop"></div>

        <div class="reel-modal-container">
            <!-- Prev Navigation Arrow -->
            <button type="button" class="reel-modal-nav-arrow reel-modal-nav-prev" id="reelModalPrevBtn" aria-label="Previous video">
                <i class="bi bi-chevron-left"></i>
            </button>

            <!-- 9:16 Video Player Card -->
            <div class="reel-modal-card" id="reelModalCard">
                <!-- Video Element -->
                <video id="reelModalVideo" class="reel-modal-video" playsinline loop></video>

                <!-- Top Right Bar: Sound & Close -->
                <div class="reel-modal-top-actions">
                    <button type="button" class="reel-glass-btn" id="reelModalSoundBtn" aria-label="Toggle sound">
                        <i class="bi bi-volume-up-fill" id="reelModalSoundIcon"></i>
                    </button>
                    <button type="button" class="reel-glass-btn" id="reelModalCloseBtn" aria-label="Close video">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <!-- Right Vertical Actions: Like & Share -->
                <div class="reel-modal-right-actions">
                    <button type="button" class="reel-action-btn" id="reelModalLikeBtn" aria-label="Like video">
                        <div class="reel-action-icon-circle">
                            <i class="bi bi-heart" id="reelModalLikeIcon"></i>
                        </div>
                        <span class="reel-action-count" id="reelModalLikeCount">13</span>
                    </button>
                    <button type="button" class="reel-action-btn" id="reelModalShareBtn" aria-label="Share video">
                        <div class="reel-action-icon-circle">
                            <i class="bi bi-share"></i>
                        </div>
                        <span class="reel-action-label">Share</span>
                    </button>
                </div>

                <!-- Bottom Floating Shoppable Product Card Dock -->
                <div class="reel-modal-product-dock" id="reelModalProductDock">
                    <div class="reel-modal-product-track" id="reelModalProductTrack">
                        <!-- Populated dynamically via JavaScript -->
                    </div>
                </div>

                <!-- COLLAPSIBLE QUICK BUY BOTTOM DRAWER (Exact match to screenshot media_1791179704926.png) -->
                <div class="reel-quickbuy-drawer" id="reelQuickBuyDrawer" aria-hidden="true">
                    <!-- Top Gallery Thumbnails & Close -->
                    <div class="reel-drawer-topbar">
                        <div class="reel-drawer-thumbs" id="reelDrawerThumbs">
                            <!-- Populated dynamically: 3 images -->
                        </div>
                        <button type="button" class="reel-drawer-close" id="reelDrawerCloseBtn" aria-label="Close product sheet">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <!-- Product Title & External Link -->
                    <div class="reel-drawer-meta">
                        <h4 class="reel-drawer-title" id="reelDrawerTitle">Product Title</h4>
                        <a href="#" target="_blank" class="reel-drawer-link" id="reelDrawerLink" title="View product page">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </div>

                    <!-- Price Row -->
                    <div class="reel-drawer-pricing">
                        <span class="reel-drawer-price" id="reelDrawerPrice">₹ 0</span>
                        <span class="reel-drawer-strike" id="reelDrawerStrike"></span>
                        <span class="reel-drawer-discount" id="reelDrawerDiscount"></span>
                    </div>

                    <!-- Size Selector -->
                    <div class="reel-drawer-size-section">
                        <span class="reel-drawer-size-title">Size</span>
                        <div class="reel-drawer-size-pills" id="reelDrawerSizes">
                            <!-- Populated dynamically -->
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="reel-drawer-actions">
                        <button type="button" class="reel-drawer-action-btn reel-drawer-btn-cart" id="reelDrawerAddCartBtn">
                            ADD TO CART
                        </button>
                        <button type="button" class="reel-drawer-action-btn reel-drawer-btn-buy" id="reelDrawerBuyBtn">
                            BUY NOW
                        </button>
                        <a href="{{ route('cart.index') }}" class="reel-drawer-bag-link" id="reelDrawerBagLink" title="View Bag">
                            <i class="bi bi-bag"></i>
                            <span class="reel-drawer-bag-badge" id="reelDrawerBagBadge">{{ count(session('cart', [])) }}</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Next Navigation Arrow -->
            <button type="button" class="reel-modal-nav-arrow reel-modal-nav-next" id="reelModalNextBtn" aria-label="Next video">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>
    @endif
    {{-- ══ SECTION 5: MEN'S COLLECTION ═════════════════ --}}
    <section class="collection-slider-section home-product-carousel" aria-label="Men's Collection">
        <div class="container-fluid position-relative px-0">
            <div class="collection-header text-center mb-5">
                <h2>MEN'S COLLECTION</h2>
                {{-- <p>stylish · comfortable · modern</p> --}}
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
            <div class="collection-header text-center mb-5">
                <h2>WOMEN'S COLLECTION</h2>
                {{-- <p>elegant · trendy · timeless</p> --}}
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
            <div class="container px-3 px-md-4 mb-4">
                <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
                    <div>
                        <h2 class="mb-1" style="font-size: clamp(1.6rem, 2.8vw, 2.2rem); font-weight: 800; color: #1e293b; letter-spacing: -0.01em;">New In</h2>
                        <p class="mb-0 text-muted" style="font-size: 0.95rem;">Upgrade your closet with everything trendy and new</p>
                    </div>
                    <div class="text-end">
                        <a href="{{ route('shop.new-arrivals') }}" style="font-size: 0.92rem; font-weight: 700; color: #1e293b; text-decoration: underline; text-underline-offset: 4px;">Shop New Arrivals</a>
                    </div>
                </div>
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

    {{-- ══ SECTION 9: LATEST NEWS ════════════════ --}}
    <section class="vibe-section py-5" aria-label="Latest News">
        <div class="container">
            <div class="vibe-header d-flex flex-wrap align-items-end justify-content-between mb-4 pb-2">
                <div class="vibe-header-left">
                    <h2 class="vibe-title">Latest News</h2>
                    <p class="vibe-subtitle">Hot off the press: All the lastest news in fashion</p>
                </div>
                <div class="vibe-header-right">
                    <a href="{{ route('news.index') }}" class="vibe-all-posts-link">
                        View all posts
                    </a>
                </div>
            </div>

            <div class="row g-4">
                @forelse($trendingStories as $story)
                    <div class="col-lg-4 col-md-6">
                        <article class="vibe-news-card">
                            <a href="{{ $story['url'] ?? route('news.index') }}" class="vibe-news-thumb-link" aria-label="Read article: {{ $story['title'] ?? '' }}">
                                <div class="vibe-news-img-wrap">
                                    <img src="{{ $story['image_url'] ?? asset('images/placeholder-story.jpg') }}"
                                         alt="{{ $story['title'] ?? 'The Trend Theory Story' }}"
                                         class="vibe-news-img"
                                         loading="lazy" width="600" height="375"
                                         onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1529139574466-a303027c1d8b?w=800&auto=format&fit=crop&q=80';">
                                </div>
                            </a>

                            <div class="vibe-news-content">
                                <time class="vibe-news-date">
                                    {{ !empty($story['formatted_date']) ? strtoupper($story['formatted_date']) : 'SEPTEMBER 25 2026' }}
                                </time>

                                <h3 class="vibe-news-title">
                                    <a href="{{ $story['url'] ?? route('news.index') }}">
                                        {{ $story['title'] ?? 'Creatures of Paradise: Where the Wild Meets Streetwear' }}
                                    </a>
                                </h3>

                                <p class="vibe-news-excerpt">
                                    {{ $story['caption'] ?? 'Step into our latest drop, where wild animal graphics meet bold typography and heavy-duty 240 GSM combed cotton.' }}
                                </p>

                                <a href="{{ $story['url'] ?? route('news.index') }}" class="vibe-news-readmore-btn" aria-label="Read more about {{ $story['title'] ?? 'this story' }}">
                                    <span>Read more</span> <span class="readmore-arrow">&rarr;</span>
                                </a>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">No news articles published yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

   

    {{-- ══ SECTION 10: REVIEWS (THE BUZZ / REAL TALK) ══════════════════════════ --}}
    @isset($reviewSchema)
        <script type="application/ld+json">{!! $reviewSchema !!}</script>
    @endisset

    <section class="buzz-section py-5 position-relative" aria-label="Customer Reviews">
        <div class="container position-relative">
            <!-- Section Header -->
            <div class="buzz-header-row mb-5">
                <div class="buzz-header-left">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="buzz-badge-pill">THE BUZZ</span>
                        <span class="buzz-verified-count-pill">VERIFIED SOCIAL PROOF</span>
                    </div>
                    <h2 class="buzz-section-title">
                        REAL TALK<span class="buzz-accent-dot">.</span>
                    </h2>
                    <p class="buzz-section-desc">
                        Unfiltered fit feedback, street-style vibes, and genuine reviews from trendsetters across India.
                    </p>
                </div>

                <div class="buzz-header-right">
                    <!-- Rating Summary Card -->
                    <div class="buzz-rating-summary-card">
                        <div class="buzz-stars-row">
                            <span class="buzz-star">&#9733;</span>
                            <span class="buzz-star">&#9733;</span>
                            <span class="buzz-star">&#9733;</span>
                            <span class="buzz-star">&#9733;</span>
                            <span class="buzz-star">&#9733;</span>
                        </div>
                        <div class="buzz-rating-numbers">
                            <span class="buzz-rating-score">4.9</span>
                            <span class="buzz-rating-max">/ 5.0</span>
                        </div>
                        <div class="buzz-review-tally">
                            2,500+ Verified Buyers
                        </div>
                    </div>

                    <a href="{{ url('/reviews') }}" class="buzz-view-all-btn">
                        <span>ALL REVIEWS</span>
                        <span class="buzz-btn-arrow">&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Reviews Grid -->
            <div class="row g-4 justify-content-center">
                @forelse($reviews as $review)
                    <div class="col-lg-4 col-md-6 d-flex">
                        <article class="buzz-luxury-card flex-fill" itemscope itemtype="https://schema.org/Review">
                            <!-- Card Top Bar: User & Verified Badge -->
                            <div class="buzz-card-top">
                                <div class="buzz-user-meta">
                                    <div class="buzz-avatar-wrap">
                                        @if (!empty($review['reviewer_image_url']))
                                            <img src="{{ $review['reviewer_image_url'] }}"
                                                alt="{{ $review['reviewer_name'] ?? 'Customer' }}" 
                                                loading="lazy" width="46" height="46" 
                                                class="buzz-user-avatar"
                                                onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($review['reviewer_name'] ?? 'Customer') }}&background=00285a&color=fff&size=92';">
                                        @else
                                            <div class="buzz-avatar-initials">
                                                {{ strtoupper(substr($review['reviewer_name'] ?? 'C', 0, 2)) }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="buzz-user-details">
                                        <h3 class="buzz-user-name" itemprop="author" itemscope itemtype="https://schema.org/Person">
                                            <span itemprop="name">{{ $review['reviewer_name'] ?? 'Verified Customer' }}</span>
                                        </h3>
                                        <div class="buzz-stars" itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                                            <meta itemprop="ratingValue" content="{{ $review['rating'] ?? 5 }}">
                                            <meta itemprop="bestRating" content="5">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <span class="buzz-star-inline {{ $i <= ($review['rating'] ?? 5) ? 'active' : 'inactive' }}">&#9733;</span>
                                            @endfor
                                            <span class="buzz-rating-digit ms-1">{{ number_format($review['rating'] ?? 5, 1) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <span class="buzz-verified-tag">
                                    Verified Buyer
                                </span>
                            </div>

                            <!-- Review Headline / Title -->
                            @if (!empty($review['title']))
                                <h4 class="buzz-review-title">"{{ $review['title'] }}"</h4>
                            @endif

                            <!-- Review Body -->
                            <div class="buzz-card-body" itemprop="reviewBody">
                                <p class="buzz-review-quote">{{ $review['comment'] ?? 'Absolutely love the quality and aesthetic of this piece. Fits true to size and the fabric feels ultra premium.' }}</p>
                            </div>

                            <!-- Card Footer: Tagged Product & Engagement -->
                            <div class="buzz-card-footer mt-auto">
                                @if (!empty($review['product_tag']))
                                    <div class="buzz-product-pill-wrap">
                                        <a href="{{ route('shop.index') }}?search={{ urlencode($review['product_tag']) }}"
                                            class="buzz-product-pill">
                                            <span class="text-truncate" style="max-width: 190px;">{{ $review['product_tag'] }}</span>
                                        </a>
                                    </div>
                                @else
                                    <div class="buzz-product-pill-wrap">
                                        <span class="buzz-product-pill text-muted">
                                            <span>Heavyweight 240 GSM</span>
                                        </span>
                                    </div>
                                @endif

                                <div class="buzz-footer-meta">
                                    <time class="buzz-date-text"
                                        datetime="{{ isset($review['created_at']) ? date('c', strtotime($review['created_at'])) : date('c') }}"
                                        itemprop="datePublished">
                                        {{ isset($review['created_at']) ? \Carbon\Carbon::parse($review['created_at'])->diffForHumans() : 'Recently' }}
                                    </time>
                                </div>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="p-5 rounded-4 bg-white shadow-sm border border-light max-w-500 mx-auto">
                            <h5 class="fw-bold text-dark">Be the Trend Pioneer</h5>
                            <p class="text-muted fs-14 mb-4">No reviews featured yet. Shop your favorite piece and be the first to share your vibe.</p>
                            <a href="{{ route('shop.index') }}" class="section-btn">
                                Browse Collection &rarr;
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Brand Commitments / Trust Strip -->
            <div class="buzz-trust-strip mt-5">
                <div class="row g-3 g-lg-4 text-center justify-content-center">
                    <div class="col-6 col-lg-3">
                        <div class="buzz-trust-card">
                            <div class="buzz-trust-icon-wrap">
                                <i class="bi bi-truck"></i>
                            </div>
                            <span class="buzz-trust-badge">48H</span>
                            <h4 class="buzz-trust-val">DISPATCH IN 48 HOURS</h4>
                            <p class="buzz-trust-lbl">Orders processed & shipped within 48 hours.</p>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="buzz-trust-card">
                            <div class="buzz-trust-icon-wrap">
                                <i class="bi bi-credit-card-2-front"></i>
                            </div>
                            <span class="buzz-trust-badge">5%</span>
                            <h4 class="buzz-trust-val">5% PREPAID OFF</h4>
                            <p class="buzz-trust-lbl">Instant 5% discount on all online payments.</p>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="buzz-trust-card">
                            <div class="buzz-trust-icon-wrap">
                                <i class="bi bi-patch-check"></i>
                            </div>
                            <span class="buzz-trust-badge">100%</span>
                            <h4 class="buzz-trust-val">MADE IN INDIA</h4>
                            <p class="buzz-trust-lbl">Crafted locally from pure combed cotton.</p>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="buzz-trust-card">
                            <div class="buzz-trust-icon-wrap">
                                <i class="bi bi-gem"></i>
                            </div>
                            <span class="buzz-trust-badge">240+</span>
                            <h4 class="buzz-trust-val">LUXURY STREETWEAR</h4>
                            <p class="buzz-trust-lbl">Heavyweight fabric at accessible pricing.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Custom Scoped Styles for THE VIBE and THE BUZZ / REAL TALK Sections -->
    <style>
        /* ══════════════════════════════════════════════════
           SECTION 9: LATEST NEWS
        ══════════════════════════════════════════════════ */
        .vibe-section {
            background: #ffffff;
            padding: 70px 0 65px;
        }
        .vibe-header {
            gap: 20px;
        }
        .vibe-title {
            font-size: clamp(1.85rem, 3.2vw, 2.35rem);
            font-weight: 800;
            color: #00285a;
            letter-spacing: -0.01em;
            line-height: 1.2;
            margin: 0;
        }
        .vibe-subtitle {
            font-size: 0.95rem;
            color: #64748b;
            margin: 6px 0 0;
            line-height: 1.5;
            font-weight: 400;
        }
        .vibe-all-posts-link {
            font-size: 0.92rem;
            font-weight: 700;
            color: #00285a;
            text-decoration: underline;
            text-underline-offset: 4px;
            transition: opacity 0.2s ease, color 0.2s ease;
        }
        .vibe-all-posts-link:hover {
            color: #001a3b;
            opacity: 0.85;
        }

        /* Vibe News Card (Matches Screenshot Editorial Layout) */
        .vibe-news-card {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .vibe-news-thumb-link {
            display: block;
            text-decoration: none;
            overflow: hidden;
            border-radius: 6px;
            cursor: pointer;
        }
        .vibe-news-img-wrap {
            position: relative;
            aspect-ratio: 16 / 10;
            width: 100%;
            overflow: hidden;
            border-radius: 6px;
            background: #0f172a;
        }
        .vibe-news-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.45s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .vibe-news-card:hover .vibe-news-img {
            transform: scale(1.04);
        }
        .vibe-news-content {
            padding-top: 16px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .vibe-news-date {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 8px;
            display: block;
        }
        .vibe-news-title {
            font-size: 1.25rem;
            font-weight: 800;
            line-height: 1.35;
            margin: 0 0 10px;
        }
        .vibe-news-title a {
            color: #00285a;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .vibe-news-title a:hover {
            color: #0a3d7c;
        }
        .vibe-news-excerpt {
            font-size: 0.88rem;
            line-height: 1.6;
            color: #475569;
            margin: 0 0 14px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Read More Option */
        .vibe-news-readmore-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: transparent;
            border: none;
            padding: 0;
            font-size: 0.88rem;
            font-weight: 700;
            color: #00285a;
            cursor: pointer;
            margin-top: auto;
            text-decoration: underline;
            text-underline-offset: 3px;
            transition: color 0.2s ease, gap 0.2s ease;
        }
        .vibe-news-readmore-btn:hover {
            color: #0a3d7c;
            gap: 10px;
        }
        .vibe-news-readmore-btn .readmore-arrow {
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.2s ease;
        }
        .vibe-news-readmore-btn:hover .readmore-arrow {
            transform: translateX(3px);
        }

        /* Article / Story Reader Modal */
        .vibe-modal-dialog {
            max-width: 720px;
        }
        .vibe-modal-content {
            border-radius: 18px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 24px 60px rgba(0, 40, 90, 0.18);
            overflow: hidden;
        }
        .vibe-modal-header {
            background: #ffffff;
            padding: 20px 28px 12px;
        }
        .vibe-modal-badge {
            display: inline-block;
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #00285a;
            background: rgba(0, 40, 90, 0.06);
            padding: 4px 12px;
            border-radius: 999px;
        }
        .vibe-modal-body {
            padding: 12px 28px 28px;
        }
        .vibe-modal-img-wrap {
            width: 100%;
            aspect-ratio: 16 / 9;
            border-radius: 10px;
            overflow: hidden;
            background: #0f172a;
            margin-bottom: 20px;
        }
        .vibe-modal-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .vibe-modal-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }
        .vibe-modal-date {
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #94a3b8;
        }
        .vibe-modal-readtime {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #00285a;
            background: #f1f5f9;
            padding: 2px 10px;
            border-radius: 999px;
        }
        .vibe-modal-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: #00285a;
            line-height: 1.3;
            margin-bottom: 14px;
        }
        .vibe-modal-text {
            font-size: 0.95rem;
            line-height: 1.75;
            color: #334155;
            margin-bottom: 24px;
        }
        .vibe-modal-footer-btns {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding-top: 18px;
            border-top: 1px solid #f1f5f9;
            flex-wrap: wrap;
        }
        .vibe-modal-cta {
            background: #00285a;
            color: #ffffff;
            font-size: 0.85rem;
            font-weight: 700;
            padding: 10px 22px;
            border-radius: 999px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s ease;
        }
        .vibe-modal-cta:hover {
            background: #001a3b;
            color: #ffffff;
        }
        .vibe-modal-close-btn {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #475569;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 8px 18px;
            border-radius: 999px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .vibe-modal-close-btn:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        /* ══════════════════════════════════════════════════
           SECTION 10: THE BUZZ / REAL TALK
        ══════════════════════════════════════════════════ */
        .buzz-section {
            background: #f8fafc;
            padding: 85px 0 75px;
            border-top: 1px solid #eef2f6;
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
            letter-spacing: 0.14em;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 999px;
            background: #00285a;
            color: #ffffff;
        }
        .buzz-verified-count-pill {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #00285a;
            background: rgba(0, 40, 90, 0.06);
            padding: 5px 14px;
            border-radius: 999px;
            border: 1px solid rgba(0, 40, 90, 0.12);
        }
        .buzz-section-title {
            font-family: 'Cinzel', serif, -apple-system, sans-serif;
            font-size: clamp(2rem, 3.8vw, 2.75rem);
            font-weight: 900;
            color: #00285a;
            letter-spacing: 0.04em;
            line-height: 1.15;
            margin: 8px 0 10px;
        }
        .buzz-accent-dot {
            color: #d4af37;
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
            gap: 16px;
            flex-wrap: wrap;
        }
        .buzz-rating-summary-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 12px 20px;
            border-radius: 16px;
            box-shadow: 0 4px 14px rgba(0, 40, 90, 0.04);
            text-align: center;
        }
        .buzz-stars-row {
            font-size: 1rem;
            line-height: 1;
            margin-bottom: 4px;
            color: #d4af37;
            letter-spacing: 2px;
        }
        .buzz-star {
            color: #d4af37;
        }
        .buzz-rating-numbers {
            display: flex;
            align-items: baseline;
            justify-content: center;
            gap: 3px;
        }
        .buzz-rating-score {
            font-size: 1.25rem;
            font-weight: 900;
            color: #00285a;
        }
        .buzz-rating-max {
            font-size: 0.8rem;
            color: #94a3b8;
            font-weight: 600;
        }
        .buzz-review-tally {
            font-size: 0.72rem;
            color: #64748b;
            font-weight: 600;
            letter-spacing: 0.02em;
        }
        .buzz-view-all-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #00285a;
            color: #ffffff;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 14px 24px;
            border-radius: 999px;
            text-decoration: none;
            transition: all 0.25s ease;
            box-shadow: 0 6px 18px rgba(0, 40, 90, 0.14);
        }
        .buzz-view-all-btn:hover {
            background: #001a3b;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(0, 40, 90, 0.22);
        }
        .buzz-btn-arrow {
            font-size: 1rem;
            line-height: 1;
            transition: transform 0.2s ease;
        }
        .buzz-view-all-btn:hover .buzz-btn-arrow {
            transform: translateX(4px);
        }

        /* Luxury Review Card */
        .buzz-luxury-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 28px;
            position: relative;
            display: flex;
            flex-direction: column;
            box-shadow: 0 8px 24px rgba(0, 40, 90, 0.04);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s ease;
        }
        .buzz-luxury-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 36px rgba(0, 40, 90, 0.08);
            border-color: #cbd5e1;
        }
        .buzz-card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 16px;
        }
        .buzz-user-meta {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .buzz-avatar-wrap {
            position: relative;
            width: 46px;
            height: 46px;
            flex-shrink: 0;
        }
        .buzz-user-avatar {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 1.5px solid #e2e8f0;
        }
        .buzz-avatar-initials {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: #00285a;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.88rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1.5px solid #e2e8f0;
        }
        .buzz-user-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 3px;
        }
        .buzz-stars {
            display: flex;
            align-items: center;
            line-height: 1;
        }
        .buzz-star-inline {
            font-size: 0.85rem;
            color: #d4af37;
            margin-right: 1px;
        }
        .buzz-star-inline.inactive {
            color: #cbd5e1;
        }
        .buzz-rating-digit {
            font-size: 0.74rem;
            font-weight: 700;
            color: #64748b;
        }
        .buzz-verified-tag {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #00285a;
            background: rgba(0, 40, 90, 0.05);
            padding: 4px 10px;
            border-radius: 999px;
            border: 1px solid rgba(0, 40, 90, 0.1);
            white-space: nowrap;
        }
        .buzz-review-title {
            font-size: 0.98rem;
            font-weight: 700;
            color: #00285a;
            margin: 0 0 10px;
            line-height: 1.35;
        }
        .buzz-card-body {
            margin-bottom: 20px;
        }
        .buzz-review-quote {
            font-size: 0.91rem;
            line-height: 1.65;
            color: #334155;
            margin: 0;
        }
        .buzz-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding-top: 14px;
            border-top: 1px solid #f1f5f9;
        }
        .buzz-product-pill {
            display: inline-block;
            font-size: 0.74rem;
            font-weight: 600;
            color: #00285a;
            background: #f1f5f9;
            padding: 5px 12px;
            border-radius: 999px;
            text-decoration: none;
            transition: background 0.2s ease;
        }
        .buzz-product-pill:hover {
            background: #e2e8f0;
            color: #00285a;
        }
        .buzz-date-text {
            font-size: 0.74rem;
            color: #94a3b8;
            font-weight: 500;
        }

        /* Trust Strip Cards */
        .buzz-trust-strip {
            margin-top: 48px;
            padding-top: 36px;
            border-top: 1px solid #e2e8f0;
        }
        .buzz-trust-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 26px 18px;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        }
        .buzz-trust-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 24px rgba(0, 40, 90, 0.08);
            border-color: #cbd5e1;
        }
        .buzz-trust-icon-wrap {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: rgba(0, 40, 90, 0.06);
            color: #00285a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.45rem;
            margin-bottom: 12px;
            transition: all 0.25s ease;
        }
        .buzz-trust-card:hover .buzz-trust-icon-wrap {
            background: #00285a;
            color: #ffffff;
            transform: scale(1.08);
        }
        .buzz-trust-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 999px;
            background: #f1f5f9;
            color: #00285a;
            font-weight: 800;
            font-size: 0.72rem;
            letter-spacing: 0.04em;
            margin-bottom: 10px;
        }
        .buzz-trust-val {
            font-size: 0.86rem;
            font-weight: 800;
            color: #00285a;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin: 0 0 6px;
            line-height: 1.25;
        }
        .buzz-trust-lbl {
            font-size: 0.8rem;
            color: #64748b;
            margin: 0;
            line-height: 1.45;
        }

        @media (max-width: 991px) {
            .vibe-media-wrapper {
                height: 420px;
            }
        }
        @media (max-width: 768px) {
            .vibe-section {
                padding: 60px 0 50px;
            }
            .buzz-section {
                padding: 60px 0 50px;
            }
            .vibe-header, .buzz-header-row {
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
            .buzz-trust-card {
                padding: 18px 12px;
            }
            .buzz-trust-val {
                font-size: 0.8rem;
            }
            .buzz-trust-lbl {
                font-size: 0.74rem;
            }
        }

        /* ══════════════════════════════════════════════════
           SECTION: INSTAGRAM SHOPPABLE REELS
        ══════════════════════════════════════════════════ */
        .reels-section {
            background: #ffffff;
            padding: 75px 0 65px;
            position: relative;
            overflow: hidden;
            border-top: 1px solid #f1f5f9;
        }
        .reels-eyebrow {
            display: inline-block;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #00285a;
            margin-bottom: 8px;
        }
        .reels-title {
            font-family: 'Cinzel', serif, -apple-system, sans-serif;
            font-size: clamp(2rem, 3.8vw, 2.75rem);
            font-weight: 900;
            color: #00285a;
            letter-spacing: 0.04em;
            line-height: 1.15;
            margin: 0 0 6px;
        }
        .reels-dot {
            color: #00285a;
        }
        .reels-subtitle {
            font-size: 0.95rem;
            color: #64748b;
            margin: 0;
            line-height: 1.5;
        }
        .reels-ig-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            color: #00285a;
            text-decoration: none;
            padding: 8px 18px;
            border-radius: 999px;
            background: rgba(0, 40, 90, 0.05);
            border: 1px solid rgba(0, 40, 90, 0.1);
            transition: all 0.25s ease;
        }
        .reels-ig-link:hover {
            background: #00285a;
            color: #ffffff;
        }
        .reels-ig-link i {
            font-size: 0.95rem;
        }

        /* Carousel Track */
        .reels-carousel-container {
            position: relative;
            padding: 10px 0;
            display: flex;
            align-items: center;
        }
        .reels-track {
            display: flex;
            gap: 14px;
            overflow-x: auto;
            scroll-behavior: smooth;
            scroll-snap-type: x mandatory;
            padding: 10px 0 20px;
            margin: 0 54px; /* STRICT: Videos are framed completely inside between the left and right arrows */
            width: calc(100% - 108px);
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .reels-track::-webkit-scrollbar {
            display: none;
        }

        /* Nav Arrows */
        .reels-arrow-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 18px rgba(0, 40, 90, 0.15);
            color: #00285a;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: all 0.25s ease;
        }
        .reels-arrow-btn:hover {
            background: #00285a;
            color: #ffffff;
            border-color: #00285a;
            box-shadow: 0 6px 22px rgba(0, 40, 90, 0.25);
        }
        .reels-arrow-btn.is-disabled {
            opacity: 0.35;
            cursor: not-allowed;
            pointer-events: none;
        }
        .reels-arrow-prev {
            left: 0;
        }
        .reels-arrow-next {
            right: 0;
        }

        /* Reel Item */
        .reel-item {
            flex: 0 0 calc((100% - (4 * 14px)) / 5);
            min-width: 190px;
            scroll-snap-align: start;
        }
        @media (min-width: 1400px) {
            .reel-item {
                flex: 0 0 calc((100% - (5 * 14px)) / 6);
                min-width: 180px;
            }
        }
        @media (max-width: 1199px) {
            .reel-item {
                flex: 0 0 calc((100% - (3 * 14px)) / 4);
                min-width: 190px;
            }
        }
        @media (max-width: 991px) {
            .reel-item {
                flex: 0 0 calc((100% - (2 * 12px)) / 3);
                min-width: 190px;
            }
        }
        @media (max-width: 768px) {
            .reels-track {
                margin: 0;
                width: 100%;
                padding: 10px 10px 20px;
            }
            .reel-item {
                flex: 0 0 65%;
                min-width: 200px;
            }
            .reels-arrow-btn {
                display: none !important;
            }
        }

        .reel-media-box {
            position: relative;
            aspect-ratio: 9 / 16;
            width: 100%;
            border-radius: 5px;
            overflow: hidden;
            background: #0f172a;
            box-shadow: 0 10px 28px rgba(0, 40, 90, 0.08);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
        }
        .reel-item:hover .reel-media-box {
            transform: translateY(-4px);
            box-shadow: 0 16px 36px rgba(0, 40, 90, 0.16);
        }
        .reel-video-element {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            cursor: pointer;
        }
        .reel-vignette {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                180deg,
                rgba(0, 0, 0, 0.3) 0%,
                rgba(0, 0, 0, 0) 35%,
                rgba(0, 0, 0, 0.1) 60%,
                rgba(0, 0, 0, 0.6) 100%
            );
            pointer-events: none;
        }
        .reel-top-bar {
            position: absolute;
            top: 14px;
            left: 14px;
            right: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 2;
        }
        .reel-creator-handle {
            font-size: 0.75rem;
            font-weight: 700;
            color: #ffffff;
            text-shadow: 0 1px 3px rgba(0,0,0,0.6);
            letter-spacing: 0.02em;
        }
        .reel-sound-toggle {
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(6px);
            border: none;
            color: #ffffff;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            cursor: pointer;
            transition: background 0.2s ease;
        }
        .reel-sound-toggle:hover {
            background: rgba(0, 0, 0, 0.7);
        }

        /* The Shoppable Pill (Bottom center, matching reference screenshot) */
        .reel-shoppable-pill {
            position: absolute;
            bottom: 16px;
            left: 50%;
            transform: translateX(-50%);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(243, 244, 246, 0.94);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            padding: 5px 12px 5px 6px;
            border-radius: 999px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.22);
            cursor: pointer;
            z-index: 5;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reel-shoppable-pill:hover,
        .reel-shoppable-pill.is-active {
            background: #ffffff;
            transform: translateX(-50%) scale(1.05);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        }
        .reel-pill-thumbs {
            display: flex;
            align-items: center;
        }
        .reel-pill-thumb {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            background: #ffffff;
        }
        .reel-pill-thumb.thumb-1 {
            margin-left: -12px;
        }
        .reel-pill-caret {
            font-size: 0.75rem;
            color: #374151;
            display: flex;
            align-items: center;
            transition: transform 0.25s ease;
        }
        .reel-shoppable-pill.is-active .reel-pill-caret {
            transform: rotate(180deg);
        }

        /* Product Drawer (Pops up above the pill) */
        .reel-product-drawer {
            position: absolute;
            bottom: 68px;
            left: 10px;
            right: 10px;
            background: #ffffff;
            border-radius: 16px;
            padding: 12px;
            box-shadow: 0 16px 36px rgba(0, 20, 50, 0.28);
            border: 1px solid #e2e8f0;
            z-index: 10;
            opacity: 0;
            visibility: hidden;
            transform: translateY(12px);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            pointer-events: none;
        }
        .reel-product-drawer.is-open {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
        }
        .reel-drawer-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 1px solid #f1f5f9;
        }
        .reel-drawer-heading {
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #64748b;
        }
        .reel-drawer-close {
            background: none;
            border: none;
            font-size: 1.1rem;
            line-height: 1;
            color: #94a3b8;
            cursor: pointer;
            padding: 0;
        }
        .reel-drawer-close:hover {
            color: #0f172a;
        }
        .reel-drawer-products {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .reel-drawer-product-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .reel-drawer-img {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #e2e8f0;
            flex-shrink: 0;
        }
        .reel-drawer-info {
            flex: 1;
            min-width: 0;
        }
        .reel-drawer-title {
            font-size: 0.78rem;
            font-weight: 700;
            color: #00285a;
            margin: 0 0 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .reel-drawer-price-wrap {
            display: flex;
            align-items: baseline;
            gap: 6px;
        }
        .reel-drawer-price {
            font-size: 0.8rem;
            font-weight: 800;
            color: #00285a;
        }
        .reel-drawer-strike {
            font-size: 0.72rem;
            color: #94a3b8;
            text-decoration: line-through;
        }
        .reel-drawer-shop-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 12px;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #ffffff;
            background: #00285a;
            border-radius: 999px;
            text-decoration: none;
            white-space: nowrap;
            transition: background 0.2s ease;
        }
        .reel-drawer-shop-btn:hover {
            background: #001a3b;
            color: #ffffff;
        }

        /* Progress Bar */
        .reels-progress-wrap {
            width: 140px;
            height: 3px;
            background: #e2e8f0;
            border-radius: 999px;
            overflow: hidden;
        }
        .reels-progress-bar {
            width: 25%;
            height: 100%;
            background: #00285a;
            border-radius: 999px;
            transition: width 0.15s ease;
        }

        /* ══════════════════════════════════════════════════
           REEL LIGHTBOX MODAL (EXACT MATCH TO SCREENSHOT)
        ══════════════════════════════════════════════════ */
        .reel-modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.3s ease;
        }
        .reel-modal-overlay.is-active {
            opacity: 1;
            visibility: visible;
        }
        .reel-modal-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.82);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        .reel-modal-container {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 28px;
            width: 100%;
            max-width: 650px;
            padding: 20px;
        }
        .reel-modal-card {
            position: relative;
            width: 360px;
            max-width: 90vw;
            aspect-ratio: 9 / 16;
            max-height: 86vh;
            border-radius: 18px;
            overflow: hidden;
            background: #000000;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.65), 0 0 0 1px rgba(255, 255, 255, 0.1);
            transform: scale(0.92);
            transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reel-modal-overlay.is-active .reel-modal-card {
            transform: scale(1);
        }
        .reel-modal-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            cursor: pointer;
        }

        /* Top Controls: Sound & Close */
        .reel-modal-top-actions {
            position: absolute;
            top: 16px;
            right: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 10;
        }
        .reel-glass-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .reel-glass-btn:hover {
            background: rgba(0, 0, 0, 0.75);
            transform: scale(1.08);
            border-color: rgba(255, 255, 255, 0.4);
        }

        /* Right Floating Actions: Like & Share */
        .reel-modal-right-actions {
            position: absolute;
            right: 16px;
            bottom: 125px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 18px;
            z-index: 10;
        }
        .reel-action-btn {
            background: transparent;
            border: none;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            cursor: pointer;
            padding: 0;
            text-shadow: 0 2px 6px rgba(0,0,0,0.6);
            transition: transform 0.2s ease;
        }
        .reel-action-btn:hover {
            transform: scale(1.1);
        }
        .reel-action-icon-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.2s ease;
        }
        .reel-action-btn:hover .reel-action-icon-circle {
            background: rgba(0, 0, 0, 0.7);
        }
        .reel-action-btn.is-liked .reel-action-icon-circle i {
            color: #ef4444;
            animation: heartPulse 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes heartPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.35); }
            100% { transform: scale(1); }
        }
        .reel-action-count, .reel-action-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.02em;
        }

        /* Bottom Floating Shoppable Product Card Dock */
        .reel-modal-product-dock {
            position: absolute;
            bottom: 14px;
            left: 12px;
            right: 12px;
            z-index: 10;
        }
        .reel-modal-product-track {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scrollbar-width: none;
            -ms-overflow-style: none;
            padding-bottom: 2px;
        }
        .reel-modal-product-track::-webkit-scrollbar {
            display: none;
        }
        .reel-modal-product-card {
            flex: 0 0 calc(100% - 30px);
            min-width: 260px;
            background: #ffffff;
            border-radius: 14px;
            padding: 9px 11px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.8);
            scroll-snap-align: start;
            transition: transform 0.2s ease;
        }
        .reel-modal-product-card.peek-next {
            flex: 0 0 54px;
            min-width: 54px;
            padding: 6px;
            overflow: hidden;
            justify-content: center;
            cursor: pointer;
            opacity: 0.88;
        }
        .reel-modal-product-card.peek-next:hover {
            opacity: 1;
        }
        .reel-modal-card-img {
            width: 54px;
            height: 64px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #e2e8f0;
            flex-shrink: 0;
            background: #f8fafc;
        }
        .reel-modal-card-info {
            flex: 1;
            min-width: 0;
        }
        .reel-modal-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
            margin-bottom: 3px;
        }
        .reel-modal-card-title {
            font-size: 0.8rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .reel-modal-card-link {
            color: #64748b;
            font-size: 0.8rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: color 0.2s ease;
        }
        .reel-modal-card-link:hover {
            color: #00285a;
        }
        .reel-modal-card-prices {
            display: flex;
            align-items: baseline;
            gap: 6px;
            margin-bottom: 6px;
        }
        .reel-modal-card-price {
            font-size: 0.85rem;
            font-weight: 800;
            color: #0f172a;
        }
        .reel-modal-card-strike {
            font-size: 0.72rem;
            color: #94a3b8;
            text-decoration: line-through;
        }
        .reel-modal-card-discount {
            font-size: 0.7rem;
            font-weight: 700;
            color: #16a34a;
        }
        .reel-modal-card-btn {
            width: 100%;
            background: #1e293b;
            color: #ffffff;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }
        .reel-modal-card-btn:hover {
            background: #00285a;
        }
        .reel-modal-card-btn:disabled {
            opacity: 0.75;
            cursor: not-allowed;
        }

        /* ── Reel Quick-Buy Collapsible Drawer (Exact Match to Screenshot) ── */
        .reel-quickbuy-drawer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: #ffffff;
            border-radius: 20px 20px 0 0;
            padding: 14px 16px 16px 16px;
            z-index: 30;
            box-shadow: 0 -12px 35px rgba(0, 0, 0, 0.45);
            transform: translateY(105%);
            transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.25s ease;
            opacity: 0;
            pointer-events: none;
            display: flex;
            flex-direction: column;
            color: #0f172a;
        }
        .reel-quickbuy-drawer.is-open {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }

        /* Top Thumbnails Bar & Close Button */
        .reel-drawer-topbar {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 10px;
        }
        .reel-drawer-thumbs {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: nowrap;
            overflow-x: auto;
            scrollbar-width: none;
        }
        .reel-drawer-thumbs::-webkit-scrollbar {
            display: none;
        }
        .reel-drawer-thumb-img {
            width: 58px;
            height: 72px;
            border-radius: 8px;
            object-fit: cover;
            border: 1.5px solid #e2e8f0;
            background: #f8fafc;
            flex-shrink: 0;
            cursor: pointer;
            transition: transform 0.15s ease, border-color 0.15s ease;
        }
        .reel-drawer-thumb-img:hover,
        .reel-drawer-thumb-img.is-active {
            border-color: #0f172a;
            transform: scale(1.03);
        }
        .reel-drawer-close {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #f1f5f9;
            border: none;
            color: #475569;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            cursor: pointer;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }
        .reel-drawer-close:hover {
            background: #e2e8f0;
            color: #0f172a;
            transform: scale(1.08);
        }

        /* Product Title & External Link */
        .reel-drawer-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 4px;
        }
        .reel-drawer-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .reel-drawer-link {
            color: #64748b;
            font-size: 0.85rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: color 0.2s ease;
        }
        .reel-drawer-link:hover {
            color: #00285a;
        }

        /* Pricing */
        .reel-drawer-pricing {
            display: flex;
            align-items: baseline;
            gap: 8px;
            margin-bottom: 12px;
        }
        .reel-drawer-price {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
        }
        .reel-drawer-strike {
            font-size: 0.85rem;
            color: #94a3b8;
            text-decoration: line-through;
        }
        .reel-drawer-discount {
            font-size: 0.8rem;
            font-weight: 700;
            color: #16a34a;
        }

        /* Size Section */
        .reel-drawer-size-section {
            margin-bottom: 14px;
        }
        .reel-drawer-size-title {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 6px;
        }
        .reel-drawer-size-pills {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .reel-drawer-size-pill {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 1.5px solid #cbd5e1;
            background: #ffffff;
            color: #0f172a;
            font-size: 0.78rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
        }
        .reel-drawer-size-pill:hover {
            border-color: #0f172a;
        }
        .reel-drawer-size-pill.is-selected {
            background: #1e293b;
            border-color: #1e293b;
            color: #ffffff;
            box-shadow: 0 3px 8px rgba(30, 41, 59, 0.3);
        }

        /* Action Buttons Row */
        .reel-drawer-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .reel-drawer-action-btn {
            flex: 1;
            height: 42px;
            border-radius: 25px;
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .reel-drawer-btn-cart {
            background: #1e293b;
            border: none;
            color: #ffffff;
            box-shadow: 0 3px 10px rgba(30, 41, 59, 0.25);
        }
        .reel-drawer-btn-cart:hover {
            background: #0f172a;
            transform: translateY(-1px);
        }
        .reel-drawer-btn-cart:disabled {
            opacity: 0.75;
            cursor: not-allowed;
            transform: none;
        }
        .reel-drawer-btn-buy {
            background: #ffffff;
            border: 1.5px solid #1e293b;
            color: #1e293b;
        }
        .reel-drawer-btn-buy:hover {
            background: #f8fafc;
            transform: translateY(-1px);
        }
        .reel-drawer-btn-buy:disabled {
            opacity: 0.75;
            cursor: not-allowed;
            transform: none;
        }
        .reel-drawer-bag-link {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            background: #ffffff;
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            font-size: 1.15rem;
            text-decoration: none;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }
        .reel-drawer-bag-link:hover {
            border-color: #0f172a;
            color: #00285a;
        }
        .reel-drawer-bag-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: #0f172a;
            color: #ffffff;
            font-size: 0.65rem;
            font-weight: 800;
            min-width: 18px;
            height: 18px;
            border-radius: 9px;
            padding: 0 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ffffff;
            line-height: 1;
        }

        /* Prev / Next Modal Arrows */
        .reel-modal-nav-arrow {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #ffffff;
            border: none;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.35);
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            cursor: pointer;
            transition: all 0.25s ease;
            flex-shrink: 0;
        }
        .reel-modal-nav-arrow:hover {
            background: #00285a;
            color: #ffffff;
            transform: scale(1.1);
        }
        @media (max-width: 640px) {
            .reel-modal-container {
                padding: 0;
                gap: 0;
            }
            .reel-modal-card {
                width: 100vw;
                height: 100vh;
                max-width: 100vw;
                max-height: 100vh;
                border-radius: 0;
            }
            .reel-modal-nav-arrow {
                display: none;
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
                    var slideDuration = 5000;
                    var isHovered = false;

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
                        clearTimeout(heroTimer);

                        heroIndex = (nextIndex + heroSlides.length) % heroSlides.length;

                        // Switch slides
                        heroSlides.forEach(function(slide, index) {
                            var isActive = index === heroIndex;
                            slide.classList.toggle('active', isActive);
                            slide.setAttribute('aria-hidden', isActive ? 'false' : 'true');
                        });

                        // Switch bullet dots and restart circular progress
                        heroDots.forEach(function(dot, index) {
                            var isActive = index === heroIndex;
                            dot.classList.toggle('active', isActive);
                            dot.classList.remove('animating');

                            if (isActive) {
                                void dot.offsetWidth; // Force DOM reflow to reset CSS keyframe animation
                                dot.classList.add('animating');
                            }
                        });

                        syncHeroMedia();
                        scheduleNext();
                    }

                    function scheduleNext() {
                        clearTimeout(heroTimer);
                        if (heroSlides.length < 2) return;
                        heroTimer = setTimeout(function() {
                            if (!isHovered) {
                                showHeroSlide(heroIndex + 1);
                            } else {
                                scheduleNext();
                            }
                        }, slideDuration);
                    }

                    // Pause timer on hover
                    heroSlider.addEventListener('mouseenter', function() {
                        isHovered = true;
                    });

                    heroSlider.addEventListener('mouseleave', function() {
                        isHovered = false;
                    });

                    if (heroPrev) {
                        heroPrev.addEventListener('click', function() {
                            showHeroSlide(heroIndex - 1);
                        });
                    }

                    if (heroNext) {
                        heroNext.addEventListener('click', function() {
                            showHeroSlide(heroIndex + 1);
                        });
                    }

                    heroDots.forEach(function(dot) {
                        dot.addEventListener('click', function() {
                            showHeroSlide(parseInt(dot.dataset.heroDot, 10) || 0);
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
                        } else if (touchEndX - touchStartX > 50) {
                            showHeroSlide(heroIndex - 1);
                        }
                    }, { passive: true });

                    showHeroSlide(0);
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
                // ─────────────────────────────────────────────────────────
                // Instagram Shoppable Reels Carousel Controller
                // ─────────────────────────────────────────────────────────
                (function() {
                    var reelsSection = document.querySelector('.reels-section');
                    if (!reelsSection) return;

                    var track = reelsSection.querySelector('[data-reels-track]');
                    var prevBtn = reelsSection.querySelector('[data-reels-arrow="prev"]');
                    var nextBtn = reelsSection.querySelector('[data-reels-arrow="next"]');
                    var progressBar = reelsSection.querySelector('[data-reels-progress]');
                    var cards = reelsSection.querySelectorAll('[data-reel-card]');

                    // Arrow scrolling
                    if (prevBtn && track) {
                        prevBtn.addEventListener('click', function() {
                            var scrollAmount = cards[0] ? (cards[0].offsetWidth + 16) * 2 : 300;
                            track.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
                        });
                    }
                    if (nextBtn && track) {
                        nextBtn.addEventListener('click', function() {
                            var scrollAmount = cards[0] ? (cards[0].offsetWidth + 16) * 2 : 300;
                            track.scrollBy({ left: scrollAmount, behavior: 'smooth' });
                        });
                    }

                    // Update arrow disabled state
                    function updateArrowState() {
                        if (!track || !prevBtn || !nextBtn) return;
                        var maxScroll = track.scrollWidth - track.clientWidth;
                        if (track.scrollLeft <= 5) {
                            prevBtn.classList.add('is-disabled');
                        } else {
                            prevBtn.classList.remove('is-disabled');
                        }
                        if (maxScroll <= 0 || track.scrollLeft >= maxScroll - 5) {
                            nextBtn.classList.add('is-disabled');
                        } else {
                            nextBtn.classList.remove('is-disabled');
                        }
                    }

                    // Progress bar sync & arrow updates
                    if (track) {
                        track.addEventListener('scroll', function() {
                            var maxScroll = track.scrollWidth - track.clientWidth;
                            if (maxScroll > 0 && progressBar) {
                                var pct = Math.max(15, Math.min(100, ((track.scrollLeft / maxScroll) * 85) + 15));
                                progressBar.style.width = pct + '%';
                            }
                            updateArrowState();
                        }, { passive: true });

                        window.addEventListener('resize', updateArrowState);
                        setTimeout(updateArrowState, 200);
                    }

                    // ─────────────────────────────────────────────────────────
                    // Instagram Reel Lightbox Modal Controller (Matches Screenshot)
                    // ─────────────────────────────────────────────────────────
                    var reelsData = @json($instagramReels ?? []);

                    var modalOverlay = document.getElementById('reelLightboxModal');
                    var modalVideo = document.getElementById('reelModalVideo');
                    var modalCloseBtn = document.getElementById('reelModalCloseBtn');
                    var modalSoundBtn = document.getElementById('reelModalSoundBtn');
                    var modalSoundIcon = document.getElementById('reelModalSoundIcon');
                    var modalBackdrop = document.getElementById('reelModalBackdrop');
                    var modalPrevBtn = document.getElementById('reelModalPrevBtn');
                    var modalNextBtn = document.getElementById('reelModalNextBtn');
                    var modalLikeBtn = document.getElementById('reelModalLikeBtn');
                    var modalLikeIcon = document.getElementById('reelModalLikeIcon');
                    var modalLikeCount = document.getElementById('reelModalLikeCount');
                    var modalShareBtn = document.getElementById('reelModalShareBtn');
                    var modalProductTrack = document.getElementById('reelModalProductTrack');

                    var currentReelIdx = 0;
                    var isModalSoundMuted = false;
                    var likedReels = {};

                    var quickBuyDrawer = document.getElementById('reelQuickBuyDrawer');
                    var drawerThumbs = document.getElementById('reelDrawerThumbs');
                    var drawerCloseBtn = document.getElementById('reelDrawerCloseBtn');
                    var drawerTitle = document.getElementById('reelDrawerTitle');
                    var drawerLink = document.getElementById('reelDrawerLink');
                    var drawerPrice = document.getElementById('reelDrawerPrice');
                    var drawerStrike = document.getElementById('reelDrawerStrike');
                    var drawerDiscount = document.getElementById('reelDrawerDiscount');
                    var drawerSizes = document.getElementById('reelDrawerSizes');
                    var drawerAddCartBtn = document.getElementById('reelDrawerAddCartBtn');
                    var drawerBuyBtn = document.getElementById('reelDrawerBuyBtn');
                    var drawerBagBadge = document.getElementById('reelDrawerBagBadge');

                    var currentDrawerProduct = null;
                    var currentSelectedSize = 'M';

                    function openQuickBuyDrawer(prod) {
                        if (!quickBuyDrawer || !prod) return;
                        currentDrawerProduct = prod;

                        // Sync bag count with header badge
                        var headerBadge = document.getElementById('cart-count');
                        if (headerBadge && drawerBagBadge) {
                            var cnt = headerBadge.textContent.trim();
                            if (cnt && !isNaN(cnt)) {
                                drawerBagBadge.textContent = cnt;
                            }
                        }

                        // Populate up to 3 thumbnails
                        if (drawerThumbs) {
                            drawerThumbs.innerHTML = '';
                            var imgs = (prod.gallery_images && prod.gallery_images.length) ? prod.gallery_images : [prod.image];
                            imgs.slice(0, 3).forEach(function(imgUrl, idx) {
                                var imgEl = document.createElement('img');
                                imgEl.src = imgUrl || '{{ asset('images/logo.png') }}';
                                imgEl.alt = prod.name;
                                imgEl.className = 'reel-drawer-thumb-img' + (idx === 0 ? ' is-active' : '');
                                imgEl.onerror = function() { this.src = '{{ asset('images/logo.png') }}'; };
                                imgEl.addEventListener('click', function() {
                                    drawerThumbs.querySelectorAll('.reel-drawer-thumb-img').forEach(function(i) {
                                        i.classList.remove('is-active');
                                    });
                                    imgEl.classList.add('is-active');
                                });
                                drawerThumbs.appendChild(imgEl);
                            });
                        }

                        // Populate Title & Link
                        if (drawerTitle) drawerTitle.textContent = prod.name;
                        if (drawerLink) drawerLink.href = prod.url;

                        // Populate Pricing
                        if (drawerPrice) drawerPrice.textContent = prod.price;
                        if (drawerStrike) {
                            if (prod.original_price) {
                                drawerStrike.textContent = prod.original_price;
                                drawerStrike.style.display = 'inline';
                            } else {
                                drawerStrike.style.display = 'none';
                            }
                        }
                        if (drawerDiscount) {
                            if (prod.discount_percent) {
                                drawerDiscount.textContent = prod.discount_percent;
                                drawerDiscount.style.display = 'inline';
                            } else {
                                drawerDiscount.style.display = 'none';
                            }
                        }

                        // Populate Sizes
                        var sizesList = (prod.sizes && prod.sizes.length) ? prod.sizes : ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
                        currentSelectedSize = sizesList.indexOf('M') !== -1 ? 'M' : sizesList[0];
                        if (drawerSizes) {
                            drawerSizes.innerHTML = '';
                            sizesList.forEach(function(sz) {
                                var pill = document.createElement('span');
                                pill.className = 'reel-drawer-size-pill' + (sz === currentSelectedSize ? ' is-selected' : '');
                                pill.textContent = sz;
                                pill.addEventListener('click', function(e) {
                                    e.stopPropagation();
                                    currentSelectedSize = sz;
                                    drawerSizes.querySelectorAll('.reel-drawer-size-pill').forEach(function(p) {
                                        p.classList.remove('is-selected');
                                    });
                                    pill.classList.add('is-selected');
                                });
                                drawerSizes.appendChild(pill);
                            });
                        }

                        // Reset action button states
                        if (drawerAddCartBtn) {
                            drawerAddCartBtn.textContent = 'ADD TO CART';
                            drawerAddCartBtn.disabled = false;
                        }
                        if (drawerBuyBtn) {
                            drawerBuyBtn.textContent = 'BUY NOW';
                            drawerBuyBtn.disabled = false;
                        }

                        // Open collapsible drawer
                        quickBuyDrawer.classList.add('is-open');
                        quickBuyDrawer.setAttribute('aria-hidden', 'false');
                    }

                    function closeQuickBuyDrawer() {
                        if (!quickBuyDrawer) return;
                        quickBuyDrawer.classList.remove('is-open');
                        quickBuyDrawer.setAttribute('aria-hidden', 'true');
                    }

                    // Attach Drawer Close Button
                    if (drawerCloseBtn) {
                        drawerCloseBtn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            closeQuickBuyDrawer();
                        });
                    }

                    // Drawer ADD TO CART
                    if (drawerAddCartBtn) {
                        drawerAddCartBtn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            if (!currentDrawerProduct || drawerAddCartBtn.disabled) return;

                            var origTxt = drawerAddCartBtn.textContent;
                            drawerAddCartBtn.textContent = 'ADDING...';
                            drawerAddCartBtn.disabled = true;

                            fetch('{{ route('cart.add.item', ':id') }}'.replace(':id', currentDrawerProduct.id), {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': window.tttCsrfToken ? window.tttCsrfToken() : '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({
                                    quantity: 1,
                                    size: currentSelectedSize,
                                    design_side: 'front'
                                })
                            })
                            .then(function(r) { return r.json(); })
                            .then(function(data) {
                                if (data.success) {
                                    var badge = document.getElementById('cart-count');
                                    if (badge) {
                                        badge.textContent = data.cart_count;
                                        badge.style.display = 'flex';
                                    }
                                    if (drawerBagBadge) {
                                        drawerBagBadge.textContent = data.cart_count;
                                    }
                                    showToast('✓ ' + currentDrawerProduct.name + ' (Size ' + currentSelectedSize + ') added to bag!', 'success');
                                    drawerAddCartBtn.textContent = 'ADDED ✓';
                                    setTimeout(function() {
                                        drawerAddCartBtn.textContent = origTxt;
                                        drawerAddCartBtn.disabled = false;
                                    }, 1500);
                                } else if (data.redirect) {
                                    window.location.href = data.redirect;
                                } else {
                                    showToast(data.message || 'Error adding to cart', 'error');
                                    drawerAddCartBtn.textContent = origTxt;
                                    drawerAddCartBtn.disabled = false;
                                }
                            })
                            .catch(function() {
                                showToast('Connection error', 'error');
                                drawerAddCartBtn.textContent = origTxt;
                                drawerAddCartBtn.disabled = false;
                            });
                        });
                    }

                    // Drawer BUY NOW
                    if (drawerBuyBtn) {
                        drawerBuyBtn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            if (!currentDrawerProduct || drawerBuyBtn.disabled) return;

                            var origTxt = drawerBuyBtn.textContent;
                            drawerBuyBtn.textContent = 'PROCEEDING...';
                            drawerBuyBtn.disabled = true;

                            fetch('{{ route('cart.add.item', ':id') }}'.replace(':id', currentDrawerProduct.id), {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': window.tttCsrfToken ? window.tttCsrfToken() : '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({
                                    quantity: 1,
                                    size: currentSelectedSize,
                                    design_side: 'front',
                                    buy_now: 1
                                })
                            })
                            .then(function(r) { return r.json(); })
                            .then(function(data) {
                                if (data.success) {
                                    window.location.href = '{{ route('checkout.index') }}';
                                } else if (data.redirect) {
                                    window.location.href = data.redirect;
                                } else {
                                    showToast(data.message || 'Error proceeding to checkout', 'error');
                                    drawerBuyBtn.textContent = origTxt;
                                    drawerBuyBtn.disabled = false;
                                }
                            })
                            .catch(function() {
                                showToast('Connection error', 'error');
                                drawerBuyBtn.textContent = origTxt;
                                drawerBuyBtn.disabled = false;
                            });
                        });
                    }

                    function renderModalProducts(products) {
                        if (!modalProductTrack) return;
                        modalProductTrack.innerHTML = '';
                        if (!products || !products.length) return;

                        var mainProd = products[0];
                        var cardHtml = `
                            <div class="reel-modal-product-card" data-main-card>
                                <img src="${mainProd.image || '{{ asset('images/logo.png') }}'}" alt="${mainProd.name}" class="reel-modal-card-img" onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}';">
                                <div class="reel-modal-card-info">
                                    <div class="reel-modal-card-header">
                                        <h5 class="reel-modal-card-title">${mainProd.name}</h5>
                                        <a href="${mainProd.url}" class="reel-modal-card-link" title="Open product" target="_blank">
                                            <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
                                    </div>
                                    <div class="reel-modal-card-prices">
                                        <span class="reel-modal-card-price">${mainProd.price}</span>
                                        ${mainProd.original_price ? `<span class="reel-modal-card-strike">${mainProd.original_price}</span>` : ''}
                                        <span class="reel-modal-card-discount">${mainProd.discount_percent || '13% OFF'}</span>
                                    </div>
                                    <button type="button" class="reel-modal-card-btn" data-reel-addtocart="${mainProd.id}" data-reel-prodname="${mainProd.name}">
                                        ADD TO CART
                                    </button>
                                </div>
                            </div>
                        `;

                        // If second product exists, peek it on the right just like the screenshot!
                        if (products.length > 1) {
                            var secondProd = products[1];
                            cardHtml += `
                                <div class="reel-modal-product-card peek-next" data-peek-idx="1" title="${secondProd.name}">
                                    <img src="${secondProd.image || '{{ asset('images/logo.png') }}'}" alt="${secondProd.name}" class="reel-modal-card-img" onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}';">
                                </div>
                            `;
                        }

                        modalProductTrack.innerHTML = cardHtml;

                        // Attach peek click to swap
                        var peekCard = modalProductTrack.querySelector('.peek-next');
                        if (peekCard) {
                            peekCard.addEventListener('click', function() {
                                var swapped = products.slice().reverse();
                                renderModalProducts(swapped);
                            });
                        }

                        // Attach click on ADD TO CART button to show collapsible drawer!
                        var addBtn = modalProductTrack.querySelector('[data-reel-addtocart]');
                        if (addBtn) {
                            addBtn.addEventListener('click', function(e) {
                                e.stopPropagation();
                                openQuickBuyDrawer(mainProd);
                            });
                        }

                        // Also clicking the product card (except the external link) opens the collapsible drawer!
                        var mainCard = modalProductTrack.querySelector('[data-main-card]');
                        if (mainCard) {
                            mainCard.addEventListener('click', function(e) {
                                if (e.target.closest('a')) return;
                                openQuickBuyDrawer(mainProd);
                            });
                        }
                    }

                    function openReelModal(index) {
                        if (!reelsData.length || !modalOverlay) return;
                        currentReelIdx = (index + reelsData.length) % reelsData.length;
                        var reel = reelsData[currentReelIdx];

                        // Close collapsible drawer if open
                        closeQuickBuyDrawer();

                        // Pause any background videos in carousel
                        cards.forEach(function(c) {
                            var v = c.querySelector('.reel-video-element');
                            if (v) v.pause();
                        });

                        // Set video source
                        if (modalVideo) {
                            modalVideo.src = reel.video_url;
                            modalVideo.poster = reel.poster || '';
                            modalVideo.muted = isModalSoundMuted;
                            modalVideo.currentTime = 0;
                            modalVideo.play().catch(function() {});
                        }

                        // Sound icon
                        if (modalSoundIcon) {
                            modalSoundIcon.className = isModalSoundMuted ? 'bi bi-volume-mute-fill' : 'bi bi-volume-up-fill';
                        }

                        // Likes
                        var isLiked = likedReels[reel.id] || false;
                        var baseLikes = reel.likes || 13;
                        if (modalLikeBtn) {
                            modalLikeBtn.classList.toggle('is-liked', isLiked);
                        }
                        if (modalLikeIcon) {
                            modalLikeIcon.className = isLiked ? 'bi bi-heart-fill' : 'bi bi-heart';
                        }
                        if (modalLikeCount) {
                            modalLikeCount.textContent = isLiked ? (baseLikes + 1) : baseLikes;
                        }

                        // Render products in bottom dock
                        renderModalProducts(reel.products || []);

                        // Show modal
                        modalOverlay.style.display = 'flex';
                        void modalOverlay.offsetWidth;
                        modalOverlay.classList.add('is-active');
                        document.body.style.overflow = 'hidden';
                    }

                    function closeReelModal() {
                        if (!modalOverlay) return;
                        closeQuickBuyDrawer();
                        modalOverlay.classList.remove('is-active');
                        if (modalVideo) {
                            modalVideo.pause();
                            modalVideo.src = '';
                        }
                        setTimeout(function() {
                            modalOverlay.style.display = 'none';
                            document.body.style.overflow = '';
                        }, 280);
                    }

                    // Click reel card in carousel to open modal!
                    cards.forEach(function(card, idx) {
                        var video = card.querySelector('.reel-video-element');

                        // Hover play preview in carousel
                        card.addEventListener('mouseenter', function() {
                            if (modalOverlay && modalOverlay.classList.contains('is-active')) return;
                            if (video) {
                                video.play().catch(function() {});
                            }
                        });
                        card.addEventListener('mouseleave', function() {
                            if (video) video.pause();
                        });

                        // CLICK TO OPEN FULL SCREEN REEL MODAL (Matches screenshot!)
                        card.addEventListener('click', function(e) {
                            // Don't trigger if clicking sound icon on carousel card
                            if (e.target.closest('[data-reel-sound]')) return;
                            e.preventDefault();
                            openReelModal(idx);
                        });
                    });

                    // Modal Sound Toggle
                    if (modalSoundBtn && modalVideo) {
                        modalSoundBtn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            isModalSoundMuted = !isModalSoundMuted;
                            modalVideo.muted = isModalSoundMuted;
                            if (modalSoundIcon) {
                                modalSoundIcon.className = isModalSoundMuted ? 'bi bi-volume-mute-fill' : 'bi bi-volume-up-fill';
                            }
                        });
                    }

                    // Modal Close Events
                    if (modalCloseBtn) {
                        modalCloseBtn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            closeReelModal();
                        });
                    }
                    if (modalBackdrop) {
                        modalBackdrop.addEventListener('click', closeReelModal);
                    }

                    // Prev / Next Arrows
                    if (modalPrevBtn) {
                        modalPrevBtn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            openReelModal(currentReelIdx - 1);
                        });
                    }
                    if (modalNextBtn) {
                        modalNextBtn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            openReelModal(currentReelIdx + 1);
                        });
                    }

                    // Like Toggle
                    if (modalLikeBtn) {
                        modalLikeBtn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            var reel = reelsData[currentReelIdx];
                            if (!reel) return;
                            var currentLiked = likedReels[reel.id] || false;
                            likedReels[reel.id] = !currentLiked;
                            var baseLikes = reel.likes || 13;

                            modalLikeBtn.classList.toggle('is-liked', !currentLiked);
                            if (modalLikeIcon) {
                                modalLikeIcon.className = !currentLiked ? 'bi bi-heart-fill' : 'bi bi-heart';
                            }
                            if (modalLikeCount) {
                                modalLikeCount.textContent = !currentLiked ? (baseLikes + 1) : baseLikes;
                            }
                        });
                    }

                    // Share Button
                    if (modalShareBtn) {
                        modalShareBtn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            var reel = reelsData[currentReelIdx];
                            var shareUrl = (reel && reel.products && reel.products[0]) ? reel.products[0].url : window.location.href;

                            if (navigator.share) {
                                navigator.share({
                                    title: (reel ? reel.title : 'The Trend Theory'),
                                    url: shareUrl
                                }).catch(function() {});
                            } else if (navigator.clipboard) {
                                navigator.clipboard.writeText(shareUrl).then(function() {
                                    showToast('✓ Link copied to clipboard!', 'success');
                                });
                            } else {
                                showToast('✓ Link copied to clipboard!', 'success');
                            }
                        });
                    }

                    // Keyboard Navigation
                    document.addEventListener('keydown', function(e) {
                        if (!modalOverlay || !modalOverlay.classList.contains('is-active')) return;
                        if (e.key === 'Escape') {
                            if (quickBuyDrawer && quickBuyDrawer.classList.contains('is-open')) {
                                closeQuickBuyDrawer();
                            } else {
                                closeReelModal();
                            }
                        } else if (e.key === 'ArrowLeft') {
                            openReelModal(currentReelIdx - 1);
                        } else if (e.key === 'ArrowRight') {
                            openReelModal(currentReelIdx + 1);
                        } else if (e.key === ' ' && modalVideo) {
                            e.preventDefault();
                            if (modalVideo.paused) modalVideo.play();
                            else modalVideo.pause();
                        }
                    });

                    // Video click in modal toggles play/pause
                    if (modalVideo) {
                        modalVideo.addEventListener('click', function() {
                            if (modalVideo.paused) modalVideo.play();
                            else modalVideo.pause();
                        });
                    }
                })();
            });
        </script>
    @endpush

@endsection
