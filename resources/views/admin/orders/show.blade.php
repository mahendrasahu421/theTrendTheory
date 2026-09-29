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

{{-- resources/views/admin/orders/_orders-ui.blade.php
     Shared CSS upgrade for BOTH orders/index.blade.php and orders/show.blade.php.
     Add right after the page's closing </style>:   @include('admin.orders._orders-ui')
     (If you added the earlier _orders-ui-upgrade include, replace it with this one.)
     Pure CSS: no markup or JS changes. Remove the include to revert. --}}
<style>
/* ═════════ 1. Tokens ═════════ */
:root{
  --u-shadow-1:0 1px 2px rgba(15,23,42,.04),0 4px 16px rgba(0,40,90,.05);
  --u-shadow-2:0 2px 4px rgba(15,23,42,.05),0 14px 34px rgba(0,40,90,.10);
  --u-ring:0 0 0 3px rgba(37,99,235,.22);
  --u-ease:cubic-bezier(.16,1,.3,1);
}

/* ═════════ 2. Fix classes used in markup/JS but never defined ═════════ */
.font-xs{font-size:12px!important}.font-xxs{font-size:10.5px!important}
.font-bold{font-weight:700!important}
.text-navy{color:#00285a!important}
.bg-white{background:#fff!important}
.text-purple{color:#7c3aed!important}
.mt-0\.5{margin-top:2px!important}
.py-0\.5{padding-top:2px!important;padding-bottom:2px!important}
.py-1\.5{padding-top:6px!important;padding-bottom:6px!important}
.px-1\.5{padding-left:6px!important;padding-right:6px!important}
.gap-1\.5{gap:6px!important}.me-1\.5{margin-right:6px!important}
.studio-alert-banner.alert-cancelled{background:#fef2f2;border:1.5px solid #fecaca;color:#b91c1c}
.studio-alert-banner.alert-refunded{background:#f8fafc;border:1.5px solid #cbd5e1;color:#475569}
.quick-chip-group{display:flex;gap:6px;flex-wrap:wrap;margin-top:8px}
.quick-courier-chip{background:#f8fafc;border:1.5px solid #cbd5e1;color:#334155;font-size:11.5px;font-weight:700;padding:5px 12px;border-radius:8px;cursor:pointer;user-select:none;transition:all .15s}
.quick-courier-chip:hover{border-color:#2563eb;color:#2563eb;background:#eff6ff;transform:translateY(-1px)}

/* ═════════ 3. Shared chrome (index toolbar + show hero) ═════════ */
.orders-toolbar,.order-hero-banner{
  border:1px solid #e2e8f0;background:linear-gradient(135deg,#fff 0%,#f6f9ff 100%);
  box-shadow:var(--u-shadow-1);position:relative;overflow:hidden}
.orders-toolbar::after,.order-hero-banner::after{
  content:"";position:absolute;right:-60px;top:-90px;width:260px;height:260px;pointer-events:none;
  background:radial-gradient(circle,rgba(37,99,235,.11),transparent 70%)}
.orders-title,.order-hero-title{letter-spacing:-.5px}
.btn-orders-action,.btn-hero-back{box-shadow:0 1px 2px rgba(15,23,42,.05)}
.btn-orders-action:focus-visible,.status-chip:focus-visible,.btn-page-nav:focus-visible,
.action-btn:focus-visible,.btn-filter-reset:focus-visible,.btn-hero-back:focus-visible,
.btn-update-dispatch:focus-visible{outline:none;box-shadow:var(--u-ring)}

/* KPI / bento cards */
.orders-metric,.bento-order-card{border:1px solid #e2e8f0;box-shadow:var(--u-shadow-1);position:relative;overflow:hidden}
.orders-metric::before,.bento-order-card::before{
  content:"";position:absolute;left:0;top:14px;bottom:14px;width:3px;border-radius:0 3px 3px 0;
  background:#2563eb;opacity:0;transform:scaleY(.4);transition:all .25s var(--u-ease)}
.orders-metric:hover::before,.orders-metric.active-metric-filter::before,.bento-order-card:hover::before{opacity:1;transform:scaleY(1)}
.orders-metric:hover,.bento-order-card:hover{box-shadow:var(--u-shadow-2)}
.metric-value,.bento-num{font-variant-numeric:tabular-nums;letter-spacing:-.5px}
.metric-value{font-size:24px}
.metric-label .bi-filter{opacity:.35;transition:opacity .2s}
.orders-metric:hover .metric-label .bi-filter{opacity:1}

/* ═════════ 4. INDEX: card, filters, table ═════════ */
.orders-card{border:1px solid #e2e8f0;box-shadow:var(--u-shadow-1)}
.orders-card-head{background:linear-gradient(180deg,#fff,#fbfcfe)}
.order-search input,.orders-filter select{border-width:1px;background-color:#fbfcfe}
.order-search input:hover,.orders-filter select:hover{border-color:#cbd5e1;background-color:#fff}
.order-search input:focus,.orders-filter select:focus{box-shadow:var(--u-ring)}
.status-strip{gap:6px;padding:10px 24px;background:#fbfcfe;scrollbar-width:none}
.status-strip::-webkit-scrollbar{display:none}
.status-chip{border-width:1px}
.status-chip[data-status="pending"] .chip-count{background:#fef3c7;color:#b45309}
.status-chip[data-status="confirmed"] .chip-count{background:#dbeafe;color:#1d4ed8}
.status-chip[data-status="processing"] .chip-count{background:#e0f2fe;color:#0369a1}
.status-chip[data-status="shipped"] .chip-count{background:#ede9fe;color:#6d28d9}
.status-chip[data-status="delivered"] .chip-count{background:#d1fae5;color:#047857}
.status-chip[data-status="cancelled"] .chip-count{background:#fee2e2;color:#b91c1c}
.status-chip.active .chip-count{background:rgba(255,255,255,.22)!important;color:#fff!important}

.orders-table-wrap{max-height:68vh;overflow:auto;scrollbar-width:thin}
.orders-table{border-collapse:separate;border-spacing:0;min-width:1080px}
.orders-table thead th{position:sticky;top:0;z-index:3;background:rgba(248,250,252,.92);backdrop-filter:blur(8px);
  border-bottom:1px solid #e2e8f0;font-size:10.5px;letter-spacing:.8px;color:#64748b}
.orders-table tbody tr:nth-child(even) td{background:#fcfdff}
.orders-table tbody tr:hover td{background:#f3f7ff}
.orders-table tbody tr.is-selected td{background:#eaf2ff}
.orders-table tbody tr:last-child td{border-bottom:0}
.orders-table tbody td:first-child{box-shadow:inset 3px 0 0 transparent}
.orders-table tbody tr:has(.status-pending) td:first-child{box-shadow:inset 3px 0 0 #f59e0b}
.orders-table tbody tr:has(.status-confirmed) td:first-child{box-shadow:inset 3px 0 0 #3b82f6}
.orders-table tbody tr:has(.status-processing) td:first-child{box-shadow:inset 3px 0 0 #0ea5e9}
.orders-table tbody tr:has(.status-shipped) td:first-child{box-shadow:inset 3px 0 0 #8b5cf6}
.orders-table tbody tr:has(.status-delivered) td:first-child{box-shadow:inset 3px 0 0 #10b981}
.orders-table tbody tr:has(.status-cancelled) td:first-child{box-shadow:inset 3px 0 0 #ef4444}
.order-number-link,.amount-cell{font-variant-numeric:tabular-nums}
.order-prod-thumb{border-radius:12px;border:1px solid #e2e8f0;transition:transform .2s var(--u-ease)}
tr:hover .order-prod-thumb{transform:scale(1.06)}
.customer-avatar,.studio-cust-avatar,.customer-avatar-square{box-shadow:0 0 0 3px #fff,0 2px 8px rgba(0,40,90,.2)}
.status-pill,.badge-clearance{box-shadow:0 1px 2px rgba(15,23,42,.05)}
.status-pill:hover,.badge-clearance:hover{transform:translateY(-1px)}
@media (hover:hover){
  .orders-table .action-group{opacity:.55;transition:opacity .2s}
  .orders-table tbody tr:hover .action-group{opacity:1}}
.action-btn{border-width:1px;width:32px;height:32px}
.table-loading-overlay{background:rgba(255,255,255,.55);backdrop-filter:blur(1px);align-items:flex-start}
.table-loading-overlay .spinner-border{display:none}
.table-loading-overlay::before{content:"";position:absolute;top:0;left:0;height:3px;width:40%;
  background:linear-gradient(90deg,transparent,#2563eb,#38bdf8,transparent);animation:uBar 1.1s infinite var(--u-ease)}
@keyframes uBar{from{transform:translateX(-100%)}to{transform:translateX(260%)}}
.empty-state>i{display:inline-flex;width:84px;height:84px;align-items:center;justify-content:center;
  border-radius:50%;background:radial-gradient(circle,#eff6ff,#f8fafc);color:#94a3b8!important}
.table-footer-bar{background:#fbfcfe}
.btn-page-nav{border-width:1px;border-radius:9px;font-variant-numeric:tabular-nums}
.floating-bulk-bar{background:rgba(0,40,90,.94);backdrop-filter:blur(14px);border:1px solid rgba(255,255,255,.12);padding:10px 14px 10px 22px}

/* ═════════ 5. INDEX: modals ═════════ */
.custom-modal-overlay{background:rgba(9,20,45,.6)}
.custom-modal-card{border-radius:22px;box-shadow:0 40px 90px -20px rgba(0,30,80,.45)}
.modal-stepper-wrap,.studio-bento-card,.studio-control-card{border-width:1px;box-shadow:var(--u-shadow-1)}
.studio-bento-card:hover,.studio-control-card:hover{box-shadow:var(--u-shadow-2)}
.modal-flow-node.active{box-shadow:0 8px 22px rgba(37,99,235,.32)}
.modal-flow-node.active .node-icon-wrap{animation:uPop .4s var(--u-ease)}
@keyframes uPop{from{transform:scale(.6)}to{transform:scale(1)}}
.form-control-modal{border-width:1px;background:#fbfcfe}
.form-control-modal:hover{border-color:#94a3b8;background:#fff}
.form-control-modal:focus{box-shadow:var(--u-ring);background:#fff}
.custom-modal-footer{position:sticky;bottom:0;z-index:2}
.scanner-gun-input:focus{box-shadow:var(--u-ring)}
.scanned-order-card{box-shadow:var(--u-shadow-2)}
.custom-toast{border-radius:14px;backdrop-filter:blur(8px);box-shadow:var(--u-shadow-2)}

/* ═════════ 6. SHOW: stepper, product cards, console ═════════ */
.stepper-card-luxury,.studio-panel{border:1px solid #e2e8f0;box-shadow:var(--u-shadow-1)}
/* equal-width nodes so the track line always meets the circles */
.stepper-track-wrap{overflow-x:auto;padding:4px 0 2px;scrollbar-width:none}
.stepper-track-wrap::-webkit-scrollbar{display:none}
.stepper-node-item{flex:1 1 0;min-width:96px}
.stepper-bg-line{left:10%;right:10%;height:5px;top:24px}
.stepper-node-item.active .stepper-circle-icon{animation:uGlow 2.2s infinite}
@keyframes uGlow{50%{box-shadow:0 0 0 9px rgba(59,130,246,.10)}}
.stepper-node-label{font-size:11.5px;line-height:1.25;max-width:100px}

.studio-panel-head{background:linear-gradient(180deg,#fff,#fbfcfe)}
.studio-panel-title{font-size:13px;letter-spacing:.7px}

/* Product image: keep 345×380 look on desktop, scale gracefully elsewhere */
.showcase-product-row{gap:26px}
.product-345-380-box{aspect-ratio:345/380;height:auto;box-shadow:0 10px 28px rgba(0,30,80,.12)}
.product-345-380-img{position:absolute;inset:0}
.product-details-pane{min-height:0;gap:18px}
.product-title-large{font-size:19px;letter-spacing:-.2px}
.attr-pills-row .badge{font-size:11.5px;font-weight:700;border-radius:8px}
.pricing-summary-cluster{background:linear-gradient(135deg,#f8fafc,#f1f5ff)}
.pricing-cluster-val{font-variant-numeric:tabular-nums}
.invoice-ledger-box{background:#fbfcff}
.ledger-total-amount,.ledger-row strong{font-variant-numeric:tabular-nums}

.customer-avatar-square{border-radius:16px}
.address-card-styled{background:linear-gradient(180deg,#f8fafc,#f4f7fb)}
.btn-contact-action{border-radius:10px}
.btn-contact-action:hover{transform:translateY(-1px)}

/* Console stays in view while you scroll long orders */
@media (min-width:1081px){
  .order-details-grid>div:last-child{position:sticky;top:16px}
}
.input-modern{border-width:1px;background:#fbfcfe}
.input-modern:hover{border-color:#94a3b8;background:#fff}
.input-modern:focus{box-shadow:var(--u-ring);background:#fff}
.btn-update-dispatch:active{transform:translateY(0) scale(.99)}
.payment-receipt-box{box-shadow:0 1px 2px rgba(15,23,42,.04)}

/* ═════════ 7. Responsive ═════════ */
@media (max-width:900px){
  .orders-toolbar,.order-hero-banner{padding:16px 18px}
  .orders-toolbar-actions,.order-hero-actions{width:100%}
  .orders-toolbar-actions .btn-orders-action{flex:1 1 calc(50% - 10px)}
  .orders-filter{justify-content:flex-start;width:100%}
  .orders-filter select{flex:1 1 140px}
}
@media (max-width:700px){
  .showcase-product-row{padding:18px;gap:16px}
  .product-345-380-box{width:100%}
  .stepper-card-luxury{padding:18px 14px}
  .customer-action-buttons{width:100%}
  .customer-action-buttons .btn-contact-action{flex:1;justify-content:center}
  .order-hero-title,.orders-title{font-size:20px}
}
@media (max-width:680px){
  .floating-bulk-bar{left:12px;right:12px;transform:translateY(140px);border-radius:20px;flex-wrap:wrap;justify-content:center}
  .floating-bulk-bar.show{transform:translateY(0)}
  .custom-modal-overlay{padding:8px;align-items:flex-end}
  .custom-modal-card{max-height:96vh;border-radius:22px 22px 14px 14px}
  .custom-modal-header,.custom-modal-body,.custom-modal-footer{padding-left:16px;padding-right:16px}
}

/* ═════════ 8. Accessibility & print ═════════ */
@media (prefers-reduced-motion:reduce){
  *,*::before,*::after{animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important}}
@media print{
  .floating-bulk-bar,.custom-toast,.orders-toolbar-actions,.order-hero-actions,
  .order-details-grid>div:last-child .studio-panel:first-child{display:none!important}
  .order-details-grid{grid-template-columns:1fr!important}
  .studio-panel,.stepper-card-luxury,.bento-order-card{box-shadow:none!important;break-inside:avoid}
}
</style>

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
                                $imgUrl = asset('assets/images/placeholder.png');
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
                                     onerror="this.src='{{ asset('assets/images/placeholder.png') }}'">
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
