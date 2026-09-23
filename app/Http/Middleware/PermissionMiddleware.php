<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        $user = Auth::guard('admin')->user() ?? Auth::guard('web')->user();

        if (!$user) {
            return redirect()->route('admin.login');
        }

        if (!method_exists($user, 'hasPermission') || !$user->hasPermission($permission)) {
            abort(403, 'Access Denied — You don\'t have permission: ' . $permission);
        }

        return $next($request);
    }
}