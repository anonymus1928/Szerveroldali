<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Resources\TicketResource;
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
            ]);
        }
    }

    /**
     * Get tickets.
     */
    public function getTickets(Request $request, null|string $ticketID = null) {
        if(isset($ticketID)) {
            // Natív
            // if (!filter_var($ticketID, FILTER_VALIDATE_INT)) {
            //     return response()->json(['error' => 'Hibás URI paraméter!'], 422);
            // }

            // Laraveles
            Validator::validate(['id' => $ticketID], ['id' => 'nullable|integer']);

            if($request->user()->tokenCan('ticket:admin')) {
                return new TicketResource(Ticket::findOrFail($ticketID));
            }
            return new TicketResource($request->user()->tickets()->findOrFail($ticketID));
        }

        if($request->user()->tokenCan('ticket:admin')) {
            return TicketResource::collection(Ticket::with('comments')->get());
        }
        return TicketResource::collection($request->user()->tickets()->with('comments')->withCount('comments')->get());
        // return TicketResource::collection($request->user()->tickets()->with(['comments', 'owner', 'users'])->get());
    }

    /**
     * Add a new comment to a ticket.
     */
    public function addComment(Request $request, string $ticketID) {
        $ticket = Ticket::findOrFail($ticketID);
        Gate::authorize('update', $ticket);

        $validated = $request->validate([
            'text' => 'required|string',
        ]);

        $ticket->comments()->create([
            'description' => $validated['text'],
            'user_id' => Auth::id(),
        ]);

        return response()->json(new TicketResource(Ticket::with('comments')->find($ticket->id)), 201);
    }
}
