<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to THE TREND THEORY</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            background-color: #f1f5f9;
            padding: 35px 15px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 30px rgba(0, 40, 90, 0.06);
        }
        .header {
            background: linear-gradient(135deg, #0b192e 0%, #00285a 50%, #1e3a8a 100%);
            padding: 36px 30px;
            color: #ffffff;
            text-align: center;
        }
        .header .brand-title {
            margin: 0 0 6px;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .header .brand-sub {
            margin: 0;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2.5px;
            color: #93c5fd;
            font-weight: 600;
        }
        .hero-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 6px 16px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            color: #ffd700;
            margin-top: 14px;
        }
        .content {
            padding: 32px 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 700;
            color: #00285a;
            margin-bottom: 12px;
        }
        .intro-text {
            font-size: 14px;
            line-height: 1.6;
            color: #475569;
            margin-bottom: 24px;
        }
        
        /* Gift Card Box */
        .gift-box {
            background: #f8fafc;
            border: 2px dashed #00285a;
            border-radius: 14px;
            padding: 24px 20px;
            text-align: center;
            margin-bottom: 26px;
        }
        .gift-badge {
            display: inline-block;
            background: #ffd700;
            color: #00285a;
            font-size: 11px;
            font-weight: 800;
            padding: 4px 12px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .gift-heading {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 6px;
        }
        .coupon-code-wrap {
            display: inline-block;
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            padding: 10px 24px;
            border-radius: 10px;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 3px;
            color: #00285a;
            margin: 10px 0;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
            font-family: monospace;
        }
        .gift-desc {
            font-size: 12px;
            color: #64748b;
            margin: 4px 0 0;
        }

        /* Perks list */
        .perks-title {
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 14px;
        }
        .perk-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 14px;
            gap: 12px;
        }
        .perk-bullet {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #eff6ff;
            color: #00285a;
            font-size: 12px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 1px;
        }
        .perk-text {
            font-size: 13px;
            line-height: 1.5;
            color: #334155;
        }
        .perk-text strong {
            color: #0f172a;
        }

        /* CTA Button */
        .cta-container {
            text-align: center;
            margin: 30px 0 15px;
        }
        .cta-btn {
            display: inline-block;
            background-color: #00285a;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 34px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            box-shadow: 0 4px 15px rgba(0, 40, 90, 0.25);
        }

        /* Footer */
        .footer {
            background-color: #f8fafc;
            padding: 24px 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            font-size: 11px;
            color: #64748b;
            line-height: 1.6;
        }
        .footer-links {
            margin-bottom: 12px;
        }
        .footer-links a {
            color: #00285a;
            text-decoration: none;
            margin: 0 8px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <div class="brand-title">{{ $siteName }}</div>
                <div class="brand-sub">Premium Indian Streetwear</div>
                <div class="hero-badge">✦ INNER CIRCLE VIP PASS ✦</div>
            </div>

            <!-- Content -->
            <div class="content">
                <div class="greeting">Hey Trendsetter, Welcome to The Circle! 👋</div>
                <div class="intro-text">
                    You're officially on the priority VIP list for <strong>{{ $siteName }}</strong> with <strong>{{ $subscriber->email }}</strong>. We craft heavyweight oversized tees, cargo pants, boxy silhouettes, and limited streetwear drops designed to make a statement.
                </div>

                <!-- 10% Welcome Gift Card -->
                <div class="gift-box">
                    <span class="gift-badge">Your Welcome Gift</span>
                    <div class="gift-heading">FLAT 10% OFF YOUR FIRST ORDER</div>
                    <div>
                        <span class="coupon-code-wrap">{{ $couponCode }}</span>
                    </div>
                    <p class="gift-desc">Apply this coupon code at checkout to claim your 10% discount on any style.</p>
                </div>

                <!-- What you get -->
                <div class="perks-title">What to Expect as an Insider:</div>

                <div class="perk-item">
                    <div class="perk-bullet">⚡</div>
                    <div class="perk-text">
                        <strong>Early Drop Access:</strong> Be the first to shop limited-run capsule collections 24 hours before public release.
                    </div>
                </div>

                <div class="perk-item">
                    <div class="perk-bullet">🏷️</div>
                    <div class="perk-text">
                        <strong>Secret Flash Sales:</strong> Exclusive discount codes and weekend deals delivered directly to your inbox.
                    </div>
                </div>

                <div class="perk-item">
                    <div class="perk-bullet">🕶️</div>
                    <div class="perk-text">
                        <strong>Streetwear Lookbooks:</strong> Curated styling tips, oversized fit breakdowns, and seasonal lookbooks.
                    </div>
                </div>

                <div class="perk-item">
                    <div class="perk-bullet">🚚</div>
                    <div class="perk-text">
                        <strong>Priority Dispatch & COD:</strong> Express order processing with Cash on Delivery available across 26,000+ Indian pincodes.
                    </div>
                </div>

                <!-- CTA Button -->
                <div class="cta-container">
                    <a href="{{ $shopUrl }}" class="cta-btn" target="_blank">Shop Latest Streetwear Drops &rarr;</a>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <div class="footer-links">
                    <a href="{{ url('/') }}">Home</a> •
                    <a href="{{ url('/shop') }}">Shop</a> •
                    <a href="{{ url('/shop/new-arrivals') }}">New Arrivals</a> •
                    <a href="{{ url('/privacy-policy') }}">Privacy</a>
                </div>
                <p style="margin:0 0 6px;">Need assistance? Email our support team at <a href="mailto:{{ $siteEmail }}" style="color:#00285a; font-weight:600;">{{ $siteEmail }}</a>.</p>
                <p style="margin:0;">&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.<br>It's Not Just a Trend, It's a Theory.</p>
            </div>
        </div>
    </div>
</body>
</html>
