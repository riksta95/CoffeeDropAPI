<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController
{
    /**
     * Register user from provided request
     * @return JsonResponse
     */
    public function register()
    {
        $user = User::create([
            'name' => request()->input('name'),
            'email' => request()->input('email'),
            'password' => Hash::make(request()->input('password')),
        ]);

        $token = $user->createToken('Laravel Password Grant Client')->accessToken;

        return response()->json($token, 200);
    }

    /**
     * Login user from provided request
     * @return JsonResponse
     */
    public function login()
    {
        $user = User::where('email', request()->input('email'))->first();

        if ($user) {
            if (Hash::check(request()->input('password'), $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;

                return response()->json($token, 200);
            }
        }

        return response()->json('Login Failed - Invalid credentials', 400);
    }
}
