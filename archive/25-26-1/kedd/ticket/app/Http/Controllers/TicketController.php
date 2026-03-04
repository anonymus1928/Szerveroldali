<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Authorizáció Gate-el és Policy-vel
        if(Gate::allows('view-any')) {
            // Admin-ok vagyunk
            $tickets = Ticket::where('done', false)
                              ->orderBYDesc(
                                Comment::select('updated_at')
                                        ->whereColumn('comments.ticket_id', 'tickets.id')
                                        ->latest()
                                        ->take(1)
                            )
                              ->paginate(10);
        } else {
            // Nem vagyunk adminok
            $tickets = Auth::user()
                            ->tickets()
                            ->where('done', false)
                            ->orderBYDesc(
                                Comment::select('updated_at')
                                        ->whereColumn('comments.ticket_id', 'tickets.id')
                                        ->latest('updated_at')
                                        ->take(1)
                            )
                            ->paginate(10);
        }

        // Authorizáció Gate és Policy nélkül
        // if(Auth::user()->admin) {
        //     $tickets = Ticket::where('done', false)
        //                       ->orderBYDesc(
        //                         Comment::select('updated_at')
        //                                 ->whereColumn('comments.ticket_id', 'tickets.id')
        //                                 ->latest()
        //                                 ->take(1)
        //                     )
        //                       ->paginate(10);
        // } else {
        //     $tickets = Auth::user()
        //                     ->tickets()
        //                     ->where('done', false)
        //                     ->orderBYDesc(
        //                         Comment::select('updated_at')
        //                                 ->whereColumn('comments.ticket_id', 'tickets.id')
        //                                 ->latest('updated_at')
        //                                 ->take(1)
        //                     )
        //                     ->paginate(10);
        // }
        return view('ticket.tickets', ['tickets' => $tickets]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Authorizáció Gate-el és Policy-val
        // (felesleges, mert konstans true-t ad vissza a Gate)
        if(!Gate::allows('create')) {
            abort(401);
        }

        
        return view('ticket.ticketform');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Authorizáció Gate-el és Policy-val
        // (felesleges, mert konstans true-t ad vissza a Gate)
        if(!Gate::allows('create')) {
            abort(401);
        }


        // $request->validate([
        //     'title' => 'required|string|min:5|max:128|ends_with:please',
        //     'priority' => 'required|integer|min:0|max:3',
        //     'text' => 'required|string|max:1000',
        // ]);
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:5|max:128|ends_with:please',
            'priority' => 'required|integer|min:0|max:3',
            'text' => 'required|string|max:1000',
            'file' => 'file',
        ], [
            'required' => ':attribute mező kitöltése kötelező!',
            'string' => ':attribute mező csak szöveget tartalmazhat!',
            'min' => ':attribute legyen minimum: :min',
            'max' => ':attribute legyen maximum: :max',
            'ends_with' => 'Bunkó vagy!',
            'integer' => ':attribute mező csak egész számot tartalmazhat!',
            'file' => ':attribute mező csak fájlt tartalmazhat!',
        ], [
            'title' => 'A cím',
            'priority' => 'A priorítás',
            'text' => 'A hibalírás',
            'file' => 'A fájl',
        ]);

        if ($validator->fails()) {
            return redirect(route('tickets.create'))->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $ticket = Ticket::create($validated);
        $ticket->users()->attach(Auth::id(), ['owner' => true]);

        // Save the uploaded file
        if ($request->hasFile('file')) {
            $filename_hash = $request->file('file')->store();

            $ticket->comments()->create([
                'description' => $validated['text'],
                'user_id' => Auth::id(),
                'filename' => $request->file('file')->getClientOriginalName(),
                'filename_hash' => $filename_hash,
            ]);
        } else {
            $ticket->comments()->create([
                'description' => $validated['text'],
                'user_id' => Auth::id(),
            ]);
        }

        return redirect(route('tickets.show', ['ticket' => $ticket->id]));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ticket = Ticket::findOrFail($id);

        // Authorizáció Gate-el és Policy-val
        if(!Gate::allows('view', $ticket)) {
            abort(401);
        }

        // Authorizáció Gate és Policy nélkül
        // if(!Auth::user()->admin && !$ticket->users->contains(Auth::user())) {
        //     abort(401);
        // }

        return view('ticket.ticket', ['ticket' => $ticket]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ticket = Ticket::findOrFail($id);

        // Authorizáció Gate-el és Policy-val
        if(!Gate::allows('update', $ticket)) {
            abort(401);
        }

        // Authorizáció Gate és Policy nélkül
        // if(!Auth::user()->admin && !$ticket->users->contains(Auth::user())) {
        //     abort(401);
        // }

        return view('ticket.ticketform', ['ticket' => $ticket]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ticket = Ticket::findOrFail($id);

        // Authorizáció Gate-el és Policy-val
        if(!Gate::allows('update', $ticket)) {
            abort(401);
        }

        // Authorizáció Gate és Policy nélkül
        // if(!Auth::user()->admin && !$ticket->users->contains(Auth::user())) {
        //     abort(401);
        // }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:5|max:128|ends_with:please',
            'priority' => 'required|integer|min:0|max:3',
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
        ]);

        if ($validator->fails()) {
            return redirect(route('tickets.edit', ['ticket' => $ticket->id]))->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();


        $ticket->update($validated);

        // $ticket->title = $validated['title'];
        // $ticket->priority = $validated['priority'];
        // $ticket->save();

        return redirect(route('tickets.show', ['ticket' => $ticket->id]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ticket = Ticket::findOrFail($id);

        // Authorizáció Gate-el és Policy-val
        if(!Gate::allows('delete', $ticket)) {
            abort(401);
        }

        // Authorizáció Gate és Policy nélkül
        // if(!Auth::user()->admin && !$ticket->users->contains(Auth::user())) {
        //     abort(401);
        // }

        $ticket->delete();

        return redirect(route('tickets.index'));
    }

    /**
     * Create a new comment for a ticket.
     */
    public function addComment(Request $request, string $id)
    {
        $ticket = Ticket::findOrFail($id);

        // Authorizáció Gate-el és Policy-val
        // Lehet készíteni külön egy policy-t
        // új comment létrehozására, de itt most
        // újra felhasználom az "update"-et
        if(!Gate::allows('update', $ticket)) {
            abort(401);
        }

        $validated = $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        // $ticket = Ticket::find($id);
        // if(!isset($id)) {
        //     abort(404);
        // }

        if ($request->hasFile('file')) {
            $filename_hash = $request->file('file')->store();

            $ticket->comments()->create([
                'description' => $validated['text'],
                'user_id' => Auth::id(),
                'filename' => $request->file('file')->getClientOriginalName(),
                'filename_hash' => $filename_hash,
            ]);
        } else {
            $ticket->comments()->create([
                'description' => $validated['text'],
                'user_id' => Auth::id(),
            ]);
        }

        return redirect(route('tickets.show', ['ticket' => $ticket->id]));
    }
}
