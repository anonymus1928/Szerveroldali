<?php

namespace App\Http\Controllers;

use App\Http\Resources\TicketCollection;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function getTickets(Request $request, $id = null) {
        // URI paraméter validálása

        // if(!filter_var($id, FILTER_VALIDATE_INT)) {
        //     return response()->json(['error' => 'Hibás URI paraméter!'], 422);
        // }

        // Validator::validate(['id' => $id], [ 'id' => 'integer' ]);

        if(isset($id)) {
            if($request->user()->tokenCan('ticket:admin')) {
                return new TicketResource(Ticket::with('owner')->with('users')->with('comments')->findOrFail($id)->loadCount('comments'));
            }
            return new TicketResource(Auth::user()->tickets()->with('owner')->with('users')->with('comments')->findOrFail($id));
        }
        if($request->user()->tokenCan('ticket:admin')) {
            // return TicketResource::collection(Ticket::with('owner')->with('users')->with('comments')->get());
            return new TicketCollection(Ticket::with('owner')->with('users')->with('comments')->get());
        }
        return new TicketCollection(Auth::user()->tickets()->with('owner')->with('users')->with('comments')->get());
    }



    public function createTicket(Request $request) {
        // Validation
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'priority' => 'required|integer|min:0|max:3',
            'text' => 'required|string|max:1000',
        ]);

        // Insert into db
        $ticket = Ticket::create($validated);

        // Attach user and ticket
        $ticket->users()->attach(Auth::id(), ['owner' => true]);

        $ticket->comments()->create([
            'text' => $validated['text'],
            'user_id' => Auth::id(),
        ]);

        // Redirect the user to the ticket's page
        return new TicketResource($ticket);
    }



    public function syncUsers(Request $request, $id) {
        $ticket = Ticket::findOrFail($id);

        if(!$request->user()->tokenCan('ticket:admin') && !$ticket->users->contains(Auth::id())) {
            return response()->json([
                'error' => 'Nincs jogosultsága módosítani a hibajegyen!'
            ], 403);
        }


        // [
        //     'asdf' => [
        //         [
        //             'a' => 'alma',
        //             'b' => 3
        //         ],
        //         [
        //             'a' => 'körte',
        //             'b' => 7
        //         ],
        //     ]
        // ];

        // [
        //     'asdf' => 'array',
        //     'asdf.*.a' => 'string',
        //     'asdf.*.b' => 'integer'
        // ];


        // [
        //     'up' => [ 1, 2, 3],
        //     'down' => [ 5, 9 ],
        // ]

        $validated = $request->validate([
            'up' => 'array',
            'up.*' => 'integer|exists:users,id',
            'down' => 'array',
            'down.*' => 'integer|exists:users,id',
        ]);

        $output = [
            'successUp' => [],
            'successDown' => [],
            'alreadyUp' => [],
            'alreadyDown' => [],
        ];

        $users = $ticket->users;

        foreach($validated['up'] as $userUp) {
            if($users->contains($userUp)) {
                $output['alreadyUp'][] = $userUp;
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
                $output['alreadyDown'][] = $userDown;
            }
        }

        return $output;
    }
}
