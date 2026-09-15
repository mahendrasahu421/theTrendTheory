{{-- resources/views/admin/employees/form.blade.php --}}
@extends('admin.layouts.app')
@section('title', $employee->exists ? 'Edit Employee' : 'Add New Employee')
@section('content')

<div class="dash-master-wrap" style="max-width:900px;margin:0 auto;">

    {{-- ── 1. Header Banner ── --}}
    <div class="dash-header-banner">
        <div class="banner-left">
            <div class="live-pill">
                <span class="live-dot-pulse"></span>
                <span>{{ $employee->exists ? 'EDIT PROFILE' : 'EMPLOYEE ONBOARDING' }}</span>
            </div>
            <h1 class="banner-title">{{ $employee->exists ? 'Edit: '.$employee->name : 'Add New Employee' }}</h1>
            <p class="banner-desc">Configure employee contact credentials, department assignment, salary, and active status.</p>
        </div>

        <div class="banner-right">
            <a href="{{ route('admin.employees.index') }}" class="btn-secondary-hr">
                <i class="bi bi-arrow-left"></i> Back to Directory
            </a>
        </div>
    </div>

    {{-- ── 2. Form Card ── --}}
    <div class="dash-card">
        <div class="dash-card-header">
            <div class="card-title-combo">
                <i class="bi bi-person-badge-fill text-indigo"></i>
                <div>
                    <h3>Employee Details</h3>
                    <small>Fill in required team profile information</small>
                </div>
            </div>
        </div>

        <div class="dash-card-body">
            <form method="POST" action="{{ $employee->exists ? route('admin.employees.update', $employee) : route('admin.employees.store') }}">
                @csrf
                @if($employee->exists)
                    @method('PUT')
                @endif

                <div class="admin-form-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:18px;">
                    {{-- Full Name --}}
                    <div class="form-grp">
                        <label>Full Name *</label>
                        <input type="text" name="name" value="{{ old('name', $employee->name) }}" class="form-control-admin" required placeholder="e.g. Rahul Sharma">
                        @error('name') <span style="font-size:11px;color:#ef4444;">{{ $message }}</span> @enderror
                    </div>

                    {{-- Email Address --}}
                    <div class="form-grp">
                        <label>Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $employee->email) }}" class="form-control-admin" placeholder="e.g. rahul@thetrendtheory.com">
                        @error('email') <span style="font-size:11px;color:#ef4444;">{{ $message }}</span> @enderror
                    </div>

                    {{-- Phone Number --}}
                    <div class="form-grp">
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}" class="form-control-admin" placeholder="e.g. +91 9876543210">
                        @error('phone') <span style="font-size:11px;color:#ef4444;">{{ $message }}</span> @enderror
                    </div>

                    {{-- Department --}}
                    <div class="form-grp">
                        <label>Department</label>
                        <input type="text" name="department" value="{{ old('department', $employee->department) }}" class="form-control-admin" placeholder="e.g. Design & Styling, Warehouse, Sales">
                        @error('department') <span style="font-size:11px;color:#ef4444;">{{ $message }}</span> @enderror
                    </div>

                    {{-- Designation --}}
                    <div class="form-grp">
                        <label>Designation / Role Title</label>
                        <input type="text" name="designation" value="{{ old('designation', $employee->designation) }}" class="form-control-admin" placeholder="e.g. Senior Apparel Designer">
                        @error('designation') <span style="font-size:11px;color:#ef4444;">{{ $message }}</span> @enderror
                    </div>

                    {{-- Monthly Salary --}}
                    <div class="form-grp">
                        <label>Monthly Salary (₹)</label>
                        <input type="number" step="0.01" name="salary" value="{{ old('salary', $employee->salary) }}" class="form-control-admin" placeholder="e.g. 45000">
                        @error('salary') <span style="font-size:11px;color:#ef4444;">{{ $message }}</span> @enderror
                    </div>

                    {{-- Joining Date --}}
                    <div class="form-grp">
                        <label>Joining Date</label>
                        <input type="date" name="joining_date" value="{{ old('joining_date', $employee->joining_date ? $employee->joining_date->format('Y-m-d') : '') }}" class="form-control-admin">
                        @error('joining_date') <span style="font-size:11px;color:#ef4444;">{{ $message }}</span> @enderror
                    </div>

                    {{-- Status --}}
                    <div class="form-grp">
                        <label>Employment Status *</label>
                        <select name="status" class="form-control-admin" required>
                            <option value="active" {{ old('status', $employee->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $employee->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="on_leave" {{ old('status', $employee->status) === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                            <option value="terminated" {{ old('status', $employee->status) === 'terminated' ? 'selected' : '' }}>Terminated</option>
                        </select>
                        @error('status') <span style="font-size:11px;color:#ef4444;">{{ $message }}</span> @enderror
                    </div>

                    {{-- Address --}}
                    <div class="form-grp" style="grid-column:1/-1;">
                        <label>Residential Address</label>
                        <textarea name="address" rows="2" class="form-control-admin" placeholder="Employee permanent address...">{{ old('address', $employee->address) }}</textarea>
                        @error('address') <span style="font-size:11px;color:#ef4444;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:24px;padding-top:16px;border-top:1px solid #eef2f6;">
                    <a href="{{ route('admin.employees.index') }}" class="btn-admin btn-light">Cancel</a>
                    <button type="submit" class="btn-admin btn-navy" style="padding:10px 24px;font-size:13px;">
                        <i class="bi bi-check-circle-fill"></i> {{ $employee->exists ? 'Update Employee' : 'Save & Onboard' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection
