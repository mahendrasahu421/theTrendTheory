{{-- resources/views/invoices/pdf_invoice.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Tax Invoice - #{{ $order->order_number }}</title>
    <style>
        @page {
            margin: 10mm 12mm 10mm 12mm;
            size: A4 portrait;
        }
        * {
            box-sizing: border-box;
            -webkit-font-smoothing: antialiased;
        }
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 8.5pt;
            line-height: 1.4;
            color: #1e293b;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }
        
        /* ── Outer Frame Container ── */
        .invoice-card {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            overflow: hidden;
            background: #ffffff;
        }

        /* ── Tables Utility ── */
        .table-layout {
            width: 100%;
            border-collapse: collapse;
        }
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }

        /* ── Header Section ── */
        .header-wrap {
            padding: 16px 20px;
            border-bottom: 1.5px solid #e2e8f0;
            background-color: #f8fafc;
        }
        .brand-logo-text {
            font-size: 16pt;
            font-weight: bold;
            color: #00285a;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin: 0 0 2px 0;
        }
        .brand-tagline {
            font-size: 8pt;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .company-info {
            font-size: 7.5pt;
            color: #475569;
            line-height: 1.35;
        }
        .invoice-badge-title {
            font-size: 14pt;
            font-weight: bold;
            color: #00285a;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }
        .invoice-subtitle {
            font-size: 7.5pt;
            color: #64748b;
            margin-top: 1px;
            margin-bottom: 6px;
        }
        .invoice-meta-table {
            border-collapse: collapse;
            font-size: 8pt;
        }
        .invoice-meta-table td {
            padding: 2px 0;
            color: #475569;
        }
        .invoice-meta-table td.val {
            font-weight: bold;
            color: #0f172a;
            padding-left: 8px;
        }

        /* ── Address Blocks ── */
        .address-section {
            padding: 12px 18px;
            border-bottom: 1px solid #e2e8f0;
        }
        .address-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 10px 12px;
        }
        .address-label {
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #00285a;
            margin-bottom: 4px;
            padding-bottom: 3px;
            border-bottom: 1px solid #e2e8f0;
        }
        .address-name {
            font-size: 9pt;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 2px;
        }
        .address-text {
            font-size: 7.8pt;
            color: #334155;
            line-height: 1.35;
        }

        /* ── Product Items Table ── */
        .items-section {
            padding: 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        .items-table th {
            background-color: #00285a;
            color: #ffffff;
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 10px;
            border: none;
        }
        .items-table td {
            padding: 9px 10px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 8.5pt;
            vertical-align: middle;
        }
        .items-table tr:nth-child(even) td {
            background-color: #fafbfc;
        }
        .item-title {
            font-weight: bold;
            color: #0f172a;
            font-size: 8.5pt;
        }
        .item-variant {
            font-size: 7.5pt;
            color: #64748b;
            margin-top: 2px;
        }
        .badge-size {
            display: inline-block;
            background-color: #e2e8f0;
            color: #00285a;
            font-size: 7pt;
            font-weight: bold;
            padding: 1px 5px;
            border-radius: 3px;
            margin-right: 4px;
        }

        /* ── Calculation & Financial Summary ── */
        .summary-section {
            padding: 12px 18px;
            border-bottom: 1px solid #e2e8f0;
        }
        .tax-breakdown-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 10px 12px;
            font-size: 7.5pt;
        }
        .tax-title {
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #00285a;
            margin-bottom: 4px;
        }
        .calc-table {
            width: 100%;
            border-collapse: collapse;
        }
        .calc-table td {
            padding: 3px 0;
            font-size: 8.5pt;
            color: #475569;
        }
        .calc-table td.val {
            font-weight: bold;
            color: #0f172a;
            text-align: right;
        }
        .grand-total-box {
            background-color: #00285a;
            color: #ffffff;
            padding: 8px 12px;
            border-radius: 4px;
            margin-top: 6px;
        }
        .grand-total-table {
            width: 100%;
            border-collapse: collapse;
        }
        .grand-total-table td {
            color: #ffffff;
            font-size: 10pt;
            font-weight: bold;
            padding: 0;
        }

        /* ── Words Row ── */
        .words-section {
            padding: 8px 18px;
            background-color: #f1f5f9;
            font-size: 7.5pt;
            color: #334155;
            border-bottom: 1px solid #e2e8f0;
        }

        /* ── Terms & Signatures ── */
        .footer-section {
            padding: 12px 18px;
        }
        .terms-title {
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #00285a;
            margin-bottom: 3px;
        }
        .terms-content {
            font-size: 7pt;
            color: #64748b;
            line-height: 1.4;
        }
        .sign-area {
            text-align: right;
        }
        .sign-company {
            font-size: 7.5pt;
            font-weight: bold;
            color: #0f172a;
        }
        .sign-img {
            font-family: 'Brush Script MT', 'Times New Roman', cursive;
            font-size: 16pt;
            font-weight: bold;
            color: #00285a;
            margin: 4px 0 2px 0;
        }
        .sign-label {
            font-size: 7pt;
            font-weight: bold;
            color: #64748b;
            border-top: 1px dashed #cbd5e1;
            padding-top: 3px;
            display: inline-block;
            min-width: 130px;
            text-align: center;
        }

        /* ── Bottom Bar ── */
        .bottom-bar {
            background-color: #f8fafc;
            text-align: center;
            padding: 6px 15px;
            font-size: 7pt;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>

<div class="invoice-card">
    {{-- ── 1. Clean Top Header: Brand & Invoice Meta ── --}}
    <div class="header-wrap">
        <table class="table-layout">
            <tr>
                <td style="width: 55%; vertical-align: top;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                        <img src="{{ public_path('images/vayu-logo-dark.png') }}" style="height: 36px; width: auto;" alt="VAYU">
                        <span class="brand-logo-text" style="font-size: 16pt; font-weight: 800; color: #00285a; letter-spacing: 2px;">VAYU</span>
                    </div>
                    <div class="brand-tagline">Luxury Streetwear &bull; Official Tax Invoice</div>
                    <div class="company-info">
                        <strong>VAYU Apparels Pvt. Ltd.</strong><br>
                        Plot No. 42, Luxury Fashion Hub, Andheri East, Mumbai, MH - 400069<br>
                        <strong>GSTIN:</strong> 27AABCT9988A1Z5 &bull; <strong>PAN:</strong> AABCT9988A<br>
                        <strong>Email:</strong> support@vayu.com &bull; <strong>Web:</strong> vayu.com
                    </div>
                </td>
                <td style="width: 45%; vertical-align: top;" class="text-right">
                    <div class="invoice-badge-title">TAX INVOICE</div>
                    <div class="invoice-subtitle">Original for Recipient</div>
                    
                    <table class="invoice-meta-table" style="margin-left: auto; margin-top: 4px;">
                        <tr>
                            <td class="text-right">Invoice No:</td>
                            <td class="val text-right" style="color: #00285a;">INV-{{ date('Y') }}-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                        </tr>
                        <tr>
                            <td class="text-right">Invoice Date:</td>
                            <td class="val text-right">{{ $order->created_at ? $order->created_at->format('d M Y') : date('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-right">Order ID:</td>
                            <td class="val text-right">#{{ $order->order_number }}</td>
                        </tr>
                        <tr>
                            <td class="text-right">Payment Channel:</td>
                            <td class="val text-right" style="text-transform: uppercase;">
                                {{ $order->payment_method ?: 'COD' }} ({{ ucfirst($order->payment_status ?: 'Completed') }})
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    {{-- ── 2. Clean Billing & Shipping Address Cards ── --}}
    <div class="address-section">
        <table class="table-layout">
            <tr>
                <td style="width: 48.5%; vertical-align: top;">
                    <div class="address-box">
                        <div class="address-label">Billed To (Customer)</div>
                        <div class="address-name">{{ $order->shipping_name }}</div>
                        <div class="address-text">
                            {{ $order->shipping_address }}<br>
                            {{ $order->shipping_city }}, {{ $order->shipping_state }} - <strong>{{ $order->shipping_pincode }}</strong><br>
                            <strong>Phone:</strong> {{ $order->shipping_phone }}<br>
                            <strong>Place of Supply:</strong> {{ $order->shipping_state ?: 'Maharashtra' }}
                        </div>
                    </div>
                </td>
                <td style="width: 3%;"></td>
                <td style="width: 48.5%; vertical-align: top;">
                    <div class="address-box">
                        <div class="address-label">Shipped To (Delivery Destination)</div>
                        <div class="address-name">{{ $order->shipping_name }}</div>
                        <div class="address-text">
                            {{ $order->shipping_address }}<br>
                            {{ $order->shipping_city }}, {{ $order->shipping_state }} - <strong>{{ $order->shipping_pincode }}</strong><br>
                            <strong>Contact:</strong> {{ $order->shipping_phone }}<br>
                            <strong>Dispatch:</strong> Express Courier Shipping
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ── 3. Clean Products List Table ── --}}
    <div class="items-section">
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;" class="text-center">#</th>
                    <th style="width: 46%;" class="text-left">Item Description</th>
                    <th style="width: 13%;" class="text-center">HSN Code</th>
                    <th style="width: 7%;" class="text-center">Qty</th>
                    <th style="width: 14%;" class="text-right">Unit Price</th>
                    <th style="width: 15%;" class="text-right">Amount (Rs.)</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $totTaxable = 0; 
                    $totTax = 0; 
                @endphp
                @foreach($order->items as $index => $item)
                    @php
                        $lineTotal = $item->subtotal ?: ($item->unit_price * $item->quantity);
                        $gstRate = 5; // 5% apparel GST
                        $taxable = round($lineTotal / (1 + ($gstRate / 100)), 2);
                        $taxAmount = $lineTotal - $taxable;
                        $totTaxable += $taxable;
                        $totTax += $taxAmount;
                        $cleanSize = trim($item->size ?? '');
                    @endphp
                    <tr>
                        <td class="text-center bold" style="color: #64748b;">{{ $index + 1 }}</td>
                        <td class="text-left">
                            <div class="item-title">{{ $item->product_name }}</div>
                            <div class="item-variant">
                                @if(!empty($cleanSize) && $cleanSize !== '-')
                                    <span class="badge-size">SIZE: {{ strtoupper($cleanSize) }}</span>
                                @endif
                                @if(!empty($item->design_side))
                                    <span class="badge-size">PRINT: {{ strtoupper($item->design_side) }}</span>
                                @endif
                                @if(!empty($item->sku))
                                    <span>SKU: {{ $item->sku }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="text-center" style="font-family: monospace; font-size: 8pt; color: #475569;">61091000</td>
                        <td class="text-center bold">{{ $item->quantity }}</td>
                        <td class="text-right">Rs. {{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right bold" style="color: #00285a;">Rs. {{ number_format($lineTotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ── 4. Financial Calculations & Taxes ── --}}
    <div class="summary-section">
        <table class="table-layout">
            <tr>
                <td style="width: 52%; vertical-align: top;">
                    <div class="tax-breakdown-box">
                        <div class="tax-title">Tax &amp; GST Ledger Breakdown</div>
                        <table class="table-layout" style="font-size: 7.5pt;">
                            <tr>
                                <td style="color: #64748b; padding: 2px 0;">Taxable Value:</td>
                                <td class="text-right bold" style="padding: 2px 0;">Rs. {{ number_format($totTaxable, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; padding: 2px 0;">CGST (2.5%):</td>
                                <td class="text-right bold" style="padding: 2px 0;">Rs. {{ number_format($totTax / 2, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; padding: 2px 0;">SGST (2.5%):</td>
                                <td class="text-right bold" style="padding: 2px 0;">Rs. {{ number_format($totTax / 2, 2) }}</td>
                            </tr>
                            <tr style="border-top: 1px solid #e2e8f0;">
                                <td style="font-weight: bold; color: #00285a; padding: 3px 0;">Total GST Included:</td>
                                <td class="text-right bold" style="color: #00285a; padding: 3px 0;">Rs. {{ number_format($totTax, 2) }}</td>
                            </tr>
                        </table>
                        <div style="font-size: 6.8pt; color: #94a3b8; margin-top: 4px;">
                            * Reverse Charge: No. Prices are inclusive of all applicable taxes.
                        </div>
                    </div>
                </td>
                <td style="width: 5%;"></td>
                <td style="width: 43%; vertical-align: top;">
                    <table class="calc-table">
                        <tr>
                            <td class="text-left">Items Subtotal:</td>
                            <td class="val">Rs. {{ number_format($order->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-left">Shipping Charges:</td>
                            <td class="val">{{ $order->shipping_charge > 0 ? 'Rs. ' . number_format($order->shipping_charge, 2) : 'FREE' }}</td>
                        </tr>
                        @if($order->discount_amount > 0)
                            <tr>
                                <td class="text-left" style="color: #059669;">Coupon Discount:</td>
                                <td class="val" style="color: #059669;">-Rs. {{ number_format($order->discount_amount, 2) }}</td>
                            </tr>
                        @endif
                    </table>

                    <div class="grand-total-box">
                        <table class="grand-total-table">
                            <tr>
                                <td class="text-left">Grand Total:</td>
                                <td class="text-right" style="color: #38bdf8;">Rs. {{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ── 5. Total in Words ── --}}
    <div class="words-section">
        <strong>Amount in Words:</strong>
        INR {{ ucwords(\App\Services\InvoicePdfService::numberToWords((int)$order->total_amount)) }} Only
    </div>

    {{-- ── 6. Terms & Signature ── --}}
    <div class="footer-section">
        <table class="table-layout">
            <tr>
                <td style="width: 60%; vertical-align: top;">
                    <div class="terms-title">Terms &amp; Conditions</div>
                    <div class="terms-content">
                        &bull; 7-Day hassle-free return or exchange with brand tags intact in original box.<br>
                        &bull; This invoice is legal proof of purchase and warranty for VAYU products.<br>
                        &bull; Computer generated official tax invoice; no physical signature required.
                    </div>
                </td>
                <td style="width: 40%; vertical-align: bottom;" class="sign-area">
                    <div class="sign-company">For VAYU Apparels Pvt. Ltd.</div>
                    <div class="sign-img">Mahendra Sahu</div>
                    <div class="sign-label">Authorized Signatory</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ── 7. Subtle Bottom Bar ── --}}
    <div class="bottom-bar">
        Official Brand Store &bull; Thank you for shopping with VAYU! &bull; www.vayu.com
    </div>
</div>

</body>
</html>
