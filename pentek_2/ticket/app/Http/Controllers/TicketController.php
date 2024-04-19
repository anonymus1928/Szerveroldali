<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'title' => 'required|string',
            'priority' => 'required|integer|min:0|max:3',
            'text' => 'required|string|max:1000',
            'file' => 'nullable|file',
        ]);

        $ticket = Ticket::create($validated);
        $ticket->users()->attach(Auth::id(), [ 'owner' => true ]);

        if($request->hasFile('file')) {
            $filename_hash = $request->file('file')->store();
            $ticket->comments()->create([
                'text' => $validated['text'],
                'user_id' => Auth::id(),
                'filename' => $request->file('file')->getClientOriginalName(),
                'filename_hash' => $filename_hash,
            ]);
        } else {
            $ticket->comments()->create([
                'text' => $validated['text'],
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('tickets.show', ['ticket' => $ticket->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ticket = Auth::user()->tickets()->where('tickets.id', $id)->first();
        if(!$ticket) {
            abort(404);
        }
        return view('ticket.ticket', ['ticket' => $ticket]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ticket = Auth::user()->tickets()->where('tickets.id', $id)->first();
        if(!$ticket) {
            abort(404);
        }
        return view('ticket.ticket_form', ['ticket' => $ticket]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'priority' => 'required|integer|min:0|max:3',
        ]);

        $ticket = Auth::user()->tickets()->where('tickets.id', $id)->first();
        if(!$ticket) {
            abort(404);
        }

        $ticket->update($validated);

        return redirect()->route('tickets.show', ['ticket' => $ticket->id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ticket = Auth::user()->tickets()->where('tickets.id', $id)->first();
        if(!$ticket) {
            abort(404);
        }

        $ticket->delete();

        return redirect()->route('tickets.index');
    }

    /**
     * Borzalmas, nem követendő példa, csak demonstráció!!!
     */
    function download($filename) {
        $comment = Comment::where('filename_hash', $filename)->first();
        return Storage::download($comment->filename_hash, $comment->filename);
    }
}
