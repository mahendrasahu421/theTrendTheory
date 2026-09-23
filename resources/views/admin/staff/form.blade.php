{{-- resources/views/admin/staff/form.blade.php --}}
@extends('admin.layouts.app')

@section('title', $isEdit ? 'Configure Staff Permissions' : 'Add New Staff Member')

@section('content')
<div class="m-form-page-root">

    {{-- ── 1. Top Header ── --}}
    <div class="m-form-header">
        <div>
            <div class="m-crumb-badge">
                <a href="{{ route('admin.staff.index') }}">Staff Directory</a>
                <i class="bi bi-chevron-right"></i>
                <span>{{ $isEdit ? 'Edit Access Profile' : 'Onboard New Staff' }}</span>
            </div>
            <h1 class="m-page-heading">
                <i class="bi bi-person-badge-fill" style="color:#00285a;"></i>
                {{ $isEdit ? "Configure Access: {$staff->name}" : 'Onboard New Staff Member' }}
            </h1>
            <p class="m-page-desc">
                {{ $isEdit ? "Modify system role credentials and grant or revoke granular module access permissions." : 'Create administrative credentials and assign role-specific permissions for this team member.' }}
            </p>
        </div>

        <div class="m-hdr-actions">
            <a href="{{ route('admin.staff.index') }}" class="m-btn-outline">
                <i class="bi bi-arrow-left"></i> Back to Directory
            </a>
        </div>
    </div>

    <form method="POST" action="{{ $isEdit ? route('admin.staff.update', $staff) : route('admin.staff.store') }}" id="staffMainForm">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="m-form-layout-grid">

            {{-- ── LEFT COLUMN: Account Credentials & Role ── --}}
            <div class="m-left-col">
                <div class="m-card-glass">
                    <div class="m-card-hdr-clean">
                        <div class="m-hdr-title">
                            <span class="m-step-num">1</span>
                            <h3>Account Credentials</h3>
                        </div>
                        <span class="m-hdr-badge">Required</span>
                    </div>

                    <div class="m-card-body-stacked">
                        {{-- Name --}}
                        <div class="m-input-group">
                            <label class="m-label">Full Name <span class="m-req">*</span></label>
                            <div class="m-input-wrap">
                                <i class="bi bi-person"></i>
                                <input type="text" name="name" value="{{ old('name', $staff->name) }}" class="m-form-control" placeholder="e.g. Alex Morgan" required>
                            </div>
                            @error('name') <span class="m-error-msg">{{ $message }}</span> @enderror
                        </div>

                        {{-- Email --}}
                        <div class="m-input-group">
                            <label class="m-label">Email Address <span class="m-req">*</span></label>
                            <div class="m-input-wrap">
                                <i class="bi bi-envelope"></i>
                                <input type="email" name="email" value="{{ old('email', $staff->email) }}" class="m-form-control" placeholder="staff@thetrendtheory.com" required>
                            </div>
                            @error('email') <span class="m-error-msg">{{ $message }}</span> @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="m-input-group">
                            <label class="m-label">Phone Number</label>
                            <div class="m-input-wrap">
                                <i class="bi bi-telephone"></i>
                                <input type="text" name="phone" value="{{ old('phone', $staff->phone) }}" class="m-form-control" placeholder="+91 98765 43210">
                            </div>
                            @error('phone') <span class="m-error-msg">{{ $message }}</span> @enderror
                        </div>

                        {{-- System Role --}}
                        <div class="m-input-group">
                            <label class="m-label">System Role <span class="m-req">*</span></label>
                            <div class="m-input-wrap">
                                <i class="bi bi-shield-check"></i>
                                <select name="role" id="roleSelect" class="m-form-control" required onchange="handleRoleChange(this.value)">
                                    <option value="staff" {{ old('role', $staff->role) === 'staff' ? 'selected' : '' }}>⚙️ Custom Staff (Custom permissions)</option>
                                    <option value="product_manager" {{ old('role', $staff->role) === 'product_manager' ? 'selected' : '' }}>📦 Product Manager</option>
                                    <option value="product_editor" {{ old('role', $staff->role) === 'product_editor' ? 'selected' : '' }}>✏️ Product Editor</option>
                                    <option value="support_staff" {{ old('role', $staff->role) === 'support_staff' ? 'selected' : '' }}>💬 Support &amp; Order Staff</option>
                                    <option value="hr" {{ old('role', $staff->role) === 'hr' ? 'selected' : '' }}>👥 HR Manager</option>
                                    <option value="admin" {{ old('role', $staff->role) === 'admin' ? 'selected' : '' }}>⭐ Admin (Full Unrestricted Access)</option>
                                    @if((auth('admin')->user() ?? auth()->user())?->isSuperAdmin())
                                        <option value="super_admin" {{ old('role', $staff->role) === 'super_admin' ? 'selected' : '' }}>👑 Super Admin (Full Root Access)</option>
                                    @endif
                                </select>
                            </div>
                            <small class="m-helper-text">Admins &amp; Super Admins automatically receive full system access.</small>
                            @error('role') <span class="m-error-msg">{{ $message }}</span> @enderror
                        </div>

                        {{-- Password --}}
                        <div class="m-input-group">
                            <label class="m-label">
                                Password {{ $isEdit ? '(Leave empty to keep current)' : '*' }}
                            </label>
                            <div class="m-input-wrap">
                                <i class="bi bi-key"></i>
                                <input type="password" name="password" class="m-form-control" placeholder="{{ $isEdit ? '••••••••' : 'Minimum 6 characters' }}" {{ $isEdit ? '' : 'required' }}>
                            </div>
                            @error('password') <span class="m-error-msg">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                {{-- Quick Summary Card --}}
                <div class="m-card-glass m-mt-4">
                    <div class="m-summary-box">
                        <div class="m-summary-icon">
                            <i class="bi bi-info-circle-fill"></i>
                        </div>
                        <div>
                            <h4>Security Note</h4>
                            <p>Staff members only have access to modules selected in the permissions matrix. Any unauthorized routes are blocked by the permission middleware.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── RIGHT COLUMN: Granular Permission Matrix ── --}}
            <div class="m-right-col">

                {{-- Full Access Callout (Shown when Super Admin or Admin is chosen) --}}
                <div id="fullAccessBanner" style="display:none;" class="m-full-access-banner">
                    <div class="m-shield-icon">
                        <i class="bi bi-shield-fill-check"></i>
                    </div>
                    <div class="m-banner-text">
                        <h4>Full Unrestricted Root Access</h4>
                        <p>This user has been assigned an <b>Administrative Role</b>. They automatically have access to all modules, catalog management, customer operations, order handling, marketing automations, and settings without needing manual permission checkboxes.</p>
                    </div>
                </div>

                {{-- Permission Matrix Container --}}
                <div id="permissionsMatrixWrapper" class="m-perm-container">
                    <div class="m-perm-header-bar">
                        <div>
                            <span class="m-step-num">2</span>
                            <h3 class="m-inline-title">Granular Module Permissions</h3>
                            <p class="m-inline-sub">Select individual permissions or toggle entire modules</p>
                        </div>

                        <div class="m-perm-btn-toggles">
                            <button type="button" class="m-btn-mini" onclick="toggleAllPermissions(true)">
                                <i class="bi bi-check2-all"></i> Check All
                            </button>
                            <button type="button" class="m-btn-mini" onclick="toggleAllPermissions(false)">
                                <i class="bi bi-x-lg"></i> Uncheck All
                            </button>
                        </div>
                    </div>

                    {{-- Permission Groups --}}
                    <div class="m-groups-stack">
                        @foreach($permissionGroups as $groupKey => $group)
                            <div class="m-group-card">
                                <div class="m-group-hdr">
                                    <div class="m-group-title-wrap">
                                        <div class="m-group-icon" style="background:{{ $group['color'] }}15;color:{{ $group['color'] }};">
                                            <i class="bi {{ $group['icon'] }}"></i>
                                        </div>
                                        <div>
                                            <h4>{{ $group['title'] }}</h4>
                                            <span class="m-perm-count-pill">{{ count($group['permissions']) }} Permissions</span>
                                        </div>
                                    </div>

                                    <button type="button" class="m-btn-select-module" onclick="toggleGroup('{{ $groupKey }}')">
                                        <i class="bi bi-check-circle"></i> Toggle Module
                                    </button>
                                </div>

                                <div class="m-perm-tiles-grid">
                                    @foreach($group['permissions'] as $pCode => $pInfo)
                                        @php
                                            $isChecked = in_array($pCode, old('permissions', $assignedPerms), true);
                                        @endphp
                                        <label class="m-perm-tile {{ $isChecked ? 'active' : '' }}">
                                            <input type="checkbox" name="permissions[]" value="{{ $pCode }}" 
                                                   class="m-perm-checkbox group-{{ $groupKey }}" 
                                                   {{ $isChecked ? 'checked' : '' }}
                                                   onchange="this.closest('.m-perm-tile').classList.toggle('active', this.checked)">
                                            
                                            <div class="m-tile-content">
                                                <div class="m-tile-top">
                                                    <span class="m-tile-name">{{ $pInfo['label'] }}</span>
                                                    <span class="m-tile-chk-indicator"></span>
                                                </div>
                                                <p class="m-tile-desc">{{ $pInfo['desc'] }}</p>
                                                <code class="m-tile-code">{{ $pCode }}</code>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>

        {{-- ── 5. Sticky Action Footer ── --}}
        <div class="m-form-action-footer">
            <div class="m-footer-left">
                <span class="m-footer-tip"><i class="bi bi-shield-lock"></i> Changes take effect immediately upon saving</span>
            </div>
            <div class="m-footer-right">
                <a href="{{ route('admin.staff.index') }}" class="m-btn-outline">Cancel</a>
                <button type="submit" class="m-btn-save">
                    <i class="bi bi-check2-circle"></i>
                    <span>{{ $isEdit ? 'Save Permissions' : 'Create Staff Member' }}</span>
                </button>
            </div>
        </div>
    </form>

</div>

<script>
function handleRoleChange(val) {
    var banner = document.getElementById('fullAccessBanner');
    var matrix = document.getElementById('permissionsMatrixWrapper');

    if (val === 'super_admin' || val === 'admin') {
        banner.style.display = 'flex';
        matrix.style.opacity = '0.35';
        matrix.style.pointerEvents = 'none';
    } else {
        banner.style.display = 'none';
        matrix.style.opacity = '1';
        matrix.style.pointerEvents = 'auto';
    }
}

function toggleAllPermissions(checked) {
    var checkboxes = document.querySelectorAll('.m-perm-checkbox');
    checkboxes.forEach(function(cb) {
        cb.checked = checked;
        cb.closest('.m-perm-tile').classList.toggle('active', checked);
    });
}

function toggleGroup(groupKey) {
    var checkboxes = document.querySelectorAll('.group-' + groupKey);
    if (checkboxes.length === 0) return;
    
    var allChecked = true;
    checkboxes.forEach(function(cb) {
        if (!cb.checked) allChecked = false;
    });

    var newState = !allChecked;
    checkboxes.forEach(function(cb) {
        cb.checked = newState;
        cb.closest('.m-perm-tile').classList.toggle('active', newState);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    var roleVal = document.getElementById('roleSelect').value;
    handleRoleChange(roleVal);
});
</script>

<style>
/* ─── Ultra Modern Form Layout ─── */
.m-form-page-root {
    display: flex;
    flex-direction: column;
    gap: 20px;
    padding-bottom: 40px;
}

/* Header */
.m-form-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    background: #ffffff;
    padding: 22px 28px;
    border-radius: 18px;
    border: 1px solid #eef2f6;
    box-shadow: 0 4px 20px -2px rgba(0, 40, 90, 0.03);
}

.m-crumb-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    margin-bottom: 6px;
}

.m-crumb-badge a {
    color: #00285a;
    text-decoration: none;
}

.m-crumb-badge a:hover {
    text-decoration: underline;
}

.m-page-heading {
    font-size: 20px;
    font-weight: 800;
    color: #00285a;
    margin: 0 0 4px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.m-page-desc {
    font-size: 12.5px;
    color: #64748b;
    margin: 0;
}

.m-btn-outline {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #ffffff;
    color: #475569;
    border: 1.5px solid #e2e8f0;
    padding: 9px 18px;
    border-radius: 10px;
    font-size: 12.5px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.15s ease;
}

.m-btn-outline:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #00285a;
}

/* 2-Column Grid Layout */
.m-form-layout-grid {
    display: grid;
    grid-template-columns: 360px 1fr;
    gap: 20px;
    align-items: start;
}

@media (max-width: 1024px) {
    .m-form-layout-grid {
        grid-template-columns: 1fr;
    }
}

/* Left Column Cards */
.m-card-glass {
    background: #ffffff;
    border: 1px solid #eef2f6;
    border-radius: 18px;
    box-shadow: 0 4px 20px -2px rgba(0, 40, 90, 0.03);
    overflow: hidden;
}

.m-card-hdr-clean {
    padding: 18px 22px;
    border-bottom: 1px solid #eef2f6;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #ffffff;
}

.m-hdr-title {
    display: flex;
    align-items: center;
    gap: 10px;
}

.m-step-num {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #00285a;
    color: #ffffff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 800;
}

.m-hdr-title h3 {
    font-size: 14px;
    font-weight: 800;
    color: #00285a;
    margin: 0;
}

.m-hdr-badge {
    font-size: 10.5px;
    font-weight: 700;
    color: #e11d48;
    background: #ffe4e6;
    padding: 2px 8px;
    border-radius: 999px;
}

.m-card-body-stacked {
    padding: 22px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.m-input-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.m-label {
    font-size: 11px;
    font-weight: 800;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

.m-req {
    color: #e11d48;
}

.m-input-wrap {
    position: relative;
    display: flex;
    align-items: center;
}

.m-input-wrap i {
    position: absolute;
    left: 12px;
    color: #94a3b8;
    font-size: 14px;
}

.m-form-control {
    padding: 10px 12px 10px 36px;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    font-size: 13px;
    color: #1e293b;
    outline: none;
    background: #ffffff;
    width: 100%;
    transition: all 0.15s ease;
}

.m-form-control:focus {
    border-color: #00285a;
    box-shadow: 0 0 0 3px rgba(0, 40, 90, 0.08);
}

.m-helper-text {
    font-size: 11px;
    color: #94a3b8;
    margin-top: 2px;
}

.m-error-msg {
    color: #dc2626;
    font-size: 11px;
    font-weight: 600;
}

.m-mt-4 {
    margin-top: 16px;
}

.m-summary-box {
    padding: 18px 20px;
    display: flex;
    gap: 12px;
    background: #f8fafc;
}

.m-summary-icon {
    color: #00285a;
    font-size: 20px;
    flex-shrink: 0;
}

.m-summary-box h4 {
    font-size: 13px;
    font-weight: 800;
    color: #00285a;
    margin: 0 0 3px;
}

.m-summary-box p {
    font-size: 11.5px;
    color: #64748b;
    margin: 0;
    line-height: 1.45;
}

/* Right Column: Permission Matrix */
.m-full-access-banner {
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    border: 1.5px solid #bfdbfe;
    border-radius: 16px;
    padding: 20px 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
    box-shadow: 0 4px 16px rgba(37, 99, 235, 0.08);
}

.m-shield-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    background: #2563eb;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.m-banner-text h4 {
    font-size: 15px;
    font-weight: 800;
    color: #1e40af;
    margin: 0 0 4px;
}

.m-banner-text p {
    font-size: 12px;
    color: #1e3a8a;
    margin: 0;
    line-height: 1.45;
}

.m-perm-header-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
    flex-wrap: wrap;
    gap: 12px;
}

.m-inline-title {
    font-size: 15px;
    font-weight: 800;
    color: #00285a;
    display: inline;
    margin-left: 6px;
}

.m-inline-sub {
    font-size: 12px;
    color: #64748b;
    margin: 2px 0 0 30px;
}

.m-perm-btn-toggles {
    display: flex;
    gap: 8px;
}

.m-btn-mini {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 11.5px;
    font-weight: 700;
    color: #475569;
    cursor: pointer;
    transition: all 0.15s ease;
}

.m-btn-mini:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #00285a;
}

/* Group Stack */
.m-groups-stack {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.m-group-card {
    background: #ffffff;
    border: 1px solid #eef2f6;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 16px -2px rgba(0, 40, 90, 0.02);
}

.m-group-hdr {
    padding: 14px 20px;
    background: #f8fafc;
    border-bottom: 1px solid #eef2f6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.m-group-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
}

.m-group-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.m-group-title-wrap h4 {
    font-size: 13.5px;
    font-weight: 800;
    color: #00285a;
    margin: 0;
}

.m-perm-count-pill {
    font-size: 10.5px;
    color: #64748b;
    font-weight: 600;
}

.m-btn-select-module {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    padding: 5px 12px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 700;
    color: #475569;
    cursor: pointer;
    transition: all 0.15s ease;
}

.m-btn-select-module:hover {
    background: #00285a;
    border-color: #00285a;
    color: #ffffff;
}

/* Permission Tiles Grid */
.m-perm-tiles-grid {
    padding: 18px 20px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 12px;
}

.m-perm-tile {
    position: relative;
    display: flex;
    align-items: flex-start;
    padding: 14px 16px;
    background: #ffffff;
    border: 1.5px solid #eef2f6;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.15s ease;
    user-select: none;
}

.m-perm-tile:hover {
    border-color: #cbd5e1;
    background: #f8fafc;
    transform: translateY(-1px);
}

.m-perm-tile.active {
    border-color: #00285a;
    background: #f0f7ff;
    box-shadow: 0 2px 10px rgba(0, 40, 90, 0.06);
}

.m-perm-checkbox {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.m-tile-content {
    display: flex;
    flex-direction: column;
    gap: 3px;
    width: 100%;
}

.m-tile-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.m-tile-name {
    font-size: 12.5px;
    font-weight: 800;
    color: #00285a;
}

.m-tile-chk-indicator {
    width: 18px;
    height: 18px;
    border-radius: 6px;
    border: 1.5px solid #cbd5e1;
    background: #ffffff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s ease;
}

.m-perm-tile.active .m-tile-chk-indicator {
    background: #00285a;
    border-color: #00285a;
}

.m-perm-tile.active .m-tile-chk-indicator::after {
    content: '✓';
    color: #ffffff;
    font-size: 11px;
    font-weight: 800;
}

.m-tile-desc {
    font-size: 11px;
    color: #64748b;
    margin: 0;
    line-height: 1.35;
}

.m-tile-code {
    font-size: 9.5px;
    font-family: monospace;
    color: #94a3b8;
    background: #f1f5f9;
    padding: 1px 5px;
    border-radius: 4px;
    width: fit-content;
    margin-top: 4px;
}

/* Action Footer */
.m-form-action-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 24px;
    background: #ffffff;
    border: 1px solid #eef2f6;
    border-radius: 16px;
    margin-top: 24px;
    box-shadow: 0 4px 20px -2px rgba(0, 40, 90, 0.03);
    flex-wrap: wrap;
    gap: 16px;
}

.m-footer-tip {
    font-size: 12px;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.m-footer-right {
    display: flex;
    align-items: center;
    gap: 10px;
}

.m-btn-save {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #00285a 0%, #1e3f75 100%);
    color: #ffffff;
    border: none;
    padding: 10px 24px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(0, 40, 90, 0.25);
    transition: all 0.15s ease;
}

.m-btn-save:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(0, 40, 90, 0.35);
}
</style>
@endsection
