<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    protected $flightService;

    public function __construct(\App\Services\FlightServiceInterface $flightService)
    {
        $this->flightService = $flightService;
    }

    public function checkout(Request $request, $flightId)
    {
        $priceInfo = $this->flightService->price($flightId);
        
        return view('bookings.checkout', [
            'flightId' => $flightId,
            'priceInfo' => $priceInfo,
            'passengers' => $request->query('passengers', 1)
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'flight_id' => 'required|string',
            'first_name' => 'required|array',
            'last_name' => 'required|array',
            'payment_method' => 'required|string'
        ]);

        $passengerDetails = [];
        foreach($request->first_name as $index => $fname) {
            $passengerDetails[] = [
                'first_name' => $fname,
                'last_name' => $request->last_name[$index] ?? '',
            ];
        }

        // Call Service
        $apiResponse = $this->flightService->book($request->flight_id, $passengerDetails);
        $priceInfo = $this->flightService->price($request->flight_id);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Store Locally
        $booking = \App\Models\Booking::create([
            'user_id' => $user->id,
            'type' => 'flight',
            'api_reference_id' => $apiResponse['api_reference_id'] ?? 'N/A',
            'total_amount' => $priceInfo['total_price'] ?? 0,
            'status' => 'confirmed'
        ]);

        foreach($passengerDetails as $p) {
            $booking->passengers()->create($p);
        }

        $booking->payments()->create([
            'provider' => $request->payment_method,
            'amount' => $priceInfo['total_price'] ?? 0,
            'status' => 'successful' // Mocking successful payment
        ]);

        return redirect()->route('dashboard')->with('success', 'Booking Confirmed! PNR: ' . $booking->api_reference_id);
    }
}
