{{-- resources/views/froentend/cart/success.blade.php --}}
@extends('froentend.layouts.app')

@push('seo')
    <title>Order Confirmed #{{ $order->order_number }} | Vayu</title>
    <meta name="robots" content="noindex, nofollow">
@endpush

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap');

    .order-success-container {
        max-width: 1100px;
        margin: 32px auto 60px;
        padding: 0 16px;
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Main Compact Card */
    .success-main-card {
        max-width: 660px;
        margin: 0 auto;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.05);
    }

    /* Header Strip */
    .success-head-strip {
        background: linear-gradient(135deg, #0b192e 0%, #00285a 100%);
        padding: 24px 20px;
        color: #ffffff;
        text-align: center;
    }

    .success-check-icon {
        width: 48px;
        height: 48px;
        background: #10b981;
        color: #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin: 0 auto 12px;
        box-shadow: 0 0 0 6px rgba(16, 185, 129, 0.2);
    }

    .success-card-title {
        font-size: 20px;
        font-weight: 800;
        margin: 0 0 6px;
        letter-spacing: -0.3px;
    }

    .success-card-desc {
        font-size: 13px;
        color: #cbd5e1;
        margin: 0 0 14px;
        line-height: 1.45;
    }

    .order-meta-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 5px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        color: #ffffff;
    }

    /* Card Body */
    .success-body {
        padding: 20px 22px;
    }

    /* 2-Column Info Grid */
    .info-dual-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-bottom: 20px;
    }

    @media (max-width: 540px) {
        .info-dual-grid {
            grid-template-columns: 1fr;
        }
    }

    .info-box {
        background: #f8fafc;
        border: 1px solid #edf2f7;
        border-radius: 12px;
        padding: 12px 14px;
        font-size: 12px;
        line-height: 1.45;
        color: #475569;
    }

    .info-box-title {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        color: #0f172a;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
        letter-spacing: 0.3px;
    }

    /* Items Section */
    .items-section-title {
        font-size: 12.5px;
        font-weight: 800;
        text-transform: uppercase;
        color: #0f172a;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        letter-spacing: 0.3px;
    }

    .item-mini-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid #f8fafc;
    }

    .item-mini-row:last-child {
        border-bottom: none;
    }

    .item-mini-thumb {
        width: 52px;
        height: 64px;
        object-fit: cover;
        border-radius: 8px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        flex-shrink: 0;
    }

    .item-mini-detail {
        flex: 1;
        min-width: 0;
    }

    .item-mini-name {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .item-mini-meta {
        font-size: 11.5px;
        color: #64748b;
        display: flex;
        gap: 6px;
    }

    .item-mini-price {
        font-size: 13.5px;
        font-weight: 800;
        color: #00285a;
        text-align: right;
    }

    /* Pricing Summary */
    .pricing-summary-card {
        background: #fafcff;
        border: 1px solid #e0e7ff;
        border-radius: 12px;
        padding: 12px 16px;
        margin-top: 14px;
        font-size: 12.5px;
    }

    .price-line {
        display: flex;
        justify-content: space-between;
        color: #64748b;
        margin-bottom: 6px;
    }

    .price-line.total-line {
        font-size: 15px;
        font-weight: 900;
        color: #0f172a;
        border-top: 1px dashed #cbd5e1;
        padding-top: 8px;
        margin-top: 8px;
        margin-bottom: 0;
    }

    /* Action Buttons Row */
    .actions-row {
        display: flex;
        gap: 10px;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .btn-action-custom {
        flex: 1;
        min-width: 140px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 12.5px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.15s ease;
        border: none;
        cursor: pointer;
    }

    .btn-pdf-inv {
        background: #0f172a;
        color: #ffffff;
    }

    .btn-wa-inv {
        background: #10b981;
        color: #ffffff;
    }

    .btn-continue {
        background: #ffffff;
        color: #475569;
        border: 1px solid #cbd5e1;
    }

    /* ═══════════════════════════════════════════════════════════
       RECENTLY VIEWED & EXPLORE MORE SECTION (ORDER SUCCESS)
       ═══════════════════════════════════════════════════════════ */
    .success-explore-wrapper {
        margin-top: 48px;
        padding-top: 32px;
        border-top: 1.5px dashed #cbd5e1;
    }

    .explore-head-wrap {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 22px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .explore-title-box h2 {
        font-family: 'Cinzel', serif !important;
        font-size: 19px;
        font-weight: 800;
        color: #0b192e;
        letter-spacing: 0.6px;
        margin: 0 0 4px;
        display: flex;
        align-items: center;
        gap: 8px;
        text-transform: uppercase;
    }

    .explore-title-box p {
        font-size: 13px;
        color: #64748b;
        margin: 0;
    }

    .explore-view-all {
        font-size: 13px;
        font-weight: 700;
        color: #00285a;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: color 0.15s ease;
    }

    .success-products-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }

    @media (max-width: 900px) {
        .success-products-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
        }
    }

    @media (max-width: 600px) {
        .success-products-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        .explore-title-box h2 {
            font-size: 17px;
        }
    }

    .explore-product-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: all 0.22s ease;
        position: relative;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
    }

    .explore-media-box {
        position: relative;
        width: 100%;
        aspect-ratio: 3/4;
        background: #f8fafc;
        overflow: hidden;
    }

    .explore-media-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.35s ease;
    }

    .explore-badge {
        position: absolute;
        top: 8px;
        left: 8px;
        background: #ff3f6c;
        color: #ffffff;
        font-size: 10px;
        font-weight: 800;
        padding: 2px 6px;
        border-radius: 4px;
        line-height: 1;
        z-index: 2;
    }

    .explore-badge.new { background: #00285a; }
    .explore-badge.trending { background: #ea580c; }

    .explore-wish-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(4px);
        border: 0;
        color: #475569;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.2s ease;
        z-index: 2;
    }.explore-wish-btn.wished {
        background: #ffffff;
        color: #ff3f6c;
        transform: scale(1.1);
    }

    .explore-info-box {
        padding: 12px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .explore-product-name {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
        text-decoration: none;
        line-height: 1.35;
        margin-bottom: 6px;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.15s ease;
    }

    .explore-price-row {
        display: flex;
        align-items: baseline;
        gap: 6px;
        margin-top: auto;
    }

    .explore-curr-price {
        font-size: 14.5px;
        font-weight: 800;
        color: #00285a;
    }

    .explore-orig-price {
        font-size: 11.5px;
        color: #94a3b8;
        text-decoration: line-through;
    }

    .explore-disc-pill {
        font-size: 10.5px;
        font-weight: 800;
        color: #059669;
    }
</style>
@endpush

@section('main')
<div class="order-success-container">
    
    <div class="success-main-card">
        
        {{-- 1. Compact Header Strip --}}
        <div class="success-head-strip">
            <div class="success-check-icon">
                <i class="bi bi-check-lg"></i>
            </div>
            <h1 class="success-card-title">Order Confirmed!</h1>
            <p class="success-card-desc">
                Thank you, <strong>{{ $order->shipping_name }}</strong>. We have received your order and are preparing your parcel for dispatch.
            </p>
            <div class="order-meta-pill">
                <i class="bi bi-bag-check-fill text-warning"></i>
                <span>Order #{{ $order->order_number }}</span>
                <span>&bull;</span>
                <span>{{ $order->created_at ? $order->created_at->format('d M Y') : date('d M Y') }}</span>
            </div>
        </div>

        {{-- 2. Card Body --}}
        <div class="success-body">
            
            {{-- 2-Column Delivery & Payment Info --}}
            <div class="info-dual-grid">
                {{-- Delivery Destination --}}
                <div class="info-box">
                    <div class="info-box-title">
                        <i class="bi bi-geo-alt-fill text-danger"></i> Deliver To
                    </div>
                    <strong class="d-block text-dark" style="margin-bottom: 2px;">{{ $order->shipping_name }}</strong>
                    <div>{{ $order->shipping_address }}</div>
                    <div><strong>{{ $order->shipping_city }}</strong>, {{ $order->shipping_state }} - {{ $order->shipping_pincode }}</div>
                    <div style="margin-top: 3px; color: #64748b;"><i class="bi bi-telephone"></i> {{ $order->shipping_phone }}</div>
                </div>

                {{-- Payment & Status --}}
                <div class="info-box">
                    <div class="info-box-title">
                        <i class="bi bi-credit-card text-success"></i> Payment &amp; Status
                    </div>
                    <div>
                        <strong>Method:</strong> 
                        <span class="text-uppercase">{{ $order->payment_method ?: 'Online' }}</span>
                    </div>
                    <div style="margin-top: 2px;">
                        <strong>Status:</strong> 
                        <span class="badge {{ strtolower($order->payment_status) === 'paid' ? 'bg-success' : 'bg-warning text-dark' }} px-2 py-0.5" style="font-size: 10.5px;">
                            {{ strtoupper($order->payment_status ?: 'PENDING') }}
                        </span>
                    </div>
                    <div style="margin-top: 4px; color: #059669; font-weight: 600;">
                        <i class="bi bi-truck"></i> Est. Delivery: 3-5 Days
                    </div>
                </div>
            </div>

            {{-- Items Ordered --}}
            <div class="items-section-title">
                <span>Items Ordered ({{ $order->items->count() }})</span>
                <span class="text-muted" style="font-size: 11px; font-weight: normal;">Surface Express</span>
            </div>

            <div class="items-mini-list">
                @foreach($order->items as $item)
                    <div class="item-mini-row">
                        <img src="{{ $item->product_image ?: ($item->product->main_image ?? asset('assets/images/placeholder.png')) }}" 
                             class="item-mini-thumb" 
                             alt="{{ $item->product_name }}"
                             onerror="this.src='{{ asset('assets/images/placeholder.png') }}'">
                        <div class="item-mini-detail">
                            <div class="item-mini-name" title="{{ $item->product_name }}">{{ $item->product_name }}</div>
                            <div class="item-mini-meta">
                                @if($item->size) <span>Size: <strong>{{ $item->size }}</strong></span> @endif
                                @if($item->color) <span>Color: <strong>{{ $item->color }}</strong></span> @endif
                                @if(!empty($item->design_side)) <span>Print: <strong>{{ ucfirst($item->design_side) }} Side</strong></span> @endif
                                <span>Qty: <strong>{{ $item->quantity }}</strong></span>
                            </div>
                        </div>
                        <div class="item-mini-price">
                            ₹{{ number_format($item->subtotal ?: ($item->unit_price * $item->quantity)) }}
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pricing Summary --}}
            <div class="pricing-summary-card">
                <div class="price-line">
                    <span>Subtotal:</span>
                    <strong>₹{{ number_format($order->subtotal) }}</strong>
                </div>
                <div class="price-line">
                    <span>Shipping &amp; Delivery:</span>
                    <strong>{{ $order->shipping_charge > 0 ? '₹' . number_format($order->shipping_charge) : 'FREE' }}</strong>
                </div>
                @if($order->discount_amount > 0)
                    <div class="price-line" style="color: #059669;">
                        <span>Coupon Discount:</span>
                        <strong>-₹{{ number_format($order->discount_amount) }}</strong>
                    </div>
                @endif
                <div class="price-line total-line">
                    <span>Total Amount Paid:</span>
                    <span style="color: #059669;">₹{{ number_format($order->total_amount) }}</span>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="actions-row">
                <a href="{{ route('order.track.detail', $order->order_number) }}" class="btn-action-custom" style="background: #00285a; color: #ffffff;">
                    <i class="bi bi-truck text-warning"></i> Track Order Live
                </a>

                <a href="{{ route('invoice.download', $order->order_number) }}" target="_blank" class="btn-action-custom btn-pdf-inv">
                    <i class="bi bi-file-earmark-pdf-fill text-danger"></i> PDF Invoice
                </a>

                @if($order->shipping_phone)
                    <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $order->shipping_phone) }}?text=Hello%20{{ urlencode($order->shipping_name) }},%20your%20Order%20{{ $order->order_number }}%20from%20The%20Trend%20Theory%20is%20confirmed!" 
                       target="_blank" 
                       class="btn-action-custom btn-wa-inv">
                        <i class="bi bi-whatsapp"></i> WhatsApp Updates
                    </a>
                @endif

                <a href="{{ route('shop.index') }}" class="btn-action-custom btn-continue">
                    <i class="bi bi-arrow-left"></i> Shop More
                </a>
            </div>

        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         RECENTLY VIEWED PRODUCTS / EXPLORE MORE RECOMMENDATIONS
         ═══════════════════════════════════════════════════════════ --}}
    @php
        $hasRecent = isset($recentlyViewedProducts) && $recentlyViewedProducts->isNotEmpty();
        $hasRecommended = isset($recommendedProducts) && $recommendedProducts->isNotEmpty();
        $displayProducts = $hasRecent ? $recentlyViewedProducts : $recommendedProducts;
    @endphp

    @if($displayProducts->isNotEmpty())
        <section class="success-explore-wrapper" aria-label="Recently Viewed and Recommended Products">
            <div class="explore-head-wrap">
                <div class="explore-title-box">
                    <h2>
                        @if($hasRecent)
                            <i class="bi bi-clock-history text-primary"></i> RECENTLY VIEWED PRODUCTS
                        @else
                            <i class="bi bi-stars text-warning"></i> YOU MIGHT ALSO LIKE
                        @endif
                    </h2>
                    <p>
                        @if($hasRecent)
                            Items you recently checked out — explore colors, sizes, or complete your wardrobe!
                        @else
                            Hand-picked streetwear styles trending right now.
                        @endif
                    </p>
                </div>
                <a href="{{ route('shop.index') }}" class="explore-view-all">
                    Explore Full Collection <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="success-products-grid">
                @foreach($displayProducts->take(8) as $prod)
                    @php
                        $prodImg = $prod->card_image ?: ($prod->image_url ?: ($prod->image ?: asset('images/placeholder-product.jpg')));
                        $hasDisc = $prod->original_price && $prod->original_price > $prod->price;
                        $discPct = $hasDisc ? (int)round((($prod->original_price - $prod->price) / $prod->original_price) * 100) : 0;
                    @endphp
                    <div class="explore-product-card">
                        <div class="explore-media-box">
                            <a href="{{ route('product.show', $prod->slug) }}">
                                <img src="{{ $prodImg }}" alt="{{ $prod->name }}" loading="lazy" onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                            </a>

                            {{-- Wishlist Button --}}
                            <button type="button" class="explore-wish-btn {{ in_array($prod->id, session('wishlist', [])) ? 'wished' : '' }}"
                                onclick="toggleWishlist({{ $prod->id }}, this)" aria-label="Wishlist">
                                <i class="bi {{ in_array($prod->id, session('wishlist', [])) ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                            </button>

                            {{-- Discount / New Badges --}}
                            @if($hasDisc && $discPct > 0)
                                <span class="explore-badge">-{{ $discPct }}%</span>
                            @elseif($prod->is_new)
                                <span class="explore-badge new">NEW</span>
                            @elseif($prod->is_trending)
                                <span class="explore-badge trending">HOT</span>
                            @endif
                        </div>

                        <div class="explore-info-box">
                            <a href="{{ route('product.show', $prod->slug) }}" class="explore-product-name" title="{{ $prod->name }}">
                                {{ $prod->name }}
                            </a>
                            <div class="explore-price-row">
                                <span class="explore-curr-price">₹{{ number_format($prod->price) }}</span>
                                @if($hasDisc)
                                    <span class="explore-orig-price">₹{{ number_format($prod->original_price) }}</span>
                                    <span class="explore-disc-pill">{{ $discPct }}% OFF</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

</div>
@endsection
