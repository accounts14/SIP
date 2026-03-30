<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'name'      => 'required|max:200',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8|confirmed',
            'role'      => 'required|in:superadmin,admin,member',
            'type'      => 'nullable|in:sip,school_admin,school_head',
        ]);
        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create user
        $user = User::create([
            'name'      => $request->name,
            'uuid'      => Str::uuid(),
            'email'     => $request->email,
            'password'  => bcrypt($request->password),
            'role'      => $request->role,
            'type'      => $request->type ?? null,
        ]);

        //return response JSON user is created
        if($user) {
            if (Auth::attempt(['email' => $user->email, 'password' => $request->password])) {
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
            return response()->json([
                'success' => true,
                'user'    => $user,  
            ], 201);
        }

        //return JSON process insert failed 
        return response()->json([
            'success' => false,
        ], 409);
    }
}