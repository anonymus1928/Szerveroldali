<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets = Auth::user()->tickets->where('done', false);
        return view('site.tickets', ['tickets' => $tickets]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('site.modify-ticket');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'priority' => 'required|integer|min:0|max:3',
            'text' => 'required|string|max:1000',
            'file' => 'file',
        ]);
        $ticket = new Ticket($validated);
        $ticket->save();

        $ticket->users()->attach(Auth::id(), ['is_responsible' => true, 'is_submitter' => true]);


        $comment = new Comment($validated);
        $comment->ticket()->associate($ticket);
        $comment->user()->associate(Auth::user());
        if($request->hasFile('file')) {
            $comment->filename = $request->file('file')->getClientOriginalName();
            $comment->filename_hash = $request->file('file')->store('public');
        }
        $comment->save();

        return redirect()->route('feladatok.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);
        if (!$ticket->users->contains(Auth::id()) && !Auth::user()->is_admin) {
            abort(401);
        }
        return view('site.ticket', ['ticket' => $ticket]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);
        if (!$ticket->users->contains(Auth::id()) && !Auth::user()->is_admin) {
            abort(401);
        }
        return view('site.modify-ticket', ['ticket' => $ticket]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'priority' => 'required|integer|min:0|max:3',
        ]);
        $ticket = Ticket::findOrFail($id);
        if (!$ticket->users->contains(Auth::id()) && !Auth::user()->is_admin) {
            abort(401);
        }
        $ticket->update($validated);

        return redirect()->route('feladatok.show', ['feladatok' => $ticket->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        if (!$ticket->users->contains(Auth::id()) && !Auth::user()->is_admin) {
            abort(401);
        }
        $ticket->delete();
        return redirect()->route('feladatok.index');
    }

    public function showUsers($id) {
        $ticket = Ticket::findOrFail($id);
        if (!$ticket->users->contains(Auth::id()) && !Auth::user()->is_admin) {
            abort(401);
        }
        $otherUsers = User::all()->diff($ticket->users);
        return view('site.ticket-user', ['ticket' => $ticket, 'otherUsers' => $otherUsers]);
    }

    public function addUser(Request $request, $id, $userId) {
        $ticket = Ticket::findOrFail($id);
        if (!$ticket->users->contains(Auth::id()) && !Auth::user()->is_admin) {
            abort(401);
        }
        $ticket->users()->attach($userId, ['is_responsible' => $request->is_responsible === 'true']);
        return redirect()->route('feladatok.felhasznalok', ['feladatok' => $ticket->id]);
    }

    public function removeUser($id, $userId) {
        $ticket = Ticket::findOrFail($id);
        if (!$ticket->users->contains(Auth::id()) && !Auth::user()->is_admin) {
            abort(401);
        }
        $ticket->users()->detach($userId);
        return redirect()->route('feladatok.felhasznalok', ['feladatok' => $ticket->id]);
    }
}
