<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::with('comments')
                           ->where('done', false)
                           ->orderByDesc(
                                Comment::select('updated_at')
                                       ->whereColumn('comments.ticket_id', 'tickets.id')
                                       ->latest('updated_at')
                                       ->take(1)
                           )
                           ->paginate(5);
                        //    ->get();
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
            'title' => [
                'required',
                'string',
                'max:100',
                'ends_with:please',
                // Egyedi rule
                // function (string $attribute, mixed $value, Closure $fail) { $fail('valami gond van'); }
            ],
            'priority' => ['required', 'integer', 'between:0,3'],
            'text' => ['required', 'string'],
        ]);

        $ticket = Ticket::create($validated);
        $ticket->users()->attach(Auth::id(), ['is_owner' => true]);

        $ticket->comments()->create([
            'description' => $validated['text'],
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('tickets.show', ['ticket' => $ticket->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ticket = Ticket::with('comments')->findOrFail($id);
        // $ticket = Ticket::with('comments')->find($id);
        // if (!$ticket) {
        //     abort(404);
        // }

        return view('ticket.ticket', ['ticket' => $ticket]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ticket = Ticket::findOrFail($id);

        return view('ticket.ticketform', ['ticket' => $ticket]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:100',
                'ends_with:please',
                // Egyedi rule
                // function (string $attribute, mixed $value, Closure $fail) { $fail('valami gond van'); }
            ],
            'priority' => ['required', 'integer', 'between:0,3'],
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
        //
    }
}
