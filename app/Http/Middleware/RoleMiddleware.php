<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $user = auth()->user();

        // Format role yang dicek: 'superadmin', 'admin:sip', 'admin:school_admin', 'admin:school_head', 'member'
        $userRole = $user->role;
        $userType = $user->type;

        $userIdentifier = $userType ? "{$userRole}:{$userType}" : $userRole;

        if (!in_array($userIdentifier, $roles) && !in_array($userRole, $roles)) {
            return response()->json(['message' => 'Unauthorized. Access denied.'], 403);
        }

        return $next($request);
    }
}