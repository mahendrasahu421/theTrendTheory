<?php
// app/Http/Middleware/RoleMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Customer ko admin access nahi
        if ($user->role === 'customer') {
            abort(403, 'Access Denied');
        }

        // Agar specific roles check kar rahe hain
        if (!empty($roles) && !in_array($user->role, $roles)) {
            abort(403, 'Access Denied — Insufficient permissions');
        }

        return $next($request);
    }
}