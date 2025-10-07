<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Models\Ticket;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('tickets.index'));
});


// Route::get(   '/tickets',           [TicketController::class, 'index']) ->name('tickets.index');
// Route::get(   '/tickets/create',    [TicketController::class, 'create'])->name('tickets.create');
// Route::post(  '/tickets',           [TicketController::class, 'store']) ->name('tickets.store');
// Route::get(   '/tickets/{id}',      [TicketController::class, 'show'])  ->where('id', '[0-9]+')->name('tickets.show');
// Route::get(   '/tickets/{id}/edit', [TicketController::class, 'edit'])  ->where('id', '[0-9]+')->name('tickets.edit');
// Route::put(   '/tickets/{id}',      [TicketController::class, 'update'])->where('id', '[0-9]+')->name('tickets.update');
// Route::delete('/tickets/{id}',      [TicketController::class, 'delete'])->where('id', '[0-9]+')->name('tickets.delete');


Route::resource('tickets', TicketController::class);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
