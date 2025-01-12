<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Utils\Constants;

class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {

        User::create([
            'access_token' => Constants::ACCESS_TOKEN,
            'client_id' => Constants::CLIENT_ID,
            'client_secret' => Constants::CLIENT_SECRET,
            'store_hash' => Constants::STORE_HASH,
            ...$request->validated()]);

        return response()->json(['data' => [
            'message' => 'User registered successfully'
        ]]);
    }
}
