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
        $tickets = Auth::user()
                        ->tickets()
                        ->where('done', false)
                        ->orderByDesc(
                            Comment::select('updated_at')
                                    ->whereColumn('comments.ticket_id', 'tickets.id')
                                    ->latest()
                                    ->take(1)
                        )
                        ->paginate(5);


        return view('tickets.tickets', ['tickets' => $tickets]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tickets.ticketform');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'title' => 'required|string|min:5|max:100',
            'priority' => 'required|integer|min:0|max:3',
            'text' => 'required|string|max:1000',
            'file' => 'nullable|file',
        ]);

        // Insert into db
        $ticket = Ticket::create($validated);

        // $t = new Ticket();
        // $t->title = $validated['title'];
        // $t->priority = $validated['priority'];
        // $t->save();

        // Attach ticket and user
        $ticket->users()->attach(Auth::id(), ['owner' => true]);

        // Create the first comment
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

        // Redirect the user to the ticket's page
        return redirect()->route('tickets.show', ['ticket' => $ticket->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ticket = Ticket::findOrFail($id);
        // $ticket = Ticket::find($id);
        // if(!$ticket) {
        //     abort(404);
        // }

        // Authorization
        if(!$ticket->users->contains(Auth::id()) && !Auth::user()->admin) {
            abort(401);
        }
        return view('tickets.ticket', ['ticket' => $ticket]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ticket = Ticket::findOrFail($id);

        // Authorization
        if(!$ticket->users->contains(Auth::id()) && !Auth::user()->admin) {
            abort(401);
        }

        return view('tickets.ticketform', ['ticket' => $ticket]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ticket = Ticket::findOrFail($id);

        // Authorization
        if(!$ticket->users->contains(Auth::id()) && !Auth::user()->admin) {
            abort(401);
        }

        $validated = $request->validate([
            'title' => 'required|string|min:5|max:100',
            'priority' => 'required|integer|min:0|max:3',
        ]);

        $ticket->update($validated);
        // $ticket->title = $validated['title'];
        // $ticket->priority = $validated['priority'];
        // $ticket->save();

        return redirect()->route('tickets.show', ['ticket' => $ticket->id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ticket = Ticket::findOrFail($id);

        // Authorization
        if(!$ticket->users->contains(Auth::id()) && !Auth::user()->admin) {
            abort(401);
        }

        $ticket->comments()->delete();

        $ticket->delete();

        return redirect()->route('tickets.index');
    }
}
