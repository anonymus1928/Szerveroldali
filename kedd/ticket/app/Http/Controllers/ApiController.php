<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
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

            // Laraveles
            // Validator::validate(['id' => $id], ['id' => 'integer']);

            if($request->user()->tokenCan('ticket:admin')) {
                return new TicketResource(Ticket::with('comments')->with('users')->findOrFail($id)->loadCount('comments'));
            }

            $ticket = Auth::user()->tickets()->find($id)->loadCount('comments');
            if(!$ticket) {
                return response(status: 401);
            }
            return new TicketResource($ticket);
        }

        if($request->user()->tokenCan('ticket:admin')) {
            return TicketResource::collection(Ticket::with('comments')->with('users')->all());
        }

        return TicketResource::collection(Auth::user()->tickets()->with('comments')->with('users')->get());
    }

    public function createTicket(StoreTicketRequest $request) {
        // $validated = $request->validate([
        //     'title' => 'required|string|min:5|max:128|ends_with:please',
        //     'priority' => 'required|integer|min:0|max:3',
        //     'text' => 'required|string|max:1000',
        // ]);

        $validated = $request->validated();

        $ticket = Ticket::create($validated);
        $ticket->users()->attach(Auth::id(), ['owner' => true]);

        $ticket->comments()->create([
            'description' => $validated['text'],
            'user_id' => Auth::id(),
        ]);

        return response()->json($ticket, 201);
    }
}
