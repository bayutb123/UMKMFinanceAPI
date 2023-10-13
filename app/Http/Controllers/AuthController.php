<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!auth()->attempt($credentials)) {
            return response()->json([
                'api_status' => '401',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'api_status' => '200',
            'message' => 'Authorized',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function register(Request $request)
    {
        $user = User::create($request->all());

        return response()->json([
            'api_status' => '200',
            'message' => 'User Created',
            'user' => $user,
        ], 200);
    }
}
