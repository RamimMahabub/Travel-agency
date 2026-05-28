<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/list-your-property', function () {
    return view('list-your-property');
})->name('list-your-property');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'role:customer'])->name('dashboard');

Route::get('/admin', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
    ->middleware([
        'auth',
        'verified',
        'role:admin,manager,support_agent,ticketing_officer,accounts_officer',
    ])
    ->name('admin.dashboard');


Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/booking/checkout/{flightId}', [\App\Http\Controllers\BookingController::class, 'checkout'])->name('booking.checkout');
    Route::post('/booking/store', [\App\Http\Controllers\BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{id}', [\App\Http\Controllers\BookingController::class, 'show'])->name('booking.show');
});

Route::get('/flights/search', [\App\Http\Controllers\SearchController::class, 'flights'])->name('flights.search');
Route::get('/ajax/airports/search', [\App\Http\Controllers\AirportController::class, 'search'])->name('airports.search');

Route::get('/integration-tester', function () {
    return view('integration-tester');
});

Route::get('/ajax/integration-test-execute', function (\App\Services\FlightServiceInterface $flightService) {
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
            'email' => 'test@ghuri.travel',
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

Route::get('/reseed-airports', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'Database\Seeders\AirportSeeder', '--force' => true]);
        $output = \Illuminate\Support\Facades\Artisan::output();
        return '<pre>' . e($output) . '</pre><br><a href="/">Back to homepage</a>';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

/* ================================================================
   HOTEL BOOKING MODULE — PROPERTY OWNER PMS ROUTES
   ================================================================ */
Route::middleware(['auth', 'verified', 'role:property_owner'])->prefix('property-owner')->name('property-owner.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\PropertyOwner\DashboardController::class, 'index'])->name('dashboard');

    // Properties (Hotels)
    Route::resource('hotels', \App\Http\Controllers\PropertyOwner\HotelController::class);
    Route::post('hotels/{hotel}/submit-approval', [\App\Http\Controllers\PropertyOwner\HotelController::class, 'submitForApproval'])->name('hotels.submit-approval');

    // Room Types (nested under hotels)
    Route::resource('hotels.rooms', \App\Http\Controllers\PropertyOwner\RoomController::class);
    Route::post('hotels/{hotel}/rooms/{room}/toggle-status', [\App\Http\Controllers\PropertyOwner\RoomController::class, 'toggleStatus'])->name('hotels.rooms.toggle-status');
    Route::post('hotels/{hotel}/rooms/{room}/duplicate', [\App\Http\Controllers\PropertyOwner\RoomController::class, 'duplicate'])->name('hotels.rooms.duplicate');

    // Rate Rules (nested under hotels)
    Route::resource('hotels.rate-rules', \App\Http\Controllers\PropertyOwner\RateRuleController::class);

    // Availability & Calendar
    Route::get('hotels/{hotel}/availability', [\App\Http\Controllers\PropertyOwner\AvailabilityController::class, 'index'])->name('availability.index');
    Route::post('hotels/{hotel}/availability/bulk-update', [\App\Http\Controllers\PropertyOwner\AvailabilityController::class, 'bulkUpdate'])->name('availability.bulk-update');

    // Bookings
    Route::resource('bookings', \App\Http\Controllers\PropertyOwner\BookingController::class)->only(['index', 'show', 'create', 'store']);
    Route::post('bookings/{booking}/confirm', [\App\Http\Controllers\PropertyOwner\BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('bookings/{booking}/cancel', [\App\Http\Controllers\PropertyOwner\BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('bookings/{booking}/check-in', [\App\Http\Controllers\PropertyOwner\BookingController::class, 'checkIn'])->name('bookings.check-in');
    Route::post('bookings/{booking}/check-out', [\App\Http\Controllers\PropertyOwner\BookingController::class, 'checkOut'])->name('bookings.check-out');
    Route::post('bookings/{booking}/no-show', [\App\Http\Controllers\PropertyOwner\BookingController::class, 'noShow'])->name('bookings.no-show');

    // Guests
    Route::resource('guests', \App\Http\Controllers\PropertyOwner\GuestController::class)->only(['index', 'show']);

    // Promotions
    Route::resource('promotions', \App\Http\Controllers\PropertyOwner\PromotionController::class);

    // Reviews
    Route::get('reviews', [\App\Http\Controllers\PropertyOwner\ReviewController::class, 'index'])->name('reviews.index');
    Route::post('reviews/{review}/respond', [\App\Http\Controllers\PropertyOwner\ReviewController::class, 'respond'])->name('reviews.respond');

    // Settings
    Route::get('settings', [\App\Http\Controllers\PropertyOwner\SettingsController::class, 'index'])->name('settings');
    Route::put('settings', [\App\Http\Controllers\PropertyOwner\SettingsController::class, 'update'])->name('settings.update');
});

/* ================================================================
   HOTEL BOOKING MODULE — GUEST-FACING ROUTES
   ================================================================ */

// Public hotel browsing
Route::get('/hotels/search', [\App\Http\Controllers\HotelSearchController::class, 'index'])->name('hotels.search');
Route::get('/hotels/{property}', [\App\Http\Controllers\HotelController::class, 'show'])->name('hotels.show');

// Authenticated guest booking
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/hotels/book/{property}/{roomType}', [\App\Http\Controllers\HotelBookingController::class, 'step1'])->name('hotels.book.step1');
    Route::post('/hotels/book/step2', [\App\Http\Controllers\HotelBookingController::class, 'step2'])->name('hotels.book.step2');
    Route::post('/hotels/book/step3', [\App\Http\Controllers\HotelBookingController::class, 'step3'])->name('hotels.book.step3');
    Route::post('/hotels/book/confirm', [\App\Http\Controllers\HotelBookingController::class, 'confirm'])->name('hotels.book.confirm');
    Route::get('/hotels/booking/confirmation/{booking}', [\App\Http\Controllers\HotelBookingController::class, 'confirmation'])->name('hotels.book.confirmation');

    Route::get('/my-bookings', [\App\Http\Controllers\MyBookingsController::class, 'index'])->name('my-bookings.index');
    Route::get('/my-bookings/{booking}', [\App\Http\Controllers\MyBookingsController::class, 'show'])->name('my-bookings.show');
    Route::post('/my-bookings/{booking}/cancel', [\App\Http\Controllers\MyBookingsController::class, 'cancel'])->name('my-bookings.cancel');
    Route::get('/my-bookings/{booking}/voucher', [\App\Http\Controllers\MyBookingsController::class, 'voucher'])->name('my-bookings.voucher');
});

/* ================================================================
   HOTEL BOOKING MODULE — ADMIN ROUTES
   ================================================================ */
Route::middleware(['auth', 'verified', 'role:admin,manager'])->prefix('admin')->name('admin.')->group(function () {
    // Property Approval
    Route::get('/properties', [\App\Http\Controllers\Admin\PropertyApprovalController::class, 'index'])->name('properties.index');
    Route::get('/properties/{property}/review', [\App\Http\Controllers\Admin\PropertyApprovalController::class, 'review'])->name('properties.review');
    Route::post('/properties/{property}/approve', [\App\Http\Controllers\Admin\PropertyApprovalController::class, 'approve'])->name('properties.approve');
    Route::post('/properties/{property}/reject', [\App\Http\Controllers\Admin\PropertyApprovalController::class, 'reject'])->name('properties.reject');
    Route::post('/properties/{property}/request-changes', [\App\Http\Controllers\Admin\PropertyApprovalController::class, 'requestChanges'])->name('properties.request-changes');

    // Commissions & Payouts
    Route::get('/commissions', [\App\Http\Controllers\Admin\CommissionController::class, 'index'])->name('commissions.index');
    Route::post('/commissions', [\App\Http\Controllers\Admin\CommissionController::class, 'updateGlobal'])->name('commissions.update-global');
    Route::post('/commissions/property/{property}', [\App\Http\Controllers\Admin\CommissionController::class, 'updateProperty'])->name('commissions.update-property');
    
    Route::get('/payouts', [\App\Http\Controllers\Admin\PayoutController::class, 'index'])->name('payouts.index');
    Route::post('/payouts/{property}', [\App\Http\Controllers\Admin\PayoutController::class, 'process'])->name('payouts.process');

    // Global Bookings
    Route::get('/bookings', [\App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [\App\Http\Controllers\Admin\BookingController::class, 'show'])->name('bookings.show');

    // User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['create', 'store', 'show']);
});
