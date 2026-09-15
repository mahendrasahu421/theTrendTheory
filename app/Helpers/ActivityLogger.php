<?php

namespace App\Helpers;

use App\Services\ActivityLoggerService;

class ActivityLogger
{
    /**
     * Log user activity event.
     */
    public static function log(string $eventType, string $eventTitle, array $details = [], $userId = null)
    {
        try {
            return app(ActivityLoggerService::class)->log($eventType, $eventTitle, $details, $userId);
        } catch (\Throwable $e) {
            report($e);
            return null;
        }
    }
}
