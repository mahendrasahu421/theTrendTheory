{{-- resources/views/froentend/product/show.blade.php --}}
@extends('froentend.layouts.app')
@section('custom_seo')
    @php
        $ogImg = !empty($product->og_image) ? $product->og_image : ($product->all_images_list[0] ?? $product->main_image);
        if (!filter_var($ogImg, FILTER_VALIDATE_URL)) {
            $ogImg = url($ogImg);
        }
        $pageTitle = ($product->meta_title ?: $product->name) . (isset($selectedColor) && $selectedColor ? ' (' . $selectedColor . ')' : '') . ' | VAYU';
        $pageDesc = $product->meta_description ?: ($product->short_description ?: 'Buy ' . $product->name . ' at ₹' . number_format($product->price) . '. 100% Cotton, Drop Shoulder Oversized Fit from VAYU.');
    @endphp
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDesc }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph (WhatsApp, Facebook, Telegram, iMessage) -->
    <meta property="og:site_name" content="VAYU">
    <meta property="og:type" content="product">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDesc }}">
    <meta property="og:image" content="{{ $ogImg }}">
    <meta property="og:image:secure_url" content="{{ $ogImg }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="1200">
    <meta property="og:image:alt" content="{{ $product->name }}">

    <!-- Product Specific Tags -->
    <meta property="product:price:amount" content="{{ $product->price }}">
    <meta property="product:price:currency" content="INR">
    <meta property="product:availability" content="{{ $product->stock > 0 ? 'in stock' : 'out of stock' }}">
    <meta property="product:brand" content="VAYU">
    <meta property="product:category" content="{{ $product->category?->name ?? 'Streetwear' }}">

    <!-- Twitter / X Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@thetrendtheory">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDesc }}">
    <meta name="twitter:image" content="{{ $ogImg }}">
    <meta name="twitter:image:alt" content="{{ $product->name }}">
@endsection

@push('styles')
<link href="{{ asset('frontend/product-show.min.css') }}?v={{ filemtime(public_path('frontend/product-show.min.css')) }}" rel="stylesheet">
<style>
/* ═══════════════════════════════════════════════════════════════════
   Vayu - LUXURY PRODUCT DETAILS STYLES
   ═══════════════════════════════════════════════════════════════════ */
:root {
    --ttt-navy: #00285a;
    --ttt-navy-dark: #001e44;
    --ttt-accent: #ff3f6c;
    --ttt-accent-hover: #e0325d;
    --ttt-border: #e2e8f0;
    --ttt-text: #0f172a;
    --ttt-muted: #64748b;
    --ttt-font-heading: 'Cinzel', serif;
    --ttt-font-body: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

.pd-wrap {
    max-width: 1440px;
    margin: 0 auto;
    padding: 16px 24px 60px;
    font-family: var(--ttt-font-body) !important;
}

.pd-breadcrumb {
    font-size: 12px;
    color: var(--ttt-muted);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}
.pd-breadcrumb a {
    color: var(--ttt-muted);
    text-decoration: none;
    transition: color 0.15s ease;
}
.pd-breadcrumb .sep {
    color: #cbd5e1;
}
.pd-breadcrumb .current {
    color: var(--ttt-navy);
    font-weight: 700;
}

.pd-grid {
    display: grid;
    grid-template-columns: 1.15fr 0.85fr;
    gap: 44px;
    align-items: start;
}

/* ── LEFT: GALLERY STREAM ── */
.pd-gallery-stream {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.pd-hero-img-wrap {
    position: relative;
    width: 100%;
    aspect-ratio: 3/4;
    background: #f8fafc;
    border-radius: 10px;
    border: 1px solid var(--ttt-border);
    overflow: hidden;
    cursor: pointer;
}

.pd-hero-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.4s ease;
}

.pd-share-btn {
    position: absolute;
    top: 14px;
    right: 14px;
    width: 36px;
    height: 36px;
    background: #ffffff;
    border-radius: 50%;
    border: 1px solid var(--ttt-border);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    color: var(--ttt-navy);
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 40, 90, 0.08);
    transition: all 0.2s ease;
    z-index: 5;
}

.pd-gallery-2col-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}

.pd-gallery-sub-card {
    position: relative;
    aspect-ratio: 3/4;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid var(--ttt-border);
    overflow: hidden;
    cursor: pointer;
}

.pd-gallery-sub-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
}

.gallery-side-badge {
    position: absolute;
    bottom: 8px;
    left: 8px;
    background: rgba(0, 40, 90, 0.85);
    color: #ffffff;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 3px 8px;
    border-radius: 4px;
    backdrop-filter: blur(4px);
    pointer-events: none;
    z-index: 2;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

/* ── RIGHT: STICKY INFO PANEL ── */
.pd-info {
    position: sticky;
    top: 90px;
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.pd-title-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
}

.pd-main-title {
    font-family: var(--ttt-font-heading) !important;
    font-size: 23px;
    font-weight: 800;
    letter-spacing: 0.6px;
    color: var(--ttt-navy);
    margin: 0;
    line-height: 1.3;
    text-transform: uppercase;
}

.pd-wish-btn-top {
    background: none;
    border: none;
    font-size: 20px;
    color: var(--ttt-muted);
    cursor: pointer;
    padding: 2px;
    transition: transform 0.2s ease, color 0.2s ease;
}.pd-wish-btn-top.wished {
    color: var(--ttt-accent);
    transform: scale(1.15);
}

/* Pricing */
.pd-pricing-box {
    margin-top: -6px;
}

.pd-price-main {
    font-size: 22px;
    font-weight: 800;
    color: var(--ttt-navy);
}

.pd-price-mrp {
    font-size: 15px;
    color: #94a3b8;
    text-decoration: line-through;
    margin-left: 8px;
    font-weight: 500;
}

.pd-disc-pill {
    display: inline-block;
    background: var(--ttt-accent);
    color: #ffffff;
    font-size: 11px;
    font-weight: 800;
    padding: 2px 7px;
    border-radius: 4px;
    margin-left: 8px;
    letter-spacing: 0.5px;
    vertical-align: middle;
}

.pd-tax-sub {
    font-size: 11.5px;
    color: var(--ttt-muted);
    margin-top: 4px;
    font-weight: 500;
}

/* Sizes */
.pd-size-header-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
    font-size: 13px;
    font-weight: 700;
    color: var(--ttt-text);
}

.pd-size-guide-link {
    font-size: 12px;
    color: var(--ttt-navy);
    cursor: pointer;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 4px;
    text-decoration: underline;
}

.pd-size-grid-boxes {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.pd-size-box-btn {
    min-width: 48px;
    height: 42px;
    padding: 0 14px;
    background: #ffffff;
    border: 1.5px solid var(--ttt-navy);
    border-radius: 6px;
    font-size: 13px;
    font-weight: 800;
    color: var(--ttt-navy);
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s ease;
}

.pd-size-box-btn.active {
    background: var(--ttt-navy);
    color: #ffffff;
    border-color: var(--ttt-navy);
}

.pd-size-box-btn.oos {
    border-color: var(--ttt-border);
    color: #cbd5e1;
    background: #f8fafc;
    position: relative;
    cursor: not-allowed;
    text-decoration: line-through;
}

/* Design / Print Side Placement Selector */
.pd-design-side-box {
    margin-top: 14px;
    margin-bottom: 6px;
}
.pd-side-header-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
    font-size: 12px;
    font-weight: 700;
    color: var(--ttt-navy);
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
.pd-side-header-row .current-side-tag {
    font-weight: 800;
    color: var(--ttt-navy);
}
.pd-side-instruction-hint {
    font-size: 11.5px;
    color: #334155;
    background: #f1f5f9;
    border-left: 3.5px solid #00285a;
    padding: 7px 10px;
    border-radius: 5px;
    margin-bottom: 10px;
    line-height: 1.45;
    font-weight: 500;
}
.pd-side-instruction-hint strong {
    color: #00285a;
}
.pd-side-confirm-badge {
    margin-top: 8px;
    font-size: 12px;
    color: #065f46;
    background: #ecfdf5;
    border: 1px solid #a7f3d0;
    border-radius: 6px;
    padding: 7px 11px;
    display: flex;
    align-items: center;
    gap: 7px;
    font-weight: 600;
    line-height: 1.35;
    transition: all 0.2s ease;
}
.pd-side-confirm-badge strong {
    color: #047857;
    text-decoration: underline;
}
.pd-side-grid-boxes {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
    gap: 10px;
}
@media (max-width: 480px) {
    .pd-side-grid-boxes {
        gap: 8px;
    }
}
.pd-side-btn {
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    padding: 10px 8px;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 3px;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    user-select: none;
}
.pd-side-btn.active {
    background: #00285a;
    border-color: #00285a;
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(0, 40, 90, 0.22);
}
.pd-side-btn .pd-side-icon {
    font-size: 17px;
    line-height: 1;
    transition: transform 0.2s ease;
}
.pd-side-btn .pd-side-name {
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.3px;
}
.pd-side-btn .pd-side-hint {
    font-size: 9px;
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.pd-side-btn.active .pd-side-name,
.pd-side-btn.active .pd-side-icon {
    color: #ffffff;
}
.pd-side-btn.active .pd-side-hint {
    color: #93c5fd;
}
.pd-side-required-msg {
    display: none;
    margin-top: 7px;
    font-size: 12px;
    font-weight: 700;
    color: #dc2626;
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 6px;
    padding: 6px 10px;
}
.pd-design-side-box.needs-choice {
    animation: sideShake 0.4s ease-in-out;
}
.pd-design-side-box.needs-choice .pd-side-grid-boxes {
    border-radius: 10px;
    outline: 2.5px solid #ef4444;
    outline-offset: 3px;
    background: #fff5f5;
}
@keyframes sideShake {
    0%, 100% { transform: translateX(0); }
    20%, 60% { transform: translateX(-6px); }
    40%, 80% { transform: translateX(6px); }
}

/* Action Buttons */
.pd-btn-row-stack {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 4px;
}

.pd-btn-split-row {
    display: grid;
    grid-template-columns: 110px 1fr;
    gap: 10px;
}

.pd-stepper-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: 1.5px solid var(--ttt-navy);
    border-radius: 6px;
    height: 48px;
    padding: 0 6px;
    background: #ffffff;
}

.pd-stepper-box button {
    background: none;
    border: none;
    font-size: 16px;
    font-weight: 800;
    color: var(--ttt-navy);
    cursor: pointer;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.pd-stepper-box span {
    font-size: 14px;
    font-weight: 800;
    color: var(--ttt-navy);
}

.pd-btn-outline-atc {
    height: 48px;
    background: #ffffff;
    border: 2px solid var(--ttt-navy);
    border-radius: 6px;
    font-size: 13.5px;
    font-weight: 800;
    letter-spacing: 0.6px;
    color: var(--ttt-navy);
    cursor: pointer;
    transition: all 0.2s ease;
    text-transform: uppercase;
}

.pd-btn-solid-buy {
    width: 100%;
    height: 48px;
    background: var(--ttt-navy);
    border: 2px solid var(--ttt-navy);
    border-radius: 6px;
    font-size: 13.5px;
    font-weight: 800;
    letter-spacing: 0.6px;
    color: #ffffff;
    cursor: pointer;
    transition: all 0.2s ease;
    text-transform: uppercase;
}

/* Prepaid Offer Pill */
.pd-offer-pill-card {
    background: var(--ttt-navy);
    color: #ffffff;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    letter-spacing: 0.3px;
}

/* Pincode Box */
.pd-pincode-card {
    border: 1px solid var(--ttt-border);
    border-radius: 8px;
    padding: 16px;
    background: #ffffff;
}

.pd-pincode-head {
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    color: var(--ttt-navy);
    margin-bottom: 10px;
    letter-spacing: 0.5px;
}

.pd-pincode-input-row {
    display: flex;
    gap: 8px;
}

.pd-pincode-field {
    flex: 1;
    height: 40px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    padding: 0 12px;
    font-size: 13px;
    outline: none;
}

.pd-pincode-field:focus {
    border-color: var(--ttt-navy);
}

.pd-pincode-btn {
    height: 40px;
    padding: 0 18px;
    background: var(--ttt-navy);
    color: #ffffff;
    border: none;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: background 0.15s ease;
}

.pd-pincode-subtext {
    font-size: 11px;
    color: var(--ttt-muted);
    margin-top: 8px;
    line-height: 1.4;
}

.pd-pincode-result {
    margin-top: 8px;
    font-size: 12px;
    font-weight: 700;
    color: #16a34a;
    display: none;
}

/* What You Get For Rs. X */
.pd-what-you-get-box {
    border: 1px solid var(--ttt-border);
    border-radius: 8px;
    padding: 18px;
    background: #f8fafc;
}

.pd-wyg-title {
    font-size: 13.5px;
    font-weight: 800;
    color: var(--ttt-navy);
    margin-bottom: 14px;
    letter-spacing: 0.3px;
}

.pd-wyg-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.pd-wyg-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.pd-wyg-icon {
    font-size: 15px;
    color: var(--ttt-navy);
    line-height: 1.2;
    flex-shrink: 0;
}

.pd-wyg-text {
    font-size: 12px;
    line-height: 1.45;
    color: #334155;
}

.pd-wyg-text strong {
    color: var(--ttt-navy);
    display: block;
    font-weight: 700;
}

/* Trend Tag Pills */
.pd-trend-tags-row {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.pd-trend-tag {
    font-size: 11px;
    font-weight: 700;
    color: var(--ttt-navy);
    background: #f1f5f9;
    padding: 4px 10px;
    border-radius: 999px;
    text-decoration: none;
}

/* Accordion sections */
.pd-accordion-item {
    border-top: 1px solid var(--ttt-border);
}

.pd-accordion-item:last-child {
    border-bottom: 1px solid var(--ttt-border);
}

.pd-accordion-btn {
    width: 100%;
    padding: 16px 0;
    background: none;
    border: none;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 13px;
    font-weight: 800;
    text-transform: uppercase;
    color: var(--ttt-navy);
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: none !important;
}

.pd-accordion-content {
    display: none;
    padding-bottom: 16px;
    font-size: 12.5px;
    color: #475569;
    line-height: 1.65;
}

.pd-accordion-item.open .pd-accordion-content {
    display: block;
}

.pd-accordion-arrow {
    font-size: 12px;
    color: var(--ttt-navy);
    transition: transform 0.2s ease;
}

.pd-accordion-item.open .pd-accordion-arrow {
    transform: rotate(180deg);
}

/* ═══════════════════════════════════════════════════════════════════
   SLIDE-OVER SHOPPING BAG DRAWER (EXACT MATCH media_1788344894799.png)
   ═══════════════════════════════════════════════════════════════════ */
#checkoutPop.checkout-pop {
    position: fixed;
    inset: 0;
    z-index: 100000;
    display: flex;
    justify-content: flex-end;
    visibility: hidden;
    opacity: 0;
    transition: visibility 0.3s ease, opacity 0.3s ease;
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
}

#checkoutPop.checkout-pop.is-open {
    visibility: visible;
    opacity: 1;
}

#checkoutPop .checkout-pop__shade {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
}

#checkoutPop .cart-drawer-panel {
    position: relative;
    width: 100%;
    max-width: 440px;
    height: 100%;
    background: #f8fafc;
    box-shadow: -10px 0 40px rgba(0, 0, 0, 0.2);
    display: flex;
    flex-direction: column;
    transform: translateX(100%);
    transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    z-index: 1;
}

#checkoutPop.checkout-pop.is-open .cart-drawer-panel {
    transform: translateX(0);
}

/* 1. Header */
.cart-slide-header {
    padding: 16px 18px;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
}

.cart-slide-title {
    font-size: 15px;
    font-weight: 700;
    color: #334155;
    margin: 0;
}

.cart-slide-close-btn {
    background: none;
    border: none;
    font-size: 19px;
    color: #0f172a;
    cursor: pointer;
    padding: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.15s ease;
}

/* Scrollable Body */
.cart-slide-scrollable-body {
    flex: 1;
    overflow-y: auto;
    padding: 0 14px 14px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

/* 2. Promo Black Strip */
.cart-slide-promo-black {
    background: #000000;
    color: #ffffff;
    border-radius: 8px;
    padding: 10px 14px;
    text-align: center;
}

.promo-black-title {
    font-size: 13.5px;
    font-weight: 800;
    letter-spacing: 0.2px;
    margin-bottom: 2px;
}

.promo-black-sub {
    font-size: 11px;
    color: #e2e8f0;
    font-weight: 500;
}

/* 3. Milestone Progress Bar */
.cart-slide-milestones-card {
    background: #f8fafc;
    padding: 8px 6px 12px;
}

.milestones-subtitle {
    font-size: 11.5px;
    color: #334155;
    font-weight: 600;
    text-align: center;
    margin-bottom: 16px;
    line-height: 1.45;
}

.milestones-track-wrap {
    position: relative;
    padding: 0 12px;
}

.milestones-line-bg {
    position: absolute;
    top: 24px;
    left: 28px;
    right: 28px;
    height: 3px;
    background: #e2e8f0;
    z-index: 1;
}

.milestones-line-fill {
    height: 100%;
    background: #0f172a;
    transition: width 0.3s ease;
}

.milestones-nodes {
    position: relative;
    display: flex;
    justify-content: space-between;
    z-index: 2;
}

.milestone-node {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    font-size: 10px;
    color: #64748b;
    font-weight: 600;
}

.milestone-val {
    font-size: 10.5px;
    color: #334155;
    font-weight: 700;
}

.milestone-icon {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
}

.milestone-node.active .milestone-icon {
    border-color: #0f172a;
    color: #0f172a;
    background: #ffffff;
}

.milestone-label {
    font-size: 10.5px;
    color: #475569;
    font-weight: 700;
}

/* 4. Cart Items */
.cart-slide-items-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.cart-slide-item {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px;
    display: flex;
    gap: 12px;
    align-items: flex-start;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
}

.cart-item-thumb-box {
    width: 68px;
    height: 86px;
    border-radius: 8px;
    overflow: hidden;
    background: #f8fafc;
    flex-shrink: 0;
}

.cart-item-thumb-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.cart-item-details {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 6px;
    min-width: 0;
}

.cart-item-row-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 8px;
}

.cart-item-name {
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    flex: 1;
}

.cart-item-mrp-price {
    font-size: 11.5px;
    color: #94a3b8;
    text-decoration: line-through;
}

.cart-item-row-mid {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
}

.cart-size-pill-select {
    border: 1px solid #d1d5db;
    border-radius: 6px;
    padding: 2px 6px;
    font-size: 11px;
    font-weight: 700;
    background: #ffffff;
    color: #0f172a;
    cursor: pointer;
    outline: none;
}

.cart-item-selling-side {
    display: flex;
    align-items: baseline;
    gap: 4px;
}

.cart-item-cur-price {
    font-size: 13.5px;
    font-weight: 800;
    color: #0f172a;
}

.cart-item-disc-green {
    font-size: 11px;
    font-weight: 700;
    color: #059669;
}

.cart-item-row-bottom {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 12px;
    margin-top: 2px;
}

.cart-qty-stepper-pill {
    display: inline-flex;
    align-items: center;
    background: #f1f5f9;
    border-radius: 6px;
    height: 26px;
}

.cart-qty-stepper-pill button {
    width: 24px;
    height: 100%;
    border: none;
    background: transparent;
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
    cursor: pointer;
}

.cart-qty-stepper-pill span {
    min-width: 20px;
    text-align: center;
    font-size: 12px;
    font-weight: 700;
    color: #0f172a;
}

.btn-cart-item-trash {
    background: none;
    border: none;
    color: #0f172a;
    font-size: 14px;
    cursor: pointer;
    padding: 0 4px;
    transition: color 0.15s ease;
}

/* 5. Coupon Card */
.cart-slide-coupon-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 14px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
}

.coupon-field-wrap {
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 0 10px;
    display: flex;
    align-items: center;
    gap: 8px;
    height: 40px;
    background: #ffffff;
}

.coupon-code-input {
    border: none;
    outline: none;
    font-size: 13px;
    font-weight: 600;
    color: #0f172a;
    flex: 1;
    background: transparent;
}

.coupon-code-input::placeholder {
    color: #94a3b8;
    font-weight: 400;
}

.btn-coupon-inline-apply {
    border: none;
    background: none;
    font-size: 11.5px;
    font-weight: 800;
    color: #00285a;
    cursor: pointer;
}

.coupon-view-offers-row {
    text-align: center;
    margin-top: 10px;
}

.btn-view-all-offers-link {
    background: none;
    border: none;
    font-size: 12.5px;
    font-weight: 700;
    color: #00285a;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

/* 6. Cross-sell Section */
.cart-slide-crosssell-section {
    margin-top: 4px;
}

.crosssell-heading {
    font-size: 13.5px;
    font-weight: 800;
    color: #0f172a;
    margin: 4px 0 8px;
}

.crosssell-items-scroll {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding-bottom: 6px;
}

.crosssell-item-card {
    flex: 0 0 130px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 8px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.crosssell-item-card img {
    width: 100%;
    height: 90px;
    object-fit: cover;
    border-radius: 4px;
}

.crosssell-title {
    font-size: 11px;
    font-weight: 600;
    color: #0f172a;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.crosssell-price {
    font-size: 11.5px;
    font-weight: 800;
    color: #0f172a;
}

.btn-crosssell-add {
    background: #0f172a;
    color: #ffffff;
    border: none;
    border-radius: 4px;
    font-size: 10px;
    font-weight: 700;
    padding: 4px 0;
    cursor: pointer;
    width: 100%;
    transition: background 0.15s ease;
}

/* 7. Sticky Bottom Checkout Card */
.cart-slide-footer-wrap {
    flex-shrink: 0;
    background: transparent;
    position: relative;
}

.cart-slide-saved-ribbon {
    display: flex;
    justify-content: center;
    margin-bottom: -4px;
    position: relative;
    z-index: 2;
}

.cart-slide-saved-ribbon span {
    background: #000000;
    color: #ffffff;
    font-size: 11px;
    font-weight: 800;
    padding: 3px 16px;
    border-radius: 4px 4px 0 0;
    letter-spacing: 0.3px;
}

.cart-slide-sticky-card {
    background: #ffffff;
    border-top: 1px solid #e2e8f0;
    padding: 14px 16px 16px;
    box-shadow: 0 -6px 20px rgba(0, 0, 0, 0.06);
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.sticky-total-line {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.sticky-total-left {
    display: flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
}

.sticky-total-label {
    font-size: 13.5px;
    font-weight: 800;
    color: #0f172a;
}

.sticky-total-right {
    display: flex;
    align-items: baseline;
    gap: 6px;
}

.sticky-mrp-price {
    font-size: 12px;
    color: #94a3b8;
    text-decoration: line-through;
}

.sticky-final-price {
    font-size: 16px;
    font-weight: 900;
    color: #0f172a;
}

.sticky-disc-tag {
    font-size: 11.5px;
    font-weight: 700;
    color: #059669;
}

.sticky-breakdown-details {
    background: #f8fafc;
    border-radius: 6px;
    padding: 8px 10px;
    display: flex;
    flex-direction: column;
    gap: 4px;
    font-size: 12px;
    color: #64748b;
}

.sticky-breakdown-details .break-line {
    display: flex;
    justify-content: space-between;
}

.sticky-badges-line {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.badge-shipping-est {
    background: #f1f5f9;
    color: #475569;
    font-size: 10.5px;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.badge-prepaid-save {
    background: #ecfdf5;
    color: #059669;
    font-size: 10.5px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.btn-sticky-black-checkout {
    background: #000000;
    color: #ffffff;
    border-radius: 10px;
    height: 50px;
    padding: 0 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    text-decoration: none;
    transition: transform 0.15s ease, background 0.15s ease;
}

.checkout-btn-text-side {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.checkout-btn-main-title {
    font-size: 14px;
    font-weight: 900;
    letter-spacing: 0.5px;
    line-height: 1.2;
}

.checkout-btn-sub-note {
    font-size: 9.5px;
    font-weight: 700;
    color: #ffffff;
    opacity: 0.9;
}

.checkout-btn-logos-side {
    display: flex;
    align-items: center;
    gap: 4px;
    background: #ffffff;
    border-radius: 999px;
    padding: 3px 8px;
}

.pay-logo-pill {
    font-size: 10px;
    font-weight: 900;
    line-height: 1;
}

.pay-logo-pill.paytm {
    color: #002970;
}

.pay-logo-pill.pe {
    color: #5f259f;
}

.pay-logo-pill.gpay {
    color: #4285f4;
}

/* Empty State */
.cart-slide-empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 60px 20px;
    margin: auto 0;
}

.empty-icon-circle {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: #f1f5f9;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    margin-bottom: 16px;
}

.empty-title {
    font-size: 17px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 6px;
}

.empty-sub {
    font-size: 13px;
    color: #64748b;
    max-width: 260px;
    line-height: 1.45;
    margin: 0 0 20px;
}

.btn-empty-shop {
    width: auto !important;
    min-width: 180px;
    height: 42px !important;
    padding: 0 24px !important;
    font-size: 12.5px !important;
    background: #000000 !important;
    color: #ffffff !important;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    text-decoration: none;
    font-weight: 800;
}

/* ═══════════════════════════════════════════════════════════════════
   OFFERS VIEW STYLES (EXACT MATCH media_1788345176092.png)
   ═══════════════════════════════════════════════════════════════════ */
.cart-slide-view-pane {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 100%;
}

.slide-offers-tabs-row {
    display: flex;
    align-items: center;
    gap: 10px;
}

.slide-offer-tab {
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #334155;
    font-size: 12.5px;
    font-weight: 600;
    padding: 6px 18px;
    border-radius: 999px;
    cursor: pointer;
    transition: all 0.15s ease;
}

.slide-offer-tab.active {
    border: 1.5px solid #059669;
    color: #0f172a;
    font-weight: 800;
    background: #ffffff;
}

.offers-section-title {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 12px;
}

.offers-list-group {
    display: flex;
    flex-direction: column;
}

.slide-offer-item-row {
    padding-bottom: 14px;
    border-bottom: 1px dashed #cbd5e1;
    margin-bottom: 14px;
}

.slide-offer-item-row:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.offer-row-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 5px;
}

.offer-code-badge-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
}

.offer-flower-icon {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #ecfdf5;
    color: #10b981;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.offer-dashed-pill {
    border: 1.5px dashed #94a3b8;
    border-radius: 6px;
    padding: 2px 8px;
    font-size: 12px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: 0.5px;
    background: #ffffff;
}

.offer-apply-btn {
    background: none;
    border: none;
    font-size: 13.5px;
    font-weight: 800;
    color: #0f172a;
    cursor: pointer;
    padding: 0;
    transition: color 0.15s ease;
}

.offer-add-items-link {
    font-size: 13px;
    font-weight: 800;
    color: #0284c7;
    text-decoration: none;
    cursor: pointer;
    background: none;
    border: none;
    padding: 0;
    transition: color 0.15s ease;
}

.offer-saving-line {
    font-size: 12px;
    font-weight: 700;
    color: #059669;
    margin-bottom: 2px;
}

.offer-warning-line {
    font-size: 11.5px;
    font-weight: 600;
    color: #dc2626;
    margin-bottom: 2px;
}

.offer-terms-line {
    font-size: 11.5px;
    color: #64748b;
    font-weight: 500;
}

@media (max-width: 991px) {
    .pd-grid {
        grid-template-columns: 1fr;
        gap: 28px;
    }
    .pd-info {
        position: static;
    }
    #checkoutPop .cart-drawer-panel {
        max-width: 100%;
    }
}
</style>
@endpush

@section('main')

@php
    $variants   = $product->variants ?? collect();
    $sizes      = $variants->pluck('size')->filter()->unique()->values();
    $selectedColor = $selectedColor ?? null;

    // Colors from variants
    $colorVariants = $variants->filter(fn($v) => $v->color)
        ->unique('color')
        ->map(fn($v) => [
            'color'     => $v->color,
            'slug'      => Str::slug($v->color),
            'hex'       => $v->color_hex,
            'stock'     => $variants->where('color',$v->color)->sum('stock'),
        ])
        ->values();

    $hasDiscount = $product->original_price && $product->original_price > $product->price;
    $discPct     = $hasDiscount ? (int)round((($product->original_price - $product->price) / $product->original_price) * 100) : 0;
    $inStock     = $product->has_variants ? $variants->sum('stock') > 0 : $product->stock > 0;

    $firstColor = $selectedColor
        ? $colorVariants->firstWhere('color', $selectedColor)
        : $colorVariants->first();
    $selectedColor = $firstColor['color'] ?? $selectedColor;

    $productImages = $product->productImages ?? collect();
    $mediaImages = $product->media ?? collect();
    $fallbackImages = $productImages->isNotEmpty()
        ? $productImages->map(fn($i) => ['url' => $i->url, 'is_primary' => $i->is_primary, 'side' => 'all', 'label' => 'Detail'])
        : $mediaImages->map(fn($i) => ['url' => $i->url, 'is_primary' => $i->is_primary, 'side' => 'all', 'label' => 'Detail']);

    // Front print images collection
    $frontImagesList = collect();
    if (!empty($product->front_image)) {
        $frontImagesList->push([
            'url' => $product->front_image,
            'is_primary' => true,
            'side' => 'front',
            'label' => 'Front Side (Primary)'
        ]);
    }
    foreach ($mediaImages->where('collection', 'front_print') as $med) {
        if (!$frontImagesList->contains('url', $med->url)) {
            $frontImagesList->push([
                'url' => $med->url,
                'is_primary' => (bool)$med->is_primary,
                'side' => 'front',
                'label' => 'Front Side'
            ]);
        }
    }
    if ($frontImagesList->isEmpty()) {
        $firstImg = $fallbackImages->first()['url'] ?? ($product->main_image ?? $product->image);
        if (!empty($firstImg)) {
            $frontImagesList->push([
                'url' => $firstImg,
                'is_primary' => true,
                'side' => 'front',
                'label' => 'Front Side'
            ]);
        }
    }

    // Back print images collection
    $backImagesList = collect();
    if (!empty($product->back_image)) {
        $backImagesList->push([
            'url' => $product->back_image,
            'is_primary' => true,
            'side' => 'back',
            'label' => 'Back Side (Primary)'
        ]);
    }
    foreach ($mediaImages->where('collection', 'back_print') as $med) {
        if (!$backImagesList->contains('url', $med->url)) {
            $backImagesList->push([
                'url' => $med->url,
                'is_primary' => (bool)$med->is_primary,
                'side' => 'back',
                'label' => 'Back Side'
            ]);
        }
    }
    if ($backImagesList->isEmpty()) {
        $backCandidate = $fallbackImages->first(function ($img) {
            return preg_match('/back|rear|reverse/i', $img['url'] ?? '')
                || preg_match('/back|rear|reverse/i', $img['alt_text'] ?? '');
        });
        if (!$backCandidate && $fallbackImages->count() > 1) {
            $backCandidate = $fallbackImages->skip(1)->first();
        }
        if ($backCandidate && !empty($backCandidate['url'])) {
            $backImagesList->push([
                'url' => $backCandidate['url'],
                'is_primary' => true,
                'side' => 'back',
                'label' => 'Back Side'
            ]);
        }
    }
    // If front and back are identical but multiple photos exist, pick 2nd photo for back
    if ($frontImagesList->isNotEmpty() && $backImagesList->isNotEmpty()
        && $frontImagesList->first()['url'] === $backImagesList->first()['url']
        && $fallbackImages->count() > 1) {
        $altBack = $fallbackImages->first(fn($img) => $img['url'] !== $frontImagesList->first()['url']);
        if ($altBack) {
            $backImagesList = collect([[
                'url' => $altBack['url'],
                'is_primary' => true,
                'side' => 'back',
                'label' => 'Back Side'
            ]]);
        }
    }

    $printSidesMode = $product->available_print_sides ?: 'both';
    $defaultDesignSide = null;

    // Both sides image stream (Default: shows both Front & Back images)
    $bothImagesList = collect();
    if ($frontImagesList->isNotEmpty()) {
        $bothImagesList->push($frontImagesList->first());
    }
    if ($backImagesList->isNotEmpty()) {
        $bothImagesList->push($backImagesList->first());
    }
    foreach ($frontImagesList->slice(1) as $fImg) {
        if (!$bothImagesList->contains('url', $fImg['url'])) {
            $bothImagesList->push($fImg);
        }
    }
    foreach ($backImagesList->slice(1) as $bImg) {
        if (!$bothImagesList->contains('url', $bImg['url'])) {
            $bothImagesList->push($bImg);
        }
    }
    foreach ($fallbackImages as $fb) {
        if (!$bothImagesList->contains('url', $fb['url'])) {
            $bothImagesList->push([
                'url' => $fb['url'],
                'is_primary' => false,
                'side' => 'all',
                'label' => 'Detail'
            ]);
        }
    }
    if (!empty($product->main_image) && !$bothImagesList->contains('url', $product->main_image)) {
        if ($bothImagesList->isEmpty()) {
            $bothImagesList->push(['url' => $product->main_image, 'is_primary' => true, 'side' => 'all', 'label' => 'Main']);
        } else {
            $bothImagesList->push(['url' => $product->main_image, 'is_primary' => false, 'side' => 'all', 'label' => 'Main']);
        }
    }

    $colorImages = [];
    foreach ($colorVariants as $cv) {
        $colorSpecific = $productImages->filter(function ($img) use ($cv) {
            return strcasecmp($img->color->name ?? '', $cv['color']) === 0
                || stripos($img->alt_text ?? '', $cv['color']) !== false;
        });

        if ($colorSpecific->isEmpty()) {
            $colorSpecific = $mediaImages->filter(fn($img) => stripos($img->alt_text ?? '', $cv['color']) !== false);
        }

        $colorImages[$cv['color']] = $colorSpecific->isNotEmpty()
            ? $colorSpecific->map(fn($i) => ['url'=>$i->url, 'is_primary'=>$i->is_primary, 'side' => 'all', 'label' => 'Color View'])->values()
            : $fallbackImages->values();
    }
    if (empty($colorImages)) {
        $colorImages['all'] = $fallbackImages->values();
    }

    // Default gallery images: Both sides if available, or selected side
    if ($defaultDesignSide === 'front' && $frontImagesList->isNotEmpty()) {
        $images = $frontImagesList;
    } elseif ($defaultDesignSide === 'back' && $backImagesList->isNotEmpty()) {
        $images = $backImagesList;
    } elseif ($bothImagesList->isNotEmpty()) {
        $images = $bothImagesList;
    } else {
        $selectedImages = ($selectedColor && isset($colorImages[$selectedColor]))
            ? collect($colorImages[$selectedColor])
            : collect($fallbackImages);
        $images = $selectedImages->isNotEmpty() ? $selectedImages : collect([['url' => $product->main_image, 'is_primary' => true, 'side' => 'all', 'label' => 'Main']]);
    }
    $mainImg = $images->first()['url'] ?? $product->main_image;
    $displayName = trim($product->name . ($selectedColor ? ' - ' . $selectedColor : ''));

    // Find default in-stock size
    $defaultSize = null;
    foreach ($sizes as $sz) {
        $sv = $variants->where('size', $sz)->when($selectedColor, fn($c) => $c->where('color', $selectedColor))->first()
              ?? $variants->where('size', $sz)->first();
        if ($sv && $sv->stock > 0) {
            $defaultSize = $sz;
            break;
        }
    }
    if (!$defaultSize && $sizes->isNotEmpty()) {
        $defaultSize = $sizes->first();
    }
@endphp

<div class="pd-wrap">
    {{-- Breadcrumb --}}
    <nav class="pd-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span class="sep">/</span>
        @if($product->category)
            @if($product->category->parent)
                <a href="{{ route('shop.category', $product->category->parent->slug) }}">{{ $product->category->parent->name }}</a>
                <span class="sep">/</span>
            @endif
            <a href="{{ route('shop.category', $product->category->slug) }}">{{ $product->category->name }}</a>
            <span class="sep">/</span>
        @endif
        <span class="current">{{ Str::limit($displayName, 32) }}</span>
    </nav>

    <div class="pd-grid">
        {{-- ── LEFT: EDITORIAL PHOTO GALLERY STREAM (BONKERS STYLE) ── --}}
        <div class="pd-gallery-col">
            <div class="pd-gallery-stream">
                {{-- Hero Main Image --}}
                <div class="pd-hero-img-wrap" onclick="openProductGallery(0)">
                    <img src="{{ $mainImg }}" alt="{{ $displayName }}" id="mainImg"
                         onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                    
                    <button type="button" class="pd-share-btn" onclick="event.stopPropagation(); shareProduct();" aria-label="Share product">
                        <i class="bi bi-share"></i>
                    </button>

                    @php
                        $heroSide = $images->first()['side'] ?? null;
                    @endphp
                    <span id="mainImgSideBadge" class="gallery-side-badge" style="bottom:14px;left:14px;font-size:11px;padding:4px 10px;{{ in_array($heroSide, ['front', 'back']) ? '' : 'display:none;' }}">
                        {{ in_array($heroSide, ['front', 'back']) ? ucfirst($heroSide) . ' View' : '' }}
                    </span>

                    @if($discPct >= 70)
                        <div class="badge-discount">🔥 {{ $discPct }}% OFF</div>
                    @elseif($hasDiscount)
                        <div class="badge-discount">-{{ $discPct }}%</div>
                    @endif
                    @if($product->is_new && !$hasDiscount)
                        <div class="badge-new">NEW</div>
                    @endif
                </div>

                {{-- 2-Column Editorial Grid for Remaining Photos --}}
                <div class="pd-gallery-2col-grid" id="gallery2ColGrid" style="{{ $images->count() > 1 ? '' : 'display:none;' }}">
                    @foreach($images->slice(1) as $idx => $img)
                        <div class="pd-gallery-sub-card" onclick="openProductGallery({{ $idx + 1 }})" data-side="{{ $img['side'] ?? 'all' }}">
                            <img src="{{ $img['url'] }}" alt="{{ $displayName }}" loading="lazy"
                                 onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                            @if(!empty($img['side']) && in_array($img['side'], ['front', 'back']))
                                <span class="gallery-side-badge">{{ ucfirst($img['side']) }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Hidden Thumbnails for legacy scripts --}}
            <div style="display:none;" id="thumbsContainer">
                @foreach($images as $i => $img)
                    <div class="pd-thumb {{ $i===0 ? 'active':'' }}" onclick='switchImg(@json($img['url']), this, {{ $i }})'></div>
                @endforeach
            </div>
        </div>

        {{-- ── RIGHT: STICKY PRODUCT INFO (BONKERS STYLE) ── --}}
        <div class="pd-info">
            {{-- Title & Wishlist Row --}}
            <div class="pd-title-row">
                <h1 class="pd-main-title">{{ $displayName }}</h1>
                <button type="button" class="pd-wish-btn-top {{ in_array($product->id, session('wishlist',[])) ? 'wished':'' }}"
                        onclick="toggleWishlist({{ $product->id }}, this)" aria-label="Wishlist">
                    <i class="bi {{ in_array($product->id, session('wishlist',[])) ? 'bi-heart-fill':'bi-heart' }}"></i>
                </button>
            </div>

            {{-- Pricing --}}
            <div class="pd-pricing-box">
                <div>
                    <span class="pd-price-main" id="displayPrice">₹{{ number_format($product->price) }}</span>
                    @if($hasDiscount)
                        <del class="pd-price-mrp">₹{{ number_format($product->original_price) }}</del>
                        <span class="pd-disc-pill">{{ $discPct }}% OFF</span>
                    @endif
                </div>
                <div class="pd-tax-sub">Inclusive of all taxes · Free shipping above ₹999</div>
            </div>

            {{-- Color Selection (if variants) --}}
            @if(isset($linkedColorProducts) && $linkedColorProducts->count() > 1)
            <div>
                <div class="section-label" style="font-size:12px; font-weight:800; color:#000; margin-bottom:8px;">COLOR : <span style="font-weight:400">{{ $product->color_name ?? '' }}</span></div>
                <div class="color-grid">
                    @foreach($linkedColorProducts as $linkedProduct)
                        <a class="color-item {{ (int) $linkedProduct->id === (int) $product->id ? 'active':'' }}"
                           href="{{ route('product.show', $linkedProduct->slug) }}"
                           title="{{ $linkedProduct->color_name ?: $linkedProduct->name }}">
                            <div class="color-img-wrap">
                                <img src="{{ $linkedProduct->card_image }}" alt="{{ $linkedProduct->color_name ?: $linkedProduct->name }}"
                                     onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                            </div>
                            <span class="color-name">{{ $linkedProduct->color_name ?: Str::limit($linkedProduct->name, 14) }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
            @elseif($colorVariants->count() > 0)
            <div>
                <div class="section-label" style="font-size:12px; font-weight:800; color:#000; margin-bottom:8px;">COLOR : <span id="selectedColorLabel" style="font-weight:400">{{ $selectedColor ?? '' }}</span></div>
                <div class="color-grid" id="colorGrid">
                    @foreach($colorVariants as $ci => $cv)
                        @php
                            $colorImg = collect($colorImages[$cv['color']] ?? [])->first();
                            $imgUrl = $colorImg['url'] ?? asset('images/placeholder-product.jpg');
                        @endphp
                        <a class="color-item {{ $cv['color'] === $selectedColor ? 'active':'' }}"
                             href="{{ route('product.show.color', ['slug' => $product->slug, 'colorSlug' => $cv['slug']]) }}"
                             data-color="{{ $cv['color'] }}"
                             data-hex="{{ $cv['hex'] ?? '#ccc' }}"
                             data-url="{{ route('product.show.color', ['slug' => $product->slug, 'colorSlug' => $cv['slug']]) }}"
                             onclick="event.preventDefault(); selectColor(this.dataset.color, this.dataset.hex, this)">
                            <div class="color-img-wrap">
                                <img src="{{ $imgUrl }}" alt="{{ $cv['color'] }}"
                                     onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                            </div>
                            <span class="color-name">{{ $cv['color'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Size Section --}}
            @if($sizes->count() > 0)
            <div>
                <div class="pd-size-header-row">
                    <div>Size : <span id="selSize" style="font-weight:800; color:var(--ttt-navy);">{{ $defaultSize ?? 'Select' }}</span></div>
                    <span class="pd-size-guide-link" onclick="document.getElementById('sizeGuideSection').scrollIntoView({behavior:'smooth'})">
                        <i class="bi bi-rulers"></i> Find Your Size / Size Guide
                    </span>
                </div>
                <div class="pd-size-grid-boxes" id="sizeOpts">
                    @foreach($sizes as $size)
                        @php
                            $selColor    = $colorVariants->first()['color'] ?? null;
                            $sv          = $variants->where('size',$size)->when($selColor, fn($c)=>$c->where('color',$selColor))->first()
                                        ?? $variants->where('size',$size)->first();
                            $sStock      = $sv ? $sv->stock : 0;
                            $isDefault   = ($size === $defaultSize && $sStock > 0);
                        @endphp
                        <button type="button"
                                class="pd-size-box-btn size-btn {{ $sStock<=0 ? 'oos':'' }} {{ $isDefault ? 'active':'' }}"
                                data-size="{{ $size }}"
                                data-stock="{{ $sStock }}"
                                {{ $sStock<=0 ? 'disabled':'' }}
                                onclick="selectSize(this)">
                            {{ $size }}
                        </button>
                    @endforeach
                </div>
                <div class="variant-msg" id="variantMsg" style="margin-top:6px; font-size:12px; color:#ff3f6c; display:none;">Please select a size</div>
            </div>
            @endif

            {{-- Print / Design Side Placement Selector --}}
            @php
                $printSidesMode = $product->available_print_sides ?: 'both';
                $defaultDesignSide = null;
            @endphp
            <div class="pd-design-side-box" id="designSideBox">
                <div class="pd-side-header-row">
                    <div>
                        <i class="bi bi-aspect-ratio text-primary me-1"></i> Print Placement :
                        <span id="selDesignSideLabel" class="current-side-tag" style="color: #ef4444; font-weight: 700;">
                            Please Select
                        </span>
                    </div>
                    <span style="font-size: 11px; color: #ef4444; font-weight: 700; text-transform: none;">
                        * Selection Required
                    </span>
                </div>

                <div class="pd-side-instruction-hint">
                    <i class="bi bi-info-circle-fill text-primary me-1"></i>
                    <strong>Print Instruction:</strong> Graphic will be printed on one side only. Please select either <strong>Front</strong> or <strong>Back</strong> below.
                </div>

                <div class="pd-side-grid-boxes">
                    @if ($printSidesMode === 'both' || $printSidesMode === 'front_only')
                        <button type="button" class="pd-side-btn" data-side="front" onclick="selectDesignSide('front', this)">
                            <span class="pd-side-icon">👕</span>
                            <span class="pd-side-name">Front Side Print</span>
                            <span class="pd-side-hint">Graphic on Front Chest</span>
                        </button>
                    @endif
                    @if ($printSidesMode === 'both' || $printSidesMode === 'back_only')
                        <button type="button" class="pd-side-btn" data-side="back" onclick="selectDesignSide('back', this)">
                            <span class="pd-side-icon">🔄</span>
                            <span class="pd-side-name">Back Side Print</span>
                            <span class="pd-side-hint">Graphic on T-Shirt Back</span>
                        </button>
                    @endif
                </div>

                <div class="pd-side-confirm-badge" id="sideConfirmBadge" style="display:none;">
                    <i class="bi bi-check-circle-fill text-success"></i>
                    <span>Selected: Graphic will be printed on the <strong id="sideConfirmText">FRONT</strong> of your T-Shirt.</span>
                </div>

                <div class="pd-side-required-msg" id="designSideMsg">⚠️ Please select Front Side or Back Side print first!</div>
            </div>

            {{-- Buttons Stack --}}
            <div class="pd-btn-row-stack">
                <div class="pd-btn-split-row">
                    <div class="pd-stepper-box">
                        <button type="button" onclick="changeQty(-1)" aria-label="Decrease quantity">-</button>
                        <span id="qtyVal">1</span>
                        <button type="button" onclick="changeQty(1)" aria-label="Increase quantity">+</button>
                    </div>
                    <button class="pd-btn-outline-atc" id="atcBtn" onclick="addToCart()" {{ !$inStock ? 'disabled':'' }}>
                        {{ !$inStock ? 'OUT OF STOCK' : 'ADD TO CART' }}
                    </button>
                </div>
                <button class="pd-btn-solid-buy" id="buyBtn" onclick="buyNow()" {{ !$inStock ? 'disabled':'' }}>
                    BUY IT NOW
                </button>
            </div>

            {{-- ⚡ Prepaid Offer Banner --}}
            <div class="pd-offer-pill-card">
                <span> Pay Online & Get Extra 5% OFF | Instant Dispatch</span>
            </div>

            {{-- Estimated Delivery Pincode Checker --}}
            <div class="pd-pincode-card">
                <div class="pd-pincode-head">Estimated Delivery Time</div>
                <div class="pd-pincode-input-row">
                    <input type="text" id="bkPincodeInput" class="pd-pincode-field" placeholder="Enter 6-digit Pincode" maxlength="6">
                    <button type="button" class="pd-pincode-btn" onclick="checkDeliveryPincode()">CHECK</button>
                </div>
                <div class="pd-pincode-result" id="bkPincodeResult"></div>
                <div class="pd-pincode-subtext">Enter your pincode to check estimated delivery and COD availability.</div>
            </div>

            {{-- "What You Get For ₹X" Trust List --}}
            <div class="pd-what-you-get-box">
                <div class="pd-wyg-title">What You Get for ₹{{ number_format($product->price) }}</div>
                <div class="pd-wyg-list">
                    <div class="pd-wyg-item">
                        <i class="bi bi-patch-check pd-wyg-icon"></i>
                        <div class="pd-wyg-text">
                            <strong>240-280 GSM Heavyweight Fabric</strong>
                            Super-combed 100% cotton with a structured streetwear drape.
                        </div>
                    </div>
                    <div class="pd-wyg-item">
                        <i class="bi bi-person-standing pd-wyg-icon"></i>
                        <div class="pd-wyg-text">
                            <strong>Relaxed Streetwear Drop-Shoulder Fit</strong>
                            Authentic oversized streetwear cut for effortless modern style.
                        </div>
                    </div>
                    <div class="pd-wyg-item">
                        <i class="bi bi-shield-check pd-wyg-icon"></i>
                        <div class="pd-wyg-text">
                            <strong>All-Day Breathable Comfort</strong>
                            Pre-shrunk and bio-washed for ultra softness & zero shrinkage.
                        </div>
                    </div>
                    <div class="pd-wyg-item">
                        <i class="bi bi-truck pd-wyg-icon"></i>
                        <div class="pd-wyg-text">
                            <strong>Express Metro Delivery</strong>
                            Dispatched within 24 hours with live SMS & WhatsApp tracking.
                        </div>
                    </div>
                    <div class="pd-wyg-item">
                        <i class="bi bi-arrow-repeat pd-wyg-icon"></i>
                        <div class="pd-wyg-text">
                            <strong>7-Day Easy Returns & Exchanges</strong>
                            Hassle-free doorstep returns and instant size swaps.
                        </div>
                    </div>
                </div>
            </div>

            {{-- Trend Tags --}}
            <div class="pd-trend-tags-row">
                <span class="pd-trend-tag">#BESTSELLER</span>
                <span class="pd-trend-tag">#OVERSIZED</span>
                <span class="pd-trend-tag">#STREETWEAR</span>
                <span class="pd-trend-tag">#HEAVYWEIGHT</span>
                <span class="pd-trend-tag">#UNISEX</span>
            </div>

            {{-- Accordion Details List --}}
            <div class="pd-accordion-list">
                {{-- Accordion 1: Description --}}
                @if($product->description || $product->short_description)
                <div class="pd-accordion-item open">
                    <button type="button" class="pd-accordion-btn" onclick="toggleAccordionItem(this)">
                        <span>Product Details & Specifications</span>
                        <i class="bi bi-chevron-down pd-accordion-arrow"></i>
                    </button>
                    <div class="pd-accordion-content">
                        @if($product->short_description)
                            <p style="margin-bottom:10px; font-weight:600; color:#1e293b;">{{ $product->short_description }}</p>
                        @endif
                        @if($product->description)
                            @php
                                $rawDescription = (string) $product->description;
                                if ($rawDescription === strip_tags($rawDescription)) {
                                    $descriptionHtml = nl2br(e($rawDescription));
                                } else {
                                    $descriptionHtml = preg_replace('#<(script|style|iframe|object|embed)\b[^>]*>.*?</\1>#is', '', $rawDescription);
                                    $descriptionHtml = strip_tags($descriptionHtml, '<p><div><br><strong><b><em><i><u><h2><h3><ul><ol><li><blockquote>');
                                }
                            @endphp
                            <div class="pd-description-content">{!! $descriptionHtml !!}</div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Accordion 2: Size & Measurements --}}
                <div class="pd-accordion-item" id="sizeGuideSection">
                    <button type="button" class="pd-accordion-btn" onclick="toggleAccordionItem(this)">
                        <span>Size Guide & Measurements (Inches)</span>
                        <i class="bi bi-chevron-down pd-accordion-arrow"></i>
                    </button>
                    <div class="pd-accordion-content">
                        <div class="pd-table-wrap">
                            <table class="pd-size-table" style="width:100%; font-size:12px;">
                                <thead>
                                    <tr>
                                        @foreach(['Size','Chest','Waist','Hip','Length'] as $h)
                                            <th>{{ $h }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(['XS'=>[32,26,34,25],'S'=>[34,28,36,26],'M'=>[36,30,38,27],'L'=>[38,32,40,28],'XL'=>[40,34,42,29],'XXL'=>[42,36,44,30]] as $s=>$m)
                                        <tr>
                                            <td style="font-weight:700;">{{ $s }}</td>
                                            @foreach($m as $v)<td>{{ $v }}"</td>@endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Accordion 3: FAQ --}}
                <div class="pd-accordion-item">
                    <button type="button" class="pd-accordion-btn" onclick="toggleAccordionItem(this)">
                        <span>Frequently Asked Questions (FAQ)</span>
                        <i class="bi bi-chevron-down pd-accordion-arrow"></i>
                    </button>
                    <div class="pd-accordion-content">
                        <div style="display:flex; flex-direction:column; gap:12px;">
                            <div>
                                <strong style="color:#000; font-size:12.5px; display:block;">Q: How does the fit run?</strong>
                                <span>A: It has an authentic relaxed oversized streetwear cut. Choose standard size for oversized look or size down for fitted.</span>
                            </div>
                            <div>
                                <strong style="color:#000; font-size:12.5px; display:block;">Q: When will my order arrive?</strong>
                                <span>A: Metro deliveries arrive in 2-4 business days, others in 4-7 business days with live WhatsApp updates.</span>
                            </div>
                            <div>
                                <strong style="color:#000; font-size:12.5px; display:block;">Q: Is Cash on Delivery available?</strong>
                                <span>A: Yes, Cash on Delivery is available across all serviceable Indian pincodes.</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Accordion 4: Washing Care --}}
                <div class="pd-accordion-item">
                    <button type="button" class="pd-accordion-btn" onclick="toggleAccordionItem(this)">
                        <span>Wash & Garment Care</span>
                        <i class="bi bi-chevron-down pd-accordion-arrow"></i>
                    </button>
                    <div class="pd-accordion-content">
                        <p style="margin:0;">Machine wash cold inside-out with like colors. Do not bleach. Tumble dry on low or hang dry in shade. Warm iron inside-out without touching prints or embroidery.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Reviews (If any) --}}
    @if(isset($product->reviews) && $product->reviews->count() > 0)
    <div class="pd-reviews-section" style="margin-top:48px;">
        <h3 class="pd-reviews-title" style="font-family:'Cinzel',serif; font-size:18px; font-weight:800; color:#000; margin-bottom:20px;">
            CUSTOMER REVIEWS ({{ $product->reviews->count() }})
        </h3>
        <div class="pd-reviews-grid">
            @foreach($product->reviews->where('is_active',true)->take(6) as $r)
            <div class="pd-review-card">
                <div class="pd-review-head">
                    <div class="pd-reviewer-avatar">{{ strtoupper(substr($r->reviewer_name,0,2)) }}</div>
                    <div class="pd-reviewer-info">
                        <div class="pd-reviewer-name">{{ $r->reviewer_name }}</div>
                        <div class="pd-reviewer-stars">@for($i=1;$i<=5;$i++){{ $i<=$r->rating?'★':'☆' }}@endfor</div>
                    </div>
                    @if($r->is_verified)
                        <span class="pd-verified-badge"><i class="bi bi-patch-check-fill"></i> Verified</span>
                    @endif
                </div>
                <p class="pd-review-comment">{{ $r->comment }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Related Products (YOU MAY ALSO LIKE) --}}
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
    <section class="pd-related" aria-label="You may also like" style="margin-top:56px;">
        <h3 class="pd-related-title" style="font-family:'Cinzel',serif; font-size:20px; font-weight:900; letter-spacing:0.8px; color:#000; margin-bottom:20px; text-transform:uppercase;">
            YOU MAY ALSO LIKE
        </h3>
        <div class="pd-related-grid">
            @foreach($relatedProducts as $rp)
                @php
                    $rpImg = $rp->card_image ?? $rp->main_image ?? asset('images/placeholder-product.jpg');
                    $rpHasDiscount = $rp->original_price && $rp->original_price > $rp->price;
                    $rpDiscount = $rpHasDiscount ? (int) round((($rp->original_price - $rp->price) / $rp->original_price) * 100) : 0;
                @endphp
                <article class="pd-related-card">
                    <a href="{{ route('product.show', $rp->slug) }}" class="pd-related-img">
                        <img src="{{ $rpImg }}" alt="{{ $rp->name }}" loading="lazy"
                             onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                        @if($rpHasDiscount)
                            <span class="pd-related-badge">-{{ $rpDiscount }}%</span>
                        @elseif($rp->is_new)
                            <span class="pd-related-badge">NEW</span>
                        @endif
                    </a>
                    <div class="pd-related-info">
                        <a href="{{ route('product.show', $rp->slug) }}" class="pd-related-name">{{ $rp->name }}</a>
                        <div class="pd-related-price">
                            <span>Rs. {{ number_format($rp->price) }}</span>
                            @if($rpHasDiscount)
                                <del>Rs. {{ number_format($rp->original_price) }}</del>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
    @endif
</div>

{{-- Lightbox Gallery --}}
<div class="pd-lightbox" id="productLightbox" aria-hidden="true">
    <button type="button" class="pd-lightbox-close" onclick="closeProductGallery()" aria-label="Close gallery">
        <i class="bi bi-x-lg"></i>
    </button>
    <button type="button" class="pd-lightbox-nav pd-lightbox-prev" onclick="moveProductGallery(-1)" aria-label="Previous image">
        <i class="bi bi-chevron-left"></i>
    </button>
    <figure class="pd-lightbox-stage">
        <img src="" alt="{{ $displayName }}" id="productLightboxImg">
        <figcaption id="productLightboxCount"></figcaption>
    </figure>
    <button type="button" class="pd-lightbox-nav pd-lightbox-next" onclick="moveProductGallery(1)" aria-label="Next image">
        <i class="bi bi-chevron-right"></i>
    </button>
    <div class="pd-lightbox-thumbs" id="productLightboxThumbs"></div>
</div>

<div class="toast" id="toast"></div>

{{-- Razorpay loading overlay --}}
<div class="rzp-loading-pop" id="rzpLoadingPop" aria-hidden="true" style="display:none;position:fixed;inset:0;z-index:999999;">
    <div class="rzp-loading-card">
        <div class="rzp-loading-spinner"></div>
        <p>Opening secure payment...</p>
    </div>
</div>

{{-- Shopping Bag Slide Drawer (Exact Match media_1788344894799.png & media_1788345176092.png) --}}
@php
    $checkoutUser = auth()->user();
    $checkoutUserSafe = optional(auth()->user());
    $allCoupons = \App\Models\Coupon::with('rules')->where('is_active', true)->get();
@endphp
<div class="checkout-pop" id="checkoutPop" aria-hidden="true">
    <div class="checkout-pop__shade" onclick="closeCheckoutPop()"></div>
    <aside class="checkout-pop__panel cart-drawer-panel" role="dialog" aria-modal="true" aria-label="Shopping Bag">
        
        {{-- VIEW 1: CART VIEW --}}
        <div class="cart-slide-view-pane" id="coSlideCartView">
            {{-- 1. Header --}}
            <div class="cart-slide-header">
                <div class="cart-slide-title-wrap">
                    <h3 class="cart-slide-title">Your Cart (<span id="coCartCount">1</span> items)</h3>
                </div>
                <button type="button" class="cart-slide-close-btn" onclick="closeCheckoutPop()" aria-label="Close cart">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="cart-slide-scrollable-body" id="coSlideScrollBody">
                {{-- 2. Promo Black Strip --}}
                <div class="cart-slide-promo-black" id="coPromoBlack">
                    <div class="promo-black-title">New customers enjoy 15% OFF!</div>
                    <div class="promo-black-sub">Use code NEW15 on orders above ₹2000</div>
                </div>

                {{-- 3. Milestone Progress Bar --}}
                <div class="cart-slide-milestones-card" id="coMilestonesCard">
                    <div class="milestones-subtitle" id="coMilestoneText">
                        Add items worth <b>₹4,700</b> more to unlock 15% off with code <b>GOBONKERS15</b>
                    </div>
                    <div class="milestones-track-wrap">
                        <div class="milestones-line-bg">
                            <div class="milestones-line-fill" id="coMilestoneFill" style="width: 20%;"></div>
                        </div>
                        <div class="milestones-nodes">
                            <div class="milestone-node active" id="node1">
                                <span class="milestone-val">₹500</span>
                                <span class="milestone-icon">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 2l2.4 2.4 3.4-.6 1.2 3.2 3.2 1.2-.6 3.4 2.4 2.4-2.4 2.4.6 3.4-3.2 1.2-1.2 3.2-3.4-.6-2.4 2.4-2.4-2.4-3.4.6-1.2-3.2-3.2-1.2.6-3.4-2.4-2.4 2.4-2.4-.6-3.4 3.2-1.2 1.2-3.2 3.4.6 2.4-2.4z"/>
                                        <line x1="9" y1="15" x2="15" y2="9"/>
                                        <circle cx="9.5" cy="9.5" r=".7" fill="currentColor"/>
                                        <circle cx="14.5" cy="14.5" r=".7" fill="currentColor"/>
                                    </svg>
                                </span>
                                <span class="milestone-label">10% Off</span>
                            </div>
                            <div class="milestone-node" id="node2">
                                <span class="milestone-val">₹5,999</span>
                                <span class="milestone-icon">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 2l2.4 2.4 3.4-.6 1.2 3.2 3.2 1.2-.6 3.4 2.4 2.4-2.4 2.4.6 3.4-3.2 1.2-1.2 3.2-3.4-.6-2.4 2.4-2.4-2.4-3.4.6-1.2-3.2-3.2-1.2.6-3.4-2.4-2.4 2.4-2.4-.6-3.4 3.2-1.2 1.2-3.2 3.4.6 2.4-2.4z"/>
                                        <line x1="9" y1="15" x2="15" y2="9"/>
                                        <circle cx="9.5" cy="9.5" r=".7" fill="currentColor"/>
                                        <circle cx="14.5" cy="14.5" r=".7" fill="currentColor"/>
                                    </svg>
                                </span>
                                <span class="milestone-label">15% Off</span>
                            </div>
                            <div class="milestone-node" id="node3">
                                <span class="milestone-val">₹9,999</span>
                                <span class="milestone-icon">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 2l2.4 2.4 3.4-.6 1.2 3.2 3.2 1.2-.6 3.4 2.4 2.4-2.4 2.4.6 3.4-3.2 1.2-1.2 3.2-3.4-.6-2.4 2.4-2.4-2.4-3.4.6-1.2-3.2-3.2-1.2.6-3.4-2.4-2.4 2.4-2.4-.6-3.4 3.2-1.2 1.2-3.2 3.4.6 2.4-2.4z"/>
                                        <line x1="9" y1="15" x2="15" y2="9"/>
                                        <circle cx="9.5" cy="9.5" r=".7" fill="currentColor"/>
                                        <circle cx="14.5" cy="14.5" r=".7" fill="currentColor"/>
                                    </svg>
                                </span>
                                <span class="milestone-label">20% Off</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 4. Cart Items --}}
                <div class="cart-slide-items-list" id="coOrderItems">
                    {{-- JS dynamically renders item cards --}}
                </div>

                {{-- 5. Coupon Card --}}
                <div class="cart-slide-coupon-card" id="cartSlideCouponCard">
                    <div class="coupon-field-wrap">
                        <span class="coupon-field-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2l2.4 2.4 3.4-.6 1.2 3.2 3.2 1.2-.6 3.4 2.4 2.4-2.4 2.4.6 3.4-3.2 1.2-1.2 3.2-3.4-.6-2.4 2.4-2.4-2.4-3.4.6-1.2-3.2-3.2-1.2.6-3.4-2.4-2.4 2.4-2.4-.6-3.4 3.2-1.2 1.2-3.2 3.4.6 2.4-2.4z"/>
                                <line x1="9" y1="15" x2="15" y2="9"/>
                                <circle cx="9.5" cy="9.5" r=".7" fill="#10b981"/>
                                <circle cx="14.5" cy="14.5" r=".7" fill="#10b981"/>
                            </svg>
                        </span>
                        <input type="text" placeholder="Enter Coupon Code" class="coupon-code-input" id="slideDrawerCouponInput" autocomplete="off" style="text-transform: uppercase;">
                        <button type="button" class="btn-coupon-inline-apply" onclick="applySlideCoupon()">APPLY</button>
                    </div>
                    <div class="coupon-view-offers-row">
                        <button type="button" class="btn-view-all-offers-link" onclick="openCouponsInSlideDrawer()">
                            <span>View All Offers</span>
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>

                {{-- 6. Cross-sell Section --}}
                @if(isset($relatedProducts) && $relatedProducts->isNotEmpty())
                <div class="cart-slide-crosssell-section" id="cartSlideCrosssell">
                    <div class="crosssell-heading">You may also like...</div>
                    <div class="crosssell-items-scroll">
                        @foreach($relatedProducts->take(4) as $rel)
                        <div class="crosssell-item-card">
                            <img src="{{ $rel->card_image ?? $rel->main_image ?? asset('images/placeholder-product.jpg') }}" alt="{{ $rel->name }}">
                            <div class="crosssell-info">
                                <div class="crosssell-title">{{ Str::limit($rel->name, 20) }}</div>
                                <div class="crosssell-price">₹{{ number_format($rel->price) }}</div>
                            </div>
                            <button type="button" class="btn-crosssell-add" onclick="quickAddRelated({{ $rel->id }})">+ ADD</button>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- 7. Sticky Bottom Checkout Card --}}
            <div class="cart-slide-footer-wrap" id="coFooterWrap">
                <div class="cart-slide-saved-ribbon">
                    <span id="coFloatingSaved">₹200 Saved so far!</span>
                </div>

                <div class="cart-slide-sticky-card">
                    <div class="sticky-total-line" onclick="toggleSlideBreakdown()">
                        <div class="sticky-total-left">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1-2-1-2 1-2-1z"/>
                                <line x1="8" y1="9" x2="16" y2="9"/>
                                <line x1="8" y1="13" x2="16" y2="13"/>
                                <line x1="8" y1="17" x2="12" y2="17"/>
                            </svg>
                            <span class="sticky-total-label">Estimated Total</span>
                            <i class="bi bi-chevron-down" id="coBreakdownChevron" style="font-size: 12px; color: #64748b;"></i>
                        </div>
                        <div class="sticky-total-right">
                            <span class="sticky-mrp-price" id="coStickyMrp">₹1,499</span>
                            <strong class="sticky-final-price" id="coStickyTotal">₹1,349</strong>
                            <span class="sticky-disc-tag" id="coStickyDiscountPct">(13% OFF)</span>
                        </div>
                    </div>

                    <div class="sticky-breakdown-details" id="coStickyBreakdown" style="display: none;">
                        <div class="break-line"><span>Bag Subtotal</span><span id="coCartSubtotal">₹0</span></div>
                        <div class="break-line"><span>Delivery Charges</span><span id="coShipping" class="text-success">FREE</span></div>
                        <div class="break-line"><span>Savings on MRP</span><span id="coSavingsMrp" class="text-success">-₹0</span></div>
                    </div>

                    <div class="sticky-badges-line">
                        <div class="badge-shipping-est">
                            <i class="bi bi-truck"></i> <span>Incl. ~₹50 est. shipping</span>
                        </div>
                        <div class="badge-prepaid-save">
                            <i class="bi bi-check2"></i> <span id="coPrepaidSaveText">Save up to ₹65 on Prepaid</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="btn-sticky-black-checkout" id="coBtnCheckout">
                        <div class="checkout-btn-text-side">
                            <span class="checkout-btn-main-title">CHECKOUT</span>
                            <span class="checkout-btn-sub-note">5% OFF ON PREPAID ORDERS</span>
                        </div>
                        <div class="checkout-btn-logos-side">
                            <span class="pay-logo-pill paytm">Paytm</span>
                            <span class="pay-logo-pill pe">Pe</span>
                            <span class="pay-logo-pill gpay">GPay</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        {{-- VIEW 2: OFFERS VIEW (EXACT MATCH media_1788345176092.png) --}}
        <div class="cart-slide-view-pane" id="coSlideOffersView" style="display: none;">
            {{-- Header --}}
            <div class="cart-slide-header">
                <div class="cart-slide-title-wrap" style="cursor: pointer; display: flex; align-items: center; gap: 6px;" onclick="switchToSlideCartView()">
                    <i class="bi bi-arrow-left" style="font-size: 19px; color: #0f172a; line-height: 1;"></i>
                    <h3 class="cart-slide-title">Your Cart (<span id="coOffersCartCount">1</span> items)</h3>
                </div>
                <button type="button" class="cart-slide-close-btn" onclick="closeCheckoutPop()" aria-label="Close cart">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="cart-slide-scrollable-body" style="padding-top: 14px;">
                {{-- Tabs: Brand Offers | Payment Offers --}}
                <div class="slide-offers-tabs-row">
                    <button type="button" class="slide-offer-tab active" id="tabBrandOffers" onclick="switchOfferTab('brand')">Brand Offers</button>
                    <button type="button" class="slide-offer-tab" id="tabPaymentOffers" onclick="switchOfferTab('payment')">Payment Offers</button>
                </div>

                {{-- Input Box --}}
                <div class="coupon-field-wrap" style="margin: 6px 0 16px;">
                    <span class="coupon-field-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2l2.4 2.4 3.4-.6 1.2 3.2 3.2 1.2-.6 3.4 2.4 2.4-2.4 2.4.6 3.4-3.2 1.2-1.2 3.2-3.4-.6-2.4 2.4-2.4-2.4-3.4.6-1.2-3.2-3.2-1.2.6-3.4-2.4-2.4 2.4-2.4-.6-3.4 3.2-1.2 1.2-3.2 3.4.6 2.4-2.4z"/>
                            <line x1="9" y1="15" x2="15" y2="9"/>
                            <circle cx="9.5" cy="9.5" r=".7" fill="#10b981"/>
                            <circle cx="14.5" cy="14.5" r=".7" fill="#10b981"/>
                        </svg>
                    </span>
                    <input type="text" placeholder="Enter Coupon Code" class="coupon-code-input" id="offersDrawerCouponInput" autocomplete="off" style="text-transform: uppercase;">
                    <button type="button" class="btn-coupon-inline-apply" onclick="applySlideCouponFromOffers()">APPLY</button>
                </div>

                {{-- Brand Offers Tab Content --}}
                <div id="contentBrandOffers">
                    <div class="offers-section-title">Available Offers</div>
                    <div class="offers-list-group" id="coAvailableOffersList">
                        {{-- Rendered via JS --}}
                    </div>

                    <div class="offers-section-title" style="margin-top: 24px;">Unavailable Offers</div>
                    <div class="offers-list-group" id="coUnavailableOffersList">
                        {{-- Rendered via JS --}}
                    </div>
                </div>

                {{-- Payment Offers Tab Content --}}
                <div id="contentPaymentOffers" style="display: none;">
                    <div class="offers-section-title">Payment Offers</div>
                    <div class="offers-list-group" id="coPaymentOffersList">
                        {{-- Rendered via JS --}}
                    </div>
                </div>
            </div>
        </div>

    </aside>
</div>

@php
    $productShowConfig = [
        'productId' => $product->id,
        'hasVariants' => (bool) $product->has_variants,
        'csrf' => csrf_token(),
        'variants' => ($product->variants ?? collect())->map(fn($v) => [
            'id' => $v->id,
            'size' => $v->size,
            'color' => $v->color,
            'stock' => $v->stock,
            'price' => (float) $v->price,
            'color_hex' => $v->color_hex,
        ])->values(),
        'sizes' => $sizes->values(),
        'colorImages' => $colorImages,
        'galleryImages' => $images->values(),
        'frontImages' => $frontImagesList->values(),
        'backImages' => $backImagesList->values(),
        'bothImages' => $bothImagesList->values(),
        'allImages' => $bothImagesList->values(),
        'selectedColor' => $selectedColor ?? '',
        'colorUrls' => $colorVariants->mapWithKeys(fn($cv) => [
            $cv['color'] => route('product.show.color', ['slug' => $product->slug, 'colorSlug' => $cv['slug']]),
        ]),
        'basePrice' => (float) $product->price,
        'frontImage' => $product->front_image ?: ($frontImagesList->first()['url'] ?? null),
        'backImage' => $product->back_image ?: ($backImagesList->first()['url'] ?? null),
        'availablePrintSides' => $printSidesMode,
        'defaultDesignSide' => $defaultDesignSide,
        'cartAddUrl' => route('cart.add'),
        'availableCouponsUrl' => route('coupon.available'),
        'cartUpdateBaseUrl' => url('/cart/update'),
        'checkoutUrl' => route('checkout.index'),
        'addressUpdateUrl' => route('profile.update'),
        'razorpayKey'         => env('RAZORPAY_KEY_ID'),
        'razorpayCreateUrl'   => route('payment.razorpay.create'),
        'razorpayVerifyUrl'   => route('payment.razorpay.verify'),
        'razorpayFailureUrl'  => route('payment.razorpay.failure'),
        'allCoupons'          => $allCoupons->map(fn($c) => [
            'code'        => $c->code,
            'type'        => $c->type,
            'value'       => (float) $c->value,
            'min_spend'   => (float) ($c->rules->min_order_amount ?? 0),
            'description' => $c->description ?: 'Applicable on Prepaid & COD orders. T&C Apply'
        ])->values(),
        'drawerProduct' => [
            'name' => $displayName,
            'image' => $mainImg,
            'price' => (float) $product->price,
            'original_price' => (float) ($product->original_price ?: $product->price),
        ],
        'user' => [
            'name'    => $checkoutUserSafe->name ?? '',
            'email'   => $checkoutUserSafe->email ?? '',
            'phone'   => $checkoutUserSafe->phone ?? '',
            'address' => $checkoutUserSafe->address ?? '',
            'city'    => $checkoutUserSafe->city ?? '',
            'state'   => $checkoutUserSafe->state ?? '',
            'pincode' => $checkoutUserSafe->pincode ?? '',
        ],
    ];
@endphp

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
window.TTT_PRODUCT_SHOW = @json($productShowConfig);

window.selectedDesignSide = null;

window.selectDesignSide = function (side, btn) {
    var cfg = window.TTT_PRODUCT_SHOW || {};
    window.selectedDesignSide = side || null;

    document.querySelectorAll('.pd-side-btn').forEach(function (b) { b.classList.remove('active'); });
    if (btn) {
        btn.classList.add('active');
    } else if (window.selectedDesignSide) {
        var matchingBtn = document.querySelector('.pd-side-btn[data-side="' + window.selectedDesignSide + '"]');
        if (matchingBtn) matchingBtn.classList.add('active');
    }

    var label = document.getElementById('selDesignSideLabel');
    if (label) {
        if (window.selectedDesignSide === 'front') {
            label.textContent = 'Front Side';
            label.style.color = '#00285a';
        } else if (window.selectedDesignSide === 'back') {
            label.textContent = 'Back Side';
            label.style.color = '#00285a';
        } else {
            label.textContent = 'Please Select';
            label.style.color = '#ef4444';
        }
    }

    var confirmBadge = document.getElementById('sideConfirmBadge');
    var confirmText = document.getElementById('sideConfirmText');
    if (confirmBadge) {
        if (window.selectedDesignSide === 'front' || window.selectedDesignSide === 'back') {
            confirmBadge.style.display = 'flex';
            if (confirmText) confirmText.textContent = window.selectedDesignSide === 'back' ? 'BACK' : 'FRONT';
        } else {
            confirmBadge.style.display = 'none';
        }
    }

    var box = document.getElementById('designSideBox');
    var msg = document.getElementById('designSideMsg');
    if (box) box.classList.remove('needs-choice');
    if (msg) msg.style.display = 'none';

    var frontList = (cfg.frontImages && cfg.frontImages.length) ? cfg.frontImages.slice() : (cfg.frontImage ? [{ url: cfg.frontImage, side: 'front' }] : []);
    var backList = (cfg.backImages && cfg.backImages.length) ? cfg.backImages.slice() : (cfg.backImage ? [{ url: cfg.backImage, side: 'back' }] : []);
    var bothList = (cfg.bothImages && cfg.bothImages.length) ? cfg.bothImages.slice() : ((cfg.allImages && cfg.allImages.length) ? cfg.allImages.slice() : (cfg.galleryImages || []).slice());

    var activeList = [];
    if (window.selectedDesignSide === 'front') {
        activeList = frontList.slice();
        if (!activeList.length) {
            activeList = (cfg.allImages || []).filter(function (img) { return img && img.side === 'front'; });
        }
        if (!activeList.length && bothList.length) {
            activeList = [bothList[0]];
        }
    } else if (window.selectedDesignSide === 'back') {
        activeList = backList.slice();
        if (!activeList.length) {
            activeList = (cfg.allImages || []).filter(function (img) { return img && img.side === 'back'; });
        }
        if (!activeList.length) {
            var backMatch = (cfg.allImages || []).find(function (img) {
                return img && img.url && /back|rear|reverse/i.test(img.url);
            });
            if (backMatch) {
                activeList = [backMatch];
            } else if (bothList.length > 1) {
                activeList = [bothList[1]];
            }
        }
    } else {
        activeList = bothList.slice();
    }

    if (!activeList.length) {
        activeList = bothList.slice();
    }

    var heroImg = activeList.length && activeList[0] ? (activeList[0].url || activeList[0]) : '';
    var heroSide = activeList.length && activeList[0] && activeList[0].side ? activeList[0].side : (window.selectedDesignSide === 'back' ? 'back' : 'front');

    var main = document.getElementById('mainImg');
    if (main && heroImg) {
        main.src = heroImg;
    }

    var badge = document.getElementById('mainImgSideBadge');
    if (badge) {
        if (heroSide === 'front' || heroSide === 'back') {
            badge.textContent = (heroSide === 'front' ? 'Front' : 'Back') + ' View';
            badge.style.display = '';
        } else {
            badge.style.display = 'none';
        }
    }

    var grid = document.getElementById('gallery2ColGrid');
    if (grid) {
        var subImgs = activeList.slice(1);
        if (!subImgs.length) {
            grid.style.display = 'none';
            grid.innerHTML = '';
        } else {
            grid.style.display = 'grid';
            grid.innerHTML = subImgs.map(function (img, idx) {
                var sBadge = '';
                if (img.side === 'front') sBadge = '<span class="gallery-side-badge">Front</span>';
                else if (img.side === 'back') sBadge = '<span class="gallery-side-badge">Back</span>';
                var u = img.url || img;
                return '<div class="pd-gallery-sub-card" onclick="if(window.openProductGallery)window.openProductGallery(' + (idx + 1) + ');" data-side="' + (img.side || 'all') + '">' +
                    '<img src="' + u + '" alt="" loading="lazy" onerror="this.src=\'/images/placeholder-product.jpg\'">' +
                    sBadge +
                    '</div>';
            }).join('');
        }
    }

    if (window.renderLightboxThumbs) {
        window.galleryImages = activeList;
        window.renderLightboxThumbs();
    }
};

function toggleAccordionItem(btn) {
    const item = btn.closest('.pd-accordion-item');
    if (!item) return;
    item.classList.toggle('open');
}

function shareProduct() {
    var shareTitle = '{{ addslashes($product->name) }} | VAYU';
    var shareText = 'Check out {{ addslashes($product->name) }} (₹{{ number_format($product->price) }}) on VAYU:';
    var shareUrl = window.location.href;

    if (navigator.share) {
        navigator.share({
            title: shareTitle,
            text: shareText,
            url: shareUrl
        }).catch(function() {});
    } else {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(shareUrl).then(function() {
                if (typeof showToast === 'function') {
                    showToast('Product link copied to clipboard!');
                } else {
                    alert('Product link copied to clipboard!');
                }
            });
        } else {
            prompt('Copy product link:', shareUrl);
        }
    }
}

function checkDeliveryPincode() {
    const pin = document.getElementById('bkPincodeInput').value.trim();
    const res = document.getElementById('bkPincodeResult');
    if (!pin || pin.length !== 6 || !/^\d{6}$/.test(pin)) {
        res.style.display = 'block';
        res.style.color = '#ef4444';
        res.innerHTML = '<i class="bi bi-x-circle me-1"></i> Please enter a valid 6-digit pincode';
        return;
    }
    res.style.display = 'block';
    res.style.color = '#00285a';
    res.innerHTML = '<i class="bi bi-arrow-repeat co-spin me-1"></i> Checking delivery & speed...';

    fetch('/api/pincode/check?pincode=' + encodeURIComponent(pin))
    .then(r => r.json())
    .then(data => {
        if (!data.success) {
            res.style.color = '#ef4444';
            res.innerHTML = `<i class="bi bi-x-circle me-1"></i> ${data.message || 'Pincode not serviceable'}`;
            return;
        }

        const cityState = (data.city && data.state) ? `<b>${data.city}, ${data.state}</b>` : `<b>${data.pincode}</b>`;
        const codBadge = data.is_cod_allowed
            ? `<span style="color:#16a34a;font-weight:700;"><i class="bi bi-check2-circle"></i> COD Available</span>`
            : `<span style="color:#c2410c;font-weight:700;"><i class="bi bi-slash-circle"></i> Prepaid Only (COD Unavailable)</span>`;

        const returnBadge = data.is_exchange_only
            ? `<span style="color:#b45309;font-weight:700;"><i class="bi bi-arrow-left-right"></i> Size/Color Exchange Only</span>`
            : `<span style="color:#00285a;font-weight:700;"><i class="bi bi-shield-check"></i> 7 Days Free Returns</span>`;

        res.style.color = '#0f172a';
        res.innerHTML = `
            <div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px;padding:12px 14px;margin-top:6px;text-align:left;">
                <div style="font-size:13px;font-weight:800;color:#00285a;display:flex;align-items:center;gap:6px;">
                    <i class="bi bi-truck text-primary"></i> Estimated Delivery: <span>${data.estimated_delivery_date}</span>
                </div>
                <div style="font-size:11.5px;color:#475569;margin-top:3px;">
                    Delivering to ${cityState} in <strong>${data.delivery_days_text}</strong>
                </div>
                <div style="display:flex;align-items:center;gap:12px;margin-top:8px;padding-top:8px;border-top:1px solid #e2e8f0;font-size:11.5px;flex-wrap:wrap;">
                    ${codBadge}
                    <span>&bull;</span>
                    ${returnBadge}
                </div>
            </div>
        `;
    })
    .catch(() => {
        res.style.color = '#16a34a';
        res.innerHTML = `✓ Delivery available to <b>${pin}</b> in <b>3-5 Business Days</b>`;
    });
}
</script>
<script src="{{ asset('frontend/product-show.min.js') }}?v={{ filemtime(public_path('frontend/product-show.min.js')) }}" defer></script>
@endpush

@endsection
