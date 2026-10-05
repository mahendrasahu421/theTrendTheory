{{-- resources/views/froentend/cart/checkout.blade.php --}}
@extends('froentend.layouts.app')

@push('seo')
    <title>Secure Luxury Checkout | THE TREND THEORY</title>
    <meta name="description" content="Complete your luxury streetwear order securely with THE TREND THEORY. Fast express delivery, instant discounts & 100% secure checkout.">
    <meta name="robots" content="noindex, nofollow">
@endpush

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   ULTRA-LUXURY MODERN CHECKOUT SYSTEM - THE TREND THEORY
   ═══════════════════════════════════════════════════════════ */
:root {
    --co-navy: #14213d;
    --co-navy-dark: #0b1020;
    --co-navy-light: #2563eb;
    --co-accent: #ff3f6c;
    --co-accent-hover: #e62e5b;
    --co-emerald: #059669;
    --co-emerald-bg: #ecfdf5;
    --co-emerald-border: #a7f3d0;
    --co-amber: #d97706;
    --co-amber-bg: #fffbeb;
    --co-bg: #f8fafc;
    --co-card-bg: #ffffff;
    --co-border: #e2e8f0;
    --co-border-hover: #cbd5e1;
    --co-text-main: #0f172a;
    --co-text-muted: #64748b;
}

.checkout-main-wrapper {
    background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
    min-height: 100vh;
    padding: 36px 0 100px;
    font-family: -apple-system, BlinkMacSystemFont, "Plus Jakarta Sans", "Segoe UI", Roboto, sans-serif;
    color: var(--co-text-main);
}

.checkout-container {
    max-width: 1260px;
    margin: 0 auto;
    padding: 0 20px;
}

/* ── Top Header & Stepper Bar ── */
.checkout-header-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 18px;
    margin-bottom: 32px;
    padding: 20px 24px;
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid var(--co-border);
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
}

.checkout-brand-title-wrap {
    display: flex;
    align-items: center;
    gap: 14px;
}

.checkout-brand-title {
    font-family: 'Cinzel', serif !important;
    font-size: 20px;
    font-weight: 900;
    color: var(--co-navy);
    letter-spacing: 1.2px;
    margin: 0;
    text-transform: uppercase;
}

.checkout-secure-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--co-emerald-bg);
    color: var(--co-emerald);
    border: 1px solid var(--co-emerald-border);
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.4px;
}

.checkout-stepper-pills {
    display: flex;
    align-items: center;
    gap: 10px;
}

.stepper-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 700;
    color: var(--co-text-muted);
    padding: 6px 14px;
    border-radius: 999px;
    background: #f1f5f9;
}

.stepper-pill.done {
    background: var(--co-emerald-bg);
    color: var(--co-emerald);
}

.stepper-pill.active {
    background: var(--co-navy);
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(0, 40, 90, 0.25);
}

.stepper-arrow {
    color: #cbd5e1;
    font-size: 11px;
}

/* ── Grid Layout ── */
.checkout-two-columns {
    display: grid;
    grid-template-columns: 1fr 440px;
    gap: 32px;
    align-items: start;
}

@media (max-width: 992px) {
    .checkout-two-columns {
        grid-template-columns: 1fr;
        gap: 28px;
    }
}

/* ── Left Column Panels ── */
.co-panel {
    background: #ffffff;
    border: 1.5px solid var(--co-border);
    border-radius: 20px;
    padding: 28px;
    margin-bottom: 24px;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
    transition: all 0.25s ease;
}

.co-panel-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
    padding-bottom: 16px;
    border-bottom: 1.5px solid #f1f5f9;
}

.co-panel-title {
    font-family: 'Cinzel', serif !important;
    font-size: 16px;
    font-weight: 900;
    color: var(--co-navy);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
    letter-spacing: 0.8px;
    text-transform: uppercase;
}

.co-panel-icon-circle {
    display: none;
}

/* ── Form Styling ── */
.co-form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
    margin-bottom: 18px;
}

@media (max-width: 600px) {
    .co-form-row {
        grid-template-columns: 1fr;
        gap: 14px;
    }
}

.co-input-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-bottom: 18px;
    position: relative;
}

.co-input-label {
    font-size: 11.5px;
    font-weight: 700;
    color: #475569;
    letter-spacing: 0.3px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.co-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
}

.co-input-icon {
    display: none !important;
}

.co-input-field {
    width: 100%;
    height: 48px;
    padding: 0 16px;
    background: #ffffff;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    color: var(--co-text-main);
    transition: all 0.2s ease;
    outline: none;
}

.co-input-field:focus {
    border-color: #0f172a;
    box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.08);
    background: #ffffff;
}

.co-textarea-field {
    width: 100%;
    min-height: 84px;
    padding: 12px 16px;
    background: #ffffff;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    color: var(--co-text-main);
    transition: all 0.2s ease;
    outline: none;
    resize: vertical;
}

.co-textarea-field:focus {
    border-color: #0f172a;
    box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.08);
}

/* ── Modern Payment Cards with Brand Badges ── */
.payment-selector-stack {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.payment-method-card {
    border: 2px solid var(--co-border);
    border-radius: 18px;
    padding: 22px;
    cursor: pointer;
    background: #ffffff;
    transition: all 0.22s ease;
    position: relative;
    overflow: hidden;
}

.payment-method-card.active {
    border-color: var(--co-navy);
    background: #f8fbff;
    box-shadow: 0 8px 24px rgba(0, 40, 90, 0.1);
}

.payment-method-card.active::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 6px;
    background: var(--co-navy);
}

.pm-card-top-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 14px;
    margin-bottom: 14px;
}

.pm-left-side {
    display: flex;
    align-items: flex-start;
    gap: 14px;
}

.pm-radio-disc {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    border: 2px solid #cbd5e1;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 2px;
    transition: all 0.2s ease;
    background: #ffffff;
}

.payment-method-card.active .pm-radio-disc {
    border-color: var(--co-navy);
    background: var(--co-navy);
}

.payment-method-card.active .pm-radio-disc::after {
    content: '';
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #ffffff;
}

.pm-details h4 {
    font-size: 15px;
    font-weight: 800;
    color: var(--co-text-main);
    margin: 0 0 4px;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.pm-details p {
    font-size: 12.5px;
    color: var(--co-text-muted);
    margin: 0;
    line-height: 1.45;
}

.pm-offer-pill {
    background: var(--co-emerald-bg);
    color: var(--co-emerald);
    border: 1px solid var(--co-emerald-border);
    font-size: 10.5px;
    font-weight: 800;
    padding: 3px 8px;
    border-radius: 6px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

/* ── Payment Brand SVGs Logos Strip ── */
.pm-brand-logos-row {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    padding-top: 12px;
    border-top: 1px dashed #e2e8f0;
}

.pm-brand-badge {
    height: 28px;
    padding: 0 10px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 800;
    color: #334155;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}

.pm-brand-badge svg {
    height: 16px;
    width: auto;
    max-width: 42px;
    display: block;
}

/* ── Right Column: Order Summary ── */
.co-sidebar-summary {
    background: #ffffff;
    border: 1.5px solid var(--co-border);
    border-radius: 22px;
    padding: 26px;
    position: sticky;
    top: 24px;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
}

.co-summary-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
    padding-bottom: 16px;
    border-bottom: 1.5px solid #f1f5f9;
}

.co-summary-title {
    font-family: 'Cinzel', serif !important;
    font-size: 16px;
    font-weight: 900;
    color: var(--co-navy);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
    letter-spacing: 0.6px;
}

.co-summary-toggle-icon {
    font-size: 13px;
    color: var(--co-navy);
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}

.co-summary-edit-link {
    font-size: 12px;
    font-weight: 800;
    color: var(--co-navy);
    text-decoration: none;
    background: #f1f5f9;
    padding: 4px 10px;
    border-radius: 6px;
    transition: all 0.15s ease;
}

/* Detailed Cart Items Scroll Box */
.co-cart-items-scroll {
    max-height: 280px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 20px;
    padding-right: 4px;
}

.co-cart-item-row {
    display: grid;
    grid-template-columns: 60px 1fr auto;
    gap: 14px;
    align-items: center;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 10px 12px;
    transition: border-color 0.2s ease;
}

.co-cart-item-thumb {
    width: 60px;
    aspect-ratio: 3/4;
    border-radius: 10px;
    overflow: hidden;
    background: #e2e8f0;
    border: 1px solid #cbd5e1;
    flex-shrink: 0;
}

.co-cart-item-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.co-cart-item-info {
    min-width: 0;
}

.co-cart-item-name {
    font-size: 13.5px;
    font-weight: 700;
    color: var(--co-text-main);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 4px;
}

.co-cart-item-meta {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    color: var(--co-text-muted);
}

.co-item-pill {
    background: #e2e8f0;
    color: #334155;
    padding: 1px 6px;
    border-radius: 4px;
    font-weight: 700;
}

.co-cart-item-price-block {
    text-align: right;
}

.co-cart-item-price {
    font-size: 14px;
    font-weight: 800;
    color: var(--co-navy);
}

.co-cart-item-unit-price {
    font-size: 11px;
    color: var(--co-text-muted);
}

/* Coupon Box */
.co-coupon-apply-wrap {
    background: #f8fafc;
    border: 1.5px dashed #cbd5e1;
    border-radius: 14px;
    padding: 14px;
    margin-bottom: 20px;
}

.co-coupon-input-row {
    display: flex;
    gap: 8px;
    margin-bottom: 8px;
}

.co-coupon-input-row input {
    flex: 1;
    height: 40px;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 0 14px;
    font-size: 12.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    outline: none;
}

.co-coupon-input-row input:focus {
    border-color: var(--co-navy);
}

.co-coupon-input-row button {
    background: var(--co-navy);
    color: #ffffff;
    border: none;
    border-radius: 8px;
    padding: 0 18px;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: background 0.2s ease;
}

.co-quick-coupon-pills {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    color: var(--co-text-muted);
    flex-wrap: wrap;
}

.co-quick-pill {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    color: var(--co-navy);
    font-weight: 800;
    padding: 2px 8px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.15s ease;
}

/* Comprehensive Price Breakdown */
/* ── Modern Order Summary (Screenshot Matched) ── */
.co-summary-header-right {
    display: flex;
    align-items: center;
    gap: 8px;
}

.co-saved-badge {
    background: #e6f9f0;
    color: #00875a;
    font-size: 12px;
    font-weight: 600;
    padding: 3px 9px;
    border-radius: 6px;
    letter-spacing: 0.1px;
    display: inline-flex;
    align-items: center;
    white-space: nowrap;
}

.co-summary-breakdown-card {
    display: flex;
    flex-direction: column;
    gap: 13px;
    padding: 16px 0 14px;
}

.co-summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13.5px;
    color: #64748b;
    line-height: 1.4;
}

.co-summary-row .row-label {
    color: #64748b;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.co-summary-row .row-val {
    color: #1e293b;
    font-weight: 600;
    font-variant-numeric: tabular-nums;
}

.co-summary-row.is-discount .row-val,
.co-summary-row.is-savings .row-val {
    color: #00a76f !important;
    font-weight: 600;
}

.co-info-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    font-size: 11px;
    font-style: normal;
    color: #94a3b8;
    border: 1px solid #cbd5e1;
    cursor: help;
    user-select: none;
    line-height: 1;
    margin-left: 3px;
}

.co-summary-divider {
    height: 1px;
    background: #eef2f6;
    margin: 6px 0 8px;
}

.co-estimated-total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.co-estimated-label {
    font-size: 16px;
    font-weight: 700;
    color: #1e293b;
}

.co-estimated-val {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
    font-variant-numeric: tabular-nums;
    letter-spacing: -0.3px;
}

/* Primary CTA Button */
.btn-checkout-primary-cta {
    width: 100%;
    height: 56px;
    background: linear-gradient(135deg, var(--co-navy) 0%, var(--co-navy-light) 100%);
    color: #ffffff !important;
    border: none;
    border-radius: 14px;
    font-family: 'Cinzel', serif !important;
    font-size: 14.5px;
    font-weight: 800;
    letter-spacing: 0.8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    box-shadow: 0 8px 24px rgba(0, 40, 90, 0.25);
    transition: all 0.25s ease;
    text-decoration: none !important;
}

/* Trust Badges Strip */
.co-trust-strip {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-top: 18px;
    padding-top: 14px;
    border-top: 1px dashed #e2e8f0;
    font-size: 11px;
    color: var(--co-text-muted);
    font-weight: 700;
}

.co-trust-strip span {
    display: flex;
    align-items: center;
    gap: 5px;
}

/* ── Checkout FAQ Accordion ── */
.co-faq-panel {
    background: #ffffff;
}

.co-faq-accordion-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.co-faq-item {
    background: #f8fafc;
    border: 1px solid var(--co-border);
    border-radius: 14px;
    overflow: hidden;
    transition: all 0.22s ease;
}

.co-faq-item.open {
    background: #ffffff;
    border-color: #cbd5e1;
    box-shadow: 0 4px 16px rgba(0, 40, 90, 0.05);
}

.co-faq-btn {
    width: 100%;
    padding: 14px 18px;
    background: none;
    border: none;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    font-size: 13.5px;
    font-weight: 700;
    color: var(--co-text-main);
    cursor: pointer;
    text-align: left;
}

.co-faq-btn span {
    display: flex;
    align-items: center;
    gap: 8px;
}

.co-faq-chevron {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: var(--co-text-muted);
    transition: transform 0.25s ease;
    flex-shrink: 0;
}

.co-faq-item.open .co-faq-chevron {
    transform: rotate(180deg);
    color: var(--co-navy);
}

.co-faq-body {
    display: none;
    padding: 0 18px 16px 42px;
    font-size: 12.5px;
    color: #475569;
    line-height: 1.6;
}

.co-faq-item.open .co-faq-body {
    display: block;
}

.order-place-loader {
    position: fixed;
    inset: 0;
    z-index: 13000;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background: rgba(15, 23, 42, 0.62);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

.order-place-loader.is-active {
    display: flex;
}

.order-place-loader__card {
    width: min(360px, 100%);
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 24px;
    text-align: center;
    box-shadow: 0 24px 70px rgba(15, 23, 42, 0.28);
}

.order-place-loader__spinner {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    border: 3px solid #e2e8f0;
    border-top-color: #00285a;
    animation: coSpin 0.8s linear infinite;
    margin: 0 auto 14px;
}

.order-place-loader.is-success .order-place-loader__spinner {
    position: relative;
    border-color: #00285a;
    background: #00285a;
    animation: none;
}

.order-place-loader.is-success .order-place-loader__spinner::after {
    content: "";
    position: absolute;
    left: 14px;
    top: 8px;
    width: 12px;
    height: 22px;
    border: solid #ffffff;
    border-width: 0 3px 3px 0;
    transform: rotate(45deg);
}

.order-place-loader__title {
    font-family: 'Cinzel', serif !important;
    font-size: 18px;
    font-weight: 900;
    color: #00285a;
    margin-bottom: 6px;
}

.order-place-loader__text {
    font-size: 13px;
    font-weight: 600;
    color: #64748b;
    line-height: 1.5;
}

/* Checkout refresh: clean, focused, scan-friendly */
.checkout-main-wrapper {
    background:
        radial-gradient(circle at top left, rgba(37, 99, 235, 0.08), transparent 34%),
        linear-gradient(180deg, #ffffff 0%, #f6f8fb 42%, #f3f6fa 100%);
    padding: 24px 0 88px;
}

.checkout-container {
    max-width: 1180px;
}

.checkout-header-bar {
    border-radius: 10px;
    padding: 16px 18px;
    margin-bottom: 20px;
    border: 1px solid #e6eaf0;
    box-shadow: none;
}

.checkout-brand-title {
    font-family: inherit !important;
    font-size: 22px;
    letter-spacing: 0;
}

.checkout-secure-badge,
.stepper-pill,
.pm-offer-pill,
.co-summary-edit-link,
.co-item-pill {
    border-radius: 7px;
    letter-spacing: 0;
}

.checkout-secure-badge {
    background: #f0fdf4;
    color: #047857;
    padding: 6px 10px;
}

.checkout-stepper-pills {
    gap: 8px;
}

.stepper-pill {
    padding: 8px 11px;
    font-size: 12px;
}

.stepper-pill.active {
    background: #14213d;
    box-shadow: none;
}

.checkout-two-columns {
    grid-template-columns: minmax(0, 1fr) 390px;
    gap: 22px;
}

.co-panel,
.co-sidebar-summary {
    border-radius: 10px;
    border: 1px solid #e6eaf0;
    box-shadow: 0 8px 30px rgba(15, 23, 42, 0.045);
}

.co-panel {
    padding: 22px;
    margin-bottom: 18px;
}

.co-panel-head,
.co-summary-header {
    padding-bottom: 14px;
    margin-bottom: 18px;
    border-bottom: 1px solid #edf0f4;
}

.co-panel-title,
.co-summary-title,
.co-final-total-val,
.btn-checkout-primary-cta,
.order-place-loader__title {
    font-family: inherit !important;
    letter-spacing: 0;
}

.co-panel-title,
.co-summary-title {
    font-size: 15px;
    text-transform: none;
    color: #111827;
}

.co-panel-icon-circle {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    background: #eff6ff;
    color: #2563eb;
    box-shadow: none;
}

.co-form-row {
    gap: 14px;
    margin-bottom: 14px;
}

.co-input-group {
    gap: 7px;
    margin-bottom: 14px;
}

.co-input-label {
    color: #334155;
    font-size: 12px;
    letter-spacing: 0;
    text-transform: none;
}

.co-input-field,
.co-textarea-field {
    border-radius: 8px;
    border: 1px solid #dbe2ea;
    background: #fbfcfe;
    font-size: 14px;
    font-weight: 600;
}

.co-input-field {
    height: 48px;
}

.co-input-field:focus,
.co-textarea-field:focus,
.co-coupon-input-row input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
}

.co-input-wrapper:focus-within .co-input-icon {
    color: #2563eb;
}

.payment-selector-stack {
    gap: 12px;
}

.payment-method-card {
    border-radius: 10px;
    padding: 16px;
    border: 1px solid #dbe2ea;
    box-shadow: none;
}

.payment-method-card.active {
    border-color: #2563eb;
    background: #f8fbff;
    box-shadow: 0 8px 24px rgba(37, 99, 235, 0.1);
}

.payment-method-card.active::before {
    width: 4px;
    background: #2563eb;
}

.payment-method-card.active .pm-radio-disc {
    border-color: #2563eb;
    background: #2563eb;
}

.pm-details h4 {
    font-size: 14.5px;
}

.pm-details p {
    color: #64748b;
}

.pm-brand-logos-row {
    padding-top: 10px;
}

.pm-brand-badge {
    border-radius: 6px;
    box-shadow: none;
}

.co-faq-panel {
    padding: 18px;
}

.co-faq-accordion-list {
    gap: 8px;
}

.co-faq-item {
    border-radius: 8px;
    background: #ffffff;
}

.co-faq-item.open {
    box-shadow: none;
}

.co-faq-btn {
    padding: 12px 14px;
    font-size: 13px;
}

.co-faq-body {
    padding: 0 14px 14px 38px;
}

.co-sidebar-summary {
    top: 18px;
    padding: 20px;
}

.co-cart-items-scroll {
    max-height: 310px;
}

.co-cart-item-row {
    grid-template-columns: 54px 1fr auto;
    gap: 11px;
    border-radius: 8px;
    background: #fbfcfe;
}

.co-cart-item-thumb {
    width: 54px;
    border-radius: 7px;
}

.co-cart-item-meta {
    flex-wrap: wrap;
}

.co-coupon-apply-wrap,
.co-bill-breakdown-box,
.co-savings-highlight-banner {
    border-radius: 8px;
}

.co-savings-highlight-banner {
    background: #ecfdf5;
}

.co-bill-breakdown-box {
    gap: 10px;
    padding: 15px 0;
}

.co-bill-row {
    font-size: 13px;
}

.co-final-total-row {
    align-items: center;
    margin-bottom: 16px;
}

.co-final-total-label {
    font-size: 13px;
    text-transform: none;
}

.co-final-total-val {
    font-size: 27px;
    color: #111827;
}

.btn-checkout-primary-cta {
    height: 54px;
    border-radius: 8px;
    background: #111827;
    box-shadow: 0 14px 28px rgba(17, 24, 39, 0.16);
    font-size: 14px;
}

.btn-checkout-primary-cta:hover {
    background: #2563eb;
    transform: translateY(-1px);
}

.co-trust-strip {
    justify-content: center;
    flex-wrap: wrap;
    gap: 8px 14px;
}

@media (max-width: 992px) {
    .checkout-main-wrapper {
        padding-top: 14px;
    }

    .checkout-two-columns {
        grid-template-columns: 1fr;
    }

    .checkout-right-column {
        order: -1;
    }

    .co-sidebar-summary {
        position: static;
    }
}

@media (max-width: 640px) {
    .checkout-container {
        padding: 0 12px;
    }

    .checkout-header-bar {
        align-items: flex-start;
        padding: 14px;
    }

    .checkout-brand-title-wrap {
        width: 100%;
        justify-content: space-between;
    }

    .checkout-secure-badge {
        font-size: 10px;
        padding: 5px 7px;
    }

    .checkout-stepper-pills {
        width: 100%;
        overflow-x: auto;
        padding-bottom: 2px;
    }

    .stepper-pill {
        white-space: nowrap;
        font-size: 11px;
    }

    .co-panel,
    .co-sidebar-summary {
        padding: 16px;
    }

    .co-panel-head,
    .co-summary-header {
        align-items: flex-start;
        gap: 10px;
    }

    .co-panel-title {
        font-size: 14px;
    }

    .pm-card-top-row,
    .pm-left-side {
        gap: 10px;
    }

    .pm-brand-logos-row {
        gap: 6px;
    }

    .co-final-total-val {
        font-size: 23px;
    }
}

/* ── Mobile Collapsible Sections (Delivery Details & Payment Method) ── */
.co-panel-collapse-chevron {
    display: none;
}

.mobile-section-summary {
    display: none;
}

@media (min-width: 769px) {
    .co-collapsible-panel .co-panel-collapse-body {
        max-height: none !important;
        opacity: 1 !important;
        overflow: visible !important;
        display: block !important;
    }
}

@media (max-width: 768px) {
    .co-collapsible-panel {
        transition: all 0.25s ease;
    }

    .co-collapsible-panel .co-panel-head {
        cursor: pointer;
        user-select: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        padding-bottom: 14px;
        transition: margin-bottom 0.25s ease, padding-bottom 0.25s ease;
    }

    .co-collapsible-panel .co-panel-head-left {
        display: flex;
        flex-direction: column;
        gap: 6px;
        flex: 1;
        min-width: 0;
    }

    .co-collapsible-panel .co-panel-head-title-row {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .co-panel-collapse-chevron {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #475569;
        font-size: 13px;
        transition: transform 0.28s ease, background 0.2s ease, color 0.2s ease;
        flex-shrink: 0;
        margin-left: 10px;
    }

    .co-collapsible-panel.is-open .co-panel-collapse-chevron {
        transform: rotate(180deg);
        background: #eff6ff;
        color: #2563eb;
    }

    .co-collapsible-panel .co-panel-collapse-body {
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.25s ease;
    }

    .co-collapsible-panel.is-open .co-panel-collapse-body {
        max-height: 1800px;
        opacity: 1;
        overflow: visible;
    }

    .co-collapsible-panel:not(.is-open) {
        padding-bottom: 16px !important;
    }

    .co-collapsible-panel:not(.is-open) .co-panel-head {
        margin-bottom: 0 !important;
        border-bottom: none !important;
        padding-bottom: 0 !important;
    }

    .mobile-section-summary {
        display: inline-block;
        font-size: 11.5px;
        color: #2563eb;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-top: 2px;
        background: #f0f7ff;
        border: 1px solid #dbeafe;
        padding: 3px 8px;
        border-radius: 6px;
        max-width: fit-content;
    }

    .co-collapsible-panel.is-open .mobile-section-summary {
        display: none !important;
    }
}

/* ── Order Summary Collapsible on Mobile ── */
.co-summary-mobile-total {
    display: none;
}

@media (min-width: 769px) {
    .co-summary-collapsible-body {
        display: block !important;
        max-height: none !important;
        opacity: 1 !important;
        overflow: visible !important;
    }
}

@media (max-width: 768px) {
    .co-sidebar-summary {
        padding: 20px 18px !important;
        border-radius: 16px;
        margin-bottom: 22px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 14px rgba(15, 23, 42, 0.05);
        background: #ffffff;
    }

    .co-summary-header {
        padding: 0 0 14px 0 !important;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #f1f5f9 !important;
        background: transparent !important;
        cursor: default !important;
    }

    .co-summary-collapsible-body {
        display: block !important;
        max-height: none !important;
        opacity: 1 !important;
        overflow: visible !important;
        padding: 0 !important;
    }

    .co-cart-items-scroll {
        display: flex !important;
    }
}

/* ── Fixed Bottom Sticky Pay Bar on Mobile (Full-Width Button & Collapsible Drawer) ── */
.checkout-mobile-sticky-wrap {
    display: none;
}

@media (max-width: 991.98px) {
    .checkout-main-wrapper {
        padding-bottom: calc(130px + env(safe-area-inset-bottom, 0px)) !important;
    }

    .checkout-right-column .btn-checkout-primary-cta {
        display: none !important; /* On mobile, the sticky bottom full-width button is the primary CTA */
    }

    .checkout-mobile-sticky-wrap {
        display: block !important;
        position: fixed !important;
        bottom: 0 !important;
        left: 0 !important;
        right: 0 !important;
        width: 100% !important;
        z-index: 99999 !important;
        pointer-events: none;
    }

    /* Dim backdrop behind the collapsible bill drawer */
    .mobile-sticky-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.25s ease, visibility 0.25s ease;
        z-index: 10001;
        pointer-events: none;
    }

    .mobile-sticky-backdrop.is-open {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    /* Bottom sticky container */
    .mobile-sticky-card {
        position: relative;
        background: #ffffff;
        border-top: 1px solid #e2e8f0;
        box-shadow: 0 -10px 32px rgba(15, 23, 42, 0.16);
        padding: 10px 14px calc(10px + env(safe-area-inset-bottom, 0px));
        z-index: 10002;
        pointer-events: auto;
        border-top-left-radius: 18px;
        border-top-right-radius: 18px;
    }

    /* Collapsible Bill Drawer */
    .mobile-sticky-drawer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.32s cubic-bezier(0.4, 0, 0.2, 1), padding 0.32s ease, opacity 0.25s ease;
        opacity: 0;
        border-bottom: 0 solid #f1f5f9;
    }

    .mobile-sticky-drawer.is-open {
        max-height: 450px;
        opacity: 1;
        padding-bottom: 12px;
        margin-bottom: 10px;
        border-bottom: 1.5px solid #f1f5f9;
    }

    .mobile-drawer-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 8px;
        margin-bottom: 8px;
        border-bottom: 1px dashed #e2e8f0;
    }

    .mobile-drawer-title {
        font-size: 13px;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .mobile-drawer-close {
        background: #f1f5f9;
        border: none;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        font-size: 12px;
        cursor: pointer;
    }

    .mobile-drawer-body {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .mobile-drawer-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12.5px;
        color: #475569;
    }

    .mobile-drawer-row strong {
        font-weight: 700;
        color: #0f172a;
    }

    .mobile-drawer-row.text-success strong {
        color: #059669;
    }

    .mobile-drawer-divider {
        height: 1px;
        background: #f1f5f9;
        margin: 4px 0;
    }

    .mobile-drawer-total-row {
        font-size: 13.5px;
        padding-top: 2px;
    }

    .mobile-drawer-total-label {
        font-weight: 800;
        color: #0f172a;
    }

    .mobile-drawer-total-val {
        font-family: 'Cinzel', serif !important;
        font-size: 20px;
        font-weight: 900;
        color: #00285a;
    }

    .mobile-drawer-savings {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 11.5px;
        font-weight: 700;
        text-align: center;
        margin-top: 4px;
    }

    /* Sticky Bar Header (Total + Collapse Trigger) */
    .mobile-sticky-bar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 8px;
        cursor: pointer;
        user-select: none;
    }

    .mobile-sticky-header-left {
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .mobile-sticky-total-label {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
    }

    .mobile-sticky-amount {
        font-family: 'Cinzel', serif !important;
        font-size: 19px;
        font-weight: 900;
        color: #00285a;
        line-height: 1;
    }

    .badge-saving-pill {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
        font-size: 10px;
        font-weight: 800;
        padding: 2px 7px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }

    .badge-cod-pill {
        background: #f1f5f9;
        color: #475569;
        font-size: 10px;
        font-weight: 800;
        padding: 2px 7px;
        border-radius: 6px;
    }

    .mobile-view-bill-btn {
        font-size: 11.5px;
        font-weight: 800;
        color: #2563eb;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        padding: 4px 10px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s ease;
    }

    .mobile-view-bill-btn .co-mobile-toggle-chevron {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.25s ease;
    }

    .mobile-view-bill-btn .co-mobile-toggle-chevron.rotated {
        transform: rotate(180deg);
    }

    /* Full-Width Mobile CTA Button */
    .mobile-sticky-btn-row {
        width: 100%;
    }

    .btn-checkout-mobile-cta-full {
        width: 100% !important;
        height: 52px;
        background: linear-gradient(135deg, #001838 0%, #00285a 100%);
        color: #ffffff !important;
        border: none;
        border-radius: 12px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 13.5px;
        font-weight: 800;
        letter-spacing: 0.5px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        box-shadow: 0 4px 18px rgba(0, 40, 90, 0.35);
        transition: all 0.2s ease;
        text-transform: uppercase;
    }

    .btn-checkout-mobile-cta-full:active {
        transform: scale(0.99);
        opacity: 0.95;
    }

    /* iOS Safari zoom prevention on inputs */
    .co-input-field,
    .co-textarea-field {
        font-size: 16px !important;
    }
}

@keyframes coSpin {
    100% { transform: rotate(360deg); }
}
.co-spin {
    display: inline-block;
    animation: coSpin 0.8s linear infinite;
}

.order-place-loader.is-success .order-place-loader__spinner::after {
    transform: rotate(45deg) !important;
}
</style>
@endpush

@section('main')
@php
    $totalItemsCount = collect($cart)->sum(fn($item) => (int) ($item['quantity'] ?? 1));
    $mrpTotal = 0;
    foreach($cart as $cItem) {
        $cQty = (int)($cItem['quantity'] ?? 1);
        $cPrice = (float)($cItem['price'] ?? 0);
        $cOrig = !empty($cItem['original_price']) && (float)$cItem['original_price'] > $cPrice 
            ? (float)$cItem['original_price'] 
            : null;
        if (!$cOrig && !empty($cItem['id'])) {
            $pModel = \App\Models\Product::find($cItem['id']);
            if ($pModel && $pModel->original_price && (float)$pModel->original_price > $cPrice) {
                $cOrig = (float)$pModel->original_price;
            }
        }
        $cOrig = $cOrig ?: $cPrice;
        $mrpTotal += ($cOrig * $cQty);
    }
    $mrpDiscount = max(0, $mrpTotal - $subtotal);
    $prepaidDiscount = round($subtotal * 0.05, 2);
    $prepaidTotal = max(1, round(($total - $prepaidDiscount), 2));
    $baseTotal = max(1, round($total, 2));
    $totalSavings = round($mrpDiscount + $couponDiscount + $prepaidDiscount, 2);
@endphp
<div class="checkout-main-wrapper">
    <div class="checkout-container">

        {{-- ── 1. Top Header & Segmented Stepper ── --}}
        <div class="checkout-header-bar">
            <div class="checkout-brand-title-wrap">
                <h1 class="checkout-brand-title">THE TREND THEORY</h1>
                <div class="checkout-secure-badge">
                    100% ENCRYPTED CHECKOUT
                </div>
            </div>

            <div class="checkout-stepper-pills">
                <a href="{{ route('cart.index') }}" class="stepper-pill done" style="text-decoration: none;">
                    1. Bag
                </a>
                <span class="stepper-arrow">›</span>
                <div class="stepper-pill active">
                    2. Address & Payment
                </div>
                <span class="stepper-arrow">›</span>
                <div class="stepper-pill">
                    3. Confirmed
                </div>
            </div>
        </div>

        {{-- ── 2. Checkout Form Two-Column Grid ── --}}
        <form action="{{ route('checkout.place') }}" method="POST" id="checkoutForm">
            @csrf
            @if(!empty($isBuyNow) && !empty($buyNowItem))
                <input type="hidden" name="buy_now" value="1">
                <input type="hidden" name="product_id" value="{{ $buyNowItem['id'] }}">
                <input type="hidden" name="qty" value="{{ $buyNowItem['quantity'] }}">
                <input type="hidden" name="size" value="{{ $buyNowItem['size'] }}">
                <input type="hidden" name="color" value="{{ $buyNowItem['color'] }}">
                <input type="hidden" name="design_side" value="{{ $buyNowItem['design_side'] }}">
            @endif
            <div class="checkout-two-columns">

                {{-- ── LEFT COLUMN: Shipping Address & Payment ── --}}
                <div class="checkout-left-column">

                    {{-- Section 1: Delivery Address (Collapsible on mobile) --}}
                    <div class="co-panel co-collapsible-panel is-open" id="sectionDelivery">
                        <div class="co-panel-head" onclick="toggleCheckoutSection('sectionDelivery')">
                            <div class="co-panel-head-left">
                                <div class="co-panel-head-title-row">
                                    <h2 class="co-panel-title">
                                        1. Delivery Details
                                    </h2>
                                    <span style="font-size: 11.5px; color: var(--co-emerald); font-weight: 700; background: var(--co-emerald-bg); border: 1px solid var(--co-emerald-border); padding: 3px 10px; border-radius: 6px;">
                                        Express Delivery
                                    </span>
                                </div>
                                <div class="mobile-section-summary" id="deliverySummarySnippet">
                                    {{ old('name', $user->name ?? '') ? old('name', $user->name ?? '') . ' • ' . (old('city', $user->city ?? '') ?: old('pincode', $user->pincode ?? 'Address set')) : 'Tap to enter recipient name & address' }}
                                </div>
                            </div>
                            <div class="co-panel-head-right">
                                <span class="co-panel-collapse-chevron" id="chevronDelivery">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                </span>
                            </div>
                        </div>

                        <div class="co-panel-collapse-body" id="bodyDelivery">
                            {{-- Name & Phone --}}
                            <div class="co-form-row">
                                <div class="co-input-group">
                                    <label class="co-input-label" for="co_name">
                                        <span>Recipient Full Name <span class="text-danger">*</span></span>
                                    </label>
                                    <div class="co-input-wrapper">
                                        <input type="text" id="co_name" name="name" class="co-input-field" value="{{ old('name', $user->name ?? '') }}" placeholder="Enter full name" required style="text-transform: capitalize;">
                                    </div>
                                </div>

                                <div class="co-input-group">
                                    <label class="co-input-label" for="co_phone">
                                        <span>Mobile Number (WhatsApp) <span class="text-danger">*</span></span>
                                    </label>
                                    <div class="co-input-wrapper">
                                        <input type="tel" id="co_phone" name="phone" class="co-input-field" value="{{ old('phone', $user->phone ?? '') }}" placeholder="10-digit mobile number" maxlength="15" required>
                                    </div>
                                </div>
                            </div>

                            {{-- Email Address --}}
                            <div class="co-input-group" style="margin-bottom: 18px;">
                                <label class="co-input-label" for="co_email">
                                    <span>Email Address <span style="color:#64748b; font-weight:500; font-size:11.5px;">(for invoice &amp; delivery updates)</span></span>
                                </label>
                                <div class="co-input-wrapper">
                                    <input type="email" id="co_email" name="email" class="co-input-field" value="{{ old('email', $user->email ?? '') }}" placeholder="name@example.com">
                                </div>
                            </div>

                            {{-- Street Address --}}
                            <div class="co-input-group">
                                <label class="co-input-label" for="co_address">
                                    <span>Complete House / Flat / Street Address <span class="text-danger">*</span></span>
                                </label>
                                <div class="co-input-wrapper">
                                    <textarea id="co_address" name="address" class="co-textarea-field" rows="2" placeholder="House No, Apartment/Building, Street" required>{{ old('address', $user->address ?? '') }}</textarea>
                                </div>
                            </div>

                            {{-- Nearby Landmark --}}
                            <div class="co-input-group">
                                <label class="co-input-label" for="co_landmark">
                                    <span>Nearby Landmark <span style="color:#94a3b8; font-weight:600;">(Optional)</span></span>
                                </label>
                                <div class="co-input-wrapper">
                                    <input type="text" id="co_landmark" name="landmark" class="co-input-field" value="{{ old('landmark') }}" placeholder="Example: near metro station, school, temple">
                                </div>
                            </div>

                            {{-- Pincode & Country (Row 3) --}}
                            <div class="co-form-row">
                                <div class="co-input-group">
                                    <label class="co-input-label" for="co_pincode">
                                        <span>Postal Pincode <span class="text-danger">*</span></span>
                                        <span id="pincodeStatus" style="font-size: 11px; margin-left: auto; text-transform: none;"></span>
                                    </label>
                                    <div class="co-input-wrapper">
                                        <input type="text" id="co_pincode" name="pincode" class="co-input-field" value="{{ old('pincode', $user->pincode ?? '') }}" placeholder="6-digit pincode (e.g. 492001)" maxlength="6" required>
                                    </div>
                                </div>

                                <div class="co-input-group">
                                    <label class="co-input-label">Country</label>
                                    <div class="co-input-wrapper">
                                        <input type="text" class="co-input-field" value="India (IN)" readonly style="background: #f8fafc; color: #475569; font-weight: 600;">
                                    </div>
                                </div>
                            </div>

                            {{-- State & City (Row 4) --}}
                            <div class="co-form-row" style="margin-bottom: 0;">
                                <div class="co-input-group" style="margin-bottom: 0;">
                                    <label class="co-input-label" for="co_state">
                                        <span>State <span class="text-danger">*</span></span>
                                    </label>
                                    <div class="co-input-wrapper">
                                        <input type="text" id="co_state" name="state" class="co-input-field" value="{{ old('state', $user->state ?? '') }}" placeholder="State (Auto-detected)" required style="text-transform: capitalize;">
                                    </div>
                                </div>

                                <div class="co-input-group" style="margin-bottom: 0;">
                                    <label class="co-input-label" for="co_city">
                                        <span>City / District <span class="text-danger">*</span></span>
                                    </label>
                                    <div class="co-input-wrapper">
                                        <input type="text" id="co_city" name="city" class="co-input-field" value="{{ old('city', $user->city ?? '') }}" placeholder="City (Auto-detected)" required style="text-transform: capitalize;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section 2: Payment Method (Collapsible on mobile) --}}
                    <div class="co-panel co-collapsible-panel is-open" id="sectionPayment">
                        <div class="co-panel-head" onclick="toggleCheckoutSection('sectionPayment')">
                            <div class="co-panel-head-left">
                                <div class="co-panel-head-title-row">
                                    <h2 class="co-panel-title">
                                        2. Select Payment Method
                                    </h2>
                                    <span class="pm-offer-pill">5% Instant Discount on Prepaid</span>
                                </div>
                                <div class="mobile-section-summary" id="paymentSummarySnippet">
                                    Online / UPI / Cards (5% Instant Discount)
                                </div>
                            </div>
                            <div class="co-panel-head-right">
                                <span class="co-panel-collapse-chevron" id="chevronPayment">
                                     <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                 </span>
                            </div>
                        </div>

                        <div class="co-panel-collapse-body" id="bodyPayment">
                            <input type="hidden" name="payment" id="selectedPaymentMethod" value="razorpay">

                            <div class="payment-selector-stack">

                                {{-- Option 1: UPI & Online Prepaid --}}
                                <div class="payment-method-card active" data-payment="razorpay" onclick="selectPaymentMethod('razorpay')">
                                    <div class="pm-card-top-row">
                                        <div class="pm-left-side">
                                            <div class="pm-radio-disc"></div>
                                            <div class="pm-details">
                                                <h4>
                                                    UPI, Cards & Net Banking
                                                    <span class="pm-offer-pill">Save 5%</span>
                                                </h4>
                                                <p>Pay securely via Google Pay, PhonePe, Paytm, BHIM, Cards or NetBanking. Instant VIP dispatch.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pm-brand-logos-row">
                                        <span class="pm-brand-badge">Google Pay</span>
                                        <span class="pm-brand-badge">PhonePe</span>
                                        <span class="pm-brand-badge">Paytm</span>
                                        <span class="pm-brand-badge">UPI</span>
                                        <span class="pm-brand-badge">Cards</span>
                                        <span class="pm-brand-badge">NetBanking</span>
                                    </div>
                                </div>

                                {{-- Option 2: Cash on Delivery --}}
                                <div class="payment-method-card" data-payment="cod" onclick="selectPaymentMethod('cod')">
                                    <div class="pm-card-top-row" style="margin-bottom: 0;">
                                        <div class="pm-left-side">
                                            <div class="pm-radio-disc"></div>
                                            <div class="pm-details">
                                                <h4>Cash on Delivery (COD)</h4>
                                                <p>Pay in cash or UPI QR with delivery partner at your doorstep.</p>
                                            </div>
                                        </div>
                                        <div class="pm-brand-badge" style="background: #f1f5f9;">
                                            <span>Cash / QR</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Section 3: Frequently Asked Questions (FAQ) --}}
                    <div class="co-panel co-faq-panel">
                        <div class="co-panel-head" style="margin-bottom: 16px;">
                            <h2 class="co-panel-title">
                                Frequently Asked Questions
                            </h2>
                            <span style="font-size: 11.5px; color: var(--co-text-muted); font-weight: 700;">
                                24/7 Help
                            </span>
                        </div>

                        <div class="co-faq-accordion-list">
                            {{-- FAQ 1 --}}
                            <div class="co-faq-item open">
                                <button type="button" class="co-faq-btn" onclick="toggleCheckoutFaq(this)">
                                    <span>When will my order arrive?</span>
                                    <span class="co-faq-chevron"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg></span>
                                </button>
                                <div class="co-faq-body">
                                    All orders are dispatched within 24 hours. Metro cities typically receive deliveries within <b>2 to 4 business days</b>, while other regions take 4 to 7 business days. You will receive live WhatsApp & SMS tracking links as soon as your parcel ships.
                                </div>
                            </div>

                            {{-- FAQ 2 --}}
                            <div class="co-faq-item">
                                <button type="button" class="co-faq-btn" onclick="toggleCheckoutFaq(this)">
                                    <span>Is online payment safe? (5% Discount)</span>
                                    <span class="co-faq-chevron"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg></span>
                                </button>
                                <div class="co-faq-body">
                                    Yes! All UPI, Cards, and NetBanking transactions are processed through RBI-approved 256-Bit SSL encrypted gateways. Plus, you get an automatic <b>5% Instant Prepaid Discount</b> on your order.
                                </div>
                            </div>

                            {{-- FAQ 3 --}}
                            <div class="co-faq-item">
                                <button type="button" class="co-faq-btn" onclick="toggleCheckoutFaq(this)">
                                    <span>How does Cash on Delivery (COD) work?</span>
                                    <span class="co-faq-chevron"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg></span>
                                </button>
                                <div class="co-faq-body">
                                    You can pay the exact invoice amount in cash or scan the delivery executive's UPI QR code right at your doorstep when the package is handed over to you.
                                </div>
                            </div>

                            {{-- FAQ 4 --}}
                            <div class="co-faq-item">
                                <button type="button" class="co-faq-btn" onclick="toggleCheckoutFaq(this)">
                                    <span>What is your Return & Exchange policy?</span>
                                    <span class="co-faq-chevron"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg></span>
                                </button>
                                <div class="co-faq-body">
                                    We offer a <b>7-day hassle-free return and exchange</b> policy from the date of delivery. If you need a size replacement or return, you can raise an instant request from your account or contact our support team.
                                </div>
                            </div>

                            {{-- FAQ 5 --}}
                            <div class="co-faq-item">
                                <button type="button" class="co-faq-btn" onclick="toggleCheckoutFaq(this)">
                                    <span>How can I track my order status?</span>
                                    <span class="co-faq-chevron"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg></span>
                                </button>
                                <div class="co-faq-body">
                                    You can track your order live anytime by visiting our <a href="{{ url('/track-order') }}" target="_blank" style="color:var(--co-navy); font-weight:700; text-decoration:underline;">Order Tracking Page</a> using your Order ID or phone number.
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ── RIGHT COLUMN: Order Summary & Pay CTA ── --}}
                <div class="checkout-right-column">
                    <div class="co-sidebar-summary" id="coSidebarSummaryCard">
                        
                        <div class="co-summary-header" id="coSummaryHeader">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <h3 class="co-summary-title">Order Summary</h3>
                            </div>
                            <div class="co-summary-header-right">
                                <span class="co-saved-badge" id="coSavedSoFarBadge" style="{{ $totalSavings > 0 ? '' : 'display:none;' }}">
                                    <span id="coSavedSoFarText">₹{{ number_format($totalSavings, 2) }} saved so far</span>
                                </span>
                                <a href="{{ route('cart.index') }}" class="co-summary-edit-link">Edit Bag</a>
                            </div>
                        </div>

                        {{-- Collapsible Body on Mobile --}}
                        <div class="co-summary-collapsible-body" id="coSummaryCollapsibleBody">
                            {{-- Comprehensive Cart Items Preview --}}
                            <div class="co-cart-items-scroll" id="coCartItemsScroll">
                                @foreach($cart as $key => $item)
                                    <div class="co-cart-item-row">
                                        <div class="co-cart-item-thumb">
                                            <img src="{{ $item['image'] ?? asset('images/placeholder-product.jpg') }}" alt="{{ $item['name'] }}" onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                                        </div>
                                        <div class="co-cart-item-info">
                                            <div class="co-cart-item-name" title="{{ $item['name'] }}">{{ $item['name'] }}</div>
                                            <div class="co-cart-item-meta">
                                                <span class="co-item-pill">Size: {{ $item['size'] ?? 'Free' }}</span>
                                                <span class="co-item-pill">Qty: {{ $item['quantity'] }}</span>
                                                @if(!empty($item['design_side']))
                                                    <span class="co-item-pill" style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe;">
                                                        Print: {{ ucfirst($item['design_side']) }} Side
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="co-cart-item-price-block">
                                            <div class="co-cart-item-price">₹{{ number_format(round($item['price'] * $item['quantity']), 2) }}</div>
                                            @if(!empty($item['original_price']) && (float)$item['original_price'] > (float)$item['price'])
                                                <div class="co-cart-item-unit-price"><del>₹{{ number_format((float)$item['original_price'] * (int)$item['quantity'], 2) }}</del></div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Offers & Rewards and Bottom Sheet Modal --}}
                            @include('froentend.partials.coupon-section', ['subtotal' => $subtotal])

                            {{-- Bill Breakdown Matching User Screenshot --}}
                            <div class="co-summary-breakdown-card">
                                <div class="co-summary-row">
                                    <span class="row-label">MRP total</span>
                                    <span class="row-val" id="coSummaryMrpTotal">₹{{ number_format($mrpTotal, 2) }}</span>
                                </div>

                                <div class="co-summary-row is-discount" id="coMrpDiscountRow" style="{{ $mrpDiscount > 0 ? '' : 'display:none;' }}">
                                    <span class="row-label">Discount on MRP</span>
                                    <span class="row-val" id="coSummaryMrpDiscount">-₹{{ number_format($mrpDiscount, 2) }}</span>
                                </div>

                                <div class="co-summary-row">
                                    <span class="row-label">Cart Subtotal</span>
                                    <span class="row-val" id="coSummarySubtotal">₹{{ number_format($subtotal, 2) }}</span>
                                </div>

                                <div class="co-summary-row is-discount" id="coCouponDiscountRow" style="{{ $couponDiscount > 0 ? '' : 'display:none;' }}">
                                    <span class="row-label">Total discount</span>
                                    <span class="row-val" id="coSummaryCouponDiscount">-₹{{ number_format($couponDiscount, 2) }}</span>
                                </div>

                                <div class="co-summary-row is-discount" id="prepaidDiscountRow">
                                    <span class="row-label">
                                        Prepaid Discount
                                        <span class="co-info-icon" title="5% Instant Discount on UPI and Card Payments">ⓘ</span>
                                    </span>
                                    <span class="row-val" id="coSummaryPrepaidDiscount">-₹{{ number_format($prepaidDiscount, 2) }}</span>
                                </div>

                                <div class="co-summary-row">
                                    <span class="row-label">
                                        Shipping Charges
                                        <span class="co-info-icon" title="{{ $shipping == 0 ? 'Free Shipping on orders above ₹999' : 'Standard Delivery ₹50' }}">ⓘ</span>
                                    </span>
                                    <span class="row-val" id="coSummaryShipping">
                                        @if($shipping == 0)
                                            <span style="color:#00a76f; font-weight:600;">FREE</span>
                                        @else
                                            ₹{{ number_format($shipping, 2) }}
                                        @endif
                                    </span>
                                </div>

                                <div class="co-summary-row is-savings" id="coTotalSavingsRow">
                                    <span class="row-label">Total savings</span>
                                    <span class="row-val" id="coSummaryTotalSavings">₹{{ number_format($totalSavings, 2) }}</span>
                                </div>

                                <div class="co-summary-divider"></div>

                                <div class="co-estimated-total-row">
                                    <span class="co-estimated-label">Estimated Total</span>
                                    <span class="co-estimated-val" id="displayGrandTotal">₹{{ number_format($prepaidTotal, 2) }}</span>
                                </div>
                            </div>

                            {{-- Submit Order Button --}}
                            <button type="submit" class="btn-checkout-primary-cta" id="btnSubmitOrder">
                                <span id="btnSubmitText">PAY ₹{{ number_format($prepaidTotal, 2) }} & PLACE ORDER</span>
                            </button>

                            {{-- Trust Badges Strip --}}
                            <div class="co-trust-strip">
                                <span>256-Bit SSL Encryption</span>
                                <span>•</span>
                                <span>100% Genuine</span>
                                <span>•</span>
                                <span>7-Day Returns</span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </form>

    </div>
</div>

{{-- ── 3. Mobile Fixed Bottom Pay Bar with Collapsible Bill Details & Full-Width CTA ── --}}
<div class="checkout-mobile-sticky-wrap" id="mobileStickyPayBar">
    {{-- Dim backdrop when bill drawer is expanded --}}
    <div class="mobile-sticky-backdrop" id="mobileStickyBackdrop" onclick="toggleMobileBillCollapse(false)"></div>

    <div class="mobile-sticky-card">
        {{-- Collapsible Bill Details Drawer --}}
        <div class="mobile-sticky-drawer" id="mobileStickyDrawer">
            <div class="mobile-drawer-header">
                <span class="mobile-drawer-title">
                    Detailed Bill Summary
                </span>
                <button type="button" class="mobile-drawer-close" onclick="toggleMobileBillCollapse(false)" aria-label="Close">
                    ✕
                </button>
            </div>

            <div class="mobile-drawer-body">
                <div class="mobile-drawer-row">
                    <span>MRP total</span>
                    <strong id="mobileMrpTotal">₹{{ number_format($mrpTotal, 2) }}</strong>
                </div>

                <div class="mobile-drawer-row text-success" id="mobileMrpDiscountRow" style="{{ $mrpDiscount > 0 ? '' : 'display:none;' }}">
                    <span>Discount on MRP</span>
                    <strong id="mobileMrpDiscount">-₹{{ number_format($mrpDiscount, 2) }}</strong>
                </div>

                <div class="mobile-drawer-row">
                    <span>Cart Subtotal</span>
                    <strong id="mobileCartSubtotal">₹{{ number_format($subtotal, 2) }}</strong>
                </div>

                <div class="mobile-drawer-row text-success" id="mobileCouponDiscountRow" style="{{ $couponDiscount > 0 ? '' : 'display:none;' }}">
                    <span>Total discount</span>
                    <strong id="mobileCouponDiscount">-₹{{ number_format($couponDiscount, 2) }}</strong>
                </div>

                <div class="mobile-drawer-row text-success" id="mobilePrepaidDiscountRow">
                    <span>Prepaid Discount ⓘ</span>
                    <strong id="mobilePrepaidDiscountVal">-₹{{ number_format($prepaidDiscount, 2) }}</strong>
                </div>

                <div class="mobile-drawer-row">
                    <span>Shipping Charges ⓘ</span>
                    @if($shipping == 0)
                        <strong class="text-success">FREE</strong>
                    @else
                        <strong>₹{{ number_format($shipping, 2) }}</strong>
                    @endif
                </div>

                <div class="mobile-drawer-row text-success" id="mobileTotalSavingsRow">
                    <span>Total savings</span>
                    <strong id="mobileTotalSavingsVal">₹{{ number_format($totalSavings, 2) }}</strong>
                </div>

                <div class="mobile-drawer-divider"></div>

                <div class="mobile-drawer-row mobile-drawer-total-row">
                    <div>
                        <span class="mobile-drawer-total-label">Estimated Total</span>
                        <small class="d-block text-muted" style="font-size: 11px;">(Inclusive of GST & all taxes)</small>
                    </div>
                    <span class="mobile-drawer-total-val" id="mobileCollapseTotalAmount">₹{{ number_format($prepaidTotal, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Sticky Header Bar (Total + Collapsible Toggle) --}}
        <div class="mobile-sticky-bar-header" onclick="toggleMobileBillCollapse()">
            <div class="mobile-sticky-header-left">
                <span class="mobile-sticky-total-label">To Pay:</span>
                <span class="mobile-sticky-amount" id="mobileStickyTotal">₹{{ number_format($prepaidTotal) }}</span>
                <span class="mobile-sticky-subtext" id="mobileStickySubtext">
                    @if($prepaidDiscount > 0)
                        <span class="badge-saving-pill">5% Off</span>
                    @else
                        <span class="badge-cod-pill">COD</span>
                    @endif
                </span>
            </div>
            <div class="mobile-sticky-header-right">
                <span class="mobile-view-bill-btn">
                    <span id="mobileToggleActionText">View Bill</span>
                    <span id="mobileToggleChevron" class="co-mobile-toggle-chevron">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
                    </span>
                </span>
            </div>
        </div>

        {{-- Full-Width Mobile Pay / Submit CTA Button --}}
        <div class="mobile-sticky-btn-row">
            <button type="button" class="btn-checkout-mobile-cta-full" id="btnMobileSubmitOrder" onclick="triggerMobileCheckoutSubmit()">
                <span id="btnMobileSubmitText">PAY ₹{{ number_format($prepaidTotal) }} & PLACE ORDER</span>
            </button>
        </div>
    </div>
</div>

<div class="order-place-loader" id="orderPlaceLoader" aria-hidden="true">
    <div class="order-place-loader__card" role="status" aria-live="polite">
        <div class="order-place-loader__spinner"></div>
        <div class="order-place-loader__title" id="orderPlaceLoaderTitle">Placing your order</div>
        <div class="order-place-loader__text" id="orderPlaceLoaderText">Please wait while we securely process your order.</div>
    </div>
</div>

@push('scripts')
<script>
const mrpTotalVal = {{ $mrpTotal }};
const mrpDiscountVal = {{ $mrpDiscount }};
const cartSubtotalVal = {{ $subtotal }};
const baseTotal = {{ $total }};
const couponDiscountVal = {{ $couponDiscount }};
const prepaidDiscountAmount = {{ $prepaidDiscount }};
const prepaidFinalTotal = {{ $prepaidTotal }};
const baseSavingsVal = {{ $mrpDiscount + $couponDiscount }};
const prepaidSavingsVal = {{ $mrpDiscount + $couponDiscount + $prepaidDiscount }};

function formatCurrency(value) {
    return '₹' + Number(value || 0).toLocaleString('en-IN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function selectPaymentMethod(method) {
    document.getElementById('selectedPaymentMethod').value = method;
    document.querySelectorAll('.payment-method-card').forEach(card => {
        card.classList.toggle('active', card.getAttribute('data-payment') === method);
    });

    const prepaidDiscountRow = document.getElementById('prepaidDiscountRow');
    const coSummaryPrepaidDiscount = document.getElementById('coSummaryPrepaidDiscount');
    const coSummaryTotalSavings = document.getElementById('coSummaryTotalSavings');
    const coSavedSoFarBadge = document.getElementById('coSavedSoFarBadge');
    const coSavedSoFarText = document.getElementById('coSavedSoFarText');
    const totalVal = document.getElementById('displayGrandTotal');
    const submitText = document.getElementById('btnSubmitText');
    const mobileTotal = document.getElementById('mobileStickyTotal');
    const mobileBtnText = document.getElementById('btnMobileSubmitText');
    const mobileSubtext = document.getElementById('mobileStickySubtext');

    // Mobile Drawer Breakdown Elements
    const mobileCollapseTotal = document.getElementById('mobileCollapseTotalAmount');
    const mobilePrepaidRow = document.getElementById('mobilePrepaidDiscountRow');
    const mobilePrepaidVal = document.getElementById('mobilePrepaidDiscountVal');
    const mobileTotalSavingsVal = document.getElementById('mobileTotalSavingsVal');

    if (method === 'cod') {
        if (prepaidDiscountRow) prepaidDiscountRow.style.display = 'none';
        if (coSummaryTotalSavings) coSummaryTotalSavings.textContent = formatCurrency(baseSavingsVal);
        if (coSavedSoFarBadge) {
            if (baseSavingsVal > 0) {
                coSavedSoFarBadge.style.display = 'inline-flex';
                if (coSavedSoFarText) coSavedSoFarText.textContent = formatCurrency(baseSavingsVal) + ' saved so far';
            } else {
                coSavedSoFarBadge.style.display = 'none';
            }
        }
        if (totalVal) totalVal.textContent = formatCurrency(baseTotal);
        if (submitText) submitText.textContent = 'CONFIRM CASH ON DELIVERY ORDER (' + formatCurrency(baseTotal) + ')';
        
        // Mobile Sticky Elements
        if (mobileTotal) mobileTotal.textContent = formatCurrency(baseTotal);
        if (mobileBtnText) mobileBtnText.textContent = 'CONFIRM CASH ON DELIVERY (' + formatCurrency(baseTotal) + ')';
        if (mobileSubtext) mobileSubtext.innerHTML = '<span class="badge-cod-pill">COD</span>';

        // Mobile Drawer Breakdown
        if (mobileCollapseTotal) mobileCollapseTotal.textContent = formatCurrency(baseTotal);
        if (mobilePrepaidRow) mobilePrepaidRow.style.display = 'none';
        if (mobileTotalSavingsVal) mobileTotalSavingsVal.textContent = formatCurrency(baseSavingsVal);
    } else {
        if (prepaidDiscountRow) prepaidDiscountRow.style.display = 'flex';
        if (coSummaryPrepaidDiscount) coSummaryPrepaidDiscount.textContent = '-' + formatCurrency(prepaidDiscountAmount);
        if (coSummaryTotalSavings) coSummaryTotalSavings.textContent = formatCurrency(prepaidSavingsVal);
        if (coSavedSoFarBadge) {
            coSavedSoFarBadge.style.display = 'inline-flex';
            if (coSavedSoFarText) coSavedSoFarText.textContent = formatCurrency(prepaidSavingsVal) + ' saved so far';
        }
        if (totalVal) totalVal.textContent = formatCurrency(prepaidFinalTotal);
        if (submitText) submitText.textContent = 'PAY ' + formatCurrency(prepaidFinalTotal) + ' & PLACE ORDER';

        // Mobile Sticky Elements
        if (mobileTotal) mobileTotal.textContent = formatCurrency(prepaidFinalTotal);
        if (mobileBtnText) mobileBtnText.textContent = 'PAY ' + formatCurrency(prepaidFinalTotal) + ' & PLACE ORDER';
        if (mobileSubtext) mobileSubtext.innerHTML = '<span class="badge-saving-pill">5% Off</span>';

        // Mobile Drawer Breakdown
        if (mobileCollapseTotal) mobileCollapseTotal.textContent = formatCurrency(prepaidFinalTotal);
        if (mobilePrepaidRow) {
            mobilePrepaidRow.style.display = 'flex';
            if (mobilePrepaidVal) mobilePrepaidVal.textContent = '-' + formatCurrency(prepaidDiscountAmount);
        }
        if (mobileTotalSavingsVal) mobileTotalSavingsVal.textContent = formatCurrency(prepaidSavingsVal);
    }
    updateSectionSummaries();
}

// Auto Capitalize Name, City, State on blur and update section summary
['co_name', 'co_city', 'co_state', 'co_pincode'].forEach(id => {
    const el = document.getElementById(id);
    if (el) {
        el.addEventListener('blur', function() {
            if (this.value && id !== 'co_pincode') {
                this.value = this.value.replace(/\b\w/g, char => char.toUpperCase());
            }
            updateSectionSummaries();
        });
        el.addEventListener('input', function() {
            updateSectionSummaries();
        });
    }
});

// ── Live Pincode Auto-Fill for City & State ──
const pincodeInput = document.getElementById('co_pincode');
const cityInput = document.getElementById('co_city');
const stateInput = document.getElementById('co_state');
const pinStatus = document.getElementById('pincodeStatus');
let pinLookupTimer = null;

function lookupPincode(pin) {
    pin = String(pin || '').trim().replace(/\D/g, '');
    if (pin.length !== 6) {
        if (pinStatus) pinStatus.innerHTML = '';
        return;
    }

    if (pinStatus) {
        pinStatus.innerHTML = '<span style="color:var(--co-navy); font-weight:600;">Checking delivery & location...</span>';
    }

    // 1. Check internal Logistics & COD Risk Rules
    fetch('/api/pincode/check?pincode=' + pin)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Update City / State if provided by DB
                if (data.city && cityInput && !cityInput.value) {
                    cityInput.value = data.city;
                }
                if (data.state && stateInput && !stateInput.value) {
                    stateInput.value = data.state;
                }

                // Handle COD Blocking
                const codCard = document.querySelector('.payment-method-card[data-payment="cod"]');
                if (codCard) {
                    let codNotice = document.getElementById('coCodBlockedNotice');
                    if (!data.is_cod_allowed) {
                        codCard.style.opacity = '0.5';
                        codCard.style.pointerEvents = 'none';
                        codCard.style.filter = 'grayscale(1)';
                        if (!codNotice) {
                            codNotice = document.createElement('div');
                            codNotice.id = 'coCodBlockedNotice';
                            codNotice.style.cssText = 'background:#fff7ed;color:#c2410c;border:1px solid #fed7aa;border-radius:10px;padding:9px 12px;font-size:11.5px;font-weight:700;margin-top:6px;';
                            codNotice.innerHTML = 'Cash on Delivery is disabled for pincode ' + pin + ' due to regional transit policy. Please pay via UPI / Cards.';
                            codCard.parentNode.insertBefore(codNotice, codCard.nextSibling);
                        }
                        const activeCard = document.querySelector('.payment-method-card.active');
                        if (activeCard && activeCard.getAttribute('data-payment') === 'cod') {
                            selectPaymentMethod('razorpay');
                        }
                    } else {
                        codCard.style.opacity = '1';
                        codCard.style.pointerEvents = 'auto';
                        codCard.style.filter = 'none';
                        if (codNotice) codNotice.remove();
                    }
                }

                if (pinStatus) {
                    pinStatus.innerHTML = '<span style="color:#00285a; font-weight:700;">Est. Delivery: ' + data.estimated_delivery_date + ' (' + data.delivery_days_text + ')</span>';
                }
            }

            // 2. Postal API for Auto-Fill City & State if still empty
            if (!cityInput.value || !stateInput.value) {
                fetch('https://api.postalpincode.in/pincode/' + pin)
                    .then(r => r.json())
                    .then(pData => {
                        if (Array.isArray(pData) && pData[0] && pData[0].Status === 'Success' && pData[0].PostOffice && pData[0].PostOffice.length > 0) {
                            const po = pData[0].PostOffice[0];
                            let c = po.District || po.Block || po.Circle || '';
                            let s = po.State || '';
                            if (c && cityInput) cityInput.value = c.toLowerCase().replace(/\b\w/g, ch => ch.toUpperCase());
                            if (s && stateInput) stateInput.value = s.toLowerCase().replace(/\b\w/g, ch => ch.toUpperCase());
                        }
                    }).catch(() => {});
            }
        })
        .catch(() => {
            if (pinStatus) pinStatus.innerHTML = '';
        });
}

if (pincodeInput) {
    pincodeInput.addEventListener('input', function() {
        clearTimeout(pinLookupTimer);
        const val = this.value;
        pinLookupTimer = setTimeout(() => {
            lookupPincode(val);
        }, 250);
    });
    pincodeInput.addEventListener('change', function() {
        lookupPincode(this.value);
    });
    pincodeInput.addEventListener('blur', function() {
        lookupPincode(this.value);
    });
    
    // Initial lookup if 6 digits present
    if (pincodeInput.value && pincodeInput.value.length === 6 && (!cityInput.value || !stateInput.value)) {
        lookupPincode(pincodeInput.value);
    }
}

function toggleCheckoutFaq(btn) {
    const item = btn.closest('.co-faq-item');
    if (!item) return;
    const wasOpen = item.classList.contains('open');
    document.querySelectorAll('.co-faq-item').forEach(el => el.classList.remove('open'));
    if (!wasOpen) {
        item.classList.add('open');
    }
}

function toggleOrderSummaryDetails() {
    if (window.innerWidth <= 768) {
        const card = document.getElementById('coSidebarSummaryCard');
        if (!card) return;
        card.classList.toggle('is-open');
    } else {
        const scrollBox = document.getElementById('coCartItemsScroll');
        const chevron = document.getElementById('coSummaryChevron');
        const header = document.getElementById('coSummaryHeader');
        if (!scrollBox) return;

        if (scrollBox.style.display === 'none' || !scrollBox.style.display) {
            scrollBox.style.display = 'flex';
            if (chevron) chevron.style.transform = 'rotate(180deg)';
            if (header) header.classList.add('is-open');
        } else {
            scrollBox.style.display = 'none';
            if (chevron) chevron.style.transform = 'rotate(0deg)';
            if (header) header.classList.remove('is-open');
        }
    }
}

function toggleCheckoutSection(sectionId) {
    if (window.innerWidth > 768) return;
    const section = document.getElementById(sectionId);
    if (!section) return;

    section.classList.toggle('is-open');
    updateSectionSummaries();
}

function ensureSectionOpen(sectionId) {
    const section = document.getElementById(sectionId);
    if (section && !section.classList.contains('is-open')) {
        section.classList.add('is-open');
    }
}

function updateSectionSummaries() {
    // Delivery summary snippet
    const nameInput = document.getElementById('co_name');
    const cityInput = document.getElementById('co_city');
    const pinInput = document.getElementById('co_pincode');
    const name = nameInput ? nameInput.value.trim() : '';
    const city = cityInput ? cityInput.value.trim() : '';
    const pin = pinInput ? pinInput.value.trim() : '';
    const delSnippet = document.getElementById('deliverySummarySnippet');
    if (delSnippet) {
        if (name && (city || pin)) {
            delSnippet.textContent = name + ' • ' + (city ? city + ' ' : '') + (pin ? '(' + pin + ')' : '');
        } else if (name) {
            delSnippet.textContent = name + ' • Tap to view address';
        } else {
            delSnippet.textContent = 'Tap to enter recipient name & address';
        }
    }

    // Payment summary snippet
    const methodInput = document.getElementById('selectedPaymentMethod');
    const method = methodInput ? methodInput.value : 'razorpay';
    const paySnippet = document.getElementById('paymentSummarySnippet');
    if (paySnippet) {
        if (method === 'cod') {
            paySnippet.textContent = 'Cash on Delivery (Pay at doorstep)';
        } else {
            paySnippet.textContent = 'Online / UPI / Cards (5% Instant Discount)';
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    updateSectionSummaries();
});
</script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
// ── Payment Submission Handler ──
const checkoutForm = document.getElementById('checkoutForm');
const btnPlaceOrder = document.getElementById('btnSubmitOrder');
const orderPlaceLoader = document.getElementById('orderPlaceLoader');
const orderPlaceLoaderTitle = document.getElementById('orderPlaceLoaderTitle');
const orderPlaceLoaderText = document.getElementById('orderPlaceLoaderText');
let checkoutSubmitInProgress = false;

function showOrderPlaceLoader(title, text) {
    if (!orderPlaceLoader) return;
    orderPlaceLoader.classList.remove('is-success');
    if (orderPlaceLoaderTitle && title) orderPlaceLoaderTitle.textContent = title;
    if (orderPlaceLoaderText && text) orderPlaceLoaderText.textContent = text;
    orderPlaceLoader.classList.add('is-active');
    orderPlaceLoader.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
}

function hideOrderPlaceLoader() {
    if (!orderPlaceLoader) return;
    orderPlaceLoader.classList.remove('is-active');
    orderPlaceLoader.classList.remove('is-success');
    orderPlaceLoader.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
}

function showOrderSuccessAndRedirect(redirectUrl, orderNumber) {
    if (!orderPlaceLoader) {
        window.location.href = redirectUrl;
        return;
    }

    orderPlaceLoader.classList.add('is-active', 'is-success');
    orderPlaceLoader.setAttribute('aria-hidden', 'false');
    if (orderPlaceLoaderTitle) orderPlaceLoaderTitle.textContent = 'Order placed successfully';
    if (orderPlaceLoaderText) {
        orderPlaceLoaderText.textContent = orderNumber
            ? 'Order #' + orderNumber + ' confirmed. Redirecting to success page...'
            : 'Your order is confirmed. Redirecting to success page...';
    }
    document.body.style.overflow = 'hidden';

    window.setTimeout(function() {
        window.location.href = redirectUrl;
    }, 1200);
}

function resetSubmitButton() {
    checkoutSubmitInProgress = false;
    hideOrderPlaceLoader();
    const method = document.getElementById('selectedPaymentMethod').value;
    
    // Desktop Button Reset
    if (btnPlaceOrder) {
        btnPlaceOrder.disabled = false;
        if (method === 'cod') {
            btnPlaceOrder.innerHTML = '<span id="btnSubmitText">CONFIRM CASH ON DELIVERY ORDER (' + formatCurrency(baseTotal) + ')</span>';
        } else {
            btnPlaceOrder.innerHTML = '<span id="btnSubmitText">PAY ' + formatCurrency(prepaidFinalTotal) + ' & PLACE ORDER</span>';
        }
    }

    // Mobile Fixed Bottom Button Reset
    const btnMobile = document.getElementById('btnMobileSubmitOrder');
    if (btnMobile) {
        btnMobile.disabled = false;
        if (method === 'cod') {
            btnMobile.innerHTML = '<span id="btnMobileSubmitText">CONFIRM CASH ON DELIVERY (' + formatCurrency(baseTotal) + ')</span>';
        } else {
            btnMobile.innerHTML = '<span id="btnMobileSubmitText">PAY ' + formatCurrency(prepaidFinalTotal) + ' & PLACE ORDER</span>';
        }
    }
}

function toggleMobileBillCollapse(forceState) {
    const drawer = document.getElementById('mobileStickyDrawer');
    const backdrop = document.getElementById('mobileStickyBackdrop');
    const chevron = document.getElementById('mobileToggleChevron');
    const actionText = document.getElementById('mobileToggleActionText');
    if (!drawer) return;

    const isOpen = drawer.classList.contains('is-open');
    const shouldOpen = typeof forceState === 'boolean' ? forceState : !isOpen;

    if (shouldOpen) {
        drawer.classList.add('is-open');
        if (backdrop) backdrop.classList.add('is-open');
        if (chevron) chevron.classList.add('rotated');
        if (actionText) actionText.textContent = 'Hide Bill';
    } else {
        drawer.classList.remove('is-open');
        if (backdrop) backdrop.classList.remove('is-open');
        if (chevron) chevron.classList.remove('rotated');
        if (actionText) actionText.textContent = 'View Bill';
    }
}

function scrollToOrderSummary() {
    const summary = document.querySelector('.co-sidebar-summary');
    if (summary) {
        summary.scrollIntoView({ behavior: 'smooth', block: 'start' });
        const scrollBox = document.getElementById('coCartItemsScroll');
        if (scrollBox && (scrollBox.style.display === 'none' || !scrollBox.style.display)) {
            toggleOrderSummaryDetails();
        }
    }
}

function triggerMobileCheckoutSubmit() {
    if (btnPlaceOrder) {
        btnPlaceOrder.click();
    } else if (checkoutForm) {
        checkoutForm.requestSubmit();
    }
}

if (checkoutForm) {
    checkoutForm.addEventListener('submit', function(e) {
        if (checkoutSubmitInProgress) {
            e.preventDefault();
            return;
        }

        const method = document.getElementById('selectedPaymentMethod').value;
        if (method === 'razorpay') {
            e.preventDefault();
            
            // Validate form HTML5 fields
            if (!checkoutForm.checkValidity()) {
                ensureSectionOpen('sectionDelivery');
                ensureSectionOpen('sectionPayment');
                checkoutForm.reportValidity();
                return;
            }

            const name = document.getElementById('co_name').value.trim();
            const phone = document.getElementById('co_phone').value.trim();
            const email = (document.getElementById('co_email') ? document.getElementById('co_email').value.trim() : '') || "{{ $user->email ?? '' }}";
            const address = document.getElementById('co_address').value.trim();
            const landmark = document.getElementById('co_landmark') ? document.getElementById('co_landmark').value.trim() : '';
            const pincode = document.getElementById('co_pincode').value.trim();
            const state = document.getElementById('co_state').value.trim();
            const city = document.getElementById('co_city').value.trim();

            if (!name || !phone || !address || !pincode || !state || !city) {
                alert('Please fill in all required delivery details.');
                return;
            }

            checkoutSubmitInProgress = true;
            showOrderPlaceLoader('Opening secure payment', 'Please wait while we connect to Razorpay.');

            if (btnPlaceOrder) {
                btnPlaceOrder.disabled = true;
                btnPlaceOrder.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="width: 14px; height: 14px; border-width: 2px;"></span> OPENING SECURE GATEWAY...';
            }
            const btnMobileInit = document.getElementById('btnMobileSubmitOrder');
            if (btnMobileInit) {
                btnMobileInit.disabled = true;
                btnMobileInit.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="width: 14px; height: 14px; border-width: 2px;"></span> CONNECTING...';
            }

            fetch("{{ route('payment.razorpay.create') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    amount: prepaidFinalTotal
                })
            })
            .then(r => r.json())
            .then(res => {
                if (!res.success) {
                    alert(res.message || 'Could not initiate payment. Please try again or choose Cash on Delivery.');
                    resetSubmitButton();
                    return;
                }

                const options = {
                    key: res.key,
                    amount: res.amount,
                    currency: res.currency || 'INR',
                    name: "THE TREND THEORY",
                    description: "Order Payment",
                    order_id: res.razorpay_order_id,
                    prefill: {
                        name: name,
                        contact: phone,
                        email: email
                    },
                    theme: {
                        color: "#00285a"
                    },
                    handler: function(response) {
                        showOrderPlaceLoader('Verifying payment', 'Payment received. We are placing your order now.');
                        if (btnPlaceOrder) {
                            btnPlaceOrder.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="width: 14px; height: 14px; border-width: 2px;"></span> VERIFYING PAYMENT...';
                        }
                        const btnMobileVerify = document.getElementById('btnMobileSubmitOrder');
                        if (btnMobileVerify) {
                            btnMobileVerify.disabled = true;
                            btnMobileVerify.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="width: 14px; height: 14px; border-width: 2px;"></span> VERIFYING...';
                        }

                        fetch("{{ route('payment.razorpay.verify') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                razorpay_order_id: response.razorpay_order_id,
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_signature: response.razorpay_signature,
                                order_data: {
                                    name: name,
                                    email: email,
                                    phone: phone,
                                    address: address,
                                    landmark: landmark,
                                    pincode: pincode,
                                    state: state,
                                    city: city,
                                    subtotal: {{ $subtotal }},
                                    shipping: {{ $shipping }},
                                    discount: {{ $couponDiscount + $prepaidDiscount }},
                                    total: prepaidFinalTotal,
                                    @if(!empty($isBuyNow) && !empty($buyNowItem))
                                    buy_now: 1,
                                    product_id: {{ $buyNowItem['id'] }},
                                    qty: {{ $buyNowItem['quantity'] }},
                                    size: "{{ $buyNowItem['size'] }}",
                                    color: "{{ $buyNowItem['color'] }}",
                                    design_side: "{{ $buyNowItem['design_side'] }}",
                                    @endif
                                }
                            })
                        })
                        .then(r => r.json())
                        .then(verifyRes => {
                            if (verifyRes.success && verifyRes.redirect) {
                                showOrderSuccessAndRedirect(verifyRes.redirect, verifyRes.order_number);
                            } else {
                                alert(verifyRes.message || 'Payment verification failed.');
                                resetSubmitButton();
                            }
                        })
                        .catch(() => {
                            alert('Payment verification failed. If money was debited, please contact support with payment ID: ' + response.razorpay_payment_id);
                            resetSubmitButton();
                        });
                    },
                    modal: {
                        ondismiss: function() {
                            resetSubmitButton();
                        }
                    }
                };

                const rzp = new Razorpay(options);
                rzp.on('payment.failed', function(response) {
                    alert('Payment failed: ' + (response.error.description || 'Transaction cancelled.'));
                    resetSubmitButton();
                });
                hideOrderPlaceLoader();
                rzp.open();
            })
            .catch(err => {
                alert('Connection error. Please try again.');
                resetSubmitButton();
            });
        } else {
            // COD mode
            e.preventDefault();

            if (!checkoutForm.checkValidity()) {
                ensureSectionOpen('sectionDelivery');
                ensureSectionOpen('sectionPayment');
                checkoutForm.reportValidity();
                return;
            }

            checkoutSubmitInProgress = true;
            showOrderPlaceLoader('Placing your order', 'Please wait. Do not refresh or press back.');
            if (btnPlaceOrder) {
                btnPlaceOrder.disabled = true;
                btnPlaceOrder.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="width: 14px; height: 14px; border-width: 2px;"></span> PLACING ORDER...';
            }
            const btnMobilePlace = document.getElementById('btnMobileSubmitOrder');
            if (btnMobilePlace) {
                btnMobilePlace.disabled = true;
                btnMobilePlace.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="width: 14px; height: 14px; border-width: 2px;"></span> PLACING ORDER...';
            }

            fetch(checkoutForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(checkoutForm)
            })
            .then(r => r.json())
            .then(res => {
                if (res.success && res.redirect) {
                    showOrderSuccessAndRedirect(res.redirect, res.order_number);
                } else {
                    alert(res.message || 'Could not place order. Please try again.');
                    resetSubmitButton();
                }
            })
            .catch(() => {
                alert('Connection error. Please try again.');
                resetSubmitButton();
            });
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    @if(!empty($isBuyNow))
        if (typeof ensureSectionOpen === 'function') {
            ensureSectionOpen('sectionPayment');
        }
        @if(!empty($user->address) && !empty($user->phone))
            setTimeout(function() {
                var paySec = document.getElementById('sectionPayment');
                if (paySec) paySec.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 300);
        @endif
    @endif
});
</script>
@endpush

@endsection
