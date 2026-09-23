{{-- resources/views/froentend/pages/privacy.blade.php --}}
@extends('froentend.layouts.app')

@section('custom_seo')
    <title>Privacy Policy | THE TREND THEORY — Luxury Streetwear</title>
    <meta name="description" content="Learn how THE TREND THEORY protects and manages your personal information, payment security, and data privacy.">
    <link rel="canonical" href="{{ url()->current() }}">
@endsection

@section('main')
<style>
/* ═══════════════════════════════════════════════════════════════════
   THE TREND THEORY — PRIVACY POLICY STYLES
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
    background: radial-gradient(circle, rgba(56, 189, 248, 0.2) 0%, transparent 70%);
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
    color: #38bdf8;
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
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-left: 4px solid #3b82f6;
    border-radius: 10px;
    padding: 16px 18px;
    font-size: 13.5px;
    color: #1e40af;
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
            <i class="bi bi-shield-check"></i>
            Data Protection &amp; Privacy
        </div>
        <h1 class="policy-hero-title">Privacy Policy</h1>
        <div class="policy-hero-meta">
            <span><i class="bi bi-calendar3 me-1"></i> Last Updated: September 2026</span>
            <span>&bull;</span>
            <span><i class="bi bi-lock-fill me-1"></i> 256-Bit SSL Encrypted</span>
        </div>
    </div>

    <div class="policy-layout-grid">
        
        {{-- ── Left Table of Contents ── --}}
        <aside class="policy-sidebar">
            <div class="policy-toc-title">Table of Contents</div>
            <ul class="policy-toc-list">
                <li><a href="#intro" class="policy-toc-link">1. Overview &amp; Commitment</a></li>
                <li><a href="#collect" class="policy-toc-link">2. Data We Collect</a></li>
                <li><a href="#usage" class="policy-toc-link">3. How We Use Data</a></li>
                <li><a href="#security" class="policy-toc-link">4. Payment &amp; Security</a></li>
                <li><a href="#cookies" class="policy-toc-link">5. Cookies &amp; Tracking</a></li>
                <li><a href="#sharing" class="policy-toc-link">6. Third-Party Sharing</a></li>
                <li><a href="#rights" class="policy-toc-link">7. Your Rights &amp; Choices</a></li>
                <li><a href="#contact" class="policy-toc-link">8. Privacy Grievances</a></li>
            </ul>
        </aside>

        {{-- ── Right Policy Content ── --}}
        <main class="policy-content-card">
            
            <section id="intro" class="policy-section">
                <h2 class="policy-sec-heading"><i class="bi bi-shield-shaded"></i> 1. Overview &amp; Commitment</h2>
                <p class="policy-text">
                    At <strong>THE TREND THEORY</strong> (operated by <strong>THE TREND THEORY Apparels Pvt. Ltd.</strong>), we value your trust and are deeply committed to protecting your personal privacy. This Privacy Policy outlines what data we collect, how it is secured, and your rights under Indian Information Technology (IT) laws and global privacy standards.
                </p>
            </section>

            <section id="collect" class="policy-section">
                <h2 class="policy-sec-heading"><i class="bi bi-database-check"></i> 2. Information We Collect</h2>
                <p class="policy-text">
                    We only collect information necessary to fulfill your orders and deliver a personalized streetwear shopping experience:
                </p>
                <ul class="policy-list">
                    <li><strong>Contact &amp; Account Data:</strong> Full Name, Email Address, Mobile Phone Number, Delivery Address, Pincode.</li>
                    <li><strong>Order Details:</strong> Purchased apparel, sizes, color selections, order numbers, return and exchange history.</li>
                    <li><strong>Device &amp; Usage Data:</strong> IP address, browser type, operating system, pages visited, and wishlist preferences.</li>
                </ul>
            </section>

            <section id="usage" class="policy-section">
                <h2 class="policy-sec-heading"><i class="bi bi-gear-wide-connected"></i> 3. How We Use Your Information</h2>
                <p class="policy-text">
                    Your personal information is used strictly for legitimate business and fulfillment purposes:
                </p>
                <ul class="policy-list">
                    <li>Processing, packing, and dispatching your streetwear orders.</li>
                    <li>Sending live SMS, WhatsApp, and email tracking updates regarding your shipments.</li>
                    <li>Providing 24/7 customer support, exchange requests, and refunds.</li>
                    <li>Detecting and preventing fraudulent transactions or bot activity.</li>
                </ul>
            </section>

            <section id="security" class="policy-section">
                <h2 class="policy-sec-heading"><i class="bi bi-credit-card-2-front"></i> 4. Payment Security &amp; Encryption</h2>
                <div class="policy-callout">
                    <strong>Zero Card Data Storage:</strong> THE TREND THEORY does NOT store your credit/debit card numbers, CVVs, or Net Banking credentials on our servers.
                </div>
                <p class="policy-text">
                    All financial transactions are processed through RBI-approved, PCI-DSS Level 1 compliant payment gateways (Razorpay) using end-to-end <strong>256-bit SSL encryption</strong>.
                </p>
            </section>

            <section id="cookies" class="policy-section">
                <h2 class="policy-sec-heading"><i class="bi bi-cookie"></i> 5. Cookies &amp; Session Tracking</h2>
                <p class="policy-text">
                    We use essential session cookies to remember items in your shopping cart, save your wishlist, and keep you securely logged into your account. You can configure your browser to reject cookies, though some features like checkout may require cookies to function.
                </p>
            </section>

            <section id="sharing" class="policy-section">
                <h2 class="policy-sec-heading"><i class="bi bi-share"></i> 6. Sharing with Third Parties</h2>
                <p class="policy-text">
                    We <strong>never sell, rent, or trade</strong> your personal data to third-party advertisers. We only share necessary data with trusted operational partners:
                </p>
                <ul class="policy-list">
                    <li><strong>Logistics Partners:</strong> Blue Dart, Delhivery, XpressBees (only Name, Address, and Phone for delivery).</li>
                    <li><strong>Payment Gateways:</strong> Razorpay (for secure transaction authorization).</li>
                    <li><strong>Law Enforcement:</strong> When strictly required by applicable Indian laws or court orders.</li>
                </ul>
            </section>

            <section id="rights" class="policy-section">
                <h2 class="policy-sec-heading"><i class="bi bi-person-gear"></i> 7. Your Privacy Rights &amp; Choices</h2>
                <p class="policy-text">
                    You have complete control over your personal data:
                </p>
                <ul class="policy-list">
                    <li>Access, review, or update your profile details anytime via your Account Dashboard.</li>
                    <li>Request complete account and personal data deletion by contacting our privacy team.</li>
                    <li>Opt-out of promotional emails or SMS notifications with 1-click unsubscribe.</li>
                </ul>
            </section>

            <section id="contact" class="policy-section">
                <h2 class="policy-sec-heading"><i class="bi bi-shield-lock"></i> 8. Grievance Officer &amp; Contact</h2>
                <p class="policy-text">
                    In accordance with the Information Technology Act 2000, the name and contact details of our Grievance Officer are provided below:
                </p>
                <div class="policy-contact-box">
                    <div style="font-weight: 800; color: #00285a; font-size: 15px; margin-bottom: 6px;">Grievance Officer: Mahendra Sahu</div>
                    <div style="font-size: 13.5px; color: #475569; line-height: 1.6;">
                        THE TREND THEORY Apparels Pvt. Ltd.<br>
                        Plot No. 42, Luxury Fashion Hub, Andheri East, Mumbai, MH - 400069<br>
                        <strong>Privacy Email:</strong> <a href="mailto:privacy@thetrendtheory.com" style="color: #00285a; font-weight: 700;">privacy@thetrendtheory.com</a><br>
                        <strong>Support Helpline:</strong> +91 98765 43210 (Mon - Sat, 10 AM - 7 PM IST)
                    </div>
                </div>
            </section>

        </main>
    </div>
</div>
@endsection
