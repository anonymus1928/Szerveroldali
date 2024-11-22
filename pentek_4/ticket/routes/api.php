<?php

use App\Http\Controllers\ApiController;
use App\Http\Middleware\ValidateURIParams;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentikáció
Route::post('register', [ApiController::class, 'register'])->name('api.register');
Route::post('login', [ApiController::class, 'login'])->name('api.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [ApiController::class, 'logout'])->name('api.logout');
    Route::get('/user', function (Request $request) {
        return $request->user();
    });


    // URI Paraméterek
    Route::get('uri-params1/{number}/{string}/{optional?}', function ($number, $string, $optional = null) {
        return response()->json([
            'number' => $number,
            'string' => $string,
            'optional' => $optional,
        ]);
    })->where('number', '[0-9]+')->where('string', '[A-Za-z]+');

    Route::get('uri-params2/{number}/{string}/{optional?}', function ($number, $string, $optional = null) {
        return response()->json([
            'number' => $number,
            'string' => $string,
            'optional' => $optional,
        ]);
    })->middleware([ValidateURIParams::class]);



    // CRUD
    Route::post('/tickets',            [ApiController::class, 'store'])                            ->name('api.store');
    Route::get('/tickets/{ticket?}',   [ApiController::class, 'getTickets'])->where('id', '[0-9]+')->name('api.getTickets');
    Route::put('/tickets/{ticket}',    [ApiController::class, 'update'])    ->where('id', '[0-9]+')->name('api.update');
    Route::delete('/tickets/{ticket}', [ApiController::class, 'destory'])   ->where('id', '[0-9]+')->name('api.destroy');
});

