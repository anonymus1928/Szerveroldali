<?php

use App\Http\Controllers\ApiAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [ ApiAuthController::class, 'register' ])->name('api.register');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
