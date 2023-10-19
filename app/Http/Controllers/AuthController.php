<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\LogoutRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Customer;
use App\Models\BookAccount;

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
            $initial_account = [
                ['Cash', 'Cash account','Asset', 'IDR', 0, true],
                ['Bank', 'Bank account','Asset', 'IDR', 0, true],
                ['Hutang', 'Hutang','Liability', 'IDR', 0, true],
                ['Piutang', 'Piutang','Asset', 'IDR', 0, true],
            ];
            
            foreach ($initial_account as $initial) {
                BookAccount::create([
                    'owner_id' => $user->id,
                    'name' => $initial[0],
                    'description' => $initial[1],
                    'type' => $initial[2],
                    'currency' => $initial[3],
                    'balance' => $initial[4],
                    'is_default' => $initial[5],
                ]);
            }

            Customer::create([
                'owner_id' => $user->id,
                'name' => 'Masyarakat Umum',
                'description' => 'Seluruh masyarakat umum',
            ]);

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
