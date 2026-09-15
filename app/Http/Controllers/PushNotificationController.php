<?php

namespace App\Http\Controllers;

use App\Services\WebPushService;
use Illuminate\Http\Request;

class PushNotificationController extends Controller
{
    public function __construct(
        protected WebPushService $webPushService
    ) {}

    /**
     * Subscribe client to Web Push notifications.
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'endpoint'         => 'required|string',
            'keys'             => 'nullable|array',
            'public_key'       => 'nullable|string',
            'auth_token'       => 'nullable|string',
            'content_encoding' => 'nullable|string',
        ]);

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
        ]);
    }

    /**
     * Unsubscribe client from Web Push.
     */
    public function unsubscribe(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|string',
        ]);

        $this->webPushService->unsubscribe($request->endpoint);

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
        $title = $request->input('title', '🔥 Flash Drop Alert — Vayu');
        $body = $request->input('body', 'New Heavyweight Oversized Tees just dropped! Explore the fresh collection now.');
        $url = $request->input('url', route('shop.index'));

        $sent = $this->webPushService->sendPush(
            title: $title,
            body: $body,
            actionUrl: $url,
            targetAudience: 'all'
        );

        return response()->json([
            'success' => true,
            'message' => "Push notification test triggered to {$sent} subscribers!",
            'count'   => $sent,
        ]);
    }
}
