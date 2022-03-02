<?php

use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/feladatok', [TicketController::class, 'show']);

Route::get('/feladat/{id}', [TicketController::class, 'showTicket'])->where('id', '[0-9]+');

Route::get('/felhasznalok', [UserController::class, 'show']);

require __DIR__.'/auth.php';
