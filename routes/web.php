<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/booking/checkout/{flightId}', [\App\Http\Controllers\BookingController::class, 'checkout'])->name('booking.checkout');
    Route::post('/booking/store', [\App\Http\Controllers\BookingController::class, 'store'])->name('booking.store');
});

Route::get('/flights/search', [\App\Http\Controllers\SearchController::class, 'flights'])->name('flights.search');

require __DIR__.'/auth.php';

Route::get('/setup-db', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--force' => true]);
        return 'Database mounted and migrated successfully! You can now <a href="/">go to the homepage</a>.';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});
