<?php

namespace App\Http\Controllers;

use App\Models\Comment;
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
        $tickets = Auth::user()->tickets()
            ->where('done', false)
            ->orderByDesc(Comment::select('created_at')
            ->whereColumn('comments.ticket_id', 'tickets.id')
            ->latest()
            ->take(1)
            )->paginate(5);
        // dd($tickets);
        // $tickets = Auth::user()->tickets()->where('done', false)->paginate(5);
        // $tickets = Auth::user()->tickets()->where('done', false)->orderBy(function ($ticket) {
        //     return $ticket->comments->sortByDesc('created_at')->first();
        // })->paginate(5);
        return view('ticket.tickets', ['tickets' => $tickets]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // dd($request);
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

        $ticket->users()->attach(Auth::id(), ['is_author' => true, 'is_responsible' => true]);

        if($request->hasFile('file')) {
            $path = $request->file('file')->store();

            $ticket->comments()->create([
                'text' => $validated['text'],
                'filename' => $request->file('file')->getClientOriginalName(),
                'filename_hash' => $path,
                'user_id' => Auth::id(),
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
        $ticket = Ticket::find($id);
        if (!$ticket || !$ticket->users->contains(Auth::user())) {
            abort(404);
        }
        return view('ticket.ticket', ['ticket' => $ticket]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ticket = Ticket::find($id);
        if (!$ticket || !$ticket->users->contains(Auth::user())) {
            abort(404);
        }

        return view('ticket.ticket_form', ['ticket' => $ticket]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ticket = Ticket::find($id);
        if (!$ticket || !$ticket->users->contains(Auth::user())) {
            abort(404);
        }

        $validated = $request->validate([
            'title' => 'required|string',
            'priority' => 'required|integer|min:0|max:3',
        ]);

        $ticket->update($validated);

        return redirect()->route('tickets.show', ['ticket' => $ticket->id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ticket = Ticket::find($id);
        if (!$ticket || !$ticket->users->contains(Auth::user())) {
            abort(404);
        }

        $ticket->delete();

        return redirect()->route('tickets.index');
    }

    /**
     * Display a listing of the closed tickets.
     */
    public function indexClosed()
    {
        // $tickets = Auth::user()->tickets->where('done', true)->sortByDesc(function ($ticket) {
        //     return $ticket->comments->sortByDesc('created_at')->first();
        // });
        $tickets = Auth::user()->tickets()->where('done', true)->paginate(5);
        return view('ticket.tickets', ['tickets' => $tickets]);
    }

    /**
     * Store a newly created comment in storage.
     */
    public function storeComment(Request $request, string $id)
    {
        $ticket = Ticket::find($id);
        if (!$ticket || !$ticket->users->contains(Auth::user())) {
            abort(404);
        }

        $validated = $request->validate([
            'text' => 'required|string|max:1000',
            'file' => 'nullable|file',
        ]);

        if($request->hasFile('file')) {
            $path = $request->file('file')->store();

            $ticket->comments()->create([
                'text' => $validated['text'],
                'filename' => $request->file('file')->getClientOriginalName(),
                'filename_hash' => $path,
                'user_id' => Auth::id(),
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
