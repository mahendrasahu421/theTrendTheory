{{-- resources/views/froentend/cart/success.blade.php --}}
@extends('froentend.layouts.app')

@push('seo')
    <title>Order Confirmed #{{ $order->order_number }} | The Trend Theory</title>
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
        max-width: 680px;
        margin: 0 auto;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 6px 28px rgba(15, 23, 42, 0.06);
    }

    /* Celebratory Header Strip */
    .success-head-strip {
        background: linear-gradient(135deg, #06152d 0%, #00285a 100%);
        padding: 28px 20px 24px;
        color: #ffffff;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .success-head-strip::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(16, 185, 129, 0.12) 0%, transparent 65%);
        pointer-events: none;
    }

    .success-check-icon {
        width: 52px;
        height: 52px;
        background: #10b981;
        color: #ffffff;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        margin: 0 auto 12px;
        box-shadow: 0 0 0 8px rgba(16, 185, 129, 0.22);
        animation: successPulse 2s infinite;
    }

    @keyframes successPulse {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.45); }
        70% { box-shadow: 0 0 0 12px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    .success-card-title {
        font-size: 22px;
        font-weight: 800;
        margin: 0 0 6px;
        letter-spacing: -0.3px;
    }

    .success-card-desc {
        font-size: 13.5px;
        color: #cbd5e1;
        margin: 0 auto 14px;
        line-height: 1.45;
        max-width: 480px;
    }

    .order-meta-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.25);
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        color: #ffffff;
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
    }

    .order-meta-pill:hover,
    .order-meta-pill:active {
        background: rgba(255, 255, 255, 0.22);
        transform: scale(1.02);
    }

    .order-copy-hint {
        font-size: 10px;
        background: rgba(255, 255, 255, 0.2);
        padding: 2px 6px;
        border-radius: 4px;
        margin-left: 2px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    /* Card Body */
    .success-body {
        padding: 22px 24px;
    }

    /* ── Order Status Stepper ── */
    .order-status-stepper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px 18px;
        margin-bottom: 20px;
    }

    .stepper-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
        position: relative;
        z-index: 1;
    }

    .stepper-dot {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #e2e8f0;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 800;
    }

    .stepper-step.completed .stepper-dot {
        background: #10b981;
        color: #ffffff;
    }

    .stepper-step.active .stepper-dot {
        background: #00285a;
        box-shadow: 0 0 0 4px rgba(0, 40, 90, 0.15);
        color: #ffffff;
    }

    .stepper-step.active .stepper-dot::after {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #ffffff;
    }

    .stepper-line {
        flex: 1;
        height: 2px;
        background: #e2e8f0;
        margin: 0 6px 16px;
    }

    .stepper-line.completed {
        background: #10b981;
    }

    .stepper-label {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
    }

    .stepper-step.active .stepper-label {
        color: #00285a;
        font-weight: 800;
    }

    .stepper-step.completed .stepper-label {
        color: #10b981;
    }

    /* ── 2-Column Info Grid ── */
    .info-dual-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 20px;
    }

    .info-box {
        background: #f8fafc;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        padding: 14px;
        font-size: 12.5px;
        line-height: 1.45;
        color: #475569;
    }

    .info-box-title {
        font-size: 11.5px;
        font-weight: 800;
        text-transform: uppercase;
        color: #0f172a;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 7px;
        letter-spacing: 0.3px;
    }

    .info-phone-link {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #00285a;
        text-decoration: none;
        font-weight: 700;
        margin-top: 6px;
        padding: 3px 8px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 11.5px;
        transition: all 0.15s ease;
    }

    .info-phone-link:hover {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #2563eb;
    }

    /* ── Items Ordered Section ── */
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

    .items-mini-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .item-mini-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px;
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        transition: background 0.15s ease;
    }

    .item-mini-thumb {
        width: 60px;
        height: 74px;
        object-fit: cover;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        flex-shrink: 0;
    }

    .item-mini-detail {
        flex: 1;
        min-width: 0;
    }

    .item-mini-name {
        font-size: 13.5px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.35;
        margin-bottom: 4px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .item-mini-meta {
        font-size: 11px;
        color: #64748b;
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    .item-mini-meta span {
        background: #f1f5f9;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 11px;
    }

    .item-mini-price {
        font-size: 14.5px;
        font-weight: 800;
        color: #00285a;
        text-align: right;
        white-space: nowrap;
        margin-left: 8px;
    }

    /* ── Pricing Summary ── */
    .pricing-summary-card {
        background: #fafcff;
        border: 1px solid #e0e7ff;
        border-radius: 14px;
        padding: 14px 16px;
        margin-top: 16px;
        font-size: 13px;
    }

    .price-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #64748b;
        margin-bottom: 8px;
    }

    .price-line b,
    .price-line strong {
        color: #0f172a;
        font-weight: 700;
    }

    .price-line.total-line {
        font-size: 15px;
        font-weight: 900;
        color: #0f172a;
        border-top: 1.5px dashed #cbd5e1;
        padding-top: 10px;
        margin-top: 10px;
        margin-bottom: 0;
    }

    .total-amount-val {
        font-size: 18px;
        color: #00285a;
        font-weight: 900;
    }

    /* ── Action Buttons Row ── */
    .actions-row {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 22px;
    }

    .actions-primary-btn {
        width: 100%;
        height: 50px;
        background: linear-gradient(135deg, #00285a 0%, #0f4c81 100%);
        color: #ffffff !important;
        border-radius: 12px;
        font-size: 14.5px;
        font-weight: 800;
        letter-spacing: 0.4px;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 6px 20px rgba(0, 40, 90, 0.22);
        transition: transform 0.18s ease, box-shadow 0.18s ease;
    }

    .actions-primary-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(0, 40, 90, 0.28);
    }

    .actions-secondary-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .actions-sub-btn {
        height: 44px;
        border-radius: 10px;
        font-size: 12.5px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        border: 1px solid #e2e8f0;
        transition: all 0.15s ease;
    }

    .btn-pdf-inv {
        background: #ffffff;
        color: #0f172a;
        border-color: #cbd5e1;
    }

    .btn-pdf-inv:hover {
        background: #f8fafc;
        border-color: #94a3b8;
    }

    .btn-wa-inv {
        background: #ecfdf5;
        color: #065f46;
        border-color: #a7f3d0;
    }

    .btn-wa-inv:hover {
        background: #d1fae5;
        color: #047857;
    }

    .btn-continue-shop {
        width: 100%;
        height: 44px;
        background: #ffffff;
        color: #475569;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.15s ease;
    }

    .btn-continue-shop:hover {
        background: #f8fafc;
        color: #0f172a;
        border-color: #94a3b8;
    }

    /* ── Trust Perks Strip ── */
    .success-trust-perks {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        background: #f8fafc;
        border: 1px solid #edf2f7;
        border-radius: 12px;
        padding: 12px 14px;
        margin-top: 18px;
    }

    .trust-perk-item {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 11.5px;
        font-weight: 700;
        color: #475569;
    }

    .trust-perk-item i {
        font-size: 15px;
        flex-shrink: 0;
    }

    /* ═══════════════════════════════════════════════════════════
       EXPLORE MORE / RECOMMENDATIONS SECTION
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
    }

    .explore-wish-btn.wished {
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

    /* ── Mobile Responsive Overrides ── */
    @media (max-width: 768px) {
        .order-success-container {
            margin: 12px auto 40px !important;
            padding: 0 12px !important;
        }

        .success-main-card {
            border-radius: 16px;
        }

        .success-head-strip {
            padding: 22px 16px 20px;
        }

        .success-check-icon {
            width: 46px;
            height: 46px;
            font-size: 23px;
            margin-bottom: 10px;
        }

        .success-card-title {
            font-size: 19px;
        }

        .success-card-desc {
            font-size: 12.5px;
            margin-bottom: 12px;
        }

        .order-meta-pill {
            font-size: 11.5px;
            padding: 5px 12px;
        }

        .success-body {
            padding: 16px 14px;
        }

        .order-status-stepper {
            padding: 12px 10px;
            margin-bottom: 16px;
        }

        .stepper-dot {
            width: 20px;
            height: 20px;
            font-size: 10px;
        }

        .stepper-label {
            font-size: 10px;
        }

        .stepper-line {
            margin: 0 4px 14px;
        }

        .info-dual-grid {
            grid-template-columns: 1fr;
            gap: 10px;
            margin-bottom: 16px;
        }

        .info-box {
            padding: 12px;
        }

        .item-mini-thumb {
            width: 54px;
            height: 66px;
        }

        .item-mini-name {
            font-size: 13px;
        }

        .pricing-summary-card {
            padding: 12px 14px;
            margin-top: 14px;
        }

        .price-line.total-line {
            font-size: 14.5px;
        }

        .total-amount-val {
            font-size: 17px;
        }

        .actions-primary-btn {
            height: 48px;
            font-size: 14px;
        }

        .actions-sub-btn {
            height: 42px;
            font-size: 11.5px;
        }

        .success-trust-perks {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            padding: 10px 12px;
        }

        .trust-perk-item {
            font-size: 10.5px;
        }

        /* Recommendations on Mobile */
        .success-explore-wrapper {
            margin-top: 32px;
            padding-top: 24px;
        }

        .explore-head-wrap {
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
            margin-bottom: 14px;
        }

        .explore-title-box h2 {
            font-size: 16px !important;
        }

        .explore-title-box p {
            font-size: 12px !important;
        }

        .success-products-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 10px !important;
        }

        .explore-info-box {
            padding: 10px !important;
        }

        .explore-product-name {
            font-size: 12px !important;
        }

        .explore-curr-price {
            font-size: 13.5px !important;
        }
    }

    /* ── Quick Toast Notification ── */
    #successToast {
        position: fixed;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%) translateY(100px);
        background: #0f172a;
        color: #ffffff;
        font-size: 13px;
        font-weight: 700;
        padding: 10px 20px;
        border-radius: 999px;
        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.28);
        z-index: 99999;
        opacity: 0;
        pointer-events: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    #successToast.is-shown {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }
</style>
@endpush

@section('main')
<div class="order-success-container">
    
    <div class="success-main-card">
        
        {{-- 1. Celebratory Header Strip --}}
        <div class="success-head-strip">
            <div class="success-check-icon">
                <i class="bi bi-check-lg"></i>
            </div>
            <h1 class="success-card-title">Order Confirmed!</h1>
            <p class="success-card-desc">
                Thank you, <strong>{{ $order->shipping_name }}</strong>. We have received your order and are preparing your parcel for dispatch.
            </p>
            <div class="order-meta-pill" onclick="copyOrderNumber('{{ $order->order_number }}')" title="Click to copy order number">
                <i class="bi bi-bag-check-fill text-warning"></i>
                <span>#{{ $order->order_number }}</span>
                <span>&bull;</span>
                <span>{{ $order->created_at ? $order->created_at->format('d M Y') : date('d M Y') }}</span>
                <span class="order-copy-hint"><i class="bi bi-copy"></i> Copy</span>
            </div>
        </div>

        {{-- 2. Card Body --}}
        <div class="success-body">
            
            {{-- Order Fulfillment Progress Stepper --}}
            <div class="order-status-stepper">
                <div class="stepper-step completed">
                    <div class="stepper-dot"><i class="bi bi-check-lg"></i></div>
                    <div class="stepper-label">Placed</div>
                </div>
                <div class="stepper-line completed"></div>
                <div class="stepper-step active">
                    <div class="stepper-dot"></div>
                    <div class="stepper-label">Processing</div>
                </div>
                <div class="stepper-line"></div>
                <div class="stepper-step">
                    <div class="stepper-dot"></div>
                    <div class="stepper-label">Shipped</div>
                </div>
                <div class="stepper-line"></div>
                <div class="stepper-step">
                    <div class="stepper-dot"></div>
                    <div class="stepper-label">Delivered</div>
                </div>
            </div>

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
                    @if($order->shipping_phone)
                        <div>
                            <a href="tel:{{ $order->shipping_phone }}" class="info-phone-link">
                                <i class="bi bi-telephone-fill text-success"></i> Call {{ $order->shipping_phone }}
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Payment & Status --}}
                <div class="info-box">
                    <div class="info-box-title">
                        <i class="bi bi-credit-card-2-front-fill text-success"></i> Payment &amp; Status
                    </div>
                    <div>
                        <strong>Method:</strong> 
                        <span class="text-uppercase fw-bold text-dark">{{ $order->payment_method ?: 'Online' }}</span>
                    </div>
                    <div style="margin-top: 4px;">
                        <strong>Status:</strong> 
                        <span class="badge {{ strtolower($order->payment_status) === 'paid' ? 'bg-success' : 'bg-warning text-dark' }} px-2 py-0.5" style="font-size: 11px;">
                            {{ strtoupper($order->payment_status ?: 'PENDING') }}
                        </span>
                    </div>
                    <div style="margin-top: 6px; color: #059669; font-weight: 700;">
                        <i class="bi bi-truck"></i> Est. Delivery: 3-5 Business Days
                    </div>
                </div>
            </div>

            {{-- Items Ordered --}}
            <div class="items-section-title">
                <span>Items Ordered ({{ $order->items->count() }})</span>
                <span class="badge bg-light text-dark border font-monospace" style="font-size: 10.5px;">Surface Express</span>
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
                    <span>Bag Subtotal:</span>
                    <strong>₹{{ number_format($order->subtotal) }}</strong>
                </div>
                <div class="price-line">
                    <span>Shipping &amp; Express Handling:</span>
                    <strong class="{{ $order->shipping_charge == 0 ? 'text-success' : '' }}">
                        {{ $order->shipping_charge > 0 ? '₹' . number_format($order->shipping_charge) : 'FREE' }}
                    </strong>
                </div>
                @if($order->discount_amount > 0)
                    <div class="price-line" style="color: #059669;">
                        <span><i class="bi bi-tag-fill me-1"></i> Coupon Discount:</span>
                        <strong>-₹{{ number_format($order->discount_amount) }}</strong>
                    </div>
                @endif
                <div class="price-line total-line">
                    <div>
                        <span>Total Amount {{ strtolower($order->payment_status) === 'paid' ? 'Paid' : 'Payable' }}:</span>
                        <div style="font-size: 11px; color: #64748b; font-weight: 500;">(Inclusive of all taxes &amp; GST)</div>
                    </div>
                    <span class="total-amount-val">₹{{ number_format($order->total_amount) }}</span>
                </div>
            </div>

            {{-- Action Buttons Hierarchy --}}
            <div class="actions-row">
                {{-- Primary Full-Width CTA --}}
                <a href="{{ route('order.track.detail', $order->order_number) }}" class="actions-primary-btn">
                    <i class="bi bi-truck text-warning"></i>
                    <span>TRACK ORDER LIVE</span>
                    <i class="bi bi-arrow-right"></i>
                </a>

                {{-- Secondary 2-Column Grid --}}
                <div class="actions-secondary-grid">
                    <a href="{{ route('invoice.download', $order->order_number) }}" target="_blank" class="actions-sub-btn btn-pdf-inv">
                        <i class="bi bi-file-earmark-pdf-fill text-danger"></i> PDF Invoice
                    </a>

                    @if($order->shipping_phone)
                        <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $order->shipping_phone) }}?text=Hello%20{{ urlencode($order->shipping_name) }},%20your%20Order%20{{ $order->order_number }}%20from%20The%20Trend%20Theory%20is%20confirmed!" 
                           target="_blank" 
                           class="actions-sub-btn btn-wa-inv">
                            <i class="bi bi-whatsapp"></i> WhatsApp Updates
                        </a>
                    @else
                        <a href="{{ route('shop.index') }}" class="actions-sub-btn btn-pdf-inv">
                            <i class="bi bi-bag"></i> Continue Shopping
                        </a>
                    @endif
                </div>

                {{-- Tertiary Full-Width Button --}}
                <a href="{{ route('shop.index') }}" class="btn-continue-shop">
                    <i class="bi bi-arrow-left"></i> Continue Shopping &amp; Explore Drops
                </a>
            </div>

            {{-- Trust Perks Strip --}}
            <div class="success-trust-perks">
                <div class="trust-perk-item">
                    <i class="bi bi-patch-check-fill text-primary"></i>
                    <span>100% Original Quality</span>
                </div>
                <div class="trust-perk-item">
                    <i class="bi bi-arrow-repeat text-success"></i>
                    <span>7-Day Easy Returns</span>
                </div>
                <div class="trust-perk-item">
                    <i class="bi bi-shield-check text-warning"></i>
                    <span>Insured Express Transit</span>
                </div>
                <div class="trust-perk-item">
                    <i class="bi bi-headset text-info"></i>
                    <span>24x7 Customer Support</span>
                </div>
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

{{-- Toast Notification Element --}}
<div id="successToast">
    <i class="bi bi-check-circle-fill text-success"></i>
    <span id="successToastMsg">Copied to clipboard!</span>
</div>

@push('scripts')
<script>
function copyOrderNumber(orderNum) {
    if (!orderNum) return;
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(orderNum).then(function() {
            showSuccessToast('Order #' + orderNum + ' copied to clipboard!');
        }).catch(function() {
            fallbackCopy(orderNum);
        });
    } else {
        fallbackCopy(orderNum);
    }
}

function fallbackCopy(text) {
    var textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.opacity = '0';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    try {
        document.execCommand('copy');
        showSuccessToast('Order #' + text + ' copied to clipboard!');
    } catch (err) {}
    document.body.removeChild(textArea);
}

function showSuccessToast(msg) {
    var toast = document.getElementById('successToast');
    var msgEl = document.getElementById('successToastMsg');
    if (!toast) return;
    if (msgEl) msgEl.textContent = msg;
    toast.classList.add('is-shown');
    setTimeout(function() {
        toast.classList.remove('is-shown');
    }, 2800);
}
</script>
@endpush
@endsection
