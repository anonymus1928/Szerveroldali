<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateURIParams
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ticketID = $request->route()->parameters()['ticket'] ?? null;

        if(isset($ticketID) && !filter_var($ticketID, FILTER_VALIDATE_INT)) {
            return response()->json(['error' => 'Hibás URI paraméter!'], 422);
        }
        return $next($request);
    }
}
