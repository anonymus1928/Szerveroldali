<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\TicketCollection;
use App\Http\Resources\TicketResource;
use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApiController extends Controller
{
    // Authentication
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
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|',
        ]);

        $user = User::create($validated);

        $token = $user->createToken($user->email, $user->admin ? ['ticket:admin'] : [], now()->addYear());

        return response()->json([
            'token' => $token->plainTextToken,
        ], 201);
    }

    public function login(Request $request) {
        // Validáció automatikus redirecttel
        // Jelszó validálásához használható a Password segédosztály (https://laravel.com/docs/11.x/validation#validating-passwords)
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if(Auth::attempt($validated)) {
            $token = $user->createToken($user->email, $user->admin ? ['ticket:admin'] : [], now()->addYear());

            return response()->json([
                'token' => $token->plainTextToken,
            ]);
        } else {
            return response()->json([
                'error' => 'Hibás jelszó vagy felhasználónév!',
            ], 401);
        }
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





    public function getTickets(Request $request, $id = null) {
        if(isset($id)) {
            // Authorization
            if($request->user()->tokenCan('ticket:admin')) {
                // return Ticket::findOrFail($id);
                return new TicketResource(Ticket::with('owner')->with('comments')->findOrFail($id));
            }
            return new TicketResource($request->user()->tickets()->with('owner')->with('comments')->findOrFail($id));
            // return $request->user()->tickets()->findOrFail($id);
        }
        // Authorization
        if($request->user()->tokenCan('ticket:admin')) {
            return Ticket::where('done', false)
                        ->orderByDesc(
                            Comment::select('created_at')
                                ->whereColumn('comments.ticket_id', 'tickets.id')
                                ->latest()
                                ->take(1)
                        )->get();
        }
        return Auth::user()->tickets()
                        ->where('done', false)
                        ->orderByDesc(
                            Comment::select('created_at')
                                ->whereColumn('comments.ticket_id', 'tickets.id')
                                ->latest()
                                ->take(1)
                        )->get();
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:20|min:5',
            'priority' => 'required|integer|min:0|max:3',
            'text' => 'required|string|max:1000',
        ]);
        $ticket = Ticket::create($validated);
        $ticket->users()->attach(Auth::id(), ['owner' => true]);

        $ticket->comments()->create([
            'text' => $validated['text'],
            'user_id' => Auth::id(),
        ]);

        return response()->json($ticket, 201);
    }

    public function update(UpdateTicketRequest $request, $id) {
        // $validated = $request->validate([
        //     'title' => 'required|string|max:20|min:5',
        //     'priority' => 'required|integer|min:0|max:3',
        // ]);
        // if(!Auth::user()->admin && !$ticket->users->contains(Auth::id())) {
        //     return response()->json([
        //         'error' => 'Nincs joga módosítani a ticket-et!',
        //     ], 401);
        // }
        $ticket = Ticket::findOrFail($id);
        $ticket->update($request->validated());

        // return response()->json($ticket);
        return new TicketResource($ticket);
    }

    public function destory(Request $request, $id) {
        $ticket = Ticket::findOrFail($id);
        if(!Auth::user()->admin && !$ticket->users->contains(Auth::id())) {
            return response()->json([
                'error' => 'Nincs joga törölni a ticket-et!',
            ], 401);
        }
        $ticket->delete();
        return response(status: 204);
    }

    public function getTicketsPaginated(Request $request) {
        if($request->user()->tokenCan('ticket:admin')) {
            return new TicketCollection(Ticket::with('owner')->with('users')->with('comments')->paginate(5));
        }
        return new TicketCollection(Auth::user()->tickets()->with('owner')->with('users')->with('comments')->paginate(5));
    }

    public function syncUsers(Request $request, $id) {
        $ticket = Ticket::findOrFail($id);

        if(!Auth::user()->admin && !$ticket->users->contains(Auth::id())) {
            return response()->json([
                'error' => 'Nincs joga módosítani a ticket-et!',
            ], 401);
        }

        $validated = $request->validate([
            'up' => 'array',
            'up.*' => 'integer|exists:users,id',
            'down' => 'array',
            'down.*' => 'integer|exists:users,id',
        ]);

        $users = $ticket->users;

        $output = [
            'successUp' => [],
            'successDown' => [],
            'errorUp' => [],
            'errorDown' => [],
        ];

        foreach($validated['up'] as $userUp) {
            if($users->contains($userUp)) {
                $output['errorUp'][] = $userUp;
            } else {
                $ticket->users()->attach($userUp);
                $output['successUp'][] = $userUp;
            }
        }

        foreach($validated['down'] as $userDown) {
            if($users->contains($userDown)) {
                $ticket->users()->detach($userDown);
                $output['successDown'][] = $userDown;
            } else {
                $output['errorDown'][] = $userDown;
            }
        }

        return $output;
    }

    // Fájl feltöltés
    public function fileUpload(Request $request, $ticketId, $commentId) {
        $ticket = Ticket::findOrFail($ticketId);
        $comment = Comment::findOrFail($commentId);

        if(!$ticket->comments->contains($commentId)) {
            return response()->json([
                'error' => 'A hozzászólás nem ehhez a hibajegyhez tartozik!',
            ], 404);
        }

        if(!Auth::user()->admin && !$ticket->users->contains(Auth::id())) {
            return response()->json([
                'error' => 'Nincs joga feltölteni a csatolmányt!',
            ], 401);
        }

        $validated = $request->validate([
            'file' => 'required|file|max:5000',
        ]);

        if(isset($comment->filename) && isset($comment->filename_hash)) {
            Storage::delete($comment->filename_hash);
        }

        $filename = $request->file('file')->store();

        $comment->filename = $request->file('file')->getClientOriginalName();
        $comment->filename_hash = $filename;
        $comment->save();

        return new TicketResource(Ticket::with('comments')->find($ticketId));
    }



}
