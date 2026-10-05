<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') — THE TREND THEORY</title>
    <!-- Brand Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}?v={{ file_exists(public_path('images/favicon.png')) ? filemtime(public_path('images/favicon.png')) : time() }}" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ file_exists(public_path('favicon.ico')) ? filemtime(public_path('favicon.ico')) : time() }}" />
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}?v={{ file_exists(public_path('apple-touch-icon.png')) ? filemtime(public_path('apple-touch-icon.png')) : time() }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f4f7fb;
            color: #1e293b;
            min-height: 100vh;
            display: flex
        }

        /* ── Modern Whitebase & Royal Blue Studio Sidebar (Reference Design) ── */
        .sidebar {
            width: 253px;
            background: #132849;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            border-right: 1px solid rgba(255, 255, 255, 0.06);
            box-shadow: 8px 0 24px rgba(15, 23, 42, 0.18);
            scrollbar-width: thin;
            scrollbar-color: rgba(148, 163, 184, 0.34) transparent;
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.34);
            border-radius: 4px;
        }

        /* Logo Brand Box */
        .sb-brand-box {
            padding: 18px 17px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            background: #102440;
        }

        .sb-brand-flex {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sb-logo-emblem-grid {
            width: 30px;
            height: 30px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3px;
            flex-shrink: 0;
        }

        .sb-emblem-dot {
            background: #93c5fd;
            border-radius: 4px;
        }
        .sb-emblem-dot:nth-child(2) { background: #38bdf8; }
        .sb-emblem-dot:nth-child(3) { background: #60a5fa; }
        .sb-emblem-dot:nth-child(4) { background: #22c55e; }

        .sb-logo-text {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13.5px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 0;
            line-height: 1.15;
            text-transform: uppercase;
        }

        .sb-logo-sub {
            font-size: 9.5px;
            color: #9fb2cb;
            margin-top: 2px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .sb-pulse-dot {
            width: 5px;
            height: 5px;
            background: #10b981;
            border-radius: 50%;
            display: inline-block;
        }

        .sb-direct-link {
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 52px;
            padding: 0 17px;
            color: #dbeafe;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: background 0.16s ease, color 0.16s ease;
        }

        .sb-direct-link i {
            width: 20px;
            color: #93a9c6;
            font-size: 16px;
            text-align: center;
        }

        .sb-direct-link:hover,
        .sb-direct-link.active {
            background: #1a3457;
            color: #ffffff;
        }

        .sb-direct-link:hover i,
        .sb-direct-link.active i {
            color: #c7d2fe;
        }

        /* Category Section Labels */
        .sb-section-label {
            font-size: 12px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 0 17px;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 40px;
            line-height: 1;
        }

        /* Navigation List */
        .sb-nav {
            padding: 0 0 10px;
            flex: 1;
        }

        .sb-dropdown-item {
            position: relative;
            margin: 0;
        }

        .sb-dropdown-btn {
            display: flex;
            align-items: center;
            gap: 0;
            min-height: 40px;
            padding: 0;
            margin: 0;
            border-radius: 0;
            color: #ffffff;
            text-decoration: none;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            cursor: pointer;
            width: 100%;
            background: transparent;
            border: 0;
            font-family: inherit;
            text-align: left;
            transition: background 0.16s ease, color 0.16s ease;
        }

        .sb-icon-wrap {
            width: 38px;
            height: 40px;
            border-radius: 0;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #93a9c6;
            flex-shrink: 0;
            transition: color 0.16s ease;
            border: 0;
        }

        .sb-dropdown-btn:hover {
            background: #1a3457;
            color: #ffffff;
        }

        .sb-dropdown-btn:hover .sb-icon-wrap {
            background: transparent;
            color: #c7d2fe;
            border-color: transparent;
        }

        .sb-dropdown-btn > .sb-section-label {
            padding-right: 0;
        }

        .sb-dropdown-btn .sb-badge + .sb-arrow {
            margin-left: 10px;
        }

        /* Active / Open Dropdown Button */
        .sb-dropdown-item.open > .sb-dropdown-btn {
            background: #233a59;
            color: #ffffff;
            font-weight: 800;
            border-color: transparent;
            box-shadow: inset 0 1px rgba(255, 255, 255, 0.03), inset 0 -1px rgba(0, 0, 0, 0.1);
        }

        .sb-dropdown-item.open > .sb-dropdown-btn .sb-icon-wrap {
            background: transparent;
            color: #c7d2fe;
            border-color: transparent;
        }

        .sb-dropdown-item.open > .sb-dropdown-btn .sb-arrow {
            transform: rotate(90deg);
            color: #ffffff;
        }

        .sb-arrow {
            margin-left: auto;
            padding-right: 20px;
            font-size: 10px;
            color: #ffffff;
            transition: transform 0.2s ease, color 0.2s ease;
        }

        /* Nested Submenu */
        .sb-submenu {
            display: none;
            margin: 0;
            padding: 5px 0 7px;
            border-left: 0;
            background: #132849;
        }

        .sb-dropdown-item.open > .sb-submenu {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .sb-sublink {
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 38px;
            padding: 0 17px 0 32px;
            border-radius: 0;
            color: #cbd5e1;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.15s ease, color 0.15s ease;
            position: relative;
        }

        .sb-sublink i {
            width: 16px;
            color: #93a9c6;
            font-size: 15px;
            text-align: center;
            flex-shrink: 0;
        }

        .sb-sublink:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.06);
            transform: none;
        }

        .sb-sublink:hover i,
        .sb-sublink.active i {
            color: #ffffff;
        }

        .sb-sublink.active {
            color: #ffffff;
            font-weight: 700;
            background: #3b4658;
            border: 0;
            box-shadow: inset 3px 0 #9fb7d8;
        }

        .sb-sublink.active::before {
            content: none;
        }

        .sb-badge {
            margin-left: auto;
            background: #e11d48;
            color: white;
            font-size: 9.5px;
            font-weight: 800;
            padding: 2px 6px;
            border-radius: 999px;
            box-shadow: 0 2px 6px rgba(225, 29, 72, 0.25);
        }

        /* Bottom User Profile Card */
        .sb-user-card {
            margin: 12px 12px 14px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.15s ease;
        }
        .sb-user-card:hover {
            background: rgba(255, 255, 255, 0.09);
            border-color: rgba(255, 255, 255, 0.16);
            box-shadow: none;
        }

        .sb-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #e5edf8;
            color: #132849;
            font-size: 12px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 1.5px solid rgba(255, 255, 255, 0.22);
        }

        .sb-user-info {
            flex: 1;
            min-width: 0;
        }

        .sb-user-name {
            font-size: 12.5px;
            font-weight: 700;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sb-user-role-badge {
            display: inline-block;
            font-size: 10.5px;
            font-weight: 600;
            color: #9fb2cb;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sb-logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #cbd5e1;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 8px;
            width: 100%;
            font-family: inherit;
            transition: all 0.15s ease;
        }

        .sb-logout-btn:hover {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border-color: rgba(239, 68, 68, 0.4);
        }

        /* ── MAIN CONTENT LAYOUT ── */
        .admin-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .admin-topbar {
            background: #ffffff;
            padding: 0 28px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #edf2f7;
            position: sticky;
            top: 0;
            z-index: 10;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        }

        .admin-topbar-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .admin-topbar-actions {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 12px;
        }

        .btn-view-store {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 6px 14px;
            border-radius: 8px;
            color: #00285a;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .btn-view-store:hover {
            background: #00285a;
            color: #ffffff;
            border-color: #00285a;
        }

        .admin-content {
            padding: 24px 28px;
            flex: 1;
        }

        /* Flash Messages */
        .alert-success {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
            padding: 12px 18px;
            border-radius: 12px;
            margin-bottom: 18px;
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-error {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
            padding: 12px 18px;
            border-radius: 12px;
            margin-bottom: 18px;
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ── 2026 Global SaaS UI Component Design System ── */
        .dash-master-wrap {
            display: flex;
            flex-direction: column;
            gap: 20px;
            padding-bottom: 40px;
        }

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
            font-size: 12.5px;
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
            background: #ffd700;
            color: #00285a;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 800;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s ease;
            box-shadow: 0 4px 12px rgba(255, 215, 0, 0.25);
            border: none;
            cursor: pointer;
        }

        .btn-primary-hr:hover {
            background: #ffffff;
            color: #00285a;
        }

        .btn-secondary-hr {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.15s ease;
        }

        .btn-secondary-hr:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

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

        .kpi-grid-8 {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .kpi-grid-5 {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 14px;
        }

        .kpi-grid-4 {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        @media(max-width: 1200px) {
            .kpi-grid-8, .kpi-grid-5, .kpi-grid-4 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media(max-width: 600px) {
            .kpi-grid-8, .kpi-grid-5, .kpi-grid-4 { grid-template-columns: 1fr; }
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
        .icon-indigo { background: #e0e7ff; color: #4338ca; }
        .icon-purple { background: #faf5ff; color: #7e22ce; }
        .icon-cyan { background: #ecfeff; color: #0e7490; }
        .icon-emerald { background: #ecfdf5; color: #047857; }
        .icon-amber { background: #fef3c7; color: #b45309; }
        .icon-teal { background: #ccfbf1; color: #0f766e; }
        .icon-rose { background: #fee2e2; color: #dc2626; }

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
            .dash-grid-70-30, .dash-grid-50-50 { grid-template-columns: 1fr; }
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

        .table-responsive-clean {
            overflow-x: auto;
        }

        .dash-table {
            width: 100%;
            border-collapse: collapse;
        }

        .dash-table th {
            padding: 12px 18px;
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
            padding: 13px 18px;
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

        .badge-units {
            font-size: 11px;
            color: #475569;
            background: #f1f5f9;
            padding: 3px 8px;
            border-radius: 6px;
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
            border: none;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-circle-edit:hover {
            background: #00285a;
            color: #ffffff;
        }

        .form-control-admin {
            padding: 9px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 13px;
            font-family: inherit;
            outline: none;
            transition: .15s;
            width: 100%;
            background: white;
        }

        .form-control-admin:focus {
            border-color: #00285a;
            box-shadow: 0 0 0 3px rgba(0, 40, 90, 0.08);
        }

        .btn-admin {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            border-radius: 9px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: none;
            font-family: inherit;
            transition: .15s;
        }

        .btn-navy { background: #00285a; color: white; }
        .btn-navy:hover { background: #1e3f75; color: white; }
        .btn-light { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
        .btn-light:hover { background: #e2e8f0; color: #0f172a; }
        .btn-sm { padding: 6px 12px; font-size: 11px; }

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

        /* Sidebar menu structure refresh */
        .sidebar {
            width: 253px;
            background: #132849;
            border-right: 1px solid rgba(255, 255, 255, 0.06);
            box-shadow: 8px 0 24px rgba(15, 23, 42, 0.18);
        }

        .sb-brand-box {
            position: sticky;
            top: 0;
            z-index: 4;
            padding: 18px 17px 16px;
            background: #102440;
            backdrop-filter: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .sb-brand-flex {
            padding: 0;
            background: transparent;
            border: 0;
            border-radius: 0;
            box-shadow: none;
        }

        .sb-brand-flex img {
            height: 34px !important;
            margin-right: 2px !important;
            filter: drop-shadow(0 4px 10px rgba(255, 255, 255, 0.14));
        }

        .sb-logo-text {
            color: #ffffff;
            letter-spacing: 0.4px;
        }

        .sb-logo-sub {
            color: #cbd5e1;
            letter-spacing: 0.7px;
        }

        .sb-nav {
            padding: 0 0 10px;
        }

        .sb-section-label {
            display: flex;
            align-items: center;
            gap: 9px;
            justify-content: space-between;
            min-height: 40px;
            padding: 0 17px;
            color: #ffffff;
            font-size: 12px;
            letter-spacing: 1.5px;
        }

        .sb-section-label::after {
            content: none;
        }

        .sb-dropdown-item {
            margin: 0;
        }

        .sb-dropdown-btn {
            width: 100%;
            min-height: 40px;
            margin: 0;
            padding: 0;
            border-radius: 0;
            color: #ffffff;
            border: 0;
            position: relative;
        }

        .sb-dropdown-btn > span {
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .sb-icon-wrap {
            width: 38px;
            height: 40px;
            border-radius: 0;
            background: transparent;
            color: #93a9c6;
            border-color: transparent;
        }

        .sb-dropdown-btn:hover {
            background: #1a3457;
            border-color: transparent;
            color: #ffffff;
            transform: none;
        }

        .sb-dropdown-btn:hover .sb-icon-wrap {
            background: transparent;
            color: #c7d2fe;
            border-color: transparent;
        }

        .sb-dropdown-item.open > .sb-dropdown-btn {
            background: #233a59;
            color: #ffffff;
            border-color: transparent;
            box-shadow: inset 0 1px rgba(255, 255, 255, 0.03), inset 0 -1px rgba(0, 0, 0, 0.1);
        }

        .sb-dropdown-item.open > .sb-dropdown-btn::before {
            content: none;
        }

        .sb-dropdown-item.open > .sb-dropdown-btn .sb-icon-wrap {
            background: transparent;
            color: #c7d2fe;
            border-color: transparent;
        }

        .sb-dropdown-item.open > .sb-dropdown-btn .sb-arrow {
            color: #ffffff;
        }

        .sb-submenu {
            margin: 0;
            padding: 5px 0 7px;
            border-left: 0;
            border-radius: 0;
            background: #132849;
            border: 0;
            box-shadow: none;
        }

        .sb-dropdown-item.open > .sb-submenu {
            gap: 3px;
        }

        .sb-sublink {
            min-height: 38px;
            padding: 0 17px 0 32px;
            border-radius: 0;
            color: #cbd5e1;
            font-size: 14px;
            line-height: 1.25;
            border: 0;
        }

        .sb-sublink::before {
            content: none;
        }

        .sb-sublink:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: transparent;
            color: #ffffff;
            transform: none;
        }

        .sb-sublink.active {
            background: #3b4658;
            color: #ffffff;
            border-color: transparent;
            box-shadow: inset 3px 0 #9fb7d8;
        }

        .sb-sublink.active::before {
            content: none;
        }

        .sb-badge {
            font-size: 9px;
            padding: 2px 6px;
            white-space: nowrap;
            box-shadow: none;
        }

        .sb-user-card {
            position: sticky;
            bottom: 0;
            margin: 12px 12px 14px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 8px;
            border-color: rgba(255, 255, 255, 0.1);
            box-shadow: none;
        }

        .sb-user-card:hover {
            background: rgba(255, 255, 255, 0.09);
            box-shadow: none;
            transform: none;
        }

        .sb-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #e5edf8;
            border: 1.5px solid rgba(255, 255, 255, 0.22);
        }

        @media (max-width: 992px) {
            .sidebar {
                width: 248px;
            }

            .sb-submenu {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    @php $user = auth('admin')->user() ?? auth()->user(); @endphp

    <aside class="sidebar">
        {{-- Brand Box --}}
        <div class="sb-brand-box">
            <div class="sb-brand-flex">
                {{-- <img src="{{ asset('images/THE TREND THEORY-logo-white-trans.png') }}" alt="THE TREND THEORY" style="height: 36px; width: auto; object-fit: contain; margin-right: 10px;"> --}}
                <div>
                    <div class="sb-logo-text">THE TREND THEORY</div>
                    <div class="sb-logo-sub">
                        <span class="sb-pulse-dot"></span>
                        <span>ADMIN STUDIO 2.0</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Collapsible Navigation Hub --}}
        <nav class="sb-nav">
            <a href="{{ route('admin.dashboard') }}"
                class="sb-direct-link {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>

            {{-- Catalog & Content --}}
            @if ($user->hasPermission('products.view') || $user->hasPermission('categories.view') || $user->hasPermission('masters.view'))
                <div class="sb-dropdown-item {{ request()->routeIs('admin.products*') || request()->routeIs('admin.inventory*') || request()->routeIs('admin.categories*') || request()->is('admin/masters*') || request()->routeIs('admin.media*') ? 'open' : '' }}">
                    <button type="button" class="sb-dropdown-btn" onclick="toggleSbMenu(this)">
                        <span class="sb-section-label">Content</span>
                        <i class="bi bi-chevron-right sb-arrow"></i>
                    </button>
                    <div class="sb-submenu">
                        @if ($user->hasPermission('categories.view'))
                            <a href="{{ route('admin.categories.index') }}"
                                class="sb-sublink {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                                <i class="bi bi-diagram-3"></i>
                                <span>Categories</span>
                            </a>
                        @endif

                        @if ($user->hasPermission('products.view'))
                            <a href="{{ route('admin.products.index') }}"
                                class="sb-sublink {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                                <i class="bi bi-box-seam"></i>
                                <span>Products</span>
                            </a>
                            @php $lowStockNavCount = \App\Models\Product::where('stock', '<=', 10)->count(); @endphp
                            <a href="{{ route('admin.inventory.index') }}"
                                class="sb-sublink {{ request()->routeIs('admin.inventory*') ? 'active' : '' }}">
                                <i class="bi bi-boxes"></i>
                                <span>Inventory</span>
                                @if ($lowStockNavCount > 0)
                                    <span class="sb-badge">{{ $lowStockNavCount }} Low</span>
                                @endif
                            </a>
                            <a href="{{ route('admin.products.create') }}"
                                class="sb-sublink {{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                                <i class="bi bi-plus-square"></i>
                                <span>Add Product</span>
                            </a>
                        @endif

                        @if ($user->hasPermission('masters.view'))
                            <a href="{{ route('admin.masters.index', 'sizes') }}"
                                class="sb-sublink {{ request()->is('admin/masters/sizes*') ? 'active' : '' }}">
                                <i class="bi bi-rulers"></i>
                                <span>Size Variants</span>
                            </a>
                            <a href="{{ route('admin.masters.index', 'colors') }}"
                                class="sb-sublink {{ request()->is('admin/masters/colors*') ? 'active' : '' }}">
                                <i class="bi bi-palette"></i>
                                <span>Color Palette</span>
                            </a>
                        @endif

                        @if ($user->hasPermission('media.upload') || $user->isAdmin() || $user->isSuperAdmin())
                            <a href="{{ route('admin.media.gallery') }}"
                                class="sb-sublink {{ request()->routeIs('admin.media*') ? 'active' : '' }}">
                                <i class="bi bi-images"></i>
                                <span>Lookbook Media</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Sales Leads --}}
            @if ($user->hasPermission('orders.view') || $user->hasPermission('coupons.view') || $user->hasPermission('customers.view'))
                @php
                    $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
                    $pendingReturnsCount = \App\Models\OrderReturn::where('status', 'pending')->count();
                @endphp
                <div class="sb-dropdown-item {{ request()->routeIs('admin.orders*') || request()->routeIs('admin.returns*') || request()->routeIs('admin.pincodes*') || request()->is('admin/coupons*') || request()->routeIs('admin.customers*') || request()->routeIs('admin.sales*') || request()->routeIs('admin.investor*') ? 'open' : '' }}">
                    <button type="button" class="sb-dropdown-btn" onclick="toggleSbMenu(this)">
                        <span class="sb-section-label">Leads</span>
                        @if ($pendingOrders > 0)
                            <span class="sb-badge">{{ $pendingOrders }}</span>
                        @endif
                        <i class="bi bi-chevron-right sb-arrow"></i>
                    </button>
                    <div class="sb-submenu">
                        @if ($user->isAdmin() || $user->isSuperAdmin())
                            <a href="{{ route('admin.investor.dashboard') }}"
                                class="sb-sublink {{ request()->routeIs('admin.investor.dashboard') ? 'active' : '' }}"
                                style="{{ request()->routeIs('admin.investor.dashboard') ? '' : 'color:#3b82f6;' }}">
                                <i class="bi bi-pie-chart-fill" style="color: #f59e0b;"></i>
                                <span class="fw-bold">Investor Cockpit</span>
                                <span class="badge bg-warning text-dark font-xs fw-bold ms-auto" style="font-size: 9px; padding: 2px 5px;">DECK</span>
                            </a>
                        @endif

                        @if ($user->isAdmin() || $user->isSuperAdmin() || $user->hasPermission('orders.view'))
                            <a href="{{ route('admin.sales.analytics') }}"
                                class="sb-sublink {{ request()->routeIs('admin.sales.analytics') ? 'active' : '' }}">
                                <i class="bi bi-graph-up-arrow"></i>
                                <span>Sales Intelligence</span>
                            </a>
                        @endif

                        @if ($user->hasPermission('orders.view'))
                            <a href="{{ route('admin.orders.index') }}"
                                class="sb-sublink {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                                <i class="bi bi-bag-check"></i>
                                <span>Orders</span>
                                @if ($pendingOrders > 0)
                                    <span class="sb-badge">{{ $pendingOrders }}</span>
                                @endif
                            </a>
                        @endif

                        @if ($user->isAdmin() || $user->isSuperAdmin() || $user->hasPermission('orders.view'))
                            <a href="{{ route('admin.returns.index') }}"
                                class="sb-sublink {{ request()->routeIs('admin.returns*') ? 'active' : '' }}">
                                <i class="bi bi-arrow-counterclockwise"></i>
                                <span>Returns</span>
                                @if ($pendingReturnsCount > 0)
                                    <span class="sb-badge">{{ $pendingReturnsCount }}</span>
                                @endif
                            </a>
                            <a href="{{ route('admin.pincodes.index') }}"
                                class="sb-sublink {{ request()->routeIs('admin.pincodes*') ? 'active' : '' }}">
                                <i class="bi bi-geo-alt"></i>
                                <span>Pincodes</span>
                            </a>
                        @endif

                        @if ($user->hasPermission('customers.view'))
                            <a href="{{ route('admin.customers.index') }}"
                                class="sb-sublink {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
                                <i class="bi bi-people"></i>
                                <span>Customers</span>
                            </a>
                        @endif

                        @if ($user->hasPermission('coupons.view'))
                            <a href="{{ route('admin.coupons.index') }}"
                                class="sb-sublink {{ request()->is('admin/coupons*') ? 'active' : '' }}">
                                <i class="bi bi-ticket-perforated"></i>
                                <span>Coupons</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            @if ($user->hasPermission('settings.view') || $user->isAdmin() || $user->isSuperAdmin())
                <div class="sb-dropdown-item {{ request()->routeIs('admin.settings.index') || request()->routeIs('admin.announcements*') ? 'open' : '' }}">
                    <button type="button" class="sb-dropdown-btn" onclick="toggleSbMenu(this)">
                        <span class="sb-section-label">Homepage</span>
                        <i class="bi bi-chevron-right sb-arrow"></i>
                    </button>
                    <div class="sb-submenu">
                        <a href="{{ route('admin.settings.index') }}"
                            class="sb-sublink {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                            <i class="bi bi-window-sidebar"></i>
                            <span>Hero Slides</span>
                        </a>
                        @if ($user->isAdmin() || $user->isSuperAdmin())
                            <a href="{{ route('admin.announcements.index') }}"
                                class="sb-sublink {{ request()->routeIs('admin.announcements*') ? 'active' : '' }}">
                                <i class="bi bi-megaphone"></i>
                                <span>Announcements</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            @if ($user->isAdmin() || $user->isSuperAdmin())
                <div class="sb-dropdown-item {{ request()->routeIs('admin.faqs*') ? 'open' : '' }}">
                    <button type="button" class="sb-dropdown-btn" onclick="toggleSbMenu(this)">
                        <span class="sb-section-label">Site Navigation</span>
                        <i class="bi bi-chevron-right sb-arrow"></i>
                    </button>
                    <div class="sb-submenu">
                        <a href="{{ route('admin.faqs.index') }}"
                            class="sb-sublink {{ request()->routeIs('admin.faqs*') ? 'active' : '' }}">
                            <i class="bi bi-question-circle"></i>
                            <span>FAQs</span>
                        </a>
                    </div>
                </div>
            @endif

            @if ($user->isAdmin() || $user->isSuperAdmin() || $user->hasPermission('products.view'))
                <div class="sb-dropdown-item {{ request()->routeIs('admin.blogs*') || request()->routeIs('admin.news*') ? 'open' : '' }}">
                    <button type="button" class="sb-dropdown-btn" onclick="toggleSbMenu(this)">
                        <span class="sb-section-label">News &amp; Blog</span>
                        <i class="bi bi-chevron-right sb-arrow"></i>
                    </button>
                    <div class="sb-submenu">
                        <a href="{{ route('admin.blogs.index') }}"
                            class="sb-sublink {{ request()->routeIs('admin.blogs.index') ? 'active' : '' }}">
                            <i class="bi bi-journal-text"></i>
                            <span>Blog Articles</span>
                        </a>
                        <a href="{{ route('admin.blogs.create') }}"
                            class="sb-sublink {{ request()->routeIs('admin.blogs.create') ? 'active' : '' }}">
                            <i class="bi bi-pencil-square"></i>
                            <span>Write Blog</span>
                        </a>
                        <a href="{{ route('admin.news.index') }}"
                            class="sb-sublink {{ request()->routeIs('admin.news.index') ? 'active' : '' }}">
                            <i class="bi bi-newspaper"></i>
                            <span>News &amp; Press</span>
                        </a>
                        <a href="{{ route('admin.news.create') }}"
                            class="sb-sublink {{ request()->routeIs('admin.news.create') ? 'active' : '' }}">
                            <i class="bi bi-send-plus"></i>
                            <span>Publish News</span>
                        </a>
                    </div>
                </div>
            @endif

            @if ($user->hasPermission('reviews.view') || $user->hasPermission('analytics.view') || $user->isAdmin() || $user->isSuperAdmin())
                @php $liveActive = \App\Models\VisitorLog::where('last_activity_at', '>=', now()->subMinutes(5))->count(); @endphp
                <div class="sb-dropdown-item {{ request()->routeIs('admin.reviews*') || request()->routeIs('admin.analytics*') || request()->routeIs('admin.notifications*') || request()->routeIs('admin.newsletter*') ? 'open' : '' }}">
                    <button type="button" class="sb-dropdown-btn" onclick="toggleSbMenu(this)">
                        <span class="sb-section-label">Social Proof</span>
                        @if ($liveActive > 0)
                            <span class="sb-badge">{{ $liveActive }}</span>
                        @endif
                        <i class="bi bi-chevron-right sb-arrow"></i>
                    </button>
                    <div class="sb-submenu">
                        @if ($user->hasPermission('reviews.view') || $user->isAdmin() || $user->isSuperAdmin())
                            <a href="{{ route('admin.reviews.index') }}"
                                class="sb-sublink {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
                                <i class="bi bi-star"></i>
                                <span>Reviews</span>
                            </a>
                        @endif
                        @if ($user->hasPermission('analytics.view') || $user->isAdmin() || $user->isSuperAdmin())
                            <a href="{{ route('admin.analytics.index') }}"
                                class="sb-sublink {{ request()->routeIs('admin.analytics.index') ? 'active' : '' }}">
                                <i class="bi bi-activity"></i>
                                <span>Visitors</span>
                                @if ($liveActive > 0)
                                    <span class="sb-badge">LIVE</span>
                                @endif
                            </a>
                            <a href="{{ route('admin.analytics.activities') }}"
                                class="sb-sublink {{ request()->routeIs('admin.analytics.activities') ? 'active' : '' }}">
                                <i class="bi bi-broadcast-pin"></i>
                                <span>Activity Feed</span>
                            </a>
                            <a href="{{ route('admin.notifications.index') }}"
                                class="sb-sublink {{ request()->routeIs('admin.notifications*') ? 'active' : '' }}">
                                <i class="bi bi-bell"></i>
                                <span>Notifications</span>
                            </a>
                            <a href="{{ route('admin.newsletter.index') }}"
                                class="sb-sublink {{ request()->routeIs('admin.newsletter*') ? 'active' : '' }}">
                                <i class="bi bi-envelope-paper"></i>
                                <span>Newsletter</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            @if ($user->hasPermission('settings.view'))
                <div class="sb-dropdown-item {{ request()->routeIs('admin.settings.payment') || request()->routeIs('admin.settings.shipping') || request()->routeIs('admin.settings.email-templates') || request()->routeIs('admin.settings.sms') || request()->routeIs('admin.backups*') ? 'open' : '' }}">
                    <button type="button" class="sb-dropdown-btn" onclick="toggleSbMenu(this)">
                        <span class="sb-section-label">Company Pages</span>
                        <i class="bi bi-chevron-right sb-arrow"></i>
                    </button>
                    <div class="sb-submenu">
                        <a href="{{ route('admin.settings.payment') }}"
                            class="sb-sublink {{ request()->routeIs('admin.settings.payment') ? 'active' : '' }}">
                            <i class="bi bi-credit-card"></i>
                            <span>Payment</span>
                        </a>
                        <a href="{{ route('admin.settings.shipping') }}"
                            class="sb-sublink {{ request()->routeIs('admin.settings.shipping') ? 'active' : '' }}">
                            <i class="bi bi-truck"></i>
                            <span>Shipping</span>
                        </a>
                        <a href="{{ route('admin.settings.email-templates') }}"
                            class="sb-sublink {{ request()->routeIs('admin.settings.email-templates') ? 'active' : '' }}">
                            <i class="bi bi-envelope-paper"></i>
                            <span>Email Templates</span>
                        </a>
                        <a href="{{ route('admin.settings.sms') }}"
                            class="sb-sublink {{ request()->routeIs('admin.settings.sms') ? 'active' : '' }}">
                            <i class="bi bi-chat-dots"></i>
                            <span>SMS &amp; WhatsApp</span>
                        </a>
                        <a href="{{ route('admin.backups.index') }}"
                            class="sb-sublink {{ request()->routeIs('admin.backups*') ? 'active' : '' }}">
                            <i class="bi bi-database-check"></i>
                            <span>Backups</span>
                        </a>
                    </div>
                </div>
            @endif

            @if ($user->hasPermission('employees.view') || $user->isSuperAdmin() || $user->isAdmin() || $user->hasPermission('roles.manage'))
                <div class="sb-dropdown-item {{ request()->routeIs('admin.staff*') || request()->routeIs('admin.employees*') || request()->routeIs('admin.attendance*') ? 'open' : '' }}">
                    <button type="button" class="sb-dropdown-btn" onclick="toggleSbMenu(this)">
                        <span class="sb-section-label">Careers</span>
                        <i class="bi bi-chevron-right sb-arrow"></i>
                    </button>
                    <div class="sb-submenu">
                        @if ($user->isSuperAdmin() || $user->isAdmin() || $user->hasPermission('roles.manage'))
                            <a href="{{ route('admin.staff.index') }}"
                                class="sb-sublink {{ request()->routeIs('admin.staff*') ? 'active' : '' }}">
                                <i class="bi bi-person-gear"></i>
                                <span>Permissions</span>
                            </a>
                        @endif

                        @if ($user->hasPermission('employees.view'))
                            <a href="{{ route('admin.employees.index') }}"
                                class="sb-sublink {{ request()->routeIs('admin.employees*') ? 'active' : '' }}">
                                <i class="bi bi-person-badge"></i>
                                <span>Employees</span>
                            </a>
                            <a href="{{ route('admin.attendance.index') }}"
                                class="sb-sublink {{ request()->routeIs('admin.attendance*') ? 'active' : '' }}">
                                <i class="bi bi-calendar-check"></i>
                                <span>Attendance</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </nav>

        {{-- Bottom User Profile Card (Reference Style) --}}
        <div class="sb-user-card">
            <div class="sb-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
            <div class="sb-user-info">
                <div class="sb-user-name">{{ $user->name }}</div>
                <div class="sb-user-role-badge">{{ $user->email ?: $user->role_label }}</div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" title="Logout" style="background:transparent; border:none; color:#94a3b8; font-size:14px; cursor:pointer; padding:4px;">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </aside>

    <main class="admin-main">
        <div class="admin-topbar">
            <div class="admin-topbar-title">
                <i class="bi bi-grid-fill" style="color:#ffd700;font-size:16px;"></i>
                <span>@yield('title', 'Dashboard')</span>
            </div>
            <div class="admin-topbar-actions">
                <a href="{{ route('home') }}" target="_blank" class="btn-view-store">
                    <i class="bi bi-box-arrow-up-right"></i> View Live Store
                </a>
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
        function toggleSbMenu(btn) {
            var parent = btn.closest('.sb-dropdown-item');
            if (!parent) return;
            var wasOpen = parent.classList.contains('open');

            document.querySelectorAll('.sb-nav .sb-dropdown-item.open').forEach(function(item) {
                if (item !== parent) {
                    item.classList.remove('open');
                    var otherBtn = item.querySelector('.sb-dropdown-btn');
                    if (otherBtn) otherBtn.setAttribute('aria-expanded', 'false');
                }
            });

            parent.classList.toggle('open', !wasOpen);
            btn.setAttribute('aria-expanded', wasOpen ? 'false' : 'true');
        }

        // Auto expand active menus on load
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.sb-dropdown-btn').forEach(function(btn) {
                var parent = btn.closest('.sb-dropdown-item');
                btn.setAttribute('aria-expanded', parent && parent.classList.contains('open') ? 'true' : 'false');
            });

            var activeSublinks = document.querySelectorAll('.sb-submenu .sb-sublink.active');
            activeSublinks.forEach(function(sublink) {
                var dropdownItem = sublink.closest('.sb-dropdown-item');
                if (dropdownItem) {
                    dropdownItem.classList.add('open');
                    var btn = dropdownItem.querySelector('.sb-dropdown-btn');
                    if (btn) btn.setAttribute('aria-expanded', 'true');
                }
            });
        });

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
    <!-- SweetAlert2 for Admin Notifications & Confirmation Modals -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: @json(session('success')),
                        showConfirmButton: false,
                        timer: 3500,
                        timerProgressBar: true
                    });
                }
            @elseif(session('error'))
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: @json(session('error')),
                        confirmButtonColor: '#00285a'
                    });
                }
            @endif
        });
    </script>
    @stack('scripts')
</body>

</html>
