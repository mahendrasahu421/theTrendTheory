{{-- resources/views/emails/order_invoice.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Invoice #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f7fb;
            color: #1e293b;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            background-color: #f4f7fb;
            padding: 30px 15px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.04);
        }
        .header {
            background: linear-gradient(135deg, #0b192e 0%, #0f2b54 50%, #1e3a8a 100%);
            padding: 28px 30px;
            color: #ffffff;
            text-align: center;
        }
        .header h1 {
            margin: 0 0 4px;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            font-size: 12px;
            color: #93c5fd;
            font-weight: 600;
        }
        .content {
            padding: 30px;
        }
        .order-meta-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
        }
        .order-meta-table {
            width: 100%;
            border-collapse: collapse;
        }
        .order-meta-table td {
            padding: 4px 0;
            font-size: 12.5px;
            color: #475569;
        }
        .order-meta-table strong {
            color: #0f172a;
        }
        .section-title {
            font-size: 13px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 20px 0 10px;
            border-bottom: 1.5px solid #edf2f7;
            padding-bottom: 6px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            font-size: 10.5px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            text-align: left;
            padding: 8px 10px;
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 12.5px;
            vertical-align: middle;
        }
        .item-name {
            font-weight: 700;
            color: #0f172a;
            display: block;
            margin-bottom: 2px;
        }
        .item-meta {
            font-size: 11px;
            color: #64748b;
        }
        .ledger-box {
            background-color: #fafcff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 20px;
            margin-top: 20px;
        }
        .ledger-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            font-size: 13px;
            color: #475569;
        }
        .ledger-total {
            border-top: 1.5px solid #cbd5e1;
            margin-top: 8px;
            padding-top: 10px;
            font-size: 16px;
            font-weight: 800;
            color: #059669;
            display: flex;
            justify-content: space-between;
        }
        .btn-track {
            display: block;
            width: 100%;
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            color: #ffffff !important;
            text-align: center;
            padding: 14px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 800;
            text-decoration: none;
            margin-top: 24px;
            box-sizing: border-box;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            font-size: 11.5px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            
            {{-- Header --}}
            <div class="header">
                <h1>VAYU</h1>
                <p>OFFICIAL ORDER TAX INVOICE &amp; CONFIRMATION</p>
            </div>

            <div class="content">
                {{-- Customer Greeting --}}
                <p style="font-size: 15px; font-weight: 700; color: #0f172a; margin-top: 0;">
                    Hello {{ $order->shipping_name ?: 'Valued Customer' }},
                </p>
                <p style="font-size: 13px; line-height: 1.6; color: #475569; margin-bottom: 20px;">
                    Thank you for your order with <strong>VAYU</strong>! Your order has been placed successfully and is being prepared for dispatch. Below is your official invoice and receipt summary.
                </p>

                {{-- Order Meta Info Box --}}
                <div class="order-meta-box">
                    <table class="order-meta-table">
                        <tr>
                            <td><strong>Order Number:</strong></td>
                            <td style="text-align: right;"><span style="color:#2563eb; font-weight:800;">#{{ $order->order_number }}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Order Date:</strong></td>
                            <td style="text-align: right;">{{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : now()->format('d M Y, h:i A') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Payment Channel:</strong></td>
                            <td style="text-align: right;">
                                <strong>{{ strtoupper($order->payment_method ?: 'COD') }}</strong> 
                                ({{ ucfirst($order->payment_status ?: 'pending') }})
                            </td>
                        </tr>
                    </table>
                </div>

                {{-- Ordered Items Table --}}
                <div class="section-title">Purchased Items</div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Item Description</th>
                            <th style="text-align: center;">Qty</th>
                            <th style="text-align: right;">Price</th>
                            <th style="text-align: right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <span class="item-name">{{ $item->product_name }}</span>
                                    <span class="item-meta">
                                        {{ $item->sku ? 'SKU: ' . $item->sku : '' }}
                                        {{ $item->size ? '• Size: ' . $item->size : '' }}
                                    </span>
                                </td>
                                <td style="text-align: center;"><strong>{{ $item->quantity }}</strong></td>
                                <td style="text-align: right;">₹{{ number_format($item->unit_price) }}</td>
                                <td style="text-align: right;"><strong>₹{{ number_format($item->subtotal ?: ($item->unit_price * $item->quantity)) }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Financial Summary Ledger --}}
                <div class="ledger-box">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 3px 0; font-size: 12.5px; color: #64748b;">Subtotal:</td>
                            <td style="padding: 3px 0; font-size: 12.5px; text-align: right;"><strong>₹{{ number_format($order->subtotal) }}</strong></td>
                        </tr>
                        <tr>
                            <td style="padding: 3px 0; font-size: 12.5px; color: #64748b;">Shipping &amp; Delivery Fee:</td>
                            <td style="padding: 3px 0; font-size: 12.5px; text-align: right;"><strong>{{ $order->shipping_charge > 0 ? '₹' . number_format($order->shipping_charge) : 'FREE' }}</strong></td>
                        </tr>
                        @if($order->discount_amount > 0)
                            <tr>
                                <td style="padding: 3px 0; font-size: 12.5px; color: #059669;">Coupon Discount:</td>
                                <td style="padding: 3px 0; font-size: 12.5px; text-align: right; color: #059669;"><strong>-₹{{ number_format($order->discount_amount) }}</strong></td>
                            </tr>
                        @endif
                        <tr>
                            <td style="padding-top: 10px; border-top: 1.5px solid #e2e8f0; font-size: 15px; font-weight: 800; color: #0f172a;">Total Payable:</td>
                            <td style="padding-top: 10px; border-top: 1.5px solid #e2e8f0; font-size: 16px; font-weight: 900; text-align: right; color: #059669;">₹{{ number_format($order->total_amount) }}</td>
                        </tr>
                    </table>
                </div>

                {{-- Shipping Destination --}}
                <div class="section-title">Delivery Destination</div>
                <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 18px; font-size: 13px; line-height: 1.6; color: #1e293b;">
                    <strong>{{ $order->shipping_name }}</strong><br>
                    {{ $order->shipping_address }}<br>
                    <strong>{{ $order->shipping_city }}</strong>, {{ $order->shipping_state }} — {{ $order->shipping_pincode }}<br>
                    Phone: <strong>{{ $order->shipping_phone }}</strong>
                </div>

                {{-- Action Buttons --}}
                <div style="margin-top: 24px; text-align: center;">
                    <a href="{{ route('order.track.detail', $order->order_number) }}" style="display: block; background: linear-gradient(135deg, #00285a 0%, #1e3f75 100%); color: #ffffff !important; text-align: center; padding: 14px 20px; border-radius: 10px; font-size: 14px; font-weight: 800; text-decoration: none; margin-bottom: 12px; box-sizing: border-box;">
                        🚚 Track Your Order Live
                    </a>
                    <a href="{{ route('invoice.download', $order->order_number) }}" style="display: inline-block; background: #f1f5f9; color: #00285a !important; border: 1.5px solid #cbd5e1; text-align: center; padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 700; text-decoration: none; box-sizing: border-box;">
                        🧾 View / Download PDF Invoice
                    </a>
                </div>

                <div style="margin-top: 20px; padding: 12px 14px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; font-size: 12px; color: #166534; text-align: center;">
                    📎 <strong>Note:</strong> Your official Tax Invoice (PDF) is also attached directly to this email.
                </div>
            </div>

            {{-- Footer --}}
            <div class="footer">
                <p style="margin: 0 0 6px;">Questions about your order? Reply directly to this email or contact support.</p>
                <p style="margin: 0; color: #94a3b8;">&copy; {{ date('Y') }} VAYU. All rights reserved.</p>
            </div>

        </div>
    </div>
</body>
</html>
