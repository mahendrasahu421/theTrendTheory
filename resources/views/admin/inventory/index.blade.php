{{-- resources/views/admin/inventory/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Inventory & Stock Management')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .inventory-wrap {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        display: flex;
        flex-direction: column;
        gap: 20px;
        color: #1e293b;
        max-width: 1440px;
        margin: 0 auto;
    }

    /* ── 1. Top Executive Banner with Vector Illustration ── */
    .inventory-banner {
        background: linear-gradient(135deg, #0b192e 0%, #0f2b54 50%, #0369a1 100%);
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
    .inventory-banner-text {
        position: relative;
        z-index: 2;
        max-width: 620px;
    }
    .inventory-banner-badge {
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
        color: #38bdf8;
        margin-bottom: 12px;
    }
    .pulse-dot-cyan {
        width: 7px;
        height: 7px;
        background: #38bdf8;
        border-radius: 50%;
        box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.4);
    }
    .inventory-banner-title {
        font-size: 26px;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin: 0 0 8px;
        color: #ffffff;
    }
    .inventory-banner-desc {
        font-size: 13.5px;
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.85);
        margin: 0;
    }
    .inventory-banner-art {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    @media (max-width: 900px) {
        .inventory-banner-art { display: none; }
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
    .kpi-icon-rose { background: #fef2f2; color: #dc2626; }

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

    /* ── 3. Main Data Card ── */
    .inventory-table-card {
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
        transition: all 0.15s ease;
    }
    .segment-pill-item:hover {
        color: #0f172a;
        background: #f8fafc;
    }
    .segment-pill-item.active {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
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
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 12.5px;
        font-family: inherit;
        outline: none;
        transition: all 0.15s ease;
        background: #ffffff;
    }
    .filter-search-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
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
        border: 1px solid #e2e8f0;
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
        font-size: 10.5px;
        font-weight: 800;
        color: #64748b;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        background: #fafcff;
        border-bottom: 1px solid #edf2f7;
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
        padding: 14px 18px;
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

    /* Stock Level Progress Bar */
    .stock-progress-wrap {
        min-width: 120px;
    }
    .stock-bar-track {
        height: 6px;
        background: #f1f5f9;
        border-radius: 999px;
        overflow: hidden;
        margin-top: 4px;
    }
    .stock-bar-fill {
        height: 100%;
        border-radius: 999px;
    }

    /* Quick Adjust Suite */
    .quick-adjust-group {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .btn-quick-qty {
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #334155;
        border-radius: 7px;
        padding: 4px 8px;
        font-size: 11.5px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .btn-quick-qty:hover {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
    }

    .input-stock-direct {
        width: 58px;
        padding: 4px 6px;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
        text-align: center;
        outline: none;
    }
    .input-stock-direct:focus {
        border-color: #2563eb;
    }
    .btn-save-stock {
        width: 28px;
        height: 28px;
        border-radius: 7px;
        background: #2563eb;
        color: #ffffff;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.15s ease;
    }
    .btn-save-stock:hover {
        background: #1d4ed8;
    }

    /* Status Badges */
    .status-badge-stock {
        display: inline-flex;
        align-items: center;
        padding: 4px 9px;
        border-radius: 7px;
        font-size: 10.5px;
        font-weight: 800;
        text-transform: uppercase;
    }
    .stock-badge-green { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
    .stock-badge-amber { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
    .stock-badge-red { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

    /* ── Custom Compact Pagination Buttons (Right-aligned) ── */
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
        transition: all 0.15s ease;
    }
    .page-nav-btn:hover:not(.disabled):not(.active) {
        background: #f8fafc;
        color: #2563eb;
        border-color: #cbd5e1;
    }
    .page-nav-btn.active {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 3px 10px rgba(37, 99, 235, 0.3);
    }
    .page-nav-btn.disabled {
        background: #f8fafc;
        color: #cbd5e1;
        border-color: #edf2f7;
        cursor: not-allowed;
    }

    /* Toast */
    .stock-toast {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 99999;
        background: #0f172a;
        color: #ffffff;
        padding: 12px 20px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.25);
        display: none;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        font-weight: 600;
    }
</style>

<div class="inventory-wrap">

    

    {{-- ── 2. Top 4 Inventory KPIs ── --}}
    <div class="kpi-modern-grid">
        {{-- Total Stock Units --}}
        <div class="kpi-modern-card">
            <div class="kpi-card-head">
                <span class="kpi-card-label">Total Stock Units</span>
                <div class="kpi-icon-box kpi-icon-blue">
                    <i class="bi bi-boxes"></i>
                </div>
            </div>
            <div class="kpi-card-number">{{ number_format($totalStockUnits) }}</div>
            <div class="kpi-card-sub">Across {{ $totalProductsCount }} active product items</div>
        </div>

        {{-- Inventory Stock Value --}}
        <div class="kpi-modern-card">
            <div class="kpi-card-head">
                <span class="kpi-card-label">Stock Valuation</span>
                <div class="kpi-icon-box kpi-icon-emerald">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
            <div class="kpi-card-number" style="color: #059669;">₹{{ number_format($totalStockValuation) }}</div>
            <div class="kpi-card-sub">Total capital held in warehouse</div>
        </div>

        {{-- Low Stock Alerts --}}
        <div class="kpi-modern-card" style="{{ $lowStockCount > 0 ? 'border-color:#f59e0b; background:#fffdf5;' : '' }}">
            <div class="kpi-card-head">
                <span class="kpi-card-label" style="{{ $lowStockCount > 0 ? 'color:#b45309;' : '' }}">Low Stock Alerts</span>
                <div class="kpi-icon-box kpi-icon-amber">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
            </div>
            <div class="kpi-card-number" style="color: #d97706;">{{ number_format($lowStockCount) }}</div>
            <div class="kpi-card-sub">{{ $lowStockCount > 0 ? 'Urgent restock recommended' : 'All items healthy' }}</div>
        </div>

        {{-- Out of Stock --}}
        <div class="kpi-modern-card" style="{{ $outOfStockCount > 0 ? 'border-color:#fca5a5; background:#fffbfb;' : '' }}">
            <div class="kpi-card-head">
                <span class="kpi-card-label" style="{{ $outOfStockCount > 0 ? 'color:#dc2626;' : '' }}">Out of Stock</span>
                <div class="kpi-icon-box kpi-icon-rose">
                    <i class="bi bi-x-octagon-fill"></i>
                </div>
            </div>
            <div class="kpi-card-number" style="color: #dc2626;">{{ number_format($outOfStockCount) }}</div>
            <div class="kpi-card-sub">{{ $outOfStockCount > 0 ? 'Depleted items on storefront' : 'Zero items depleted' }}</div>
        </div>
    </div>

    {{-- ── 3. Main Data Card ── --}}
    <div class="inventory-table-card">
        
        {{-- Segment Filter Tabs --}}
        <div class="table-segment-bar">
            <button type="button" class="segment-pill-item {{ !request('status') ? 'active' : '' }}" data-status="">
                All Items ({{ $totalProductsCount }})
            </button>
            <button type="button" class="segment-pill-item {{ request('status') === 'low_stock' ? 'active' : '' }}" data-status="low_stock" style="{{ $lowStockCount > 0 ? 'color:#d97706;' : '' }}">
                <i class="bi bi-exclamation-triangle-fill"></i> Low Stock ({{ $lowStockCount }})
            </button>
            <button type="button" class="segment-pill-item {{ request('status') === 'out_of_stock' ? 'active' : '' }}" data-status="out_of_stock" style="{{ $outOfStockCount > 0 ? 'color:#dc2626;' : '' }}">
                <i class="bi bi-x-circle-fill"></i> Out of Stock ({{ $outOfStockCount }})
            </button>
            <button type="button" class="segment-pill-item {{ request('status') === 'in_stock' ? 'active' : '' }}" data-status="in_stock">
                <i class="bi bi-check-circle-fill text-success"></i> Healthy Stock ({{ max(0, $totalProductsCount - $lowStockCount - $outOfStockCount) }})
            </button>
        </div>

        {{-- Clean Real-Time Filter Toolbar --}}
        <div class="table-filter-toolbar">
            <div class="filter-search-box">
                <i class="bi bi-search filter-search-icon"></i>
                <input type="text" 
                       name="search" 
                       id="inventorySearchInput"
                       value="{{ request('search') }}" 
                       placeholder="Search title, SKU, or color..." 
                       class="filter-search-input">
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                {{-- Category Filter --}}
                @if ($categories->isNotEmpty())
                    <select name="category" id="inventoryCategorySelect" class="filter-select">
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                @endif

                {{-- Sort Filter --}}
                <select name="sort" id="inventorySortSelect" class="filter-select">
                    <option value="stock_asc" @selected(request('sort', 'stock_asc') === 'stock_asc')>Sort by: Lowest Stock First</option>
                    <option value="stock_desc" @selected(request('sort') === 'stock_desc')>Sort by: Highest Stock First</option>
                    <option value="sold_desc" @selected(request('sort') === 'sold_desc')>Sort by: Most Sold</option>
                    <option value="price_desc" @selected(request('sort') === 'price_desc')>Sort by: Highest Price</option>
                    <option value="price_asc" @selected(request('sort') === 'price_asc')>Sort by: Lowest Price</option>
                </select>

                <button type="button" class="btn btn-sm btn-light" id="btnResetInventoryFilters" style="border-radius: 10px; padding: 9px 13px; border: 1px solid #e2e8f0;" title="Reset filters">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </button>
            </div>
        </div>

        {{-- Inventory Table --}}
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
                        <th>SOLD COUNT</th>
                        <th>RETAIL PRICE</th>
                        <th>CURRENT STOCK</th>
                        <th>LIVE STATUS</th>
                        <th style="text-align: right; width: 220px;">QUICK STOCK ADJUST</th>
                    </tr>
                </thead>
                <tbody id="inventoryTableBody">
                    @include('admin.inventory.partials.table_rows')
                </tbody>
            </table>
        </div>

        {{-- Custom Clean AJAX Pagination Footer (Left: Info, Right: Pagination Buttons) --}}
        <div class="table-footer-bar">
            <div id="inventoryShowingInfo" class="table-footer-left">
                Showing <strong>{{ $products->firstItem() ?? 1 }}</strong> to <strong>{{ $products->lastItem() ?? $products->count() }}</strong> of <strong>{{ $products->total() }}</strong> inventory items
            </div>
            <div id="inventoryPaginationContainer" class="table-footer-right">
                @include('admin.inventory.partials.pagination')
            </div>
        </div>
    </div>

</div>

{{-- Toast Alert --}}
<div id="stockToast" class="stock-toast">
    <i class="bi bi-check-circle-fill text-success fs-5"></i>
    <span id="stockToastMsg">Stock updated successfully</span>
</div>

<script>
    let searchTimeout = null;
    let currentStatus = '{{ request("status", "") }}';

    function showToast(msg) {
        const toast = document.getElementById('stockToast');
        const toastMsg = document.getElementById('stockToastMsg');
        if (toast && toastMsg) {
            toastMsg.textContent = msg;
            toast.style.display = 'flex';
            setTimeout(() => { toast.style.display = 'none'; }, 3000);
        }
    }

    // ── AJAX DataTable Page & Filter Loader ──
    function loadInventoryData(url) {
        const overlay = document.getElementById('tableLoadingOverlay');
        if (overlay) overlay.style.display = 'flex';

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (overlay) overlay.style.display = 'none';
            if (data.success) {
                document.getElementById('inventoryTableBody').innerHTML = data.table_html;
                document.getElementById('inventoryPaginationContainer').innerHTML = data.pagination_html;
                document.getElementById('inventoryShowingInfo').innerHTML = data.showing_text;

                // Update browser URL without reloading
                window.history.pushState({}, '', url);
            }
        })
        .catch(err => {
            if (overlay) overlay.style.display = 'none';
            console.error('AJAX inventory load error:', err);
        });
    }

    function loadInventoryPage(pageUrl) {
        if (!pageUrl) return;
        loadInventoryData(pageUrl);
    }

    function buildFilterUrl() {
        const search = document.getElementById('inventorySearchInput').value.trim();
        const categorySelect = document.getElementById('inventoryCategorySelect');
        const category = categorySelect ? categorySelect.value : '';
        const sort = document.getElementById('inventorySortSelect').value;

        const params = new URLSearchParams();
        if (currentStatus) params.set('status', currentStatus);
        if (search) params.set('search', search);
        if (category) params.set('category', category);
        if (sort) params.set('sort', sort);

        return `{{ route('admin.inventory.index') }}?${params.toString()}`;
    }

    // ── Real-time Search with Debounce (300ms) ──
    document.getElementById('inventorySearchInput').addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            loadInventoryData(buildFilterUrl());
        }, 300);
    });

    // ── Category & Sort Filter Change Handlers ──
    const categorySelect = document.getElementById('inventoryCategorySelect');
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            loadInventoryData(buildFilterUrl());
        });
    }

    document.getElementById('inventorySortSelect').addEventListener('change', function() {
        loadInventoryData(buildFilterUrl());
    });

    // ── Segment Tab Click Handlers ──
    document.querySelectorAll('.segment-pill-item').forEach(pill => {
        pill.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.segment-pill-item').forEach(p => p.classList.remove('active'));
            this.classList.add('active');

            currentStatus = this.getAttribute('data-status') || '';
            loadInventoryData(buildFilterUrl());
        });
    });

    // ── Reset Filters ──
    document.getElementById('btnResetInventoryFilters').addEventListener('click', function() {
        document.getElementById('inventorySearchInput').value = '';
        if (categorySelect) categorySelect.value = '';
        document.getElementById('inventorySortSelect').value = 'stock_asc';
        currentStatus = '';
        document.querySelectorAll('.segment-pill-item').forEach(p => p.classList.remove('active'));
        const allPill = document.querySelector('.segment-pill-item[data-status=""]');
        if (allPill) allPill.classList.add('active');
        loadInventoryData(buildFilterUrl());
    });

    // ── Quick Stock AJAX Adjustment ──
    function adjustStock(productId, action, amount) {
        fetch(`/admin/inventory/${productId}/stock`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ action: action, amount: amount })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                updateRowUI(productId, data.stock);
                showToast(data.message);
            } else {
                alert(data.message || 'Failed to update stock');
            }
        })
        .catch(err => alert('Failed to update stock'));
    }

    function saveDirectStock(productId) {
        const input = document.getElementById('directStockInput' + productId);
        const amount = parseInt(input.value) || 0;
        adjustStock(productId, 'set', amount);
    }

    function updateRowUI(productId, newStock) {
        const stockDisplay = document.getElementById('stockDisplay' + productId);
        const directInput = document.getElementById('directStockInput' + productId);
        const stockBar = document.getElementById('stockBar' + productId);
        const badge = document.getElementById('statusBadge' + productId);

        if (stockDisplay) stockDisplay.textContent = newStock + ' units';
        if (directInput) directInput.value = newStock;

        const isOut = newStock <= 0;
        const isLow = newStock > 0 && newStock <= 10;
        const percent = Math.min(100, Math.max(0, Math.round((newStock / 50) * 100)));

        if (stockBar) {
            stockBar.style.width = percent + '%';
            stockBar.style.background = isOut ? '#dc2626' : (isLow ? '#f59e0b' : '#10b981');
        }

        if (stockDisplay) {
            stockDisplay.style.color = isOut ? '#dc2626' : (isLow ? '#d97706' : '#059669');
        }

        if (badge) {
            if (isOut) {
                badge.className = 'status-badge-stock stock-badge-red';
                badge.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> Out of Stock';
            } else if (isLow) {
                badge.className = 'status-badge-stock stock-badge-amber';
                badge.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-1"></i> Low Stock';
            } else {
                badge.className = 'status-badge-stock stock-badge-green';
                badge.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> In Stock';
            }
        }
    }
</script>
@endsection
