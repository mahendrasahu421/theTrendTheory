@extends('froentend.layouts.app')
@push('seo')
    <title>{{ $meta_title ?? 'Shop' }}</title>
    <meta name="description" content="{{ $meta_description ?? '' }}">
    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">
@endpush
@section('main')
    <style>
        .shop-hero {
            background: linear-gradient(135deg, #00285a, #1e3f75);
            background-position: center;
            background-size: cover;
            color: #fff;
            padding: 50px 0 40px;
            position: relative;
            text-align: center;
        }

        .shop-hero.has-image {
            background-image:
                linear-gradient(135deg, rgba(0, 40, 90, .78), rgba(30, 63, 117, .58)),
                var(--shop-hero-image);
            min-height: 260px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .shop-hero h1 {
            font-family: 'Cinzel', serif;
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: 3px;
            margin-bottom: 8px;
        }

        .shop-hero p {
            font-size: 1rem;
            opacity: .8;
        }

        .shop-breadcrumb {
            background: #f8f9fc;
            padding: 12px 0;
            border-bottom: 1px solid #e8edf5;
            font-size: 13px;
        }

        .shop-breadcrumb .container {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #7a8fa6;
        }

        .shop-breadcrumb a {
            color: #00285a;
            text-decoration: none;
            font-weight: 600;
        }

        .shop-breadcrumb a:hover {
            color: #ff3f6c;
        }

        .subcat-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding: 20px 0 0;
        }

        .subcat-pill {
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
            border: 1.5px solid #d9dee6;
            color: #00285a;
            background: white;
            text-decoration: none;
            transition: .2s;
        }

        .subcat-pill:hover,
        .subcat-pill.active {
            background: #00285a;
            color: white;
            border-color: #00285a;
        }

        .shop-wrap {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px 60px;
        }

        .shop-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0 16px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .result-count {
            font-size: 14px;
            color: #7a8fa6;
        }

        .result-count strong {
            color: #00285a;
            font-size: 15px;
        }

        .sort-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sort-wrap label {
            font-size: 13px;
            color: #7a8fa6;
        }

        .sort-select {
            padding: 8px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 30px;
            font-size: 13px;
            color: #00285a;
            background: white;
            cursor: pointer;
            outline: none;
        }

        .shop-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }

        .shop-card {
            background: white;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 6px 16px rgba(0, 40, 90, .06);
            border: 1px solid #eef2f6;
            transition: all .25s;
            position: relative;
        }

        .shop-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 18px 32px rgba(0, 40, 90, .11);
        }

        .shop-card-img {
            height: 400px;
            overflow: hidden;
            position: relative;
        }

        .shop-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s;
        }

        .shop-card:hover .shop-card-img img {
            transform: scale(1.07);
        }

        .shop-card-hover {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%) translateY(8px);
            opacity: 0;
            transition: .25s;
            white-space: nowrap;
        }

        .shop-card:hover .shop-card-hover {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }

        .shop-card-info {
            padding: 12px 14px 16px;
        }

        .shop-card-name {
            font-size: 13px;
            font-weight: 600;
            color: #00285a;
            margin-bottom: 4px;
            text-decoration: none;
            display: block;
        }

        .shop-card-name:hover {
            color: #ff3f6c;
        }

        .shop-card-price {
            font-size: 15px;
            font-weight: 800;
            color: #c44536;
        }

        .shop-card-oldprice {
            font-size: 11px;
            color: #aaa;
            text-decoration: line-through;
            margin-left: 5px;
        }

        .shop-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .shop-badge.new {
            background: #00285a;
            color: white;
        }

        .shop-badge.sale {
            background: #ff3f6c;
            color: white;
        }

        .empty-shop {
            text-align: center;
            padding: 60px 20px;
            color: #7a8fa6;
        }

        .empty-shop i {
            font-size: 48px;
            margin-bottom: 16px;
            display: block;
        }

        .empty-shop h3 {
            font-size: 1.2rem;
            margin-bottom: 8px;
            color: #00285a;
        }

        .pagi {
            display: flex;
            justify-content: center;
            gap: 6px;
        }

        .pagi a,
        .pagi span {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 13px;
            font-weight: 600;
            border: 1.5px solid #e2e8f0;
            color: #00285a;
            text-decoration: none;
            transition: .2s;
        }

        .pagi a:hover,
        .pagi span.active {
            background: #00285a;
            color: white;
            border-color: #00285a;
        }

        @media(max-width:1200px) {
            .shop-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media(max-width:768px) {
            .shop-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .shop-card-img {
                height: 260px;
            }
        }

        @media(max-width:480px) {
            .shop-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    @php
        $shopHeroImage = $currentCategory?->banner_image_url ?? (isset($parentCategory) ? $parentCategory?->banner_image_url : null);
    @endphp

    <div class="shop-hero {{ $shopHeroImage ? 'has-image' : '' }}"
        @if ($shopHeroImage) style="--shop-hero-image: url('{{ $shopHeroImage }}')" @endif>
        <h1>{{ $pageHeading ?? ($currentCategory ? strtoupper($currentCategory->name) : 'SHOP ALL') }}</h1>
        <p>{{ $currentCategory->description ?? 'Discover the latest styles' }}</p>
    </div>

    @if ($currentCategory)
        <div class="shop-breadcrumb">
            <div class="container">
                <a href="{{ route('home') }}">Home</a>
                <i class="bi bi-chevron-right" style="font-size:10px"></i>
                @isset($parentCategory)
                    <a href="{{ route('shop.category', $parentCategory->slug) }}">{{ $parentCategory->name }}</a>
                    <i class="bi bi-chevron-right" style="font-size:10px"></i>
                @endisset
                <span>{{ $currentCategory->name }}</span>
            </div>
        </div>
    @endif

    <div class="shop-wrap">

        {{-- Subcategory filter pills --}}
        @if (isset($subCategories) && $subCategories->count() > 0)
            <div class="subcat-pills">
                <a href="{{ route('shop.category', $parentCategory->slug ?? $currentCategory->slug) }}" class="subcat-pill"
                    style="background:#00285a;color:white;border-color:#00285a">
                    All {{ $parentCategory->name ?? $currentCategory->name }}
                </a>
                @foreach ($subCategories as $sub)
                    <a href="{{ route('shop.category', $sub->slug) }}"
                        class="subcat-pill {{ $currentCategory && $currentCategory->id === $sub->id ? 'active' : '' }}">
                        {{ $sub->name }}
                    </a>
                @endforeach
            </div>
        @endif

        <div class="shop-top">
            <div class="result-count">Showing <strong>{{ $products->total() }}</strong> products</div>
            <div class="sort-wrap">
                <label>Sort:</label>
                <select class="sort-select" onchange="window.location=this.value">
                    <option value="?sort=latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Latest
                    </option>
                    <option value="?sort=popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most
                        Popular</option>
                    <option value="?sort=price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>
                        Price ↑</option>
                    <option value="?sort=price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>
                        Price ↓</option>
                </select>
            </div>
        </div>

        @if ($products->count() > 0)
            <div class="shop-grid">
                @foreach ($products as $product)
                    @php
                        $hasDiscount = $product->original_price && $product->original_price > $product->price;
                        $discPct = $hasDiscount
                            ? (int) round(
                                (($product->original_price - $product->price) / $product->original_price) * 100,
                            )
                            : 0;
                        $inWishlist = in_array($product->id, session('wishlist', []));
                    @endphp
                    <div class="shop-card">
                        @if ($product->is_new)
                            <span class="shop-badge new">NEW</span>
                        @elseif($hasDiscount)
                            <span class="shop-badge sale">-{{ $discPct }}%</span>
                        @endif

                        <div class="shop-card-img">
                            <img src="{{ $product->image_url ?? asset('images/placeholder-product.jpg') }}"
                                alt="{{ $product->name }}" loading="lazy">

                            {{-- ❤ Wishlist button --}}
                            <button class="wish-btn {{ $inWishlist ? 'wished' : '' }}"
                                onclick="toggleWishlist({{ $product->id }}, this)" aria-label="Add to Wishlist">
                                <i class="bi {{ $inWishlist ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                            </button>

                            <button class="btn-hover-add shop-card-hover open-product-slider"
                                data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}">
                                ADD TO CART
                            </button>
                        </div>

                        <div class="shop-card-info">
                            <a href="{{ route('product.show', $product->slug) }}" class="shop-card-name">
                                {{ $product->name }}
                            </a>
                            <div>
                                <span class="shop-card-price">Rs. {{ number_format($product->price) }}</span>
                                @if ($hasDiscount)
                                    <span class="shop-card-oldprice">Rs.
                                        {{ number_format($product->original_price) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($products->hasPages())
                <div class="pagi">
                    @if ($products->onFirstPage())
                        <span style="opacity:.4">‹</span>
                    @else
                        <a href="{{ $products->previousPageUrl() }}">‹</a>
                    @endif
                    @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                        @if ($page == $products->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                    @if ($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}">›</a>
                    @else
                        <span style="opacity:.4">›</span>
                    @endif
                </div>
            @endif
        @else
            <div class="empty-shop">
                <i class="bi bi-bag-x"></i>
                <h3>No products found</h3>
                <p>Try a different category.</p>
                <a href="{{ route('shop.index') }}" class="btn-shop" style="margin-top:16px;display:inline-block">View
                    All</a>
            </div>
        @endif
    </div>
@endsection
