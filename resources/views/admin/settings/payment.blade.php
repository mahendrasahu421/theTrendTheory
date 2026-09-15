{{-- resources/views/admin/settings/payment.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Payment Gateway Settings')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

    .settings-wrap {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        display: flex;
        flex-direction: column;
        gap: 24px;
        color: #1e293b;
        max-width: 1440px;
        margin: 0 auto;
        padding-bottom: 40px;
    }

    /* 1. Studio Banner */
    .settings-banner {
        background: linear-gradient(135deg, #0b192e 0%, #0f2b54 50%, #1e3a8a 100%);
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
        color: #60a5fa;
        margin-bottom: 12px;
    }
    .pulse-dot-blue {
        width: 7px;
        height: 7px;
        background: #60a5fa;
        border-radius: 50%;
        box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.4);
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

    /* 2. Primary Gateway Selector Card */
    .primary-gateway-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 18px;
        padding: 24px 28px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.02);
    }
    .primary-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
    }
    .primary-card-title {
        font-size: 17px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 4px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .primary-card-desc {
        font-size: 12.5px;
        color: #64748b;
        margin: 0;
    }
    .active-pill-badge {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
        font-size: 11.5px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* 3. Primary Choice Grid */
    .primary-choice-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }
    @media (max-width: 900px) {
        .primary-choice-grid {
            grid-template-columns: 1fr;
        }
    }

    .gateway-choice-card {
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px 20px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #ffffff;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 120px;
    }
    .gateway-choice-card:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
    }
    .gateway-choice-card.is-selected-phonepe {
        border-color: #7c3aed;
        background: #faf5ff;
        box-shadow: 0 6px 20px rgba(124, 58, 237, 0.12);
    }
    .gateway-choice-card.is-selected-razorpay {
        border-color: #0284c7;
        background: #f0f9ff;
        box-shadow: 0 6px 20px rgba(2, 132, 199, 0.12);
    }
    .gateway-choice-card.is-selected-both {
        border-color: #059669;
        background: #f0fdf4;
        box-shadow: 0 6px 20px rgba(5, 150, 105, 0.12);
    }

    .choice-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }
    .choice-brand-tag {
        font-size: 11px;
        font-weight: 800;
        padding: 3px 10px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .tag-phonepe { background: #5f259f; color: #ffffff; }
    .tag-razorpay { background: #0284c7; color: #ffffff; }
    .tag-both { background: #059669; color: #ffffff; }

    .choice-radio-circle {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid #cbd5e1;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
    }
    .is-selected-phonepe .choice-radio-circle { border-color: #7c3aed; background: #7c3aed; }
    .is-selected-razorpay .choice-radio-circle { border-color: #0284c7; background: #0284c7; }
    .is-selected-both .choice-radio-circle { border-color: #059669; background: #059669; }
    .choice-radio-inner {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #ffffff;
    }

    .choice-title {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 3px;
    }
    .choice-sub {
        font-size: 12px;
        color: #64748b;
        margin: 0;
        line-height: 1.35;
    }

    /* 4. Gateway Cards Grid (2-Columns Bento) */
    .gateway-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 24px;
    }
    @media (max-width: 960px) {
        .gateway-grid { grid-template-columns: 1fr; }
    }

    .gateway-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 18px;
        padding: 24px 26px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.02);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.2s ease;
    }
    .gateway-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
    }

    .gateway-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f1f5f9;
    }
    .gateway-logo-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .gateway-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .icon-razorpay { background: #eff6ff; color: #0284c7; }
    .icon-cod { background: #ecfdf5; color: #059669; }
    .icon-phonepe { background: #faf5ff; color: #7c3aed; }
    .icon-stripe { background: #f0fdf4; color: #16a34a; }

    .gateway-title-text {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
    }
    .gateway-sub-text {
        font-size: 11.5px;
        color: #64748b;
        margin: 2px 0 0;
    }

    /* iOS Toggle Switch */
    .ios-switch {
        position: relative;
        display: inline-block;
        width: 44px;
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
    input:checked + .ios-slider { background-color: #2563eb; }
    input:checked + .ios-slider:before { transform: translateX(20px); }

    /* Custom Form Layouts */
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
        box-sizing: border-box;
    }
    .form-input-custom:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
    }

    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }
    .form-grid-8-4 {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 14px;
    }
    @media (max-width: 540px) {
        .form-grid-2, .form-grid-8-4 {
            grid-template-columns: 1fr;
        }
    }

    /* Mode Box Strip */
    .mode-box-strip {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 14px;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #edf2f7;
        margin-top: 10px;
    }

    /* Save Button Bar */
    .save-actions-bar {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-top: 10px;
    }
    .btn-save-settings {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #ffffff;
        border: none;
        padding: 13px 32px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 6px 18px rgba(37, 99, 235, 0.3);
        transition: all 0.15s ease;
    }
    .btn-save-settings:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 22px rgba(37, 99, 235, 0.4);
    }
</style>

<div class="settings-wrap">

    {{-- ── 1. Top Executive Studio Banner ── --}}
    <div class="settings-banner">
        <div class="settings-banner-text">
            <div class="settings-banner-badge">
                <span class="pulse-dot-blue"></span>
                <span>FINANCIAL &amp; CHECKOUT INFRASTRUCTURE</span>
            </div>
            <h1 class="settings-banner-title">Payment Gateways &amp; Methods</h1>
            <p class="settings-banner-desc">
                Configure UPI, PhonePe, Razorpay, Cash on Delivery (COD), and International cards for seamless customer checkout transactions.
            </p>
        </div>

        <div class="settings-banner-art">
            <svg width="200" height="120" viewBox="0 0 200 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="25" y="20" width="120" height="75" rx="12" fill="#ffffff" fill-opacity="0.15" stroke="#60a5fa" stroke-width="1.5" />
                <rect x="35" y="35" width="22" height="15" rx="4" fill="#fbbf24" />
                <rect x="35" y="65" width="60" height="5" rx="2.5" fill="#ffffff" fill-opacity="0.6" />
                <rect x="35" y="75" width="40" height="4" rx="2" fill="#ffffff" fill-opacity="0.4" />
                
                <circle cx="120" cy="55" r="12" fill="#38bdf8" fill-opacity="0.4" />
                <circle cx="132" cy="55" r="12" fill="#60a5fa" fill-opacity="0.4" />
                
                <rect x="135" y="50" width="45" height="50" rx="10" fill="#ffffff" fill-opacity="0.25" stroke="#38bdf8" stroke-width="1.5" />
                <path d="M157 65L152 70L148 66" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </div>

    {{-- ── 2. Gateways Settings Form ── --}}
    <form method="POST" action="{{ route('admin.settings.payment.update') }}">
        @csrf

        @php
            $primaryGateway = $settings['primary_payment_gateway'] ?? 'phonepe';
        @endphp

        {{-- ── 2.1 Primary Active Payment Gateway Selector ── --}}
        <div class="primary-gateway-card">
            <div class="primary-card-header">
                <div>
                    <h2 class="primary-card-title">🎯 Default Active Payment Gateway</h2>
                    <p class="primary-card-desc">Select which gateway triggers automatically when customers tap <strong>"Proceed to Pay"</strong> at checkout.</p>
                </div>
                <div class="active-pill-badge">
                    <i class="bi bi-shuffle"></i> Dynamic Switcher Active
                </div>
            </div>

            <div class="primary-choice-grid">
                {{-- Option 1: PhonePe --}}
                <div class="gateway-choice-card {{ $primaryGateway === 'phonepe' ? 'is-selected-phonepe' : '' }}" onclick="selectPrimaryGateway('phonepe')">
                    <input type="radio" name="primary_payment_gateway" id="radio_phonepe" value="phonepe" @checked($primaryGateway === 'phonepe') style="display:none;">
                    <div class="choice-card-top">
                        <span class="choice-brand-tag tag-phonepe">🟣 PhonePe (v2)</span>
                        <div class="choice-radio-circle">
                            @if($primaryGateway === 'phonepe') <div class="choice-radio-inner"></div> @endif
                        </div>
                    </div>
                    <div>
                        <div class="choice-title">PhonePe Direct Gateway</div>
                        <p class="choice-sub">Fastest UPI QR, PhonePe App, NetBanking &amp; Cards</p>
                    </div>
                </div>

                {{-- Option 2: Razorpay --}}
                <div class="gateway-choice-card {{ $primaryGateway === 'razorpay' ? 'is-selected-razorpay' : '' }}" onclick="selectPrimaryGateway('razorpay')">
                    <input type="radio" name="primary_payment_gateway" id="radio_razorpay" value="razorpay" @checked($primaryGateway === 'razorpay') style="display:none;">
                    <div class="choice-card-top">
                        <span class="choice-brand-tag tag-razorpay">🔵 Razorpay</span>
                        <div class="choice-radio-circle">
                            @if($primaryGateway === 'razorpay') <div class="choice-radio-inner"></div> @endif
                        </div>
                    </div>
                    <div>
                        <div class="choice-title">Razorpay Standard</div>
                        <p class="choice-sub">Credit/Debit Cards, UPI, Wallets &amp; NetBanking</p>
                    </div>
                </div>

                {{-- Option 3: Both (Multi-Gateway) --}}
                <div class="gateway-choice-card {{ $primaryGateway === 'both' ? 'is-selected-both' : '' }}" onclick="selectPrimaryGateway('both')">
                    <input type="radio" name="primary_payment_gateway" id="radio_both" value="both" @checked($primaryGateway === 'both') style="display:none;">
                    <div class="choice-card-top">
                        <span class="choice-brand-tag tag-both">⚡ Multi-Gateway</span>
                        <div class="choice-radio-circle">
                            @if($primaryGateway === 'both') <div class="choice-radio-inner"></div> @endif
                        </div>
                    </div>
                    <div>
                        <div class="choice-title">Multi-Gateway Mode</div>
                        <p class="choice-sub">Display both PhonePe &amp; Razorpay options to customer</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── 2.2 Gateways Configuration Grid ── --}}
        <div class="gateway-grid">

            {{-- 1. PhonePe / UPI Gateway --}}
            <div class="gateway-card">
                <div>
                    <div class="gateway-header">
                        <div class="gateway-logo-title">
                            <div class="gateway-icon-box icon-phonepe">
                                <i class="bi bi-phone-fill"></i>
                            </div>
                            <div>
                                <h2 class="gateway-title-text">PhonePe / UPI Gateway</h2>
                                <p class="gateway-sub-text">Direct QR, UPI Apps &amp; Card payments</p>
                            </div>
                        </div>
                        <label class="ios-switch" title="Enable/Disable PhonePe">
                            <input type="checkbox" name="phonepe_enabled" value="1" @checked($settings['phonepe_enabled'] ?? '1' == '1')>
                            <span class="ios-slider"></span>
                        </label>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group-custom">
                            <label class="form-label-custom">Merchant ID / Client ID</label>
                            <input type="text" name="phonepe_merchant_id" value="{{ $settings['phonepe_merchant_id'] ?? '' }}" placeholder="PGTESTPAYUAT..." class="form-input-custom">
                        </div>
                        <div class="form-group-custom">
                            <label class="form-label-custom">Client Version</label>
                            <input type="text" name="phonepe_client_version" value="{{ $settings['phonepe_client_version'] ?? '1' }}" placeholder="1" class="form-input-custom">
                        </div>
                    </div>

                    <div class="form-grid-8-4">
                        <div class="form-group-custom">
                            <label class="form-label-custom">Salt Key / Client Secret</label>
                            <input type="password" name="phonepe_salt_key" value="{{ $settings['phonepe_salt_key'] ?? '' }}" placeholder="••••••••••••••••" class="form-input-custom">
                        </div>
                        <div class="form-group-custom">
                            <label class="form-label-custom">Salt Index</label>
                            <input type="number" name="phonepe_salt_index" value="{{ $settings['phonepe_salt_index'] ?? '1' }}" placeholder="1" class="form-input-custom">
                        </div>
                    </div>

                    <div class="mode-box-strip">
                        <div>
                            <strong style="font-size:12px; color:#0f172a; display:block;">UAT / Sandbox Mode (PhonePe Simulator)</strong>
                            <span style="font-size:11px; color:#64748b;">Simulate test transactions before going live</span>
                        </div>
                        <input type="checkbox" name="phonepe_sandbox" value="1" @checked($settings['phonepe_sandbox'] ?? '1' == '1') style="width:17px; height:17px; cursor:pointer;">
                    </div>
                </div>
            </div>

            {{-- 2. Razorpay Payments --}}
            <div class="gateway-card">
                <div>
                    <div class="gateway-header">
                        <div class="gateway-logo-title">
                            <div class="gateway-icon-box icon-razorpay">
                                <i class="bi bi-credit-card-2-front-fill"></i>
                            </div>
                            <div>
                                <h2 class="gateway-title-text">Razorpay Payments</h2>
                                <p class="gateway-sub-text">Accept UPI, Credit/Debit Cards, Net Banking</p>
                            </div>
                        </div>
                        <label class="ios-switch" title="Enable/Disable Razorpay">
                            <input type="checkbox" name="razorpay_enabled" value="1" @checked($settings['razorpay_enabled'] ?? '1' == '1')>
                            <span class="ios-slider"></span>
                        </label>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">
                            <span>Key ID</span>
                            <span class="badge bg-light text-muted font-xs">Required</span>
                        </label>
                        <input type="text" name="razorpay_key_id" value="{{ $settings['razorpay_key_id'] ?? '' }}" placeholder="rzp_live_..." class="form-input-custom">
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">
                            <span>Key Secret</span>
                            <span class="badge bg-light text-muted font-xs">Secret</span>
                        </label>
                        <input type="password" name="razorpay_key_secret" value="{{ $settings['razorpay_key_secret'] ?? '' }}" placeholder="••••••••••••••••" class="form-input-custom">
                    </div>

                    <div class="mode-box-strip">
                        <div>
                            <strong style="font-size:12px; color:#0f172a; display:block;">Sandbox / Test Mode</strong>
                            <span style="font-size:11px; color:#64748b;">Use for test mode payments</span>
                        </div>
                        <input type="checkbox" name="razorpay_sandbox" value="1" @checked($settings['razorpay_sandbox'] ?? '0' == '1') style="width:17px; height:17px; cursor:pointer;">
                    </div>
                </div>
            </div>

            {{-- 3. Cash on Delivery (COD) --}}
            <div class="gateway-card">
                <div>
                    <div class="gateway-header">
                        <div class="gateway-logo-title">
                            <div class="gateway-icon-box icon-cod">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                            <div>
                                <h2 class="gateway-title-text">Cash on Delivery (COD)</h2>
                                <p class="gateway-sub-text">Allow doorstep cash payment on delivery</p>
                            </div>
                        </div>
                        <label class="ios-switch" title="Enable/Disable COD">
                            <input type="checkbox" name="cod_enabled" value="1" @checked($settings['cod_enabled'] ?? '1' == '1')>
                            <span class="ios-slider"></span>
                        </label>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">
                            <span>COD Handling Charge (₹)</span>
                            <span class="badge bg-light text-muted font-xs">Optional</span>
                        </label>
                        <input type="number" name="cod_fee" value="{{ $settings['cod_fee'] ?? '0' }}" placeholder="0" class="form-input-custom">
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group-custom">
                            <label class="form-label-custom">Min Order Value (₹)</label>
                            <input type="number" name="cod_min_order" value="{{ $settings['cod_min_order'] ?? '0' }}" placeholder="0" class="form-input-custom">
                        </div>
                        <div class="form-group-custom">
                            <label class="form-label-custom">Max Order Value (₹)</label>
                            <input type="number" name="cod_max_order" value="{{ $settings['cod_max_order'] ?? '10000' }}" placeholder="10000" class="form-input-custom">
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-3 border font-xs text-muted" style="font-size:11.5px; line-height:1.4;">
                        <i class="bi bi-info-circle-fill text-primary me-1"></i>
                        Orders exceeding maximum order value will automatically require online prepayment.
                    </div>
                </div>
            </div>

            {{-- 4. Stripe International --}}
            <div class="gateway-card">
                <div>
                    <div class="gateway-header">
                        <div class="gateway-logo-title">
                            <div class="gateway-icon-box icon-stripe">
                                <i class="bi bi-globe2"></i>
                            </div>
                            <div>
                                <h2 class="gateway-title-text">Stripe (International)</h2>
                                <p class="gateway-sub-text">Accept USD, EUR, GBP from global buyers</p>
                            </div>
                        </div>
                        <label class="ios-switch" title="Enable/Disable Stripe">
                            <input type="checkbox" name="stripe_enabled" value="1" @checked($settings['stripe_enabled'] ?? '0' == '1')>
                            <span class="ios-slider"></span>
                        </label>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Publishable Key</label>
                        <input type="text" name="stripe_publishable_key" value="{{ $settings['stripe_publishable_key'] ?? '' }}" placeholder="pk_live_..." class="form-input-custom">
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Secret Key</label>
                        <input type="password" name="stripe_secret_key" value="{{ $settings['stripe_secret_key'] ?? '' }}" placeholder="sk_live_..." class="form-input-custom">
                    </div>

                    <div class="mode-box-strip">
                        <div>
                            <strong style="font-size:12px; color:#0f172a; display:block;">Stripe Test Mode</strong>
                            <span style="font-size:11px; color:#64748b;">Use pk_test / sk_test keys</span>
                        </div>
                        <input type="checkbox" name="stripe_sandbox" value="1" @checked($settings['stripe_sandbox'] ?? '1' == '1') style="width:17px; height:17px; cursor:pointer;">
                    </div>
                </div>
            </div>

        </div>

        {{-- ── 2.3 Save Button ── --}}
        <div class="save-actions-bar" style="margin-top: 24px;">
            <button type="submit" class="btn-save-settings">
                <i class="bi bi-check-circle-fill"></i> Save Payment Gateways
            </button>
        </div>

    </form>
</div>

<script>
function selectPrimaryGateway(val) {
    var radioPhonepe = document.getElementById('radio_phonepe');
    var radioRazorpay = document.getElementById('radio_razorpay');
    var radioBoth = document.getElementById('radio_both');

    if (val === 'phonepe' && radioPhonepe) radioPhonepe.checked = true;
    if (val === 'razorpay' && radioRazorpay) radioRazorpay.checked = true;
    if (val === 'both' && radioBoth) radioBoth.checked = true;

    document.querySelectorAll('.gateway-choice-card').forEach(function(card) {
        card.classList.remove('is-selected-phonepe', 'is-selected-razorpay', 'is-selected-both');
        var inner = card.querySelector('.choice-radio-inner');
        if (inner) inner.remove();
    });

    var selectedCard = event.currentTarget;
    if (selectedCard) {
        selectedCard.classList.add('is-selected-' + val);
        var circle = selectedCard.querySelector('.choice-radio-circle');
        if (circle && !circle.querySelector('.choice-radio-inner')) {
            var dot = document.createElement('div');
            dot.className = 'choice-radio-inner';
            circle.appendChild(dot);
        }
    }
}
</script>
@endsection
