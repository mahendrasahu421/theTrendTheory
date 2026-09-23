{{-- resources/views/froentend/cart/index.blade.php --}}
@extends('froentend.layouts.app')

@push('seo')
    <title>Shopping Cart | THE TREND THEORY</title>
    <meta name="description" content="Review your shopping cart, apply coupons, and checkout securely with THE TREND THEORY.">
    <meta name="robots" content="noindex, nofollow">
@endpush

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   LUXURY SHOPPING CART & RECOMMENDATIONS STYLES
   ═══════════════════════════════════════════════════════════ */
:root {
    --tt-primary: #00285a;
    --tt-primary-dark: #001c3f;
    --tt-accent: #ff3f6c;
    --tt-accent-hover: #e62e5b;
    --tt-emerald: #059669;
    --tt-emerald-light: #ecfdf5;
    --tt-emerald-border: #a7f3d0;
    --tt-bg-light: #f8fafc;
    --tt-border: #e2e8f0;
    --tt-text-main: #0f172a;
    --tt-text-muted: #64748b;
}

.cart-page-wrapper {
    max-width: 1280px;
    margin: 24px auto 90px;
    padding: 0 20px;
    font-family: -apple-system, BlinkMacSystemFont, "Plus Jakarta Sans", "Segoe UI", Roboto, sans-serif;
    color: var(--tt-text-main);
}

/* ── Breadcrumb & Stepper ── */
.cart-nav-strip {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 24px;
}

.cart-breadcrumb {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--tt-text-muted);
}

.cart-breadcrumb a {
    color: var(--tt-text-muted);
    text-decoration: none;
    transition: color 0.15s ease;
}

.cart-breadcrumb span {
    color: var(--tt-text-main);
    font-weight: 700;
}

.cart-step-indicators {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 12px;
    font-weight: 700;
}

.cart-step {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--tt-text-muted);
}

.cart-step.active {
    color: var(--tt-primary);
}

.cart-step .step-dot {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #e2e8f0;
    color: var(--tt-text-muted);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: 800;
}

.cart-step.active .step-dot {
    background: var(--tt-primary);
    color: #ffffff;
}

.cart-step-line {
    width: 24px;
    height: 2px;
    background: #e2e8f0;
}

/* ── Page Header ── */
.cart-header-row {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    padding-bottom: 16px;
    margin-bottom: 24px;
    border-bottom: 1px solid var(--tt-border);
    flex-wrap: wrap;
    gap: 12px;
}

.cart-main-heading {
    font-family: 'Cinzel', serif !important;
    font-size: 26px;
    font-weight: 800;
    color: var(--tt-primary);
    letter-spacing: 1px;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
    text-transform: uppercase;
}

.cart-item-count-badge {
    background: #eff6ff;
    color: var(--tt-primary);
    border: 1px solid #bfdbfe;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    font-size: 12.5px;
    font-weight: 800;
    padding: 3px 12px;
    border-radius: 999px;
    letter-spacing: 0;
}

.cart-trust-shield-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 700;
    color: var(--tt-emerald);
    background: var(--tt-emerald-light);
    border: 1px solid var(--tt-emerald-border);
    padding: 5px 14px;
    border-radius: 999px;
}

/* ── Main Two-Column Layout ── */
.cart-layout-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 32px;
    align-items: flex-start;
}

/* ── Free Shipping Progress Card ── */
.free-shipping-tracker {
    background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);
    border: 1.5px solid var(--tt-emerald-border);
    border-radius: 14px;
    padding: 14px 18px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(5, 150, 105, 0.04);
}

.free-ship-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 13px;
    font-weight: 700;
    color: #166534;
    margin-bottom: 8px;
}

.free-ship-header.unlocked {
    color: #047857;
}

.free-ship-track-bar {
    height: 7px;
    background: #e2e8f0;
    border-radius: 999px;
    overflow: hidden;
    position: relative;
}

.free-ship-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #00285a 0%, #10b981 100%);
    border-radius: 999px;
    transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ── Cart Items List ── */
.cart-items-wrapper {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.cart-item-row-card {
    background: #ffffff;
    border: 1px solid var(--tt-border);
    border-radius: 16px;
    padding: 18px 20px;
    display: grid;
    grid-template-columns: 105px 1fr auto;
    gap: 22px;
    align-items: center;
    box-shadow: 0 2px 12px rgba(15, 23, 42, 0.03);
    transition: all 0.22s ease;
    position: relative;
}

.cart-item-media {
    position: relative;
    width: 105px;
    aspect-ratio: 3/4;
    border-radius: 12px;
    overflow: hidden;
    background: #f1f5f9;
    flex-shrink: 0;
}

.cart-item-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
}

.cart-item-badge-disc {
    position: absolute;
    top: 6px;
    left: 6px;
    background: var(--tt-accent);
    color: #ffffff;
    font-size: 10.5px;
    font-weight: 800;
    padding: 2px 6px;
    border-radius: 5px;
    line-height: 1;
    box-shadow: 0 2px 6px rgba(255, 63, 108, 0.3);
}

.cart-item-content {
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.cart-item-name-link {
    font-size: 15.5px;
    font-weight: 700;
    color: var(--tt-text-main);
    text-decoration: none;
    line-height: 1.35;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: color 0.15s ease;
}

.cart-item-tags-row {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    margin: 2px 0;
}

.cart-tag-pill {
    background: #f1f5f9;
    color: #334155;
    font-size: 12px;
    font-weight: 700;
    padding: 3px 9px;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.cart-in-stock-tag {
    color: var(--tt-emerald);
    font-size: 11.5px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.cart-item-pricing {
    display: flex;
    align-items: baseline;
    gap: 8px;
}

.cart-price-current {
    font-size: 16.5px;
    font-weight: 800;
    color: var(--tt-primary);
}

.cart-price-mrp {
    font-size: 13px;
    color: #94a3b8;
    text-decoration: line-through;
    font-weight: 500;
}

.cart-item-bottom-controls {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-top: 6px;
}

/* Quantity Stepper */
.cart-stepper {
    display: inline-flex;
    align-items: center;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    background: #ffffff;
    height: 34px;
    overflow: hidden;
}

.cart-stepper button {
    border: 0;
    background: transparent;
    width: 32px;
    height: 100%;
    font-size: 16px;
    font-weight: 800;
    cursor: pointer;
    color: var(--tt-text-main);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.15s ease;
}

.cart-stepper span {
    width: 32px;
    text-align: center;
    font-size: 13.5px;
    font-weight: 800;
    color: var(--tt-text-main);
}

/* Subtotal & Remove Column */
.cart-item-right-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    justify-content: space-between;
    height: 100%;
    min-height: 96px;
}

.cart-item-subtotal-val {
    font-size: 18px;
    font-weight: 900;
    color: var(--tt-primary);
}

.cart-btn-trash {
    background: #fef2f2;
    border: 1px solid #fee2e2;
    color: #ef4444;
    width: 36px;
    height: 36px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 15px;
    transition: all 0.2s ease;
}

/* ── Cart Perks Bar (Under Items) ── */
.cart-perks-strip {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-top: 24px;
    padding: 16px;
    background: #ffffff;
    border: 1px solid var(--tt-border);
    border-radius: 14px;
}

.cart-perk-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 12px;
    font-weight: 700;
    color: #475569;
}

.cart-perk-item i {
    font-size: 20px;
    color: var(--tt-primary);
}

/* ── Sticky Order Summary Panel ── */
.cart-summary-card {
    background: #ffffff;
    border: 1px solid var(--tt-border);
    border-radius: 20px;
    padding: 24px;
    box-shadow: 0 4px 24px rgba(15, 23, 42, 0.05);
    position: sticky;
    top: 96px;
}

.cart-summary-head {
    font-family: 'Cinzel', serif !important;
    font-size: 17px;
    font-weight: 800;
    color: var(--tt-primary);
    letter-spacing: 0.8px;
    margin: 0 0 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f1f5f9;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.cart-summary-head-title {
    display: flex;
    align-items: center;
    gap: 8px;
}

.cart-summary-head-count {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    font-size: 13px;
    font-weight: 700;
    color: #64748b;
    text-transform: none;
    letter-spacing: 0;
}

.cart-summary-head-right {
    display: flex;
    align-items: center;
    gap: 8px;
}

.cart-summary-head-total {
    display: none;
}

.cart-summary-head-secure {
    font-size: 11.5px;
    font-weight: 700;
    color: #64748b;
    font-family: sans-serif;
    letter-spacing: 0.5px;
}

.cart-summary-chevron {
    display: none;
}

.cart-summary-collapsible-body {
    display: block;
}

.cart-prepaid-alert {
    background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%);
    border: 1px solid #a7f3d0;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 12px;
    color: #047857;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
}

.cart-prepaid-alert i {
    font-size: 15px;
    color: #059669;
}

.cart-calc-rows {
    display: flex;
    flex-direction: column;
    gap: 11px;
    margin-bottom: 18px;
}

.calc-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13.5px;
    color: #475569;
}

.calc-row b {
    color: var(--tt-text-main);
    font-weight: 700;
}

.calc-row.green b,
.calc-row.green span {
    color: #047857;
    font-weight: 700;
}

.calc-row-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1.5px dashed #cbd5e1;
    padding-top: 14px;
    margin-top: 4px;
}

.calc-total-title {
    font-size: 14.5px;
    font-weight: 800;
    color: var(--tt-text-main);
    text-transform: uppercase;
}

.calc-total-num {
    font-size: 24px;
    font-weight: 900;
    color: var(--tt-primary);
    letter-spacing: -0.5px;
}

.cart-savings-highlight {
    background: #ecfdf5;
    border: 1px solid #a7f3d0;
    border-radius: 10px;
    padding: 10px 14px;
    color: #047857;
    font-size: 12.5px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}

/* Coupon Box */
.cart-promo-container {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px;
    margin-bottom: 16px;
}

.cart-promo-input-group {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    padding: 0 10px;
    height: 42px;
    transition: all 0.2s ease;
}

.cart-promo-input-group:focus-within {
    border-color: var(--tt-primary);
    box-shadow: 0 0 0 2px rgba(0, 40, 90, 0.08);
}

.cart-promo-input-group input {
    flex: 1;
    border: 0;
    outline: 0;
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--tt-text-main);
    background: transparent;
}

.cart-promo-input-group button {
    border: 0;
    background: var(--tt-primary);
    color: #ffffff;
    border-radius: 6px;
    padding: 6px 14px;
    font-size: 11.5px;
    font-weight: 800;
    cursor: pointer;
    transition: background 0.15s ease;
}

.cart-promo-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 10px;
}

.promo-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #f0fdf4;
    border: 1px dashed #86efac;
    color: #166534;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 9px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.15s ease;
    user-select: none;
}

.promo-chip small {
    background: #22c55e;
    color: #ffffff;
    font-size: 9px;
    font-weight: 800;
    padding: 1px 4px;
    border-radius: 3px;
}

.promo-response-msg {
    font-size: 12px;
    margin-top: 8px;
    padding: 6px 10px;
    border-radius: 6px;
    text-align: center;
    display: none;
}

.promo-response-msg.success {
    background: #ecfdf5;
    color: #047857;
    display: block;
}

.promo-response-msg.error {
    background: #fef2f2;
    color: #dc2626;
    display: block;
}

/* Primary CTA */
.btn-checkout-primary {
    width: 100%;
    height: 54px;
    border: 0;
    border-radius: 12px;
    background: linear-gradient(135deg, #00285a 0%, #0f4c81 100%);
    color: #ffffff;
    font-size: 14.5px;
    font-weight: 800;
    letter-spacing: 0.6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    box-shadow: 0 10px 24px rgba(0, 40, 90, 0.28);
    transition: all 0.25s ease;
    text-transform: uppercase;
}

.btn-continue-shopping {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    margin-top: 14px;
    color: #64748b;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    transition: color 0.15s ease;
}

/* Trust Badges Strip */
.cart-summary-trust-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-top: 20px;
    padding-top: 18px;
    border-top: 1px solid #f1f5f9;
}

.trust-badge-card {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
}

.trust-badge-card i {
    font-size: 16px;
    color: var(--tt-primary);
}

/* ── Empty Cart State ── */
.cart-empty-wrapper {
    text-align: center;
    padding: 60px 20px;
    background: #ffffff;
    border-radius: 20px;
    border: 1px solid var(--tt-border);
    max-width: 580px;
    margin: 30px auto 50px;
    box-shadow: 0 4px 24px rgba(15, 23, 42, 0.04);
}

.cart-empty-circle {
    width: 86px;
    height: 86px;
    border-radius: 50%;
    background: #f1f5f9;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
    margin: 0 auto 20px;
}

.cart-empty-headline {
    font-family: 'Cinzel', serif !important;
    font-size: 22px;
    font-weight: 800;
    color: var(--tt-primary);
    margin-bottom: 8px;
    letter-spacing: 0.5px;
}

.cart-empty-subline {
    font-size: 14px;
    color: #64748b;
    margin-bottom: 24px;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

.btn-start-shopping-cta {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--tt-primary);
    color: #ffffff;
    padding: 13px 34px;
    border-radius: 999px;
    text-decoration: none;
    font-size: 13.5px;
    font-weight: 800;
    letter-spacing: 0.5px;
    transition: all 0.22s ease;
    box-shadow: 0 8px 22px rgba(0, 40, 90, 0.22);
}

/* ═══════════════════════════════════════════════════════════
   RECOMMENDATIONS SECTIONS (RECENTLY VIEWED & RELATED PRODUCTS)
   ═══════════════════════════════════════════════════════════ */
.cart-recommendations-section {
    margin-top: 60px;
    padding-top: 40px;
    border-top: 1.5px dashed #cbd5e1;
}

.section-title-wrap {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 12px;
}

.section-title-wrap .title-area h3 {
    font-family: 'Cinzel', serif !important;
    font-size: 20px;
    font-weight: 800;
    color: var(--tt-primary);
    letter-spacing: 0.8px;
    margin: 0 0 4px;
    display: flex;
    align-items: center;
    gap: 8px;
    text-transform: uppercase;
}

.section-title-wrap .title-area p {
    font-size: 13px;
    color: #64748b;
    margin: 0;
}

.section-title-wrap .view-all-link {
    font-size: 13px;
    font-weight: 700;
    color: var(--tt-primary);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: color 0.15s ease;
}

/* ── Cart Product Sliders (Matching Home Page Carousel) ── */
.cart-slider-section {
    margin-top: 48px;
    padding-top: 36px;
    border-top: 1px solid #e2e8f0;
}

.cart-slider-section .collection-header {
    margin-bottom: 24px;
    text-align: center;
}

.cart-slider-section .collection-header h2 {
    font-family: 'Cinzel', serif;
    font-size: clamp(22px, 2.4vw, 30px);
    font-weight: 800;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #00285a;
    margin: 0 0 6px;
}

.cart-slider-section .collection-header p {
    font-size: 13px;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin: 0;
}

.cart-slider-section .slider-container-collection {
    position: relative;
    max-width: 100%;
    padding: 0 45px;
    overflow: hidden;
}

.cart-slider-section .collection-arrow {
    width: 44px;
    height: 44px;
    background: #ffffff;
    color: #00285a;
    border: 1.5px solid #e2e8f0;
    font-size: 18px;
    border-radius: 50%;
    box-shadow: 0 6px 20px rgba(0, 40, 90, 0.12);
    z-index: 25;
    position: absolute;
    top: 42%;
    transform: translateY(-50%);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.cart-slider-section .collection-arrow:hover {
    background: #00285a;
    color: #ffffff;
    border-color: #00285a;
    box-shadow: 0 10px 25px rgba(0, 40, 90, 0.25);
    transform: translateY(-50%) scale(1.08);
}

.cart-slider-section .collection-arrow-left {
    left: 4px;
}

.cart-slider-section .collection-arrow-right {
    right: 4px;
}

.cart-slider-section .collection-slider-wrapper {
    overflow: hidden;
    width: 100%;
    cursor: grab;
}

.cart-slider-section .collection-slider-wrapper:active {
    cursor: grabbing;
}

.cart-slider-section .collection-track {
    display: flex;
    gap: 22px;
    width: max-content;
    transition: transform 0.4s cubic-bezier(0.22, 0.68, 0.32, 1);
    will-change: transform;
    padding: 6px 2px 16px;
}

.cart-slider-section .collection-slide {
    width: 280px;
    flex-shrink: 0;
}

@media (max-width: 768px) {
    .cart-slider-section .slider-container-collection {
        padding: 0 24px;
    }
    .cart-slider-section .collection-slide {
        width: 72vw;
        max-width: 270px;
    }
    .cart-slider-section .collection-arrow {
        width: 36px;
        height: 36px;
        font-size: 15px;
    }
    .cart-slider-section .collection-arrow-left {
        left: 0;
    }
    .cart-slider-section .collection-arrow-right {
        right: 0;
    }
}

/* ── Responsive Media Queries ── */
@media (max-width: 1024px) {
    .cart-layout-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    .cart-summary-card {
        position: static;
    }
    .cart-perks-strip {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .cart-page-wrapper {
        margin: 12px auto calc(130px + env(safe-area-inset-bottom, 0px)) !important;
        padding: 0 14px;
    }

    .cart-nav-strip {
        gap: 8px;
        margin-bottom: 16px;
    }

    .cart-breadcrumb {
        font-size: 12px;
    }

    .cart-step-indicators {
        gap: 6px;
        font-size: 11px;
    }

    .cart-step .step-dot {
        width: 18px;
        height: 18px;
        font-size: 9px;
    }

    .cart-step-line {
        width: 14px;
    }

    .cart-header-row {
        margin-bottom: 16px;
        padding-bottom: 12px;
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .cart-main-heading {
        font-size: 19px !important;
        letter-spacing: 0.5px;
    }

    .cart-item-count-badge {
        font-size: 11px;
        padding: 2px 8px;
    }

    .cart-trust-shield-pill {
        font-size: 11px;
        padding: 4px 10px;
    }

    /* Free shipping tracker */
    .free-shipping-tracker {
        padding: 12px 14px;
        margin-bottom: 14px;
    }

    .free-ship-header {
        font-size: 12px;
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
    }

    /* Cart item card on mobile */
    .cart-item-row-card {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px;
        border-radius: 14px;
        position: relative;
    }

    .cart-item-media {
        width: 90px;
        border-radius: 10px;
        flex-shrink: 0;
    }

    .cart-item-content {
        flex: 1;
        min-width: 0;
        gap: 5px;
    }

    .cart-item-title-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 8px;
    }

    .cart-item-name-link {
        font-size: 14px;
        line-height: 1.3;
        font-weight: 700;
        flex: 1;
    }

    .cart-btn-trash-mobile {
        background: #fef2f2;
        border: 1px solid #fee2e2;
        color: #ef4444;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        cursor: pointer;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }

    .cart-item-tags-row {
        gap: 5px;
        margin: 2px 0;
    }

    .cart-tag-pill {
        font-size: 11px;
        padding: 2px 7px;
    }

    .cart-price-current {
        font-size: 16px;
    }

    .cart-item-bottom-controls {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-top: 8px;
        padding-top: 6px;
    }

    .cart-stepper {
        height: 32px;
    }

    .cart-stepper button {
        width: 28px;
        font-size: 15px;
    }

    .cart-stepper span {
        width: 28px;
        font-size: 13px;
    }

    .cart-item-subtotal-mobile strong {
        font-size: 15px;
        color: var(--tt-primary);
        font-weight: 800;
    }

    /* Assurance Perks Strip */
    .cart-perks-strip {
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        padding: 12px;
        margin-top: 18px;
    }

    .cart-perk-item {
        font-size: 11px;
        gap: 8px;
    }

    .cart-perk-item i {
        font-size: 16px;
    }

    /* Summary card collapsible on mobile */
    .cart-summary-card {
        padding: 0 !important;
        border-radius: 16px;
        margin-top: 16px;
        overflow: hidden;
        border: 1.5px solid #e2e8f0;
        box-shadow: 0 2px 14px rgba(15, 23, 42, 0.05);
        background: #ffffff;
        transition: border-color 0.25s ease, box-shadow 0.25s ease;
    }

    .cart-summary-card.is-open {
        border-color: #cbd5e1;
        box-shadow: 0 6px 22px rgba(15, 23, 42, 0.09);
    }

    .cart-summary-head {
        padding: 14px 16px;
        margin: 0;
        cursor: pointer;
        user-select: none;
        -webkit-tap-highlight-color: transparent;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 0 solid transparent;
        transition: background-color 0.2s ease, border-color 0.2s ease;
    }

    .cart-summary-card.is-open .cart-summary-head {
        border-bottom: 1px solid #f1f5f9;
        background-color: #f8fafc;
    }

    .cart-summary-head-title {
        font-size: 14.5px;
        font-weight: 800;
        color: var(--tt-primary);
        letter-spacing: 0.6px;
        gap: 6px;
    }

    .cart-summary-head-count {
        font-size: 12px;
        color: #64748b;
    }

    .cart-summary-head-total {
        display: inline-block !important;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        font-size: 15px;
        font-weight: 800;
        color: var(--tt-primary);
        letter-spacing: -0.2px;
    }

    .cart-summary-head-secure {
        display: none !important;
    }

    .cart-summary-chevron {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #00285a;
        font-size: 13px;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), background-color 0.2s ease, color 0.2s ease;
    }

    .cart-summary-card.is-open .cart-summary-chevron {
        transform: rotate(180deg);
        background: #e2e8f0;
        color: var(--tt-primary);
    }

    .cart-summary-collapsible-body {
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        padding: 0 16px;
        transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.25s ease, padding 0.3s ease;
    }

    .cart-summary-card.is-open .cart-summary-collapsible-body {
        max-height: 1500px;
        opacity: 1;
        padding: 16px 16px 18px;
    }

    /* Hide desktop checkout button on mobile since we have the sticky bar */
    .cart-summary-card .btn-checkout-primary {
        display: none !important;
    }

    /* ── Mobile Sticky Checkout Bar Styles ── */
    .cart-mobile-sticky-wrap {
        display: block !important;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 10000;
        pointer-events: none;
    }

    .cart-mobile-backdrop {
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

    .cart-mobile-backdrop.is-open {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    .cart-mobile-card {
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

    .cart-mobile-drawer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.32s cubic-bezier(0.4, 0, 0.2, 1), padding 0.32s ease, opacity 0.25s ease;
        opacity: 0;
        border-bottom: 0 solid #f1f5f9;
    }

    .cart-mobile-drawer.is-open {
        max-height: 480px;
        opacity: 1;
        padding-bottom: 12px;
        margin-bottom: 10px;
        border-bottom: 1.5px solid #f1f5f9;
    }

    .cart-drawer-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 8px;
        margin-bottom: 8px;
        border-bottom: 1px dashed #e2e8f0;
    }

    .cart-drawer-title {
        font-size: 13px;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .cart-drawer-close {
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

    .cart-drawer-body {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .cart-drawer-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12.5px;
        color: #475569;
    }

    .cart-drawer-row strong {
        font-weight: 700;
        color: #0f172a;
    }

    .cart-drawer-row.text-success strong {
        color: #059669;
    }

    .cart-drawer-divider {
        height: 1px;
        background: #f1f5f9;
        margin: 4px 0;
    }

    .cart-drawer-total-row {
        font-size: 13.5px;
        padding-top: 2px;
    }

    .cart-drawer-total-label {
        font-weight: 800;
        color: #0f172a;
    }

    .cart-drawer-total-val {
        font-family: 'Cinzel', serif !important;
        font-size: 20px;
        font-weight: 900;
        color: #00285a;
    }

    .cart-drawer-savings {
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

    /* Sticky Bar Header */
    .cart-mobile-bar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 8px;
        cursor: pointer;
        user-select: none;
    }

    .cart-mobile-header-left {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .cart-mobile-total-label {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
    }

    .cart-mobile-amount {
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

    .cart-mobile-view-bill {
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

    .cart-mobile-view-bill i {
        font-size: 10px;
        transition: transform 0.25s ease;
    }

    .cart-mobile-view-bill i.rotated {
        transform: rotate(180deg);
    }

    /* Full-Width Mobile Checkout CTA Button */
    .cart-mobile-btn-row {
        width: 100%;
    }

    .btn-cart-mobile-checkout-full {
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

    .btn-cart-mobile-checkout-full:active {
        transform: scale(0.99);
        opacity: 0.95;
    }
}

/* Hide sticky bar on desktop */
.cart-mobile-sticky-wrap {
    display: none;
}
</style>
@endpush

@section('main')
<div class="cart-page-wrapper">

    {{-- Breadcrumb & Stepper --}}
    <div class="cart-nav-strip">
        <div class="cart-breadcrumb">
            <a href="{{ route('home') }}"><i class="bi bi-house-door"></i> Home</a>
            <i class="bi bi-chevron-right" style="font-size: 10px;"></i>
            <a href="{{ route('shop.index') }}">Shop</a>
            <i class="bi bi-chevron-right" style="font-size: 10px;"></i>
            <span>Shopping Cart</span>
        </div>

        <div class="cart-step-indicators">
            <div class="cart-step active">
                <span class="step-dot">1</span>
                <span>Cart</span>
            </div>
            <div class="cart-step-line"></div>
            <div class="cart-step">
                <span class="step-dot">2</span>
                <span>Address</span>
            </div>
            <div class="cart-step-line"></div>
            <div class="cart-step">
                <span class="step-dot">3</span>
                <span>Payment</span>
            </div>
        </div>
    </div>

    @if(count($cart) > 0)
        @php
            $totalItemsCount = collect($cart)->sum('quantity');
            $totalMrp = collect($cart)->sum(function($item) {
                $p = (float)($item['price'] ?? 0);
                $orig = (float)($item['original_price'] ?? $p);
                return $orig * (int)($item['quantity'] ?? 1);
            });
            $discountOnMrp = max(0, $totalMrp - $subtotal);
            $prepaidDiscount = round($subtotal * 0.05, 2);
            $freeShippingThreshold = 999;
            $freeShippingProgress = min(100, round(($subtotal / $freeShippingThreshold) * 100));
            $neededForFreeShip = max(0, $freeShippingThreshold - $subtotal);
            $totalSavings = $discountOnMrp + ($couponDiscount ?? 0) + $prepaidDiscount;
        @endphp

        {{-- Header Bar --}}
        <div class="cart-header-row">
            <div>
                <h1 class="cart-main-heading">
                    <i class="bi bi-bag-check-fill"></i> YOUR SHOPPING BAG
                    <span class="cart-item-count-badge" id="cartPageBadge">{{ $totalItemsCount }} {{ $totalItemsCount === 1 ? 'ITEM' : 'ITEMS' }}</span>
                </h1>
            </div>
            <div class="cart-trust-shield-pill">
                <i class="bi bi-shield-lock-fill"></i> 100% Verified &amp; Secure Checkout
            </div>
        </div>

        {{-- Two-Column Layout --}}
        <div class="cart-layout-grid">

            {{-- ── LEFT: CART ITEMS ── --}}
            <div class="cart-items-column">

                {{-- Free Shipping Tracker --}}
                <div class="free-shipping-tracker" id="freeShipTracker">
                    <div class="free-ship-header {{ $neededForFreeShip == 0 ? 'unlocked' : '' }}" id="freeShipText">
                        @if($neededForFreeShip == 0)
                            <span><i class="bi bi-patch-check-fill text-success"></i> <strong>Congratulations!</strong> You unlocked FREE Shipping!</span>
                            <span class="badge bg-success-subtle text-success border px-2 py-1">FREE DELIVERY</span>
                        @else
                            <span><i class="bi bi-truck text-primary"></i> Add <strong>₹{{ number_format($neededForFreeShip) }}</strong> more to unlock <strong>FREE Shipping</strong></span>
                            <span class="text-muted font-monospace">₹{{ number_format($subtotal) }}/₹999</span>
                        @endif
                    </div>
                    <div class="free-ship-track-bar">
                        <div class="free-ship-progress-fill" id="freeShipBar" style="width: {{ $freeShippingProgress }}%;"></div>
                    </div>
                </div>

                {{-- Items Cards List --}}
                <div class="cart-items-wrapper" id="cartItemsContainer">
                    @foreach($cart as $key => $item)
                        @php
                            $q = (int) ($item['quantity'] ?? 1);
                            $p = (float) ($item['price'] ?? 0);
                            $orig = (float) ($item['original_price'] ?? $p);
                            $disc = $orig > $p ? round((($orig - $p) / $orig) * 100) : 0;
                            $itemSlug = $item['slug'] ?? '';
                            $itemUrl = $itemSlug ? route('product.show', $itemSlug) : '#';
                        @endphp
                        <div class="cart-item-row-card" id="cart-item-{{ $key }}">
                            {{-- Thumbnail --}}
                            <div class="cart-item-media">
                                <a href="{{ $itemUrl }}">
                                    <img src="{{ $item['image'] ?? asset('images/placeholder-product.jpg') }}" alt="{{ $item['name'] ?? 'Product' }}" loading="lazy" onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                                </a>
                                @if($disc > 0)
                                    <span class="cart-item-badge-disc">-{{ $disc }}%</span>
                                @endif
                            </div>

                            {{-- Content details --}}
                            <div class="cart-item-content">
                                <div class="cart-item-title-row">
                                    <a href="{{ $itemUrl }}" class="cart-item-name-link">
                                        {{ $item['name'] ?? 'Product' }}
                                    </a>
                                    <button type="button" class="cart-btn-trash-mobile d-md-none" onclick="removeCartItem('{{ $key }}')" title="Remove item from bag">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>

                                <div class="cart-item-tags-row">
                                    @if(!empty($item['size']))
                                        <span class="cart-tag-pill"><i class="bi bi-rulers"></i> Size: {{ $item['size'] }}</span>
                                    @endif
                                    @if(!empty($item['color']))
                                        <span class="cart-tag-pill"><i class="bi bi-palette"></i> Color: {{ $item['color'] }}</span>
                                    @endif
                                    @if(!empty($item['design_side']))
                                        <span class="cart-tag-pill"><i class="bi bi-aspect-ratio"></i> Print: {{ ucfirst($item['design_side']) }} Side</span>
                                    @endif
                                    <span class="cart-in-stock-tag"><i class="bi bi-check-circle-fill"></i> In Stock</span>
                                </div>

                                <div class="cart-item-pricing">
                                    <span class="cart-price-current">₹{{ number_format($p) }}</span>
                                    @if($orig > $p)
                                        <span class="cart-price-mrp">₹{{ number_format($orig) }}</span>
                                    @endif
                                </div>

                                <div class="cart-item-bottom-controls">
                                    <div class="cart-stepper">
                                        <button type="button" aria-label="Decrease quantity" onclick="updateCartItemQty('{{ $key }}', -1)">−</button>
                                        <span id="qty-val-{{ $key }}">{{ $q }}</span>
                                        <button type="button" aria-label="Increase quantity" onclick="updateCartItemQty('{{ $key }}', 1)">+</button>
                                    </div>
                                    <div class="cart-item-subtotal-mobile d-md-none">
                                        <span class="text-muted" style="font-size: 11px;">Subtotal:</span>
                                        <strong id="subtotal-val-mobile-{{ $key }}">₹{{ number_format($p * $q) }}</strong>
                                    </div>
                                </div>
                            </div>

                            {{-- Subtotal & Remove (Desktop) --}}
                            <div class="cart-item-right-actions d-none d-md-flex">
                                <div class="cart-item-subtotal-val" id="subtotal-val-{{ $key }}">
                                    ₹{{ number_format($p * $q) }}
                                </div>
                                <button type="button" class="cart-btn-trash" onclick="removeCartItem('{{ $key }}')" title="Remove item from bag">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Assurance Strip Below Items --}}
                <div class="cart-perks-strip">
                    <div class="cart-perk-item">
                        <i class="bi bi-patch-check-fill text-primary"></i>
                        <span>100% Authentic Guaranteed</span>
                    </div>
                    <div class="cart-perk-item">
                        <i class="bi bi-arrow-repeat text-success"></i>
                        <span>7-Day Easy Returns</span>
                    </div>
                    <div class="cart-perk-item">
                        <i class="bi bi-truck text-warning"></i>
                        <span>Express Fast Dispatch</span>
                    </div>
                    <div class="cart-perk-item">
                        <i class="bi bi-cash-coin text-info"></i>
                        <span>Cash on Delivery Available</span>
                    </div>
                </div>

            </div>

            {{-- ── RIGHT: STICKY ORDER SUMMARY ── --}}
            <div class="cart-summary-column">
                <div class="cart-summary-card" id="cartSummaryCard">
                    <div class="cart-summary-head" id="cartSummaryHead" onclick="toggleCartOrderSummary()">
                        <div class="cart-summary-head-title">
                            <i class="bi bi-receipt"></i>
                            <span>ORDER SUMMARY</span>
                            <span class="cart-summary-head-count" id="summaryHeadCountBadge">({{ $totalItemsCount }})</span>
                        </div>
                        <div class="cart-summary-head-right">
                            <span class="cart-summary-head-total" id="summaryHeadTotalVal">₹{{ number_format(max(0, $total - $prepaidDiscount)) }}</span>
                            <span class="cart-summary-head-secure">SECURE</span>
                            <i class="bi bi-chevron-down cart-summary-chevron" id="cartSummaryChevron"></i>
                        </div>
                    </div>

                    {{-- Collapsible Body on Mobile --}}
                    <div class="cart-summary-collapsible-body" id="cartSummaryBody">
                        {{-- Prepaid Discount Alert Ribbon --}}
                        <div class="cart-prepaid-alert">
                            <i class="bi bi-lightning-charge-fill"></i>
                            <span><strong>Prepaid Offer:</strong> Extra 5% Instant Discount auto-applied at checkout!</span>
                        </div>

                        {{-- Price Calculation Rows --}}
                        <div class="cart-calc-rows">
                            <div class="calc-row">
                                <span>Total MRP (<span id="summaryTotalItems">{{ $totalItemsCount }}</span> items)</span>
                                <b id="summaryMrpVal">₹{{ number_format($totalMrp) }}</b>
                            </div>
                            <div class="calc-row green" id="mrpDiscountRow">
                                <span><i class="bi bi-percent"></i> Discount on MRP</span>
                                <b id="summaryMrpDiscountVal">-₹{{ number_format($discountOnMrp) }}</b>
                            </div>
                            <div class="calc-row">
                                <span>Subtotal</span>
                                <b id="summarySubtotalVal">₹{{ number_format($subtotal) }}</b>
                            </div>
                            <div class="calc-row green" id="prepaidDiscountRow">
                                <span><i class="bi bi-lightning-fill"></i> Prepaid 5% Extra Off</span>
                                <b id="summaryPrepaidDiscountVal">-₹{{ number_format($prepaidDiscount) }}</b>
                            </div>
                            <div class="calc-row {{ $shipping == 0 ? 'green' : '' }}">
                                <span><i class="bi bi-truck"></i> Estimated Shipping</span>
                                <b id="summaryShippingVal">{{ $shipping == 0 ? 'FREE' : '₹' . $shipping }}</b>
                            </div>
                            <div class="calc-row green" id="couponDiscountRow" style="display: {{ isset($couponDiscount) && $couponDiscount > 0 ? 'flex' : 'none' }};">
                                <span><i class="bi bi-tag-fill"></i> Coupon Discount (<span id="couponCodeBadge">{{ session('coupon_code', '') }}</span>)</span>
                                <b id="summaryCouponDiscountVal">-₹{{ number_format($couponDiscount ?? 0) }}</b>
                            </div>

                            <div class="calc-row-total">
                                <div>
                                    <div class="calc-total-title">Total Payable</div>
                                    <div style="font-size: 11px; color: #64748b; font-weight: 500;">(Inclusive of all taxes)</div>
                                </div>
                                <span class="calc-total-num" id="summaryGrandTotalVal">₹{{ number_format(max(0, $total - $prepaidDiscount)) }}</span>
                            </div>
                        </div>

                        {{-- Savings Highlight Pill --}}
                        <div class="cart-savings-highlight" id="totalSavingsBadge">
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <i class="bi bi-patch-check-fill"></i>
                                <span>YOUR TOTAL SAVINGS</span>
                            </div>
                            <b id="summaryTotalSavingsVal">₹{{ number_format($totalSavings) }}</b>
                        </div>

                        {{-- Offers & Rewards and Bottom Sheet Modal --}}
                        @include('froentend.partials.coupon-section', ['subtotal' => $subtotal])

                        {{-- Primary Checkout Button --}}
                        <button type="button" class="btn-checkout-primary" onclick="proceedToGlobalCheckout()">
                            <span>PROCEED TO CHECKOUT</span>
                            <i class="bi bi-shield-lock-fill"></i>
                        </button>

                        <a href="{{ route('shop.index') }}" class="btn-continue-shopping">
                            <i class="bi bi-arrow-left"></i> Continue Shopping
                        </a>

                        {{-- Trust Grid --}}
                        <div class="cart-summary-trust-grid">
                            <div class="trust-badge-card"><i class="bi bi-patch-check-fill"></i> 100% Original</div>
                            <div class="trust-badge-card"><i class="bi bi-arrow-repeat"></i> 7-Day Returns</div>
                            <div class="trust-badge-card"><i class="bi bi-shield-check"></i> RBI Verified</div>
                            <div class="trust-badge-card"><i class="bi bi-lock-fill"></i> 256-Bit SSL</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Mobile Fixed Sticky Checkout Bar (Full-Width Button & Collapsible Bill Details) ── --}}
        <div class="cart-mobile-sticky-wrap" id="cartMobileStickyBar">
            {{-- Dim backdrop when bill drawer is expanded --}}
            <div class="cart-mobile-backdrop" id="cartMobileBackdrop" onclick="toggleCartMobileBill(false)"></div>

            <div class="cart-mobile-card">
                {{-- Collapsible Bill Details Drawer --}}
                <div class="cart-mobile-drawer" id="cartMobileDrawer">
                    <div class="cart-drawer-header">
                        <span class="cart-drawer-title">
                            <i class="bi bi-receipt-cutoff text-primary"></i> Order Price Details
                        </span>
                        <button type="button" class="cart-drawer-close" onclick="toggleCartMobileBill(false)" aria-label="Close">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <div class="cart-drawer-body">
                        <div class="cart-drawer-row">
                            <span>Total MRP (<span id="mobileDrawerTotalItems">{{ $totalItemsCount }}</span> items)</span>
                            <strong id="mobileDrawerMrpVal">₹{{ number_format($totalMrp) }}</strong>
                        </div>

                        <div class="cart-drawer-row text-success" id="mobileDrawerMrpDiscountRow">
                            <span><i class="bi bi-percent"></i> Discount on MRP</span>
                            <strong id="mobileDrawerMrpDiscountVal">-₹{{ number_format($discountOnMrp) }}</strong>
                        </div>

                        <div class="cart-drawer-row">
                            <span>Bag Subtotal</span>
                            <strong id="mobileDrawerSubtotalVal">₹{{ number_format($subtotal) }}</strong>
                        </div>

                        <div class="cart-drawer-row text-success" id="mobileDrawerPrepaidDiscountRow">
                            <span><i class="bi bi-lightning-fill"></i> Prepaid 5% Extra Off</span>
                            <strong id="mobileDrawerPrepaidDiscountVal">-₹{{ number_format($prepaidDiscount) }}</strong>
                        </div>

                        <div class="cart-drawer-row">
                            <span>Estimated Shipping</span>
                            <strong id="mobileDrawerShippingVal" class="{{ $shipping == 0 ? 'text-success' : '' }}">
                                {{ $shipping == 0 ? 'FREE' : '₹' . $shipping }}
                            </strong>
                        </div>

                        @if(isset($couponDiscount) && $couponDiscount > 0)
                            <div class="cart-drawer-row text-success" id="mobileDrawerCouponDiscountRow">
                                <span><i class="bi bi-tag-fill"></i> Coupon Discount</span>
                                <strong id="mobileDrawerCouponDiscountVal">-₹{{ number_format($couponDiscount) }}</strong>
                            </div>
                        @endif

                        <div class="cart-drawer-divider"></div>

                        <div class="cart-drawer-row cart-drawer-total-row">
                            <div>
                                <span class="cart-drawer-total-label">Total Payable</span>
                                <small class="d-block text-muted" style="font-size: 11px;">(Inclusive of GST & all taxes)</small>
                            </div>
                            <span class="cart-drawer-total-val" id="mobileDrawerGrandTotalVal">₹{{ number_format(max(0, $total - $prepaidDiscount)) }}</span>
                        </div>

                        <div class="cart-drawer-savings" id="mobileDrawerSavingsPill">
                            <i class="bi bi-stars"></i> Total Savings: <b id="mobileDrawerTotalSavingsVal">₹{{ number_format($totalSavings) }}</b>
                        </div>
                    </div>
                </div>

                {{-- Sticky Header Bar (Total + Collapsible Toggle) --}}
                <div class="cart-mobile-bar-header" onclick="toggleCartMobileBill()">
                    <div class="cart-mobile-header-left">
                        <span class="cart-mobile-total-label">Total:</span>
                        <span class="cart-mobile-amount" id="mobileStickyCartTotal">₹{{ number_format(max(0, $total - $prepaidDiscount)) }}</span>
                        @if($totalSavings > 0)
                            <span class="badge-saving-pill" id="mobileStickyCartSavings">
                                <i class="bi bi-stars"></i> Save ₹{{ number_format($totalSavings) }}
                            </span>
                        @endif
                    </div>
                    <div class="cart-mobile-header-right">
                        <span class="cart-mobile-view-bill">
                            <span id="cartMobileToggleText">View Bill</span>
                            <i class="bi bi-chevron-up" id="cartMobileToggleChevron"></i>
                        </span>
                    </div>
                </div>

                {{-- Full-Width Checkout Button --}}
                <div class="cart-mobile-btn-row">
                    <button type="button" class="btn-cart-mobile-checkout-full" onclick="proceedToGlobalCheckout()">
                        <span>PROCEED TO CHECKOUT (<span id="mobileStickyCartCount">{{ $totalItemsCount }}</span>)</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    @else
        {{-- Empty Cart State --}}
        <div class="cart-empty-wrapper">
            <div class="cart-empty-circle">
                <i class="bi bi-bag-x"></i>
            </div>
            <h2 class="cart-empty-headline">YOUR CART IS EMPTY</h2>
            <p class="cart-empty-subline">Explore our latest luxury streetwear drops, discover trending pieces and add them to your cart.</p>
            <a href="{{ route('shop.index') }}" class="btn-start-shopping-cta">
                <span>EXPLORE ALL DROPS</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════
         NEW SECTION 1: RECENTLY VIEWED PRODUCTS SLIDER
         ═══════════════════════════════════════════════════════════ --}}
    @if(isset($recentlyViewedProducts) && $recentlyViewedProducts->count() > 0)
        <section class="collection-slider-section home-product-carousel cart-slider-section" aria-label="Recently Viewed Products">
            <div class="container-fluid position-relative px-0">
                <div class="collection-header text-center">
                    <h2>RECENTLY VIEWED</h2>
                    <p>Pick up right where you left off</p>
                </div>
                <div class="slider-container-collection position-relative">
                    <button type="button" class="collection-arrow collection-arrow-left" id="recentArrowLeft" aria-label="Previous">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button type="button" class="collection-arrow collection-arrow-right" id="recentArrowRight" aria-label="Next">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                    <div class="collection-slider-wrapper" id="recentSliderWrapper">
                        <div class="collection-track" id="recentSliderTrack">
                            @foreach($recentlyViewedProducts as $product)
                                @include('froentend.partials.product-card', [
                                    'product' => $product,
                                    'type' => 'collection',
                                ])
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ═══════════════════════════════════════════════════════════
         NEW SECTION 2: RELATED PRODUCTS / YOU MAY ALSO LIKE SLIDER
         ═══════════════════════════════════════════════════════════ --}}
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <section class="collection-slider-section home-product-carousel cart-slider-section" aria-label="You May Also Like">
            <div class="container-fluid position-relative px-0">
                <div class="collection-header text-center">
                    <h2>YOU MAY ALSO LIKE</h2>
                    <p>Curated luxury streetwear pieces handpicked for your style</p>
                </div>
                <div class="slider-container-collection position-relative">
                    <button type="button" class="collection-arrow collection-arrow-left" id="relatedArrowLeft" aria-label="Previous">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button type="button" class="collection-arrow collection-arrow-right" id="relatedArrowRight" aria-label="Next">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                    <div class="collection-slider-wrapper" id="relatedSliderWrapper">
                        <div class="collection-track" id="relatedSliderTrack">
                            @foreach($relatedProducts as $product)
                                @include('froentend.partials.product-card', [
                                    'product' => $product,
                                    'type' => 'collection',
                                ])
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="collection-footer text-center mt-3">
                    <a href="{{ route('shop.index') }}" class="section-btn" style="text-decoration:none;">
                        VIEW ALL DROPS <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </section>
    @endif

</div>

@push('scripts')
<script>
(function() {
    var csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '{{ csrf_token() }}';

    // Toggle In-page Order Summary Collapsible on Mobile
    window.toggleCartOrderSummary = function() {
        if (window.innerWidth > 768) return;
        var card = document.getElementById('cartSummaryCard');
        if (!card) return;
        card.classList.toggle('is-open');
    };

    // Quantity update handler
    function cartQtyValue(value) {
        return Math.max(1, Math.min(10, parseInt(value, 10) || 1));
    }

    window.updateCartItemQty = function(key, delta) {
        var qtyEl = document.getElementById('qty-val-' + key);
        if (!qtyEl) return;

        var currentQty = cartQtyValue(qtyEl.textContent);
        var nextQty = cartQtyValue(currentQty + delta);

        if (delta > 0 && currentQty >= 10) {
            if (typeof window.tttNotify === 'function') {
                window.tttNotify('Maximum 10 quantity allowed.', 'error');
            }
            qtyEl.textContent = 10;
            return;
        }

        if (delta < 0 && currentQty <= 1) {
            qtyEl.textContent = 1;
            return;
        }

        qtyEl.textContent = nextQty;

        fetch('/cart/update/' + encodeURIComponent(key), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-HTTP-Method-Override': 'PATCH'
            },
            body: JSON.stringify({ quantity: nextQty, key: key })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                updateCartBadgeCount(data.cart_count);
                syncCartSummaryData(data);
                var subEl = document.getElementById('subtotal-val-' + key);
                var subMobileEl = document.getElementById('subtotal-val-mobile-' + key);
                var card = document.getElementById('cart-item-' + key);
                if (card) {
                    var unitPriceEl = card.querySelector('.cart-price-current');
                    var unitPrice = unitPriceEl ? parseFloat(unitPriceEl.textContent.replace(/[^\d.]/g, '')) || 0 : 0;
                    var formattedPrice = '₹' + Math.round(unitPrice * nextQty).toLocaleString('en-IN');
                    if (subEl) subEl.textContent = formattedPrice;
                    if (subMobileEl) subMobileEl.textContent = formattedPrice;
                }
            }
        })
        .catch(function(err) {
            console.error('Cart update error:', err);
        });
    };

    // Remove item handler
    window.removeCartItem = function(key) {
        var itemCard = document.getElementById('cart-item-' + key);
        if (itemCard) {
            itemCard.style.opacity = '0.3';
            itemCard.style.pointerEvents = 'none';
        }

        fetch('/cart/remove/' + encodeURIComponent(key), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-HTTP-Method-Override': 'DELETE'
            },
            body: JSON.stringify({ key: key })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                if (typeof window.tttNotify === 'function') {
                    window.tttNotify(data.message || 'Item removed from cart successfully.', 'info');
                } else if (typeof toastr !== 'undefined') {
                    toastr.info(data.message || 'Item removed from cart successfully.');
                }
                if (itemCard) itemCard.remove();
                updateCartBadgeCount(data.cart_count);

                var remaining = document.querySelectorAll('.cart-item-row-card').length;
                if (remaining === 0) {
                    location.reload();
                } else {
                    syncCartSummaryData(data);
                }
            } else if (itemCard) {
                itemCard.style.opacity = '1';
                itemCard.style.pointerEvents = 'auto';
            }
        })
        .catch(function(err) {
            console.error('Remove item error:', err);
            if (itemCard) {
                itemCard.style.opacity = '1';
                itemCard.style.pointerEvents = 'auto';
            }
        });
    };

    // Synchronize UI summary values
    function syncCartSummaryData(data) {
        var subtotal = Number(data.subtotal || 0);
        var shipping = Number(data.shipping || 0);
        var discount = Number(data.discount || 0);
        var count = Number(data.cart_count || 0);

        // Recalculate MRP from live DOM items
        var totalMrp = 0;
        document.querySelectorAll('.cart-item-row-card').forEach(function(card) {
            var key = card.id.replace('cart-item-', '');
            var qtyEl = document.getElementById('qty-val-' + key);
            var qty = qtyEl ? parseInt(qtyEl.textContent) || 1 : 1;
            var mrpEl = card.querySelector('.cart-price-mrp');
            var unitPriceEl = card.querySelector('.cart-price-current');
            var mrp = mrpEl ? parseFloat(mrpEl.textContent.replace(/[^\d.]/g, '')) : (unitPriceEl ? parseFloat(unitPriceEl.textContent.replace(/[^\d.]/g, '')) : 0);
            totalMrp += (mrp * qty);
        });

        if (totalMrp < subtotal) totalMrp = subtotal;
        var discountOnMrp = Math.max(0, totalMrp - subtotal);
        var prepaidDiscount = Math.round(subtotal * 0.05 * 100) / 100;
        var grandTotal = Math.max(0, subtotal + shipping - discount - prepaidDiscount);
        var totalSavings = discountOnMrp + discount + prepaidDiscount;

        var mrpEl = document.getElementById('summaryMrpVal');
        if (mrpEl) mrpEl.textContent = '₹' + Math.round(totalMrp).toLocaleString('en-IN');

        var mrpDiscEl = document.getElementById('summaryMrpDiscountVal');
        if (mrpDiscEl) mrpDiscEl.textContent = '-₹' + Math.round(discountOnMrp).toLocaleString('en-IN');

        var subEl = document.getElementById('summarySubtotalVal');
        if (subEl) subEl.textContent = '₹' + Math.round(subtotal).toLocaleString('en-IN');

        var prepDiscEl = document.getElementById('summaryPrepaidDiscountVal');
        if (prepDiscEl) prepDiscEl.textContent = '-₹' + Math.round(prepaidDiscount).toLocaleString('en-IN');

        var shipEl = document.getElementById('summaryShippingVal');
        if (shipEl) {
            shipEl.textContent = shipping === 0 ? 'FREE' : '₹' + shipping;
            if (shipEl.parentElement) shipEl.parentElement.classList.toggle('green', shipping === 0);
        }

        var discRow = document.getElementById('couponDiscountRow');
        var discVal = document.getElementById('summaryCouponDiscountVal');
        if (discRow && discVal) {
            if (discount > 0) {
                discRow.style.display = 'flex';
                discVal.textContent = '-₹' + Math.round(discount).toLocaleString('en-IN');
            } else {
                discRow.style.display = 'none';
            }
        }

        var totalEl = document.getElementById('summaryGrandTotalVal');
        if (totalEl) totalEl.textContent = '₹' + Math.round(grandTotal).toLocaleString('en-IN');

        var savingsEl = document.getElementById('summaryTotalSavingsVal');
        if (savingsEl) savingsEl.textContent = '₹' + Math.round(totalSavings).toLocaleString('en-IN');

        var badgeEl = document.getElementById('cartPageBadge');
        if (badgeEl) badgeEl.textContent = count + ' ' + (count === 1 ? 'ITEM' : 'ITEMS');

        var itemsEl = document.getElementById('summaryTotalItems');
        if (itemsEl) itemsEl.textContent = count;

        var headTotEl = document.getElementById('summaryHeadTotalVal');
        if (headTotEl) headTotEl.textContent = '₹' + Math.round(grandTotal).toLocaleString('en-IN');

        var headCountBadge = document.getElementById('summaryHeadCountBadge');
        if (headCountBadge) headCountBadge.textContent = '(' + count + ')';

        // Free shipping bar calculation
        var threshold = 999;
        var pct = Math.min(100, Math.round((subtotal / threshold) * 100));
        var needed = Math.max(0, threshold - subtotal);
        var barEl = document.getElementById('freeShipBar');
        if (barEl) barEl.style.width = pct + '%';

        var textEl = document.getElementById('freeShipText');
        if (textEl) {
            if (needed === 0) {
                textEl.className = 'free-ship-header unlocked';
                textEl.innerHTML = '<span><i class="bi bi-patch-check-fill text-success"></i> <strong>Congratulations!</strong> You unlocked FREE Shipping!</span> <span class="badge bg-success-subtle text-success border px-2 py-1">FREE DELIVERY</span>';
            } else {
                textEl.className = 'free-ship-header';
                textEl.innerHTML = '<span><i class="bi bi-truck text-primary"></i> Add <strong>₹' + Math.round(needed).toLocaleString('en-IN') + '</strong> more to unlock <strong>FREE Shipping</strong></span> <span class="text-muted font-monospace">₹' + Math.round(subtotal).toLocaleString('en-IN') + '/₹999</span>';
            }
        }

        // ── Mobile Sticky Bar & Drawer Updates ──
        var mobileTotalEl = document.getElementById('mobileStickyCartTotal');
        if (mobileTotalEl) mobileTotalEl.textContent = '₹' + Math.round(grandTotal).toLocaleString('en-IN');

        var mobileCountEl = document.getElementById('mobileStickyCartCount');
        if (mobileCountEl) mobileCountEl.textContent = count;

        var mobileSavingsEl = document.getElementById('mobileStickyCartSavings');
        if (mobileSavingsEl) {
            mobileSavingsEl.innerHTML = '<i class="bi bi-stars"></i> Save ₹' + Math.round(totalSavings).toLocaleString('en-IN');
            mobileSavingsEl.style.display = totalSavings > 0 ? 'inline-flex' : 'none';
        }

        var drawerItems = document.getElementById('mobileDrawerTotalItems');
        if (drawerItems) drawerItems.textContent = count;

        var drawerMrp = document.getElementById('mobileDrawerMrpVal');
        if (drawerMrp) drawerMrp.textContent = '₹' + Math.round(totalMrp).toLocaleString('en-IN');

        var drawerMrpDisc = document.getElementById('mobileDrawerMrpDiscountVal');
        if (drawerMrpDisc) drawerMrpDisc.textContent = '-₹' + Math.round(discountOnMrp).toLocaleString('en-IN');

        var drawerSub = document.getElementById('mobileDrawerSubtotalVal');
        if (drawerSub) drawerSub.textContent = '₹' + Math.round(subtotal).toLocaleString('en-IN');

        var drawerPrep = document.getElementById('mobileDrawerPrepaidDiscountVal');
        if (drawerPrep) drawerPrep.textContent = '-₹' + Math.round(prepaidDiscount).toLocaleString('en-IN');

        var drawerShip = document.getElementById('mobileDrawerShippingVal');
        if (drawerShip) {
            drawerShip.textContent = shipping === 0 ? 'FREE' : '₹' + shipping;
            drawerShip.className = shipping === 0 ? 'text-success' : '';
        }

        var drawerCouponRow = document.getElementById('mobileDrawerCouponDiscountRow');
        var drawerCouponVal = document.getElementById('mobileDrawerCouponDiscountVal');
        if (drawerCouponRow && drawerCouponVal) {
            if (discount > 0) {
                drawerCouponRow.style.display = 'flex';
                drawerCouponVal.textContent = '-₹' + Math.round(discount).toLocaleString('en-IN');
            } else {
                drawerCouponRow.style.display = 'none';
            }
        }

        var drawerGrand = document.getElementById('mobileDrawerGrandTotalVal');
        if (drawerGrand) drawerGrand.textContent = '₹' + Math.round(grandTotal).toLocaleString('en-IN');

        var drawerSav = document.getElementById('mobileDrawerTotalSavingsVal');
        if (drawerSav) drawerSav.textContent = '₹' + Math.round(totalSavings).toLocaleString('en-IN');
    }

    // Toggle Mobile Bill Details Drawer
    window.toggleCartMobileBill = function(forceState) {
        var drawer = document.getElementById('cartMobileDrawer');
        var backdrop = document.getElementById('cartMobileBackdrop');
        var chevron = document.getElementById('cartMobileToggleChevron');
        var toggleText = document.getElementById('cartMobileToggleText');
        if (!drawer) return;

        var isOpen = drawer.classList.contains('is-open');
        var shouldOpen = typeof forceState === 'boolean' ? forceState : !isOpen;

        if (shouldOpen) {
            drawer.classList.add('is-open');
            if (backdrop) backdrop.classList.add('is-open');
            if (chevron) chevron.classList.add('rotated');
            if (toggleText) toggleText.textContent = 'Hide Bill';
        } else {
            drawer.classList.remove('is-open');
            if (backdrop) backdrop.classList.remove('is-open');
            if (chevron) chevron.classList.remove('rotated');
            if (toggleText) toggleText.textContent = 'View Bill';
        }
    };

    // Coupon selection chip helper
    window.selectCartCoupon = function(code) {
        var input = document.getElementById('cartCouponInput');
        if (input) {
            input.value = code;
            window.applyCartCoupon();
        }
    };

    // Apply Coupon
    window.applyCartCoupon = function() {
        var input = document.getElementById('cartCouponInput');
        var code = input ? input.value.trim() : '';
        if (!code) {
            showCartCouponMsg('Please enter a coupon code.', 'error');
            return;
        }

        showCartCouponMsg('Applying coupon...', 'success');

        fetch('/coupon/apply', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ coupon_code: code })
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.success) {
                if (typeof window.showCouponPartyPopup === 'function') {
                    window.showCouponPartyPopup({
                        code: code,
                        discount: Number(res.discount || 0),
                        message: res.message || ('Coupon ' + code + ' applied successfully!')
                    });
                } else {
                    showCartCouponMsg(res.message || 'Coupon applied successfully!', 'success');
                }
                var codeBadge = document.getElementById('couponCodeBadge');
                if (codeBadge) codeBadge.textContent = code;
                setTimeout(function() {
                    location.reload();
                }, 1600);
            } else {
                showCartCouponMsg(res.message || 'Invalid or expired coupon code.', 'error');
            }
        })
        .catch(function() {
            showCartCouponMsg('Failed to apply coupon. Please try again.', 'error');
        });
    };

    function showCartCouponMsg(text, type) {
        var msgEl = document.getElementById('cartCouponMsg');
        if (!msgEl) return;
        msgEl.textContent = text;
        msgEl.className = 'promo-response-msg ' + type;
        setTimeout(function() {
            if (msgEl) msgEl.style.display = 'none';
        }, 4000);
    }

    function updateCartBadgeCount(count) {
        var badges = document.querySelectorAll('#cart-count, .cart-count-badge');
        badges.forEach(function(b) {
            b.textContent = count;
            b.style.display = count > 0 ? 'inline-flex' : 'none';
        });
    }

    // Open Unified Luxury Checkout Modal
    window.proceedToGlobalCheckout = function() {
        if (typeof window.openGlobalCheckoutModal !== 'function') {
            window.location.href = '{{ route('checkout.index') }}';
            return;
        }

        var cartItems = [];
        document.querySelectorAll('.cart-item-row-card').forEach(function(card) {
            var key = card.id.replace('cart-item-', '');
            var nameEl = card.querySelector('.cart-item-name-link');
            var imgEl = card.querySelector('.cart-item-media img');
            var priceEl = card.querySelector('.cart-price-current');
            var qtyEl = document.getElementById('qty-val-' + key);
            var sizeEl = card.querySelector('.cart-tag-pill');

            var price = priceEl ? parseFloat(priceEl.textContent.replace(/[^\d.]/g, '')) || 0 : 0;
            var qty = qtyEl ? parseInt(qtyEl.textContent) || 1 : 1;

            cartItems.push({
                key: key,
                name: nameEl ? nameEl.textContent.trim() : 'Product',
                image: imgEl ? imgEl.src : '',
                price: price,
                original_price: price,
                quantity: qty,
                size: sizeEl ? sizeEl.textContent.replace(/Size:/i, '').trim() : 'Free Size'
            });
        });

        window.openGlobalCheckoutModal({
            items: cartItems,
            count: cartItems.reduce(function(acc, i) { return acc + i.quantity; }, 0)
        });
    };

    // Initialize Cart Carousels (Recently Viewed & You May Also Like)
    function initCartSliders() {
        if (typeof window.makeSlider === 'function') {
            window.makeSlider('recentSliderTrack', 'recentSliderWrapper', 'recentArrowLeft', 'recentArrowRight', 300);
            window.makeSlider('relatedSliderTrack', 'relatedSliderWrapper', 'relatedArrowLeft', 'relatedArrowRight', 300);
        }
        if (typeof window.initCardHoverGalleries === 'function') {
            window.initCardHoverGalleries();
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCartSliders);
    } else {
        initCartSliders();
    }
})();
</script>
@endpush
@endsection
