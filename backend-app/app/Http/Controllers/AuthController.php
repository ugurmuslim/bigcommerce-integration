<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\utils\ResponseWrapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request) {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'data' => [
                    'token' => $token,
                    'user' => $user
                ]
            ]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
