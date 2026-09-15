{{-- resources/views/admin/settings/shipping.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Shipping & Delivery Settings')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .settings-wrap {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        display: flex;
        flex-direction: column;
        gap: 20px;
        color: #1e293b;
        max-width: 1440px;
        margin: 0 auto;
    }

    /* 1. Studio Banner */
    .settings-banner {
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
    .settings-banner-text {
        position: relative;
        z-index: 2;
        max-width: 620px;
    }
    .settings-banner-badge {
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
    .settings-banner-title {
        font-size: 26px;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin: 0 0 8px;
        color: #ffffff;
    }
    .settings-banner-desc {
        font-size: 13.5px;
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.85);
        margin: 0;
    }
    .settings-banner-art {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    @media (max-width: 900px) {
        .settings-banner-art { display: none; }
    }

    /* 2. Form Grid */
    .shipping-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }
    @media (max-width: 900px) {
        .shipping-grid { grid-template-columns: 1fr; }
    }

    .shipping-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.02);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.2s ease;
    }
    .shipping-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
    }

    .shipping-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f1f5f9;
    }
    .shipping-logo-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .shipping-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .icon-rates { background: #eff6ff; color: #0284c7; }
    .icon-shiprocket { background: #faf5ff; color: #9333ea; }
    .icon-delhivery { background: #fef2f2; color: #dc2626; }
    .icon-intl { background: #ecfdf5; color: #059669; }

    .shipping-title-text {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
    }
    .shipping-sub-text {
        font-size: 11.5px;
        color: #64748b;
        margin: 2px 0 0;
    }

    /* iOS Switch */
    .ios-switch {
        position: relative;
        display: inline-block;
        width: 42px;
        height: 24px;
    }
    .ios-switch input { opacity: 0; width: 0; height: 0; }
    .ios-slider {
        position: absolute; cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #cbd5e1;
        transition: .2s;
        border-radius: 24px;
    }
    .ios-slider:before {
        position: absolute; content: "";
        height: 18px; width: 18px;
        left: 3px; bottom: 3px;
        background-color: white;
        transition: .2s;
        border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    input:checked + .ios-slider { background-color: #0284c7; }
    input:checked + .ios-slider:before { transform: translateX(18px); }

    /* Input Groups */
    .form-group-custom {
        margin-bottom: 16px;
    }
    .form-label-custom {
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .form-input-custom {
        width: 100%;
        padding: 9px 13px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 13px;
        font-family: inherit;
        color: #0f172a;
        outline: none;
        transition: all 0.15s ease;
    }
    .form-input-custom:focus {
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.08);
    }

    .btn-save-settings {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff;
        border: none;
        padding: 12px 28px;
        border-radius: 12px;
        font-size: 13.5px;
        font-weight: 800;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 14px rgba(2, 132, 199, 0.3);
        transition: all 0.15s ease;
    }
    .btn-save-settings:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(2, 132, 199, 0.4);
    }
</style>

<div class="settings-wrap">

    {{-- ── 1. Top Executive Studio Banner ── --}}
    <div class="settings-banner">
        <div class="settings-banner-text">
            <div class="settings-banner-badge">
                <span class="pulse-dot-cyan"></span>
                <span>LOGISTICS &amp; FULFILLMENT RULES</span>
            </div>
            <h1 class="settings-banner-title">Shipping &amp; Delivery Settings</h1>
            <p class="settings-banner-desc">
                Set domestic delivery fees, free shipping cart thresholds, express shipping rates, and automate courier API dispatch via Shiprocket or Delhivery.
            </p>
        </div>

        <div class="settings-banner-art">
            <svg width="200" height="120" viewBox="0 0 200 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Isometric Delivery Van Art -->
                <rect x="25" y="45" width="85" height="50" rx="8" fill="#ffffff" fill-opacity="0.15" stroke="#38bdf8" stroke-width="1.5" />
                <path d="M110 55H140L155 75V95H110V55Z" fill="#ffffff" fill-opacity="0.25" stroke="#38bdf8" stroke-width="1.5" />
                <circle cx="55" cy="95" r="10" fill="#0f172a" stroke="#38bdf8" stroke-width="2" />
                <circle cx="135" cy="95" r="10" fill="#0f172a" stroke="#38bdf8" stroke-width="2" />
                <rect x="40" y="58" width="30" height="6" rx="2" fill="#38bdf8" />
                <rect x="40" y="70" width="50" height="4" rx="2" fill="#ffffff" fill-opacity="0.5" />
            </svg>
        </div>
    </div>

    {{-- ── 2. Shipping Settings Form ── --}}
    <form method="POST" action="{{ route('admin.settings.shipping.update') }}">
        @csrf

        <div class="shipping-grid">

            {{-- Domestic Shipping Rates --}}
            <div class="shipping-card">
                <div>
                    <div class="shipping-header">
                        <div class="shipping-logo-title">
                            <div class="shipping-icon-box icon-rates">
                                <i class="bi bi-truck"></i>
                            </div>
                            <div>
                                <h2 class="shipping-title-text">Domestic Shipping Rates</h2>
                                <p class="shipping-sub-text">Standard delivery fees &amp; free thresholds</p>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-6 form-group-custom">
                            <label class="form-label-custom">Standard Delivery (₹)</label>
                            <input type="number" name="shipping_rate" value="{{ $settings['shipping_rate'] ?? '99' }}" placeholder="99" class="form-input-custom">
                        </div>
                        <div class="col-6 form-group-custom">
                            <label class="form-label-custom">Free Shipping Above (₹)</label>
                            <input type="number" name="shipping_free_above" value="{{ $settings['shipping_free_above'] ?? '999' }}" placeholder="999" class="form-input-custom">
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Standard Delivery Estimated Days</label>
                        <input type="text" name="standard_shipping_days" value="{{ $settings['standard_shipping_days'] ?? '3 - 5 business days' }}" placeholder="3 - 5 business days" class="form-input-custom">
                    </div>

                    <div class="p-3 bg-light rounded-3 border mt-2 font-xs text-muted">
                        <i class="bi bi-tag-fill text-success me-1"></i>
                        Cart total exceeding <strong>₹{{ $settings['shipping_free_above'] ?? '999' }}</strong> will automatically qualify for Free Standard Delivery at checkout.
                    </div>
                </div>
            </div>

            {{-- Express / Priority Shipping --}}
            <div class="shipping-card">
                <div>
                    <div class="shipping-header">
                        <div class="shipping-logo-title">
                            <div class="shipping-icon-box icon-intl">
                                <i class="bi bi-lightning-charge-fill"></i>
                            </div>
                            <div>
                                <h2 class="shipping-title-text">Express Priority Delivery</h2>
                                <p class="shipping-sub-text">Fast 1-2 day express courier option</p>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-6 form-group-custom">
                            <label class="form-label-custom">Express Shipping Fee (₹)</label>
                            <input type="number" name="express_shipping_rate" value="{{ $settings['express_shipping_rate'] ?? '199' }}" placeholder="199" class="form-input-custom">
                        </div>
                        <div class="col-6 form-group-custom">
                            <label class="form-label-custom">Express Timeline</label>
                            <input type="text" name="express_shipping_days" value="{{ $settings['express_shipping_days'] ?? '1 - 2 business days' }}" placeholder="1 - 2 business days" class="form-input-custom">
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light border mt-2">
                        <div>
                            <strong class="font-xs text-navy d-block">International Shipping</strong>
                            <span class="text-muted font-xs">Allow cross-border international orders</span>
                        </div>
                        <input type="checkbox" name="intl_shipping_enabled" value="1" @checked($settings['intl_shipping_enabled'] ?? '0' == '1') class="form-check-input">
                    </div>

                    <div class="form-group-custom mt-3">
                        <label class="form-label-custom">International Flat Shipping Fee (₹)</label>
                        <input type="number" name="intl_shipping_rate" value="{{ $settings['intl_shipping_rate'] ?? '1499' }}" placeholder="1499" class="form-input-custom">
                    </div>
                </div>
            </div>

            {{-- Shiprocket Courier Integration --}}
            <div class="shipping-card">
                <div>
                    <div class="shipping-header">
                        <div class="shipping-logo-title">
                            <div class="shipping-icon-box icon-shiprocket">
                                <i class="bi bi-box2-heart-fill"></i>
                            </div>
                            <div>
                                <h2 class="shipping-title-text">Shiprocket Integration</h2>
                                <p class="shipping-sub-text">Automated AWB generation &amp; pickup dispatch</p>
                            </div>
                        </div>
                        <label class="ios-switch" title="Enable Shiprocket">
                            <input type="checkbox" name="shiprocket_enabled" value="1" @checked($settings['shiprocket_enabled'] ?? '0' == '1')>
                            <span class="ios-slider"></span>
                        </label>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Shiprocket Account Email</label>
                        <input type="email" name="shiprocket_email" value="{{ $settings['shiprocket_email'] ?? '' }}" placeholder="account@store.com" class="form-input-custom">
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Shiprocket Password / Token</label>
                        <input type="password" name="shiprocket_password" value="{{ $settings['shiprocket_password'] ?? '' }}" placeholder="••••••••••••" class="form-input-custom">
                    </div>
                </div>
            </div>

            {{-- Delhivery Direct Integration --}}
            <div class="shipping-card">
                <div>
                    <div class="shipping-header">
                        <div class="shipping-logo-title">
                            <div class="shipping-icon-box icon-delhivery">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <h2 class="shipping-title-text">Delhivery Direct API</h2>
                                <p class="shipping-sub-text">Surface &amp; Express parcel tracking</p>
                            </div>
                        </div>
                        <label class="ios-switch" title="Enable Delhivery">
                            <input type="checkbox" name="delhivery_enabled" value="1" @checked($settings['delhivery_enabled'] ?? '0' == '1')>
                            <span class="ios-slider"></span>
                        </label>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Delhivery Client ID / Warehouse Name</label>
                        <input type="text" name="delhivery_client_id" value="{{ $settings['delhivery_client_id'] ?? '' }}" placeholder="THE_TREND_HUB" class="form-input-custom">
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">API Token</label>
                        <input type="password" name="delhivery_api_token" value="{{ $settings['delhivery_api_token'] ?? '' }}" placeholder="••••••••••••" class="form-input-custom">
                    </div>
                </div>
            </div>

        </div>

        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn-save-settings">
                <i class="bi bi-truck-flatbed"></i> Save Shipping &amp; Logistics
            </button>
        </div>
    </form>

</div>
@endsection
