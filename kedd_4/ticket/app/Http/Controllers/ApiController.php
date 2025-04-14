<?php

namespace App\Http\Controllers;

use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function getTickets(Request $request, $id = null) {
        if(isset($id)) {
            // URI paraméter validáció

            // Natív
            // if(!filter_var($id, FILTER_VALIDATE_INT)) {
            //     return response()->json(['error' => 'Hibás URI paraméter!'], 422);
            // }

            // "Laraveles"
            // Validator::validate([ 'id' => $id ], [ 'id' => 'integer' ]);


            if($request->user()->tokenCan('ticket:admin')) {
                return new TicketResource(Ticket::with('comments')->with('owner')->with('users')->findOrFail($id));
            }
            return new TicketResource(Auth::user()->tickets()->with('comments')->with('owner')->with('users')->find($id));
        }

        if($request->user()->tokenCan('ticket:admin')) {
            return Ticket::with('comments')->with('owner')->with('users')->all();
        }
        return Auth::user()->tickets()->with('comments')->with('owner')->with('users')->get();
    }
}
