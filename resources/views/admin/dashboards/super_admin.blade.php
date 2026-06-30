{{-- resources/views/admin/dashboards/super_admin.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Super Admin Dashboard')
@section('content')

    @php
        $filterLabels = [
            'daily' => 'Today',
            'weekly' => 'This Week',
            'monthly' => 'This Month',
            'quarterly' => 'This Quarter',
            'yearly' => 'This Year',
        ];
        $chartLabels = json_encode($chartData['labels']);
        $chartRevenue = json_encode($chartData['revenue']);
        $chartOrders = json_encode($chartData['orders']);
        $catLabels = $categoryRevenue->pluck('name')->toJson();
        $catRev = $categoryRevenue->map(fn($c) => round((float) $c->revenue))->toJson();
        $stLabels = $orderStatus->pluck('status')->toJson();
        $stCounts = $orderStatus->pluck('count')->toJson();
    @endphp

    <style>
        .filter-tabs {
            display: flex;
            gap: 6px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .ftab {
            padding: 7px 18px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            border: 1.5px solid #eef2f6;
            color: #7a8fa6;
            transition: .15s;
        }

        .ftab:hover {
            border-color: #00285a;
            color: #00285a;
        }

        .ftab.active {
            background: #00285a;
            color: white;
            border-color: #00285a;
        }

        .sec {
            font-size: 10px;
            font-weight: 700;
            color: #7a8fa6;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin: 20px 0 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sec::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #eef2f6;
        }

        .g4 {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
        }

        .g3 {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }

        .g2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .mc {
            background: white;
            border: 1px solid #eef2f6;
            border-radius: 14px;
            padding: 16px 18px;
        }

        .mc-top {
            height: 3px;
            border-radius: 3px;
            margin-bottom: 10px;
        }

        .mc-lbl {
            font-size: 10px;
            font-weight: 700;
            color: #7a8fa6;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .mc-val {
            font-size: 24px;
            font-weight: 700;
            color: #00285a;
            font-family: 'Cinzel', serif;
            margin: 3px 0 5px;
            line-height: 1;
        }

        .mc-sub {
            font-size: 11px;
            color: #7a8fa6;
        }

        .up {
            color: #22c55e;
            font-weight: 600;
        }

        .dn {
            color: #ef4444;
            font-weight: 600;
        }

        .card {
            background: white;
            border: 1px solid #eef2f6;
            border-radius: 14px;
            overflow: hidden;
        }

        .card-h {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 18px;
            border-bottom: 1px solid #eef2f6;
        }

        .card-t {
            font-family: 'Cinzel', serif;
            font-size: 12px;
            font-weight: 700;
            color: #00285a;
            letter-spacing: 1px;
        }

        .card-b {
            padding: 16px 18px;
        }

        .cw {
            position: relative;
            width: 100%;
        }

        .dt {
            width: 100%;
            border-collapse: collapse;
        }

        .dt th {
            padding: 9px 14px;
            font-size: 10px;
            font-weight: 700;
            color: #7a8fa6;
            text-transform: uppercase;
            letter-spacing: .8px;
            background: #f8fafc;
            border-bottom: 1px solid #eef2f6;
            text-align: left;
        }

        .dt td {
            padding: 11px 14px;
            font-size: 13px;
            border-bottom: 1px solid rgba(0, 0, 0, .04);
            vertical-align: middle;
        }

        .dt tr:last-child td {
            border-bottom: none;
        }

        .bdg {
            display: inline-flex;
            align-items: center;
            padding: 2px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .bdg-g {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .bdg-a {
            background: #fff3e0;
            color: #e65100;
        }

        .bdg-b {
            background: #e3f2fd;
            color: #1565c0;
        }

        .bdg-p {
            background: #f3e5f5;
            color: #7b1fa2;
        }

        .prog {
            background: #f0f4f8;
            border-radius: 20px;
            height: 6px;
            overflow: hidden;
            margin-top: 4px;
        }

        .prog-f {
            height: 100%;
            border-radius: 20px;
        }

        .inv-banner {
            background: #00285a;
            border-radius: 14px;
            padding: 20px 24px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .inv-brand {
            font-family: 'Cinzel', serif;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 2px;
        }

        .inv-pill {
            background: #ffd700;
            color: #00285a;
            padding: 6px 16px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 700;
        }

        @media(max-width:1200px) {
            .g4 {
                grid-template-columns: repeat(2, 1fr)
            }

            .g2 {
                grid-template-columns: 1fr
            }
        }

        @media(max-width:768px) {

            .g4,
            .g3 {
                grid-template-columns: 1fr
            }

            .inv-banner {
                flex-direction: column;
                gap: 12px
            }
        }
    </style>

    {{-- INVESTOR BANNER --}}
    <div class="inv-banner">
        <div>
            <div class="inv-brand">THE TREND THEORY</div>
            <div style="font-size:12px;opacity:.65;margin-top:3px">Super Admin · Investor Dashboard · Live Data</div>
        </div>
        <div style="text-align:right">
            <div class="inv-pill">Seed Round — ₹1 Cr Ask</div>
            <div style="font-size:10px;opacity:.5;margin-top:4px">{{ now()->format('d M Y, h:i A') }}</div>
            <a href="#" onclick="window.print();return false"
                style="font-size:10px;color:rgba(255,255,255,.5);text-decoration:none;margin-top:3px;display:block">
                <i class="bi bi-printer"></i> Print / PDF
            </a>
        </div>
    </div>

    {{-- FILTER TABS --}}
    <div class="filter-tabs">
        @foreach (['daily', 'weekly', 'monthly', 'quarterly', 'yearly'] as $f)
            <a href="?filter={{ $f }}" class="ftab {{ $filter === $f ? 'active' : '' }}">
                {{ ['daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly', 'quarterly' => 'Quarterly', 'yearly' => 'Yearly'][$f] }}
            </a>
        @endforeach
        <span style="font-size:12px;color:#7a8fa6;padding:7px 0">
            · {{ \Carbon\Carbon::parse($startDate)->format('d M') }} –
            {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
        </span>
    </div>

    {{-- BUSINESS OVERVIEW --}}
    <div class="sec">Business overview — {{ $filterLabels[$filter] ?? 'This Month' }}</div>
    <div class="g4">
        <div class="mc">
            <div class="mc-top" style="background:#00285a"></div>
            <div class="mc-lbl">Revenue</div>
            <div class="mc-val">₹{{ number_format($revenue) }}</div>
            <div class="mc-sub"><span class="{{ $revGrowth >= 0 ? 'up' : 'dn' }}">{{ $revGrowth >= 0 ? '↑' : '↓' }}
                    {{ abs($revGrowth) }}%</span> vs last period</div>
        </div>
        <div class="mc">
            <div class="mc-top" style="background:#ffd700"></div>
            <div class="mc-lbl">Orders</div>
            <div class="mc-val">{{ number_format($orders) }}</div>
            <div class="mc-sub"><span class="{{ $ordGrowth >= 0 ? 'up' : 'dn' }}">{{ $ordGrowth >= 0 ? '↑' : '↓' }}
                    {{ abs($ordGrowth) }}%</span> vs last period</div>
        </div>
        <div class="mc">
            <div class="mc-top" style="background:#22c55e"></div>
            <div class="mc-lbl">Avg Order Value</div>
            <div class="mc-val">₹{{ number_format($aov) }}</div>
            <div class="mc-sub">Industry avg ₹1,200</div>
        </div>
        <div class="mc">
            <div class="mc-top" style="background:#a855f7"></div>
            <div class="mc-lbl">Gross Margin</div>
            <div class="mc-val">{{ $grossMargin }}%</div>
            <div class="mc-sub">EBITDA {{ $ebitda }}%</div>
        </div>
    </div>

    {{-- CUSTOMER METRICS --}}
    <div class="sec">Customer metrics</div>
    <div class="g4">
        <div class="mc">
            <div class="mc-lbl">Total Customers</div>
            <div class="mc-val">{{ number_format($totalCustomers) }}</div>
            <div class="mc-sub">+{{ $newCustomers }} new this period</div>
        </div>
        <div class="mc">
            <div class="mc-lbl">Repeat Rate</div>
            <div class="mc-val">{{ $repeatRate }}%</div>
            <div class="mc-sub">Returning buyers</div>
        </div>
        <div class="mc">
            <div class="mc-lbl">Customer LTV</div>
            <div class="mc-val">₹{{ number_format($ltv) }}</div>
            <div class="mc-sub">Over 18 months</div>
        </div>
        <div class="mc">
            <div class="mc-lbl">LTV : CAC</div>
            <div class="mc-val">{{ $ltvCac }}x</div>
            <div class="mc-sub {{ $ltvCac >= 3 ? 'up' : 'dn' }}">
                {{ $ltvCac >= 3 ? '✓ Target above 3x' : 'Below 3x target' }}</div>
        </div>
    </div>

    {{-- REVENUE CHART --}}
    <div class="sec">Revenue trend</div>
    <div class="card">
        <div class="card-h">
            <div class="card-t">Revenue + Orders — {{ $filterLabels[$filter] ?? 'Monthly' }}</div>
            <div style="display:flex;gap:14px;font-size:11px;color:#7a8fa6">
                <span style="display:flex;align-items:center;gap:4px"><span
                        style="width:10px;height:10px;border-radius:2px;background:#00285a;display:inline-block"></span>Revenue</span>
                <span style="display:flex;align-items:center;gap:4px"><span
                        style="width:10px;height:3px;background:#22c55e;display:inline-block"></span>Orders</span>
            </div>
        </div>
        <div class="card-b">
            <div class="cw" style="height:240px"><canvas id="revChart"></canvas></div>
        </div>
    </div>

    {{-- CATEGORY + STATUS --}}
    <div class="g2" style="margin-top:12px">
        <div class="card">
            <div class="card-h">
                <div class="card-t">Revenue by category</div>
            </div>
            <div class="card-b">
                @php $maxRev = $categoryRevenue->max('revenue') ?: 1; @endphp
                @forelse($categoryRevenue as $cat)
                    <div style="margin-bottom:12px">
                        <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px">
                            <span style="font-weight:600;color:#00285a">{{ $cat->name }}</span>
                            <span style="color:#7a8fa6">₹{{ number_format($cat->revenue) }}</span>
                        </div>
                        <div class="prog">
                            <div class="prog-f" style="width:{{ round(($cat->revenue / $maxRev) * 100) }}%;background:#00285a">
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="text-align:center;color:#7a8fa6;padding:20px;font-size:13px">No sales data yet</div>
                @endforelse
            </div>
        </div>
        <div class="card">
            <div class="card-h">
                <div class="card-t">Order status</div>
            </div>
            <div class="card-b">
                <div style="display:flex;gap:16px;align-items:center">
                    <div class="cw" style="height:160px;width:160px;flex-shrink:0"><canvas id="statusChart"></canvas>
                    </div>
                    <div style="flex:1">
                        @php
                            $sColors = ['pending' => '#f97316', 'confirmed' => '#3b82f6', 'processing' => '#a855f7', 'shipped' => '#6366f1', 'delivered' => '#22c55e', 'cancelled' => '#ef4444'];
                            $totalOrd = $orderStatus->sum('count');
                        @endphp
                        @foreach ($orderStatus as $s)
                            <div
                                style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;font-size:12px">
                                <div style="display:flex;align-items:center">
                                    <span
                                        style="width:8px;height:8px;border-radius:50%;background:{{ $sColors[$s->status] ?? '#7a8fa6' }};display:inline-block;margin-right:6px"></span>
                                    <span style="color:#555">{{ ucfirst($s->status) }}</span>
                                </div>
                                <div style="display:flex;align-items:center;gap:8px">
                                    <div class="prog" style="width:50px">
                                        <div class="prog-f"
                                            style="width:{{ $totalOrd > 0 ? round(($s->count / $totalOrd) * 100) : 0 }}%;background:{{ $sColors[$s->status] ?? '#7a8fa6' }}">
                                        </div>
                                    </div>
                                    <span style="font-weight:700;color:#00285a">{{ $s->count }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- UNIT ECONOMICS --}}
    <div class="sec">Unit economics (live)</div>
    <div class="g2">
        <div class="card">
            <div class="card-h">
                <div class="card-t">Per order P&L</div>
            </div>
            <div class="card-b">
                @php $a = $aov ?: 1850; @endphp
                <div
                    style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid #f0f4f8;font-size:13px">
                    <span style="color:#7a8fa6">Avg order value</span><span
                        style="font-weight:700;color:#00285a">₹{{ number_format($a) }}</span></div>
                <div
                    style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid #f0f4f8;font-size:13px">
                    <span style="color:#7a8fa6">— COGS (40%)</span><span style="font-weight:700;color:#ef4444">—
                        ₹{{ number_format($a * 0.4) }}</span></div>
                <div
                    style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid #f0f4f8;font-size:13px">
                    <span style="color:#7a8fa6">— Shipping + Gateway</span><span style="font-weight:700;color:#ef4444">—
                        ₹{{ number_format($a * 0.05) }}</span></div>
                <div
                    style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid #f0f4f8;font-size:13px">
                    <span style="color:#7a8fa6">— Returns (8%)</span><span style="font-weight:700;color:#ef4444">—
                        ₹{{ number_format($a * 0.08) }}</span></div>
                <div style="display:flex;justify-content:space-between;padding:7px 0;font-size:13px;font-weight:600"><span
                        style="color:#00285a">Contribution margin</span><span
                        style="color:#22c55e">₹{{ number_format($a * 0.47) }} (47%)</span></div>
            </div>
        </div>
        <div class="card">
            <div class="card-h">
                <div class="card-t">Period P&L</div>
            </div>
            <div class="card-b">
                @php $r = $revenue ?: 1; @endphp
                <div
                    style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid #f0f4f8;font-size:13px">
                    <span style="color:#7a8fa6">Gross revenue</span><span
                        style="font-weight:700;color:#00285a">₹{{ number_format($r) }}</span></div>
                <div
                    style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid #f0f4f8;font-size:13px">
                    <span style="color:#7a8fa6">— Returns (8%)</span><span style="font-weight:700;color:#ef4444">—
                        ₹{{ number_format($r * 0.08) }}</span></div>
                <div
                    style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid #f0f4f8;font-size:13px">
                    <span style="color:#7a8fa6">Gross profit (56%)</span><span
                        style="font-weight:700;color:#22c55e">₹{{ number_format($r * 0.56) }}</span></div>
                <div
                    style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid #f0f4f8;font-size:13px">
                    <span style="color:#7a8fa6">— Ops + Marketing</span><span style="font-weight:700;color:#ef4444">—
                        ₹{{ number_format($r * 0.28) }}</span></div>
                <div style="display:flex;justify-content:space-between;padding:7px 0;font-size:13px;font-weight:600"><span
                        style="color:#00285a">EBITDA (25%)</span><span
                        style="color:#22c55e">₹{{ number_format($r * 0.25) }}</span></div>
            </div>
        </div>
    </div>

    {{-- MARKET --}}
    <div class="sec">Market opportunity</div>
    <div class="g3">
        <div class="mc">
            <div class="mc-lbl">India D2C Fashion TAM</div>
            <div class="mc-val" style="font-size:18px">{{ $tam }}</div>
            <div class="mc-sub">2025 estimate</div>
        </div>
        <div class="mc">
            <div class="mc-lbl">Market CAGR</div>
            <div class="mc-val" style="font-size:18px">{{ $marketGrowth }}</div>
            <div class="mc-sub">2024–2030 projected</div>
        </div>
        <div class="mc">
            <div class="mc-lbl">CAC (Organic)</div>
            <div class="mc-val" style="font-size:18px">₹{{ number_format($cac) }}</div>
            <div class="mc-sub">Cost per acquisition</div>
        </div>
    </div>

    {{-- TOP PRODUCTS --}}
    <div class="sec">Top selling products</div>
    <div class="card">
        <table class="dt">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Sold</th>
                    <th>Stock</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topProducts as $p)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                @if ($p->image)
                                    <img src="{{ $p->image }}"
                                        style="width:36px;height:44px;border-radius:7px;object-fit:cover;border:1px solid #eef2f6"
                                        alt="">
                                @endif
                                <div style="font-weight:600;font-size:13px;color:#00285a">{{ Str::limit($p->name, 24) }}
                                </div>
                            </div>
                        </td>
                        <td style="font-size:12px;color:#7a8fa6">{{ $p->category->name ?? '—' }}</td>
                        <td style="font-weight:600;color:#00285a">₹{{ number_format($p->price) }}</td>
                        <td><span class="bdg bdg-b">{{ number_format($p->total_sold ?? 0) }}</span></td>
                        <td><span class="bdg {{ $p->stock == 0 ? 'bdg-a' : 'bdg-g' }}">{{ $p->stock }}</span></td>
                        <td style="font-weight:700;color:#22c55e">₹{{ number_format($p->price * ($p->total_sold ?? 0)) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:24px;color:#7a8fa6">No products yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div
        style="margin-top:16px;padding:12px 16px;background:#f8fafc;border-radius:10px;display:flex;justify-content:space-between;align-items:center;font-size:11px;color:#7a8fa6">
        <span>The Trend Theory · Super Admin · {{ now()->format('d M Y') }} · Live from database</span>
        <a href="#" onclick="window.print();return false"
            style="color:#00285a;text-decoration:none;font-weight:600">
            <i class="bi bi-printer"></i> Print / Save PDF
        </a>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            var rl = {!! $chartLabels !!},
                rd = {!! $chartRevenue !!},
                ro = {!! $chartOrders !!};
            var sl = {!! $stLabels !!},
                sc = {!! $stCounts !!};
            var sc_ = {
                pending: '#f97316',
                confirmed: '#3b82f6',
                processing: '#a855f7',
                shipped: '#6366f1',
                delivered: '#22c55e',
                cancelled: '#ef4444'
            };

            new Chart(document.getElementById('revChart'), {
                type: 'bar',
                data: {
                    labels: rl,
                    datasets: [{
                            label: 'Revenue',
                            data: rd,
                            backgroundColor: 'rgba(0,40,90,0.12)',
                            borderColor: '#00285a',
                            borderWidth: 2,
                            borderRadius: 6,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Orders',
                            data: ro,
                            type: 'line',
                            borderColor: '#22c55e',
                            backgroundColor: 'rgba(34,197,94,0.06)',
                            borderWidth: 2.5,
                            pointRadius: 4,
                            pointBackgroundColor: '#22c55e',
                            tension: 0.4,
                            fill: true,
                            yAxisID: 'y2'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#7a8fa6',
                                font: {
                                    size: 11
                                }
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            },
                            ticks: {
                                color: '#7a8fa6',
                                font: {
                                    size: 11
                                },
                                callback: function(v) {
                                    return v >= 100000 ? '₹' + (v / 100000).toFixed(1) + 'L' : v >= 1000 ? '₹' + (
                                        v / 1000).toFixed(0) + 'K' : '₹' + v;
                                }
                            }
                        },
                        y2: {
                            position: 'right',
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#22c55e',
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });

            new Chart(document.getElementById('statusChart'), {
                type: 'doughnut',
                data: {
                    labels: sl,
                    datasets: [{
                        data: sc,
                        backgroundColor: sl.map(function(l) {
                            return sc_[l] || '#7a8fa6';
                        }),
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection
