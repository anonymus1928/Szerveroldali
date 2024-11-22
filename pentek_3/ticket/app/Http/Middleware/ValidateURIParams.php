<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
        $validator = Validator::make($request->route()->parameters(), [
            'number' => 'integer',
            'string' => 'alpha',
            'optional' => 'nullable',
        ]);
        if($validator->fails()) {
            return response()->json([
                'error' => $validator->messages(),
            ], 418);
        }
        return $next($request);
    }
}
