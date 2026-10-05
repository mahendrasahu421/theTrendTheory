{{-- resources/views/admin/orders/show.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Order ' . $order->order_number . ' - Fulfillment Studio')

@section('content')
@php
    $isCod = strtolower($order->payment_method ?? '') === 'cod' || empty($order->payment_method);
    $payMethod = strtoupper($order->payment_method ?: 'COD');

    $statusSteps = [
        'pending'    => ['label' => 'Order Placed', 'icon' => 'bi-bag-check-fill'],
        'confirmed'  => ['label' => 'Confirmed', 'icon' => 'bi-shield-check'],
        'processing' => ['label' => 'Packaging', 'icon' => 'bi-box-seam-fill'],
        'shipped'    => ['label' => 'Shipped / In-Transit', 'icon' => 'bi-truck'],
        'delivered'  => ['label' => 'Delivered', 'icon' => 'bi-check-circle-fill']
    ];

    $stepKeys = array_keys($statusSteps);
    $currentStatusIndex = array_search($order->status, $stepKeys);
    if ($currentStatusIndex === false) {
        $currentStatusIndex = $order->status === 'cancelled' || $order->status === 'refunded' ? -1 : 0;
    }
@endphp
a
{{-- Shared design layer --}}
@include('admin.orders._orders-ui')

<div class="order-studio-wrap">

    {{-- ── 1. Executive Studio Banner ── --}}
    <div class="order-hero-banner">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <div class="order-hero-badge">
                    <span class="order-pulse-dot"></span>
                    <span>EXECUTIVE ORDER DISPATCH &amp; FULFILLMENT</span>
                </div>
                <h1 class="order-hero-title">
                    <span>Order {{ $order->order_number }}</span>
                    @if($isCod)
                        <span style="font-size:11.5px; padding:4px 10px; border-radius:8px; background:#fffbeb; color:#b45309; border:1px solid #fde68a; font-weight:800;">
                            <i class="bi bi-cash-stack"></i> CASH ON DELIVERY
                        </span>
                    @else
                        <span style="font-size:11.5px; padding:4px 10px; border-radius:8px; background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; font-weight:800;">
                            <i class="bi bi-credit-card-2-front-fill"></i> PREPAID ({{ $payMethod }})
                        </span>
                    @endif
                </h1>
                <p class="order-hero-desc">
                    Customer purchase placed on <strong>{{ $order->created_at->format('d M Y, h:i A') }}</strong> &bull; Total volume of <strong>{{ $order->items->count() }} items</strong> for ₹{{ number_format($order->total_amount) }}.
                </p>
            </div>

            <div class="order-hero-actions">
                <a href="{{ route('admin.orders.index') }}" class="btn-hero-back">
                    <i class="bi bi-arrow-left"></i> All Orders
                </a>
                <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="btn btn-sm btn-light border fw-bold px-3 py-2" style="border-radius:10px; text-decoration:none; color:#334155;">
                    <i class="bi bi-file-earmark-pdf-fill me-1 text-danger"></i> PDF Tax Invoice
                </a>
                <a href="{{ route('admin.orders.shipping-label', $order) }}" target="_blank" class="btn btn-sm btn-light border fw-bold px-3 py-2" style="border-radius:10px; text-decoration:none; color:#334155;">
                    <i class="bi bi-tag-fill me-1 text-warning"></i> Parcel Label (Sticker)
                </a>
                @if($order->shipping_phone)
                    <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $order->shipping_phone) }}?text=Hello%20{{ urlencode($order->shipping_name) }},%20your%20Order%20{{ $order->order_number }}%20from%20The%20Trend%20Theory%20is%20being%20processed!" 
                       target="_blank" 
                       class="btn btn-sm btn-success fw-bold px-3 py-2" 
                       style="border-radius:10px; background:#10b981; border-color:#10b981;">
                        <i class="bi bi-whatsapp me-1"></i> WhatsApp
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- ── 2. Top 4 Bento KPI Metric Cards ── --}}
    <div class="bento-kpi-grid">
        {{-- Total Amount --}}
        <div class="bento-order-card">
            <div class="bento-card-head">
                <span class="bento-card-label">Payable Amount</span>
                <div class="bento-icon-box" style="background:#eff6ff; color:#2563eb;">
                    <i class="bi bi-currency-rupee"></i>
                </div>
            </div>
            <div class="bento-num">₹{{ number_format($order->total_amount) }}</div>
            <div class="bento-sub">
                @if($order->payment_status === 'paid')
                    <span class="text-success fw-bold"><i class="bi bi-check-circle-fill"></i> Paid &amp; Cleared</span>
                @else
                    <span class="text-warning fw-bold"><i class="bi bi-clock-history"></i> Pending Collection</span>
                @endif
            </div>
        </div>

        {{-- Order Status --}}
        <div class="bento-order-card">
            <div class="bento-card-head">
                <span class="bento-card-label">Fulfillment Stage</span>
                <div class="bento-icon-box" style="background:#fffbeb; color:#d97706;">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
            </div>
            <div class="bento-num" style="color:#d97706;">{{ ucfirst($order->status) }}</div>
            <div class="bento-sub">Lifecycle: {{ $order->status === 'delivered' ? 'Completed' : 'In Motion' }}</div>
        </div>

        {{-- Payment Channel --}}
        <div class="bento-order-card">
            <div class="bento-card-head">
                <span class="bento-card-label">Payment Channel</span>
                <div class="bento-icon-box" style="background:#f0fdf4; color:#059669;">
                    <i class="bi bi-credit-card-fill"></i>
                </div>
            </div>
            <div class="bento-num" style="color:#059669;">{{ $payMethod }}</div>
            <div class="bento-sub">{{ $isCod ? 'Cash Collection' : 'Gateway Cleared' }}</div>
        </div>

        {{-- Delivery Location --}}
        <div class="bento-order-card">
            <div class="bento-card-head">
                <span class="bento-card-label">Destination Hub</span>
                <div class="bento-icon-box" style="background:#faf5ff; color:#9333ea;">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>
            </div>
            <div class="bento-num" style="font-size:18px; color:#0f172a;">{{ $order->shipping_city ?: 'India' }}</div>
            <div class="bento-sub">{{ $order->shipping_state ?: 'Domestic' }} ({{ $order->shipping_pincode ?: '—' }})</div>
        </div>
    </div>

    {{-- ── 3. Visual Lifecycle Stepper ── --}}
    @if($order->status !== 'cancelled' && $order->status !== 'refunded')
        <div class="stepper-card-luxury">
            @php
                $pct = $currentStatusIndex >= 0 ? ($currentStatusIndex / (count($statusSteps) - 1)) * 100 : 0;
            @endphp
            <div class="stepper-track-wrap">
                <div class="stepper-bg-line">
                    <div class="stepper-fill-line" style="width: {{ $pct }}%;"></div>
                </div>

                @foreach($statusSteps as $k => $step)
                    @php
                        $idx = array_search($k, $stepKeys);
                        $isCompleted = $idx < $currentStatusIndex;
                        $isActive = $idx === $currentStatusIndex;
                    @endphp
                    <div class="stepper-node-item {{ $isActive ? 'active' : ($isCompleted ? 'completed' : '') }}">
                        <div class="stepper-circle-icon">
                            @if($isCompleted)
                                <i class="bi bi-check-lg"></i>
                            @else
                                <i class="bi {{ $step['icon'] }}"></i>
                            @endif
                        </div>
                        <span class="stepper-node-label">{{ $step['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ── 4. Main Two-Column Layout ── --}}
    <div class="order-details-grid">
        
        {{-- Left: Items & Customer Details --}}
        <div>
            
            {{-- Ordered Products Box with 345px x 380px Showcase Images --}}
            <div class="studio-panel">
                <div class="studio-panel-head">
                    <h3 class="studio-panel-title">
                        <i class="bi bi-bag-check-fill text-primary"></i> Ordered Merchandise &amp; Showcase ({{ $order->items->count() }})
                    </h3>
                </div>

                <div>
                    @foreach($order->items as $item)
                        @php
                            $imgUrl = $item->product_image;
                            if (!$imgUrl && $item->product) {
                                $imgUrl = $item->product->image;
                            }
                            if (!$imgUrl) {
                                $imgUrl = route('images.placeholder', 'product.png');
                            }
                        @endphp
                        <div class="showcase-product-row">
                            {{-- EXACT 345px x 380px SHOWCASE IMAGE --}}
                            <div class="product-345-380-box">
                                <span class="showcase-badge-floating">
                                    <i class="bi bi-tag-fill me-1"></i> ORDER ITEM
                                </span>
                                <img src="{{ $imgUrl }}" 
                                     class="product-345-380-img" 
                                     alt="{{ $item->product_name }}"
                                     onerror="this.src='{{ route('images.placeholder', 'product.png') }}'">
                            </div>

                            {{-- Right Product Details & Attributes Pane --}}
                            <div class="product-details-pane">
                                <div>
                                    <h3 class="product-title-large">{{ $item->product_name }}</h3>
                                    
                                    <div class="attr-pills-row">
                                        <span class="badge bg-light text-navy border px-3 py-2 font-xs font-bold">
                                            <i class="bi bi-upc-scan me-1"></i> SKU: {{ $item->sku ?: 'SKU-' . $item->product_id }}
                                        </span>
                                        @if($item->size)
                                            <span class="badge bg-primary-subtle text-primary border px-3 py-2 font-xs font-bold">
                                                Size: {{ $item->size }}
                                            </span>
                                        @endif
                                        @if($item->color)
                                            <span class="badge bg-light text-muted border px-3 py-2 font-xs font-bold">
                                                Color: {{ $item->color }}
                                            </span>
                                        @endif
                                        @if($item->design_side)
                                            <span class="badge bg-warning-subtle text-dark border px-3 py-2 font-xs font-bold">
                                                <i class="bi bi-aspect-ratio me-1"></i> Print: {{ ucfirst($item->design_side) }} Side
                                            </span>
                                        @endif
                                        <span class="badge bg-success-subtle text-success border px-3 py-2 font-xs font-bold">
                                            <i class="bi bi-check-circle-fill me-1"></i> In Stock
                                        </span>
                                    </div>
                                </div>

                                {{-- Pricing Metric Cluster --}}
                                <div class="pricing-summary-cluster">
                                    <div class="pricing-cluster-item">
                                        <span class="pricing-cluster-label">Unit Price</span>
                                        <span class="pricing-cluster-val">₹{{ number_format($item->unit_price) }}</span>
                                    </div>
                                    <div class="pricing-cluster-item">
                                        <span class="pricing-cluster-label">Quantity</span>
                                        <span class="pricing-cluster-val">× {{ $item->quantity }}</span>
                                    </div>
                                    <div class="pricing-cluster-item" style="text-align: right;">
                                        <span class="pricing-cluster-label">Line Total</span>
                                        <span class="pricing-cluster-val" style="color:#059669; font-size:18px;">₹{{ number_format($item->subtotal ?: ($item->unit_price * $item->quantity)) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Dedicated Financial Invoice Ledger --}}
                <div class="invoice-ledger-box">
                    <div class="row justify-content-end">
                        <div class="col-md-7">
                            <div class="ledger-row">
                                <span>Item(s) Subtotal:</span>
                                <strong>₹{{ number_format($order->subtotal) }}</strong>
                            </div>
                            <div class="ledger-row">
                                <span>Shipping &amp; Logistics Delivery:</span>
                                <strong>{{ $order->shipping_charge > 0 ? '₹' . number_format($order->shipping_charge) : 'FREE' }}</strong>
                            </div>
                            @if($order->discount_amount > 0)
                                <div class="ledger-row" style="color: #059669;">
                                    <span>Coupon / Promotional Discount:</span>
                                    <strong>-₹{{ number_format($order->discount_amount) }}</strong>
                                </div>
                            @endif

                            <div class="ledger-total-highlight">
                                <span class="ledger-total-label">Total Payable:</span>
                                <span class="ledger-total-amount">₹{{ number_format($order->total_amount) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Shipping & Customer Destination Manifest Box --}}
            <div class="studio-panel">
                <div class="studio-panel-head">
                    <h3 class="studio-panel-title">
                        <i class="bi bi-truck text-primary"></i> Customer Delivery Manifest
                    </h3>
                </div>
                
                <div class="customer-manifest-container">
                    {{-- Customer Profile Row --}}
                    <div class="customer-header-row">
                        <div class="customer-info-left">
                            <div class="customer-avatar-square">
                                {{ strtoupper(substr($order->shipping_name ?: 'C', 0, 1)) }}
                            </div>
                            <div>
                                <h4 class="customer-name-heading">{{ $order->shipping_name ?: 'Guest Customer' }}</h4>
                                <div class="customer-phone-tag">
                                    <i class="bi bi-telephone-fill text-primary"></i>
                                    <span>{{ $order->shipping_phone ?: 'No phone number' }}</span>
                                </div>
                            </div>
                        </div>

                        @if($order->shipping_phone)
                            <div class="customer-action-buttons">
                                <a href="tel:{{ $order->shipping_phone }}" class="btn-contact-action btn-call-style">
                                    <i class="bi bi-telephone-outbound"></i> Direct Call
                                </a>
                                <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $order->shipping_phone) }}?text=Hello%20{{ urlencode($order->shipping_name) }},%20your%20Order%20{{ $order->order_number }}%20from%20The%20Trend%20Theory%20is%20being%20processed!" 
                                   target="_blank" 
                                   class="btn-contact-action btn-whatsapp-style">
                                    <i class="bi bi-whatsapp"></i> WhatsApp
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Address Manifest Box --}}
                    <div class="address-card-styled">
                        <div class="address-card-label">
                            <i class="bi bi-geo-alt-fill text-primary"></i> Destination Street Address
                        </div>
                        <div class="address-card-body">
                            {{ $order->shipping_address ?: 'Street address not specified' }}<br>
                            <strong>{{ $order->shipping_city }}</strong>, {{ $order->shipping_state }} &mdash; <span class="badge bg-white text-navy border font-bold">{{ $order->shipping_pincode }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Right: Fulfillment Dispatch Command Center --}}
        <div>
            
            {{-- Update & Process Action Box --}}
            <div class="studio-panel" style="border-top: 5px solid #2563eb;">
                <div class="studio-panel-head">
                    <h3 class="studio-panel-title">
                        <i class="bi bi-sliders text-primary"></i> Fulfillment Console
                    </h3>
                </div>
                <div class="studio-panel-body" style="padding: 22px 24px;">
                    <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                        @csrf
                        @method('PUT')

                        {{-- Order Lifecycle Status --}}
                        <div class="form-group-modern">
                            <label class="form-label-modern">1. Order Fulfillment Status</label>
                            <select name="status" class="input-modern">
                                @foreach(['pending' => 'Pending Confirmation', 'confirmed' => 'Confirmed & Verified', 'processing' => 'Processing (Packaging)', 'shipped' => 'Shipped / Dispatched', 'delivered' => 'Delivered to Customer', 'cancelled' => 'Cancelled', 'refunded' => 'Refunded'] as $val => $lbl)
                                    <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Payment Status --}}
                        <div class="form-group-modern">
                            <label class="form-label-modern">2. Payment Clearance Status</label>
                            <select name="payment_status" class="input-modern">
                                @foreach(['pending' => 'Pending Clearance', 'paid' => 'Paid / Cash Collected', 'failed' => 'Payment Failed', 'refunded' => 'Refunded'] as $val => $lbl)
                                    <option value="{{ $val }}" {{ $order->payment_status === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Courier Partner --}}
                        <div class="form-group-modern">
                            <label class="form-label-modern">3. Courier Logistics Partner</label>
                            <input type="text" name="courier_name" class="input-modern" value="{{ $order->courier_name }}" placeholder="e.g. Delhivery, Blue Dart, DTDC, Shiprocket">
                        </div>

                        {{-- Tracking Number --}}
                        <div class="form-group-modern">
                            <label class="form-label-modern">4. Airway Bill (AWB) Tracking #</label>
                            <input type="text" name="tracking_number" class="input-modern" value="{{ $order->tracking_number }}" placeholder="e.g. DEL987654321">
                        </div>

                        <button type="submit" class="btn-update-dispatch mt-3">
                            <i class="bi bi-check-circle-fill"></i> Update Order &amp; Dispatch
                        </button>
                    </form>
                </div>
            </div>

            {{-- Payment Intelligence Receipt Widget --}}
            <div class="studio-panel">
                <div class="studio-panel-head">
                    <h3 class="studio-panel-title">
                        <i class="bi bi-shield-check text-success"></i> Payment Intelligence
                    </h3>
                </div>
                <div class="payment-intel-container">
                    @if($isCod)
                        <div class="payment-receipt-box payment-receipt-cod">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div style="width:40px; height:40px; border-radius:10px; background:#f59e0b; color:#fff; display:flex; align-items:center; justify-content:center; font-size:18px;">
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                                <div>
                                    <strong class="d-block" style="color:#b45309; font-size:14px;">CASH ON DELIVERY (COD)</strong>
                                    <span class="font-xs text-muted">Doorstep physical cash collection</span>
                                </div>
                            </div>
                            
                            <div class="payment-row-item">
                                <span class="text-muted">Payment Channel:</span>
                                <strong class="text-navy">Cash at Doorstep</strong>
                            </div>
                            <div class="payment-row-item">
                                <span class="text-muted">Clearance Status:</span>
                                <strong class="{{ $order->payment_status === 'paid' ? 'text-success' : 'text-warning' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </strong>
                            </div>
                            <div class="payment-row-item">
                                <span class="text-muted">Payable Amount:</span>
                                <strong style="color:#b45309; font-size:15px;">₹{{ number_format($order->total_amount) }}</strong>
                            </div>
                        </div>
                    @else
                        <div class="payment-receipt-box payment-receipt-prepaid">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div style="width:40px; height:40px; border-radius:10px; background:#2563eb; color:#fff; display:flex; align-items:center; justify-content:center; font-size:18px;">
                                    <i class="bi bi-credit-card-2-front-fill"></i>
                                </div>
                                <div>
                                    <strong class="d-block text-primary" style="font-size:14px;">ONLINE PREPAID ({{ $payMethod }})</strong>
                                    <span class="font-xs text-muted">Cleared via Payment Gateway</span>
                                </div>
                            </div>

                            <div class="payment-row-item">
                                <span class="text-muted">Payment Channel:</span>
                                <strong class="text-navy">{{ $payMethod }}</strong>
                            </div>
                            <div class="payment-row-item">
                                <span class="text-muted">Gateway Status:</span>
                                <strong class="text-success"><i class="bi bi-check-circle-fill"></i> Cleared &amp; Paid</strong>
                            </div>
                            @if($order->payment_id)
                                <div class="payment-row-item">
                                    <span class="text-muted">Gateway Ref ID:</span>
                                    <code class="text-navy font-bold">{{ $order->payment_id }}</code>
                                </div>
                            @endif
                            <div class="payment-row-item">
                                <span class="text-muted">Paid Amount:</span>
                                <strong class="text-success" style="font-size:15px;">₹{{ number_format($order->total_amount) }}</strong>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
