<?php

namespace App\Services;

use App\Models\PushSubscription;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseNotificationService
{
    protected ?string $projectId;
    protected ?string $apiKey;
    protected ?string $senderId;
    protected ?string $serverKey;
    protected ?string $storageBucket;
    protected ?string $appId;

    public function __construct()
    {
        $this->projectId     = config('services.firebase.project_id', 'the-trend-theory');
        $this->apiKey        = config('services.firebase.api_key', 'AIzaSyBxewN-r_TDJfHBwuzcdIq2Bme6dyRCWVo');
        $this->senderId      = config('services.firebase.sender_id', '664156075505');
        $this->serverKey     = config('services.firebase.server_key');
        $this->storageBucket = config('services.firebase.storage_bucket', 'the-trend-theory.firebasestorage.app');
        $this->appId         = config('services.firebase.app_id', '1:664156075505:ios:6e6b662021c3ce7eef0050');
    }

    /**
     * Get Firebase Project ID
     */
    public function getProjectId(): ?string
    {
        return $this->projectId;
    }

    /**
     * Get Firebase Sender ID
     */
    public function getSenderId(): ?string
    {
        return $this->senderId;
    }

    /**
     * Get Client Web Configuration for frontend JS SDK
     */
    public function getClientConfig(): array
    {
        return [
            'apiKey'            => $this->apiKey,
            'authDomain'        => "{$this->projectId}.firebaseapp.com",
            'projectId'         => $this->projectId,
            'storageBucket'     => $this->storageBucket,
            'messagingSenderId' => $this->senderId,
            'appId'             => $this->appId,
        ];
    }

    /**
     * Broadcast notification to all or targeted subscribers via Firebase Cloud Messaging.
     */
    public function broadcast(
        string $title,
        string $body,
        ?string $actionUrl = null,
        ?string $imageUrl = null,
        string $targetAudience = 'all',
        ?int $specificUserId = null
    ): int {
        $query = PushSubscription::active()->withFcmToken();

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
        if ($subscriptions->isEmpty()) {
            Log::info("FCM Broadcast: No active subscriptions with FCM token found for audience '{$targetAudience}'.");
            return 0;
        }

        $tokens = $subscriptions->pluck('fcm_token')->unique()->values()->all();
        $results = $this->sendToTokens($tokens, $title, $body, $actionUrl, $imageUrl);

        Log::info("FCM Broadcast Dispatched: '{$title}' to " . count($tokens) . " tokens. Success: {$results['success_count']}, Failures: {$results['failure_count']}.");

        // Clean up invalid tokens if any
        if (!empty($results['invalid_tokens'])) {
            PushSubscription::whereIn('fcm_token', $results['invalid_tokens'])->update(['is_active' => false]);
            Log::info("Deactivated " . count($results['invalid_tokens']) . " invalid FCM push subscriptions.");
        }

        return $results['success_count'];
    }

    /**
     * Send notification to a single FCM device token.
     */
    public function sendToToken(
        string $fcmToken,
        string $title,
        string $body,
        ?string $actionUrl = null,
        ?string $imageUrl = null,
        array $extraData = []
    ): bool {
        $response = $this->sendToTokens([$fcmToken], $title, $body, $actionUrl, $imageUrl, $extraData);
        return ($response['success_count'] ?? 0) > 0;
    }

    /**
     * Send notification to multiple FCM device tokens (chunked up to 500 per batch).
     */
    public function sendToTokens(
        array $tokens,
        string $title,
        string $body,
        ?string $actionUrl = null,
        ?string $imageUrl = null,
        array $extraData = []
    ): array {
        $successCount = 0;
        $failureCount = 0;
        $invalidTokens = [];

        $targetUrl = $actionUrl ?: url('/shop');
        $icon = asset('TheTrendTheory.jpg');
        $badge = asset('TheTrendTheory.jpg');

        $chunks = array_chunk(array_filter($tokens), 500);

        foreach ($chunks as $chunk) {
            $payload = [
                'registration_ids' => $chunk,
                'notification' => [
                    'title'        => $title,
                    'body'         => $body,
                    'icon'         => $icon,
                    'image'        => $imageUrl,
                    'click_action' => $targetUrl,
                    'sound'        => 'default',
                ],
                'data' => array_merge([
                    'title'        => $title,
                    'body'         => $body,
                    'url'          => $targetUrl,
                    'image'        => $imageUrl,
                    'icon'         => $icon,
                    'badge'        => $badge,
                    'click_action' => $targetUrl,
                ], $extraData),
                'priority' => 'high',
            ];

            try {
                $authKey = $this->serverKey ?: $this->apiKey;
                $response = Http::timeout(10)
                    ->withHeaders([
                        'Authorization' => 'key=' . $authKey,
                        'Content-Type'  => 'application/json',
                    ])
                    ->post('https://fcm.googleapis.com/fcm/send', $payload);

                if ($response->successful()) {
                    $json = $response->json();
                    $success = $json['success'] ?? 0;
                    $failure = $json['failure'] ?? 0;
                    $successCount += $success;
                    $failureCount += $failure;

                    // Inspect per-token results for expired/invalid tokens
                    if (!empty($json['results'])) {
                        foreach ($json['results'] as $index => $result) {
                            if (isset($result['error'])) {
                                $error = $result['error'];
                                if (in_array($error, ['NotRegistered', 'InvalidRegistration', 'MismatchSenderId'])) {
                                    if (isset($chunk[$index])) {
                                        $invalidTokens[] = $chunk[$index];
                                    }
                                }
                            }
                        }
                    }
                } else {
                    $failureCount += count($chunk);
                    Log::warning("FCM Send HTTP {$response->status()}: " . $response->body());
                }
            } catch (\Throwable $e) {
                $failureCount += count($chunk);
                Log::error("FCM Send Exception: " . $e->getMessage());
            }
        }

        return [
            'success_count'  => $successCount,
            'failure_count'  => $failureCount,
            'invalid_tokens' => $invalidTokens,
        ];
    }

    /**
     * Send notification to a topic (e.g. 'all_users')
     */
    public function sendToTopic(
        string $topic,
        string $title,
        string $body,
        ?string $actionUrl = null,
        ?string $imageUrl = null,
        array $extraData = []
    ): bool {
        $targetUrl = $actionUrl ?: url('/shop');
        $icon = asset('TheTrendTheory.jpg');

        $payload = [
            'to' => '/topics/' . ltrim($topic, '/'),
            'notification' => [
                'title'        => $title,
                'body'         => $body,
                'icon'         => $icon,
                'image'        => $imageUrl,
                'click_action' => $targetUrl,
                'sound'        => 'default',
            ],
            'data' => array_merge([
                'title'        => $title,
                'body'         => $body,
                'url'          => $targetUrl,
                'image'        => $imageUrl,
                'icon'         => $icon,
                'click_action' => $targetUrl,
            ], $extraData),
            'priority' => 'high',
        ];

        try {
            $authKey = $this->serverKey ?: $this->apiKey;
            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'key=' . $authKey,
                    'Content-Type'  => 'application/json',
                ])
                ->post('https://fcm.googleapis.com/fcm/send', $payload);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error("FCM Topic Send Exception: " . $e->getMessage());
            return false;
        }
    }
}
