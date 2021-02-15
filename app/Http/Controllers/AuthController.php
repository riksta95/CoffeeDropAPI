<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController
{
    /**
     * Register user from provided request
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }
            
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
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
