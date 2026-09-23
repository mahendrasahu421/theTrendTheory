<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = Auth::guard('admin')->user() ?? Auth::guard('web')->user();

        if (!$user) {
            return redirect()->route('admin.login');
        }

        // Customer ko admin access nahi
        if (method_exists($user, 'isCustomer') && $user->isCustomer()) {
            abort(403, 'Access Denied — Customers cannot access staff resources');
        }

        // Agar specific roles check kar rahe hain
        if (!empty($roles) && !in_array($user->role, $roles, true)) {
            abort(403, 'Access Denied — Insufficient permissions');
        }

        return $next($request);
    }
}