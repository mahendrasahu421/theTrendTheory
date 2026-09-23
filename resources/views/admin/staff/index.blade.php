{{-- resources/views/admin/staff/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Staff & Permissions')

@section('content')
<div class="m-staff-wrap">

    {{-- ── 1. Top Header ── --}}
    <div class="m-top-header">
        <div class="m-header-info">
            <div class="m-badge-pill">
                <span class="m-pulse-dot"></span>
                <span>ACCESS &amp; ROLE SECURITY</span>
            </div>
            <h1 class="m-title">Staff &amp; Permissions Hub</h1>
            <p class="m-subtitle">Manage administrative team members, assign granular module permissions, and control system access.</p>
        </div>

        <div class="m-header-actions">
            <a href="{{ route('admin.staff.create') }}" class="m-btn-primary">
                <i class="bi bi-person-plus-fill"></i>
                <span>Add Staff Member</span>
            </a>
        </div>
    </div>

    {{-- ── 2. Bento Stat Cards Grid ── --}}
    <div class="m-stats-grid">
        {{-- Card 1: Total Staff --}}
        <div class="m-stat-card">
            <div class="m-stat-top">
                <span class="m-stat-label">Total Staff Members</span>
                <div class="m-icon-box m-icon-navy">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
            <div class="m-stat-value">{{ number_format($totalStaff) }}</div>
            <div class="m-stat-footer">
                <span class="m-stat-sub"><i class="bi bi-person-check-fill text-success"></i> All system accounts</span>
            </div>
        </div>

        {{-- Card 2: Super Admins --}}
        <div class="m-stat-card">
            <div class="m-stat-top">
                <span class="m-stat-label">Super Admins</span>
                <div class="m-icon-box m-icon-indigo">
                    <i class="bi bi-shield-fill-check"></i>
                </div>
            </div>
            <div class="m-stat-value text-indigo">{{ number_format($superAdminCount) }}</div>
            <div class="m-stat-footer">
                <span class="m-stat-sub text-indigo">Root Unrestricted Access</span>
            </div>
        </div>

        {{-- Card 3: Admins --}}
        <div class="m-stat-card">
            <div class="m-stat-top">
                <span class="m-stat-label">Administrators</span>
                <div class="m-icon-box m-icon-emerald">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
            <div class="m-stat-value text-emerald">{{ number_format($adminCount) }}</div>
            <div class="m-stat-footer">
                <span class="m-stat-sub text-emerald">Full Management Access</span>
            </div>
        </div>

        {{-- Card 4: Custom Staff & Editors --}}
        <div class="m-stat-card">
            <div class="m-stat-top">
                <span class="m-stat-label">Custom Staff &amp; Editors</span>
                <div class="m-icon-box m-icon-amber">
                    <i class="bi bi-sliders"></i>
                </div>
            </div>
            <div class="m-stat-value text-amber">{{ number_format($subStaffCount) }}</div>
            <div class="m-stat-footer">
                <span class="m-stat-sub text-amber">Granular Permission Controlled</span>
            </div>
        </div>
    </div>

    {{-- ── 3. Main Directory Card ── --}}
    <div class="m-main-card">
        
        {{-- Card Header & Filter Bar --}}
        <div class="m-card-header">
            <div class="m-card-title-group">
                <h3><i class="bi bi-shield-lock-fill text-indigo"></i> Team Directory &amp; Permissions</h3>
                <span class="m-count-badge">{{ $staffUsers->total() }} accounts</span>
            </div>

            {{-- Search & Role Filters --}}
            <form method="GET" action="{{ route('admin.staff.index') }}" class="m-filter-bar">
                <div class="m-select-wrap">
                    <select name="role" class="m-select" onchange="this.form.submit()">
                        <option value="">All Staff Roles</option>
                        <option value="super_admin" {{ $roleFilter === 'super_admin' ? 'selected' : '' }}>👑 Super Admin</option>
                        <option value="admin" {{ $roleFilter === 'admin' ? 'selected' : '' }}>⭐ Admin</option>
                        <option value="product_manager" {{ $roleFilter === 'product_manager' ? 'selected' : '' }}>📦 Product Manager</option>
                        <option value="product_editor" {{ $roleFilter === 'product_editor' ? 'selected' : '' }}>✏️ Product Editor</option>
                        <option value="hr" {{ $roleFilter === 'hr' ? 'selected' : '' }}>👥 HR Manager</option>
                        <option value="support_staff" {{ $roleFilter === 'support_staff' ? 'selected' : '' }}>💬 Support Staff</option>
                        <option value="staff" {{ $roleFilter === 'staff' ? 'selected' : '' }}>⚙️ Custom Staff</option>
                    </select>
                </div>

                <div class="m-search-wrap">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search name, email, phone..." class="m-search-input">
                </div>

                <button type="submit" class="m-btn-search">Search</button>

                @if($search || $roleFilter)
                    <a href="{{ route('admin.staff.index') }}" class="m-btn-clear" title="Reset Filters">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="m-table-wrap">
            <table class="m-table">
                <thead>
                    <tr>
                        <th>STAFF MEMBER</th>
                        <th>SYSTEM ROLE</th>
                        <th>ASSIGNED PERMISSIONS</th>
                        <th>STATUS</th>
                        <th style="text-align:right;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staffUsers as $u)
                        @php
                            $isFullAccess = in_array($u->role, ['super_admin', 'admin'], true);
                            $customPermsCount = $u->userPermissions->count();
                            $defaultRolePerms = $u->getRolePermissions();
                        @endphp
                        <tr>
                            {{-- Member Profile --}}
                            <td>
                                <div class="m-user-cell">
                                    <div class="m-avatar-wrap">
                                        <img src="{{ $u->avatar }}" alt="avatar" class="m-avatar">
                                        @if($u->status === 'active' || is_null($u->status))
                                            <span class="m-online-indicator"></span>
                                        @endif
                                    </div>
                                    <div class="m-user-meta">
                                        <strong class="m-user-name">{{ $u->name }}</strong>
                                        <span class="m-user-email">{{ $u->email }}</span>
                                        @if($u->phone)
                                            <span class="m-user-phone"><i class="bi bi-telephone"></i> {{ $u->phone }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Role --}}
                            <td>
                                <span class="m-role-pill role-{{ $u->role }}">
                                    @if($u->role === 'super_admin') 👑 @elseif($u->role === 'admin') ⭐ @elseif(str_contains($u->role, 'product')) 📦 @elseif($u->role === 'hr') 👥 @else ⚙️ @endif
                                    {{ $u->role_label }}
                                </span>
                            </td>

                            {{-- Permissions Summary --}}
                            <td>
                                @if($isFullAccess)
                                    <span class="m-perm-full">
                                        <i class="bi bi-unlock-fill"></i> Full Root Access (All Modules)
                                    </span>
                                @else
                                    <div class="m-perm-summary">
                                        <span class="m-perm-badge">
                                            <i class="bi bi-key-fill"></i> {{ $customPermsCount }} Custom Permissions
                                        </span>
                                        @if($customPermsCount > 0)
                                            <div class="m-perm-tags">
                                                @foreach($u->userPermissions->take(3) as $up)
                                                    <span class="m-perm-tag">{{ $up->permission }}</span>
                                                @endforeach
                                                @if($customPermsCount > 3)
                                                    <span class="m-perm-more">+{{ $customPermsCount - 3 }} more</span>
                                                @endif
                                            </div>
                                        @else
                                            <small class="m-perm-default-text">Using {{ count($defaultRolePerms) }} role defaults</small>
                                        @endif
                                    </div>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td>
                                @if($u->status === 'active' || is_null($u->status))
                                    <span class="m-status-pill status-active">
                                        <span class="m-status-dot dot-active"></span> Active
                                    </span>
                                @else
                                    <span class="m-status-pill status-inactive">
                                        <span class="m-status-dot dot-inactive"></span> Inactive
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td style="text-align:right;">
                                <div class="m-action-group">
                                    <a href="{{ route('admin.staff.edit', $u) }}" class="m-action-btn btn-perm" title="Configure Permissions">
                                        <i class="bi bi-sliders"></i>
                                        <span>Permissions</span>
                                    </a>

                                    @if($u->id !== (auth('admin')->id() ?? auth()->id()))
                                        <form method="POST" action="{{ route('admin.staff.toggle-status', $u) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="m-action-icon-btn" title="{{ $u->status === 'active' ? 'Deactivate Account' : 'Activate Account' }}">
                                                <i class="bi bi-power {{ $u->status === 'active' ? 'text-emerald' : 'text-rose' }}"></i>
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.staff.destroy', $u) }}" style="display:inline;" onsubmit="return confirm('Delete this staff account permanently?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="m-action-icon-btn text-rose" title="Delete Staff">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="m-empty-state">
                                <i class="bi bi-person-x"></i>
                                <h4>No staff members found</h4>
                                <p>Try adjusting your search query or role filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($staffUsers->hasPages())
            <div class="m-card-footer">
                {{ $staffUsers->links() }}
            </div>
        @endif
    </div>

</div>

<style>
/* ─── Ultra Modern 2026 SaaS UI Architecture ─── */
.m-staff-wrap {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Header */
.m-top-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    background: #ffffff;
    padding: 22px 26px;
    border-radius: 18px;
    border: 1px solid #eef2f6;
    box-shadow: 0 4px 20px -2px rgba(0, 40, 90, 0.03);
}

.m-badge-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #eff6ff;
    color: #1d4ed8;
    font-size: 10.5px;
    font-weight: 800;
    letter-spacing: 0.5px;
    padding: 3px 10px;
    border-radius: 999px;
    margin-bottom: 6px;
}

.m-pulse-dot {
    width: 6px;
    height: 6px;
    background: #10b981;
    border-radius: 50%;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.3);
}

.m-title {
    font-size: 20px;
    font-weight: 800;
    color: #00285a;
    margin: 0 0 4px;
    letter-spacing: -0.3px;
}

.m-subtitle {
    font-size: 12.5px;
    color: #64748b;
    margin: 0;
}

.m-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #00285a 0%, #1e3f75 100%);
    color: #ffffff;
    padding: 10px 20px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    box-shadow: 0 4px 14px rgba(0, 40, 90, 0.22);
    transition: all 0.2s ease;
    border: none;
}

.m-btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(0, 40, 90, 0.3);
    color: #ffffff;
}

/* Stats Bento Grid */
.m-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
}

@media (max-width: 1024px) {
    .m-stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}

@media (max-width: 600px) {
    .m-stats-grid { grid-template-columns: 1fr; }
}

.m-stat-card {
    background: #ffffff;
    border: 1px solid #eef2f6;
    border-radius: 16px;
    padding: 18px 20px;
    box-shadow: 0 4px 16px -2px rgba(0, 40, 90, 0.02);
    display: flex;
    flex-direction: column;
    gap: 6px;
    transition: transform 0.2s ease;
}

.m-stat-card:hover {
    transform: translateY(-2px);
    border-color: #cbd5e1;
}

.m-stat-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.m-stat-label {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.m-icon-box {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.m-icon-navy { background: #eff6ff; color: #00285a; }
.m-icon-indigo { background: #e0e7ff; color: #4338ca; }
.m-icon-emerald { background: #d1fae5; color: #047857; }
.m-icon-amber { background: #fef3c7; color: #b45309; }

.m-stat-value {
    font-size: 26px;
    font-weight: 800;
    color: #00285a;
    letter-spacing: -0.5px;
}

.m-stat-footer {
    font-size: 11px;
    color: #64748b;
}

.text-indigo { color: #4338ca; }
.text-emerald { color: #047857; }
.text-amber { color: #b45309; }
.text-rose { color: #dc2626; }

/* Main Card */
.m-main-card {
    background: #ffffff;
    border: 1px solid #eef2f6;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 4px 20px -2px rgba(0, 40, 90, 0.03);
}

.m-card-header {
    padding: 16px 22px;
    border-bottom: 1px solid #eef2f6;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 14px;
    background: #ffffff;
}

.m-card-title-group {
    display: flex;
    align-items: center;
    gap: 10px;
}

.m-card-title-group h3 {
    font-size: 15px;
    font-weight: 800;
    color: #00285a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.m-count-badge {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    background: #f1f5f9;
    padding: 2px 8px;
    border-radius: 999px;
}

.m-filter-bar {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.m-select {
    padding: 8px 14px;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    font-size: 13px;
    color: #334155;
    outline: none;
    background: #ffffff;
    cursor: pointer;
    transition: all 0.15s ease;
}

.m-select:focus {
    border-color: #00285a;
}

.m-search-wrap {
    position: relative;
    display: flex;
    align-items: center;
    min-width: 220px;
}

.m-search-wrap i {
    position: absolute;
    left: 12px;
    color: #94a3b8;
    font-size: 13px;
}

.m-search-input {
    padding: 8px 12px 8px 34px;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    font-size: 13px;
    color: #334155;
    outline: none;
    width: 100%;
    transition: all 0.15s ease;
}

.m-search-input:focus {
    border-color: #00285a;
}

.m-btn-search {
    background: #00285a;
    color: #ffffff;
    border: none;
    padding: 8px 16px;
    border-radius: 10px;
    font-size: 12.5px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.15s ease;
}

.m-btn-search:hover {
    background: #1e3f75;
}

.m-btn-clear {
    color: #94a3b8;
    font-size: 16px;
    text-decoration: none;
    padding: 4px;
    transition: color 0.15s ease;
}

.m-btn-clear:hover {
    color: #dc2626;
}

/* Table */
.m-table-wrap {
    overflow-x: auto;
}

.m-table {
    width: 100%;
    border-collapse: collapse;
}

.m-table th {
    padding: 13px 22px;
    background: #f8fafc;
    border-bottom: 1px solid #eef2f6;
    font-size: 10.5px;
    font-weight: 800;
    color: #64748b;
    letter-spacing: 0.6px;
    text-align: left;
}

.m-table td {
    padding: 16px 22px;
    border-bottom: 1px solid #f8fafc;
    vertical-align: middle;
}

.m-table tr:hover td {
    background: #f8fafc;
}

/* User Cell */
.m-user-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.m-avatar-wrap {
    position: relative;
    width: 42px;
    height: 42px;
    flex-shrink: 0;
}

.m-avatar {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    object-fit: cover;
    border: 1.5px solid #e2e8f0;
}

.m-online-indicator {
    position: absolute;
    bottom: -2px;
    right: -2px;
    width: 10px;
    height: 10px;
    background: #10b981;
    border: 2px solid #ffffff;
    border-radius: 50%;
}

.m-user-meta {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.m-user-name {
    font-size: 13.5px;
    font-weight: 800;
    color: #00285a;
}

.m-user-email {
    font-size: 11.5px;
    color: #64748b;
}

.m-user-phone {
    font-size: 11px;
    color: #94a3b8;
}

/* Role Pills */
.m-role-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px;
    border-radius: 8px;
    font-size: 11.5px;
    font-weight: 700;
}

.role-super_admin { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
.role-admin { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
.role-hr { background: #faf5ff; color: #7e22ce; border: 1px solid #e9d5ff; }
.role-product_manager, .role-product_editor { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
.role-support_staff { background: #ecfeff; color: #0e7490; border: 1px solid #a5f3fc; }
.role-staff { background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; }

/* Permission badges */
.m-perm-full {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #eff6ff;
    color: #1d4ed8;
    font-size: 11.5px;
    font-weight: 800;
    padding: 5px 14px;
    border-radius: 999px;
    border: 1px solid #bfdbfe;
}

.m-perm-summary {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.m-perm-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #fef3c7;
    color: #b45309;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 6px;
    width: fit-content;
}

.m-perm-tags {
    display: flex;
    gap: 4px;
    flex-wrap: wrap;
}

.m-perm-tag {
    font-size: 10px;
    font-family: monospace;
    background: #f1f5f9;
    color: #475569;
    padding: 2px 6px;
    border-radius: 4px;
}

.m-perm-more {
    font-size: 10px;
    font-weight: 700;
    color: #64748b;
    padding: 2px 4px;
}

.m-perm-default-text {
    font-size: 11px;
    color: #94a3b8;
}

/* Status Pills */
.m-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
}

.status-active { background: #ecfdf5; color: #047857; }
.status-inactive { background: #fee2e2; color: #dc2626; }

.m-status-dot { width: 6px; height: 6px; border-radius: 50%; }
.dot-active { background: #10b981; }
.dot-inactive { background: #ef4444; }

/* Action buttons */
.m-action-group {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.m-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.15s ease;
}

.btn-perm {
    background: #f8fafc;
    color: #00285a;
    border: 1.5px solid #e2e8f0;
}

.btn-perm:hover {
    background: #00285a;
    color: #ffffff;
    border-color: #00285a;
}

.m-action-icon-btn {
    padding: 6px 10px;
    border-radius: 8px;
    border: 1.5px solid #e2e8f0;
    background: #ffffff;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.15s ease;
}

.m-action-icon-btn:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
}

.m-empty-state {
    text-align: center;
    padding: 50px 20px;
    color: #64748b;
}

.m-empty-state i {
    font-size: 36px;
    color: #cbd5e1;
    display: block;
    margin-bottom: 8px;
}

.m-empty-state h4 {
    font-size: 15px;
    font-weight: 800;
    color: #00285a;
    margin: 0 0 4px;
}

.m-empty-state p {
    font-size: 12px;
    color: #94a3b8;
    margin: 0;
}

.m-card-footer {
    padding: 14px 22px;
    border-top: 1px solid #eef2f6;
    display: flex;
    justify-content: flex-end;
}
</style>
@endsection
