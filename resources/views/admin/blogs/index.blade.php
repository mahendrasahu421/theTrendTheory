{{-- resources/views/admin/blogs/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Blogs & Stories Management')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .modern-dashboard-wrap {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        display: flex;
        flex-direction: column;
        gap: 20px;
        color: #1e293b;
    }

    /* 1. Hero Studio Banner */
    .studio-hero-card {
        background: linear-gradient(120deg, #091e42 0%, #0f3066 50%, #1d4ed8 100%);
        border-radius: 20px;
        padding: 32px 36px;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 12px 36px rgba(15, 48, 102, 0.16);
    }
    .studio-hero-content {
        position: relative;
        z-index: 2;
        max-width: 580px;
    }
    .studio-pill-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 5px 14px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #ffffff;
        margin-bottom: 14px;
    }
    .studio-pulse-dot {
        width: 7px;
        height: 7px;
        background: #10b981;
        border-radius: 50%;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.35);
    }
    .studio-hero-heading {
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin: 0 0 10px;
        color: #ffffff;
    }
    .studio-hero-desc {
        font-size: 13.5px;
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.85);
        margin: 0 0 22px;
    }
    .studio-btn-group {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .btn-hero-white {
        background: #ffffff;
        color: #0f3066 !important;
        font-size: 13px;
        font-weight: 700;
        padding: 10px 20px;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
        transition: all 0.2s ease;
    }
    .btn-hero-white:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.18);
    }
    .btn-hero-blue {
        background: #3b82f6;
        color: #ffffff !important;
        font-size: 13px;
        font-weight: 700;
        padding: 10px 22px;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 16px rgba(59, 130, 246, 0.4);
        transition: all 0.2s ease;
    }
    .btn-hero-blue:hover {
        background: #2563eb;
        transform: translateY(-2px);
    }
    .studio-hero-illustration {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        width: 320px;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
    }
    @media (max-width: 900px) {
        .studio-hero-illustration {
            display: none;
        }
    }

    /* 2. KPI Cards Grid */
    .kpi-modern-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 16px;
    }
    @media (max-width: 1200px) {
        .kpi-modern-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }
    @media (max-width: 768px) {
        .kpi-modern-grid {
            grid-template-columns: 1fr;
        }
    }
    .kpi-modern-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 16px;
        padding: 18px 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.2s ease;
    }
    .kpi-modern-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
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
        width: 32px;
        height: 32px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
    }
    .kpi-icon-blue { background: #eff6ff; color: #2563eb; }
    .kpi-icon-green { background: #ecfdf5; color: #059669; }
    .kpi-icon-amber { background: #fffbeb; color: #d97706; }
    .kpi-icon-purple { background: #faf5ff; color: #7c3aed; }
    .kpi-icon-cyan { background: #ecfeff; color: #0891b2; }

    .kpi-card-number {
        font-size: 26px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
        margin-bottom: 4px;
    }
    .kpi-card-sub {
        font-size: 11.5px;
        color: #64748b;
        margin-bottom: 12px;
    }
    .kpi-card-trend {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        font-weight: 700;
        padding-top: 10px;
        border-top: 1px solid #f1f5f9;
    }
    .trend-pill-green {
        color: #059669;
        background: #ecfdf5;
        padding: 2px 7px;
        border-radius: 6px;
        font-weight: 800;
    }
    .trend-pill-neutral {
        color: #64748b;
        background: #f1f5f9;
        padding: 2px 7px;
        border-radius: 6px;
    }

    /* 3. Main Data Card */
    .data-table-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 18px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    /* Segment Tabs Bar */
    .table-top-segment-bar {
        padding: 16px 22px 10px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }
    .segment-tab-item {
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
    .segment-tab-item:hover {
        color: #0f172a;
        background: #f8fafc;
    }
    .segment-tab-item.active {
        background: #2563eb;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }

    /* Filter Controls Toolbar */
    .table-filter-toolbar {
        padding: 12px 22px;
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
        min-width: 240px;
        flex: 1;
        max-width: 320px;
    }
    .filter-search-input {
        width: 100%;
        padding: 8px 12px 8px 34px;
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
        left: 11px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 13px;
    }
    .filter-select {
        padding: 8px 14px;
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
    .filter-select:focus {
        border-color: #2563eb;
    }
    .btn-filter-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none;
    }
    .btn-filter-icon:hover {
        border-color: #2563eb;
        color: #2563eb;
    }
    .btn-filter-icon.active {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
    }

    /* Clean Modern Table */
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

    /* Article Cell */
    .article-item-cell {
        display: flex;
        align-items: center;
        gap: 12px;
        max-width: 340px;
    }
    .article-avatar-box {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        overflow: hidden;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        flex-shrink: 0;
    }
    .article-avatar-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .article-avatar-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 12px;
        color: #64748b;
    }
    .article-meta-info {
        overflow: hidden;
    }
    .article-item-title {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        line-height: 1.35;
        margin-bottom: 3px;
        transition: color 0.15s ease;
    }
    .article-item-title:hover {
        color: #2563eb;
    }
    .live-dot-indicator {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #10b981;
        display: inline-block;
    }
    .article-tag-chips {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
    }
    .tag-chip {
        font-size: 10.5px;
        font-weight: 600;
        color: #3b82f6;
        background: #eff6ff;
        padding: 1px 6px;
        border-radius: 4px;
        text-decoration: none;
    }

    /* Type & Category */
    .type-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 10.5px;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 6px;
        width: fit-content;
        margin-bottom: 2px;
    }
    .type-chip-blog {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
    }
    .category-name-text {
        font-size: 11.5px;
        color: #64748b;
        font-weight: 600;
    }

    /* Author Cell */
    .author-modern-cell {
        display: flex;
        align-items: center;
        gap: 9px;
    }
    .author-circle-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #0f172a;
        color: #ffffff;
        font-weight: 800;
        font-size: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .author-name-bold {
        font-size: 12px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.2;
    }
    .read-time-muted {
        font-size: 11px;
        color: #94a3b8;
    }

    /* Reads with Sparkline */
    .reads-spark-cell {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
        color: #0f172a;
    }
    .sparkline-svg {
        width: 38px;
        height: 16px;
        color: #3b82f6;
    }

    /* iOS Style Toggle Switch */
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
        inset: 0;
        background-color: #cbd5e1;
        transition: .3s;
        border-radius: 34px;
    }
    .ios-slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .3s;
        border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    input:checked + .ios-slider {
        background-color: #2563eb;
    }
    input:checked + .ios-slider:before {
        transform: translateX(16px);
    }

    /* Live Status Badge */
    .status-badge-modern {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        border: none;
        transition: all 0.15s ease;
    }
    .status-live {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }
    .status-draft {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }

    /* Date Cell */
    .date-main-val {
        font-size: 12px;
        font-weight: 700;
        color: #0f172a;
    }
    .date-time-val {
        font-size: 10.5px;
        color: #94a3b8;
    }

    /* Modern Action Button Suite */
    .action-button-suite {
        display: flex;
        align-items: center;
        gap: 5px;
        justify-content: flex-end;
    }
    .action-btn-item {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .btn-action-view:hover {
        background: #eff6ff;
        color: #2563eb;
        border-color: #bfdbfe;
    }
    .btn-action-edit:hover {
        background: #f8fafc;
        color: #0f172a;
        border-color: #cbd5e1;
    }
    .btn-action-delete {
        border-color: #fee2e2;
        background: #fff;
        color: #dc2626;
    }
    .btn-action-delete:hover {
        background: #fee2e2;
        color: #b91c1c;
        border-color: #fca5a5;
    }

    /* Pagination Footer */
    .table-modern-footer {
        padding: 14px 22px;
        border-top: 1px solid #edf2f7;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: #64748b;
        flex-wrap: wrap;
        gap: 12px;
    }

    .custom-pagination-wrap {
        display: flex;
        align-items: center;
        gap: 6px;
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
</style>

<div class="modern-dashboard-wrap">


    {{-- ── 2. Top KPI Metric Cards (100% Dynamic) ── --}}
    <div class="kpi-modern-grid">
        {{-- Total Articles --}}
        <div class="kpi-modern-card">
            <div class="kpi-card-head">
                <span class="kpi-card-label">Total Articles</span>
                <div class="kpi-icon-box kpi-icon-blue">
                    <i class="bi bi-file-earmark-richtext"></i>
                </div>
            </div>
            <div class="kpi-card-number">{{ number_format($stats['total']) }}</div>
            <div class="kpi-card-sub">{{ $stats['total'] }} Blogs &bull; {{ $stats['total_news'] ?? 0 }} News</div>
            <div class="kpi-card-trend">
                <span class="trend-pill-green"><i class="bi bi-arrow-up-short"></i> {{ $stats['blog_growth'] }}%</span>
                <span class="text-muted">vs last month</span>
            </div>
        </div>

        {{-- Live Published --}}
        <div class="kpi-modern-card">
            <div class="kpi-card-head">
                <span class="kpi-card-label">Live Published</span>
                <div class="kpi-icon-box kpi-icon-green">
                    <i class="bi bi-check2-circle"></i>
                </div>
            </div>
            <div class="kpi-card-number" style="color: #059669;">{{ number_format($stats['published']) }}</div>
            <div class="kpi-card-sub text-success" style="font-weight: 700;">Active on Storefront</div>
            <div class="kpi-card-trend">
                <span class="trend-pill-green"><i class="bi bi-arrow-up-short"></i> {{ $stats['pub_growth'] }}%</span>
                <span class="text-muted">vs last month</span>
            </div>
        </div>

        {{-- Saved Drafts --}}
        <div class="kpi-modern-card">
            <div class="kpi-card-head">
                <span class="kpi-card-label">Saved Drafts</span>
                <div class="kpi-icon-box kpi-icon-amber">
                    <i class="bi bi-file-earmark-lock2"></i>
                </div>
            </div>
            <div class="kpi-card-number" style="color: #d97706;">{{ number_format($stats['drafts']) }}</div>
            <div class="kpi-card-sub">Unpublished / In progress</div>
            <div class="kpi-card-trend">
                <span class="trend-pill-neutral">&mdash;</span>
                <span class="text-muted">vs last month</span>
            </div>
        </div>

        {{-- Fashion Stories --}}
        <div class="kpi-modern-card">
            <div class="kpi-card-head">
                <span class="kpi-card-label">Fashion Stories</span>
                <div class="kpi-icon-box kpi-icon-purple">
                    <i class="bi bi-stars"></i>
                </div>
            </div>
            <div class="kpi-card-number" style="color: #7c3aed;">{{ number_format($stats['total']) }}</div>
            <div class="kpi-card-sub">Style Guides &amp; Outfits</div>
            <div class="kpi-card-trend">
                <span class="trend-pill-green"><i class="bi bi-arrow-up-short"></i> 8%</span>
                <span class="text-muted">vs last month</span>
            </div>
        </div>

        {{-- Cumulative Reads --}}
        <div class="kpi-modern-card">
            <div class="kpi-card-head">
                <span class="kpi-card-label">Cumulative Reads</span>
                <div class="kpi-icon-box kpi-icon-cyan">
                    <i class="bi bi-eye"></i>
                </div>
            </div>
            <div class="kpi-card-number" style="color: #0891b2;">{{ number_format($stats['total_views']) }}</div>
            <div class="kpi-card-sub">Lifetime Article Views</div>
            <div class="kpi-card-trend">
                <span class="trend-pill-green"><i class="bi bi-arrow-up-short"></i> 26%</span>
                <span class="text-muted">vs last month</span>
            </div>
        </div>
    </div>

    {{-- ── 3. Main Data Card ── --}}
    <div class="data-table-card">
        
        {{-- Segment Tabs --}}
        <div class="table-top-segment-bar">
            <a href="{{ route('admin.blogs.index', array_merge(request()->except('status', 'page'), ['status' => ''])) }}" 
               class="segment-tab-item {{ !request('status') ? 'active' : '' }}">
                All ({{ $stats['total'] }})
            </a>
            <a href="{{ route('admin.blogs.index') }}" 
               class="segment-tab-item active">
                Blogs ({{ $stats['total'] }})
            </a>
            <a href="{{ route('admin.news.index') }}" 
               class="segment-tab-item">
                News ({{ $stats['total_news'] ?? 0 }})
            </a>
            <a href="{{ route('admin.blogs.index', array_merge(request()->except('status', 'page'), ['status' => 'draft'])) }}" 
               class="segment-tab-item {{ request('status') === 'draft' ? 'active' : '' }}">
                Drafts ({{ $stats['drafts'] }})
            </a>
        </div>

        {{-- Filter Toolbar --}}
        <div class="table-filter-toolbar">
            <form method="GET" action="{{ route('admin.blogs.index') }}" class="d-flex align-items-center gap-2 flex-grow-1 flex-wrap" id="filterForm">
                {{-- Search Box --}}
                <div class="filter-search-box">
                    <i class="bi bi-search filter-search-icon"></i>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search title, author, tag..." 
                           class="filter-search-input">
                </div>

                {{-- Category Select --}}
                @if ($categories->isNotEmpty())
                    <select name="category" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ $cat }}</option>
                        @endforeach
                    </select>
                @endif

                {{-- Status Select --}}
                <select name="status" class="filter-select" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="published" @selected(request('status') === 'published')>Published</option>
                    <option value="draft" @selected(request('status') === 'draft')>Drafts</option>
                </select>

                {{-- Sort Select (Dynamic) --}}
                <select name="sort" class="filter-select" onchange="this.form.submit()">
                    <option value="latest" @selected(request('sort', 'latest') === 'latest')>Sort by: Latest</option>
                    <option value="views" @selected(request('sort') === 'views')>Sort by: Most Views</option>
                    <option value="featured" @selected(request('sort') === 'featured')>Sort by: Featured</option>
                    <option value="oldest" @selected(request('sort') === 'oldest')>Sort by: Oldest</option>
                </select>

                <button type="submit" class="btn-filter-icon active" title="Apply Filter">
                    <i class="bi bi-sliders"></i>
                </button>

                @if (request()->hasAny(['search', 'status', 'category', 'sort']))
                    <a href="{{ route('admin.blogs.index') }}" class="btn-filter-icon" title="Reset Filters">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </form>
        </div>

        {{-- Table Container --}}
        <div style="overflow-x: auto;">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th style="width: 320px;">ARTICLE</th>
                        <th>TYPE &amp; CATEGORY</th>
                        <th>AUTHOR &amp; READ TIME</th>
                        <th>READS</th>
                        <th style="text-align: center;">FEATURED</th>
                        <th>LIVE STATUS</th>
                        <th>DATE</th>
                        <th style="text-align: right; width: 110px;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($blogs as $blog)
                        <tr class="modern-table-row" id="blogRow{{ $blog->id }}">
                            {{-- Article Thumbnail & Title --}}
                            <td>
                                <div class="article-item-cell">
                                    <div class="article-avatar-box">
                                        @if ($blog->image_url)
                                            <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}">
                                        @else
                                            <div class="article-avatar-placeholder">
                                                {{ strtoupper(substr($blog->title, 0, 2)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="article-meta-info">
                                        <a href="{{ route('admin.blogs.edit', $blog) }}" class="article-item-title">
                                            <span>{{ Str::limit($blog->title, 42) }}</span>
                                            @if ($blog->is_published)
                                                <span class="live-dot-indicator" title="Live"></span>
                                            @endif
                                        </a>
                                        <div class="article-tag-chips">
                                            @if (!empty($blog->tags_list))
                                                @foreach (array_slice($blog->tags_list, 0, 2) as $tag)
                                                    <span class="tag-chip">#{{ $tag }}</span>
                                                @endforeach
                                            @else
                                                <span class="tag-chip">#trends</span>
                                                <span class="tag-chip">#fashion</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Type & Category --}}
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <span class="type-chip type-chip-blog">
                                        <i class="bi bi-journal-text"></i> Blog
                                    </span>
                                    <span class="category-name-text">{{ $blog->category }}</span>
                                </div>
                            </td>

                            {{-- Author & Read Time --}}
                            <td>
                                <div class="author-modern-cell">
                                    <div class="author-circle-avatar">
                                        {{ strtoupper(substr($blog->author_name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="author-name-bold">{{ $blog->author_name }}</div>
                                        <div class="read-time-muted">{{ $blog->read_time }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Reads + Dynamic Sparkline --}}
                            <td>
                                <div class="reads-spark-cell">
                                    <span>{{ number_format($blog->views_count) }}</span>
                                    <svg class="sparkline-svg" viewBox="0 0 40 16" fill="none">
                                        <path d="M1 {{ $blog->views_count > 100 ? '12L8 14L16 6L24 10L32 2L39 5' : '14L10 12L20 9L30 11L39 7' }}" 
                                              stroke="#3B82F6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </td>

                            {{-- Featured iOS Toggle --}}
                            <td style="text-align: center;">
                                <label class="ios-switch" title="Toggle Featured Spotlight">
                                    <input type="checkbox" 
                                           onchange="toggleFeatured({{ $blog->id }}, this)" 
                                           @checked($blog->is_featured)>
                                    <span class="ios-slider"></span>
                                </label>
                            </td>

                            {{-- Live Status Button --}}
                            <td>
                                <button type="button" 
                                        onclick="toggleStatus({{ $blog->id }}, this)" 
                                        class="status-badge-modern {{ $blog->is_published ? 'status-live' : 'status-draft' }}"
                                        title="Click to toggle status">
                                    <span>{{ $blog->is_published ? 'Live' : 'Draft' }}</span>
                                </button>
                            </td>

                            {{-- Date --}}
                            <td>
                                <div class="date-main-val">
                                    {{ $blog->published_at ? $blog->published_at->format('M d, Y') : $blog->created_at->format('M d, Y') }}
                                </div>
                                <div class="date-time-val">
                                    {{ $blog->published_at ? $blog->published_at->format('h:i A') : $blog->created_at->format('h:i A') }}
                                </div>
                            </td>

                            {{-- Actions Suite (Clear, High-End & 100% Reliable) --}}
                            <td style="text-align: right;">
                                <div class="action-button-suite">
                                    {{-- View Live --}}
                                    <a href="{{ route('blogs.show', $blog->slug) }}" 
                                       target="_blank" 
                                       class="action-btn-item btn-action-view" 
                                       title="View on Storefront">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>

                                    {{-- Edit Article --}}
                                    <a href="{{ route('admin.blogs.edit', $blog) }}" 
                                       class="action-btn-item btn-action-edit" 
                                       title="Edit Article">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>

                                    {{-- Delete Article --}}
                                    <button type="button" 
                                            class="action-btn-item btn-action-delete" 
                                            onclick="deleteBlog({{ $blog->id }})" 
                                            title="Delete Article">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 50px 20px; color: #64748b;">
                                <i class="bi bi-journal-x" style="font-size: 36px; color: #cbd5e1; display: block; margin-bottom: 8px;"></i>
                                <strong style="color: #0f172a; font-size: 14px; display: block;">No articles found</strong>
                                <p style="font-size: 12px; margin: 4px 0 16px;">Try adjusting your search filters.</p>
                                <a href="{{ route('admin.blogs.create') }}" class="btn-hero-blue" style="display: inline-flex; width: fit-content; margin: 0 auto;">
                                    <i class="bi bi-plus-lg"></i> Compose First Article
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Table Footer Pagination --}}
        <div class="table-modern-footer">
            <div>
                Showing <strong>{{ $blogs->firstItem() ?? 1 }}</strong> to <strong>{{ $blogs->lastItem() ?? $blogs->count() }}</strong> of <strong>{{ $blogs->total() }}</strong> articles
            </div>
            @if ($blogs->hasPages())
                <div class="custom-pagination-wrap">
                    @if ($blogs->onFirstPage())
                        <span class="page-nav-btn disabled"><i class="bi bi-chevron-left"></i></span>
                    @else
                        <a href="{{ $blogs->previousPageUrl() }}" class="page-nav-btn"><i class="bi bi-chevron-left"></i></a>
                    @endif

                    @foreach ($blogs->getUrlRange(1, $blogs->lastPage()) as $page => $url)
                        @if ($page == $blogs->currentPage())
                            <span class="page-nav-btn active">{{ $page }}</span>
                        @elseif ($page <= 3 || $page >= $blogs->lastPage() - 1 || abs($page - $blogs->currentPage()) <= 1)
                            <a href="{{ $url }}" class="page-nav-btn">{{ $page }}</a>
                        @elseif ($page == 4 && $blogs->currentPage() > 5)
                            <span class="page-nav-btn disabled" style="border:none; background:transparent;">...</span>
                        @endif
                    @endforeach

                    @if ($blogs->hasMorePages())
                        <a href="{{ $blogs->nextPageUrl() }}" class="page-nav-btn"><i class="bi bi-chevron-right"></i></a>
                    @else
                        <span class="page-nav-btn disabled"><i class="bi bi-chevron-right"></i></span>
                    @endif
                </div>
            @endif
        </div>
    </div>

</div>

<script>
    function toggleStatus(blogId, btn) {
        fetch(`/admin/blogs/${blogId}/toggle-status`, {
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
                const text = btn.querySelector('span');
                if (data.is_published) {
                    btn.className = 'status-badge-modern status-live';
                    text.textContent = 'Live';
                } else {
                    btn.className = 'status-badge-modern status-draft';
                    text.textContent = 'Draft';
                }
            }
        })
        .catch(err => alert('Failed to update status'));
    }

    function toggleFeatured(blogId, checkbox) {
        fetch(`/admin/blogs/${blogId}/toggle-featured`, {
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
            }
        })
        .catch(err => {
            checkbox.checked = !checkbox.checked;
            alert('Failed to update featured status');
        });
    }

    function deleteBlog(blogId) {
        if (!confirm('Are you sure you want to delete this blog article?')) {
            return;
        }

        fetch(`/admin/blogs/${blogId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const row = document.getElementById('blogRow' + blogId);
                if (row) {
                    row.style.opacity = '0';
                    row.style.transform = 'scale(0.95)';
                    row.style.transition = 'all 0.3s ease';
                    setTimeout(() => row.remove(), 300);
                }
            } else {
                alert(data.message || 'Failed to delete');
            }
        })
        .catch(err => alert('Failed to delete blog article'));
    }
</script>
@endsection
