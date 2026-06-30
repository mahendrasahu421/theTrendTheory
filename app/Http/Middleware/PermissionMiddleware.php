<?php
// app/Http/Middleware/PermissionMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!$user->hasPermission($permission)) {
            abort(403, 'Access Denied — You don\'t have permission: '.$permission);
        }

        return $next($request);
    }
}