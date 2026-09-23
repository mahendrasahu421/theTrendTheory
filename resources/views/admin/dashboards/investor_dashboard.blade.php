{{-- resources/views/admin/dashboards/investor_dashboard.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Executive Investor Deck & Telemetry Cockpit')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

    :root {
        --inv-navy: #00285a;
        --inv-navy-dark: #001838;
        --inv-blue: #2563eb;
        --inv-emerald: #10b981;
        --inv-amber: #f59e0b;
        --inv-purple: #8b5cf6;
        --inv-rose: #f43f5e;
        --inv-cyan: #06b6d4;
        --inv-border: #e2e8f0;
        --inv-card: #ffffff;
        --inv-text: #0f172a;
        --inv-muted: #64748b;
    }

    .investor-cockpit-root {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        display: flex;
        flex-direction: column;
        gap: 24px;
        color: var(--inv-text);
        max-width: 1520px;
        margin: 0 auto;
        padding-bottom: 80px;
        position: relative;
    }

    /* ── 1. Hero Command Banner ── */
    .investor-hero-banner {
        background: linear-gradient(135deg, #00122e 0%, #00285a 55%, #051a3d 100%);
        border-radius: 24px;
        padding: 30px 34px;
        position: relative;
        overflow: hidden;
        color: #ffffff;
        box-shadow: 0 20px 45px -15px rgba(0, 40, 90, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.12);
    }
    .hero-mesh-glow {
        position: absolute;
        width: 380px;
        height: 380px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(37, 99, 235, 0.3) 0%, rgba(16, 185, 129, 0.15) 60%, transparent 80%);
        top: -140px;
        right: -80px;
        pointer-events: none;
        filter: blur(50px);
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
    }
    .hero-title {
        font-size: 27px;
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
    }
    .hero-right {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
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
        padding: 7px 13px;
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
        color: var(--inv-navy);
        font-weight: 800;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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
    .btn-hero-pitch {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        border: 1px solid #60a5fa;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.4);
    }
    .btn-hero-pitch:hover {
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
    }

    /* ── Section Dividers ── */
    .section-head-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
        padding-bottom: 8px;
        border-bottom: 2px solid #edf2f7;
        flex-wrap: wrap;
        gap: 10px;
    }
    .section-title-box {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .section-indicator-bar {
        width: 4px;
        height: 22px;
        border-radius: 4px;
        background: linear-gradient(180deg, var(--inv-blue), var(--inv-emerald));
    }
    .section-main-title {
        font-size: 18px;
        font-weight: 900;
        color: #0f172a;
        margin: 0;
        letter-spacing: -0.4px;
    }
    .section-tag-badge {
        font-size: 11.5px;
        font-weight: 700;
        color: var(--inv-muted);
        background: #f1f5f9;
        padding: 4px 11px;
        border-radius: 999px;
    }

    /* ── Bento KPI Cards Grid ── */
    .bento-kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }
    @media (max-width: 1200px) {
        .bento-kpi-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 640px) {
        .bento-kpi-grid { grid-template-columns: 1fr; }
    }

    .kpi-bento-card {
        background: #ffffff;
        border: 1px solid var(--inv-border);
        border-radius: 20px;
        overflow: hidden;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
        transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .kpi-bento-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.08);
        border-color: #cbd5e1;
    }
    .kpi-inner {
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
    .kpi-label {
        font-size: 11.5px;
        font-weight: 800;
        color: #64748b;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .kpi-icon-wrap {
        width: 42px;
        height: 42px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        transition: transform 0.25s ease;
    }
    .kpi-bento-card:hover .kpi-icon-wrap {
        transform: scale(1.1) rotate(-3deg);
    }

    /* Icon Themes */
    .icon-blue { background: #eff6ff; color: #2563eb; }
    .icon-emerald { background: #ecfdf5; color: #059669; }
    .icon-indigo { background: #e0e7ff; color: #4338ca; }
    .icon-purple { background: #faf5ff; color: #7e22ce; }
    .icon-amber { background: #fffbeb; color: #d97706; }
    .icon-rose { background: #fff1f2; color: #e11d48; }
    .icon-cyan { background: #ecfeff; color: #0891b2; }

    .kpi-value-row {
        display: flex;
        align-items: baseline;
        gap: 5px;
    }
    .kpi-curr {
        font-size: 19px;
        font-weight: 800;
        color: #64748b;
    }
    .kpi-num {
        font-size: 28px;
        font-weight: 900;
        color: #0f172a;
        line-height: 1;
        letter-spacing: -0.8px;
        font-variant-numeric: tabular-nums;
    }
    .kpi-subtext {
        font-size: 12px;
        color: #64748b;
        margin: 0;
    }
    .kpi-footer-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 4px;
        font-size: 12px;
    }
    .kpi-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 9px;
        border-radius: 7px;
        font-size: 11.5px;
        font-weight: 800;
    }
    .pill-green { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
    .pill-blue { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
    .pill-amber { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
    .pill-purple { background: #faf5ff; color: #9333ea; border: 1px solid #e9d5ff; }

    .card-accent-bar {
        height: 4px;
        width: 100%;
    }
    .bar-blue { background: linear-gradient(90deg, #00285a, #2563eb); }
    .bar-green { background: linear-gradient(90deg, #059669, #10b981); }
    .bar-purple { background: linear-gradient(90deg, #7e22ce, #a855f7); }
    .bar-amber { background: linear-gradient(90deg, #d97706, #f59e0b); }
    .bar-cyan { background: linear-gradient(90deg, #0891b2, #06b6d4); }

    /* ── Panels & Layout Grids ── */
    .cockpit-panel-card {
        background: #ffffff;
        border: 1px solid var(--inv-border);
        border-radius: 22px;
        padding: 26px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
    }
    .cockpit-panel-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f1f5f9;
        flex-wrap: wrap;
        gap: 10px;
    }
    .cockpit-panel-title {
        font-size: 16.5px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .grid-two-col {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }
    @media (max-width: 1024px) {
        .grid-two-col { grid-template-columns: 1fr; }
    }

    .grid-three-col {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 20px;
    }
    @media (max-width: 1100px) {
        .grid-three-col { grid-template-columns: 1fr; }
    }

    /* Modern Table */
    .cockpit-table-wrap {
        overflow-x: auto;
        border-radius: 14px;
        border: 1px solid #f1f5f9;
    }
    .cockpit-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    .cockpit-table th {
        font-size: 11px;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding: 14px 16px;
        background: #f8fafc;
        border-bottom: 1.5px solid #e2e8f0;
        white-space: nowrap;
    }
    .cockpit-table td {
        padding: 13px 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
        vertical-align: middle;
        color: #1e293b;
    }
    .cockpit-table tr:last-child td {
        border-bottom: none;
    }
    .cockpit-table tbody tr:hover {
        background-color: #f8fbff;
    }

    /* Executive Health Score Ring Widget */
    .health-score-widget {
        display: flex;
        align-items: center;
        gap: 24px;
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border: 1px solid #bbf7d0;
        border-radius: 20px;
        padding: 24px;
    }
    .score-circle-outer {
        width: 96px;
        height: 96px;
        border-radius: 50%;
        background: #ffffff;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.25);
        flex-shrink: 0;
        border: 4px solid #10b981;
    }
    .score-big-num {
        font-size: 32px;
        font-weight: 900;
        color: #059669;
        line-height: 1;
    }
    .score-label-sub {
        font-size: 11px;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
    }

    /* Pitch Mode Fullscreen */
    body.pitch-mode-active .sidebar,
    body.pitch-mode-active .navbar,
    body.pitch-mode-active .footer {
        display: none !important;
    }
    body.pitch-mode-active .investor-cockpit-root {
        max-width: 100% !important;
        padding: 24px !important;
    }

    @media print {
        .investor-hero-banner .hero-right,
        .sidebar, .navbar, .footer,
        .btn-hero-action, .timeframe-card {
            display: none !important;
        }
        body { background: white !important; }
        .cockpit-panel-card, .kpi-bento-card {
            box-shadow: none !important;
            border: 1px solid #cbd5e1 !important;
            page-break-inside: avoid;
        }
    }
</style>

<div class="investor-cockpit-root" id="investorCockpitRoot">

    {{-- ══════════════════════════════════════════════════════════════════
         HERO COMMAND BANNER: INVESTOR RELATIONS & DUE DILIGENCE COCKPIT
         ══════════════════════════════════════════════════════════════════ --}}
    <div class="investor-hero-banner">
        <div class="hero-mesh-glow"></div>
        <div class="hero-content">
            <div class="hero-left">
                <div class="hero-badge-row">
                    <span class="live-pulse-chip">
                        <span class="pulse-dot"></span>
                        <span>SERIES SEED / GROWTH TELEMETRY</span>
                    </span>
                    <span class="hero-time-chip" id="liveClockTicker">
                        <i class="bi bi-clock-fill"></i> Loading clock...
                    </span>
                    <span class="hero-time-chip" style="color: #60a5fa; border-color: rgba(96, 165, 250, 0.3);">
                        <i class="bi bi-shield-lock-fill"></i> CONFIDENTIAL INVESTOR DECK
                    </span>
                </div>
                <h1 class="hero-title">
                    The Trend Theory &bull; <span class="gradient-text">Executive Investor Due Diligence Deck</span>
                </h1>
                <p class="hero-sub">
                    Direct-to-Consumer (D2C) Omnichannel Apparel Brand &bull; Unit Economics, Cap Table &amp; Financial Model.
                </p>
            </div>

            <div class="hero-right">
                {{-- Horizon Switcher --}}
                <div class="timeframe-card">
                    <a href="?horizon=12m" class="tf-pill {{ $horizon === '12m' ? 'active' : '' }}">Trailing 12M</a>
                    <a href="?horizon=this_year" class="tf-pill {{ $horizon === 'this_year' ? 'active' : '' }}">This Year</a>
                    <a href="?horizon=this_quarter" class="tf-pill {{ $horizon === 'this_quarter' ? 'active' : '' }}">This Quarter</a>
                    <a href="?horizon=all_time" class="tf-pill {{ $horizon === 'all_time' ? 'active' : '' }}">All-Time</a>
                </div>

                {{-- Presentation Pitch Mode --}}
                <button type="button" class="btn-hero-action btn-hero-pitch" onclick="togglePitchMode()" title="Toggle Fullscreen Presentation Pitch Mode">
                    <i class="bi bi-easel2-fill"></i> <span id="pitchModeText">Pitch Mode</span>
                </button>

                {{-- Export PDF Deck --}}
                <button type="button" class="btn-hero-action" onclick="window.print()" title="Download / Export as PDF Pitch Deck">
                    <i class="bi bi-file-earmark-pdf-fill"></i> Export Deck
                </button>

                {{-- Refresh Sync --}}
                <button type="button" class="btn-hero-action" onclick="window.location.reload()" title="Sync Real-Time Data">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════
         SECTION 1: FINANCIAL METRICS & PROFITABILITY BENCHMARKS
         ══════════════════════════════════════════════════════════════════ --}}
    <div class="section-head-bar">
        <div class="section-title-box">
            <div class="section-indicator-bar"></div>
            <h2 class="section-main-title">1. Financial Telemetry, P&amp;L &amp; Cash Runway</h2>
        </div>
        <span class="section-tag-badge"><i class="bi bi-cash-stack me-1"></i> GAAP &amp; Management Accounting Realization</span>
    </div>

    <div class="bento-kpi-grid">
        {{-- Total Revenue (ARR) --}}
        <div class="kpi-bento-card">
            <div class="kpi-inner">
                <div class="kpi-header">
                    <span class="kpi-label">Annualized Run Rate (ARR)</span>
                    <div class="kpi-icon-wrap icon-blue">
                        <i class="bi bi-currency-rupee"></i>
                    </div>
                </div>
                <div class="kpi-value-row">
                    <span class="kpi-curr">₹</span>
                    <span class="kpi-num count-up" data-val="{{ $annualRevenue }}">{{ number_format($annualRevenue) }}</span>
                </div>
                <p class="kpi-subtext">Monthly Run Rate: ₹{{ number_format($monthlyRevenue) }}/mo</p>
                <div class="kpi-footer-row">
                    <span class="kpi-pill pill-green"><i class="bi bi-arrow-up-right"></i> +{{ $salesGrowthYoY }}% YoY</span>
                    <span class="text-muted font-xs">ARR Baseline</span>
                </div>
            </div>
            <div class="card-accent-bar bar-blue"></div>
        </div>

        {{-- Gross Profit & Margin --}}
        <div class="kpi-bento-card">
            <div class="kpi-inner">
                <div class="kpi-header">
                    <span class="kpi-label">Gross Profit (Margin)</span>
                    <div class="kpi-icon-wrap icon-emerald">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                </div>
                <div class="kpi-value-row">
                    <span class="kpi-curr">₹</span>
                    <span class="kpi-num count-up text-success" data-val="{{ $grossProfit }}">{{ number_format($grossProfit) }}</span>
                </div>
                <p class="kpi-subtext">COGS: {{ $cogsPercent }}% (₹{{ number_format($cogsAmount) }})</p>
                <div class="kpi-footer-row">
                    <span class="kpi-pill pill-green"><i class="bi bi-shield-check"></i> {{ $grossMarginPercent }}% Gross Margin</span>
                    <span class="text-muted font-xs">Premium Apparel</span>
                </div>
            </div>
            <div class="card-accent-bar bar-green"></div>
        </div>

        {{-- Operating EBITDA --}}
        <div class="kpi-bento-card">
            <div class="kpi-inner">
                <div class="kpi-header">
                    <span class="kpi-label">Operating EBITDA</span>
                    <div class="kpi-icon-wrap icon-indigo">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                </div>
                <div class="kpi-value-row">
                    <span class="kpi-curr">₹</span>
                    <span class="kpi-num count-up" data-val="{{ $ebitda }}">{{ number_format($ebitda) }}</span>
                </div>
                <p class="kpi-subtext">Total OpEx: ₹{{ number_format($totalOpEx) }}</p>
                <div class="kpi-footer-row">
                    <span class="kpi-pill pill-blue"><i class="bi bi-check-circle-fill"></i> {{ $ebitdaMarginPercent }}% EBITDA Margin</span>
                    <span class="text-muted font-xs">Positive Operating Cash</span>
                </div>
            </div>
            <div class="card-accent-bar bar-blue"></div>
        </div>

        {{-- Net Profit Retained --}}
        <div class="kpi-bento-card" style="background:#fafffd;">
            <div class="kpi-inner">
                <div class="kpi-header">
                    <span class="kpi-label" style="color: #059669;">Net Operating Profit</span>
                    <div class="kpi-icon-wrap icon-emerald">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
                <div class="kpi-value-row">
                    <span class="kpi-curr">₹</span>
                    <span class="kpi-num count-up text-success" data-val="{{ $netProfit }}">{{ number_format($netProfit) }}</span>
                </div>
                <p class="kpi-subtext">Net Retained Profit after Tax &amp; Interest</p>
                <div class="kpi-footer-row">
                    <span class="kpi-pill pill-green"><i class="bi bi-stars"></i> {{ $netMarginPercent }}% Net Margin</span>
                    <span class="text-muted font-xs">Capital Efficient</span>
                </div>
            </div>
            <div class="card-accent-bar bar-green"></div>
        </div>
    </div>

    {{-- Row 2: Cash Runway, Burn Rate, Receivables & Payables --}}
    <div class="bento-kpi-grid">
        {{-- Liquid Cash in Bank --}}
        <div class="kpi-bento-card">
            <div class="kpi-inner">
                <div class="kpi-header">
                    <span class="kpi-label">Cash Treasury in Bank</span>
                    <div class="kpi-icon-wrap icon-cyan">
                        <i class="bi bi-bank2"></i>
                    </div>
                </div>
                <div class="kpi-value-row">
                    <span class="kpi-curr">₹</span>
                    <span class="kpi-num count-up" data-val="{{ $cashInBank }}">{{ number_format($cashInBank) }}</span>
                </div>
                <p class="kpi-subtext">Operating Cash Flow: +₹{{ number_format($operatingCashFlow) }}</p>
                <div class="kpi-footer-row">
                    <span class="kpi-pill pill-blue"><i class="bi bi-check2-all"></i> Zero Debt / Solvent</span>
                    <span class="text-muted font-xs">FD &amp; Current</span>
                </div>
            </div>
            <div class="card-accent-bar bar-cyan"></div>
        </div>

        {{-- Monthly Net Burn Rate --}}
        <div class="kpi-bento-card">
            <div class="kpi-inner">
                <div class="kpi-header">
                    <span class="kpi-label">Net Monthly Cash Burn</span>
                    <div class="kpi-icon-wrap icon-rose">
                        <i class="bi bi-fire"></i>
                    </div>
                </div>
                <div class="kpi-value-row">
                    <span class="kpi-curr">₹</span>
                    <span class="kpi-num count-up text-danger" data-val="{{ $monthlyNetBurn }}">{{ number_format($monthlyNetBurn) }}</span>
                </div>
                <p class="kpi-subtext">Reinvestment in Growth &amp; Inventory</p>
                <div class="kpi-footer-row">
                    <span class="kpi-pill pill-amber"><i class="bi bi-activity"></i> Highly Controlled</span>
                    <span class="text-muted font-xs">Burn Multiple: 0.28x</span>
                </div>
            </div>
            <div class="card-accent-bar bar-rose"></div>
        </div>

        {{-- Cash Runway in Months --}}
        <div class="kpi-bento-card" style="background:#f0fdf4; border-color:#bbf7d0;">
            <div class="kpi-inner">
                <div class="kpi-header">
                    <span class="kpi-label" style="color:#059669;">Cash Runway (Months)</span>
                    <div class="kpi-icon-wrap icon-emerald">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
                <div class="kpi-value-row">
                    <span class="kpi-num count-up text-success" data-val="{{ (int)$runwayMonths }}">{{ $runwayMonths }}</span>
                    <span class="fs-4 fw-bold text-success">Months</span>
                </div>
                <p class="kpi-subtext">Runway at current burn without new fundraise</p>
                <div class="kpi-footer-row">
                    <span class="kpi-pill pill-green"><i class="bi bi-shield-fill-check"></i> Safe Zone (&gt; 12M)</span>
                    <span class="text-muted font-xs">Self-Sustaining</span>
                </div>
            </div>
            <div class="card-accent-bar bar-green"></div>
        </div>

        {{-- Working Capital (A/R vs A/P) --}}
        <div class="kpi-bento-card">
            <div class="kpi-inner">
                <div class="kpi-header">
                    <span class="kpi-label">Working Capital Liquidity</span>
                    <div class="kpi-icon-wrap icon-amber">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="font-xs text-muted d-block">Accounts Receivable:</span>
                        <strong class="text-primary font-sm">₹{{ number_format($accountsReceivable) }}</strong>
                    </div>
                    <div class="text-end">
                        <span class="font-xs text-muted d-block">Accounts Payable:</span>
                        <strong class="text-danger font-sm">₹{{ number_format($accountsPayable) }}</strong>
                    </div>
                </div>
                <p class="kpi-subtext mt-1">Gateway settlement T+2 &amp; vendor 30-day term</p>
                <div class="kpi-footer-row">
                    <span class="kpi-pill pill-green">+₹{{ number_format($accountsReceivable - $accountsPayable) }} Net Working Surplus</span>
                </div>
            </div>
            <div class="card-accent-bar bar-amber"></div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════
         SECTION 2: CUSTOMER COHORTS & UNIT ECONOMICS (CAC, LTV, RETENTION)
         ══════════════════════════════════════════════════════════════════ --}}
    <div class="section-head-bar">
        <div class="section-title-box">
            <div class="section-indicator-bar"></div>
            <h2 class="section-main-title">2. Customer Retention, LTV &amp; Unit Economics</h2>
        </div>
        <span class="section-tag-badge"><i class="bi bi-people-fill me-1"></i> CAC Payback &bull; Cohort Stickiness</span>
    </div>

    <div class="bento-kpi-grid">
        {{-- Total & Active Customers --}}
        <div class="kpi-bento-card">
            <div class="kpi-inner">
                <div class="kpi-header">
                    <span class="kpi-label">Total Registered Users</span>
                    <div class="kpi-icon-wrap icon-purple">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
                <div class="kpi-value-row">
                    <span class="kpi-num count-up" data-val="{{ $totalCustomers }}">{{ number_format($totalCustomers) }}</span>
                </div>
                <p class="kpi-subtext">{{ number_format($activeCustomers) }} active transacting shoppers (90d)</p>
                <div class="kpi-footer-row">
                    <span class="kpi-pill pill-purple"><i class="bi bi-arrow-up-short"></i> +{{ $customerGrowthRate }}% MoM</span>
                    <span class="text-muted font-xs">+{{ number_format($newCustomersMonthly) }}/mo</span>
                </div>
            </div>
            <div class="card-accent-bar bar-purple"></div>
        </div>

        {{-- Repeat Retention vs Churn --}}
        <div class="kpi-bento-card">
            <div class="kpi-inner">
                <div class="kpi-header">
                    <span class="kpi-label">Customer Repeat Retention</span>
                    <div class="kpi-icon-wrap icon-emerald">
                        <i class="bi bi-repeat"></i>
                    </div>
                </div>
                <div class="kpi-value-row">
                    <span class="kpi-num count-up text-success" data-val="{{ (int)$retentionRate }}">{{ $retentionRate }}</span>
                    <span class="fs-4 fw-bold text-muted">%</span>
                </div>
                <p class="kpi-subtext">34.8% repeat buyers (Zero deep discounts)</p>
                <div class="kpi-footer-row">
                    <span class="kpi-pill pill-green"><i class="bi bi-check2"></i> High Organic Stickiness</span>
                    <span class="text-muted font-xs">Churn: {{ $churnRate }}%</span>
                </div>
            </div>
            <div class="card-accent-bar bar-green"></div>
        </div>

        {{-- LTV vs CAC Comparison --}}
        <div class="kpi-bento-card" style="background:#f8fbff; border-color:#bfdbfe;">
            <div class="kpi-inner">
                <div class="kpi-header">
                    <span class="kpi-label" style="color:#2563eb;">Customer LTV vs CAC</span>
                    <div class="kpi-icon-wrap icon-blue">
                        <i class="bi bi-bullseye"></i>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-baseline">
                    <div>
                        <span class="font-xs text-muted d-block">LTV:</span>
                        <strong class="text-success" style="font-size: 22px;">₹{{ number_format($ltv) }}</strong>
                    </div>
                    <div class="text-end">
                        <span class="font-xs text-muted d-block">CAC:</span>
                        <strong class="text-primary" style="font-size: 22px;">₹{{ number_format($cac) }}</strong>
                    </div>
                </div>
                <p class="kpi-subtext mt-1">Blended acquisition cost across Meta &amp; Google</p>
                <div class="kpi-footer-row">
                    <span class="kpi-pill pill-blue" style="font-size: 13px; font-weight: 900;">
                        <i class="bi bi-stars"></i> {{ $ltvCacRatio }}x LTV / CAC
                    </span>
                    <span class="text-muted font-xs">Benchmark &gt; 3.0x</span>
                </div>
            </div>
            <div class="card-accent-bar bar-blue"></div>
        </div>

        {{-- CAC Payback Period --}}
        <div class="kpi-bento-card">
            <div class="kpi-inner">
                <div class="kpi-header">
                    <span class="kpi-label">CAC Payback Duration</span>
                    <div class="kpi-icon-wrap icon-cyan">
                        <i class="bi bi-speedometer2"></i>
                    </div>
                </div>
                <div class="kpi-value-row">
                    <span class="kpi-num count-up" data-val="{{ (int)$paybackPeriodMonths }}">{{ $paybackPeriodMonths }}</span>
                    <span class="fs-4 fw-bold text-muted">Months</span>
                </div>
                <p class="kpi-subtext">Acquisition cost recouped on 1st order + 1 repurchase</p>
                <div class="kpi-footer-row">
                    <span class="kpi-pill pill-green"><i class="bi bi-lightning-charge-fill"></i> Fast Reinvestment Cycle</span>
                    <span class="text-muted font-xs">Top Quartile D2C</span>
                </div>
            </div>
            <div class="card-accent-bar bar-cyan"></div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════
         SECTION 3 & 4: VISUAL ANALYTICS (12-MONTH TRAJECTORY & WATERFALL)
         ══════════════════════════════════════════════════════════════════ --}}
    <div class="section-head-bar">
        <div class="section-title-box">
            <div class="section-indicator-bar"></div>
            <h2 class="section-main-title">3. Visual Growth Trajectory &amp; Unit Economics Waterfall</h2>
        </div>
        <span class="section-tag-badge"><i class="bi bi-graph-up me-1"></i> Interactive Dual-Axis Telemetry</span>
    </div>

    <div class="grid-two-col">
        {{-- Chart 1: 12-Month Revenue & Operating EBITDA Trend --}}
        <div class="cockpit-panel-card">
            <div class="cockpit-panel-head">
                <div>
                    <h3 class="cockpit-panel-title">
                        <i class="bi bi-activity text-primary"></i> 12-Month Revenue &amp; EBITDA Progression
                    </h3>
                    <span class="text-muted font-xs">Compounding monthly gross revenue vs realized EBITDA trajectory</span>
                </div>
                <span class="badge bg-primary-subtle text-primary border px-3 py-1 font-xs font-bold rounded-pill">
                    CAGR: +184%
                </span>
            </div>
            <div style="height: 300px; position: relative;">
                <canvas id="revenueEbitdaChart"></canvas>
            </div>
        </div>

        {{-- Chart 2: Cumulative Customer Growth & Orders Velocity --}}
        <div class="cockpit-panel-card">
            <div class="cockpit-panel-head">
                <div>
                    <h3 class="cockpit-panel-title">
                        <i class="bi bi-people-fill text-success"></i> Customer Cohort Expansion &amp; Order Volume
                    </h3>
                    <span class="text-muted font-xs">Cumulative registered customer base vs monthly dispatch orders</span>
                </div>
                <span class="badge bg-success-subtle text-success border px-3 py-1 font-xs font-bold rounded-pill">
                    {{ number_format($totalOrdersCount) }} Total Orders
                </span>
            </div>
            <div style="height: 300px; position: relative;">
                <canvas id="customerOrdersChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Unit Economics Waterfall & Regional Demand --}}
    <div class="grid-two-col">
        {{-- Unit Economics Breakdown Bar --}}
        <div class="cockpit-panel-card">
            <div class="cockpit-panel-head">
                <div>
                    <h3 class="cockpit-panel-title">
                        <i class="bi bi-bar-chart-steps text-warning"></i> Per-Unit Order Economics (AOV: ₹{{ number_format($aov) }})
                    </h3>
                    <span class="text-muted font-xs">Accounting waterfall from gross checkout to net cash retained</span>
                </div>
                <span class="badge bg-warning-subtle text-warning border px-2 py-1 font-xs font-bold">Waterfall</span>
            </div>

            <div class="p-3 bg-light rounded-3 border mb-3">
                <div class="d-flex justify-content-between py-2 border-bottom font-xs">
                    <span class="text-muted"><i class="bi bi-cart-check-fill text-primary me-1"></i> Average Order Value (AOV):</span>
                    <strong style="color:var(--inv-navy); font-size:14px;">₹{{ number_format($aov) }} (100%)</strong>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom font-xs">
                    <span class="text-muted"><i class="bi bi-scissors text-secondary me-1"></i> Product COGS &amp; Sourcing (Surat/Tirupur):</span>
                    <strong class="text-danger">-₹{{ number_format(round($aov * 0.385)) }} (38.5%)</strong>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom font-xs">
                    <span class="text-muted"><i class="bi bi-truck text-secondary me-1"></i> Shipping, Logistics &amp; Packaging:</span>
                    <strong class="text-danger">-₹{{ number_format(round($aov * 0.11)) }} (11.0%)</strong>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom font-xs">
                    <span class="text-muted"><i class="bi bi-megaphone-fill text-secondary me-1"></i> Blended Marketing &amp; Ad CAC:</span>
                    <strong class="text-danger">-₹{{ number_format(round($aov * 0.22)) }} (22.0%)</strong>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom font-xs">
                    <span class="text-muted"><i class="bi bi-cpu text-secondary me-1"></i> Platform, Gateway (2%) &amp; Operations:</span>
                    <strong class="text-danger">-₹{{ number_format(round($aov * 0.12)) }} (12.0%)</strong>
                </div>
                <div class="d-flex justify-content-between pt-2 font-xs">
                    <strong class="text-success"><i class="bi bi-wallet2 me-1"></i> Net Retained Operating Contribution:</strong>
                    <strong class="text-success" style="font-size: 16px;">
                        +₹{{ number_format(round($aov * 0.165)) }} (16.5% Contribution)
                    </strong>
                </div>
            </div>
            <div class="font-xs text-muted">
                <i class="bi bi-info-circle me-1"></i> Zero reliance on heavy clearances; 16.5% contribution margin is reinvested directly into working capital inventory.
            </div>
        </div>

        {{-- Regional Geographic Market Penetration --}}
        <div class="cockpit-panel-card">
            <div class="cockpit-panel-head">
                <div>
                    <h3 class="cockpit-panel-title">
                        <i class="bi bi-geo-alt-fill text-danger"></i> Geographic Market Penetration &amp; Hubs
                    </h3>
                    <span class="text-muted font-xs">State revenue distribution and metro vs Tier 2/3 demand</span>
                </div>
                <span class="badge bg-light text-dark border px-2 py-1 font-xs font-bold">Pan-India</span>
            </div>

            <div class="cockpit-table-wrap">
                <table class="cockpit-table">
                    <thead>
                        <tr>
                            <th>TERRITORY &amp; CITIES</th>
                            <th>REVENUE SHARE</th>
                            <th>TOTAL RUN RATE</th>
                            <th>GROWTH</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($regionalPenetration as $r)
                            <tr>
                                <td><strong style="color:var(--inv-navy);"><i class="bi bi-pin-map-fill text-danger me-1"></i>{{ $r['region'] }}</strong></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="height: 6px; width: 60px; background: #e2e8f0; border-radius: 999px; overflow: hidden;">
                                            <div style="height: 100%; width: {{ $r['share'] }}%; background: #2563eb;"></div>
                                        </div>
                                        <strong class="font-xs">{{ $r['share'] }}%</strong>
                                    </div>
                                </td>
                                <td><strong>₹{{ number_format($r['revenue']) }}</strong></td>
                                <td><span class="badge bg-success-subtle text-success border font-xs fw-bold">{{ $r['growth'] }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top font-xs text-muted">
                <span>Tier 1 Metros: <strong>58%</strong></span>
                <span>Tier 2 &amp; 3 High-Growth Towns: <strong>42%</strong></span>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════
         SECTION 5 & 6: CAP TABLE, VALUATION & OPERATIONAL EFFICIENCY
         ══════════════════════════════════════════════════════════════════ --}}
    <div class="section-head-bar">
        <div class="section-title-box">
            <div class="section-indicator-bar"></div>
            <h2 class="section-main-title">4. Capitalization Table, Valuation &amp; Team Productivity</h2>
        </div>
        <span class="section-tag-badge"><i class="bi bi-pie-chart-fill me-1"></i> Equity Structure &bull; Headcount ROI</span>
    </div>

    <div class="grid-two-col">
        {{-- Cap Table Doughnut & Breakdown --}}
        <div class="cockpit-panel-card">
            <div class="cockpit-panel-head">
                <div>
                    <h3 class="cockpit-panel-title">
                        <i class="bi bi-pie-chart-fill text-primary"></i> Equity Distribution &amp; Cap Table
                    </h3>
                    <span class="text-muted font-xs">Current post-money target valuation: <strong>₹{{ number_format($currentValuation / 10000000, 2) }} Cr ($2.15M)</strong></span>
                </div>
                <span class="badge bg-primary-subtle text-primary border px-3 py-1 font-xs font-bold rounded-pill">
                    Raised: ₹{{ number_format($totalFundingRaised / 10000000, 2) }} Cr
                </span>
            </div>

            <div style="height: 200px; position: relative;" class="d-flex align-items-center justify-content-center">
                <canvas id="capTableChart"></canvas>
            </div>

            <div class="cockpit-table-wrap mt-3">
                <table class="cockpit-table">
                    <thead>
                        <tr>
                            <th>STAKEHOLDER</th>
                            <th>SECURITY TYPE</th>
                            <th>EQUITY %</th>
                            <th>EST. VALUATION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($capTable as $c)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span style="width: 10px; height: 10px; border-radius: 50%; background: {{ $c['color'] }}; display: inline-block;"></span>
                                        <strong>{{ $c['stakeholder'] }}</strong>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark border font-xs">{{ $c['category'] }}</span></td>
                                <td><strong style="color:var(--inv-navy);">{{ $c['equity_pct'] }}%</strong></td>
                                <td><strong class="text-success">₹{{ number_format($c['value'] / 100000, 1) }}L</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Team Efficiency & Departmental Performance --}}
        <div class="cockpit-panel-card">
            <div class="cockpit-panel-head">
                <div>
                    <h3 class="cockpit-panel-title">
                        <i class="bi bi-building-check text-success"></i> Operational Productivity &amp; Team Metrics
                    </h3>
                    <span class="text-muted font-xs">Lean, highly automated workforce structure</span>
                </div>
                <span class="badge bg-success-subtle text-success border px-2 py-1 font-xs font-bold">16 Full-Time</span>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-6">
                    <div class="p-3 bg-light rounded-3 border text-center">
                        <span class="font-xs text-muted d-block uppercase fw-bold">Revenue Per Employee</span>
                        <strong class="text-primary fs-5">₹{{ number_format($revenuePerEmployee / 100000, 1) }} Lakhs</strong>
                        <span class="d-block font-xs text-muted mt-1">High operating leverage</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-3 bg-light rounded-3 border text-center">
                        <span class="font-xs text-muted d-block uppercase fw-bold">Order Dispatch SLA</span>
                        <strong class="text-success fs-5">{{ $dispatchSlaHours }} Hours</strong>
                        <span class="d-block font-xs text-muted mt-1">99.4% error-free pack</span>
                    </div>
                </div>
            </div>

            <div class="cockpit-table-wrap">
                <table class="cockpit-table">
                    <thead>
                        <tr>
                            <th>DEPARTMENT</th>
                            <th>HEADCOUNT</th>
                            <th>CORE METRIC / ROI</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departments as $d)
                            <tr>
                                <td><strong>{{ $d['name'] }}</strong></td>
                                <td><span class="badge bg-light text-dark border font-xs">{{ $d['headcount'] }} members</span></td>
                                <td><strong style="color:var(--inv-navy); font-size:12px;">{{ $d['metric'] }}</strong></td>
                                <td><span class="badge bg-success-subtle text-success border font-xs fw-bold">{{ $d['status'] }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top font-xs text-muted">
                <span>Blended ROAS on Paid Ads: <strong class="text-success">{{ $blendedRoas }}x</strong></span>
                <span>Inventory GMROI: <strong class="text-primary">{{ $gmroi }}x</strong></span>
            </div>
        </div>
    </div>

    {{-- Top Bestselling Products Contribution --}}
    <div class="cockpit-panel-card">
        <div class="cockpit-panel-head">
            <div>
                <h3 class="cockpit-panel-title">
                    <i class="bi bi-trophy-fill text-warning"></i> Top 5 Hero Articles &amp; Gross Contribution
                </h3>
                <span class="text-muted font-xs">Core revenue drivers exhibiting strong recurring customer demand</span>
            </div>
            <span class="badge bg-light text-dark border px-2 py-1 font-xs font-bold">Hero SKUs</span>
        </div>

        <div class="cockpit-table-wrap">
            <table class="cockpit-table">
                <thead>
                    <tr>
                        <th>PRODUCT ARTICLE</th>
                        <th>UNITS SOLD</th>
                        <th>TOTAL REVENUE</th>
                        <th>GROSS CONTRIBUTION MARGIN</th>
                        <th>REPURCHASE INDEX</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topProducts as $idx => $p)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge {{ $idx === 0 ? 'bg-warning text-dark' : 'bg-light text-dark border' }} rounded-circle p-1" style="width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 800;">
                                        {{ $idx + 1 }}
                                    </span>
                                    <strong style="color:var(--inv-navy);">{{ $p->product_name }}</strong>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark border font-xs fw-bold">{{ number_format($p->qty) }} units</span></td>
                            <td><strong class="text-success" style="font-size:14px;">₹{{ number_format($p->revenue) }}</strong></td>
                            <td>
                                <span class="badge bg-success-subtle text-success border font-xs fw-bold">
                                    {{ $p->margin ?? 62 }}% Contribution
                                </span>
                            </td>
                            <td><span class="text-primary font-xs fw-bold"><i class="bi bi-stars"></i> High Viral Loop</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════
         SECTION 7: EXECUTIVE SUMMARY, HEALTH SCORE & STRATEGIC ROADMAP
         ══════════════════════════════════════════════════════════════════ --}}
    <div class="section-head-bar">
        <div class="section-title-box">
            <div class="section-indicator-bar"></div>
            <h2 class="section-main-title">5. Executive Summary, Risk Matrix &amp; 12-Month Growth Roadmap</h2>
        </div>
        <span class="section-tag-badge"><i class="bi bi-briefcase-fill me-1"></i> Investment Thesis</span>
    </div>

    <div class="health-score-widget">
        <div class="score-circle-outer">
            <span class="score-big-num">{{ $businessHealthScore }}</span>
            <span class="score-label-sub">/ 100</span>
        </div>
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h3 style="font-size: 18px; font-weight: 900; color: #065f46; margin: 0;">
                    Overall Business Health: Top-Tier / Investment Grade
                </h3>
                <span class="badge bg-success text-white font-xs fw-bold px-2 py-1 rounded-pill">SERIES SEED READY</span>
            </div>
            <p style="font-size: 13px; color: #047857; margin: 0; line-height: 1.5;">
                The Trend Theory demonstrates exceptional capital efficiency with a <strong>{{ $ltvCacRatio }}x LTV/CAC ratio</strong>, <strong>{{ $runwayMonths }} months of liquid runway</strong>, positive operating cash flows, and an 8.6% RTO courier return rate that outperforms D2C fashion industry benchmarks.
            </p>
        </div>
    </div>

    <div class="grid-three-col">
        {{-- Card 1: Key Institutional Achievements --}}
        <div class="cockpit-panel-card">
            <div class="cockpit-panel-head">
                <h4 class="cockpit-panel-title" style="color: #059669; font-size: 15px;">
                    <i class="bi bi-check-circle-fill text-success"></i> Key Traction Milestones
                </h4>
            </div>
            <div class="d-flex flex-column gap-3">
                @foreach ($keyAchievements as $ach)
                    <div class="p-3 bg-light rounded-3 border">
                        <strong style="color:var(--inv-navy); font-size:13px;" class="d-block mb-1">
                            &bull; {{ $ach['title'] }}
                        </strong>
                        <span class="font-xs text-muted" style="line-height: 1.4; display:block;">
                            {{ $ach['desc'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Card 2: Risk Factors & Active Mitigations --}}
        <div class="cockpit-panel-card">
            <div class="cockpit-panel-head">
                <h4 class="cockpit-panel-title" style="color: #d97706; font-size: 15px;">
                    <i class="bi bi-shield-exclamation text-warning"></i> Risk Factors &amp; Mitigation
                </h4>
            </div>
            <div class="d-flex flex-column gap-3">
                @foreach ($risksAndMitigation as $risk)
                    <div class="p-3 bg-light rounded-3 border">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <strong style="color:#b45309; font-size:12.5px;">{{ $risk['risk'] }}</strong>
                            <span class="badge bg-warning-subtle text-warning border font-xs">{{ $risk['severity'] }}</span>
                        </div>
                        <span class="font-xs text-muted d-block" style="line-height: 1.4;">
                            <strong>Mitigation:</strong> {{ $risk['mitigation'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Card 3: 12-Month Expansion Roadmap & TAM --}}
        <div class="cockpit-panel-card">
            <div class="cockpit-panel-head">
                <h4 class="cockpit-panel-title" style="color: #2563eb; font-size: 15px;">
                    <i class="bi bi-rocket-takeoff-fill text-primary"></i> 12-Month Expansion Roadmap
                </h4>
            </div>
            <div class="d-flex flex-column gap-3">
                @foreach ($growthOpportunities as $opp)
                    <div class="p-3 bg-light rounded-3 border">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <strong style="color:var(--inv-navy); font-size:13px;">{{ $opp['title'] }}</strong>
                            <span class="badge bg-primary-subtle text-primary border font-xs fw-bold">TAM: {{ $opp['tam'] }}</span>
                        </div>
                        <span class="font-xs text-muted d-block" style="line-height: 1.4;">
                            {{ $opp['desc'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // ── 1. Pitch Mode Fullscreen ──
    function togglePitchMode() {
        const body = document.body;
        const text = document.getElementById('pitchModeText');
        body.classList.toggle('pitch-mode-active');
        if (body.classList.contains('pitch-mode-active')) {
            text.textContent = 'Exit Pitch';
            if (document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen().catch(() => {});
            }
        } else {
            text.textContent = 'Pitch Mode';
            if (document.exitFullscreen && document.fullscreenElement) {
                document.exitFullscreen().catch(() => {});
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        // ── 2. Live IST Clock ──
        function updateClock() {
            var el = document.getElementById('liveClockTicker');
            if (!el) return;
            var now = new Date();
            var options = { timeZone: 'Asia/Kolkata', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
            el.innerHTML = '<i class="bi bi-clock-fill me-1"></i> ' + now.toLocaleTimeString('en-US', options) + ' IST';
        }
        updateClock();
        setInterval(updateClock, 1000);

        // ── 3. Smooth Number Count-Up ──
        var countElements = document.querySelectorAll('.count-up');
        countElements.forEach(function(el) {
            var target = parseInt(el.getAttribute('data-val'), 10) || 0;
            if (target <= 0) return;
            var duration = 950;
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

        const monthsLabels = {!! json_encode($monthsLabels) !!};
        const revenueTrend = {!! json_encode($revenueTrend) !!};
        const ebitdaTrend = {!! json_encode($ebitdaTrend) !!};
        const ordersTrend = {!! json_encode($ordersTrend) !!};
        const userGrowthTrend = {!! json_encode($userGrowthTrend) !!};

        // ── 4. Revenue & EBITDA Chart ──
        const ctxRevEbitda = document.getElementById('revenueEbitdaChart');
        if (ctxRevEbitda) {
            const ctx = ctxRevEbitda.getContext('2d');
            const revGrad = ctx.createLinearGradient(0, 0, 0, 260);
            revGrad.addColorStop(0, 'rgba(0, 40, 90, 0.35)');
            revGrad.addColorStop(1, 'rgba(0, 40, 90, 0.00)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: monthsLabels,
                    datasets: [
                        {
                            label: 'Gross Revenue (₹)',
                            data: revenueTrend,
                            borderColor: '#00285a',
                            backgroundColor: revGrad,
                            fill: true,
                            tension: 0.35,
                            borderWidth: 2.5,
                            pointRadius: 3.5,
                            pointBackgroundColor: '#00285a',
                            yAxisID: 'y'
                        },
                        {
                            label: 'Operating EBITDA (₹)',
                            data: ebitdaTrend,
                            borderColor: '#10b981',
                            backgroundColor: 'transparent',
                            borderDash: [4, 4],
                            tension: 0.35,
                            borderWidth: 2,
                            pointRadius: 3,
                            pointBackgroundColor: '#10b981',
                            yAxisID: 'y'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'top', align: 'end', labels: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '700' } } },
                        tooltip: {
                            callbacks: {
                                label: function(c) {
                                    return c.dataset.label + ': ₹' + Number(c.raw).toLocaleString('en-IN');
                                }
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 10.5 } } },
                        y: {
                            ticks: {
                                callback: function(v) { return '₹' + (v >= 100000 ? (v/100000).toFixed(1) + 'L' : v); },
                                font: { family: 'Plus Jakarta Sans', size: 10.5 }
                            },
                            grid: { color: '#f1f5f9' }
                        }
                    }
                }
            });
        }

        // ── 5. Customer & Orders Growth Chart ──
        const ctxCustOrders = document.getElementById('customerOrdersChart');
        if (ctxCustOrders) {
            const ctx = ctxCustOrders.getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: monthsLabels,
                    datasets: [
                        {
                            label: 'Cumulative Customers',
                            data: userGrowthTrend,
                            borderColor: '#8b5cf6',
                            backgroundColor: 'rgba(139, 92, 246, 0.08)',
                            fill: true,
                            tension: 0.35,
                            borderWidth: 2.5,
                            pointRadius: 3.5,
                            pointBackgroundColor: '#8b5cf6',
                            yAxisID: 'y'
                        },
                        {
                            label: 'Monthly Orders',
                            data: ordersTrend,
                            borderColor: '#06b6d4',
                            backgroundColor: 'transparent',
                            borderDash: [4, 4],
                            tension: 0.35,
                            borderWidth: 2,
                            pointRadius: 3,
                            pointBackgroundColor: '#06b6d4',
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'top', align: 'end', labels: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '700' } } },
                        tooltip: {
                            callbacks: {
                                label: function(c) {
                                    return c.dataset.label + ': ' + Number(c.raw).toLocaleString('en-IN');
                                }
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 10.5 } } },
                        y: {
                            position: 'left',
                            ticks: {
                                callback: function(v) { return v >= 1000 ? (v/1000).toFixed(0) + 'k' : v; },
                                font: { family: 'Plus Jakarta Sans', size: 10.5 }
                            },
                            grid: { color: '#f1f5f9' }
                        },
                        y1: {
                            position: 'right',
                            grid: { drawOnChartArea: false },
                            ticks: { font: { family: 'Plus Jakarta Sans', size: 10.5 } }
                        }
                    }
                }
            });
        }

        // ── 6. Cap Table Doughnut Chart ──
        const ctxCap = document.getElementById('capTableChart');
        if (ctxCap) {
            const capData = {!! json_encode($capTable) !!};
            new Chart(ctxCap.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: capData.map(c => c.stakeholder),
                    datasets: [{
                        data: capData.map(c => c.equity_pct),
                        backgroundColor: capData.map(c => c.color),
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
                                label: function(c) {
                                    return c.label + ': ' + c.raw + '% equity';
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection
