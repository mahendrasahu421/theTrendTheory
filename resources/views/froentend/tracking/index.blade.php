{{-- resources/views/froentend/tracking/index.blade.php --}}
@extends('froentend.layouts.app')

@push('seo')
    <title>{{ isset($order) ? "Track Order #{$order->order_number}" : 'My Orders & Live Tracking' }} | THE TREND THEORY</title>
    <meta name="description" content="View all your orders, live shipment tracking, courier dispatch status, and itemized invoice summary with THE TREND THEORY.">
@endpush

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   MY ORDERS & 2-COLUMN LIVE TRACKING STYLES
   ═══════════════════════════════════════════════════════════ */
:root {
    --track-primary: #00285a;
    --track-primary-dark: #001838;
    --track-accent: #ff3f6c;
    --track-emerald: #059669;
    --track-emerald-light: #ecfdf5;
    --track-emerald-border: #a7f3d0;
    --track-border: #e2e8f0;
    --track-bg-light: #f8fafc;
    --track-text-main: #0f172a;
    --track-text-muted: #64748b;
}

.tracking-page-wrap {
    max-width: 1240px;
    margin: 28px auto 90px;
    padding: 0 20px;
    font-family: -apple-system, BlinkMacSystemFont, "Plus Jakarta Sans", "Segoe UI", Roboto, sans-serif;
    color: var(--track-text-main);
}

/* Breadcrumb */
.track-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--track-text-muted);
    margin-bottom: 24px;
}

.track-breadcrumb a {
    color: var(--track-text-muted);
    text-decoration: none;
    transition: color 0.15s ease;
}

.track-breadcrumb span {
    color: var(--track-text-main);
    font-weight: 700;
}

/* Page Header */
.track-header-hero {
    text-align: center;
    margin-bottom: 30px;
}

.track-page-title {
    font-family: 'Cinzel', serif !important;
    font-size: 28px;
    font-weight: 800;
    color: var(--track-primary);
    letter-spacing: 1.2px;
    margin: 0 0 6px;
    text-transform: uppercase;
}

.track-page-subtitle {
    font-size: 14px;
    color: var(--track-text-muted);
    max-width: 560px;
    margin: 0 auto;
    line-height: 1.5;
}

/* Lookup Search Card */
.track-lookup-card {
    background: #ffffff;
    border: 1px solid var(--track-border);
    border-radius: 20px;
    padding: 22px 28px;
    box-shadow: 0 4px 24px rgba(15, 23, 42, 0.05);
    margin-bottom: 30px;
}

.track-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 16px;
    align-items: flex-end;
}

.track-input-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.track-input-group label {
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    color: var(--track-text-muted);
    letter-spacing: 0.5px;
}

.track-input-group input {
    height: 48px;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    padding: 0 14px;
    font-size: 14px;
    font-weight: 600;
    color: var(--track-text-main);
    outline: none;
    transition: all 0.2s ease;
}

.track-input-group input:focus {
    border-color: var(--track-primary);
    box-shadow: 0 0 0 3px rgba(0, 40, 90, 0.1);
}

.btn-track-submit {
    height: 48px;
    padding: 0 28px;
    background: linear-gradient(135deg, #00285a 0%, #0f4c81 100%);
    color: #ffffff;
    border: 0;
    border-radius: 10px;
    font-size: 13.5px;
    font-weight: 800;
    letter-spacing: 0.5px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.25s ease;
    text-transform: uppercase;
    box-shadow: 0 6px 18px rgba(0, 40, 90, 0.2);
}

.track-alert-error {
    background: #fef2f2;
    border: 1px solid #fee2e2;
    color: #dc2626;
    padding: 14px 18px;
    border-radius: 12px;
    font-size: 13.5px;
    font-weight: 600;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* ═══════════════════════════════════════════════════════════
   VIEW 1: ALL ORDERS LIST (When no specific order is active)
   ═══════════════════════════════════════════════════════════ */
.orders-list-wrapper {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.orders-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
    flex-wrap: wrap;
    gap: 12px;
}

.orders-section-title {
    font-family: 'Cinzel', serif !important;
    font-size: 18px;
    font-weight: 800;
    color: var(--track-primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
    letter-spacing: 0.5px;
}

/* Individual Order Card in List */
.user-order-card {
    background: #ffffff;
    border: 1.5px solid var(--track-border);
    border-radius: 18px;
    padding: 22px 24px;
    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
    transition: all 0.22s ease;
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 20px;
    align-items: center;
}

.order-card-main-info {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.order-card-meta-top {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.order-card-num {
    font-family: 'Cinzel', serif !important;
    font-size: 17px;
    font-weight: 800;
    color: var(--track-primary);
    letter-spacing: 0.5px;
    text-decoration: none;
}

.order-card-date {
    font-size: 12.5px;
    color: var(--track-text-muted);
}

.order-status-badge {
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 11.5px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.order-status-badge.confirmed { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.order-status-badge.processing { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
.order-status-badge.shipped { background: #f0fdf4; color: #047857; border: 1px solid #a7f3d0; }
.order-status-badge.delivered { background: #ecfdf5; color: #065f46; border: 1px solid #6ee7b7; }
.order-status-badge.cancelled { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

/* Item Thumbnails Preview Row */
.order-card-items-preview {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.thumb-preview-box {
    width: 52px;
    aspect-ratio: 3/4;
    border-radius: 8px;
    object-fit: cover;
    background: #f8fafc;
    border: 1px solid var(--track-border);
}

.order-card-item-names {
    font-size: 13px;
    font-weight: 600;
    color: var(--track-text-main);
    max-width: 440px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.order-card-pricing-block {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
}

.order-card-pricing-block strong {
    font-size: 16px;
    color: var(--track-primary);
    font-weight: 900;
}

/* Card CTA Action Buttons */
.order-card-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 170px;
}

.btn-card-track {
    background: linear-gradient(135deg, #00285a 0%, #0f4c81 100%);
    color: #ffffff !important;
    padding: 10px 18px;
    border-radius: 10px;
    font-size: 12.5px;
    font-weight: 800;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.2s ease;
    text-transform: uppercase;
    box-shadow: 0 4px 14px rgba(0, 40, 90, 0.15);
}

.btn-card-inv {
    background: #f8fafc;
    border: 1.5px solid #cbd5e1;
    color: #334155 !important;
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 11.5px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.15s ease;
}

/* Guest / Empty Callout Box */
.guest-login-callout {
    background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
    border: 1.5px solid #bfdbfe;
    border-radius: 16px;
    padding: 24px;
    text-align: center;
    margin-top: 20px;
}

/* Pagination Container */
.track-pagination-container {
    margin-top: 28px;
    margin-bottom: 10px;
    display: flex;
    justify-content: center;
}
.track-pagination-container nav {
    display: flex;
    justify-content: center;
}
.track-pagination-container .pagination {
    display: flex;
    gap: 6px;
    list-style: none;
    padding: 0;
    margin: 0;
    align-items: center;
}
.track-pagination-container .page-item .page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 38px;
    height: 38px;
    padding: 0 14px;
    border-radius: 10px !important;
    border: 1.5px solid #e2e8f0;
    background: #ffffff;
    color: #0f172a;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.15s ease;
}
.track-pagination-container .page-item.active .page-link {
    background: #00285a !important;
    border-color: #00285a !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(0, 40, 90, 0.2);
}
.track-pagination-container .page-item.disabled .page-link {
    color: #94a3b8;
    background: #f8fafc;
    border-color: #e2e8f0;
    cursor: not-allowed;
    opacity: 0.7;
}

.guest-login-callout h3 {
    font-size: 16px;
    font-weight: 800;
    color: var(--track-primary);
    margin: 0 0 6px;
}

.guest-login-callout p {
    font-size: 13px;
    color: var(--track-text-muted);
    margin: 0 0 16px;
}

.btn-guest-login {
    background: var(--track-primary);
    color: #ffffff !important;
    padding: 9px 24px;
    border-radius: 999px;
    font-size: 12.5px;
    font-weight: 800;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

/* ═══════════════════════════════════════════════════════════
   VIEW 2: ACTIVE 2-COLUMN ORDER TRACKING & SUMMARY
   ═══════════════════════════════════════════════════════════ */
.active-tracking-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
    flex-wrap: wrap;
    gap: 12px;
}

.btn-back-orders {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    color: var(--track-primary);
    font-size: 13px;
    font-weight: 800;
    padding: 8px 18px;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.2s ease;
}

.track-main-2col {
    display: grid;
    grid-template-columns: 1.08fr 1.15fr;
    gap: 28px;
    align-items: flex-start;
}

/* Left Column */
.track-left-col {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.track-card {
    background: #ffffff;
    border: 1px solid var(--track-border);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
}

.track-status-head-banner {
    background: linear-gradient(135deg, #001c3f 0%, #00285a 100%);
    padding: 22px 24px;
    color: #ffffff;
}

.track-head-top-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 12px;
}

.track-head-order-num {
    font-family: 'Cinzel', serif !important;
    font-size: 20px;
    font-weight: 800;
    letter-spacing: 0.8px;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.track-status-badge-pill {
    background: rgba(255, 255, 255, 0.16);
    backdrop-filter: blur(6px);
    border: 1px solid rgba(255, 255, 255, 0.25);
    padding: 5px 14px;
    border-radius: 999px;
    font-size: 12.5px;
    font-weight: 800;
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.track-status-badge-pill.delivered { background: #10b981; border-color: #10b981; }

.track-est-delivery-bar {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 12px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 13px;
}

.track-est-delivery-bar strong {
    color: #34d399;
    font-size: 14.5px;
}

/* Vertical Timeline */
.timeline-vertical-card {
    padding: 24px;
}

.timeline-card-title {
    font-size: 13.5px;
    font-weight: 800;
    text-transform: uppercase;
    color: var(--track-text-main);
    letter-spacing: 0.6px;
    margin: 0 0 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.vertical-stepper {
    position: relative;
    padding-left: 10px;
}

.vertical-step-item {
    position: relative;
    padding-left: 48px;
    padding-bottom: 26px;
}

.vertical-step-item:last-child {
    padding-bottom: 0;
}

.vertical-step-item::before {
    content: '';
    position: absolute;
    left: 17px;
    top: 36px;
    bottom: 0;
    width: 3px;
    background: #e2e8f0;
    transition: background 0.3s ease;
}

.vertical-step-item:last-child::before {
    display: none;
}

.vertical-step-item.done::before {
    background: #10b981;
}

.step-icon-bubble {
    position: absolute;
    left: 0;
    top: 0;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #ffffff;
    border: 2.5px solid #cbd5e1;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    z-index: 2;
    transition: all 0.3s ease;
}

.vertical-step-item.done .step-icon-bubble {
    background: #10b981;
    border-color: #10b981;
    color: #ffffff;
    box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.18);
}

.vertical-step-item.current .step-icon-bubble {
    background: var(--track-primary);
    border-color: var(--track-primary);
    color: #ffffff;
    box-shadow: 0 0 0 5px rgba(0, 40, 90, 0.2);
    animation: pulseRing 1.8s infinite;
}

@keyframes pulseRing {
    0% { box-shadow: 0 0 0 0 rgba(0, 40, 90, 0.4); }
    70% { box-shadow: 0 0 0 8px rgba(0, 40, 90, 0); }
    100% { box-shadow: 0 0 0 0 rgba(0, 40, 90, 0); }
}

.step-info-box {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.step-title-text {
    font-size: 13.5px;
    font-weight: 800;
    color: #334155;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.vertical-step-item.done .step-title-text,
.vertical-step-item.current .step-title-text {
    color: var(--track-text-main);
}

.step-timestamp-tag {
    font-size: 11px;
    font-weight: 700;
    color: #047857;
    background: #f0fdf4;
    border: 1px solid #dcfce7;
    padding: 2px 7px;
    border-radius: 4px;
    display: inline-block;
}

.step-desc-text {
    font-size: 12.5px;
    color: #64748b;
    line-height: 1.45;
    margin: 2px 0 0;
}

/* Courier Logistics Card */
.courier-card-wrap {
    padding: 20px 24px;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 14px;
}

.courier-info-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.courier-logo-circle {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: #f8fafc;
    border: 1.5px solid var(--track-border);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: var(--track-primary);
}

.courier-details-text strong {
    font-size: 14px;
    color: var(--track-text-main);
    display: block;
}

.courier-details-text span {
    font-size: 12px;
    color: var(--track-text-muted);
}

.btn-open-courier {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #ffffff;
    border: 1.5px solid var(--track-primary);
    color: var(--track-primary);
    font-size: 12px;
    font-weight: 800;
    padding: 8px 14px;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.18s ease;
}

/* Right Column */
.track-right-col {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.section-subheading {
    font-family: 'Cinzel', serif !important;
    font-size: 14.5px;
    font-weight: 800;
    color: var(--track-primary);
    letter-spacing: 0.6px;
    margin: 0 0 16px;
    padding-bottom: 10px;
    border-bottom: 1px solid #f1f5f9;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.track-items-container {
    padding: 24px;
}

.order-items-scroll-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.order-item-detail-row {
    display: grid;
    grid-template-columns: 68px 1fr auto;
    gap: 16px;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
}

.order-item-detail-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.item-thumb-box {
    width: 68px;
    aspect-ratio: 3/4;
    border-radius: 10px;
    overflow: hidden;
    background: #f8fafc;
    border: 1px solid var(--track-border);
    flex-shrink: 0;
}

.item-thumb-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.item-info-meta {
    min-width: 0;
}

.item-name-heading {
    font-size: 14px;
    font-weight: 700;
    color: var(--track-text-main);
    text-decoration: none;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.35;
    margin-bottom: 5px;
}

.item-pill-tags {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.item-meta-badge {
    font-size: 11px;
    font-weight: 700;
    color: #475569;
    background: #f1f5f9;
    padding: 2px 8px;
    border-radius: 4px;
    border: 1px solid #e2e8f0;
}

.item-price-side {
    text-align: right;
}

.item-price-val {
    font-size: 15px;
    font-weight: 800;
    color: var(--track-primary);
}

.item-mrp-strike {
    font-size: 12px;
    color: #94a3b8;
    text-decoration: line-through;
}

.item-unit-calc {
    font-size: 11px;
    color: #94a3b8;
}

/* Full Order Summary Card */
.full-order-summary-card {
    padding: 24px;
    background: #ffffff;
}

.summary-calc-rows {
    display: flex;
    flex-direction: column;
    gap: 11px;
    margin-bottom: 18px;
}

.calc-row-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13.5px;
    color: #475569;
}

.calc-row-item b {
    color: var(--track-text-main);
    font-weight: 700;
}

.calc-row-item.savings b,
.calc-row-item.savings span {
    color: #047857;
    font-weight: 700;
}

.calc-row-item.discount-coupon {
    background: #f0fdf4;
    border: 1px dashed #86efac;
    padding: 6px 12px;
    border-radius: 8px;
    color: #166534;
}

.calc-row-item.discount-coupon b {
    color: #047857;
    font-size: 14px;
}

.calc-grand-total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1.5px dashed #cbd5e1;
    padding-top: 14px;
    margin-top: 6px;
}

.grand-total-label {
    font-size: 14.5px;
    font-weight: 800;
    color: var(--track-text-main);
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.grand-total-value {
    font-size: 22px;
    font-weight: 900;
    color: var(--track-primary);
}

.savings-callout-banner {
    background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%);
    border: 1.5px solid #a7f3d0;
    border-radius: 12px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 16px;
    font-size: 13px;
    font-weight: 700;
    color: #065f46;
}

.savings-callout-banner i {
    font-size: 18px;
    color: #10b981;
}

.payment-details-box {
    background: #f8fafc;
    border: 1px solid var(--track-border);
    border-radius: 14px;
    padding: 16px;
    margin-top: 18px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.payment-meta-line {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 12.5px;
    color: #64748b;
}

.payment-meta-line strong {
    color: var(--track-text-main);
    font-weight: 700;
}

.pay-badge-status {
    font-size: 11px;
    font-weight: 800;
    padding: 3px 9px;
    border-radius: 6px;
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.pay-badge-status.paid {
    background: #ecfdf5;
    color: #047857;
    border: 1px solid #a7f3d0;
}

.pay-badge-status.unpaid,
.pay-badge-status.pending {
    background: #fffbeb;
    color: #b45309;
    border: 1px solid #fde68a;
}

.delivery-address-box {
    padding: 22px;
}

.addr-recipient-name {
    font-size: 14px;
    font-weight: 800;
    color: var(--track-text-main);
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.addr-details-body {
    font-size: 13px;
    color: #475569;
    line-height: 1.5;
    margin-bottom: 10px;
}

.addr-phone-line {
    font-size: 12.5px;
    font-weight: 700;
    color: #334155;
    display: flex;
    align-items: center;
    gap: 6px;
}

.track-actions-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.btn-track-cta {
    height: 46px;
    border-radius: 10px;
    font-size: 12.5px;
    font-weight: 800;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.2s ease;
    border: 0;
    cursor: pointer;
    text-transform: uppercase;
}

.btn-invoice-pdf {
    background: #0f172a;
    color: #ffffff;
}

.btn-wa-support {
    background: #10b981;
    color: #ffffff;
}

/* Responsive */
@media (max-width: 900px) {
    .user-order-card {
        grid-template-columns: 1fr;
    }
    .order-card-actions {
        flex-direction: row;
        width: 100%;
    }
    .btn-card-track, .btn-card-inv {
        flex: 1;
    }
    .track-main-2col {
        grid-template-columns: 1fr;
    }
    .track-form-grid {
        grid-template-columns: 1fr;
    }
    .track-actions-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('main')
<div class="tracking-page-wrap">

    {{-- Breadcrumb --}}
    <div class="track-breadcrumb">
        <a href="{{ route('home') }}"><i class="bi bi-house-door"></i> Home</a>
        <i class="bi bi-chevron-right" style="font-size: 10px;"></i>
        <a href="{{ route('shop.index') }}">Shop</a>
        <i class="bi bi-chevron-right" style="font-size: 10px;"></i>
        <span>{{ isset($order) ? "Tracking Order #{$order->order_number}" : 'My Orders & Live Tracking' }}</span>
    </div>

    {{-- Flash Errors --}}
    @if(session('error'))
        <div class="track-alert-error">
            <i class="bi bi-exclamation-circle-fill fs-5"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════
         SCENARIO 1: VIEWING A SPECIFIC ORDER (2-COLUMN TRACKING & FULL SUMMARY)
         ═══════════════════════════════════════════════════════════ --}}
    @if(isset($order) && isset($timeline))
        @php
            $subtotalAmount = (float) $order->subtotal;
            $shippingCharge = (float) $order->shipping_charge;
            $discountAmount = (float) $order->discount_amount;
            $totalAmount = (float) $order->total_amount;
            $totalQty = $order->items->sum('quantity');

            // Compute Estimated Total MRP and MRP Savings
            $totalMRP = 0;
            foreach ($order->items as $it) {
                $origPrice = ($it->product && $it->product->original_price && $it->product->original_price > $it->unit_price)
                    ? (float) $it->product->original_price
                    : ((float) $it->unit_price * 1.35);
                $totalMRP += ($origPrice * $it->quantity);
            }
            $mrpDiscount = max(0, $totalMRP - $subtotalAmount);
            $totalSavings = $mrpDiscount + $discountAmount;
            $gstAmount = round($totalAmount * 0.05 / 1.05, 2);
        @endphp

        {{-- Top Bar with Back Button & Switcher --}}
        <div class="active-tracking-topbar">
            <a href="{{ route('order.track') }}" class="btn-back-orders">
                <i class="bi bi-arrow-left"></i>
                <span>View All My Orders</span>
            </a>

            <button type="button" class="btn-mobile-open-summary" onclick="tttOpenSummaryModal()">
                <i class="bi bi-receipt-cutoff"></i>
                <span>Bill &amp; Items ({{ $totalQty }})</span>
            </button>

            <div class="active-tracking-topbar-info" style="font-size: 13px; color: var(--track-text-muted);">
                Viewing Live Tracking for <strong>#{{ $order->order_number }}</strong>
            </div>
        </div>

        <div class="track-main-2col">

            {{-- ═══════════════════════════════════════════════════════════
                 LEFT COLUMN: TIMELINE, STATUS & COURIER DISPATCH
                 ═══════════════════════════════════════════════════════════ --}}
            <div class="track-left-col">
                
                {{-- Live Status Banner --}}
                <div class="track-card">
                    <div class="track-status-head-banner">
                        <div class="track-head-top-row">
                            <h2 class="track-head-order-num">
                                <i class="bi bi-box-seam-fill text-warning"></i>
                                #{{ $order->order_number }}
                            </h2>
                            <span class="track-status-badge-pill {{ $order->status }}">
                                <i class="bi {{ $order->status === 'delivered' ? 'bi-check-circle-fill' : ($order->status === 'shipped' ? 'bi-truck' : 'bi-shield-check') }}"></i>
                                {{ $timeline['status_label'] }}
                            </span>
                        </div>

                        <div class="track-est-delivery-bar">
                            <span><i class="bi bi-lightning-charge-fill text-warning"></i> Estimated Delivery:</span>
                            <strong>{{ $timeline['expected_delivery'] }}</strong>
                        </div>
                    </div>

                    {{-- Courier Logistics Strip --}}
                    @if($order->courier_name || $order->tracking_number)
                        <div class="courier-card-wrap">
                            <div class="courier-info-left">
                                <div class="courier-logo-circle">
                                    <i class="bi bi-truck-flatbed"></i>
                                </div>
                                <div class="courier-details-text">
                                    <strong>{{ $order->courier_name ?: 'Express Logistics Partner' }}</strong>
                                    <span>AWB: <b class="font-monospace text-dark">{{ $order->tracking_number ?: 'Assigned on Dispatch' }}</b></span>
                                </div>
                            </div>

                            @if($courierUrl)
                                <a href="{{ $courierUrl }}" target="_blank" rel="noopener" class="btn-open-courier">
                                    <span>Track on Courier Site</span>
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Vertical Timeline Stepper --}}
                <div class="track-card timeline-vertical-card">
                    <div class="timeline-card-title">
                        <span><i class="bi bi-geo-alt-fill text-primary"></i> TRACKING ROADMAP</span>
                        <span style="font-size: 11.5px; color: #64748b; font-family: sans-serif;">{{ $timeline['progress_pct'] }}% Completed</span>
                    </div>

                    <div class="vertical-stepper">
                        @foreach($timeline['steps'] as $step)
                            <div class="vertical-step-item {{ $step['is_done'] ? 'done' : '' }} {{ $step['is_current'] ? 'current' : '' }}">
                                <div class="step-icon-bubble">
                                    <i class="bi {{ $step['is_done'] ? 'bi-check-lg' : $step['icon'] }}"></i>
                                </div>
                                <div class="step-info-box">
                                    <div class="step-title-text">
                                        <span>{{ $step['title'] }}</span>
                                        @if($step['is_done'] || $step['is_current'])
                                            <span class="step-timestamp-tag">{{ $step['date'] }}</span>
                                        @endif
                                    </div>
                                    <p class="step-desc-text">{{ $step['description'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="track-actions-grid">
                    <a href="{{ route('invoice.download', $order->order_number) }}" target="_blank" class="btn-track-cta btn-invoice-pdf">
                        <i class="bi bi-file-earmark-pdf-fill text-danger"></i>
                        <span>Download Invoice</span>
                    </a>

                    <button type="button" class="btn-track-cta btn-mobile-summary-cta" onclick="tttOpenSummaryModal()">
                        <i class="bi bi-receipt-cutoff text-primary"></i>
                        <span>View Items &amp; Bill</span>
                    </button>

                    @if($order->shipping_phone)
                        <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $order->shipping_phone) }}?text=Hello%20The%20Trend%20Theory%20team,%20I%20need%20an%20update%20on%20my%20Order%20{{ $order->order_number }}" target="_blank" rel="noopener" class="btn-track-cta btn-wa-support">
                            <i class="bi bi-whatsapp"></i>
                            <span>WhatsApp Support</span>
                        </a>
                    @else
                        <a href="{{ route('shop.index') }}" class="btn-track-cta btn-invoice-pdf">
                            <i class="bi bi-bag"></i>
                            <span>Continue Shopping</span>
                        </a>
                    @endif
                </div>

                {{-- ── Cancel / Return / Exchange Section ── --}}
                @auth
                @if($order->user_id === auth()->id())
                <div style="margin-top:16px;border-top:1px solid #e2e8f0;padding-top:16px">
                    <p style="font-size:11.5px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.6px;margin-bottom:10px">Manage Order</p>
                    <div style="display:flex;flex-direction:column;gap:8px">

                        {{-- Cancel (only pending/confirmed) --}}
                        @if(in_array(strtolower($order->status), ['pending', 'confirmed']))
                            <button type="button"
                                onclick="tttOpenCancelModal('{{ $order->id }}', '{{ $order->order_number }}')"
                                style="width:100%;padding:11px 16px;background:#fff5f5;color:#e53935;border:1.5px solid #ffcdd2;border-radius:12px;font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;font-family:inherit;transition:all .15s"
                                onmouseover="this.style.background='#e53935';this.style.color='#fff'"
                                onmouseout="this.style.background='#fff5f5';this.style.color='#e53935'">
                                <i class="bi bi-x-circle-fill"></i> Cancel This Order
                            </button>
                        @endif

                        {{-- Return / Exchange (only delivered) --}}
                        @if(in_array(strtolower($order->status), ['delivered', 'completed']) && !$order->return()->exists())
                            <a href="{{ route('order.return.create', $order->id) }}"
                               style="width:100%;padding:11px 16px;background:#fff8f0;color:#e65100;border:1.5px solid #fed7aa;border-radius:12px;font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none;transition:all .15s"
                               onmouseover="this.style.background='#e65100';this.style.color='#fff'"
                               onmouseout="this.style.background='#fff8f0';this.style.color='#e65100'">
                                <i class="bi bi-arrow-return-left"></i> Return / Exchange
                            </a>
                        @endif

                        {{-- Already returned --}}
                        @if($order->return()->exists())
                            <a href="{{ route('order.return.show', $order->return->id) }}"
                               style="width:100%;padding:11px 16px;background:#f0fdf4;color:#166534;border:1.5px solid #86efac;border-radius:12px;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none;">
                                <i class="bi bi-arrow-return-left"></i> View Return Request
                            </a>
                        @endif

                        {{-- Already cancelled --}}
                        @if(strtolower($order->status) === 'cancelled')
                            <div style="width:100%;padding:11px 16px;background:#f5f5f5;color:#757575;border:1.5px solid #e0e0e0;border-radius:12px;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;gap:8px;">
                                <i class="bi bi-x-circle"></i> Order Cancelled
                            </div>
                        @endif

                    </div>
                </div>
                @endif
                @endauth

            </div>

            {{-- ═══════════════════════════════════════════════════════════
                 RIGHT COLUMN: PRODUCTS IN ORDER & FULL ORDER SUMMARY
                 ═══════════════════════════════════════════════════════════ --}}
            <div class="track-right-col">

                {{-- 1. Products in Order --}}
                <div class="track-card track-items-container">
                    <div class="section-subheading">
                        <span><i class="bi bi-bag-check-fill text-primary"></i> ITEMS IN THIS ORDER</span>
                        <span class="badge bg-light text-dark border">{{ $totalQty }} {{ $totalQty === 1 ? 'Piece' : 'Pieces' }}</span>
                    </div>

                    <div class="order-items-scroll-list">
                        @foreach($order->items as $item)
                            @php
                                $itemImg = $item->product_image ?: ($item->product->main_image ?? asset('images/placeholder-product.jpg'));
                                $itemPrice = (float) $item->unit_price;
                                $itemSubtotal = (float) ($item->subtotal ?: ($itemPrice * $item->quantity));
                                $origItemPrice = ($item->product && $item->product->original_price && $item->product->original_price > $itemPrice) 
                                    ? (float) $item->product->original_price 
                                    : ($itemPrice * 1.35);
                            @endphp
                            <div class="order-item-detail-row">
                                <div class="item-thumb-box">
                                    <img src="{{ $itemImg }}" alt="{{ $item->product_name }}" onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                                </div>
                                <div class="item-info-meta">
                                    <a href="{{ $item->product ? route('product.show', $item->product->slug) : '#' }}" class="item-name-heading" title="{{ $item->product_name }}">
                                        {{ $item->product_name }}
                                    </a>
                                    <div class="item-pill-tags">
                                        @if($item->size)
                                            <span class="item-meta-badge"><i class="bi bi-rulers"></i> Size: {{ $item->size }}</span>
                                        @endif
                                        <span class="item-meta-badge">Qty: {{ $item->quantity }}</span>
                                    </div>
                                </div>
                                <div class="item-price-side">
                                    <div class="item-price-val">₹{{ number_format($itemSubtotal) }}</div>
                                    @if($origItemPrice > $itemPrice)
                                        <div class="item-mrp-strike">₹{{ number_format($origItemPrice * $item->quantity) }}</div>
                                    @endif
                                    @if($item->quantity > 1)
                                        <div class="item-unit-calc">₹{{ number_format($itemPrice) }} / pc</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- 2. Comprehensive FULL ORDER SUMMARY Card --}}
                <div class="track-card full-order-summary-card">
                    <div class="section-subheading">
                        <span><i class="bi bi-receipt-cutoff text-primary"></i> FULL ORDER SUMMARY</span>
                        <span style="font-size: 11px; color: #059669; font-weight: 700; font-family: sans-serif;">
                            <i class="bi bi-patch-check-fill"></i> TAX INVOICE
                        </span>
                    </div>

                    <div class="summary-calc-rows">
                        {{-- Total MRP --}}
                        <div class="calc-row-item">
                            <span>Total MRP (Original Price)</span>
                            <b>₹{{ number_format($totalMRP) }}</b>
                        </div>

                        {{-- MRP Discount --}}
                        @if($mrpDiscount > 0)
                            <div class="calc-row-item savings">
                                <span><i class="bi bi-percent"></i> Bag MRP Discount</span>
                                <b>-₹{{ number_format($mrpDiscount) }}</b>
                            </div>
                        @endif

                        {{-- Bag Subtotal --}}
                        <div class="calc-row-item">
                            <span>Bag Subtotal ({{ $totalQty }} items)</span>
                            <b>₹{{ number_format($subtotalAmount) }}</b>
                        </div>

                        {{-- Coupon Discount --}}
                        @if($discountAmount > 0)
                            <div class="calc-row-item discount-coupon">
                                <span><i class="bi bi-tag-fill"></i> Coupon Discount {{ $order->coupon_code ? "({$order->coupon_code})" : '' }}</span>
                                <b>-₹{{ number_format($discountAmount) }}</b>
                            </div>
                        @endif

                        {{-- Shipping Charge --}}
                        <div class="calc-row-item {{ $shippingCharge == 0 ? 'savings' : '' }}">
                            <span><i class="bi bi-truck"></i> Shipping / Delivery Charge</span>
                            <b>{{ $shippingCharge == 0 ? 'FREE' : '₹' . number_format($shippingCharge) }}</b>
                        </div>

                        {{-- GST / Taxes Included --}}
                        <div class="calc-row-item" style="font-size: 12.5px; color: #64748b;">
                            <span>Estimated Taxes (5% GST Included)</span>
                            <span>₹{{ number_format(round($gstAmount)) }}</span>
                        </div>

                        {{-- Grand Total Paid --}}
                        <div class="calc-grand-total-row">
                            <div>
                                <div class="grand-total-label">Final Amount Paid</div>
                                <div style="font-size: 11px; color: #64748b; font-weight: 500;">(All taxes &amp; shipping included)</div>
                            </div>
                            <span class="grand-total-value">₹{{ number_format($totalAmount) }}</span>
                        </div>
                    </div>

                    {{-- Total Savings Callout --}}
                    @if($totalSavings > 0)
                        <div class="savings-callout-banner">
                            <i class="bi bi-gift-fill"></i>
                            <div>You saved a total of <strong>₹{{ number_format($totalSavings) }}</strong> on this order!</div>
                        </div>
                    @endif

                    {{-- Comprehensive Payment & Order Metadata --}}
                    <div class="payment-details-box">
                        <div class="payment-meta-line">
                            <span>Payment Method</span>
                            <strong style="text-transform: uppercase;">{{ $order->payment_method ?: 'Online Payment' }}</strong>
                        </div>

                        <div class="payment-meta-line">
                            <span>Payment Status</span>
                            <span class="pay-badge-status {{ strtolower($order->payment_status) }}">
                                <i class="bi {{ strtolower($order->payment_status) === 'paid' ? 'bi-check-circle-fill' : 'bi-clock-fill' }}"></i>
                                {{ strtoupper($order->payment_status ?: 'PENDING') }}
                            </span>
                        </div>

                        @if($order->payment_id || $order->razorpay_order_id)
                            <div class="payment-meta-line">
                                <span>Transaction ID</span>
                                <span class="font-monospace" style="font-size: 11.5px; color: #0f172a; font-weight: 600;">
                                    {{ $order->payment_id ?: $order->razorpay_order_id }}
                                </span>
                            </div>
                        @endif

                        <div class="payment-meta-line">
                            <span>Order Date</span>
                            <span>{{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : 'Recently' }}</span>
                        </div>
                    </div>
                </div>

                {{-- 3. Delivery Destination Card --}}
                <div class="track-card delivery-address-box">
                    <div class="section-subheading">
                        <span><i class="bi bi-geo-alt-fill text-danger"></i> SHIPPING DESTINATION</span>
                    </div>

                    <div class="addr-recipient-name">
                        <i class="bi bi-person-fill text-primary"></i>
                        <span>{{ $order->shipping_name }}</span>
                    </div>
                    <div class="addr-details-body">
                        {{ $order->shipping_address }}<br>
                        <strong>{{ $order->shipping_city }}</strong>, {{ $order->shipping_state }} - {{ $order->shipping_pincode }}
                    </div>
                    <div class="addr-phone-line">
                        <i class="bi bi-telephone-fill text-muted"></i>
                        <span>{{ $order->shipping_phone }}</span>
                    </div>
                </div>

            </div>

        </div>

    {{-- ═══════════════════════════════════════════════════════════
         SCENARIO 2: LANDING PAGE — SHOW ALL USER ORDERS & SEARCH
         ═══════════════════════════════════════════════════════════ --}}
    @else

        {{-- Page Header --}}
        <div class="track-header-hero">
            <h1 class="track-page-title">MY ORDERS &amp; LIVE TRACKING</h1>
            <p class="track-page-subtitle">Select any order below to view live tracking progress, courier dispatch status, and full invoice summary.</p>
        </div>

        {{-- 1. Search Box (Collapsible / Compact) --}}
        <div class="track-lookup-card">
            <form action="{{ route('order.track.search') }}" method="POST">
                @csrf
                <div class="track-form-grid">
                    <div class="track-input-group">
                        <label for="orderNumberInput">Search Order ID / Tracking Number *</label>
                        <input type="text" id="orderNumberInput" name="order_number" placeholder="e.g. TTT-2026-000001" value="{{ old('order_number', $orderNumber ?? '') }}" required autocomplete="off">
                    </div>
                    <div class="track-input-group">
                        <label for="phoneInput">Mobile Number (Optional)</label>
                        <input type="tel" id="phoneInput" name="phone" placeholder="10-digit mobile number" value="{{ old('phone', $phone ?? '') }}" autocomplete="tel">
                    </div>
                    <button type="submit" class="btn-track-submit">
                        <i class="bi bi-search"></i>
                        <span>TRACK ORDER</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- 2. List of All Orders (If Logged In) --}}
        @if(auth()->check() && isset($userOrders) && $userOrders->count() > 0)
            <div class="orders-list-wrapper">
                <div class="orders-section-header">
                    <h2 class="orders-section-title">
                        <i class="bi bi-bag-check-fill"></i>
                        YOUR ORDERS ({{ method_exists($userOrders, 'total') ? $userOrders->total() : $userOrders->count() }})
                    </h2>
                    <span style="font-size: 13px; color: var(--track-text-muted);">
                        Click on any order to view live tracking &amp; order summary
                    </span>
                </div>

                @foreach($userOrders as $uOrder)
                    @php
                        $uTotalQty = $uOrder->items->sum('quantity');
                    @endphp
                    <div class="user-order-card">
                        
                        {{-- Left Info Block --}}
                        <div class="order-card-main-info">
                            
                            {{-- Meta Top Line --}}
                            <div class="order-card-meta-top">
                                <a href="{{ route('order.track.detail', $uOrder->order_number) }}" class="order-card-num">
                                    #{{ $uOrder->order_number }}
                                </a>
                                <span class="order-card-date">
                                    <i class="bi bi-calendar3"></i> {{ $uOrder->created_at ? $uOrder->created_at->format('d M Y, h:i A') : 'Recently' }}
                                </span>
                                <span class="order-status-badge {{ strtolower($uOrder->status) }}">
                                    <i class="bi {{ $uOrder->status === 'delivered' ? 'bi-check-circle-fill' : ($uOrder->status === 'shipped' ? 'bi-truck' : 'bi-clock-fill') }}"></i>
                                    {{ ucfirst($uOrder->status) }}
                                </span>
                            </div>

                            {{-- Products Thumbnails & Titles Preview --}}
                            <div class="order-card-items-preview">
                                @foreach($uOrder->items->take(4) as $uItem)
                                    @php
                                        $uItemImg = $uItem->product_image ?: ($uItem->product->main_image ?? asset('images/placeholder-product.jpg'));
                                    @endphp
                                    <img src="{{ $uItemImg }}" 
                                         alt="{{ $uItem->product_name }}" 
                                         title="{{ $uItem->product_name }} (Size: {{ $uItem->size }})"
                                         class="thumb-preview-box"
                                         onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                                @endforeach

                                <div>
                                    <div class="order-card-item-names">
                                        {{ $uOrder->items->pluck('product_name')->implode(', ') }}
                                    </div>
                                    <div class="order-card-pricing-block">
                                        <span>{{ $uTotalQty }} {{ $uTotalQty === 1 ? 'Item' : 'Items' }}</span>
                                        <span>&bull;</span>
                                        <strong>₹{{ number_format($uOrder->total_amount) }}</strong>
                                        <span class="badge {{ strtolower($uOrder->payment_status) === 'paid' ? 'bg-success' : 'bg-secondary' }} text-white px-2 py-0.5" style="font-size: 10px;">
                                             {{ strtoupper($uOrder->payment_status ?: ($uOrder->payment_method ?: 'COD')) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- Right Actions Block --}}
                        <div class="order-card-actions">
                            <a href="{{ route('order.track.detail', $uOrder->order_number) }}" class="btn-card-track">
                                <i class="bi bi-truck"></i>
                                <span>Track Live</span>
                            </a>

                            <a href="{{ route('invoice.download', $uOrder->order_number) }}" target="_blank" class="btn-card-inv">
                                <i class="bi bi-file-earmark-pdf-fill text-danger"></i>
                                <span>Invoice</span>
                            </a>

                            @if(in_array(strtolower($uOrder->status), ['pending', 'confirmed']))
                                <button type="button"
                                    onclick="tttOpenCancelModal('{{ $uOrder->id }}', '{{ $uOrder->order_number }}')"
                                    style="height:38px;padding:0 14px;background:#fff5f5;color:#e53935;border:1.5px solid #ffcdd2;border-radius:8px;font-size:12px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:5px;font-family:inherit;white-space:nowrap;transition:all .15s"
                                    onmouseover="this.style.background='#e53935';this.style.color='#fff'"
                                    onmouseout="this.style.background='#fff5f5';this.style.color='#e53935'">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </button>
                            @endif
                            @if(in_array(strtolower($uOrder->status), ['delivered', 'completed']) && !$uOrder->return()->exists())
                                <a href="{{ route('order.return.create', $uOrder->id) }}"
                                   style="height:38px;padding:0 14px;background:#fff8f0;color:#e65100;border:1.5px solid #fed7aa;border-radius:8px;font-size:12px;font-weight:700;display:flex;align-items:center;gap:5px;text-decoration:none;white-space:nowrap;transition:all .15s"
                                   onmouseover="this.style.background='#e65100';this.style.color='#fff'"
                                   onmouseout="this.style.background='#fff8f0';this.style.color='#e65100'">
                                    <i class="bi bi-arrow-return-left"></i> Return
                                </a>
                            @endif
                        </div>

                    </div>
                @endforeach

                {{-- Pagination Links --}}
                @if(method_exists($userOrders, 'hasPages') && $userOrders->hasPages())
                    <div class="track-pagination-container">
                        {{ $userOrders->links() }}
                    </div>
                @endif
            </div>

        {{-- If Logged In but No Orders --}}
        @elseif(auth()->check())
            <div class="guest-login-callout" style="background:#ffffff; border: 1.5px solid var(--track-border);">
                <i class="bi bi-bag-x text-muted" style="font-size: 42px; display: block; margin-bottom: 10px;"></i>
                <h3>No Orders Found Yet</h3>
                <p>You haven't placed any orders with THE TREND THEORY yet. Discover our latest oversized drops and streetwear collections!</p>
                <a href="{{ route('shop.index') }}" class="btn-guest-login">
                    <i class="bi bi-bag-fill"></i> Start Shopping
                </a>
            </div>

        {{-- If Guest (Not Logged In) --}}
        @else
            <div class="guest-login-callout">
                <i class="bi bi-person-lock text-primary" style="font-size: 36px; display: block; margin-bottom: 8px;"></i>
                <h3>Looking for All Your Orders?</h3>
                <p>Log in to your account to view all your previous orders, real-time dispatch progress, and download tax invoices instantly.</p>
                <a href="{{ route('login') }}" class="btn-guest-login">
                    <i class="bi bi-box-arrow-in-right"></i> Login to View All Orders
                </a>
            </div>
        @endif

    @endif

</div>

@if(isset($order) && isset($timeline))
{{-- ── Mobile Order Summary & Items Popup Modal ── --}}
<div id="tttMobileSummaryModal" class="tttm-modal-wrapper" style="display:none;">
    <div class="tttm-backdrop" onclick="tttCloseSummaryModal()"></div>
    <div class="tttm-modal-box">
        <div class="tttm-drag-handle" onclick="tttCloseSummaryModal()"><span class="tttm-drag-bar"></span></div>

        {{-- Header --}}
        <div class="tttm-head">
            <div class="tttm-head-left">
                <div class="tttm-head-tag"><i class="bi bi-receipt"></i> ORDER SUMMARY &amp; ITEMS</div>
                <h3 class="tttm-head-title">#{{ $order->order_number }}</h3>
            </div>
            <div class="tttm-head-right">
                <span class="track-status-badge-pill {{ $order->status }}" style="font-size: 11px; padding: 4px 10px;">
                    <i class="bi {{ $order->status === 'delivered' ? 'bi-check-circle-fill' : ($order->status === 'shipped' ? 'bi-truck' : 'bi-shield-check') }}"></i>
                    {{ $timeline['status_label'] }}
                </span>
                <button type="button" class="tttm-btn-close" onclick="tttCloseSummaryModal()" aria-label="Close summary modal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>

        {{-- Scrollable Content Body --}}
        <div class="tttm-scroll-body">
            
            {{-- 1. Items Section --}}
            <div class="tttm-section-block">
                <div class="tttm-sec-label">
                    <span><i class="bi bi-bag-check-fill text-primary"></i> ITEMS ({{ $totalQty }})</span>
                    <span class="badge bg-light text-dark border">₹{{ number_format($subtotalAmount) }}</span>
                </div>
                <div class="tttm-items-list">
                    @foreach($order->items as $item)
                        @php
                            $mItemImg = $item->product_image ?: ($item->product->main_image ?? asset('images/placeholder-product.jpg'));
                            $mItemPrice = (float) $item->unit_price;
                            $mItemSubtotal = (float) ($item->subtotal ?: ($mItemPrice * $item->quantity));
                            $mOrigPrice = ($item->product && $item->product->original_price && $item->product->original_price > $mItemPrice) 
                                ? (float) $item->product->original_price 
                                : ($mItemPrice * 1.35);
                        @endphp
                        <div class="tttm-item-row">
                            <img src="{{ $mItemImg }}" alt="{{ $item->product_name }}" class="tttm-item-thumb" onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                            <div class="tttm-item-info">
                                <a href="{{ $item->product ? route('product.show', $item->product->slug) : '#' }}" class="tttm-item-name">
                                    {{ $item->product_name }}
                                </a>
                                <div class="tttm-item-pills">
                                    @if($item->size)
                                        <span class="item-meta-badge"><i class="bi bi-rulers"></i> {{ $item->size }}</span>
                                    @endif
                                    <span class="item-meta-badge">Qty: {{ $item->quantity }}</span>
                                </div>
                            </div>
                            <div class="tttm-item-pricing">
                                <div class="tttm-item-price-val">₹{{ number_format($mItemSubtotal) }}</div>
                                @if($mOrigPrice > $mItemPrice)
                                    <div class="tttm-item-mrp-strike">₹{{ number_format($mOrigPrice * $item->quantity) }}</div>
                                @endif
                                @if($item->quantity > 1)
                                    <div style="font-size: 10px; color: #94a3b8; text-align: right;">₹{{ number_format($mItemPrice) }}/pc</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 2. Bill Calculation --}}
            <div class="tttm-section-block">
                <div class="tttm-sec-label">
                    <span><i class="bi bi-receipt-cutoff text-primary"></i> PRICE BREAKDOWN</span>
                    <span style="font-size: 11px; color: #059669; font-weight: 700;"><i class="bi bi-patch-check-fill"></i> TAX INVOICE</span>
                </div>
                <div class="tttm-bill-calc">
                    <div class="tttm-calc-row">
                        <span>Total MRP (Original Price)</span>
                        <b>₹{{ number_format($totalMRP) }}</b>
                    </div>
                    @if($mrpDiscount > 0)
                        <div class="tttm-calc-row savings">
                            <span><i class="bi bi-percent"></i> Bag MRP Discount</span>
                            <b>-₹{{ number_format($mrpDiscount) }}</b>
                        </div>
                    @endif
                    <div class="tttm-calc-row">
                        <span>Bag Subtotal ({{ $totalQty }} items)</span>
                        <b>₹{{ number_format($subtotalAmount) }}</b>
                    </div>
                    @if($discountAmount > 0)
                        <div class="tttm-calc-row" style="color:#166534;background:#f0fdf4;padding:6px 10px;border-radius:8px;border:1px dashed #86efac;">
                            <span><i class="bi bi-tag-fill"></i> Coupon Discount {{ $order->coupon_code ? "({$order->coupon_code})" : '' }}</span>
                            <b>-₹{{ number_format($discountAmount) }}</b>
                        </div>
                    @endif
                    <div class="tttm-calc-row {{ $shippingCharge == 0 ? 'savings' : '' }}">
                        <span><i class="bi bi-truck"></i> Shipping / Delivery Charge</span>
                        <b>{{ $shippingCharge == 0 ? 'FREE' : '₹' . number_format($shippingCharge) }}</b>
                    </div>
                    <div class="tttm-calc-row" style="font-size: 11.5px; color: #64748b;">
                        <span>Estimated Taxes (5% GST Included)</span>
                        <span>₹{{ number_format(round($gstAmount)) }}</span>
                    </div>
                    <div class="tttm-calc-total">
                        <div>
                            <div class="tttm-calc-total-label">Final Amount Paid</div>
                            <div style="font-size: 10.5px; color: #64748b; font-weight: 500;">(All taxes &amp; shipping included)</div>
                        </div>
                        <span class="tttm-calc-total-val">₹{{ number_format($totalAmount) }}</span>
                    </div>
                </div>

                @if($totalSavings > 0)
                    <div class="savings-callout-banner" style="margin-top: 14px; padding: 10px 14px; font-size: 12px;">
                        <i class="bi bi-gift-fill" style="font-size: 16px;"></i>
                        <div>You saved <strong>₹{{ number_format($totalSavings) }}</strong> on this order!</div>
                    </div>
                @endif
            </div>

            {{-- 3. Shipping & Payment info --}}
            <div class="tttm-section-block">
                <div class="tttm-sec-label">
                    <span><i class="bi bi-geo-alt-fill text-danger"></i> SHIPPING DESTINATION</span>
                </div>
                <div class="tttm-meta-card">
                    <div class="tttm-addr-line">
                        <strong>{{ $order->shipping_name }}</strong>
                        @if($order->shipping_phone)
                            <span style="color:#64748b;"> &bull; {{ $order->shipping_phone }}</span>
                        @endif
                    </div>
                    <div class="tttm-addr-text">
                        {{ $order->shipping_address }}<br>
                        <strong>{{ $order->shipping_city }}</strong>, {{ $order->shipping_state }} - {{ $order->shipping_pincode }}
                    </div>
                    <div class="tttm-pay-row">
                        <div>
                            <span style="color:#64748b;font-size:11px;display:block;">Payment Method</span>
                            <strong style="text-transform:uppercase;font-size:12.5px;">{{ $order->payment_method ?: 'Online Payment' }}</strong>
                        </div>
                        <span class="pay-badge-status {{ strtolower($order->payment_status) }}">
                            <i class="bi {{ strtolower($order->payment_status) === 'paid' ? 'bi-check-circle-fill' : 'bi-clock-fill' }}"></i>
                            {{ strtoupper($order->payment_status ?: 'PENDING') }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

        {{-- Footer Actions Bar (Pinned at bottom) --}}
        <div class="tttm-actions-bar">
            <a href="{{ route('invoice.download', $order->order_number) }}" target="_blank" class="tttm-btn-inv">
                <i class="bi bi-file-earmark-pdf-fill text-danger"></i>
                <span>Download Invoice</span>
            </a>

            @if(in_array(strtolower($order->status), ['pending', 'confirmed']))
                <button type="button" class="tttm-btn-cancel" onclick="tttCloseSummaryModal(); tttOpenCancelModal('{{ $order->id }}', '{{ $order->order_number }}');">
                    <i class="bi bi-x-circle-fill"></i>
                    <span>Cancel Order</span>
                </button>
            @endif

            <button type="button" class="tttm-btn-close-text" onclick="tttCloseSummaryModal()">
                <span>Close</span>
            </button>
        </div>

    </div>
</div>
@endif

{{-- ── Cancel Order Modal (Responsive Mobile Bottom-Sheet & Desktop Dialog) ── --}}
<div id="tttCancelModal" class="tttc-modal-wrapper" style="display:none;">
    <div class="tttc-backdrop" onclick="tttCloseCancelModal()"></div>
    <div class="tttc-modal-box">
        <div class="tttc-drag-handle" onclick="tttCloseCancelModal()"><span class="tttc-drag-bar"></span></div>

        {{-- Header --}}
        <div class="tttc-head">
            <div class="tttc-icon-ring">
                <i class="bi bi-shield-exclamation"></i>
            </div>
            <div class="tttc-head-content">
                <div class="tttc-tag">Cancellation Request</div>
                <h3 class="tttc-main-title">Cancel this order?</h3>
                <span class="tttc-order-pill" id="tttCancelOrderLabel">Order #---</span>
            </div>
            <button type="button" class="tttc-btn-close" onclick="tttCloseCancelModal()" aria-label="Close cancel modal">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        {{-- Scrollable Body --}}
        <div class="tttc-scrollable-body">
            {{-- Modern Compact Notice Card --}}
            <div class="tttc-notice-card">
                <div class="tttc-notice-icon"><i class="bi bi-info-circle-fill"></i></div>
                <div class="tttc-notice-text">
                    Cancellation cannot be undone. Prepaid amount will be refunded to your original payment method in <strong>5–7 business days</strong>.
                </div>
            </div>

            {{-- Reason Section --}}
            <div class="tttc-body">
                <div class="tttc-field-header">
                    <label class="tttc-label">Please tell us why you are cancelling</label>
                    <span class="tttc-helper">Select one option</span>
                </div>

                <div class="tttc-reasons-list" id="tttCancelChips">
                    <button type="button" class="tttc-reason-card" onclick="tttSelectReason(this, 'Changed my mind')">
                        <div class="tttc-card-left">
                            <span class="tttc-emoji-badge">💭</span>
                            <span class="tttc-reason-title">Changed my mind</span>
                        </div>
                        <span class="tttc-radio-indicator"><i class="bi bi-check2"></i></span>
                    </button>

                    <button type="button" class="tttc-reason-card" onclick="tttSelectReason(this, 'Found better price elsewhere')">
                        <div class="tttc-card-left">
                            <span class="tttc-emoji-badge">🏷️</span>
                            <span class="tttc-reason-title">Found better price elsewhere</span>
                        </div>
                        <span class="tttc-radio-indicator"><i class="bi bi-check2"></i></span>
                    </button>

                    <button type="button" class="tttc-reason-card" onclick="tttSelectReason(this, 'Ordered by mistake')">
                        <div class="tttc-card-left">
                            <span class="tttc-emoji-badge">⚡</span>
                            <span class="tttc-reason-title">Ordered by mistake</span>
                        </div>
                        <span class="tttc-radio-indicator"><i class="bi bi-check2"></i></span>
                    </button>

                    <button type="button" class="tttc-reason-card" onclick="tttSelectReason(this, 'Delivery time is too long')">
                        <div class="tttc-card-left">
                            <span class="tttc-emoji-badge">⏱️</span>
                            <span class="tttc-reason-title">Delivery time is too long</span>
                        </div>
                        <span class="tttc-radio-indicator"><i class="bi bi-check2"></i></span>
                    </button>

                    <button type="button" class="tttc-reason-card" onclick="tttSelectReason(this, 'Wrong item ordered')">
                        <div class="tttc-card-left">
                            <span class="tttc-emoji-badge">📦</span>
                            <span class="tttc-reason-title">Wrong item or size selected</span>
                        </div>
                        <span class="tttc-radio-indicator"><i class="bi bi-check2"></i></span>
                    </button>

                    <button type="button" class="tttc-reason-card" onclick="tttSelectReason(this, 'Other')">
                        <div class="tttc-card-left">
                            <span class="tttc-emoji-badge">✍️</span>
                            <span class="tttc-reason-title">Other reason</span>
                        </div>
                        <span class="tttc-radio-indicator"><i class="bi bi-check2"></i></span>
                    </button>
                </div>

                <div id="tttOtherInputWrap" style="display:none;margin-top:12px;">
                    <textarea id="tttCancelReasonInput"
                        placeholder="Please specify your reason in detail..."
                        maxlength="250"
                        rows="3"
                        class="tttc-textarea"></textarea>
                </div>
            </div>
        </div>

        {{-- Actions Bar (Side-by-side buttons) --}}
        <div class="tttc-actions-bar">
            <button type="button" class="tttc-btn-keep" onclick="tttCloseCancelModal()">
                Keep My Order
            </button>
            <button type="button" class="tttc-btn-confirm" id="tttCancelConfirmBtn" onclick="tttSubmitCancel()">
                <span id="tttCancelBtnText">Confirm Cancel</span>
            </button>
        </div>

    </div>
</div>

<style>
/* ═══════════════════════════════════════════════════════════
   PREMIUM MODAL TYPOGRAPHY & RESPONSIVE BOTTOM-SHEET STYLES
   ═══════════════════════════════════════════════════════════ */
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.tttc-modal-wrapper,
.tttm-modal-wrapper {
    position: fixed;
    inset: 0;
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
    box-sizing: border-box;
}

.tttc-modal-box,
.tttm-modal-box,
.tttc-modal-box *,
.tttm-modal-box * {
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif !important;
    box-sizing: border-box;
}

/* Backdrops */
.tttc-backdrop,
.tttm-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.72);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    animation: tttcFadeIn .22s ease-out both;
}

@keyframes tttcFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Drag handles for mobile touch affordance */
.tttc-drag-handle,
.tttm-drag-handle {
    display: none;
    width: 100%;
    padding: 10px 0 4px;
    text-align: center;
    cursor: pointer;
    flex-shrink: 0;
    user-select: none;
}

.tttc-drag-bar,
.tttm-drag-bar {
    display: inline-block;
    width: 38px;
    height: 4px;
    border-radius: 999px;
    background: #cbd5e1;
    transition: background 0.2s ease;
}

/* Modal Boxes (Desktop default: floating centered card) */
.tttc-modal-box,
.tttm-modal-box {
    position: relative;
    width: 100%;
    max-width: 480px;
    max-height: 90vh;
    background: #ffffff;
    border-radius: 22px;
    box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25), 0 0 0 1px rgba(226, 232, 240, 0.8);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    animation: tttcPopIn .26s cubic-bezier(0.16, 1, 0.3, 1) both;
    z-index: 2;
}

.tttm-modal-box {
    max-width: 540px;
}

@keyframes tttcPopIn {
    from { opacity: 0; transform: scale(0.96) translateY(10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

/* Modal Headers */
.tttc-head {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 18px 22px 14px;
    border-bottom: 1px solid #f1f5f9;
    background: #ffffff;
    flex-shrink: 0;
}

.tttc-icon-ring {
    width: 42px;
    height: 42px;
    min-width: 42px;
    border-radius: 12px;
    background: #fff1f2;
    border: 1px solid #fecdd3;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
    color: #e11d48;
}

.tttc-head-content {
    flex: 1;
    min-width: 0;
}

.tttc-tag {
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.7px;
    color: #e11d48;
    line-height: 1.2;
    margin-bottom: 2px;
}

.tttc-main-title {
    font-size: 17.5px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 3px;
    letter-spacing: -0.02em;
    line-height: 1.25;
}

.tttc-order-pill {
    display: inline-block;
    padding: 2px 8px;
    background: #f1f5f9;
    color: #475569;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.2px;
    font-family: ui-monospace, SFMono-Regular, monospace !important;
}

.tttc-btn-close {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    color: #64748b;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all .15s ease;
    flex-shrink: 0;
}

.tttc-btn-close:hover {
    background: #f1f5f9;
    color: #0f172a;
}

/* Scrollable Inner Bodies */
.tttc-scrollable-body,
.tttm-scroll-body {
    flex: 1 1 auto;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    overscroll-behavior: contain;
}

/* Notice Card in Cancel Modal */
.tttc-notice-card {
    margin: 14px 22px 0;
    background: #fff8f6;
    border: 1px solid #fed7aa;
    border-radius: 12px;
    padding: 11px 14px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.tttc-notice-icon {
    width: 22px;
    height: 22px;
    min-width: 22px;
    border-radius: 6px;
    background: #ffedd5;
    color: #ea580c;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    margin-top: 1px;
}

.tttc-notice-text {
    font-size: 12px;
    color: #7c2d12;
    line-height: 1.45;
    font-weight: 500;
}

.tttc-notice-text strong {
    font-weight: 700;
    color: #9a3412;
}

/* Cancel Reason Selection */
.tttc-body {
    padding: 14px 22px 0;
}

.tttc-field-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
}

.tttc-label {
    font-size: 12px;
    font-weight: 700;
    color: #334155;
    letter-spacing: -0.01em;
    margin: 0;
}

.tttc-helper {
    font-size: 11px;
    color: #94a3b8;
    font-weight: 500;
}

/* Sleek Reason Cards List */
.tttc-reasons-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.tttc-reason-card {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 11px 14px;
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    cursor: pointer;
    transition: all .16s ease;
    text-align: left;
    outline: none;
    width: 100%;
}

.tttc-reason-card:hover {
    border-color: #cbd5e1;
    background: #fafafa;
}

.tttc-card-left {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
    flex: 1;
}

.tttc-emoji-badge {
    font-size: 15px;
    line-height: 1;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.tttc-reason-title {
    font-size: 13px;
    font-weight: 600;
    color: #1e293b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.tttc-radio-indicator {
    width: 20px;
    height: 20px;
    min-width: 20px;
    border-radius: 50%;
    border: 1.5px solid #cbd5e1;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    color: transparent;
    font-size: 13px;
    transition: all .16s ease;
}

.tttc-reason-card.active {
    background: #f8faff;
    border-color: #00285a;
    box-shadow: 0 0 0 1px #00285a;
}

.tttc-reason-card.active .tttc-reason-title {
    color: #00285a;
    font-weight: 700;
}

.tttc-reason-card.active .tttc-radio-indicator {
    border-color: #00285a;
    background: #00285a;
    color: #ffffff;
}

.tttc-textarea {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid #cbd5e1;
    border-radius: 12px;
    font-size: 12.5px;
    font-weight: 500;
    color: #0f172a;
    outline: none;
    box-sizing: border-box;
    transition: border-color .15s ease, box-shadow .15s ease;
    resize: vertical;
}

.tttc-textarea:focus {
    border-color: #00285a;
    box-shadow: 0 0 0 3px rgba(0, 40, 90, 0.08);
}

/* Actions Footer Pinned Bar (Always Side-by-Side) */
.tttc-actions-bar {
    display: grid !important;
    grid-template-columns: 1fr 1fr !important;
    gap: 10px !important;
    padding: 14px 22px 18px !important;
    border-top: 1px solid #f1f5f9 !important;
    background: #ffffff !important;
    flex-shrink: 0 !important;
}

.tttc-btn-keep {
    height: 46px !important;
    border: 1.5px solid #e2e8f0 !important;
    border-radius: 12px !important;
    background: #f8fafc !important;
    color: #334155 !important;
    font-size: 13.5px !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: all .16s ease !important;
    width: 100% !important;
}

.tttc-btn-keep:hover {
    background: #f1f5f9 !important;
    border-color: #cbd5e1 !important;
    color: #0f172a !important;
}

.tttc-btn-keep:active {
    transform: scale(0.985);
}

.tttc-btn-confirm {
    height: 46px !important;
    border: none !important;
    border-radius: 12px !important;
    background: linear-gradient(135deg, #e11d48 0%, #be123c 100%) !important;
    color: #ffffff !important;
    font-size: 13.5px !important;
    font-weight: 700 !important;
    cursor: pointer !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    box-shadow: 0 4px 14px rgba(225, 29, 72, 0.28) !important;
    transition: all .16s ease !important;
    width: 100% !important;
}

.tttc-btn-confirm:hover {
    box-shadow: 0 6px 18px rgba(225, 29, 72, 0.38) !important;
}

.tttc-btn-confirm:active {
    transform: scale(0.985);
}

.tttc-btn-confirm:disabled {
    opacity: 0.65 !important;
    cursor: not-allowed !important;
    transform: none !important;
    box-shadow: none !important;
}

/* ═══════════════════════════════════════════════════════════
   SUMMARY MODAL STYLES (ORDER DETAILS & BILL)
   ═══════════════════════════════════════════════════════════ */
.tttm-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 22px 14px;
    border-bottom: 1px solid #f1f5f9;
    flex-shrink: 0;
}

.tttm-head-left {
    min-width: 0;
    flex: 1;
}

.tttm-head-tag {
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.7px;
    color: var(--track-primary);
    margin-bottom: 2px;
}

.tttm-head-title {
    font-size: 17.5px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    letter-spacing: -0.02em;
}

.tttm-head-right {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.tttm-btn-close {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    color: #64748b;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all .15s ease;
}

.tttm-btn-close:hover {
    background: #f1f5f9;
    color: #0f172a;
}

.tttm-scroll-body {
    padding: 14px 20px;
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.tttm-section-block {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 14px;
}

.tttm-sec-label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 11.5px;
    font-weight: 800;
    letter-spacing: 0.5px;
    color: #334155;
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 1px solid #e2e8f0;
}

.tttm-items-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.tttm-item-row {
    display: grid;
    grid-template-columns: 50px 1fr auto;
    gap: 12px;
    align-items: center;
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 12px;
    padding: 8px 10px;
}

.tttm-item-thumb {
    width: 50px;
    aspect-ratio: 3/4;
    border-radius: 8px;
    object-fit: cover;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
}

.tttm-item-info {
    min-width: 0;
}

.tttm-item-name {
    font-size: 12px;
    font-weight: 700;
    color: #0f172a;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.35;
    text-decoration: none;
}

.tttm-item-pills {
    display: flex;
    gap: 6px;
    margin-top: 3px;
    font-size: 10.5px;
    color: #64748b;
    font-weight: 600;
}

.tttm-item-pricing {
    text-align: right;
    flex-shrink: 0;
}

.tttm-item-price-val {
    font-size: 13.5px;
    font-weight: 800;
    color: var(--track-primary);
}

.tttm-item-mrp-strike {
    font-size: 11px;
    color: #94a3b8;
    text-decoration: line-through;
}

.tttm-bill-calc {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.tttm-calc-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 12.5px;
    color: #475569;
}

.tttm-calc-row b {
    color: #0f172a;
    font-weight: 700;
}

.tttm-calc-row.savings b,
.tttm-calc-row.savings span {
    color: #059669;
    font-weight: 700;
}

.tttm-calc-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1.5px dashed #cbd5e1;
    padding-top: 10px;
    margin-top: 4px;
}

.tttm-calc-total-label {
    font-size: 13px;
    font-weight: 800;
    color: #0f172a;
    text-transform: uppercase;
}

.tttm-calc-total-val {
    font-size: 19px;
    font-weight: 900;
    color: var(--track-primary);
}

.tttm-meta-card {
    font-size: 12px;
    color: #475569;
    line-height: 1.45;
}

.tttm-addr-line strong {
    color: #0f172a;
}

.tttm-pay-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 8px;
    padding-top: 8px;
    border-top: 1px dashed #e2e8f0;
}

.tttm-actions-bar {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 14px 20px 18px;
    border-top: 1px solid #f1f5f9;
    background: #ffffff;
    flex-shrink: 0;
}

.tttm-btn-inv {
    flex: 1;
    height: 44px;
    border: none;
    border-radius: 12px;
    background: #0f172a;
    color: #ffffff !important;
    font-size: 12.5px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: background .15s ease;
}

.tttm-btn-inv:hover {
    background: #1e293b;
}

.tttm-btn-cancel {
    height: 44px;
    padding: 0 14px;
    border: 1.5px solid #fecdd3;
    border-radius: 12px;
    background: #fff1f2;
    color: #e11d48;
    font-size: 12.5px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    transition: all .15s ease;
    white-space: nowrap;
}

.tttm-btn-cancel:hover {
    background: #e11d48;
    color: #ffffff;
}

.tttm-btn-close-text {
    height: 44px;
    padding: 0 14px;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    background: #f8fafc;
    color: #64748b;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all .15s ease;
}

.tttm-btn-close-text:hover {
    background: #f1f5f9;
    color: #0f172a;
}

/* ═══════════════════════════════════════════════════════════
   BUTTONS ON TRACKING PAGE FOR OPENING SUMMARY MODAL
   ═══════════════════════════════════════════════════════════ */
.btn-mobile-open-summary {
    display: none;
    align-items: center;
    gap: 7px;
    background: linear-gradient(135deg, #00285a 0%, #0f4c81 100%);
    color: #ffffff !important;
    border: none;
    border-radius: 10px;
    padding: 9px 15px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(0, 40, 90, 0.2);
    text-transform: uppercase;
    letter-spacing: 0.3px;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}

.btn-mobile-open-summary:active {
    transform: scale(0.97);
}

.btn-mobile-summary-cta {
    display: none;
    background: #ffffff;
    color: var(--track-primary);
    border: 1.5px solid #cbd5e1;
}

.btn-mobile-summary-cta:hover {
    background: #f8fafc;
    border-color: var(--track-primary);
}

/* ═══════════════════════════════════════════════════════════
   RESPONSIVE MODAL POPUP RULES (MOBILE BOTTOM-SHEETS)
   ═══════════════════════════════════════════════════════════ */
@media (max-width: 900px) {
    .btn-mobile-open-summary {
        display: inline-flex !important;
    }
    .btn-mobile-summary-cta {
        display: inline-flex !important;
    }
    .active-tracking-topbar {
        gap: 10px;
    }
    .active-tracking-topbar-info {
        width: 100%;
        order: 3;
    }
}

@media (max-width: 768px) {
    /* Transform modal wrappers into bottom sheet alignment */
    .tttc-modal-wrapper,
    .tttm-modal-wrapper {
        align-items: flex-end !important;
        justify-content: center !important;
        padding: 0 !important;
    }

    /* Transform modal boxes into modern mobile bottom sheets */
    .tttc-modal-box,
    .tttm-modal-box {
        width: 100% !important;
        max-width: 100% !important;
        max-height: 86dvh !important;
        max-height: 86vh !important;
        border-radius: 22px 22px 0 0 !important;
        animation: tttcSlideUp 0.28s cubic-bezier(0.16, 1, 0.3, 1) both !important;
        margin: 0 !important;
        box-shadow: 0 -10px 35px rgba(15, 23, 42, 0.22) !important;
    }

    .tttc-drag-handle,
    .tttm-drag-handle {
        display: block !important;
    }

    .tttc-head {
        padding: 10px 18px 12px !important;
    }

    .tttm-head {
        padding: 12px 18px 12px !important;
    }

    .tttc-notice-card {
        margin: 10px 18px 0 !important;
        padding: 9px 12px !important;
    }

    .tttc-body {
        padding: 12px 18px 0 !important;
    }

    .tttm-scroll-body {
        padding: 12px 16px !important;
    }

    .tttc-reason-card {
        padding: 10px 12px !important;
    }

    .tttc-reason-title {
        font-size: 12.5px !important;
    }

    /* Side-by-side action buttons on mobile */
    .tttc-actions-bar {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 10px !important;
        padding: 12px 16px calc(12px + env(safe-area-inset-bottom, 12px)) !important;
        box-shadow: 0 -4px 16px rgba(15, 23, 42, 0.05) !important;
        background: #ffffff !important;
    }

    .tttc-btn-keep,
    .tttc-btn-confirm {
        height: 46px !important;
        font-size: 13px !important;
        width: 100% !important;
    }

    .tttm-actions-bar {
        padding: 12px 16px calc(12px + env(safe-area-inset-bottom, 12px)) !important;
        box-shadow: 0 -4px 16px rgba(15, 23, 42, 0.05) !important;
        background: #ffffff !important;
    }

    .tttm-btn-inv,
    .tttm-btn-cancel,
    .tttm-btn-close-text {
        height: 44px !important;
    }
}

@keyframes tttcSlideUp {
    from { transform: translateY(100%); opacity: 0.5; }
    to { transform: translateY(0); opacity: 1; }
}
</style>

<script>
// ── Summary Modal Functions ──
function tttOpenSummaryModal() {
    var m = document.getElementById('tttMobileSummaryModal');
    if (m) {
        m.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function tttCloseSummaryModal() {
    var m = document.getElementById('tttMobileSummaryModal');
    if (m) {
        m.style.display = 'none';
        var cancelModal = document.getElementById('tttCancelModal');
        if (!cancelModal || cancelModal.style.display !== 'flex') {
            document.body.style.overflow = '';
        }
    }
}

// ── Cancel Modal Functions ──
var tttCancelOrderId = null, tttCancelOrderNum = null, tttSelectedReason = '';

function tttOpenCancelModal(id, num) {
    tttCancelOrderId = id;
    tttCancelOrderNum = num;
    tttSelectedReason = '';
    var lbl = document.getElementById('tttCancelOrderLabel');
    if (lbl) lbl.textContent = 'Order #' + num;
    var inp = document.getElementById('tttCancelReasonInput');
    if (inp) inp.value = '';
    var wrap = document.getElementById('tttOtherInputWrap');
    if (wrap) wrap.style.display = 'none';
    document.querySelectorAll('.tttc-reason-card, .tttc-reason-opt').forEach(function(c){ c.classList.remove('active'); });
    var m = document.getElementById('tttCancelModal');
    if (m) {
        m.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function tttCloseCancelModal() {
    var m = document.getElementById('tttCancelModal');
    if (m) m.style.display = 'none';
    var summaryModal = document.getElementById('tttMobileSummaryModal');
    if (!summaryModal || summaryModal.style.display !== 'flex') {
        document.body.style.overflow = '';
    }
}

function tttSelectReason(el, reason) {
    document.querySelectorAll('.tttc-reason-card, .tttc-reason-opt').forEach(function(c){ c.classList.remove('active'); });
    el.classList.add('active');
    tttSelectedReason = reason;
    var wrap = document.getElementById('tttOtherInputWrap');
    var inp = document.getElementById('tttCancelReasonInput');
    if (reason === 'Other') {
        if (wrap) wrap.style.display = 'block';
        if (inp) {
            inp.focus();
            setTimeout(function(){
                try { inp.scrollIntoView({ behavior: 'smooth', block: 'center' }); } catch(e){}
            }, 150);
        }
        tttSelectedReason = '';
    } else {
        if (wrap) wrap.style.display = 'none';
        if (inp) inp.value = '';
    }
}

function tttSubmitCancel() {
    var inp = document.getElementById('tttCancelReasonInput');
    var reason = tttSelectedReason || (inp ? inp.value.trim() : '');
    if (!reason) {
        alert('Please select or write a reason for cancellation.');
        return;
    }
    var btn = document.getElementById('tttCancelConfirmBtn');
    if (btn) btn.disabled = true;
    var textEl = document.getElementById('tttCancelBtnText');
    if (textEl) {
        textEl.innerHTML = '<span class="spinner-border spinner-border-sm" style="width:13px;height:13px;border-width:2px;margin-right:6px"></span> Cancelling...';
    }
    var csrfMeta = document.querySelector('meta[name="csrf-token"]');
    var csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';

    fetch('/orders/' + tttCancelOrderId + '/cancel', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ cancel_reason: reason })
    }).then(function(r){ return r.json(); }).then(function(d){
        tttCloseCancelModal();
        if (d.success) {
            var t = document.createElement('div');
            t.style.cssText = 'position:fixed;bottom:max(24px, env(safe-area-inset-bottom, 24px));left:50%;transform:translateX(-50%);background:#0f172a;color:#fff;padding:12px 24px;border-radius:30px;font-size:13px;font-weight:700;z-index:999999;box-shadow:0 12px 30px rgba(0,0,0,.35);display:flex;align-items:center;gap:8px;width:max-content;max-width:90vw;';
            t.innerHTML = '<i class="bi bi-check-circle-fill" style="color:#22c55e;font-size:16px"></i> Order #' + tttCancelOrderNum + ' cancelled!';
            document.body.appendChild(t);
            setTimeout(function(){
                if (d.redirect) window.location.href = d.redirect;
                else window.location.reload();
            }, 1800);
        } else {
            alert(d.message || 'Could not cancel. Please try again.');
            if (btn) btn.disabled = false;
            if (textEl) textEl.textContent = 'Confirm Cancel';
        }
    }).catch(function(){
        tttCloseCancelModal();
        alert('Connection error. Please try again.');
        if (btn) btn.disabled = false;
        if (textEl) textEl.textContent = 'Confirm Cancel';
    });
}

// ── Keyboard & Swipe Handlers ──
document.addEventListener('keydown', function(e){
    if (e.key === 'Escape') {
        tttCloseCancelModal();
        tttCloseSummaryModal();
    }
});

// Mobile touch swipe-down dismiss on drag handles
(function setupSwipeDismiss() {
    function bindSwipe(handleId, closeFn) {
        var el = document.querySelector(handleId);
        if (!el) return;
        var startY = 0;
        el.addEventListener('touchstart', function(e){
            startY = e.touches[0].clientY;
        }, { passive: true });
        el.addEventListener('touchend', function(e){
            var endY = e.changedTouches[0].clientY;
            if (endY - startY > 40) {
                closeFn();
            }
        }, { passive: true });
    }
    document.addEventListener('DOMContentLoaded', function(){
        bindSwipe('.tttc-drag-handle', tttCloseCancelModal);
        bindSwipe('.tttm-drag-handle', tttCloseSummaryModal);
    });
})();
</script>

@endsection
