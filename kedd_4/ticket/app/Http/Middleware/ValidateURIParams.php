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
        $ticket = $request->route()->parameters['ticket'];
        if(isset($ticket) && !filter_var($ticket, FILTER_VALIDATE_INT)) {
            return response()->json(['error' => 'Hibás URI paraméter!'], 422);
        }
        return $next($request);
    }
}
