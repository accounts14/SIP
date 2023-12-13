<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request, $role)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // if ($user->role == $role && $role == 'superadmin' || $role != 'superadmin' && $user->role != 'superadmin') {
                $expired = Carbon::now()->addHours(1);
                $token = $user->createToken('access_token', ['*'], [
                    'expires_at' => $expired,
                ]);
                return response()->json([
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role'  => $user->role,
                    'expired'  => $expired,
                    'token' => $token->accessToken
                ], 200);
            // }
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    } 
}
