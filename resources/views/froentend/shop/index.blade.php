@extends('froentend.layouts.app')
@push('seo')
    <title>{{ $meta_title ?? 'Shop All Streetwear Drops — Vayu' }}</title>
    <meta name="description" content="{{ $meta_description ?? 'Shop luxury streetwear, oversized tees, hoodies, cargos and drops.' }}">
    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">
@endpush

@section('main')
    <style>
        /* ══════════════════════════════════════════════════════════════════
           SHOP HERO & BREADCRUMB
           ══════════════════════════════════════════════════════════════════ */
        .shop-hero {
            background: linear-gradient(135deg, #001938 0%, #00285a 60%, #103b7b 100%);
            background-position: center;
            background-size: cover;
            color: #fff;
            padding: 48px 0 36px;
            position: relative;
            text-align: center;
            overflow: hidden;
        }

        .shop-hero.has-image {
            background-image: linear-gradient(135deg, rgba(0, 25, 56, .85), rgba(0, 40, 90, .75)), var(--shop-hero-image);
            min-height: 220px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .shop-hero-tag {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #ff3f6c;
            margin-bottom: 8px;
        }

        .shop-hero h1 {
            font-family: 'Cinzel', serif;
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .shop-hero p {
            font-size: 0.95rem;
            opacity: .85;
            max-width: 600px;
            margin: 0 auto;
            color: #e2e8f0;
        }

        .shop-breadcrumb {
            background: #f8fafc;
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
            font-size: 13px;
        }

        .shop-breadcrumb .container {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #64748b;
        }

        .shop-breadcrumb a {
            color: #00285a;
            text-decoration: none;
            font-weight: 600;
        }

        /* ══════════════════════════════════════════════════════════════════
           2-COLUMN SHOP LAYOUT (SIDEBAR + MAIN GRID)
           ══════════════════════════════════════════════════════════════════ */
        .shop-container {
            max-width: 1440px;
            margin: 0 auto;
            padding: 24px 20px 80px;
        }

        .shop-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 32px;
            align-items: start;
        }

        /* ══════════════════════════════════════════════════════════════════
           LEFT SIDEBAR FILTERS
           ══════════════════════════════════════════════════════════════════ */
        .shop-sidebar {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 22px;
            position: sticky;
            top: 90px;
            max-height: calc(100vh - 110px);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
            box-shadow: 0 4px 20px rgba(0, 40, 90, 0.03);
            transition: all 0.3s ease;
        }

        .shop-sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .shop-sidebar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 16px;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 18px;
        }

        .sidebar-title {
            font-size: 15px;
            font-weight: 800;
            letter-spacing: 1px;
            color: #00285a;
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
            text-transform: uppercase;
        }

        .filter-count-badge {
            background: #00285a;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 12px;
        }

        .clear-all-link {
            font-size: 12px;
            font-weight: 700;
            color: #ff3f6c;
            text-decoration: none;
            transition: .2s;
        }

        /* Filter Blocks */
        .filter-group {
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 18px;
            margin-bottom: 18px;
        }

        .filter-group:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 0;
        }

        /* ══════════════════════════════════════════════════════════════════
           ACCORDION & COLLAPSIBLE FILTER GROUPS
           ══════════════════════════════════════════════════════════════════ */
        .filter-group-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            font-weight: 800;
            color: #00285a;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 0;
            padding: 4px 0;
            cursor: pointer;
            user-select: none;
            transition: color 0.15s ease;
        }

        .filter-toggle-icon {
            font-size: 11px;
            color: #64748b;
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .filter-group.collapsed .filter-toggle-icon {
            transform: rotate(-90deg);
        }

        .filter-group-body {
            padding-top: 12px;
            transition: all 0.25s ease;
        }

        .filter-group.collapsed .filter-group-body {
            display: none;
        }

        /* Checkbox & Options lists */
        .filter-options-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .filter-checkbox-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13px;
            color: #334155;
            cursor: pointer;
            padding: 3px 0;
            transition: color 0.15s ease;
        }

        .filter-checkbox-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-checkbox-left input[type="checkbox"],
        .filter-checkbox-left input[type="radio"] {
            width: 16px;
            height: 16px;
            accent-color: #00285a;
            cursor: pointer;
            border-radius: 4px;
        }

        .filter-item-count {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            background: #f8fafc;
            padding: 2px 6px;
            border-radius: 10px;
        }

        /* ══════════════════════════════════════════════════════════════════
           PRICE RANGE SLIDER (SCREENSHOT MATCHING)
           ══════════════════════════════════════════════════════════════════ */
        .price-range-title {
            font-size: 14px;
            font-weight: 700;
            color: #00285a;
            margin-bottom: 14px;
            letter-spacing: 0.2px;
        }

        .price-range-title span {
            color: #00285a;
            font-weight: 700;
        }

        .dual-range-container {
            position: relative;
            width: 100%;
            height: 24px;
            display: flex;
            align-items: center;
            margin-bottom: 14px;
        }

        .dual-range-track {
            position: absolute;
            width: 100%;
            height: 6px;
            background: #e2e8f0;
            border-radius: 999px;
            z-index: 1;
        }

        .dual-range-fill {
            position: absolute;
            height: 6px;
            background: #00285a;
            border-radius: 999px;
            z-index: 2;
            pointer-events: none;
        }

        .dual-range-container input[type="range"] {
            position: absolute;
            width: 100%;
            height: 6px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            pointer-events: none;
            -webkit-appearance: none;
            appearance: none;
            z-index: 3;
            margin: 0;
            outline: none;
        }

        .dual-range-container input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #00285a;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 6px rgba(0, 40, 90, 0.4);
            cursor: pointer;
            pointer-events: auto;
            transition: transform 0.15s ease, background 0.15s ease;
        }.dual-range-container input[type="range"]::-webkit-slider-thumb:active {
            transform: scale(1.25);
            background: #ff3f6c;
        }

        .dual-range-container input[type="range"]::-moz-range-thumb {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #00285a;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 6px rgba(0, 40, 90, 0.4);
            cursor: pointer;
            pointer-events: auto;
        }

        .price-preset-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .price-chip {
            font-size: 11px;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 20px;
            background: #f1f5f9;
            color: #475569;
            border: 1px solid transparent;
            cursor: pointer;
            transition: .15s;
            text-decoration: none;
            display: inline-block;
        }.price-chip.active {
            background: #00285a;
            color: #fff;
            border-color: #00285a;
        }

        /* ══════════════════════════════════════════════════════════════════
           COLOR CHECKBOX WITH DOT INDICATOR
           ══════════════════════════════════════════════════════════════════ */
        .color-dot-indicator {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 1.5px solid rgba(0, 40, 90, 0.15);
            display: inline-block;
            flex-shrink: 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .color-options-list {
            max-height: 240px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
            padding-right: 4px;
        }

        .category-options-list {
            max-height: 240px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
            padding-right: 4px;
        }

        /* Tags / Fit / Fabric Pills */
        .tag-filter-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .tag-filter-pill {
            font-size: 11px;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 8px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #475569;
            cursor: pointer;
            transition: .15s;
            user-select: none;
        }

        .tag-filter-pill.active {
            background: #00285a;
            color: #fff;
            border-color: #00285a;
        }

        /* ══════════════════════════════════════════════════════════════════
           MAIN CONTENT AREA
           ══════════════════════════════════════════════════════════════════ */
        .shop-main-content {
            min-width: 0;
        }

        .shop-top-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 0 18px;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .shop-results-info {
            font-size: 14px;
            color: #64748b;
        }

        .shop-results-info strong {
            color: #00285a;
            font-size: 15px;
            font-weight: 700;
        }

        .shop-actions-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .mobile-filter-trigger {
            display: none;
            align-items: center;
            gap: 8px;
            background: #00285a;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        .sort-select-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sort-select-wrapper label {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
        }

        .custom-sort-select {
            padding: 8px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
            color: #00285a;
            background: #ffffff;
            cursor: pointer;
            outline: none;
            transition: border-color 0.2s;
        }

        .custom-sort-select:focus {
            border-color: #00285a;
        }

        /* Active Filter Chips */
        .active-chips-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .active-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            text-decoration: none;
            transition: .15s;
        }

        .active-chip i {
            font-size: 13px;
            font-weight: 900;
        }

        .clear-all-chip {
            font-size: 12px;
            font-weight: 700;
            color: #ff3f6c;
            text-decoration: underline;
            margin-left: 4px;
        }

        /* ══════════════════════════════════════════════════════════════════
           PRODUCT GRID & CARDS
           ══════════════════════════════════════════════════════════════════ */
        .shop-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 40px;
        }

        .shop-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 40, 90, 0.05);
            border: 1px solid #f1f5f9;
            transition: all .25s ease;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .shop-card-img {
            height: 380px;
            overflow: hidden;
            position: relative;
            background: #f8fafc;
        }

        .shop-card-slider-viewport {
            position: relative;
            width: 100%;
            height: 100%;
            display: block;
            overflow: hidden;
        }

        .shop-card-slide {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            visibility: hidden;
            transform: scale(1.03);
            transition: opacity 0.55s cubic-bezier(0.4, 0, 0.2, 1), transform 0.65s cubic-bezier(0.2, 0.8, 0.2, 1), visibility 0.55s;
            pointer-events: none;
            z-index: 1;
        }

        .shop-card-slide.active {
            opacity: 1;
            visibility: visible;
            transform: scale(1);
            z-index: 2;
        }

        .shop-card-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        /* Multi-Image Hover Scrubber & Auto-Slide Dots */
        .shop-card-img-dots {
            position: absolute;
            bottom: 64px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            z-index: 4;
            pointer-events: auto;
            opacity: 1;
            padding: 3px 8px;
            background: rgba(0, 0, 0, 0.28);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            border-radius: 999px;
            transition: all 0.25s ease;
            z-index: 12 !important;
        }

        .shop-img-dot {
            width: 20px;
            height: 3px;
            background: rgba(255, 255, 255, 0.45);
            border-radius: 999px;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            pointer-events: auto;
            border: 0;
            padding: 0;
        }

        .shop-img-dot.active {
            background: #ffffff;
            box-shadow: 0 0 6px rgba(255, 255, 255, 0.9);
            transform: scaleY(1.4);
        }

        /* ── CLICKABLE CARD PHOTO NAVIGATION ARROWS ── */
        .shop-card-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 14 !important;
            cursor: pointer;
            opacity: 0;
            pointer-events: auto !important;
            transition: all 0.22s cubic-bezier(0.2, 0.8, 0.2, 1);
            font-size: 15px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.3);
            padding: 0;
        }

        .shop-card-nav-btn.prev {
            left: 10px;
        }

        .shop-card-nav-btn.next {
            right: 10px;
        }

        .shop-card-nav-btn:active {
            transform: translateY(-50%) scale(0.95);
        }

        .shop-card:hover .shop-card-nav-btn,
        .shop-card:focus-within .shop-card-nav-btn,
        .shop-card:hover .shop-quick-add-btn,
        .shop-card:focus-within .shop-quick-add-btn {
            opacity: 1;
            transform: translateY(0);
        }

        .shop-card:hover .shop-card-nav-btn,
        .shop-card:focus-within .shop-card-nav-btn {
            transform: translateY(-50%);
        }

        .shop-card:hover .shop-card-slide.active img,
        .shop-card:focus-within .shop-card-slide.active img {
            transform: scale(1.04);
        }

        @media (max-width: 768px) {
            .shop-card-nav-btn {
                opacity: 0.85;
                width: 30px;
                height: 30px;
                font-size: 13px;
            }
            .shop-quick-add-btn {
                opacity: 1;
                transform: translateY(0);
                bottom: 10px;
                left: 10px;
                right: 10px;
                padding: 10px 12px;
                font-size: 11px;
            }
            .shop-card-img-dots {
                bottom: 54px;
            }
        }

        .shop-card-hover-zones {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 50px;
            display: flex;
            z-index: 3;
        }

        .shop-hover-zone {
            flex: 1;
            height: 100%;
            cursor: pointer;
        }

        .shop-card-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.5px;
            z-index: 5;
            pointer-events: none;
        }

        .shop-card-badge.new {
            background: #00285a;
            color: #fff;
        }

        .shop-card-badge.sale {
            background: #ff3f6c;
            color: #fff;
        }

        .shop-wishlist-btn {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10 !important;
            pointer-events: auto !important;
            transition: transform 0.15s, background 0.15s;
            color: #00285a;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .shop-wishlist-btn.wished {
            color: #ff3f6c;
        }

        .shop-quick-add-btn {
            position: absolute;
            bottom: 0px;
            left: 0px;
            right: 0px;
            background: rgba(0, 40, 90, 0.95);
            backdrop-filter: blur(6px);
            color: #fff;
            border: none;
            padding: 11px 16px;
            /* border-radius: 30px; */
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.8px;
            cursor: pointer;
            opacity: 0;
            transform: translateY(8px);
            transition: all 0.25s ease;
            text-align: center;
            z-index: 10 !important;
            pointer-events: auto !important;
            box-shadow: 0 6px 16px rgba(0, 40, 90, 0.25);
        }

        .shop-quick-add-btn i {
            margin-right: 6px;
        }

        .shop-quick-add-btn:disabled {
            background: rgba(100, 116, 139, 0.92);
            cursor: not-allowed;
        }

        @media (max-width: 768px) {
            .shop-quick-add-btn {
                opacity: 1;
                transform: translateY(0);
                bottom: 10px;
                left: 10px;
                right: 10px;
                padding: 10px 12px;
                font-size: 11px;
            }
            .shop-card-img-dots {
                bottom: 54px;
            }
        }

        .shop-card-info {
            padding: 14px 16px 18px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .shop-card-category {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .shop-card-name {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13.5px;
            font-weight: 700;
            color: #0f172a;
            text-decoration: none;
            margin-bottom: 8px;
            line-height: 1.35;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            transition: color 0.15s;
        }

        .shop-card-pricing {
            margin-top: auto;
            display: flex;
            align-items: baseline;
            gap: 8px;
        }

        .shop-card-price {
            font-size: 16px;
            font-weight: 800;
            color: #00285a;
        }

        .shop-card-mrp {
            font-size: 12px;
            color: #94a3b8;
            text-decoration: line-through;
        }

        .shop-card-save {
            font-size: 11px;
            font-weight: 700;
            color: #16a34a;
        }

        /* ══════════════════════════════════════════════════════════════════
           INFINITE SCROLL & LUXURY LOADER
           ══════════════════════════════════════════════════════════════════ */
        .infinite-scroll-status {
            width: 100%;
            padding: 24px 0 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .infinite-scroll-loader {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            padding: 20px;
        }

        .infinite-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #e2e8f0;
            border-top-color: #00285a;
            border-right-color: #ff3f6c;
            border-radius: 50%;
            animation: infiniteSpin 0.75s cubic-bezier(0.68, -0.55, 0.27, 1.55) infinite;
        }

        @keyframes infiniteSpin {
            to { transform: rotate(360deg); }
        }

        .infinite-loader-text {
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #00285a;
            margin: 0;
        }

        .infinite-scroll-end {
            padding: 20px 0 10px;
        }

        .end-drops-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 10px 22px;
            border-radius: 999px;
            color: #475569;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 40, 90, 0.03);
        }

        .end-drops-badge i {
            color: #16a34a;
            font-size: 16px;
        }

        .btn-load-more {
            background: #00285a;
            color: #fff;
            border: 0;
            padding: 12px 28px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(0, 40, 90, 0.2);
        }

        /* ══════════════════════════════════════════════════════════════════
           SCROLLABLE LUXURY PAGINATION (FALLBACK)
           ══════════════════════════════════════════════════════════════════ */
        .shop-pagination-wrapper {
            margin-top: 40px;
            padding-top: 24px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }

        .pagination-progress-info {
            font-size: 13px;
            color: #64748b;
            font-weight: 600;
        }

        .pagination-progress-info strong {
            color: #00285a;
        }

        .shop-pagination-scroll-container {
            max-width: 100%;
            overflow-x: auto;
            white-space: nowrap;
            padding: 6px 4px;
            display: flex;
            align-items: center;
            gap: 8px;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
            -webkit-overflow-scrolling: touch;
        }

        .shop-pagination-scroll-container::-webkit-scrollbar {
            height: 4px;
        }

        .shop-pagination-scroll-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .pagi-link,
        .pagi-current,
        .pagi-disabled {
            min-width: 38px;
            height: 38px;
            padding: 0 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
            user-select: none;
        }

        .pagi-link {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            color: #00285a;
        }

        .pagi-current {
            background: #00285a;
            color: #ffffff;
            border: 1.5px solid #00285a;
            box-shadow: 0 4px 12px rgba(0, 40, 90, 0.2);
        }

        .pagi-disabled {
            background: #f8fafc;
            border: 1.5px solid #f1f5f9;
            color: #cbd5e1;
            cursor: not-allowed;
        }

        /* ══════════════════════════════════════════════════════════════════
           MOBILE FILTER DRAWER (OFF-CANVAS)
           ══════════════════════════════════════════════════════════════════ */
        .mobile-filter-drawer-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(2px);
            z-index: 1050;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .mobile-filter-drawer-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .mobile-filter-drawer {
            position: fixed;
            top: 0;
            left: 0;
            width: 85%;
            max-width: 360px;
            height: 100%;
            background: #fff;
            z-index: 1055;
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.15);
        }

        .mobile-filter-drawer.active {
            transform: translateX(0);
        }

        .mobile-drawer-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .mobile-drawer-title {
            font-size: 16px;
            font-weight: 800;
            color: #00285a;
            margin: 0;
            text-transform: uppercase;
        }

        .mobile-drawer-close {
            background: none;
            border: none;
            font-size: 22px;
            color: #64748b;
            cursor: pointer;
        }

        .mobile-drawer-body {
            padding: 20px;
            overflow-y: auto;
            flex: 1;
        }

        .mobile-drawer-footer {
            padding: 14px 20px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            gap: 10px;
            background: #fff;
        }

        .mobile-apply-btn {
            flex: 1;
            background: #00285a;
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        .mobile-clear-btn {
            background: #f1f5f9;
            color: #475569;
            border: none;
            padding: 12px 18px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        /* ══════════════════════════════════════════════════════════════════
           RESPONSIVE BREAKPOINTS
           ══════════════════════════════════════════════════════════════════ */
        @media (max-width: 1100px) {
            .shop-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 18px;
            }
            .shop-card-img {
                height: 320px;
            }
        }

        @media (max-width: 992px) {
            .shop-layout {
                grid-template-columns: 1fr;
            }
            .shop-sidebar.desktop-sidebar {
                display: none;
            }
            .mobile-filter-trigger {
                display: inline-flex;
            }
        }

        @media (max-width: 600px) {
            .shop-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
            .shop-card {
                border-radius: 12px;
            }
            .shop-card-img {
                height: 240px;
            }
            .shop-card-info {
                padding: 10px 12px 14px;
            }
            .shop-card-name {
                font-size: 12.5px;
            }
            .shop-hero h1 {
                font-size: 1.6rem;
            }
        }
    </style>

    @php
        $shopHeroImage = $currentCategory?->banner_image_url ?? ($parentCategory?->banner_image_url ?? null);
        $activeCategories = request()->filled('category') ? (is_array(request('category')) ? request('category') : explode(',', request('category'))) : [];
        $activeSizes = request()->filled('size') ? (is_array(request('size')) ? request('size') : explode(',', request('size'))) : [];
        $activeColors = request()->filled('color') ? (is_array(request('color')) ? request('color') : explode(',', request('color'))) : [];
        $activeFits = request()->filled('fit') ? (is_array(request('fit')) ? request('fit') : explode(',', request('fit'))) : [];
        $activeFabrics = request()->filled('fabric') ? (is_array(request('fabric')) ? request('fabric') : explode(',', request('fabric'))) : [];
        $activeFiltersCount = count($activeCategories) + count($activeSizes) + count($activeColors) + count($activeFits) + count($activeFabrics) + (request()->filled('min_price') || request()->filled('max_price') ? 1 : 0) + (request()->boolean('in_stock') ? 1 : 0) + (request()->boolean('on_sale') ? 1 : 0);
    @endphp

    <div class="shop-hero {{ $shopHeroImage ? 'has-image' : '' }}"
        @if ($shopHeroImage) style="--shop-hero-image: url('{{ $shopHeroImage }}')" @endif>
        <div class="container">
            <span class="shop-hero-tag">THE DROP CULTURE • 240+ GSM</span>
            <h1>{{ $pageHeading ?? ($currentCategory ? strtoupper($currentCategory->name) : 'ALL STREETWEAR DROPS') }}</h1>
            <p>{{ $pageDescription ?? ($currentCategory->description ?? 'Discover engineered silhouettes, luxury streetwear and oversized essentials.') }}</p>
        </div>
    </div>

    <div class="shop-breadcrumb">
        <div class="container">
            <a href="{{ route('home') }}">Home</a>
            <i class="bi bi-chevron-right" style="font-size:10px"></i>
            @if ($currentCategory)
                @isset($parentCategory)
                    <a href="{{ route('shop.category', $parentCategory->slug) }}">{{ $parentCategory->name }}</a>
                    <i class="bi bi-chevron-right" style="font-size:10px"></i>
                @endisset
                <span>{{ $currentCategory->name }}</span>
            @else
                <span>Shop</span>
            @endif
        </div>
    </div>

    <div class="shop-container">
        <div class="shop-layout">
            <aside class="shop-sidebar desktop-sidebar" aria-label="Product Filters">
                <div class="sidebar-header">
                    <h3 class="sidebar-title">
                        <i class="bi bi-funnel-fill"></i> FILTERS
                        @if ($activeFiltersCount > 0)
                            <span class="filter-count-badge">{{ $activeFiltersCount }}</span>
                        @endif
                    </h3>
                    @if ($activeFiltersCount > 0)
                        <a href="{{ url()->current() }}" class="clear-all-link">Reset All</a>
                    @endif
                </div>

                {{-- 1. CATEGORIES --}}
                <div class="filter-group">
                    <div class="filter-group-header" onclick="toggleFilterGroup(this)">
                        <span>Categories</span>
                        <i class="bi bi-chevron-down filter-toggle-icon"></i>
                    </div>
                    <div class="filter-group-body">
                        <div class="filter-options-list category-options-list">
                            @foreach ($categories as $cat)
                                @php
                                    $isCatActive = in_array($cat->slug, $activeCategories) || (isset($currentCategory) && $currentCategory->id === $cat->id);
                                @endphp
                                <label class="filter-checkbox-label">
                                    <div class="filter-checkbox-left">
                                        <input type="checkbox" value="{{ $cat->slug }}"
                                            {{ $isCatActive ? 'checked' : '' }}
                                            onchange="toggleArrayFilterParam('category', '{{ $cat->slug }}')">
                                        <span>{{ $cat->name }}</span>
                                    </div>
                                    @if (isset($cat->products_count) && $cat->products_count > 0)
                                        <span class="filter-item-count">{{ $cat->products_count }}</span>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- 2. PRICE RANGE (SCREENSHOT STYLE) --}}
                <div class="filter-group">
                    <div class="filter-group-header" onclick="toggleFilterGroup(this)">
                        <span>Price Range</span>
                        <i class="bi bi-chevron-down filter-toggle-icon"></i>
                    </div>
                    <div class="filter-group-body">
                        <div class="price-range-title">
                            Price Range: <span id="desk_price_display">₹ {{ request('min_price', 199) }} - ₹ {{ request('max_price', 2999) }}</span>
                        </div>
                        <div class="dual-range-container">
                            <div class="dual-range-track"></div>
                            <div class="dual-range-fill" id="desk_price_fill"></div>
                            <input type="range" id="desk_slider_min" min="0" max="3000" step="50"
                                value="{{ request('min_price', 199) }}"
                                oninput="updatePriceSlider('desk_slider_min', 'desk_slider_max', 'desk_price_display', 'desk_price_fill')"
                                onchange="applyPriceSliderFilter('desk_slider_min', 'desk_slider_max')">
                            <input type="range" id="desk_slider_max" min="0" max="3000" step="50"
                                value="{{ request('max_price', 2999) }}"
                                oninput="updatePriceSlider('desk_slider_min', 'desk_slider_max', 'desk_price_display', 'desk_price_fill')"
                                onchange="applyPriceSliderFilter('desk_slider_min', 'desk_slider_max')">
                        </div>

                        <div class="price-preset-chips">
                            <button type="button" class="price-chip {{ request('max_price') == 499 && !request('min_price') ? 'active' : '' }}"
                                onclick="setPricePreset('', 499)">Under ₹499</button>
                            <button type="button" class="price-chip {{ request('min_price') == 499 && request('max_price') == 999 ? 'active' : '' }}"
                                onclick="setPricePreset(499, 999)">₹499 – ₹999</button>
                            <button type="button" class="price-chip {{ request('min_price') == 999 && request('max_price') == 1499 ? 'active' : '' }}"
                                onclick="setPricePreset(999, 1499)">₹999 – ₹1,499</button>
                            <button type="button" class="price-chip {{ request('min_price') == 1499 && !request('max_price') ? 'active' : '' }}"
                                onclick="setPricePreset(1499, '')">₹1,499+</button>
                        </div>
                    </div>
                </div>

                {{-- 3. SIZES --}}
                <div class="filter-group">
                    <div class="filter-group-header" onclick="toggleFilterGroup(this)">
                        <span>Size</span>
                        <i class="bi bi-chevron-down filter-toggle-icon"></i>
                    </div>
                    <div class="filter-group-body">
                        <div class="filter-options-list">
                            @foreach (['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $sizeName)
                                @php $isSizeActive = in_array($sizeName, $activeSizes); @endphp
                                <label class="filter-checkbox-label">
                                    <div class="filter-checkbox-left">
                                        <input type="checkbox" value="{{ $sizeName }}"
                                            {{ $isSizeActive ? 'checked' : '' }}
                                            onchange="toggleArrayFilterParam('size', '{{ $sizeName }}')">
                                        <span>{{ $sizeName }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- 4. COLORS WITH CHECKBOX + NAME --}}
                <div class="filter-group">
                    <div class="filter-group-header" onclick="toggleFilterGroup(this)">
                        <span>Color</span>
                        <i class="bi bi-chevron-down filter-toggle-icon"></i>
                    </div>
                    <div class="filter-group-body">
                        <div class="filter-options-list color-options-list">
                            @foreach ($allColors as $c)
                                @php
                                    $isColorActive = in_array($c->name, $activeColors);
                                    $hex = $c->hex ?? '#111111';
                                @endphp
                                <label class="filter-checkbox-label">
                                    <div class="filter-checkbox-left">
                                        <input type="checkbox" value="{{ $c->name }}"
                                            {{ $isColorActive ? 'checked' : '' }}
                                            onchange="toggleArrayFilterParam('color', '{{ $c->name }}')">
                                        <span class="color-dot-indicator" style="background-color: {{ $hex }};"></span>
                                        <span>{{ $c->name }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- 5. FIT & SILHOUETTE --}}
                <div class="filter-group">
                    <div class="filter-group-header" onclick="toggleFilterGroup(this)">
                        <span>Fit & Silhouette</span>
                        <i class="bi bi-chevron-down filter-toggle-icon"></i>
                    </div>
                    <div class="filter-group-body">
                        <div class="filter-options-list">
                            @foreach ($allFits as $fitLabel => $fitVal)
                                @php $isFitActive = in_array($fitVal, $activeFits); @endphp
                                <label class="filter-checkbox-label">
                                    <div class="filter-checkbox-left">
                                        <input type="checkbox" value="{{ $fitVal }}"
                                            {{ $isFitActive ? 'checked' : '' }}
                                            onchange="toggleArrayFilterParam('fit', '{{ $fitVal }}')">
                                        <span>{{ $fitLabel }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- 6. FABRIC & WEIGHT --}}
                <div class="filter-group">
                    <div class="filter-group-header" onclick="toggleFilterGroup(this)">
                        <span>Fabric & GSM</span>
                        <i class="bi bi-chevron-down filter-toggle-icon"></i>
                    </div>
                    <div class="filter-group-body">
                        <div class="filter-options-list">
                            @foreach ($allFabrics as $fabLabel => $fabVal)
                                @php $isFabActive = in_array($fabVal, $activeFabrics); @endphp
                                <label class="filter-checkbox-label">
                                    <div class="filter-checkbox-left">
                                        <input type="checkbox" value="{{ $fabVal }}"
                                            {{ $isFabActive ? 'checked' : '' }}
                                            onchange="toggleArrayFilterParam('fabric', '{{ $fabVal }}')">
                                        <span>{{ $fabLabel }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- 7. AVAILABILITY & SALE --}}
                <div class="filter-group">
                    <div class="filter-group-header" onclick="toggleFilterGroup(this)">
                        <span>Preferences</span>
                        <i class="bi bi-chevron-down filter-toggle-icon"></i>
                    </div>
                    <div class="filter-group-body">
                        <div class="filter-options-list">
                            <label class="filter-checkbox-label">
                                <div class="filter-checkbox-left">
                                    <input type="checkbox" value="1"
                                        {{ request()->boolean('in_stock') ? 'checked' : '' }}
                                        onchange="updateShopUrlParam('in_stock', this.checked ? '1' : '')">
                                    <span>In Stock Only</span>
                                </div>
                            </label>

                            <label class="filter-checkbox-label">
                                <div class="filter-checkbox-left">
                                    <input type="checkbox" value="1"
                                        {{ request()->boolean('on_sale') ? 'checked' : '' }}
                                        onchange="updateShopUrlParam('on_sale', this.checked ? '1' : '')">
                                    <span>Special Sale Deals</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </aside>

            <main class="shop-main-content">
                <div class="shop-top-toolbar">
                    <div class="shop-results-info">
                        Showing <strong>{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}</strong> of
                        <strong>{{ $products->total() }}</strong> Streetwear Drops
                    </div>

                    <div class="shop-actions-right">
                        <button type="button" class="mobile-filter-trigger" onclick="toggleMobileFilterDrawer(true)">
                            <i class="bi bi-sliders"></i>
                            <span>FILTERS {{ $activeFiltersCount > 0 ? "($activeFiltersCount)" : '' }}</span>
                        </button>

                        <div class="sort-select-wrapper">
                            <label for="shopSortSelect">Sort:</label>
                            <select id="shopSortSelect" class="custom-sort-select" onchange="updateShopUrlParam('sort', this.value)">
                                <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Newest Drops</option>
                                <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Best Sellers</option>
                                <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="discount" {{ request('sort') === 'discount' ? 'selected' : '' }}>Highest Savings</option>
                            </select>
                        </div>
                    </div>
                </div>

                @if ($activeFiltersCount > 0)
                    <div class="active-chips-bar">
                        <span style="font-size:12px; font-weight:700; color:#64748b;">Active Filters:</span>
                        @foreach ($activeCategories as $catVal)
                            <a href="javascript:void(0)" onclick="removeFilterParam('category', '{{ $catVal }}')" class="active-chip">
                                Category: {{ ucfirst($catVal) }} <i class="bi bi-x"></i>
                            </a>
                        @endforeach
                        @if (request()->filled('min_price') || request()->filled('max_price'))
                            <a href="javascript:void(0)" onclick="removePriceFilter()" class="active-chip">
                                Price: ₹{{ request('min_price', 0) }} - ₹{{ request('max_price', 2499) }} <i class="bi bi-x"></i>
                            </a>
                        @endif
                        @foreach ($activeSizes as $sVal)
                            <a href="javascript:void(0)" onclick="removeFilterParam('size', '{{ $sVal }}')" class="active-chip">
                                Size: {{ $sVal }} <i class="bi bi-x"></i>
                            </a>
                        @endforeach
                        @foreach ($activeColors as $cVal)
                            <a href="javascript:void(0)" onclick="removeFilterParam('color', '{{ $cVal }}')" class="active-chip">
                                Color: {{ $cVal }} <i class="bi bi-x"></i>
                            </a>
                        @endforeach
                        @foreach ($activeFits as $fVal)
                            <a href="javascript:void(0)" onclick="removeFilterParam('fit', '{{ $fVal }}')" class="active-chip">
                                Fit: {{ $fVal }} <i class="bi bi-x"></i>
                            </a>
                        @endforeach
                        @foreach ($activeFabrics as $fabVal)
                            <a href="javascript:void(0)" onclick="removeFilterParam('fabric', '{{ $fabVal }}')" class="active-chip">
                                Fabric: {{ $fabVal }} <i class="bi bi-x"></i>
                            </a>
                        @endforeach
                        @if (request()->boolean('in_stock'))
                            <a href="javascript:void(0)" onclick="removeFilterParam('in_stock', '1')" class="active-chip">
                                In Stock Only <i class="bi bi-x"></i>
                            </a>
                        @endif
                        @if (request()->boolean('on_sale'))
                            <a href="javascript:void(0)" onclick="removeFilterParam('on_sale', '1')" class="active-chip">
                                On Sale <i class="bi bi-x"></i>
                            </a>
                        @endif
                        <a href="{{ url()->current() }}" class="clear-all-chip">Clear All ({{ $activeFiltersCount }})</a>
                    </div>
                @endif

                @if ($products->count() > 0)
                    <div class="shop-grid" id="shopProductGrid">
                        @include('froentend.shop.partials.product-cards', ['products' => $products])
                    </div>

                    {{-- Infinite Scroll Status & Indicators --}}
                    <div id="infiniteScrollContainer" class="infinite-scroll-status">
                        {{-- Loading Spinner --}}
                        <div id="infiniteScrollLoader" class="infinite-scroll-loader" style="display: none;">
                            <div class="infinite-spinner"></div>
                            <p class="infinite-loader-text">Loading more drops...</p>
                        </div>

                        {{-- End of Results Message --}}
                        <div id="infiniteScrollEnd" class="infinite-scroll-end" style="{{ $products->hasMorePages() ? 'display: none;' : '' }}">
                            <div class="end-drops-badge">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>You've viewed all <strong>{{ $products->total() }}</strong> drops</span>
                            </div>
                        </div>

                        {{-- Manual Load More Button fallback --}}
                        <div id="infiniteScrollManual" style="display: none; margin-top: 16px;">
                            <button type="button" class="btn-load-more" onclick="loadNextPage()">
                                <i class="bi bi-arrow-down-circle"></i> Load More Drops
                            </button>
                        </div>
                    </div>

                    {{-- Sentinel element for IntersectionObserver --}}
                    <div id="infiniteScrollSentinel" style="height: 20px; margin-top: 10px;"></div>

                    {{-- Fallback Pagination for Non-JS / Search Engines --}}
                    <noscript>
                        @if ($products->hasPages())
                            <div class="shop-pagination-wrapper">
                                <div class="pagination-progress-info">Page <strong>{{ $products->currentPage() }}</strong> of <strong>{{ $products->lastPage() }}</strong></div>
                                <div class="shop-pagination-scroll-container">
                                    @if ($products->onFirstPage()) <span class="pagi-disabled"><i class="bi bi-chevron-left"></i></span>
                                    @else <a href="{{ $products->previousPageUrl() }}" class="pagi-link"><i class="bi bi-chevron-left"></i> Prev</a> @endif
                                    @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                        @if ($page == $products->currentPage()) <span class="pagi-current">{{ $page }}</span>
                                        @else <a href="{{ $url }}" class="pagi-link">{{ $page }}</a> @endif
                                    @endforeach
                                    @if ($products->hasMorePages()) <a href="{{ $products->nextPageUrl() }}" class="pagi-link">Next <i class="bi bi-chevron-right"></i></a>
                                    @else <span class="pagi-disabled"><i class="bi bi-chevron-right"></i></span> @endif
                                </div>
                            </div>
                        @endif
                    </noscript>
                @else
                    <div style="text-align: center; padding: 70px 20px; background:#fff; border-radius:16px; border:1px solid #e2e8f0;">
                        <i class="bi bi-search" style="font-size: 48px; color: #94a3b8; margin-bottom: 16px; display: block;"></i>
                        <h3 style="font-size: 1.25rem; font-weight:800; color:#00285a;">No Drops Found</h3>
                        <a href="{{ url()->current() }}" class="price-apply-btn" style="display:inline-block; margin-top:16px;">RESET FILTERS</a>
                    </div>
                @endif
            </main>
        </div>
    </div>

    <div class="mobile-filter-drawer-overlay" id="mobileFilterOverlay" onclick="toggleMobileFilterDrawer(false)"></div>
    <div class="mobile-filter-drawer" id="mobileFilterDrawer">
        <div class="mobile-drawer-header">
            <h3 class="mobile-drawer-title">FILTERS & REFINE</h3>
            <button type="button" class="mobile-drawer-close" onclick="toggleMobileFilterDrawer(false)">&times;</button>
        </div>
        <div class="mobile-drawer-body">
            {{-- 1. CATEGORIES --}}
            <div class="filter-group">
                <div class="filter-group-header" onclick="toggleFilterGroup(this)">
                    <span>Categories</span>
                    <i class="bi bi-chevron-down filter-toggle-icon"></i>
                </div>
                <div class="filter-group-body">
                    <div class="filter-options-list category-options-list">
                        @foreach ($categories as $cat)
                            @php
                                $isCatActive = in_array($cat->slug, $activeCategories) || (isset($currentCategory) && $currentCategory->id === $cat->id);
                            @endphp
                            <label class="filter-checkbox-label">
                                <div class="filter-checkbox-left">
                                    <input type="checkbox" value="{{ $cat->slug }}"
                                        {{ $isCatActive ? 'checked' : '' }}
                                        onchange="toggleArrayFilterParam('category', '{{ $cat->slug }}')">
                                    <span>{{ $cat->name }}</span>
                                </div>
                                @if (isset($cat->products_count) && $cat->products_count > 0)
                                    <span class="filter-item-count">{{ $cat->products_count }}</span>
                                @endif
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 2. PRICE RANGE --}}
            <div class="filter-group">
                <div class="filter-group-header" onclick="toggleFilterGroup(this)">
                    <span>Price Range</span>
                    <i class="bi bi-chevron-down filter-toggle-icon"></i>
                </div>
                <div class="filter-group-body">
                    <div class="price-range-title">
                        Price Range: <span id="mob_price_display">₹ {{ request('min_price', 199) }} - ₹ {{ request('max_price', 2999) }}</span>
                    </div>
                    <div class="dual-range-container">
                        <div class="dual-range-track"></div>
                        <div class="dual-range-fill" id="mob_price_fill"></div>
                        <input type="range" id="mob_slider_min" min="0" max="3000" step="50"
                            value="{{ request('min_price', 199) }}"
                            oninput="updatePriceSlider('mob_slider_min', 'mob_slider_max', 'mob_price_display', 'mob_price_fill')"
                            onchange="applyPriceSliderFilter('mob_slider_min', 'mob_slider_max')">
                        <input type="range" id="mob_slider_max" min="0" max="3000" step="50"
                            value="{{ request('max_price', 2999) }}"
                            oninput="updatePriceSlider('mob_slider_min', 'mob_slider_max', 'mob_price_display', 'mob_price_fill')"
                            onchange="applyPriceSliderFilter('mob_slider_min', 'mob_slider_max')">
                    </div>
                    <div class="price-preset-chips">
                        <button type="button" class="price-chip {{ request('max_price') == 499 && !request('min_price') ? 'active' : '' }}"
                            onclick="setPricePreset('', 499)">Under ₹499</button>
                        <button type="button" class="price-chip {{ request('min_price') == 499 && request('max_price') == 999 ? 'active' : '' }}"
                            onclick="setPricePreset(499, 999)">₹499 – ₹999</button>
                        <button type="button" class="price-chip {{ request('min_price') == 999 && request('max_price') == 1499 ? 'active' : '' }}"
                            onclick="setPricePreset(999, 1499)">₹999 – ₹1,499</button>
                        <button type="button" class="price-chip {{ request('min_price') == 1499 && !request('max_price') ? 'active' : '' }}"
                            onclick="setPricePreset(1499, '')">₹1,499+</button>
                    </div>
                </div>
            </div>

            {{-- 3. SIZES --}}
            <div class="filter-group">
                <div class="filter-group-header" onclick="toggleFilterGroup(this)">
                    <span>Size</span>
                    <i class="bi bi-chevron-down filter-toggle-icon"></i>
                </div>
                <div class="filter-group-body">
                    <div class="filter-options-list">
                        @foreach (['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $sizeName)
                            @php $isSizeActive = in_array($sizeName, $activeSizes); @endphp
                            <label class="filter-checkbox-label">
                                <div class="filter-checkbox-left">
                                    <input type="checkbox" value="{{ $sizeName }}"
                                        {{ $isSizeActive ? 'checked' : '' }}
                                        onchange="toggleArrayFilterParam('size', '{{ $sizeName }}')">
                                    <span>{{ $sizeName }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 4. COLORS --}}
            <div class="filter-group">
                <div class="filter-group-header" onclick="toggleFilterGroup(this)">
                    <span>Color</span>
                    <i class="bi bi-chevron-down filter-toggle-icon"></i>
                </div>
                <div class="filter-group-body">
                    <div class="filter-options-list color-options-list">
                        @foreach ($allColors as $c)
                            @php
                                $isColorActive = in_array($c->name, $activeColors);
                                $hex = $c->hex ?? '#111111';
                            @endphp
                            <label class="filter-checkbox-label">
                                <div class="filter-checkbox-left">
                                    <input type="checkbox" value="{{ $c->name }}"
                                        {{ $isColorActive ? 'checked' : '' }}
                                        onchange="toggleArrayFilterParam('color', '{{ $c->name }}')">
                                    <span class="color-dot-indicator" style="background-color: {{ $hex }};"></span>
                                    <span>{{ $c->name }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 5. FIT & SILHOUETTE --}}
            <div class="filter-group">
                <div class="filter-group-header" onclick="toggleFilterGroup(this)">
                    <span>Fit & Silhouette</span>
                    <i class="bi bi-chevron-down filter-toggle-icon"></i>
                </div>
                <div class="filter-group-body">
                    <div class="filter-options-list">
                        @foreach ($allFits as $fitLabel => $fitVal)
                            @php $isFitActive = in_array($fitVal, $activeFits); @endphp
                            <label class="filter-checkbox-label">
                                <div class="filter-checkbox-left">
                                    <input type="checkbox" value="{{ $fitVal }}"
                                        {{ $isFitActive ? 'checked' : '' }}
                                        onchange="toggleArrayFilterParam('fit', '{{ $fitVal }}')">
                                    <span>{{ $fitLabel }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 6. FABRIC & GSM --}}
            <div class="filter-group">
                <div class="filter-group-header" onclick="toggleFilterGroup(this)">
                    <span>Fabric & GSM</span>
                    <i class="bi bi-chevron-down filter-toggle-icon"></i>
                </div>
                <div class="filter-group-body">
                    <div class="filter-options-list">
                        @foreach ($allFabrics as $fabLabel => $fabVal)
                            @php $isFabActive = in_array($fabVal, $activeFabrics); @endphp
                            <label class="filter-checkbox-label">
                                <div class="filter-checkbox-left">
                                    <input type="checkbox" value="{{ $fabVal }}"
                                        {{ $isFabActive ? 'checked' : '' }}
                                        onchange="toggleArrayFilterParam('fabric', '{{ $fabVal }}')">
                                    <span>{{ $fabLabel }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="mobile-drawer-footer">
            <button type="button" class="mobile-clear-btn" onclick="window.location='{{ url()->current() }}'">Clear All</button>
            <button type="button" class="mobile-apply-btn" onclick="applyFiltersNow()">Show Results</button>
        </div>
    </div>

    <script>
        function toggleFilterGroup(headerEl) {
            const group = headerEl.closest('.filter-group');
            if (group) {
                group.classList.toggle('collapsed');
            }
        }

        // Dual Range Price Slider logic matching screenshot
        function updatePriceSlider(minInputId, maxInputId, displayId, fillId) {
            const minInput = document.getElementById(minInputId);
            const maxInput = document.getElementById(maxInputId);
            const display = document.getElementById(displayId);
            const fill = document.getElementById(fillId);

            if (!minInput || !maxInput) return;

            let minVal = parseInt(minInput.value) || 0;
            let maxVal = parseInt(maxInput.value) || 3000;

            if (minVal > maxVal - 50) {
                if (window.event && window.event.target === minInput) {
                    minInput.value = maxVal - 50;
                    minVal = maxVal - 50;
                } else {
                    maxInput.value = minVal + 50;
                    maxVal = minVal + 50;
                }
            }

            const minLimit = parseInt(minInput.min || 0);
            const maxLimit = parseInt(minInput.max || 3000);

            const leftPercent = ((minVal - minLimit) / (maxLimit - minLimit)) * 100;
            const rightPercent = ((maxVal - minLimit) / (maxLimit - minLimit)) * 100;

            if (fill) {
                fill.style.left = leftPercent + '%';
                fill.style.width = (rightPercent - leftPercent) + '%';
            }

            if (display) {
                display.textContent = `₹ ${minVal.toLocaleString('en-IN')} - ₹ ${maxVal.toLocaleString('en-IN')}`;
            }
        }

        function applyPriceSliderFilter(minInputId, maxInputId) {
            const minInput = document.getElementById(minInputId);
            const maxInput = document.getElementById(maxInputId);
            if (!minInput || !maxInput) return;

            const minVal = parseInt(minInput.value) || 0;
            const maxVal = parseInt(maxInput.value) || 3000;

            const url = new URL(window.location.href);
            if (minVal > 0) {
                url.searchParams.set('min_price', minVal);
            } else {
                url.searchParams.delete('min_price');
            }

            if (maxVal < 3000) {
                url.searchParams.set('max_price', maxVal);
            } else {
                url.searchParams.delete('max_price');
            }

            url.searchParams.delete('page');
            window.location.href = url.toString();
        }

        // Initialize sliders on page load
        document.addEventListener('DOMContentLoaded', function () {
            updatePriceSlider('desk_slider_min', 'desk_slider_max', 'desk_price_display', 'desk_price_fill');
            updatePriceSlider('mob_slider_min', 'mob_slider_max', 'mob_price_display', 'mob_price_fill');
        });
        function toggleMobileFilterDrawer(show) {
            const overlay = document.getElementById('mobileFilterOverlay');
            const drawer = document.getElementById('mobileFilterDrawer');
            if (show) { overlay.classList.add('active'); drawer.classList.add('active'); document.body.style.overflow = 'hidden'; }
            else { overlay.classList.remove('active'); drawer.classList.remove('active'); document.body.style.overflow = ''; }
        }
        function updateShopUrlParam(key, value) {
            const url = new URL(window.location.href);
            if (value === '' || value === null) url.searchParams.delete(key); else url.searchParams.set(key, value);
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }
        function toggleArrayFilterParam(key, value) {
            const url = new URL(window.location.href);
            const current = url.searchParams.get(key);
            let items = current ? current.split(',') : [];

            if (items.includes(value)) {
                items = items.filter(item => item !== value);
            } else {
                items.push(value);
            }

            if (items.length > 0) {
                url.searchParams.set(key, items.join(','));
            } else {
                url.searchParams.delete(key);
            }

            url.searchParams.delete('page');
            window.location.href = url.toString();
        }

        function removeFilterParam(key, value) {
            const url = new URL(window.location.href);
            const current = url.searchParams.get(key);
            if (!current) return;

            let items = current.split(',');
            items = items.filter(item => item !== value);

            if (items.length > 0) {
                url.searchParams.set(key, items.join(','));
            } else {
                url.searchParams.delete(key);
            }

            url.searchParams.delete('page');
            window.location.href = url.toString();
        }

        function applyPriceFilter(minId, maxId) {
            const min = document.getElementById(minId)?.value;
            const max = document.getElementById(maxId)?.value;
            const url = new URL(window.location.href);

            if (min && min > 0) {
                url.searchParams.set('min_price', min);
            } else {
                url.searchParams.delete('min_price');
            }

            if (max && max > 0) {
                url.searchParams.set('max_price', max);
            } else {
                url.searchParams.delete('max_price');
            }

            url.searchParams.delete('page');
            window.location.href = url.toString();
        }

        function removePriceFilter() {
            const url = new URL(window.location.href);
            url.searchParams.delete('min_price');
            url.searchParams.delete('max_price');
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }

        function setPricePreset(min, max) {
            const url = new URL(window.location.href);
            if (min) url.searchParams.set('min_price', min);
            else url.searchParams.delete('min_price');

            if (max) url.searchParams.set('max_price', max);
            else url.searchParams.delete('max_price');

            url.searchParams.delete('page');
            window.location.href = url.toString();
        }

        function applyFiltersNow() {
            toggleMobileFilterDrawer(false);
        }

        // ══════════════════════════════════════════════════════════════════
        // INFINITE SCROLL PAGINATION (Scroll hone par products load)
        // ══════════════════════════════════════════════════════════════════
        (function () {
            let nextPageUrl = @json($products->nextPageUrl());
            let hasMorePages = @json($products->hasMorePages());
            let isLoading = false;
            let currentPage = {{ $products->currentPage() }};
            const totalCount = {{ $products->total() }};

            const grid = document.getElementById('shopProductGrid');
            const loader = document.getElementById('infiniteScrollLoader');
            const endMsg = document.getElementById('infiniteScrollEnd');
            const sentinel = document.getElementById('infiniteScrollSentinel');
            const manualBtn = document.getElementById('infiniteScrollManual');

            async function loadNextPage() {
                if (!hasMorePages || !nextPageUrl || isLoading) return;

                isLoading = true;
                if (loader) loader.style.display = 'flex';
                if (manualBtn) manualBtn.style.display = 'none';

                try {
                    const urlObj = new URL(nextPageUrl, window.location.origin);
                    urlObj.searchParams.set('ajax', '1');

                    const response = await fetch(urlObj.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) throw new Error('Network response error');

                    const data = await response.json();

                    if (data.success && data.html && grid) {
                        const temp = document.createElement('div');
                        temp.innerHTML = data.html;
                        const newCards = Array.from(temp.children);
                        newCards.forEach((card, idx) => {
                            card.style.opacity = '0';
                            card.style.transform = 'translateY(16px)';
                            card.style.transition = 'opacity 0.3s ease ' + (idx * 0.03) + 's, transform 0.3s ease ' + (idx * 0.03) + 's';
                            grid.appendChild(card);
                            setTimeout(() => {
                                card.style.opacity = '1';
                                card.style.transform = 'translateY(0)';
                            }, 50);
                        });

                        hasMorePages = data.hasMorePages;
                        nextPageUrl = data.nextPageUrl;
                        currentPage = data.currentPage;

                        // URL bina reload ke page update karega
                        if (window.history && window.history.replaceState) {
                            const cleanUrl = new URL(window.location.href);
                            cleanUrl.searchParams.set('page', currentPage);
                            window.history.replaceState(null, '', cleanUrl.toString());
                        }

                        // Product count badge update
                        const countBadges = document.querySelectorAll('.shop-results-count');
                        countBadges.forEach(b => {
                            const loadedCount = grid.querySelectorAll('.shop-card').length;
                            b.innerHTML = `Showing <strong>${loadedCount}</strong> of <strong>${totalCount}</strong> drops`;
                        });
                    }

                    if (!hasMorePages) {
                        if (endMsg) endMsg.style.display = 'block';
                        if (sentinel) sentinel.style.display = 'none';
                    }
                } catch (err) {
                    console.error('Infinite scroll error:', err);
                    if (manualBtn && hasMorePages) manualBtn.style.display = 'block';
                } finally {
                    isLoading = false;
                    if (loader) loader.style.display = 'none';
                }
            }

            window.loadNextPage = loadNextPage;

            // IntersectionObserver for high performance smooth scroll detection
            if ('IntersectionObserver' in window && sentinel) {
                const observer = new IntersectionObserver((entries) => {
                    if (entries[0].isIntersecting && hasMorePages && !isLoading) {
                        loadNextPage();
                    }
                }, {
                    rootMargin: '350px 0px',
                    threshold: 0.01
                });

                observer.observe(sentinel);
            } else {
                // Fallback to window scroll
                window.addEventListener('scroll', () => {
                    if (isLoading || !hasMorePages) return;
                    const scrollPos = window.innerHeight + window.pageYOffset;
                    const threshold = document.documentElement.offsetHeight - 500;
                    if (scrollPos >= threshold) {
                        loadNextPage();
                    }
                }, { passive: true });
            }
        })();
    </script>
@endsection
