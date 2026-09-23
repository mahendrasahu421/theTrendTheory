{{-- resources/views/admin/customers/show.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Customer 360: ' . $user->name)
@section('content')

@php
    $brandName = config('app.name', 'The Trend');
    $phone = $user->phone;
    $cleanPhone = $phone ? preg_replace('/[^0-9]/', '', $phone) : '';
    if ($cleanPhone && strlen($cleanPhone) === 10) {
        $cleanPhone = '91' . $cleanPhone;
    }
    $latestLog = $visitorLogs->first();
    $firstActivity = $activities->first();
    $totalOrdersCount = $user->orders->count();
    $deliveredOrdersCount = $user->orders->where('status', 'delivered')->count();
    $pendingOrdersCount = $user->orders->whereIn('status', ['pending', 'confirmed', 'packaging', 'shipped'])->count();
    $totalUnitsPurchased = $user->orders->sum(fn($o) => $o->items->sum('quantity'));
    $avgOrderValue = $totalOrdersCount > 0 ? round($totalSpent / $totalOrdersCount) : 0;

    // Advanced Customer Segmentation & VIP Tiering
    if ($totalSpent >= 25000) {
        $tierName = 'Diamond Sovereign VIP';
        $tierClass = 'tier-diamond';
        $tierIcon = 'bi-gem';
        $tierProgress = 100;
        $nextTierMsg = 'Maximum Elite Tier Achieved';
    } elseif ($totalSpent >= 15000) {
        $tierName = 'VIP Gold Patron';
        $tierClass = 'tier-gold';
        $tierIcon = 'bi-award-fill';
        $tierProgress = min(100, round(($totalSpent / 25000) * 100));
        $nextTierMsg = '₹' . number_format(25000 - $totalSpent) . ' to Diamond Sovereign';
    } elseif ($totalSpent >= 5000 || $totalOrdersCount >= 2) {
        $tierName = 'Frequent Collector';
        $tierClass = 'tier-repeat';
        $tierIcon = 'bi-arrow-repeat';
        $tierProgress = min(100, round(($totalSpent / 15000) * 100));
        $nextTierMsg = '₹' . number_format(15000 - $totalSpent) . ' to VIP Gold';
    } elseif ($totalOrdersCount >= 1) {
        $tierName = 'First Drop Buyer';
        $tierClass = 'tier-first';
        $tierIcon = 'bi-bag-check-fill';
        $tierProgress = min(100, round(($totalSpent / 5000) * 100));
        $nextTierMsg = '₹' . number_format(5000 - $totalSpent) . ' to Frequent Collector';
    } else {
        $tierName = 'Registered Guest';
        $tierClass = 'tier-guest';
        $tierIcon = 'bi-person';
        $tierProgress = 10;
        $nextTierMsg = 'Awaiting 1st drop order';
    }

    $initial = strtoupper(substr($user->name ?? 'C', 0, 1));
    $defaultWaText = "Hi " . $user->name . "! Thank you for being a valued patron of " . $brandName . ". Can we assist you with any questions or styling guidance?";
@endphp

<div class="cust-hub-container">

    {{-- ════════════════════════════════════════════════════════════════ --}}
    {{-- 1. LUXURY EXECUTIVE HERO BANNER                                  --}}
    {{-- ════════════════════════════════════════════════════════════════ --}}
    <div class="cust-hero-card">
        <div class="hero-aurora-orb hero-orb-1"></div>
        <div class="hero-aurora-orb hero-orb-2"></div>

        <div class="hero-inner-content">
            {{-- Navigation Breadcrumb Bar --}}
            <div class="hero-top-nav">
                <a href="{{ route('admin.customers.index') }}" class="hero-back-btn">
                    <i class="bi bi-arrow-left"></i>
                    <span>All Customers</span>
                </a>
                <div class="hero-badge-strip">
                    <span class="hero-badge hero-id-badge">
                        <i class="bi bi-fingerprint"></i> #CUST-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}
                    </span>
                    <span class="hero-badge hero-reg-date">
                        <i class="bi bi-calendar-event"></i> Member since {{ $user->created_at->format('M Y') }} ({{ $user->created_at->diffForHumans() }})
                    </span>
                </div>
            </div>

            {{-- Main Identity Profile Row --}}
            <div class="hero-identity-row">
                <div class="hero-avatar-wrapper">
                    <div class="hero-avatar-ring">
                        <div class="hero-avatar-core">
                            {{ $initial }}
                        </div>
                    </div>
                    <span class="avatar-live-beacon {{ $user->is_active ? 'active-beacon' : 'inactive-beacon' }}" 
                          title="{{ $user->is_active ? 'Customer Account Active' : 'Customer Account Suspended' }}">
                        <span class="beacon-wave"></span>
                        <span class="beacon-core"></span>
                    </span>
                </div>

                <div class="hero-profile-info">
                    <div class="hero-title-line">
                        <h1 class="hero-customer-name">{{ $user->name }}</h1>
                        <div class="hero-tier-chip {{ $tierClass }}">
                            <i class="bi {{ $tierIcon }}"></i>
                            <span>{{ $tierName }}</span>
                        </div>
                    </div>

                    <div class="hero-contact-strip">
                        @if($user->email)
                            <span class="hero-contact-pill" onclick="copyInteractive('{{ $user->email }}', this)" title="Click to copy email">
                                <i class="bi bi-envelope"></i>
                                <span>{{ $user->email }}</span>
                                <i class="bi bi-clipboard copy-icon"></i>
                            </span>
                        @endif

                        @if($user->phone)
                            <span class="hero-contact-pill" onclick="copyInteractive('{{ $user->phone }}', this)" title="Click to copy phone">
                                <i class="bi bi-telephone"></i>
                                <span>{{ $user->phone }}</span>
                                <i class="bi bi-clipboard copy-icon"></i>
                            </span>
                        @endif

                        <span class="hero-contact-pill">
                            <i class="bi bi-geo-alt"></i>
                            <span>{{ $user->city ?? ($latestLog?->city ?? 'Location N/A') }}{{ ($user->state ?? $latestLog?->state) ? ', ' . ($user->state ?? $latestLog?->state) : '' }}</span>
                        </span>
                    </div>
                </div>

                {{-- Quick Executive Actions Toolbar --}}
                <div class="hero-actions-toolbar">
                    @if($cleanPhone)
                        <button type="button" class="btn-action-glass btn-glass-wa" onclick="openCustWaModal()" title="Instant WhatsApp Outreach">
                            <i class="bi bi-whatsapp"></i>
                            <span>WhatsApp</span>
                        </button>
                        <a href="tel:{{ $cleanPhone }}" class="btn-action-glass btn-glass-call" title="Direct Phone Call">
                            <i class="bi bi-telephone-fill"></i>
                            <span>Call</span>
                        </a>
                    @endif

                    @if($user->email)
                        <button type="button" class="btn-action-glass btn-glass-email" onclick="scrollToEmailStudio()" title="Send Official Brand Email">
                            <i class="bi bi-envelope-at-fill"></i>
                            <span>Email Studio</span>
                        </button>
                    @endif

                    <button type="button" class="btn-action-glass btn-glass-neutral" onclick="copyCustomerDossier(this)" title="Copy Full Customer Summary">
                        <i class="bi bi-clipboard2-check"></i>
                        <span>Copy Dossier</span>
                    </button>

                    <form method="POST" action="{{ route('admin.customers.toggle', $user) }}" class="d-inline" onsubmit="return confirm('Confirm profile status change for {{ addslashes($user->name) }}?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-action-glass {{ $user->is_active ? 'btn-glass-danger' : 'btn-glass-success' }}" title="{{ $user->is_active ? 'Suspend Account' : 'Reactivate Account' }}">
                            <i class="bi bi-{{ $user->is_active ? 'shield-lock' : 'shield-check' }}"></i>
                            <span>{{ $user->is_active ? 'Suspend' : 'Activate' }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════ --}}
    {{-- 2. ANIMATED BENTO KPI METRICS GRID                               --}}
    {{-- ════════════════════════════════════════════════════════════════ --}}
    <div class="cust-kpi-grid">
        
        {{-- Card 1: Customer Lifetime Value (LTV) --}}
        <div class="kpi-glass-card kpi-card-anim" style="--anim-delay: 0.05s;">
            <div class="kpi-card-header">
                <div class="kpi-title-group">
                    <span class="kpi-meta-tag">FINANCIAL VALUE</span>
                    <h3 class="kpi-metric-title">Lifetime Value (LTV)</h3>
                </div>
                <div class="kpi-icon-glow kpi-glow-emerald">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>

            <div class="kpi-big-number">
                &#8377;{{ number_format($totalSpent) }}
            </div>

            <div class="kpi-progress-wrap">
                <div class="kpi-progress-bar">
                    <div class="kpi-progress-fill emerald-fill" style="width: {{ $tierProgress }}%;"></div>
                </div>
                <div class="kpi-progress-labels">
                    <span>{{ $nextTierMsg }}</span>
                    <span class="font-bold">{{ $tierProgress }}%</span>
                </div>
            </div>

            <div class="kpi-footer-sub">
                <i class="bi bi-calculator"></i>
                <span>Avg Order: <strong>&#8377;{{ number_format($avgOrderValue) }}</strong></span>
            </div>
        </div>

        {{-- Card 2: Orders & Fulfilled Rate --}}
        <div class="kpi-glass-card kpi-card-anim" style="--anim-delay: 0.10s;">
            <div class="kpi-card-header">
                <div class="kpi-title-group">
                    <span class="kpi-meta-tag">PURCHASE VOLUME</span>
                    <h3 class="kpi-metric-title">Orders Executed</h3>
                </div>
                <div class="kpi-icon-glow kpi-glow-navy">
                    <i class="bi bi-bag-check-fill"></i>
                </div>
            </div>

            <div class="kpi-big-number">
                {{ $totalOrdersCount }} <span class="kpi-number-unit">Orders</span>
            </div>

            <div class="kpi-order-stats-strip">
                <div class="stat-pill-mini stat-delivered">
                    <i class="bi bi-check-circle-fill"></i>
                    <span><strong>{{ $deliveredOrdersCount }}</strong> Delivered</span>
                </div>
                <div class="stat-pill-mini stat-pending">
                    <i class="bi bi-hourglass-split"></i>
                    <span><strong>{{ $pendingOrdersCount }}</strong> In-Transit</span>
                </div>
            </div>

            <div class="kpi-footer-sub">
                <i class="bi bi-truck"></i>
                <span>Fulfillment Rate: <strong>{{ $totalOrdersCount > 0 ? round(($deliveredOrdersCount / $totalOrdersCount) * 100) : 0 }}%</strong></span>
            </div>
        </div>

        {{-- Card 3: Apparel Units & Drop Affinity --}}
        <div class="kpi-glass-card kpi-card-anim" style="--anim-delay: 0.15s;">
            <div class="kpi-card-header">
                <div class="kpi-title-group">
                    <span class="kpi-meta-tag">WARDROBE METRICS</span>
                    <h3 class="kpi-metric-title">Units Purchased</h3>
                </div>
                <div class="kpi-icon-glow kpi-glow-purple">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
            </div>

            <div class="kpi-big-number">
                {{ $totalUnitsPurchased }} <span class="kpi-number-unit">Items</span>
            </div>

            <div class="kpi-basket-affinity">
                <div class="affinity-pill">
                    <i class="bi bi-tag-fill"></i>
                    <span>Basket Size: <strong>{{ $totalOrdersCount > 0 ? round($totalUnitsPurchased / $totalOrdersCount, 1) : 0 }}</strong> pcs/order</span>
                </div>
            </div>

            <div class="kpi-footer-sub">
                <i class="bi bi-grid-fill"></i>
                <span>Multi-drop streetwear collector</span>
            </div>
        </div>

        {{-- Card 4: Customer Profile Status --}}
        <div class="kpi-glass-card kpi-card-anim" style="--anim-delay: 0.20s;">
            <div class="kpi-card-header">
                <div class="kpi-title-group">
                    <span class="kpi-meta-tag">TRUST & VERIFICATION</span>
                    <h3 class="kpi-metric-title">Profile Integrity</h3>
                </div>
                <div class="kpi-icon-glow {{ $user->is_active ? 'kpi-glow-gold' : 'kpi-glow-danger' }}">
                    <i class="bi bi-{{ $user->is_active ? 'shield-fill-check' : 'shield-fill-x' }}"></i>
                </div>
            </div>

            <div class="kpi-big-number status-text {{ $user->is_active ? 'text-active-safe' : 'text-danger' }}">
                {{ $user->is_active ? 'Verified Patron' : 'Account Flagged' }}
            </div>

            <div class="kpi-verification-badges">
                <span class="badge-verify {{ $user->phone ? 'badge-ok' : 'badge-warn' }}">
                    <i class="bi bi-{{ $user->phone ? 'telephone-check' : 'telephone-x' }}"></i> Phone
                </span>
                <span class="badge-verify {{ $user->email ? 'badge-ok' : 'badge-warn' }}">
                    <i class="bi bi-{{ $user->email ? 'envelope-check' : 'envelope-x' }}"></i> Email
                </span>
                <span class="badge-verify {{ $user->addresses->count() > 0 ? 'badge-ok' : 'badge-warn' }}">
                    <i class="bi bi-geo-alt-fill"></i> Address
                </span>
            </div>

            <div class="kpi-footer-sub">
                <i class="bi bi-clock-history"></i>
                <span>Registered: <strong>{{ $user->created_at->format('d M Y') }}</strong></span>
            </div>
        </div>

    </div>

    {{-- ════════════════════════════════════════════════════════════════ --}}
    {{-- 3. INTERACTIVE TWO-COLUMN OPERATIONS STUDIO                      --}}
    {{-- ════════════════════════════════════════════════════════════════ --}}
    <div class="cust-workspace-grid">

        {{-- ── Left Sidebar: Dossier & Digital Intelligence ── --}}
        <div class="cust-sidebar-column">

            {{-- Dossier Card 1: Verified Identity & Contacts --}}
            <div class="modern-card-glass sidebar-card-anim">
                <div class="card-glass-header">
                    <div class="card-glass-icon"><i class="bi bi-person-badge"></i></div>
                    <h4 class="card-glass-title">Customer Dossier</h4>
                </div>
                <div class="card-glass-body">
                    <div class="dossier-record-row">
                        <span class="dossier-label"><i class="bi bi-person"></i> Legal Name</span>
                        <div class="dossier-value-box">
                            <span class="dossier-val font-bold">{{ $user->name }}</span>
                        </div>
                    </div>

                    <div class="dossier-record-row">
                        <span class="dossier-label"><i class="bi bi-envelope"></i> Email Address</span>
                        <div class="dossier-value-box">
                            <span class="dossier-val">{{ $user->email ?? 'No email recorded' }}</span>
                            @if($user->email)
                                <button type="button" class="btn-copy-interactive" onclick="copyInteractive('{{ $user->email }}', this)" title="Copy Email">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="dossier-record-row">
                        <span class="dossier-label"><i class="bi bi-telephone"></i> Direct Phone</span>
                        <div class="dossier-value-box">
                            <span class="dossier-val">{{ $user->phone ?? 'Not provided' }}</span>
                            @if($user->phone)
                                <button type="button" class="btn-copy-interactive" onclick="copyInteractive('{{ $user->phone }}', this)" title="Copy Phone">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="dossier-record-row">
                        <span class="dossier-label"><i class="bi bi-geo-alt"></i> Primary Geography</span>
                        <div class="dossier-value-box">
                            <span class="dossier-val">{{ $user->city ?? ($latestLog?->city ?? 'N/A') }}, {{ $user->state ?? ($latestLog?->state ?? 'India') }}</span>
                            @if($user->pincode)
                                <span class="badge-pin-luxury">{{ $user->pincode }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dossier Card 2: Hardware, Browser & Attribution Radar --}}
            <div class="modern-card-glass sidebar-card-anim">
                <div class="card-glass-header">
                    <div class="card-glass-icon"><i class="bi bi-cpu"></i></div>
                    <h4 class="card-glass-title">Hardware & Attribution</h4>
                </div>
                <div class="card-glass-body">
                    @php
                        $deviceBrand = $latestLog?->device_brand ?? ($firstActivity?->device_brand ?? 'Unknown Hardware');
                        $deviceModel = $latestLog?->device_model ?? ($firstActivity?->device_model ?? '');
                        $browser = $latestLog?->browser ?? ($firstActivity?->browser ?? 'Browser');
                        $os = $latestLog?->os ?? 'OS';
                        $source = $latestLog?->source ?: ($firstActivity?->source ?: 'Direct');
                        $ip = $latestLog?->ip_address ?: ($firstActivity?->ip_address ?: 'Protected');
                        $isApple = stripos($deviceBrand, 'apple') !== false || stripos($os, 'ios') !== false || stripos($os, 'mac') !== false;
                        $isAndroid = stripos($deviceBrand, 'android') !== false || stripos($os, 'android') !== false;
                    @endphp

                    <div class="dossier-record-row">
                        <span class="dossier-label">
                            <i class="bi bi-{{ $isApple ? 'apple' : ($isAndroid ? 'android2' : 'laptop') }}"></i> Device Spec
                        </span>
                        <div class="dossier-value-box">
                            <span class="dossier-val font-bold text-navy">{{ $deviceBrand }} {{ $deviceModel ? "({$deviceModel})" : '' }}</span>
                        </div>
                    </div>

                    <div class="dossier-record-row">
                        <span class="dossier-label"><i class="bi bi-browser-chrome"></i> Environment</span>
                        <div class="dossier-value-box">
                            <span class="dossier-val">{{ $browser }} &bull; {{ $os }}</span>
                        </div>
                    </div>

                    <div class="dossier-record-row">
                        <span class="dossier-label"><i class="bi bi-funnel"></i> Attribution Source</span>
                        <div class="dossier-value-box">
                            <span class="badge-source-tag"><i class="bi bi-compass"></i> {{ $source }}</span>
                        </div>
                    </div>

                    <div class="dossier-record-row">
                        <span class="dossier-label"><i class="bi bi-router"></i> Last IP Address</span>
                        <div class="dossier-value-box">
                            <span class="dossier-val font-mono">{{ $ip }}</span>
                            <button type="button" class="btn-copy-interactive" onclick="copyInteractive('{{ $ip }}', this)" title="Copy IP">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                    </div>

                    @if($latestLog && $latestLog->page_views_count)
                        <div class="dossier-record-row">
                            <span class="dossier-label"><i class="bi bi-eye"></i> Recorded Pageviews</span>
                            <div class="dossier-value-box">
                                <span class="badge-pageview-counter"><i class="bi bi-graph-up-arrow"></i> {{ $latestLog->page_views_count }} views</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Dossier Card 3: Default Delivery Coordinates --}}
            @php
                $primaryAddress = $user->addresses->firstWhere('is_default', true) ?? $user->addresses->first();
                $shippingName = $primaryAddress?->name ?? ($user->orders->first()?->shipping_name ?? $user->name);
                $shippingAddressLine = $primaryAddress?->address_line ?? ($user->orders->first()?->shipping_address ?? $user->address);
                $shippingCity = $primaryAddress?->city ?? ($user->orders->first()?->shipping_city ?? $user->city);
                $shippingState = $primaryAddress?->state ?? ($user->orders->first()?->shipping_state ?? $user->state);
                $shippingPincode = $primaryAddress?->pincode ?? ($user->orders->first()?->shipping_pincode ?? $user->pincode);
                $shippingPhone = $primaryAddress?->phone ?? ($user->orders->first()?->shipping_phone ?? $user->phone);
            @endphp

            @if($shippingAddressLine)
                <div class="modern-card-glass sidebar-card-anim">
                    <div class="card-glass-header">
                        <div class="card-glass-icon"><i class="bi bi-geo-alt-fill"></i></div>
                        <h4 class="card-glass-title">Primary Delivery Hub</h4>
                    </div>
                    <div class="card-glass-body">
                        <div class="shipping-hub-card">
                            <div class="shipping-hub-recipient">
                                <i class="bi bi-person-circle text-navy"></i>
                                <strong>{{ $shippingName }}</strong>
                            </div>
                            <div class="shipping-hub-address">{{ $shippingAddressLine }}</div>
                            <div class="shipping-hub-meta">
                                <span class="hub-city">{{ $shippingCity }}, {{ $shippingState }}</span>
                                <span class="badge-pin-luxury">{{ $shippingPincode }}</span>
                            </div>
                            @if($shippingPhone)
                                <div class="shipping-hub-phone">
                                    <i class="bi bi-telephone-outbound text-emerald"></i>
                                    <span>{{ $shippingPhone }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

        </div>

        {{-- ── Right Workspace: Multi-Tab Interactive Operations Studio ── --}}
        <div class="cust-main-column">

            {{-- Interactive Tab Navigator Strip --}}
            <div class="studio-tab-strip">
                <button type="button" class="studio-tab-btn active" onclick="switchCustomerTab('ordersTab', this)">
                    <i class="bi bi-bag-check-fill tab-icon"></i>
                    <span>Order History</span>
                    <span class="tab-count-badge">{{ $totalOrdersCount }}</span>
                </button>

                <button type="button" class="studio-tab-btn" onclick="switchCustomerTab('timelineTab', this)">
                    <i class="bi bi-activity tab-icon"></i>
                    <span>Live Journey</span>
                    <span class="tab-count-badge">{{ $activities->count() }}</span>
                    <span class="tab-pulse-dot"></span>
                </button>

                <button type="button" class="studio-tab-btn" onclick="switchCustomerTab('addressTab', this)">
                    <i class="bi bi-geo-alt-fill tab-icon"></i>
                    <span>Saved Addresses</span>
                    <span class="tab-count-badge">{{ $user->addresses->count() }}</span>
                </button>

                <button type="button" class="studio-tab-btn" id="outreachTabBtn" onclick="switchCustomerTab('outreachTab', this)">
                    <i class="bi bi-chat-dots-fill tab-icon"></i>
                    <span>Outreach & Concierge Studio</span>
                </button>
            </div>

            {{-- ════════════════════════════════════════════════════════════ --}}
            {{-- TAB 1: ORDER HISTORY CARDS                                   --}}
            {{-- ════════════════════════════════════════════════════════════ --}}
            <div id="ordersTab" class="studio-tab-pane active">
                @forelse($user->orders as $order)
                    @php
                        $statusPills = [
                            'pending'    => ['border' => '#f59e0b', 'bg' => '#fffbeb', 'text' => '#b45309', 'icon' => 'hourglass-split', 'label' => 'Pending'],
                            'confirmed'  => ['border' => '#3b82f6', 'bg' => '#eff6ff', 'text' => '#1d4ed8', 'icon' => 'check2', 'label' => 'Confirmed'],
                            'packaging'  => ['border' => '#a855f7', 'bg' => '#faf5ff', 'text' => '#7e22ce', 'icon' => 'box-seam', 'label' => 'Packaging'],
                            'shipped'    => ['border' => '#6366f1', 'bg' => '#eef2ff', 'text' => '#4338ca', 'icon' => 'truck', 'label' => 'Shipped'],
                            'delivered'  => ['border' => '#10b981', 'bg' => '#ecfdf5', 'text' => '#047857', 'icon' => 'check-circle-fill', 'label' => 'Delivered'],
                            'cancelled'  => ['border' => '#ef4444', 'bg' => '#fef2f2', 'text' => '#b91c1c', 'icon' => 'x-circle-fill', 'label' => 'Cancelled'],
                            'refunded'   => ['border' => '#64748b', 'bg' => '#f8fafc', 'text' => '#475569', 'icon' => 'arrow-counterclockwise', 'label' => 'Refunded'],
                        ];
                        $st = $statusPills[$order->status] ?? ['border' => '#94a3b8', 'bg' => '#f1f5f9', 'text' => '#475569', 'icon' => 'info-circle', 'label' => ucfirst($order->status)];
                        $isPaid = strtolower($order->payment_status ?? '') === 'paid';
                        $isCod = str_contains(strtoupper($order->payment_method ?? ''), 'COD') || str_contains(strtoupper($order->payment_method ?? ''), 'CASH');
                    @endphp

                    <div class="order-interactive-card" style="border-left: 4px solid {{ $st['border'] }};">
                        
                        {{-- Top Header Row --}}
                        <div class="order-card-top-row">
                            <div class="order-num-wrapper">
                                <a href="{{ route('admin.orders.show', $order) }}" class="order-link-title" title="Open Full Order Dossier">
                                    {{ $order->order_number }}
                                </a>
                                <button type="button" class="btn-copy-interactive" onclick="copyInteractive('{{ $order->order_number }}', this)" title="Copy Order Number">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                                <span class="order-timestamp">
                                    <i class="bi bi-clock"></i> {{ $order->created_at->format('d M Y, h:i A') }}
                                </span>
                            </div>

                            <div class="order-badge-cluster">
                                {{-- Fulfillment Status --}}
                                <span class="order-badge-pill" style="background: {{ $st['bg'] }}; color: {{ $st['text'] }}; border: 1px solid {{ $st['border'] }}33;">
                                    <i class="bi bi-{{ $st['icon'] }}"></i> {{ $st['label'] }}
                                </span>

                                {{-- Payment Channel --}}
                                <span class="order-badge-pill {{ $isCod ? 'badge-cod' : 'badge-prepaid' }}">
                                    <i class="bi bi-{{ $isCod ? 'cash-coin' : 'credit-card-2-front' }}"></i>
                                    {{ $isCod ? 'COD' : 'PREPAID' }}
                                </span>

                                {{-- Payment Clearance --}}
                                <span class="order-badge-pill {{ $isPaid ? 'badge-paid' : 'badge-unpaid' }}">
                                    <i class="bi bi-{{ $isPaid ? 'check-circle-fill' : 'clock-history' }}"></i>
                                    {{ $isPaid ? 'Paid' : 'Pending' }}
                                </span>
                            </div>
                        </div>

                        {{-- Middle Items Preview Strip --}}
                        <div class="order-items-grid">
                            @foreach($order->items as $item)
                                <div class="order-item-pod">
                                    <div class="item-img-zoom-container">
                                        <img src="{{ $item->product_image ?? ($item->product?->image_url ?? asset('images/placeholder-product.jpg')) }}" 
                                             alt="{{ $item->product_name }}" 
                                             class="item-img-thumb"
                                             onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                                    </div>
                                    <div class="item-pod-details">
                                        <h5 class="item-pod-title">{{ $item->product_name }}</h5>
                                        <div class="item-pod-tags">
                                            @if($item->size)
                                                <span class="spec-chip spec-size">Size {{ $item->size }}</span>
                                            @endif
                                            @if($item->color)
                                                <span class="spec-chip spec-color">{{ $item->color }}</span>
                                            @endif
                                            <span class="spec-chip spec-qty">Qty: <strong>{{ $item->quantity }}</strong></span>
                                            <span class="spec-chip spec-price">&#8377;{{ number_format(round($item->line_total ?: $item->unit_price * $item->quantity)) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Bottom Tracking, Grand Total & Quick Actions --}}
                        <div class="order-card-bottom-row">
                            <div class="order-courier-track">
                                @if($order->tracking_number)
                                    <div class="courier-live-pill">
                                        <i class="bi bi-truck text-navy"></i>
                                        <span class="courier-name">{{ $order->courier_name ?: 'Courier' }}:</span>
                                        <strong class="font-mono">{{ $order->tracking_number }}</strong>
                                        <button type="button" class="btn-copy-interactive" onclick="copyInteractive('{{ $order->tracking_number }}', this)" title="Copy Tracking AWB">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="courier-empty-msg">
                                        <i class="bi bi-info-circle"></i> Awaiting courier dispatch assignment
                                    </span>
                                @endif
                            </div>

                            <div class="order-total-and-cta">
                                <div class="order-grand-total">
                                    <span class="lbl-total">Total:</span>
                                    <span class="val-total">&#8377;{{ number_format($order->total_amount) }}</span>
                                </div>

                                <div class="order-cta-buttons">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn-order-glow btn-glow-studio" title="Inspect Full Order">
                                        <i class="bi bi-eye"></i> View Order
                                    </a>
                                    <a href="{{ route('admin.orders.shipping-label', $order) }}" target="_blank" class="btn-order-glow btn-glow-light" title="Print 4x6 Shipping Label">
                                        <i class="bi bi-upc-scan"></i> Label
                                    </a>
                                    <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="btn-order-glow btn-glow-light" title="Generate Tax Invoice">
                                        <i class="bi bi-receipt"></i> Invoice
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="modern-card-glass">
                        <div class="empty-state-card">
                            <div class="empty-state-icon-anim">
                                <i class="bi bi-bag-x"></i>
                            </div>
                            <h3 class="empty-state-title">No Orders Checked Out Yet</h3>
                            <p class="empty-state-desc">This customer created an account but hasn't completed their first drop checkout.</p>
                            @if($cleanPhone)
                                <button type="button" class="btn-action-glass btn-glass-wa" onclick="openCustWaModal()">
                                    <i class="bi bi-whatsapp"></i> Send Welcome Drop Catalog
                                </button>
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- ════════════════════════════════════════════════════════════ --}}
            {{-- TAB 2: LIVE JOURNEY TIMELINE                                 --}}
            {{-- ════════════════════════════════════════════════════════════ --}}
            <div id="timelineTab" class="studio-tab-pane">
                <div class="modern-card-glass">
                    <div class="card-glass-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <div class="card-glass-icon"><i class="bi bi-activity"></i></div>
                            <h4 class="card-glass-title">Real-Time Interaction Footprint</h4>
                        </div>
                        <span class="timeline-meta-count">{{ $activities->count() }} Events Tracked</span>
                    </div>

                    <div class="timeline-stream-wrapper">
                        @forelse($activities as $index => $act)
                            <div class="timeline-node-item">
                                {{-- Glowing Node Icon --}}
                                <div class="timeline-node-dot {{ $act->event_badge_class }} {{ $index === 0 ? 'node-pulse' : '' }}">
                                    <i class="bi {{ $act->event_icon }}"></i>
                                </div>

                                {{-- Node Content Body --}}
                                <div class="timeline-node-card">
                                    <div class="timeline-node-header">
                                        <span class="timeline-node-title">{{ $act->event_title }}</span>
                                        <span class="timeline-node-time">
                                            <i class="bi bi-clock"></i> {{ $act->created_at->diffForHumans() }} &bull; {{ $act->created_at->format('d M, h:i A') }}
                                        </span>
                                    </div>

                                    <div class="timeline-node-chips">
                                        <span class="tag-event-badge {{ $act->event_badge_class }}">
                                            {{ $act->event_label }}
                                        </span>

                                        @if($act->city)
                                            <span class="tag-geo-chip">
                                                <i class="bi bi-geo-alt"></i> {{ $act->city }}{{ $act->state ? ', ' . $act->state : '' }}
                                            </span>
                                        @endif

                                        @if($act->device_brand || $act->device_model)
                                            <span class="tag-device-chip">
                                                <i class="bi bi-phone"></i> {{ $act->device_brand }} {{ $act->device_model }}
                                            </span>
                                        @endif

                                        @if($act->source)
                                            <span class="tag-source-chip">
                                                <i class="bi bi-compass"></i> {{ $act->source }}
                                            </span>
                                        @endif

                                        @if($act->url)
                                            <a href="{{ $act->url }}" target="_blank" class="tag-url-link" title="Open visited URL">
                                                <i class="bi bi-box-arrow-up-right"></i> {{ Str::limit($act->url, 40) }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state-card">
                                <div class="empty-state-icon-anim"><i class="bi bi-clock-history"></i></div>
                                <h3 class="empty-state-title">No Activity Events Recorded</h3>
                                <p class="empty-state-desc">Live customer browsing events, searches, and cart actions will stream here automatically.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ════════════════════════════════════════════════════════════ --}}
            {{-- TAB 3: SAVED DELIVERY ADDRESSES                              --}}
            {{-- ════════════════════════════════════════════════════════════ --}}
            <div id="addressTab" class="studio-tab-pane">
                <div class="address-grid-layout">
                    @forelse($user->addresses as $addr)
                        <div class="address-card-luxury {{ $addr->is_default ? 'address-is-default' : '' }}">
                            <div class="addr-card-topbar">
                                <span class="addr-type-pill"><i class="bi bi-house-door"></i> {{ ucfirst($addr->type ?? 'Delivery Address') }}</span>
                                @if($addr->is_default)
                                    <span class="addr-star-pill"><i class="bi bi-star-fill"></i> Default</span>
                                @endif
                            </div>

                            <h4 class="addr-name-title">{{ $addr->name ?? $user->name }}</h4>
                            <div class="addr-street-line">{{ $addr->address_line }}</div>
                            <div class="addr-city-zip">
                                <span>{{ $addr->city }}, {{ $addr->state }}</span>
                                <span class="badge-pin-luxury">{{ $addr->pincode }}</span>
                            </div>

                            @if($addr->phone)
                                <div class="addr-contact-row">
                                    <i class="bi bi-telephone text-emerald"></i>
                                    <span>{{ $addr->phone }}</span>
                                    <button type="button" class="btn-copy-interactive" onclick="copyInteractive('{{ $addr->phone }}', this)" title="Copy Phone">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    @empty
                        @if($user->address)
                            <div class="address-card-luxury address-is-default">
                                <div class="addr-card-topbar">
                                    <span class="addr-type-pill"><i class="bi bi-house-door"></i> Account Profile Address</span>
                                    <span class="addr-star-pill"><i class="bi bi-star-fill"></i> Default</span>
                                </div>

                                <h4 class="addr-name-title">{{ $user->name }}</h4>
                                <div class="addr-street-line">{{ $user->address }}</div>
                                <div class="addr-city-zip">
                                    <span>{{ $user->city }}, {{ $user->state }}</span>
                                    <span class="badge-pin-luxury">{{ $user->pincode }}</span>
                                </div>

                                @if($user->phone)
                                    <div class="addr-contact-row">
                                        <i class="bi bi-telephone text-emerald"></i>
                                        <span>{{ $user->phone }}</span>
                                        <button type="button" class="btn-copy-interactive" onclick="copyInteractive('{{ $user->phone }}', this)">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="modern-card-glass" style="grid-column: 1 / -1;">
                                <div class="empty-state-card">
                                    <div class="empty-state-icon-anim"><i class="bi bi-geo-alt"></i></div>
                                    <h3 class="empty-state-title">No Saved Addresses Found</h3>
                                    <p class="empty-state-desc">This customer has not recorded a delivery address in their address book yet.</p>
                                </div>
                            </div>
                        @endif
                    @endforelse
                </div>
            </div>

            {{-- ════════════════════════════════════════════════════════════ --}}
            {{-- TAB 4: OUTREACH & CONCIERGE STUDIO                           --}}
            {{-- ════════════════════════════════════════════════════════════ --}}
            <div id="outreachTab" class="studio-tab-pane">
                
                {{-- ── SECTION A: 1-Click WhatsApp Concierge ── --}}
                <div class="modern-card-glass mb-4">
                    <div class="card-glass-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <div class="card-glass-icon" style="background:#ecfdf5; color:#059669;">
                                <i class="bi bi-whatsapp"></i>
                            </div>
                            <h4 class="card-glass-title">1-Click WhatsApp Concierge Templates</h4>
                        </div>
                        <span class="text-muted font-xs">Target: <strong>{{ $user->phone ?? 'No Phone' }}</strong></span>
                    </div>

                    <div class="card-glass-body">
                        <p class="text-muted font-xs mb-3">
                            Directly trigger pre-composed streetwear concierge messages tailored with customer variables. Clicking launches WhatsApp Web or Desktop instantly:
                        </p>

                        <div class="wa-chat-bubbles-grid">
                            
                            {{-- Bubble 1: VIP Welcome --}}
                            @php
                                $msg1 = "Hi {$user->name}! Thank you for being a valued patron of {$brandName}. We're thrilled to have you in our community! If you need any styling tips, drop recommendations, or sizing assistance, feel free to text us right here anytime. Explore latest collection: " . url('/shop');
                            @endphp
                            <div class="wa-chat-bubble-card">
                                <div class="wa-bubble-header">
                                    <span class="wa-intent-badge"><i class="bi bi-stars"></i> VIP Welcome & Concierge</span>
                                    <span class="wa-read-ticks"><i class="bi bi-check2-all"></i></span>
                                </div>
                                <div class="wa-bubble-body">
                                    {{ $msg1 }}
                                </div>
                                <div class="wa-bubble-footer">
                                    <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode($msg1) }}" target="_blank" class="btn-wa-launch-glow">
                                        <i class="bi bi-whatsapp"></i> Send via WhatsApp
                                    </a>
                                </div>
                            </div>

                            {{-- Bubble 2: Order Follow-up --}}
                            @php
                                $lastOrder = $user->orders->first();
                                $orderRef = $lastOrder ? $lastOrder->order_number : '#ORDER-LATEST';
                                $msg2 = "Hi {$user->name}! This is from {$brandName} Dispatch Studio. We're checking in on your drop order {$orderRef}. Our fulfillment team has carefully packed your order. Please let us know if you need any tracking or delivery assistance!";
                            @endphp
                            <div class="wa-chat-bubble-card">
                                <div class="wa-bubble-header">
                                    <span class="wa-intent-badge"><i class="bi bi-truck"></i> Order Status & Care Follow-up</span>
                                    <span class="wa-read-ticks"><i class="bi bi-check2-all"></i></span>
                                </div>
                                <div class="wa-bubble-body">
                                    {{ $msg2 }}
                                </div>
                                <div class="wa-bubble-footer">
                                    <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode($msg2) }}" target="_blank" class="btn-wa-launch-glow">
                                        <i class="bi bi-whatsapp"></i> Send via WhatsApp
                                    </a>
                                </div>
                            </div>

                            {{-- Bubble 3: VIP Loyalty Reward --}}
                            @php
                                $msg3 = "Exclusive Drop Perk for {$user->name} ✨ As one of our top patrons at {$brandName}, here is an exclusive secret discount voucher for your next wardrobe drop: Use code TRENDVIP at checkout for priority express delivery: " . url('/shop');
                            @endphp
                            <div class="wa-chat-bubble-card">
                                <div class="wa-bubble-header">
                                    <span class="wa-intent-badge"><i class="bi bi-ticket-perforated"></i> Exclusive VIP Reward Voucher</span>
                                    <span class="wa-read-ticks"><i class="bi bi-check2-all"></i></span>
                                </div>
                                <div class="wa-bubble-body">
                                    {{ $msg3 }}
                                </div>
                                <div class="wa-bubble-footer">
                                    <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode($msg3) }}" target="_blank" class="btn-wa-launch-glow">
                                        <i class="bi bi-whatsapp"></i> Send via WhatsApp
                                    </a>
                                </div>
                            </div>

                            {{-- Bubble 4: Address Verification --}}
                            @php
                                $msg4 = "Hi {$user->name}, this is from {$brandName} Dispatch Studio. Could you please confirm your complete street address and pincode so our courier partner can guarantee timely doorstep fulfillment?";
                            @endphp
                            <div class="wa-chat-bubble-card">
                                <div class="wa-bubble-header">
                                    <span class="wa-intent-badge"><i class="bi bi-geo-alt"></i> Delivery Address Verification</span>
                                    <span class="wa-read-ticks"><i class="bi bi-check2-all"></i></span>
                                </div>
                                <div class="wa-bubble-body">
                                    {{ $msg4 }}
                                </div>
                                <div class="wa-bubble-footer">
                                    <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode($msg4) }}" target="_blank" class="btn-wa-launch-glow">
                                        <i class="bi bi-whatsapp"></i> Send via WhatsApp
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ── SECTION B: REDESIGNED EXECUTIVE BRAND EMAIL STUDIO ── --}}
                <div class="email-studio-wrapper" id="emailStudioSection">
                    <div class="email-studio-card">
                        
                        {{-- Studio Top Bar --}}
                        <div class="email-studio-topbar">
                            <div class="email-studio-brand">
                                <div class="email-studio-avatar">
                                    <i class="bi bi-envelope-paper-heart-fill"></i>
                                </div>
                                <div>
                                    <div class="email-studio-title-row">
                                        <h3 class="email-studio-title">Executive Brand Email Studio</h3>
                                        <span class="email-live-badge"><i class="bi bi-shield-check"></i> SMTP Connected</span>
                                    </div>
                                    <p class="email-studio-sub">Compose & dispatch official branded emails to <strong>{{ $user->name }}</strong> with 1-click template presets.</p>
                                </div>
                            </div>

                            <div class="email-recipient-chip {{ $user->email ? 'has-email' : 'no-email' }}">
                                <i class="bi bi-{{ $user->email ? 'at' : 'exclamation-circle' }}"></i>
                                <span class="recipient-address">{{ $user->email ?? 'No email recorded' }}</span>
                                @if($user->email)
                                    <span class="verified-dot" title="Verified Inbox"></span>
                                @endif
                            </div>
                        </div>

                        {{-- Preset Templates Strip --}}
                        <div class="email-presets-bar">
                            <span class="presets-label"><i class="bi bi-lightning-charge-fill"></i> Quick Presets:</span>
                            <div class="presets-buttons">
                                <button type="button" class="btn-email-preset" onclick="applyEmailPreset('vip')">
                                    <i class="bi bi-stars"></i> VIP Drop Invitation
                                </button>
                                <button type="button" class="btn-email-preset" onclick="applyEmailPreset('order')">
                                    <i class="bi bi-truck"></i> Order Care & Tracking
                                </button>
                                <button type="button" class="btn-email-preset" onclick="applyEmailPreset('concierge')">
                                    <i class="bi bi-person-heart"></i> Stylist Concierge
                                </button>
                            </div>
                        </div>

                        {{-- Inline Interactive Email Form --}}
                        <form id="custEmailForm" onsubmit="handleSendCustEmail(event)" class="email-composer-form">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <input type="hidden" name="channel" value="email">
                            <input type="hidden" name="email" value="{{ $user->email }}">

                            <div class="email-form-grid">
                                {{-- Subject Field --}}
                                <div class="composer-field-group">
                                    <div class="field-label-row">
                                        <label for="custEmailSubject"><i class="bi bi-chat-left-quote"></i> Subject Line</label>
                                        <span class="field-hint">Visible in customer inbox</span>
                                    </div>
                                    <div class="input-glow-wrapper">
                                        <input type="text" 
                                               name="subject" 
                                               id="custEmailSubject" 
                                               class="composer-input" 
                                               required 
                                               placeholder="Enter subject line..."
                                               value="Special Update from {{ $brandName }}">
                                    </div>
                                </div>

                                {{-- Message Body Field --}}
                                <div class="composer-field-group">
                                    <div class="field-label-row">
                                        <label for="custEmailMessage"><i class="bi bi-body-text"></i> Message Content</label>
                                        <span class="field-hint" id="charCountLabel">Formatted Luxury HTML</span>
                                    </div>
                                    <div class="textarea-glow-wrapper">
                                        <textarea name="message" 
                                                  id="custEmailMessage" 
                                                  rows="6" 
                                                  class="composer-textarea" 
                                                  required 
                                                  placeholder="Write your email body here...">Hi {{ $user->name }},

Thank you for being a valued patron of {{ $brandName }}. We are delighted to share exclusive updates with you regarding your styling preferences and recent collection drops!

If you need any sizing guidance, drop reservations, or order inquiries, please reply to this email or reach out to our concierge team anytime.

Warm regards,
{{ $brandName }} Customer Experience Studio</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- Status Feedback Strip --}}
                            <div id="custEmailStatus" class="email-dispatch-status" style="display:none;"></div>

                            {{-- Form Action Footer --}}
                            <div class="composer-action-footer">
                                <div class="composer-footer-note">
                                    <i class="bi bi-info-circle"></i>
                                    <span>Emails are dispatched with {{ $brandName }}'s official dark luxury typography & brand headers.</span>
                                </div>

                                <button type="submit" 
                                        class="btn-send-email-glow" 
                                        id="custEmailBtn" 
                                        {{ !$user->email ? 'disabled' : '' }}>
                                    <i class="bi bi-send-fill"></i>
                                    <span>{{ $user->email ? 'Dispatch Official Email' : 'Email Address Required' }}</span>
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>

        </div>
    </div>

</div>

{{-- ════════════════════════════════════════════════════════════════ --}}
{{-- MODAL: WHATSAPP QUICK LAUNCH MODAL                               --}}
{{-- ════════════════════════════════════════════════════════════════ --}}
<div class="luxury-modal-backdrop" id="custWaModal" style="display:none;">
    <div class="luxury-modal-window">
        <div class="luxury-modal-header">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-whatsapp text-emerald fs-5"></i>
                <h3 class="luxury-modal-title">WhatsApp Quick Concierge</h3>
            </div>
            <button type="button" class="luxury-modal-close" onclick="closeCustWaModal()">&times;</button>
        </div>
        <div class="luxury-modal-body">
            <div class="form-group-luxury mb-3">
                <label>Recipient:</label>
                <input type="text" value="{{ $user->name }} ({{ $cleanPhone ? '+' . $cleanPhone : 'No valid mobile' }})" class="form-control-luxury" readonly style="background:#f8fafc; font-weight:700;">
            </div>

            <div class="form-group-luxury mb-3">
                <label>Custom Message:</label>
                <textarea id="customWaText" rows="5" class="form-control-luxury" style="resize:vertical;">{{ $defaultWaText }}</textarea>
            </div>
        </div>
        <div class="luxury-modal-footer">
            <button type="button" class="btn-action-glass btn-glass-neutral" onclick="closeCustWaModal()">Cancel</button>
            <button type="button" class="btn-action-glass btn-glass-wa" onclick="launchCustomWa()">
                <i class="bi bi-whatsapp"></i> Launch Chat Now
            </button>
        </div>
    </div>
</div>

{{-- ── Floating Interactive Toast ── --}}
<div id="custInteractiveToast" class="cust-toast-luxury">
    <div class="toast-icon-circle"><i class="bi bi-check2-circle"></i></div>
    <span id="custToastText">Copied to clipboard</span>
</div>

<script>
/* ── Interactive Tab Switcher with Animation ── */
function switchCustomerTab(tabId, btn) {
    const activePanes = document.querySelectorAll('.studio-tab-pane.active');
    activePanes.forEach(pane => pane.classList.remove('active'));

    document.querySelectorAll('.studio-tab-btn').forEach(el => el.classList.remove('active'));

    const target = document.getElementById(tabId);
    if (target) {
        target.classList.add('active');
        target.style.animation = 'none';
        target.offsetHeight; /* trigger reflow */
        target.style.animation = null;
    }
    if (btn) btn.classList.add('active');
}

/* ── Smooth Scroll & Switch to Email Studio ── */
function scrollToEmailStudio() {
    const outreachBtn = document.getElementById('outreachTabBtn');
    if (outreachBtn) {
        switchCustomerTab('outreachTab', outreachBtn);
    }
    setTimeout(() => {
        const emailSection = document.getElementById('emailStudioSection');
        if (emailSection) {
            emailSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
            const subjectInput = document.getElementById('custEmailSubject');
            if (subjectInput) {
                subjectInput.focus();
            }
        }
    }, 150);
}

/* ── Interactive Copy with Button Morph & Toast ── */
function copyInteractive(text, btnElement) {
    if (!text) return;

    navigator.clipboard.writeText(text).then(function() {
        showLuxuryToast('Copied: ' + text);
        if (btnElement) {
            const icon = btnElement.querySelector('i');
            if (icon) {
                const originalClass = icon.className;
                icon.className = 'bi bi-check-lg text-success';
                btnElement.classList.add('copied-glow');
                setTimeout(() => {
                    icon.className = originalClass;
                    btnElement.classList.remove('copied-glow');
                }, 1800);
            }
        }
    }).catch(function() {
        showLuxuryToast('Copied to clipboard');
    });
}

function copyCustomerDossier(btnElement) {
    const dossier = `Customer Dossier:
Name: {{ $user->name }}
ID: #CUST-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}
Phone: {{ $user->phone ?? 'N/A' }}
Email: {{ $user->email ?? 'N/A' }}
Geography: {{ $user->city ?? 'N/A' }}, {{ $user->state ?? 'N/A' }} - {{ $user->pincode ?? '' }}
LTV: ₹{{ number_format($totalSpent) }} across {{ $totalOrdersCount }} orders.`;

    copyInteractive(dossier, btnElement);
}

function showLuxuryToast(msg) {
    const toast = document.getElementById('custInteractiveToast');
    const toastText = document.getElementById('custToastText');
    if (toast && toastText) {
        toastText.textContent = msg;
        toast.classList.add('toast-active');
        clearTimeout(window.__toastTimer);
        window.__toastTimer = setTimeout(() => {
            toast.classList.remove('toast-active');
        }, 2600);
    }
}

/* ── WhatsApp Modal Controls ── */
function openCustWaModal() {
    document.getElementById('custWaModal').style.display = 'flex';
}
function closeCustWaModal() {
    document.getElementById('custWaModal').style.display = 'none';
}
function launchCustomWa() {
    const phone = '{{ $cleanPhone }}';
    const text = document.getElementById('customWaText').value;
    if (!phone) {
        alert('Customer does not have a recorded phone number.');
        return;
    }
    const url = `https://wa.me/${phone}?text=${encodeURIComponent(text)}`;
    window.open(url, '_blank');
    closeCustWaModal();
}

/* ── Email Presets Dynamic Populator ── */
function applyEmailPreset(type) {
    const subjectEl = document.getElementById('custEmailSubject');
    const messageEl = document.getElementById('custEmailMessage');
    const custName = '{{ addslashes($user->name) }}';
    const brand = '{{ addslashes($brandName) }}';
    const shopUrl = '{{ url('/shop') }}';

    if (type === 'vip') {
        subjectEl.value = `Exclusive VIP Drop Invitation for ${custName} ✨`;
        messageEl.value = `Dear ${custName},\n\nAs a cherished patron of ${brand}, we are excited to extend a private invitation to our newest limited-run streetwear collection.\n\nEnjoy early access, reserved stock, and complimentary priority dispatch on your selection: ${shopUrl}\n\nUse your VIP reserve code: TRENDVIP at checkout.\n\nWarm regards,\n${brand} Private Client Team`;
    } else if (type === 'order') {
        subjectEl.value = `Update on Your ${brand} Wardrobe Drop`;
        messageEl.value = `Hi ${custName},\n\nWe wanted to share a personal update regarding your recent order with ${brand}. Our dispatch team has meticulously inspected and prepped your apparel for safe transit.\n\nIf you need any real-time tracking assistance or delivery rescheduling, simply reply to this email and our team will handle it immediately.\n\nThank you for choosing ${brand}.\nFulfillment & Care Studio`;
    } else if (type === 'concierge') {
        subjectEl.value = `Styling & Sizing Concierge from ${brand}`;
        messageEl.value = `Hi ${custName},\n\nWe are checking in to make sure your experience with ${brand} has been nothing short of exceptional!\n\nWhether you need personal styling recommendations, guidance on oversized fits, or drop availability, we are here to help.\n\nBest regards,\n${brand} Concierge Experience`;
    }

    // visual highlight
    subjectEl.classList.add('input-pulse');
    messageEl.classList.add('input-pulse');
    setTimeout(() => {
        subjectEl.classList.remove('input-pulse');
        messageEl.classList.remove('input-pulse');
    }, 600);

    showLuxuryToast('Applied: ' + subjectEl.value);
}

/* ── Email Send Handler ── */
function handleSendCustEmail(e) {
    e.preventDefault();
    const form = document.getElementById('custEmailForm');
    const btn = document.getElementById('custEmailBtn');
    const status = document.getElementById('custEmailStatus');
    const origHtml = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Dispatching...';
    status.style.display = 'none';

    const formData = new FormData(form);

    fetch('{{ route('admin.analytics.notify') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = origHtml;
        status.style.display = 'block';

        if (data.success) {
            status.className = 'email-dispatch-status status-success';
            status.innerHTML = '<i class="bi bi-check-circle-fill"></i> ' + data.message;
            showLuxuryToast('Email dispatched successfully!');
        } else {
            status.className = 'email-dispatch-status status-danger';
            status.innerHTML = '<i class="bi bi-exclamation-circle-fill"></i> ' + (data.message || 'Error sending email');
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = origHtml;
        status.style.display = 'block';
        status.className = 'email-dispatch-status status-danger';
        status.innerHTML = '<i class="bi bi-exclamation-circle-fill"></i> Network error occurred. Please verify SMTP configuration.';
    });
}
</script>

<style>
/* ─── Ultra-Modern Executive Customer 360 Design System ─────────────── */
:root {
    --rn-midnight: #071329;
    --rn-navy: #00285a;
    --rn-navy-light: #1e4b85;
    --rn-accent-blue: #3b82f6;
    --rn-emerald: #059669;
    --rn-emerald-light: #10b981;
    --rn-gold: #d97706;
    --rn-card-border: rgba(226, 232, 240, 0.85);
    --rn-card-shadow: 0 4px 20px -2px rgba(0, 40, 90, 0.04);
}

.cust-hub-container {
    display: flex;
    flex-direction: column;
    gap: 22px;
    max-width: 1460px;
    margin: 0 auto;
}

/* ══════════════════════════════════════════════════════════════════════
   1. LUXURY EXECUTIVE HERO BANNER
   ══════════════════════════════════════════════════════════════════════ */
.cust-hero-card {
    position: relative;
    border-radius: 20px;
    background: radial-gradient(circle at 10% 20%, rgba(30, 64, 175, 0.45) 0%, transparent 50%),
                radial-gradient(circle at 90% 80%, rgba(16, 185, 129, 0.18) 0%, transparent 50%),
                linear-gradient(135deg, #091224 0%, #001f4d 55%, #081e3d 100%);
    box-shadow: 0 20px 45px -10px rgba(0, 31, 77, 0.35);
    border: 1px solid rgba(255, 255, 255, 0.12);
    overflow: hidden;
    padding: 24px 30px;
    color: #ffffff;
    animation: heroFadeSlide 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes heroFadeSlide {
    0% { opacity: 0; transform: translateY(-10px); }
    100% { opacity: 1; transform: translateY(0); }
}

/* Ambient Floating Glow Orbs */
.hero-aurora-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(60px);
    pointer-events: none;
    z-index: 1;
    opacity: 0.35;
    animation: orbPulse 8s ease-in-out infinite alternate;
}
.hero-orb-1 {
    width: 250px;
    height: 250px;
    background: #38bdf8;
    top: -60px;
    right: 15%;
}
.hero-orb-2 {
    width: 220px;
    height: 220px;
    background: #818cf8;
    bottom: -50px;
    left: 25%;
    animation-delay: -4s;
}

@keyframes orbPulse {
    0% { transform: scale(1) translate(0, 0); }
    100% { transform: scale(1.15) translate(15px, -15px); }
}

.hero-inner-content {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.hero-top-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.hero-back-btn {
    font-size: 12.5px;
    font-weight: 700;
    color: rgba(255, 255, 255, 0.75);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.14);
    padding: 5px 14px;
    border-radius: 20px;
    backdrop-filter: blur(6px);
    transition: all 0.2s ease;
}
.hero-back-btn:hover {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.16);
    transform: translateX(-2px);
}

.hero-badge-strip {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.hero-badge {
    font-size: 11.5px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.hero-id-badge {
    background: rgba(255, 255, 255, 0.12);
    color: #93c5fd;
    font-family: monospace;
    letter-spacing: 0.5px;
    border: 1px solid rgba(147, 197, 253, 0.25);
}
.hero-reg-date {
    background: rgba(255, 255, 255, 0.08);
    color: rgba(255, 255, 255, 0.75);
}

/* Identity Profile Row */
.hero-identity-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
}

.hero-avatar-wrapper {
    position: relative;
    flex-shrink: 0;
}

.hero-avatar-ring {
    width: 68px;
    height: 68px;
    border-radius: 20px;
    padding: 2.5px;
    background: linear-gradient(135deg, #38bdf8 0%, #6366f1 50%, #ec4899 100%);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
    display: flex;
    align-items: center;
    justify-content: center;
    animation: avatarPulse 4s ease-in-out infinite alternate;
}

@keyframes avatarPulse {
    0% { filter: drop-shadow(0 4px 12px rgba(56, 189, 248, 0.4)); }
    100% { filter: drop-shadow(0 4px 18px rgba(99, 102, 241, 0.6)); }
}

.hero-avatar-core {
    width: 100%;
    height: 100%;
    border-radius: 17px;
    background: #001f4d;
    color: #ffffff;
    font-family: 'Cinzel', serif, sans-serif;
    font-size: 28px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Radar Beacon for Active / Suspended State */
.avatar-live-beacon {
    position: absolute;
    bottom: -3px;
    right: -3px;
    width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.beacon-core {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #001f4d;
    position: relative;
    z-index: 2;
}

.beacon-wave {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    opacity: 0.7;
    animation: beaconSonar 2s cubic-bezier(0, 0.2, 0.8, 1) infinite;
}

.active-beacon .beacon-core { background: #10b981; }
.active-beacon .beacon-wave { background: #10b981; }

.inactive-beacon .beacon-core { background: #ef4444; }
.inactive-beacon .beacon-wave { background: #ef4444; }

@keyframes beaconSonar {
    0% { transform: scale(0.8); opacity: 0.9; }
    100% { transform: scale(2.2); opacity: 0; }
}

.hero-profile-info {
    display: flex;
    flex-direction: column;
    gap: 8px;
    flex: 1;
    min-width: 260px;
}

.hero-title-line {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.hero-customer-name {
    font-family: 'Cinzel', serif, sans-serif;
    font-size: 24px;
    font-weight: 800;
    color: #ffffff;
    margin: 0;
    letter-spacing: 0.5px;
}

/* Shimmering VIP Gold & Tier Badges */
.hero-tier-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11.5px;
    font-weight: 800;
    padding: 4px 12px;
    border-radius: 20px;
    letter-spacing: 0.4px;
    position: relative;
    overflow: hidden;
}

.tier-diamond {
    background: linear-gradient(135deg, #0284c7 0%, #6366f1 100%);
    color: #ffffff;
    box-shadow: 0 2px 10px rgba(2, 132, 199, 0.4);
}
.tier-gold {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    color: #ffffff;
    box-shadow: 0 2px 10px rgba(217, 119, 6, 0.4);
}
.tier-gold::after, .tier-diamond::after {
    content: '';
    position: absolute;
    top: 0; left: -100%; width: 50%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.35), transparent);
    animation: goldShimmer 3.2s infinite;
}

@keyframes goldShimmer {
    0% { left: -100%; }
    40% { left: 150%; }
    100% { left: 150%; }
}

.tier-repeat { background: rgba(59, 130, 246, 0.2); color: #93c5fd; border: 1px solid rgba(147, 197, 253, 0.3); }
.tier-first { background: rgba(168, 85, 247, 0.2); color: #d8b4fe; border: 1px solid rgba(216, 180, 254, 0.3); }
.tier-guest { background: rgba(255, 255, 255, 0.12); color: rgba(255, 255, 255, 0.8); }

.hero-contact-strip {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.hero-contact-pill {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.85);
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.12);
    padding: 3px 10px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    transition: all 0.2s;
}
.hero-contact-pill:hover {
    background: rgba(255, 255, 255, 0.16);
    color: #ffffff;
}
.hero-contact-pill .copy-icon {
    font-size: 11px;
    opacity: 0.6;
}
.hero-contact-pill:hover .copy-icon {
    opacity: 1;
}

/* Glassmorphism Action Toolbar */
.hero-actions-toolbar {
    display: flex;
    align-items: center;
    gap: 9px;
    flex-wrap: wrap;
}

.btn-action-glass {
    padding: 9px 16px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 700;
    border: 1px solid transparent;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    text-decoration: none;
    backdrop-filter: blur(8px);
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
.btn-action-glass:hover {
    transform: translateY(-2px);
}

.btn-glass-wa {
    background: #25d366;
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(37, 211, 102, 0.35);
}
.btn-glass-wa:hover {
    background: #20ba59;
    color: #ffffff;
    box-shadow: 0 6px 20px rgba(37, 211, 102, 0.5);
}

.btn-glass-call {
    background: rgba(2, 132, 199, 0.25);
    border-color: rgba(56, 189, 248, 0.4);
    color: #e0f2fe;
}
.btn-glass-call:hover {
    background: #0284c7;
    color: #ffffff;
}

.btn-glass-email {
    background: rgba(99, 102, 241, 0.25);
    border-color: rgba(165, 180, 252, 0.4);
    color: #e0e7ff;
}
.btn-glass-email:hover {
    background: #4f46e5;
    color: #ffffff;
}

.btn-glass-neutral {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.18);
    color: #ffffff;
}
.btn-glass-neutral:hover {
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff;
}

.btn-glass-danger {
    background: rgba(239, 68, 68, 0.18);
    border-color: rgba(248, 113, 113, 0.35);
    color: #fca5a5;
}
.btn-glass-danger:hover {
    background: #dc2626;
    color: #ffffff;
}

.btn-glass-success {
    background: rgba(16, 185, 129, 0.18);
    border-color: rgba(52, 211, 153, 0.35);
    color: #6ee7b7;
}
.btn-glass-success:hover {
    background: #059669;
    color: #ffffff;
}

/* ══════════════════════════════════════════════════════════════════════
   2. ANIMATED BENTO KPI GRID
   ══════════════════════════════════════════════════════════════════════ */
.cust-kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}

@media (max-width: 1100px) {
    .cust-kpi-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 600px) {
    .cust-kpi-grid { grid-template-columns: 1fr; }
}

.kpi-glass-card {
    background: #ffffff;
    border: 1px solid var(--rn-card-border);
    border-radius: 16px;
    padding: 20px;
    box-shadow: var(--rn-card-shadow);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 12px;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    overflow: hidden;
}

.kpi-card-anim {
    animation: cardSlideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
    animation-delay: var(--anim-delay, 0s);
}

@keyframes cardSlideUp {
    0% { opacity: 0; transform: translateY(16px); }
    100% { opacity: 1; transform: translateY(0); }
}

.kpi-glass-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px -5px rgba(0, 40, 90, 0.09);
    border-color: #cbd5e1;
}

.kpi-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.kpi-title-group {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.kpi-meta-tag {
    font-size: 10px;
    font-weight: 800;
    color: #94a3b8;
    letter-spacing: 0.6px;
}

.kpi-metric-title {
    font-size: 13.5px;
    font-weight: 800;
    color: var(--rn-navy);
    margin: 0;
}

.kpi-icon-glow {
    width: 38px;
    height: 38px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}
.kpi-glow-emerald { background: #ecfdf5; color: #059669; }
.kpi-glow-navy { background: #eff6ff; color: var(--rn-navy); }
.kpi-glow-purple { background: #faf5ff; color: #7e22ce; }
.kpi-glow-gold { background: #fffbeb; color: #d97706; }
.kpi-glow-danger { background: #fef2f2; color: #dc2626; }

.kpi-big-number {
    font-size: 24px;
    font-weight: 800;
    color: var(--rn-navy);
    line-height: 1.15;
}

.kpi-number-unit {
    font-size: 13px;
    color: #64748b;
    font-weight: 600;
}

/* Progress bar inside LTV card */
.kpi-progress-wrap {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.kpi-progress-bar {
    width: 100%;
    height: 6px;
    background: #f1f5f9;
    border-radius: 10px;
    overflow: hidden;
}

.kpi-progress-fill {
    height: 100%;
    border-radius: 10px;
    transition: width 0.8s ease;
}
.emerald-fill {
    background: linear-gradient(90deg, #10b981, #059669);
}

.kpi-progress-labels {
    display: flex;
    justify-content: space-between;
    font-size: 11px;
    color: #64748b;
}

/* Order stats sub-pills */
.kpi-order-stats-strip {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.stat-pill-mini {
    font-size: 11px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.stat-delivered { background: #ecfdf5; color: #047857; }
.stat-pending { background: #fffbeb; color: #b45309; }

.affinity-pill {
    font-size: 11.5px;
    color: #475569;
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    padding: 4px 8px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.kpi-verification-badges {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.badge-verify {
    font-size: 10.5px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 5px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.badge-ok { background: #ecfdf5; color: #059669; }
.badge-warn { background: #fef2f2; color: #dc2626; }

.status-text {
    font-size: 17px;
}
.text-active-safe { color: #059669; }

.kpi-footer-sub {
    font-size: 11.5px;
    color: #64748b;
    border-top: 1px solid #f1f5f9;
    padding-top: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* ══════════════════════════════════════════════════════════════════════
   3. TWO-COLUMN WORKSPACE LAYOUT
   ══════════════════════════════════════════════════════════════════════ */
.cust-workspace-grid {
    display: grid;
    grid-template-columns: 360px 1fr;
    gap: 22px;
    align-items: flex-start;
}

@media (max-width: 1024px) {
    .cust-workspace-grid {
        grid-template-columns: 1fr;
    }
}

.cust-sidebar-column {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.cust-main-column {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

/* Modern Card Glass Style */
.modern-card-glass {
    background: #ffffff;
    border: 1px solid var(--rn-card-border);
    border-radius: 16px;
    box-shadow: var(--rn-card-shadow);
    overflow: hidden;
    transition: box-shadow 0.2s;
}
.modern-card-glass:hover {
    box-shadow: 0 6px 24px rgba(0, 40, 90, 0.05);
}

.card-glass-header {
    padding: 15px 20px;
    background: #fbfcfe;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    gap: 9px;
}

.card-glass-icon {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: #eff6ff;
    color: var(--rn-navy);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
}

.card-glass-title {
    font-family: 'Cinzel', serif, sans-serif;
    font-size: 12.5px;
    font-weight: 800;
    color: var(--rn-navy);
    letter-spacing: 0.5px;
    margin: 0;
}

.card-glass-body {
    padding: 16px 20px;
}

/* Dossier Record Rows */
.dossier-record-row {
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding: 9px 0;
    border-bottom: 1px solid #f8fafc;
}
.dossier-record-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.dossier-label {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 6px;
}

.dossier-value-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}

.dossier-val {
    font-size: 13px;
    color: #0f172a;
    word-break: break-all;
}

.badge-pin-luxury {
    font-size: 11px;
    font-weight: 800;
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    padding: 2px 7px;
    border-radius: 5px;
    font-family: monospace;
}

.badge-source-tag {
    font-size: 11.5px;
    font-weight: 700;
    background: #eff6ff;
    color: #1d4ed8;
    padding: 2px 8px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.badge-pageview-counter {
    font-size: 11.5px;
    font-weight: 700;
    background: #ecfdf5;
    color: #047857;
    padding: 2px 8px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.btn-copy-interactive {
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    font-size: 13px;
    padding: 3px 6px;
    border-radius: 5px;
    transition: all 0.15s ease;
}
.btn-copy-interactive:hover {
    color: var(--rn-navy);
    background: #f1f5f9;
}
.btn-copy-interactive.copied-glow {
    background: #ecfdf5;
    color: #059669;
}

/* Shipping Hub Card in Sidebar */
.shipping-hub-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.shipping-hub-recipient {
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.shipping-hub-address {
    font-size: 12px;
    color: #334155;
    line-height: 1.45;
}
.shipping-hub-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 12px;
    color: #64748b;
}
.shipping-hub-phone {
    font-size: 12px;
    color: #059669;
    font-weight: 700;
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 5px;
}

/* ══════════════════════════════════════════════════════════════════════
   4. TAB NAVIGATOR & TAB PANES
   ══════════════════════════════════════════════════════════════════════ */
.studio-tab-strip {
    display: flex;
    gap: 8px;
    background: #ffffff;
    padding: 6px;
    border-radius: 14px;
    border: 1px solid var(--rn-card-border);
    box-shadow: 0 2px 12px rgba(0, 40, 90, 0.02);
    overflow-x: auto;
}

.studio-tab-btn {
    padding: 10px 18px;
    border: none;
    background: transparent;
    border-radius: 10px;
    font-size: 12.5px;
    font-weight: 700;
    color: #64748b;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
    position: relative;
    transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
}

.studio-tab-btn:hover {
    color: var(--rn-navy);
    background: #f8fafc;
}

.studio-tab-btn.active {
    background: var(--rn-navy);
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(0, 40, 90, 0.22);
}

.tab-icon {
    font-size: 14px;
}

.tab-count-badge {
    background: rgba(0, 40, 90, 0.07);
    color: var(--rn-navy);
    font-size: 11px;
    font-weight: 800;
    padding: 2px 7px;
    border-radius: 12px;
}
.studio-tab-btn.active .tab-count-badge {
    background: rgba(255, 255, 255, 0.22);
    color: #ffffff;
}

.tab-pulse-dot {
    width: 6px;
    height: 6px;
    background: #10b981;
    border-radius: 50%;
    display: inline-block;
    animation: tabDotPulse 1.5s infinite;
}
@keyframes tabDotPulse {
    0% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.3; transform: scale(0.6); }
    100% { opacity: 1; transform: scale(1); }
}

.studio-tab-pane {
    display: none;
}

.studio-tab-pane.active {
    display: flex;
    flex-direction: column;
    gap: 16px;
    animation: tabReveal 0.35s cubic-bezier(0.16, 1, 0.3, 1) both;
}

@keyframes tabReveal {
    0% { opacity: 0; transform: translateY(8px); }
    100% { opacity: 1; transform: translateY(0); }
}

/* ══════════════════════════════════════════════════════════════════════
   5. ORDER INTERACTIVE CARDS
   ══════════════════════════════════════════════════════════════════════ */
.order-interactive-card {
    background: #ffffff;
    border: 1px solid var(--rn-card-border);
    border-radius: 16px;
    box-shadow: var(--rn-card-shadow);
    padding: 18px 22px;
    display: flex;
    flex-direction: column;
    gap: 14px;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.order-interactive-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 40, 90, 0.06);
    border-color: #cbd5e1;
}

.order-card-top-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f1f5f9;
}

.order-num-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.order-link-title {
    font-family: monospace;
    font-size: 14.5px;
    font-weight: 800;
    color: var(--rn-navy);
    text-decoration: none;
    letter-spacing: 0.3px;
}
.order-link-title:hover {
    text-decoration: underline;
    color: var(--rn-accent-blue);
}

.order-timestamp {
    font-size: 11.5px;
    color: #94a3b8;
    margin-left: 4px;
}

.order-badge-cluster {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.order-badge-pill {
    font-size: 11px;
    font-weight: 800;
    padding: 3px 9px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.badge-cod { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
.badge-prepaid { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }

.badge-paid { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
.badge-unpaid { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }

/* Order Items Grid Preview */
.order-items-grid {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.order-item-pod {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 12px;
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid #f1f5f9;
}

.item-img-zoom-container {
    width: 46px;
    height: 46px;
    border-radius: 9px;
    overflow: hidden;
    background: #0f172a;
    border: 1px solid #e2e8f0;
    flex-shrink: 0;
}

.item-img-thumb {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1);
}
.order-item-pod:hover .item-img-thumb {
    transform: scale(1.15);
}

.item-pod-details {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.item-pod-title {
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
}

.item-pod-tags {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.spec-chip {
    font-size: 10.5px;
    padding: 1px 6px;
    border-radius: 4px;
}
.spec-size { background: #ffffff; color: #475569; border: 1px solid #cbd5e1; font-weight: 800; }
.spec-color { background: #f1f5f9; color: #475569; font-weight: 600; }
.spec-qty { color: #64748b; font-size: 11px; }
.spec-price {
    font-size: 12px;
    font-weight: 800;
    color: var(--rn-navy);
    margin-left: auto;
}

/* Order Bottom Row */
.order-card-bottom-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    padding-top: 10px;
    border-top: 1px solid #f1f5f9;
}

.order-courier-track {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
}

.courier-live-pill {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 3px 9px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.courier-empty-msg {
    font-size: 11.5px;
    color: #94a3b8;
}

.order-total-and-cta {
    display: flex;
    align-items: center;
    gap: 18px;
    flex-wrap: wrap;
}

.order-grand-total {
    display: flex;
    align-items: baseline;
    gap: 6px;
}

.lbl-total {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
}

.val-total {
    font-size: 19px;
    font-weight: 800;
    color: var(--rn-navy);
}

.order-cta-buttons {
    display: flex;
    align-items: center;
    gap: 6px;
}

.btn-order-glow {
    padding: 6px 13px;
    border-radius: 8px;
    font-size: 11.5px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.18s ease;
}

.btn-glow-studio {
    background: var(--rn-navy);
    color: #ffffff;
}
.btn-glow-studio:hover {
    background: var(--rn-navy-light);
    color: #ffffff;
    box-shadow: 0 3px 10px rgba(0, 40, 90, 0.25);
}

.btn-glow-light {
    background: #f8fafc;
    color: #475569;
    border: 1px solid #e2e8f0;
}
.btn-glow-light:hover {
    background: #e2e8f0;
    color: #0f172a;
}

/* ══════════════════════════════════════════════════════════════════════
   6. LIVE ACTIVITY TIMELINE
   ══════════════════════════════════════════════════════════════════════ */
.timeline-stream-wrapper {
    position: relative;
    padding: 24px 20px 24px 34px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Connected glowing vertical line */
.timeline-stream-wrapper::before {
    content: '';
    position: absolute;
    top: 24px;
    bottom: 24px;
    left: 48px;
    width: 2px;
    background: linear-gradient(180deg, #3b82f6 0%, #cbd5e1 50%, #f1f5f9 100%);
    border-radius: 2px;
}

.timeline-node-item {
    position: relative;
    display: flex;
    gap: 16px;
    align-items: flex-start;
}

.timeline-node-dot {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
    background: #ffffff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    position: relative;
    z-index: 2;
    border: 2px solid #ffffff;
}

.timeline-node-dot.node-pulse {
    animation: radarPing 2.2s infinite;
}

@keyframes radarPing {
    0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.6); }
    70% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
    100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
}

.timeline-node-card {
    flex: 1;
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    border-radius: 12px;
    padding: 12px 16px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    transition: all 0.2s;
}
.timeline-node-card:hover {
    background: #ffffff;
    border-color: #e2e8f0;
    box-shadow: 0 4px 14px rgba(0, 40, 90, 0.04);
}

.timeline-node-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
}

.timeline-node-title {
    font-size: 13.5px;
    font-weight: 700;
    color: var(--rn-navy);
}

.timeline-node-time {
    font-size: 11px;
    color: #94a3b8;
}

.timeline-node-chips {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
    margin-top: 2px;
}

.tag-event-badge {
    font-size: 10px;
    font-weight: 800;
    padding: 2px 7px;
    border-radius: 4px;
    text-transform: uppercase;
}

.tag-geo-chip, .tag-device-chip, .tag-source-chip {
    font-size: 11px;
    color: #64748b;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    padding: 1px 7px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.tag-url-link {
    font-size: 11px;
    color: #0284c7;
    background: #f0f9ff;
    border: 1px solid #bae6fd;
    padding: 1px 7px;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 600;
}
.tag-url-link:hover {
    text-decoration: underline;
}

/* ══════════════════════════════════════════════════════════════════════
   7. SAVED ADDRESSES GRID
   ══════════════════════════════════════════════════════════════════════ */
.address-grid-layout {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}
@media (max-width: 768px) {
    .address-grid-layout { grid-template-columns: 1fr; }
}

.address-card-luxury {
    background: #ffffff;
    border: 1.5px solid var(--rn-card-border);
    border-radius: 14px;
    padding: 18px 20px;
    box-shadow: var(--rn-card-shadow);
    display: flex;
    flex-direction: column;
    gap: 6px;
    transition: all 0.2s;
}

.address-card-luxury.address-is-default {
    border-color: #93c5fd;
    background: #fafcff;
}

.addr-card-topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 4px;
}

.addr-type-pill {
    font-size: 10.5px;
    font-weight: 800;
    color: #475569;
    background: #f1f5f9;
    padding: 2px 8px;
    border-radius: 4px;
}

.addr-star-pill {
    font-size: 10px;
    font-weight: 800;
    color: #1d4ed8;
    background: #dbeafe;
    padding: 2px 8px;
    border-radius: 10px;
}

.addr-name-title {
    font-size: 14px;
    font-weight: 800;
    color: var(--rn-navy);
    margin: 0;
}

.addr-street-line {
    font-size: 12.5px;
    color: #334155;
    line-height: 1.45;
}

.addr-city-zip {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 12px;
    color: #64748b;
}

.addr-contact-row {
    font-size: 12px;
    color: #059669;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 4px;
}

/* ══════════════════════════════════════════════════════════════════════
   8. WHATSAPP CHAT BUBBLES GRID
   ══════════════════════════════════════════════════════════════════════ */
.wa-chat-bubbles-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}
@media (max-width: 800px) {
    .wa-chat-bubbles-grid { grid-template-columns: 1fr; }
}

.wa-chat-bubble-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 16px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 12px;
    transition: all 0.2s;
}
.wa-chat-bubble-card:hover {
    border-color: #cbd5e1;
    background: #ffffff;
    box-shadow: 0 4px 16px rgba(37, 211, 102, 0.08);
}

.wa-bubble-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.wa-intent-badge {
    font-size: 11.5px;
    font-weight: 800;
    color: var(--rn-navy);
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.wa-read-ticks {
    color: #38bdf8;
    font-size: 14px;
}

.wa-bubble-body {
    font-size: 12px;
    color: #334155;
    line-height: 1.5;
    background: #ffffff;
    padding: 12px 14px;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    position: relative;
}

.btn-wa-launch-glow {
    background: #25d366;
    color: #ffffff;
    border: none;
    border-radius: 9px;
    padding: 8px 14px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(37, 211, 102, 0.25);
}
.btn-wa-launch-glow:hover {
    background: #20ba59;
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(37, 211, 102, 0.45);
    transform: translateY(-1px);
}

/* ══════════════════════════════════════════════════════════════════════
   9. REDESIGNED EXECUTIVE BRAND EMAIL STUDIO
   ══════════════════════════════════════════════════════════════════════ */
.email-studio-wrapper {
    position: relative;
    border-radius: 18px;
    padding: 2px;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.3) 0%, rgba(99, 102, 241, 0.15) 50%, rgba(14, 165, 233, 0.3) 100%);
    box-shadow: 0 10px 30px -5px rgba(0, 40, 90, 0.08);
}

.email-studio-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 24px 28px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.email-studio-topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    padding-bottom: 18px;
    border-bottom: 1px solid #f1f5f9;
}

.email-studio-brand {
    display: flex;
    align-items: center;
    gap: 14px;
}

.email-studio-avatar {
    width: 46px;
    height: 46px;
    border-radius: 14px;
    background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    box-shadow: 0 6px 16px rgba(30, 64, 175, 0.3);
    flex-shrink: 0;
}

.email-studio-title-row {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.email-studio-title {
    font-family: 'Cinzel', serif, sans-serif;
    font-size: 16px;
    font-weight: 800;
    color: var(--rn-navy);
    margin: 0;
    letter-spacing: 0.4px;
}

.email-live-badge {
    font-size: 11px;
    font-weight: 800;
    background: #ecfdf5;
    color: #047857;
    border: 1px solid #a7f3d0;
    padding: 2px 8px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.email-studio-sub {
    font-size: 12px;
    color: #64748b;
    margin: 3px 0 0 0;
}

.email-recipient-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    border-radius: 10px;
    font-size: 12.5px;
    font-weight: 700;
    position: relative;
}
.email-recipient-chip.has-email {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #15803d;
}
.email-recipient-chip.no-email {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #dc2626;
}

.verified-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #10b981;
    display: inline-block;
    box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
}

/* Preset Strip */
.email-presets-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    background: #f8fafc;
    padding: 10px 14px;
    border-radius: 12px;
    border: 1px solid #f1f5f9;
}

.presets-label {
    font-size: 11.5px;
    font-weight: 800;
    color: var(--rn-navy);
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.presets-buttons {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.btn-email-preset {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    color: #334155;
    padding: 5px 12px;
    border-radius: 8px;
    font-size: 11.5px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.18s ease;
}
.btn-email-preset:hover {
    background: var(--rn-navy);
    color: #ffffff;
    border-color: var(--rn-navy);
    transform: translateY(-1px);
    box-shadow: 0 3px 10px rgba(0, 40, 90, 0.15);
}

/* Email Form Grid */
.email-composer-form {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.email-form-grid {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.composer-field-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.field-label-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.field-label-row label {
    font-size: 12px;
    font-weight: 800;
    color: var(--rn-navy);
    display: flex;
    align-items: center;
    gap: 6px;
    margin: 0;
}

.field-hint {
    font-size: 11px;
    color: #94a3b8;
    font-weight: 600;
}

.composer-input {
    width: 100%;
    padding: 11px 16px;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    font-size: 13.5px;
    font-weight: 600;
    color: #0f172a;
    background: #ffffff;
    outline: none;
    transition: all 0.2s;
    font-family: inherit;
}
.composer-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3.5px rgba(59, 130, 246, 0.12);
    background: #fafcff;
}

.composer-textarea {
    width: 100%;
    padding: 14px 16px;
    border: 1.5px solid #cbd5e1;
    border-radius: 12px;
    font-size: 13px;
    line-height: 1.6;
    color: #1e293b;
    background: #ffffff;
    outline: none;
    transition: all 0.2s;
    font-family: inherit;
    resize: vertical;
}
.composer-textarea:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3.5px rgba(59, 130, 246, 0.12);
    background: #fafcff;
}

.input-pulse {
    animation: fieldFlash 0.5s ease;
}
@keyframes fieldFlash {
    0% { background: #eff6ff; border-color: #3b82f6; }
    100% { background: #ffffff; border-color: #cbd5e1; }
}

/* Dispatch status */
.email-dispatch-status {
    padding: 10px 16px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 8px;
    animation: statusSlide 0.25s ease;
}
.status-success {
    background: #ecfdf5;
    color: #047857;
    border: 1px solid #a7f3d0;
}
.status-danger {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
}
@keyframes statusSlide {
    0% { opacity: 0; transform: translateY(-5px); }
    100% { opacity: 1; transform: translateY(0); }
}

/* Action Footer */
.composer-action-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 14px;
    padding-top: 14px;
    border-top: 1px solid #f1f5f9;
}

.composer-footer-note {
    font-size: 11.5px;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 6px;
}

.btn-send-email-glow {
    padding: 11px 24px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 800;
    border: none;
    background: linear-gradient(135deg, #00285a 0%, #1e40af 100%);
    color: #ffffff;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 14px rgba(0, 40, 90, 0.25);
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
.btn-send-email-glow:hover:not(:disabled) {
    background: linear-gradient(135deg, #001f4d 0%, #1d4ed8 100%);
    box-shadow: 0 6px 20px rgba(0, 40, 90, 0.35);
    transform: translateY(-2px);
}
.btn-send-email-glow:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    box-shadow: none;
}

/* ══════════════════════════════════════════════════════════════════════
   10. EMPTY STATE ANIMATIONS
   ══════════════════════════════════════════════════════════════════════ */
.empty-state-card {
    padding: 45px 20px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
}

.empty-state-icon-anim {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #f1f5f9;
    color: #94a3b8;
    font-size: 26px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-state-title {
    font-size: 16px;
    font-weight: 800;
    color: var(--rn-navy);
    margin: 0;
}

.empty-state-desc {
    font-size: 13px;
    color: #64748b;
    max-width: 440px;
    margin: 0;
}

/* ══════════════════════════════════════════════════════════════════════
   11. LUXURY MODALS
   ══════════════════════════════════════════════════════════════════════ */
.luxury-modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(7, 19, 41, 0.65);
    backdrop-filter: blur(8px);
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    animation: modalBackdropFade 0.25s ease;
}

@keyframes modalBackdropFade {
    0% { opacity: 0; }
    100% { opacity: 1; }
}

.luxury-modal-window {
    background: #ffffff;
    border-radius: 20px;
    width: 100%;
    max-width: 560px;
    box-shadow: 0 25px 65px -10px rgba(0, 0, 0, 0.35);
    border: 1px solid rgba(255, 255, 255, 0.3);
    overflow: hidden;
    animation: modalPop 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes modalPop {
    0% { opacity: 0; transform: scale(0.94); }
    100% { opacity: 1; transform: scale(1); }
}

.luxury-modal-header {
    padding: 18px 24px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fafbff;
}

.luxury-modal-title {
    margin: 0;
    font-size: 15.5px;
    font-weight: 800;
    color: var(--rn-navy);
}

.luxury-modal-close {
    background: #f1f5f9;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    font-size: 18px;
    color: #64748b;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s;
}
.luxury-modal-close:hover {
    background: #fee2e2;
    color: #ef4444;
}

.luxury-modal-body {
    padding: 22px 24px;
}

.form-group-luxury label {
    font-size: 12px;
    font-weight: 700;
    color: #475569;
    margin-bottom: 5px;
    display: block;
}

.form-control-luxury {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    font-size: 13px;
    outline: none;
    transition: all 0.15s;
    font-family: inherit;
}
.form-control-luxury:focus {
    border-color: var(--rn-navy);
    box-shadow: 0 0 0 3.5px rgba(0, 40, 90, 0.1);
}

.luxury-modal-footer {
    padding: 16px 24px;
    border-top: 1px solid #f1f5f9;
    background: #fafbff;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

/* ══════════════════════════════════════════════════════════════════════
   12. FLOATING LUXURY TOAST
   ══════════════════════════════════════════════════════════════════════ */
.cust-toast-luxury {
    position: fixed;
    bottom: 32px;
    right: 32px;
    background: #0b192c;
    color: #ffffff;
    padding: 12px 22px;
    border-radius: 14px;
    font-size: 13px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.28);
    border: 1px solid rgba(255, 255, 255, 0.15);
    z-index: 100000;
    opacity: 0;
    transform: translateY(20px) scale(0.95);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    pointer-events: none;
}

.cust-toast-luxury.toast-active {
    opacity: 1;
    transform: translateY(0) scale(1);
}

.toast-icon-circle {
    color: #10b981;
    font-size: 17px;
    display: flex;
    align-items: center;
}
</style>
@endsection
