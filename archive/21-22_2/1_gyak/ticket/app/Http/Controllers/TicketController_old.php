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

    public function showTicket($id) {
        $ticket = Ticket::find($id);
        return view('site.ticket', ['ticket' => $ticket]);
    }
}
