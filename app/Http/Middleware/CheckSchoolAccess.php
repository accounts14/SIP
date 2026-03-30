<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSchoolAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Superadmin & admin SIP bisa akses semua sekolah
        if ($user->role === 'superadmin' || ($user->role === 'admin' && $user->type === 'sip')) {
            return $next($request);
        }

        // Admin sekolah & kepala sekolah hanya bisa akses sekolahnya sendiri
        if (in_array($user->type, ['school_admin', 'school_head'])) {
            $schoolId = $request->route('id') 
                ?? $request->route('identifier')
                ?? $request->route('schID')
                ?? $request->input('school_id');

            if ($schoolId && $user->school_id != $schoolId) {
                return response()->json(['message' => 'Unauthorized. You can only access your own school data.'], 403);
            }
        }

        return $next($request);
    }
}