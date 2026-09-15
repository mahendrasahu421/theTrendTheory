{{-- resources/views/admin/settings/sms.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'SMS & WhatsApp Gateway Settings')

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
        background: linear-gradient(135deg, #0b192e 0%, #0f2b54 50%, #059669 100%);
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
        color: #6ee7b7;
        margin-bottom: 12px;
    }
    .pulse-dot-emerald {
        width: 7px;
        height: 7px;
        background: #6ee7b7;
        border-radius: 50%;
        box-shadow: 0 0 0 3px rgba(110, 231, 183, 0.4);
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

    /* Cards Grid */
    .sms-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }
    @media (max-width: 900px) {
        .sms-grid { grid-template-columns: 1fr; }
    }

    .sms-card {
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
    .sms-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
    }

    .sms-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f1f5f9;
    }
    .sms-logo-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .sms-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .icon-sms { background: #ecfdf5; color: #059669; }
    .icon-wa { background: #f0fdf4; color: #16a34a; }
    .icon-triggers { background: #eff6ff; color: #2563eb; }

    .sms-title-text {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
    }
    .sms-sub-text {
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
    input:checked + .ios-slider { background-color: #059669; }
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
        display: block;
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
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.08);
    }
    .form-textarea-custom {
        width: 100%;
        min-height: 80px;
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 12px;
        font-family: inherit;
        color: #0f172a;
        outline: none;
        transition: all 0.15s ease;
    }
    .form-textarea-custom:focus {
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.08);
    }

    .btn-save-settings {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
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
        box-shadow: 0 4px 14px rgba(5, 150, 105, 0.3);
        transition: all 0.15s ease;
    }
    .btn-save-settings:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(5, 150, 105, 0.4);
    }
</style>

<div class="settings-wrap">

    {{-- ── 1. Top Executive Studio Banner ── --}}
    <div class="settings-banner">
        <div class="settings-banner-text">
            <div class="settings-banner-badge">
                <span class="pulse-dot-emerald"></span>
                <span>INSTANT SMS &amp; WHATSAPP ALERTS</span>
            </div>
            <h1 class="settings-banner-title">SMS &amp; WhatsApp Gateway</h1>
            <p class="settings-banner-desc">
                Broadcast instant OTPs, order confirmation SMS, dispatch notifications, and WhatsApp tracking updates to customers across India.
            </p>
        </div>

        <div class="settings-banner-art">
            <svg width="200" height="120" viewBox="0 0 200 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Isometric Phone & Chat Bubble Art -->
                <rect x="40" y="20" width="60" height="90" rx="10" fill="#ffffff" fill-opacity="0.15" stroke="#6ee7b7" stroke-width="1.5" />
                <rect x="52" y="32" width="36" height="50" rx="4" fill="#ffffff" fill-opacity="0.2" />
                <circle cx="70" cy="98" r="4" fill="#6ee7b7" />
                
                <rect x="90" y="35" width="80" height="45" rx="10" fill="#ffffff" fill-opacity="0.25" stroke="#34d399" stroke-width="1.5" />
                <rect x="102" y="46" width="45" height="5" rx="2" fill="#ffffff" />
                <rect x="102" y="56" width="30" height="4" rx="2" fill="#6ee7b7" />
            </svg>
        </div>
    </div>

    {{-- ── 2. SMS Settings Form ── --}}
    <form method="POST" action="{{ route('admin.settings.sms.update') }}">
        @csrf

        <div class="sms-grid">

            {{-- SMS Gateway Provider --}}
            <div class="sms-card">
                <div>
                    <div class="sms-header">
                        <div class="sms-logo-title">
                            <div class="sms-icon-box icon-sms">
                                <i class="bi bi-chat-left-dots-fill"></i>
                            </div>
                            <div>
                                <h2 class="sms-title-text">SMS Gateway Provider</h2>
                                <p class="sms-sub-text">Fast2SMS, Twilio, MSG91, TextLocal</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Select Active Provider</label>
                        <select name="sms_provider" class="form-input-custom" style="cursor:pointer;">
                            <option value="fast2sms" @selected(($settings['sms_provider'] ?? 'fast2sms') === 'fast2sms')>Fast2SMS (India)</option>
                            <option value="twilio" @selected(($settings['sms_provider'] ?? '') === 'twilio')>Twilio (Global)</option>
                            <option value="msg91" @selected(($settings['sms_provider'] ?? '') === 'msg91')>MSG91</option>
                            <option value="textlocal" @selected(($settings['sms_provider'] ?? '') === 'textlocal')>TextLocal</option>
                        </select>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">API Key / Authorization Header</label>
                        <input type="password" name="sms_api_key" value="{{ $settings['sms_api_key'] ?? '' }}" placeholder="••••••••••••••••" class="form-input-custom">
                    </div>

                    <div class="row g-2">
                        <div class="col-6 form-group-custom">
                            <label class="form-label-custom">Sender ID / DLT Header</label>
                            <input type="text" name="sms_sender_id" value="{{ $settings['sms_sender_id'] ?? 'TRNDTH' }}" placeholder="TRNDTH" class="form-input-custom">
                        </div>
                        <div class="col-6 form-group-custom">
                            <label class="form-label-custom">Account SID (Twilio)</label>
                            <input type="text" name="sms_account_sid" value="{{ $settings['sms_account_sid'] ?? '' }}" placeholder="AC..." class="form-input-custom">
                        </div>
                    </div>
                </div>
            </div>

            {{-- WhatsApp Cloud API --}}
            <div class="sms-card">
                <div>
                    <div class="sms-header">
                        <div class="sms-logo-title">
                            <div class="sms-icon-box icon-wa">
                                <i class="bi bi-whatsapp"></i>
                            </div>
                            <div>
                                <h2 class="sms-title-text">WhatsApp Business Cloud API</h2>
                                <p class="sms-sub-text">Direct Meta Graph API integration</p>
                            </div>
                        </div>
                        <label class="ios-switch" title="Enable WhatsApp">
                            <input type="checkbox" name="whatsapp_enabled" value="1" @checked($settings['whatsapp_enabled'] ?? '0' == '1')>
                            <span class="ios-slider"></span>
                        </label>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Phone Number ID</label>
                        <input type="text" name="whatsapp_phone_number_id" value="{{ $settings['whatsapp_phone_number_id'] ?? '' }}" placeholder="1029384756..." class="form-input-custom">
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">WABA (Business Account ID)</label>
                        <input type="text" name="whatsapp_business_account_id" value="{{ $settings['whatsapp_business_account_id'] ?? '' }}" placeholder="1122334455..." class="form-input-custom">
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Permanent Access Token</label>
                        <input type="password" name="whatsapp_access_token" value="{{ $settings['whatsapp_access_token'] ?? '' }}" placeholder="EAAG..." class="form-input-custom">
                    </div>
                </div>
            </div>

            {{-- Notification Triggers & Templates --}}
            <div class="sms-card" style="grid-column: span 2;">
                <div>
                    <div class="sms-header">
                        <div class="sms-logo-title">
                            <div class="sms-icon-box icon-triggers">
                                <i class="bi bi-bell-fill"></i>
                            </div>
                            <div>
                                <h2 class="sms-title-text">Automated Trigger Events &amp; SMS Templates</h2>
                                <p class="sms-sub-text">Choose when automated SMS messages should fire</p>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3 border h-100">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <strong class="font-xs text-navy">Order Placed SMS</strong>
                                    <input type="checkbox" name="sms_trigger_order_placed" value="1" @checked($settings['sms_trigger_order_placed'] ?? '1' == '1') class="form-check-input">
                                </div>
                                <textarea name="sms_template_order_placed" class="form-textarea-custom">{{ $settings['sms_template_order_placed'] ?? "Hi {customer_name}, your order #{order_id} for ₹{order_total} is confirmed! - Vayu" }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3 border h-100">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <strong class="font-xs text-navy">Order Shipped SMS</strong>
                                    <input type="checkbox" name="sms_trigger_order_shipped" value="1" @checked($settings['sms_trigger_order_shipped'] ?? '1' == '1') class="form-check-input">
                                </div>
                                <textarea name="sms_template_order_shipped" class="form-textarea-custom">{{ $settings['sms_template_order_shipped'] ?? "Your package #{order_id} has shipped via {courier_name}. Track here: {tracking_url} - Vayu" }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3 border h-100">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <strong class="font-xs text-navy">Order Delivered SMS</strong>
                                    <input type="checkbox" name="sms_trigger_order_delivered" value="1" @checked($settings['sms_trigger_order_delivered'] ?? '1' == '1') class="form-check-input">
                                </div>
                                <textarea name="sms_template_order_delivered" class="form-textarea-custom">{{ $settings['sms_template_order_delivered'] ?? "Your order #{order_id} from Vayu has been delivered. Enjoy styling!" }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn-save-settings">
                <i class="bi bi-chat-square-text"></i> Save SMS &amp; WhatsApp Settings
            </button>
        </div>
    </form>

</div>
@endsection
