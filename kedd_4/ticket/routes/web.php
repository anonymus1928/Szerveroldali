<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Read
Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->where('ticket', '[0-9]+')->name('tickets.show');

// Create
Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');

// Update
Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->where('ticket', '[0-9]+')->name('tickets.edit');
Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->where('ticket', '[0-9]+')->name('tickets.update');

// Delete
Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->where('ticket', '[0-9]+')->name('tickets.destroy');

// Route::resource('/tickets', TicketController::class);



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
