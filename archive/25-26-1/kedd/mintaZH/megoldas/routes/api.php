<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/locations', [ApiController::class, 'task1']);

Route::get('/locations/{id}', [ApiController::class, 'task2']);

Route::post('/locations', [ApiController::class, 'task3']);

Route::post('/login', [ApiController::class, 'task5']);

Route::get('/local-weather-log', [ApiController::class, 'task6'])->middleware('auth:sanctum');

Route::post('/insert-many', [ApiController::class, 'task7'])->middleware('auth:sanctum');
