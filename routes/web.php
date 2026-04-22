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
    Route::get('/booking/{id}', [\App\Http\Controllers\BookingController::class, 'show'])->name('booking.show');
});

Route::get('/flights/search', [\App\Http\Controllers\SearchController::class, 'flights'])->name('flights.search');
Route::get('/ajax/airports/search', [\App\Http\Controllers\AirportController::class, 'search'])->name('airports.search');

Route::get('/trawex-tester', function () {
    return view('trawex-tester');
});

Route::get('/ajax/trawex-test-execute', function (\App\Services\FlightServiceInterface $flightService) {
    try {
        $logs = [];

        // 1. Search
        $flights = $flightService->search('DEL', 'DXB', '2026-06-05', 1, 'OneWay');
        if(empty($flights)) {
            $logs[] = ['step' => 'Search', 'message' => 'No flights found. Check credentials, dates, or IP.', 'type' => 'error'];
            return response()->json(['logs' => $logs]);
        }
        $logs[] = [
            'step' => 'Search', 
            'message' => 'Successfully found ' . count($flights) . ' flights.', 
            'type' => 'success', 
            'data' => array_slice($flights, 0, 3) // show top 3
        ];

        // 2. Revalidate
        $flightId = $flights[0]['id'];
        $priceData = $flightService->price($flightId);

        if($priceData['status'] !== 'available') {
            $logs[] = ['step' => 'Validate Fare', 'message' => 'Fare validation rejected or expired.', 'type' => 'error', 'data' => $priceData];
            return response()->json(['logs' => $logs]);
        }
        $logs[] = [
            'step' => 'Validate Fare', 
            'message' => 'Fare validated successfully.', 
            'type' => 'success', 
            'data' => $priceData
        ];

        // 3. Book
        $passengers = [[
            'first_name' => 'Paul',
            'last_name' => 'Richard',
            'dob' => '1990-01-01',
            'title' => 'Mr',
            'email' => 'test@trawex.com',
            'phone' => '1234567890',
            'nationality' => 'IN'
        ]];

        try {
            $bookingData = $flightService->book($flightId, $passengers);
            $logs[] = ['step' => 'Book Flight', 'message' => 'Booking API Hit Successfully.', 'type' => 'success', 'data' => $bookingData];
        } catch (\Exception $e) {
            $logs[] = ['step' => 'Book Flight', 'message' => 'Booking Provider Rejection (Expected in Sandbox)', 'type' => 'error', 'data' => ['error' => $e->getMessage()]];
        }

        return response()->json(['logs' => $logs]);
    } catch (\Exception $e) {
        return response()->json(['logs' => [['step' => 'System', 'message' => $e->getMessage(), 'type' => 'error']]]);
    }
});

require __DIR__.'/auth.php';

Route::get('/setup-db', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--force' => true]);
        return 'Database mounted and migrated successfully! You can now <a href="/">go to the homepage</a>.';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});
