{{-- resources/views/admin/settings/email_templates.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Email Templates & SMTP Settings')

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
        background: linear-gradient(135deg, #0b192e 0%, #0f2b54 50%, #4338ca 100%);
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
        color: #a5b4fc;
        margin-bottom: 12px;
    }
    .pulse-dot-indigo {
        width: 7px;
        height: 7px;
        background: #a5b4fc;
        border-radius: 50%;
        box-shadow: 0 0 0 3px rgba(165, 180, 252, 0.4);
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

    /* Cards */
    .template-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.02);
        margin-bottom: 20px;
    }
    .template-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 18px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f1f5f9;
    }
    .template-icon-box {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #eef2ff;
        color: #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .var-chip {
        display: inline-block;
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
        padding: 2px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        margin: 2px;
        transition: all 0.15s ease;
    }
    .var-chip:hover {
        background: #2563eb;
        color: #ffffff;
    }

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
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.08);
    }
    .form-textarea-custom {
        width: 100%;
        min-height: 120px;
        padding: 10px 13px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 12.5px;
        font-family: monospace;
        color: #0f172a;
        outline: none;
        transition: all 0.15s ease;
        resize: vertical;
    }
    .form-textarea-custom:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.08);
    }

    .btn-save-settings {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
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
        box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
        transition: all 0.15s ease;
    }
    .btn-save-settings:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(79, 70, 229, 0.4);
    }
</style>

<div class="settings-wrap">

    {{-- ── 1. Top Executive Studio Banner ── --}}
    <div class="settings-banner">
        <div class="settings-banner-text">
            <div class="settings-banner-badge">
                <span class="pulse-dot-indigo"></span>
                <span>CUSTOMER COMMUNICATIONS &amp; AUTOMATION</span>
            </div>
            <h1 class="settings-banner-title">Email Templates &amp; SMTP Gateway</h1>
            <p class="settings-banner-desc">
                Customize transactional email messages for order placement, tracking dispatch, delivery confirmations, and welcome journeys.
            </p>
        </div>

        <div class="settings-banner-art">
            <svg width="200" height="120" viewBox="0 0 200 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Isometric Envelope Art -->
                <rect x="30" y="30" width="130" height="75" rx="12" fill="#ffffff" fill-opacity="0.15" stroke="#a5b4fc" stroke-width="1.5" />
                <path d="M30 35L95 80L160 35" stroke="#ffffff" stroke-width="2" stroke-linecap="round" />
                <circle cx="150" cy="35" r="8" fill="#38bdf8" />
                <path d="M147 35L149 37L153 33" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" />
            </svg>
        </div>
    </div>

    {{-- ── 2. Email Templates Form ── --}}
    <form method="POST" action="{{ route('admin.settings.email-templates.update') }}">
        @csrf

        {{-- SMTP Gateway Credentials --}}
        <div class="template-card">
            <div class="template-card-header">
                <div class="template-icon-box">
                    <i class="bi bi-server"></i>
                </div>
                <div>
                    <h2 style="font-size:16px; font-weight:800; color:#0f172a; margin:0;">SMTP Mail Server Configuration</h2>
                    <p style="font-size:11.5px; color:#64748b; margin:2px 0 0;">Connect your custom business domain mail server</p>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-4 form-group-custom">
                    <label class="form-label-custom">Mail Driver / Host</label>
                    <input type="text" name="mail_host" value="{{ $settings['mail_host'] ?? env('MAIL_HOST', 'smtp.mailgun.org') }}" placeholder="smtp.gmail.com" class="form-input-custom">
                </div>
                <div class="col-md-2 form-group-custom">
                    <label class="form-label-custom">SMTP Port</label>
                    <input type="text" name="mail_port" value="{{ $settings['mail_port'] ?? env('MAIL_PORT', '587') }}" placeholder="587" class="form-input-custom">
                </div>
                <div class="col-md-3 form-group-custom">
                    <label class="form-label-custom">SMTP Username</label>
                    <input type="text" name="mail_username" value="{{ $settings['mail_username'] ?? '' }}" placeholder="support@store.com" class="form-input-custom">
                </div>
                <div class="col-md-3 form-group-custom">
                    <label class="form-label-custom">SMTP Password</label>
                    <input type="password" name="mail_password" value="{{ $settings['mail_password'] ?? '' }}" placeholder="••••••••••••" class="form-input-custom">
                </div>
                <div class="col-md-6 form-group-custom">
                    <label class="form-label-custom">From Sender Email</label>
                    <input type="email" name="mail_from_address" value="{{ $settings['mail_from_address'] ?? 'orders@thetrendtheory.com' }}" placeholder="orders@thetrendtheory.com" class="form-input-custom">
                </div>
                <div class="col-md-6 form-group-custom">
                    <label class="form-label-custom">From Sender Name</label>
                    <input type="text" name="mail_from_name" value="{{ $settings['mail_from_name'] ?? 'THE TREND THEORY' }}" placeholder="THE TREND THEORY" class="form-input-custom">
                </div>
            </div>
        </div>

        {{-- Dynamic Variables Legend --}}
        <div class="p-3 bg-light rounded-3 border mb-3">
            <strong class="font-xs text-navy d-block mb-1"><i class="bi bi-code-slash me-1"></i> Available Dynamic Variables (Click to copy/use):</strong>
            <div>
                <span class="var-chip">{customer_name}</span>
                <span class="var-chip">{order_id}</span>
                <span class="var-chip">{order_total}</span>
                <span class="var-chip">{payment_status}</span>
                <span class="var-chip">{tracking_url}</span>
                <span class="var-chip">{courier_name}</span>
                <span class="var-chip">{store_name}</span>
            </div>
        </div>

        <div class="row g-3">

            {{-- 1. Order Placed Email --}}
            <div class="col-md-6">
                <div class="template-card h-100">
                    <div class="template-card-header">
                        <div class="template-icon-box" style="background:#ecfdf5; color:#059669;">
                            <i class="bi bi-bag-check-fill"></i>
                        </div>
                        <div>
                            <h3 style="font-size:15px; font-weight:800; color:#0f172a; margin:0;">Order Placed Confirmation</h3>
                            <p style="font-size:11.5px; color:#64748b; margin:2px 0 0;">Sent immediately after customer places order</p>
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Email Subject Line</label>
                        <input type="text" name="email_order_placed_subject" value="{{ $settings['email_order_placed_subject'] ?? 'Order Confirmed #{order_id} - Thank you for shopping with {store_name}!' }}" class="form-input-custom">
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Message Body (HTML / Markdown supported)</label>
                        <textarea name="email_order_placed_body" class="form-textarea-custom">{{ $settings['email_order_placed_body'] ?? "Hi {customer_name},\n\nThank you for your order #{order_id}! We are preparing your apparel package for dispatch.\n\nTotal Paid: ₹{order_total}\nPayment Status: {payment_status}\n\nWe will send you tracking updates as soon as your parcel ships.\n\nWarm regards,\n{store_name} Studio Team" }}</textarea>
                    </div>
                </div>
            </div>

            {{-- 2. Order Shipped Email --}}
            <div class="col-md-6">
                <div class="template-card h-100">
                    <div class="template-card-header">
                        <div class="template-icon-box" style="background:#eff6ff; color:#2563eb;">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div>
                            <h3 style="font-size:15px; font-weight:800; color:#0f172a; margin:0;">Order Dispatched &amp; In-Transit</h3>
                            <p style="font-size:11.5px; color:#64748b; margin:2px 0 0;">Sent when AWB tracking is generated</p>
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Email Subject Line</label>
                        <input type="text" name="email_order_shipped_subject" value="{{ $settings['email_order_shipped_subject'] ?? 'Your Order #{order_id} has been Shipped via {courier_name}!' }}" class="form-input-custom">
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Message Body</label>
                        <textarea name="email_order_shipped_body" class="form-textarea-custom">{{ $settings['email_order_shipped_body'] ?? "Hi {customer_name},\n\nGreat news! Your order #{order_id} is on its way via {courier_name}.\n\nYou can track live parcel journey here:\n{tracking_url}\n\nThank you for styling with {store_name}!" }}</textarea>
                    </div>
                </div>
            </div>

            {{-- 3. Order Delivered Email --}}
            <div class="col-md-6">
                <div class="template-card h-100">
                    <div class="template-card-header">
                        <div class="template-icon-box" style="background:#faf5ff; color:#9333ea;">
                            <i class="bi bi-house-check-fill"></i>
                        </div>
                        <div>
                            <h3 style="font-size:15px; font-weight:800; color:#0f172a; margin:0;">Order Delivered &amp; Review</h3>
                            <p style="font-size:11.5px; color:#64748b; margin:2px 0 0;">Sent after package is marked delivered</p>
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Email Subject Line</label>
                        <input type="text" name="email_order_delivered_subject" value="{{ $settings['email_order_delivered_subject'] ?? 'Delivered: Your package from {store_name} has arrived!' }}" class="form-input-custom">
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Message Body</label>
                        <textarea name="email_order_delivered_body" class="form-textarea-custom">{{ $settings['email_order_delivered_body'] ?? "Hi {customer_name},\n\nYour order #{order_id} has been delivered!\n\nWe hope you love your new pieces. Tag us on Instagram @thetrendtheory to get featured.\n\nWith love,\n{store_name}" }}</textarea>
                    </div>
                </div>
            </div>

            {{-- 4. Welcome Email --}}
            <div class="col-md-6">
                <div class="template-card h-100">
                    <div class="template-card-header">
                        <div class="template-icon-box" style="background:#fffbeb; color:#d97706;">
                            <i class="bi bi-stars"></i>
                        </div>
                        <div>
                            <h3 style="font-size:15px; font-weight:800; color:#0f172a; margin:0;">Customer Welcome Journey</h3>
                            <p style="font-size:11.5px; color:#64748b; margin:2px 0 0;">Sent upon new user registration</p>
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Email Subject Line</label>
                        <input type="text" name="email_welcome_subject" value="{{ $settings['email_welcome_subject'] ?? 'Welcome to THE TREND THEORY Club &bull; Enjoy your exclusive wardrobe!' }}" class="form-input-custom">
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Message Body</label>
                        <textarea name="email_welcome_body" class="form-textarea-custom">{{ $settings['email_welcome_body'] ?? "Hello {customer_name},\n\nWelcome to {store_name}! We are thrilled to have you in our fashion inner circle.\n\nExplore curated collections, high-street essentials, and exclusive seasonal releases.\n\nHappy styling!\n{store_name} Team" }}</textarea>
                    </div>
                </div>
            </div>

        </div>

        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn-save-settings">
                <i class="bi bi-envelope-check"></i> Save Email Templates
            </button>
        </div>
    </form>

</div>
@endsection
