<?php

namespace App\Http\Middleware;

use Closure;

class CheckStaffPermission
{
    public function handle($request, Closure $next, $permission)
    {
        if (!$request->user() || !$request->user()->hasPermission($permission)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        return $next($request);
    }
}
