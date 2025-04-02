<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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

        $token = $user->createToken('auth', $user->admin ? [ 'ticket:admin' ] : [ 'ticket:user' ]);

        return response()->json([
            'token' => $token->plainTextToken,
            'raw' => $token,
        ], 201);
    }
}
