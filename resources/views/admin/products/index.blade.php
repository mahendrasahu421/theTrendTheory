{{-- resources/views/admin/products/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Product Catalog')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .products-master-wrap {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        display: flex;
        flex-direction: column;
        gap: 20px;
        color: #1e293b;
        max-width: 1440px;
        margin: 0 auto;
    }

    /* ── 1. Top Executive Banner ── */
    .products-banner {
        background: linear-gradient(135deg, #0b192e 0%, #0f2b54 50%, #1e3a8a 100%);
        border-radius: 20px;
        padding: 28px 36px;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(11, 25, 46, 0.2);
    }
    .products-banner-text {
        position: relative;
        z-index: 2;
        max-width: 620px;
    }
    .products-banner-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 5px 14px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: #60a5fa;
        margin-bottom: 12px;
    }
    .pulse-dot-blue {
        width: 7px;
        height: 7px;
        background: #60a5fa;
        border-radius: 50%;
        box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.4);
    }
    .products-banner-title {
        font-size: 26px;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin: 0 0 8px;
        color: #ffffff;
    }
    .products-banner-desc {
        font-size: 13.5px;
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.85);
        margin: 0 0 18px;
    }
    .products-btn-group {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .btn-banner-white {
        background: #ffffff;
        color: #0f2b54;
        border: none;
        padding: 9px 18px;
        border-radius: 11px;
        font-size: 12.5px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.15s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    .btn-banner-white:hover {
        background: #f8fafc;
        transform: translateY(-1px);
        color: #001636;
    }
    .btn-banner-blue {
        background: rgba(255, 255, 255, 0.18);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 9px 18px;
        border-radius: 11px;
        font-size: 12.5px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.15s ease;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
    }
    .btn-banner-blue:hover {
        background: rgba(255, 255, 255, 0.28);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
    }
    .products-banner-art {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    @media (max-width: 900px) {
        .products-banner-art { display: none; }
    }

    /* ── 2. Top 4 KPI Bento Cards ── */
    .kpi-modern-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }
    @media (max-width: 1024px) {
        .kpi-modern-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 600px) {
        .kpi-modern-grid { grid-template-columns: 1fr; }
    }
    .kpi-modern-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 16px;
        padding: 18px 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.2s ease;
    }
    .kpi-modern-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        border-color: #cbd5e1;
    }
    .kpi-card-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }
    .kpi-card-label {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
    }
    .kpi-icon-box {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
    }
    .kpi-icon-blue { background: #eff6ff; color: #2563eb; }
    .kpi-icon-emerald { background: #ecfdf5; color: #059669; }
    .kpi-icon-amber { background: #fffbeb; color: #d97706; }
    .kpi-icon-purple { background: #faf5ff; color: #9333ea; }

    .kpi-card-number {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.15;
        margin-bottom: 4px;
    }
    .kpi-card-sub {
        font-size: 11.5px;
        color: #64748b;
    }

    /* ── 3. Main Catalog Card ── */
    .products-table-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 18px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        overflow: hidden;
    }

    /* Segment Tabs */
    .table-segment-bar {
        padding: 16px 20px 10px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
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

    /* Filter Controls Toolbar */
    .table-filter-toolbar {
        padding: 14px 20px;
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
        max-width: 360px;
    }
    .filter-search-input {
        width: 100%;
        padding: 9px 12px 9px 36px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 12.5px;
        font-family: inherit;
        outline: none;
        transition: all 0.15s ease;
        background: #ffffff;
    }
    .filter-search-input:focus {
        border-color: #00285a;
        box-shadow: 0 0 0 3px rgba(0, 40, 90, 0.08);
    }
    .filter-search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
    }
    .filter-select {
        padding: 9px 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 12.5px;
        font-family: inherit;
        font-weight: 600;
        color: #334155;
        background: #ffffff;
        outline: none;
        cursor: pointer;
    }

    /* Modern Table */
    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .modern-table th {
        padding: 13px 18px;
        font-size: 11px;
        font-weight: 800;
        color: #475569;
        letter-spacing: 0.5px;
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
    .modern-table td {
        padding: 13px 18px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        font-size: 12.5px;
    }
    .modern-table tr:last-child td {
        border-bottom: none;
    }

    /* Product Avatar */
    .product-img-box {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        flex-shrink: 0;
    }

    /* iOS Toggle Switch */
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
        border-radius: 8px;
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

    /* ── Custom Compact Pagination (Right-aligned) ── */
    .table-footer-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 22px;
        background: #ffffff;
        border-top: 1px solid #f1f5f9;
        font-size: 12.5px;
        color: #64748b;
        width: 100%;
        box-sizing: border-box;
    }
    .table-footer-left {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12.5px;
        color: #64748b;
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
        margin-left: auto;
    }
    .page-nav-btn {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        font-size: 12px;
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
</style>

<div class="products-master-wrap">

    

    {{-- ── 2. Top 4 KPI Bento Cards ── --}}
    <div class="kpi-modern-grid">
        {{-- Total Products --}}
        <div class="kpi-modern-card">
            <div class="kpi-card-head">
                <span class="kpi-card-label">Total Catalog</span>
                <div class="kpi-icon-box kpi-icon-blue">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
            <div class="kpi-card-number">{{ number_format($totalProducts) }}</div>
            <div class="kpi-card-sub">Articles in master catalog</div>
        </div>

        {{-- Active Products --}}
        <div class="kpi-modern-card">
            <div class="kpi-card-head">
                <span class="kpi-card-label">Live Active</span>
                <div class="kpi-icon-box kpi-icon-emerald">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
            <div class="kpi-card-number" style="color: #059669;">{{ number_format($activeProducts) }}</div>
            <div class="kpi-card-sub">Visible to customers online</div>
        </div>

        {{-- Low Stock Alerts --}}
        <div class="kpi-modern-card" style="{{ $lowStockProducts > 0 ? 'border-color:#f59e0b; background:#fffdf5;' : '' }}">
            <div class="kpi-card-head">
                <span class="kpi-card-label" style="{{ $lowStockProducts > 0 ? 'color:#b45309;' : '' }}">Low Stock Alerts</span>
                <div class="kpi-icon-box kpi-icon-amber">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
            </div>
            <div class="kpi-card-number" style="color: #d97706;">{{ number_format($lowStockProducts) }}</div>
            <div class="kpi-card-sub">Stock level &le; 10 units</div>
        </div>

        {{-- Drafts / Inactive --}}
        <div class="kpi-modern-card">
            <div class="kpi-card-head">
                <span class="kpi-card-label">Drafts / Inactive</span>
                <div class="kpi-icon-box kpi-icon-purple">
                    <i class="bi bi-eye-slash-fill"></i>
                </div>
            </div>
            <div class="kpi-card-number" style="color: #9333ea;">{{ number_format($draftProducts) }}</div>
            <div class="kpi-card-sub">Hidden from storefront</div>
        </div>
    </div>

    {{-- ── 3. Main Catalog Data Card ── --}}
    <div class="products-table-card">
        
        {{-- Segment Tabs --}}
        <div class="table-segment-bar">
            <button type="button" class="segment-pill-item active" data-status="">
                All ({{ $totalProducts }})
            </button>
            <button type="button" class="segment-pill-item" data-status="active">
                Live Active ({{ $activeProducts }})
            </button>
            <button type="button" class="segment-pill-item" data-status="inactive">
                Drafts ({{ $draftProducts }})
            </button>
            <button type="button" class="segment-pill-item" data-status="low_stock" style="{{ $lowStockProducts > 0 ? 'color:#d97706;' : '' }}">
                <i class="bi bi-exclamation-triangle-fill"></i> Low Stock ({{ $lowStockProducts }})
            </button>
            <button type="button" class="segment-pill-item" data-status="featured">
                <i class="bi bi-star-fill text-warning"></i> Featured
            </button>
        </div>

        {{-- Filter Toolbar --}}
        <div class="table-filter-toolbar">
            <div class="filter-search-box">
                <i class="bi bi-search filter-search-icon"></i>
                <input type="text" 
                       id="productSearchInput" 
                       placeholder="Search title, SKU, color..." 
                       class="filter-search-input">
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

                {{-- Sort Filter --}}
                <select id="productSortSelect" class="filter-select">
                    <option value="latest">Sort by: Newest First</option>
                    <option value="stock_asc">Sort by: Lowest Stock</option>
                    <option value="sold_desc">Sort by: Most Sold</option>
                    <option value="price_desc">Sort by: Price High to Low</option>
                    <option value="price_asc">Sort by: Price Low to High</option>
                </select>

                <button type="button" class="btn btn-sm btn-light" id="btnResetFilters" style="border-radius: 10px; padding: 9px 13px; border: 1px solid #e2e8f0;" title="Reset filters">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </button>
            </div>
        </div>

        {{-- Modern DataTable --}}
        <div style="overflow-x: auto; position: relative;">
            {{-- Loading Overlay --}}
            <div id="tableLoadingOverlay" style="position: absolute; inset: 0; background: rgba(255,255,255,0.75); backdrop-filter: blur(2px); z-index: 10; display: none; align-items: center; justify-content: center;">
                <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <table class="modern-table">
                <thead>
                    <tr>
                        <th style="width: 340px;">ARTICLE &amp; SKU</th>
                        <th>CATEGORY</th>
                        <th>RETAIL PRICE</th>
                        <th>STOCK LEVEL</th>
                        <th>TOTAL SOLD</th>
                        <th>LIVE STATUS</th>
                        <th style="text-align: right; width: 140px;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody id="productsTableBody">
                    {{-- Dynamically populated via AJAX --}}
                </tbody>
            </table>
        </div>

        {{-- Custom Clean AJAX Pagination Footer (Left: Info, Right: Buttons) --}}
        <div class="table-footer-bar">
            <div id="productsShowingInfo" class="table-footer-left">
                Loading products catalog...
            </div>
            <div id="productsPaginationContainer" class="table-footer-right">
                {{-- Dynamically populated via AJAX --}}
            </div>
        </div>
    </div>

</div>

<script>
    let searchDebounceTimer = null;
    let currentStatus = '';
    let currentPage = 1;

    document.addEventListener('DOMContentLoaded', function() {
        fetchProducts(1);
    });

    // ── AJAX DataTable Loader ──
    function fetchProducts(page = 1) {
        currentPage = page;
        const overlay = document.getElementById('tableLoadingOverlay');
        if (overlay) overlay.style.display = 'flex';

        const search = document.getElementById('productSearchInput').value.trim();
        const category = document.getElementById('productCategorySelect') ? document.getElementById('productCategorySelect').value : '';
        const sort = document.getElementById('productSortSelect').value;

        const params = new URLSearchParams();
        params.set('page', page);
        params.set('per_page', 20);
        if (currentStatus) params.set('status', currentStatus);
        if (search) params.set('search', search);
        if (category) params.set('category', category);
        if (sort) params.set('sort', sort);

        fetch(`{{ route('admin.products.ajax') }}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (overlay) overlay.style.display = 'none';
            renderTableRows(data.data);
            renderPagination(data);
        })
        .catch(err => {
            if (overlay) overlay.style.display = 'none';
            console.error('Failed to load products:', err);
        });
    }

    // ── Render Table Rows ──
    function renderTableRows(products) {
        const tbody = document.getElementById('productsTableBody');
        if (!products || products.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" style="text-align: center; padding: 50px 20px; color: #64748b;">
                        <i class="bi bi-box-seam" style="font-size: 36px; color: #cbd5e1; display: block; margin-bottom: 8px;"></i>
                        <strong style="color: #0f172a; font-size: 14px; display: block;">No products found</strong>
                        <p style="font-size: 12px; margin: 4px 0 16px;">Try adjusting your search criteria or create a new product.</p>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-primary" style="border-radius: 9px; padding: 7px 16px;">
                            + Add New Product
                        </a>
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        products.forEach(p => {
            const isLow = p.stock <= 10 && p.stock > 0;
            const isOut = p.stock <= 0;
            const stockPercent = Math.min(100, Math.max(0, Math.round((p.stock / 50) * 100)));
            const hasDiscount = p.original_price && p.original_price > p.price;
            const discountPct = hasDiscount ? Math.round(((p.original_price - p.price) / p.original_price) * 100) : 0;

            html += `
                <tr class="modern-table-row" id="productRow${p.id}">
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="${p.image || 'https://placehold.co/80x80/f1f5f9/94a3b8?text=Product'}" 
                                 class="product-img-box" 
                                 alt="${p.name}" 
                                 onerror="this.onerror=null;this.src='https://placehold.co/80x80/f1f5f9/94a3b8?text=Product';">
                            <div>
                                <a href="${p.edit_url}" style="font-size: 13.5px; font-weight: 700; color: #0f172a; text-decoration: none;" class="d-block mb-1">
                                    ${p.name}
                                </a>
                                <div class="d-flex align-items-center gap-2 flex-wrap" style="font-size: 11px;">
                                    <span style="font-family: monospace; font-size: 11px; background: #f1f5f9; padding: 2px 6px; border-radius: 4px; border: 1px solid #e2e8f0; color: #00285a; font-weight: 700;">
                                        ${p.sku}
                                    </span>
                                    ${p.has_variants ? `<span style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; padding:2px 7px; border-radius:999px; font-weight:700; font-size:10.5px;">${p.variants_count} variants</span>` : ''}
                                    ${p.is_featured ? `<span style="color:#eab308; font-size:12px;" title="Featured Product"><i class="bi bi-star-fill"></i></span>` : ''}
                                </div>
                            </div>
                        </div>
                    </td>

                    <td>
                        <span style="font-size: 11.5px; font-weight: 700; color: #00285a; background: #f0f4ff; border: 1px solid #dbeafe; padding: 3px 9px; border-radius: 6px; display: inline-block;">
                            ${p.category || 'Uncategorized'}
                        </span>
                    </td>

                    <td>
                        <div>
                            <strong style="font-size: 14px; font-weight: 800; color: #00285a;">₹${Number(p.price).toLocaleString()}</strong>
                            ${hasDiscount ? `
                                <div style="font-size: 11px; margin-top: 2px; display: flex; align-items: center; gap: 4px;">
                                    <del style="color: #94a3b8;">₹${Number(p.original_price).toLocaleString()}</del>
                                    <span style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; border-radius: 4px; padding: 1px 4px; font-weight: 800; font-size: 10px;">${discountPct}% off</span>
                                </div>
                            ` : ''}
                        </div>
                    </td>

                    <td>
                        <div style="min-width: 110px;">
                            <div class="d-flex justify-content-between mb-1" style="font-size: 11.5px;">
                                <span style="font-weight: 800; color: ${isOut ? '#dc2626' : (isLow ? '#d97706' : '#059669')};">
                                    ${p.stock} units
                                </span>
                            </div>
                            <div style="height: 5px; background: #f1f5f9; border-radius: 999px; overflow: hidden;">
                                <div style="height: 100%; width: ${stockPercent}%; background: ${isOut ? '#dc2626' : (isLow ? '#f59e0b' : '#10b981')};"></div>
                            </div>
                        </div>
                    </td>

                    <td>
                        <strong style="font-size: 13px; font-weight: 800; color: #0f172a;">${p.total_sold}</strong>
                        <span style="font-size: 11px; color: #64748b;">sold</span>
                    </td>

                    <td>
                        <label class="ios-switch">
                            <input type="checkbox" ${p.is_active ? 'checked' : ''} onchange="toggleProductStatus(${p.id}, this)">
                            <span class="ios-slider"></span>
                        </label>
                    </td>

                    <td style="text-align: right;">
                        <div class="action-suite">
                            <a href="${p.view_url}" target="_blank" class="action-icon-btn" title="View on storefront">
                                <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                            <a href="${p.edit_url}" class="action-icon-btn btn-edit" title="Edit product details">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <button type="button" onclick="deleteProduct(${p.id})" class="action-icon-btn btn-delete" title="Delete product">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
    }

    // ── Render Pagination (Left: Showing Info, Right: Buttons) ──
    function renderPagination(res) {
        document.getElementById('productsShowingInfo').innerHTML = `
            Showing <strong>${res.from || 1}</strong> to <strong>${res.to || res.total}</strong> of <strong>${res.total}</strong> products
        `;

        const container = document.getElementById('productsPaginationContainer');
        if (res.last_page <= 1) {
            container.innerHTML = '';
            return;
        }

        let html = '<div class="custom-pagination-wrap">';

        // Prev button
        if (res.current_page > 1) {
            html += `<button type="button" class="page-nav-btn" onclick="fetchProducts(${res.current_page - 1})"><i class="bi bi-chevron-left"></i></button>`;
        } else {
            html += `<span class="page-nav-btn disabled"><i class="bi bi-chevron-left"></i></span>`;
        }

        // Page buttons
        for (let i = 1; i <= res.last_page; i++) {
            if (i === res.current_page) {
                html += `<span class="page-nav-btn active">${i}</span>`;
            } else if (i <= 2 || i >= res.last_page - 1 || Math.abs(i - res.current_page) <= 1) {
                html += `<button type="button" class="page-nav-btn" onclick="fetchProducts(${i})">${i}</button>`;
            } else if (i === 3 && res.current_page > 4) {
                html += `<span class="page-nav-btn disabled" style="border:none; background:transparent;">...</span>`;
            }
        }

        // Next button
        if (res.current_page < res.last_page) {
            html += `<button type="button" class="page-nav-btn" onclick="fetchProducts(${res.current_page + 1})"><i class="bi bi-chevron-right"></i></button>`;
        } else {
            html += `<span class="page-nav-btn disabled"><i class="bi bi-chevron-right"></i></span>`;
        }

        html += '</div>';
        container.innerHTML = html;
    }

    // ── Debounced Search Listener ──
    document.getElementById('productSearchInput').addEventListener('input', function() {
        clearTimeout(searchDebounceTimer);
        searchDebounceTimer = setTimeout(() => {
            fetchProducts(1);
        }, 350);
    });

    // ── Filter Change Handlers ──
    const catSelect = document.getElementById('productCategorySelect');
    if (catSelect) {
        catSelect.addEventListener('change', () => fetchProducts(1));
    }
    document.getElementById('productSortSelect').addEventListener('change', () => fetchProducts(1));

    document.getElementById('btnResetFilters').addEventListener('click', function() {
        document.getElementById('productSearchInput').value = '';
        if (catSelect) catSelect.value = '';
        document.getElementById('productSortSelect').value = 'latest';
        currentStatus = '';
        document.querySelectorAll('.segment-pill-item').forEach(p => p.classList.remove('active'));
        document.querySelector('.segment-pill-item[data-status=""]').classList.add('active');
        fetchProducts(1);
    });

    // ── Segment Pill Tabs Click ──
    document.querySelectorAll('.segment-pill-item').forEach(pill => {
        pill.addEventListener('click', function() {
            document.querySelectorAll('.segment-pill-item').forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            currentStatus = this.getAttribute('data-status');
            fetchProducts(1);
        });
    });

    // ── Toggle Active Status ──
    function toggleProductStatus(productId, checkbox) {
        fetch(`/admin/products/${productId}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                checkbox.checked = !checkbox.checked;
                alert(data.message || 'Failed to toggle status');
            }
        })
        .catch(err => {
            checkbox.checked = !checkbox.checked;
            alert('Failed to toggle status');
        });
    }

    // ── Delete Product ──
    function deleteProduct(productId) {
        if (!confirm('Are you sure you want to permanently delete this product?')) return;

        fetch(`/admin/products/${productId}`, {
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
                const row = document.getElementById('productRow' + productId);
                if (row) {
                    row.style.transition = 'opacity 0.3s ease';
                    row.style.opacity = '0';
                    setTimeout(() => fetchProducts(currentPage), 300);
                }
            } else {
                alert(data.message || 'Failed to delete product');
            }
        })
        .catch(err => alert('Failed to delete product'));
    }
</script>
@endsection
