<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request, $role)
{
    $credentials = $request->only('email', 'password');

    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Email atau password salah'], 401);
    }

    $user = Auth::user();

    // Validasi role - superadmin bisa login via tab superadmin
    // admin:sip bisa login via tab admin
    $allowedRoles = [
        'superadmin' => ['superadmin'],
        'admin'      => ['admin'],
        'member'     => ['member'],
    ];

    if (!in_array($user->role, $allowedRoles[$role] ?? [])) {
        Auth::logout();
        return response()->json([
            'message' => 'Anda tidak memiliki akses sebagai ' . $role
        ], 403);
    }

    $expired = Carbon::now()->addHours(1);
    $tokenResult = $user->createToken('access_token', ['*']);
    $token = $tokenResult->token;
    $token->expires_at = $expired;
    $token->save();

    return response()->json([
        'id'        => $user->id,
        'uuid'      => $user->uuid,
        'name'      => $user->name,
        'avatar'    => $user->avatar,
        'email'     => $user->email,
        'school_id' => $user->school_id,
        'role'      => $user->role,
        'type'      => $user->type,   // ← TAMBAH INI!
        'expired'   => $expired,
        'token'     => $tokenResult->accessToken
    ], 200);
}
}