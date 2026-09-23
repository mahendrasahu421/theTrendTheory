<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\NotificationSetting;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\UserActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Send manual broadcast or targeted notification.
     */
    public function broadcastManualNotification(
        string $title,
        string $message,
        string $targetAudience = 'all',
        ?string $actionUrl = null,
        ?string $actionLabel = null,
        ?string $imageUrl = null,
        array $channels = ['in_app'],
        ?int $specificUserId = null,
        ?int $adminUserId = null
    ): int {
        $users = $this->resolveTargetUsers($targetAudience, $specificUserId);
        $count = 0;

        foreach ($users as $user) {
            Notification::create([
                'user_id'         => $user->id,
                'title'           => $title,
                'message'         => $message,
                'type'            => 'manual_broadcast',
                'action_url'      => $actionUrl ?: url('/shop'),
                'action_label'    => $actionLabel ?: 'Explore Now',
                'image_url'       => $imageUrl,
                'icon'            => 'bi-megaphone-fill',
                'is_read'         => false,
                'channels'        => $channels,
                'target_audience' => $targetAudience,
                'created_by'      => $adminUserId,
            ]);

            if (in_array('email', $channels) && $user->email) {
                $this->sendEmailNotification($user->email, $title, $message, $actionUrl);
            }

            $count++;
        }

        // Trigger Web Push & Firebase Cloud Messaging to active subscribers
        if (in_array('web_push', $channels) || in_array('firebase_push', $channels)) {
            try {
                app(\App\Services\WebPushService::class)->sendPush(
                    title: $title,
                    body: $message,
                    actionUrl: $actionUrl ?: url('/shop'),
                    imageUrl: $imageUrl,
                    targetAudience: $targetAudience,
                    specificUserId: $specificUserId
                );
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Web Push dispatch error: ' . $e->getMessage());
            }

            try {
                app(\App\Services\FirebaseNotificationService::class)->broadcast(
                    title: $title,
                    body: $message,
                    actionUrl: $actionUrl ?: url('/shop'),
                    imageUrl: $imageUrl,
                    targetAudience: $targetAudience,
                    specificUserId: $specificUserId
                );
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Firebase FCM broadcast error: ' . $e->getMessage());
            }
        }

        return $count;
    }

    /**
     * Send Order Invoice simultaneously to Customer via Email & WhatsApp upon order placement.
     */
    public function sendOrderInvoiceBoth(Order $order): array
    {
        $order->loadMissing(['items.product', 'user']);
        $results = [
            'email_sent'    => false,
            'whatsapp_sent' => false,
            'in_app_sent'   => false,
        ];

        // 1. Email Invoice
        $email = $order->shipping_email ?: ($order->user?->email);
        if ($email) {
            try {
                Mail::to($email)->send(new \App\Mail\OrderInvoiceMail($order));
                $results['email_sent'] = true;
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Order invoice email error: ' . $e->getMessage());
            }
        }

        // 2. WhatsApp Invoice
        if ($order->shipping_phone) {
            try {
                $waResult = app(WhatsAppService::class)->sendOrderInvoice($order);
                $results['whatsapp_sent'] = $waResult['success'] ?? false;
                $results['wa_url'] = $waResult['direct_wa_url'] ?? null;
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Order invoice WhatsApp error: ' . $e->getMessage());
            }
        }

        // 3. In-App Notification
        $userId = $order->user_id;
        if (!$userId && $order->shipping_phone) {
            $user = User::where('phone', $order->shipping_phone)->first();
            $userId = $user?->id;
        }

        if ($userId) {
            try {
                Notification::create([
                    'user_id'         => $userId,
                    'title'           => "🛍️ Order Confirmed #{$order->order_number}",
                    'message'         => "Your order #{$order->order_number} (₹" . number_format($order->total_amount) . ") has been confirmed! Live tracking and tax invoice have been dispatched to your email & WhatsApp.",
                    'type'            => 'order_invoice',
                    'action_url'      => route('order.track.detail', $order->order_number),
                    'action_label'    => 'Track Order Live',
                    'icon'            => 'bi-receipt-cutoff',
                    'is_read'         => false,
                    'channels'        => ['in_app', 'email', 'whatsapp'],
                    'target_audience' => 'single_user',
                ]);
                $results['in_app_sent'] = true;
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return $results;
    }

    /**
     * Auto Trigger 1: New Product Launched / Added.
     */
    public function notifyNewProduct(Product $product): int
    {
        if (!NotificationSetting::getBool('auto_new_product', true)) {
            return 0;
        }

        $title = "🔥 New Drop Alert: " . $product->name;
        $message = "Fresh in stock! Discover our new " . ($product->category?->name ?? 'streetwear') . " drop: " . $product->name . " (₹" . number_format($product->price) . "). Grab yours before it sells out!";
        $actionUrl = route('product.show', $product->slug);
        $actionLabel = "Shop New Drop";

        $users = User::where('role', 'customer')->where('is_active', true)->get();
        $count = 0;

        foreach ($users as $user) {
            Notification::create([
                'user_id'         => $user->id,
                'title'           => $title,
                'message'         => $message,
                'type'            => 'product_launched',
                'action_url'      => $actionUrl,
                'action_label'    => $actionLabel,
                'image_url'       => $product->image_url,
                'icon'            => 'bi-stars',
                'is_read'         => false,
                'channels'        => ['in_app'],
                'target_audience' => 'all',
            ]);
            $count++;
        }

        return $count;
    }

    /**
     * Auto Trigger 2: New Coupon / Offer Created.
     */
    public function notifyNewOffer($coupon): int
    {
        if (!NotificationSetting::getBool('auto_new_offer', true)) {
            return 0;
        }

        $code = is_object($coupon) ? ($coupon->code ?? 'SPECIAL') : (string)$coupon;
        $title = "🎉 Exclusive Offer Alert: Use Code {$code}!";
        $message = "Unlock special savings on premium oversized tees & streetwear today! Apply coupon code {$code} at checkout.";
        $actionUrl = route('shop.index');
        $actionLabel = "Shop with Discount";

        $users = User::where('role', 'customer')->where('is_active', true)->get();
        $count = 0;

        foreach ($users as $user) {
            Notification::create([
                'user_id'         => $user->id,
                'title'           => $title,
                'message'         => $message,
                'type'            => 'offer_created',
                'action_url'      => $actionUrl,
                'action_label'    => $actionLabel,
                'icon'            => 'bi-tag-fill',
                'is_read'         => false,
                'channels'        => ['in_app'],
                'target_audience' => 'all',
            ]);
            $count++;
        }

        return $count;
    }

    /**
     * Auto Trigger 3: Abandoned Cart Reminders (Runs every 2-3 hours).
     */
    public function processAbandonedCartReminders(): int
    {
        if (!NotificationSetting::getBool('auto_abandoned_cart', true)) {
            return 0;
        }

        $minHours = (int) NotificationSetting::get('abandoned_cart_hours', 2);
        $since = now()->subHours($minHours);
        $until = now()->subHours(24);

        // Find users with cart activities in the window who haven't placed an order
        $recentActivities = UserActivity::whereNotNull('user_id')
            ->whereIn('event_type', ['cart_added', 'checkout_started'])
            ->where('created_at', '<=', $since)
            ->where('created_at', '>=', $until)
            ->latest()
            ->get()
            ->unique('user_id');

        $count = 0;

        foreach ($recentActivities as $act) {
            $user = $act->user;
            if (!$user) continue;

            // Check if user already placed an order recently
            $hasRecentOrder = Order::where('user_id', $user->id)
                ->where('created_at', '>=', $act->created_at)
                ->exists();

            if ($hasRecentOrder) continue;

            // Check if reminder was already sent in the last 3 hours
            $alreadyNotified = Notification::where('user_id', $user->id)
                ->where('type', 'cart_abandoned')
                ->where('created_at', '>=', now()->subHours(3))
                ->exists();

            if ($alreadyNotified) continue;

            $title = "🛍️ Items waiting in your shopping bag!";
            $message = "Hi " . $user->name . ", we saved the items in your bag. Complete your checkout today to enjoy fast delivery and exclusive streetwear styles!";
            $actionUrl = url('/checkout');
            $actionLabel = "Complete Order";

            Notification::create([
                'user_id'         => $user->id,
                'title'           => $title,
                'message'         => $message,
                'type'            => 'cart_abandoned',
                'action_url'      => $actionUrl,
                'action_label'    => $actionLabel,
                'icon'            => 'bi-bag-check-fill',
                'is_read'         => false,
                'channels'        => ['in_app', 'email'],
                'target_audience' => 'cart_abandoned',
            ]);

            if ($user->email) {
                $this->sendEmailNotification($user->email, $title, $message, $actionUrl);
            }

            $count++;
        }

        return $count;
    }

    /**
     * Auto Trigger 4: Inactive Registered Users Engagement ("Explore Products").
     */
    public function processInactiveUserReminders(): int
    {
        if (!NotificationSetting::getBool('auto_inactive_user', true)) {
            return 0;
        }

        // Users registered > 24 hours ago with 0 orders
        $inactiveUsers = User::where('role', 'customer')
            ->where('is_active', true)
            ->where('created_at', '<=', now()->subHours(24))
            ->whereDoesntHave('orders')
            ->get();

        $count = 0;

        foreach ($inactiveUsers as $user) {
            // Only send if not notified in last 7 days
            $alreadyNotified = Notification::where('user_id', $user->id)
                ->where('type', 'inactive_welcome')
                ->where('created_at', '>=', now()->subDays(7))
                ->exists();

            if ($alreadyNotified) continue;

            $title = "✨ Discover Trending Streetwear Drops";
            $message = "Hi " . $user->name . ", welcome to THE TREND THEORY! Check out our best-selling heavyweight oversized t-shirts, co-ords, and latest streetwear collections crafted for you.";
            $actionUrl = url('/shop');
            $actionLabel = "Explore Collection";

            Notification::create([
                'user_id'         => $user->id,
                'title'           => $title,
                'message'         => $message,
                'type'            => 'inactive_welcome',
                'action_url'      => $actionUrl,
                'action_label'    => $actionLabel,
                'icon'            => 'bi-compass-fill',
                'is_read'         => false,
                'channels'        => ['in_app', 'email'],
                'target_audience' => 'inactive',
            ]);

            if ($user->email) {
                $this->sendEmailNotification($user->email, $title, $message, $actionUrl);
            }

            $count++;
        }

        return $count;
    }

    /**
     * Auto Trigger 5: Order lifecycle action notification (Confirmed, Processing, Shipped, Delivered, Cancelled, Refunded).
     * Note: Payment status updates do NOT send notifications as per customer communication policy.
     */
    public function notifyOrderStatus(Order $order, string $status, ?string $courierName = null, ?string $trackingNumber = null): ?Notification
    {
        $userId = $order->user_id;
        if (!$userId && $order->shipping_phone) {
            $user = User::where('phone', $order->shipping_phone)->first();
            $userId = $user?->id;
        }

        $courier = $courierName ?: $order->courier_name;
        $tracking = $trackingNumber ?: $order->tracking_number;
        $trackingInfo = ($courier ? " via {$courier}" : "") . ($tracking ? " (AWB: {$tracking})" : "");

        $title = match(strtolower($status)) {
            'confirmed'  => "✅ Order #{$order->order_number} Confirmed",
            'processing' => "📦 Order #{$order->order_number} Being Packed",
            'shipped'    => "🚚 Order #{$order->order_number} Shipped & On The Way",
            'delivered'  => "🎉 Order #{$order->order_number} Successfully Delivered",
            'cancelled'  => "❌ Order #{$order->order_number} Cancelled",
            'refunded'   => "↩️ Order #{$order->order_number} Refund Initiated",
            'returned'   => "🔄 Order #{$order->order_number} Return Processed",
            default      => "📦 Order #{$order->order_number} Status: " . ucfirst($status),
        };

        $message = match(strtolower($status)) {
            'confirmed'  => "Your order #{$order->order_number} has been verified and confirmed! Our dispatch team is preparing your package.",
            'processing' => "Your order #{$order->order_number} is currently being packed and quality-checked for fast dispatch.",
            'shipped'    => "Great news! Your order #{$order->order_number} has been shipped{$trackingInfo} and is in-transit to your delivery destination.",
            'delivered'  => "Your order #{$order->order_number} has been delivered successfully. Thank you for shopping with THE TREND THEORY!",
            'cancelled'  => "Your order #{$order->order_number} has been cancelled. Please contact customer support if you need any assistance.",
            'refunded'   => "Your order #{$order->order_number} has been marked as refunded. The amount will reflect in your source account.",
            'returned'   => "Return request for order #{$order->order_number} has been processed.",
            default      => "Your order #{$order->order_number} status has been updated to " . ucfirst($status) . ".",
        };

        $icon = match(strtolower($status)) {
            'confirmed'  => 'bi-shield-check',
            'processing' => 'bi-box-seam-fill',
            'shipped'    => 'bi-truck',
            'delivered'  => 'bi-check-circle-fill',
            'cancelled'  => 'bi-x-circle-fill',
            'refunded'   => 'bi-arrow-return-left',
            default      => 'bi-bag-check-fill',
        };

        $notification = null;
        $trackingUrl = route('order.track.detail', $order->order_number);

        if ($userId) {
            $notification = Notification::create([
                'user_id'         => $userId,
                'title'           => $title,
                'message'         => $message,
                'type'            => 'order_status',
                'action_url'      => $trackingUrl,
                'action_label'    => 'Track Order Live',
                'icon'            => $icon,
                'is_read'         => false,
                'channels'        => ['in_app', 'email'],
                'target_audience' => 'single_user',
            ]);
        }

        $email = $order->shipping_email ?: ($order->user?->email);
        if ($email) {
            $this->sendEmailNotification($email, $title, $message, $trackingUrl);
        }

        return $notification;
    }

    /**
     * Resolve target users based on audience selection.
     */
    protected function resolveTargetUsers(string $audience, ?int $specificUserId = null)
    {
        if ($audience === 'single_user' && $specificUserId) {
            return User::where('id', $specificUserId)->get();
        }

        if ($audience === 'inactive') {
            return User::where('role', 'customer')
                ->where('created_at', '<=', now()->subHours(24))
                ->whereDoesntHave('orders')
                ->get();
        }

        if ($audience === 'cart_abandoned') {
            $userIds = UserActivity::whereNotNull('user_id')
                ->whereIn('event_type', ['cart_added', 'checkout_started'])
                ->where('created_at', '>=', now()->subDays(3))
                ->pluck('user_id')
                ->unique();

            return User::whereIn('id', $userIds)->get();
        }

        if ($audience === 'buyers') {
            return User::where('role', 'customer')->has('orders')->get();
        }

        // 'all' default
        return User::where('role', 'customer')->where('is_active', true)->get();
    }

    /**
     * Send email helper.
     */
    protected function sendEmailNotification(string $email, string $title, string $message, ?string $actionUrl = null)
    {
        try {
            $content = $message . ($actionUrl ? "\n\nVisit: " . $actionUrl : "");
            Mail::raw($content, function ($m) use ($email, $title) {
                $m->to($email)->subject($title . ' — ' . config('app.name', 'THE TREND THEORY'));
            });
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
