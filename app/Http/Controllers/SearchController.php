<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            'trip_type' => 'nullable|string|in:one_way,round_way,multi_city',
            'return_date' => 'nullable|date|after_or_equal:date|required_if:trip_type,round_way',
            'class' => 'nullable|string|in:economy,premium_economy,business,first',
        ]);

        if (($validated['trip_type'] ?? 'one_way') === 'multi_city') {
            return back()
                ->withInput()
                ->withErrors(['search' => 'Multi-city search needs additional segment inputs and is not enabled in this form yet.']);
        }

        try {
            $flights = $this->flightService->search(
                strtoupper($validated['origin']),
                strtoupper($validated['destination']),
                $validated['date'],
                (int) $validated['passengers'],
                $validated['trip_type'] ?? 'one_way',
                $validated['return_date'] ?? null,
                $validated['class'] ?? 'economy'
            );
        } catch (\Throwable $e) {
            Log::error('Flight search failed', [
                'message' => $e->getMessage(),
                'trip_type' => $validated['trip_type'] ?? 'one_way',
                'origin' => $validated['origin'] ?? null,
                'destination' => $validated['destination'] ?? null,
            ]);

            return back()
                ->withInput()
                ->withErrors(['search' => 'Search failed: ' . $e->getMessage()]);
        }

        return view('flights.results', [
            'flights' => $flights,
            'search' => $validated
        ]);
    }
}
