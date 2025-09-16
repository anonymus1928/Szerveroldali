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
                'password' => ['required', Password::min(8)->letters()->numbers()->mixedCase()],
            ],
            [
                'required' => ':attribute mező kötelező!',
                'string' => ':attribute mező szöveges adatot tartalmazhat csak!',
                'email' => ':attribute mezőnek helyesen formázott email címnek kell lennie!',
                'unique' => ':attribute cím foglalt!',
            ],
            [
                'name' => 'A név',
                'email' => 'Az email',
                'password' => 'A jelszó',
            ]
        );
        if($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $validated = $validator->validated();

        $user = User::create($validated);

        $token = $user->createToken('auth', $user->admin ? [ 'ticket:admin' ] : [ 'ticket:user' ], now()->addWeek());

        return response()->json([
            'token' => $token->plainTextToken,
            'raw' => $token,
        ], 201);
    }


    public function login(Request $request) {
        $validator = Validator::make(
            $request->all(),
            [
                // 'email' => 'required|string|email|exists:users,email', // exists itt nem egy jó ötlet
                'email' => 'required|string|email',
                'password' => 'required|string',
            ],
            [
                'required' => ':attribute mező kötelező!',
                'string' => ':attribute mező szöveges adatot tartalmazhat csak!',
                'email' => ':attribute mezőnek helyesen formázott email címnek kell lennie!',
                'exists' => ':attribute cím helytelen!',
            ],
            [
                'email' => 'Az email',
                'password' => 'A jelszó',
            ]
        );
        if($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $validated = $validator->validated();

        if(Auth::attempt($validated)) {
            $user = User::where('email', $validated['email'])->first();
            $token = $user->createToken('auth', $user->admin ? [ 'ticket:admin' ] : [ 'ticket:user' ], now()->addWeek());
            return response()->json([
                'token' => $token->plainTextToken,
                'raw' => $token,
            ], 200);
        }

        return response()->json(['error' => 'Hibás email vagy jelszó!'], 401);
    }


    public function logout(Request $request) {
        // $user = Auth::user();

        // User összes tokenjének törlése
        // $user->tokens()->delete();

        // Egy bizonyos id-jú token törlése
        // $user->tokens()->where('id', $tokenID)->delete();

        // Az adott authentikáció során használt token törlése
        $request->user()->currentAccessToken()->delete();

        return response(status: 204);
    }
}
