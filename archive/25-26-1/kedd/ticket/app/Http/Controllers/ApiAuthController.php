<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    function login(Request $request) {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if(!$user) {
            return response()->json(['error' => 'Hibás jelszó vagy email!'], 401);
        }

        if(!Auth::attempt($validated)) {
            return response()->json(['error' => 'Hibás jelszó vagy email!'], 401);
        }

        $token = $user->createToken('auth', $user->admin ? ['ticket:admin'] : ['ticket:user'], now()->addWeek());

        return response()->json([
            'token' => $token->plainTextToken,
            'rawtoken' => $token,
            'user' => $user,
        ], 200);
    }

    function logout(Request $request) {

        // Összes token törlése
        // Auth::user()->tokens()->delete();

        // Egy bizonyos id-jú token törlése
        // Auth::user()->tokens()->where('id', $tokenID)->delete();

        // Jelenleg használt token törlése
        $request->user()->currentAccessToken()->delete();
        //Auth::user()->currentAccessToken()->delete();

        return response(status: 204);
    }
}
