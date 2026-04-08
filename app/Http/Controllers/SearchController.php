<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected $flightService;

    public function __construct(\App\Services\FlightServiceInterface $flightService)
    {
        $this->flightService = $flightService;
    }

    public function flights(Request $request)
    {
        $validated = $request->validate([
            'origin' => 'required|string',
            'destination' => 'required|string',
            'date' => 'required|date',
            'passengers' => 'required|integer|min:1',
        ]);

        $flights = $this->flightService->search(
            $validated['origin'],
            $validated['destination'],
            $validated['date'],
            $validated['passengers']
        );

        return view('flights.results', [
            'flights' => $flights,
            'search' => $validated
        ]);
    }
}
