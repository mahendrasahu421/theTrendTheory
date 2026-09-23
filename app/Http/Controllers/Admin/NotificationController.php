<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\NotificationSetting;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    /**
     * Notification Control Center Dashboard.
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'broadcast'); // broadcast, automations, logs
        $typeFilter = $request->query('type');
        $search = $request->query('search');

        // Logs Query
        $logsQuery = Notification::with(['user', 'creator'])
            ->orderByDesc('created_at');

        if ($typeFilter) {
            $logsQuery->where('type', $typeFilter);
        }

        if ($search) {
            $logsQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $notifications = $logsQuery->paginate(20)->withQueryString();

        // Metrics
        $totalSent = Notification::count();
        $broadcastCount = Notification::where('type', 'manual_broadcast')->count();
        $autoCount = Notification::where('type', '!=', 'manual_broadcast')->count();
        $cartCount = Notification::where('type', 'cart_abandoned')->count();
        $readCount = Notification::where('is_read', true)->count();
        $readRate = $totalSent > 0 ? round(($readCount / $totalSent) * 100, 1) : 0;

        // Automation Settings
        $settings = [
            'auto_new_product'     => NotificationSetting::getBool('auto_new_product', true),
            'auto_new_offer'       => NotificationSetting::getBool('auto_new_offer', true),
            'auto_abandoned_cart'  => NotificationSetting::getBool('auto_abandoned_cart', true),
            'auto_inactive_user'   => NotificationSetting::getBool('auto_inactive_user', true),
            'abandoned_cart_hours' => NotificationSetting::get('abandoned_cart_hours', '2'),
        ];

        $customers = User::where('role', 'customer')->orderBy('name')->get(['id', 'name', 'email', 'phone']);
        $pushSubscribersCount = \App\Models\PushSubscription::where('is_active', true)->count();
        $fcmSubscribersCount = \App\Models\PushSubscription::where('is_active', true)->withFcmToken()->count();
        $firebaseProject = config('services.firebase.project_id', 'the-trend-theory');

        return view('admin.notifications.index', compact(
            'notifications',
            'tab',
            'typeFilter',
            'search',
            'totalSent',
            'broadcastCount',
            'autoCount',
            'cartCount',
            'readRate',
            'settings',
            'customers',
            'pushSubscribersCount',
            'fcmSubscribersCount',
            'firebaseProject'
        ));
    }

    /**
     * Send Manual Broadcast or Targeted Notification.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'           => 'required|string|max:200',
            'message'         => 'required|string',
            'target_audience' => 'required|in:all,inactive,cart_abandoned,buyers,single_user',
            'specific_user_id'=> 'nullable|required_if:target_audience,single_user|integer',
            'action_url'      => 'nullable|string|max:500',
            'action_label'    => 'nullable|string|max:100',
            'image_url'       => 'nullable|string|max:500',
            'channels'        => 'nullable|array',
        ]);

        $channels = $request->input('channels', ['in_app']);
        if (!in_array('in_app', $channels)) {
            $channels[] = 'in_app';
        }

        $sentCount = $this->notificationService->broadcastManualNotification(
            title: $request->title,
            message: $request->message,
            targetAudience: $request->target_audience,
            actionUrl: $request->action_url,
            actionLabel: $request->action_label ?: 'Explore Now',
            imageUrl: $request->image_url,
            channels: $channels,
            specificUserId: $request->specific_user_id,
            adminUserId: auth('admin')->id() ?? auth()->id()
        );

        return redirect()->route('admin.notifications.index', ['tab' => 'logs'])
            ->with('success', "Notification broadcast dispatched successfully to {$sentCount} recipient(s)!");
    }

    /**
     * Update Automation Settings.
     */
    public function updateSettings(Request $request)
    {
        NotificationSetting::set('auto_new_product', $request->has('auto_new_product') ? '1' : '0');
        NotificationSetting::set('auto_new_offer', $request->has('auto_new_offer') ? '1' : '0');
        NotificationSetting::set('auto_abandoned_cart', $request->has('auto_abandoned_cart') ? '1' : '0');
        NotificationSetting::set('auto_inactive_user', $request->has('auto_inactive_user') ? '1' : '0');
        NotificationSetting::set('abandoned_cart_hours', $request->input('abandoned_cart_hours', '2'));

        return redirect()->route('admin.notifications.index', ['tab' => 'automations'])
            ->with('success', 'Automation triggers & rules updated successfully!');
    }

    /**
     * Manually Trigger Automation Job on demand.
     */
    public function triggerAutomation(Request $request)
    {
        $type = $request->input('trigger_type');

        if ($type === 'abandoned_cart') {
            $count = $this->notificationService->processAbandonedCartReminders();
            return redirect()->back()->with('success', "Dispatched {$count} abandoned cart recovery notifications!");
        }

        if ($type === 'inactive_users') {
            $count = $this->notificationService->processInactiveUserReminders();
            return redirect()->back()->with('success', "Dispatched {$count} explore collection notifications to inactive users!");
        }

        return redirect()->back()->with('error', 'Invalid automation trigger type.');
    }

    /**
     * Delete Notification Record.
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();
        return redirect()->back()->with('success', 'Notification deleted.');
    }
}
