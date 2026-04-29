<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

use function Illuminate\Support\days;
use function Symfony\Component\Clock\now;

class ApiController extends Controller
{
    // Authentication

    /**
     * Register
     */
    public function register(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => [ 'required', Password::min(8)->letters()->mixedCase()->numbers()->symbols() ],
        ]);

        $user = User::create([
            ...$validated,
            'password' => Hash::make($validated['password']),
            'is_admin' => false,
        ]);

        $token = $user->createToken('auth', $user->is_admin ? ['ticket:admin'] : ['ticket:user'])->plainTextToken;
        return response()->json([
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    /**
     * Login
     */
    public function login(Request $request) {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();
        if(!$user) {
            return response()->json(['error' => 'Hibás jelszó vagy email!'], 401);
        }

        if(Auth::attempt($validated)) {
            $token = $user->createToken('auth', $user->is_admin ? ['ticket:admin'] : ['ticket:user'])->plainTextToken;
            return response()->json([
                'token' => $token,
                'user' => $user,
            ], 201);
        }
    }
}
