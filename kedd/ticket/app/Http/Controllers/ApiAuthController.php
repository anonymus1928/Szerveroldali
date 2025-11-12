<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ApiAuthController extends Controller
{
    function register(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => ['required', Password::min(8)->letters()->numbers()->mixedCase()],
        ]);

        $user = User::create([
            ...$validated,
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('auth', $user->admin ? ['ticket:admin'] : ['ticket:user'], now()->addWeek());

        return response()->json([
            'token' => $token->plainTextToken,
            'rawtoken' => $token,
            'user' => $user,
        ], 201);
    }
}
