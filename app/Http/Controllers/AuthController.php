<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\LogoutRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        if (!auth()->attempt($validated)) {
            return response()->json([
                'api_status' => '401',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = User::where('email', $request->email)->first();
        if ($user != null) {
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'api_status' => '200',
                'message' => 'Authorized',
                'user' => $user,
                'token' => $token,
            ], 200);
        }

        return response()->json([
            'api_status' => '500',
            'message' => 'Internal Server Error',
        ], 500);
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        
        if (User::isValid($validated['email'])) {
            return response()->json([
                'api_status' => '409',
                'message' => 'Email already exist',
            ], 409);
        } else {
            $user = User::create($validated);
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'api_status' => '201',
                'message' => 'Created',
                'user' => $user,
                'token' => $token,
            ], 201);
        }

        return response()->json([
            'api_status' => '500',
            'message' => 'Internal Server Error',
        ], 500);
    }


}
