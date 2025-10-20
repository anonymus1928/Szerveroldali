<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::paginate(10);
        return view('ticket.tickets', ['tickets' => $tickets]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ticket.ticketform');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'title' => 'required|string|min:5|max:128|ends_with:please',
        //     'priority' => 'required|integer|min:0|max:3',
        //     'text' => 'required|string|max:1000',
        // ]);
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:5|max:128|ends_with:please',
            'priority' => 'required|integer|min:0|max:3',
            'text' => 'required|string|max:1000',
        ], [
            'required' => ':attribute mező kitöltése kötelező!',
            'string' => ':attribute mező csak szöveget tartalmazhat!',
            'min' => ':attribute legyen minimum: :min',
            'max' => ':attribute legyen maximum: :max',
            'ends_with' => 'Bunkó vagy!',
            'integer' => ':attribute mező csak egész számot tartalmazhat!',
        ], [
            'title' => 'A cím',
            'priority' => 'A priorítás',
            'text' => 'A hibalírás',
        ]);

        if ($validator->fails()) {
            return redirect(route('tickets.create'))->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $ticket = Ticket::create($validated);
        $ticket->users()->attach(Auth::id(), ['owner' => true]);
        $ticket->comments()->create(['description' => $validated['text'], 'user_id' => Auth::id()]);

        return redirect(route('tickets.show', ['ticket' => $ticket->id]));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ticket = Ticket::find($id);
        return view('ticket.ticket', ['ticket' => $ticket]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Create a new comment for a ticket.
     */
    public function addComment(Request $request, string $id)
    {
        $validated = $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->comments()->create([
            'description' => $validated['text'],
            'user_id' => Auth::id(),
        ]);

        return redirect(route('tickets.show', ['ticket' => $ticket->id]));
    }
}
