{{--
    resources/views/froentend/partials/product-card.blade.php
    Reusable product card with ❤ Wishlist button
--}}

@php
    $fallbackProductImage = $product['image_url'] ?? ($product['card_image_url'] ?? ($product['image'] ?? asset('images/placeholder-product.jpg')));
    $productImages = $product['all_images_list'] ?? [];
    $productImages = is_array($productImages) ? array_values(array_unique(array_filter($productImages))) : [];
    if (empty($productImages)) {
        $productImages[] = $fallbackProductImage;
    }
    $primaryProductImage = $productImages[0] ?? $fallbackProductImage;
    $hasProductGallery = count($productImages) > 1;
@endphp

@if (isset($type) && $type === 'lifestyle')

    {{-- LIFESTYLE CARD VARIANT --}}
    <div class="collection-slide">
        <div class="lifestyle-card">
            <div class="lifestyle-img home-card-auto-gallery {{ $hasProductGallery ? 'has-home-gallery' : '' }}" style="position:relative" data-home-card-gallery>
                {{-- Image with link --}}
                <a href="{{ route('product.show', $product['slug']) }}" class="home-card-gallery-link">
                    @foreach ($productImages as $idx => $imageUrl)
                        <img src="{{ $imageUrl }}"
                            class="home-card-gallery-img {{ $loop->first ? 'active' : '' }}"
                            alt="{{ $product['name'] ?? 'Product' }} view {{ $idx + 1 }}"
                            loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                            onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                    @endforeach
                </a>

                {{-- Wishlist Button --}}
                <button type="button" class="wish-btn {{ in_array($product['id'], session('wishlist', [])) ? 'wished' : '' }}"
                    onclick="toggleWishlist({{ $product['id'] }}, this)" aria-label="Wishlist">
                    <i class="bi {{ in_array($product['id'], session('wishlist', [])) ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                </button>

                {{-- Hover Button --}}
                <div class="lifestyle-hover-btn">
                    <button type="button" class="btn-hover-add open-product-slider" data-product-id="{{ $product['id'] }}"
                        data-product-name="{{ $product['name'] }}"
                        data-product-image="{{ $primaryProductImage }}"
                        {{ (isset($product['stock']) && $product['stock'] <= 0) || (isset($product['stock_status']) && $product['stock_status'] === 'out_of_stock') ? 'disabled' : '' }}>
                        SHOP LOOK
                    </button>
                </div>

                {{-- NEW Flag --}}
                @if (isset($product['is_new']) && $product['is_new'])
                    <span class="new-flag">NEW</span>
                @endif
            </div>

            <a href="{{ route('product.show', $product['slug']) }}" class="lifestyle-info" style="text-decoration:none;color:inherit;display:block;cursor:pointer;">
                <h3>{{ $product['name'] }}</h3>
                <p>Rs. {{ number_format($product['price']) }}</p>
            </a>
        </div>
    </div>
@else
    {{-- DEFAULT COLLECTION CARD VARIANT --}}
    <div class="collection-slide">
        <div class="collection-card">
            <div class="collection-img home-card-auto-gallery {{ $hasProductGallery ? 'has-home-gallery' : '' }}" style="position:relative" data-home-card-gallery>

                {{-- Image with multiple fallbacks and lazy loading --}}
                <a href="{{ route('product.show', $product['slug']) }}" class="home-card-gallery-link">
                    @foreach ($productImages as $idx => $imageUrl)
                        <img src="{{ $imageUrl }}"
                            class="home-card-gallery-img {{ $loop->first ? 'active' : '' }}"
                            alt="{{ $product['name'] ?? 'Product' }} view {{ $idx + 1 }}"
                            loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                            onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                    @endforeach
                </a>

                {{-- Wishlist Button --}}
                <button type="button" class="wish-btn {{ in_array($product['id'], session('wishlist', [])) ? 'wished' : '' }}"
                    onclick="toggleWishlist({{ $product['id'] }}, this)" aria-label="Wishlist">
                    <i class="bi {{ in_array($product['id'], session('wishlist', [])) ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                </button>

                {{-- Hover Button (Add to Cart / Quick View) --}}
                <div class="collection-hover-btn">
                    @php
                        $isOutOfStock =
                            (isset($product['stock']) && $product['stock'] <= 0) ||
                            (isset($product['stock_status']) && $product['stock_status'] === 'out_of_stock');
                        $buttonText = $isOutOfStock ? 'OUT OF STOCK' : 'ADD TO CART';
                        $prodImg = $primaryProductImage;
                    @endphp
                    <button type="button" class="btn-hover-add open-product-slider" data-product-id="{{ $product['id'] }}"
                        data-product-name="{{ $product['name'] }}" data-product-price="{{ $product['price'] }}"
                        data-product-image="{{ $prodImg }}"
                        {{ $isOutOfStock ? 'disabled' : '' }}>
                        {{ $buttonText }}
                    </button>
                </div>

                {{-- Product Flags (NEW, HOT, SALE) --}}
                @php
                    $isNew = $product['is_new'] ?? false;
                    $isTrending = $product['is_trending'] ?? false;
                    $isOnSale = $product['is_on_sale'] ?? false;

                    $originalPrice = $product['original_price'] ?? null;
                    $currentPrice = $product['price'] ?? 0;
                    $hasDiscount = $originalPrice && $originalPrice > $currentPrice;
                    $discountPct = $hasDiscount
                        ? (int) round((($originalPrice - $currentPrice) / $originalPrice) * 100)
                        : 0;

                    $showSaleFlag = $hasDiscount || $isOnSale;
                    $showNewFlag = !$showSaleFlag && $isNew;
                    $showTrendingFlag = !$showSaleFlag && !$showNewFlag && $isTrending;
                @endphp

                @if ($showSaleFlag && $discountPct > 0)
                    <span class="product-flag sale-flag">SAVE {{ $discountPct }}%</span>
                @elseif($showSaleFlag && $isOnSale)
                    <span class="product-flag sale-flag">SALE</span>
                @endif

                @if ($showNewFlag)
                    <span class="product-flag new-flag">NEW</span>
                @endif

                @if ($showTrendingFlag)
                    <span class="product-flag trending-flag">HOT</span>
                @endif
            </div>

            {{-- Product Info Section (Clickable) --}}
            <a href="{{ route('product.show', $product['slug']) }}" class="collection-info" style="text-decoration:none;color:inherit;display:block;cursor:pointer;">
                <h3 class="product-title-link">{{ $product['name'] }}</h3>

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
            </a>
        </div>
    </div>

@endif
