<?php

namespace App\Services;

use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as RequestFacade;

class ActivityLoggerService
{
    public function __construct(
        protected VisitorTrackerService $tracker
    ) {}

    /**
     * Log user activity event.
     */
    public function log(string $eventType, string $eventTitle, array $details = [], $userId = null, ?Request $request = null): ?UserActivity
    {
        try {
            $req = $request ?: RequestFacade::instance();
            if (!$req) return null;

            $ip = $this->tracker->resolveGeoLocation($req->ip() ?: '127.0.0.1', $req);
            $sourceData = $this->tracker->resolveTrafficSource($req);
            $deviceData = $this->tracker->resolveDeviceData((string) $req->userAgent());
            $sessionId = $req->session()->getId() ?: ('guest_' . md5($req->ip() . $req->userAgent()));
            $currentUserId = $userId ?: auth()->id();

            return UserActivity::create([
                'session_id'    => $sessionId,
                'user_id'       => $currentUserId,
                'ip_address'    => $req->ip() ?: '127.0.0.1',
                'event_type'    => $eventType,
                'event_title'   => $eventTitle,
                'event_details' => $details,
                'url'           => $req->fullUrl(),
                'source'        => $sourceData['source'] ?? 'Direct',
                'city'          => $ip['city'] ?? 'Unknown',
                'state'         => $ip['state'] ?? 'Unknown',
                'country'       => $ip['country'] ?? 'India',
                'device_type'   => $deviceData['device_type'] ?? 'mobile',
                'device_brand'  => $deviceData['device_brand'] ?? 'Unknown',
                'device_model'  => $deviceData['device_model'] ?? 'Device',
                'browser'       => $deviceData['browser'] ?? 'Browser',
            ]);
        } catch (\Throwable $e) {
            report($e);
            return null;
        }
    }
}
