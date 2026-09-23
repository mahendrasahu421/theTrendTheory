<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Format and send order invoice via WhatsApp.
     */
    public function sendOrderInvoice(Order $order): array
    {
        $phone = preg_replace('/[^0-9]/', '', $order->shipping_phone);
        if (empty($phone)) {
            return ['success' => false, 'message' => 'No valid phone number found for order.'];
        }

        // Add 91 country code if missing
        if (strlen($phone) === 10) {
            $phone = '91' . $phone;
        }

        $order->loadMissing(['items.product', 'user']);

        // Build item text summary
        $itemsText = '';
        foreach ($order->items as $idx => $item) {
            $num = $idx + 1;
            $sizeStr = $item->size ? " [Size: {$item->size}]" : "";
            $itemsText .= "{$num}. *{$item->product_name}*{$sizeStr}\n   Qty: {$item->quantity} × ₹" . number_format($item->unit_price) . " = ₹" . number_format($item->subtotal ?: ($item->unit_price * $item->quantity)) . "\n";
        }

        $payMethod = strtoupper($order->payment_method ?: 'COD');
        $payStatus = ucfirst($order->payment_status ?: 'pending');
        $shippingText = $order->shipping_charge > 0 ? '₹' . number_format($order->shipping_charge) : 'FREE';
        $discountText = $order->discount_amount > 0 ? "\n• *Discount:* -₹" . number_format($order->discount_amount) : '';

        $trackingUrl = route('order.track.detail', $order->order_number);
        $invoiceUrl = route('invoice.download', $order->order_number);

        // Clean structured WhatsApp Confirmation & Invoice Message
        $message = "🎉 *ORDER CONFIRMED & INVOICE — THE TREND THEORY*\n\n"
                 . "Hello *{$order->shipping_name}*,\n"
                 . "Thank you for shopping with us! Your order *#{$order->order_number}* has been confirmed and is being prepared for dispatch.\n\n"
                 . "📋 *Order Details:*\n"
                 . "• *Order ID:* #{$order->order_number}\n"
                 . "• *Date:* " . ($order->created_at ? $order->created_at->format('d M Y, h:i A') : now()->format('d M Y, h:i A')) . "\n"
                 . "• *Payment Mode:* {$payMethod} ({$payStatus})\n\n"
                 . "📦 *Items Ordered:*\n"
                 . $itemsText . "\n"
                 . "💰 *Order Summary:*\n"
                 . "• *Subtotal:* ₹" . number_format($order->subtotal) . "\n"
                 . "• *Delivery:* {$shippingText}"
                 . $discountText . "\n"
                 . "• *Total Paid:* ₹" . number_format($order->total_amount) . "\n\n"
                 . "📍 *Delivery Address:*\n"
                 . "{$order->shipping_name}\n"
                 . "{$order->shipping_address}\n"
                 . "{$order->shipping_city}, {$order->shipping_state} - {$order->shipping_pincode}\n"
                 . "Phone: {$order->shipping_phone}\n\n"
                 . "🚚 *Live Order Tracking:* {$trackingUrl}\n"
                 . "🧾 *Download Tax Invoice (PDF):* {$invoiceUrl}\n\n"
                 . "💬 *Need Help?* Reply to this WhatsApp message or call our support team.\n"
                 . "✨ *THE TREND THEORY* — It's Not Just a Trend, It's a Theory.";

        // Direct WhatsApp Click-to-Chat Link for Instant Customer & Admin Verification
        $directWaUrl = "https://wa.me/{$phone}?text=" . urlencode($message);

        // Check if external WhatsApp Gateway API is configured in .env
        $apiUrl = config('services.whatsapp.api_url') ?: env('WHATSAPP_API_URL');
        $apiToken = config('services.whatsapp.token') ?: env('WHATSAPP_TOKEN');
        $apiPhoneId = config('services.whatsapp.phone_number_id') ?: env('WHATSAPP_PHONE_NUMBER_ID');

        $gatewayDispatched = false;

        if ($apiUrl && $apiToken) {
            try {
                $response = Http::withToken($apiToken)
                    ->timeout(10)
                    ->post($apiUrl, [
                        'messaging_product' => 'whatsapp',
                        'to' => $phone,
                        'type' => 'text',
                        'text' => ['body' => $message],
                    ]);

                $gatewayDispatched = $response->successful();
                Log::info('WhatsApp Invoice API Response', [
                    'order_id' => $order->id,
                    'status'   => $response->status(),
                    'response' => $response->json(),
                ]);
            } catch (\Throwable $e) {
                Log::warning('WhatsApp Gateway dispatch exception: ' . $e->getMessage());
            }
        }

        // Log the WhatsApp Invoice Dispatch Event
        Log::info("WhatsApp Invoice Generated for Order #{$order->order_number} to +{$phone}", [
            'order_id'     => $order->id,
            'phone'        => $phone,
            'gateway_sent' => $gatewayDispatched,
            'wa_url'       => $directWaUrl,
        ]);

        return [
            'success'          => true,
            'phone'            => $phone,
            'message'          => $message,
            'direct_wa_url'    => $directWaUrl,
            'gateway_sent'     => $gatewayDispatched,
        ];
    }
}
