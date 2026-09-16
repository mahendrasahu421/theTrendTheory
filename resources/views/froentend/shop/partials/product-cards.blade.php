{{-- resources/views/froentend/shop/partials/product-cards.blade.php --}}
@foreach ($products as $product)
    @php
        $displayOriginalPrice = $product->display_original_price ?: $product->original_price;
        $hasDiscount = $displayOriginalPrice && $displayOriginalPrice > $product->price;
        $discPct = $hasDiscount ? (int) round((($displayOriginalPrice - $product->price) / $displayOriginalPrice) * 100) : 0;
        $inWishlist = in_array($product->id, session('wishlist', []));
        $imagesList = $product->all_images_list;
        $hasMultiple = count($imagesList) > 1;
        $primaryImage = $imagesList[0] ?? asset('images/placeholder-product.jpg');
        $isOutOfStock = ($product->stock ?? 0) <= 0 || ($product->stock_status ?? '') === 'out_of_stock';
    @endphp
    <div class="shop-card {{ $hasMultiple ? 'has-multi-img' : '' }}" data-product-card data-images='@json($imagesList)'>
        @if ($product->is_new)
            <span class="shop-card-badge new">NEW DROP</span>
        @elseif($hasDiscount)
            <span class="shop-card-badge sale">-{{ $discPct }}% OFF</span>
        @endif

        <div class="shop-card-img" data-card-img-wrap>
            <a href="{{ route('product.show', $product->slug) }}" class="shop-card-slider-viewport">
                @foreach ($imagesList as $idx => $img)
                    <div class="shop-card-slide {{ $loop->first ? 'active' : '' }}" data-slide-idx="{{ $idx }}">
                        <img src="{{ $img }}" alt="{{ $product->name }} view {{ $idx + 1 }}" loading="{{ $loop->first ? 'eager' : 'lazy' }}" onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                    </div>
                @endforeach
            </a>

            @if ($hasMultiple)
                <div class="shop-card-img-dots" aria-label="Product image gallery">
                    @foreach ($imagesList as $idx => $img)
                        <button type="button" class="shop-img-dot {{ $loop->first ? 'active' : '' }}"
                            onclick="event.preventDefault(); event.stopPropagation(); jumpCardSlide(this, {{ $idx }})"
                            aria-label="Show image {{ $idx + 1 }}"></button>
                    @endforeach
                </div>

                <button type="button" class="shop-card-nav-btn prev" onclick="event.preventDefault(); event.stopPropagation(); stepCardSlide(this, -1)" aria-label="Previous Image">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button type="button" class="shop-card-nav-btn next" onclick="event.preventDefault(); event.stopPropagation(); stepCardSlide(this, 1)" aria-label="Next Image">
                    <i class="bi bi-chevron-right"></i>
                </button>
            @endif

            <button type="button" class="shop-wishlist-btn {{ $inWishlist ? 'wished' : '' }}" onclick="toggleWishlist({{ $product->id }}, this)" aria-label="Add to Wishlist">
                <i class="bi {{ $inWishlist ? 'bi-heart-fill' : 'bi-heart' }}"></i>
            </button>

            <button type="button" class="shop-quick-add-btn open-product-slider"
                data-product-id="{{ $product->id }}"
                data-product-name="{{ $product->name }}"
                data-product-price="{{ $product->price }}"
                data-product-image="{{ $primaryImage }}"
                {{ $isOutOfStock ? 'disabled' : '' }}>
                <i class="bi bi-bag-plus"></i> {{ $isOutOfStock ? 'OUT OF STOCK' : 'ADD TO CART' }}
            </button>
        </div>

        <a href="{{ route('product.show', $product->slug) }}" class="shop-card-info" style="text-decoration:none; color:inherit; display:flex; flex-direction:column; flex:1; cursor:pointer;">
            <span class="shop-card-category">{{ $product->category?->name ?? 'Streetwear' }}</span>
            <span class="shop-card-name">{{ $product->name }}</span>
            <div class="shop-card-pricing">
                <span class="shop-card-price">₹{{ number_format($product->price) }}</span>
                @if ($hasDiscount)
                    <span class="shop-card-mrp">₹{{ number_format($displayOriginalPrice) }}</span>
                    <span class="shop-card-save">{{ $discPct }}% OFF</span>
                @endif
            </div>
        </a>
    </div>
@endforeach
