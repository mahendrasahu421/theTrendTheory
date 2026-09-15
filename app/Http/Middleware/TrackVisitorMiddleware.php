<?php

namespace App\Http\Middleware;

use App\Services\VisitorTrackerService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitorMiddleware
{
    public function __construct(
        protected VisitorTrackerService $tracker
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            $this->tracker->track($request);
        } catch (\Throwable $e) {
            // Never break site flow if logging fails
            report($e);
        }

        return $response;
    }
}
