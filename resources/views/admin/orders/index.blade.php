{{-- resources/views/admin/orders/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Orders Management & Fulfillment')
@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

    :root {
        --orders-bg: #f8fafc;
        --orders-card-bg: #ffffff;
        --orders-border: #e2e8f0;
        --orders-navy: #00285a;
        --orders-text-main: #0f172a;
        --orders-text-muted: #64748b;
        --orders-primary: #2563eb;
        --orders-primary-hover: #1d4ed8;
    }

    .orders-page {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        display: flex;
        flex-direction: column;
        gap: 20px;
        color: var(--orders-text-main);
    }

    /* ── Top Header Toolbar ── */
    .orders-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        background: #ffffff;
        border: 1.5px solid var(--orders-border);
        border-radius: 20px;
        padding: 20px 26px;
        box-shadow: 0 4px 20px rgba(0, 40, 90, 0.03);
    }

    .orders-heading {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .orders-title-row {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .orders-title {
        font-size: 23px;
        font-weight: 800;
        color: var(--orders-navy);
        letter-spacing: -0.4px;
        margin: 0;
    }

    .orders-live-indicator {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
        padding: 3px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.3px;
    }

    .orders-pulse-dot {
        width: 7px;
        height: 7px;
        background: #2563eb;
        border-radius: 50%;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25);
        animation: pulseLive 2s infinite;
    }

    @keyframes pulseLive {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.25); opacity: 0.7; }
    }

    .orders-subtitle {
        font-size: 13px;
        color: var(--orders-text-muted);
    }

    .orders-toolbar-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-orders-action {
        height: 42px;
        border-radius: 12px;
        border: 1.5px solid #dbe4ef;
        background: #ffffff;
        color: #334155;
        padding: 0 16px;
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        text-decoration: none;
    }

    .btn-orders-action:hover {
        border-color: #2563eb;
        color: #2563eb;
        background: #eff6ff;
        transform: translateY(-1px);
    }

    .btn-orders-action.active-guide {
        background: #eff6ff;
        border-color: #2563eb;
        color: #2563eb;
    }

    .btn-orders-scanner {
        background: #f0fdf4;
        border-color: #bbf7d0;
        color: #166534;
    }
    .btn-orders-scanner:hover {
        background: #dcfce7;
        border-color: #86efac;
        color: #15803d;
    }

    .btn-orders-print {
        background: var(--orders-navy);
        border-color: var(--orders-navy);
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(0, 40, 90, 0.2);
    }

    .btn-orders-print:hover {
        background: #001f46;
        border-color: #001f46;
        color: #ffffff;
        transform: translateY(-1px);
    }

    /* ── Interactive Bento KPI Summary ── */
    .orders-summary {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 14px;
    }

    .orders-metric {
        background: #ffffff;
        border: 1.5px solid #edf2f7;
        border-radius: 18px;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 2px 10px rgba(0, 40, 90, 0.02);
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
        user-select: none;
        position: relative;
        overflow: hidden;
    }

    .orders-metric:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 40, 90, 0.06);
        border-color: #cbd5e1;
    }

    .orders-metric.active-metric-filter {
        border-color: #2563eb;
        background: #f8faff;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
    }

    .metric-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .metric-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--orders-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .metric-value {
        margin-top: 3px;
        font-size: 22px;
        line-height: 1.1;
        font-weight: 800;
        color: var(--orders-navy);
    }

    .metric-hint {
        font-size: 10.5px;
        color: #94a3b8;
        margin-top: 2px;
    }

    /* ── Order & Payment Clearance Flow Guide Component ── */
    .orders-flow-guide {
        background: #ffffff;
        border-radius: 20px;
        padding: 24px 28px;
        box-shadow: 0 4px 20px rgba(0, 40, 90, 0.04);
        border: 1.5px solid var(--orders-border);
        position: relative;
        overflow: hidden;
        margin-bottom: 2px;
        display: none;
        animation: flowSlideDown 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .orders-flow-guide.open {
        display: block;
    }
    @keyframes flowSlideDown {
        from { opacity: 0; transform: translateY(-12px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .flow-guide-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }
    .flow-guide-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--orders-navy);
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
    }
    .flow-guide-badge {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #2563eb;
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.4px;
        text-transform: uppercase;
    }
    .flow-guide-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }
    @media (max-width: 900px) {
        .flow-guide-grid { grid-template-columns: 1fr; }
    }
    .flow-card-box {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px 20px;
    }
    .flow-card-head {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        font-size: 13.5px;
        font-weight: 800;
    }
    .flow-card-head.flow-head-track { color: #2563eb; }
    .flow-card-head.flow-head-pay { color: #059669; }
    .flow-stepper-row {
        display: flex;
        align-items: center;
        gap: 6px;
        overflow-x: auto;
        padding-bottom: 6px;
    }
    .flow-step-node {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 7px 11px;
        font-size: 11.5px;
        font-weight: 700;
        color: #334155;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }
    .flow-step-node i { font-size: 13px; }
    .flow-step-arrow {
        color: #94a3b8;
        font-size: 11px;
        flex-shrink: 0;
    }
    .flow-desc-note {
        font-size: 12px;
        color: #64748b;
        margin-top: 10px;
        line-height: 1.5;
    }
    .flow-desc-note strong { color: #0f172a; }

    /* ── Main Orders Card & Table ── */
    .orders-card {
        background: #ffffff;
        border: 1.5px solid var(--orders-border);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 6px 24px rgba(0, 40, 90, 0.03);
        position: relative;
    }

    .orders-card-head {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        align-items: center;
        padding: 18px 24px;
        border-bottom: 1px solid #f1f5f9;
        flex-wrap: wrap;
        background: #ffffff;
    }

    .orders-card-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--orders-navy);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .orders-card-note {
        margin-top: 3px;
        font-size: 12px;
        color: var(--orders-text-muted);
    }

    /* Filters Toolbar */
    .orders-filter {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .order-search {
        position: relative;
        width: 270px;
    }

    .order-search i.search-icon {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 13px;
        pointer-events: none;
    }

    .order-search input {
        width: 100%;
        height: 40px;
        border: 1.5px solid #e2e8f0;
        border-radius: 11px;
        padding: 0 60px 0 36px;
        font-size: 12.5px;
        font-family: inherit;
        outline: none;
        background: #ffffff;
        color: #1e293b;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .order-search input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .search-scanner-btn {
        position: absolute;
        right: 32px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #64748b;
        font-size: 15px;
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .search-scanner-btn:hover { color: #2563eb; }

    .search-clear-btn {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #94a3b8;
        font-size: 14px;
        cursor: pointer;
        display: none;
        padding: 4px;
    }
    .search-clear-btn:hover { color: #dc2626; }

    .orders-filter select {
        height: 40px;
        border: 1.5px solid #e2e8f0;
        border-radius: 11px;
        padding: 0 12px;
        font-size: 12.5px;
        font-weight: 600;
        color: #334155;
        background: #ffffff;
        outline: none;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .orders-filter select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .btn-filter-reset {
        width: 40px;
        height: 40px;
        border: 1.5px solid #e2e8f0;
        border-radius: 11px;
        background: #ffffff;
        color: #64748b;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        transition: all 0.2s ease;
    }
    .btn-filter-reset:hover {
        border-color: #cbd5e1;
        color: #0f172a;
        background: #f8fafc;
    }

    /* Fulfillment Status Chip Strip */
    .status-strip {
        display: flex;
        gap: 8px;
        padding: 12px 24px;
        border-bottom: 1px solid #f1f5f9;
        overflow-x: auto;
        background: #ffffff;
    }

    .status-chip {
        border: 1.5px solid #edf2f7;
        background: #ffffff;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 999px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        transition: all 0.15s ease;
    }

    .status-chip:hover {
        border-color: #cbd5e1;
        color: #0f172a;
        background: #f8fafc;
    }

    .status-chip.active {
        background: var(--orders-navy);
        border-color: var(--orders-navy);
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(0, 40, 90, 0.2);
    }

    .status-chip .chip-count {
        background: rgba(0, 0, 0, 0.06);
        color: inherit;
        padding: 1px 7px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
    }

    .status-chip.active .chip-count {
        background: rgba(255, 255, 255, 0.22);
        color: #ffffff;
    }

    /* Table Component */
    .orders-table-wrap {
        position: relative;
        overflow-x: auto;
    }

    .orders-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12.5px;
    }

    .orders-table th {
        background: #f8fafc;
        color: #475569;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding: 14px 18px;
        border-bottom: 1.5px solid #e2e8f0;
        text-align: left;
        white-space: nowrap;
    }

    .orders-table td {
        padding: 14px 18px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .orders-table tbody tr {
        transition: background-color 0.15s ease;
    }

    .orders-table tbody tr:hover {
        background-color: #f8faff;
    }

    .orders-table tbody tr.is-selected {
        background-color: #eff6ff;
    }

    /* Custom Checkbox */
    .custom-check {
        width: 18px;
        height: 18px;
        cursor: pointer;
        border-radius: 5px;
        accent-color: #2563eb;
    }

    /* Order # Link & Copy */
    .order-number-wrap {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .order-number-link {
        font-size: 13.5px;
        font-weight: 800;
        color: var(--orders-navy);
        text-decoration: none;
        letter-spacing: -0.2px;
    }
    .order-number-link:hover { color: #2563eb; text-decoration: underline; }

    .btn-copy-order {
        background: none;
        border: none;
        color: #94a3b8;
        padding: 0;
        cursor: pointer;
        font-size: 12px;
        transition: color 0.15s ease;
    }
    .btn-copy-order:hover { color: #2563eb; }

    /* Product Preview Cell */
    .product-preview-cell {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 260px;
        max-width: 380px;
    }

    .order-prod-thumb-wrap {
        position: relative;
        flex-shrink: 0;
    }

    .order-prod-thumb {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        object-fit: cover;
        border: 1.5px solid #e2e8f0;
        background: #ffffff;
    }

    .order-prod-name {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.35;
        text-decoration: none;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .order-prod-name:hover { color: #2563eb; }

    .prod-spec-pills {
        display: flex;
        gap: 5px;
        align-items: center;
        flex-wrap: wrap;
        margin-top: 3px;
    }

    .pill-sku {
        font-size: 10.5px;
        font-weight: 700;
        color: #64748b;
        background: #f1f5f9;
        padding: 1px 6px;
        border-radius: 5px;
    }

    .pill-size {
        font-size: 10.5px;
        font-weight: 800;
        color: #0f172a;
        background: #e2e8f0;
        padding: 1px 6px;
        border-radius: 5px;
    }

    .pill-print {
        font-size: 10px;
        font-weight: 800;
        color: #2563eb;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        padding: 1px 5px;
        border-radius: 5px;
    }

    /* Customer Cell */
    .customer-cell {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 200px;
    }

    .customer-avatar {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, #00285a 0%, #2563eb 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13.5px;
        font-weight: 800;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(0, 40, 90, 0.15);
    }

    .customer-name {
        color: #0f172a;
        font-size: 13px;
        font-weight: 700;
    }

    .customer-phone {
        margin-top: 2px;
        color: #64748b;
        font-size: 11.5px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
    }
    .customer-phone:hover { color: #2563eb; }

    .customer-location {
        margin-top: 2px;
        color: #94a3b8;
        font-size: 11px;
        display: flex;
        align-items: center;
        gap: 3px;
    }

    /* Amount Cell */
    .amount-cell {
        color: var(--orders-navy);
        font-size: 15px;
        font-weight: 800;
        white-space: nowrap;
        letter-spacing: -0.2px;
    }

    .items-count-badge {
        font-size: 11px;
        color: #64748b;
        font-weight: 600;
    }

    /* Payment Badges (Method & Clearance Separated) */
    .payment-cell-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        align-items: flex-start;
    }

    .badge-payment-method {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 10.5px;
        font-weight: 800;
        letter-spacing: 0.4px;
        text-transform: uppercase;
    }

    .badge-method-cod {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .badge-method-prepaid {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }

    /* Clearance Badges */
    .badge-clearance {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 9px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.15s ease;
        border: 1px solid transparent;
    }

    .badge-clearance:hover {
        transform: scale(1.04);
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }

    .clearance-pending {
        background: #fffbeb;
        color: #d97706;
        border-color: #fde68a;
    }

    .clearance-paid {
        background: #ecfdf5;
        color: #059669;
        border-color: #a7f3d0;
    }

    .clearance-failed {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }

    .clearance-refunded {
        background: #faf5ff;
        color: #9333ea;
        border-color: #e9d5ff;
    }

    /* Order Fulfillment Status Pills */
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 11px;
        border-radius: 8px;
        font-size: 11.5px;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.15s ease;
        border: 1px solid transparent;
        white-space: nowrap;
    }

    .status-pill:hover {
        transform: scale(1.04);
        box-shadow: 0 3px 8px rgba(0,0,0,0.08);
    }

    .status-pending { background: #fffbeb; color: #d97706; border-color: #fde68a; }
    .status-confirmed { background: #eff6ff; color: #2563eb; border-color: #bfdbfe; }
    .status-processing { background: #e0f2fe; color: #0284c7; border-color: #bae6fd; }
    .status-shipped { background: #f5f3ff; color: #7c3aed; border-color: #ddd6fe; }
    .status-delivered { background: #ecfdf5; color: #059669; border-color: #a7f3d0; }
    .status-cancelled { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
    .status-refunded { background: #f8fafc; color: #475569; border-color: #cbd5e1; }

    /* Action Buttons */
    .action-group {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .action-btn {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: #ffffff;
        color: #475569;
        border: 1.5px solid #e2e8f0;
        transition: all 0.15s ease;
        text-decoration: none;
        cursor: pointer;
    }

    .action-btn:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #cbd5e1;
        transform: translateY(-1px);
    }

    .action-btn-thermal {
        background: #fffbeb;
        color: #b45309;
        border-color: #fde68a;
    }
    .action-btn-thermal:hover {
        background: #fef3c7;
        color: #92400e;
        border-color: #fcd34d;
    }

    .action-btn-invoice {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }
    .action-btn-invoice:hover {
        background: #fee2e2;
        color: #b91c1c;
        border-color: #fca5a5;
    }

    .action-btn-status {
        background: #eff6ff;
        color: #2563eb;
        border-color: #bfdbfe;
    }
    .action-btn-status:hover {
        background: #dbeafe;
        color: #1d4ed8;
        border-color: #93c5fd;
    }

    /* ── Table Footer & Pagination ── */
    .table-footer-bar {
        padding: 16px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        background: #ffffff;
        border-top: 1px solid #f1f5f9;
        font-size: 12.5px;
        color: var(--orders-text-muted);
    }

    .table-footer-controls {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .ajax-pagination-wrap {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-page-nav {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        padding: 0 10px;
        border-radius: 8px;
        border: 1.5px solid #e2e8f0;
        background: #ffffff;
        color: #334155;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-page-nav:hover:not(:disabled) {
        border-color: #2563eb;
        color: #2563eb;
        background: #eff6ff;
    }

    .btn-page-nav.active {
        background: var(--orders-navy);
        color: #ffffff;
        border-color: var(--orders-navy);
        box-shadow: 0 2px 6px rgba(0, 40, 90, 0.25);
    }

    .btn-page-nav:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    /* ── Floating Bulk Actions Bar ── */
    .floating-bulk-bar {
        position: fixed;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%) translateY(120px);
        background: #00285a;
        color: #ffffff;
        padding: 12px 24px;
        border-radius: 999px;
        box-shadow: 0 16px 40px rgba(0, 40, 90, 0.35);
        display: flex;
        align-items: center;
        gap: 16px;
        z-index: 1050;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .floating-bulk-bar.show {
        transform: translateX(-50%) translateY(0);
    }
    .bulk-selected-count {
        font-size: 13px;
        font-weight: 800;
        color: #93c5fd;
    }
    .btn-bulk-act {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #ffffff;
        border: none;
        border-radius: 999px;
        padding: 7px 18px;
        font-size: 12px;
        font-weight: 800;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: opacity 0.2s ease;
    }
    .btn-bulk-act:hover { opacity: 0.95; }
    .btn-bulk-act:disabled { opacity: 0.55; cursor: not-allowed; }
    .btn-bulk-print {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        color: #ffffff;
    }
    .btn-bulk-clear {
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff;
        border: none;
        border-radius: 999px;
        padding: 7px 14px;
        font-size: 11.5px;
        font-weight: 700;
        cursor: pointer;
    }

    /* ── EXECUTIVE REDESIGNED ORDER FULFILLMENT & DISPATCH STUDIO MODAL ── */
    .custom-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 20, 50, 0.65);
        backdrop-filter: blur(8px);
        z-index: 2000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        transition: opacity 0.25s ease;
    }
    .custom-modal-overlay.open {
        display: flex !important;
        opacity: 1 !important;
    }

    .custom-modal-card {
        background: #ffffff;
        border-radius: 24px;
        border: 1px solid #edf2f7;
        box-shadow: 0 30px 70px -15px rgba(0, 40, 90, 0.35);
        width: 100%;
        max-width: 980px;
        overflow: hidden;
        transform: translateY(20px) scale(0.98);
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        max-height: 94vh;
        display: flex;
        flex-direction: column;
    }
    .custom-modal-overlay.open .custom-modal-card {
        transform: translateY(0) scale(1);
    }

    .custom-modal-card form {
        display: flex;
        flex-direction: column;
        flex: 1;
        min-height: 0;
        overflow: hidden;
        margin: 0;
    }

    /* Modal Top Header */
    .custom-modal-header {
        background: #ffffff;
        padding: 18px 28px;
        color: #0f172a;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
        border-bottom: 1.5px solid #edf2f7;
        gap: 16px;
        flex-wrap: wrap;
    }

    .custom-modal-title-group {
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .custom-modal-icon-badge {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        background: linear-gradient(135deg, #00285a 0%, #2563eb 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        box-shadow: 0 4px 14px rgba(0, 40, 90, 0.2);
        flex-shrink: 0;
    }

    .custom-modal-title {
        font-size: 17.5px;
        font-weight: 800;
        color: var(--orders-navy);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: -0.3px;
    }

    .modal-order-tag {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-copy-tag {
        cursor: pointer;
        background: none;
        border: none;
        padding: 0;
        color: #2563eb;
        font-size: 12px;
        transition: transform 0.15s ease;
    }
    .btn-copy-tag:hover { transform: scale(1.15); }

    .modal-header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-modal-ext-link {
        font-size: 12px;
        font-weight: 700;
        color: #334155;
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        padding: 6px 12px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
    }
    .btn-modal-ext-link:hover {
        background: #eff6ff;
        border-color: #2563eb;
        color: #2563eb;
    }

    .btn-modal-close {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #475569;
        width: 34px;
        height: 34px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: all 0.15s ease;
    }
    .btn-modal-close:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    /* Modal Body */
    .custom-modal-body {
        padding: 22px 28px;
        overflow-y: auto;
        flex: 1;
        min-height: 0;
        background: #fbfcfe;
    }

    /* Stepper Container */
    .modal-stepper-wrap {
        background: #ffffff;
        border: 1.5px solid #edf2f7;
        border-radius: 18px;
        padding: 16px 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0, 40, 90, 0.02);
    }

    .modal-stepper-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .modal-stepper-title {
        font-size: 11.5px;
        font-weight: 800;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .modal-stepper-hint {
        font-size: 11px;
        color: #94a3b8;
        font-weight: 600;
    }

    .modal-flow-stepper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
        overflow-x: auto;
        padding: 4px 0;
    }
    .modal-flow-node {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 12px;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        white-space: nowrap;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        cursor: pointer;
        user-select: none;
    }
    .modal-flow-node:hover {
        border-color: #2563eb;
        color: #2563eb;
        background: #eff6ff;
        transform: translateY(-1px);
    }
    .modal-flow-node .node-icon-wrap {
        width: 26px;
        height: 26px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        background: #f1f5f9;
        color: #64748b;
        transition: all 0.2s ease;
    }
    .modal-flow-node .node-text-col {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
    }
    .modal-flow-node .node-title {
        font-size: 12px;
        font-weight: 800;
        color: #334155;
    }
    .modal-flow-node .node-sub {
        font-size: 10px;
        color: #94a3b8;
        font-weight: 600;
    }
    .modal-flow-node.active {
        background: #2563eb;
        border-color: #2563eb;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
    }
    .modal-flow-node.active .node-icon-wrap {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }
    .modal-flow-node.active .node-title,
    .modal-flow-node.active .node-sub {
        color: #ffffff;
    }
    .modal-flow-node.completed {
        background: #ecfdf5;
        border-color: #a7f3d0;
    }
    .modal-flow-node.completed .node-icon-wrap {
        background: #10b981;
        color: #ffffff;
    }
    .modal-flow-node.completed .node-title {
        color: #065f46;
    }
    .modal-flow-node.completed .node-sub {
        color: #059669;
    }

    .modal-flow-node-divider {
        flex: 1;
        height: 2.5px;
        background: #e2e8f0;
        min-width: 12px;
        border-radius: 99px;
        transition: background 0.25s ease;
    }
    .modal-flow-node-divider.completed {
        background: #10b981;
    }

    /* 2-Column Grid */
    .modal-studio-grid {
        display: grid;
        grid-template-columns: 360px 1fr;
        gap: 22px;
        align-items: flex-start;
    }
    @media (max-width: 860px) {
        .modal-studio-grid { grid-template-columns: 1fr; }
    }

    /* Left Studio Pane (Showcase & Details) */
    .modal-showcase-pane {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .studio-bento-card {
        background: #ffffff;
        border: 1.5px solid #edf2f7;
        border-radius: 18px;
        padding: 18px 20px;
        box-shadow: 0 2px 10px rgba(0, 40, 90, 0.02);
    }

    .studio-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f1f5f9;
    }
    .studio-card-label {
        font-size: 11px;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Items List */
    .studio-items-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
        max-height: 220px;
        overflow-y: auto;
    }
    .studio-item-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }
    .studio-item-thumb {
        width: 52px;
        height: 52px;
        border-radius: 10px;
        object-fit: cover;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        flex-shrink: 0;
    }
    .studio-item-name {
        font-size: 13px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Customer & WhatsApp Card */
    .studio-cust-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }
    .studio-cust-avatar {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, #00285a 0%, #2563eb 100%);
        color: #ffffff;
        font-size: 16px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(0, 40, 90, 0.15);
    }
    .studio-cust-name {
        font-size: 14.5px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }
    .studio-cust-phone {
        font-size: 12px;
        color: #64748b;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-top: 2px;
    }
    .studio-cust-phone:hover { color: #2563eb; }

    .studio-address-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 12px;
        font-size: 12px;
        color: #334155;
        line-height: 1.45;
        margin-bottom: 12px;
    }

    .btn-whatsapp-action {
        width: 100%;
        height: 38px;
        border-radius: 10px;
        background: #25d366;
        color: #ffffff;
        border: none;
        font-size: 12px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(37, 211, 102, 0.25);
        transition: all 0.15s ease;
    }
    .btn-whatsapp-action:hover {
        background: #20ba59;
        color: #ffffff;
        transform: translateY(-1px);
    }

    /* Financial Ledger Card */
    .studio-financial-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 5px 0;
        font-size: 12px;
        color: #64748b;
    }
    .studio-financial-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 10px;
        margin-top: 8px;
        border-top: 1.5px solid #e2e8f0;
    }
    .studio-total-price {
        font-size: 18px;
        font-weight: 900;
        color: var(--orders-navy);
    }

    /* Right Studio Pane (Dispatch Controls) */
    .modal-controls-pane {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .studio-control-card {
        background: #ffffff;
        border: 1.5px solid #edf2f7;
        border-radius: 18px;
        padding: 20px 22px;
        box-shadow: 0 2px 10px rgba(0, 40, 90, 0.02);
    }

    .studio-section-label {
        font-size: 12px;
        font-weight: 800;
        color: var(--orders-navy);
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .studio-section-sub {
        font-size: 11px;
        color: #94a3b8;
        font-weight: 600;
        text-transform: none;
    }

    .form-group-modal {
        margin-bottom: 14px;
    }

    .form-label-modal {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 11.5px;
        font-weight: 800;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .form-control-modal {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: 11px;
        font-size: 13px;
        font-family: inherit;
        font-weight: 600;
        color: #0f172a;
        outline: none;
        background: #ffffff;
        transition: all 0.15s ease;
    }

    .form-control-modal:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    /* Dynamic Hint Alert */
    .studio-rule-box {
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 11.5px;
        margin-top: 8px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        line-height: 1.45;
    }
    .studio-rule-cod {
        background: #fffbeb;
        border: 1px solid #fde68a;
        color: #92400e;
    }
    .studio-rule-prepaid {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1e40af;
    }

    /* Courier Chips Grid */
    .courier-chips-suite {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        margin-top: 8px;
    }
    .courier-badge-chip {
        background: #f8fafc;
        border: 1.5px solid #cbd5e1;
        color: #334155;
        font-size: 11.5px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.15s ease;
        user-select: none;
    }
    .courier-badge-chip:hover {
        border-color: #2563eb;
        color: #2563eb;
        background: #eff6ff;
    }
    .courier-badge-chip.selected {
        background: var(--orders-navy);
        color: #ffffff;
        border-color: var(--orders-navy);
        box-shadow: 0 2px 6px rgba(0, 40, 90, 0.2);
    }

    .btn-track-external {
        height: 40px;
        padding: 0 14px;
        border-radius: 10px;
        background: #eff6ff;
        color: #2563eb;
        border: 1.5px solid #bfdbfe;
        font-size: 12px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.15s ease;
        white-space: nowrap;
    }
    .btn-track-external:hover {
        background: #dbeafe;
        color: #1d4ed8;
    }

    /* Footer */
    .custom-modal-footer {
        padding: 16px 28px;
        background: #ffffff;
        border-top: 1.5px solid #edf2f7;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        flex-shrink: 0;
        box-shadow: 0 -4px 16px rgba(0, 40, 90, 0.03);
    }

    /* Scanner Styles */
    .scanner-mode-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 18px;
        background: #f1f5f9;
        padding: 5px;
        border-radius: 12px;
    }

    .btn-scanner-tab {
        flex: 1;
        border: none;
        background: transparent;
        color: #64748b;
        font-size: 12.5px;
        font-weight: 700;
        padding: 8px 14px;
        border-radius: 9px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    .btn-scanner-tab.active {
        background: #ffffff;
        color: var(--orders-navy);
        box-shadow: 0 2px 8px rgba(0, 40, 90, 0.08);
    }

    .scanner-gun-pane {
        background: #f8fafc;
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        padding: 24px;
        text-align: center;
    }
    .scanner-gun-pane.listening {
        border-color: #3b82f6;
        background: #eff6ff;
    }

    .scanner-gun-input {
        width: 100%;
        max-width: 380px;
        height: 44px;
        border: 2px solid #3b82f6;
        border-radius: 12px;
        padding: 0 16px;
        font-size: 15px;
        font-weight: 700;
        text-align: center;
        outline: none;
        background: #ffffff;
        color: #0f172a;
    }

    .camera-viewport-wrap {
        position: relative;
        width: 100%;
        max-width: 400px;
        height: 250px;
        margin: 0 auto 14px;
        background: #0f172a;
        border-radius: 14px;
        overflow: hidden;
    }

    .camera-laser-line {
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 2px;
        background: #ef4444;
        box-shadow: 0 0 8px #ef4444;
        animation: laserScan 2s infinite ease-in-out;
        z-index: 5;
    }

    @keyframes laserScan {
        0%, 100% { transform: translateY(-70px); }
        50% { transform: translateY(70px); }
    }

    .scanned-order-card {
        background: #ffffff;
        border: 1.5px solid #bfdbfe;
        border-radius: 16px;
        padding: 16px;
        margin-top: 16px;
        display: none;
        text-align: left;
    }
    .scanned-order-card.show { display: block; animation: flowSlideDown 0.25s ease; }

    /* Loading Overlay */
    .table-loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.82);
        backdrop-filter: blur(2px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }

    /* Toast Notification */
    .custom-toast {
        position: fixed;
        bottom: 24px;
        right: 24px;
        background: #00285a;
        color: #ffffff;
        padding: 12px 20px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        box-shadow: 0 10px 25px rgba(0, 40, 90, 0.25);
        z-index: 3000;
        display: none;
        align-items: center;
        gap: 10px;
        transform: translateY(30px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .custom-toast.show {
        display: flex;
        transform: translateY(0);
        opacity: 1;
    }
    .custom-toast.success { background: #059669; }
    .custom-toast.error { background: #dc2626; }

    @media (max-width: 1100px) {
        .orders-summary { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    }
    @media (max-width: 680px) {
        .orders-summary { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .order-search { width: 100%; }
    }
</style>

<div class="orders-page">
    
    {{-- ── Top Toolbar ── --}}
    <div class="orders-toolbar">
        <div class="orders-heading">
            <div class="orders-title-row">
                <h1 class="orders-title">Orders Management &amp; Fulfillment</h1>
                <div class="orders-live-indicator">
                    <span class="orders-pulse-dot"></span> Live Queue
                </div>
            </div>
            <div class="orders-subtitle">Track incoming purchases, COD cash remittances, batch label printing, and order dispatch status.</div>
        </div>
        <div class="orders-toolbar-actions">
            <button type="button" class="btn-orders-action" id="btnToggleFlowGuide" title="View order lifecycle and money collection flow">
                <i class="bi bi-diagram-3-fill text-primary"></i>
                <span>Fulfillment &amp; Clearance Guide</span>
            </button>
            <button type="button" class="btn-orders-action btn-orders-scanner" id="btnOpenScannerModal" title="Open Barcode & QR Scanner Studio (Press F2)">
                <i class="bi bi-upc-scan fs-6"></i>
                <span>Scan Barcode / QR (F2)</span>
            </button>
            <button type="button" class="btn-orders-action btn-orders-print" id="btnPrintCurrentPageLabels" title="Generate 4x6 inch thermal shipping labels for current page">
                <i class="bi bi-printer-fill"></i>
                <span>Print Page Labels (4x6")</span>
            </button>
            <button type="button" class="btn-orders-action" id="btnRefreshOrders" title="Refresh live orders queue">
                <i class="bi bi-arrow-clockwise"></i>
                <span>Refresh</span>
            </button>
        </div>
    </div>

    {{-- ── Interactive Bento KPI Summary Grid (Click to filter) ── --}}
    <div class="orders-summary">
        {{-- 1. Total Orders --}}
        <div class="orders-metric" id="cardKpiTotal" onclick="quickKpiFilter('all')" title="Click to view all orders">
            <div class="metric-icon" style="background:#eff6ff; color:#2563eb;">
                <i class="bi bi-bag-check-fill"></i>
            </div>
            <div>
                <div class="metric-label">Total Orders <i class="bi bi-filter"></i></div>
                <div class="metric-value" id="kpiTotalOrders">{{ number_format($totalOrders) }}</div>
                <div class="metric-hint">All records</div>
            </div>
        </div>

        {{-- 2. Pending Orders --}}
        <div class="orders-metric" id="cardKpiPending" onclick="quickKpiFilter('pending')" title="Click to filter Pending Confirmation">
            <div class="metric-icon" style="background:#fffbeb; color:#d97706;">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div>
                <div class="metric-label">Pending <i class="bi bi-filter"></i></div>
                <div class="metric-value" style="color:#d97706;" id="kpiPendingOrders">{{ number_format($statusCounts->get('pending', 0)) }}</div>
                <div class="metric-hint">Awaiting confirmation</div>
            </div>
        </div>

        {{-- 3. COD Orders --}}
        <div class="orders-metric" id="cardKpiCod" onclick="quickKpiFilter('cod')" title="Click to filter COD orders">
            <div class="metric-icon" style="background:#fffbeb; color:#b45309;">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div>
                <div class="metric-label">Cash on Delivery <i class="bi bi-filter"></i></div>
                <div class="metric-value" style="color:#b45309;" id="kpiCodOrders">{{ number_format($codCount ?? 0) }}</div>
                <div class="metric-hint">Pay on arrival</div>
            </div>
        </div>

        {{-- 4. Prepaid Orders --}}
        <div class="orders-metric" id="cardKpiPrepaid" onclick="quickKpiFilter('prepaid')" title="Click to filter Prepaid orders">
            <div class="metric-icon" style="background:#eff6ff; color:#1d4ed8;">
                <i class="bi bi-credit-card-2-front-fill"></i>
            </div>
            <div>
                <div class="metric-label">Prepaid Online <i class="bi bi-filter"></i></div>
                <div class="metric-value" style="color:#1d4ed8;" id="kpiPrepaidOrders">{{ number_format($prepaidCount ?? 0) }}</div>
                <div class="metric-hint">UPI &amp; Cards</div>
            </div>
        </div>

        {{-- 5. Delivered Orders --}}
        <div class="orders-metric" id="cardKpiDelivered" onclick="quickKpiFilter('delivered')" title="Click to filter Delivered orders">
            <div class="metric-icon" style="background:#ecfdf5; color:#059669;">
                <i class="bi bi-check2-circle"></i>
            </div>
            <div>
                <div class="metric-label">Delivered <i class="bi bi-filter"></i></div>
                <div class="metric-value" style="color:#059669;" id="kpiDeliveredOrders">{{ number_format($statusCounts->get('delivered', 0)) }}</div>
                <div class="metric-hint">Successfully fulfilled</div>
            </div>
        </div>
    </div>

    {{-- ── Interactive Order Lifecycle & Payment Clearance Flow Guide (Collapsible) ── --}}
    <div class="orders-flow-guide" id="ordersFlowGuidePane">
        <div class="flow-guide-top">
            <div>
                <h3 class="flow-guide-title">
                    <i class="bi bi-diagram-3-fill text-primary"></i>
                    <span>Order Fulfillment vs Payment Clearance Architecture</span>
                    <span class="flow-guide-badge">Standard Operating Procedure</span>
                </h3>
                <div style="font-size:12.5px; color:#64748b; margin-top:3px;">
                    Understand how physical parcel dispatch operates independently alongside financial money collection and remittances.
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-1 fw-bold" onclick="toggleFlowGuidePane()" style="border-radius:8px;">
                <i class="bi bi-chevron-up me-1"></i> Hide Guide
            </button>
        </div>

        <div class="flow-guide-grid">
            {{-- Track A: Physical Fulfillment Lifecycle --}}
            <div class="flow-card-box">
                <div class="flow-card-head flow-head-track">
                    <i class="bi bi-box-seam-fill fs-5"></i>
                    <span>TRACK A: Physical Order Fulfillment (Warehouse Lifecycle)</span>
                </div>
                <div class="flow-stepper-row">
                    <div class="flow-step-node" title="Customer placed order">
                        <i class="bi bi-bag-check-fill text-warning"></i> 1. Pending
                    </div>
                    <i class="bi bi-arrow-right flow-step-arrow"></i>
                    <div class="flow-step-node" title="Address & phone confirmed">
                        <i class="bi bi-shield-check text-primary"></i> 2. Confirmed
                    </div>
                    <i class="bi bi-arrow-right flow-step-arrow"></i>
                    <div class="flow-step-node" title="Packed in parcel & label printed">
                        <i class="bi bi-box-seam text-info"></i> 3. Packaging
                    </div>
                    <i class="bi bi-arrow-right flow-step-arrow"></i>
                    <div class="flow-step-node" title="Handed over to courier with AWB">
                        <i class="bi bi-truck text-purple" style="color:#7c3aed;"></i> 4. Shipped
                    </div>
                    <i class="bi bi-arrow-right flow-step-arrow"></i>
                    <div class="flow-step-node" title="Delivered successfully to customer">
                        <i class="bi bi-check2-circle text-success"></i> 5. Delivered
                    </div>
                </div>
                <div class="flow-desc-note">
                    <strong>Physical Fulfillment:</strong> Tracks the exact warehouse and logistics stage of the parcel. Transitioning stages triggers automated SMS, WhatsApp, and Email shipping alerts to the customer.
                </div>
            </div>

            {{-- Track B: Payment Clearance Flow --}}
            <div class="flow-card-box">
                <div class="flow-card-head flow-head-pay">
                    <i class="bi bi-cash-coin fs-5"></i>
                    <span>TRACK B: Financial Payment Clearance (Accounts Reconciliation)</span>
                </div>
                <div class="flow-stepper-row">
                    <div class="flow-step-node" title="Cash not yet received">
                        <i class="bi bi-hourglass-split text-warning"></i> Pending Clearance
                    </div>
                    <i class="bi bi-arrow-right flow-step-arrow"></i>
                    <div class="flow-step-node" title="Payment cleared & verified">
                        <i class="bi bi-check-circle-fill text-success"></i> Paid / Cash Collected
                    </div>
                    <i class="bi bi-arrow-right flow-step-arrow"></i>
                    <div class="flow-step-node" title="Failed or returned">
                        <i class="bi bi-arrow-counterclockwise text-danger"></i> Refunded
                    </div>
                </div>
                <div class="flow-desc-note">
                    <strong>Financial Reconciliation:</strong> Online prepaid transactions (UPI, Cards, NetBanking) clear instantly at checkout. For <strong>Cash on Delivery (COD)</strong>, the logistics courier collects cash at the customer doorstep and remits it to your bank within 3 to 7 business days. Decoupling fulfillment status from payment clearance guarantees accurate accounting audits.
                </div>
            </div>
        </div>
    </div>

    {{-- ── Orders Processing Queue Card & Table ── --}}
    <div class="orders-card">
        
        {{-- Card Header & Filter Controls --}}
        <div class="orders-card-head">
            <div>
                <h2 class="orders-card-title">
                    <i class="bi bi-inbox-fill text-primary"></i> Order Processing Queue
                </h2>
                <div class="orders-card-note" id="orderQueueNote">
                    Loading order records...
                </div>
            </div>

            <div class="orders-filter">
                {{-- Search Box with Clear Button & Barcode Trigger --}}
                <div class="order-search">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" id="orderSearchInput" placeholder="Order #, barcode, customer, city..." autocomplete="off">
                    <button type="button" class="search-scanner-btn" id="btnSearchScanTrigger" title="Scan Barcode / QR (F2)">
                        <i class="bi bi-upc-scan"></i>
                    </button>
                    <button type="button" class="search-clear-btn" id="btnSearchClear" title="Clear Search">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </div>

                {{-- Payment Method Filter (COD vs Prepaid) --}}
                <select id="orderMethodSelect" title="Filter by Payment Method">
                    <option value="">All Payment Types</option>
                    <option value="cod">💵 Cash on Delivery (COD)</option>
                    <option value="prepaid">💳 Prepaid Online (UPI/Cards)</option>
                </select>

                {{-- Payment Clearance Filter --}}
                <select id="orderPaymentSelect" title="Filter by Payment Clearance Status">
                    <option value="">All Clearance Statuses</option>
                    <option value="pending">⏳ Pending Clearance</option>
                    <option value="paid">✅ Paid / Cash Collected</option>
                    <option value="failed">❌ Payment Failed</option>
                    <option value="refunded">↩️ Refunded</option>
                </select>

                {{-- Sort Order --}}
                <select id="orderSortSelect" title="Sort Orders">
                    <option value="latest">Sort: Newest First</option>
                    <option value="oldest">Sort: Oldest First</option>
                    <option value="amount_desc">Sort: Highest Amount</option>
                    <option value="amount_asc">Sort: Lowest Amount</option>
                </select>

                {{-- Per Page Selector --}}
                <select id="orderPerPageSelect" title="Items per page">
                    <option value="15">15 / page</option>
                    <option value="25">25 / page</option>
                    <option value="50">50 / page</option>
                    <option value="100">100 / page</option>
                </select>

                {{-- Reset Filter Button --}}
                <button type="button" class="btn-filter-reset" id="btnResetOrders" title="Reset all filters">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </button>
            </div>
        </div>

        {{-- Fulfillment Lifecycle Filter Strip --}}
        <div class="status-strip">
            <button type="button" class="status-chip active" data-status="">
                All <span class="chip-count" id="chipCountAll">{{ number_format($totalOrders) }}</span>
            </button>
            <button type="button" class="status-chip" data-status="pending">
                Pending <span class="chip-count" id="chipCountPending">{{ number_format($statusCounts->get('pending', 0)) }}</span>
            </button>
            <button type="button" class="status-chip" data-status="confirmed">
                Confirmed <span class="chip-count" id="chipCountConfirmed">{{ number_format($statusCounts->get('confirmed', 0)) }}</span>
            </button>
            <button type="button" class="status-chip" data-status="processing">
                Packaging <span class="chip-count" id="chipCountProcessing">{{ number_format($statusCounts->get('processing', 0)) }}</span>
            </button>
            <button type="button" class="status-chip" data-status="shipped">
                Shipped <span class="chip-count" id="chipCountShipped">{{ number_format($statusCounts->get('shipped', 0)) }}</span>
            </button>
            <button type="button" class="status-chip" data-status="delivered">
                Delivered <span class="chip-count" id="chipCountDelivered">{{ number_format($statusCounts->get('delivered', 0)) }}</span>
            </button>
            <button type="button" class="status-chip" data-status="cancelled">
                Cancelled <span class="chip-count" id="chipCountCancelled">{{ number_format($statusCounts->get('cancelled', 0)) }}</span>
            </button>
        </div>

        {{-- Table Container --}}
        <div class="orders-table-wrap">
            {{-- Loading Spinner Overlay --}}
            <div class="table-loading-overlay" id="ordersLoadingOverlay">
                <div class="spinner-border text-primary" role="status" style="width:2.5rem; height:2.5rem;">
                    <span class="visually-hidden">Loading orders...</span>
                </div>
            </div>

            <table class="orders-table">
                <thead>
                    <tr>
                        <th style="width: 44px; text-align: center;">
                            <input type="checkbox" class="custom-check" id="selectAllCheckbox" title="Select All on Page">
                        </th>
                        <th style="min-width: 140px;">ORDER # &amp; TIME</th>
                        <th>PRODUCT PREVIEW</th>
                        <th>CUSTOMER &amp; DESTINATION</th>
                        <th>AMOUNT &amp; ITEMS</th>
                        <th>PAYMENT &amp; CLEARANCE</th>
                        <th>FULFILLMENT STATUS</th>
                        <th style="text-align:center; min-width: 150px;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody id="ordersTableBody">
                    {{-- Dynamically populated via AJAX --}}
                </tbody>
            </table>
        </div>

        {{-- Table Footer & Pagination --}}
        <div class="table-footer-bar">
            <div id="ordersShowingText">Showing 0 to 0 of 0 orders</div>
            
            <div class="table-footer-controls">
                <div class="ajax-pagination-wrap" id="ordersPaginationWrap">
                    {{-- Pagination buttons generated via JS --}}
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── Floating Bulk Action Bar ── --}}
<div class="floating-bulk-bar" id="floatingBulkBar">
    <div class="d-flex align-items-center gap-2">
        <i class="bi bi-check2-all text-primary fs-5"></i>
        <span class="bulk-selected-count" id="bulkSelectedCount">0 Orders Selected</span>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn-bulk-act btn-bulk-print" id="btnPrintSelectedLabels">
            <i class="bi bi-printer-fill"></i> Print Thermal Labels
        </button>
        <button type="button" class="btn-bulk-act" id="btnOpenBulkModal">
            <i class="bi bi-sliders"></i> Bulk Update Status
        </button>
        <button type="button" class="btn-bulk-clear" id="btnDeselectAll">
            Deselect
        </button>
    </div>
</div>

{{-- ── 1. SINGLE ORDER QUICK FULFILLMENT STUDIO MODAL (980px) ── --}}
<div class="custom-modal-overlay" id="singleStatusModal">
    <div class="custom-modal-card" style="max-width: 980px;">
        <div class="custom-modal-header">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <div class="modal-header-icon" style="width:38px; height:38px; border-radius:10px; background:linear-gradient(135deg,#00285a 0%,#2563eb 100%); color:#fff; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0; box-shadow: 0 2px 8px rgba(0,40,90,0.15);">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
                <div>
                    <h5 class="custom-modal-title" style="margin:0; font-size:16px; font-weight:800; color:var(--orders-navy);">
                        Order Fulfillment &amp; Dispatch Studio
                    </h5>
                    <div class="d-flex align-items-center gap-2 mt-0.5" style="font-size:12px; color:#64748b;">
                        <span>Order Ref:</span>
                        <span class="modal-order-tag" id="singleModalOrderNumBadge" style="cursor:pointer;" title="Click to copy order number" onclick="copyModalOrderNumber()">
                            <span id="singleModalOrderNumText">#0000</span>
                            <i class="bi bi-clipboard ms-1"></i>
                        </span>
                        <a href="#" id="singleModalViewDetailLink" target="_blank" class="text-primary text-decoration-none fw-bold" style="font-size:11.5px;">
                            <i class="bi bi-box-arrow-up-right me-0.5"></i> Open Full View
                        </a>
                    </div>
                </div>
            </div>
            <button type="button" class="btn-modal-close" onclick="closeCustomModal('singleStatusModal')" title="Close Studio (ESC)">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form id="singleStatusForm">
            @csrf
            <input type="hidden" id="singleModalOrderId">

            <div class="custom-modal-body" style="padding: 22px 24px;">
                
                {{-- Live Visual & Interactive Progress Stepper --}}
                <div class="modal-stepper-wrap">
                    <div class="modal-flow-stepper" id="singleModalStepper">
                        <div class="modal-flow-node" data-step="pending" onclick="setModalStatusFromStep('pending')" title="Click to transition to Pending">
                            <div class="node-icon-wrap"><i class="bi bi-hourglass-split"></i></div>
                            <div class="node-text-wrap">
                                <span class="node-title">1. Pending</span>
                                <span class="node-sub">Awaiting verification</span>
                            </div>
                        </div>
                        <div class="modal-flow-node-divider"></div>
                        <div class="modal-flow-node" data-step="confirmed" onclick="setModalStatusFromStep('confirmed')" title="Click to transition to Confirmed">
                            <div class="node-icon-wrap"><i class="bi bi-shield-check"></i></div>
                            <div class="node-text-wrap">
                                <span class="node-title">2. Confirmed</span>
                                <span class="node-sub">Order approved</span>
                            </div>
                        </div>
                        <div class="modal-flow-node-divider"></div>
                        <div class="modal-flow-node" data-step="processing" onclick="setModalStatusFromStep('processing')" title="Click to transition to Packaging">
                            <div class="node-icon-wrap"><i class="bi bi-box-seam"></i></div>
                            <div class="node-text-wrap">
                                <span class="node-title">3. Packaging</span>
                                <span class="node-sub">Packing in warehouse</span>
                            </div>
                        </div>
                        <div class="modal-flow-node-divider"></div>
                        <div class="modal-flow-node" data-step="shipped" onclick="setModalStatusFromStep('shipped')" title="Click to transition to Shipped">
                            <div class="node-icon-wrap"><i class="bi bi-truck"></i></div>
                            <div class="node-text-wrap">
                                <span class="node-title">4. Shipped</span>
                                <span class="node-sub">In-transit with courier</span>
                            </div>
                        </div>
                        <div class="modal-flow-node-divider"></div>
                        <div class="modal-flow-node" data-step="delivered" onclick="setModalStatusFromStep('delivered')" title="Click to transition to Delivered">
                            <div class="node-icon-wrap"><i class="bi bi-check-circle-fill"></i></div>
                            <div class="node-text-wrap">
                                <span class="node-title">5. Delivered</span>
                                <span class="node-sub">Delivered to recipient</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Studio Bento Grid --}}
                <div class="modal-studio-grid">
                    
                    {{-- Left Studio Column: Showcase & Details --}}
                    <div class="modal-showcase-pane">
                        
                        {{-- 1. Ordered Products Card --}}
                        <div class="studio-bento-card">
                            <div class="studio-card-head">
                                <span class="studio-card-label">
                                    <i class="bi bi-bag-check-fill text-primary"></i> Ordered Articles
                                </span>
                                <span class="badge bg-light text-dark border px-2 py-0.5 fw-bold" id="singleModalItemsCountBadge" style="font-size:11px;">0 Items</span>
                            </div>
                            <div class="studio-items-list" id="singleModalItemsList">
                                {{-- Dynamically populated via JS --}}
                            </div>
                        </div>

                        {{-- 2. Customer Destination Card & WhatsApp Link --}}
                        <div class="studio-bento-card">
                            <div class="studio-card-head">
                                <span class="studio-card-label">
                                    <i class="bi bi-geo-alt-fill text-danger"></i> Customer Destination
                                </span>
                                <span class="badge bg-primary-subtle text-primary border px-2 py-0.5 font-xxs fw-bold">Verified Contact</span>
                            </div>
                            
                            <div class="studio-cust-header">
                                <div class="studio-cust-avatar" id="singleModalCustAvatar">G</div>
                                <div>
                                    <strong class="studio-cust-name d-block" id="singleModalCustomerName">Customer Name</strong>
                                    <a href="tel:" class="studio-cust-phone" id="singleModalCustomerPhone">
                                        <i class="bi bi-telephone-fill text-primary"></i> <span id="singleModalCustomerPhoneText">+91 0000000000</span>
                                    </a>
                                </div>
                            </div>

                            <div class="studio-address-box" id="singleModalCustomerAddress">
                                Loading shipping destination...
                            </div>

                            <a href="#" target="_blank" class="btn-whatsapp-action" id="singleModalWhatsAppBtn">
                                <i class="bi bi-whatsapp fs-6"></i> Chat with Customer on WhatsApp
                            </a>
                        </div>

                        {{-- 3. Financial Ledger Snapshot Card --}}
                        <div class="studio-bento-card">
                            <div class="studio-card-head">
                                <span class="studio-card-label">
                                    <i class="bi bi-receipt text-success"></i> Financial Ledger
                                </span>
                                <div id="singleModalMethodBadge"></div>
                            </div>

                            <div class="studio-financial-row">
                                <span>Subtotal</span>
                                <strong id="singleModalSubtotal">₹0</strong>
                            </div>
                            <div class="studio-financial-row" id="singleModalDiscountRow" style="display:none;">
                                <span>Discount / Coupon</span>
                                <strong class="text-success" id="singleModalDiscount">-₹0</strong>
                            </div>
                            <div class="studio-financial-row">
                                <span>Shipping Charge</span>
                                <strong id="singleModalShipping">Free</strong>
                            </div>
                            <div class="studio-financial-total">
                                <span style="font-size:12px; font-weight:800; color:#334155; text-transform:uppercase; letter-spacing:0.5px;">Grand Total</span>
                                <strong class="studio-total-price" id="singleModalOrderAmount">₹0</strong>
                            </div>
                        </div>

                    </div>

                    {{-- Right Studio Column: Dispatch & Logistics Controls --}}
                    <div class="modal-controls-pane">
                        
                        {{-- 1. Fulfillment Stage Card --}}
                        <div class="studio-control-card">
                            <div class="studio-section-label">
                                <span><i class="bi bi-diagram-3-fill text-primary me-1.5"></i> 1. Fulfillment Stage</span>
                                <span class="studio-section-sub">Warehouse &amp; Logistics Step</span>
                            </div>
                            <div class="form-group-modal mb-0">
                                <label class="form-label-modal">
                                    <span>Active Order Status</span>
                                    <span class="text-muted fw-normal" style="font-size:11px;">Triggers shipping notification</span>
                                </label>
                                <select id="singleModalStatus" class="form-control-modal" onchange="syncModalStepper(this.value)">
                                    <option value="pending">🟡 1. Pending Confirmation</option>
                                    <option value="confirmed">🔵 2. Confirmed &amp; Verified</option>
                                    <option value="processing">📦 3. Packaging (Label Printed &amp; Packed)</option>
                                    <option value="shipped">🚚 4. Shipped / In-Transit (Dispatched)</option>
                                    <option value="delivered">🟢 5. Delivered to Customer</option>
                                    <option value="cancelled">🔴 Cancelled</option>
                                    <option value="refunded">⚪ Refunded</option>
                                </select>
                            </div>
                        </div>

                        {{-- 2. Payment Clearance Card --}}
                        <div class="studio-control-card">
                            <div class="studio-section-label">
                                <span><i class="bi bi-cash-coin text-success me-1.5"></i> 2. Payment Clearance</span>
                                <span class="studio-section-sub">Accounts Reconciliation</span>
                            </div>
                            <div class="form-group-modal mb-2">
                                <label class="form-label-modal">
                                    <span>Settlement Clearance</span>
                                    <span class="text-muted fw-normal" style="font-size:11px;">Independent of fulfillment</span>
                                </label>
                                <select id="singleModalPaymentStatus" class="form-control-modal" onchange="updatePaymentAdvice()">
                                    <option value="pending">⏳ Pending Clearance (Awaiting settlement / cash)</option>
                                    <option value="paid">✅ Paid / Cash Collected (Verified in bank)</option>
                                    <option value="failed">❌ Payment Failed / Refused</option>
                                    <option value="refunded">↩️ Refunded to Customer</option>
                                </select>
                            </div>

                            {{-- Dynamic Advice Callout --}}
                            <div id="singleModalPaymentAdvice" class="studio-rule-box studio-rule-cod">
                                <i class="bi bi-info-circle-fill fs-6 flex-shrink-0 mt-0.5"></i>
                                <div id="singleModalPaymentAdviceText">
                                    <strong>COD Reconciliation:</strong> Logistics courier will collect cash upon doorstep delivery and remit to your bank in 3-7 days. Mark as <em>Paid</em> once bank remittance arrives.
                                </div>
                            </div>
                        </div>

                        {{-- 3. Logistics Courier & Live Tracking Card --}}
                        <div class="studio-control-card">
                            <div class="studio-section-label">
                                <span><i class="bi bi-truck text-purple me-1.5" style="color:#7c3aed;"></i> 3. Courier &amp; Live Tracking</span>
                                <span class="studio-section-sub">Airway Bill Assignment</span>
                            </div>

                            <div class="form-group-modal">
                                <label class="form-label-modal">
                                    <span>Logistics Courier Partner</span>
                                    <span class="text-muted fw-normal" style="font-size:11px;">Select chip or type custom</span>
                                </label>
                                <input type="text" id="singleModalCourier" class="form-control-modal" placeholder="e.g. Delhivery, Blue Dart, Shiprocket..." oninput="onCourierInputChange()">
                                
                                <div class="courier-chips-suite" id="singleModalCourierChips">
                                    <span class="courier-badge-chip" data-courier="Delhivery" onclick="setQuickCourier('Delhivery')">Delhivery</span>
                                    <span class="courier-badge-chip" data-courier="Blue Dart" onclick="setQuickCourier('Blue Dart')">Blue Dart</span>
                                    <span class="courier-badge-chip" data-courier="Shiprocket" onclick="setQuickCourier('Shiprocket')">Shiprocket</span>
                                    <span class="courier-badge-chip" data-courier="DTDC" onclick="setQuickCourier('DTDC')">DTDC</span>
                                    <span class="courier-badge-chip" data-courier="India Post" onclick="setQuickCourier('India Post')">India Post</span>
                                    <span class="courier-badge-chip" data-courier="Xpressbees" onclick="setQuickCourier('Xpressbees')">Xpressbees</span>
                                    <span class="courier-badge-chip" data-courier="Shadowfax" onclick="setQuickCourier('Shadowfax')">Shadowfax</span>
                                </div>
                            </div>

                            <div class="form-group-modal" style="margin-bottom:0;">
                                <label class="form-label-modal">
                                    <span>Airway Bill (AWB) / Tracking Number</span>
                                    <span class="text-muted fw-normal" style="font-size:11px;">Provided by courier</span>
                                </label>
                                <div class="d-flex gap-2">
                                    <input type="text" id="singleModalTracking" class="form-control-modal" placeholder="e.g. 128934509124..." oninput="onTrackingInputChange()">
                                    <a href="#" target="_blank" id="singleModalTrackBtn" class="btn-track-external" style="display:none;" title="Track package in new window">
                                        <i class="bi bi-geo-fill"></i> Track
                                    </a>
                                </div>
                                <div id="singleModalTrackHint" style="font-size:11px; color:#64748b; margin-top:6px; display:none;">
                                    <i class="bi bi-info-circle me-1"></i> Live tracking link ready for <span id="singleModalTrackCourierName" class="fw-bold"></span>.
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <div class="custom-modal-footer">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <a href="" id="singleModalLabelBtn" target="_blank" class="btn btn-sm btn-light border fw-bold px-3 py-2" style="border-radius:10px; text-decoration:none; color:#334155;">
                        <i class="bi bi-tag-fill text-warning me-1"></i> 4x6" Thermal Sticker
                    </a>
                    <a href="" id="singleModalPdfBtn" target="_blank" class="btn btn-sm btn-light border fw-bold px-3 py-2" style="border-radius:10px; text-decoration:none; color:#334155;">
                        <i class="bi bi-file-earmark-pdf-fill text-danger me-1"></i> Tax Invoice PDF
                    </a>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-light btn-sm fw-bold px-3 py-2" style="border-radius: 10px; border: 1.5px solid #e2e8f0; cursor:pointer;" onclick="closeCustomModal('singleStatusModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold px-4 py-2" id="btnSingleModalSubmit" style="border-radius: 10px; background: linear-gradient(135deg, #00285a 0%, #2563eb 100%); border:none; color:#ffffff; cursor:pointer;">
                        <i class="bi bi-check-circle-fill me-1"></i> Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ── 2. BATCH ORDER BULK PROCESSING MODAL (750px) ── --}}
<div class="custom-modal-overlay" id="bulkStatusModal">
    <div class="custom-modal-card" style="max-width: 750px;">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title">
                <i class="bi bi-sliders text-primary"></i> Bulk Batch Order Processing
            </h5>
            <button type="button" class="btn-modal-close" onclick="closeCustomModal('bulkStatusModal')">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <form id="bulkStatusForm">
            @csrf
            <div class="custom-modal-body">
                <div style="padding: 16px 20px; margin-bottom: 20px; border-radius: 16px; background:#eff6ff; border:1.5px solid #bfdbfe; display:flex; align-items:center; gap:14px;">
                    <div style="width:42px; height:42px; border-radius:12px; background:var(--orders-navy); color:#fff; display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0;">
                        <i class="bi bi-check2-all"></i>
                    </div>
                    <div>
                        <strong class="d-block" id="bulkModalCountText" style="font-size:15px; color:var(--orders-navy);">Updating 0 Selected Orders</strong>
                        <span class="text-muted" style="font-size:12px;">This batch action will update fulfillment and payment clearance status simultaneously for all selected records.</span>
                    </div>
                </div>

                <div class="row g-3">
                    {{-- Target Fulfillment Status --}}
                    <div class="col-md-6">
                        <div class="form-group-modal">
                            <label class="form-label-modal">
                                <span>Target Fulfillment Status</span>
                            </label>
                            <select id="bulkModalStatus" class="form-control-modal">
                                <option value="confirmed">🔵 Confirmed &amp; Verified</option>
                                <option value="processing">📦 Packaging (In Preparation)</option>
                                <option value="shipped">🚚 Shipped / Dispatched</option>
                                <option value="delivered">🟢 Delivered to Customer</option>
                                <option value="cancelled">🔴 Cancelled</option>
                                <option value="pending">🟡 Pending Confirmation</option>
                                <option value="refunded">⚪ Refunded</option>
                            </select>
                        </div>
                    </div>

                    {{-- Target Payment Clearance --}}
                    <div class="col-md-6">
                        <div class="form-group-modal">
                            <label class="form-label-modal">
                                <span>Payment Clearance Action</span>
                            </label>
                            <select id="bulkModalPaymentStatus" class="form-control-modal">
                                <option value="">Keep Existing Payment Status</option>
                                <option value="paid">✅ Mark as Paid / Cash Collected</option>
                                <option value="pending">⏳ Mark as Pending Clearance</option>
                                <option value="refunded">↩️ Mark as Refunded</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Courier Partner (Optional) --}}
                <div class="form-group-modal" style="margin-bottom:0; margin-top:10px;">
                    <label class="form-label-modal">
                        <span>Assign Courier Partner (Optional)</span>
                    </label>
                    <input type="text" id="bulkModalCourier" class="form-control-modal" placeholder="e.g. Delhivery, Blue Dart, Shiprocket">
                    <div class="quick-chip-group">
                        <span class="quick-courier-chip" onclick="setBulkQuickCourier('Delhivery')">Delhivery</span>
                        <span class="quick-courier-chip" onclick="setBulkQuickCourier('Blue Dart')">Blue Dart</span>
                        <span class="quick-courier-chip" onclick="setBulkQuickCourier('Shiprocket')">Shiprocket</span>
                        <span class="quick-courier-chip" onclick="setBulkQuickCourier('DTDC')">DTDC</span>
                        <span class="quick-courier-chip" onclick="setBulkQuickCourier('India Post')">India Post</span>
                    </div>
                </div>
            </div>
            <div class="custom-modal-footer">
                <button type="button" class="btn btn-light btn-sm fw-bold px-4 py-2" style="border-radius: 10px; border: 1.5px solid #e2e8f0; cursor:pointer;" onclick="closeCustomModal('bulkStatusModal')">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm fw-bold px-4 py-2" id="btnBulkModalSubmit" style="border-radius: 10px; background: linear-gradient(135deg, #00285a 0%, #2563eb 100%); border:none; color:#ffffff; cursor:pointer;">
                    <i class="bi bi-check-all me-1"></i> Apply to All Selected
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── 3. LIVE BARCODE & QR DISPATCH SCANNER STUDIO MODAL (640px) ── --}}
<div class="custom-modal-overlay" id="barcodeScannerModal">
    <div class="custom-modal-card" style="max-width: 640px;">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title">
                <i class="bi bi-upc-scan text-primary"></i>
                <span>Live Barcode &amp; QR Dispatch Scanner</span>
            </h5>
            <button type="button" class="btn-modal-close" onclick="closeScannerModal()" title="Close (ESC)">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="custom-modal-body" style="padding: 22px;">
            {{-- Mode Switch Tabs --}}
            <div class="scanner-mode-tabs">
                <button type="button" class="btn-scanner-tab active" id="tabModeGun" onclick="switchScannerMode('gun')">
                    <i class="bi bi-upc"></i> USB Barcode Gun (Laser)
                </button>
                <button type="button" class="btn-scanner-tab" id="tabModeCamera" onclick="switchScannerMode('camera')">
                    <i class="bi bi-camera-video"></i> Camera Live Scanner
                </button>
            </div>

            {{-- Mode 1: USB Gun View --}}
            <div id="paneModeGun" class="scanner-gun-pane listening">
                <div class="d-flex align-items-center justify-content-center gap-2 font-xs fw-bold text-primary mb-1">
                    <span class="orders-pulse-dot"></span> SCANNER GUN LISTENING
                </div>
                <div style="font-size:12px; color:#64748b; margin-bottom:8px;">
                    Trigger your handheld laser scanner gun on any shipping sticker or barcode.
                </div>
                <input type="text" id="scannerGunInput" class="scanner-gun-input" placeholder="Point scanner &amp; pull trigger..." autocomplete="off">
                <div class="d-flex align-items-center justify-content-between mt-2 font-xxs text-muted">
                    <span>Press <strong>Enter</strong> or gun trigger</span>
                    <button type="button" class="btn btn-sm btn-link text-decoration-none font-xs p-0 text-primary fw-bold" onclick="testScanSample()">Test with sample order</button>
                </div>
            </div>

            {{-- Mode 2: Camera Live View --}}
            <div id="paneModeCamera" style="display:none; text-align:center;">
                <div class="camera-viewport-wrap" id="cameraViewportWrap">
                    <div class="camera-laser-line"></div>
                    <div id="cameraReader" style="width:100%; height:100%;"></div>
                </div>
                <div class="d-flex align-items-center justify-content-center gap-2">
                    <button type="button" class="btn btn-sm btn-primary fw-bold px-3 py-1.5" id="btnToggleCamera" onclick="toggleCameraScanner()" style="border-radius:8px;">
                        <i class="bi bi-camera-fill me-1"></i> Start Camera Scan
                    </button>
                </div>
                <div class="font-xs text-muted mt-2">Align parcel barcode or QR code inside the camera view.</div>
            </div>

            {{-- Matched Order Result Card --}}
            <div class="scanned-order-card" id="scannedOrderResultCard">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-success-subtle text-success border px-2 py-1 fw-bold" style="border-radius:6px;">
                            <i class="bi bi-check-circle-fill me-1"></i> PARCEL MATCHED
                        </span>
                        <strong class="text-navy" id="scanResultOrderNum" style="font-size:14px; color:var(--orders-navy);"></strong>
                    </div>
                    <div id="scanResultPayBadge"></div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <img src="" id="scanResultProdImg" style="width:50px; height:50px; border-radius:10px; object-fit:cover; border:1.5px solid #e2e8f0;">
                    <div style="flex:1;">
                        <div id="scanResultProdName" style="font-size:13px; font-weight:800; color:#0f172a; line-height:1.3;"></div>
                        <div class="d-flex align-items-center gap-2 font-xs text-muted mt-0.5" id="scanResultProdMeta"></div>
                    </div>
                    <div class="text-end">
                        <strong id="scanResultAmount" style="font-size:15px; color:#00285a; font-weight:800;"></strong>
                        <div class="font-xxs text-muted" id="scanResultItemsCount"></div>
                    </div>
                </div>

                <div class="bg-light p-2 rounded mt-2 border d-flex justify-content-between align-items-center font-xs">
                    <div>
                        <i class="bi bi-person-fill text-muted"></i> <strong id="scanResultCustomer"></strong>
                        <span class="text-muted ms-1" id="scanResultLocation"></span>
                    </div>
                    <div id="scanResultStatusPill"></div>
                </div>

                {{-- Fast 1-Click Warehouse Dispatch Actions --}}
                <div class="pt-3 mt-2 border-top">
                    <div class="font-xs fw-bold text-muted text-uppercase mb-2" style="letter-spacing:0.5px;">1-Click Quick Dispatch Action:</div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <button type="button" class="btn btn-sm btn-primary fw-bold px-3 py-1.5" onclick="quickUpdateScannedStatus('processing')" style="border-radius:8px; background:#0284c7; border:none;">
                            <i class="bi bi-box-seam me-1"></i> Pack
                        </button>
                        <button type="button" class="btn btn-sm btn-primary fw-bold px-3 py-1.5" onclick="quickUpdateScannedStatus('shipped')" style="border-radius:8px; background:#7c3aed; border:none;">
                            <i class="bi bi-truck me-1"></i> Ship
                        </button>
                        <button type="button" class="btn btn-sm btn-success fw-bold px-3 py-1.5" onclick="quickUpdateScannedStatus('delivered')" style="border-radius:8px;">
                            <i class="bi bi-check2-circle me-1"></i> Delivered
                        </button>
                        <a href="" id="scanResultLabelBtn" target="_blank" class="btn btn-sm btn-light border fw-bold px-3 py-1.5" style="border-radius:8px;">
                            <i class="bi bi-tag-fill text-warning me-1"></i> 4x6 Label
                        </a>
                        <a href="" id="scanResultDetailsBtn" class="btn btn-sm btn-light border fw-bold px-3 py-1.5" style="border-radius:8px;">
                            <i class="bi bi-arrow-right-short"></i> View
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Custom Toast Notification --}}
<div class="custom-toast" id="customToast">
    <i class="bi bi-check-circle-fill fs-5" id="toastIcon"></i>
    <span id="toastMessage">Action completed successfully!</span>
</div>

{{-- ── Interactive JavaScript Engine ── --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentPage = 1;
    let currentStatus = '';
    let searchTimeout = null;
    let loadedOrdersMap = {};
    let selectedOrderIds = new Set();

    const tableBody = document.getElementById('ordersTableBody');
    const loadingOverlay = document.getElementById('ordersLoadingOverlay');
    const showingText = document.getElementById('ordersShowingText');
    const paginationWrap = document.getElementById('ordersPaginationWrap');
    const queueNote = document.getElementById('orderQueueNote');

    const searchInput = document.getElementById('orderSearchInput');
    const btnSearchClear = document.getElementById('btnSearchClear');
    const methodSelect = document.getElementById('orderMethodSelect');
    const paymentSelect = document.getElementById('orderPaymentSelect');
    const sortSelect = document.getElementById('orderSortSelect');
    const perPageSelect = document.getElementById('orderPerPageSelect');
    const btnReset = document.getElementById('btnResetOrders');

    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const floatingBulkBar = document.getElementById('floatingBulkBar');
    const bulkSelectedCount = document.getElementById('bulkSelectedCount');
    const btnDeselectAll = document.getElementById('btnDeselectAll');
    const btnOpenBulkModal = document.getElementById('btnOpenBulkModal');
    const btnPrintSelectedLabels = document.getElementById('btnPrintSelectedLabels');
    const btnPrintCurrentPageLabels = document.getElementById('btnPrintCurrentPageLabels');
    const btnRefreshOrders = document.getElementById('btnRefreshOrders');
    const bulkLabelsBaseUrl = @json(route('admin.orders.shipping-labels'));

    // Toast Function
    const toast = document.getElementById('customToast');
    const toastMessage = document.getElementById('toastMessage');
    const toastIcon = document.getElementById('toastIcon');
    let toastTimer = null;

    function showToast(msg, isError = false) {
        clearTimeout(toastTimer);
        toast.className = 'custom-toast ' + (isError ? 'error' : 'success');
        toastMessage.textContent = msg;
        toastIcon.className = isError ? 'bi bi-exclamation-circle-fill fs-5' : 'bi bi-check-circle-fill fs-5';
        toast.classList.add('show');
        toastTimer = setTimeout(() => {
            toast.classList.remove('show');
        }, 3500);
    }

    // Modal Control Functions
    window.openCustomModal = function(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('open');
        }
    };

    window.closeCustomModal = function(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('open');
        }
    };

    window.setQuickCourier = function(name) {
        const input = document.getElementById('singleModalCourier');
        if (input) {
            input.value = name;
            if (typeof syncCourierChips === 'function') syncCourierChips(name);
            if (typeof updateLiveTrackingButton === 'function') updateLiveTrackingButton();
        }
    };

    window.setBulkQuickCourier = function(name) {
        document.getElementById('bulkModalCourier').value = name;
    };

    // Close on overlay backdrop click
    document.querySelectorAll('.custom-modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('open');
            }
        });
    });

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.custom-modal-overlay.open').forEach(modal => {
                modal.classList.remove('open');
            });
        }
    });

    // Copy to clipboard helper
    window.copyToClipboard = function(text, label) {
        navigator.clipboard.writeText(text).then(() => {
            showToast(`${label || 'Text'} copied to clipboard!`);
        }).catch(() => {
            showToast('Failed to copy', true);
        });
    };

    // Status mapping configuration
    const statusBadges = {
        'pending': { cls: 'status-pending', icon: '<i class="bi bi-hourglass-split"></i>', text: 'Pending' },
        'confirmed': { cls: 'status-confirmed', icon: '<i class="bi bi-shield-check"></i>', text: 'Confirmed' },
        'processing': { cls: 'status-processing', icon: '<i class="bi bi-box-seam"></i>', text: 'Packaging' },
        'shipped': { cls: 'status-shipped', icon: '<i class="bi bi-truck"></i>', text: 'Shipped' },
        'delivered': { cls: 'status-delivered', icon: '<i class="bi bi-check-circle-fill"></i>', text: 'Delivered' },
        'cancelled': { cls: 'status-cancelled', icon: '<i class="bi bi-x-circle-fill"></i>', text: 'Cancelled' },
        'refunded': { cls: 'status-refunded', icon: '<i class="bi bi-arrow-counterclockwise"></i>', text: 'Refunded' },
    };

    const clearanceBadges = {
        'paid': { cls: 'clearance-paid', icon: '<i class="bi bi-check-circle-fill"></i>', text: 'Paid / Cleared' },
        'pending': { cls: 'clearance-pending', icon: '<i class="bi bi-clock-history"></i>', text: 'Pending Clearance' },
        'failed': { cls: 'clearance-failed', icon: '<i class="bi bi-x-octagon"></i>', text: 'Failed' },
        'refunded': { cls: 'clearance-refunded', icon: '<i class="bi bi-arrow-return-left"></i>', text: 'Refunded' },
        'unpaid': { cls: 'clearance-pending', icon: '<i class="bi bi-dash-circle"></i>', text: 'Pending' },
    };

    // Fetch orders via AJAX
    function fetchOrders(page = 1) {
        currentPage = page;
        loadingOverlay.style.display = 'flex';

        const params = new URLSearchParams({
            page: page,
            per_page: perPageSelect.value,
            search: searchInput.value.trim(),
            status: currentStatus,
            method: methodSelect.value,
            payment: paymentSelect.value,
            sort: sortSelect.value
        });

        // Toggle clear search button
        btnSearchClear.style.display = searchInput.value.trim().length > 0 ? 'block' : 'none';

        fetch(`{{ route('admin.orders.ajax') }}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            loadingOverlay.style.display = 'none';
            if (data.success) {
                loadedOrdersMap = {};
                data.orders.forEach(o => loadedOrdersMap[o.id] = o);
                renderTable(data.orders);
                renderPagination(data.pagination);
                updateKpis(data.kpis);
                syncSelectionUI();
            }
        })
        .catch(err => {
            loadingOverlay.style.display = 'none';
            console.error('Orders AJAX error:', err);
            tableBody.innerHTML = `<tr><td colspan="8" class="text-center py-4 text-danger font-xs">Failed to load orders. Please refresh page.</td></tr>`;
        });
    }

    // Render Table Rows
    function renderTable(orders) {
        if (!orders || orders.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="8">
                        <div class="empty-state text-center py-5">
                            <i class="bi bi-bag-x text-muted" style="font-size:3rem;"></i>
                            <div class="fw-bold mt-2" style="font-size:15px; color:var(--orders-navy);">No matching orders found</div>
                            <div class="text-muted font-xs mt-1">Try resetting search keyword, payment filter, or status tabs.</div>
                            <button type="button" class="btn btn-sm btn-primary mt-3 fw-bold px-3 py-1.5" onclick="document.getElementById('btnResetOrders').click()" style="border-radius:8px; background:var(--orders-navy); border-color:var(--orders-navy);">
                                Reset All Filters
                            </button>
                        </div>
                    </td>
                </tr>`;
            return;
        }

        let html = '';
        orders.forEach(order => {
            const st = statusBadges[order.status] || { cls: 'status-refunded', icon: '', text: order.status };
            const clr = clearanceBadges[order.payment_status] || { cls: 'clearance-pending', icon: '', text: order.payment_status };
            const initial = (order.shipping_name || 'G').charAt(0).toUpperCase();
            const isChecked = selectedOrderIds.has(order.id) ? 'checked' : '';

            html += `
                <tr data-order-id="${order.id}">
                    <td style="text-align:center;">
                        <input type="checkbox" class="custom-check order-row-cb" data-id="${order.id}" ${isChecked}>
                    </td>
                    <td>
                        <div class="order-number-wrap">
                            <div class="d-flex align-items-center gap-1.5">
                                <a href="${order.show_url}" class="order-number-link">
                                    #${escapeHtml(order.order_number)}
                                </a>
                                <button type="button" class="btn-copy-order" onclick="copyToClipboard('${escapeHtml(order.order_number)}', 'Order Number')" title="Copy Order Number">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </div>
                            <div class="text-muted" style="font-size:11px;">
                                <i class="bi bi-calendar3"></i> ${order.created_date} &bull; ${order.created_time}
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="product-preview-cell">
                            <div class="order-prod-thumb-wrap">
                                <a href="${order.show_url}">
                                    <img src="${order.item_image}" class="order-prod-thumb" alt="${escapeHtml(order.item_name)}" onerror="this.src='{{ asset('assets/images/placeholder.png') }}'">
                                </a>
                            </div>
                            <div>
                                <a href="${order.show_url}" class="order-prod-name">${escapeHtml(order.item_name)}</a>
                                <div class="prod-spec-pills">
                                    <span class="pill-sku">${escapeHtml(order.item_sku)}</span>
                                    ${order.item_size ? `<span class="pill-size">Size: ${escapeHtml(order.item_size)}</span>` : ''}
                                    ${order.item_design_side ? `<span class="pill-print"><i class="bi bi-aspect-ratio me-1"></i>${escapeHtml(order.item_design_side.toUpperCase())} PRINT</span>` : ''}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="customer-cell">
                            <div class="customer-avatar">${initial}</div>
                            <div>
                                <strong class="customer-name d-block">${escapeHtml(order.shipping_name)}</strong>
                                <a href="tel:${escapeHtml(order.shipping_phone)}" class="customer-phone">
                                    <i class="bi bi-telephone"></i> ${escapeHtml(order.shipping_phone)}
                                </a>
                                <div class="customer-location">
                                    <i class="bi bi-geo-alt"></i> ${escapeHtml(order.shipping_city || '—')}, ${escapeHtml(order.shipping_state || '')}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="amount-cell">${order.formatted_amount}</span>
                        <div class="items-count-badge mt-0.5">
                            <i class="bi bi-bag"></i> ${order.items_count} ${order.items_count === 1 ? 'item' : 'items'}
                        </div>
                    </td>
                    <td>
                        <div class="payment-cell-group">
                            ${order.is_cod 
                                ? `<span class="badge-payment-method badge-method-cod"><i class="bi bi-cash-stack"></i> CASH ON DELIVERY</span>`
                                : `<span class="badge-payment-method badge-method-prepaid"><i class="bi bi-credit-card-2-front-fill"></i> PREPAID (${escapeHtml(order.payment_method)})</span>`
                            }
                            <span class="badge-clearance ${clr.cls}" onclick="openSingleStatusModal(${order.id})" title="Click to update clearance &amp; status">
                                ${clr.icon} ${clr.text}
                            </span>
                        </div>
                    </td>
                    <td>
                        <span class="status-pill ${st.cls}" onclick="openSingleStatusModal(${order.id})" title="Click to change order status">
                            ${st.icon} ${st.text} <i class="bi bi-pencil-fill ms-1 font-xxs opacity-75"></i>
                        </span>
                    </td>
                    <td style="text-align:center">
                        <div class="action-group">
                            <a href="/admin/orders/${order.id}/shipping-label" target="_blank" class="action-btn action-btn-thermal" title="Print Parcel Sticker (4x6 Thermal)">
                                <i class="bi bi-tag-fill font-xs"></i>
                            </a>
                            <a href="/admin/orders/${order.id}/invoice" target="_blank" class="action-btn action-btn-invoice" title="Download Tax Invoice PDF">
                                <i class="bi bi-file-earmark-pdf-fill font-xs"></i>
                            </a>
                            <button type="button" class="action-btn action-btn-status" onclick="openSingleStatusModal(${order.id})" title="Fulfillment &amp; Clearance Studio">
                                <i class="bi bi-sliders"></i>
                            </button>
                            <a href="${order.show_url}" class="action-btn" title="View Full Order Details">
                                <i class="bi bi-arrow-right-short fs-5"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            `;
        });

        tableBody.innerHTML = html;
        attachCheckboxListeners();
    }

    // Render Pagination Controls
    function renderPagination(pg) {
        if (!pg) return;

        showingText.textContent = `Showing ${pg.from} to ${pg.to} of ${pg.total} orders`;
        queueNote.textContent = `Showing ${pg.from}-${pg.to} of ${pg.total} orders in processing queue`;

        if (pg.last_page <= 1) {
            paginationWrap.innerHTML = '';
            return;
        }

        let html = '';
        html += `<button type="button" class="btn-page-nav" ${pg.current_page === 1 ? 'disabled' : ''} data-page="${pg.current_page - 1}" title="Previous Page">‹</button>`;

        for (let i = 1; i <= pg.last_page; i++) {
            if (i === 1 || i === pg.last_page || (i >= pg.current_page - 2 && i <= pg.current_page + 2)) {
                html += `<button type="button" class="btn-page-nav ${i === pg.current_page ? 'active' : ''}" data-page="${i}">${i}</button>`;
            } else if (i === pg.current_page - 3 || i === pg.current_page + 3) {
                html += `<span class="px-1 text-muted">...</span>`;
            }
        }

        html += `<button type="button" class="btn-page-nav" ${pg.current_page === pg.last_page ? 'disabled' : ''} data-page="${pg.current_page + 1}" title="Next Page">›</button>`;

        paginationWrap.innerHTML = html;

        paginationWrap.querySelectorAll('.btn-page-nav').forEach(btn => {
            btn.addEventListener('click', function() {
                const targetPage = parseInt(this.getAttribute('data-page'));
                if (targetPage && targetPage !== currentPage) {
                    fetchOrders(targetPage);
                }
            });
        });
    }

    // Update KPI Card Numbers
    function updateKpis(kpis) {
        if (!kpis) return;
        if (document.getElementById('kpiTotalOrders')) document.getElementById('kpiTotalOrders').textContent = Number(kpis.total || 0).toLocaleString();
        if (document.getElementById('kpiPendingOrders')) document.getElementById('kpiPendingOrders').textContent = Number(kpis.pending || 0).toLocaleString();
        if (document.getElementById('kpiCodOrders')) document.getElementById('kpiCodOrders').textContent = Number(kpis.cod || 0).toLocaleString();
        if (document.getElementById('kpiPrepaidOrders')) document.getElementById('kpiPrepaidOrders').textContent = Number(kpis.prepaid || 0).toLocaleString();
        if (document.getElementById('kpiDeliveredOrders')) document.getElementById('kpiDeliveredOrders').textContent = Number(kpis.delivered || 0).toLocaleString();

        // Update status chips count
        if (document.getElementById('chipCountAll')) document.getElementById('chipCountAll').textContent = Number(kpis.total || 0).toLocaleString();
        if (document.getElementById('chipCountPending')) document.getElementById('chipCountPending').textContent = Number(kpis.pending || 0).toLocaleString();
        if (document.getElementById('chipCountConfirmed')) document.getElementById('chipCountConfirmed').textContent = Number(kpis.confirmed || 0).toLocaleString();
        if (document.getElementById('chipCountProcessing')) document.getElementById('chipCountProcessing').textContent = Number(kpis.processing || 0).toLocaleString();
        if (document.getElementById('chipCountShipped')) document.getElementById('chipCountShipped').textContent = Number(kpis.shipped || 0).toLocaleString();
        if (document.getElementById('chipCountDelivered')) document.getElementById('chipCountDelivered').textContent = Number(kpis.delivered || 0).toLocaleString();
        if (document.getElementById('chipCountCancelled')) document.getElementById('chipCountCancelled').textContent = Number(kpis.cancelled || 0).toLocaleString();
    }

    // Quick KPI Click-to-Filter Engine
    window.quickKpiFilter = function(type) {
        document.querySelectorAll('.orders-metric').forEach(m => m.classList.remove('active-metric-filter'));
        
        if (type === 'all') {
            btnReset.click();
            document.getElementById('cardKpiTotal').classList.add('active-metric-filter');
            return;
        }

        if (type === 'pending' || type === 'delivered') {
            methodSelect.value = '';
            paymentSelect.value = '';
            currentStatus = type;
            document.querySelectorAll('.status-chip').forEach(c => {
                c.classList.toggle('active', c.getAttribute('data-status') === type);
            });
            document.getElementById(type === 'pending' ? 'cardKpiPending' : 'cardKpiDelivered').classList.add('active-metric-filter');
            fetchOrders(1);
            return;
        }

        if (type === 'cod' || type === 'prepaid') {
            currentStatus = '';
            document.querySelectorAll('.status-chip').forEach(c => {
                c.classList.toggle('active', c.getAttribute('data-status') === '');
            });
            methodSelect.value = type;
            paymentSelect.value = '';
            document.getElementById(type === 'cod' ? 'cardKpiCod' : 'cardKpiPrepaid').classList.add('active-metric-filter');
            fetchOrders(1);
            return;
        }
    };

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // ── Checkbox Selection Engine ──
    function openBulkLabels(ids) {
        const cleanIds = [...new Set((ids || []).map(id => parseInt(id)).filter(Boolean))];
        if (!cleanIds.length) {
            showToast('Please select at least one order to print labels.', true);
            return;
        }
        window.open(`${bulkLabelsBaseUrl}?ids=${encodeURIComponent(cleanIds.join(','))}`, '_blank');
    }

    function attachCheckboxListeners() {
        const rowCheckboxes = document.querySelectorAll('.order-row-cb');
        rowCheckboxes.forEach(cb => {
            const existingRow = cb.closest('tr');
            if (existingRow) {
                existingRow.classList.toggle('is-selected', cb.checked);
            }

            cb.addEventListener('change', function() {
                const id = parseInt(this.getAttribute('data-id'));
                if (this.checked) {
                    selectedOrderIds.add(id);
                } else {
                    selectedOrderIds.delete(id);
                }
                const row = this.closest('tr');
                if (row) {
                    row.classList.toggle('is-selected', this.checked);
                }
                syncSelectionUI();
            });
        });
    }

    selectAllCheckbox.addEventListener('change', function() {
        const rowCheckboxes = document.querySelectorAll('.order-row-cb');
        rowCheckboxes.forEach(cb => {
            const id = parseInt(cb.getAttribute('data-id'));
            cb.checked = selectAllCheckbox.checked;
            if (selectAllCheckbox.checked) {
                selectedOrderIds.add(id);
            } else {
                selectedOrderIds.delete(id);
            }
            const row = cb.closest('tr');
            if (row) {
                row.classList.toggle('is-selected', cb.checked);
            }
        });
        syncSelectionUI();
    });

    btnDeselectAll.addEventListener('click', function() {
        selectedOrderIds.clear();
        selectAllCheckbox.checked = false;
        document.querySelectorAll('.order-row-cb').forEach(cb => {
            cb.checked = false;
            const row = cb.closest('tr');
            if (row) {
                row.classList.remove('is-selected');
            }
        });
        syncSelectionUI();
    });

    if (btnPrintSelectedLabels) {
        btnPrintSelectedLabels.addEventListener('click', function() {
            openBulkLabels(Array.from(selectedOrderIds));
        });
    }

    if (btnPrintCurrentPageLabels) {
        btnPrintCurrentPageLabels.addEventListener('click', function() {
            openBulkLabels(Object.keys(loadedOrdersMap));
        });
    }

    if (btnRefreshOrders) {
        btnRefreshOrders.addEventListener('click', function() {
            fetchOrders(currentPage);
        });
    }

    function syncSelectionUI() {
        const count = selectedOrderIds.size;
        bulkSelectedCount.textContent = `${count} Order${count === 1 ? '' : 's'} Selected`;

        if (btnPrintSelectedLabels) {
            btnPrintSelectedLabels.disabled = count === 0;
        }
        if (btnOpenBulkModal) {
            btnOpenBulkModal.disabled = count === 0;
        }

        if (count > 0) {
            floatingBulkBar.classList.add('show');
        } else {
            floatingBulkBar.classList.remove('show');
        }

        const rowCheckboxes = document.querySelectorAll('.order-row-cb');
        let allChecked = rowCheckboxes.length > 0;
        rowCheckboxes.forEach(cb => {
            const id = parseInt(cb.getAttribute('data-id'));
            cb.checked = selectedOrderIds.has(id);
            const row = cb.closest('tr');
            if (row) {
                row.classList.toggle('is-selected', cb.checked);
            }
            if (!selectedOrderIds.has(id)) {
                allChecked = false;
            }
        });
        selectAllCheckbox.checked = allChecked && rowCheckboxes.length > 0;
    }

    // ── Interactive Stepper in Single Order Modal ──
    const statusStepOrder = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
    window.syncModalStepper = function(status) {
        const targetIndex = statusStepOrder.indexOf(status);
        const stepper = document.getElementById('singleModalStepper');
        if (!stepper) return;

        const nodes = stepper.querySelectorAll('.modal-flow-node');
        const dividers = stepper.querySelectorAll('.modal-flow-node-divider');

        nodes.forEach((node, idx) => {
            node.classList.remove('active', 'completed');
            if (targetIndex !== -1) {
                if (idx < targetIndex) {
                    node.classList.add('completed');
                } else if (idx === targetIndex) {
                    node.classList.add('active');
                }
            }
        });

        dividers.forEach((div, idx) => {
            div.classList.toggle('completed', targetIndex !== -1 && idx < targetIndex);
        });
    };

    // 1-Click Interactive Step Setter
    window.setModalStatusFromStep = function(stepName) {
        const select = document.getElementById('singleModalStatus');
        if (select) {
            select.value = stepName;
            syncModalStepper(stepName);
            if (stepName === 'shipped') {
                const courierEl = document.getElementById('singleModalCourier');
                if (courierEl && !courierEl.value) {
                    courierEl.focus();
                }
            }
        }
    };

    // Copy Modal Order Number
    window.copyModalOrderNumber = function() {
        const textEl = document.getElementById('singleModalOrderNumText');
        if (textEl) {
            const rawNum = textEl.textContent.replace('#', '').trim();
            copyToClipboard(rawNum, 'Order Number');
        }
    };

    // Courier Chips & Live Tracking Engine
    window.syncCourierChips = function(courierName) {
        const chips = document.querySelectorAll('#singleModalCourierChips .courier-badge-chip');
        const target = (courierName || '').trim().toLowerCase();
        chips.forEach(chip => {
            const val = (chip.getAttribute('data-courier') || '').trim().toLowerCase();
            chip.classList.toggle('selected', Boolean(target && val === target));
        });
    };

    window.onCourierInputChange = function() {
        const courier = (document.getElementById('singleModalCourier').value || '').trim();
        syncCourierChips(courier);
        updateLiveTrackingButton();
    };

    window.onTrackingInputChange = function() {
        updateLiveTrackingButton();
    };

    window.updateLiveTrackingButton = function() {
        const courier = (document.getElementById('singleModalCourier').value || '').trim();
        const awb = (document.getElementById('singleModalTracking').value || '').trim();
        const btnTrack = document.getElementById('singleModalTrackBtn');
        const hintTrack = document.getElementById('singleModalTrackHint');
        const courierNameSpan = document.getElementById('singleModalTrackCourierName');

        if (!awb) {
            if (btnTrack) btnTrack.style.display = 'none';
            if (hintTrack) hintTrack.style.display = 'none';
            return;
        }

        let trackingUrl = '';
        const courierLower = courier.toLowerCase();

        if (courierLower.includes('delhivery')) {
            trackingUrl = `https://www.delhivery.com/track/package/${encodeURIComponent(awb)}`;
        } else if (courierLower.includes('blue dart') || courierLower.includes('bluedart')) {
            trackingUrl = `https://www.bluedart.com/tracking`;
        } else if (courierLower.includes('shiprocket')) {
            trackingUrl = `https://shiprocket.co/tracking/${encodeURIComponent(awb)}`;
        } else if (courierLower.includes('dtdc')) {
            trackingUrl = `https://www.dtdc.in/tracking/shipment-tracking.asp`;
        } else if (courierLower.includes('india post') || courierLower.includes('speed post')) {
            trackingUrl = `https://www.indiapost.gov.in/_layouts/15/dpt.cept.tracking/trackconsignment.aspx`;
        } else if (courierLower.includes('xpressbees')) {
            trackingUrl = `https://www.xpressbees.com/shipment/tracking`;
        } else if (courierLower.includes('shadowfax')) {
            trackingUrl = `https://tracker.shadowfax.in/`;
        } else {
            trackingUrl = `https://www.google.com/search?q=${encodeURIComponent((courier ? courier + ' ' : '') + 'tracking ' + awb)}`;
        }

        if (btnTrack) {
            btnTrack.href = trackingUrl;
            btnTrack.style.display = 'inline-flex';
        }
        if (hintTrack && courierNameSpan) {
            courierNameSpan.textContent = courier || 'Courier Partner';
            hintTrack.style.display = 'block';
        }
    };

    // Dynamic Payment Advice Box
    let currentModalOrder = null;
    window.updatePaymentAdvice = function() {
        if (!currentModalOrder) return;
        const payStatus = document.getElementById('singleModalPaymentStatus').value;
        const adviceBox = document.getElementById('singleModalPaymentAdvice');
        const adviceText = document.getElementById('singleModalPaymentAdviceText');
        if (!adviceBox || !adviceText) return;

        if (currentModalOrder.is_cod) {
            if (payStatus === 'paid') {
                adviceBox.className = 'studio-rule-box studio-rule-prepaid';
                adviceText.innerHTML = `<strong>COD Cash Reconciled:</strong> Marked as <em>Paid</em>. Verify that door-step cash collected by the courier partner matches your bank remittance.`;
            } else {
                adviceBox.className = 'studio-rule-box studio-rule-cod';
                adviceText.innerHTML = `<strong>COD Settlement Rule:</strong> Logistics courier will collect cash upon doorstep delivery and remit to your bank in 3-7 business days. Keep as <em>Pending Clearance</em> until cash arrives in your bank.`;
            }
        } else {
            if (payStatus === 'paid') {
                adviceBox.className = 'studio-rule-box studio-rule-prepaid';
                adviceText.innerHTML = `<strong>Prepaid Verified:</strong> Customer completed payment (${escapeHtml(currentModalOrder.payment_method)}) during checkout. Funds are cleared.`;
            } else {
                adviceBox.className = 'studio-rule-box studio-rule-cod';
                adviceText.innerHTML = `<strong>Prepaid Note:</strong> Order is marked as <em>${escapeHtml(payStatus)}</em>. Verify online gateway transaction ID before re-dispatching.`;
            }
        }
    };

    // ── Open Single Order Quick Status Modal ──
    window.openSingleStatusModal = function(orderId) {
        const order = loadedOrdersMap[orderId];
        if (!order) return;
        currentModalOrder = order;

        document.getElementById('singleModalOrderId').value = order.id;
        document.getElementById('singleModalOrderNumText').textContent = `#${order.order_number}`;
        
        const detailLink = document.getElementById('singleModalViewDetailLink');
        if (detailLink) detailLink.href = order.show_url;

        // Render Ordered Articles List
        const itemsListEl = document.getElementById('singleModalItemsList');
        const itemsCountBadge = document.getElementById('singleModalItemsCountBadge');
        if (itemsCountBadge) {
            itemsCountBadge.textContent = `${order.items_count} Article${order.items_count === 1 ? '' : 's'}`;
        }

        if (itemsListEl) {
            if (order.items_list && order.items_list.length > 0) {
                let itemsHtml = '';
                order.items_list.forEach(item => {
                    const printSideHtml = item.design_side ? `<span class="pill-print" style="font-size:10px;"><i class="bi bi-aspect-ratio me-1"></i>${escapeHtml(item.design_side.toUpperCase())}</span>` : '';
                    const sizeHtml = item.size ? `<span class="pill-size" style="font-size:10px;">Size: ${escapeHtml(item.size)}</span>` : '';
                    const colorHtml = item.color ? `<span class="badge bg-light text-secondary border px-1.5 py-0.5" style="font-size:10px;">${escapeHtml(item.color)}</span>` : '';
                    
                    itemsHtml += `
                        <div class="studio-item-row">
                            <img src="${item.image}" class="studio-item-thumb" alt="${escapeHtml(item.name)}" onerror="this.src='{{ asset('assets/images/placeholder.png') }}'">
                            <div style="flex:1; min-width:0;">
                                <div class="studio-item-name" title="${escapeHtml(item.name)}">${escapeHtml(item.name)}</div>
                                <div class="prod-spec-pills mt-1" style="gap:4px;">
                                    <span class="pill-sku" style="font-size:10px;">${escapeHtml(item.sku)}</span>
                                    ${sizeHtml}
                                    ${colorHtml}
                                    ${printSideHtml}
                                </div>
                            </div>
                            <div style="text-align:right; flex-shrink:0;">
                                <div style="font-size:13px; font-weight:800; color:#0f172a;">${item.price}</div>
                                <div class="text-muted font-xs">Qty: <strong>${item.quantity}</strong></div>
                            </div>
                        </div>
                    `;
                });
                itemsListEl.innerHTML = itemsHtml;
            } else {
                itemsListEl.innerHTML = `
                    <div class="studio-item-row">
                        <img src="${order.item_image}" class="studio-item-thumb" alt="${escapeHtml(order.item_name)}" onerror="this.src='{{ asset('assets/images/placeholder.png') }}'">
                        <div style="flex:1; min-width:0;">
                            <div class="studio-item-name">${escapeHtml(order.item_name)}</div>
                            <div class="prod-spec-pills mt-1">
                                <span class="pill-sku">${escapeHtml(order.item_sku)}</span>
                                ${order.item_size ? `<span class="pill-size">Size: ${escapeHtml(order.item_size)}</span>` : ''}
                            </div>
                        </div>
                        <div style="text-align:right; flex-shrink:0;">
                            <div style="font-size:13px; font-weight:800; color:#0f172a;">${order.formatted_amount}</div>
                            <div class="text-muted font-xs">Qty: <strong>${order.items_count}</strong></div>
                        </div>
                    </div>
                `;
            }
        }

        // Customer & Destination Card
        const initial = (order.shipping_name || 'G').charAt(0).toUpperCase();
        document.getElementById('singleModalCustAvatar').textContent = initial;
        document.getElementById('singleModalCustomerName').textContent = order.shipping_name;
        
        const custPhoneText = document.getElementById('singleModalCustomerPhoneText');
        const custPhoneLink = document.getElementById('singleModalCustomerPhone');
        if (custPhoneText) custPhoneText.textContent = order.shipping_phone || 'Not provided';
        if (custPhoneLink) custPhoneLink.href = order.shipping_phone ? `tel:${order.shipping_phone}` : '#';

        const addressParts = [
            order.shipping_address,
            order.shipping_city,
            order.shipping_state,
            order.shipping_pincode ? `PIN: ${order.shipping_pincode}` : ''
        ].filter(Boolean);
        const fullAddress = addressParts.length > 0 ? addressParts.join(', ') : 'Destination address details not provided.';
        document.getElementById('singleModalCustomerAddress').textContent = fullAddress;

        // WhatsApp Direct Link
        const cleanPhone = (order.shipping_phone || '').replace(/[^0-9]/g, '');
        const waBtn = document.getElementById('singleModalWhatsAppBtn');
        if (waBtn) {
            if (cleanPhone.length >= 10) {
                const formattedWaPhone = cleanPhone.length === 10 ? '91' + cleanPhone : cleanPhone;
                const waMsg = encodeURIComponent(`Hello ${order.shipping_name}, regarding your order #${order.order_number} from The Trend...`);
                waBtn.href = `https://wa.me/${formattedWaPhone}?text=${waMsg}`;
                waBtn.style.display = 'inline-flex';
            } else {
                waBtn.style.display = 'none';
            }
        }

        // Financial Ledger Snapshot
        document.getElementById('singleModalSubtotal').textContent = order.subtotal || order.formatted_amount;
        
        const discRow = document.getElementById('singleModalDiscountRow');
        const discVal = document.getElementById('singleModalDiscount');
        if (order.discount_amount) {
            if (discVal) discVal.textContent = `-${order.discount_amount}`;
            if (discRow) discRow.style.display = 'flex';
        } else {
            if (discRow) discRow.style.display = 'none';
        }

        document.getElementById('singleModalShipping').textContent = order.shipping_charge || 'Free';
        document.getElementById('singleModalOrderAmount').textContent = order.formatted_amount;

        const methodBadgeHtml = order.is_cod 
            ? `<span class="badge-payment-method badge-method-cod"><i class="bi bi-cash-stack"></i> CASH ON DELIVERY</span>`
            : `<span class="badge-payment-method badge-method-prepaid"><i class="bi bi-credit-card-2-front-fill"></i> PREPAID</span>`;
        document.getElementById('singleModalMethodBadge').innerHTML = methodBadgeHtml;

        // Controls
        document.getElementById('singleModalStatus').value = order.status;
        document.getElementById('singleModalPaymentStatus').value = order.payment_status;
        document.getElementById('singleModalCourier').value = order.courier_name || '';
        document.getElementById('singleModalTracking').value = order.tracking_number || '';

        // Trigger updates
        syncCourierChips(order.courier_name || '');
        updatePaymentAdvice();
        updateLiveTrackingButton();
        syncModalStepper(order.status);

        // Print Buttons
        document.getElementById('singleModalLabelBtn').href = `/admin/orders/${order.id}/shipping-label`;
        document.getElementById('singleModalPdfBtn').href = `/admin/orders/${order.id}/invoice`;

        openCustomModal('singleStatusModal');
    };

    // Submit Single Order Quick Status
    document.getElementById('singleStatusForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const orderId = document.getElementById('singleModalOrderId').value;
        const btnSubmit = document.getElementById('btnSingleModalSubmit');
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span> Saving Changes...`;

        const payload = {
            _token: '{{ csrf_token() }}',
            status: document.getElementById('singleModalStatus').value,
            payment_status: document.getElementById('singleModalPaymentStatus').value,
            courier_name: document.getElementById('singleModalCourier').value,
            tracking_number: document.getElementById('singleModalTracking').value,
        };

        fetch(`/admin/orders/${orderId}/quick-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = `<i class="bi bi-check-circle-fill me-1"></i> Save Changes`;
            if (data.success) {
                closeCustomModal('singleStatusModal');
                showToast(data.message);
                fetchOrders(currentPage);
            } else {
                showToast(data.message || 'Failed to update order.', true);
            }
        })
        .catch(err => {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = `<i class="bi bi-check-circle-fill me-1"></i> Save Changes`;
            console.error('Quick status error:', err);
            showToast('An error occurred. Please try again.', true);
        });
    });

    // ── Bulk Status Update Modal ──
    btnOpenBulkModal.addEventListener('click', function() {
        const count = selectedOrderIds.size;
        if (count === 0) return;

        document.getElementById('bulkModalCountText').textContent = `Updating ${count} Selected Order${count === 1 ? '' : 's'}`;
        document.getElementById('bulkModalCourier').value = '';
        openCustomModal('bulkStatusModal');
    });

    document.getElementById('bulkStatusForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const btnSubmit = document.getElementById('btnBulkModalSubmit');
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span> Updating...`;

        const payload = {
            _token: '{{ csrf_token() }}',
            order_ids: Array.from(selectedOrderIds),
            status: document.getElementById('bulkModalStatus').value,
            payment_status: document.getElementById('bulkModalPaymentStatus').value,
            courier_name: document.getElementById('bulkModalCourier').value,
        };

        fetch(`{{ route('admin.orders.bulk-status') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = `<i class="bi bi-check-all me-1"></i> Apply to All Selected`;
            if (data.success) {
                closeCustomModal('bulkStatusModal');
                showToast(data.message);
                selectedOrderIds.clear();
                selectAllCheckbox.checked = false;
                syncSelectionUI();
                fetchOrders(currentPage);
            } else {
                showToast(data.message || 'Failed to update orders in batch.', true);
            }
        })
        .catch(err => {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = `<i class="bi bi-check-all me-1"></i> Apply to All Selected`;
            console.error('Bulk update error:', err);
            showToast('An error occurred during bulk update.', true);
        });
    });

    // ── Search Input Debounce (300ms) ──
    searchInput.addEventListener('input', function() {
        btnSearchClear.style.display = this.value.trim().length > 0 ? 'block' : 'none';
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            fetchOrders(1);
        }, 300);
    });

    btnSearchClear.addEventListener('click', function() {
        searchInput.value = '';
        this.style.display = 'none';
        fetchOrders(1);
    });

    // ── Dropdown Filter Events ──
    methodSelect.addEventListener('change', () => fetchOrders(1));
    paymentSelect.addEventListener('change', () => fetchOrders(1));
    sortSelect.addEventListener('change', () => fetchOrders(1));
    perPageSelect.addEventListener('change', () => fetchOrders(1));

    // ── Flow Guide Pane Toggle ──
    const flowGuidePane = document.getElementById('ordersFlowGuidePane');
    const btnToggleFlowGuide = document.getElementById('btnToggleFlowGuide');
    window.toggleFlowGuidePane = function() {
        if (!flowGuidePane) return;
        flowGuidePane.classList.toggle('open');
        const isOpen = flowGuidePane.classList.contains('open');
        if (btnToggleFlowGuide) {
            btnToggleFlowGuide.classList.toggle('active-guide', isOpen);
        }
    };
    if (btnToggleFlowGuide) {
        btnToggleFlowGuide.addEventListener('click', toggleFlowGuidePane);
    }

    // ── Status Chips Filter ──
    document.querySelectorAll('.status-chip').forEach(chip => {
        chip.addEventListener('click', function() {
            document.querySelectorAll('.status-chip').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            currentStatus = this.getAttribute('data-status') || '';
            document.querySelectorAll('.orders-metric').forEach(m => m.classList.remove('active-metric-filter'));
            fetchOrders(1);
        });
    });

    // ── Reset All Filters Button ──
    btnReset.addEventListener('click', function() {
        searchInput.value = '';
        btnSearchClear.style.display = 'none';
        methodSelect.value = '';
        paymentSelect.value = '';
        sortSelect.value = 'latest';
        perPageSelect.value = '15';
        currentStatus = '';
        selectedOrderIds.clear();
        syncSelectionUI();

        document.querySelectorAll('.status-chip').forEach(c => c.classList.remove('active'));
        document.querySelector('.status-chip[data-status=""]').classList.add('active');
        document.querySelectorAll('.orders-metric').forEach(m => m.classList.remove('active-metric-filter'));

        fetchOrders(1);
    });

    // ── Barcode & QR Scanner Engine ──
    const scannerModal = document.getElementById('barcodeScannerModal');
    const btnOpenScanner = document.getElementById('btnOpenScannerModal');
    const btnSearchScan = document.getElementById('btnSearchScanTrigger');
    const scannerGunInput = document.getElementById('scannerGunInput');
    const scannedOrderResultCard = document.getElementById('scannedOrderResultCard');
    let html5QrScannerInstance = null;
    let currentScannedOrder = null;

    // Synthesize Audio Beep via Web Audio API (100% offline & standard warehouse sound)
    function playScanBeep(success = true) {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.type = success ? 'sine' : 'sawtooth';
            osc.frequency.value = success ? 900 : 320;
            gain.gain.value = 0.25;
            osc.start();
            osc.stop(ctx.currentTime + (success ? 0.12 : 0.28));
        } catch(e) {}
    }

    window.openScannerModal = function() {
        openCustomModal('barcodeScannerModal');
        scannedOrderResultCard.classList.remove('show');
        document.getElementById('paneModeGun').style.display = 'block';
        document.getElementById('paneModeCamera').style.display = 'none';
        document.getElementById('tabModeGun').classList.add('active');
        document.getElementById('tabModeCamera').classList.remove('active');
        setTimeout(() => {
            scannerGunInput.focus();
            scannerGunInput.select();
        }, 150);
    };

    window.closeScannerModal = function() {
        closeCustomModal('barcodeScannerModal');
        stopCameraScanner();
    };

    window.switchScannerMode = function(mode) {
        document.getElementById('tabModeGun').classList.toggle('active', mode === 'gun');
        document.getElementById('tabModeCamera').classList.toggle('active', mode === 'camera');
        document.getElementById('paneModeGun').style.display = mode === 'gun' ? 'block' : 'none';
        document.getElementById('paneModeCamera').style.display = mode === 'camera' ? 'block' : 'none';

        if (mode === 'gun') {
            stopCameraScanner();
            setTimeout(() => scannerGunInput.focus(), 100);
        } else {
            startCameraScanner();
        }
    };

    if (btnOpenScanner) {
        btnOpenScanner.addEventListener('click', openScannerModal);
    }
    if (btnSearchScan) {
        btnSearchScan.addEventListener('click', openScannerModal);
    }

    // Global Shortcut: Press F2 to open/close Scanner anywhere on orders page
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F2') {
            e.preventDefault();
            if (scannerModal.classList.contains('open')) {
                closeScannerModal();
            } else {
                openScannerModal();
            }
        }
    });

    // Handle USB Scanner Gun input (Enter key triggered automatically by barcode gun)
    scannerGunInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const rawCode = this.value.trim();
            if (rawCode) {
                processBarcodeScan(rawCode);
            }
        }
    });

    // Process Barcode / QR payload
    function processBarcodeScan(rawCode) {
        let cleanCode = rawCode.trim();

        // If scanned from 2D QR format (ORDER:TT-1049|AWB:...|PIN:...)
        if (cleanCode.includes('|')) {
            const parts = cleanCode.split('|');
            for (let p of parts) {
                if (p.startsWith('ORDER:')) {
                    cleanCode = p.replace('ORDER:', '').trim();
                    break;
                } else if (p.startsWith('AWB:')) {
                    cleanCode = p.replace('AWB:', '').trim();
                    break;
                }
            }
        }

        // Clean any leading/trailing brackets (e.g. (AWB123) or #TT-123)
        cleanCode = cleanCode.replace(/^[#\(\)]+|[#\(\)]+$/g, '').trim();

        // 1. Try local lookup first
        let matched = null;
        for (let id in loadedOrdersMap) {
            const o = loadedOrdersMap[id];
            if (String(o.order_number).toLowerCase() === cleanCode.toLowerCase() ||
                String(o.id) === cleanCode ||
                (o.tracking_number && String(o.tracking_number).toLowerCase() === cleanCode.toLowerCase())) {
                matched = o;
                break;
            }
        }

        if (matched) {
            playScanBeep(true);
            displayScannedOrder(matched);
            scannerGunInput.value = '';
            scannerGunInput.focus();
            return;
        }

        // 2. Lookup via Server AJAX search
        fetch(`{{ route('admin.orders.ajax') }}?search=${encodeURIComponent(cleanCode)}&per_page=1`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.orders && data.orders.length > 0) {
                playScanBeep(true);
                const order = data.orders[0];
                loadedOrdersMap[order.id] = order;
                displayScannedOrder(order);
            } else {
                playScanBeep(false);
                showToast(`No order matched barcode: "${cleanCode}"`, true);
            }
            scannerGunInput.value = '';
            scannerGunInput.focus();
        })
        .catch(err => {
            playScanBeep(false);
            console.error('Scan lookup error:', err);
            showToast('Scan lookup error. Check network.', true);
            scannerGunInput.value = '';
            scannerGunInput.focus();
        });
    }

    // Display Scanned Order Card
    function displayScannedOrder(order) {
        currentScannedOrder = order;

        document.getElementById('scanResultOrderNum').textContent = `#${order.order_number}`;
        document.getElementById('scanResultProdImg').src = order.item_image;
        document.getElementById('scanResultProdName').textContent = order.item_name;
        document.getElementById('scanResultProdMeta').innerHTML = `<span>${order.item_sku}</span>` + (order.item_size ? ` &bull; <span class="badge bg-light text-navy border">${order.item_size}</span>` : '');
        document.getElementById('scanResultAmount').textContent = order.formatted_amount;
        document.getElementById('scanResultItemsCount').textContent = `${order.items_count} item(s)`;
        document.getElementById('scanResultCustomer').textContent = order.shipping_name;
        document.getElementById('scanResultLocation').textContent = `(${order.shipping_city || '—'})`;

        const payBadge = order.is_cod 
            ? `<span class="badge-payment-method badge-method-cod"><i class="bi bi-cash-stack"></i> COD</span>`
            : `<span class="badge-payment-method badge-method-prepaid"><i class="bi bi-credit-card-2-front-fill"></i> PREPAID</span>`;
        document.getElementById('scanResultPayBadge').innerHTML = payBadge;

        const st = statusBadges[order.status] || { cls: 'status-refunded', icon: '', text: order.status };
        document.getElementById('scanResultStatusPill').innerHTML = `<span class="status-pill ${st.cls}">${st.icon} ${st.text}</span>`;

        document.getElementById('scanResultLabelBtn').href = `/admin/orders/${order.id}/shipping-label`;
        document.getElementById('scanResultDetailsBtn').href = order.show_url;

        scannedOrderResultCard.classList.add('show');
    }

    // 1-Click Quick Status Update directly from Scanner Studio!
    window.quickUpdateScannedStatus = function(newStatus) {
        if (!currentScannedOrder) return;
        const orderId = currentScannedOrder.id;

        fetch(`/admin/orders/${orderId}/quick-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                _token: '{{ csrf_token() }}',
                status: newStatus
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                playScanBeep(true);
                showToast(`Order #${currentScannedOrder.order_number} marked as ${newStatus.toUpperCase()}!`);
                currentScannedOrder.status = newStatus;
                displayScannedOrder(currentScannedOrder);
                fetchOrders(currentPage);
                scannerGunInput.focus();
            } else {
                showToast(data.message || 'Status update failed', true);
            }
        })
        .catch(err => {
            console.error('Quick status update error:', err);
            showToast('Failed to update status', true);
        });
    };

    // Camera Scanner Controller using Html5Qrcode
    let isCameraActive = false;
    window.toggleCameraScanner = function() {
        if (isCameraActive) {
            stopCameraScanner();
        } else {
            startCameraScanner();
        }
    };

    function startCameraScanner() {
        const btn = document.getElementById('btnToggleCamera');
        if (!window.Html5Qrcode) {
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span> Loading Camera Driver...`;
            btn.disabled = true;
            const script = document.createElement('script');
            script.src = 'https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js';
            script.onload = () => {
                btn.disabled = false;
                initCameraInstance();
            };
            script.onerror = () => {
                btn.disabled = false;
                btn.innerHTML = `<i class="bi bi-camera-fill me-1"></i> Start Camera Scan`;
                showToast('Camera driver failed to load. Use USB Barcode Gun mode.', true);
            };
            document.head.appendChild(script);
        } else {
            initCameraInstance();
        }
    }

    function initCameraInstance() {
        const btn = document.getElementById('btnToggleCamera');
        if (!html5QrScannerInstance) {
            html5QrScannerInstance = new Html5Qrcode("cameraReader");
        }

        btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span> Accessing Camera...`;
        btn.disabled = true;

        html5QrScannerInstance.start(
            { facingMode: "environment" },
            { fps: 15, qrbox: { width: 250, height: 200 } },
            (decodedText) => {
                processBarcodeScan(decodedText);
            },
            (errorMessage) => {
                // scanning frame error (ignore)
            }
        ).then(() => {
            isCameraActive = true;
            btn.disabled = false;
            btn.innerHTML = `<i class="bi bi-stop-circle-fill me-1"></i> Stop Camera`;
            btn.className = 'btn btn-sm btn-danger fw-bold px-3 py-1.5';
        }).catch(err => {
            isCameraActive = false;
            btn.disabled = false;
            btn.innerHTML = `<i class="bi bi-camera-fill me-1"></i> Start Camera Scan`;
            btn.className = 'btn btn-sm btn-primary fw-bold px-3 py-1.5';
            console.warn('Camera access error:', err);
            showToast('Unable to access camera. Check browser permissions.', true);
        });
    }

    function stopCameraScanner() {
        const btn = document.getElementById('btnToggleCamera');
        if (html5QrScannerInstance && isCameraActive) {
            html5QrScannerInstance.stop().then(() => {
                isCameraActive = false;
                if (btn) {
                    btn.innerHTML = `<i class="bi bi-camera-fill me-1"></i> Start Camera Scan`;
                    btn.className = 'btn btn-sm btn-primary fw-bold px-3 py-1.5';
                }
            }).catch(e => {
                isCameraActive = false;
            });
        }
    }

    // Sample Test Scan function
    window.testScanSample = function() {
        const ordersKeys = Object.keys(loadedOrdersMap);
        if (ordersKeys.length > 0) {
            const first = loadedOrdersMap[ordersKeys[0]];
            const sampleCode = first.tracking_number || first.order_number;
            scannerGunInput.value = sampleCode;
            processBarcodeScan(sampleCode);
        } else {
            processBarcodeScan('1');
        }
    };

    // Initial Fetch
    fetchOrders(1);
});
</script>
@endsection
