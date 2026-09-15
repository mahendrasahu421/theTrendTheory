{{-- resources/views/admin/dashboards/super_admin.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Executive Intelligence Dashboard')
@section('content')

@php
    $chartLabelsJson  = json_encode($chartData['labels'] ?? []);
    $chartRevenueJson = json_encode($chartData['revenue'] ?? []);
    $chartOrdersJson  = json_encode($chartData['orders'] ?? []);
    $stLabelsJson     = $orderStatus->pluck('status')->toJson();
    $stCountsJson     = $orderStatus->pluck('count')->toJson();
@endphp

<div class="dash-master-wrap">

   
    {{-- ── URGENT LOW STOCK ALERT HIGHLIGHT ── --}}
    @if ($lowStockCount > 0 || $outOfStockCount > 0)
        <div style="background: linear-gradient(135deg, #fff1f2 0%, #fffbeb 100%); border: 1.5px solid #fecaca; border-radius: 16px; padding: 14px 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; box-shadow: 0 4px 14px rgba(220, 38, 38, 0.06); margin-bottom: 8px;">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div>
                    <strong style="color: #991b1b; font-size: 13.5px;" class="d-block">
                        ⚠️ Inventory Alert: {{ $lowStockCount }} items low on stock &bull; {{ $outOfStockCount }} items out of stock!
                    </strong>
                    <span style="color: #b45309; font-size: 12px;">Storefront products running out of inventory. Restock now to avoid lost sales revenue.</span>
                </div>
            </div>
            <a href="{{ route('admin.inventory.index', ['status' => 'low_stock']) }}" class="btn btn-sm btn-danger" style="border-radius: 9px; font-weight: 700; padding: 7px 16px; font-size: 12px; display: inline-flex; align-items: center; gap: 6px;">
                <i class="bi bi-lightning-charge-fill"></i> Manage &amp; Restock Inventory &rarr;
            </a>
        </div>
    @endif

    {{-- ── 2. Top 8 Summary KPI Cards (Clickable Direct Links) ── --}}
    <div class="sec-divider">
        <span>TOP BUSINESS HEALTH METRICS (CLICK TO VIEW DETAILS)</span>
    </div>

    <div class="kpi-grid-8">
        {{-- 1. Total Sales --}}
        <a href="{{ route('admin.orders.index') }}" class="kpi-card-link" title="Click to view all sales & orders">
            <div class="kpi-card">
                <div class="kpi-top">
                    <span class="kpi-title">TOTAL SALES (LIFETIME)</span>
                    <div class="kpi-icon-box icon-navy">
                        <i class="bi bi-currency-rupee"></i>
                    </div>
                </div>
                <div class="kpi-val">₹{{ number_format($totalSalesAllTime) }}</div>
                <div class="kpi-sub">
                    <span class="text-success font-bold"><i class="bi bi-check-circle-fill"></i> Paid</span> lifetime volume
                    <i class="bi bi-arrow-right-short kpi-hover-arrow"></i>
                </div>
            </div>
        </a>

        {{-- 2. Total Orders --}}
        <a href="{{ route('admin.orders.index') }}" class="kpi-card-link" title="Click to view all orders">
            <div class="kpi-card">
                <div class="kpi-top">
                    <span class="kpi-title">TOTAL ORDERS</span>
                    <div class="kpi-icon-box icon-indigo">
                        <i class="bi bi-bag-check-fill"></i>
                    </div>
                </div>
                <div class="kpi-val text-indigo">{{ number_format($totalOrdersCount) }}</div>
                <div class="kpi-sub">
                    <i class="bi bi-cart"></i> Placed in store
                    <i class="bi bi-arrow-right-short kpi-hover-arrow"></i>
                </div>
            </div>
        </a>

        {{-- 3. Total Customers --}}
        <a href="{{ route('admin.customers.index') }}" class="kpi-card-link" title="Click to view customers directory">
            <div class="kpi-card">
                <div class="kpi-top">
                    <span class="kpi-title">TOTAL CUSTOMERS</span>
                    <div class="kpi-icon-box icon-purple">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
                <div class="kpi-val text-purple">{{ number_format($totalCustomersCount) }}</div>
                <div class="kpi-sub">
                    <span class="text-success font-bold">+{{ $newCustomers }}</span> new this period
                    <i class="bi bi-arrow-right-short kpi-hover-arrow"></i>
                </div>
            </div>
        </a>

        {{-- 4. Total Products --}}
        <a href="{{ route('admin.products.index') }}" class="kpi-card-link" title="Click to view catalog products">
            <div class="kpi-card">
                <div class="kpi-top">
                    <span class="kpi-title">TOTAL PRODUCTS</span>
                    <div class="kpi-icon-box icon-cyan">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
                <div class="kpi-val text-cyan">{{ number_format($totalProductsCount) }}</div>
                <div class="kpi-sub">
                    <i class="bi bi-tags"></i> Active in catalog
                    <i class="bi bi-arrow-right-short kpi-hover-arrow"></i>
                </div>
            </div>
        </a>

        {{-- 5. Today's Revenue --}}
        <a href="{{ route('admin.orders.index') }}" class="kpi-card-link" title="Click to view today's orders">
            <div class="kpi-card">
                <div class="kpi-top">
                    <span class="kpi-title">TODAY'S REVENUE</span>
                    <div class="kpi-icon-box icon-emerald">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
                <div class="kpi-val text-emerald">₹{{ number_format($todayRevenue) }}</div>
                <div class="kpi-sub">
                    <i class="bi bi-lightning-fill text-warning"></i> Generated today
                    <i class="bi bi-arrow-right-short kpi-hover-arrow"></i>
                </div>
            </div>
        </a>

        {{-- 6. Pending Orders --}}
        <a href="{{ route('admin.orders.index') }}" class="kpi-card-link" title="Click to view pending orders">
            <div class="kpi-card">
                <div class="kpi-top">
                    <span class="kpi-title">PENDING ORDERS</span>
                    <div class="kpi-icon-box icon-amber">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
                <div class="kpi-val text-amber">{{ number_format($pendingOrdersCount) }}</div>
                <div class="kpi-sub">
                    <span class="{{ $pendingOrdersCount > 0 ? 'text-amber font-bold' : 'text-muted' }}">{{ $pendingOrdersCount > 0 ? 'Awaiting fulfillment' : 'All clear' }}</span>
                    <i class="bi bi-arrow-right-short kpi-hover-arrow"></i>
                </div>
            </div>
        </a>

        {{-- 7. Delivered Orders --}}
        <a href="{{ route('admin.orders.index') }}" class="kpi-card-link" title="Click to view delivered orders">
            <div class="kpi-card">
                <div class="kpi-top">
                    <span class="kpi-title">DELIVERED ORDERS</span>
                    <div class="kpi-icon-box icon-teal">
                        <i class="bi bi-check2-all"></i>
                    </div>
                </div>
                <div class="kpi-val text-teal">{{ number_format($deliveredOrdersCount) }}</div>
                <div class="kpi-sub">
                    <i class="bi bi-truck text-success"></i> Successfully fulfilled
                    <i class="bi bi-arrow-right-short kpi-hover-arrow"></i>
                </div>
            </div>
        </a>

        {{-- 8. Low Stock Products --}}
        <a href="{{ route('admin.products.index') }}" class="kpi-card-link" title="Click to view inventory & stock">
            <div class="kpi-card">
                <div class="kpi-top">
                    <span class="kpi-title">LOW STOCK (&le; 5)</span>
                    <div class="kpi-icon-box icon-rose">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                </div>
                <div class="kpi-val text-rose">{{ number_format($lowStockCount + $outOfStockCount) }}</div>
                <div class="kpi-sub">
                    <span class="text-rose font-bold">{{ $outOfStockCount }} out of stock</span>
                    <i class="bi bi-arrow-right-short kpi-hover-arrow"></i>
                </div>
            </div>
        </a>
    </div>

    {{-- ── 3. Revenue Breakdown Ribbon (Clickable Links) ── --}}
    <div class="revenue-ribbon-card">
        <a href="{{ route('admin.orders.index') }}" class="rev-col-link">
            <div class="rev-col">
                <span class="rev-lbl"><i class="bi bi-calendar-day"></i> Today's Revenue</span>
                <div class="rev-amount">₹{{ number_format($todayRevenue) }}</div>
            </div>
        </a>
        <div class="rev-divider"></div>
        <a href="{{ route('admin.orders.index') }}" class="rev-col-link">
            <div class="rev-col">
                <span class="rev-lbl"><i class="bi bi-calendar-week"></i> This Week</span>
                <div class="rev-amount text-indigo">₹{{ number_format($thisWeekRevenue) }}</div>
            </div>
        </a>
        <div class="rev-divider"></div>
        <a href="{{ route('admin.orders.index') }}" class="rev-col-link">
            <div class="rev-col">
                <span class="rev-lbl"><i class="bi bi-calendar-month"></i> This Month</span>
                <div class="rev-amount text-emerald">₹{{ number_format($thisMonthRevenue) }}</div>
            </div>
        </a>
        <div class="rev-divider"></div>
        <a href="{{ route('admin.orders.index') }}" class="rev-col-link">
            <div class="rev-col">
                <span class="rev-lbl"><i class="bi bi-bank"></i> Total All-Time Revenue</span>
                <div class="rev-amount text-navy">₹{{ number_format($totalSalesAllTime) }}</div>
            </div>
        </a>
    </div>

    {{-- ── 4. Sales & Orders Dual Chart ── --}}
    <div class="sec-divider">
        <span>SALES &amp; ORDER VOLUME ANALYTICS</span>
    </div>

    <div class="dash-card">
        <div class="dash-card-header">
            <div class="card-title-combo">
                <i class="bi bi-graph-up-arrow text-indigo"></i>
                <div>
                    <h3>Revenue &amp; Orders Trend</h3>
                    <small>Interactive transaction volume &bull; {{ $filter === 'daily' ? 'Hourly today' : ($filter === 'weekly' ? 'Daily this week' : 'Monthly data') }}</small>
                </div>
            </div>

            <div class="chart-legend-wrap">
                <span class="legend-item">
                    <span class="legend-dot bg-navy"></span>
                    <span>Revenue (₹)</span>
                </span>
                <span class="legend-item">
                    <span class="legend-dot bg-emerald"></span>
                    <span>Orders</span>
                </span>
                <span class="growth-pill {{ $revGrowth >= 0 ? 'growth-pos' : 'growth-neg' }}">
                    <i class="bi bi-arrow-{{ $revGrowth >= 0 ? 'up' : 'down' }}-right"></i>
                    {{ abs($revGrowth) }}% vs previous period
                </span>
            </div>
        </div>

        <div class="dash-card-body">
            <div style="height:270px;position:relative;">
                <canvas id="revChart"></canvas>
            </div>
        </div>
    </div>

    {{-- ── 5. Row: Recent Orders (70%) + Order Status Overview (30%) ── --}}
    <div class="dash-grid-70-30">
        {{-- Recent Orders --}}
        <div class="dash-card">
            <div class="dash-card-header">
                <div class="card-title-combo">
                    <i class="bi bi-receipt text-indigo"></i>
                    <div>
                        <h3>Recent Orders</h3>
                        <small>Latest customer purchases (Click row to inspect)</small>
                    </div>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="link-view-all">View All Orders &rarr;</a>
            </div>

            <div class="table-responsive-clean">
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th style="text-align:right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $ord)
                            <tr class="clickable-row" onclick="window.location='{{ route('admin.orders.index') }}'">
                                <td>
                                    <strong class="text-navy font-mono">#{{ $ord->order_number ?? $ord->id }}</strong>
                                </td>
                                <td>
                                    <div class="cust-avatar-cell">
                                        <div class="mini-avatar">{{ strtoupper(substr($ord->user->name ?? $ord->billing_name ?? 'Guest', 0, 1)) }}</div>
                                        <span class="cust-name-text">{{ $ord->user->name ?? $ord->billing_name ?? 'Guest User' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <strong class="text-navy">₹{{ number_format($ord->total_amount) }}</strong>
                                </td>
                                <td>
                                    @if($ord->payment_status === 'paid')
                                        <span class="badge-pay pay-paid"><i class="bi bi-check-circle-fill"></i> Paid</span>
                                    @elseif($ord->payment_status === 'failed')
                                        <span class="badge-pay pay-failed"><i class="bi bi-x-circle-fill"></i> Failed</span>
                                    @else
                                        <span class="badge-pay pay-pending"><i class="bi bi-clock-fill"></i> Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge-order status-{{ $ord->status }}">
                                        {{ ucfirst($ord->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted font-sm">{{ $ord->created_at->diffForHumans() }}</span>
                                </td>
                                <td style="text-align:right;">
                                    <span class="btn-table-arrow"><i class="bi bi-chevron-right"></i></span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-table-msg">No orders recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Order Status Overview Doughnut (Clickable Rows) --}}
        <div class="dash-card">
            <div class="dash-card-header">
                <div class="card-title-combo">
                    <i class="bi bi-pie-chart-fill text-purple"></i>
                    <div>
                        <h3>Fulfillment Status</h3>
                        <small>Click to filter orders by stage</small>
                    </div>
                </div>
            </div>

            <div class="dash-card-body">
                <div style="height:170px;position:relative;margin-bottom:16px;">
                    <canvas id="statusDoughnutChart"></canvas>
                </div>

                <div class="status-bars-stack">
                    @php
                        $stMap = [
                            'pending'    => ['color' => '#f97316', 'count' => $pendingOrdersCount],
                            'processing' => ['color' => '#a855f7', 'count' => $processingCount],
                            'shipped'    => ['color' => '#3b82f6', 'count' => $shippedCount],
                            'delivered'  => ['color' => '#10b981', 'count' => $deliveredOrdersCount],
                            'cancelled'  => ['color' => '#ef4444', 'count' => $cancelledCount],
                            'returned'   => ['color' => '#64748b', 'count' => $returnedCount],
                        ];
                    @endphp
                    @foreach($stMap as $stKey => $stData)
                        <a href="{{ route('admin.orders.index') }}" class="status-meter-row clickable-meter" title="Filter by {{ ucfirst($stKey) }}">
                            <div class="status-meter-left">
                                <span class="meter-dot" style="background:{{ $stData['color'] }};"></span>
                                <span class="meter-name">{{ ucfirst($stKey) }}</span>
                            </div>
                            <div class="status-meter-right">
                                <div class="meter-bar-track">
                                    <div class="meter-bar-fill" style="width:{{ $totalOrdersCount > 0 ? round(($stData['count'] / $totalOrdersCount) * 100) : 0 }}%;background:{{ $stData['color'] }};"></div>
                                </div>
                                <span class="meter-count font-bold">{{ $stData['count'] }}</span>
                                <i class="bi bi-chevron-right meter-arrow"></i>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ── 6. Row: Top Selling Products (50%) + Low Stock Alerts (50%) ── --}}
    <div class="dash-grid-50-50">
        {{-- Top Selling Products --}}
        <div class="dash-card">
            <div class="dash-card-header">
                <div class="card-title-combo">
                    <i class="bi bi-fire text-amber"></i>
                    <div>
                        <h3>Top Selling Products</h3>
                        <small>Best performing drops (Click to view product)</small>
                    </div>
                </div>
                <a href="{{ route('admin.products.index') }}" class="link-view-all">All Products &rarr;</a>
            </div>

            <div class="dash-card-body p-0">
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Sold Units</th>
                            <th>Price</th>
                            <th style="text-align:right;">Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $tp)
                            <tr class="clickable-row" onclick="window.location='{{ route('admin.products.edit', $tp) }}'">
                                <td>
                                    <div class="prod-cell">
                                        @if($tp->image)
                                            <img src="{{ $tp->image }}" alt="" class="prod-thumb">
                                        @endif
                                        <strong class="text-navy">{{ Str::limit($tp->name, 28) }}</strong>
                                    </div>
                                </td>
                                <td><span class="text-muted">{{ $tp->category->name ?? '—' }}</span></td>
                                <td><span class="badge-units font-bold">{{ $tp->total_sold ?? 0 }} sold</span></td>
                                <td><strong class="text-navy">₹{{ number_format($tp->price) }}</strong></td>
                                <td style="text-align:right;">
                                    <a href="{{ route('admin.products.edit', $tp) }}" class="btn-circle-edit" onclick="event.stopPropagation();">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="empty-table-msg">No sales data yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Low Stock & Urgent Restock Alerts --}}
        <div class="dash-card">
            <div class="dash-card-header">
                <div class="card-title-combo">
                    <i class="bi bi-exclamation-octagon-fill text-rose"></i>
                    <div>
                        <h3>Stock &amp; Inventory Alerts</h3>
                        <small>Items running out of stock (1-Click Restock)</small>
                    </div>
                </div>
                <a href="{{ route('admin.products.index') }}" class="link-view-all">Manage Stock &rarr;</a>
            </div>

            <div class="dash-card-body p-0">
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Available</th>
                            <th style="text-align:right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lowStockProductsList as $lsp)
                            <tr class="clickable-row" onclick="window.location='{{ route('admin.products.edit', $lsp) }}'">
                                <td>
                                    <div class="prod-cell">
                                        @if($lsp->image)
                                            <img src="{{ $lsp->image }}" alt="" class="prod-thumb">
                                        @endif
                                        <strong class="text-navy">{{ Str::limit($lsp->name, 26) }}</strong>
                                    </div>
                                </td>
                                <td><code class="sku-code">{{ $lsp->sku ?? '—' }}</code></td>
                                <td>
                                    @if($lsp->stock <= 0)
                                        <span class="stock-pill stock-zero">Out of Stock</span>
                                    @else
                                        <span class="stock-pill stock-low">{{ $lsp->stock }} remaining</span>
                                    @endif
                                </td>
                                <td style="text-align:right;">
                                    <a href="{{ route('admin.products.edit', $lsp) }}" class="btn-restock" onclick="event.stopPropagation();">
                                        Restock
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="empty-table-msg"><i class="bi bi-check-circle text-success"></i> All catalog products have healthy stock!</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ── 7. Row: Latest Customers (50%) + Quick Actions Hub & Alerts (50%) ── --}}
    <div class="dash-grid-50-50">
        {{-- Latest Customers --}}
        <div class="dash-card">
            <div class="dash-card-header">
                <div class="card-title-combo">
                    <i class="bi bi-person-check-fill text-emerald"></i>
                    <div>
                        <h3>Latest Registered Customers</h3>
                        <small>New customer profiles &amp; history</small>
                    </div>
                </div>
                <a href="{{ route('admin.customers.index') }}" class="link-view-all">All Customers &rarr;</a>
            </div>

            <div class="dash-card-body p-0">
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Contact</th>
                            <th>Total Orders</th>
                            <th>Joined</th>
                            <th style="text-align:right;">Profile</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestCustomers as $cust)
                            <tr class="clickable-row" onclick="window.location='{{ route('admin.customers.index') }}'">
                                <td>
                                    <div class="cust-avatar-cell">
                                        <img src="{{ $cust->avatar }}" alt="" class="mini-avatar-img">
                                        <strong class="text-navy">{{ $cust->name }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted font-sm">{{ $cust->email }}</span>
                                </td>
                                <td>
                                    <span class="badge-units">{{ $cust->orders_count ?? 0 }} orders</span>
                                </td>
                                <td>
                                    <span class="text-muted font-sm">{{ $cust->created_at->format('d M Y') }}</span>
                                </td>
                                <td style="text-align:right;">
                                    <a href="{{ route('admin.customers.index') }}" class="btn-circle-edit" onclick="event.stopPropagation();">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="empty-table-msg">No customers registered yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick Actions Hub & Notifications Feed --}}
        <div class="dash-card">
            <div class="dash-card-header">
                <div class="card-title-combo">
                    <i class="bi bi-lightning-charge-fill text-amber"></i>
                    <div>
                        <h3>Quick Actions &amp; Operations Hub</h3>
                        <small>1-Click shortcuts to common tasks</small>
                    </div>
                </div>
            </div>

            <div class="dash-card-body">
                {{-- Shortcuts Grid --}}
                <div class="quick-actions-grid">
                    <a href="{{ route('admin.products.create') }}" class="quick-act-btn">
                        <i class="bi bi-plus-circle-fill text-indigo"></i>
                        <span>Add Product</span>
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="quick-act-btn">
                        <i class="bi bi-folder-plus text-purple"></i>
                        <span>Add Category</span>
                    </a>
                    <a href="{{ route('admin.coupons.index') }}" class="quick-act-btn">
                        <i class="bi bi-ticket-perforated-fill text-amber"></i>
                        <span>Create Coupon</span>
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="quick-act-btn">
                        <i class="bi bi-bag-check-fill text-emerald"></i>
                        <span>View Orders</span>
                    </a>
                    <a href="{{ route('admin.notifications.index') }}" class="quick-act-btn">
                        <i class="bi bi-bell-fill text-rose"></i>
                        <span>Send Broadcast</span>
                    </a>
                    <a href="{{ route('admin.staff.index') }}" class="quick-act-btn">
                        <i class="bi bi-shield-lock-fill text-cyan"></i>
                        <span>Permissions</span>
                    </a>
                </div>

                {{-- Notifications Feed preview --}}
                <div class="notif-feed-box">
                    <div class="notif-feed-head">
                        <span class="font-bold font-sm text-navy"><i class="bi bi-bell-fill text-rose"></i> SYSTEM NOTIFICATION LOGS</span>
                        <a href="{{ route('admin.notifications.index') }}" class="font-sm text-indigo font-bold text-decoration-none">Manage Alerts &rarr;</a>
                    </div>
                    <div class="notif-list-items">
                        @forelse($recentNotifications as $notif)
                            <a href="{{ route('admin.notifications.index') }}" class="notif-row-link">
                                <div class="notif-row-item">
                                    <i class="bi bi-chat-left-dots-fill text-indigo"></i>
                                    <div class="notif-meta">
                                        <strong class="text-navy font-sm">{{ $notif->title }}</strong>
                                        <p class="font-xs text-muted mb-0">{{ Str::limit($notif->message, 55) }}</p>
                                    </div>
                                    <span class="notif-time">{{ $notif->created_at->diffForHumans() }}</span>
                                </div>
                            </a>
                        @empty
                            <div class="empty-table-msg p-2">No recent system notifications.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
.rev-col-link {
    text-decoration: none;
    color: inherit;
    flex: 1;
    min-width: 140px;
    transition: transform 0.15s ease;
}

.rev-col-link:hover {
    transform: translateY(-2px);
}

.revenue-ribbon-card {
    background: #ffffff;
    border: 1px solid #eef2f6;
    border-radius: 16px;
    padding: 16px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 16px -2px rgba(0, 40, 90, 0.02);
    flex-wrap: wrap;
    gap: 16px;
}

.rev-col {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.rev-lbl {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.rev-amount {
    font-family: 'Cinzel', serif;
    font-size: 20px;
    font-weight: 700;
    color: #00285a;
}

.rev-divider {
    width: 1px;
    height: 36px;
    background: #e2e8f0;
}

@media(max-width: 768px) {
    .rev-divider { display: none; }
    .revenue-ribbon-card { flex-direction: column; align-items: flex-start; }
}

.clickable-meter {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 12px;
    padding: 6px 10px;
    border-radius: 8px;
    text-decoration: none;
    color: inherit;
    transition: background 0.15s ease;
}

.clickable-meter:hover {
    background: #f8fafc;
}

.status-meter-left {
    display: flex;
    align-items: center;
    gap: 6px;
}

.meter-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.status-meter-right {
    display: flex;
    align-items: center;
    gap: 8px;
}

.meter-bar-track {
    width: 60px;
    height: 6px;
    background: #f1f5f9;
    border-radius: 999px;
    overflow: hidden;
}

.meter-bar-fill {
    height: 100%;
    border-radius: 999px;
}

.meter-arrow {
    color: #cbd5e1;
    font-size: 12px;
}

.clickable-meter:hover .meter-arrow {
    color: #00285a;
}

.status-bars-stack {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.prod-thumb {
    width: 34px;
    height: 40px;
    border-radius: 6px;
    object-fit: cover;
    border: 1px solid #e2e8f0;
}

.mini-avatar-img {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    border: 1.5px solid #e2e8f0;
}

.sku-code {
    font-size: 11px;
    background: #f1f5f9;
    color: #00285a;
    padding: 2px 7px;
    border-radius: 4px;
    font-weight: 700;
}

.stock-pill {
    display: inline-flex;
    padding: 2px 7px;
    border-radius: 6px;
    font-size: 10.5px;
    font-weight: 700;
}

.stock-zero { background: #fee2e2; color: #dc2626; }
.stock-low { background: #ffedd5; color: #c2410c; }

.btn-restock {
    background: #00285a;
    color: #ffffff;
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.15s ease;
}

.btn-restock:hover {
    background: #1e3f75;
    color: #ffffff;
}

.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin-bottom: 18px;
}

.quick-act-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 12px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    text-decoration: none;
    color: #00285a;
    font-size: 11.5px;
    font-weight: 700;
    transition: all 0.15s ease;
}

.quick-act-btn i {
    font-size: 18px;
}

.quick-act-btn:hover {
    background: #ffffff;
    border-color: #00285a;
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(0, 40, 90, 0.08);
}

.notif-feed-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 14px;
}

.notif-feed-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    padding-bottom: 6px;
    border-bottom: 1px solid #e2e8f0;
}

.notif-list-items {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.notif-row-link {
    text-decoration: none;
    color: inherit;
    display: block;
    padding: 6px 8px;
    border-radius: 8px;
    transition: background 0.15s ease;
}

.notif-row-link:hover {
    background: #ffffff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
}

.notif-row-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 11.5px;
}

.notif-meta {
    flex: 1;
}

.notif-time {
    font-size: 10px;
    color: #94a3b8;
}

.chart-legend-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.legend-item {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    font-weight: 700;
    color: #64748b;
}

.legend-dot {
    width: 8px;
    height: 8px;
    border-radius: 2px;
}

.bg-navy { background: #00285a; }
.bg-emerald { background: #10b981; }

.growth-pill {
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.growth-pos { background: #ecfdf5; color: #047857; }
.growth-neg { background: #fee2e2; color: #dc2626; }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // 1. Dual-Axis Revenue & Orders Trend Chart
    var ctx = document.getElementById('revChart').getContext('2d');
    var revGradient = ctx.createLinearGradient(0, 0, 0, 260);
    revGradient.addColorStop(0, 'rgba(0, 40, 90, 0.28)');
    revGradient.addColorStop(1, 'rgba(0, 40, 90, 0.01)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! $chartLabelsJson !!},
            datasets: [{
                label: 'Revenue (₹)',
                data: {!! $chartRevenueJson !!},
                borderColor: '#00285a',
                backgroundColor: revGradient,
                borderWidth: 2.5,
                fill: true,
                tension: 0.35,
                yAxisID: 'y'
            }, {
                label: 'Orders',
                data: {!! $chartOrdersJson !!},
                borderColor: '#10b981',
                backgroundColor: 'transparent',
                borderWidth: 2,
                borderDash: [4, 4],
                tension: 0.35,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false } },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    ticks: { callback: function(v) { return '₹' + (v >= 1000 ? (v/1000).toFixed(0) + 'k' : v); } },
                    grid: { color: 'rgba(0,0,0,0.04)' }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: { drawOnChartArea: false }
                }
            }
        }
    });

    // 2. Order Status Doughnut Chart
    var ctxStatus = document.getElementById('statusDoughnutChart').getContext('2d');
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
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            plugins: { legend: { display: false } }
        }
    });
</script>
@endpush

@endsection
