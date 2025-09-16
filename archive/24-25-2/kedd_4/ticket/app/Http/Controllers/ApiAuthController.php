<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class ApiAuthController extends Controller
{
    function register(Request $request) {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users,email',
                'password' => ['required', 'string', Password::min(8)->letters()->mixedCase()->numbers()],
            ],
            [
                'required' => ':attribute mező megadása kötelező!',
                'string' => ':attribute mező csak szöveges lehet!',
                'email' => ':attribute mező csak helyesen formázott email címet tartalmazhat!',
                'unique' => ':attribute cím már foglalt!',
                // 'min' => ':attribute legalább :min karakterből kell állnia!'
            ],
            [
                'name' => 'A név',
                'email' => 'Az email',
                'password' => 'A jelszó',
            ]
        );
        if($validator->fails()) {
            return response()->json([
                'error' => $validator->messages(),
            ], 400);
        }

        $validated = $validator->validated();

        $user = User::create($validated);

        $token = $user->createToken('auth', $user->admin ? ['ticket:admin'] : ['ticket:user'], now()->addWeek());

        return response()->json([
            'token' => $token->plainTextToken,
            'raw' => $token,
        ], 201);
    }

    public function login(Request $request) {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|string|email|exists:users,email',
                'password' => 'required|string',
            ],
            [
                'required' => ':attribute mező megadása kötelező!',
                'string' => ':attribute mező csak szöveges lehet!',
                'email' => ':attribute mező csak helyesen formázott email címet tartalmazhat!',
                'exists' => ':attribute cím nem található!',
                // 'min' => ':attribute legalább :min karakterből kell állnia!'
            ],
            [
                'name' => 'A név',
                'email' => 'Az email',
                'password' => 'A jelszó',
            ]
        );
        if($validator->fails()) {
            return response()->json([
                'error' => $validator->messages(),
            ], 400);
        }

        $validated = $validator->validated();

        $user = User::where('email', $validated['email'])->first();

        if(!$user) {
            return response()->json(['error' => 'Hibás jelszó vagy email cím!'], 401);
        }

        if(Auth::attempt($validated)) {
            $token = $user->createToken('auth', $user->admin ? ['ticket:admin'] : ['ticket:user'], now()->addWeek());

            return response()->json([
                'token' => $token->plainTextToken,
                'raw' => $token,
            ], 200);
        }

        return response()->json(['error' => 'Hibás jelszó vagy email cím!'], 401);
    }

    public function logout(Request $request) {
        // $user = Auth::user();

        // Összes token törlése
        // $user->tokens()->delete();

        // Egy bizonyos id-jú token törlése
        // $user->tokens()->where('id', $tokenID)->delete();

        // A jelenleg használt token törlése
        $request->user()->currentAccessToken()->delete();

        return response(status: 204);
    }
}

