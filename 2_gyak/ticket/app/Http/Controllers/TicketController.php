<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function show() {
        $tickets = Ticket::all();
        return view('site.tickets', ['tickets' => $tickets]);
    }

    public function ticketShow($id) {
        $ticket = Ticket::findOrFail($id);
        return view('site.ticket', ['ticket' => $ticket]);
    }
}
