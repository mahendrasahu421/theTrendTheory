{{-- resources/views/admin/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TTT Admin — @yield('title', 'Dashboard')</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=DM+Sans:wght@400;600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #f0f4f8;
            display: flex;
            min-height: 100vh
        }

        /* SIDEBAR */
        .sidebar {
            width: 240px;
            background: #00285a;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto
        }

        .sb-logo {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, .08)
        }

        .sb-logo-text {
            font-family: 'Cinzel', serif;
            font-size: 14px;
            font-weight: 700;
            color: white;
            letter-spacing: 2px
        }

        .sb-logo-sub {
            font-size: 10px;
            color: rgba(255, 255, 255, .4);
            margin-top: 2px;
            text-transform: uppercase;
            letter-spacing: 1px
        }

        .sb-user {
            padding: 14px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
            display: flex;
            align-items: center;
            gap: 10px
        }

        .sb-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffd700;
            color: #00285a;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0
        }

        .sb-user-name {
            font-size: 13px;
            font-weight: 600;
            color: white
        }

        .sb-user-role {
            font-size: 10px;
            color: rgba(255, 255, 255, .4);
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-top: 1px
        }

        .sb-nav {
            padding: 10px 0;
            flex: 1
        }

        .sb-section {
            font-size: 9px;
            font-weight: 700;
            color: rgba(255, 255, 255, .25);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 10px 20px 4px
        }

        .sb-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 20px;
            color: rgba(255, 255, 255, .7);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: .15s;
            border-left: 3px solid transparent
        }

        .sb-link:hover {
            background: rgba(255, 255, 255, .06);
            color: white
        }

        .sb-link.active {
            background: rgba(255, 255, 255, .1);
            color: white;
            border-left-color: #ffd700
        }

        .sb-link i {
            font-size: 15px;
            width: 18px;
            text-align: center
        }

        .sb-badge {
            margin-left: auto;
            background: #ff3f6c;
            color: white;
            font-size: 9px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 20px
        }

        .sb-footer {
            padding: 14px 20px;
            border-top: 1px solid rgba(255, 255, 255, .08)
        }

        .sb-logout {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, .5);
            font-size: 12px;
            text-decoration: none;
            cursor: pointer;
            background: none;
            border: none;
            width: 100%;
            font-family: inherit;
            transition: .15s
        }

        .sb-logout:hover {
            color: #ff3f6c
        }

        /* MAIN */
        .admin-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0
        }

        .admin-topbar {
            background: white;
            padding: 0 24px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #eef2f6;
            position: sticky;
            top: 0;
            z-index: 10
        }

        .admin-topbar-title {
            font-family: 'Cinzel', serif;
            font-size: 13px;
            font-weight: 700;
            color: #00285a;
            letter-spacing: 1px
        }

        .admin-content {
            padding: 20px 24px;
            flex: 1
        }

        /* CARDS */
        .admin-card {
            background: white;
            border: 1px solid #eef2f6;
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 20px
        }

        .admin-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            border-bottom: 1px solid #eef2f6
        }

        .admin-card-title {
            font-family: 'Cinzel', serif;
            font-size: 13px;
            font-weight: 700;
            color: #00285a;
            letter-spacing: 1px
        }

        .admin-form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px
        }

        .form-grp {
            display: flex;
            flex-direction: column;
            gap: 5px
        }

        .form-grp.full {
            grid-column: 1/-1
        }

        .form-grp label {
            font-size: 11px;
            font-weight: 700;
            color: #7a8fa6;
            text-transform: uppercase;
            letter-spacing: .5px
        }

        .form-control-admin {
            padding: 10px 14px;
            border: 1.5px solid #e8edf5;
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: .15s;
            width: 100%;
            background: white
        }

        .form-control-admin:focus {
            border-color: #00285a
        }

        .form-toggle {
            display: flex;
            align-items: center;
            gap: 7px;
            cursor: pointer
        }

        .form-toggle input {
            width: 16px;
            height: 16px;
            accent-color: #00285a;
            cursor: pointer
        }

        .btn-admin {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: none;
            font-family: inherit;
            transition: .15s
        }

        .btn-navy {
            background: #00285a;
            color: white
        }

        .btn-navy:hover {
            background: #1e3f75
        }

        .btn-light {
            background: #f0f4f8;
            color: #555;
            border: 1px solid #e8edf5
        }

        .btn-light:hover {
            background: #e8edf5
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 11px
        }

        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 13px;
            font-weight: 600
        }

        .alert-error {
            background: #fce4ec;
            color: #c62828;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 13px
        }
    </style>
</head>

<body>

    @php $user = auth()->user(); @endphp

    <aside class="sidebar">
        <div class="sb-logo">
            <div class="sb-logo-text">THE TREND THEORY</div>
            <div class="sb-logo-sub">Admin Panel</div>
        </div>
        <div class="sb-user">
            <div class="sb-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
            <div>
                <div class="sb-user-name">{{ $user->name }}</div>
                <div class="sb-user-role">{{ $user->role_label }}</div>
            </div>
        </div>

        <nav class="sb-nav">

            {{-- Dashboard --}}
            <div class="sb-section">Dashboard</div>
            <a href="{{ route('admin.dashboard') }}"
                class="sb-link {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                @if ($user->isSuperAdmin())
                    Investor Dashboard
                @elseif($user->isAdmin())
                    Admin Dashboard
                @elseif($user->isHR())
                    HR Dashboard
                @else
                    My Dashboard
                @endif
            </a>

            {{-- Super Admin only --}}
            @if ($user->isSuperAdmin())
                <a href="{{ route('admin.dashboard.admin') }}"
                    class="sb-link {{ request()->routeIs('admin.dashboard.admin') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-line"></i> Admin View
                </a>
                <a href="{{ route('admin.dashboard.hr') }}"
                    class="sb-link {{ request()->routeIs('admin.dashboard.hr') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> HR View
                </a>
            @endif

            {{-- Products --}}
            @if ($user->hasPermission('products.view'))
                <div class="sb-section">Catalog</div>
                <a href="{{ route('admin.products.index') }}"
                    class="sb-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i> Products
                </a>
            @endif

            @if ($user->hasPermission('categories.view'))
                <a href="{{ route('admin.categories.index') }}"
                    class="sb-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i> Categories
                </a>
            @endif

            {{-- Orders --}}
            @if ($user->hasPermission('orders.view'))
                <div class="sb-section">Sales</div>
                <a href="{{ route('admin.orders.index') }}"
                    class="sb-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                    <i class="bi bi-bag-check"></i> Orders
                    @php $pending = \App\Models\Order::where('status','pending')->count(); @endphp
                    @if ($pending > 0)
                        <span class="sb-badge">{{ $pending }}</span>
                    @endif
                </a>
            @endif
            {{-- coupons --}}
            @if ($user->hasPermission('coupons.view'))
                <a href="{{ route('admin.coupons.index') }}"
                    class="sb-link {{ request()->is('admin/coupons*') ? 'active' : '' }}">
                    <i class="bi bi-ticket-perforated"></i> Coupons
                </a>
            @endif
            {{-- Sizes --}}
            @if ($user->hasPermission('masters.view'))
                <a href="{{ route('admin.masters.index', 'sizes') }}"
                    class="sb-link {{ request()->is('admin/masters/sizes*') ? 'active' : '' }}">
                    <i class="bi bi-rulers"></i> Sizes
                </a>
            @endif

            {{-- Colors --}}
            @if ($user->hasPermission('masters.view'))
                <a href="{{ route('admin.masters.index', 'colors') }}"
                    class="sb-link {{ request()->is('admin/masters/colors*') ? 'active' : '' }}">
                    <i class="bi bi-palette"></i> Colors
                </a>
            @endif
            @if ($user->hasPermission('customers.view'))
                <a href="{{ route('admin.customers.index') }}"
                    class="sb-link {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Customers
                </a>
            @endif

            {{-- HR / Employees --}}
            @if ($user->hasPermission('employees.view'))
                <div class="sb-section">HR</div>
                <a href="{{ route('admin.employees.index') }}"
                    class="sb-link {{ request()->routeIs('admin.employees*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge"></i> Employees
                </a>
                <a href="{{ route('admin.attendance.index') }}"
                    class="sb-link {{ request()->routeIs('admin.attendance*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i> Attendance
                </a>
            @endif

            {{-- Settings --}}
            @if ($user->hasPermission('settings.view'))
                <div class="sb-section">System</div>
                <a href="{{ route('admin.settings.index') }}"
                    class="sb-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i> Settings
                </a>
            @endif

        </nav>

        <div class="sb-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sb-logout">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <main class="admin-main">
        <div class="admin-topbar">
            <div class="admin-topbar-title">@yield('title', 'Dashboard')</div>
            <div style="display:flex;align-items:center;gap:12px;font-size:12px;color:#7a8fa6">
                <a href="{{ route('home') }}" target="_blank" style="color:#7a8fa6;text-decoration:none"><i
                        class="bi bi-box-arrow-up-right"></i> View Site</a>
                <span>{{ $user->name }}</span>
            </div>
        </div>

        <div class="admin-content">
            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert-error"><i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
        document.addEventListener('submit', function(e) {
            if (e.defaultPrevented) return;
            var form = e.target;
            if (!form || form.dataset.submitLock === '1') return;
            form.dataset.submitLock = '1';
            form.querySelectorAll('button[type="submit"]').forEach(function(btn) {
                btn.disabled = true;
                if (!btn.dataset.originalHtml) btn.dataset.originalHtml = btn.innerHTML;
                if (!btn.dataset.keepLabel) btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...';
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
