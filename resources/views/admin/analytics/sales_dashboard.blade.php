{{-- resources/views/admin/analytics/sales_dashboard.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Executive Sales Intelligence & COD Profitability')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

    :root {
        --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        --emerald-gradient: linear-gradient(135deg, #059669 0%, #10b981 100%);
        --amber-gradient: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
        --rose-gradient: linear-gradient(135deg, #dc2626 0%, #f43f5e 100%);
        --purple-gradient: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
        --dark-card-gradient: linear-gradient(135deg, #0b192e 0%, #0f2b54 50%, #1e3a8a 100%);
    }

    .sales-studio-root {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        display: flex;
        flex-direction: column;
        gap: 22px;
        color: #0f172a;
        max-width: 1440px;
        margin: 0 auto;
    }

    /* ── 1. Top Executive Banner ── */
    .sales-banner-card {
        background: var(--dark-card-gradient);
        border-radius: 22px;
        padding: 30px 38px;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 12px 35px rgba(11, 25, 46, 0.22);
    }
    .sales-banner-glow {
        position: absolute;
        top: -60px;
        right: -60px;
        width: 280px;
        height: 280px;
        background: radial-gradient(circle, rgba(96, 165, 250, 0.22) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .sales-banner-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 5px 14px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: #60a5fa;
        margin-bottom: 12px;
    }
    .sales-pulse-dot {
        width: 7px;
        height: 7px;
        background: #60a5fa;
        border-radius: 50%;
        box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.4);
    }
    .sales-banner-title {
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin: 0 0 8px;
        color: #ffffff;
    }
    .sales-banner-desc {
        font-size: 13.5px;
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.85);
        margin: 0 0 20px;
        max-width: 620px;
    }

    /* Date Filter Segment Pills */
    .filter-segment-group {
        display: inline-flex;
        align-items: center;
        background: rgba(0, 0, 0, 0.28);
        padding: 4px;
        border-radius: 12px;
        gap: 4px;
        flex-wrap: wrap;
        border: 1px solid rgba(255, 255, 255, 0.16);
    }
    .filter-pill {
        color: rgba(255, 255, 255, 0.85);
        padding: 7px 15px;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.15s ease;
    }
    .filter-pill:hover {
        color: #ffffff;
        background: rgba(255, 255, 255, 0.15);
    }
    .filter-pill.active {
        background: #ffffff;
        color: #0b192e;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.18);
    }

    .sales-banner-art {
        position: absolute;
        right: 32px;
        bottom: 0;
        top: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
    }
    @media (max-width: 960px) {
        .sales-banner-art { display: none; }
    }

    /* ── 2. Top 4 Master Bento Cards with 3D Glowing Icons ── */
    .bento-master-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }
    @media (max-width: 1100px) {
        .bento-master-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 580px) {
        .bento-master-grid { grid-template-columns: 1fr; }
    }

    .bento-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 18px;
        padding: 20px 22px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        transition: all 0.2s ease;
    }
    .bento-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.06);
        border-color: #cbd5e1;
    }
    .bento-card-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }
    .bento-card-label {
        font-size: 11.5px;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .bento-icon-box {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    .icon-glow-blue { background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); color: #2563eb; }
    .icon-glow-green { background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); color: #059669; }
    .icon-glow-amber { background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); color: #d97706; }
    .icon-glow-purple { background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%); color: #9333ea; }

    .bento-num {
        font-size: 26px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.15;
        margin-bottom: 4px;
    }
    .bento-sub {
        font-size: 12px;
        color: #64748b;
    }

    /* Dual Progress Meters */
    .dual-meter-wrap {
        margin-top: 10px;
    }
    .dual-meter-track {
        height: 8px;
        background: #eff6ff;
        border-radius: 999px;
        display: flex;
        overflow: hidden;
    }
    .dual-meter-cod { background: #f59e0b; height: 100%; }
    .dual-meter-prepaid { background: #3b82f6; height: 100%; }

    /* Trend Badges */
    .trend-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 9px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 700;
    }
    .trend-green { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
    .trend-red { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    .trend-neutral { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }

    /* ── 3. Visual Intelligence Grid (Charts + Tables) ── */
    .two-col-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }
    @media (max-width: 960px) {
        .two-col-grid { grid-template-columns: 1fr; }
    }

    .studio-panel-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.02);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .studio-panel-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f1f5f9;
    }
    .studio-panel-title {
        font-size: 15.5px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Metric Mini Tiles */
    .metric-tiles-row {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
        margin-bottom: 16px;
    }
    .metric-tile {
        padding: 14px 16px;
        border-radius: 14px;
        border: 1px solid #edf2f7;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .metric-tile-blue { background: #f8fbff; border-color: #dbeafe; }
    .metric-tile-green { background: #f0fdf4; border-color: #bbf7d0; }
    .metric-tile-amber { background: #fffdf5; border-color: #fde68a; }
    .metric-tile-rose { background: #fffbfb; border-color: #fecaca; }

    .tile-label { font-size: 11px; font-weight: 700; color: #64748b; margin-bottom: 2px; }
    .tile-num { font-size: 17px; font-weight: 800; color: #0f172a; }
    .tile-sub { font-size: 11px; font-weight: 600; }

    /* Tables */
    .analytics-table {
        width: 100%;
        border-collapse: collapse;
    }
    .analytics-table th {
        font-size: 10.5px;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 14px;
        border-bottom: 1px solid #edf2f7;
        background: #fafcff;
        text-align: left;
    }
    .analytics-table td {
        padding: 12px 14px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 12.5px;
        vertical-align: middle;
    }
    .analytics-table tr:last-child td {
        border-bottom: none;
    }
    .analytics-table tr:hover td {
        background: #f8fafc;
    }

    /* Risk Badges */
    .risk-badge {
        padding: 3px 9px;
        border-radius: 6px;
        font-size: 10.5px;
        font-weight: 800;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .risk-badge-success { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
    .risk-badge-warning { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
    .risk-badge-danger { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

    /* Avatar Pill */
    .cust-avatar-pill {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #eff6ff;
        color: #2563eb;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 12px;
        flex-shrink: 0;
    }
</style>

<div class="sales-studio-root">

    {{-- ── 1. Top Executive Studio Banner ── --}}
    <div class="sales-banner-card">
        <div class="sales-banner-glow"></div>
        <div class="sales-banner-badge">
            <span class="sales-pulse-dot"></span>
            <span>EXECUTIVE REVENUE &amp; COD PROFITABILITY ENGINE</span>
        </div>
        <h1 class="sales-banner-title">Sales, COD &amp; Profit Intelligence</h1>
        <p class="sales-banner-desc">
            Real-time cash conversion, COD vs Prepaid order share, customer return (RTO) financial loss accounting, and net business operating margins.
        </p>

        {{-- Segment Filter Pills --}}
        <div class="filter-segment-group">
            <a href="{{ route('admin.sales.analytics', ['range' => 'today']) }}" class="filter-pill {{ $range === 'today' ? 'active' : '' }}">Today</a>
            <a href="{{ route('admin.sales.analytics', ['range' => 'yesterday']) }}" class="filter-pill {{ $range === 'yesterday' ? 'active' : '' }}">Yesterday</a>
            <a href="{{ route('admin.sales.analytics', ['range' => '7days']) }}" class="filter-pill {{ $range === '7days' ? 'active' : '' }}">Last 7 Days</a>
            <a href="{{ route('admin.sales.analytics', ['range' => 'this_month']) }}" class="filter-pill {{ $range === 'this_month' ? 'active' : '' }}">This Month</a>
            <a href="{{ route('admin.sales.analytics', ['range' => 'last_month']) }}" class="filter-pill {{ $range === 'last_month' ? 'active' : '' }}">Last Month</a>
            <a href="{{ route('admin.sales.analytics', ['range' => 'this_year']) }}" class="filter-pill {{ $range === 'this_year' ? 'active' : '' }}">Year 2026</a>
        </div>

        {{-- Right Illustration Artwork --}}
        <div class="sales-banner-art">
            <svg width="260" height="150" viewBox="0 0 260 150" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="20" y="20" width="190" height="110" rx="16" fill="white" fill-opacity="0.95" />
                <rect x="35" y="35" width="65" height="10" rx="3" fill="#0F3066" />
                <rect x="35" y="52" width="45" height="14" rx="4" fill="#3B82F6" fill-opacity="0.15" />
                <path d="M35 95L60 80L85 90L115 70L145 80L180 55" stroke="#2563EB" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                <circle cx="180" cy="55" r="4" fill="#10B981" stroke="white" stroke-width="2" />
                <rect x="135" y="10" width="80" height="26" rx="13" fill="#10B981" />
                <path d="M150 25L157 18L162 22L170 14" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </div>

    {{-- ── 2. Top 4 Master Bento Cards with 3D Glowing Icons ── --}}
    <div class="bento-master-grid">
        {{-- Gross Revenue --}}
        <div class="bento-card">
            <div class="bento-card-head">
                <span class="bento-card-label">Gross Booked Sales</span>
                <div class="bento-icon-box icon-glow-blue">
                    <i class="bi bi-currency-rupee"></i>
                </div>
            </div>
            <div class="bento-num">₹{{ number_format($grossSales) }}</div>
            <div class="bento-sub">{{ $totalOrdersCount }} orders booked in {{ $rangeLabel }}</div>
            <div class="mt-2">
                <span class="trend-pill {{ $salesGrowth >= 0 ? 'trend-green' : 'trend-red' }}">
                    <i class="bi {{ $salesGrowth >= 0 ? 'bi-arrow-up-short' : 'bi-arrow-down-short' }}"></i> {{ abs($salesGrowth) }}% vs prev period
                </span>
            </div>
        </div>

        {{-- Delivered Cash --}}
        <div class="bento-card">
            <div class="bento-card-head">
                <span class="bento-card-label">Realized Delivered Cash</span>
                <div class="bento-icon-box icon-glow-green">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
            <div class="bento-num" style="color: #059669;">₹{{ number_format($deliveredRevenue ?: $netRealizedSales) }}</div>
            <div class="bento-sub text-success font-weight-bold">Successful Doorstep Deliveries</div>
            <div class="mt-2">
                <span class="trend-pill trend-green">
                    <i class="bi bi-shield-check"></i> Clean Retained Revenue
                </span>
            </div>
        </div>

        {{-- COD vs Prepaid Mix --}}
        <div class="bento-card">
            <div class="bento-card-head">
                <span class="bento-card-label">Payment Channel Mix</span>
                <div class="bento-icon-box icon-glow-amber">
                    <i class="bi bi-pie-chart-fill"></i>
                </div>
            </div>
            <div class="bento-num" style="font-size: 22px;">
                <span style="color:#d97706;">{{ $codRatio }}% COD</span> &bull; <span style="color:#2563eb;">{{ $prepaidRatio }}% Online</span>
            </div>
            <div class="bento-sub">₹{{ number_format($codRevenue) }} COD &bull; ₹{{ number_format($prepaidRevenue) }} Prepaid</div>
            <div class="dual-meter-wrap">
                <div class="dual-meter-track">
                    <div class="dual-meter-cod" style="width: {{ $codRatio }}%;"></div>
                    <div class="dual-meter-prepaid" style="width: {{ $prepaidRatio }}%;"></div>
                </div>
            </div>
        </div>

        {{-- Net Operating Profit --}}
        <div class="bento-card" style="background:#fafffd; border-color:#bbf7d0;">
            <div class="bento-card-head">
                <span class="bento-card-label" style="color:#059669;">Net Operating Profit</span>
                <div class="bento-icon-box icon-glow-green">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
            </div>
            <div class="bento-num" style="color: #059669;">₹{{ number_format($netRealizedProfit) }}</div>
            <div class="bento-sub">In-pocket profit after COGS &amp; RTO Freight</div>
            <div class="mt-2">
                <span class="trend-pill trend-green" style="font-weight: 800;">
                    <i class="bi bi-check-circle-fill"></i> {{ $netProfitMargin }}% Net Operating Margin
                </span>
            </div>
        </div>
    </div>

    {{-- ── 3. Interactive Visual Graphs Row (Payment Donut + Profit vs Loss Bar) ── --}}
    <div class="two-col-grid">
        
        {{-- Graph 1: Payment Channel Split Doughnut Chart --}}
        <div class="studio-panel-card">
            <div class="studio-panel-head">
                <div>
                    <h3 class="studio-panel-title">
                        <i class="bi bi-pie-chart text-primary"></i> Payment Channel Split &amp; Conversion
                    </h3>
                    <span class="text-muted font-xs">Share of COD vs Prepaid Online Revenue</span>
                </div>
            </div>

            <div style="height: 220px; position: relative;" class="d-flex align-items-center justify-content-center">
                <canvas id="paymentDonutChart"></canvas>
            </div>

            <div class="row g-2 mt-3 pt-3 border-top text-center font-xs">
                <div class="col-6">
                    <span class="text-muted d-block"><i class="bi bi-circle-fill text-warning me-1"></i> Cash on Delivery:</span>
                    <strong class="text-navy" style="font-size:14px;">₹{{ number_format($codRevenue) }} ({{ $codRatio }}%)</strong>
                </div>
                <div class="col-6">
                    <span class="text-muted d-block"><i class="bi bi-circle-fill text-primary me-1"></i> Online Prepaid:</span>
                    <strong class="text-navy" style="font-size:14px;">₹{{ number_format($prepaidRevenue) }} ({{ $prepaidRatio }}%)</strong>
                </div>
            </div>
        </div>

        {{-- Graph 2: Financial Profitability & Loss Waterfall Bar Chart --}}
        <div class="studio-panel-card">
            <div class="studio-panel-head">
                <div>
                    <h3 class="studio-panel-title">
                        <i class="bi bi-bar-chart-line-fill text-success"></i> Financial Realization &amp; Loss Breakdown
                    </h3>
                    <span class="text-muted font-xs">Delivered Revenue vs COGS vs RTO Shipping Loss vs Net Profit</span>
                </div>
            </div>

            <div style="height: 220px; position: relative;">
                <canvas id="profitWaterfallChart"></canvas>
            </div>

            <div class="d-flex justify-content-between align-items-center pt-3 border-top font-xs mt-3">
                <span class="text-muted">Net Retained Operating Profit:</span>
                <strong class="text-success" style="font-size: 15px;">₹{{ number_format($netRealizedProfit) }} ({{ $netProfitMargin }}%)</strong>
            </div>
        </div>

    </div>

    {{-- ── 4. COD vs Prepaid Velocity & Conversion Tiles ── --}}
    <div class="two-col-grid">
        
        {{-- Cash on Delivery (COD) Performance --}}
        <div class="studio-panel-card">
            <div class="studio-panel-head">
                <div>
                    <h3 class="studio-panel-title">
                        <i class="bi bi-cash-stack text-warning"></i> Cash on Delivery (COD) Intelligence
                    </h3>
                    <span class="text-muted font-xs">Doorstep cash conversion, ticket size, and delivery success</span>
                </div>
                <span class="badge bg-warning-subtle text-warning border px-2 py-1 font-xs font-bold">{{ $codRatio }}% Share</span>
            </div>

            <div class="metric-tiles-row">
                <div class="metric-tile metric-tile-amber">
                    <span class="tile-label"><i class="bi bi-receipt me-1"></i> Booked</span>
                    <div class="tile-num">₹{{ number_format($codRevenue) }}</div>
                    <span class="tile-sub text-muted">{{ $codTotalCount }} orders</span>
                </div>
                <div class="metric-tile metric-tile-green">
                    <span class="tile-label"><i class="bi bi-check2-circle me-1"></i> Cash Delivered</span>
                    <div class="tile-num" style="color:#059669;">{{ $codDelivered }}</div>
                    <span class="tile-sub text-success">{{ $codRealizationRate }}% Realized</span>
                </div>
                <div class="metric-tile metric-tile-rose">
                    <span class="tile-label"><i class="bi bi-x-octagon me-1"></i> Returned/RTO</span>
                    <div class="tile-num" style="color:#dc2626;">{{ $codReturnCount }}</div>
                    <span class="tile-sub text-danger">{{ $codReturnRate }}% RTO Rate</span>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3 font-xs">
                <div>
                    <span class="text-muted d-block">Average COD Ticket Size (AOV):</span>
                    <strong class="text-navy" style="font-size: 14.5px;">₹{{ number_format($codAov) }}</strong>
                </div>
                <div class="text-end">
                    <span class="text-muted d-block">Doorstep Realization:</span>
                    <strong class="{{ $codRealizationRate >= 70 ? 'text-success' : 'text-danger' }}" style="font-size: 14.5px;">{{ $codRealizationRate }}%</strong>
                </div>
            </div>
        </div>

        {{-- Online Prepaid Performance --}}
        <div class="studio-panel-card">
            <div class="studio-panel-head">
                <div>
                    <h3 class="studio-panel-title">
                        <i class="bi bi-credit-card-2-front-fill text-primary"></i> Online Prepaid Intelligence
                    </h3>
                    <span class="text-muted font-xs">UPI, Cards, Net Banking &amp; Wallet transactions</span>
                </div>
                <span class="badge bg-primary-subtle text-primary border px-2 py-1 font-xs font-bold">{{ $prepaidRatio }}% Share</span>
            </div>

            <div class="metric-tiles-row">
                <div class="metric-tile metric-tile-blue">
                    <span class="tile-label"><i class="bi bi-receipt me-1"></i> Prepaid</span>
                    <div class="tile-num">₹{{ number_format($prepaidRevenue) }}</div>
                    <span class="tile-sub text-muted">{{ $prepaidTotalCount }} orders</span>
                </div>
                <div class="metric-tile metric-tile-green">
                    <span class="tile-label"><i class="bi bi-check2-circle me-1"></i> Successful</span>
                    <div class="tile-num" style="color:#059669;">{{ $prepaidDelivered }}</div>
                    <span class="tile-sub text-success">{{ $prepaidRealizationRate }}% Delivered</span>
                </div>
                <div class="metric-tile metric-tile-rose">
                    <span class="tile-label"><i class="bi bi-arrow-return-left me-1"></i> Returns</span>
                    <div class="tile-num" style="color:#dc2626;">{{ $prepaidReturnCount }}</div>
                    <span class="tile-sub text-danger">{{ $prepaidReturnRate }}% Returns</span>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3 font-xs">
                <div>
                    <span class="text-muted d-block">Average Prepaid Ticket Size (AOV):</span>
                    <strong class="text-navy" style="font-size: 14.5px;">₹{{ number_format($prepaidAov) }}</strong>
                </div>
                <div class="text-end">
                    <span class="text-muted d-block">Prepaid Realization:</span>
                    <strong class="text-success" style="font-size: 14.5px;">{{ $prepaidRealizationRate }}%</strong>
                </div>
            </div>
        </div>

    </div>

    {{-- ── 5. Returns & RTO Profit vs Loss Financial Accounting ── --}}
    <div class="studio-panel-card" style="border-left: 5px solid #dc2626;">
        <div class="studio-panel-head">
            <div>
                <h3 class="studio-panel-title">
                    <i class="bi bi-arrow-left-right text-danger"></i> Returns &amp; RTO Financial Loss vs Realized Profit
                </h3>
                <span class="text-muted font-xs">Full accounting of return courier logistics losses, dead stock, and net retained margins</span>
            </div>
            <span class="badge bg-danger-subtle text-danger border px-3 py-1 font-xs font-bold">{{ $overallReturnRate }}% Store Return Rate</span>
        </div>

        <div class="row g-3">
            {{-- Out of Pocket Losses --}}
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 border h-100">
                    <h4 style="font-size: 13px; font-weight: 800; color: #dc2626; margin-bottom: 12px;">
                        <i class="bi bi-exclamation-octagon-fill me-1"></i> Cost of Returns &amp; Financial Losses:
                    </h4>

                    <div class="d-flex justify-content-between py-2 border-bottom font-xs">
                        <span class="text-muted"><i class="bi bi-box-seam me-1"></i> Returned / RTO Orders:</span>
                        <strong class="text-navy">{{ $returnCount }} parcels</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom font-xs">
                        <span class="text-muted"><i class="bi bi-truck me-1"></i> Freight Courier Loss (₹120/parcel):</span>
                        <strong class="text-danger">-₹{{ number_format($returnLogisticsLoss) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom font-xs">
                        <span class="text-muted"><i class="bi bi-archive me-1"></i> Packaging &amp; Restocking (₹30/parcel):</span>
                        <strong class="text-danger">-₹{{ number_format($returnPackagingLoss) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom font-xs">
                        <span class="text-muted"><i class="bi bi-tag me-1"></i> Gross Returned Merchandise Value:</span>
                        <strong class="text-muted">₹{{ number_format($returnedMerchandiseValue) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between pt-2 font-xs">
                        <strong class="text-danger">Total Out-of-Pocket Loss on Returns:</strong>
                        <strong class="text-danger" style="font-size: 15.5px;">-₹{{ number_format($totalReturnFinancialLoss) }}</strong>
                    </div>
                </div>
            </div>

            {{-- Realized Operating Profit --}}
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 border h-100" style="background:#f0fdf4 !important; border-color:#bbf7d0 !important;">
                    <h4 style="font-size: 13px; font-weight: 800; color: #059669; margin-bottom: 12px;">
                        <i class="bi bi-wallet-fill me-1"></i> Net Operating Profitability:
                    </h4>

                    <div class="d-flex justify-content-between py-2 border-bottom font-xs">
                        <span class="text-muted"><i class="bi bi-cash me-1"></i> Gross Delivered Revenue:</span>
                        <strong class="text-navy">₹{{ number_format($deliveredRevenue ?: $grossSales) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom font-xs">
                        <span class="text-muted"><i class="bi bi-boxes me-1"></i> Estimated Product COGS (40%):</span>
                        <strong class="text-muted">-₹{{ number_format($estimatedCOGS) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom font-xs">
                        <span class="text-muted"><i class="bi bi-calculator me-1"></i> Gross Product Margin:</span>
                        <strong class="text-navy">₹{{ number_format($grossProductMargin) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom font-xs">
                        <span class="text-muted"><i class="bi bi-dash-circle me-1"></i> Deduct RTO Shipping Losses:</span>
                        <strong class="text-danger">-₹{{ number_format($totalReturnFinancialLoss) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between pt-2 font-xs">
                        <strong class="text-success">Net Realized Profit in Pocket:</strong>
                        <strong class="text-success" style="font-size: 16.5px;">₹{{ number_format($netRealizedProfit) }} ({{ $netProfitMargin }}%)</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── 6. Customer COD Profiling Table ("Kon kitna COD kr rha hai, uska avg kya hai") ── --}}
    <div class="studio-panel-card">
        <div class="studio-panel-head">
            <div>
                <h3 class="studio-panel-title">
                    <i class="bi bi-people-fill text-primary"></i> High-Frequency COD Customers &amp; RTO Risk Profiling
                </h3>
                <span class="text-muted font-xs">Customer COD order frequency, total spend, ticket average (AOV), and return risk</span>
            </div>
        </div>

        <div style="overflow-x: auto;">
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
                                        <strong class="text-navy font-bold d-block">{{ $c->shipping_name ?: 'Anonymous Customer' }}</strong>
                                        <span class="text-muted font-xs"><i class="bi bi-telephone me-1"></i>{{ $c->shipping_phone ?: '—' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-navy border font-xs">
                                    <i class="bi bi-geo-alt-fill text-primary me-1"></i>{{ $c->shipping_city ?: '—' }}, {{ $c->shipping_state ?: 'India' }}
                                </span>
                            </td>
                            <td>
                                <strong class="text-navy">{{ $c->total_cod_orders }} orders</strong>
                            </td>
                            <td>
                                <span class="text-success font-bold"><i class="bi bi-check-circle-fill me-1"></i>{{ $c->delivered_count }}</span> &bull; 
                                <span class="{{ $c->returned_count > 0 ? 'text-danger font-bold' : 'text-muted' }}"><i class="bi bi-x-circle-fill me-1"></i>{{ $c->returned_count }} RTO</span>
                            </td>
                            <td>
                                <strong class="text-navy">₹{{ number_format($c->avg_cod_order_value) }}</strong>
                            </td>
                            <td>
                                <strong class="text-success" style="font-size: 13.5px;">₹{{ number_format($c->total_cod_spend) }}</strong>
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

    {{-- ── 7. Geographical Demand Matrix & Payment Mix ("Kha se aa rha hai") ── --}}
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
            </div>

            <div style="overflow-x: auto;">
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
                                <td><strong class="text-navy"><i class="bi bi-pin-map-fill text-danger me-1"></i>{{ $st->shipping_state }}</strong></td>
                                <td>{{ $st->orders_count }} orders</td>
                                <td>
                                    <div style="font-size: 11px;">
                                        <span class="text-warning font-bold">{{ $codStRatio }}% COD</span> &bull; 
                                        <span class="text-primary font-bold">{{ 100 - $codStRatio }}% Online</span>
                                    </div>
                                    <div style="height: 4px; background: #e2e8f0; border-radius: 4px; overflow: hidden; width: 100px; margin-top: 3px;">
                                        <div style="height: 100%; width: {{ $codStRatio }}%; background: #f59e0b;"></div>
                                    </div>
                                </td>
                                <td><strong class="text-navy">₹{{ number_format($st->state_revenue) }}</strong></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-3 text-muted font-xs">No regional state data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Top Hub Cities --}}
        <div class="studio-panel-card">
            <div class="studio-panel-head">
                <div>
                    <h3 class="studio-panel-title">
                        <i class="bi bi-buildings-fill text-primary"></i> Top Hub Cities
                    </h3>
                    <span class="text-muted font-xs">Cities generating highest volume</span>
                </div>
            </div>

            <div style="overflow-x: auto;">
                <table class="analytics-table">
                    <thead>
                        <tr>
                            <th>CITY &amp; STATE</th>
                            <th>ORDERS</th>
                            <th>COD COUNT</th>
                            <th>ONLINE COUNT</th>
                            <th>CITY REVENUE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topCitiesData as $ct)
                            <tr>
                                <td>
                                    <strong class="text-navy d-block"><i class="bi bi-building me-1"></i>{{ $ct->shipping_city }}</strong>
                                    <span class="text-muted font-xs">{{ $ct->shipping_state }}</span>
                                </td>
                                <td>{{ $ct->orders_count }}</td>
                                <td><span class="badge bg-warning-subtle text-warning border">{{ $ct->cod_count }}</span></td>
                                <td><span class="badge bg-primary-subtle text-primary border">{{ $ct->online_count }}</span></td>
                                <td><strong class="text-navy">₹{{ number_format($ct->city_revenue) }}</strong></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-3 text-muted font-xs">No city data available.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- ── 8. Velocity Curve Timeline Line Chart ── --}}
    <div class="studio-panel-card">
        <div class="studio-panel-head">
            <div>
                <h3 class="studio-panel-title">
                    <i class="bi bi-activity text-primary"></i> Revenue &amp; Order Velocity Curve
                </h3>
                <span class="text-muted font-xs">Daily gross revenue and order volume during {{ $rangeLabel }} &bull; Peak Window: <strong>{{ $peakHourLabel }}</strong></span>
            </div>
        </div>
        <div style="height: 280px; position: relative;">
            <canvas id="salesVelocityChart"></canvas>
        </div>
    </div>

</div>

{{-- Chart.js Script --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ── 1. Payment Donut Chart ──
        const ctxDonut = document.getElementById('paymentDonutChart').getContext('2d');
        const codRev = {{ (float) $codRevenue }};
        const prepaidRev = {{ (float) $prepaidRevenue }};

        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: ['Cash on Delivery (COD)', 'Online Prepaid (UPI/Cards)'],
                datasets: [{
                    data: [codRev || 1, prepaidRev || 0],
                    backgroundColor: ['#f59e0b', '#3b82f6'],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                return ctx.label + ': ₹' + Number(ctx.raw).toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // ── 2. Profit Waterfall Bar Chart ──
        const ctxWaterfall = document.getElementById('profitWaterfallChart').getContext('2d');
        new Chart(ctxWaterfall, {
            type: 'bar',
            data: {
                labels: ['Gross Delivered', 'Product COGS', 'RTO Freight Loss', 'Net Profit'],
                datasets: [{
                    label: 'Financial Flow (₹)',
                    data: [
                        {{ (float) ($deliveredRevenue ?: $grossSales) }},
                        {{ (float) $estimatedCOGS }},
                        {{ (float) $totalReturnFinancialLoss }},
                        {{ (float) $netRealizedProfit }}
                    ],
                    backgroundColor: [
                        '#3b82f6',
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
                                return ctx.label + ': ₹' + Number(ctx.raw).toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '700' } } },
                    y: {
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            callback: function(v) { return '₹' + (v >= 1000 ? (v/1000) + 'k' : v); },
                            font: { family: 'Plus Jakarta Sans', size: 10 }
                        }
                    }
                }
            }
        });

        // ── 3. Revenue & Order Velocity Line Chart ──
        const ctxVelocity = document.getElementById('salesVelocityChart').getContext('2d');
        const labels = @json($chartLabels);
        const revenueData = @json($chartRevenue);
        const ordersData = @json($chartOrderCount);

        new Chart(ctxVelocity, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Gross Revenue (₹)',
                        data: revenueData,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.08)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 4,
                        pointBackgroundColor: '#2563eb',
                        yAxisID: 'y',
                    },
                    {
                        label: 'Order Count',
                        data: ordersData,
                        borderColor: '#10b981',
                        backgroundColor: 'transparent',
                        borderDash: [4, 4],
                        tension: 0.35,
                        pointRadius: 3,
                        pointBackgroundColor: '#10b981',
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
                        labels: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '700' } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label.includes('Revenue')) {
                                    return label + ': ₹' + Number(context.raw).toLocaleString();
                                }
                                return label + ': ' + context.raw + ' orders';
                            }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 10.5 } } },
                    y: {
                        type: 'linear', display: true, position: 'left',
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            callback: function(value) { return '₹' + (value >= 1000 ? (value/1000) + 'k' : value); },
                            font: { family: 'Plus Jakarta Sans', size: 10 }
                        }
                    },
                    y1: {
                        type: 'linear', display: true, position: 'right',
                        grid: { drawOnChartArea: false },
                        ticks: { precision: 0, font: { family: 'Plus Jakarta Sans', size: 10 } }
                    }
                }
            }
        });
    });
</script>
@endsection
