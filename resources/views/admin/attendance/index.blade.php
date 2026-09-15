{{-- resources/views/admin/attendance/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Attendance & Daily Check-ins')
@section('content')

<div class="dash-master-wrap">

    {{-- ── 1. Header Banner ── --}}
    <div class="dash-header-banner">
        <div class="banner-left">
            <div class="live-pill">
                <span class="live-dot-pulse"></span>
                <span>ATTENDANCE LOGS &amp; DAILY ROSTER</span>
            </div>
            <h1 class="banner-title">Daily Team Attendance</h1>
            <p class="banner-desc">Mark and inspect daily staff presence, check-in timestamps, and leave status.</p>
        </div>

        <div class="banner-right">
            <a href="{{ route('admin.employees.index') }}" class="btn-secondary-hr">
                <i class="bi bi-people-fill"></i> Staff Directory
            </a>
            <a href="{{ route('admin.dashboard.hr') }}" class="btn-primary-hr">
                <i class="bi bi-speedometer2"></i> HR Dashboard
            </a>
        </div>
    </div>

    {{-- ── 2. Date Selector & Daily Stats Toolbar ── --}}
    <div class="dash-card">
        <div class="dash-card-body" style="padding:16px 20px;">
            <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
                <form method="GET" action="{{ route('admin.attendance.index') }}" style="display:flex;align-items:center;gap:10px;">
                    <label style="font-size:12px;font-weight:700;color:#00285a;"><i class="bi bi-calendar-event"></i> Select Date:</label>
                    <input type="date" name="date" value="{{ $selectedDate }}" class="form-control-admin" style="width:auto;" onchange="this.form.submit()">
                    <button type="submit" class="btn-admin btn-navy btn-sm">View</button>
                    @if($selectedDate !== today()->format('Y-m-d'))
                        <a href="{{ route('admin.attendance.index') }}" class="btn-admin btn-light btn-sm">Today</a>
                    @endif
                </form>

                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <span class="badge-pay pay-paid"><i class="bi bi-check-circle-fill"></i> {{ $presentCount }} Present</span>
                    <span class="badge-pay pay-failed"><i class="bi bi-x-circle-fill"></i> {{ $absentCount }} Absent</span>
                    <span class="badge-pay pay-pending"><i class="bi bi-cup-hot-fill"></i> {{ $leaveCount }} On Leave</span>
                    <span class="badge-order status-pending">{{ $unmarkedCount }} Pending</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── 3. Attendance Roster Form ── --}}
    <div class="dash-card">
        <div class="dash-card-header">
            <div class="card-title-combo">
                <i class="bi bi-card-checklist text-indigo"></i>
                <div>
                    <h3>Attendance Roster &bull; {{ \Carbon\Carbon::parse($selectedDate)->format('l, d F Y') }}</h3>
                    <small>Configure today's presence and check-in logs</small>
                </div>
            </div>

            <div style="display:flex;gap:8px;">
                <button type="button" class="btn-admin btn-light btn-sm" onclick="markAll('present')">Mark All Present</button>
                <button type="button" class="btn-admin btn-light btn-sm" onclick="markAll('absent')">Mark All Absent</button>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.attendance.store') }}">
            @csrf
            <input type="hidden" name="date" value="{{ $selectedDate }}">

            <div class="table-responsive-clean">
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Check In Time</th>
                            <th>Check Out Time</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $emp)
                            @php
                                $att = $attendances->get($emp->id);
                                $currentStatus = $att->status ?? 'present';
                            @endphp
                            <tr>
                                <td>
                                    <div class="cust-avatar-cell">
                                        <div class="mini-avatar">{{ strtoupper(substr($emp->name, 0, 2)) }}</div>
                                        <div>
                                            <strong class="text-navy">{{ $emp->name }}</strong>
                                            <div class="font-xs text-muted">{{ $emp->employee_id ?? 'EMP-'.$emp->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-units font-bold">{{ $emp->department ?? 'General' }}</span>
                                </td>
                                <td>
                                    <div style="display:flex;gap:6px;">
                                        <label class="btn-status-radio">
                                            <input type="radio" name="attendance[{{ $emp->id }}][status]" value="present" class="att-status-input" data-emp="{{ $emp->id }}" {{ $currentStatus === 'present' ? 'checked' : '' }}>
                                            <span class="status-chip chip-present">Present</span>
                                        </label>
                                        <label class="btn-status-radio">
                                            <input type="radio" name="attendance[{{ $emp->id }}][status]" value="absent" class="att-status-input" data-emp="{{ $emp->id }}" {{ $currentStatus === 'absent' ? 'checked' : '' }}>
                                            <span class="status-chip chip-absent">Absent</span>
                                        </label>
                                        <label class="btn-status-radio">
                                            <input type="radio" name="attendance[{{ $emp->id }}][status]" value="on_leave" class="att-status-input" data-emp="{{ $emp->id }}" {{ $currentStatus === 'on_leave' ? 'checked' : '' }}>
                                            <span class="status-chip chip-leave">Leave</span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <input type="time" name="attendance[{{ $emp->id }}][check_in]" value="{{ $att->check_in ?? '09:30' }}" class="form-control-admin" style="padding:4px 8px;font-size:12px;width:110px;">
                                </td>
                                <td>
                                    <input type="time" name="attendance[{{ $emp->id }}][check_out]" value="{{ $att->check_out ?? '18:30' }}" class="form-control-admin" style="padding:4px 8px;font-size:12px;width:110px;">
                                </td>
                                <td>
                                    <input type="text" name="attendance[{{ $emp->id }}][notes]" value="{{ $att->notes ?? '' }}" placeholder="Optional notes..." class="form-control-admin" style="padding:4px 8px;font-size:12px;min-width:140px;">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-table-msg">
                                    <div style="padding:24px;">
                                        <i class="bi bi-people" style="font-size:36px;color:#cbd5e1;display:block;margin-bottom:10px;"></i>
                                        No active staff members found. Add employees in the directory first.
                                        <div style="margin-top:12px;">
                                            <a href="{{ route('admin.employees.create') }}" class="btn-primary-hr" style="display:inline-flex;">+ Add New Employee</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($employees->count() > 0)
                <div style="padding:18px 24px;border-top:1px solid #eef2f6;display:flex;justify-content:flex-end;">
                    <button type="submit" class="btn-admin btn-navy" style="padding:10px 24px;font-size:13px;">
                        <i class="bi bi-check2-all"></i> Save Attendance Records
                    </button>
                </div>
            @endif
        </form>
    </div>

</div>

<style>
.btn-status-radio {
    cursor: pointer;
    margin: 0;
}

.btn-status-radio input {
    display: none;
}

.status-chip {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 700;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    color: #64748b;
    transition: all 0.15s ease;
}

.btn-status-radio input:checked + .chip-present {
    background: #ecfdf5;
    color: #047857;
    border-color: #a7f3d0;
    box-shadow: 0 2px 6px rgba(4, 120, 87, 0.15);
}

.btn-status-radio input:checked + .chip-absent {
    background: #fee2e2;
    color: #dc2626;
    border-color: #fecaca;
    box-shadow: 0 2px 6px rgba(220, 38, 38, 0.15);
}

.btn-status-radio input:checked + .chip-leave {
    background: #fef3c7;
    color: #b45309;
    border-color: #fde68a;
    box-shadow: 0 2px 6px rgba(180, 83, 9, 0.15);
}
</style>

@push('scripts')
<script>
function markAll(status) {
    document.querySelectorAll('.att-status-input').forEach(function(input) {
        if (input.value === status) {
            input.checked = true;
        }
    });
}
</script>
@endpush

@endsection
