<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ApiController;
use App\Http\Middleware\ValidateURIParams;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    dd($request->headers);
    return response()->json(['status' => 'Hello there!', 'request' => $request->headers]);
});

Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/logout', [ApiAuthController::class, 'logout']);
    Route::get('/tickets/{ticket?}', [ApiController::class, 'getTickets'])->middleware(ValidateURIParams::class);
    Route::post('/tickets', [ApiController::class, 'createTicket']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
