{{-- resources/views/invoices/shipping_label.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parcel Shipping Sticker #{{ $order->order_number }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800;900&family=JetBrains+Mono:wght@700;800&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, 'Plus Jakarta Sans', sans-serif;
            background-color: #e2e8f0;
            color: #000000;
            padding: 24px 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* ── Top Screen Control Toolbar ── */
        .top-action-bar {
            width: 100%;
            max-width: 410px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            gap: 10px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            height: 38px;
            padding: 0 16px;
            border-radius: 8px;
            border: 1.5px solid #cbd5e1;
            background: #ffffff;
            color: #334155;
            font-size: 12.5px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-action:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        .btn-print {
            background: #0f172a;
            color: #ffffff;
            border-color: #0f172a;
            flex: 1;
            justify-content: center;
        }

        .btn-print:hover {
            background: #000000;
            color: #ffffff;
        }

        .print-tip {
            width: 100%;
            max-width: 410px;
            margin-bottom: 16px;
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 11.5px;
            color: #92400e;
            font-weight: 600;
        }

        /* ── Exact Flipkart / E-Kart Thermal 4x6 Label ── */
        .shipping-label-card {
            width: 100%;
            max-width: 410px;
            background: #ffffff;
            border: 2px solid #000000;
            padding: 0;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.12);
            font-size: 11px;
            line-height: 1.25;
            color: #000000;
            position: relative;
        }

        .label-table {
            width: 100%;
            border-collapse: collapse;
        }

        .label-table td,
        .label-table th {
            border: 1.5px solid #000000;
            padding: 4px 6px;
            vertical-align: top;
        }

        /* Header Row */
        .cell-std {
            width: 44px;
            font-size: 16px;
            font-weight: 900;
            text-align: center;
            vertical-align: middle !important;
            letter-spacing: 0.5px;
            border-left: none !important;
            border-top: none !important;
        }

        .cell-courier-title {
            vertical-align: middle !important;
            border-top: none !important;
            padding: 4px 8px !important;
        }

        .courier-text {
            font-size: 13.5px;
            font-weight: 900;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .cell-surface {
            width: 105px;
            text-align: center;
            vertical-align: middle !important;
            border-top: none !important;
            padding: 2px 4px !important;
        }

        .surface-banner {
            font-size: 13px;
            font-weight: 900;
            letter-spacing: 1.5px;
            text-decoration: underline;
            text-decoration-thickness: 1.5px;
            display: inline-block;
        }

        .cell-zone {
            width: 36px;
            font-size: 20px;
            font-weight: 900;
            text-align: center;
            vertical-align: middle !important;
            border-top: none !important;
            border-right: none !important;
        }

        /* Middle 2-Column Section */
        .cell-ordered-through {
            width: 34%;
            vertical-align: top !important;
            padding: 6px 5px !important;
            border-left: none !important;
            text-align: center;
        }

        .ordered-text {
            font-size: 9.5px;
            font-weight: 700;
            text-align: left;
            margin-bottom: 3px;
        }

        .brand-logo-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            margin-bottom: 6px;
        }

        .brand-logo-img {
            height: 22px;
            width: auto;
            object-fit: contain;
        }

        .brand-logo-text {
            font-size: 14px;
            font-weight: 900;
            font-style: italic;
            letter-spacing: -0.3px;
        }

        .vert-barcode-label {
            font-size: 9.5px;
            font-weight: 900;
            margin-bottom: 3px;
            letter-spacing: 0.5px;
        }

        .vertical-barcode-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 4px auto;
        }

        .awb-text-rotate {
            font-size: 10px;
            font-weight: 900;
            letter-spacing: 1px;
            margin: 4px 0;
            font-family: 'JetBrains Mono', monospace;
            word-break: break-all;
        }

        .hbd-cpd-row {
            font-size: 9px;
            font-weight: 800;
            text-align: left;
            margin-top: 6px;
            line-height: 1.3;
            border-top: 1px dashed #000;
            padding-top: 4px;
        }

        /* Right Column (2D Code & Address) */
        .cell-matrix-addr {
            width: 66%;
            padding: 0 !important;
            border-right: none !important;
        }

        .matrix-code-box {
            padding: 8px 6px;
            text-align: center;
            border-bottom: 1.5px solid #000000;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .matrix-img {
            width: 145px;
            height: 145px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
            image-rendering: pixelated;
        }

        .customer-address-box {
            padding: 7px 9px;
            font-size: 11px;
            line-height: 1.3;
        }

        .addr-head-title {
            font-size: 10.5px;
            font-weight: 800;
            margin-bottom: 2px;
        }

        .cust-name-line {
            font-size: 12.5px;
            font-weight: 900;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .cust-detail-text {
            font-size: 11px;
            font-weight: 600;
            color: #111;
        }

        .cust-phone-line {
            margin-top: 4px;
            font-size: 11px;
            font-weight: 800;
        }

        /* Sold By & GSTIN Row */
        .cell-sold-by {
            border-left: none !important;
            border-right: none !important;
            padding: 5px 8px !important;
            font-size: 9.5px;
            line-height: 1.35;
        }

        .sold-by-title {
            font-weight: 800;
            font-size: 10px;
        }

        .gstin-line {
            font-weight: 800;
            font-size: 10px;
            margin-top: 2px;
        }

        /* Items Manifest Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        .items-table th {
            border-top: 1.5px solid #000 !important;
            border-bottom: 1.5px solid #000 !important;
            border-left: none !important;
            border-right: 1.5px solid #000 !important;
            padding: 3px 6px;
            font-weight: 900;
            background: #ffffff;
            text-align: left;
        }

        .items-table th:last-child {
            border-right: none !important;
            text-align: center;
            width: 42px;
        }

        .items-table td {
            border-top: none !important;
            border-bottom: 1.5px solid #000 !important;
            border-left: none !important;
            border-right: 1.5px solid #000 !important;
            padding: 4px 6px;
            font-weight: 700;
            vertical-align: middle;
        }

        .items-table td:last-child {
            border-right: none !important;
            text-align: center;
            font-weight: 900;
        }

        .item-sno {
            width: 22px;
            text-align: center;
            font-weight: 900;
        }

        .item-spec-tag {
            font-size: 8.5px;
            font-weight: 800;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 0 4px;
            border-radius: 3px;
            margin-left: 3px;
        }

        /* Horizontal Tracking Barcode Section */
        .cell-bottom-barcode-row {
            border-left: none !important;
            border-right: none !important;
            border-bottom: 1.5px solid #000000 !important;
            padding: 6px 8px !important;
        }

        .bottom-barcode-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .barcode-left-block {
            flex: 1;
        }

        .tracking-code-text {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 1px;
            margin-bottom: 3px;
        }

        .horiz-barcode-svg {
            width: 100%;
            max-width: 280px;
            height: 38px;
            display: block;
        }

        /* Routing Code Box B0 */
        .hub-routing-box {
            width: 64px;
            height: 48px;
            border: 2px solid #000000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 900;
            letter-spacing: 1px;
            flex-shrink: 0;
            background: #ffffff;
        }

        /* Footer Line */
        .label-footer-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 4px 8px;
            font-size: 9px;
            font-weight: 800;
            background: #ffffff;
        }

        /* Payment Banner Strip */
        .payment-callout-strip {
            border-bottom: 1.5px solid #000000;
            padding: 4px 8px;
            font-size: 11px;
            font-weight: 900;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .strip-cod {
            background: #000000;
            color: #ffffff;
        }
        .strip-prepaid {
            background: #ffffff;
            color: #000000;
        }

        /* ── PRINT RULES (100mm x 150mm / 4x6 in) ── */
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .top-action-bar,
            .print-tip {
                display: none !important;
            }

            .shipping-label-card {
                max-width: 100% !important;
                width: 100% !important;
                box-shadow: none !important;
                border: 2px solid #000000 !important;
                border-radius: 0 !important;
            }

            @page {
                size: 100mm 150mm;
                margin: 1.5mm 2.5mm;
            }
        }
    </style>
</head>
<body>

    @php
        $isCod = strtolower($order->payment_method ?? '') === 'cod' || empty($order->payment_method);
        $courier = strtoupper($order->courier_name ?: 'E-Kart Logistics');
        $tracking = $order->tracking_number ?: 'FMX' . strtoupper(substr(md5($order->id . $order->order_number), 0, 10));
        
        $pinLast = substr(trim($order->shipping_pincode ?: '400001'), -1);
        $cityClean = trim($order->shipping_city ?: 'MUMBAI');
        $hubCode = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $cityClean), 0, 1)) . $pinLast;
        if (empty($hubCode) || strlen($hubCode) < 2) $hubCode = 'B0';

        $orderDate = $order->created_at ? $order->created_at : now();
        $hbdDate = $orderDate->copy()->addDay()->format('d - m');
        $cpdDate = $orderDate->copy()->addDays(2)->format('d - m');
        $printTime = now()->format('H:i');
        $printDate = now()->format('d/m/y');
    @endphp

    {{-- Top Action Bar --}}
    <div class="top-action-bar">
        <a href="{{ route('admin.orders.index') }}" class="btn-action">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Back</span>
        </a>
        <button type="button" onclick="window.print()" class="btn-action btn-print">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            <span>Print 4x6" Thermal Sticker</span>
        </button>
    </div>

    <div class="print-tip">
        💡 Paper Size: <strong>4x6 in (100x150mm)</strong> &bull; Margins: <strong>None</strong>
    </div>

    {{-- Exact Flipkart / E-Kart 4x6 Thermal Label --}}
    <article class="shipping-label-card">
        
        {{-- ── 1. Top Header Row (STD | E-Kart Logistics | SURFACE | E) ── --}}
        <table class="label-table">
            <tr>
                <td class="cell-std">STD</td>
                <td class="cell-courier-title">
                    <div class="courier-text">{{ $courier }}</div>
                </td>
                <td class="cell-surface">
                    <span class="surface-banner">SURFACE</span>
                </td>
                <td class="cell-zone">E</td>
            </tr>
        </table>

        {{-- ── 2. Middle 2-Column Split ── --}}
        <table class="label-table" style="border-top:none;">
            <tr>
                {{-- Left Column: Ordered through + Vertical Barcode + Dates --}}
                <td class="cell-ordered-through">
                    <div class="ordered-text">Ordered through</div>
                    
                    <div class="brand-logo-wrap">
                        <span class="brand-logo-text">The Trend</span>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="#000">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4zM3.8 6l1.5-2h13.4l1.5 2zM16 10a4 4 0 0 1-8 0"/>
                        </svg>
                    </div>

                    <div class="vert-barcode-label">AWB No.</div>

                    {{-- Vertical Barcode SVG --}}
                    <div class="vertical-barcode-container">
                        <svg width="34" height="165" viewBox="0 0 34 165" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                            @php
                                $hashV = crc32($tracking . 'VERT');
                                srand($hashV);
                                $y = 4;
                                $vBars = [];
                                while ($y < 160) {
                                    $h = rand(1, 3);
                                    $gap = rand(1, 3);
                                    $vBars[] = ['y' => $y, 'h' => $h];
                                    $y += ($h + $gap);
                                }
                            @endphp
                            @foreach($vBars as $vb)
                                <rect x="0" y="{{ $vb['y'] }}" width="34" height="{{ $vb['h'] }}" fill="#000000" />
                            @endforeach
                        </svg>
                    </div>

                    <div class="awb-text-rotate">({{ $tracking }})</div>

                    <div class="hbd-cpd-row">
                        <div>HBD: {{ $hbdDate }}</div>
                        <div>CPD: {{ $cpdDate }}</div>
                    </div>
                </td>

                {{-- Right Column: 2D DataMatrix / QR Code + Customer Address --}}
                <td class="cell-matrix-addr">
                    {{-- 2D Matrix Code --}}
                    <div class="matrix-code-box">
                        <img 
                            src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&margin=0&data={{ urlencode('ORDER:' . $order->order_number . '|AWB:' . $tracking . '|PIN:' . $order->shipping_pincode . '|AMT:' . $order->total_amount) }}" 
                            class="matrix-img" 
                            alt="2D Code"
                            onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'140\' height=\'140\' viewBox=\'0 0 140 140\'><rect width=\'140\' height=\'140\' fill=\'black\'/><rect x=\'10\' y=\'10\' width=\'120\' height=\'120\' fill=\'white\'/><rect x=\'25\' y=\'25\' width=\'90\' height=\'90\' fill=\'black\'/></svg>'"
                        >
                    </div>

                    {{-- Customer Delivery Address --}}
                    <div class="customer-address-box">
                        <div class="addr-head-title">Shipping/Customer address:</div>
                        <div class="cust-name-line">Name: {{ $order->shipping_name ?: 'Valued Customer' }}</div>
                        <div class="cust-detail-text">
                            {{ $order->shipping_address }}<br>
                            <strong>{{ $order->shipping_city }}</strong>, {{ $order->shipping_state }}<br>
                            PIN: <strong>{{ $order->shipping_pincode }}</strong>
                        </div>
                        <div class="cust-phone-line">
                            Phone: <strong>{{ $order->shipping_phone ?: '—' }}</strong>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        {{-- ── 3. High-Contrast Payment Strip (COD / Prepaid) ── --}}
        @if($isCod)
            <div class="payment-callout-strip strip-cod">
                <span>CASH ON DELIVERY (COD)</span>
                <span>COLLECT CASH: &#8377;{{ number_format($order->total_amount) }}</span>
            </div>
        @else
            <div class="payment-callout-strip strip-prepaid">
                <span>PREPAID ONLINE VERIFIED</span>
                <span>DO NOT COLLECT CASH &bull; &#8377;{{ number_format($order->total_amount) }}</span>
            </div>
        @endif

        {{-- ── 4. Sold By & GSTIN ── --}}
        <table class="label-table" style="border-top:none;">
            <tr>
                <td class="cell-sold-by">
                    <div class="sold-by-title">Sold By: The Trend Apparels Pvt. Ltd., Plot 42, Fashion Hub, Andheri East, Mumbai, MH - 400069</div>
                    <div class="gstin-line">GSTIN: 27AAAAA0000A1Z5 &bull; CIN: U74999MH2024PTC123456</div>
                </td>
            </tr>
        </table>

        {{-- ── 5. SKU Items Manifest Table ── --}}
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width:22px; text-align:center;">#</th>
                    <th>SKU ID | Description</th>
                    <th>QTY</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $idx => $item)
                    <tr>
                        <td class="item-sno">{{ $idx + 1 }}</td>
                        <td>
                            <strong>{{ $item->sku ?: 'SKU-' . $item->product_id }}</strong> | {{ $item->product_name }}
                            @if($item->size)
                                <span class="item-spec-tag">Size: {{ $item->size }}</span>
                            @endif
                            @if($item->design_side)
                                <span class="item-spec-tag">{{ strtoupper($item->design_side) }}</span>
                            @endif
                        </td>
                        <td>{{ $item->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- ── 6. Horizontal Barcode & Routing Hub Box (B0) ── --}}
        <table class="label-table" style="border-top:none;">
            <tr>
                <td class="cell-bottom-barcode-row">
                    <div class="tracking-code-text">{{ $tracking }}</div>
                    
                    <div class="bottom-barcode-wrap">
                        <div class="barcode-left-block">
                            {{-- Horizontal Barcode SVG --}}
                            <svg class="horiz-barcode-svg" viewBox="0 0 280 40" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                                @php
                                    $hashH = crc32($tracking . 'HORIZ');
                                    srand($hashH);
                                    $x = 4;
                                    $hBars = [];
                                    while ($x < 275) {
                                        $w = rand(1, 3);
                                        $gap = rand(1, 3);
                                        $hBars[] = ['x' => $x, 'w' => $w];
                                        $x += ($w + $gap);
                                    }
                                @endphp
                                @foreach($hBars as $hb)
                                    <rect x="{{ $hb['x'] }}" y="0" width="{{ $hb['w'] }}" height="40" fill="#000000" />
                                @endforeach
                            </svg>
                        </div>

                        {{-- Large Hub Routing Box (B0) --}}
                        <div class="hub-routing-box">
                            {{ $hubCode }}
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        {{-- ── 7. Footer: Not for resale. & Printed at --}}
        <footer class="label-footer-row">
            <span>Not for resale.</span>
            <span>Printed at {{ $printTime }} hrs, {{ $printDate }}</span>
        </footer>

    </article>

</body>
</html>
