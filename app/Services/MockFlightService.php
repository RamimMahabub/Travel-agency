<?php

namespace App\Services;

use Illuminate\Support\Str;

class MockFlightService implements FlightServiceInterface
{
    public function search(string $origin, string $destination, string $date, int $passengers): array
    {
        return [
            [
                'id' => 'fl_' . Str::random(10),
                'airline' => 'Biman Bangladesh Airlines',
                'flight_number' => 'BG-101',
                'origin' => $origin,
                'destination' => $destination,
                'departure_time' => $date . ' 10:00:00',
                'arrival_time' => $date . ' 12:30:00',
                'duration' => '2h 30m',
                'price' => 5500 * $passengers,
                'currency' => 'BDT',
                'stops' => 0,
            ],
            [
                'id' => 'fl_' . Str::random(10),
                'airline' => 'US-Bangla Airlines',
                'flight_number' => 'BS-202',
                'origin' => $origin,
                'destination' => $destination,
                'departure_time' => $date . ' 14:00:00',
                'arrival_time' => $date . ' 16:15:00',
                'duration' => '2h 15m',
                'price' => 5200 * $passengers,
                'currency' => 'BDT',
                'stops' => 0,
            ],
            [
                'id' => 'fl_' . Str::random(10),
                'airline' => 'Novoair',
                'flight_number' => 'VQ-303',
                'origin' => $origin,
                'destination' => $destination,
                'departure_time' => $date . ' 18:30:00',
                'arrival_time' => $date . ' 21:00:00',
                'duration' => '2h 30m',
                'price' => 4800 * $passengers,
                'currency' => 'BDT',
                'stops' => 1,
            ]
        ];
    }

    public function price(string $flightId): array
    {
        // Mock a confirmed price block
        return [
            'flight_id' => $flightId,
            'price_confirmed' => true,
            'total_price' => 5500, // mock price
            'currency' => 'BDT',
        ];
    }

    public function book(string $flightId, array $passengerDetails): array
    {
        // Mock booking successfully with a PNR
        return [
            'status' => 'confirmed',
            'api_reference_id' => strtoupper(Str::random(6)),
            'flight_id' => $flightId,
            'message' => 'Booking confirmed via Mock GDS',
        ];
    }
}
