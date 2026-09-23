{{-- resources/views/admin/analytics/sales_dashboard.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Sales Analytics & Revenue Intelligence')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

    :root {
        --brand-navy: #00285a;
        --brand-navy-dark: #071933;
        --brand-navy-deep: #0a1128;
        --brand-blue: #2563eb;
        --brand-emerald: #10b981;
        --brand-amber: #f59e0b;
        --brand-purple: #8b5cf6;
        --brand-rose: #f43f5e;
        --brand-cyan: #06b6d4;
        --studio-border: #e2e8f0;
        --studio-bg: #f8fafc;
        --studio-card-bg: #ffffff;
        --studio-text: #0f172a;
        --studio-muted: #64748b;
    }

    .sales-studio-root {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        display: flex;
        flex-direction: column;
        gap: 24px;
        color: var(--studio-text);
        max-width: 1480px;
        margin: 0 auto;
        padding-bottom: 80px;
    }

    /* ── 1. Executive Hero Command Banner ── */
    .dash-hero-banner {
        background: linear-gradient(135deg, #001838 0%, #00285a 50%, #071933 100%);
        border-radius: 24px;
        padding: 28px 32px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px -15px rgba(0, 40, 90, 0.45);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.12);
    }
    .hero-mesh-glow {
        position: absolute;
        width: 320px;
        height: 320px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.28) 0%, rgba(16, 185, 129, 0.12) 60%, transparent 80%);
        top: -120px;
        right: -80px;
        pointer-events: none;
        filter: blur(40px);
        animation: floatGlow 8s ease-in-out infinite alternate;
    }
    @keyframes floatGlow {
        0% { transform: translate(0, 0) scale(1); }
        100% { transform: translate(-30px, 30px) scale(1.15); }
    }
    .hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
    }
    .hero-left {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .hero-badge-row {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .live-pulse-chip {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: rgba(16, 185, 129, 0.18);
        border: 1px solid rgba(16, 185, 129, 0.35);
        color: #34d399;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.6px;
        text-transform: uppercase;
    }
    .pulse-dot {
        width: 7px;
        height: 7px;
        background: #10b981;
        border-radius: 50%;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.4);
        animation: pulseAnimation 2s infinite;
    }
    @keyframes pulseAnimation {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.35); opacity: 0.7; }
    }
    .hero-time-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: #cbd5e1;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 11.5px;
        font-weight: 600;
        font-variant-numeric: tabular-nums;
    }
    .hero-title {
        font-size: 26px;
        font-weight: 900;
        color: #ffffff;
        margin: 0;
        line-height: 1.2;
        letter-spacing: -0.6px;
    }
    .gradient-text {
        background: linear-gradient(135deg, #60a5fa 0%, #34d399 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .hero-sub {
        font-size: 13.5px;
        color: #94a3b8;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .hero-right {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    /* Timeframe Selector in Hero */
    .timeframe-card {
        background: rgba(255, 255, 255, 0.07);
        border: 1px solid rgba(255, 255, 255, 0.14);
        backdrop-filter: blur(10px);
        border-radius: 14px;
        padding: 5px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .tf-pill {
        padding: 7px 14px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        color: #cbd5e1;
        text-decoration: none;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        white-space: nowrap;
    }
    .tf-pill:hover {
        color: #ffffff;
        background: rgba(255, 255, 255, 0.12);
    }
    .tf-pill.active {
        background: #ffffff;
        color: var(--brand-navy);
        font-weight: 800;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    .select-period-dropdown {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #ffffff;
        padding: 7px 12px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        outline: none;
        transition: background 0.15s ease;
    }
    .select-period-dropdown:hover {
        background: rgba(255, 255, 255, 0.14);
    }
    .select-period-dropdown option {
        background: #001838;
        color: #ffffff;
    }
    .btn-hero-action {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.18);
        color: #ffffff;
        padding: 8px 14px;
        border-radius: 12px;
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }
    .btn-hero-action:hover {
        background: rgba(255, 255, 255, 0.18);
        color: #ffffff;
        transform: translateY(-1px);
    }
    .btn-hero-action.active {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        border-color: #60a5fa;
    }

    /* ── Stock Alert Banner (If Low Stock Exists) ── */
    .urgent-alert-card {
        background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%);
        border: 1px solid #fecdd3;
        border-radius: 16px;
        padding: 14px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        box-shadow: 0 4px 14px rgba(244, 63, 94, 0.08);
    }
    .alert-headline {
        font-size: 13px;
        font-weight: 800;
        color: #9f1239;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ── Section Headings ── */
    .dash-section-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 4px;
        flex-wrap: wrap;
        gap: 8px;
    }
    .section-title-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .section-indicator {
        width: 4px;
        height: 20px;
        background: linear-gradient(180deg, var(--brand-blue), var(--brand-emerald));
        border-radius: 4px;
    }
    .section-title-wrap h2 {
        font-size: 17px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        letter-spacing: -0.3px;
    }
    .section-meta-tag {
        font-size: 12px;
        color: var(--studio-muted);
        font-weight: 600;
    }

    /* ── 2. Top Animated Bento KPI Cards ── */
    .kpi-cards-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }
    @media (max-width: 1200px) {
        .kpi-cards-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 640px) {
        .kpi-cards-grid { grid-template-columns: 1fr; }
    }

    .kpi-card-box {
        background: #ffffff;
        border: 1px solid var(--studio-border);
        border-radius: 20px;
        overflow: hidden;
        position: relative;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
        transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .kpi-card-box:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.09);
        border-color: #cbd5e1;
    }
    .kpi-glass {
        padding: 22px 24px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .kpi-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .kpi-tag {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        color: #64748b;
    }
    .kpi-icon-bubble {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        transition: transform 0.25s ease;
    }
    .kpi-card-box:hover .kpi-icon-bubble {
        transform: scale(1.12) rotate(-4deg);
    }

    /* Color Bubble Themes */
    .theme-navy .kpi-icon-bubble { background: #eff6ff; color: #00285a; }
    .theme-emerald .kpi-icon-bubble { background: #ecfdf5; color: #059669; }
    .theme-indigo .kpi-icon-bubble { background: #e0e7ff; color: #4338ca; }
    .theme-purple .kpi-icon-bubble { background: #faf5ff; color: #7e22ce; }
    .theme-amber .kpi-icon-bubble { background: #fffbeb; color: #d97706; }
    .theme-rose .kpi-icon-bubble { background: #fff1f2; color: #e11d48; }
    .theme-cyan .kpi-icon-bubble { background: #ecfeff; color: #0891b2; }

    .kpi-number-wrap {
        display: flex;
        align-items: baseline;
        gap: 4px;
    }
    .kpi-currency {
        font-size: 19px;
        font-weight: 800;
        color: #64748b;
    }
    .kpi-val {
        font-size: 28px;
        font-weight: 900;
        color: #0f172a;
        line-height: 1;
        letter-spacing: -0.8px;
        font-variant-numeric: tabular-nums;
    }
    .kpi-sub {
        font-size: 12px;
        color: #64748b;
        margin: 0;
    }
    .kpi-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 4px;
    }
    .kpi-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 9px;
        border-radius: 8px;
        font-size: 11.5px;
        font-weight: 700;
    }
    .badge-success { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
    .badge-danger { background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3; }
    .badge-info { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
    .badge-amber { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }

    /* Animated bottom color bar */
    .kpi-bottom-bar {
        height: 4px;
        width: 100%;
    }
    .bar-navy { background: linear-gradient(90deg, #00285a, #2563eb); }
    .bar-emerald { background: linear-gradient(90deg, #059669, #10b981); }
    .bar-indigo { background: linear-gradient(90deg, #4338ca, #6366f1); }
    .bar-purple { background: linear-gradient(90deg, #7e22ce, #a855f7); }
    .bar-amber { background: linear-gradient(90deg, #d97706, #f59e0b); }
    .bar-rose { background: linear-gradient(90deg, #e11d48, #f43f5e); }
    .bar-cyan { background: linear-gradient(90deg, #0891b2, #06b6d4); }

    /* Dual Segment Meter */
    .dual-meter-track {
        height: 8px;
        background: #f1f5f9;
        border-radius: 999px;
        display: flex;
        overflow: hidden;
        margin-top: 6px;
    }
    .dual-meter-cod { background: #f59e0b; height: 100%; transition: width 0.8s ease; }
    .dual-meter-prepaid { background: #2563eb; height: 100%; transition: width 0.8s ease; }

    /* ── 3. Panels & Grids ── */
    .studio-panel-card {
        background: #ffffff;
        border: 1px solid var(--studio-border);
        border-radius: 22px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        transition: border-color 0.2s ease;
    }
    .studio-panel-card:hover {
        border-color: #cbd5e1;
    }
    .studio-panel-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f1f5f9;
        flex-wrap: wrap;
        gap: 10px;
    }
    .studio-panel-title {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        letter-spacing: -0.3px;
    }
    .two-col-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }
    @media (max-width: 992px) {
        .two-col-grid { grid-template-columns: 1fr; }
    }

    /* Metric Tiles inside Panels */
    .metric-tiles-row {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 16px;
    }
    @media (max-width: 580px) {
        .metric-tiles-row { grid-template-columns: 1fr; }
    }
    .metric-tile {
        padding: 16px 18px;
        border-radius: 16px;
        border: 1px solid var(--studio-border);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 4px;
        transition: transform 0.2s ease;
    }
    .metric-tile:hover {
        transform: translateY(-2px);
    }
    .metric-tile-blue { background: #f8fbff; border-color: #dbeafe; }
    .metric-tile-green { background: #f0fdf4; border-color: #bbf7d0; }
    .metric-tile-amber { background: #fffdf5; border-color: #fde68a; }
    .metric-tile-rose { background: #fff5f5; border-color: #fecaca; }

    .tile-label { font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
    .tile-num { font-size: 20px; font-weight: 900; color: #0f172a; line-height: 1.1; font-variant-numeric: tabular-nums; }
    .tile-sub { font-size: 12px; font-weight: 600; margin-top: 2px; }

    /* ── High-Tech Analytics Tables ── */
    .analytics-table-wrap {
        overflow-x: auto;
        border-radius: 14px;
        border: 1px solid #f1f5f9;
    }
    .analytics-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    .analytics-table th {
        font-size: 11px;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding: 14px 16px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    .analytics-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
        vertical-align: middle;
        color: #1e293b;
    }
    .analytics-table tr:last-child td {
        border-bottom: none;
    }
    .analytics-table tbody tr {
        transition: background-color 0.15s ease;
    }
    .analytics-table tbody tr:hover {
        background-color: #f8fbff;
    }

    /* Customer Avatar Pill */
    .cust-avatar-pill {
        width: 36px;
        height: 36px;
        border-radius: 12px;
        background: linear-gradient(135deg, #00285a, #2563eb);
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 13px;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(0, 40, 90, 0.2);
    }

    /* Risk Badges */
    .risk-badge {
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }
    .risk-badge-success { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
    .risk-badge-warning { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
    .risk-badge-danger {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
        animation: dangerPulse 2s infinite;
    }
    @keyframes dangerPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.02); }
    }

    /* Custom Date Range Modal */
    .date-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(7, 25, 51, 0.65);
        backdrop-filter: blur(6px);
        z-index: 1050;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        animation: fadeInModal 0.2s ease forwards;
    }
    @keyframes fadeInModal {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .date-modal-overlay.active {
        display: flex;
    }
    .date-modal-card {
        background: #ffffff;
        border-radius: 22px;
        padding: 28px;
        width: 100%;
        max-width: 440px;
        box-shadow: 0 25px 60px -15px rgba(0, 40, 90, 0.35);
        border: 1px solid var(--studio-border);
    }
    .date-modal-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .date-modal-title {
        font-size: 17px;
        font-weight: 900;
        color: var(--brand-navy);
        margin: 0;
    }
    .form-label-custom {
        font-size: 12px;
        font-weight: 800;
        color: #475569;
        margin-bottom: 6px;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .form-input-custom {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid var(--studio-border);
        border-radius: 12px;
        font-size: 13.5px;
        font-family: inherit;
        outline: none;
        transition: border-color 0.15s ease;
    }
    .form-input-custom:focus {
        border-color: var(--brand-navy);
        box-shadow: 0 0 0 3px rgba(0, 40, 90, 0.1);
    }

    @media print {
        .dash-hero-banner .hero-right, .btn-hero-action, .select-period-dropdown, .timeframe-card {
            display: none !important;
        }
        body { background: white !important; }
        .sidebar, .navbar { display: none !important; }
    }
</style>

<div class="sales-studio-root">

    {{-- ══════════════════════════════════════════════════════════════════
         1. EXECUTIVE HERO COMMAND BANNER (ANIMATED GRADIENT & TELEMETRY)
         ══════════════════════════════════════════════════════════════════ --}}
    <div class="dash-hero-banner">
        <div class="hero-mesh-glow"></div>
        <div class="hero-content">
            <div class="hero-left">
                <div class="hero-badge-row">
                    <span class="live-pulse-chip">
                        <span class="pulse-dot"></span>
                        <span>RADAR ACTIVE &bull; LIVE</span>
                    </span>
                    <span class="hero-time-chip" id="liveClockTicker">
                        <i class="bi bi-clock-fill"></i> Loading IST clock...
                    </span>
                    <span class="hero-time-chip" style="color: #60a5fa; border-color: rgba(96, 165, 250, 0.3);">
                        <i class="bi bi-calendar2-week-fill"></i> {{ $rangeLabel }}
                    </span>
                </div>
                <h1 class="hero-title">
                    Sales Analytics &amp; <span class="gradient-text">Revenue Intelligence</span>
                </h1>
                <p class="hero-sub">
                    Multi-channel unit economics, doorstep COD realization &amp; returns financial reconciliation.
                </p>
            </div>

            <div class="hero-right">
                {{-- Quick Time Horizon Switcher --}}
                <div class="timeframe-card">
                    <a href="{{ route('admin.sales.analytics', ['range' => 'today']) }}" 
                       class="tf-pill {{ $range === 'today' ? 'active' : '' }}" title="Today's sales">
                        Today
                    </a>
                    <a href="{{ route('admin.sales.analytics', ['range' => 'this_week']) }}" 
                       class="tf-pill {{ $range === 'this_week' ? 'active' : '' }}" title="Current week">
                        Week
                    </a>
                    <a href="{{ route('admin.sales.analytics', ['range' => 'this_month']) }}" 
                       class="tf-pill {{ $range === 'this_month' ? 'active' : '' }}" title="Current month">
                        Month
                    </a>
                    <a href="{{ route('admin.sales.analytics', ['range' => 'this_quarter']) }}" 
                       class="tf-pill {{ $range === 'this_quarter' ? 'active' : '' }}" title="Current quarter">
                        Quarter
                    </a>
                    <a href="{{ route('admin.sales.analytics', ['range' => 'this_year']) }}" 
                       class="tf-pill {{ $range === 'this_year' ? 'active' : '' }}" title="Current year">
                        Year
                    </a>
                </div>

                {{-- Extended Dropdown for Rolling Periods --}}
                <select class="select-period-dropdown" onchange="if(this.value) window.location.href=this.value;">
                    <option value="" disabled selected>More Horizons...</option>
                    <optgroup label="Daily">
                        <option value="{{ route('admin.sales.analytics', ['range' => 'today']) }}" {{ $range === 'today' ? 'selected' : '' }}>Today</option>
                        <option value="{{ route('admin.sales.analytics', ['range' => 'yesterday']) }}" {{ $range === 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                    </optgroup>
                    <optgroup label="Weekly &amp; Rolling">
                        <option value="{{ route('admin.sales.analytics', ['range' => 'this_week']) }}" {{ $range === 'this_week' ? 'selected' : '' }}>This Week</option>
                        <option value="{{ route('admin.sales.analytics', ['range' => 'last_week']) }}" {{ $range === 'last_week' ? 'selected' : '' }}>Last Week</option>
                        <option value="{{ route('admin.sales.analytics', ['range' => '7days']) }}" {{ $range === '7days' ? 'selected' : '' }}>Rolling 7 Days</option>
                        <option value="{{ route('admin.sales.analytics', ['range' => '30days']) }}" {{ $range === '30days' ? 'selected' : '' }}>Rolling 30 Days</option>
                    </optgroup>
                    <optgroup label="Monthly">
                        <option value="{{ route('admin.sales.analytics', ['range' => 'this_month']) }}" {{ $range === 'this_month' ? 'selected' : '' }}>This Month</option>
                        <option value="{{ route('admin.sales.analytics', ['range' => 'last_month']) }}" {{ $range === 'last_month' ? 'selected' : '' }}>Last Month</option>
                    </optgroup>
                    <optgroup label="Quarterly &amp; Lifetime">
                        <option value="{{ route('admin.sales.analytics', ['range' => 'this_quarter']) }}" {{ $range === 'this_quarter' ? 'selected' : '' }}>This Quarter</option>
                        <option value="{{ route('admin.sales.analytics', ['range' => 'last_quarter']) }}" {{ $range === 'last_quarter' ? 'selected' : '' }}>Last Quarter</option>
                        <option value="{{ route('admin.sales.analytics', ['range' => 'this_year']) }}" {{ $range === 'this_year' ? 'selected' : '' }}>This Year</option>
                        <option value="{{ route('admin.sales.analytics', ['range' => 'last_year']) }}" {{ $range === 'last_year' ? 'selected' : '' }}>Last Year</option>
                        <option value="{{ route('admin.sales.analytics', ['range' => 'all_time']) }}" {{ $range === 'all_time' ? 'selected' : '' }}>All-Time (Lifetime)</option>
                    </optgroup>
                </select>

                {{-- Custom Date Modal Trigger --}}
                <button type="button" class="btn-hero-action {{ ($customStart && $customEnd) ? 'active' : '' }}" onclick="openDateModal()" title="Pick Custom Date Interval">
                    <i class="bi bi-calendar-range"></i> Custom Range
                </button>

                {{-- Print / Save Report --}}
                <button type="button" class="btn-hero-action" onclick="window.print()" title="Print / Export Report">
                    <i class="bi bi-printer"></i>
                </button>

                {{-- Sync Radar --}}
                <button type="button" class="btn-hero-action" onclick="window.location.reload();" title="Sync Radar">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════
         2. INVENTORY RESTOCK ADVISORY (IF LOW STOCK EXISTS)
         ══════════════════════════════════════════════════════════════════ --}}
    @if ($lowStockRisks->count() > 0)
        <div class="urgent-alert-card">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-4"></i>
                <div>
                    <div class="alert-headline">
                        LOW INVENTORY RESTOCK RADAR: {{ $lowStockRisks->count() }} Fast-Moving SKU(s) Critical!
                    </div>
                    <span class="text-muted font-xs">
                        High velocity products are below 10 units threshold. Restock now to prevent checkout stockouts.
                    </span>
                </div>
            </div>
            <a href="{{ route('admin.inventory.index', ['status' => 'low_stock']) }}" class="btn btn-sm btn-outline-danger font-xs fw-bold px-3 py-1 rounded-pill">
                Inspect SKUs &rarr;
            </a>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════════
         3. TOP 8 ANIMATED MASTER BENTO KPI CARDS (WITH COUNT-UP & HOVER GLOW)
         ══════════════════════════════════════════════════════════════════ --}}
    <div class="dash-section-head">
        <div class="section-title-wrap">
            <span class="section-indicator"></span>
            <h2>Financial &amp; Volume Master KPIs</h2>
        </div>
        <span class="section-meta-tag"><i class="bi bi-bar-chart-line-fill me-1"></i> Comparing active cycle vs previous period</span>
    </div>

    <div class="kpi-cards-grid">
        {{-- Card 1: Gross Booked Sales --}}
        <div class="kpi-card-box theme-navy">
            <div class="kpi-glass">
                <div class="kpi-header">
                    <span class="kpi-tag">Gross Booked Sales</span>
                    <div class="kpi-icon-bubble">
                        <i class="bi bi-currency-rupee"></i>
                    </div>
                </div>
                <div class="kpi-number-wrap">
                    <span class="kpi-currency">₹</span>
                    <span class="kpi-val count-up" data-val="{{ (int)$grossSales }}">{{ number_format($grossSales) }}</span>
                </div>
                <p class="kpi-sub">{{ number_format($totalOrdersCount) }} orders booked in cycle</p>
                <div class="kpi-footer">
                    <span class="kpi-badge {{ $salesGrowth >= 0 ? 'badge-success' : 'badge-danger' }}">
                        <i class="bi {{ $salesGrowth >= 0 ? 'bi-arrow-up-short' : 'bi-arrow-down-short' }}"></i>
                        {{ abs($salesGrowth) }}% vs prev cycle
                    </span>
                    <span class="font-xs text-muted">₹{{ number_format($totalDiscounts) }} disc.</span>
                </div>
            </div>
            <div class="kpi-bottom-bar bar-navy"></div>
        </div>

        {{-- Card 2: Realized Doorstep Cash --}}
        <div class="kpi-card-box theme-emerald">
            <div class="kpi-glass">
                <div class="kpi-header">
                    <span class="kpi-tag">Realized Delivered Cash</span>
                    <div class="kpi-icon-bubble">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
                <div class="kpi-number-wrap">
                    <span class="kpi-currency">₹</span>
                    <span class="kpi-val count-up text-success" data-val="{{ (int)($deliveredRevenue ?: $netRealizedSales) }}">
                        {{ number_format($deliveredRevenue ?: $netRealizedSales) }}
                    </span>
                </div>
                <p class="kpi-sub">Retained Doorstep &amp; Online Volume</p>
                <div class="kpi-footer">
                    <span class="kpi-badge badge-success">
                        <i class="bi bi-shield-check"></i> Net Realized
                    </span>
                    <span class="font-xs text-muted">Delivered to client</span>
                </div>
            </div>
            <div class="kpi-bottom-bar bar-emerald"></div>
        </div>

        {{-- Card 3: Average Order Value (AOV) --}}
        <div class="kpi-card-box theme-indigo">
            <div class="kpi-glass">
                <div class="kpi-header">
                    <span class="kpi-tag">Avg. Ticket Size (AOV)</span>
                    <div class="kpi-icon-bubble">
                        <i class="bi bi-receipt-cutoff"></i>
                    </div>
                </div>
                <div class="kpi-number-wrap">
                    <span class="kpi-currency">₹</span>
                    <span class="kpi-val count-up" data-val="{{ (int)$aov }}">{{ number_format($aov) }}</span>
                </div>
                <p class="kpi-sub">Basket size per successful checkout</p>
                <div class="kpi-footer">
                    <span class="kpi-badge {{ $aovGrowth >= 0 ? 'badge-success' : 'badge-danger' }}">
                        <i class="bi {{ $aovGrowth >= 0 ? 'bi-arrow-up-short' : 'bi-arrow-down-short' }}"></i>
                        {{ abs($aovGrowth) }}% vs prev
                    </span>
                    <span class="font-xs text-muted">COD: ₹{{ number_format($codAov) }}</span>
                </div>
            </div>
            <div class="kpi-bottom-bar bar-indigo"></div>
        </div>

        {{-- Card 4: Total Orders Booked --}}
        <div class="kpi-card-box theme-purple">
            <div class="kpi-glass">
                <div class="kpi-header">
                    <span class="kpi-tag">Total Orders Placed</span>
                    <div class="kpi-icon-bubble">
                        <i class="bi bi-bag-check-fill"></i>
                    </div>
                </div>
                <div class="kpi-number-wrap">
                    <span class="kpi-val count-up" data-val="{{ $totalOrdersCount }}">{{ number_format($totalOrdersCount) }}</span>
                </div>
                <p class="kpi-sub">Including COD &amp; prepaid transactions</p>
                <div class="kpi-footer">
                    <span class="kpi-badge {{ $ordersGrowth >= 0 ? 'badge-success' : 'badge-danger' }}">
                        <i class="bi {{ $ordersGrowth >= 0 ? 'bi-arrow-up-short' : 'bi-arrow-down-short' }}"></i>
                        {{ abs($ordersGrowth) }}% orders
                    </span>
                    <span class="font-xs text-muted">{{ $statusBreakdown['delivered'] ?? 0 }} delivered</span>
                </div>
            </div>
            <div class="kpi-bottom-bar bar-purple"></div>
        </div>

        {{-- Card 5: Payment Channel Mix --}}
        <div class="kpi-card-box theme-amber">
            <div class="kpi-glass">
                <div class="kpi-header">
                    <span class="kpi-tag">Payment Channel Mix</span>
                    <div class="kpi-icon-bubble">
                        <i class="bi bi-pie-chart-fill"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline justify-content-between">
                    <span class="fw-bold" style="color: #d97706; font-size: 19px;">{{ $codRatio }}% COD</span>
                    <span class="fw-bold" style="color: #2563eb; font-size: 19px;">{{ $prepaidRatio }}% Online</span>
                </div>
                <div class="dual-meter-track">
                    <div class="dual-meter-cod" style="width: {{ $codRatio }}%;"></div>
                    <div class="dual-meter-prepaid" style="width: {{ $prepaidRatio }}%;"></div>
                </div>
                <div class="kpi-footer">
                    <span class="font-xs text-muted">₹{{ number_format($codRevenue) }} COD</span>
                    <span class="font-xs text-muted">₹{{ number_format($prepaidRevenue) }} Online</span>
                </div>
            </div>
            <div class="kpi-bottom-bar bar-amber"></div>
        </div>

        {{-- Card 6: Customer Loyalty & Repeat Ratio --}}
        <div class="kpi-card-box theme-cyan">
            <div class="kpi-glass">
                <div class="kpi-header">
                    <span class="kpi-tag">Repeat Buyer Ratio</span>
                    <div class="kpi-icon-bubble">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
                <div class="kpi-number-wrap">
                    <span class="kpi-val count-up" data-val="{{ (int)$repeatCustomerRatio }}">{{ $repeatCustomerRatio }}</span>
                    <span class="fs-4 fw-bold text-muted">%</span>
                </div>
                <p class="kpi-sub">Customers placing 2+ orders</p>
                <div class="kpi-footer">
                    <span class="kpi-badge badge-info">
                        <i class="bi bi-stars"></i> Retention Rate
                    </span>
                    <span class="font-xs text-muted">High LTV Cohort</span>
                </div>
            </div>
            <div class="kpi-bottom-bar bar-cyan"></div>
        </div>

        {{-- Card 7: 30-Day Sales Run Rate & Forecast --}}
        <div class="kpi-card-box theme-indigo">
            <div class="kpi-glass">
                <div class="kpi-header">
                    <span class="kpi-tag">30-Day Run Rate Forecast</span>
                    <div class="kpi-icon-bubble">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                </div>
                <div class="kpi-number-wrap">
                    <span class="kpi-currency">₹</span>
                    <span class="kpi-val count-up" data-val="{{ (int)$forecast30Days }}">{{ number_format($forecast30Days) }}</span>
                </div>
                <p class="kpi-sub">Daily run rate: ₹{{ number_format($dailyRunRate) }}/day</p>
                <div class="kpi-footer">
                    <span class="kpi-badge badge-info">
                        <i class="bi bi-bullseye"></i> {{ $targetPacingPercent }}% Benchmark
                    </span>
                    <span class="font-xs text-muted">Target: ₹5L</span>
                </div>
            </div>
            <div class="kpi-bottom-bar bar-indigo"></div>
        </div>

        {{-- Card 8: Net Realized Profit in Pocket --}}
        <div class="kpi-card-box theme-emerald" style="background: #fafffd;">
            <div class="kpi-glass">
                <div class="kpi-header">
                    <span class="kpi-tag" style="color: #059669;">Net Operating Profit</span>
                    <div class="kpi-icon-bubble">
                        <i class="bi bi-piggy-bank-fill"></i>
                    </div>
                </div>
                <div class="kpi-number-wrap">
                    <span class="kpi-currency">₹</span>
                    <span class="kpi-val count-up text-success" data-val="{{ (int)$netRealizedProfit }}">
                        {{ number_format($netRealizedProfit) }}
                    </span>
                </div>
                <p class="kpi-sub">Net after 40% COGS &amp; RTO Logistics losses</p>
                <div class="kpi-footer">
                    <span class="kpi-badge badge-success">
                        <i class="bi bi-check-circle-fill"></i> {{ $netProfitMargin }}% Operating Margin
                    </span>
                    <span class="font-xs text-danger">-₹{{ number_format($totalReturnFinancialLoss) }} RTO loss</span>
                </div>
            </div>
            <div class="kpi-bottom-bar bar-emerald"></div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════
         4. MASTER DUAL-AXIS REVENUE & ORDER VELOCITY TIMELINE
         ══════════════════════════════════════════════════════════════════ --}}
    <div class="studio-panel-card">
        <div class="studio-panel-head">
            <div>
                <h3 class="studio-panel-title">
                    <i class="bi bi-activity text-primary"></i> Revenue &amp; Order Velocity Trajectory
                </h3>
                <span class="text-muted font-xs">
                    Continuous sales run rate across <strong>{{ $rangeLabel }}</strong> &bull; Interactive dual-axis synchronization.
                </span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary-subtle text-primary border px-3 py-1 font-xs font-bold rounded-pill">
                    <i class="bi bi-fire me-1"></i> Peak Shopping Window: {{ $peakHourLabel }}
                </span>
            </div>
        </div>
        <div style="height: 310px; position: relative;">
            <canvas id="salesVelocityChart"></canvas>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════
         5. VISUAL INTELLIGENCE ROW (PAYMENT DONUT + PROFIT WATERFALL)
         ══════════════════════════════════════════════════════════════════ --}}
    <div class="two-col-grid">
        {{-- Graph 1: Payment Channel Breakdown Donut --}}
        <div class="studio-panel-card">
            <div class="studio-panel-head">
                <div>
                    <h3 class="studio-panel-title">
                        <i class="bi bi-pie-chart-fill text-warning"></i> Payment Channel Realization Split
                    </h3>
                    <span class="text-muted font-xs">Gross share of Cash on Delivery vs Online Prepaid</span>
                </div>
                <span class="badge bg-light text-dark border px-2 py-1 font-xs font-bold">Volume Breakdown</span>
            </div>

            <div style="height: 230px; position: relative;" class="d-flex align-items-center justify-content-center">
                <canvas id="paymentDonutChart"></canvas>
            </div>

            <div class="row g-2 mt-3 pt-3 border-top text-center font-xs">
                <div class="col-6">
                    <span class="text-muted d-block"><i class="bi bi-circle-fill text-warning me-1"></i> Cash on Delivery:</span>
                    <strong style="color:var(--brand-navy); font-size:15px;">₹{{ number_format($codRevenue) }} ({{ $codRatio }}%)</strong>
                    <div class="text-muted font-xs">{{ number_format($codTotalCount) }} total orders</div>
                </div>
                <div class="col-6">
                    <span class="text-muted d-block"><i class="bi bi-circle-fill text-primary me-1"></i> Online Prepaid:</span>
                    <strong style="color:var(--brand-navy); font-size:15px;">₹{{ number_format($prepaidRevenue) }} ({{ $prepaidRatio }}%)</strong>
                    <div class="text-muted font-xs">{{ number_format($prepaidTotalCount) }} total orders</div>
                </div>
            </div>
        </div>

        {{-- Graph 2: Financial Realization & Loss Waterfall --}}
        <div class="studio-panel-card">
            <div class="studio-panel-head">
                <div>
                    <h3 class="studio-panel-title">
                        <i class="bi bi-bar-chart-steps text-success"></i> Financial Realization &amp; Loss Waterfall
                    </h3>
                    <span class="text-muted font-xs">Delivered Revenue vs COGS vs RTO Shipping Losses vs Net Profit</span>
                </div>
                <span class="badge bg-success-subtle text-success border px-2 py-1 font-xs font-bold">P&amp;L Intelligence</span>
            </div>

            <div style="height: 230px; position: relative;">
                <canvas id="profitWaterfallChart"></canvas>
            </div>

            <div class="d-flex justify-content-between align-items-center pt-3 border-top font-xs mt-3">
                <span class="text-muted">Net Retained Operating Profit:</span>
                <strong class="text-success" style="font-size: 16px;">
                    ₹{{ number_format($netRealizedProfit) }} ({{ $netProfitMargin }}% margin)
                </strong>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════
         6. COD VS PREPAID DEEP-DIVE CHANNEL HUBS
         ══════════════════════════════════════════════════════════════════ --}}
    <div class="two-col-grid">
        {{-- COD Performance Hub --}}
        <div class="studio-panel-card">
            <div class="studio-panel-head">
                <div>
                    <h3 class="studio-panel-title">
                        <i class="bi bi-cash-stack text-warning"></i> Cash on Delivery (COD) Channel Radar
                    </h3>
                    <span class="text-muted font-xs">Doorstep cash conversion, fulfillment velocity, and RTO risk</span>
                </div>
                <span class="badge bg-warning-subtle text-warning border px-3 py-1 font-xs font-bold rounded-pill">
                    {{ $codRatio }}% Share
                </span>
            </div>

            <div class="metric-tiles-row">
                <div class="metric-tile metric-tile-amber">
                    <span class="tile-label"><i class="bi bi-receipt me-1"></i> Booked COD</span>
                    <div class="tile-num">₹{{ number_format($codRevenue) }}</div>
                    <span class="tile-sub text-muted">{{ number_format($codTotalCount) }} orders</span>
                </div>
                <div class="metric-tile metric-tile-green">
                    <span class="tile-label"><i class="bi bi-check2-circle me-1"></i> Cash Delivered</span>
                    <div class="tile-num" style="color:#059669;">{{ number_format($codDelivered) }}</div>
                    <span class="tile-sub text-success">{{ $codRealizationRate }}% Realized</span>
                </div>
                <div class="metric-tile metric-tile-rose">
                    <span class="tile-label"><i class="bi bi-x-octagon me-1"></i> Returned / RTO</span>
                    <div class="tile-num" style="color:#dc2626;">{{ number_format($codReturnCount) }}</div>
                    <span class="tile-sub text-danger">{{ $codReturnRate }}% RTO Rate</span>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3 font-xs">
                <div>
                    <span class="text-muted d-block">Average COD Ticket Size (AOV):</span>
                    <strong style="color:var(--brand-navy); font-size: 15px;">₹{{ number_format($codAov) }}</strong>
                </div>
                <div class="text-end">
                    <span class="text-muted d-block">Doorstep Cash Realization:</span>
                    <strong class="{{ $codRealizationRate >= 70 ? 'text-success' : 'text-danger' }}" style="font-size: 15px;">
                        {{ $codRealizationRate }}%
                    </strong>
                </div>
            </div>
        </div>

        {{-- Online Prepaid Hub --}}
        <div class="studio-panel-card">
            <div class="studio-panel-head">
                <div>
                    <h3 class="studio-panel-title">
                        <i class="bi bi-credit-card-2-front-fill text-primary"></i> Online Prepaid Channel Radar
                    </h3>
                    <span class="text-muted font-xs">UPI, Credit/Debit Cards, Net Banking &amp; Wallet transactions</span>
                </div>
                <span class="badge bg-primary-subtle text-primary border px-3 py-1 font-xs font-bold rounded-pill">
                    {{ $prepaidRatio }}% Share
                </span>
            </div>

            <div class="metric-tiles-row">
                <div class="metric-tile metric-tile-blue">
                    <span class="tile-label"><i class="bi bi-receipt me-1"></i> Booked Prepaid</span>
                    <div class="tile-num">₹{{ number_format($prepaidRevenue) }}</div>
                    <span class="tile-sub text-muted">{{ number_format($prepaidTotalCount) }} orders</span>
                </div>
                <div class="metric-tile metric-tile-green">
                    <span class="tile-label"><i class="bi bi-check2-circle me-1"></i> Successful</span>
                    <div class="tile-num" style="color:#059669;">{{ number_format($prepaidDelivered) }}</div>
                    <span class="tile-sub text-success">{{ $prepaidRealizationRate }}% Delivered</span>
                </div>
                <div class="metric-tile metric-tile-rose">
                    <span class="tile-label"><i class="bi bi-arrow-return-left me-1"></i> Returns</span>
                    <div class="tile-num" style="color:#dc2626;">{{ number_format($prepaidReturnCount) }}</div>
                    <span class="tile-sub text-danger">{{ $prepaidReturnRate }}% Returns</span>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3 font-xs">
                <div>
                    <span class="text-muted d-block">Average Prepaid Ticket Size (AOV):</span>
                    <strong style="color:var(--brand-navy); font-size: 15px;">₹{{ number_format($prepaidAov) }}</strong>
                </div>
                <div class="text-end">
                    <span class="text-muted d-block">Prepaid Delivery Realization:</span>
                    <strong class="text-success" style="font-size: 15px;">{{ $prepaidRealizationRate }}%</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════
         7. RETURNS & RTO FINANCIAL LOSS VS REALIZED PROFIT RECONCILIATION
         ══════════════════════════════════════════════════════════════════ --}}
    <div class="studio-panel-card" style="border-left: 6px solid #dc2626;">
        <div class="studio-panel-head">
            <div>
                <h3 class="studio-panel-title">
                    <i class="bi bi-arrow-left-right text-danger"></i> Returns &amp; RTO Financial Loss vs Realized Profit Reconciliation
                </h3>
                <span class="text-muted font-xs">
                    Comprehensive accounting of forward &amp; reverse courier logistics, packaging loss, and net retained margin
                </span>
            </div>
            <span class="badge bg-danger-subtle text-danger border px-3 py-1 font-xs font-bold rounded-pill">
                {{ $overallReturnRate }}% Store Return Rate
            </span>
        </div>

        <div class="row g-3">
            {{-- Column 1: Out of Pocket Losses --}}
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 border h-100">
                    <h4 style="font-size: 13.5px; font-weight: 800; color: #dc2626; margin-bottom: 14px;">
                        <i class="bi bi-exclamation-octagon-fill me-1"></i> Cost of Returns &amp; Out-of-Pocket Courier Losses:
                    </h4>

                    <div class="d-flex justify-content-between py-2 border-bottom font-xs">
                        <span class="text-muted"><i class="bi bi-box-seam me-1"></i> Returned / RTO Orders:</span>
                        <strong style="color:var(--brand-navy);">{{ number_format($returnCount) }} parcels</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom font-xs">
                        <span class="text-muted"><i class="bi bi-truck me-1"></i> Forward + Reverse Freight Courier Loss (₹120/parcel):</span>
                        <strong class="text-danger">-₹{{ number_format($returnLogisticsLoss) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom font-xs">
                        <span class="text-muted"><i class="bi bi-archive me-1"></i> Packaging &amp; Restocking Cost (₹30/parcel):</span>
                        <strong class="text-danger">-₹{{ number_format($returnPackagingLoss) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom font-xs">
                        <span class="text-muted"><i class="bi bi-tag me-1"></i> Gross Returned Merchandise Value:</span>
                        <strong class="text-muted">₹{{ number_format($returnedMerchandiseValue) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between pt-2 font-xs">
                        <strong class="text-danger">Total Out-of-Pocket Loss on Returns:</strong>
                        <strong class="text-danger" style="font-size: 16px;">-₹{{ number_format($totalReturnFinancialLoss) }}</strong>
                    </div>
                </div>
            </div>

            {{-- Column 2: Net Operating Profit --}}
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 border h-100" style="background:#f0fdf4 !important; border-color:#bbf7d0 !important;">
                    <h4 style="font-size: 13.5px; font-weight: 800; color: #059669; margin-bottom: 14px;">
                        <i class="bi bi-wallet-fill me-1"></i> Net Operating Profitability Reconciliation:
                    </h4>

                    <div class="d-flex justify-content-between py-2 border-bottom font-xs">
                        <span class="text-muted"><i class="bi bi-cash me-1"></i> Gross Delivered Cash:</span>
                        <strong style="color:var(--brand-navy);">₹{{ number_format($deliveredRevenue ?: $grossSales) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom font-xs">
                        <span class="text-muted"><i class="bi bi-boxes me-1"></i> Estimated Product COGS (40%):</span>
                        <strong class="text-muted">-₹{{ number_format($estimatedCOGS) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom font-xs">
                        <span class="text-muted"><i class="bi bi-calculator me-1"></i> Gross Product Margin:</span>
                        <strong style="color:var(--brand-navy);">₹{{ number_format($grossProductMargin) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom font-xs">
                        <span class="text-muted"><i class="bi bi-dash-circle me-1"></i> Deduct RTO Logistics Losses:</span>
                        <strong class="text-danger">-₹{{ number_format($totalReturnFinancialLoss) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between pt-2 font-xs">
                        <strong class="text-success">Net Realized Profit in Pocket:</strong>
                        <strong class="text-success" style="font-size: 17px;">
                            ₹{{ number_format($netRealizedProfit) }} ({{ $netProfitMargin }}%)
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════
         8. CUSTOMER COD PROFILING & HIGH-RISK RADAR TABLE
         ══════════════════════════════════════════════════════════════════ --}}
    <div class="studio-panel-card">
        <div class="studio-panel-head">
            <div>
                <h3 class="studio-panel-title">
                    <i class="bi bi-people-fill text-primary"></i> High-Frequency COD Customers &amp; RTO Risk Profiling
                </h3>
                <span class="text-muted font-xs">
                    Customer order velocity, delivered/returned history, average spend (AOV), and algorithmic return risk
                </span>
            </div>
            <span class="badge bg-light text-dark border font-xs fw-bold px-2 py-1 rounded-pill">Top COD Volume</span>
        </div>

        <div class="analytics-table-wrap">
            <table class="analytics-table">
                <thead>
                    <tr>
                        <th>CUSTOMER &amp; CONTACT</th>
                        <th>LOCATION</th>
                        <th>TOTAL COD ORDERS</th>
                        <th>DELIVERED / RETURNED</th>
                        <th>AVG TICKET (AOV)</th>
                        <th>TOTAL COD SPEND</th>
                        <th>RTO RISK LEVEL</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($codCustomers as $c)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="cust-avatar-pill">{{ strtoupper(substr($c->shipping_name ?: 'C', 0, 2)) }}</div>
                                    <div>
                                        <strong style="color:var(--brand-navy); font-weight:700;" class="d-block">{{ $c->shipping_name ?: 'Customer' }}</strong>
                                        <span class="text-muted font-xs"><i class="bi bi-telephone me-1"></i>{{ $c->shipping_phone ?: '—' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border font-xs">
                                    <i class="bi bi-geo-alt-fill text-primary me-1"></i>{{ $c->shipping_city ?: '—' }}, {{ $c->shipping_state ?: 'India' }}
                                </span>
                            </td>
                            <td>
                                <strong style="color:var(--brand-navy);">{{ $c->total_cod_orders }} orders</strong>
                            </td>
                            <td>
                                <span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i>{{ $c->delivered_count }}</span> &bull; 
                                <span class="{{ $c->returned_count > 0 ? 'text-danger fw-bold' : 'text-muted' }}">
                                    <i class="bi bi-x-circle-fill me-1"></i>{{ $c->returned_count }} RTO
                                </span>
                            </td>
                            <td>
                                <strong style="color:var(--brand-navy);">₹{{ number_format($c->avg_cod_order_value) }}</strong>
                            </td>
                            <td>
                                <strong class="text-success" style="font-size: 14px;">₹{{ number_format($c->total_cod_spend) }}</strong>
                            </td>
                            <td>
                                <span class="risk-badge risk-badge-{{ $c->risk_color }}">
                                    <i class="bi bi-shield-exclamation"></i> {{ $c->risk_level }} ({{ $c->return_rate }}%)
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted font-xs">No COD customer records found for this period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════
         9. GEOGRAPHICAL DEMAND MATRIX & TOP BESTSELLING ARTICLES
         ══════════════════════════════════════════════════════════════════ --}}
    <div class="two-col-grid">
        {{-- State Origins with COD vs Online Breakdown --}}
        <div class="studio-panel-card">
            <div class="studio-panel-head">
                <div>
                    <h3 class="studio-panel-title">
                        <i class="bi bi-map-fill text-primary"></i> State Order Origins &amp; Payment Mix
                    </h3>
                    <span class="text-muted font-xs">Top revenue states and their COD vs Online split</span>
                </div>
                <span class="badge bg-light text-dark border px-2 py-1 font-xs font-bold">Territories</span>
            </div>

            <div class="analytics-table-wrap">
                <table class="analytics-table">
                    <thead>
                        <tr>
                            <th>STATE</th>
                            <th>ORDERS</th>
                            <th>COD / PREPAID MIX</th>
                            <th>TOTAL REVENUE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topStatesData as $st)
                            @php
                                $codStRatio = $st->orders_count > 0 ? round(($st->cod_count / $st->orders_count) * 100) : 0;
                            @endphp
                            <tr>
                                <td>
                                    <strong style="color:var(--brand-navy);">
                                        <i class="bi bi-pin-map-fill text-danger me-1"></i>{{ $st->shipping_state }}
                                    </strong>
                                </td>
                                <td>{{ number_format($st->orders_count) }} orders</td>
                                <td>
                                    <div style="font-size: 11px;">
                                        <span class="text-warning fw-bold">{{ $codStRatio }}% COD</span> &bull; 
                                        <span class="text-primary fw-bold">{{ 100 - $codStRatio }}% Online</span>
                                    </div>
                                    <div style="height: 5px; background: #e2e8f0; border-radius: 4px; overflow: hidden; width: 110px; margin-top: 4px;">
                                        <div style="height: 100%; width: {{ $codStRatio }}%; background: #f59e0b;"></div>
                                    </div>
                                </td>
                                <td><strong style="color:var(--brand-navy);">₹{{ number_format($st->state_revenue) }}</strong></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-3 text-muted font-xs">No regional state data in period.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Top Selling Products --}}
        <div class="studio-panel-card">
            <div class="studio-panel-head">
                <div>
                    <h3 class="studio-panel-title">
                        <i class="bi bi-trophy-fill text-warning"></i> Top Bestselling Articles
                    </h3>
                    <span class="text-muted font-xs">Highest volume &amp; revenue items in period</span>
                </div>
                <span class="badge bg-light text-dark border px-2 py-1 font-xs font-bold">Catalog Leaders</span>
            </div>

            <div class="analytics-table-wrap">
                <table class="analytics-table">
                    <thead>
                        <tr>
                            <th>ARTICLE</th>
                            <th>UNITS SOLD</th>
                            <th>TOTAL REVENUE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topProducts as $idx => $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge {{ $idx === 0 ? 'bg-warning text-dark' : ($idx === 1 ? 'bg-secondary text-white' : 'bg-light text-dark border') }} rounded-circle p-1" style="width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 800;">
                                            {{ $idx + 1 }}
                                        </span>
                                        <strong style="color:var(--brand-navy);" class="d-block text-truncate" style="max-width: 220px;">
                                            {{ $item->product_name ?: ($item->product->name ?? 'Article') }}
                                        </strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border font-xs fw-bold">{{ number_format($item->total_qty) }} units</span>
                                </td>
                                <td>
                                    <strong class="text-success">₹{{ number_format($item->total_revenue) }}</strong>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center py-3 text-muted font-xs">No product sales in period.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════════════
     CUSTOM DATE RANGE MODAL
     ══════════════════════════════════════════════════════════════════ --}}
<div id="customDateModal" class="date-modal-overlay">
    <div class="date-modal-card">
        <div class="date-modal-head">
            <h4 class="date-modal-title"><i class="bi bi-calendar-range me-2"></i> Custom Date Range</h4>
            <button type="button" class="btn-close" onclick="closeDateModal()"></button>
        </div>
        <form method="GET" action="{{ route('admin.sales.analytics') }}">
            <div class="mb-3">
                <label class="form-label-custom">Start Date</label>
                <input type="date" name="start_date" value="{{ $customStart ?: date('Y-m-01') }}" required class="form-input-custom">
            </div>
            <div class="mb-4">
                <label class="form-label-custom">End Date</label>
                <input type="date" name="end_date" value="{{ $customEnd ?: date('Y-m-d') }}" required class="form-input-custom">
            </div>
            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-sm btn-light border px-3 py-2 rounded-3" onclick="closeDateModal()">Cancel</button>
                <button type="submit" class="btn btn-sm text-white px-4 py-2 rounded-3 fw-bold" style="background:#00285a;">
                    Apply Range
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // ── 1. Date Modal Functions ──
    function openDateModal() {
        document.getElementById('customDateModal').classList.add('active');
    }
    function closeDateModal() {
        document.getElementById('customDateModal').classList.remove('active');
    }
    document.getElementById('customDateModal').addEventListener('click', function(e) {
        if (e.target === this) closeDateModal();
    });

    document.addEventListener('DOMContentLoaded', function () {
        // ── 2. Live IST Clock Ticker ──
        function updateLiveClock() {
            var clockEl = document.getElementById('liveClockTicker');
            if (!clockEl) return;
            var now = new Date();
            var options = { timeZone: 'Asia/Kolkata', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
            var timeStr = now.toLocaleTimeString('en-US', options);
            clockEl.innerHTML = '<i class="bi bi-clock-fill me-1"></i> ' + timeStr + ' IST';
        }
        updateLiveClock();
        setInterval(updateLiveClock, 1000);

        // ── 3. Smooth Number Count-Up Animation for KPI Cards ──
        var countElements = document.querySelectorAll('.count-up');
        countElements.forEach(function(el) {
            var target = parseInt(el.getAttribute('data-val'), 10) || 0;
            if (target <= 0) return;

            var duration = 900;
            var start = 0;
            var startTime = null;

            function step(timestamp) {
                if (!startTime) startTime = timestamp;
                var progress = Math.min((timestamp - startTime) / duration, 1);
                var easeVal = Math.floor((1 - (1 - progress) * (1 - progress)) * target);
                el.textContent = easeVal.toLocaleString('en-IN');
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                } else {
                    el.textContent = target.toLocaleString('en-IN');
                }
            }
            window.requestAnimationFrame(step);
        });

        // ── 4. Payment Donut Chart ──
        const canvasDonut = document.getElementById('paymentDonutChart');
        if (canvasDonut) {
            const ctxDonut = canvasDonut.getContext('2d');
            const codRev = {{ (float) $codRevenue }};
            const prepaidRev = {{ (float) $prepaidRevenue }};

            new Chart(ctxDonut, {
                type: 'doughnut',
                data: {
                    labels: ['Cash on Delivery (COD)', 'Online Prepaid (UPI/Cards)'],
                    datasets: [{
                        data: [codRev || 1, prepaidRev || 0],
                        backgroundColor: ['#f59e0b', '#2563eb'],
                        borderWidth: 3,
                        borderColor: '#ffffff',
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    return ctx.label + ': ₹' + Number(ctx.raw).toLocaleString('en-IN');
                                }
                            }
                        }
                    }
                }
            });
        }

        // ── 5. Profit Waterfall Bar Chart ──
        const canvasWaterfall = document.getElementById('profitWaterfallChart');
        if (canvasWaterfall) {
            const ctxWaterfall = canvasWaterfall.getContext('2d');
            new Chart(ctxWaterfall, {
                type: 'bar',
                data: {
                    labels: ['Gross Delivered', 'Product COGS (40%)', 'RTO Freight Loss', 'Net Profit'],
                    datasets: [{
                        label: 'Financial Breakdown (₹)',
                        data: [
                            {{ (float) ($deliveredRevenue ?: $grossSales) }},
                            {{ (float) $estimatedCOGS }},
                            {{ (float) $totalReturnFinancialLoss }},
                            {{ (float) $netRealizedProfit }}
                        ],
                        backgroundColor: [
                            '#2563eb',
                            '#94a3b8',
                            '#ef4444',
                            '#10b981'
                        ],
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    return ctx.label + ': ₹' + Number(ctx.raw).toLocaleString('en-IN');
                                }
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '700' } } },
                        y: {
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                callback: function(v) { return '₹' + (v >= 1000 ? (v/1000).toFixed(0) + 'k' : v); },
                                font: { family: 'Plus Jakarta Sans', size: 10 }
                            }
                        }
                    }
                }
            });
        }

        // ── 6. Revenue & Order Velocity Dual-Axis Line Chart ──
        const canvasVelocity = document.getElementById('salesVelocityChart');
        if (canvasVelocity) {
            const ctxVelocity = canvasVelocity.getContext('2d');
            const labels = {!! json_encode($chartLabels) !!};
            const revenueData = {!! json_encode($chartRevenue) !!};
            const ordersData = {!! json_encode($chartOrderCount) !!};

            const revGradient = ctxVelocity.createLinearGradient(0, 0, 0, 280);
            revGradient.addColorStop(0, 'rgba(0, 40, 90, 0.35)');
            revGradient.addColorStop(1, 'rgba(0, 40, 90, 0.00)');

            new Chart(ctxVelocity, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Gross Revenue (₹)',
                            data: revenueData,
                            borderColor: '#00285a',
                            backgroundColor: revGradient,
                            fill: true,
                            tension: 0.36,
                            pointRadius: 3,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#00285a',
                            borderWidth: 2.5,
                            yAxisID: 'y',
                        },
                        {
                            label: 'Orders Count',
                            data: ordersData,
                            borderColor: '#10b981',
                            backgroundColor: 'transparent',
                            borderDash: [5, 4],
                            tension: 0.36,
                            pointRadius: 3,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#10b981',
                            borderWidth: 2,
                            yAxisID: 'y1',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '700' }, boxWidth: 14 }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.92)',
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 12 },
                            padding: 10,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label.includes('Revenue')) {
                                        return label + ': ₹' + Number(context.raw).toLocaleString('en-IN');
                                    }
                                    return label + ': ' + context.raw + ' orders';
                                }
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' }, color: '#64748b' } },
                        y: {
                            type: 'linear', display: true, position: 'left',
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                callback: function(value) { return '₹' + (value >= 1000 ? (value/1000).toFixed(0) + 'k' : value); },
                                font: { family: 'Plus Jakarta Sans', size: 10.5 },
                                color: '#64748b'
                            }
                        },
                        y1: {
                            type: 'linear', display: true, position: 'right',
                            grid: { drawOnChartArea: false },
                            ticks: { precision: 0, font: { family: 'Plus Jakarta Sans', size: 10.5 }, color: '#10b981' }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection
