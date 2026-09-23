<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationApiController extends Controller
{
    /**
     * Get user notifications feed.
     */
    public function feed(Request $request)
    {
        $userId = auth()->id();

        if ($userId) {
            $notifications = Notification::where('user_id', $userId)
                ->latest()
                ->limit(15)
                ->get();

            $unreadCount = Notification::where('user_id', $userId)
                ->where('is_read', false)
                ->count();
        } else {
            // For guest visitors, show recent general broadcasts/new products
            $notifications = Notification::whereIn('type', ['manual_broadcast', 'product_launched', 'offer_created'])
                ->latest()
                ->limit(8)
                ->get()
                ->unique('title');

            $lastReadGuest = session('notifications_read_at');
            $unreadCount = $lastReadGuest 
                ? $notifications->where('created_at', '>', $lastReadGuest)->count() 
                : $notifications->count();
        }

        return response()->json([
            'success'       => true,
            'unread_count'  => $unreadCount,
            'notifications' => $notifications->map(fn($n) => [
                'id'           => $n->id,
                'title'        => $n->title,
                'message'      => $n->message,
                'type'         => $n->type,
                'type_label'   => $n->type_label,
                'action_url'   => $n->action_url ?: url('/shop'),
                'action_label' => $n->action_label ?: 'View',
                'image_url'    => $n->image_url,
                'icon'         => $n->icon ?: 'bi-bell-fill',
                'is_read'      => (bool) ($n->is_read || (session('notifications_read_at') && $n->created_at <= session('notifications_read_at'))),
                'time_ago'     => $n->created_at->diffForHumans(),
            ]),
        ]);
    }

    /**
     * Mark single notification as read.
     */
    public function markRead(Notification $notification)
    {
        if (auth()->id() && $notification->user_id === auth()->id()) {
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Mark all user notifications as read.
     */
    public function markAllRead()
    {
        if (auth()->check()) {
            Notification::where('user_id', auth()->id())
                ->where('is_read', false)
                ->update([
                    'is_read' => true,
                    'read_at' => now(),
                ]);
        }

        session(['notifications_read_at' => now()]);

        return response()->json(['success' => true]);
    }
}
