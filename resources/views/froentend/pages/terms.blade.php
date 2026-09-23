{{-- resources/views/froentend/pages/terms.blade.php --}}
@extends('froentend.layouts.app')

@section('custom_seo')
    <title>Terms &amp; Conditions | THE TREND THEORY — Luxury Streetwear</title>
    <meta name="description" content="Read the official Terms & Conditions of THE TREND THEORY Apparels Pvt. Ltd. regarding orders, shipping, returns, and intellectual property.">
    <link rel="canonical" href="{{ url()->current() }}">
@endsection

@section('main')
<style>
/* ═══════════════════════════════════════════════════════════════════
   THE TREND THEORY — LEGAL POLICY STYLES
   ═══════════════════════════════════════════════════════════════════ */
:root {
    --policy-navy: #00285a;
    --policy-navy-dark: #001838;
    --policy-accent: #ff3f6c;
    --policy-bg: #f8fafc;
    --policy-border: #e2e8f0;
    --policy-text-dark: #0f172a;
    --policy-text-muted: #64748b;
    --policy-font-head: 'Cinzel', serif;
    --policy-font-body: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
}

.policy-page-wrap {
    background: #f8fafc;
    min-height: 80vh;
    padding: 40px 20px 80px;
    font-family: var(--policy-font-body);
}

.policy-hero-banner {
    max-width: 1120px;
    margin: 0 auto 36px;
    background: linear-gradient(135deg, #001838 0%, #00285a 60%, #0c3875 100%);
    border-radius: 20px;
    padding: 44px 36px;
    color: #ffffff;
    box-shadow: 0 16px 40px rgba(0, 40, 90, 0.12);
    position: relative;
    overflow: hidden;
}

.policy-hero-banner::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(255, 63, 108, 0.2) 0%, transparent 70%);
    pointer-events: none;
}

.policy-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 5px 14px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #ffffff;
    margin-bottom: 16px;
}

.policy-hero-title {
    font-family: var(--policy-font-head);
    font-size: 34px;
    font-weight: 800;
    letter-spacing: 1px;
    margin: 0 0 10px 0;
}

.policy-hero-meta {
    font-size: 13px;
    color: #cbd5e1;
    display: flex;
    align-items: center;
    gap: 18px;
    flex-wrap: wrap;
}

.policy-layout-grid {
    max-width: 1120px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 36px;
    align-items: start;
}

/* ── STICKY SIDEBAR NAV ── */
.policy-sidebar {
    position: sticky;
    top: 90px;
    background: #ffffff;
    border: 1px solid var(--policy-border);
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
}

.policy-toc-title {
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: var(--policy-navy);
    margin-bottom: 14px;
    padding-bottom: 8px;
    border-bottom: 1.5px solid #f1f5f9;
}

.policy-toc-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.policy-toc-link {
    display: block;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #64748b;
    text-decoration: none;
    transition: all 0.15s ease;
}

/* ── CONTENT BODY ── */
.policy-content-card {
    background: #ffffff;
    border: 1px solid var(--policy-border);
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 6px 30px rgba(0, 0, 0, 0.03);
}

.policy-section {
    margin-bottom: 36px;
    scroll-margin-top: 100px;
}

.policy-section:last-child {
    margin-bottom: 0;
}

.policy-sec-heading {
    font-size: 20px;
    font-weight: 800;
    color: var(--policy-navy);
    margin: 0 0 14px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.policy-sec-heading i {
    color: var(--policy-accent);
    font-size: 18px;
}

.policy-text {
    font-size: 14.5px;
    line-height: 1.75;
    color: #334155;
    margin-bottom: 14px;
}

.policy-list {
    margin: 0 0 16px 20px;
    padding: 0;
    color: #334155;
    font-size: 14px;
    line-height: 1.7;
}

.policy-list li {
    margin-bottom: 8px;
}

.policy-callout {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-left: 4px solid #16a34a;
    border-radius: 10px;
    padding: 16px 18px;
    font-size: 13.5px;
    color: #166534;
    line-height: 1.6;
    margin: 18px 0;
}

.policy-contact-box {
    background: #f8fafc;
    border: 1.5px solid var(--policy-border);
    border-radius: 14px;
    padding: 22px;
    margin-top: 24px;
}

@media (max-width: 900px) {
    .policy-layout-grid {
        grid-template-columns: 1fr;
    }
    .policy-sidebar {
        display: none;
    }
    .policy-content-card {
        padding: 26px 20px;
    }
    .policy-hero-banner {
        padding: 30px 20px;
    }
    .policy-hero-title {
        font-size: 26px;
    }
}
</style>

<div class="policy-page-wrap">
    
    {{-- ── Hero Banner ── --}}
    <div class="policy-hero-banner">
        <div class="policy-badge">
            <i class="bi bi-shield-lock-fill"></i>
            Legal Agreement
        </div>
        <h1 class="policy-hero-title">Terms &amp; Conditions</h1>
        <div class="policy-hero-meta">
            <span><i class="bi bi-calendar3 me-1"></i> Last Updated: September 2026</span>
            <span>&bull;</span>
            <span><i class="bi bi-building me-1"></i> THE TREND THEORY Apparels Pvt. Ltd.</span>
        </div>
    </div>

    <div class="policy-layout-grid">
        
        {{-- ── Left Table of Contents ── --}}
        <aside class="policy-sidebar">
            <div class="policy-toc-title">Table of Contents</div>
            <ul class="policy-toc-list">
                <li><a href="#acceptance" class="policy-toc-link">1. Acceptance of Terms</a></li>
                <li><a href="#eligibility" class="policy-toc-link">2. Eligibility &amp; Account</a></li>
                <li><a href="#products" class="policy-toc-link">3. Products &amp; Pricing</a></li>
                <li><a href="#orders" class="policy-toc-link">4. Orders &amp; Payments</a></li>
                <li><a href="#shipping" class="policy-toc-link">5. Shipping &amp; Delivery</a></li>
                <li><a href="#returns" class="policy-toc-link">6. Returns &amp; Exchange</a></li>
                <li><a href="#ip" class="policy-toc-link">7. Intellectual Property</a></li>
                <li><a href="#liability" class="policy-toc-link">8. Limitation of Liability</a></li>
                <li><a href="#contact" class="policy-toc-link">9. Contact &amp; Grievance</a></li>
            </ul>
        </aside>

        {{-- ── Right Policy Content ── --}}
        <main class="policy-content-card">
            
            <section id="acceptance" class="policy-section">
                <h2 class="policy-sec-heading"><i class="bi bi-check2-circle"></i> 1. Acceptance of Terms</h2>
                <p class="policy-text">
                    Welcome to <strong>THE TREND THEORY</strong> (accessible via <code>www.thetrendtheory.com</code> and related mobile web services). By browsing, accessing, creating an account, or purchasing any luxury streetwear apparel from this website, you agree to be bound by these Terms and Conditions and our Privacy Policy.
                </p>
                <p class="policy-text">
                    If you do not agree with any part of these terms, you must discontinue the use of our services immediately.
                </p>
            </section>

            <section id="eligibility" class="policy-section">
                <h2 class="policy-sec-heading"><i class="bi bi-person-check"></i> 2. Eligibility &amp; Account Security</h2>
                <p class="policy-text">
                    You must be at least 18 years of age or accessing under the supervision of a parent/legal guardian to make purchases. When creating an account, you agree to:
                </p>
                <ul class="policy-list">
                    <li>Provide true, accurate, and current personal and shipping details.</li>
                    <li>Maintain the confidentiality of your login credentials and OTPs.</li>
                    <li>Accept full responsibility for all activities that occur under your account.</li>
                </ul>
            </section>

            <section id="products" class="policy-section">
                <h2 class="policy-sec-heading"><i class="bi bi-tag"></i> 3. Product Information &amp; Pricing</h2>
                <p class="policy-text">
                    We strive to display our luxury oversized t-shirts, fabrics, colors, graphics, and size specifications as accurately as possible. However, slight color variations may occur due to individual monitor screen settings and studio lighting.
                </p>
                <div class="policy-callout">
                    <strong>Pricing Transparency:</strong> All product prices displayed on the website are in Indian Rupees (INR) and are inclusive of Goods &amp; Services Tax (GST).
                </div>
            </section>

            <section id="orders" class="policy-section">
                <h2 class="policy-sec-heading"><i class="bi bi-credit-card"></i> 4. Orders &amp; Payment Methods</h2>
                <p class="policy-text">
                    We accept various secure payment options including UPI (Google Pay, PhonePe, Paytm), Credit/Debit Cards, Net Banking, and Cash on Delivery (COD) for eligible pincodes.
                </p>
                <ul class="policy-list">
                    <li>Orders are deemed confirmed upon receipt of transaction authorization or OTP confirmation for COD.</li>
                    <li>THE TREND THEORY reserves the right to cancel suspicious, fraudulent, or out-of-stock orders with a full refund.</li>
                </ul>
            </section>

            <section id="shipping" class="policy-section">
                <h2 class="policy-sec-heading"><i class="bi bi-truck"></i> 5. Shipping &amp; Delivery</h2>
                <p class="policy-text">
                    We dispatch all orders within <strong>24 to 48 hours</strong> through premium courier partners (Blue Dart, Delhivery, XpressBees). Standard delivery timelines range between <strong>3 to 6 business days</strong> across India.
                </p>
            </section>

            <section id="returns" class="policy-section">
                <h2 class="policy-sec-heading"><i class="bi bi-arrow-repeat"></i> 6. 7-Day Exchange &amp; Returns Policy</h2>
                <p class="policy-text">
                    We offer a <strong>7-Day Hassle-Free Exchange &amp; Return Policy</strong> from the date of parcel delivery:
                </p>
                <ul class="policy-list">
                    <li>Apparel must be unused, unwashed, and returned in original brand packaging with all tags attached.</li>
                    <li>Prepaid order refunds are processed back to the original payment source within 4-7 working days after quality inspection.</li>
                    <li>For COD orders, refunds are credited via UPI or Bank Transfer.</li>
                </ul>
            </section>

            <section id="ip" class="policy-section">
                <h2 class="policy-sec-heading"><i class="bi bi-brush"></i> 7. Intellectual Property Rights</h2>
                <p class="policy-text">
                    All graphics, streetwear artworks, typography, brand logos, website designs, and media on THE TREND THEORY are the exclusive intellectual property of <strong>THE TREND THEORY Apparels Pvt. Ltd.</strong> Unauthorized reproduction, scraping, or commercial misuse is strictly prohibited by law.
                </p>
            </section>

            <section id="liability" class="policy-section">
                <h2 class="policy-sec-heading"><i class="bi bi-shield-exclamation"></i> 8. Limitation of Liability</h2>
                <p class="policy-text">
                    THE TREND THEORY will not be liable for any indirect, incidental, or punitive damages arising from the use of our website, payment gateways, or courier transit delays beyond our reasonable control.
                </p>
            </section>

            <section id="contact" class="policy-section">
                <h2 class="policy-sec-heading"><i class="bi bi-envelope-at"></i> 9. Contact &amp; Grievance Redressal</h2>
                <p class="policy-text">
                    For any questions regarding these terms or your orders, our support team is available 24/7:
                </p>
                <div class="policy-contact-box">
                    <div style="font-weight: 800; color: #00285a; font-size: 15px; margin-bottom: 6px;">THE TREND THEORY Apparels Pvt. Ltd.</div>
                    <div style="font-size: 13.5px; color: #475569; line-height: 1.6;">
                        Plot No. 42, Luxury Fashion Hub, Andheri East, Mumbai, MH - 400069<br>
                        <strong>Email:</strong> <a href="mailto:support@thetrendtheory.com" style="color: #00285a; font-weight: 700;">support@thetrendtheory.com</a><br>
                        <strong>Support Helpline:</strong> +91 98765 43210 (Mon - Sat, 10 AM - 7 PM IST)
                    </div>
                </div>
            </section>

        </main>
    </div>
</div>
@endsection
