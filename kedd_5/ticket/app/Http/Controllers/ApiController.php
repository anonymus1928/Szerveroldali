<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function getTickets(Request $request, $id = null) {
        // URI paraméter validálása

        // if(!filter_var($id, FILTER_VALIDATE_INT)) {
        //     return response()->json(['error' => 'Hibás URI paraméter!'], 422);
        // }

        // Validator::validate(['id' => $id], [ 'id' => 'integer' ]);

        if(isset($id)) {
            if($request->user()->tokenCan('ticket:admin')) {
                return Ticket::findOrFail($id);
            }
            return Auth::user()->tickets()->findOrFail($id);
        }
        if($request->user()->tokenCan('ticket:admin')) {
            return Ticket::all();
        }
        return Auth::user()->tickets;
    }
}
