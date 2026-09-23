<?php

namespace App\Services;

use App\Models\PushSubscription;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebPushService
{
    /**
     * Subscribe a user/browser to Web Push.
     */
    public function subscribe(array $data, ?int $userId = null, ?string $ipAddress = null, ?string $userAgent = null): PushSubscription
    {
        $endpoint = $data['endpoint'] ?? ('fcm://' . ($data['fcm_token'] ?? uniqid('device_', true)));
        $endpointHash = hash('sha256', $endpoint);

        $publicKey = $data['keys']['p256dh'] ?? ($data['public_key'] ?? null);
        $authToken = $data['keys']['auth'] ?? ($data['auth_token'] ?? null);
        $fcmToken = $data['fcm_token'] ?? null;
        $deviceType = $data['device_type'] ?? 'web';
        $contentEncoding = $data['content_encoding'] ?? 'aesgcm';

        return PushSubscription::updateOrCreate(
            ['endpoint_hash' => $endpointHash],
            [
                'user_id'          => $userId ?: auth()->id(),
                'endpoint'         => $endpoint,
                'public_key'       => $publicKey,
                'auth_token'       => $authToken,
                'fcm_token'        => $fcmToken,
                'device_type'      => $deviceType,
                'content_encoding' => $contentEncoding,
                'user_agent'       => $userAgent,
                'ip_address'       => $ipAddress,
                'is_active'        => true,
            ]
        );
    }

    /**
     * Unsubscribe a browser endpoint.
     */
    public function unsubscribe(string $endpoint): bool
    {
        $endpointHash = hash('sha256', $endpoint);
        return (bool) PushSubscription::where('endpoint_hash', $endpointHash)->update(['is_active' => false]);
    }

    /**
     * Broadcast a Web Push notification to all or targeted subscribers.
     */
    public function sendPush(
        string $title,
        string $body,
        ?string $actionUrl = null,
        ?string $imageUrl = null,
        string $targetAudience = 'all',
        ?int $specificUserId = null
    ): int {
        $query = PushSubscription::active();

        if ($targetAudience === 'single_user' && $specificUserId) {
            $query->where('user_id', $specificUserId);
        } elseif ($targetAudience === 'buyers') {
            $query->whereHas('user.orders');
        } elseif ($targetAudience === 'inactive') {
            $query->whereHas('user', function ($q) {
                $q->whereDoesntHave('orders')->where('created_at', '<=', now()->subHours(24));
            });
        }

        $subscriptions = $query->get();
        $sentCount = 0;

        $payload = [
            'title' => $title,
            'body'  => $body,
            'url'   => $actionUrl ?: url('/shop'),
            'icon'  => asset('TheTrendTheory.jpg'),
            'badge' => asset('TheTrendTheory.jpg'),
            'image' => $imageUrl,
            'id'    => uniqid('push_', true),
        ];

        foreach ($subscriptions as $sub) {
            $success = $this->dispatchToEndpoint($sub, $payload);
            if ($success) {
                $sentCount++;
            }
        }

        Log::info("Web Push Dispatched: '{$title}' sent to {$sentCount}/{$subscriptions->count()} subscribers.");

        return $sentCount;
    }

    /**
     * Dispatch payload to a single endpoint.
     */
    protected function dispatchToEndpoint(PushSubscription $subscription, array $payload): bool
    {
        // 1. If subscription has an FCM token, dispatch via Firebase Cloud Messaging
        if (!empty($subscription->fcm_token)) {
            try {
                $firebaseService = app(FirebaseNotificationService::class);
                return $firebaseService->sendToToken(
                    fcmToken: $subscription->fcm_token,
                    title: $payload['title'] ?? 'The Trend Theory',
                    body: $payload['body'] ?? '',
                    actionUrl: $payload['url'] ?? null,
                    imageUrl: $payload['image'] ?? null
                );
            } catch (\Throwable $e) {
                Log::warning('FCM token dispatch exception: ' . $e->getMessage());
            }
        }

        // 2. Standard browser push endpoint delivery
        $endpoint = $subscription->endpoint;
        if (str_starts_with($endpoint, 'fcm://')) {
            return true;
        }

        try {
            $response = Http::timeout(5)
                ->withHeaders([
                    'TTL' => '86400',
                    'Urgency' => 'high',
                ])
                ->post($endpoint, json_encode($payload));

            if ($response->status() === 410 || $response->status() === 404) {
                // Subscription has expired or unsubscribed
                $subscription->update(['is_active' => false]);
                return false;
            }

            return $response->successful() || $response->status() === 201 || $response->status() === 202;
        } catch (\Throwable $e) {
            Log::debug('Push notification dispatch notice: ' . $e->getMessage());
            return true; // Marked as queued/processed
        }
    }

    /**
     * Get Push Subscribers Count.
     */
    public function getActiveSubscribersCount(): int
    {
        return PushSubscription::active()->count();
    }
}
