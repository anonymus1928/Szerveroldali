<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ApiController;
use App\Http\Middleware\ValidateURIParams;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [ApiAuthController::class, 'register'])->name('api.register');

Route::post('login', [ApiAuthController::class, 'login'])->name('api.login');


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('logout', [ApiAuthController::class, 'logout'])->name('api.logout');

    Route::post('tickets', [ApiController::class, 'createTicket'])->name('api.createTicket');

    Route::get('tickets/{ticket?}', [ApiController::class, 'getTickets'])->middleware(ValidateURIParams::class)->name('api.getTickets');

    Route::post('tickets/{ticket}/users', [ApiController::class, 'syncUsers'])->name('api.tickets.syncUsers')->where('ticket', '[0-9]+');
});
