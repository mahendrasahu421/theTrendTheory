{{-- resources/views/froentend/cart/checkout.blade.php --}}
@extends('froentend.layouts.app')

@push('seo')
    <title>Secure Luxury Checkout | Vayu</title>
    <meta name="description" content="Complete your luxury streetwear order securely with Vayu. Fast express delivery, instant discounts & 100% secure checkout.">
    <meta name="robots" content="noindex, nofollow">
@endpush

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   ULTRA-LUXURY MODERN CHECKOUT SYSTEM - Vayu
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
    width: 36px;
    height: 36px;
    border-radius: 12px;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    color: var(--co-navy);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    box-shadow: inset 0 1px 2px rgba(255, 255, 255, 0.8);
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
    font-weight: 800;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.co-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.co-input-icon {
    position: absolute;
    left: 15px;
    color: #94a3b8;
    font-size: 15px;
    pointer-events: none;
    transition: color 0.2s ease;
}

.co-input-field {
    width: 100%;
    height: 50px;
    padding: 0 16px 0 44px;
    background: #ffffff;
    border: 1.5px solid var(--co-border);
    border-radius: 12px;
    font-size: 13.5px;
    font-weight: 600;
    color: var(--co-text-main);
    transition: all 0.2s ease;
    outline: none;
}

.co-input-field:focus {
    border-color: var(--co-navy);
    box-shadow: 0 0 0 3.5px rgba(0, 40, 90, 0.1);
    background: #ffffff;
}

.co-input-field:focus + .co-input-icon,
.co-input-wrapper:focus-within .co-input-icon {
    color: var(--co-navy);
}

.co-textarea-field {
    width: 100%;
    min-height: 86px;
    padding: 12px 16px 12px 44px;
    background: #ffffff;
    border: 1.5px solid var(--co-border);
    border-radius: 12px;
    font-size: 13.5px;
    font-weight: 600;
    color: var(--co-text-main);
    transition: all 0.2s ease;
    outline: none;
    resize: vertical;
}

.co-textarea-field:focus {
    border-color: var(--co-navy);
    box-shadow: 0 0 0 3.5px rgba(0, 40, 90, 0.1);
}

.co-textarea-field + .co-input-icon {
    top: 14px;
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
.co-bill-breakdown-box {
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding: 18px 0;
    border-top: 1.5px solid #f1f5f9;
    border-bottom: 1.5px solid #f1f5f9;
    margin-bottom: 18px;
}

.co-bill-row {
    display: flex;
    justify-content: space-between;
    font-size: 13.5px;
    color: #475569;
}

.co-bill-row strong {
    color: var(--co-text-main);
    font-weight: 700;
}

.co-bill-row.green {
    color: var(--co-emerald);
    font-weight: 700;
}

.co-savings-highlight-banner {
    background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
    border: 1px solid var(--co-emerald-border);
    border-radius: 12px;
    padding: 10px 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    color: #065f46;
    font-size: 12px;
    font-weight: 800;
    margin-bottom: 16px;
}

.co-final-total-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 22px;
}

.co-final-total-label {
    font-size: 14px;
    font-weight: 800;
    color: var(--co-text-main);
    text-transform: uppercase;
}

.co-tax-inclusive-tag {
    font-size: 11px;
    color: var(--co-text-muted);
    display: block;
    margin-top: 2px;
}

.co-final-total-val {
    font-family: 'Cinzel', serif !important;
    font-size: 28px;
    font-weight: 900;
    color: var(--co-navy);
    letter-spacing: 0.5px;
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
    font-size: 12px;
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

    .btn-checkout-primary-cta {
        height: auto;
        min-height: 52px;
        padding: 12px;
        font-size: 12.5px;
        line-height: 1.3;
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
@endphp
<div class="checkout-main-wrapper">
    <div class="checkout-container">

        {{-- ── 1. Top Header & Segmented Stepper ── --}}
        <div class="checkout-header-bar">
            <div class="checkout-brand-title-wrap">
                <h1 class="checkout-brand-title">Vayu</h1>
                <div class="checkout-secure-badge">
                    <i class="bi bi-shield-fill-check"></i> 100% ENCRYPTED CHECKOUT
                </div>
            </div>

            <div class="checkout-stepper-pills">
                <a href="{{ route('cart.index') }}" class="stepper-pill done" style="text-decoration: none;">
                    <i class="bi bi-check-circle-fill"></i> 1. Bag
                </a>
                <i class="bi bi-chevron-right stepper-arrow"></i>
                <div class="stepper-pill active">
                    <i class="bi bi-geo-alt-fill"></i> 2. Address & Payment
                </div>
                <i class="bi bi-chevron-right stepper-arrow"></i>
                <div class="stepper-pill">
                    <i class="bi bi-award"></i> 3. Confirmed
                </div>
            </div>
        </div>

        {{-- ── 2. Checkout Form Two-Column Grid ── --}}
        <form action="{{ route('checkout.place') }}" method="POST" id="checkoutForm">
            @csrf
            <div class="checkout-two-columns">

                {{-- ── LEFT COLUMN: Shipping Address & Payment ── --}}
                <div class="checkout-left-column">

                    {{-- Section 1: Delivery Address --}}
                    <div class="co-panel">
                        <div class="co-panel-head">
                            <h2 class="co-panel-title">
                                <span class="co-panel-icon-circle"><i class="bi bi-truck"></i></span>
                                1. Delivery Details
                            </h2>
                            <span style="font-size: 11.5px; color: var(--co-emerald); font-weight: 700; background: var(--co-emerald-bg); border: 1px solid var(--co-emerald-border); padding: 3px 10px; border-radius: 999px;">
                                <i class="bi bi-lightning-charge-fill"></i> Express Delivery
                            </span>
                        </div>

                        {{-- Name & Phone --}}
                        <div class="co-form-row">
                            <div class="co-input-group">
                                <label class="co-input-label" for="co_name">
                                    <span>Recipient Full Name <span class="text-danger">*</span></span>
                                </label>
                                <div class="co-input-wrapper">
                                    <i class="bi bi-person-fill co-input-icon"></i>
                                    <input type="text" id="co_name" name="name" class="co-input-field" value="{{ old('name', $user->name ?? '') }}" placeholder="Enter full name" required style="text-transform: capitalize;">
                                </div>
                            </div>

                            <div class="co-input-group">
                                <label class="co-input-label" for="co_phone">
                                    <span>Mobile Number (WhatsApp) <span class="text-danger">*</span></span>
                                </label>
                                <div class="co-input-wrapper">
                                    <i class="bi bi-whatsapp co-input-icon text-success"></i>
                                    <input type="tel" id="co_phone" name="phone" class="co-input-field" value="{{ old('phone', $user->phone ?? '') }}" placeholder="10-digit mobile number" maxlength="15" required>
                                </div>
                            </div>
                        </div>

                        {{-- Street Address --}}
                        <div class="co-input-group">
                            <label class="co-input-label" for="co_address">
                                <span>Complete House / Flat / Street Address <span class="text-danger">*</span></span>
                            </label>
                            <div class="co-input-wrapper">
                                <i class="bi bi-house-door-fill co-input-icon"></i>
                                <textarea id="co_address" name="address" class="co-textarea-field" rows="2" placeholder="House No, Apartment/Building, Street" required>{{ old('address', $user->address ?? '') }}</textarea>
                            </div>
                        </div>

                        {{-- Nearby Landmark --}}
                        <div class="co-input-group">
                            <label class="co-input-label" for="co_landmark">
                                <span>Nearby Landmark <span style="color:#94a3b8; font-weight:700;">(Optional)</span></span>
                            </label>
                            <div class="co-input-wrapper">
                                <i class="bi bi-geo-alt-fill co-input-icon"></i>
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
                                    <i class="bi bi-pin-map-fill co-input-icon"></i>
                                    <input type="text" id="co_pincode" name="pincode" class="co-input-field" value="{{ old('pincode', $user->pincode ?? '') }}" placeholder="6-digit pincode (e.g. 492001)" maxlength="6" required>
                                </div>
                            </div>

                            <div class="co-input-group">
                                <label class="co-input-label">Country</label>
                                <div class="co-input-wrapper">
                                    <i class="bi bi-globe2 co-input-icon"></i>
                                    <input type="text" class="co-input-field" value="India (IN)" readonly style="background: #f8fafc; color: #475569; font-weight: 700;">
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
                                    <i class="bi bi-map-fill co-input-icon"></i>
                                    <input type="text" id="co_state" name="state" class="co-input-field" value="{{ old('state', $user->state ?? '') }}" placeholder="State (Auto-detected)" required style="text-transform: capitalize;">
                                </div>
                            </div>

                            <div class="co-input-group" style="margin-bottom: 0;">
                                <label class="co-input-label" for="co_city">
                                    <span>City / District <span class="text-danger">*</span></span>
                                </label>
                                <div class="co-input-wrapper">
                                    <i class="bi bi-building-fill co-input-icon"></i>
                                    <input type="text" id="co_city" name="city" class="co-input-field" value="{{ old('city', $user->city ?? '') }}" placeholder="City (Auto-detected)" required style="text-transform: capitalize;">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section 2: Payment Method (with Brand Badges) --}}
                    <div class="co-panel">
                        <div class="co-panel-head">
                            <h2 class="co-panel-title">
                                <span class="co-panel-icon-circle"><i class="bi bi-shield-check"></i></span>
                                2. Select Payment Method
                            </h2>
                            <span class="pm-offer-pill">⚡ 5% Instant Discount on Prepaid</span>
                        </div>

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
                                                <span class="pm-offer-pill"><i class="bi bi-patch-check-fill"></i> Save 5%</span>
                                            </h4>
                                            <p>Pay via Google Pay, PhonePe, Paytm, BHIM, Cards or NetBanking. Instant VIP dispatch.</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Authentic SVG Brand Badges --}}
                                <div class="pm-brand-logos-row">
                                    {{-- Google Pay --}}
                                    <div class="pm-brand-badge" title="Google Pay">
                                        <svg viewBox="0 0 48 48" style="width: 20px; height: 20px;">
                                            <path fill="#4285F4" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                                            <path fill="#34A853" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                                            <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                                            <path fill="#EA4335" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                                        </svg>
                                        <span>GPay</span>
                                    </div>

                                    {{-- PhonePe --}}
                                    <div class="pm-brand-badge" style="color: #5f259f;" title="PhonePe">
                                        <svg viewBox="0 0 24 24" style="width: 18px; height: 18px; fill: #5f259f;">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.6 15.5l-3.2-4.5v4.5H8.8V6.5h3.6c2.4 0 4.1 1.5 4.1 3.7 0 1.6-.9 2.8-2.2 3.3l3.6 4h-4.3zm0-8.2c0-.9-.7-1.6-1.7-1.6h-1.5v3.2h1.5c1 0 1.7-.7 1.7-1.6z"/>
                                        </svg>
                                        <span>PhonePe</span>
                                    </div>

                                    {{-- Paytm --}}
                                    <div class="pm-brand-badge" style="color: #002e6e;" title="Paytm">
                                        <span style="color: #00b9f5; font-weight: 900;">Pay</span><span style="color: #002970; font-weight: 900;">tm</span>
                                    </div>

                                    {{-- BHIM UPI --}}
                                    <div class="pm-brand-badge" style="color: #00875a;" title="BHIM UPI">
                                        <i class="bi bi-qr-code-scan" style="color: #00875a; font-size: 13px;"></i>
                                        <span>UPI</span>
                                    </div>

                                    {{-- Visa / Mastercard --}}
                                    <div class="pm-brand-badge" title="Debit & Credit Cards">
                                        <i class="bi bi-credit-card-2-front-fill" style="color: var(--co-navy); font-size: 13px;"></i>
                                        <span>Cards</span>
                                    </div>

                                    {{-- NetBanking --}}
                                    <div class="pm-brand-badge" title="Net Banking">
                                        <i class="bi bi-bank2" style="color: #475569; font-size: 13px;"></i>
                                        <span>NetBanking</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Option 2: Cash on Delivery --}}
                            <div class="payment-method-card" data-payment="cod" onclick="selectPaymentMethod('cod')">
                                <div class="pm-card-top-row" style="margin-bottom: 0;">
                                    <div class="pm-left-side">
                                        <div class="pm-radio-disc"></div>
                                        <div class="pm-details">
                                            <h4>Cash on Delivery (COD)</h4>
                                            <p>Pay in cash or scan QR scanner with delivery partner at your doorstep.</p>
                                        </div>
                                    </div>
                                    <div class="pm-brand-badge" style="background: #f1f5f9;">
                                        <i class="bi bi-cash-stack text-success" style="font-size: 14px;"></i>
                                        <span>Cash / QR</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Section 3: Frequently Asked Questions (FAQ) --}}
                    <div class="co-panel co-faq-panel">
                        <div class="co-panel-head" style="margin-bottom: 16px;">
                            <h2 class="co-panel-title">
                                <span class="co-panel-icon-circle"><i class="bi bi-question-circle-fill"></i></span>
                                Frequently Asked Questions (FAQs)
                            </h2>
                            <span style="font-size: 11.5px; color: var(--co-text-muted); font-weight: 700;">
                                <i class="bi bi-shield-check text-success"></i> 24/7 Help
                            </span>
                        </div>

                        <div class="co-faq-accordion-list">
                            {{-- FAQ 1 --}}
                            <div class="co-faq-item open">
                                <button type="button" class="co-faq-btn" onclick="toggleCheckoutFaq(this)">
                                    <span><i class="bi bi-truck text-primary"></i> When will my order arrive?</span>
                                    <i class="bi bi-chevron-down co-faq-chevron"></i>
                                </button>
                                <div class="co-faq-body">
                                    All orders are dispatched within 24 hours. Metro cities typically receive deliveries within <b>2 to 4 business days</b>, while other regions take 4 to 7 business days. You will receive live WhatsApp & SMS tracking links as soon as your parcel ships.
                                </div>
                            </div>

                            {{-- FAQ 2 --}}
                            <div class="co-faq-item">
                                <button type="button" class="co-faq-btn" onclick="toggleCheckoutFaq(this)">
                                    <span><i class="bi bi-shield-lock-fill text-success"></i> Is online payment safe? (5% Discount)</span>
                                    <i class="bi bi-chevron-down co-faq-chevron"></i>
                                </button>
                                <div class="co-faq-body">
                                    Yes! All UPI, Cards, and NetBanking transactions are processed through RBI-approved 256-Bit SSL encrypted gateways. Plus, you get an automatic <b>⚡ 5% Instant Prepaid Discount</b> on your order.
                                </div>
                            </div>

                            {{-- FAQ 3 --}}
                            <div class="co-faq-item">
                                <button type="button" class="co-faq-btn" onclick="toggleCheckoutFaq(this)">
                                    <span><i class="bi bi-cash-stack text-warning"></i> How does Cash on Delivery (COD) work?</span>
                                    <i class="bi bi-chevron-down co-faq-chevron"></i>
                                </button>
                                <div class="co-faq-body">
                                    You can pay the exact invoice amount in cash or scan the delivery executive's UPI QR code right at your doorstep when the package is handed over to you.
                                </div>
                            </div>

                            {{-- FAQ 4 --}}
                            <div class="co-faq-item">
                                <button type="button" class="co-faq-btn" onclick="toggleCheckoutFaq(this)">
                                    <span><i class="bi bi-arrow-repeat text-info"></i> What is your Return & Exchange policy?</span>
                                    <i class="bi bi-chevron-down co-faq-chevron"></i>
                                </button>
                                <div class="co-faq-body">
                                    We offer a <b>7-day hassle-free return and exchange</b> policy from the date of delivery. If you need a size replacement or return, you can raise an instant request from your account or contact our support team.
                                </div>
                            </div>

                            {{-- FAQ 5 --}}
                            <div class="co-faq-item">
                                <button type="button" class="co-faq-btn" onclick="toggleCheckoutFaq(this)">
                                    <span><i class="bi bi-geo-alt-fill text-danger"></i> How can I track my order status?</span>
                                    <i class="bi bi-chevron-down co-faq-chevron"></i>
                                </button>
                                <div class="co-faq-body">
                                    You can track your order live anytime by visiting our <a href="{{ url('/track-order') }}" target="_blank" style="color:var(--co-navy); font-weight:800; text-decoration:underline;">Order Tracking Page</a> using your Order ID or phone number.
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ── RIGHT COLUMN: Order Summary & Pay CTA ── --}}
                <div class="checkout-right-column">
                    <div class="co-sidebar-summary">
                        
                        <div class="co-summary-header" id="coSummaryHeader" onclick="toggleOrderSummaryDetails()" style="cursor: pointer; user-select: none;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <h3 class="co-summary-title">
                                    <i class="bi bi-bag-check-fill text-primary"></i> Order Summary ({{ $totalItemsCount }})
                                </h3>
                                <i class="bi bi-chevron-down co-summary-toggle-icon" id="coSummaryChevron"></i>
                            </div>
                            <div onclick="event.stopPropagation();">
                                <a href="{{ route('cart.index') }}" class="co-summary-edit-link">Edit Bag</a>
                            </div>
                        </div>

                        {{-- Comprehensive Cart Items Preview (Collapsed by Default) --}}
                        <div class="co-cart-items-scroll" id="coCartItemsScroll" style="display: none;">
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
                                                    <i class="bi bi-aspect-ratio me-1"></i>Print: {{ ucfirst($item['design_side']) }} Side
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="co-cart-item-price-block">
                                        <div class="co-cart-item-price">₹{{ number_format(round($item['price'] * $item['quantity'])) }}</div>
                                        <div class="co-cart-item-unit-price">@ ₹{{ number_format(round($item['price'])) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Offers & Rewards and Bottom Sheet Modal --}}
                        @include('froentend.partials.coupon-section', ['subtotal' => $subtotal])

                        {{-- Calculations --}}
                        @php
                            $prepaidDiscount = round($subtotal * 0.05);
                            $prepaidTotal = max(1, round($total - $prepaidDiscount));
                            $totalSavings = round($couponDiscount + $prepaidDiscount);
                        @endphp

                        {{-- Savings Banner --}}
                        <div class="co-savings-highlight-banner" id="savingsHighlightBanner">
                            <i class="bi bi-stars" style="font-size: 15px;"></i>
                            <span>You are saving <b>₹{{ number_format($totalSavings) }}</b> on this order!</span>
                        </div>

                        {{-- Detailed Bill Breakdown --}}
                        <div class="co-bill-breakdown-box">
                            <div class="co-bill-row">
                                <span>Bag Subtotal ({{ $totalItemsCount }} {{ $totalItemsCount === 1 ? 'Item' : 'Items' }})</span>
                                <strong>₹{{ number_format(round($subtotal)) }}</strong>
                            </div>

                            @if($couponDiscount > 0)
                                <div class="co-bill-row green">
                                    <span>Coupon Discount Applied</span>
                                    <strong>-₹{{ number_format(round($couponDiscount)) }}</strong>
                                </div>
                            @endif

                            <div class="co-bill-row green" id="prepaidDiscountRow">
                                <span>⚡ 5% Instant Prepaid Discount</span>
                                <strong>-₹{{ number_format($prepaidDiscount) }}</strong>
                            </div>

                            <div class="co-bill-row">
                                <span>Shipping & Express Handling</span>
                                @if($shipping == 0)
                                    <strong class="text-success"><i class="bi bi-patch-check-fill"></i> FREE</strong>
                                @else
                                    <strong>₹{{ number_format(round($shipping)) }}</strong>
                                @endif
                            </div>
                        </div>

                        {{-- Total Row --}}
                        <div class="co-final-total-row">
                            <div>
                                <div class="co-final-total-label">Total Payable</div>
                                <span class="co-tax-inclusive-tag">(Inclusive of all taxes & GST)</span>
                            </div>
                            <div class="co-final-total-val" id="displayGrandTotal">
                                ₹{{ number_format($prepaidTotal) }}
                            </div>
                        </div>

                        {{-- Submit Order Button --}}
                        <button type="submit" class="btn-checkout-primary-cta" id="btnSubmitOrder">
                            <span id="btnSubmitText">PAY ₹{{ number_format($prepaidTotal) }} & PLACE ORDER</span>
                            <i class="bi bi-shield-lock-fill"></i>
                        </button>

                        {{-- Trust Badges Strip --}}
                        <div class="co-trust-strip">
                            <span><i class="bi bi-shield-fill-check text-success"></i> 256-Bit SSL</span>
                            <span><i class="bi bi-patch-check-fill text-primary"></i> 100% Genuine</span>
                            <span><i class="bi bi-arrow-repeat text-dark"></i> 7-Day Returns</span>
                        </div>

                    </div>
                </div>

            </div>
        </form>

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
const baseTotal = {{ $total }};
const couponDiscountVal = {{ $couponDiscount }};
const prepaidDiscountAmount = {{ $prepaidDiscount }};
const prepaidFinalTotal = {{ $prepaidTotal }};

function formatRoundedCurrency(value) {
    return '₹' + Math.round(Number(value || 0)).toLocaleString('en-IN');
}

function selectPaymentMethod(method) {
    document.getElementById('selectedPaymentMethod').value = method;
    document.querySelectorAll('.payment-method-card').forEach(card => {
        card.classList.toggle('active', card.getAttribute('data-payment') === method);
    });

    const discountRow = document.getElementById('prepaidDiscountRow');
    const savingsBanner = document.getElementById('savingsHighlightBanner');
    const totalVal = document.getElementById('displayGrandTotal');
    const submitText = document.getElementById('btnSubmitText');

    if (method === 'cod') {
        if (discountRow) discountRow.style.display = 'none';
        if (savingsBanner) {
            if (couponDiscountVal > 0) {
                savingsBanner.innerHTML = '<i class="bi bi-stars"></i> <span>You are saving <b>' + formatRoundedCurrency(couponDiscountVal) + '</b> on this order!</span>';
                savingsBanner.style.display = 'flex';
            } else {
                savingsBanner.style.display = 'none';
            }
        }
        if (totalVal) totalVal.textContent = formatRoundedCurrency(baseTotal);
        if (submitText) submitText.textContent = 'CONFIRM CASH ON DELIVERY ORDER (' + formatRoundedCurrency(baseTotal) + ')';
    } else {
        if (discountRow) discountRow.style.display = 'flex';
        if (savingsBanner) {
            const totSav = couponDiscountVal + prepaidDiscountAmount;
            savingsBanner.innerHTML = '<i class="bi bi-stars"></i> <span>You are saving <b>' + formatRoundedCurrency(totSav) + '</b> on this order!</span>';
            savingsBanner.style.display = 'flex';
        }
        if (totalVal) totalVal.textContent = formatRoundedCurrency(prepaidFinalTotal);
        if (submitText) submitText.textContent = 'PAY ' + formatRoundedCurrency(prepaidFinalTotal) + ' & PLACE ORDER';
    }
}

// Auto Capitalize Name, City, State on blur
['co_name', 'co_city', 'co_state'].forEach(id => {
    const el = document.getElementById(id);
    if (el) {
        el.addEventListener('blur', function() {
            if (this.value) {
                this.value = this.value.replace(/\b\w/g, char => char.toUpperCase());
            }
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
        pinStatus.innerHTML = '<span style="color:var(--co-navy); font-weight:700;"><i class="bi bi-arrow-repeat co-spin"></i> Checking delivery & location...</span>';
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
                            codNotice.innerHTML = `<i class="bi bi-slash-circle-fill me-1"></i> Cash on Delivery is disabled for pincode ${pin} due to regional transit policy. Please pay via UPI / Cards.`;
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
                    pinStatus.innerHTML = `<span style="color:#00285a; font-weight:800;"><i class="bi bi-truck text-primary"></i> Est. Delivery: ${data.estimated_delivery_date} (${data.delivery_days_text})</span>`;
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
    if (!btnPlaceOrder) return;
    btnPlaceOrder.disabled = false;
    const method = document.getElementById('selectedPaymentMethod').value;
    if (method === 'cod') {
        btnPlaceOrder.innerHTML = '<span id="btnSubmitText">CONFIRM CASH ON DELIVERY ORDER (' + formatRoundedCurrency(baseTotal) + ')</span> <i class="bi bi-shield-lock-fill"></i>';
    } else {
        btnPlaceOrder.innerHTML = '<span id="btnSubmitText">PAY ' + formatRoundedCurrency(prepaidFinalTotal) + ' & PLACE ORDER</span> <i class="bi bi-shield-lock-fill"></i>';
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
                checkoutForm.reportValidity();
                return;
            }

            const name = document.getElementById('co_name').value.trim();
            const phone = document.getElementById('co_phone').value.trim();
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
                    name: "Vayu",
                    description: "Order Payment",
                    order_id: res.razorpay_order_id,
                    prefill: {
                        name: name,
                        contact: phone,
                        email: "{{ $user->email ?? '' }}"
                    },
                    theme: {
                        color: "#00285a"
                    },
                    handler: function(response) {
                        showOrderPlaceLoader('Verifying payment', 'Payment received. We are placing your order now.');
                        if (btnPlaceOrder) {
                            btnPlaceOrder.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="width: 14px; height: 14px; border-width: 2px;"></span> VERIFYING PAYMENT...';
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
                                    phone: phone,
                                    address: address,
                                    landmark: landmark,
                                    pincode: pincode,
                                    state: state,
                                    city: city,
                                    subtotal: {{ $subtotal }},
                                    shipping: {{ $shipping }},
                                    discount: {{ $couponDiscount + $prepaidDiscount }},
                                    total: prepaidFinalTotal
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
                checkoutForm.reportValidity();
                return;
            }

            checkoutSubmitInProgress = true;
            showOrderPlaceLoader('Placing your order', 'Please wait. Do not refresh or press back.');
            if (btnPlaceOrder) {
                btnPlaceOrder.disabled = true;
                btnPlaceOrder.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="width: 14px; height: 14px; border-width: 2px;"></span> PLACING ORDER...';
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
</script>
@endpush

@endsection
