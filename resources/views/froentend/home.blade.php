@extends('froentend.layouts.app')

{{-- ══════════════════════════════════════════════════
     ✅ FIX: @push('seo') — sirf title, description, canonical
════════════════════════════════════════════════════ --}}
@push('seo')
    <title>{{ $meta_title ?? 'THE TREND THEORY — Premium Fashion Store India' }}</title>
    <meta name="description" content="{{ $meta_description ?? 'Shop latest men & women fashion.' }}">
    <link rel="canonical" href="{{ $canonical ?? url('/') }}">
    {{-- Schema JSON-LD --}}
    @isset($schema)
        <script type="application/ld+json">{!! $schema !!}</script>
    @endisset
@endpush

@section('main')

    {{-- SECTION 1: GALLERY HERO MEDIA SLIDER --}}
    @php
        $topHeroSlides = !empty($heroMediaSlides) ? $heroMediaSlides : (!empty($heroPrimary) ? [$heroPrimary] : []);
    @endphp
    @if (count($topHeroSlides))
        <section class="video-hero hero-media-slider" data-hero-media-slider>
            <div class="hero-media-track">
                @foreach ($topHeroSlides as $slide)
                    <article class="hero-media-slide {{ $loop->first ? 'active' : '' }}"
                        aria-hidden="{{ $loop->first ? 'false' : 'true' }}">
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
                        <div class="video-overlay"></div>
                        <div class="video-content">
                            @if (!empty($slide['subtitle']))
                                <p>{{ $slide['subtitle'] }}</p>
                            @endif
                            <h1>{{ $slide['title'] ?? 'NEW FASHION COLLECTION' }}</h1>
                            <div class="hero-buttons">
                                <a href="{{ $slide['button_link'] ?? route('shop.index') }}" class="btn-primary" target="_blank">
                                    {{ $slide['button_text'] ?? 'SHOP NOW' }}
                                </a>
                                {{-- <a href="{{ route('shop.new-arrivals') }}" class="btn-outline" target="_blank">NEW ARRIVALS</a> --}}
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            @if (count($topHeroSlides) > 1)
                <button class="top-hero-arrow top-hero-arrow-left" type="button" data-hero-prev aria-label="Previous hero slide">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button class="top-hero-arrow top-hero-arrow-right" type="button" data-hero-next aria-label="Next hero slide">
                    <i class="bi bi-chevron-right"></i>
                </button>
                <div class="top-hero-dots" aria-label="Hero slide navigation">
                    @foreach ($topHeroSlides as $slide)
                        <button class="top-hero-dot {{ $loop->first ? 'active' : '' }}" type="button"
                            data-hero-dot="{{ $loop->index }}" aria-label="Go to hero slide {{ $loop->iteration }}"></button>
                    @endforeach
                </div>
            @endif
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
                                    <p class="slide-subtitle">{{ $slide['subtitle'] }}</p>
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
                                alt="{{ $category['name'] }} Collection — The Trend Theory" loading="lazy" width="600"
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
    <section class="most-slider-section" aria-label="Most Purchased Products">
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
                                    <div class="most-badge">🔥
                                        {{ $product['formatted_sold'] ?? ($product['sold_count'] ?? 0) }} sold</div>
                                    <div class="most-img">
                                        <img src="{{ $product['card_image_url'] ?? ($product['image_url'] ?? asset('images/placeholder-product.jpg')) }}"
                                            alt="{{ $product['name'] }}" loading="lazy" width="300" height="380"
                                            onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">

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
    <section class="collection-slider-section" aria-label="Men's Collection">
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
    <section class="collection-slider-section" aria-label="Women's Collection">
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
    <section class="collection-slider-section" aria-label="New Arrivals">
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
                        alt="The Trend Theory — Streetwear Born in India" loading="lazy" width="800" height="550">
                    <div class="image-overlay"></div>
                </div>
                <div class="story-content">
                    <h2 class="story-heading">
                        <span class="heading-line">THE TREND</span>
                        <span class="heading-line">THEORY</span>
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
                                alt="{{ $story['caption'] ?? 'Trending Story' }} — The Trend Theory" class="reel-img"
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

    {{-- ══ SECTION 10: REVIEWS ══════════════════════════ --}}
    @isset($reviewSchema)
        <script type="application/ld+json">{!! $reviewSchema !!}</script>
    @endisset

    <section class="trending-reviews" aria-label="Customer Reviews">
        <div class="container">
            <div class="trending-header">
                <div class="header-left">
                    <span class="trending-tag">THE BUZZ</span>
                    <h2 class="trending-title">REAL TALK<span class="title-dot">.</span></h2>
                </div>
                <div class="header-right">
                    <p class="header-subtitle">4.8 ★ from 2.5k+ reviews</p>
                    <a href="{{ url('/reviews') }}" class="view-all-link" target="_blank">VIEW ALL <i
                            class="bi bi-arrow-right"></i></a>
                </div>
            </div>
            <div class="reviews-showcase">
                @forelse($reviews as $review)
                    <article class="review-vibe" itemscope itemtype="https://schema.org/Review">
                        <div class="vibe-header">
                            <div class="vibe-user">
                                <div class="user-pic">
                                    <img src="{{ $review['reviewer_image_url'] ?? asset('images/default-avatar.jpg') }}"
                                        alt="{{ $review['reviewer_name'] }}" loading="lazy" width="45"
                                        height="45" onerror="this.src='{{ asset('images/default-avatar.jpg') }}'">
                                </div>
                                <div class="user-info">
                                    <h4 itemprop="author" itemscope itemtype="https://schema.org/Person">
                                        <span itemprop="name">{{ $review['reviewer_name'] ?? 'Verified Customer' }}</span>
                                    </h4>
                                    <div class="vibe-rating" itemprop="reviewRating" itemscope
                                        itemtype="https://schema.org/Rating">
                                        <meta itemprop="ratingValue" content="{{ $review['rating'] ?? 5 }}">
                                        <meta itemprop="bestRating" content="5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star-fill{{ $i <= ($review['rating'] ?? 5) ? '' : '-empty' }}"
                                                aria-hidden="true"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            @if (!empty($review['is_verified']))
                                <span class="verified-badge">✓ Verified</span>
                            @endif
                        </div>
                        <div class="vibe-content" itemprop="reviewBody">
                            <p>"{{ $review['comment'] ?? '' }}"</p>
                        </div>
                        <div class="vibe-footer">
                            @if (!empty($review['product_tag']))
                                <div class="vibe-product">
                                    <a href="{{ route('shop.index') }}?tag={{ Str::slug($review['product_tag']) }}"
                                        class="product-tag" target="_blank">
                                        {{ $review['product_tag'] }}
                                    </a>
                                </div>
                            @endif
                            <div class="vibe-stats">
                                <span>❤️ {{ number_format($review['likes'] ?? 0) }} likes</span>
                                <time
                                    datetime="{{ isset($review['created_at']) ? date('c', strtotime($review['created_at'])) : date('c') }}"
                                    itemprop="datePublished">
                                    🕒
                                    {{ isset($review['created_at']) ? \Carbon\Carbon::parse($review['created_at'])->diffForHumans() : 'recent' }}
                                </time>
                            </div>
                        </div>
                        @if (!empty($review['review_media']) && count($review['review_media']) > 0)
                            <div class="review-media">
                                <img src="{{ $review['review_media'][0] }}?tr=w-400,h-300,q-80,f-webp"
                                    alt="Review photo by {{ $review['reviewer_name'] ?? 'Customer' }}" loading="lazy"
                                    width="400" height="300"
                                    onerror="this.src='{{ asset('images/placeholder-review.jpg') }}'">
                            </div>
                        @endif
                    </article>
                @empty
                    <p class="text-muted text-center py-4">Be the first to review!</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════
     ADD TO CART AJAX
════════════════════════════════════════════════════ --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // Top gallery hero slider
                var heroSlider = document.querySelector('[data-hero-media-slider]');
                if (heroSlider) {
                    var heroSlides = Array.prototype.slice.call(heroSlider.querySelectorAll('.hero-media-slide'));
                    var heroDots = Array.prototype.slice.call(heroSlider.querySelectorAll('[data-hero-dot]'));
                    var heroPrev = heroSlider.querySelector('[data-hero-prev]');
                    var heroNext = heroSlider.querySelector('[data-hero-next]');
                    var heroIndex = 0;
                    var heroTimer = null;

                    function syncHeroVideos() {
                        heroSlides.forEach(function(slide, index) {
                            var video = slide.querySelector('video');
                            if (!video) return;

                            if (index === heroIndex) {
                                video.play().catch(function() {});
                            } else {
                                video.pause();
                                video.currentTime = 0;
                            }
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
                            dot.classList.toggle('active', index === heroIndex);
                        });

                        syncHeroVideos();
                    }

                    function startHeroAuto() {
                        if (heroSlides.length < 2) return;
                        clearInterval(heroTimer);
                        heroTimer = setInterval(function() {
                            showHeroSlide(heroIndex + 1);
                        }, 9000);
                    }

                    if (heroPrev) {
                        heroPrev.addEventListener('click', function() {
                            showHeroSlide(heroIndex - 1);
                            startHeroAuto();
                        });
                    }

                    if (heroNext) {
                        heroNext.addEventListener('click', function() {
                            showHeroSlide(heroIndex + 1);
                            startHeroAuto();
                        });
                    }

                    heroDots.forEach(function(dot) {
                        dot.addEventListener('click', function() {
                            showHeroSlide(parseInt(dot.dataset.heroDot, 10) || 0);
                            startHeroAuto();
                        });
                    });

                    showHeroSlide(0);
                    startHeroAuto();
                }

                // Add to cart functionality
                document.querySelectorAll('.add-to-cart-btn:not(.open-product-slider)').forEach(function(btn) {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        if (this.disabled) return;

                        var productId = this.dataset.productId;
                        var productName = this.dataset.productName;
                        var me = this;
                        var orig = me.textContent;

                        me.textContent = 'ADDING...';
                        me.disabled = true;

                        fetch('{{ route('cart.add', ':id') }}'.replace(':id', productId), {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
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
