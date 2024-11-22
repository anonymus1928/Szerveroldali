<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function register(Request $request) {
        // Manuális validáció
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|string',
        //     'email' => 'required|string|email|unique:users,email',
        //     'password' => 'required|string',
        // ], [
        //     'required' => ':attribute mező megadása kötelező!',
        //     'string' => ':attribute mező kötelezően szöveges lehet csak!',
        //     'email' => ':attribute mező csak helyesen formázott email címet tartalmazhat!',
        //     'unique' => ':attribute cím már foglalt!'
        // ], [
        //     'name' => 'A név',
        //     'email' => 'Az email',
        //     'password' => 'A jelszó',
        // ]);
        // if($validator->fails()) {
        //     return response()->json([
        //         'error' => $validator->messages()
        //     ], 400); // itt beállítható a kívánt visszatérési státusz
        // }

        // Validáció automatikus redirecttel
        // Jelszó validálásához használható a Password segédosztály (https://laravel.com/docs/11.x/validation#validating-passwords)
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
        ]);

        $user = User::create($validated);

        $token = $user->createToken('auth-token', $user->admin ? ['ticket:admin'] : [], now()->addYear());

        return response()->json([
            'token' => $token->plainTextToken,
        ], 201);
    }

    public function login(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if(Auth::attempt($validated)) {
            $token = $user->createToken('auth-token', $user->admin ? ['ticket:admin'] : [], now()->addYear());
            return response()->json([
                'token' => $token->plainTextToken,
            ]);
        }
        return response()->json([
            'error' => 'Hibás jelszó vagy felhasználó!',
        ], 401);


    }

    public function logout(Request $request) {
        // $user = Auth::user();
        // A felhasználó összes tokenjének törlése
        // $user->tokens()->delete();

        // A felhasználó egy bizonyos id-jú tokenjének törlése
        // $user->tokens()->where('id', <tokenId>)->delete();

        // A jelenlegi authentikáció során használt token törlése
        $request->user()->currentAccessToken()->delete();

        return response(status: 204);
    }





    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|min:5|max:20',
            'priority' => 'required|integer|min:0|max:3',
            'text' => 'required|string|max:1000',
            'file' => 'nullable|file',
        ]);
        $ticket = Ticket::create($validated);
        $ticket->users()->attach(Auth::id(), ['owner' => true]);

        $ticket->comments()->create([
            'text' => $validated['text'],
            'user_id' => Auth::id(),
        ]);

        return response()->json($ticket, 201);
    }

    public function getTickets(Request $request, $id = null) {
        // Authorizáció
        if(isset($id)) {
            if($request->user()->tokenCan('ticket:admin')) {
                return new TicketResource(Ticket::with('owner')->with('comments')->findOrFail($id));
            }
            return new TicketResource(Auth::user()->tickets()->findOrFail($id));
        }

        if($request->user()->tokenCan('ticket:admin')) {
            return Ticket::where('done', false)
                        ->orderByDesc(
                            Comment::select('created_at')
                                ->whereColumn('comments.ticket_id', 'tickets.id')
                                ->latest()
                                ->take(1)
                        )
                        ->get();
        }
        return Auth::user()->tickets()
                        ->where('done', false)
                        ->orderByDesc(
                            Comment::select('created_at')
                                ->whereColumn('comments.ticket_id', 'tickets.id')
                                ->latest()
                                ->take(1)
                        )
                        ->get();
    }

    public function update(UpdateTicketRequest $request, $id) {
        $ticket = Ticket::findOrFail($id);
        // if(!$request->user()->tokenCan('token:admin') && !$ticket->users->contains(Auth::id())) {
        //     return response()->json([
        //         'error' => 'Nincs joga a módosításhoz!',
        //     ], 401);
        // }

        // $validated = $request->validate([
        //     'title' => 'required|string|min:5|max:20',
        //     'priority' => 'required|integer|min:0|max:3',
        // ]);

        $ticket->update($request->validated());

        return $ticket;
    }

    public function destory(Request $request, $id) {
        $ticket = Ticket::findOrFail($id);
        if(!$request->user()->tokenCan('token:admin') && !$ticket->users->contains(Auth::id())) {
            return response()->json([
                'error' => 'Nincs joga a törléshez!',
            ], 401);
        }

        $ticket->delete();

        return response(status: 204);
    }
}
