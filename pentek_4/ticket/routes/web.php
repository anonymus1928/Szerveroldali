<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    // Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    // Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    // Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    // Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
    // Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
    // Route::patch('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update2');
    // Route::delete('/tickets/{ticket}', [TicketController::class, 'destory'])->name('tickets.destroy');

    Route::post('/tickets/{ticket}/comment', [TicketController::class, 'newComment'])
            ->name('tickets.newComment')
            ->where('ticket', '[0-9]+');
    Route::resource('tickets', TicketController::class);
    Route::get('/', function () {
        return redirect()->route('tickets.index');
    });
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
