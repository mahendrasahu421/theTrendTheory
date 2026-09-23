<?php

namespace App\Http\Controllers;

use App\Services\FirebaseNotificationService;
use App\Services\WebPushService;
use Illuminate\Http\Request;

class PushNotificationController extends Controller
{
    public function __construct(
        protected WebPushService $webPushService,
        protected FirebaseNotificationService $firebaseService
    ) {}

    /**
     * Subscribe client to Web Push / Firebase notifications.
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'endpoint'         => 'nullable|string',
            'fcm_token'        => 'nullable|string',
            'device_type'      => 'nullable|string|in:web,ios,android',
            'keys'             => 'nullable|array',
            'public_key'       => 'nullable|string',
            'auth_token'       => 'nullable|string',
            'content_encoding' => 'nullable|string',
        ]);

        if (empty($request->endpoint) && empty($request->fcm_token)) {
            return response()->json([
                'success' => false,
                'message' => 'Either endpoint or fcm_token is required.',
            ], 422);
        }

        $sub = $this->webPushService->subscribe(
            data: $request->all(),
            userId: auth()->id(),
            ipAddress: $request->ip(),
            userAgent: $request->userAgent()
        );

        return response()->json([
            'success' => true,
            'message' => 'Subscribed to push notifications successfully!',
            'id'      => $sub->id,
            'fcm'     => !empty($sub->fcm_token),
        ]);
    }

    /**
     * Unsubscribe client from Web Push.
     */
    public function unsubscribe(Request $request)
    {
        $request->validate([
            'endpoint'  => 'nullable|string',
            'fcm_token' => 'nullable|string',
        ]);

        if ($request->fcm_token) {
            \App\Models\PushSubscription::where('fcm_token', $request->fcm_token)->update(['is_active' => false]);
        }
        if ($request->endpoint) {
            $this->webPushService->unsubscribe($request->endpoint);
        }

        return response()->json([
            'success' => true,
            'message' => 'Unsubscribed from push notifications.',
        ]);
    }

    /**
     * Send test push notification.
     */
    public function sendTest(Request $request)
    {
        $title = $request->input('title', '🔥 Flash Drop Alert — The Trend Theory');
        $body  = $request->input('body', 'New Heavyweight Oversized Tees just dropped! Explore the fresh collection now.');
        $url   = $request->input('url', route('shop.index'));
        $image = $request->input('image', asset('TheTrendTheory.jpg'));

        // 1. Dispatch Web Push
        $sentWebPush = $this->webPushService->sendPush(
            title: $title,
            body: $body,
            actionUrl: $url,
            imageUrl: $image,
            targetAudience: 'all'
        );

        // 2. Dispatch FCM Broadcast if subscribers exist
        $sentFcm = 0;
        try {
            $sentFcm = $this->firebaseService->broadcast(
                title: $title,
                body: $body,
                actionUrl: $url,
                imageUrl: $image,
                targetAudience: 'all'
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('FCM Test Broadcast Warning: ' . $e->getMessage());
        }

        return response()->json([
            'success'        => true,
            'message'        => "Push notification test triggered! (WebPush: {$sentWebPush}, Firebase: {$sentFcm})",
            'web_push_count' => $sentWebPush,
            'fcm_count'      => $sentFcm,
        ]);
    }
}
