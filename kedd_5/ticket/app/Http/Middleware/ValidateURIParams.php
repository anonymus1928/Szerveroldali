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
        $validator = Validator::make(['id' => $request->route()->parameters['ticket']], [ 'id' => 'integer' ]);
        if($validator->fails()) {
            return response()->json([ 'error' => 'Hibás URI paraméter!' ], 422);
        }
        return $next($request);
    }
}
