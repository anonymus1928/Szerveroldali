<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiAuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $apiToken = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'status' => 'ok',
            'token' => $apiToken,
            'user' => $user,
        ]);
    }

    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|string',
            'password' => 'required|string',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'messages' => $validator->errors(),
            ], 422);
        }
        $validatedData = $validator->validated();

        if(!Auth::attempt($validatedData)) {
            // hibával térünk vissza
        }

        $user = User::find(Auth::id());

        $apiToken = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'status' => 'ok',
            'token' => $apiToken,
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => 'ok',
        ]);
    }
}
