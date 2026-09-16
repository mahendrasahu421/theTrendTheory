{{-- resources/views/admin/analytics/sales_dashboard.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Sales Analytics & Financial Intelligence')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

    :root {
        --studio-navy: #00285a;
        --studio-navy-dark: #071933;
        --studio-navy-light: #eff6ff;
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
        gap: 22px;
        color: var(--studio-text);
        max-width: 1440px;
        margin: 0 auto;
        padding-bottom: 70px;
    }

    /* ── 1. Top Header Action Bar ── */
    .sales-header-bar {
        background: #ffffff;
        border: 1px solid var(--studio-border);
        border-radius: 20px;
        padding: 22px 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 18px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
    }
    .sales-header-title-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .sales-header-title-row {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .sales-header-title {
        font-size: 23px;
        font-weight: 800;
        color: var(--studio-navy);
        letter-spacing: -0.4px;
        margin: 0;
        line-height: 1.2;
    }
    .sales-live-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
        padding: 4px 11px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.6px;
        text-transform: uppercase;
    }
    .pulse-dot-blue {
        width: 7px;
        height: 7px;
        background: #2563eb;
        border-radius: 50%;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25);
        animation: pulseLive 2s infinite;
    }
    @keyframes pulseLive {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.25); opacity: 0.7; }
    }
    .sales-header-sub {
        font-size: 13px;
        color: var(--studio-muted);
        margin: 0;
    }

    /* Filters Group in Header */
    .sales-filters-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .quick-pills-bar {
        display: inline-flex;
        align-items: center;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        padding: 4px;
        border-radius: 12px;
        gap: 3px;
    }
    .quick-pill {
        padding: 6px 13px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        text-decoration: none;
        transition: all 0.15s ease;
        white-space: nowrap;
    }
    .quick-pill:hover {
        color: #0f172a;
        background: rgba(255, 255, 255, 0.6);
    }
    .quick-pill.active {
        background: #00285a;
        color: #ffffff;
        box-shadow: 0 3px 10px rgba(0, 40, 90, 0.2);
    }
    .filter-dropdown-select {
        padding: 8px 14px;
        border: 1.5px solid var(--studio-border);
        border-radius: 11px;
        font-size: 12.5px;
        font-family: inherit;
        font-weight: 700;
        color: #334155;
        background: #ffffff;
        cursor: pointer;
        outline: none;
        transition: border-color 0.15s ease;
    }
    .filter-dropdown-select:focus {
        border-color: #00285a;
    }
    .btn-custom-date {
        background: #ffffff;
        color: #334155;
        border: 1.5px solid var(--studio-border);
        padding: 8px 15px;
        border-radius: 11px;
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: all 0.15s ease;
    }
    .btn-custom-date:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
    }
    .btn-custom-date.active {
        border-color: #00285a;
        color: #00285a;
        background: #eff6ff;
    }

    /* ── 2. Top 4 Master Bento Cards ── */
    .bento-master-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }
    @media (max-width: 1100px) {
        .bento-master-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 600px) {
        .bento-master-grid { grid-template-columns: 1fr; }
    }
    .bento-card {
        background: #ffffff;
        border: 1px solid var(--studio-border);
        border-radius: 18px;
        padding: 20px 22px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .bento-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
        border-color: #cbd5e1;
    }
    .bento-card-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .bento-card-label {
        font-size: 11.5px;
        font-weight: 800;
        color: var(--studio-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .bento-icon-box {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
    }
    .icon-glow-blue { background: #eff6ff; color: #2563eb; }
    .icon-glow-green { background: #ecfdf5; color: #059669; }
    .icon-glow-amber { background: #fffbeb; color: #d97706; }
    .icon-glow-purple { background: #faf5ff; color: #9333ea; }

    .bento-num {
        font-size: 26px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.15;
        margin-bottom: 4px;
        letter-spacing: -0.5px;
    }
    .bento-sub {
        font-size: 12px;
        color: var(--studio-muted);
    }

    /* Dual Progress Meters */
    .dual-meter-wrap {
        margin-top: 10px;
    }
    .dual-meter-track {
        height: 7px;
        background: #eff6ff;
        border-radius: 999px;
        display: flex;
        overflow: hidden;
    }
    .dual-meter-cod { background: #f59e0b; height: 100%; }
    .dual-meter-prepaid { background: #2563eb; height: 100%; }

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

    /* ── 3. Visual Panels ── */
    .two-col-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }
    @media (max-width: 992px) {
        .two-col-grid { grid-template-columns: 1fr; }
    }
    .studio-panel-card {
        background: #ffffff;
        border: 1px solid var(--studio-border);
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
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
        flex-wrap: wrap;
        gap: 8px;
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

    /* Metric Tiles Row */
    .metric-tiles-row {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 16px;
    }
    @media (max-width: 540px) {
        .metric-tiles-row { grid-template-columns: 1fr; }
    }
    .metric-tile {
        padding: 14px 16px;
        border-radius: 14px;
        border: 1px solid var(--studio-border);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .metric-tile-blue { background: #f8fbff; border-color: #dbeafe; }
    .metric-tile-green { background: #f0fdf4; border-color: #bbf7d0; }
    .metric-tile-amber { background: #fffdf5; border-color: #fde68a; }
    .metric-tile-rose { background: #fffbfb; border-color: #fecaca; }

    .tile-label { font-size: 11px; font-weight: 700; color: #64748b; margin-bottom: 2px; }
    .tile-num { font-size: 18px; font-weight: 800; color: #0f172a; }
    .tile-sub { font-size: 11.5px; font-weight: 600; margin-top: 2px; }

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
        letter-spacing: 0.6px;
        padding: 12px 14px;
        border-bottom: 1px solid #edf2f7;
        background: #fafcff;
        text-align: left;
    }
    .analytics-table td {
        padding: 13px 14px;
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

    .cust-avatar-pill {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        background: #eff6ff;
        color: #2563eb;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 12px;
        flex-shrink: 0;
    }

    /* Custom Date Modal */
    .date-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        backdrop-filter: blur(3px);
        z-index: 1050;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }
    .date-modal-overlay.active {
        display: flex;
    }
    .date-modal-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 24px;
        width: 100%;
        max-width: 420px;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.2);
    }
    .date-modal-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
    }
    .date-modal-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--studio-navy);
        margin: 0;
    }
    .form-label-custom {
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        margin-bottom: 6px;
        display: block;
    }
    .form-input-custom {
        width: 100%;
        padding: 9px 13px;
        border: 1.5px solid var(--studio-border);
        border-radius: 10px;
        font-size: 13px;
        font-family: inherit;
        outline: none;
    }
    .form-input-custom:focus {
        border-color: #00285a;
    }

    @media print {
        .sales-header-actions, .filter-segment-group, .quick-pills-bar, .filter-dropdown-select, .btn-custom-date {
            display: none !important;
        }
        body { background: white !important; }
        .sidebar { display: none !important; }
    }
</style>

<div class="sales-studio-root">

    {{-- ── 1. Top Executive Header Bar & Time Filter Suite ── --}}
    <div class="sales-header-bar">
        <div class="sales-header-title-group">
            <div class="sales-header-title-row">
                <h1 class="sales-header-title">Sales Analytics &amp; Revenue Intelligence</h1>
                <span class="sales-live-pill">
                    <span class="pulse-dot-blue"></span> Live Analytics
                </span>
            </div>
            <p class="sales-header-sub">
                Performance overview for <strong>{{ $rangeLabel }}</strong> &bull; Comparing vs previous cycle.
            </p>
        </div>

        <div class="sales-filters-wrap">
            {{-- Quick Filter Pills --}}
            <div class="quick-pills-bar">
                <a href="{{ route('admin.sales.analytics', ['range' => 'today']) }}" 
                   class="quick-pill {{ $range === 'today' ? 'active' : '' }}" 
                   title="Today's sales">
                    Today
                </a>
                <a href="{{ route('admin.sales.analytics', ['range' => 'this_week']) }}" 
                   class="quick-pill {{ $range === 'this_week' ? 'active' : '' }}" 
                   title="Current week (Mon–Sun)">
                    This Week
                </a>
                <a href="{{ route('admin.sales.analytics', ['range' => 'this_month']) }}" 
                   class="quick-pill {{ $range === 'this_month' ? 'active' : '' }}" 
                   title="Current month">
                    This Month
                </a>
                <a href="{{ route('admin.sales.analytics', ['range' => 'this_quarter']) }}" 
                   class="quick-pill {{ $range === 'this_quarter' ? 'active' : '' }}" 
                   title="Current quarter">
                    This Quarter
                </a>
                <a href="{{ route('admin.sales.analytics', ['range' => 'this_year']) }}" 
                   class="quick-pill {{ $range === 'this_year' ? 'active' : '' }}" 
                   title="Full current year">
                    This Year
                </a>
            </div>

            {{-- Comprehensive Dropdown Select --}}
            <select class="filter-dropdown-select" onchange="if(this.value) window.location.href=this.value;">
                <option value="" disabled selected>More Periods...</option>
                
                <optgroup label="Daily Filters">
                    <option value="{{ route('admin.sales.analytics', ['range' => 'today']) }}" {{ $range === 'today' ? 'selected' : '' }}>
                        Today
                    </option>
                    <option value="{{ route('admin.sales.analytics', ['range' => 'yesterday']) }}" {{ $range === 'yesterday' ? 'selected' : '' }}>
                        Yesterday
                    </option>
                </optgroup>

                <optgroup label="Weekly Filters">
                    <option value="{{ route('admin.sales.analytics', ['range' => 'this_week']) }}" {{ $range === 'this_week' ? 'selected' : '' }}>
                        This Week (Mon–Sun)
                    </option>
                    <option value="{{ route('admin.sales.analytics', ['range' => 'last_week']) }}" {{ $range === 'last_week' ? 'selected' : '' }}>
                        Last Week
                    </option>
                    <option value="{{ route('admin.sales.analytics', ['range' => '7days']) }}" {{ $range === '7days' ? 'selected' : '' }}>
                        Last 7 Days (Rolling)
                    </option>
                </optgroup>

                <optgroup label="Monthly Filters">
                    <option value="{{ route('admin.sales.analytics', ['range' => 'this_month']) }}" {{ $range === 'this_month' ? 'selected' : '' }}>
                        This Month
                    </option>
                    <option value="{{ route('admin.sales.analytics', ['range' => 'last_month']) }}" {{ $range === 'last_month' ? 'selected' : '' }}>
                        Last Month
                    </option>
                    <option value="{{ route('admin.sales.analytics', ['range' => '30days']) }}" {{ $range === '30days' ? 'selected' : '' }}>
                        Last 30 Days (Rolling)
                    </option>
                </optgroup>

                <optgroup label="Quarterly Filters">
                    <option value="{{ route('admin.sales.analytics', ['range' => 'this_quarter']) }}" {{ $range === 'this_quarter' ? 'selected' : '' }}>
                        This Quarter
                    </option>
                    <option value="{{ route('admin.sales.analytics', ['range' => 'last_quarter']) }}" {{ $range === 'last_quarter' ? 'selected' : '' }}>
                        Last Quarter
                    </option>
                </optgroup>

                <optgroup label="Yearly &amp; Lifetime">
                    <option value="{{ route('admin.sales.analytics', ['range' => 'this_year']) }}" {{ $range === 'this_year' ? 'selected' : '' }}>
                        This Year
                    </option>
                    <option value="{{ route('admin.sales.analytics', ['range' => 'last_year']) }}" {{ $range === 'last_year' ? 'selected' : '' }}>
                        Last Year
                    </option>
                    <option value="{{ route('admin.sales.analytics', ['range' => 'all_time']) }}" {{ $range === 'all_time' ? 'selected' : '' }}>
                        All Time (Lifetime)
                    </option>
                </optgroup>
            </select>

            {{-- Custom Date Range Button --}}
            <button type="button" 
                    class="btn-custom-date {{ ($customStart && $customEnd) ? 'active' : '' }}" 
                    onclick="openDateModal()" 
                    title="Choose custom date range">
                <i class="bi bi-calendar-range"></i> Custom Range
            </button>

            {{-- Print / Export Report --}}
            <button type="button" class="btn-custom-date" onclick="window.print()" title="Print / save report as PDF">
                <i class="bi bi-printer"></i>
            </button>
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
            <div class="bento-sub">{{ number_format($totalOrdersCount) }} orders booked in period</div>
            <div class="mt-2">
                <span class="trend-pill {{ $salesGrowth >= 0 ? 'trend-green' : 'trend-red' }}">
                    <i class="bi {{ $salesGrowth >= 0 ? 'bi-arrow-up-short' : 'bi-arrow-down-short' }}"></i> 
                    {{ abs($salesGrowth) }}% vs prev cycle
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
            <div class="bento-sub">Successful Doorstep Deliveries</div>
            <div class="mt-2">
                <span class="trend-pill trend-green">
                    <i class="bi bi-shield-check"></i> Retained Doorstep Cash
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
            <div class="bento-sub">₹{{ number_format($codRevenue) }} COD &bull; ₹{{ number_format($prepaidRevenue) }} Online</div>
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
            <div class="bento-sub">Net profit after COGS &amp; RTO Freight</div>
            <div class="mt-2">
                <span class="trend-pill trend-green" style="font-weight: 800;">
                    <i class="bi bi-check-circle-fill"></i> {{ $netProfitMargin }}% Operating Margin
                </span>
            </div>
        </div>
    </div>

    {{-- ── 3. Velocity Curve Timeline Line Chart ── --}}
    <div class="studio-panel-card">
        <div class="studio-panel-head">
            <div>
                <h3 class="studio-panel-title">
                    <i class="bi bi-activity text-primary"></i> Revenue &amp; Order Velocity Curve
                </h3>
                <span class="text-muted font-xs">
                    Gross revenue and order trajectory across {{ $rangeLabel }} &bull; Peak Shopping Hour: <strong>{{ $peakHourLabel }}</strong>
                </span>
            </div>
        </div>
        <div style="height: 290px; position: relative;">
            <canvas id="salesVelocityChart"></canvas>
        </div>
    </div>

    {{-- ── 4. Interactive Visual Graphs Row (Payment Donut + Profit vs Loss Bar) ── --}}
    <div class="two-col-grid">
        
        {{-- Graph 1: Payment Channel Split Doughnut Chart --}}
        <div class="studio-panel-card">
            <div class="studio-panel-head">
                <div>
                    <h3 class="studio-panel-title">
                        <i class="bi bi-pie-chart text-primary"></i> Payment Channel Split &amp; Conversion
                    </h3>
                    <span class="text-muted font-xs">Share of COD vs Online Prepaid Revenue</span>
                </div>
            </div>

            <div style="height: 220px; position: relative;" class="d-flex align-items-center justify-content-center">
                <canvas id="paymentDonutChart"></canvas>
            </div>

            <div class="row g-2 mt-3 pt-3 border-top text-center font-xs">
                <div class="col-6">
                    <span class="text-muted d-block"><i class="bi bi-circle-fill text-warning me-1"></i> Cash on Delivery:</span>
                    <strong style="color:#00285a; font-size:14px;">₹{{ number_format($codRevenue) }} ({{ $codRatio }}%)</strong>
                </div>
                <div class="col-6">
                    <span class="text-muted d-block"><i class="bi bi-circle-fill text-primary me-1"></i> Online Prepaid:</span>
                    <strong style="color:#00285a; font-size:14px;">₹{{ number_format($prepaidRevenue) }} ({{ $prepaidRatio }}%)</strong>
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

    {{-- ── 5. COD vs Prepaid Velocity & Conversion Tiles ── --}}
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
                    <span class="tile-sub text-muted">{{ number_format($codTotalCount) }} orders</span>
                </div>
                <div class="metric-tile metric-tile-green">
                    <span class="tile-label"><i class="bi bi-check2-circle me-1"></i> Cash Delivered</span>
                    <div class="tile-num" style="color:#059669;">{{ number_format($codDelivered) }}</div>
                    <span class="tile-sub text-success">{{ $codRealizationRate }}% Realized</span>
                </div>
                <div class="metric-tile metric-tile-rose">
                    <span class="tile-label"><i class="bi bi-x-octagon me-1"></i> Returned/RTO</span>
                    <div class="tile-num" style="color:#dc2626;">{{ number_format($codReturnCount) }}</div>
                    <span class="tile-sub text-danger">{{ $codReturnRate }}% RTO Rate</span>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3 font-xs">
                <div>
                    <span class="text-muted d-block">Average COD Ticket Size (AOV):</span>
                    <strong style="color:#00285a; font-size: 14.5px;">₹{{ number_format($codAov) }}</strong>
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
                    <strong style="color:#00285a; font-size: 14.5px;">₹{{ number_format($prepaidAov) }}</strong>
                </div>
                <div class="text-end">
                    <span class="text-muted d-block">Prepaid Realization:</span>
                    <strong class="text-success" style="font-size: 14.5px;">{{ $prepaidRealizationRate }}%</strong>
                </div>
            </div>
        </div>

    </div>

    {{-- ── 6. Returns & RTO Profit vs Loss Financial Accounting ── --}}
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
                        <strong style="color:#00285a;">{{ number_format($returnCount) }} parcels</strong>
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
                        <strong style="color:#00285a;">₹{{ number_format($deliveredRevenue ?: $grossSales) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom font-xs">
                        <span class="text-muted"><i class="bi bi-boxes me-1"></i> Estimated Product COGS (40%):</span>
                        <strong class="text-muted">-₹{{ number_format($estimatedCOGS) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom font-xs">
                        <span class="text-muted"><i class="bi bi-calculator me-1"></i> Gross Product Margin:</span>
                        <strong style="color:#00285a;">₹{{ number_format($grossProductMargin) }}</strong>
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

    {{-- ── 7. Customer COD Profiling Table ── --}}
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
                                        <strong style="color:#00285a; font-weight:700;" class="d-block">{{ $c->shipping_name ?: 'Customer' }}</strong>
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
                                <strong style="color:#00285a;">{{ $c->total_cod_orders }} orders</strong>
                            </td>
                            <td>
                                <span class="text-success font-bold"><i class="bi bi-check-circle-fill me-1"></i>{{ $c->delivered_count }}</span> &bull; 
                                <span class="{{ $c->returned_count > 0 ? 'text-danger font-bold' : 'text-muted' }}"><i class="bi bi-x-circle-fill me-1"></i>{{ $c->returned_count }} RTO</span>
                            </td>
                            <td>
                                <strong style="color:#00285a;">₹{{ number_format($c->avg_cod_order_value) }}</strong>
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

    {{-- ── 8. Geographical Demand Matrix & Top Bestsellers ── --}}
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
                                <td><strong style="color:#00285a;"><i class="bi bi-pin-map-fill text-danger me-1"></i>{{ $st->shipping_state }}</strong></td>
                                <td>{{ number_format($st->orders_count) }} orders</td>
                                <td>
                                    <div style="font-size: 11px;">
                                        <span class="text-warning font-bold">{{ $codStRatio }}% COD</span> &bull; 
                                        <span class="text-primary font-bold">{{ 100 - $codStRatio }}% Online</span>
                                    </div>
                                    <div style="height: 4px; background: #e2e8f0; border-radius: 4px; overflow: hidden; width: 100px; margin-top: 3px;">
                                        <div style="height: 100%; width: {{ $codStRatio }}%; background: #f59e0b;"></div>
                                    </div>
                                </td>
                                <td><strong style="color:#00285a;">₹{{ number_format($st->state_revenue) }}</strong></td>
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
            </div>

            <div style="overflow-x: auto;">
                <table class="analytics-table">
                    <thead>
                        <tr>
                            <th>ARTICLE</th>
                            <th>UNITS SOLD</th>
                            <th>TOTAL REVENUE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topProducts as $item)
                            <tr>
                                <td>
                                    <strong style="color:#00285a;" class="d-block text-truncate" style="max-width: 220px;">
                                        {{ $item->product_name ?: ($item->product->name ?? 'Article') }}
                                    </strong>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border font-xs font-bold">{{ number_format($item->total_qty) }} units</span>
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

{{-- ── Custom Date Range Modal ── --}}
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
                <button type="button" class="btn btn-sm btn-light border" onclick="closeDateModal()">Cancel</button>
                <button type="submit" class="btn btn-sm text-white" style="background:#00285a; font-weight:700; border-radius:9px; padding: 7px 18px;">
                    Apply Range
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
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
                    backgroundColor: ['#f59e0b', '#2563eb'],
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
                                return ctx.label + ': ₹' + Number(ctx.raw).toLocaleString('en-IN');
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
                            callback: function(v) { return '₹' + (v >= 1000 ? (v/1000) + 'k' : v); },
                            font: { family: 'Plus Jakarta Sans', size: 10 }
                        }
                    }
                }
            }
        });

        // ── 3. Revenue & Order Velocity Line Chart ──
        const ctxVelocity = document.getElementById('salesVelocityChart').getContext('2d');
        const labels = {!! json_encode($chartLabels) !!};
        const revenueData = {!! json_encode($chartRevenue) !!};
        const ordersData = {!! json_encode($chartOrderCount) !!};

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
                        label: 'Orders Count',
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
                                    return label + ': ₹' + Number(context.raw).toLocaleString('en-IN');
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
