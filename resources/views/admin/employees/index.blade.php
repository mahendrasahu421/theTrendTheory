{{-- resources/views/admin/employees/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Employees Directory')
@section('content')

<div class="dash-master-wrap">

    {{-- ── 1. Header Banner ── --}}
    <div class="dash-header-banner">
        <div class="banner-left">
            <div class="live-pill">
                <span class="live-dot-pulse"></span>
                <span>TEAM &amp; TALENT ROSTER</span>
            </div>
            <h1 class="banner-title">Employees Directory</h1>
            <p class="banner-desc">Manage studio staff, payroll roles, department allocations &amp; contact profiles.</p>
        </div>

        <div class="banner-right">
            <a href="{{ route('admin.employees.create') }}" class="btn-primary-hr">
                <i class="bi bi-person-plus-fill"></i> + Add New Employee
            </a>
            <a href="{{ route('admin.attendance.index') }}" class="btn-secondary-hr">
                <i class="bi bi-calendar-check-fill"></i> Attendance Logs
            </a>
        </div>
    </div>

    {{-- ── 2. Top Summary Stat Bento Cards ── --}}
    <div class="kpi-grid-4">
        {{-- Total Employees --}}
        <div class="kpi-card">
            <div class="kpi-top">
                <span class="kpi-title">TOTAL HEADCOUNT</span>
                <div class="kpi-icon-box icon-navy">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
            <div class="kpi-val">{{ $totalCount }}</div>
            <div class="kpi-sub"><span class="text-success font-bold">{{ $activeCount }} Active</span> on studio payroll</div>
        </div>

        {{-- Active Employees --}}
        <div class="kpi-card">
            <div class="kpi-top">
                <span class="kpi-title">ACTIVE STAFF</span>
                <div class="kpi-icon-box icon-emerald">
                    <i class="bi bi-person-check-fill"></i>
                </div>
            </div>
            <div class="kpi-val text-emerald">{{ $activeCount }}</div>
            <div class="kpi-sub"><i class="bi bi-check2 text-emerald"></i> Available on duty</div>
        </div>

        {{-- Departments --}}
        <div class="kpi-card">
            <div class="kpi-top">
                <span class="kpi-title">DEPARTMENTS</span>
                <div class="kpi-icon-box icon-purple">
                    <i class="bi bi-diagram-3-fill"></i>
                </div>
            </div>
            <div class="kpi-val text-purple">{{ $departments->count() ?: 3 }}</div>
            <div class="kpi-sub"><i class="bi bi-building"></i> Studio divisions</div>
        </div>

        {{-- Quick Attendance --}}
        <div class="kpi-card">
            <div class="kpi-top">
                <span class="kpi-title">DAILY LOGS</span>
                <div class="kpi-icon-box icon-amber">
                    <i class="bi bi-calendar-week-fill"></i>
                </div>
            </div>
            <div class="kpi-val text-amber">{{ now()->format('d M') }}</div>
            <div class="kpi-sub"><i class="bi bi-clock-history"></i> {{ now()->format('l') }}</div>
        </div>
    </div>

    {{-- ── 3. Filter & Search Toolbar ── --}}
    <div class="dash-card">
        <div class="dash-card-body" style="padding:16px 20px;">
            <form method="GET" action="{{ route('admin.employees.index') }}" style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
                <div style="flex:1;min-width:240px;position:relative;">
                    <i class="bi bi-search" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#94a3b8;"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, employee ID, role..." class="form-control-admin" style="padding-left:38px;">
                </div>

                @if($departments->count() > 0)
                    <div style="min-width:180px;">
                        <select name="department" class="form-control-admin" onchange="this.form.submit()">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div style="min-width:140px;">
                    <select name="status" class="form-control-admin" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="on_leave" {{ request('status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                    </select>
                </div>

                <button type="submit" class="btn-admin btn-navy">
                    <i class="bi bi-funnel-fill"></i> Filter
                </button>

                @if(request()->hasAny(['search', 'department', 'status']))
                    <a href="{{ route('admin.employees.index') }}" class="btn-admin btn-light">
                        <i class="bi bi-x-circle"></i> Clear
                    </a>
                @endif
            </form>
        </div>
    </div>

    {{-- ── 4. Employees Table ── --}}
    <div class="dash-card">
        <div class="dash-card-header">
            <div class="card-title-combo">
                <i class="bi bi-people-fill text-indigo"></i>
                <div>
                    <h3>Staff Members ({{ $employees->total() }})</h3>
                    <small>Active payroll staff &amp; team members</small>
                </div>
            </div>
            <a href="{{ route('admin.employees.create') }}" class="btn-admin btn-navy btn-sm">
                <i class="bi bi-plus-lg"></i> + Add Employee
            </a>
        </div>

        <div class="table-responsive-clean">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>ID Code</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Joining Date</th>
                        <th>Status</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $emp)
                        <tr class="clickable-row" onclick="window.location='{{ route('admin.employees.edit', $emp) }}'">
                            <td>
                                <div class="cust-avatar-cell">
                                    <div class="mini-avatar">{{ strtoupper(substr($emp->name, 0, 2)) }}</div>
                                    <div>
                                        <strong class="text-navy" style="font-size:13.5px;">{{ $emp->name }}</strong>
                                        <div class="font-xs text-muted">{{ $emp->email ?? $emp->phone ?? 'No contact info' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <code style="font-size:11.5px;background:#f1f5f9;color:#00285a;padding:3px 8px;border-radius:6px;font-weight:700;">
                                    {{ $emp->employee_id ?? 'EMP-'.$emp->id }}
                                </code>
                            </td>
                            <td>
                                <span class="badge-units font-bold">{{ $emp->department ?? 'General' }}</span>
                            </td>
                            <td>
                                <span class="font-sm font-bold text-navy">{{ $emp->designation ?? 'Team Member' }}</span>
                            </td>
                            <td>
                                <span class="font-sm text-muted">{{ $emp->joining_date ? $emp->joining_date->format('d M Y') : '—' }}</span>
                            </td>
                            <td>
                                @if($emp->status === 'active')
                                    <span class="badge-pay pay-paid"><i class="bi bi-check-circle-fill"></i> Active</span>
                                @elseif($emp->status === 'on_leave')
                                    <span class="badge-pay pay-pending"><i class="bi bi-cup-hot-fill"></i> On Leave</span>
                                @else
                                    <span class="badge-pay pay-failed"><i class="bi bi-x-circle-fill"></i> Inactive</span>
                                @endif
                            </td>
                            <td style="text-align:right;">
                                <div style="display:inline-flex;gap:6px;">
                                    <a href="{{ route('admin.employees.edit', $emp) }}" class="btn-circle-edit" title="Edit Employee" onclick="event.stopPropagation();">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.employees.destroy', $emp) }}" onsubmit="return confirm('Are you sure you want to delete employee {{ $emp->name }}?')" style="display:inline;" onclick="event.stopPropagation();">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-circle-edit" style="color:#ef4444;" title="Delete">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-table-msg">
                                <div style="padding:32px 20px;">
                                    <div style="width:60px;height:60px;border-radius:50%;background:#eff6ff;color:#00285a;display:inline-flex;align-items:center;justify-content:center;font-size:26px;margin-bottom:14px;">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <h4 style="font-size:16px;font-weight:700;color:#00285a;margin-bottom:6px;">No Employees Found</h4>
                                    <p style="font-size:12.5px;color:#64748b;margin-bottom:16px;">Start building your studio workforce by onboarding your first team member.</p>
                                    <a href="{{ route('admin.employees.create') }}" class="btn-primary-hr" style="display:inline-flex;">
                                        <i class="bi bi-person-plus-fill"></i> + Onboard First Employee
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($employees->hasPages())
            <div style="padding:16px 20px;border-top:1px solid #eef2f6;">
                {{ $employees->links() }}
            </div>
        @endif
    </div>

</div>

@endsection
