{{-- resources/views/froentend/cart/phonepe_simulator.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhonePe Payment Gateway (Sandbox)</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap');

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f1f3f6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .phonepe-card {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(95, 37, 159, 0.12);
            border: 1px solid #e2e8f0;
        }

        /* Top Brand Header */
        .phonepe-header {
            background: #5f259f;
            color: #ffffff;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .phonepe-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .phonepe-logo-circle {
            width: 38px;
            height: 38px;
            background: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            color: #5f259f;
            font-size: 20px;
        }

        .phonepe-brand-title {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -0.3px;
        }

        .sandbox-badge {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.4);
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Order Summary Strip */
        .order-strip {
            background: #faf5ff;
            border-bottom: 1px solid #f3e8ff;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .merchant-name {
            font-size: 13px;
            font-weight: 700;
            color: #4b5563;
        }

        .merchant-store {
            font-size: 15px;
            font-weight: 800;
            color: #0f172a;
        }

        .order-amount-box {
            text-align: right;
        }

        .order-amount-label {
            font-size: 11px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
        }

        .order-amount-val {
            font-size: 22px;
            font-weight: 900;
            color: #5f259f;
            letter-spacing: -0.5px;
        }

        /* Body Section */
        .phonepe-body {
            padding: 24px;
        }

        .qr-section {
            background: #ffffff;
            border: 2px dashed #d8b4fe;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }

        .qr-mockup {
            width: 140px;
            height: 140px;
            margin: 0 auto 12px;
            background: #0f172a;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 12px;
            font-weight: 700;
            position: relative;
            padding: 8px;
        }

        .qr-mockup img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 6px;
            background: #fff;
        }

        .qr-instruction {
            font-size: 13px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .qr-sub {
            font-size: 11.5px;
            color: #6b7280;
        }

        /* Payment Apps Row */
        .apps-strip {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin: 16px 0;
        }

        .app-chip {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 6px 12px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 700;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Actions */
        .btn-pay-now {
            width: 100%;
            background: linear-gradient(135deg, #5f259f 0%, #7e22ce 100%);
            color: #ffffff;
            border: none;
            padding: 14px 20px;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 8px 20px rgba(95, 37, 159, 0.3);
            transition: all 0.2s ease;
            text-decoration: none;
            margin-bottom: 10px;
        }

        .btn-cancel {
            display: block;
            text-align: center;
            color: #94a3b8;
            font-size: 12.5px;
            font-weight: 600;
            text-decoration: none;
            padding: 6px;
            transition: color 0.15s ease;
        }

        .security-footer {
            text-align: center;
            padding: 14px;
            border-top: 1px solid #f1f5f9;
            background: #fafafa;
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="phonepe-card">
    {{-- Header --}}
    <div class="phonepe-header">
        <div class="phonepe-brand">
            <div class="phonepe-logo-circle">&#2346;&#2375;</div>
            <div class="phonepe-brand-title">PhonePe</div>
        </div>
        <div class="sandbox-badge">Sandbox Simulator</div>
    </div>

    {{-- Order Summary --}}
    <div class="order-strip">
        <div>
            <div class="merchant-name">Paying to</div>
            <div class="merchant-store">THE TREND THEORY</div>
        </div>
        <div class="order-amount-box">
            <div class="order-amount-label">Amount Payable</div>
            <div class="order-amount-val">&#8377;{{ number_format($pendingData['total'] ?? 599) }}</div>
        </div>
    </div>

    {{-- Body --}}
    <div class="phonepe-body">
        
        <div class="qr-section">
            <div class="qr-mockup">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=upi://pay?pa=thetrendtheory@ybl%26pn=TheTrendTheory%26am={{ $pendingData['total'] ?? 599 }}%26tr={{ $txnId }}" alt="PhonePe QR Code">
            </div>
            <div class="qr-instruction">Scan with any UPI App</div>
            <div class="qr-sub">PhonePe &bull; Google Pay &bull; Paytm &bull; BHIM UPI</div>
        </div>

        <div class="apps-strip">
            <div class="app-chip">&#128241; PhonePe UPI</div>
            <div class="app-chip">&#128179; Debit/Credit Card</div>
            <div class="app-chip">&#127974; NetBanking</div>
        </div>

        {{-- Test Pay Now Button --}}
        <form method="POST" action="{{ route('payment.phonepe.callback') }}">
            @csrf
            <input type="hidden" name="txn" value="{{ $txnId }}">
            <input type="hidden" name="code" value="PAYMENT_SUCCESS">
            <button type="submit" class="btn-pay-now">
                <span>Complete Payment (&#8377;{{ number_format($pendingData['total'] ?? 599) }})</span>
                <span>&rarr;</span>
            </button>
        </form>

        <a href="{{ route('cart.index') }}" class="btn-cancel">
            &times; Cancel &amp; Return to Store
        </a>
    </div>

    <div class="security-footer">
        &#128274; 256-Bit Encrypted &bull; Official PhonePe PG Integration
    </div>
</div>

</body>
</html>
