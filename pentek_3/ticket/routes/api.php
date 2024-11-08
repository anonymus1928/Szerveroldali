<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('register', [ApiController::class, 'register'])->name('api.register');
Route::post('logout', [ApiController::class, 'logout'])->middleware('auth:sanctum')->name('api.logout');


