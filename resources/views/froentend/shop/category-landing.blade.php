@extends('froentend.layouts.app')
@push('seo')
    <title>{{ $meta_title }}</title>
    <meta name="description" content="{{ $meta_description }}">
    <link rel="canonical" href="{{ $canonical }}">
@endpush
@section('main')
    <style>
        /* ── CATEGORY LANDING ──────────────────────────── */
        .cat-hero {
            background: linear-gradient(135deg, #00285a 0%, #1e3f75 100%);
            color: white;
            padding: 60px 0 50px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cat-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('{{ $category->image_url ?? '' }}') center/cover no-repeat;
            opacity: .15;
        }

        .cat-hero-content {
            position: relative;
            z-index: 2;
        }

        .cat-hero h1 {
            font-family: 'Cinzel', serif;
            font-size: 3rem;
            font-weight: 700;
            letter-spacing: 4px;
            margin-bottom: 10px;
        }

        .cat-hero p {
            font-size: 1.1rem;
            opacity: .85;
            margin-bottom: 24px;
        }

        .cat-hero-shopall {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: white;
            color: #00285a;
            padding: 12px 32px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 1px;
            text-decoration: none;
            transition: .2s;
        }

        /* ── SUBCATEGORY CARDS ─────────────────────────── */
        .subcat-section {
            padding: 60px 0;
            background: #f8f9fc;
        }

        .subcat-section-title {
            text-align: center;
            margin-bottom: 40px;
            font-family: 'Cinzel', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: #00285a;
            letter-spacing: 2px;
        }

        .subcat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .subcat-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            border: 1.5px solid #e8edf5;
            transition: all .25s;
            text-decoration: none;
            display: block;
            position: relative;
        }

        .subcat-card-img {
            height: 400px;
            background: linear-gradient(135deg, #e8edf5, #d9dee6);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        .subcat-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s;
        }

        .subcat-card-img-placeholder {
            font-size: 48px;
            opacity: .3;
        }

        .subcat-card-body {
            padding: 16px 18px 20px;
        }

        .subcat-card-name {
            font-family: 'Cinzel', serif;
            font-size: 1rem;
            font-weight: 700;
            color: #00285a;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .subcat-card-count {
            font-size: 12px;
            color: #7a8fa6;
            margin-bottom: 12px;
        }

        .subcat-card-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #00285a;
            color: white;
            padding: 7px 18px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .5px;
            transition: background .2s;
        }

        /* ── FEATURED PRODUCTS ─────────────────────────── */
        .featured-section {
            padding: 60px 0;
            background: white;
        }

        .featured-header {
            max-width: 1200px;
            margin: 0 auto 36px;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .featured-title {
            font-family: 'Cinzel', serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: #00285a;
            letter-spacing: 2px;
        }

        .featured-viewall {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #00285a;
            border: 2px solid #00285a;
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: .2s;
        }

        .featured-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .feat-card {
            background: white;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid #eef2f6;
            transition: all .25s;
            position: relative;
        }

        .feat-card-img {
            height: 280px;
            overflow: hidden;
            position: relative;
        }

        .feat-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s;
        }

        .feat-card-hover {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%) translateY(8px);
            opacity: 0;
            transition: .25s;
            white-space: nowrap;
        }

        .feat-card-info {
            padding: 12px 14px 16px;
        }

        .feat-card-name {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13.5px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
            text-decoration: none;
            display: block;
        }

        .feat-card-price {
            font-size: 15px;
            font-weight: 800;
            color: #c44536;
        }

        .feat-card-oldprice {
            font-size: 11px;
            color: #aaa;
            text-decoration: line-through;
            margin-left: 5px;
        }

        @media(max-width:1000px) {

            .subcat-grid,
            .featured-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media(max-width:600px) {

            .subcat-grid,
            .featured-grid {
                grid-template-columns: 1fr;
            }

            .cat-hero h1 {
                font-size: 2rem;
            }
        }
    </style>

    {{-- HERO BANNER --}}
    <div class="cat-hero">
        <div class="cat-hero-content">
            <h1>{{ strtoupper($category->name) }}</h1>
            <p>{{ $category->description ?? 'Discover the latest styles' }}</p>
            <a href="{{ route('shop.category', $category->slug) }}?sub=all" class="cat-hero-shopall">
                SHOP ALL {{ strtoupper($category->name) }} <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>

    {{-- SUBCATEGORY CARDS --}}
    <section class="subcat-section">
        <div class="subcat-section-title">SHOP BY CATEGORY</div>
        <div class="subcat-grid">
            @foreach ($subCategories as $sub)
                <a href="{{ route('shop.category', $sub->slug) }}" class="subcat-card">
                    <div class="subcat-card-img">
                        @if ($sub->image)
                            <img src="{{ $sub->image }}" alt="{{ $sub->name }}" loading="lazy">
                        @else
                            <div class="subcat-card-img-placeholder">
                                @php
                                    $emojis = [
                                        'T-Shirts' => '👕',
                                        'Shirts' => '👔',
                                        'Hoodies' => '🧥',
                                        'Cargo Pants' => '👖',
                                        'Jeans' => '👖',
                                        'Jackets' => '🧥',
                                        'Joggers' => '🩱',
                                        'Shorts' => '🩲',
                                        'Tops & Tees' => '👚',
                                        'Dresses' => '👗',
                                        'Co-ord Sets' => '👘',
                                        'Skirts' => '🪡',
                                    ];
                                @endphp
                                {{ $emojis[$sub->name] ?? '🛍️' }}
                            </div>
                        @endif
                    </div>
                    <div class="subcat-card-body">
                        <div class="subcat-card-name">{{ strtoupper($sub->name) }}</div>
                        <div class="subcat-card-count">{{ $sub->product_count }} Products</div>
                        <div class="subcat-card-btn">
                            SHOP NOW <i class="bi bi-arrow-right"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- FEATURED / BEST SELLING --}}
    @if ($featuredProducts->count() > 0)
        <section class="featured-section">
            <div class="featured-header">
                <div class="featured-title">BEST SELLING</div>
                <a href="{{ route('shop.category', $category->slug) }}?sub=all" class="featured-viewall">
                    VIEW ALL <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="featured-grid">
                @foreach ($featuredProducts as $product)
                    @php
                        $displayOriginalPrice = $product->display_original_price ?: $product->original_price;
                        $hasDiscount = $displayOriginalPrice && $displayOriginalPrice > $product->price;
                        $discPct = $hasDiscount
                            ? (int) round(
                                (($displayOriginalPrice - $product->price) / $displayOriginalPrice) * 100,
                            )
                            : 0;

                        // ✅ TRY ALL POSSIBLE SOURCES
                        $imageUrl = null;

                        // Check if product has direct image field
                        if (!empty($product->image)) {
                            $imageUrl = $product->image;
                        }
                        // Check card_image accessor
                        elseif (!empty($product->card_image) && !str_contains($product->card_image, 'placeholder')) {
                            $imageUrl = $product->card_image;
                        }
                        // Check main_image accessor
                        elseif (!empty($product->main_image) && !str_contains($product->main_image, 'placeholder')) {
                            $imageUrl = $product->main_image;
                        }
                        // Check media relationship
                        elseif ($product->media && $product->media->count() > 0) {
                            $primary = $product->media->where('is_primary', true)->first();
                            if ($primary) {
                                $imageUrl = $primary->getUrl();
                            } else {
                                $imageUrl = $product->media->first()->getUrl();
                            }
                        }

                        // Final fallback
                        if (empty($imageUrl)) {
                            $imageUrl = asset('images/placeholder-product.jpg');
                        }

                        // If it's an ImageKit path, add the base URL
if (
    $imageUrl &&
    !filter_var($imageUrl, FILTER_VALIDATE_URL) &&
    !str_starts_with($imageUrl, '/')
) {
    $imageUrl = url('/storage/' . $imageUrl);
                        }
                    @endphp

                    {{-- Debug comment - remove after fixing --}}
                    <!-- Product ID: {{ $product->id }}, Image URL: {{ $imageUrl }} -->

                    <div class="feat-card">
                        <div class="feat-card-img">
                            <img src="{{ $imageUrl }}" alt="{{ $product->name }}" loading="lazy"
                                onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                            <button class="btn-hover-add feat-card-hover open-product-slider"
                                data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}"
                                data-product-price="{{ $product->price }}" data-product-image="{{ $imageUrl }}">
                                ADD TO CART
                            </button>
                        </div>
                        <div class="feat-card-info">
                            <a href="{{ route('product.show', $product->slug) }}" class="feat-card-name">
                                {{ $product->name }}
                            </a>
                            <div>
                                <span class="feat-card-price">₹{{ number_format($product->price) }}</span>
                                @if ($hasDiscount)
                                    <span class="feat-card-oldprice">₹{{ number_format($displayOriginalPrice) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
@endsection
