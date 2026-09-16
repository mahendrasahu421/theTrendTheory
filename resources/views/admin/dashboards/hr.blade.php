{{-- resources/views/admin/dashboards/hr.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'HR & Team Operations Dashboard')
@section('content')

<style>
/* ── Modern 2026 HR & Operations Studio Styles ── */
.dash-master-wrap {
    display: flex;
    flex-direction: column;
    gap: 20px;
    padding-bottom: 40px;
}

/* 1. Header Banner */
.dash-header-banner {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 20px 24px;
    color: #00285a;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    box-shadow: 0 4px 20px rgba(0, 40, 90, 0.04);
}

.live-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #eff6ff;
    color: #1e40af;
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1px;
    margin-bottom: 6px;
    border: 1px solid #dbeafe;
}

.live-dot-pulse {
    width: 6px;
    height: 6px;
    background: #10b981;
    border-radius: 50%;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.35);
}

.banner-title {
    font-size: 20px;
    font-weight: 800;
    color: #00285a;
    margin: 0 0 4px;
}

.banner-desc {
    font-size: 13px;
    color: #64748b;
    margin: 0;
}

.banner-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-primary-hr {
    background: #00285a;
    color: #ffffff;
    padding: 8px 16px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.15s ease;
    box-shadow: 0 4px 12px rgba(0, 40, 90, 0.15);
}

.btn-primary-hr:hover {
    background: #1e40af;
    color: #ffffff;
}

.btn-secondary-hr {
    background: #f8fafc;
    color: #00285a;
    padding: 8px 16px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid #e2e8f0;
    transition: all 0.15s ease;
}

.btn-secondary-hr:hover {
    background: #edf2f7;
    color: #00285a;
}

/* 2. Section Dividers */
.sec-divider {
    font-size: 11px;
    font-weight: 800;
    color: #64748b;
    letter-spacing: 1px;
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 4px 0 -4px;
}

.sec-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e2e8f0;
}

/* 3. KPI Grid & Cards */
.kpi-grid-5 {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 14px;
}

@media(max-width: 1200px) {
    .kpi-grid-5 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
}

@media(max-width: 768px) {
    .kpi-grid-5 { grid-template-columns: 1fr; }
}

.kpi-card-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.kpi-card {
    background: #ffffff;
    border: 1.5px solid #eef2f6;
    border-radius: 16px;
    padding: 18px 20px;
    box-shadow: 0 4px 16px -2px rgba(0, 40, 90, 0.02);
    display: flex;
    flex-direction: column;
    gap: 6px;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    cursor: pointer;
}

.kpi-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 28px -6px rgba(0, 40, 90, 0.12);
    border-color: #00285a;
}

.kpi-hover-arrow {
    font-size: 18px;
    margin-left: auto;
    opacity: 0;
    transform: translateX(-4px);
    transition: all 0.2s ease;
}

.kpi-card:hover .kpi-hover-arrow {
    opacity: 1;
    transform: translateX(0);
    color: #00285a;
}

.kpi-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.kpi-title {
    font-size: 10.5px;
    font-weight: 800;
    color: #64748b;
    letter-spacing: 0.5px;
}

.kpi-icon-box {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.icon-navy { background: #eff6ff; color: #00285a; }
.icon-emerald { background: #ecfdf5; color: #047857; }
.icon-rose { background: #fee2e2; color: #dc2626; }
.icon-amber { background: #fef3c7; color: #b45309; }
.icon-purple { background: #faf5ff; color: #7e22ce; }

.kpi-val {
    font-family: 'Cinzel', serif;
    font-size: 24px;
    font-weight: 700;
    color: #00285a;
    line-height: 1.1;
    margin: 2px 0;
}

.kpi-sub {
    font-size: 11px;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* 4. Dash Cards & Layouts */
.dash-card {
    background: #ffffff;
    border: 1px solid #eef2f6;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 4px 20px -2px rgba(0, 40, 90, 0.03);
}

.dash-card-header {
    padding: 16px 22px;
    border-bottom: 1px solid #eef2f6;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    background: #ffffff;
}

.card-title-combo {
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-title-combo i {
    font-size: 20px;
}

.card-title-combo h3 {
    font-size: 14.5px;
    font-weight: 800;
    color: #00285a;
    margin: 0;
}

.card-title-combo small {
    font-size: 11px;
    color: #64748b;
}

.dash-card-body {
    padding: 20px 22px;
}

.dash-grid-70-30 {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 18px;
}

.dash-grid-50-50 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}

@media(max-width: 1024px) {
    .dash-grid-70-30, .dash-grid-50-50 {
        grid-template-columns: 1fr;
    }
}

.link-view-all {
    font-size: 11.5px;
    font-weight: 700;
    color: #4338ca;
    text-decoration: none;
}

.link-view-all:hover {
    text-decoration: underline;
}

/* 5. Stat Pill Rows & Bars */
.stat-pill-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 14px;
    background: #f8fafc;
    border-radius: 10px;
    border: 1px solid #eef2f6;
    transition: background 0.15s ease;
}

.stat-pill-row:hover {
    background: #ffffff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.prog-track-modern {
    background: #f1f5f9;
    border-radius: 999px;
    height: 8px;
    overflow: hidden;
}

.prog-fill-modern {
    height: 100%;
    border-radius: 999px;
    transition: width 0.4s ease;
}

/* 6. Table & Clickable Rows */
.table-responsive-clean {
    overflow-x: auto;
}

.dash-table {
    width: 100%;
    border-collapse: collapse;
}

.dash-table th {
    padding: 11px 18px;
    background: #f8fafc;
    border-bottom: 1px solid #eef2f6;
    font-size: 10.5px;
    font-weight: 800;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    text-align: left;
}

.dash-table td {
    padding: 12px 18px;
    border-bottom: 1px solid #f8fafc;
    vertical-align: middle;
    font-size: 12.5px;
}

.clickable-row {
    cursor: pointer;
    transition: background 0.15s ease;
}

.clickable-row:hover td {
    background: #f0f7ff !important;
}

.btn-circle-edit {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #f1f5f9;
    color: #00285a;
    text-decoration: none;
    font-size: 11px;
    transition: all 0.15s ease;
}

.btn-circle-edit:hover {
    background: #00285a;
    color: #ffffff;
}

.cust-avatar-cell {
    display: flex;
    align-items: center;
    gap: 10px;
}

.mini-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #eff6ff;
    color: #00285a;
    font-size: 11.5px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #bfdbfe;
    flex-shrink: 0;
}

/* 7. Badges */
.badge-pay {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 10.5px;
    font-weight: 700;
}

.pay-paid { background: #ecfdf5; color: #047857; }
.pay-failed { background: #fee2e2; color: #dc2626; }
.pay-pending { background: #fffbeb; color: #b45309; }

.badge-order {
    display: inline-flex;
    align-items: center;
    padding: 3px 9px;
    border-radius: 999px;
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
}

.status-pending { background: #fffbeb; color: #b45309; }

.badge-units {
    font-size: 11px;
    color: #475569;
    background: #f1f5f9;
    padding: 3px 8px;
    border-radius: 6px;
}

/* 8. Quick Actions & Policy */
.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
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
    padding: 14px;
}

.notif-feed-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
    padding-bottom: 6px;
    border-bottom: 1px solid #e2e8f0;
}

/* Typography & colors */
.text-navy { color: #00285a; }
.text-indigo { color: #4338ca; }
.text-purple { color: #7e22ce; }
.text-emerald { color: #047857; }
.text-amber { color: #b45309; }
.text-rose { color: #dc2626; }
.font-bold { font-weight: 700; }
.font-sm { font-size: 11.5px; }
.font-xs { font-size: 10.5px; }
.empty-table-msg { text-align: center; color: #94a3b8; padding: 24px; font-size: 12px; }
</style>

<div class="dash-master-wrap">


    {{-- ── 2. Top Summary KPI Cards (Clickable) ── --}}
    <div class="sec-divider">
        <span>TODAY'S WORKFORCE HEALTH &amp; ATTENDANCE ({{ now()->format('d M Y') }})</span>
    </div>

    <div class="kpi-grid-5">
        {{-- Total Employees --}}
        <a href="{{ route('admin.employees.index') }}" class="kpi-card-link" title="Click to view employees">
            <div class="kpi-card">
                <div class="kpi-top">
                    <span class="kpi-title">TOTAL HEADCOUNT</span>
                    <div class="kpi-icon-box icon-navy">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
                <div class="kpi-val">{{ $totalEmployees }}</div>
                <div class="kpi-sub">
                    <span class="text-success font-bold">{{ $activeEmp }} active</span> on payroll
                    <i class="bi bi-arrow-right-short kpi-hover-arrow"></i>
                </div>
            </div>
        </a>

        {{-- Present Today --}}
        <a href="{{ route('admin.attendance.index') }}" class="kpi-card-link" title="Click to view attendance logs">
            <div class="kpi-card">
                <div class="kpi-top">
                    <span class="kpi-title">PRESENT TODAY</span>
                    <div class="kpi-icon-box icon-emerald">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                </div>
                <div class="kpi-val text-emerald">{{ $presentToday }}</div>
                <div class="kpi-sub">
                    <span class="text-emerald font-bold">{{ $attendanceRate }}%</span> attendance rate
                    <i class="bi bi-arrow-right-short kpi-hover-arrow"></i>
                </div>
            </div>
        </a>

        {{-- Absent Today --}}
        <a href="{{ route('admin.attendance.index') }}" class="kpi-card-link" title="Click to view attendance logs">
            <div class="kpi-card">
                <div class="kpi-top">
                    <span class="kpi-title">ABSENT TODAY</span>
                    <div class="kpi-icon-box icon-rose">
                        <i class="bi bi-person-x-fill"></i>
                    </div>
                </div>
                <div class="kpi-val text-rose">{{ $absentToday }}</div>
                <div class="kpi-sub">
                    <span class="{{ $absentToday > 0 ? 'text-rose font-bold' : 'text-muted' }}">{{ $absentToday > 0 ? 'Unscheduled absence' : 'Full attendance' }}</span>
                    <i class="bi bi-arrow-right-short kpi-hover-arrow"></i>
                </div>
            </div>
        </a>

        {{-- On Leave Today --}}
        <a href="{{ route('admin.attendance.index') }}" class="kpi-card-link" title="Click to view leave logs">
            <div class="kpi-card">
                <div class="kpi-top">
                    <span class="kpi-title">ON LEAVE / OFF</span>
                    <div class="kpi-icon-box icon-amber">
                        <i class="bi bi-cup-hot-fill"></i>
                    </div>
                </div>
                <div class="kpi-val text-amber">{{ $onLeaveToday }}</div>
                <div class="kpi-sub">
                    <i class="bi bi-clock-history text-amber"></i> Approved leaves
                    <i class="bi bi-arrow-right-short kpi-hover-arrow"></i>
                </div>
            </div>
        </a>

        {{-- Unverified / Not Logged --}}
        <a href="{{ route('admin.attendance.index') }}" class="kpi-card-link" title="Click to log attendance">
            <div class="kpi-card">
                <div class="kpi-top">
                    <span class="kpi-title">PENDING CHECK-IN</span>
                    <div class="kpi-icon-box icon-purple">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
                <div class="kpi-val text-purple">{{ $notMarked }}</div>
                <div class="kpi-sub">
                    <span class="{{ $notMarked > 0 ? 'text-purple font-bold' : 'text-muted' }}">{{ $notMarked > 0 ? 'Awaiting morning log' : 'All logged' }}</span>
                    <i class="bi bi-arrow-right-short kpi-hover-arrow"></i>
                </div>
            </div>
        </a>
    </div>

    {{-- ── 3. Monthly Attendance Analytics & Department Distribution ── --}}
    <div class="sec-divider">
        <span>MONTHLY ATTENDANCE TREND &amp; DEPARTMENTS</span>
    </div>

    <div class="dash-grid-50-50">
        {{-- Monthly Attendance Chart --}}
        <div class="dash-card">
            <div class="dash-card-header">
                <div class="card-title-combo">
                    <i class="bi bi-pie-chart-fill text-indigo"></i>
                    <div>
                        <h3>Monthly Attendance Breakdown ({{ now()->format('F Y') }})</h3>
                        <small>Cumulative presence vs absences this calendar month</small>
                    </div>
                </div>
            </div>

            <div class="dash-card-body">
                <div style="display:flex;align-items:center;gap:24px;flex-wrap:wrap;">
                    <div style="height:170px;width:170px;flex-shrink:0;position:relative;">
                        <canvas id="hrMonthlyDoughnut"></canvas>
                    </div>
                    <div style="flex:1;min-width:180px;display:flex;flex-direction:column;gap:10px;">
                        <div class="stat-pill-row">
                            <div style="display:flex;align-items:center;gap:8px;">
                                <span style="width:10px;height:10px;border-radius:50%;background:#10b981;"></span>
                                <span class="font-sm font-bold text-navy">Total Present Days</span>
                            </div>
                            <strong class="text-emerald font-sm">{{ $presentMonth }}</strong>
                        </div>
                        <div class="stat-pill-row">
                            <div style="display:flex;align-items:center;gap:8px;">
                                <span style="width:10px;height:10px;border-radius:50%;background:#ef4444;"></span>
                                <span class="font-sm font-bold text-navy">Total Absences</span>
                            </div>
                            <strong class="text-rose font-sm">{{ $absentMonth }}</strong>
                        </div>
                        <div class="stat-pill-row">
                            <div style="display:flex;align-items:center;gap:8px;">
                                <span style="width:10px;height:10px;border-radius:50%;background:#f59e0b;"></span>
                                <span class="font-sm font-bold text-navy">Total Leave Days</span>
                            </div>
                            <strong class="text-amber font-sm">{{ $leaveMonth }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Department Distribution --}}
        <div class="dash-card">
            <div class="dash-card-header">
                <div class="card-title-combo">
                    <i class="bi bi-diagram-3-fill text-purple"></i>
                    <div>
                        <h3>Team Strength by Department</h3>
                        <small>Headcount allocation across studio divisions</small>
                    </div>
                </div>
            </div>

            <div class="dash-card-body">
                @php $maxDept = $deptBreakdown->max('count') ?: 1; @endphp
                <div style="display:flex;flex-direction:column;gap:12px;">
                    @forelse($deptBreakdown as $dept)
                        <div>
                            <div style="display:flex;justify-content:space-between;font-size:12.5px;margin-bottom:5px;">
                                <strong class="text-navy"><i class="bi bi-briefcase text-indigo"></i> {{ $dept->department ?? 'General' }}</strong>
                                <span class="badge-units font-bold">{{ $dept->count }} members</span>
                            </div>
                            <div class="prog-track-modern">
                                <div class="prog-fill-modern" style="width:{{ round(($dept->count / $maxDept) * 100) }}%;background:linear-gradient(90deg, #00285a, #4338ca);"></div>
                            </div>
                        </div>
                    @empty
                        {{-- Fallback department sample mockup visualization --}}
                        <div>
                            <div style="display:flex;justify-content:space-between;font-size:12.5px;margin-bottom:5px;">
                                <strong class="text-navy"><i class="bi bi-briefcase text-indigo"></i> Design &amp; Styling</strong>
                                <span class="badge-units font-bold">4 members</span>
                            </div>
                            <div class="prog-track-modern"><div class="prog-fill-modern" style="width:80%;background:linear-gradient(90deg, #00285a, #4338ca);"></div></div>
                        </div>
                        <div>
                            <div style="display:flex;justify-content:space-between;font-size:12.5px;margin-bottom:5px;">
                                <strong class="text-navy"><i class="bi bi-box-seam text-indigo"></i> Warehouse &amp; Logistics</strong>
                                <span class="badge-units font-bold">6 members</span>
                            </div>
                            <div class="prog-track-modern"><div class="prog-fill-modern" style="width:100%;background:linear-gradient(90deg, #00285a, #4338ca);"></div></div>
                        </div>
                        <div>
                            <div style="display:flex;justify-content:space-between;font-size:12.5px;margin-bottom:5px;">
                                <strong class="text-navy"><i class="bi bi-headset text-indigo"></i> Customer Operations</strong>
                                <span class="badge-units font-bold">3 members</span>
                            </div>
                            <div class="prog-track-modern"><div class="prog-fill-modern" style="width:50%;background:linear-gradient(90deg, #00285a, #4338ca);"></div></div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- ── 4. Live Employee Roster Table & Quick HR Actions ── --}}
    <div class="dash-grid-70-30">
        {{-- Employee Directory Roster --}}
        <div class="dash-card">
            <div class="dash-card-header">
                <div class="card-title-combo">
                    <i class="bi bi-person-lines-fill text-indigo"></i>
                    <div>
                        <h3>Active Employee Roster</h3>
                        <small>Team members, today's status &amp; monthly attendance</small>
                    </div>
                </div>
                <a href="{{ route('admin.employees.index') }}" class="link-view-all">View All Employees &rarr;</a>
            </div>

            <div class="table-responsive-clean">
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Role / Dept</th>
                            <th>Today's Status</th>
                            <th>Monthly Log</th>
                            <th style="text-align:right;">Profile</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $emp)
                            @php
                                $todayAtt = $emp->attendance->where('date', today()->format('Y-m-d'))->first();
                                $monthPresent = $emp->attendance->where('status', 'present')->count();
                            @endphp
                            <tr class="clickable-row" onclick="window.location='{{ route('admin.employees.index') }}'">
                                <td>
                                    <div class="cust-avatar-cell">
                                        <div class="mini-avatar">{{ strtoupper(substr($emp->name, 0, 2)) }}</div>
                                        <div>
                                            <strong class="text-navy">{{ $emp->name }}</strong>
                                            <div class="font-xs text-muted">{{ $emp->email ?? $emp->phone ?? 'EMP-'.$emp->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="font-sm text-navy font-bold">{{ $emp->designation ?? 'Team Member' }}</div>
                                    <div class="font-xs text-muted">{{ $emp->department ?? 'General' }}</div>
                                </td>
                                <td>
                                    @if($todayAtt)
                                        @if($todayAtt->status === 'present')
                                            <span class="badge-pay pay-paid"><i class="bi bi-check-circle-fill"></i> Present</span>
                                        @elseif($todayAtt->status === 'absent')
                                            <span class="badge-pay pay-failed"><i class="bi bi-x-circle-fill"></i> Absent</span>
                                        @elseif($todayAtt->status === 'on_leave')
                                            <span class="badge-pay pay-pending"><i class="bi bi-cup-hot-fill"></i> On Leave</span>
                                        @endif
                                    @else
                                        <span class="badge-order status-pending">Not Marked</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge-units font-bold">{{ $monthPresent }} days logged</span>
                                </td>
                                <td style="text-align:right;">
                                    <a href="{{ route('admin.employees.index') }}" class="btn-circle-edit" onclick="event.stopPropagation();">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-table-msg">
                                    <div style="padding:16px;">
                                        <i class="bi bi-people" style="font-size:32px;color:#cbd5e1;display:block;margin-bottom:8px;"></i>
                                        No employee profiles recorded yet.
                                        <div style="margin-top:10px;">
                                            <a href="{{ route('admin.employees.create') }}" class="btn-primary-hr" style="display:inline-flex;">+ Add First Employee</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- HR Quick Actions & Policy Card --}}
        <div class="dash-card">
            <div class="dash-card-header">
                <div class="card-title-combo">
                    <i class="bi bi-lightning-charge-fill text-amber"></i>
                    <div>
                        <h3>HR Shortcuts Hub</h3>
                        <small>Quick operations &amp; tools</small>
                    </div>
                </div>
            </div>

            <div class="dash-card-body">
                <div class="quick-actions-grid" style="margin-bottom:16px;">
                    <a href="{{ route('admin.employees.index') }}" class="quick-act-btn">
                        <i class="bi bi-person-plus-fill text-indigo"></i>
                        <span>Directory</span>
                    </a>
                    <a href="{{ route('admin.attendance.index') }}" class="quick-act-btn">
                        <i class="bi bi-calendar2-check-fill text-emerald"></i>
                        <span>Attendance</span>
                    </a>
                    <a href="{{ route('admin.staff.index') }}" class="quick-act-btn">
                        <i class="bi bi-shield-lock-fill text-purple"></i>
                        <span>Permissions</span>
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="quick-act-btn">
                        <i class="bi bi-speedometer2 text-navy"></i>
                        <span>Store View</span>
                    </a>
                </div>

                <div class="notif-feed-box">
                    <div class="notif-feed-head">
                        <span class="font-bold font-sm text-navy"><i class="bi bi-info-circle-fill text-indigo"></i> HR POLICY BRIEF</span>
                    </div>
                    <p class="font-xs text-muted mb-0" style="line-height:1.6;">
                        Attendance must be submitted every working morning by 11:00 AM. For overtime calculation or shift changes, navigate to the Attendance Logs portal.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    var ctxHr = document.getElementById('hrMonthlyDoughnut').getContext('2d');
    var pCount = {{ (int)$presentMonth }};
    var aCount = {{ (int)$absentMonth }};
    var lCount = {{ (int)$leaveMonth }};
    
    // Provide default mockup distribution if month data is fresh 0
    var chartDataVals = (pCount === 0 && aCount === 0 && lCount === 0) ? [85, 10, 5] : [pCount, aCount, lCount];

    new Chart(ctxHr, {
        type: 'doughnut',
        data: {
            labels: ['Present Days', 'Absences', 'Leave Days'],
            datasets: [{
                data: chartDataVals,
                backgroundColor: ['#10b981', '#ef4444', '#f59e0b'],
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
