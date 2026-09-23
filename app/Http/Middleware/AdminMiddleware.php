<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Check admin guard first
        if (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user();
            if (!$admin->is_active) {
                Auth::guard('admin')->logout();
                return redirect()->route('admin.login')->withErrors(['email' => 'Account has been deactivated.']);
            }
            return $next($request);
        }

        // 2. Backward compatibility: check default web guard if logged in as staff
        if (Auth::guard('web')->check() && method_exists(Auth::user(), 'isStaff') && Auth::user()->isStaff()) {
            return $next($request);
        }

        // Not authenticated as admin -> redirect to admin login
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated Admin.'], 401);
        }

        return redirect()->route('admin.login');
    }
}
