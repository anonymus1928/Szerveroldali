<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Auth::user()->tickets()->where('done', 0)->get();
        return view('ticket.tickets', ['tickets' => $tickets, 'closed' => false]);
    }

    /**
     * Display a listing of the closed resource.
     */
    public function indexClosed()
    {
        $tickets = Auth::user()->tickets()->where('done', 1)->get();
        return view('ticket.tickets', ['tickets' => $tickets, 'closed' => true]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ticket.ticket_form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|required|string',
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

        return redirect()->route('tickets.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ticket = Auth::user()->tickets()->where('tickets.id', $id)->first();
        if(!$ticket) {
            return abort(404);
        }
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
}
