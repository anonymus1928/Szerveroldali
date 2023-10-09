<?php

use App\Http\Controllers\ProfileController;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        $tickets = Auth::user()->tickets;
        return view('ticket.tickets', ['tickets' => $tickets]);
    });
    Route::get('/{id}', function (int $id) {
        $ticket = Ticket::find($id);
        if (!$ticket || !$ticket->users->contains(Auth::user())) {
            abort(404);
        }
        return view('ticket.ticket', ['ticket' => $ticket]);
    })->where('id', '[0-9]+');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
