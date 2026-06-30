{{--
    resources/views/froentend/partials/product-card.blade.php
    Reusable product card with ❤ Wishlist button
--}}

@if (isset($type) && $type === 'lifestyle')

    {{-- LIFESTYLE CARD VARIANT --}}
    <div class="collection-slide">
        <div class="lifestyle-card">
            <div class="lifestyle-img" style="position:relative">
                {{-- Image with multiple fallbacks --}}
                <img src="{{ $product['image_url'] ?? ($product['card_image_url'] ?? ($product['image'] ?? asset('images/placeholder-product.jpg'))) }}"
                    alt="{{ $product['name'] ?? 'Product' }}" loading="lazy"
                    onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">

                {{-- Wishlist Button --}}
                <button class="wish-btn {{ in_array($product['id'], session('wishlist', [])) ? 'wished' : '' }}"
                    onclick="toggleWishlist({{ $product['id'] }}, this)" aria-label="Wishlist">
                    <i
                        class="bi {{ in_array($product['id'], session('wishlist', [])) ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                </button>

                {{-- Hover Button --}}
                <div class="lifestyle-hover-btn">
                    <button class="btn-hover-add open-product-slider" data-product-id="{{ $product['id'] }}"
                        data-product-name="{{ $product['name'] }}"
                        {{ (isset($product['stock']) && $product['stock'] <= 0) || (isset($product['stock_status']) && $product['stock_status'] === 'out_of_stock') ? 'disabled' : '' }}>
                        SHOP LOOK
                    </button>
                </div>

                {{-- NEW Flag --}}
                @if (isset($product['is_new']) && $product['is_new'])
                    <span class="new-flag">NEW</span>
                @endif
            </div>

            <div class="lifestyle-info">
                <a href="{{ route('product.show', $product['slug']) }}" style="text-decoration:none;color:inherit;">
                    <h3>{{ $product['name'] }}</h3>
                </a>
                <p>Rs. {{ number_format($product['price']) }}</p>
            </div>
        </div>
    </div>
@else
    {{-- DEFAULT COLLECTION CARD VARIANT --}}
    <div class="collection-slide">
        <div class="collection-card">
            <div class="collection-img" style="position:relative">

                {{-- Image with multiple fallbacks and lazy loading --}}
                <img src="{{ $product['image_url'] ?? ($product['card_image_url'] ?? ($product['image'] ?? asset('images/placeholder-product.jpg'))) }}"
                    alt="{{ $product['name'] ?? 'Product' }}" loading="lazy"
                    onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">

                {{-- Wishlist Button --}}
                <button class="wish-btn {{ in_array($product['id'], session('wishlist', [])) ? 'wished' : '' }}"
                    onclick="toggleWishlist({{ $product['id'] }}, this)" aria-label="Wishlist">
                    <i
                        class="bi {{ in_array($product['id'], session('wishlist', [])) ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                </button>

                {{-- Hover Button (Add to Cart / Quick View) --}}
                <div class="collection-hover-btn">
                    @php
                        $isOutOfStock =
                            (isset($product['stock']) && $product['stock'] <= 0) ||
                            (isset($product['stock_status']) && $product['stock_status'] === 'out_of_stock');
                        $buttonText = $isOutOfStock ? 'OUT OF STOCK' : 'ADD TO CART';
                    @endphp
                    <button class="btn-hover-add open-product-slider" data-product-id="{{ $product['id'] }}"
                        data-product-name="{{ $product['name'] }}" data-product-price="{{ $product['price'] }}"
                        data-product-image="{{ $product['image_url'] ?? ($product['card_image_url'] ?? '') }}"
                        {{ $isOutOfStock ? 'disabled' : '' }}>
                        {{ $buttonText }}
                    </button>
                </div>

                {{-- Product Flags (NEW, HOT, SALE) --}}
                @php
                    // Safe array access with defaults
                    $isNew = $product['is_new'] ?? false;
                    $isTrending = $product['is_trending'] ?? false;
                    $isOnSale = $product['is_on_sale'] ?? false;

                    // Discount calculation
                    $originalPrice = $product['original_price'] ?? null;
                    $currentPrice = $product['price'] ?? 0;
                    $hasDiscount = $originalPrice && $originalPrice > $currentPrice;
                    $discountPct = $hasDiscount
                        ? (int) round((($originalPrice - $currentPrice) / $originalPrice) * 100)
                        : 0;

                    // Determine which flag to show (priority: SALE > NEW > HOT)
                    $showSaleFlag = $hasDiscount || $isOnSale;
                    $showNewFlag = !$showSaleFlag && $isNew;
                    $showTrendingFlag = !$showSaleFlag && !$showNewFlag && $isTrending;
                @endphp

                {{-- Sale Flag (highest priority) --}}
                @if ($showSaleFlag && $discountPct > 0)
                    <span class="product-flag sale-flag">-{{ $discountPct }}%</span>
                @elseif($showSaleFlag && $isOnSale)
                    <span class="product-flag sale-flag">SALE</span>
                @endif

                {{-- New Flag --}}
                @if ($showNewFlag)
                    <span class="product-flag new-flag">NEW</span>
                @endif

                {{-- Trending/HOT Flag --}}
                @if ($showTrendingFlag)
                    <span class="product-flag trending-flag">HOT</span>
                @endif
            </div>

            {{-- Product Info Section --}}
            <div class="collection-info">
                <a href="{{ route('product.show', $product['slug']) }}" class="product-title-link">
                    <h3>{{ $product['name'] }}</h3>
                </a>

                {{-- Price Display --}}
                <div class="price-wrap">
                    @if ($hasDiscount)
                        <span class="price">Rs. {{ number_format($currentPrice) }}</span>
                        <del class="original-price">Rs. {{ number_format($originalPrice) }}</del>
                        <span class="discount-badge">{{ $discountPct }}% OFF</span>
                    @else
                        <span class="price">Rs. {{ number_format($currentPrice) }}</span>
                        @if ($originalPrice && $originalPrice > $currentPrice)
                            <del class="original-price">Rs. {{ number_format($originalPrice) }}</del>
                        @endif
                    @endif
                </div>

                {{-- Size Chips (if available) --}}
                @if (isset($product['sizes']) && is_array($product['sizes']) && count($product['sizes']) > 0)
                    <div class="size-chips">
                        @foreach (array_slice($product['sizes'], 0, 4) as $size)
                            <span class="size-chip">{{ $size }}</span>
                        @endforeach
                        @if (count($product['sizes']) > 4)
                            <span class="size-chip">+{{ count($product['sizes']) - 4 }}</span>
                        @endif
                    </div>
                @endif

                {{-- Rating Display (if available) --}}
                @if (isset($product['avg_rating']) && $product['avg_rating'] > 0)
                    <div class="rating-stars">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= round($product['avg_rating']))
                                <i class="bi bi-star-fill" style="color: #ffc107; font-size: 12px;"></i>
                            @else
                                <i class="bi bi-star" style="color: #ffc107; font-size: 12px;"></i>
                            @endif
                        @endfor
                        <span class="rating-count">({{ $product['avg_rating'] ?? 0 }})</span>
                    </div>
                @endif

                {{-- Debug: Show category if needed (remove in production) --}}
                @if (isset($product['category_name']) && $product['category_name'])
                    <div class="product-category-badge" style="font-size: 10px; color: #999; margin-top: 5px;">
                        {{ $product['category_name'] }}
                    </div>
                @endif
            </div>
        </div>
    </div>

@endif
