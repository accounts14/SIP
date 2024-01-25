<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request, $role)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $expired = Carbon::now()->addHours(1);
            $token = $user->createToken('access_token', ['*'], [
                'expires_at' => $expired,
            ]);
            return response()->json([
                'id' => $user->id,
                'uuid' => $user->uuid,
                'name' => $user->name,
                'email' => $user->email,
                'school_id' => $user->school_id,
                'role'  => $user->role,
                'expired'  => $expired,
                'token' => $token->accessToken
            ], 200);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['status' => 'success', 'msg' => 'Logout berhasil']);
    }
}
