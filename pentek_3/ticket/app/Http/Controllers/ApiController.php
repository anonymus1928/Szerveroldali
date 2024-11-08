<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function register(Request $request) {
        // Validáció
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
        ]);

        $user = User::create($validated);

        $token = $user->createToken('auth-token', $user->admin ? ['ticket:admin'] : []);

        return response()->json([
            'token' => $token->plainTextToken,
        ], 201);
    }

    public function logout(Request $request) {
        $user = Auth::user();


        $request->user()->currentAccessToken()->delete();

        
        return response(status: 204);
    }
}
