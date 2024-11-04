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
        $tickets = Auth::user()->tickets()
                        ->where('done', false)
                        ->orderByDesc(
                            Comment::select('created_at')
                                ->whereColumn('comments.ticket_id', 'tickets.id')
                                ->latest()
                                ->take(1)
                        )->paginate(5);
        // $tickets = Auth::user()->tickets()->where('done', false)->paginate(5);
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
        $validated = $request->validate([
            'title' => 'required|string|max:20|min:5',
            'priority' => 'required|integer|min:0|max:3',
            'text' => 'required|string|max:1000',
            'file' => 'nullable|file',
        ]);
        $ticket = Ticket::create($validated);
        $ticket->users()->attach(Auth::id(), ['owner' => true]);

        if($request->hasFile('file')) {
            $filename = $request->file('file')->store();
            $ticket->comments()->create([
                'text' => $validated['text'],
                'user_id' => Auth::id(),
                'filename' => $request->file('file')->getClientOriginalName(),
                'filename_hash' => $filename,
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
        $ticket = Ticket::findOrFail($id);
        // if(!$ticket) {
        //     abort(404, 'Nem található!');
        // }
        if(!Auth::user()->admin && !$ticket->users->contains(Auth::id())) {
            abort(401);
        }
        return view('ticket.ticket', ['ticket' => $ticket]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ticket = Ticket::findOrFail($id);
        if(!Auth::user()->admin && !$ticket->users->contains(Auth::id())) {
            abort(401);
        }
        return view('ticket.ticketform', ['ticket' => $ticket]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:20|min:5',
            'priority' => 'required|integer|min:0|max:3',
        ]);
        $ticket = Ticket::findOrFail($id);
        $ticket->update($validated);

        return redirect()->route('tickets.show', ['ticket' => $ticket->id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ticket = Ticket::findOrFail($id);
        if(!Auth::user()->admin && !$ticket->users->contains(Auth::id())) {
            abort(401);
        }
        $ticket->delete();
        return redirect()->route('tickets.index');
    }

    /**
     * Create a new comment.
     */
    public function newComment(Request $request, string $id) {
        $ticket = Ticket::findOrFail($id);
        if(!Auth::user()->admin && !$ticket->users->contains(Auth::id())) {
            abort(401);
        }
        $validated = Validator::make($request->all(), [
            'text' => 'required|string|max:1000',
            'file' => 'nullable|file',
        ], [
            'required' => 'A :attribute mező kitöltése kötelező!',
            'string' => 'A :attribute mezőnek szövegesnek kell lennie!',
            'max' => 'A :attribute mező maximális hossza nem lépheti túl a :max-ot!',
            'file' => 'A :attribute mezőnek fájlnak kell lennie!'
        ], [
            'text' => 'hozzászólás',
            'file' => 'fájl'
        ])->validate();
        if($request->hasFile('file')) {
            $filename = $request->file('file')->store();
            $ticket->comments()->create([
                'text' => $validated['text'],
                'user_id' => Auth::id(),
                'filename' => $request->file('file')->getClientOriginalName(),
                'filename_hash' => $filename,
            ]);
        } else {
            $ticket->comments()->create([
                'text' => $validated['text'],
                'user_id' => Auth::id(),
            ]);
        }
        return redirect()->route('tickets.show', ['ticket' => $ticket->id]);
    }
}
