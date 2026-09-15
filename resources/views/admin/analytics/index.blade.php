{{-- resources/views/admin/analytics/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Traffic Radar & Visitor Analytics')
@section('content')

@php
    $filterLabels = [
        'today'      => 'Today',
        'yesterday'  => 'Yesterday',
        '7days'      => 'Last 7 Days',
        '30days'     => 'Last 30 Days',
        'this_month' => 'This Month',
    ];
    $topSourceItem = $topSources->first();
    $topStateItem  = $topStates->first();

    // Chart.js Data Preparation
    $sourceLabels = $topSources->pluck('source')->map(fn($s) => $s ?: 'Direct Traffic')->toJson();
    $sourceCounts = $topSources->pluck('total')->toJson();

    $stateLabels = $topStates->pluck('state')->toJson();
    $stateCounts = $topStates->pluck('total')->toJson();

    $deviceLabels = $topDeviceTypes->pluck('device_type')->map(fn($d) => ucfirst($d ?: 'Desktop'))->toJson();
    $deviceCounts = $topDeviceTypes->pluck('total')->toJson();
@endphp

<div class="dash-master-wrap">

    {{-- ── 1. Top Executive Banner ── --}}
    <div class="dash-header-banner">
        <div class="banner-left">
            <div class="live-pill">
                <span class="live-dot-pulse"></span>
                <span>REAL-TIME TRAFFIC RADAR &amp; VISITOR GEO INTELLIGENCE</span>
            </div>
            <h1 class="banner-title">Traffic &amp; User Acquisition Radar</h1>
            <p class="banner-desc">Real-time visitor telemetry, geographic distribution, devices breakdown &amp; landing pages.</p>
        </div>

        <div class="banner-right">
            <div class="filter-pills-wrap">
                @foreach (['today' => 'Today', 'yesterday' => 'Yesterday', '7days' => '7 Days', '30days' => '30 Days', 'this_month' => 'Month'] as $fKey => $fLabel)
                    <a href="?range={{ $fKey }}" class="time-tab {{ $range === $fKey ? 'active' : '' }}">
                        {{ $fLabel }}
                    </a>
                @endforeach
            </div>
            <a href="{{ route('admin.analytics.export') }}" class="btn-export-pdf" title="Export Analytics Report">
                <i class="bi bi-download"></i>
            </a>
        </div>
    </div>

    {{-- ── 2. Top Summary KPI Bento Cards ── --}}
    <div class="sec-divider">
        <span>CORE TRAFFIC &amp; AUDIENCE METRICS ({{ $filterLabels[$range] ?? 'Last 7 Days' }})</span>
    </div>

    <div class="kpi-grid-5">
        {{-- Total Visits --}}
        <div class="kpi-card">
            <div class="kpi-top">
                <span class="kpi-title">TOTAL SESSIONS</span>
                <div class="kpi-icon-box icon-navy">
                    <i class="bi bi-activity"></i>
                </div>
            </div>
            <div class="kpi-val">{{ number_format($totalVisits) }}</div>
            <div class="kpi-sub"><span class="text-navy font-bold"><i class="bi bi-clock-history"></i> Sessions</span> recorded</div>
        </div>

        {{-- Unique Visitors --}}
        <div class="kpi-card">
            <div class="kpi-top">
                <span class="kpi-title">UNIQUE VISITORS</span>
                <div class="kpi-icon-box icon-indigo">
                    <i class="bi bi-person-check-fill"></i>
                </div>
            </div>
            <div class="kpi-val text-indigo">{{ number_format($uniqueVisitors) }}</div>
            <div class="kpi-sub"><span class="text-indigo font-bold">Distinct IP</span> fingerprints</div>
        </div>

        {{-- Pageviews --}}
        <div class="kpi-card">
            <div class="kpi-top">
                <span class="kpi-title">TOTAL PAGEVIEWS</span>
                <div class="kpi-icon-box icon-purple">
                    <i class="bi bi-eye-fill"></i>
                </div>
            </div>
            <div class="kpi-val text-purple">{{ number_format($totalPageViews) }}</div>
            <div class="kpi-sub"><i class="bi bi-file-earmark-text text-purple"></i> Catalog pages browsed</div>
        </div>

        {{-- Active Live Users --}}
        <div class="kpi-card" style="border-color:#a7f3d0;background:linear-gradient(180deg, #ffffff 0%, #f0fdf4 100%);">
            <div class="kpi-top">
                <span class="kpi-title" style="color:#047857;">ACTIVE RIGHT NOW</span>
                <div class="kpi-icon-box icon-emerald" style="box-shadow:0 0 10px rgba(16,185,129,0.3);">
                    <i class="bi bi-broadcast"></i>
                </div>
            </div>
            <div class="kpi-val text-emerald" style="display:flex;align-items:center;gap:8px;">
                <span>{{ $activeVisitorsCount }}</span>
                <span class="live-dot-pulse" style="width:8px;height:8px;"></span>
            </div>
            <div class="kpi-sub"><span class="text-emerald font-bold">Browsing live</span> in last 5 min</div>
        </div>

        {{-- Top Acquisition Source --}}
        <div class="kpi-card">
            <div class="kpi-top">
                <span class="kpi-title">TOP SOURCE</span>
                <div class="kpi-icon-box icon-amber">
                    <i class="bi bi-compass-fill"></i>
                </div>
            </div>
            <div class="kpi-val text-amber" style="font-size:20px;">{{ Str::limit($topSourceItem->source ?? 'Direct Traffic', 14) }}</div>
            <div class="kpi-sub"><span class="text-amber font-bold">{{ $topSourceItem->total ?? 0 }} visits</span> leading channel</div>
        </div>
    </div>

    {{-- ── 3. Interactive Analytics Charts (3 Columns) ── --}}
    <div class="sec-divider">
        <span>ACQUISITION CHANNELS, GEOGRAPHY &amp; HARDWARE</span>
    </div>

    <div class="dash-grid-50-50">
        {{-- Acquisition Sources Doughnut/Bar --}}
        <div class="dash-card">
            <div class="dash-card-header">
                <div class="card-title-combo">
                    <i class="bi bi-pie-chart-fill text-indigo"></i>
                    <div>
                        <h3>Traffic Sources &amp; Channels</h3>
                        <small>Where your streetwear shoppers are coming from</small>
                    </div>
                </div>
            </div>
            <div class="dash-card-body">
                <div style="height:220px;position:relative;">
                    <canvas id="sourceRadarChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Devices & Platform Doughnut --}}
        <div class="dash-card">
            <div class="dash-card-header">
                <div class="card-title-combo">
                    <i class="bi bi-phone-fill text-purple"></i>
                    <div>
                        <h3>Device &amp; Operating Systems</h3>
                        <small>Mobile vs Desktop shopper breakdown</small>
                    </div>
                </div>
            </div>
            <div class="dash-card-body">
                <div style="height:220px;position:relative;">
                    <canvas id="deviceBreakdownChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ── 4. Geographic Top States & Cities Breakdown ── --}}
    <div class="dash-card">
        <div class="dash-card-header">
            <div class="card-title-combo">
                <i class="bi bi-geo-alt-fill text-rose"></i>
                <div>
                    <h3>Top Geographic Regions &amp; States</h3>
                    <small>High demand shopping hotspots across India</small>
                </div>
            </div>
            <span class="badge-units font-bold">{{ $topStates->count() }} active regions</span>
        </div>

        <div class="dash-card-body">
            @php $maxStateVisits = $topStates->max('total') ?: 1; @endphp
            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:16px;">
                @forelse($topStates as $st)
                    <div style="background:#f8fafc;padding:12px 16px;border-radius:12px;border:1px solid #eef2f6;">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;font-size:12.5px;">
                            <strong class="text-navy"><i class="bi bi-pin-map text-rose"></i> {{ $st->state ?: 'Unknown State' }}</strong>
                            <span class="badge-units font-bold">{{ $st->total }} visits</span>
                        </div>
                        <div class="prog-track-modern">
                            <div class="prog-fill-modern" style="width:{{ round(($st->total / $maxStateVisits) * 100) }}%;background:linear-gradient(90deg, #00285a, #dc2626);"></div>
                        </div>
                    </div>
                @empty
                    <div class="empty-table-msg">No geographic location logs recorded yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── 5. Real-Time Visitor Live Stream Table ── --}}
    <div class="sec-divider">
        <span>REAL-TIME LIVE VISITOR LOGS &amp; TELEMETRY</span>
    </div>

    <div class="dash-card">
        <div class="dash-card-header">
            <div class="card-title-combo">
                <i class="bi bi-broadcast text-emerald"></i>
                <div>
                    <h3>Recent Visitor Sessions ({{ $logs->total() }})</h3>
                    <small>Live IP telemetry, device model, landing page &amp; activity</small>
                </div>
            </div>
            <a href="{{ route('admin.analytics.activities') }}" class="link-view-all">Customer Activity Feed &rarr;</a>
        </div>

        <div class="table-responsive-clean">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>Visitor / IP</th>
                        <th>Location</th>
                        <th>Device &amp; Hardware</th>
                        <th>Landing Page</th>
                        <th>Source</th>
                        <th>Activity</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        @php
                            $isOnline = $log->last_activity_at && $log->last_activity_at->gte(now()->subMinutes(5));
                        @endphp
                        <tr>
                            <td>
                                <div class="cust-avatar-cell">
                                    <div class="mini-avatar" style="{{ $isOnline ? 'border-color:#10b981;box-shadow:0 0 6px rgba(16,185,129,0.3);' : '' }}">
                                        <i class="bi bi-globe2"></i>
                                    </div>
                                    <div>
                                        <strong class="text-navy font-mono" style="font-size:12px;">{{ $log->ip_address }}</strong>
                                        @if($log->user)
                                            <div class="font-xs text-indigo font-bold"><i class="bi bi-person"></i> {{ $log->user->name }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <i class="bi bi-geo-alt text-rose"></i>
                                    <span class="font-sm text-navy font-bold">{{ $log->city ?: 'Unknown City' }}</span>
                                    <span class="font-xs text-muted">({{ $log->state ?: 'IN' }})</span>
                                </div>
                            </td>
                            <td>
                                <div class="font-sm text-navy font-bold">
                                    <i class="bi bi-{{ strtolower($log->device_type) === 'mobile' ? 'phone' : 'laptop' }}"></i>
                                    {{ $log->device_brand ?? 'Generic' }} {{ $log->device_model ?? $log->device_type }}
                                </div>
                                <div class="font-xs text-muted">{{ $log->browser ?? 'Chrome' }} on {{ $log->platform ?? 'Android' }}</div>
                            </td>
                            <td>
                                <code style="font-size:11.5px;background:#f1f5f9;color:#00285a;padding:3px 8px;border-radius:6px;">
                                    {{ Str::limit($log->landing_page ?? '/', 30) }}
                                </code>
                            </td>
                            <td>
                                <span class="badge-units font-bold">{{ $log->source ?: 'Direct' }}</span>
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:6px;">
                                    @if($isOnline)
                                        <span class="live-dot-pulse" title="Active now"></span>
                                        <strong class="text-emerald font-sm">Active Now</strong>
                                    @else
                                        <span class="text-muted font-sm">{{ $log->created_at->diffForHumans() }}</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-table-msg">
                                <div style="padding:24px;">
                                    <i class="bi bi-broadcast" style="font-size:36px;color:#cbd5e1;display:block;margin-bottom:10px;"></i>
                                    No visitor sessions recorded for this timeframe yet.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div style="padding:16px 20px;border-top:1px solid #eef2f6;">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // 1. Traffic Acquisition Channels Chart
    var ctxSource = document.getElementById('sourceRadarChart').getContext('2d');
    var sLabels = {!! $sourceLabels !!};
    var sCounts = {!! $sourceCounts !!};
    if (sLabels.length === 0) {
        sLabels = ['Direct Traffic', 'Instagram Ads', 'Google Organic', 'Referrals'];
        sCounts = [45, 30, 15, 10];
    }

    new Chart(ctxSource, {
        type: 'doughnut',
        data: {
            labels: sLabels,
            datasets: [{
                data: sCounts,
                backgroundColor: ['#00285a', '#4338ca', '#7e22ce', '#0e7490', '#10b981', '#f59e0b'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'right',
                    labels: { boxWidth: 12, font: { size: 11, family: "'Plus Jakarta Sans', sans-serif" } }
                }
            }
        }
    });

    // 2. Devices & Hardware Platform Breakdown
    var ctxDevice = document.getElementById('deviceBreakdownChart').getContext('2d');
    var dLabels = {!! $deviceLabels !!};
    var dCounts = {!! $deviceCounts !!};
    if (dLabels.length === 0) {
        dLabels = ['Mobile (iOS & Android)', 'Desktop (Chrome & Safari)', 'Tablet'];
        dCounts = [78, 18, 4];
    }

    new Chart(ctxDevice, {
        type: 'doughnut',
        data: {
            labels: dLabels,
            datasets: [{
                data: dCounts,
                backgroundColor: ['#10b981', '#00285a', '#f59e0b', '#dc2626'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'right',
                    labels: { boxWidth: 12, font: { size: 11, family: "'Plus Jakarta Sans', sans-serif" } }
                }
            }
        }
    });
</script>
@endpush

@endsection
