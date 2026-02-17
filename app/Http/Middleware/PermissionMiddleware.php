<?php

namespace App\Http\Middleware;

use App\Support\Permissions\PermissionService;
use Closure;
use Illuminate\Http\Request;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $requiredKey)
    {
        $permission = app(PermissionService::class);

        if (!$permission->hasPermission($requiredKey)) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}