{{-- resources/views/admin/products/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Product Catalog & Inventory')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    :root {
        --cl-navy: #00285a;
        --cl-navy-hover: #0a3d82;
        --cl-bg: #f8fafc;
        --cl-card: #ffffff;
        --cl-border: #e2e8f0;
        --cl-border-light: #f1f5f9;
        --cl-text-dark: #0f172a;
        --cl-text-muted: #64748b;
        --cl-text-light: #94a3b8;
    }

    .clean-catalog-wrap {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        display: flex;
        flex-direction: column;
        gap: 20px;
        color: var(--cl-text-dark);
        max-width: 1440px;
        margin: 0 auto;
        padding-bottom: 70px;
        position: relative;
    }

    /* ── Header Bar ── */
    .catalog-top-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        padding: 4px 0;
    }
    .catalog-top-title-group h1 {
        font-size: 22px;
        font-weight: 800;
        color: var(--cl-text-dark);
        letter-spacing: -0.4px;
        margin: 0 0 4px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .catalog-count-pill {
        font-size: 12px;
        font-weight: 700;
        background: #f1f5f9;
        color: #475569;
        padding: 2px 10px;
        border-radius: 999px;
        border: 1px solid #e2e8f0;
    }
    .catalog-top-title-group p {
        font-size: 13px;
        color: var(--cl-text-muted);
        margin: 0;
    }
    .catalog-top-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .btn-clean-secondary {
        background: #ffffff;
        color: #334155;
        border: 1.5px solid #cbd5e1;
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: all 0.15s ease;
        cursor: pointer;
    }
    .btn-clean-secondary:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a;
    }
    .btn-clean-primary {
        background: var(--cl-navy);
        color: #ffffff;
        border: none;
        padding: 9px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: all 0.15s ease;
        box-shadow: 0 2px 8px rgba(0, 40, 90, 0.15);
        cursor: pointer;
    }
    .btn-clean-primary:hover {
        background: var(--cl-navy-hover);
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(0, 40, 90, 0.25);
    }

    /* ── Metric Cards ── */
    .metric-grid-4 {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }
    @media (max-width: 1024px) {
        .metric-grid-4 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 600px) {
        .metric-grid-4 { grid-template-columns: 1fr; }
    }
    .metric-clean-card {
        background: #ffffff;
        border: 1px solid var(--cl-border);
        border-radius: 14px;
        padding: 16px 18px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: all 0.15s ease;
        user-select: none;
    }
    .metric-clean-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
        transform: translateY(-1px);
    }
    .metric-clean-card.active-filter {
        border-color: var(--cl-navy);
        box-shadow: 0 0 0 2px rgba(0, 40, 90, 0.12);
        background: #fcfdfe;
    }
    .metric-left {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .metric-title {
        font-size: 11.5px;
        font-weight: 700;
        color: var(--cl-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    .metric-val {
        font-size: 22px;
        font-weight: 800;
        color: var(--cl-text-dark);
        line-height: 1.2;
    }
    .metric-sub {
        font-size: 11.5px;
        color: var(--cl-text-light);
    }
    .metric-icon-box {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .icon-blue { background: #eff6ff; color: #2563eb; }
    .icon-emerald { background: #ecfdf5; color: #059669; }
    .icon-amber { background: #fffbeb; color: #d97706; }
    .icon-rose { background: #fff1f2; color: #e11d48; }

    /* ── Main Catalog Box ── */
    .catalog-main-box {
        background: #ffffff;
        border: 1px solid var(--cl-border);
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
        overflow: hidden;
    }

    /* ── Segment Tabs Bar ── */
    .clean-tabs-bar {
        padding: 12px 20px;
        border-bottom: 1px solid var(--cl-border-light);
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
        background: #ffffff;
    }
    .tab-clean-item {
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 700;
        color: var(--cl-text-muted);
        border: none;
        background: transparent;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
    }
    .tab-clean-item:hover {
        color: var(--cl-text-dark);
        background: #f8fafc;
    }
    .tab-clean-item.active {
        background: var(--cl-navy);
        color: #ffffff;
    }
    .tab-badge {
        font-size: 11px;
        font-weight: 800;
        padding: 1px 6px;
        border-radius: 999px;
        background: #f1f5f9;
        color: #475569;
    }
    .tab-clean-item.active .tab-badge {
        background: rgba(255, 255, 255, 0.22);
        color: #ffffff;
    }

    /* ── Filter Toolbar ── */
    .clean-filter-toolbar {
        padding: 12px 20px;
        background: #fafcff;
        border-bottom: 1px solid var(--cl-border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .search-input-wrap {
        position: relative;
        flex: 1;
        min-width: 240px;
        max-width: 360px;
    }
    .search-input-wrap input {
        width: 100%;
        height: 38px;
        padding: 0 34px 0 36px;
        border: 1.5px solid var(--cl-border);
        border-radius: 9px;
        font-size: 13px;
        color: var(--cl-text-dark);
        background: #ffffff;
        outline: none;
        transition: all 0.15s ease;
    }
    .search-input-wrap input:focus {
        border-color: var(--cl-navy);
        box-shadow: 0 0 0 3px rgba(0, 40, 90, 0.08);
    }
    .search-icon-left {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--cl-text-light);
        font-size: 14px;
        pointer-events: none;
    }
    .search-clear-btn {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--cl-text-light);
        font-size: 12px;
        cursor: pointer;
        padding: 2px 4px;
        display: none;
    }
    .search-clear-btn:hover {
        color: var(--cl-text-dark);
    }

    .filters-right-group {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .clean-select {
        height: 38px;
        padding: 0 12px;
        border: 1.5px solid var(--cl-border);
        border-radius: 9px;
        font-size: 12.5px;
        font-weight: 600;
        color: #334155;
        background: #ffffff;
        outline: none;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .clean-select:focus {
        border-color: var(--cl-navy);
    }
    .btn-icon-square {
        width: 38px;
        height: 38px;
        border: 1.5px solid var(--cl-border);
        border-radius: 9px;
        background: #ffffff;
        color: #475569;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .btn-icon-square:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a;
    }

    /* ── Table Styling ── */
    .clean-table-responsive {
        width: 100%;
        overflow-x: auto;
        position: relative;
    }
    .clean-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 13px;
    }
    .clean-table th {
        background: #f8fafc;
        padding: 12px 16px;
        font-size: 11px;
        font-weight: 800;
        color: #64748b;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        border-bottom: 1.5px solid var(--cl-border);
        white-space: nowrap;
    }
    .clean-table td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--cl-border-light);
        vertical-align: middle;
        background: #ffffff;
        transition: background 0.15s ease;
    }
    .clean-table tr:hover td {
        background: #fafcff;
    }
    .clean-table tr.row-selected td {
        background: #f0f7ff;
    }

    /* Row Checkbox */
    .row-cb {
        width: 16px;
        height: 16px;
        border-radius: 4px;
        accent-color: var(--cl-navy);
        cursor: pointer;
    }

    /* Product Item in Table */
    .product-cell-flex {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 260px;
    }
    .product-thumb-img {
        width: 44px;
        height: 44px;
        border-radius: 9px;
        object-fit: cover;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        flex-shrink: 0;
    }
    .product-info-col {
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .product-title-link {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--cl-text-dark);
        text-decoration: none;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 320px;
        transition: color 0.15s;
    }
    .product-title-link:hover {
        color: var(--cl-navy);
    }
    .product-meta-row {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 11px;
        color: var(--cl-text-light);
    }
    .sku-clean-pill {
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        color: #64748b;
        font-family: monospace;
        font-size: 11px;
    }
    .sku-clean-pill:hover {
        color: var(--cl-navy);
    }
    .variant-clean-tag {
        background: #eff6ff;
        color: #1d4ed8;
        padding: 1px 6px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 700;
    }

    /* Status Dot */
    .stock-indicator {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12.5px;
        font-weight: 700;
        white-space: nowrap;
    }
    .stock-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
    }
    .stock-in { color: #059669; }
    .stock-in .stock-dot { background: #10b981; box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2); }
    .stock-low { color: #d97706; }
    .stock-low .stock-dot { background: #f59e0b; box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.2); }
    .stock-out { color: #e11d48; }
    .stock-out .stock-dot { background: #ef4444; box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2); }

    /* Category Pill */
    .cat-clean-pill {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 600;
        background: #f1f5f9;
        color: #475569;
        white-space: nowrap;
    }

    /* Price cell */
    .price-main {
        font-size: 13.5px;
        font-weight: 800;
        color: var(--cl-text-dark);
    }
    .price-original {
        font-size: 11px;
        color: var(--cl-text-light);
        text-decoration: line-through;
        margin-left: 4px;
    }
    .discount-pill {
        background: #ecfdf5;
        color: #059669;
        font-size: 10px;
        font-weight: 800;
        padding: 1px 5px;
        border-radius: 4px;
        margin-left: 4px;
    }

    /* iOS Switch for Live Status */
    .switch-ios {
        position: relative;
        display: inline-block;
        width: 36px;
        height: 20px;
        margin: 0;
        cursor: pointer;
    }
    .switch-ios input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider-ios {
        position: absolute;
        cursor: pointer;
        inset: 0;
        background-color: #cbd5e1;
        transition: .2s ease;
        border-radius: 20px;
    }
    .slider-ios:before {
        position: absolute;
        content: "";
        height: 14px;
        width: 14px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .2s ease;
        border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    .switch-ios input:checked + .slider-ios {
        background-color: var(--cl-navy);
    }
    .switch-ios input:checked + .slider-ios:before {
        transform: translateX(16px);
    }

    /* Action Suite */
    .actions-suite-flex {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 6px;
    }
    .btn-action-icon {
        width: 30px;
        height: 30px;
        border-radius: 7px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .btn-action-icon:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: var(--cl-text-dark);
    }
    .btn-action-delete:hover {
        background: #fff1f2;
        border-color: #fecdd3;
        color: #e11d48;
    }

    /* ── Table Footer & Pagination ── */
    .clean-table-footer {
        padding: 12px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        border-top: 1px solid var(--cl-border-light);
        background: #ffffff;
        font-size: 12.5px;
        color: var(--cl-text-muted);
    }
    .clean-pagination-group {
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .btn-page-step {
        height: 32px;
        padding: 0 10px;
        border-radius: 6px;
        border: 1px solid var(--cl-border);
        background: #ffffff;
        color: #334155;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
    }
    .btn-page-step:hover:not(:disabled) {
        background: #f8fafc;
        border-color: #94a3b8;
    }
    .btn-page-step.active {
        background: var(--cl-navy);
        border-color: var(--cl-navy);
        color: #ffffff;
    }
    .btn-page-step:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    /* ── Floating Bulk Action Bar ── */
    .clean-bulk-bar {
        position: fixed;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%) translateY(100px);
        background: #0f172a;
        color: #ffffff;
        padding: 10px 18px;
        border-radius: 30px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.25);
        z-index: 999;
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        font-size: 13px;
    }
    .clean-bulk-bar.visible {
        transform: translateX(-50%) translateY(0);
    }
    .bulk-btn-clean {
        background: rgba(255,255,255,0.12);
        color: #ffffff;
        border: none;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: background 0.15s;
    }
    .bulk-btn-clean:hover {
        background: rgba(255,255,255,0.22);
    }
    .bulk-btn-clean.danger:hover {
        background: #e11d48;
    }

    /* ── Toast Container ── */
    .clean-toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 99999;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .clean-toast {
        background: #ffffff;
        color: var(--cl-text-dark);
        border: 1px solid var(--cl-border);
        border-radius: 10px;
        padding: 10px 16px;
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.08);
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        animation: slideInToast .25s ease;
    }
    .toast-success { border-left: 4px solid #10b981; }
    .toast-error { border-left: 4px solid #ef4444; }
    .toast-info { border-left: 4px solid #3b82f6; }

    @keyframes slideInToast {
        from { transform: translateX(40px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
</style>

<div class="clean-catalog-wrap">

    {{-- ── 1. Top Header ── --}}
    <div class="catalog-top-header">
        <div class="catalog-top-title-group">
            <h1>
                Products
                <span class="catalog-count-pill" id="totalCountBadge">{{ number_format($totalProducts) }}</span>
            </h1>
            <p>Manage product catalog, real-time stock, variants &amp; storefront visibility.</p>
        </div>

        <div class="catalog-top-actions">
            <a href="{{ route('admin.products.export') }}" class="btn-clean-secondary" title="Export to CSV">
                <i class="bi bi-download"></i> Export CSV
            </a>
            <button type="button" class="btn-clean-secondary" id="btnTopRefresh" title="Refresh">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </button>
            <a href="{{ route('admin.products.create') }}" class="btn-clean-primary">
                <i class="bi bi-plus-lg"></i> + Add Product
            </a>
        </div>
    </div>

    {{-- ── 2. Metric Overview Cards ── --}}
    <div class="metric-grid-4">
        {{-- Total Products --}}
        <div class="metric-clean-card active-filter" data-kpi-status="" title="Show all products">
            <div class="metric-left">
                <span class="metric-title">Total Products</span>
                <span class="metric-val">{{ number_format($totalProducts) }}</span>
                <span class="metric-sub">Catalog articles</span>
            </div>
            <div class="metric-icon-box icon-blue">
                <i class="bi bi-box-seam"></i>
            </div>
        </div>

        {{-- Active / Live --}}
        <div class="metric-clean-card" data-kpi-status="active" title="Filter live active products">
            <div class="metric-left">
                <span class="metric-title">Published Live</span>
                <span class="metric-val" style="color:#059669;">{{ number_format($activeProducts) }}</span>
                <span class="metric-sub">Visible on store</span>
            </div>
            <div class="metric-icon-box icon-emerald">
                <i class="bi bi-check-circle"></i>
            </div>
        </div>

        {{-- Low Stock --}}
        <div class="metric-clean-card" data-kpi-status="low_stock" title="Filter low stock items (&le; 10 units)">
            <div class="metric-left">
                <span class="metric-title">Low Stock</span>
                <span class="metric-val" style="color:#d97706;">{{ number_format($lowStockProducts) }}</span>
                <span class="metric-sub">&le; 10 units remaining</span>
            </div>
            <div class="metric-icon-box icon-amber">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
        </div>

        {{-- Out of Stock --}}
        <div class="metric-clean-card" data-kpi-status="out_of_stock" title="Filter out of stock items">
            <div class="metric-left">
                <span class="metric-title">Out of Stock</span>
                <span class="metric-val" style="color:#e11d48;">{{ number_format($outOfStockProducts) }}</span>
                <span class="metric-sub">0 units available</span>
            </div>
            <div class="metric-icon-box icon-rose">
                <i class="bi bi-x-circle"></i>
            </div>
        </div>
    </div>

    {{-- ── 3. Main Catalog Card ── --}}
    <div class="catalog-main-box">

        {{-- Segment Tabs --}}
        <div class="clean-tabs-bar">
            <button type="button" class="tab-clean-item active" data-status="">
                All <span class="tab-badge">{{ $totalProducts }}</span>
            </button>
            <button type="button" class="tab-clean-item" data-status="active">
                Active <span class="tab-badge">{{ $activeProducts }}</span>
            </button>
            <button type="button" class="tab-clean-item" data-status="inactive">
                Drafts <span class="tab-badge">{{ $draftProducts }}</span>
            </button>
            <button type="button" class="tab-clean-item" data-status="low_stock">
                Low Stock <span class="tab-badge">{{ $lowStockProducts }}</span>
            </button>
            <button type="button" class="tab-clean-item" data-status="out_of_stock">
                Out of Stock <span class="tab-badge">{{ $outOfStockProducts }}</span>
            </button>
            <button type="button" class="tab-clean-item" data-status="featured">
                Featured <span class="tab-badge">{{ $featuredProducts ?? 0 }}</span>
            </button>
        </div>

        {{-- Filter Toolbar --}}
        <div class="clean-filter-toolbar">
            <div class="search-input-wrap">
                <i class="bi bi-search search-icon-left"></i>
                <input type="text" id="cleanSearchInput" placeholder="Search by name, SKU, or color...">
                <button type="button" id="btnCleanSearchClear" class="search-clear-btn" title="Clear">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="filters-right-group">
                {{-- Category Filter --}}
                @if ($categories->isNotEmpty())
                    <select id="cleanCategorySelect" class="clean-select">
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                @endif

                {{-- Stock Filter --}}
                <select id="cleanStockSelect" class="clean-select">
                    <option value="">All Stock</option>
                    <option value="in_stock">In Stock (&gt;10)</option>
                    <option value="low_stock">Low Stock (1–10)</option>
                    <option value="out_of_stock">Out of Stock (0)</option>
                </select>

                {{-- Sort Filter --}}
                <select id="cleanSortSelect" class="clean-select">
                    <option value="latest">Newest First</option>
                    <option value="stock_asc">Stock: Low to High</option>
                    <option value="sold_desc">Most Sold</option>
                    <option value="price_desc">Price: High to Low</option>
                    <option value="price_asc">Price: Low to High</option>
                </select>

                {{-- Per Page --}}
                <select id="cleanPerPageSelect" class="clean-select" title="Items per page">
                    <option value="10" selected>10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                    <option value="100">100 per page</option>
                </select>

                <button type="button" class="btn-icon-square" id="btnCleanRefresh" title="Refresh list">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>

                <button type="button" class="btn-icon-square" id="btnCleanReset" title="Reset filters">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </button>
            </div>
        </div>

        {{-- Table Container --}}
        <div class="clean-table-responsive">
            {{-- Loading Overlay --}}
            <div id="cleanTableLoader" style="position:absolute;inset:0;background:rgba(255,255,255,0.7);backdrop-filter:blur(2px);z-index:10;display:none;align-items:center;justify-content:center;">
                <div class="spinner-border text-primary" role="status" style="width:2rem;height:2rem;color:var(--cl-navy)!important;">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <table class="clean-table">
                <thead>
                    <tr>
                        <th style="width:40px;text-align:center;">
                            <input type="checkbox" id="selectAllCheckbox" class="row-cb" title="Select all on this page">
                        </th>
                        <th>Product &amp; SKU</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Sales</th>
                        <th>Storefront</th>
                        <th style="text-align:right;width:120px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="cleanProductsTbody">
                    {{-- Rendered via AJAX --}}
                </tbody>
            </table>
        </div>

        {{-- Footer & Pagination --}}
        <div class="clean-table-footer">
            <div id="cleanShowingInfo">Loading products...</div>
            <div id="cleanPaginationWrap" class="clean-pagination-group"></div>
        </div>

    </div>

    {{-- ── 4. Floating Bulk Bar ── --}}
    <div id="cleanBulkBar" class="clean-bulk-bar">
        <span id="cleanBulkCount" style="font-weight:700;">0 selected</span>
        <button type="button" class="bulk-btn-clean" onclick="applyBulkAction('activate')">
            <i class="bi bi-check-circle"></i> Activate
        </button>
        <button type="button" class="bulk-btn-clean" onclick="applyBulkAction('deactivate')">
            <i class="bi bi-eye-slash"></i> Draft
        </button>
        <button type="button" class="bulk-btn-clean" onclick="applyBulkAction('feature')">
            <i class="bi bi-star"></i> Feature
        </button>
        <button type="button" class="bulk-btn-clean danger" onclick="applyBulkAction('delete')">
            <i class="bi bi-trash3"></i> Delete
        </button>
        <button type="button" class="bulk-btn-clean" onclick="clearBulkSelection()" style="padding:6px 8px;" title="Deselect all">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    {{-- ── 5. Toast Notifications ── --}}
    <div id="cleanToastContainer" class="clean-toast-container"></div>

</div>

<script>
    let searchDebounceTimer = null;
    let currentStatus = '';
    let currentPage = 1;
    let currentPerPage = 10;
    let requestSeq = 0;
    let selectedProductIds = new Set();

    const productsAjaxUrl = {!! json_encode(route('admin.products.ajax')) !!};
    const productToggleUrlTemplate = {!! json_encode(route('admin.products.toggle', ['product' => '__ID__'])) !!};
    const productDestroyUrlTemplate = {!! json_encode(route('admin.products.destroy', ['product' => '__ID__'])) !!};
    const productBulkActionUrl = {!! json_encode(route('admin.products.bulk-action')) !!};

    document.addEventListener('DOMContentLoaded', function() {
        initFiltersFromUrl();
        fetchProducts(currentPage || 1);
        bindEventListeners();
    });

    // ── Toast Notification ──
    function showToast(message, type = 'success') {
        const container = document.getElementById('cleanToastContainer');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = `clean-toast toast-${type}`;
        
        let icon = 'bi-check-circle-fill text-success';
        if (type === 'error') icon = 'bi-exclamation-circle-fill text-danger';
        if (type === 'info') icon = 'bi-info-circle-fill text-primary';

        toast.innerHTML = `<i class="bi ${icon}"></i> <span>${escapeHtml(message)}</span>`;
        container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-10px)';
            toast.style.transition = 'all 0.25s ease';
            setTimeout(() => toast.remove(), 250);
        }, 3000);
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // ── AJAX Fetch ──
    function fetchProducts(page = 1) {
        currentPage = page;
        currentPerPage = Number(document.getElementById('cleanPerPageSelect')?.value || currentPerPage || 10);
        const thisSeq = ++requestSeq;
        const loader = document.getElementById('cleanTableLoader');
        if (loader) loader.style.display = 'flex';

        const searchVal = document.getElementById('cleanSearchInput')?.value.trim() || '';
        const catVal = document.getElementById('cleanCategorySelect')?.value || '';
        const stockVal = document.getElementById('cleanStockSelect')?.value || '';
        const sortVal = document.getElementById('cleanSortSelect')?.value || 'latest';

        const params = new URLSearchParams({
            page: page,
            per_page: currentPerPage,
            search: searchVal,
            category: catVal,
            sort: sortVal,
        });

        if (currentStatus) {
            params.set('status', currentStatus);
        } else if (stockVal) {
            params.set('status', stockVal);
        }

        fetch(`${productsAjaxUrl}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(res => {
            if (thisSeq !== requestSeq) return;
            if (loader) loader.style.display = 'none';

            renderRows(res.data || []);
            renderPagination(res);
            updateUrlParams(params);
        })
        .catch(err => {
            if (thisSeq !== requestSeq) return;
            if (loader) loader.style.display = 'none';
            const tbody = document.getElementById('cleanProductsTbody');
            if (tbody) {
                tbody.innerHTML = `<tr><td colspan="8" style="text-align:center;padding:40px;color:#e11d48;"><i class="bi bi-exclamation-triangle"></i> Failed to load products. <button type="button" onclick="fetchProducts(${page})" class="btn-clean-secondary" style="margin-left:8px;padding:4px 10px;">Retry</button></td></tr>`;
            }
        });
    }

    // ── Render Table Rows ──
    function renderRows(products) {
        const tbody = document.getElementById('cleanProductsTbody');
        if (!tbody) return;

        if (!products || products.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" style="text-align:center;padding:60px 20px;color:#64748b;">
                        <i class="bi bi-box-seam" style="font-size:36px;color:#cbd5e1;display:block;margin-bottom:8px;"></i>
                        <strong style="color:#0f172a;font-size:15px;display:block;">No products found</strong>
                        <p style="font-size:12.5px;color:#64748b;margin:4px 0 14px;">Try adjusting your search criteria or add a new article.</p>
                        <a href="{{ route('admin.products.create') }}" class="btn-clean-primary" style="padding:7px 16px;">
                            <i class="bi bi-plus-lg"></i> + Add Product
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
            const hasDiscount = p.original_price && p.original_price > p.price;
            const discountPct = hasDiscount ? Math.round(((p.original_price - p.price) / p.original_price) * 100) : 0;
            const name = escapeHtml(p.name);
            const sku = escapeHtml(p.sku);
            const cat = escapeHtml(p.category || '—');
            const img = escapeHtml(p.image || 'https://placehold.co/100x100/f1f5f9/94a3b8?text=Product');
            const isChecked = selectedProductIds.has(p.id);

            // Stock badge class
            let stockClass = 'stock-in';
            let stockText = `In Stock (${p.stock})`;
            if (isOut) {
                stockClass = 'stock-out';
                stockText = 'Out of Stock';
            } else if (isLow) {
                stockClass = 'stock-low';
                stockText = `Low (${p.stock})`;
            }

            html += `
                <tr id="rowProduct${p.id}" class="${isChecked ? 'row-selected' : ''}">
                    <td style="text-align:center;">
                        <input type="checkbox" class="row-cb" value="${p.id}" ${isChecked ? 'checked' : ''} onchange="onRowCheck(this, ${p.id})">
                    </td>
                    <td>
                        <div class="product-cell-flex">
                            <img src="${img}" class="product-thumb-img" alt="${name}" loading="lazy" onerror="this.onerror=null;this.src='https://placehold.co/100x100/f1f5f9/94a3b8?text=Product';">
                            <div class="product-info-col">
                                <a href="${escapeHtml(p.edit_url)}" class="product-title-link" title="${name}">${name}</a>
                                <div class="product-meta-row">
                                    <span class="sku-clean-pill" onclick="copySku('${sku}', event)" title="Click to copy SKU">
                                        <i class="bi bi-copy" style="font-size:10px;"></i> ${sku}
                                    </span>
                                    ${p.has_variants ? `<span class="variant-clean-tag">${p.variants_count} variants</span>` : ''}
                                    ${p.is_featured ? `<span style="color:#eab308;" title="Featured"><i class="bi bi-star-fill"></i></span>` : ''}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="cat-clean-pill">${cat}</span>
                    </td>
                    <td>
                        <div>
                            <span class="price-main">₹${Number(p.price).toLocaleString('en-IN')}</span>
                            ${hasDiscount ? `<span class="price-original">₹${Number(p.original_price).toLocaleString('en-IN')}</span><span class="discount-pill">${discountPct}% off</span>` : ''}
                        </div>
                    </td>
                    <td>
                        <span class="stock-indicator ${stockClass}">
                            <span class="stock-dot"></span>
                            ${stockText}
                        </span>
                    </td>
                    <td>
                        <strong style="font-weight:700;">${Number(p.total_sold).toLocaleString('en-IN')}</strong>
                        <span style="font-size:11px;color:#64748b;"> sold</span>
                    </td>
                    <td>
                        <label class="switch-ios" title="Toggle active status">
                            <input type="checkbox" ${p.is_active ? 'checked' : ''} onchange="toggleStatus(${p.id}, this)">
                            <span class="slider-ios"></span>
                        </label>
                    </td>
                    <td>
                        <div class="actions-suite-flex">
                            <a href="${escapeHtml(p.view_url)}" target="_blank" class="btn-action-icon" title="View in storefront">
                                <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                            <a href="${escapeHtml(p.edit_url)}" class="btn-action-icon" title="Edit product">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn-action-icon btn-action-delete" onclick="deleteProduct(${p.id}, '${name}')" title="Delete">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
        updateSelectAllState();
    }

    // ── Pagination ──
    function renderPagination(res) {
        const infoEl = document.getElementById('cleanShowingInfo');
        const wrapEl = document.getElementById('cleanPaginationWrap');

        if (infoEl) {
            infoEl.innerHTML = res.total > 0
                ? `Showing <strong>${res.from || 1}</strong> to <strong>${res.to || res.total}</strong> of <strong>${res.total}</strong> products`
                : 'Showing <strong>0</strong> products';
        }

        if (!wrapEl) return;
        if (res.last_page <= 1) {
            wrapEl.innerHTML = '';
            return;
        }

        let html = '';
        html += `<button type="button" class="btn-page-step" ${res.current_page === 1 ? 'disabled' : ''} onclick="fetchProducts(${res.current_page - 1})"><i class="bi bi-chevron-left"></i></button>`;

        let start = Math.max(1, res.current_page - 2);
        let end = Math.min(res.last_page, res.current_page + 2);

        if (start > 1) {
            html += `<button type="button" class="btn-page-step" onclick="fetchProducts(1)">1</button>`;
            if (start > 2) html += `<span style="padding:0 4px;color:#94a3b8;">…</span>`;
        }

        for (let i = start; i <= end; i++) {
            html += `<button type="button" class="btn-page-step ${i === res.current_page ? 'active' : ''}" onclick="fetchProducts(${i})">${i}</button>`;
        }

        if (end < res.last_page) {
            if (end < res.last_page - 1) html += `<span style="padding:0 4px;color:#94a3b8;">…</span>`;
            html += `<button type="button" class="btn-page-step" onclick="fetchProducts(${res.last_page})">${res.last_page}</button>`;
        }

        html += `<button type="button" class="btn-page-step" ${res.current_page === res.last_page ? 'disabled' : ''} onclick="fetchProducts(${res.current_page + 1})"><i class="bi bi-chevron-right"></i></button>`;
        wrapEl.innerHTML = html;
    }

    // ── Copy SKU ──
    function copySku(sku, e) {
        e.stopPropagation();
        if (!sku || sku === '—') return;
        navigator.clipboard.writeText(sku).then(() => {
            showToast(`SKU "${sku}" copied!`, 'info');
        });
    }

    // ── Toggle Active Status ──
    function toggleStatus(productId, cb) {
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
                showToast(`Product status updated`, 'success');
            } else {
                cb.checked = !cb.checked;
                showToast(data.message || 'Failed to update', 'error');
            }
        })
        .catch(err => {
            cb.checked = !cb.checked;
            showToast('Server error updating status', 'error');
        });
    }

    // ── Delete Single Product ──
    function deleteProduct(productId, name) {
        if (!confirm(`Are you sure you want to delete "${name}"?\nThis cannot be undone.`)) return;

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
                fetchProducts(currentPage);
            } else {
                showToast(data.message || 'Failed to delete', 'error');
            }
        })
        .catch(err => showToast('Failed to delete product', 'error'));
    }

    // ── Row Checkboxes & Bulk Actions ──
    function onRowCheck(cb, id) {
        if (cb.checked) {
            selectedProductIds.add(id);
        } else {
            selectedProductIds.delete(id);
        }
        const row = document.getElementById('rowProduct' + id);
        if (row) {
            if (cb.checked) row.classList.add('row-selected');
            else row.classList.remove('row-selected');
        }
        updateBulkBar();
        updateSelectAllState();
    }

    function updateSelectAllState() {
        const selectAll = document.getElementById('selectAllCheckbox');
        const cbs = document.querySelectorAll('.clean-table tbody .row-cb');
        if (!selectAll || cbs.length === 0) return;
        const checkedCount = Array.from(cbs).filter(c => c.checked).length;
        selectAll.checked = (checkedCount === cbs.length);
        selectAll.indeterminate = (checkedCount > 0 && checkedCount < cbs.length);
    }

    function updateBulkBar() {
        const bar = document.getElementById('cleanBulkBar');
        const countSpan = document.getElementById('cleanBulkCount');
        if (!bar || !countSpan) return;

        if (selectedProductIds.size > 0) {
            countSpan.textContent = `${selectedProductIds.size} selected`;
            bar.classList.add('visible');
        } else {
            bar.classList.remove('visible');
        }
    }

    function clearBulkSelection() {
        selectedProductIds.clear();
        document.querySelectorAll('.clean-table .row-cb').forEach(c => c.checked = false);
        document.querySelectorAll('.clean-table tr').forEach(r => r.classList.remove('row-selected'));
        updateBulkBar();
        updateSelectAllState();
    }

    function applyBulkAction(action) {
        if (selectedProductIds.size === 0) return;
        const ids = Array.from(selectedProductIds);

        if (action === 'delete') {
            if (!confirm(`Delete ${ids.length} selected product(s)? This cannot be undone.`)) return;
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
                showToast(data.message || 'Bulk action completed', 'success');
                clearBulkSelection();
                fetchProducts(currentPage);
            } else {
                showToast(data.message || 'Bulk action failed', 'error');
            }
        })
        .catch(err => showToast('Failed to perform bulk action', 'error'));
    }

    // ── Setup Event Listeners ──
    function bindEventListeners() {
        // Select all checkbox
        document.getElementById('selectAllCheckbox')?.addEventListener('change', function() {
            const isChecked = this.checked;
            document.querySelectorAll('.clean-table tbody .row-cb').forEach(cb => {
                cb.checked = isChecked;
                const id = Number(cb.value);
                if (isChecked) {
                    selectedProductIds.add(id);
                } else {
                    selectedProductIds.delete(id);
                }
                const row = document.getElementById('rowProduct' + id);
                if (row) {
                    if (isChecked) row.classList.add('row-selected');
                    else row.classList.remove('row-selected');
                }
            });
            updateBulkBar();
        });

        // Search Input with Debounce
        const searchInput = document.getElementById('cleanSearchInput');
        const clearBtn = document.getElementById('btnCleanSearchClear');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const val = this.value;
                if (clearBtn) clearBtn.style.display = val.length > 0 ? 'block' : 'none';
                clearTimeout(searchDebounceTimer);
                searchDebounceTimer = setTimeout(() => {
                    fetchProducts(1);
                }, 350);
            });
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', function() {
                if (searchInput) searchInput.value = '';
                this.style.display = 'none';
                fetchProducts(1);
            });
        }

        // Dropdowns
        ['cleanCategorySelect', 'cleanStockSelect', 'cleanSortSelect', 'cleanPerPageSelect'].forEach(id => {
            document.getElementById(id)?.addEventListener('change', () => fetchProducts(1));
        });

        // Top refresh & toolbar refresh
        ['btnTopRefresh', 'btnCleanRefresh'].forEach(id => {
            document.getElementById(id)?.addEventListener('click', () => {
                fetchProducts(currentPage);
                showToast('Catalog refreshed', 'info');
            });
        });

        // Reset Filters
        document.getElementById('btnCleanReset')?.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (clearBtn) clearBtn.style.display = 'none';
            const catSel = document.getElementById('cleanCategorySelect');
            if (catSel) catSel.value = '';
            const stockSel = document.getElementById('cleanStockSelect');
            if (stockSel) stockSel.value = '';
            document.getElementById('cleanSortSelect').value = 'latest';
            document.getElementById('cleanPerPageSelect').value = '10';
            currentStatus = '';
            clearBulkSelection();
            syncActiveTabs();
            fetchProducts(1);
            showToast('Filters reset', 'info');
        });

        // Segment Tabs Click
        document.querySelectorAll('.tab-clean-item').forEach(tab => {
            tab.addEventListener('click', function() {
                currentStatus = this.getAttribute('data-status') || '';
                syncActiveTabs();
                fetchProducts(1);
            });
        });

        // Metric Cards Click
        document.querySelectorAll('.metric-clean-card').forEach(card => {
            card.addEventListener('click', function() {
                currentStatus = this.getAttribute('data-kpi-status') || '';
                syncActiveTabs();
                fetchProducts(1);
            });
        });
    }

    function syncActiveTabs() {
        document.querySelectorAll('.tab-clean-item').forEach(tab => {
            const st = tab.getAttribute('data-status') || '';
            if (st === currentStatus) tab.classList.add('active');
            else tab.classList.remove('active');
        });

        document.querySelectorAll('.metric-clean-card').forEach(card => {
            const st = card.getAttribute('data-kpi-status') || '';
            if (st === currentStatus) card.classList.add('active-filter');
            else card.classList.remove('active-filter');
        });
    }

    function initFiltersFromUrl() {
        const params = new URLSearchParams(window.location.search);
        if (params.has('status')) currentStatus = params.get('status');
        if (params.has('search')) {
            const s = document.getElementById('cleanSearchInput');
            if (s) {
                s.value = params.get('search');
                const c = document.getElementById('btnCleanSearchClear');
                if (c) c.style.display = 'block';
            }
        }
        if (params.has('category')) {
            const cat = document.getElementById('cleanCategorySelect');
            if (cat) cat.value = params.get('category');
        }
        if (params.has('sort')) {
            const s = document.getElementById('cleanSortSelect');
            if (s) s.value = params.get('sort');
        }
        if (params.has('per_page')) {
            const pp = document.getElementById('cleanPerPageSelect');
            if (pp) pp.value = params.get('per_page');
        }
        syncActiveTabs();
    }

    function updateUrlParams(params) {
        const url = new URL(window.location.href);
        params.forEach((v, k) => {
            if (v && v !== '0' && v !== 'latest' && v !== '10') {
                url.searchParams.set(k, v);
            } else if (k === 'status' && v) {
                url.searchParams.set(k, v);
            } else {
                url.searchParams.delete(k);
            }
        });
        window.history.replaceState({}, '', url.toString());
    }
</script>
@endsection
