{{-- resources/views/admin/products/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Product Catalog & Inventory')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

    :root {
        --catalog-primary: #00285a;
        --catalog-primary-hover: #0a3d82;
        --catalog-primary-light: #eff6ff;
        --catalog-bg: #f8fafc;
        --catalog-card: #ffffff;
        --catalog-border: #e2e8f0;
        --catalog-text-main: #0f172a;
        --catalog-text-muted: #64748b;
    }

    .products-master-wrap {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        display: flex;
        flex-direction: column;
        gap: 22px;
        color: var(--catalog-text-main);
        max-width: 1440px;
        margin: 0 auto;
        padding-bottom: 70px;
        position: relative;
    }

    /* ── 1. Header Toolbar ── */
    .catalog-header-bar {
        background: #ffffff;
        border: 1px solid var(--catalog-border);
        border-radius: 18px;
        padding: 20px 26px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
    }
    .catalog-header-title-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .catalog-header-title-row {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .catalog-header-title {
        font-size: 22px;
        font-weight: 800;
        color: #00285a;
        letter-spacing: -0.4px;
        margin: 0;
        line-height: 1.2;
    }
    .catalog-live-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .pulse-dot-blue {
        width: 6px;
        height: 6px;
        background: #2563eb;
        border-radius: 50%;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25);
        animation: pulseCatalog 2s infinite;
    }
    @keyframes pulseCatalog {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.25); opacity: 0.7; }
    }
    .catalog-header-sub {
        font-size: 13px;
        color: var(--catalog-text-muted);
        margin: 0;
    }
    .catalog-header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .btn-catalog-secondary {
        background: #ffffff;
        color: #334155;
        border: 1.5px solid var(--catalog-border);
        padding: 9px 16px;
        border-radius: 11px;
        font-size: 12.5px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.15s ease;
        cursor: pointer;
    }
    .btn-catalog-secondary:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
        transform: translateY(-1px);
    }
    .btn-catalog-primary {
        background: #00285a;
        color: #ffffff;
        border: none;
        padding: 10px 20px;
        border-radius: 11px;
        font-size: 13px;
        font-weight: 800;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 14px rgba(0, 40, 90, 0.25);
        cursor: pointer;
    }
    .btn-catalog-primary:hover {
        background: #0a3d82;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(0, 40, 90, 0.35);
    }

    /* ── 2. Bento KPI Cards ── */
    .kpi-modern-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }
    @media (max-width: 1100px) {
        .kpi-modern-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 640px) {
        .kpi-modern-grid { grid-template-columns: 1fr; }
    }
    .kpi-modern-card {
        background: #ffffff;
        border: 1px solid var(--catalog-border);
        border-radius: 16px;
        padding: 20px 22px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
        user-select: none;
    }
    .kpi-modern-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
        border-color: #cbd5e1;
    }
    .kpi-modern-card.card-active {
        border-color: #00285a;
        box-shadow: 0 0 0 2px rgba(0, 40, 90, 0.15);
    }
    .kpi-card-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .kpi-card-label {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }
    .kpi-icon-box {
        width: 38px;
        height: 38px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    .kpi-icon-blue { background: #eff6ff; color: #2563eb; }
    .kpi-icon-emerald { background: #ecfdf5; color: #059669; }
    .kpi-icon-amber { background: #fffbeb; color: #d97706; }
    .kpi-icon-purple { background: #faf5ff; color: #9333ea; }

    .kpi-card-number {
        font-size: 26px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
        margin-bottom: 4px;
        letter-spacing: -0.5px;
    }
    .kpi-card-sub {
        font-size: 12px;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* ── 3. Main Catalog Card ── */
    .products-table-card {
        background: #ffffff;
        border: 1px solid var(--catalog-border);
        border-radius: 18px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        overflow: hidden;
    }

    /* Segment Pills Tab Bar */
    .table-segment-bar {
        padding: 14px 22px 10px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
        background: #ffffff;
    }
    .segment-pill-item {
        padding: 7px 16px;
        border-radius: 10px;
        font-size: 12.5px;
        font-weight: 700;
        color: #64748b;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        border: none;
        background: transparent;
        transition: all 0.15s ease;
    }
    .segment-pill-item:hover {
        color: #0f172a;
        background: #f8fafc;
    }
    .segment-pill-item.active {
        background: #00285a;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 40, 90, 0.18);
    }
    .segment-pill-badge {
        font-size: 11px;
        font-weight: 800;
        padding: 2px 7px;
        border-radius: 999px;
        background: rgba(0, 0, 0, 0.06);
    }
    .segment-pill-item.active .segment-pill-badge {
        background: rgba(255, 255, 255, 0.22);
        color: #ffffff;
    }

    /* Filter Toolbar */
    .table-filter-toolbar {
        padding: 14px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
    }
    .filter-search-box {
        position: relative;
        min-width: 260px;
        flex: 1;
        max-width: 380px;
    }
    .filter-search-input {
        width: 100%;
        padding: 9px 36px 9px 38px;
        border: 1.5px solid #e2e8f0;
        border-radius: 11px;
        font-size: 13px;
        font-family: inherit;
        outline: none;
        transition: all 0.15s ease;
        background: #ffffff;
        color: #0f172a;
    }
    .filter-search-input:focus {
        border-color: #00285a;
        box-shadow: 0 0 0 3px rgba(0, 40, 90, 0.08);
    }
    .filter-search-icon {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 15px;
        pointer-events: none;
    }
    .filter-search-clear {
        position: absolute;
        right: 11px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 14px;
        padding: 4px;
        display: none;
        border-radius: 50%;
    }
    .filter-search-clear:hover {
        color: #0f172a;
    }
    .filter-select {
        padding: 9px 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: 11px;
        font-size: 12.5px;
        font-family: inherit;
        font-weight: 600;
        color: #334155;
        background: #ffffff;
        outline: none;
        cursor: pointer;
        transition: border-color 0.15s ease;
    }
    .filter-select:focus {
        border-color: #00285a;
    }
    .datatable-control-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        margin: 0;
    }

    /* ── 4. Floating Bulk Actions Toolbar ── */
    .bulk-floating-bar {
        position: fixed;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%) translateY(120px);
        background: #0f172a;
        color: #ffffff;
        border-radius: 14px;
        padding: 10px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.3);
        z-index: 999;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        pointer-events: none;
        opacity: 0;
    }
    .bulk-floating-bar.active {
        transform: translateX(-50%) translateY(0);
        pointer-events: auto;
        opacity: 1;
    }
    .bulk-count-badge {
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
    }
    .bulk-btn-action {
        background: rgba(255, 255, 255, 0.1);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.18);
        padding: 6px 13px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
    }
    .bulk-btn-action:hover {
        background: rgba(255, 255, 255, 0.22);
    }
    .bulk-btn-danger {
        background: rgba(239, 68, 68, 0.25);
        color: #fca5a5;
        border-color: rgba(239, 68, 68, 0.4);
    }
    .bulk-btn-danger:hover {
        background: rgba(239, 68, 68, 0.4);
        color: #ffffff;
    }
    .bulk-btn-close {
        background: transparent;
        border: none;
        color: #94a3b8;
        font-size: 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        padding: 4px;
    }
    .bulk-btn-close:hover {
        color: #ffffff;
    }

    /* ── 5. Modern Table ── */
    .table-container-responsive {
        overflow-x: auto;
        position: relative;
    }
    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .modern-table th {
        padding: 14px 18px;
        font-size: 11px;
        font-weight: 800;
        color: #475569;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        background: #f8fafc;
        border-bottom: 1.5px solid #e2e8f0;
        text-align: left;
        white-space: nowrap;
    }
    .modern-table-row {
        transition: background 0.15s ease;
    }
    .modern-table-row:hover {
        background: #f8fafc;
    }
    .modern-table-row.row-selected {
        background: #f0f7ff;
    }
    .modern-table td {
        padding: 14px 18px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        font-size: 13px;
    }
    .modern-table tr:last-child td {
        border-bottom: none;
    }

    /* Row Checkbox */
    .custom-row-checkbox {
        width: 18px;
        height: 18px;
        border-radius: 5px;
        border: 1.5px solid #cbd5e1;
        cursor: pointer;
        accent-color: #00285a;
    }

    /* Product Avatar Thumbnail */
    .product-img-wrap {
        position: relative;
        width: 48px;
        height: 48px;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        flex-shrink: 0;
    }
    .product-img-box {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.25s ease;
    }
    .product-img-wrap:hover .product-img-box {
        transform: scale(1.1);
    }

    /* Monospace SKU Badge */
    .sku-badge {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 11px;
        font-weight: 700;
        color: #00285a;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        padding: 2px 7px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
        transition: background 0.15s ease;
    }
    .sku-badge:hover {
        background: #e2e8f0;
    }

    /* Status Switch */
    .ios-switch {
        position: relative;
        display: inline-block;
        width: 38px;
        height: 22px;
    }
    .ios-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .ios-slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #cbd5e1;
        transition: .2s;
        border-radius: 22px;
    }
    .ios-slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .2s;
        border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    input:checked + .ios-slider {
        background-color: #10b981;
    }
    input:checked + .ios-slider:before {
        transform: translateX(16px);
    }

    /* Action Suite Buttons */
    .action-suite {
        display: flex;
        align-items: center;
        gap: 6px;
        justify-content: flex-end;
    }
    .action-icon-btn {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        border: 1px solid #edf2f7;
        background: #ffffff;
        color: #475569;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .action-icon-btn:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #cbd5e1;
        transform: translateY(-1px);
    }
    .action-icon-btn.btn-edit:hover {
        background: #eff6ff;
        color: #2563eb;
        border-color: #bfdbfe;
    }
    .action-icon-btn.btn-delete:hover {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }

    /* ── 6. Pagination Footer ── */
    .table-footer-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 24px;
        background: #ffffff;
        border-top: 1px solid #f1f5f9;
        font-size: 13px;
        color: #64748b;
        width: 100%;
        box-sizing: border-box;
    }
    .table-footer-left {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .table-footer-right {
        margin-left: auto;
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }
    .custom-pagination-wrap {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .page-nav-btn {
        min-width: 34px;
        height: 34px;
        padding: 0 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        font-size: 12.5px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .page-nav-btn:hover:not(.disabled):not(.active) {
        background: #f8fafc;
        color: #00285a;
        border-color: #00285a;
    }
    .page-nav-btn.active {
        background: #00285a;
        color: #ffffff;
        border-color: #00285a;
        box-shadow: 0 3px 10px rgba(0, 40, 90, 0.2);
    }
    .page-nav-btn.disabled {
        background: #f8fafc;
        color: #cbd5e1;
        border-color: #edf2f7;
        cursor: not-allowed;
    }

    /* ── 7. Floating Toast Alerts ── */
    .catalog-toast-container {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 1050;
        display: flex;
        flex-direction: column;
        gap: 10px;
        pointer-events: none;
    }
    .catalog-toast {
        background: #0f172a;
        color: #ffffff;
        padding: 12px 18px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        pointer-events: auto;
        transform: translateY(20px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .catalog-toast.show {
        transform: translateY(0);
        opacity: 1;
    }
    .catalog-toast.toast-success { background: #065f46; border-color: #059669; }
    .catalog-toast.toast-error { background: #991b1b; border-color: #dc2626; }
    .catalog-toast.toast-info { background: #1e3a8a; border-color: #2563eb; }

    @media (max-width: 768px) {
        .catalog-header-bar {
            flex-direction: column;
            align-items: flex-start;
        }
        .catalog-header-actions {
            width: 100%;
            justify-content: flex-start;
        }
        .table-filter-toolbar {
            flex-direction: column;
            align-items: stretch;
        }
        .filter-search-box {
            max-width: 100%;
        }
        .table-footer-bar {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
        .table-footer-right {
            width: 100%;
            justify-content: flex-start;
            overflow-x: auto;
        }
    }
</style>

<div class="products-master-wrap">

    {{-- ── 1. Top Header Action Bar ── --}}
    <div class="catalog-header-bar">
        <div class="catalog-header-title-group">
            <div class="catalog-header-title-row">
                <h1 class="catalog-header-title">Product Catalog</h1>
                <span class="catalog-live-pill">
                    <span class="pulse-dot-blue"></span> Live Master
                </span>
                <span style="font-size: 12px; font-weight: 700; color: #64748b; background: #f1f5f9; padding: 3px 9px; border-radius: 8px;">
                    {{ number_format($totalProducts) }} Total
                </span>
            </div>
            <p class="catalog-header-sub">Manage master catalog, real-time inventory, variants, and digital storefront visibility.</p>
        </div>

        <div class="catalog-header-actions">
            {{-- Export CSV --}}
            <a href="{{ route('admin.products.export') }}" class="btn-catalog-secondary" title="Export catalog to CSV spreadsheet">
                <i class="bi bi-download"></i> Export CSV
            </a>

            {{-- Refresh --}}
            <button type="button" class="btn-catalog-secondary" id="btnTopRefresh" title="Refresh products">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </button>

            {{-- + Add New Product Primary Button --}}
            <a href="{{ route('admin.products.create') }}" class="btn-catalog-primary">
                <i class="bi bi-plus-lg"></i> + Add New Product
            </a>
        </div>
    </div>

    {{-- ── 2. Top 4 Interactive KPI Bento Cards ── --}}
    <div class="kpi-modern-grid">
        {{-- Total Catalog --}}
        <div class="kpi-modern-card card-active" data-kpi-status="" title="Click to view all products">
            <div class="kpi-card-head">
                <span class="kpi-card-label">Total Catalog</span>
                <div class="kpi-icon-box kpi-icon-blue">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
            <div class="kpi-card-number">{{ number_format($totalProducts) }}</div>
            <div class="kpi-card-sub"><i class="bi bi-collection"></i> All catalog articles</div>
        </div>

        {{-- Active Products --}}
        <div class="kpi-modern-card" data-kpi-status="active" title="Click to filter by active products">
            <div class="kpi-card-head">
                <span class="kpi-card-label">Live Active</span>
                <div class="kpi-icon-box kpi-icon-emerald">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
            <div class="kpi-card-number" style="color: #059669;">{{ number_format($activeProducts) }}</div>
            <div class="kpi-card-sub"><i class="bi bi-eye"></i> Published on storefront</div>
        </div>

        {{-- Low Stock Alerts --}}
        <div class="kpi-modern-card" data-kpi-status="low_stock" title="Click to filter by low stock" style="{{ $lowStockProducts > 0 ? 'border-color:#fde68a; background:#fffdf5;' : '' }}">
            <div class="kpi-card-head">
                <span class="kpi-card-label" style="{{ $lowStockProducts > 0 ? 'color:#b45309;' : '' }}">Low Stock Alerts</span>
                <div class="kpi-icon-box kpi-icon-amber">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
            </div>
            <div class="kpi-card-number" style="color: #d97706;">{{ number_format($lowStockProducts) }}</div>
            <div class="kpi-card-sub"><i class="bi bi-speedometer2"></i> Stock level &le; 10 units</div>
        </div>

        {{-- Drafts / Inactive --}}
        <div class="kpi-modern-card" data-kpi-status="inactive" title="Click to filter by inactive drafts">
            <div class="kpi-card-head">
                <span class="kpi-card-label">Drafts / Inactive</span>
                <div class="kpi-icon-box kpi-icon-purple">
                    <i class="bi bi-eye-slash-fill"></i>
                </div>
            </div>
            <div class="kpi-card-number" style="color: #9333ea;">{{ number_format($draftProducts) }}</div>
            <div class="kpi-card-sub"><i class="bi bi-shield-lock"></i> Hidden from storefront</div>
        </div>
    </div>

    {{-- ── 3. Main Catalog Card ── --}}
    <div class="products-table-card">
        
        {{-- Segment Tabs --}}
        <div class="table-segment-bar">
            <button type="button" class="segment-pill-item active" data-status="">
                All <span class="segment-pill-badge">{{ $totalProducts }}</span>
            </button>
            <button type="button" class="segment-pill-item" data-status="active">
                Live Active <span class="segment-pill-badge">{{ $activeProducts }}</span>
            </button>
            <button type="button" class="segment-pill-item" data-status="inactive">
                Drafts <span class="segment-pill-badge">{{ $draftProducts }}</span>
            </button>
            <button type="button" class="segment-pill-item" data-status="low_stock" style="{{ $lowStockProducts > 0 ? 'color:#d97706;' : '' }}">
                <i class="bi bi-exclamation-triangle-fill"></i> Low Stock <span class="segment-pill-badge">{{ $lowStockProducts }}</span>
            </button>
            @if(isset($outOfStockProducts) && $outOfStockProducts > 0)
                <button type="button" class="segment-pill-item" data-status="out_of_stock" style="color:#dc2626;">
                    <i class="bi bi-x-circle-fill"></i> Out of Stock <span class="segment-pill-badge">{{ $outOfStockProducts }}</span>
                </button>
            @endif
            <button type="button" class="segment-pill-item" data-status="featured">
                <i class="bi bi-star-fill text-warning"></i> Featured <span class="segment-pill-badge">{{ $featuredProducts ?? 0 }}</span>
            </button>
        </div>

        {{-- Filter Toolbar --}}
        <div class="table-filter-toolbar">
            <div class="filter-search-box">
                <i class="bi bi-search filter-search-icon"></i>
                <input type="text" 
                       id="productSearchInput" 
                       placeholder="Search by title, SKU, or color..." 
                       class="filter-search-input">
                <button type="button" id="btnSearchClear" class="filter-search-clear" title="Clear search">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                {{-- Category Filter --}}
                @if ($categories->isNotEmpty())
                    <select id="productCategorySelect" class="filter-select">
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                @endif

                {{-- Stock Filter --}}
                <select id="productStockSelect" class="filter-select">
                    <option value="">All Stock Levels</option>
                    <option value="in_stock">In Stock (&gt; 10)</option>
                    <option value="low_stock">Low Stock (1–10)</option>
                    <option value="out_of_stock">Out of Stock (0)</option>
                </select>

                {{-- Sort Filter --}}
                <select id="productSortSelect" class="filter-select">
                    <option value="latest">Sort: Newest First</option>
                    <option value="stock_asc">Sort: Stock Low to High</option>
                    <option value="sold_desc">Sort: Most Sold</option>
                    <option value="price_desc">Sort: Price High to Low</option>
                    <option value="price_asc">Sort: Price Low to High</option>
                </select>

                <label class="datatable-control-label">
                    Show
                    <select id="productPerPageSelect" class="filter-select">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </label>

                <button type="button" class="btn-catalog-secondary" id="btnRefreshProducts" style="padding: 9px 13px;" title="Refresh catalog">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>

                <button type="button" class="btn-catalog-secondary" id="btnResetFilters" style="padding: 9px 13px;" title="Reset all filters">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </button>
            </div>
        </div>

        {{-- Modern DataTable --}}
        <div class="table-container-responsive">
            {{-- Loading Overlay --}}
            <div id="tableLoadingOverlay" style="position: absolute; inset: 0; background: rgba(255,255,255,0.75); backdrop-filter: blur(2px); z-index: 10; display: none; align-items: center; justify-content: center;">
                <div class="spinner-border text-primary" role="status" style="width: 2.2rem; height: 2.2rem; color: #00285a !important;">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <table class="modern-table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">
                            <input type="checkbox" id="selectAllCheckbox" class="custom-row-checkbox" title="Select all on this page">
                        </th>
                        <th style="min-width: 320px;">PRODUCT &amp; SKU</th>
                        <th>CATEGORY</th>
                        <th>RETAIL PRICE</th>
                        <th>INVENTORY</th>
                        <th>SALES</th>
                        <th>LIVE STATUS</th>
                        <th style="text-align: right; width: 130px;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody id="productsTableBody">
                    {{-- Populated via AJAX --}}
                </tbody>
            </table>
        </div>

        {{-- Pagination Footer --}}
        <div class="table-footer-bar">
            <div class="table-footer-left">
                <span id="productsShowingInfo">Loading product catalog...</span>
                <span id="productsPageInfo"></span>
            </div>
            <div id="productsPaginationContainer" class="table-footer-right">
                {{-- Populated via AJAX --}}
            </div>
        </div>
    </div>

    {{-- ── 4. Floating Batch Action Bar (Sticky at bottom when rows are checked) ── --}}
    <div id="bulkActionsBar" class="bulk-floating-bar">
        <span id="bulkSelectedCount" class="bulk-count-badge">0 selected</span>
        <button type="button" class="bulk-btn-action" onclick="applyBulkAction('activate')">
            <i class="bi bi-check-circle"></i> Activate
        </button>
        <button type="button" class="bulk-btn-action" onclick="applyBulkAction('deactivate')">
            <i class="bi bi-eye-slash"></i> Draft
        </button>
        <button type="button" class="bulk-btn-action" onclick="applyBulkAction('feature')">
            <i class="bi bi-star"></i> Feature
        </button>
        <button type="button" class="bulk-btn-action bulk-btn-danger" onclick="applyBulkAction('delete')">
            <i class="bi bi-trash3"></i> Delete
        </button>
        <button type="button" class="bulk-btn-close" onclick="clearBulkSelection()" title="Cancel selection">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    {{-- ── 5. Toast Container ── --}}
    <div id="catalogToastContainer" class="catalog-toast-container"></div>

</div>

<script>
    let searchDebounceTimer = null;
    let currentStatus = '';
    let currentPage = 1;
    let currentPerPage = 10;
    let productsRequestSeq = 0;
    let selectedProductIds = new Set();

    const productsAjaxUrl = {!! json_encode(route('admin.products.ajax')) !!};
    const productToggleUrlTemplate = {!! json_encode(route('admin.products.toggle', ['product' => '__ID__'])) !!};
    const productDestroyUrlTemplate = {!! json_encode(route('admin.products.destroy', ['product' => '__ID__'])) !!};
    const productBulkActionUrl = {!! json_encode(route('admin.products.bulk-action')) !!};

    document.addEventListener('DOMContentLoaded', function() {
        hydrateProductsFiltersFromUrl();
        fetchProducts(currentPage || 1);
        setupEventListeners();
    });

    // ── Toast Notification ──
    function showToast(message, type = 'success') {
        const container = document.getElementById('catalogToastContainer');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = `catalog-toast toast-${type}`;
        
        let icon = 'bi-check-circle-fill';
        if (type === 'error') icon = 'bi-exclamation-octagon-fill';
        if (type === 'info') icon = 'bi-info-circle-fill';

        toast.innerHTML = `<i class="bi ${icon}"></i> <span>${escapeHtml(message)}</span>`;
        container.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 20);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3500);
    }

    // ── AJAX DataTable Loader ──
    function fetchProducts(page = 1) {
        currentPage = page;
        currentPerPage = Number(document.getElementById('productPerPageSelect')?.value || currentPerPage || 10);
        const requestSeq = ++productsRequestSeq;
        const overlay = document.getElementById('tableLoadingOverlay');
        if (overlay) overlay.style.display = 'flex';

        const search = document.getElementById('productSearchInput')?.value.trim() || '';
        const category = document.getElementById('productCategorySelect')?.value || '';
        const stockFilter = document.getElementById('productStockSelect')?.value || '';
        const sort = document.getElementById('productSortSelect')?.value || 'latest';

        const params = new URLSearchParams();
        params.set('page', page);
        params.set('per_page', currentPerPage);
        
        // Stock filter overrides or maps to status
        if (stockFilter) {
            params.set('status', stockFilter);
        } else if (currentStatus) {
            params.set('status', currentStatus);
        }
        
        if (search) params.set('search', search);
        if (category) params.set('category', category);
        if (sort) params.set('sort', sort);

        updateProductsUrl(params);

        fetch(`${productsAjaxUrl}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('Catalog request failed');
            return res.json();
        })
        .then(data => {
            if (requestSeq !== productsRequestSeq) return;
            if (overlay) overlay.style.display = 'none';
            renderTableRows(data.data);
            renderPagination(data);
            updateSelectAllState();
        })
        .catch(err => {
            if (requestSeq !== productsRequestSeq) return;
            if (overlay) overlay.style.display = 'none';
            console.error('Failed to load catalog:', err);
            renderProductsError();
        });
    }

    function hydrateProductsFiltersFromUrl() {
        const params = new URLSearchParams(window.location.search);
        const searchInput = document.getElementById('productSearchInput');
        const categorySelect = document.getElementById('productCategorySelect');
        const stockSelect = document.getElementById('productStockSelect');
        const sortSelect = document.getElementById('productSortSelect');
        const perPageSelect = document.getElementById('productPerPageSelect');
        const searchClear = document.getElementById('btnSearchClear');

        if (searchInput && params.has('search')) {
            searchInput.value = params.get('search') || '';
            if (searchClear) searchClear.style.display = searchInput.value ? 'block' : 'none';
        }
        if (categorySelect && params.has('category')) categorySelect.value = params.get('category') || '';
        if (sortSelect && params.has('sort')) sortSelect.value = params.get('sort') || 'latest';
        if (perPageSelect && params.has('per_page')) perPageSelect.value = params.get('per_page') || '10';
        
        currentStatus = params.get('status') || '';
        if (['in_stock', 'low_stock', 'out_of_stock'].includes(currentStatus) && stockSelect) {
            stockSelect.value = currentStatus;
        }

        currentPage = Number(params.get('page') || 1);
        updateActiveTabUI();
    }

    function updateActiveTabUI() {
        document.querySelectorAll('.segment-pill-item').forEach(p => {
            const tabStatus = p.getAttribute('data-status') || '';
            p.classList.toggle('active', tabStatus === currentStatus);
        });
        document.querySelectorAll('.kpi-modern-card').forEach(kpi => {
            const kpiStatus = kpi.getAttribute('data-kpi-status') || '';
            kpi.classList.toggle('card-active', kpiStatus === currentStatus);
        });
    }

    function updateProductsUrl(params) {
        const cleanParams = new URLSearchParams(params);
        if (cleanParams.get('page') === '1') cleanParams.delete('page');
        if (cleanParams.get('per_page') === '10') cleanParams.delete('per_page');
        if (cleanParams.get('sort') === 'latest') cleanParams.delete('sort');
        const nextUrl = `${window.location.pathname}${cleanParams.toString() ? '?' + cleanParams.toString() : ''}`;
        window.history.replaceState({}, '', nextUrl);
    }

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, function(match) {
            return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' })[match];
        });
    }

    function copyToClipboard(text, e) {
        if (e) e.stopPropagation();
        navigator.clipboard.writeText(text).then(() => {
            showToast(`Copied SKU: ${text}`, 'info');
        }).catch(() => {
            showToast('Failed to copy', 'error');
        });
    }

    // ── Render Table Rows ──
    function renderProductsError() {
        const tbody = document.getElementById('productsTableBody');
        const info = document.getElementById('productsShowingInfo');
        const pageInfo = document.getElementById('productsPageInfo');
        const pagination = document.getElementById('productsPaginationContainer');
        if (tbody) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" style="text-align: center; padding: 46px 20px; color: #64748b;">
                        <i class="bi bi-wifi-off" style="font-size: 36px; color: #cbd5e1; display:block; margin-bottom: 10px;"></i>
                        <strong style="color: #0f172a; font-size: 15px; display:block;">Could not load products</strong>
                        <p style="font-size: 12.5px; margin: 6px 0 16px;">Please check your server connection and retry.</p>
                        <button type="button" class="btn-catalog-primary" onclick="fetchProducts(currentPage)" style="padding: 8px 18px; font-size: 12.5px;">Retry</button>
                    </td>
                </tr>
            `;
        }
        if (info) info.textContent = 'Failed to load products';
        if (pageInfo) pageInfo.textContent = '';
        if (pagination) pagination.innerHTML = '';
    }

    function renderTableRows(products) {
        const tbody = document.getElementById('productsTableBody');
        if (!products || products.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" style="text-align: center; padding: 60px 20px; color: #64748b;">
                        <i class="bi bi-box-seam" style="font-size: 40px; color: #cbd5e1; display: block; margin-bottom: 12px;"></i>
                        <strong style="color: #0f172a; font-size: 16px; display: block;">No products found</strong>
                        <p style="font-size: 13px; margin: 6px 0 18px; color: #64748b;">Try adjusting your search criteria or create a new article.</p>
                        <a href="{{ route('admin.products.create') }}" class="btn-catalog-primary" style="padding: 8px 20px;">
                            <i class="bi bi-plus-lg"></i> + Add New Product
                        </a>
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        products.forEach(p => {
            const isOut = p.stock <= 0;
            const isLow = p.stock > 0 && p.stock <= 10;
            const stockPercent = Math.min(100, Math.max(0, Math.round((p.stock / 50) * 100)));
            const hasDiscount = p.original_price && p.original_price > p.price;
            const discountPct = hasDiscount ? Math.round(((p.original_price - p.price) / p.original_price) * 100) : 0;
            const productName = escapeHtml(p.name);
            const sku = escapeHtml(p.sku);
            const category = escapeHtml(p.category || 'Uncategorized');
            const image = escapeHtml(p.image || 'https://placehold.co/100x100/f1f5f9/94a3b8?text=Product');
            const editUrl = escapeHtml(p.edit_url);
            const viewUrl = escapeHtml(p.view_url);
            const isChecked = selectedProductIds.has(p.id);

            html += `
                <tr class="modern-table-row ${isChecked ? 'row-selected' : ''}" id="productRow${p.id}">
                    <td style="text-align: center;">
                        <input type="checkbox" 
                               class="custom-row-checkbox product-row-checkbox" 
                               value="${p.id}" 
                               ${isChecked ? 'checked' : ''} 
                               onchange="handleRowCheckboxChange(this, ${p.id})">
                    </td>

                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="product-img-wrap">
                                <img src="${image}"
                                     class="product-img-box"
                                     alt="${productName}"
                                     loading="lazy"
                                     onerror="this.onerror=null;this.src='https://placehold.co/100x100/f1f5f9/94a3b8?text=Product';">
                            </div>
                            <div style="min-width: 0;">
                                <a href="${editUrl}" style="font-size: 13.5px; font-weight: 700; color: #0f172a; text-decoration: none;" class="d-block mb-1 text-truncate" title="${productName}">
                                    ${productName}
                                </a>
                                <div class="d-flex align-items-center gap-2 flex-wrap" style="font-size: 11px;">
                                    <span class="sku-badge" onclick="copyToClipboard('${sku}', event)" title="Click to copy SKU">
                                        <i class="bi bi-copy" style="font-size: 10px; opacity: 0.6;"></i> ${sku}
                                    </span>
                                    ${p.has_variants ? `<span style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; padding:2px 7px; border-radius:999px; font-weight:700; font-size:10.5px;">${p.variants_count} variants</span>` : ''}
                                    ${p.is_featured ? `<span style="color:#eab308; font-size:12px;" title="Featured on homepage"><i class="bi bi-star-fill"></i></span>` : ''}
                                    ${hasDiscount ? `<span style="background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; padding:1px 5px; border-radius:4px; font-weight:800; font-size:10px;">${discountPct}% OFF</span>` : ''}
                                </div>
                            </div>
                        </div>
                    </td>

                    <td>
                        <span style="font-size: 11.5px; font-weight: 700; color: #1e3a8a; background: #eff6ff; border: 1px solid #dbeafe; padding: 4px 10px; border-radius: 7px; display: inline-block;">
                            ${category}
                        </span>
                    </td>

                    <td>
                        <div>
                            <strong style="font-size: 14px; font-weight: 800; color: #00285a;">₹${Number(p.price).toLocaleString('en-IN')}</strong>
                            ${hasDiscount ? `
                                <div style="font-size: 11px; margin-top: 2px; color: #94a3b8;">
                                    <del>₹${Number(p.original_price).toLocaleString('en-IN')}</del>
                                </div>
                            ` : ''}
                        </div>
                    </td>

                    <td>
                        <div style="min-width: 120px;">
                            <div class="d-flex justify-content-between mb-1" style="font-size: 11.5px;">
                                <span style="font-weight: 800; color: ${isOut ? '#dc2626' : (isLow ? '#d97706' : '#059669')};">
                                    ${isOut ? 'Out of Stock' : (isLow ? `Low (${p.stock})` : `In Stock (${p.stock})`)}
                                </span>
                            </div>
                            <div style="height: 5px; background: #f1f5f9; border-radius: 999px; overflow: hidden;">
                                <div style="height: 100%; width: ${isOut ? 100 : stockPercent}%; background: ${isOut ? '#dc2626' : (isLow ? '#f59e0b' : '#10b981')};"></div>
                            </div>
                        </div>
                    </td>

                    <td>
                        <strong style="font-size: 13.5px; font-weight: 800; color: #0f172a;">${Number(p.total_sold).toLocaleString('en-IN')}</strong>
                        <span style="font-size: 11px; color: #64748b; margin-left: 2px;">sold</span>
                    </td>

                    <td>
                        <label class="ios-switch" title="Toggle active storefront visibility">
                            <input type="checkbox" ${p.is_active ? 'checked' : ''} onchange="toggleProductStatus(${p.id}, this)">
                            <span class="ios-slider"></span>
                        </label>
                    </td>

                    <td style="text-align: right;">
                        <div class="action-suite">
                            <a href="${viewUrl}" target="_blank" class="action-icon-btn" title="View on customer storefront">
                                <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                            <a href="${editUrl}" class="action-icon-btn btn-edit" title="Edit product details">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <button type="button" onclick="deleteProduct(${p.id}, '${productName}')" class="action-icon-btn btn-delete" title="Delete product">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
    }

    // ── Render Pagination ──
    function renderPagination(res) {
        const infoEl = document.getElementById('productsShowingInfo');
        const pageInfoEl = document.getElementById('productsPageInfo');
        if (infoEl) {
            infoEl.innerHTML = res.total > 0
                ? `Showing <strong>${res.from || 1}</strong> to <strong>${res.to || res.total}</strong> of <strong>${res.total}</strong> products`
                : 'Showing <strong>0</strong> products';
        }
        if (pageInfoEl) {
            pageInfoEl.innerHTML = res.last_page > 1
                ? `<span style="color:#94a3b8; font-size:12px;">(Page ${res.current_page} of ${res.last_page})</span>`
                : '';
        }

        const container = document.getElementById('productsPaginationContainer');
        if (res.last_page <= 1) {
            container.innerHTML = '';
            return;
        }

        let html = '<div class="custom-pagination-wrap">';

        // Previous
        if (res.current_page > 1) {
            html += `<button type="button" class="page-nav-btn" onclick="fetchProducts(${res.current_page - 1})" title="Previous page"><i class="bi bi-chevron-left"></i></button>`;
        } else {
            html += `<span class="page-nav-btn disabled"><i class="bi bi-chevron-left"></i></span>`;
        }

        // Numeric pages
        const pages = buildPaginationPages(res.current_page, res.last_page);
        pages.forEach(i => {
            if (i === '...') {
                html += `<span class="page-nav-btn disabled" style="border:none; background:transparent;">...</span>`;
                return;
            }
            if (i === res.current_page) {
                html += `<span class="page-nav-btn active">${i}</span>`;
            } else {
                html += `<button type="button" class="page-nav-btn" onclick="fetchProducts(${i})">${i}</button>`;
            }
        });

        // Next
        if (res.current_page < res.last_page) {
            html += `<button type="button" class="page-nav-btn" onclick="fetchProducts(${res.current_page + 1})" title="Next page"><i class="bi bi-chevron-right"></i></button>`;
        } else {
            html += `<span class="page-nav-btn disabled"><i class="bi bi-chevron-right"></i></span>`;
        }

        html += '</div>';
        container.innerHTML = html;
    }

    function buildPaginationPages(current, last) {
        const pages = [];
        for (let i = 1; i <= last; i++) {
            if (i === 1 || i === last || Math.abs(i - current) <= 1 || i <= 2 || i >= last - 1) {
                pages.push(i);
            } else if (pages[pages.length - 1] !== '...') {
                pages.push('...');
            }
        }
        return pages;
    }

    // ── Bulk Selection Handlers ──
    function handleRowCheckboxChange(cb, id) {
        if (cb.checked) {
            selectedProductIds.add(id);
            document.getElementById('productRow' + id)?.classList.add('row-selected');
        } else {
            selectedProductIds.delete(id);
            document.getElementById('productRow' + id)?.classList.remove('row-selected');
        }
        updateBulkBar();
        updateSelectAllState();
    }

    function updateSelectAllState() {
        const selectAll = document.getElementById('selectAllCheckbox');
        const rowCheckboxes = document.querySelectorAll('.product-row-checkbox');
        if (!selectAll || rowCheckboxes.length === 0) return;

        const allChecked = Array.from(rowCheckboxes).every(cb => cb.checked);
        const someChecked = Array.from(rowCheckboxes).some(cb => cb.checked);
        selectAll.checked = allChecked;
        selectAll.indeterminate = someChecked && !allChecked;
    }

    function updateBulkBar() {
        const bar = document.getElementById('bulkActionsBar');
        const countBadge = document.getElementById('bulkSelectedCount');
        const count = selectedProductIds.size;

        if (countBadge) countBadge.textContent = `${count} selected`;
        if (bar) {
            if (count > 0) {
                bar.classList.add('active');
            } else {
                bar.classList.remove('active');
            }
        }
    }

    function clearBulkSelection() {
        selectedProductIds.clear();
        document.querySelectorAll('.product-row-checkbox').forEach(cb => {
            cb.checked = false;
        });
        document.querySelectorAll('.modern-table-row').forEach(tr => {
            tr.classList.remove('row-selected');
        });
        const selectAll = document.getElementById('selectAllCheckbox');
        if (selectAll) {
            selectAll.checked = false;
            selectAll.indeterminate = false;
        }
        updateBulkBar();
    }

    // Select All checkbox on current page
    document.getElementById('selectAllCheckbox')?.addEventListener('change', function() {
        const isChecked = this.checked;
        document.querySelectorAll('.product-row-checkbox').forEach(cb => {
            cb.checked = isChecked;
            const id = Number(cb.value);
            if (isChecked) {
                selectedProductIds.add(id);
                document.getElementById('productRow' + id)?.classList.add('row-selected');
            } else {
                selectedProductIds.delete(id);
                document.getElementById('productRow' + id)?.classList.remove('row-selected');
            }
        });
        updateBulkBar();
    });

    // Apply Bulk Action
    function applyBulkAction(action) {
        const ids = Array.from(selectedProductIds);
        if (ids.length === 0) {
            showToast('Please select at least one product', 'error');
            return;
        }

        if (action === 'delete') {
            if (!confirm(`Are you sure you want to permanently delete ${ids.length} selected product(s)?`)) return;
        }

        fetch(productBulkActionUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ action: action, ids: ids })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message || 'Action executed successfully', 'success');
                clearBulkSelection();
                fetchProducts(currentPage);
            } else {
                showToast(data.message || 'Bulk action failed', 'error');
            }
        })
        .catch(err => {
            showToast('Server error executing bulk action', 'error');
        });
    }

    // ── Event Listeners Setup ──
    function setupEventListeners() {
        const searchInput = document.getElementById('productSearchInput');
        const searchClear = document.getElementById('btnSearchClear');

        // Search debouncing
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                if (searchClear) searchClear.style.display = this.value ? 'block' : 'none';
                clearTimeout(searchDebounceTimer);
                searchDebounceTimer = setTimeout(() => fetchProducts(1), 350);
            });
        }

        if (searchClear) {
            searchClear.addEventListener('click', function() {
                if (searchInput) {
                    searchInput.value = '';
                    this.style.display = 'none';
                    searchInput.focus();
                }
                fetchProducts(1);
            });
        }

        // Filters
        document.getElementById('productCategorySelect')?.addEventListener('change', () => fetchProducts(1));
        document.getElementById('productStockSelect')?.addEventListener('change', function() {
            currentStatus = this.value;
            updateActiveTabUI();
            fetchProducts(1);
        });
        document.getElementById('productSortSelect')?.addEventListener('change', () => fetchProducts(1));
        document.getElementById('productPerPageSelect')?.addEventListener('change', () => fetchProducts(1));
        document.getElementById('btnRefreshProducts')?.addEventListener('click', () => fetchProducts(currentPage));
        document.getElementById('btnTopRefresh')?.addEventListener('click', () => fetchProducts(currentPage));

        // Reset
        document.getElementById('btnResetFilters')?.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (searchClear) searchClear.style.display = 'none';
            const catSel = document.getElementById('productCategorySelect');
            if (catSel) catSel.value = '';
            const stockSel = document.getElementById('productStockSelect');
            if (stockSel) stockSel.value = '';
            document.getElementById('productSortSelect').value = 'latest';
            document.getElementById('productPerPageSelect').value = '10';
            currentPerPage = 10;
            currentStatus = '';
            clearBulkSelection();
            updateActiveTabUI();
            fetchProducts(1);
            showToast('Filters reset to default', 'info');
        });

        // Segment Tabs click
        document.querySelectorAll('.segment-pill-item').forEach(pill => {
            pill.addEventListener('click', function() {
                currentStatus = this.getAttribute('data-status') || '';
                const stockSel = document.getElementById('productStockSelect');
                if (stockSel) {
                    if (['in_stock', 'low_stock', 'out_of_stock'].includes(currentStatus)) {
                        stockSel.value = currentStatus;
                    } else {
                        stockSel.value = '';
                    }
                }
                updateActiveTabUI();
                fetchProducts(1);
            });
        });

        // KPI Bento Cards click
        document.querySelectorAll('.kpi-modern-card').forEach(kpi => {
            kpi.addEventListener('click', function() {
                currentStatus = this.getAttribute('data-kpi-status') || '';
                const stockSel = document.getElementById('productStockSelect');
                if (stockSel) {
                    if (['in_stock', 'low_stock', 'out_of_stock'].includes(currentStatus)) {
                        stockSel.value = currentStatus;
                    } else {
                        stockSel.value = '';
                    }
                }
                updateActiveTabUI();
                fetchProducts(1);
            });
        });
    }

    // ── Toggle Active Status ──
    function toggleProductStatus(productId, checkbox) {
        fetch(productToggleUrlTemplate.replace('__ID__', productId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(`Product #${productId} status updated`, 'success');
            } else {
                checkbox.checked = !checkbox.checked;
                showToast(data.message || 'Failed to update status', 'error');
            }
        })
        .catch(err => {
            checkbox.checked = !checkbox.checked;
            showToast('Server error updating status', 'error');
        });
    }

    // ── Delete Product ──
    function deleteProduct(productId, productName) {
        if (!confirm(`Delete product "${productName}"?\nThis action cannot be undone.`)) return;

        fetch(productDestroyUrlTemplate.replace('__ID__', productId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast('Product deleted successfully', 'success');
                selectedProductIds.delete(productId);
                updateBulkBar();
                const row = document.getElementById('productRow' + productId);
                if (row) {
                    row.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
                    row.style.opacity = '0';
                    row.style.transform = 'scale(0.96)';
                    setTimeout(() => fetchProducts(currentPage), 260);
                }
            } else {
                showToast(data.message || 'Failed to delete product', 'error');
            }
        })
        .catch(err => showToast('Failed to delete product', 'error'));
    }
</script>
@endsection
