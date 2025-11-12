<?php

use App\Http\Controllers\ApiAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    dd($request->headers);
    return response()->json(['status' => 'Hello there!', 'request' => $request->headers]);
});

Route::post('/register', [ApiAuthController::class, 'register']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
