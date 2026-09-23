{{-- resources/views/admin/dashboards/super_admin.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Executive Intelligence Dashboard')
@section('content')

@php
    $chartLabelsJson   = json_encode($chartData['labels'] ?? []);
    $chartRevenueJson  = json_encode($chartData['revenue'] ?? []);
    $chartOrdersJson   = json_encode($chartData['orders'] ?? []);
    $stLabelsJson      = $orderStatus->pluck('status')->toJson();
    $stCountsJson      = $orderStatus->pluck('count')->toJson();

    // Category Revenue Data for Donut Chart
    $catLabelsJson     = $categoryRevenue->pluck('name')->toJson();
    $catRevenuesJson   = $categoryRevenue->pluck('revenue')->map(fn($v) => (float)$v)->toJson();
    $totalCatRevenue   = (float) $categoryRevenue->sum('revenue');
@endphp

<div class="super-dash-container">

    {{-- ══════════════════════════════════════════════════════════════════
         1. EXECUTIVE HERO COMMAND BANNER (ANIMATED GRADIENT & LIVE STATUS)
         ══════════════════════════════════════════════════════════════════ --}}
    <div class="dash-hero-banner">
        <div class="hero-mesh-glow"></div>
        <div class="hero-content">
            <div class="hero-left">
                <div class="hero-badge-row">
                    <span class="live-pulse-chip">
                        <span class="pulse-dot"></span>
                        <span>SYSTEM LIVE &bull; IST</span>
                    </span>
                    <span class="hero-time-chip" id="liveClockTicker">
                        <i class="bi bi-clock-fill"></i> Loading clock...
                    </span>
                    <span class="hero-role-chip">
                        <i class="bi bi-shield-check"></i> SUPER ADMIN COCKPIT
                    </span>
                </div>
                <h1 class="hero-title">
                    Welcome Back, <span class="gradient-text">{{ (Auth::guard('admin')->user() ?? Auth::user())?->name ?? 'Executive Chief' }}</span>
                </h1>
                <p class="hero-sub">
                    Real-time omnichannel commerce telemetry, revenue intelligence &amp; fulfillment radar.
                </p>
            </div>

            <div class="hero-right">
                {{-- Timeframe Filter Switcher --}}
                <div class="timeframe-card">
                    <span class="timeframe-label"><i class="bi bi-funnel-fill"></i> TIME HORIZON</span>
                    <div class="timeframe-pills">
                        <a href="?filter=daily" class="tf-pill {{ $filter === 'daily' ? 'active' : '' }}">Today</a>
                        <a href="?filter=weekly" class="tf-pill {{ $filter === 'weekly' ? 'active' : '' }}">Weekly</a>
                        <a href="?filter=monthly" class="tf-pill {{ $filter === 'monthly' ? 'active' : '' }}">Monthly</a>
                        <a href="?filter=quarterly" class="tf-pill {{ $filter === 'quarterly' ? 'active' : '' }}">Quarterly</a>
                        <a href="?filter=yearly" class="tf-pill {{ $filter === 'yearly' ? 'active' : '' }}">Yearly</a>
                    </div>
                </div>

                <button type="button" class="btn-hero-refresh" onclick="window.location.reload();" title="Refresh Live Data">
                    <i class="bi bi-arrow-clockwise"></i> Sync Radar
                </button>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════
         2. URGENT INVENTORY & STOCK ALERT BANNER (IF ANY)
         ══════════════════════════════════════════════════════════════════ --}}
    @if ($lowStockCount > 0 || $outOfStockCount > 0)
        <div class="urgent-alert-card animate-pulse-border">
            <div class="alert-left">
                <div class="alert-icon-wrap">
                    <i class="bi bi-exclamation-octagon-fill"></i>
                </div>
                <div>
                    <div class="alert-headline">
                        CRITICAL INVENTORY ALERT: {{ $lowStockCount }} Low Stock &bull; {{ $outOfStockCount }} Out of Stock!
                    </div>
                    <div class="alert-desc">
                        Storefront SKUs have dipped below safety thresholds. Restock immediately to protect conversion rates and avoid lost sales.
                    </div>
                </div>
            </div>
            <div class="alert-right">
                <a href="{{ route('admin.inventory.index', ['status' => 'low_stock']) }}" class="btn-alert-action">
                    <i class="bi bi-lightning-charge-fill"></i> 1-Click Restock Radar &rarr;
                </a>
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════════
         3. TOP 8 ANIMATED KPI STAT CARDS (WITH COUNT-UP & HOVER GLOW)
         ══════════════════════════════════════════════════════════════════ --}}
    <div class="dash-section-head">
        <div class="section-title-wrap">
            <span class="section-indicator"></span>
            <h2>Executive Core Performance Indicators</h2>
        </div>
        <span class="section-meta-tag"><i class="bi bi-cursor-fill"></i> Interactive Cards &bull; Click to Inspect Details</span>
    </div>

    <div class="kpi-cards-grid">

        {{-- 1. Lifetime Revenue --}}
        <a href="{{ route('admin.orders.index') }}" class="kpi-card-box theme-navy">
            <div class="kpi-glass">
                <div class="kpi-header">
                    <span class="kpi-tag">TOTAL SALES (LIFETIME)</span>
                    <div class="kpi-icon-bubble">
                        <i class="bi bi-currency-rupee"></i>
                    </div>
                </div>
                <div class="kpi-number-wrap">
                    <span class="kpi-currency">₹</span>
                    <span class="kpi-val count-up" data-val="{{ (int)$totalSalesAllTime }}">{{ number_format($totalSalesAllTime) }}</span>
                </div>
                <div class="kpi-footer">
                    <span class="kpi-badge badge-success"><i class="bi bi-shield-check"></i> Paid Volume</span>
                    <span class="kpi-link-arrow"><i class="bi bi-arrow-right"></i></span>
                </div>
            </div>
            <div class="kpi-bottom-bar bar-navy"></div>
        </a>

        {{-- 2. Today's Revenue --}}
        <a href="{{ route('admin.orders.index') }}" class="kpi-card-box theme-emerald">
            <div class="kpi-glass">
                <div class="kpi-header">
                    <span class="kpi-tag">TODAY'S REVENUE</span>
                    <div class="kpi-icon-bubble">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                </div>
                <div class="kpi-number-wrap">
                    <span class="kpi-currency">₹</span>
                    <span class="kpi-val count-up text-emerald" data-val="{{ (int)$todayRevenue }}">{{ number_format($todayRevenue) }}</span>
                </div>
                <div class="kpi-footer">
                    <span class="kpi-badge badge-emerald"><i class="bi bi-clock-history"></i> Real-time Today</span>
                    <span class="kpi-link-arrow"><i class="bi bi-arrow-right"></i></span>
                </div>
            </div>
            <div class="kpi-bottom-bar bar-emerald"></div>
        </a>

        {{-- 3. Total Orders --}}
        <a href="{{ route('admin.orders.index') }}" class="kpi-card-box theme-indigo">
            <div class="kpi-glass">
                <div class="kpi-header">
                    <span class="kpi-tag">TOTAL ORDERS</span>
                    <div class="kpi-icon-bubble">
                        <i class="bi bi-bag-check-fill"></i>
                    </div>
                </div>
                <div class="kpi-number-wrap">
                    <span class="kpi-val count-up text-indigo" data-val="{{ $totalOrdersCount }}">{{ number_format($totalOrdersCount) }}</span>
                </div>
                <div class="kpi-footer">
                    <span class="kpi-badge badge-indigo"><i class="bi bi-cart-check"></i> {{ $pendingOrdersCount }} pending</span>
                    <span class="kpi-link-arrow"><i class="bi bi-arrow-right"></i></span>
                </div>
            </div>
            <div class="kpi-bottom-bar bar-indigo"></div>
        </a>

        {{-- 4. Total Customers --}}
        <a href="{{ route('admin.customers.index') }}" class="kpi-card-box theme-purple">
            <div class="kpi-glass">
                <div class="kpi-header">
                    <span class="kpi-tag">ACTIVE CUSTOMERS</span>
                    <div class="kpi-icon-bubble">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
                <div class="kpi-number-wrap">
                    <span class="kpi-val count-up text-purple" data-val="{{ $totalCustomersCount }}">{{ number_format($totalCustomersCount) }}</span>
                </div>
                <div class="kpi-footer">
                    <span class="kpi-badge badge-purple">+{{ $newCustomers }} new this period</span>
                    <span class="kpi-link-arrow"><i class="bi bi-arrow-right"></i></span>
                </div>
            </div>
            <div class="kpi-bottom-bar bar-purple"></div>
        </a>

        {{-- 5. Average Order Value (AOV) --}}
        <a href="{{ route('admin.orders.index') }}" class="kpi-card-box theme-amber">
            <div class="kpi-glass">
                <div class="kpi-header">
                    <span class="kpi-tag">AVG ORDER VALUE (AOV)</span>
                    <div class="kpi-icon-bubble">
                        <i class="bi bi-calculator-fill"></i>
                    </div>
                </div>
                <div class="kpi-number-wrap">
                    <span class="kpi-currency">₹</span>
                    <span class="kpi-val count-up text-amber" data-val="{{ (int)$aov }}">{{ number_format($aov) }}</span>
                </div>
                <div class="kpi-footer">
                    <span class="kpi-badge badge-amber"><i class="bi bi-graph-up"></i> Per Paid Order</span>
                    <span class="kpi-link-arrow"><i class="bi bi-arrow-right"></i></span>
                </div>
            </div>
            <div class="kpi-bottom-bar bar-amber"></div>
        </a>

        {{-- 6. Customer Lifetime Value (LTV) --}}
        <a href="{{ route('admin.customers.index') }}" class="kpi-card-box theme-cyan">
            <div class="kpi-glass">
                <div class="kpi-header">
                    <span class="kpi-tag">ESTIMATED LTV</span>
                    <div class="kpi-icon-bubble">
                        <i class="bi bi-award-fill"></i>
                    </div>
                </div>
                <div class="kpi-number-wrap">
                    <span class="kpi-currency">₹</span>
                    <span class="kpi-val count-up text-cyan" data-val="{{ (int)$ltv }}">{{ number_format($ltv) }}</span>
                </div>
                <div class="kpi-footer">
                    <span class="kpi-badge badge-cyan"><i class="bi bi-repeat"></i> {{ $repeatRate }}% Repeat Rate</span>
                    <span class="kpi-link-arrow"><i class="bi bi-arrow-right"></i></span>
                </div>
            </div>
            <div class="kpi-bottom-bar bar-cyan"></div>
        </a>

        {{-- 7. Delivered / Fulfilled --}}
        <a href="{{ route('admin.orders.index') }}" class="kpi-card-box theme-teal">
            <div class="kpi-glass">
                <div class="kpi-header">
                    <span class="kpi-tag">DELIVERED ORDERS</span>
                    <div class="kpi-icon-bubble">
                        <i class="bi bi-truck"></i>
                    </div>
                </div>
                <div class="kpi-number-wrap">
                    <span class="kpi-val count-up text-teal" data-val="{{ $deliveredOrdersCount }}">{{ number_format($deliveredOrdersCount) }}</span>
                </div>
                <div class="kpi-footer">
                    <span class="kpi-badge badge-teal">
                        <i class="bi bi-check2-circle"></i> {{ $totalOrdersCount > 0 ? round(($deliveredOrdersCount / $totalOrdersCount)*100) : 0 }}% Success
                    </span>
                    <span class="kpi-link-arrow"><i class="bi bi-arrow-right"></i></span>
                </div>
            </div>
            <div class="kpi-bottom-bar bar-teal"></div>
        </a>

        {{-- 8. Low Stock Alerts --}}
        <a href="{{ route('admin.inventory.index', ['status' => 'low_stock']) }}" class="kpi-card-box theme-rose">
            <div class="kpi-glass">
                <div class="kpi-header">
                    <span class="kpi-tag">STOCK ALERT RADAR</span>
                    <div class="kpi-icon-bubble">
                        <i class="bi bi-shield-exclamation"></i>
                    </div>
                </div>
                <div class="kpi-number-wrap">
                    <span class="kpi-val count-up text-rose" data-val="{{ $lowStockCount + $outOfStockCount }}">{{ number_format($lowStockCount + $outOfStockCount) }}</span>
                </div>
                <div class="kpi-footer">
                    <span class="kpi-badge badge-rose">{{ $outOfStockCount }} Out of Stock</span>
                    <span class="kpi-link-arrow"><i class="bi bi-arrow-right"></i></span>
                </div>
            </div>
            <div class="kpi-bottom-bar bar-rose"></div>
        </a>

    </div>

    {{-- ══════════════════════════════════════════════════════════════════
         4. EXECUTIVE UNIT ECONOMICS & MARGINS RIBBON
         ══════════════════════════════════════════════════════════════════ --}}
    <div class="economics-ribbon-card">
        <div class="econ-item">
            <div class="econ-icon-wrap bg-blue-subtle text-primary">
                <i class="bi bi-percent"></i>
            </div>
            <div class="econ-meta">
                <span class="econ-label">GROSS MARGIN</span>
                <strong class="econ-val">{{ $grossMargin }}%</strong>
                <small class="econ-sub">Product margin after COGS</small>
            </div>
        </div>

        <div class="econ-sep"></div>

        <div class="econ-item">
            <div class="econ-icon-wrap bg-emerald-subtle text-success">
                <i class="bi bi-pie-chart-fill"></i>
            </div>
            <div class="econ-meta">
                <span class="econ-label">PROJECTED EBITDA</span>
                <strong class="econ-val text-emerald">{{ $ebitda }}%</strong>
                <small class="econ-sub">Operating profit estimate</small>
            </div>
        </div>

        <div class="econ-sep"></div>

        <div class="econ-item">
            <div class="econ-icon-wrap bg-purple-subtle text-purple">
                <i class="bi bi-arrow-repeat"></i>
            </div>
            <div class="econ-meta">
                <span class="econ-label">REPEAT CUSTOMER RATE</span>
                <strong class="econ-val text-purple">{{ $repeatRate }}%</strong>
                <small class="econ-sub">Multi-order loyalty index</small>
            </div>
        </div>

        <div class="econ-sep"></div>

        <div class="econ-item">
            <div class="econ-icon-wrap bg-amber-subtle text-amber">
                <i class="bi bi-calendar2-week-fill"></i>
            </div>
            <div class="econ-meta">
                <span class="econ-label">THIS WEEK'S SALES</span>
                <strong class="econ-val text-navy">₹{{ number_format($thisWeekRevenue) }}</strong>
                <small class="econ-sub">Current 7-day velocity</small>
            </div>
        </div>

        <div class="econ-sep"></div>

        <div class="econ-item">
            <div class="econ-icon-wrap bg-indigo-subtle text-indigo">
                <i class="bi bi-calendar3"></i>
            </div>
            <div class="econ-meta">
                <span class="econ-label">THIS MONTH'S SALES</span>
                <strong class="econ-val text-indigo">₹{{ number_format($thisMonthRevenue) }}</strong>
                <small class="econ-sub">Current month volume</small>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════
         5. DUAL ANALYTICS: SALES TRENDS (68%) + CATEGORY REVENUE (32%)
         ══════════════════════════════════════════════════════════════════ --}}
    <div class="dash-grid-split">

        {{-- Main Revenue & Order Timeline Chart --}}
        <div class="dash-panel card-timeline">
            <div class="panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-icon-bubble bg-navy">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <div>
                        <h3 class="panel-title">Sales &amp; Transaction Volume Dynamics</h3>
                        <p class="panel-sub">
                            Dual-axis trajectory &bull; {{ $filter === 'daily' ? 'Hourly transactions today' : ($filter === 'weekly' ? 'Daily breakdown this week' : ($filter === 'yearly' ? 'Monthly volume this year' : 'Daily volume this month')) }}
                        </p>
                    </div>
                </div>

                <div class="timeline-legend">
                    <div class="legend-badge">
                        <span class="legend-dot dot-navy"></span>
                        <span>Revenue (₹)</span>
                    </div>
                    <div class="legend-badge">
                        <span class="legend-dot dot-emerald"></span>
                        <span>Orders Count</span>
                    </div>
                    <div class="growth-badge {{ $revGrowth >= 0 ? 'growth-pos' : 'growth-neg' }}">
                        <i class="bi bi-arrow-{{ $revGrowth >= 0 ? 'up' : 'down' }}-right"></i>
                        {{ abs($revGrowth) }}% vs prior period
                    </div>
                </div>
            </div>

            <div class="panel-body">
                <div class="chart-canvas-container">
                    <canvas id="revChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Category Revenue Breakdown --}}
        <div class="dash-panel card-categories">
            <div class="panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-icon-bubble bg-purple">
                        <i class="bi bi-grid-1x2-fill"></i>
                    </div>
                    <div>
                        <h3 class="panel-title">Category Revenue</h3>
                        <p class="panel-sub">Share by main catalog lines</p>
                    </div>
                </div>
            </div>

            <div class="panel-body d-flex flex-column justify-content-between">
                <div class="category-donut-wrap">
                    <canvas id="categoryDonutChart"></canvas>
                    <div class="donut-center-stat">
                        <span class="center-title">CATALOG</span>
                        <strong class="center-val">₹{{ number_format($totalCatRevenue) }}</strong>
                    </div>
                </div>

                <div class="category-progress-list">
                    @forelse($categoryRevenue->take(4) as $idx => $cat)
                        @php
                            $pct = $totalCatRevenue > 0 ? round(($cat->revenue / $totalCatRevenue) * 100) : 0;
                            $catColors = ['#00285a', '#10b981', '#a855f7', '#f59e0b', '#3b82f6'];
                            $col = $catColors[$idx % count($catColors)];
                        @endphp
                        <div class="cat-bar-item">
                            <div class="cat-bar-meta">
                                <div class="cat-name-tag">
                                    <span class="cat-dot" style="background: {{ $col }};"></span>
                                    <span>{{ $cat->name }}</span>
                                </div>
                                <div class="cat-val-tag">
                                    <strong>₹{{ number_format($cat->revenue) }}</strong>
                                    <small>({{ $pct }}%)</small>
                                </div>
                            </div>
                            <div class="cat-track">
                                <div class="cat-fill" style="width: {{ $pct }}%; background: {{ $col }};"></div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state-muted">No category revenue data recorded in this timeframe.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════════════
         6. RECENT ORDERS TABLE (68%) + FULFILLMENT PIPELINE (32%)
         ══════════════════════════════════════════════════════════════════ --}}
    <div class="dash-grid-split mt-4">

        {{-- Recent Orders Interactive Table --}}
        <div class="dash-panel card-table">
            <div class="panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-icon-bubble bg-indigo">
                        <i class="bi bi-receipt-cutoff"></i>
                    </div>
                    <div>
                        <h3 class="panel-title">Recent Transactions Radar</h3>
                        <p class="panel-sub">Latest consumer store purchases &bull; Click row for full order view</p>
                    </div>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="panel-view-all">
                    View All Orders <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="table-responsive-wrapper">
                <table class="modern-dash-table">
                    <thead>
                        <tr>
                            <th>ORDER ID</th>
                            <th>CUSTOMER</th>
                            <th>AMOUNT</th>
                            <th>PAYMENT</th>
                            <th>FULFILLMENT</th>
                            <th>TIMESTAMP</th>
                            <th style="text-align:right;">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $ord)
                            <tr class="modern-row" onclick="window.location='{{ route('admin.orders.index') }}'">
                                <td>
                                    <span class="order-id-pill">
                                        #{{ $ord->order_number ?? $ord->id }}
                                    </span>
                                </td>
                                <td>
                                    <div class="cust-info-cell">
                                        <div class="avatar-letter">
                                            {{ strtoupper(substr($ord->user->name ?? $ord->billing_name ?? 'G', 0, 1)) }}
                                        </div>
                                        <div class="cust-names">
                                            <span class="cust-main-name">{{ $ord->user->name ?? $ord->billing_name ?? 'Guest Buyer' }}</span>
                                            <span class="cust-sub-email">{{ $ord->user->email ?? $ord->billing_email ?? 'Online Checkout' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="order-amt-val">₹{{ number_format($ord->total_amount) }}</div>
                                </td>
                                <td>
                                    @if($ord->payment_status === 'paid')
                                        <span class="pay-chip pay-paid"><i class="bi bi-check-circle-fill"></i> Paid</span>
                                    @elseif($ord->payment_status === 'failed')
                                        <span class="pay-chip pay-failed"><i class="bi bi-x-circle-fill"></i> Failed</span>
                                    @else
                                        <span class="pay-chip pay-pending"><i class="bi bi-clock-fill"></i> Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="status-chip chip-{{ $ord->status }}">
                                        {{ ucfirst($ord->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="time-ago-text">{{ $ord->created_at->diffForHumans() }}</span>
                                </td>
                                <td style="text-align:right;">
                                    <span class="row-arrow-icon"><i class="bi bi-chevron-right"></i></span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-state-muted py-4">No recent orders registered yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Order Fulfillment Lifecycle Radar --}}
        <div class="dash-panel card-fulfillment">
            <div class="panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-icon-bubble bg-teal">
                        <i class="bi bi-pie-chart-fill"></i>
                    </div>
                    <div>
                        <h3 class="panel-title">Fulfillment Pipeline</h3>
                        <p class="panel-sub">Order journey &amp; logistics breakdown</p>
                    </div>
                </div>
            </div>

            <div class="panel-body">
                <div class="donut-container-status">
                    <canvas id="statusDoughnutChart"></canvas>
                </div>

                <div class="fulfillment-meter-list">
                    @php
                        $stMap = [
                            'pending'    => ['label' => 'Pending',    'color' => '#f97316', 'count' => $pendingOrdersCount],
                            'processing' => ['label' => 'Processing', 'color' => '#a855f7', 'count' => $processingCount],
                            'shipped'    => ['label' => 'In Transit',  'color' => '#3b82f6', 'count' => $shippedCount],
                            'delivered'  => ['label' => 'Delivered',   'color' => '#10b981', 'count' => $deliveredOrdersCount],
                            'cancelled'  => ['label' => 'Cancelled',   'color' => '#ef4444', 'count' => $cancelledCount],
                            'returned'   => ['label' => 'Returned',    'color' => '#64748b', 'count' => $returnedCount],
                        ];
                    @endphp

                    @foreach($stMap as $stKey => $stData)
                        @php
                            $sharePct = $totalOrdersCount > 0 ? round(($stData['count'] / $totalOrdersCount) * 100) : 0;
                        @endphp
                        <a href="{{ route('admin.orders.index') }}" class="meter-row-btn" title="Filter {{ $stData['label'] }} orders">
                            <div class="meter-row-left">
                                <span class="status-marker" style="background: {{ $stData['color'] }};"></span>
                                <span class="status-name">{{ $stData['label'] }}</span>
                            </div>
                            <div class="meter-row-right">
                                <div class="meter-mini-track">
                                    <div class="meter-mini-fill" style="width: {{ $sharePct }}%; background: {{ $stData['color'] }};"></div>
                                </div>
                                <span class="meter-num">{{ $stData['count'] }}</span>
                                <i class="bi bi-chevron-right meter-chevron"></i>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════════════
         7. TOP PERFORMING DROPS (50%) + INVENTORY RESTOCK RADAR (50%)
         ══════════════════════════════════════════════════════════════════ --}}
    <div class="dash-grid-halves mt-4">

        {{-- Best Sellers / Top Performing Drops --}}
        <div class="dash-panel">
            <div class="panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-icon-bubble bg-amber">
                        <i class="bi bi-fire"></i>
                    </div>
                    <div>
                        <h3 class="panel-title">Top Performing Drops</h3>
                        <p class="panel-sub">Volume leaders across all campaigns</p>
                    </div>
                </div>
                <a href="{{ route('admin.products.index') }}" class="panel-view-all">All Products &rarr;</a>
            </div>

            <div class="table-responsive-wrapper">
                <table class="modern-dash-table">
                    <thead>
                        <tr>
                            <th>PRODUCT</th>
                            <th>CATEGORY</th>
                            <th>VOLUME SOLD</th>
                            <th>PRICE</th>
                            <th style="text-align:right;">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $tp)
                            <tr class="modern-row" onclick="window.location='{{ route('admin.products.edit', $tp) }}'">
                                <td>
                                    <div class="prod-profile-cell">
                                        <div class="prod-thumb-box">
                                            <img src="{{ $tp->card_image ?? $tp->image ?? asset('images/placeholder-product.jpg') }}" alt="" onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                                        </div>
                                        <div class="prod-meta">
                                            <strong class="prod-title">{{ Str::limit($tp->name, 26) }}</strong>
                                            <small class="prod-sku">{{ $tp->sku ?? 'No SKU' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="cat-pill-badge">{{ $tp->category->name ?? 'General' }}</span>
                                </td>
                                <td>
                                    <span class="volume-sold-chip"><i class="bi bi-cart-check-fill text-success"></i> {{ $tp->total_sold ?? 0 }} units</span>
                                </td>
                                <td>
                                    <strong class="text-navy font-mono">₹{{ number_format($tp->price) }}</strong>
                                </td>
                                <td style="text-align:right;">
                                    <a href="{{ route('admin.products.edit', $tp) }}" class="btn-micro-edit" onclick="event.stopPropagation();" title="Edit Product">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state-muted py-4">No product sales records yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Urgent Stock Radar --}}
        <div class="dash-panel">
            <div class="panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-icon-bubble bg-rose">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div>
                        <h3 class="panel-title">Urgent Inventory Depletion Radar</h3>
                        <p class="panel-sub">SKUs approaching zero stock &bull; 1-Click Restock</p>
                    </div>
                </div>
                <a href="{{ route('admin.inventory.index', ['status' => 'low_stock']) }}" class="panel-view-all">Inventory Hub &rarr;</a>
            </div>

            <div class="table-responsive-wrapper">
                <table class="modern-dash-table">
                    <thead>
                        <tr>
                            <th>PRODUCT</th>
                            <th>SKU</th>
                            <th>IN-STOCK</th>
                            <th style="text-align:right;">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lowStockProductsList as $lsp)
                            <tr class="modern-row" onclick="window.location='{{ route('admin.products.edit', $lsp) }}'">
                                <td>
                                    <div class="prod-profile-cell">
                                        <div class="prod-thumb-box">
                                            <img src="{{ $lsp->card_image ?? $lsp->image ?? asset('images/placeholder-product.jpg') }}" alt="" onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                                        </div>
                                        <strong class="prod-title">{{ Str::limit($lsp->name, 26) }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <code class="sku-mono-code">{{ $lsp->sku ?? '—' }}</code>
                                </td>
                                <td>
                                    @if($lsp->stock <= 0)
                                        <span class="stock-status-pill pill-zero">OUT OF STOCK</span>
                                    @else
                                        <span class="stock-status-pill pill-low">{{ $lsp->stock }} left</span>
                                    @endif
                                </td>
                                <td style="text-align:right;">
                                    <a href="{{ route('admin.products.edit', $lsp) }}" class="btn-restock-instant" onclick="event.stopPropagation();">
                                        <i class="bi bi-plus-lg"></i> Restock
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="empty-state-muted py-4">
                                    <i class="bi bi-check2-circle text-success me-1"></i> All products have healthy stock inventory levels!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════════════
         8. OPERATIONS LAUNCHPAD (50%) + SYSTEM AUDIT FEED (50%)
         ══════════════════════════════════════════════════════════════════ --}}
    <div class="dash-grid-halves mt-4 mb-4">

        {{-- Quick Operations Launchpad --}}
        <div class="dash-panel">
            <div class="panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-icon-bubble bg-cyan">
                        <i class="bi bi-grid-fill"></i>
                    </div>
                    <div>
                        <h3 class="panel-title">Operations Quick Launchpad</h3>
                        <p class="panel-sub">Instant shortcuts to daily executive operations</p>
                    </div>
                </div>
            </div>

            <div class="panel-body">
                <div class="launchpad-grid">
                    <a href="{{ route('admin.products.create') }}" class="launch-card theme-indigo">
                        <div class="launch-icon-box">
                            <i class="bi bi-plus-circle-fill"></i>
                        </div>
                        <span class="launch-title">Add Product</span>
                        <small class="launch-sub">New SKU drop</small>
                    </a>

                    <a href="{{ route('admin.categories.index') }}" class="launch-card theme-purple">
                        <div class="launch-icon-box">
                            <i class="bi bi-folder-plus"></i>
                        </div>
                        <span class="launch-title">Categories</span>
                        <small class="launch-sub">Taxonomy tree</small>
                    </a>

                    <a href="{{ route('admin.coupons.index') }}" class="launch-card theme-amber">
                        <div class="launch-icon-box">
                            <i class="bi bi-ticket-perforated-fill"></i>
                        </div>
                        <span class="launch-title">Discount Deals</span>
                        <small class="launch-sub">Promo coupons</small>
                    </a>

                    <a href="{{ route('admin.orders.index') }}" class="launch-card theme-emerald">
                        <div class="launch-icon-box">
                            <i class="bi bi-bag-check-fill"></i>
                        </div>
                        <span class="launch-title">Order Shipments</span>
                        <small class="launch-sub">Fulfillment queue</small>
                    </a>

                    <a href="{{ route('admin.notifications.index') }}" class="launch-card theme-rose">
                        <div class="launch-icon-box">
                            <i class="bi bi-megaphone-fill"></i>
                        </div>
                        <span class="launch-title">Send Broadcast</span>
                        <small class="launch-sub">Push notifications</small>
                    </a>

                    <a href="{{ route('admin.staff.index') }}" class="launch-card theme-cyan">
                        <div class="launch-icon-box">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <span class="launch-title">Staff &amp; Roles</span>
                        <small class="launch-sub">Access control</small>
                    </a>
                </div>
            </div>
        </div>

        {{-- Real-Time System Notification Logs --}}
        <div class="dash-panel">
            <div class="panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-icon-bubble bg-rose">
                        <i class="bi bi-bell-fill"></i>
                    </div>
                    <div>
                        <h3 class="panel-title">System Activity Stream</h3>
                        <p class="panel-sub">Automated event logging &amp; notifications</p>
                    </div>
                </div>
                <a href="{{ route('admin.notifications.index') }}" class="panel-view-all">All Logs &rarr;</a>
            </div>

            <div class="panel-body">
                <div class="activity-timeline">
                    @forelse($recentNotifications as $notif)
                        <div class="timeline-event-row">
                            <div class="event-icon-circle">
                                <i class="bi bi-bell-fill"></i>
                            </div>
                            <div class="event-body">
                                <div class="event-top-line">
                                    <strong class="event-title">{{ $notif->title }}</strong>
                                    <span class="event-time">{{ $notif->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="event-msg">{{ Str::limit($notif->message, 85) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state-muted py-4">No recent system notifications recorded.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</div>

{{-- ══════════════════════════════════════════════════════════════════
     MODERN ANIMATED DASHBOARD STYLES (GLASSMORPHISM & MICRO-INTERACTIONS)
     ══════════════════════════════════════════════════════════════════ --}}
<style>
/* Base Container */
.super-dash-container {
    padding: 6px 4px 28px;
    display: flex;
    flex-direction: column;
    gap: 20px;
    animation: fadeInDashboard 0.4s ease-out;
}

@keyframes fadeInDashboard {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ── 1. Hero Command Banner ── */
.dash-hero-banner {
    position: relative;
    background: linear-gradient(135deg, #09172e 0%, #102440 50%, #00285a 100%);
    border-radius: 20px;
    padding: 26px 30px;
    color: #ffffff;
    box-shadow: 0 16px 36px -10px rgba(0, 25, 60, 0.45), inset 0 1px 0 rgba(255, 255, 255, 0.12);
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.hero-mesh-glow {
    position: absolute;
    top: -60px;
    right: -40px;
    width: 320px;
    height: 320px;
    background: radial-gradient(circle, rgba(56, 189, 248, 0.22) 0%, rgba(37, 99, 235, 0.1) 50%, transparent 75%);
    border-radius: 50%;
    filter: blur(40px);
    pointer-events: none;
    animation: pulseMesh 8s ease-in-out infinite alternate;
}

@keyframes pulseMesh {
    0% { transform: scale(1) translate(0, 0); opacity: 0.7; }
    100% { transform: scale(1.25) translate(-30px, 20px); opacity: 1; }
}

.hero-content {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    flex-wrap: wrap;
}

.hero-badge-row {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 12px;
}

.live-pulse-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(16, 185, 129, 0.16);
    border: 1px solid rgba(16, 185, 129, 0.35);
    color: #34d399;
    font-size: 11px;
    font-weight: 800;
    padding: 3px 10px;
    border-radius: 999px;
    letter-spacing: 0.5px;
}

.pulse-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #10b981;
    box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
    animation: liveDotPulse 1.8s infinite;
}

@keyframes liveDotPulse {
    0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
    100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

.hero-time-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: #e2e8f0;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 999px;
    font-variant-numeric: tabular-nums;
}

.hero-role-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: rgba(99, 102, 241, 0.2);
    border: 1px solid rgba(129, 140, 248, 0.35);
    color: #a5b4fc;
    font-size: 11px;
    font-weight: 800;
    padding: 3px 10px;
    border-radius: 999px;
    letter-spacing: 0.4px;
}

.hero-title {
    font-size: 26px;
    font-weight: 800;
    line-height: 1.25;
    margin-bottom: 6px;
    letter-spacing: -0.5px;
}

.gradient-text {
    background: linear-gradient(90deg, #60a5fa 0%, #38bdf8 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero-sub {
    font-size: 13.5px;
    color: #94a3b8;
    margin-bottom: 0;
    max-width: 560px;
}

.hero-right {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
}

.timeframe-card {
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.14);
    border-radius: 14px;
    padding: 6px 8px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.timeframe-label {
    font-size: 9.5px;
    font-weight: 800;
    color: #94a3b8;
    letter-spacing: 0.6px;
    padding-left: 6px;
}

.timeframe-pills {
    display: flex;
    align-items: center;
    gap: 4px;
}

.tf-pill {
    font-size: 11.5px;
    font-weight: 700;
    color: #cbd5e1;
    padding: 6px 12px;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.2s ease;
}

.tf-pill:hover {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.09);
}

.tf-pill.active {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
}

.btn-hero-refresh {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.18);
    color: #ffffff;
    font-size: 12px;
    font-weight: 700;
    padding: 10px 16px;
    border-radius: 12px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    transition: all 0.2s ease;
}

.btn-hero-refresh:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
    color: #ffffff;
}

/* ── 2. Urgent Inventory Alert Banner ── */
.urgent-alert-card {
    background: linear-gradient(135deg, #fff1f2 0%, #fffbeb 100%);
    border: 1.5px solid #fecaca;
    border-radius: 16px;
    padding: 14px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 14px;
    box-shadow: 0 6px 20px rgba(220, 38, 38, 0.08);
}

.animate-pulse-border {
    animation: borderAlertPulse 3s infinite;
}

@keyframes borderAlertPulse {
    0%, 100% { border-color: #fecaca; box-shadow: 0 4px 14px rgba(220, 38, 38, 0.06); }
    50% { border-color: #f87171; box-shadow: 0 4px 22px rgba(220, 38, 38, 0.18); }
}

.alert-left {
    display: flex;
    align-items: center;
    gap: 14px;
}

.alert-icon-wrap {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    background: #fee2e2;
    color: #dc2626;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.alert-headline {
    color: #991b1b;
    font-size: 13.5px;
    font-weight: 800;
    letter-spacing: 0.2px;
}

.alert-desc {
    color: #b45309;
    font-size: 12px;
    margin-top: 2px;
}

.btn-alert-action {
    background: #dc2626;
    color: #ffffff;
    padding: 8px 18px;
    border-radius: 10px;
    font-size: 12.5px;
    font-weight: 800;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 4px 14px rgba(220, 38, 38, 0.25);
    transition: all 0.2s ease;
}

.btn-alert-action:hover {
    background: #b91c1c;
    color: #ffffff;
    transform: translateY(-2px);
}

/* ── 3. Section Headers ── */
.dash-section-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 4px;
}

.section-title-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-indicator {
    width: 4px;
    height: 16px;
    background: #00285a;
    border-radius: 4px;
}

.dash-section-head h2 {
    font-size: 14px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: #334155;
    margin: 0;
}

.section-meta-tag {
    font-size: 11.5px;
    font-weight: 600;
    color: #64748b;
}

/* ── 4. KPI Cards Grid (8 Cards) ── */
.kpi-cards-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}

@media(max-width: 1200px) {
    .kpi-cards-grid { grid-template-columns: repeat(2, 1fr); }
}

@media(max-width: 600px) {
    .kpi-cards-grid { grid-template-columns: 1fr; }
}

.kpi-card-box {
    text-decoration: none;
    color: inherit;
    position: relative;
    border-radius: 18px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 18px rgba(0, 40, 90, 0.04);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transition: all 0.24s cubic-bezier(0.16, 1, 0.3, 1);
}

.kpi-card-box:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(0, 40, 90, 0.1);
    border-color: #cbd5e1;
}

.kpi-glass {
    padding: 18px 20px 16px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    flex: 1;
}

.kpi-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.kpi-tag {
    font-size: 11px;
    font-weight: 800;
    color: #64748b;
    letter-spacing: 0.5px;
}

.kpi-icon-bubble {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    transition: transform 0.2s ease;
}

.kpi-card-box:hover .kpi-icon-bubble {
    transform: scale(1.1) rotate(5deg);
}

.theme-navy .kpi-icon-bubble   { background: #eff6ff; color: #00285a; }
.theme-emerald .kpi-icon-bubble{ background: #ecfdf5; color: #059669; }
.theme-indigo .kpi-icon-bubble { background: #e0e7ff; color: #4338ca; }
.theme-purple .kpi-icon-bubble { background: #f3e8ff; color: #7e22ce; }
.theme-amber .kpi-icon-bubble  { background: #fef3c7; color: #d97706; }
.theme-cyan .kpi-icon-bubble   { background: #cffafe; color: #0891b2; }
.theme-teal .kpi-icon-bubble   { background: #ccfbf1; color: #0f766e; }
.theme-rose .kpi-icon-bubble   { background: #ffe4e6; color: #e11d48; }

.kpi-number-wrap {
    display: flex;
    align-items: baseline;
    gap: 2px;
}

.kpi-currency {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
}

.kpi-val {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.1;
    letter-spacing: -0.5px;
}

.kpi-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 2px;
}

.kpi-badge {
    font-size: 11px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.badge-success { background: #ecfdf5; color: #047857; }
.badge-emerald { background: #ecfdf5; color: #059669; }
.badge-indigo  { background: #e0e7ff; color: #4338ca; }
.badge-purple  { background: #f3e8ff; color: #7e22ce; }
.badge-amber   { background: #fef3c7; color: #b45309; }
.badge-cyan    { background: #cffafe; color: #0e7490; }
.badge-teal    { background: #ccfbf1; color: #0f766e; }
.badge-rose    { background: #fee2e2; color: #b91c1c; }

.kpi-link-arrow {
    color: #94a3b8;
    font-size: 14px;
    transition: transform 0.2s ease, color 0.2s ease;
}

.kpi-card-box:hover .kpi-link-arrow {
    transform: translateX(4px);
    color: #00285a;
}

.kpi-bottom-bar {
    height: 3px;
    width: 100%;
}
.bar-navy   { background: #00285a; }
.bar-emerald{ background: #10b981; }
.bar-indigo { background: #6366f1; }
.bar-purple { background: #a855f7; }
.bar-amber  { background: #f59e0b; }
.bar-cyan   { background: #06b6d4; }
.bar-teal   { background: #14b8a6; }
.bar-rose   { background: #f43f5e; }

/* ── 5. Economics & Margins Ribbon ── */
.economics-ribbon-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 16px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 4px 16px rgba(0, 40, 90, 0.04);
    gap: 16px;
    flex-wrap: wrap;
}

.econ-item {
    display: flex;
    align-items: center;
    gap: 14px;
    flex: 1;
    min-width: 160px;
}

.econ-icon-wrap {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.bg-blue-subtle    { background: #eff6ff; }
.bg-emerald-subtle { background: #ecfdf5; }
.bg-purple-subtle  { background: #f3e8ff; }
.bg-amber-subtle   { background: #fef3c7; }
.bg-indigo-subtle  { background: #e0e7ff; }

.econ-meta {
    display: flex;
    flex-direction: column;
}

.econ-label {
    font-size: 10.5px;
    font-weight: 800;
    color: #64748b;
    letter-spacing: 0.5px;
}

.econ-val {
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.2;
}

.econ-sub {
    font-size: 11px;
    color: #94a3b8;
}

.econ-sep {
    width: 1px;
    height: 40px;
    background: #e2e8f0;
}

@media(max-width: 991px) {
    .econ-sep { display: none; }
}

/* ── 6. Dual Grid Panels Layouts ── */
.dash-grid-split {
    display: grid;
    grid-template-columns: 68% 30.5%;
    gap: 20px;
    align-items: stretch;
}

.dash-grid-halves {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    align-items: stretch;
}

@media(max-width: 1100px) {
    .dash-grid-split,
    .dash-grid-halves {
        grid-template-columns: 1fr;
    }
}

.dash-panel {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    box-shadow: 0 4px 18px rgba(0, 40, 90, 0.04);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transition: box-shadow 0.2s ease;
}

.dash-panel:hover {
    box-shadow: 0 8px 24px rgba(0, 40, 90, 0.08);
}

.panel-header {
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}

.panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
}

.panel-icon-bubble {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    color: #ffffff;
    flex-shrink: 0;
}

.panel-icon-bubble.bg-navy   { background: #00285a; }
.panel-icon-bubble.bg-purple { background: #7c3aed; }
.panel-icon-bubble.bg-indigo { background: #4f46e5; }
.panel-icon-bubble.bg-teal   { background: #0d9488; }
.panel-icon-bubble.bg-amber  { background: #d97706; }
.panel-icon-bubble.bg-rose   { background: #e11d48; }
.panel-icon-bubble.bg-cyan   { background: #0891b2; }

.panel-title {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
}

.panel-sub {
    font-size: 11.5px;
    color: #64748b;
    margin: 0;
}

.panel-view-all {
    font-size: 12px;
    font-weight: 700;
    color: #2563eb;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: transform 0.15s ease;
}

.panel-view-all:hover {
    transform: translateX(3px);
    color: #1d4ed8;
}

.panel-body {
    padding: 18px 20px;
    flex: 1;
}

/* ── 7. Charts & Legends ── */
.timeline-legend {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.legend-badge {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 11.5px;
    font-weight: 700;
    color: #64748b;
}

.legend-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}
.dot-navy    { background: #00285a; }
.dot-emerald { background: #10b981; }

.growth-badge {
    font-size: 11px;
    font-weight: 800;
    padding: 3px 8px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.growth-pos { background: #ecfdf5; color: #047857; }
.growth-neg { background: #fee2e2; color: #b91c1c; }

.chart-canvas-container {
    height: 290px;
    position: relative;
    width: 100%;
}

/* Category Donut & Progress */
.category-donut-wrap {
    height: 180px;
    position: relative;
    margin-bottom: 16px;
}

.donut-center-stat {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    pointer-events: none;
}

.center-title {
    display: block;
    font-size: 10px;
    font-weight: 800;
    color: #94a3b8;
    letter-spacing: 0.5px;
}

.center-val {
    font-size: 15px;
    font-weight: 800;
    color: #0f172a;
}

.category-progress-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.cat-bar-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.cat-bar-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 12px;
}

.cat-name-tag {
    display: flex;
    align-items: center;
    gap: 7px;
    font-weight: 700;
    color: #334155;
}

.cat-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
}

.cat-val-tag strong {
    color: #0f172a;
}

.cat-val-tag small {
    color: #64748b;
}

.cat-track {
    width: 100%;
    height: 5px;
    border-radius: 999px;
    background: #f1f5f9;
    overflow: hidden;
}

.cat-fill {
    height: 100%;
    border-radius: 999px;
    transition: width 0.6s ease;
}

/* ── 8. Modern Table Styles ── */
.table-responsive-wrapper {
    overflow-x: auto;
    width: 100%;
}

.modern-dash-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12.5px;
}

.modern-dash-table th {
    background: #f8fafc;
    color: #64748b;
    font-size: 10.5px;
    font-weight: 800;
    letter-spacing: 0.5px;
    padding: 10px 16px;
    border-bottom: 1px solid #e2e8f0;
    text-align: left;
    white-space: nowrap;
}

.modern-row {
    cursor: pointer;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.15s ease, transform 0.15s ease;
}

.modern-row:hover {
    background: #f8fafc;
}

.modern-dash-table td {
    padding: 12px 16px;
    vertical-align: middle;
}

.order-id-pill {
    font-family: 'Plus Jakarta Sans', monospace;
    font-weight: 800;
    font-size: 12px;
    color: #00285a;
    background: #eff6ff;
    padding: 3px 8px;
    border-radius: 6px;
}

.cust-info-cell {
    display: flex;
    align-items: center;
    gap: 10px;
}

.avatar-letter {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: linear-gradient(135deg, #00285a 0%, #1e40af 100%);
    color: #ffffff;
    font-size: 12px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.cust-names {
    display: flex;
    flex-direction: column;
}

.cust-main-name {
    font-weight: 700;
    color: #0f172a;
    font-size: 12.5px;
}

.cust-sub-email {
    font-size: 10.5px;
    color: #64748b;
}

.order-amt-val {
    font-weight: 800;
    color: #0f172a;
    font-size: 13px;
}

.pay-chip {
    font-size: 11px;
    font-weight: 800;
    padding: 3px 8px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.pay-paid    { background: #ecfdf5; color: #047857; }
.pay-failed  { background: #fee2e2; color: #b91c1c; }
.pay-pending { background: #fef3c7; color: #b45309; }

.status-chip {
    font-size: 11px;
    font-weight: 800;
    padding: 3px 9px;
    border-radius: 6px;
}
.chip-pending    { background: #fff7ed; color: #ea580c; border: 1px solid #ffedd5; }
.chip-processing { background: #faf5ff; color: #9333ea; border: 1px solid #f3e8ff; }
.chip-shipped    { background: #eff6ff; color: #2563eb; border: 1px solid #dbeafe; }
.chip-delivered  { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
.chip-cancelled  { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
.chip-returned   { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }

.time-ago-text {
    font-size: 11px;
    color: #64748b;
}

.row-arrow-icon {
    color: #cbd5e1;
    font-size: 13px;
    transition: transform 0.15s ease, color 0.15s ease;
}

.modern-row:hover .row-arrow-icon {
    transform: translateX(3px);
    color: #00285a;
}

/* ── 9. Fulfillment Pipeline Meters ── */
.donut-container-status {
    height: 160px;
    position: relative;
    margin-bottom: 14px;
}

.fulfillment-meter-list {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.meter-row-btn {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 6px 10px;
    border-radius: 8px;
    text-decoration: none;
    color: inherit;
    transition: all 0.15s ease;
}

.meter-row-btn:hover {
    background: #f8fafc;
    transform: translateX(2px);
}

.meter-row-left {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 700;
    color: #334155;
}

.status-marker {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.meter-row-right {
    display: flex;
    align-items: center;
    gap: 8px;
}

.meter-mini-track {
    width: 60px;
    height: 5px;
    background: #f1f5f9;
    border-radius: 999px;
    overflow: hidden;
}

.meter-mini-fill {
    height: 100%;
    border-radius: 999px;
}

.meter-num {
    font-size: 12px;
    font-weight: 800;
    color: #0f172a;
    min-width: 20px;
    text-align: right;
}

.meter-chevron {
    font-size: 11px;
    color: #cbd5e1;
}

/* ── 10. Products Table Cells ── */
.prod-profile-cell {
    display: flex;
    align-items: center;
    gap: 10px;
}

.prod-thumb-box {
    width: 36px;
    height: 42px;
    border-radius: 7px;
    overflow: hidden;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    flex-shrink: 0;
}

.prod-thumb-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.2s ease;
}

.modern-row:hover .prod-thumb-box img {
    transform: scale(1.08);
}

.prod-meta {
    display: flex;
    flex-direction: column;
}

.prod-title {
    font-size: 12.5px;
    font-weight: 700;
    color: #0f172a;
}

.prod-sku {
    font-size: 10px;
    color: #64748b;
    font-family: monospace;
}

.cat-pill-badge {
    font-size: 11px;
    color: #475569;
    background: #f1f5f9;
    padding: 2px 7px;
    border-radius: 5px;
}

.volume-sold-chip {
    font-size: 11.5px;
    font-weight: 700;
    color: #00285a;
    background: #eff6ff;
    padding: 3px 8px;
    border-radius: 6px;
    white-space: nowrap;
}

.btn-micro-edit {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    background: #eff6ff;
    color: #1d4ed8;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    text-decoration: none;
    transition: all 0.15s ease;
}

.btn-micro-edit:hover {
    background: #1d4ed8;
    color: #ffffff;
}

.sku-mono-code {
    font-size: 11px;
    background: #f1f5f9;
    color: #0f172a;
    padding: 2px 6px;
    border-radius: 4px;
}

.stock-status-pill {
    font-size: 10.5px;
    font-weight: 800;
    padding: 3px 8px;
    border-radius: 6px;
    display: inline-block;
}
.pill-zero { background: #fee2e2; color: #b91c1c; }
.pill-low  { background: #ffedd5; color: #c2410c; }

.btn-restock-instant {
    background: #00285a;
    color: #ffffff;
    font-size: 11px;
    font-weight: 700;
    padding: 5px 12px;
    border-radius: 7px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all 0.15s ease;
}

.btn-restock-instant:hover {
    background: #1e3f75;
    color: #ffffff;
    transform: translateY(-1px);
}

/* ── 11. Launchpad & Timeline Feed ── */
.launchpad-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}

@media(max-width: 600px) {
    .launchpad-grid { grid-template-columns: repeat(2, 1fr); }
}

.launch-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 14px 10px;
    border-radius: 14px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    text-decoration: none;
    color: #0f172a;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.launch-card:hover {
    background: #ffffff;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 40, 90, 0.08);
}

.launch-icon-box {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    margin-bottom: 6px;
    transition: transform 0.2s ease;
}

.launch-card:hover .launch-icon-box {
    transform: scale(1.15);
}

.launch-card.theme-indigo .launch-icon-box { background: #e0e7ff; color: #4338ca; }
.launch-card.theme-purple .launch-icon-box { background: #f3e8ff; color: #7e22ce; }
.launch-card.theme-amber .launch-icon-box  { background: #fef3c7; color: #b45309; }
.launch-card.theme-emerald .launch-icon-box{ background: #ecfdf5; color: #059669; }
.launch-card.theme-rose .launch-icon-box   { background: #fee2e2; color: #b91c1c; }
.launch-card.theme-cyan .launch-icon-box   { background: #cffafe; color: #0e7490; }

.launch-title {
    font-size: 12px;
    font-weight: 800;
    color: #0f172a;
}

.launch-sub {
    font-size: 10px;
    color: #64748b;
}

/* Activity Timeline */
.activity-timeline {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.timeline-event-row {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 8px 10px;
    border-radius: 10px;
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    transition: background 0.15s ease;
}

.timeline-event-row:hover {
    background: #ffffff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.event-icon-circle {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #fee2e2;
    color: #dc2626;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    flex-shrink: 0;
    margin-top: 2px;
}

.event-body {
    flex: 1;
}

.event-top-line {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}

.event-title {
    font-size: 12px;
    font-weight: 700;
    color: #0f172a;
}

.event-time {
    font-size: 10.5px;
    color: #94a3b8;
}

.event-msg {
    font-size: 11.5px;
    color: #475569;
    margin: 2px 0 0 0;
}

.empty-state-muted {
    text-align: center;
    color: #94a3b8;
    font-size: 12px;
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // ── 1. Live Real-Time Clock Ticker (IST) ──
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

    // ── 2. Smooth Number Count-Up Animation for KPI Cards ──
    var countElements = document.querySelectorAll('.count-up');
    countElements.forEach(function(el) {
        var target = parseInt(el.getAttribute('data-val'), 10) || 0;
        if (target <= 0) return;

        var duration = 900; // ms
        var start = 0;
        var startTime = null;

        function step(timestamp) {
            if (!startTime) startTime = timestamp;
            var progress = Math.min((timestamp - startTime) / duration, 1);
            // Ease out quad
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

    // ── 3. Dual-Axis Revenue & Orders Trend Chart ──
    var canvasRev = document.getElementById('revChart');
    if (canvasRev) {
        var ctxRev = canvasRev.getContext('2d');
        var revGradient = ctxRev.createLinearGradient(0, 0, 0, 280);
        revGradient.addColorStop(0, 'rgba(0, 40, 90, 0.35)');
        revGradient.addColorStop(1, 'rgba(0, 40, 90, 0.00)');

        new Chart(ctxRev, {
            type: 'line',
            data: {
                labels: {!! $chartLabelsJson !!},
                datasets: [{
                    label: 'Revenue (₹)',
                    data: {!! $chartRevenueJson !!},
                    borderColor: '#00285a',
                    backgroundColor: revGradient,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#00285a',
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.38,
                    yAxisID: 'y'
                }, {
                    label: 'Orders',
                    data: {!! $chartOrdersJson !!},
                    borderColor: '#10b981',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    borderDash: [5, 4],
                    pointBackgroundColor: '#10b981',
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    tension: 0.38,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.92)',
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 12 },
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(ctx) {
                                if (ctx.datasetIndex === 0) {
                                    return 'Revenue: ₹' + (ctx.raw || 0).toLocaleString('en-IN');
                                }
                                return 'Orders: ' + (ctx.raw || 0);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11, weight: '600' }, color: '#64748b' }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        ticks: {
                            callback: function(v) { return '₹' + (v >= 1000 ? (v/1000).toFixed(0) + 'k' : v); },
                            font: { size: 11 },
                            color: '#64748b'
                        },
                        grid: { color: 'rgba(0, 0, 0, 0.04)' }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        ticks: { font: { size: 11 }, color: '#10b981' },
                        grid: { drawOnChartArea: false }
                    }
                }
            }
        });
    }

    // ── 4. Category Revenue Donut Chart ──
    var canvasCat = document.getElementById('categoryDonutChart');
    if (canvasCat) {
        var ctxCat = canvasCat.getContext('2d');
        var catLabels = {!! $catLabelsJson !!};
        var catData = {!! $catRevenuesJson !!};
        var catColors = ['#00285a', '#10b981', '#a855f7', '#f59e0b', '#3b82f6', '#ec4899'];

        new Chart(ctxCat, {
            type: 'doughnut',
            data: {
                labels: catLabels.length ? catLabels : ['General'],
                datasets: [{
                    data: catData.length ? catData : [1],
                    backgroundColor: catColors,
                    borderWidth: 2.5,
                    borderColor: '#ffffff',
                    hoverOffset: 4
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
                                return ' ' + ctx.label + ': ₹' + Number(ctx.raw).toLocaleString('en-IN');
                            }
                        }
                    }
                }
            }
        });
    }

    // ── 5. Order Status Fulfillment Donut Chart ──
    var canvasStatus = document.getElementById('statusDoughnutChart');
    if (canvasStatus) {
        var ctxStatus = canvasStatus.getContext('2d');
        var stColorsMap = {
            'pending': '#f97316',
            'processing': '#a855f7',
            'shipped': '#3b82f6',
            'delivered': '#10b981',
            'cancelled': '#ef4444',
            'returned': '#64748b'
        };
        var stLabels = {!! $stLabelsJson !!};
        var bgColors = stLabels.map(function(s) { return stColorsMap[s] || '#94a3b8'; });

        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: stLabels,
                datasets: [{
                    data: {!! $stCountsJson !!},
                    backgroundColor: bgColors,
                    borderWidth: 2.5,
                    borderColor: '#ffffff',
                    hoverOffset: 4
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
                                return ' ' + ctx.label.toUpperCase() + ': ' + ctx.raw + ' orders';
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
