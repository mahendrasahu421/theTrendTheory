{{-- resources/views/admin/returns/show.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Return #' . $return->return_number . ' - Fulfillment Studio')

@section('content')
@php
    $steps = [
        'pending'   => ['label' => '1. Submitted', 'desc' => 'Customer requested', 'icon' => 'bi-file-earmark-text'],
        'approved'  => ['label' => '2. Approved', 'desc' => 'Pickup scheduled', 'icon' => 'bi-check2-circle'],
        'picked_up' => ['label' => '3. Picked Up', 'desc' => 'In courier transit', 'icon' => 'bi-truck'],
        'received'  => ['label' => '4. Received', 'desc' => 'Inspected at hub', 'icon' => 'bi-box-seam'],
        'completed' => ['label' => '5. Completed', 'desc' => $return->type === 'return' ? 'Refund settled' : 'Exchange dispatched', 'icon' => 'bi-shield-fill-check'],
    ];
    $statusOrder = array_keys($steps);
    $currentIdx = array_search($return->status, $statusOrder);
    if ($currentIdx === false) {
        $currentIdx = ($return->status === 'rejected') ? -1 : 0;
    }
@endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

    .rtn-studio-wrap {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        display: flex;
        flex-direction: column;
        gap: 20px;
        color: #0f172a;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* ── 1. Hero Banner ── */
    .rtn-hero-banner {
        background: linear-gradient(135deg, #0b192e 0%, #0f2b54 50%, #1e3a8a 100%);
        border-radius: 20px;
        padding: 24px 30px;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(11, 25, 46, 0.2);
    }
    .rtn-hero-glow {
        position: absolute;
        top: -60px;
        right: -60px;
        width: 260px;
        height: 260px;
        background: radial-gradient(circle, rgba(96, 165, 250, 0.22) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .rtn-hero-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 12px;
    }
    .rtn-hero-badges {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .rtn-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        color: #93c5fd;
    }
    .rtn-pulse-dot {
        width: 7px;
        height: 7px;
        background: #60a5fa;
        border-radius: 50%;
        box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.4);
    }
    .rtn-hero-title {
        font-size: 24px;
        font-weight: 800;
        letter-spacing: -0.4px;
        margin: 0 0 6px;
        color: #ffffff;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .rtn-hero-meta {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.82);
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }
    .rtn-hero-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .rtn-hero-btn {
        padding: 7px 16px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.18s ease;
    }
    .rtn-hero-btn-light {
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.25);
    }
    .rtn-hero-btn-light:hover {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }

    /* ── 2. Visual Progress Tracker ── */
    .rtn-tracker-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 20px 24px;
        box-shadow: 0 4px 16px -4px rgba(15, 23, 42, 0.04);
    }
    .rtn-tracker-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        position: relative;
        gap: 8px;
    }
    .rtn-tracker-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        position: relative;
        z-index: 1;
    }
    .rtn-tracker-line {
        position: absolute;
        top: 20px;
        left: 10%;
        right: 10%;
        height: 3px;
        background: #e2e8f0;
        z-index: 0;
    }
    .rtn-tracker-line-fill {
        height: 100%;
        background: #00285a;
        transition: width 0.3s ease;
    }
    .rtn-step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #f8fafc;
        border: 2px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: #64748b;
        margin-bottom: 8px;
        transition: all 0.2s ease;
    }
    .rtn-tracker-step.done .rtn-step-circle {
        background: #00285a;
        border-color: #00285a;
        color: #ffffff;
    }
    .rtn-tracker-step.active .rtn-step-circle {
        background: #00285a;
        border-color: #00285a;
        color: #ffffff;
        box-shadow: 0 0 0 5px rgba(0, 40, 90, 0.15);
    }
    .rtn-step-lbl {
        font-size: 12px;
        font-weight: 800;
        color: #0f172a;
    }
    .rtn-step-sub {
        font-size: 10.5px;
        color: #64748b;
        margin-top: 1px;
    }

    /* ── 3. Bento 2-Col Grid ── */
    .rtn-bento-grid {
        display: grid;
        grid-template-columns: 1.25fr 0.95fr;
        gap: 20px;
        align-items: flex-start;
    }

    /* Card System */
    .rtn-bento-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        box-shadow: 0 4px 16px -4px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }
    .rtn-bento-head {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
    }
    .rtn-bento-title {
        font-size: 13.5px;
        font-weight: 800;
        color: #0f172a;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .rtn-bento-body {
        padding: 18px 20px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    /* Product Item Rows */
    .rtn-prod-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
    }
    .rtn-prod-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .rtn-prod-img {
        width: 56px;
        height: 56px;
        min-width: 56px;
        border-radius: 12px;
        object-fit: cover;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }
    .rtn-prod-fallback {
        width: 56px;
        height: 56px;
        min-width: 56px;
        border-radius: 12px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 22px;
    }
    .rtn-prod-title {
        font-size: 13.5px;
        font-weight: 700;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .rtn-prod-meta {
        font-size: 11.5px;
        color: #64748b;
        margin-top: 2px;
    }
    .rtn-prod-price {
        font-size: 14px;
        font-weight: 800;
        color: #00285a;
        white-space: nowrap;
    }

    /* Detail Rows */
    .rtn-info-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 12.5px;
        border-bottom: 1px solid #f8fafc;
    }
    .rtn-info-row:last-child { border-bottom: none; }
    .rtn-info-label { color: #64748b; font-weight: 500; }
    .rtn-info-val { color: #0f172a; font-weight: 700; text-align: right; }

    /* Pills */
    .rtn-pill-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
    }
    .rtn-p-pending   { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
    .rtn-p-approved  { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .rtn-p-rejected  { background: #fff1f2; color: #9f1239; border: 1px solid #fecdd3; }
    .rtn-p-completed { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .rtn-p-picked_up, .rtn-p-received { background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd; }

    /* Banking / Copyable Info Box */
    .rtn-bank-box {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 14px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .rtn-copy-btn {
        padding: 2px 8px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        border-radius: 6px;
        font-size: 10.5px;
        font-weight: 700;
        cursor: pointer;
        color: #00285a;
        margin-left: 6px;
        transition: all 0.15s ease;
    }
    .rtn-copy-btn:hover {
        background: #f0f4ff;
        border-color: #00285a;
    }

    /* ── Form Controls & Buttons ── */
    .rtn-form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 14px;
    }
    .rtn-form-label {
        font-size: 11.5px;
        font-weight: 800;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin: 0;
    }
    .rtn-form-select, .rtn-form-input, .rtn-form-textarea {
        width: 100% !important;
        padding: 10px 14px !important;
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 10px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        color: #0f172a !important;
        font-family: inherit !important;
        outline: none !important;
        box-sizing: border-box !important;
        display: block !important;
        transition: all 0.15s ease !important;
    }
    .rtn-form-select:focus, .rtn-form-input:focus, .rtn-form-textarea:focus {
        border-color: #00285a !important;
        box-shadow: 0 0 0 3px rgba(0, 40, 90, 0.1) !important;
    }
    .rtn-form-textarea {
        min-height: 80px !important;
        resize: vertical !important;
        line-height: 1.5 !important;
    }

    .rtn-btn-primary {
        width: 100% !important;
        padding: 12px 18px !important;
        background: #00285a !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 10px !important;
        font-size: 13px !important;
        font-weight: 800 !important;
        cursor: pointer !important;
        font-family: inherit !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 4px 12px rgba(0, 40, 90, 0.15) !important;
    }
    .rtn-btn-primary:hover {
        background: #0f4c81 !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 16px rgba(0, 40, 90, 0.22) !important;
    }

    .rtn-btn-success {
        width: 100% !important;
        padding: 12px 18px !important;
        background: #15803d !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 10px !important;
        font-size: 13px !important;
        font-weight: 800 !important;
        cursor: pointer !important;
        font-family: inherit !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 4px 12px rgba(21, 128, 61, 0.18) !important;
    }
    .rtn-btn-success:hover {
        background: #166534 !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 16px rgba(21, 128, 61, 0.25) !important;
    }

    @media (max-width: 960px) {
        .rtn-bento-grid { grid-template-columns: 1fr; }
        .rtn-tracker-grid { grid-template-columns: 1fr; gap: 14px; }
        .rtn-tracker-line { display: none; }
    }
</style>

<div class="rtn-studio-wrap">

    {{-- 1. Executive Hero Banner --}}
    <div class="rtn-hero-banner">
        <div class="rtn-hero-glow"></div>
        <div class="rtn-hero-top">
            <div class="rtn-hero-badges">
                <div class="rtn-hero-badge">
                    <div class="rtn-pulse-dot"></div>
                    <span>{{ strtoupper($return->type) }} REQUEST</span>
                </div>
                <span class="rtn-pill-badge rtn-p-{{ $return->status }}" style="background:rgba(255,255,255,0.18);color:#ffffff;border-color:rgba(255,255,255,0.3);">
                    {{ ucwords(str_replace('_',' ',$return->status)) }}
                </span>
            </div>

            <div class="rtn-hero-actions">
                <a href="{{ route('admin.orders.show', $return->order_id) }}" class="rtn-hero-btn rtn-hero-btn-light">
                    <i class="bi bi-box-arrow-up-right"></i> View Order #{{ optional($return->order)->order_number }}
                </a>
                <a href="{{ route('admin.returns.index') }}" class="rtn-hero-btn rtn-hero-btn-light">
                    <i class="bi bi-arrow-left"></i> Returns List
                </a>
            </div>
        </div>

        <h1 class="rtn-hero-title">
            <span>Return #{{ $return->return_number }}</span>
        </h1>

        <div class="rtn-hero-meta">
            <span><i class="bi bi-person me-1"></i> {{ optional($return->user)->name ?? 'Customer' }}</span>
            <span>&bull;</span>
            <span><i class="bi bi-telephone me-1"></i> {{ optional($return->user)->phone ?: optional($return->user)->email }}</span>
            <span>&bull;</span>
            <span><i class="bi bi-calendar3 me-1"></i> Submitted {{ $return->created_at->format('d M Y, h:i A') }}</span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:12px;font-size:12.5px;font-weight:700;">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:12px;font-size:12.5px;font-weight:700;">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- 2. Visual Progress Tracker --}}
    @if($return->status !== 'rejected')
    <div class="rtn-tracker-card">
        <div class="rtn-tracker-grid">
            <div class="rtn-tracker-line">
                <div class="rtn-tracker-line-fill" style="width: {{ $currentIdx >= 0 ? ($currentIdx / 4) * 100 : 0 }}%;"></div>
            </div>
            @foreach($steps as $key => $st)
                @php $idx = array_search($key, $statusOrder); @endphp
                <div class="rtn-tracker-step {{ $idx < $currentIdx ? 'done' : ($idx === $currentIdx ? 'active' : '') }}">
                    <div class="rtn-step-circle">
                        @if($idx < $currentIdx)
                            <i class="bi bi-check-lg"></i>
                        @else
                            <i class="bi {{ $st['icon'] }}"></i>
                        @endif
                    </div>
                    <div class="rtn-step-lbl">{{ $st['label'] }}</div>
                    <div class="rtn-step-sub">{{ $st['desc'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="alert alert-danger" style="border-radius:14px;padding:16px 20px;display:flex;align-items:center;gap:12px;">
        <i class="bi bi-x-circle-fill" style="font-size:24px;"></i>
        <div>
            <div style="font-weight:800;font-size:14px;">This Return Request was Rejected</div>
            <div style="font-size:12px;color:#991b1b;margin-top:2px;">Remarks: {{ $return->admin_notes ?: 'Request did not meet return policy criteria.' }}</div>
        </div>
    </div>
    @endif

    {{-- 3. Bento 2-Col Grid --}}
    <div class="rtn-bento-grid">

        {{-- ═══════════════════════════════════════════
             LEFT COLUMN: PRODUCTS & REASON DETAILS
             ═══════════════════════════════════════════ --}}
        <div style="display:flex;flex-direction:column;gap:18px;">

            {{-- Products Card --}}
            <div class="rtn-bento-card">
                <div class="rtn-bento-head">
                    <h2 class="rtn-bento-title"><i class="bi bi-bag-check"></i> Products in this Order</h2>
                    <span style="font-size:11.5px;font-weight:700;color:#64748b;">
                        {{ optional($return->order)->items ? $return->order->items->count() : 0 }} Items
                    </span>
                </div>
                <div class="rtn-bento-body">
                    @if($return->order && $return->order->items)
                        @foreach($return->order->items as $item)
                            @php
                                $prodImg = '';
                                if (!empty($item->product_image)) {
                                    if (filter_var($item->product_image, FILTER_VALIDATE_URL) || str_starts_with($item->product_image, 'http') || str_starts_with($item->product_image, '/')) {
                                        $prodImg = $item->product_image;
                                    } else {
                                        $prodImg = url('/storage/' . $item->product_image);
                                    }
                                } elseif ($item->product) {
                                    $prodImg = $item->product->main_image ?? $item->product->image ?? '';
                                }
                            @endphp
                            <div class="rtn-prod-item">
                                @if($prodImg)
                                    <img class="rtn-prod-img"
                                         src="{{ $prodImg }}"
                                         alt="{{ $item->product_name }}"
                                         onerror="this.onerror=null;this.parentElement.innerHTML='<div class=\'rtn-prod-fallback\'><i class=\'bi bi-bag\'></i></div>';">
                                @else
                                    <div class="rtn-prod-fallback"><i class="bi bi-bag"></i></div>
                                @endif
                                <div style="flex:1;min-width:0;">
                                    <div class="rtn-prod-title">{{ $item->product_name }}</div>
                                    <div class="rtn-prod-meta">
                                        @if($item->size) Size: <strong>{{ $item->size }}</strong> &bull; @endif
                                        Qty: <strong>{{ $item->quantity }}</strong> &bull; Price: ₹{{ number_format($item->unit_price) }}
                                    </div>
                                </div>
                                <div class="rtn-prod-price">
                                    ₹{{ number_format($item->subtotal ?: ($item->unit_price * $item->quantity)) }}
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- Request Reason & Details --}}
            <div class="rtn-bento-card">
                <div class="rtn-bento-head">
                    <h2 class="rtn-bento-title"><i class="bi bi-card-text"></i> Return Reason &amp; Specification</h2>
                </div>
                <div class="rtn-bento-body">
                    <div class="rtn-info-row">
                        <span class="rtn-info-label">Request Type</span>
                        <span class="rtn-info-val" style="color:#00285a;text-transform:uppercase;">{{ $return->type }}</span>
                    </div>
                    <div class="rtn-info-row">
                        <span class="rtn-info-label">Reason Selected</span>
                        <span class="rtn-pill-badge" style="background:#f0f4ff;color:#00285a;border:1px solid #dbeafe;">
                            {{ $return->reason_label ?? ucfirst(str_replace('_',' ',$return->reason)) }}
                        </span>
                    </div>
                    @if($return->description)
                        <div class="rtn-info-row" style="align-items:flex-start;">
                            <span class="rtn-info-label">Customer Remarks</span>
                            <span class="rtn-info-val" style="max-width:65%;">{{ $return->description }}</span>
                        </div>
                    @endif

                    {{-- If Exchange --}}
                    @if($return->type === 'exchange')
                        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;padding:12px 14px;margin-top:6px;">
                            <div style="font-size:11.5px;font-weight:800;color:#1d4ed8;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:6px;">
                                <i class="bi bi-arrow-left-right me-1"></i> Exchange Replacement Details
                            </div>
                            <div class="rtn-info-row">
                                <span class="rtn-info-label">Requested Size</span>
                                <span class="rtn-info-val" style="color:#1d4ed8;font-size:13px;">{{ $return->exchange_size ?? 'N/A' }}</span>
                            </div>
                            @if($return->exchange_color)
                                <div class="rtn-info-row">
                                    <span class="rtn-info-label">Color Preference</span>
                                    <span class="rtn-info-val">{{ $return->exchange_color }}</span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- Customer Profile --}}
            <div class="rtn-bento-card">
                <div class="rtn-bento-head">
                    <h2 class="rtn-bento-title"><i class="bi bi-person-badge"></i> Customer Profile</h2>
                </div>
                <div class="rtn-bento-body">
                    <div class="rtn-info-row">
                        <span class="rtn-info-label">Full Name</span>
                        <span class="rtn-info-val">{{ optional($return->user)->name ?? 'Customer' }}</span>
                    </div>
                    <div class="rtn-info-row">
                        <span class="rtn-info-label">Email Address</span>
                        <span class="rtn-info-val">{{ optional($return->user)->email ?? 'N/A' }}</span>
                    </div>
                    <div class="rtn-info-row">
                        <span class="rtn-info-label">Contact Phone</span>
                        <span class="rtn-info-val">{{ optional($return->user)->phone ?? 'N/A' }}</span>
                    </div>
                    @if(optional($return->user)->city)
                        <div class="rtn-info-row">
                            <span class="rtn-info-label">Location</span>
                            <span class="rtn-info-val">{{ $return->user->city }}, {{ $return->user->state }}</span>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- ═══════════════════════════════════════════
             RIGHT COLUMN: OPERATIONS & REFUND SETTLEMENT
             ═══════════════════════════════════════════ --}}
        <div style="display:flex;flex-direction:column;gap:18px;">

            {{-- 1. Lifecycle Status Action Card --}}
            <div class="rtn-bento-card" style="border: 2px solid #00285a;">
                <div class="rtn-bento-head" style="background:#f8fafc;">
                    <h2 class="rtn-bento-title" style="color:#00285a;"><i class="bi bi-sliders"></i> Update Lifecycle Status</h2>
                </div>
                <div class="rtn-bento-body">
                    <form method="POST" action="{{ route('admin.returns.update-status', $return->id) }}">
                        @csrf
                        @method('PATCH')

                        <div class="rtn-form-group">
                            <label class="rtn-form-label">Change Return Status</label>
                            <select name="status" class="rtn-form-select">
                                @foreach([
                                    'pending'   => '1. Pending Review',
                                    'approved'  => '2. Approved for Pickup',
                                    'picked_up' => '3. Item Picked Up by Courier',
                                    'received'  => '4. Item Received & Inspected',
                                    'completed' => '5. Completed (Settled)',
                                    'rejected'  => 'X. Reject Request'
                                ] as $sVal => $sLbl)
                                    <option value="{{ $sVal }}" {{ $return->status === $sVal ? 'selected' : '' }}>{{ $sLbl }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="rtn-form-group">
                            <label class="rtn-form-label">Admin Operational Remarks</label>
                            <textarea name="admin_notes" class="rtn-form-textarea" placeholder="Enter inspection notes, courier tracking ID, or remarks...">{{ old('admin_notes', $return->admin_notes) }}</textarea>
                        </div>

                        <button type="submit" class="rtn-btn-primary">
                            <i class="bi bi-check2-circle"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>

            {{-- 2. Refund Settlement Card (if Return) --}}
            @if($return->type === 'return' && $return->refund)
                @php $refund = $return->refund; @endphp
                <div class="rtn-bento-card" style="border: 2px solid #059669;">
                    <div class="rtn-bento-head" style="background:#f0fdf4;">
                        <h2 class="rtn-bento-title" style="color:#166534;"><i class="bi bi-cash-stack"></i> Refund Settlement</h2>
                        <span class="rtn-pill-badge rtn-p-{{ $refund->status }}">{{ ucfirst($refund->status) }}</span>
                    </div>
                    <div class="rtn-bento-body">
                        
                        {{-- Refund Amount Header --}}
                        <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:12px;padding:14px 16px;text-align:center;">
                            <div style="font-size:11px;font-weight:800;color:#166534;text-transform:uppercase;letter-spacing:0.5px;">Refundable Amount</div>
                            <div style="font-size:26px;font-weight:900;color:#15803d;letter-spacing:-0.5px;margin-top:2px;">₹{{ number_format(round($refund->amount)) }}</div>
                            <div style="font-size:11.5px;color:#166534;margin-top:2px;">Destination: <strong>{{ ucwords(str_replace('_',' ',$refund->method)) }}</strong></div>
                        </div>

                        {{-- Bank / UPI Destination Card --}}
                        @if($refund->method === 'bank_transfer')
                            <div class="rtn-bank-box">
                                <div style="font-size:11px;font-weight:800;color:#475569;text-transform:uppercase;margin-bottom:2px;">Customer Bank Account Details</div>
                                <div class="rtn-info-row">
                                    <span class="rtn-info-label">Account Holder</span>
                                    <span class="rtn-info-val">{{ $refund->account_holder ?? 'N/A' }}</span>
                                </div>
                                <div class="rtn-info-row">
                                    <span class="rtn-info-label">Bank Name</span>
                                    <span class="rtn-info-val">{{ $refund->bank_name ?? 'N/A' }}</span>
                                </div>
                                <div class="rtn-info-row">
                                    <span class="rtn-info-label">Account Number</span>
                                    <span class="rtn-info-val">
                                        {{ $refund->account_number ?? 'N/A' }}
                                        @if($refund->account_number)
                                            <button type="button" class="rtn-copy-btn" onclick="navigator.clipboard.writeText('{{ $refund->account_number }}');alert('Account number copied!');">Copy</button>
                                        @endif
                                    </span>
                                </div>
                                <div class="rtn-info-row">
                                    <span class="rtn-info-label">IFSC Code</span>
                                    <span class="rtn-info-val">
                                        {{ $refund->ifsc_code ?? 'N/A' }}
                                        @if($refund->ifsc_code)
                                            <button type="button" class="rtn-copy-btn" onclick="navigator.clipboard.writeText('{{ $refund->ifsc_code }}');alert('IFSC copied!');">Copy</button>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        @elseif($refund->method === 'upi')
                            <div class="rtn-bank-box">
                                <div style="font-size:11px;font-weight:800;color:#475569;text-transform:uppercase;margin-bottom:2px;">UPI Virtual Address</div>
                                <div class="rtn-info-row">
                                    <span class="rtn-info-label">UPI ID / VPA</span>
                                    <span class="rtn-info-val" style="color:#00285a;font-size:13px;">
                                        {{ $refund->upi_id ?? 'N/A' }}
                                        @if($refund->upi_id)
                                            <button type="button" class="rtn-copy-btn" onclick="navigator.clipboard.writeText('{{ $refund->upi_id }}');alert('UPI ID copied!');">Copy</button>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        @endif

                        <hr style="border:none;border-top:1px solid #f1f5f9;margin:2px 0;">

                        {{-- Update Refund Form --}}
                        <form method="POST" action="{{ route('admin.returns.update-refund', $return->id) }}">
                            @csrf
                            @method('PATCH')

                            <div class="rtn-form-group">
                                <label class="rtn-form-label">Payout Settlement Status</label>
                                <select name="status" class="rtn-form-select">
                                    <option value="pending" {{ $refund->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $refund->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="completed" {{ $refund->status === 'completed' ? 'selected' : '' }}>Completed (Money Transferred)</option>
                                    <option value="failed" {{ $refund->status === 'failed' ? 'selected' : '' }}>Failed</option>
                                </select>
                            </div>

                            <div class="rtn-form-group">
                                <label class="rtn-form-label">Gateway Refund ID / UTR / Reference No.</label>
                                <input type="text" name="refund_id" class="rtn-form-input" value="{{ old('refund_id', $refund->refund_id) }}" placeholder="e.g. rfnd_ABC123 or UTR12345678">
                            </div>

                            <button type="submit" class="rtn-btn-success">
                                <i class="bi bi-shield-check"></i> Save Refund Settlement
                            </button>
                        </form>

                    </div>
                </div>
            @endif

        </div>

    </div>

</div>
@endsection
