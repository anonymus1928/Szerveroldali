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
        if(isset($id)) {
            // URI paraméter validáció

            // Natív
            // if(!filter_var($id, FILTER_VALIDATE_INT)) {
            //     return response()->json(['error' => 'Hibás URI paraméter!'], 422);
            // }

            // "Laraveles"
            // Validator::validate([ 'id' => $id ], [ 'id' => 'integer' ]);


            if($request->user()->tokenCan('ticket:admin')) {
                return new TicketResource(Ticket::with('comments')->with('owner')->with('users')->findOrFail($id)->loadCount('comments'));
            }
            return new TicketResource(Auth::user()->tickets()->with('comments')->with('owner')->with('users')->findOrFail($id)->loadCount('comments'));
        }

        if($request->user()->tokenCan('ticket:admin')) {
            // return TicketResource::collection(Ticket::with('comments')->with('owner')->with('users')->get());
            return new TicketCollection(Ticket::with('comments')->with('owner')->with('users')->get());
        }
        return new TicketCollection(Auth::user()->tickets()->with('comments')->with('owner')->with('users')->get());
    }


    public function createTicket(Request $request) {
        // Validation
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'priority' => 'required|integer|min:0|max:3',
            'text' => 'required|string|max:1000',
            'file' => 'nullable|file',
        ]);

        // Insert into db
        $ticket = Ticket::create($validated);

        // Attach ticket and user
        $ticket->users()->attach(Auth::id(), ['owner' => true]);

        // Create the first comment
        $ticket->comments()->create([
            'text' => $validated['text'],
            'user_id' => Auth::id(),
        ]);

        // Redirect the user to the ticket's page
        return response()->json($ticket, 201);
    }



    public function syncUsers(Request $request, $id) {
        $ticket = Ticket::findOrFail($id);

        if(!$request->user()->tokenCan('ticket:admin') && !$ticket->users->contains(Auth::id())) {
            return response()->json([
                'error' => 'Nincs jogosultsága módosítani a hibajegyet!',
            ], 403);
        }


        // $asdf = [
        //     'asdfasdf' => [
        //         [
        //             'a' => 'alma',
        //             'b' => 3,
        //         ],
        //         [
        //             'a' => 'körte',
        //             'b' => 1,
        //         ]
        //     ],
        // ];

        // [
        //     'asdfasdf' => 'array',
        //     'asdfasdf.*.a' => 'string',
        //     'asdfasdf.*.b' => 'integer',
        // ]

        // $up = [
        //     2,5,7,8
        // ];
        // $down = [
        //     3,8,1
        // ];
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
            'alreadyUp' => [],
            'alreadyDown' => [],
        ];

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
