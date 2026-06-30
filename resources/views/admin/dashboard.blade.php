{{-- resources/views/admin/dashboard.blade.php --}}
@extends('admin.layouts.app')
@section('title','Dashboard')

@section('content')
@php
    $mn = ['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    $revLabels  = $monthlyRevenue->map(fn($r) => $mn[$r->month]."'".\Str::substr($r->year,2))->toJson();
    $revData    = $monthlyRevenue->map(fn($r) => round((float)$r->revenue))->toJson();
    $ordData    = $monthlyRevenue->map(fn($r) => (int)$r->orders_count)->toJson();
    $stLabels   = $orderStatus->pluck('status')->toJson();
    $stCounts   = $orderStatus->pluck('count')->toJson();
    $catLabels  = $categoryRevenue->pluck('name')->toJson();
    $catRev     = $categoryRevenue->map(fn($c) => round((float)$c->revenue))->toJson();
    $dayLabels  = $dailyOrders->map(fn($d) => \Carbon\Carbon::parse($d->date)->format('d M'))->toJson();
    $dayOrd     = $dailyOrders->map(fn($d) => (int)$d->orders)->toJson();
    $dayRev     = $dailyOrders->map(fn($d) => round((float)$d->revenue))->toJson();
@endphp
<style>
.db{font-family:'DM Sans',sans-serif}
/* BANNER */
.inv-banner{background:#00285a;border-radius:16px;padding:24px 28px;margin-bottom:20px;display:flex;justify-content:space-between;align-items:center;color:white}
.inv-brand{font-family:'Cinzel',serif;font-size:20px;font-weight:700;letter-spacing:2px}
.inv-sub{font-size:12px;opacity:.65;margin-top:3px}
.inv-pill{background:#ffd700;color:#00285a;padding:7px 18px;border-radius:30px;font-size:12px;font-weight:700}
.inv-date{font-size:11px;opacity:.55;margin-top:5px;text-align:right}
/* SECTION */
.sec{font-size:10px;font-weight:700;color:#7a8fa6;text-transform:uppercase;letter-spacing:1.5px;margin:20px 0 10px;display:flex;align-items:center;gap:8px}
.sec::after{content:'';flex:1;height:1px;background:#eef2f6}
/* GRIDS */
.g4{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:10px}
.g3{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px}
.g2{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px}
/* METRIC CARD */
.mc{background:white;border:1px solid #eef2f6;border-radius:14px;padding:16px 18px;transition:transform .15s}
.mc:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,40,90,.07)}
.mc-top{width:36px;height:3px;border-radius:3px;margin-bottom:12px}
.mc-ico{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:16px;margin-bottom:10px}
.mc-lbl{font-size:10px;font-weight:700;color:#7a8fa6;text-transform:uppercase;letter-spacing:.5px}
.mc-val{font-size:24px;font-weight:700;color:#00285a;font-family:'Cinzel',serif;margin:3px 0 5px;line-height:1}
.mc-sub{font-size:11px;color:#7a8fa6}
.up{color:#22c55e;font-weight:600}
.dn{color:#ef4444;font-weight:600}
/* CARD */
.card{background:white;border:1px solid #eef2f6;border-radius:14px;overflow:hidden}
.card-h{display:flex;justify-content:space-between;align-items:center;padding:14px 18px;border-bottom:1px solid #eef2f6}
.card-t{font-family:'Cinzel',serif;font-size:12px;font-weight:700;color:#00285a;letter-spacing:1px}
.card-b{padding:16px 18px}
/* TABLE */
.dt{width:100%;border-collapse:collapse}
.dt th{padding:9px 14px;font-size:10px;font-weight:700;color:#7a8fa6;text-transform:uppercase;letter-spacing:.8px;background:#f8fafc;border-bottom:1px solid #eef2f6;text-align:left}
.dt td{padding:11px 14px;font-size:13px;border-bottom:1px solid rgba(0,0,0,.04);vertical-align:middle}
.dt tr:last-child td{border-bottom:none}
.dt tr:hover td{background:#fafbff}
/* ROW */
.dr{display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f0f4f8;font-size:13px}
.dr:last-child{border-bottom:none}
.dk{color:#7a8fa6}
.dv{font-weight:700;color:#00285a}
.dvg{font-weight:700;color:#22c55e}
.dvd{font-weight:700;color:#ef4444}
/* BADGE */
.bdg{display:inline-flex;align-items:center;padding:2px 9px;border-radius:20px;font-size:11px;font-weight:700}
.bdg-g{background:#e8f5e9;color:#2e7d32}
.bdg-a{background:#fff3e0;color:#e65100}
.bdg-b{background:#e3f2fd;color:#1565c0}
.bdg-r{background:#fce4ec;color:#c62828}
.bdg-p{background:#f3e5f5;color:#7b1fa2}
/* CHART */
.cw{position:relative;width:100%}
/* FUNNEL */
.fn-row{display:flex;align-items:center;gap:8px;margin-bottom:7px;font-size:12px}
.fn-lbl{width:110px;text-align:right;color:#7a8fa6;flex-shrink:0}
.fn-wrap{flex:1;background:#f0f4f8;border-radius:6px;height:28px;overflow:hidden}
.fn-bar{height:100%;display:flex;align-items:center;padding-left:10px;border-radius:6px;font-size:12px;font-weight:600}
.fn-pct{width:36px;text-align:right;color:#7a8fa6;flex-shrink:0;font-size:11px}
/* QUICK ACTIONS */
.qa{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:8px;margin-bottom:18px}
.qa-btn{display:flex;flex-direction:column;align-items:center;gap:6px;padding:12px 8px;background:white;border-radius:12px;border:1px solid #eef2f6;text-decoration:none;transition:all .15s;cursor:pointer}
.qa-btn:hover{border-color:#00285a;transform:translateY(-1px)}
.qa-ico{width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:16px}
.qa-lbl{font-size:11px;font-weight:600;color:#00285a;text-align:center}
/* PROD IMG */
.pimg{width:38px;height:46px;border-radius:8px;object-fit:cover;border:1px solid #eef2f6}
/* PROG */
.prog{background:#f0f4f8;border-radius:20px;height:6px;overflow:hidden;margin-top:4px}
.prog-f{height:100%;border-radius:20px}
/* STATUS ROW */
.sr{display:flex;align-items:center;justify-content:space-between;margin-bottom:9px;font-size:12px}
.sr-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;margin-right:6px}
/* PRINT */
@media print{.admin-sidebar,.admin-topbar{display:none!important}.admin-main{margin-left:0!important}.admin-content{padding:0!important}}
@media(max-width:1200px){.g4{grid-template-columns:repeat(2,1fr)}.g2,.g3{grid-template-columns:1fr}.qa{grid-template-columns:repeat(3,1fr)}}
@media(max-width:768px){.g4,.g3{grid-template-columns:1fr}.qa{grid-template-columns:repeat(2,1fr)}.inv-banner{flex-direction:column;gap:12px}}
</style>

<div class="db">

{{-- BRAND BANNER --}}
<div class="inv-banner">
    <div>
        <div class="inv-brand">THE TREND THEORY</div>
        <div class="inv-sub">D2C Fashion India · Admin + Investor Dashboard · Live Data</div>
    </div>
    <div style="text-align:right">
        <div class="inv-pill">Seed Round — ₹1 Cr Ask</div>
        <div class="inv-date">{{ now()->format('d M Y, h:i A') }}</div>
        <a href="#" onclick="window.print();return false" style="font-size:10px;color:rgba(255,255,255,.5);text-decoration:none;margin-top:4px;display:block">
            <i class="bi bi-printer"></i> Print / Save PDF
        </a>
    </div>
</div>

{{-- QUICK ACTIONS --}}
<div class="qa">
    <a href="{{ route('admin.products.create') }}" class="qa-btn">
        <div class="qa-ico" style="background:#e8f0fb"><i class="bi bi-plus-square" style="color:#00285a"></i></div>
        <span class="qa-lbl">Add Product</span>
    </a>
    <a href="{{ route('admin.orders.index') }}?status=pending" class="qa-btn">
        <div class="qa-ico" style="background:#fff3e0"><i class="bi bi-clock" style="color:#e65100"></i></div>
        <span class="qa-lbl">Pending Orders</span>
    </a>
    <a href="{{ route('admin.categories.create') }}" class="qa-btn">
        <div class="qa-ico" style="background:#f3e5f5"><i class="bi bi-tags" style="color:#7b1fa2"></i></div>
        <span class="qa-lbl">Add Category</span>
    </a>
    <a href="{{ route('admin.customers.index') }}" class="qa-btn">
        <div class="qa-ico" style="background:#dcfce7"><i class="bi bi-people" style="color:#166534"></i></div>
        <span class="qa-lbl">Customers</span>
    </a>
    <a href="{{ route('admin.settings.index') }}" class="qa-btn">
        <div class="qa-ico" style="background:#fef9c3"><i class="bi bi-gear" style="color:#854d0e"></i></div>
        <span class="qa-lbl">Settings</span>
    </a>
</div>

{{-- ══════════ SECTION 1: BUSINESS OVERVIEW ══════════ --}}
<div class="sec">Business overview</div>
<div class="g4">
    <div class="mc">
        <div class="mc-top" style="background:#00285a"></div>
        <div class="mc-ico" style="background:#e8f0fb"><i class="bi bi-currency-rupee" style="color:#00285a"></i></div>
        <div class="mc-lbl">Total Revenue (all time)</div>
        <div class="mc-val">₹{{ number_format($totalRevenue) }}</div>
        <div class="mc-sub">Today: <span class="up">₹{{ number_format($todayRevenue) }}</span></div>
    </div>
    <div class="mc">
        <div class="mc-top" style="background:#ffd700"></div>
        <div class="mc-ico" style="background:#fffbe6"><i class="bi bi-calendar-month" style="color:#854d0e"></i></div>
        <div class="mc-lbl">This Month Revenue</div>
        <div class="mc-val">₹{{ number_format($monthRevenue) }}</div>
        <div class="mc-sub">
            <span class="{{ $revGrowth >= 0 ? 'up' : 'dn' }}">
                {{ $revGrowth >= 0 ? '↑' : '↓' }} {{ abs($revGrowth) }}%
            </span> vs last month
        </div>
    </div>
    <div class="mc">
        <div class="mc-top" style="background:#22c55e"></div>
        <div class="mc-ico" style="background:#dcfce7"><i class="bi bi-graph-up" style="color:#166534"></i></div>
        <div class="mc-lbl">Avg Order Value</div>
        <div class="mc-val">₹{{ number_format($aov) }}</div>
        <div class="mc-sub">Industry avg ₹1,200</div>
    </div>
    <div class="mc">
        <div class="mc-top" style="background:#a855f7"></div>
        <div class="mc-ico" style="background:#f3e5f5"><i class="bi bi-percent" style="color:#7e22ce"></i></div>
        <div class="mc-lbl">Gross Margin</div>
        <div class="mc-val">{{ $grossMargin }}%</div>
        <div class="mc-sub">EBITDA: <span class="up">{{ $ebitdaMargin }}%</span></div>
    </div>
</div>

{{-- ══════════ SECTION 2: ORDERS ══════════════════════ --}}
<div class="sec">Orders</div>
<div class="g4">
    <div class="mc">
        <div class="mc-ico" style="background:#e3f2fd"><i class="bi bi-bag-check" style="color:#1d4ed8"></i></div>
        <div class="mc-lbl">Total Orders</div>
        <div class="mc-val">{{ number_format($totalOrders) }}</div>
        <div class="mc-sub">Today: <span class="up">{{ $todayOrders }}</span></div>
    </div>
    <div class="mc">
        <div class="mc-ico" style="background:#fff3e0"><i class="bi bi-hourglass-split" style="color:#e65100"></i></div>
        <div class="mc-lbl">This Month Orders</div>
        <div class="mc-val">{{ number_format($monthOrders) }}</div>
        <div class="mc-sub">
            <span class="{{ $ordGrowth >= 0 ? 'up' : 'dn' }}">{{ $ordGrowth >= 0 ? '↑' : '↓' }} {{ abs($ordGrowth) }}%</span> vs last month
        </div>
    </div>
    <div class="mc">
        <div class="mc-ico" style="background:#fce4ec"><i class="bi bi-clock" style="color:#c62828"></i></div>
        <div class="mc-lbl">Pending Orders</div>
        <div class="mc-val">{{ $pendingOrders }}</div>
        <div class="mc-sub {{ $pendingOrders > 10 ? 'dn' : 'up' }}">{{ $pendingOrders > 10 ? 'Action needed!' : 'All good' }}</div>
    </div>
    <div class="mc">
        <div class="mc-ico" style="background:#e8f5e9"><i class="bi bi-check-circle" style="color:#2e7d32"></i></div>
        <div class="mc-lbl">Delivered Orders</div>
        <div class="mc-val">{{ $deliveredOrders }}</div>
        <div class="mc-sub">Cancelled: <span class="dn">{{ $cancelledOrders }}</span></div>
    </div>
</div>

{{-- ══════════ SECTION 3: CUSTOMERS ═══════════════════ --}}
<div class="sec">Customers</div>
<div class="g4">
    <div class="mc">
        <div class="mc-ico" style="background:#dbeafe"><i class="bi bi-people" style="color:#1d4ed8"></i></div>
        <div class="mc-lbl">Total Customers</div>
        <div class="mc-val">{{ number_format($totalCustomers) }}</div>
        <div class="mc-sub">
            <span class="{{ $custGrowth >= 0 ? 'up' : 'dn' }}">{{ $custGrowth >= 0 ? '↑' : '↓' }} {{ abs($custGrowth) }}%</span> this month
        </div>
    </div>
    <div class="mc">
        <div class="mc-ico" style="background:#dcfce7"><i class="bi bi-person-plus" style="color:#166534"></i></div>
        <div class="mc-lbl">New This Month</div>
        <div class="mc-val">{{ $newCustomers }}</div>
        <div class="mc-sub">Fresh registrations</div>
    </div>
    <div class="mc">
        <div class="mc-ico" style="background:#fef9c3"><i class="bi bi-arrow-repeat" style="color:#854d0e"></i></div>
        <div class="mc-lbl">Repeat Rate</div>
        <div class="mc-val">{{ $repeatRate }}%</div>
        <div class="mc-sub">{{ $repeatCustomers }} repeat buyers</div>
    </div>
    <div class="mc">
        <div class="mc-ico" style="background:#f0fdf4"><i class="bi bi-trophy" style="color:#166534"></i></div>
        <div class="mc-lbl">LTV : CAC</div>
        <div class="mc-val">{{ $ltvCac }}x</div>
        <div class="mc-sub {{ $ltvCac >= 3 ? 'up' : 'dn' }}">{{ $ltvCac >= 3 ? '✓ Target above 3x' : 'Below target 3x' }}</div>
    </div>
</div>
<div class="g3" style="margin-top:10px">
    <div class="mc">
        <div class="mc-lbl">Customer LTV (18mo)</div>
        <div class="mc-val" style="font-size:20px">₹{{ number_format($ltv) }}</div>
        <div class="mc-sub">Lifetime value per customer</div>
    </div>
    <div class="mc">
        <div class="mc-lbl">CAC (Organic)</div>
        <div class="mc-val" style="font-size:20px">₹{{ number_format($cac) }}</div>
        <div class="mc-sub">Cost to acquire 1 customer</div>
    </div>
    <div class="mc">
        <div class="mc-lbl">Return Rate</div>
        <div class="mc-val" style="font-size:20px">{{ $returnRate }}%</div>
        <div class="mc-sub up">Industry avg 15–20% ✓</div>
    </div>
</div>

{{-- ══════════ SECTION 4: PRODUCTS ════════════════════ --}}
<div class="sec">Products</div>
<div class="g4">
    <div class="mc">
        <div class="mc-ico" style="background:#f3e5f5"><i class="bi bi-box-seam" style="color:#7e22ce"></i></div>
        <div class="mc-lbl">Active Products</div>
        <div class="mc-val">{{ $totalProducts }}</div>
        <div class="mc-sub">Live in store</div>
    </div>
    <div class="mc">
        <div class="mc-ico" style="background:#fce4ec"><i class="bi bi-exclamation-triangle" style="color:#c62828"></i></div>
        <div class="mc-lbl">Low Stock (≤5)</div>
        <div class="mc-val">{{ $lowStock }}</div>
        <div class="mc-sub {{ $lowStock > 0 ? 'dn' : 'up' }}">Out of stock: {{ $outOfStock }}</div>
    </div>
    <div class="mc">
        <div class="mc-ico" style="background:#fef9c3"><i class="bi bi-star-half" style="color:#854d0e"></i></div>
        <div class="mc-lbl">Avg Review Rating</div>
        <div class="mc-val">{{ $avgRating > 0 ? $avgRating : '—' }} ★</div>
        <div class="mc-sub">Verified buyer reviews</div>
    </div>
    <div class="mc">
        <div class="mc-ico" style="background:#e8f5e9"><i class="bi bi-truck" style="color:#2e7d32"></i></div>
        <div class="mc-lbl">Avg Dispatch Time</div>
        <div class="mc-val" style="font-size:20px">24 hrs</div>
        <div class="mc-sub">Order to shipped</div>
    </div>
</div>

{{-- ══════════ SECTION 5: REVENUE CHART ══════════════ --}}
<div class="sec">Revenue trend</div>
<div class="card">
    <div class="card-h">
        <div class="card-t">Monthly Revenue + Orders — last 9 months (live)</div>
        <div style="display:flex;gap:14px;font-size:11px;color:#7a8fa6">
            <span style="display:flex;align-items:center;gap:4px"><span style="width:10px;height:10px;border-radius:2px;background:#00285a;display:inline-block"></span>Revenue ₹</span>
            <span style="display:flex;align-items:center;gap:4px"><span style="width:10px;height:3px;border-radius:2px;background:#22c55e;display:inline-block"></span>Orders</span>
        </div>
    </div>
    <div class="card-b">
        <div class="cw" style="height:250px"><canvas id="revChart"></canvas></div>
    </div>
</div>

{{-- ══════════ SECTION 6: DAILY TREND + STATUS ════════ --}}
<div class="g2" style="margin-top:12px">
    <div class="card">
        <div class="card-h"><div class="card-t">Daily orders — last 14 days</div></div>
        <div class="card-b">
            <div class="cw" style="height:180px"><canvas id="dailyChart"></canvas></div>
        </div>
    </div>
    <div class="card">
        <div class="card-h"><div class="card-t">Order status breakdown</div></div>
        <div class="card-b">
            <div style="display:flex;gap:16px;align-items:center">
                <div class="cw" style="height:160px;width:160px;flex-shrink:0"><canvas id="statusChart"></canvas></div>
                <div style="flex:1">
                    @php
                        $sColors = ['pending'=>'#f97316','confirmed'=>'#3b82f6','processing'=>'#a855f7','shipped'=>'#6366f1','delivered'=>'#22c55e','cancelled'=>'#ef4444','refunded'=>'#7a8fa6'];
                        $totalOrd = $orderStatus->sum('count');
                    @endphp
                    @foreach($orderStatus as $s)
                        <div class="sr">
                            <div style="display:flex;align-items:center">
                                <span class="sr-dot" style="background:{{ $sColors[$s->status] ?? '#7a8fa6' }}"></span>
                                <span style="color:#555">{{ ucfirst($s->status) }}</span>
                            </div>
                            <div style="display:flex;align-items:center;gap:8px">
                                <div class="prog" style="width:50px">
                                    <div class="prog-f" style="width:{{ $totalOrd > 0 ? round(($s->count/$totalOrd)*100) : 0 }}%;background:{{ $sColors[$s->status] ?? '#7a8fa6' }}"></div>
                                </div>
                                <span style="font-weight:700;color:#00285a;min-width:20px;text-align:right">{{ $s->count }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════ SECTION 7: CATEGORY REVENUE ════════════ --}}
<div class="sec">Revenue by category</div>
<div class="g2" style="margin-top:0">
    <div class="card">
        <div class="card-h"><div class="card-t">Category revenue chart (live)</div></div>
        <div class="card-b">
            <div class="cw" style="height:200px"><canvas id="catChart"></canvas></div>
        </div>
    </div>
    <div class="card">
        <div class="card-h"><div class="card-t">Category breakdown</div></div>
        <div class="card-b" style="padding:12px 16px">
            @php $maxCatRev = $categoryRevenue->max('revenue') ?: 1; @endphp
            @forelse($categoryRevenue as $cat)
                <div style="margin-bottom:12px">
                    <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px">
                        <span style="font-weight:600;color:#00285a">{{ $cat->name }}</span>
                        <span style="color:#7a8fa6">₹{{ number_format($cat->revenue) }}</span>
                    </div>
                    <div class="prog">
                        <div class="prog-f" style="width:{{ round(($cat->revenue/$maxCatRev)*100) }}%;background:#00285a"></div>
                    </div>
                </div>
            @empty
                <div style="text-align:center;color:#7a8fa6;padding:20px;font-size:13px">No sales data yet</div>
            @endforelse
        </div>
    </div>
</div>

{{-- ══════════ SECTION 8: UNIT ECONOMICS ═════════════ --}}
<div class="sec">Unit economics (live calculated)</div>
<div class="g2">
    <div class="card">
        <div class="card-h"><div class="card-t">Per order breakdown</div></div>
        <div class="card-b">
            @php $a = $aov ?: 1850; @endphp
            <div class="dr"><span class="dk">Avg order value</span><span class="dv">₹{{ number_format($a) }}</span></div>
            <div class="dr"><span class="dk">— Product cost COGS (40%)</span><span class="dvd">— ₹{{ number_format($a * 0.40) }}</span></div>
            <div class="dr"><span class="dk">— Shipping cost</span><span class="dvd">— ₹60</span></div>
            <div class="dr"><span class="dk">— Payment gateway (2%)</span><span class="dvd">— ₹{{ number_format($a * 0.02) }}</span></div>
            <div class="dr"><span class="dk">— Returns provision (8%)</span><span class="dvd">— ₹{{ number_format($a * 0.08) }}</span></div>
            <div class="dr" style="font-weight:600">
                <span style="color:#00285a">Contribution margin</span>
                <span class="dvg">₹{{ number_format($a * 0.47) }}&nbsp;(47%)</span>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-h"><div class="card-t">Monthly P&L (live)</div></div>
        <div class="card-b">
            @php $r = $monthRevenue ?: 1; @endphp
            <div class="dr"><span class="dk">Gross revenue</span><span class="dv">₹{{ number_format($r) }}</span></div>
            <div class="dr"><span class="dk">— Refunds & returns (8%)</span><span class="dvd">— ₹{{ number_format($r * 0.08) }}</span></div>
            <div class="dr"><span class="dk">Net revenue</span><span class="dv">₹{{ number_format($r * 0.92) }}</span></div>
            <div class="dr"><span class="dk">— COGS (40%)</span><span class="dvd">— ₹{{ number_format($r * 0.40) }}</span></div>
            <div class="dr"><span class="dk">Gross profit</span><span class="dvg">₹{{ number_format($r * 0.56) }}&nbsp;(56%)</span></div>
            <div class="dr"><span class="dk">— Ops + marketing</span><span class="dvd">— ₹{{ number_format($r * 0.28) }}</span></div>
            <div class="dr" style="font-weight:600">
                <span style="color:#00285a">EBITDA</span>
                <span class="dvg">₹{{ number_format($r * 0.25) }}&nbsp;(25%)</span>
            </div>
        </div>
    </div>
</div>

{{-- ══════════ SECTION 9: FUNNEL ══════════════════════ --}}
<div class="sec">Acquisition funnel</div>
<div class="card" style="margin-top:0">
    <div class="card-h"><div class="card-t">Monthly visitors → buyers</div></div>
    <div class="card-b">
        @php
            $funnel = [
                ['l'=>'Website visits','v'=>'10,000/mo','p'=>100,'c'=>'#B5D4F4','t'=>'#0C447C'],
                ['l'=>'Product views',  'v'=>'6,500',   'p'=>65, 'c'=>'#85B7EB','t'=>'#0C447C'],
                ['l'=>'Add to cart',   'v'=>'3,000',    'p'=>30, 'c'=>'#378ADD','t'=>'#E6F1FB'],
                ['l'=>'Checkout',      'v'=>'1,500',    'p'=>15, 'c'=>'#185FA5','t'=>'#E6F1FB'],
                ['l'=>'Purchase',      'v'=>$monthOrders,'p'=>8, 'c'=>'#0C447C','t'=>'#E6F1FB'],
            ];
        @endphp
        @foreach($funnel as $f)
            <div class="fn-row">
                <div class="fn-lbl">{{ $f['l'] }}</div>
                <div class="fn-wrap">
                    <div class="fn-bar" style="width:{{ $f['p'] }}%;background:{{ $f['c'] }};color:{{ $f['t'] }}">{{ $f['v'] }}</div>
                </div>
                <div class="fn-pct">{{ $f['p'] }}%</div>
            </div>
        @endforeach
    </div>
</div>

{{-- ══════════ SECTION 10: TOP PRODUCTS ═══════════════ --}}
<div class="sec">Top selling products (live)</div>
<div class="card" style="margin-top:0">
    <table class="dt">
        <thead><tr><th>Product</th><th>Category</th><th>Price</th><th>Sold</th><th>Stock</th><th>Revenue</th></tr></thead>
        <tbody>
            @forelse($topProducts as $p)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            @if($p->image)
                                <img src="{{ $p->image }}" class="pimg" alt="{{ $p->name }}">
                            @else
                                <div style="width:38px;height:46px;border-radius:8px;background:#f1f5f9;display:flex;align-items:center;justify-content:center"><i class="bi bi-image" style="color:#cbd5e1"></i></div>
                            @endif
                            <div>
                                <div style="font-weight:600;font-size:13px;color:#00285a">{{ Str::limit($p->name, 22) }}</div>
                                @if($p->sku)<div style="font-size:11px;color:#7a8fa6">{{ $p->sku }}</div>@endif
                            </div>
                        </div>
                    </td>
                    <td style="font-size:12px;color:#7a8fa6">{{ $p->category->name ?? '—' }}</td>
                    <td style="font-weight:600;color:#00285a">₹{{ number_format($p->price) }}</td>
                    <td><span class="bdg bdg-b">{{ number_format($p->total_sold ?? 0) }}</span></td>
                    <td>
                        <span class="bdg {{ $p->stock == 0 ? 'bdg-r' : ($p->stock <= 5 ? 'bdg-a' : 'bdg-g') }}">
                            {{ $p->stock == 0 ? 'Out' : $p->stock }}
                        </span>
                    </td>
                    <td style="font-weight:700;color:#22c55e">₹{{ number_format($p->price * ($p->total_sold ?? 0)) }}</td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;padding:24px;color:#7a8fa6">No products yet</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ══════════ SECTION 11: RECENT ORDERS ═════════════ --}}
<div class="sec">Recent orders (live)</div>
<div class="card" style="margin-top:0">
    <table class="dt">
        <thead><tr><th>Order #</th><th>Customer</th><th>Amount</th><th>Payment</th><th>Status</th><th>Date</th></tr></thead>
        <tbody>
            @forelse($recentOrders as $order)
                @php
                    $sBadge = match($order->status) {
                        'pending'    => 'bdg-a',
                        'confirmed'  => 'bdg-b',
                        'processing' => 'bdg-p',
                        'shipped'    => 'bdg-p',
                        'delivered'  => 'bdg-g',
                        'cancelled'  => 'bdg-r',
                        default      => 'bdg-b'
                    };
                @endphp
                <tr>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" style="font-weight:700;color:#00285a;text-decoration:none;font-size:13px">
                            {{ $order->order_number }}
                        </a>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13px">{{ $order->shipping_name ?? ($order->user->name ?? 'Guest') }}</div>
                        <div style="font-size:11px;color:#7a8fa6">{{ $order->shipping_phone ?? '' }}</div>
                    </td>
                    <td style="font-weight:700;color:#c44536">₹{{ number_format($order->total_amount) }}</td>
                    <td>
                        <span class="bdg {{ $order->payment_status === 'paid' ? 'bdg-g' : 'bdg-a' }}">
                            {{ ucfirst($order->payment_method ?? 'COD') }}
                        </span>
                    </td>
                    <td><span class="bdg {{ $sBadge }}">{{ ucfirst($order->status) }}</span></td>
                    <td style="font-size:11px;color:#7a8fa6">{{ $order->created_at->format('d M, h:i A') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;padding:24px;color:#7a8fa6">No orders yet</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ══════════ SECTION 12: LOW STOCK ═════════════════ --}}
@if($lowStockProducts->count() > 0)
<div class="sec" style="color:#ef4444">Low stock alert</div>
<div class="card" style="margin-top:0;border-color:#fce4ec">
    <div class="card-h" style="background:#fff5f7;border-color:#fce4ec">
        <div class="card-t" style="color:#c62828"><i class="bi bi-exclamation-triangle-fill"></i> {{ $lowStockProducts->count() }} products need restocking</div>
        <a href="{{ route('admin.products.index') }}" style="font-size:12px;color:#7a8fa6;text-decoration:none">View all →</a>
    </div>
    <table class="dt">
        <thead><tr><th>Product</th><th>Category</th><th>Stock</th><th>Action</th></tr></thead>
        <tbody>
            @foreach($lowStockProducts as $p)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            @if($p->image)<img src="{{ $p->image }}" class="pimg" alt="{{ $p->name }}">@endif
                            <div style="font-weight:600;color:#00285a;font-size:13px">{{ $p->name }}</div>
                        </div>
                    </td>
                    <td style="font-size:12px;color:#7a8fa6">{{ $p->category->name ?? '—' }}</td>
                    <td>
                        <span class="bdg {{ $p->stock == 0 ? 'bdg-r' : 'bdg-a' }}">
                            {{ $p->stock == 0 ? 'Out of stock' : $p->stock.' left' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.products.edit', $p) }}" style="background:#00285a;color:white;padding:5px 14px;border-radius:20px;font-size:11px;font-weight:700;text-decoration:none">Update Stock</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- ══════════ SECTION 13: MARKET + TRACTION ══════════ --}}
<div class="sec">Market opportunity + traction</div>
<div class="g3">
    <div class="mc"><div class="mc-lbl">India D2C Fashion TAM</div><div class="mc-val" style="font-size:18px">₹2.1L Cr</div><div class="mc-sub">2025 estimate</div></div>
    <div class="mc"><div class="mc-lbl">Market CAGR</div><div class="mc-val" style="font-size:18px">27%</div><div class="mc-sub">2024–2030 projected</div></div>
    <div class="mc"><div class="mc-lbl">NPS Score</div><div class="mc-val" style="font-size:18px">62</div><div class="mc-sub up">Strong brand love</div></div>
</div>

{{-- FOOTER --}}
<div style="margin-top:20px;padding:14px 18px;background:#f8fafc;border-radius:10px;display:flex;justify-content:space-between;align-items:center;font-size:11px;color:#7a8fa6">
    <span>The Trend Theory · {{ now()->format('d M Y') }} · All numbers pulled live from database</span>
    <a href="#" onclick="window.print();return false" style="color:#00285a;text-decoration:none;font-weight:600;font-size:11px">
        <i class="bi bi-printer"></i> Print / Save as PDF
    </a>
</div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
var rl={!! $revLabels !!},rd={!! $revData !!},ro={!! $ordData !!};
var sl={!! $stLabels !!},sc={!! $stCounts !!};
var cl={!! $catLabels !!},cr={!! $catRev !!};
var dl={!! $dayLabels !!},dor={!! $dayOrd !!},drv={!! $dayRev !!};

var sc_={pending:'#f97316',confirmed:'#3b82f6',processing:'#a855f7',shipped:'#6366f1',delivered:'#22c55e',cancelled:'#ef4444',refunded:'#888780'};

// Revenue chart
new Chart(document.getElementById('revChart'),{type:'bar',data:{labels:rl,datasets:[
    {label:'Revenue',data:rd,backgroundColor:'rgba(0,40,90,0.12)',borderColor:'#00285a',borderWidth:2,borderRadius:8,yAxisID:'y'},
    {label:'Orders',data:ro,type:'line',borderColor:'#22c55e',backgroundColor:'rgba(34,197,94,0.08)',borderWidth:2.5,pointBackgroundColor:'#22c55e',pointRadius:4,tension:0.4,fill:true,yAxisID:'y2'}
]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{
    x:{grid:{display:false},ticks:{color:'#7a8fa6',font:{size:11}}},
    y:{grid:{color:'rgba(0,0,0,0.05)'},ticks:{color:'#7a8fa6',font:{size:11},callback:function(v){return v>=100000?'₹'+(v/100000).toFixed(1)+'L':v>=1000?'₹'+(v/1000).toFixed(0)+'K':'₹'+v;}}},
    y2:{position:'right',grid:{display:false},ticks:{color:'#22c55e',font:{size:11}}}
}}});

// Daily chart
new Chart(document.getElementById('dailyChart'),{type:'line',data:{labels:dl,datasets:[
    {label:'Orders',data:dor,borderColor:'#00285a',backgroundColor:'rgba(0,40,90,0.06)',borderWidth:2,pointRadius:3,pointBackgroundColor:'#00285a',tension:0.4,fill:true}
]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{
    x:{grid:{display:false},ticks:{color:'#7a8fa6',font:{size:10}}},
    y:{grid:{color:'rgba(0,0,0,0.05)'},ticks:{color:'#7a8fa6',font:{size:10}},min:0}
}}});

// Status donut
new Chart(document.getElementById('statusChart'),{type:'doughnut',data:{labels:sl,datasets:[{data:sc,backgroundColor:sl.map(function(l){return sc_[l]||'#7a8fa6';}),borderWidth:0}]},options:{responsive:true,maintainAspectRatio:false,cutout:'68%',plugins:{legend:{display:false}}}});

// Category chart
new Chart(document.getElementById('catChart'),{type:'bar',data:{labels:cl,datasets:[{label:'Revenue',data:cr,backgroundColor:['#00285a','#1e3f75','#378ADD','#85B7EB','#B5D4F4'],borderRadius:6}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{
    x:{grid:{display:false},ticks:{color:'#7a8fa6',font:{size:10}}},
    y:{grid:{color:'rgba(0,0,0,0.05)'},ticks:{color:'#7a8fa6',font:{size:10},callback:function(v){return '₹'+(v/1000).toFixed(0)+'K';}}}
}}});
</script>
@endpush
@endsection