<?php
use App\Services\TrawexFlightService;

$service = app(TrawexFlightService::class);

echo "\n--- STARTING TRAWEX API TEST ---\n";
echo "1. Searching Flights (DEL -> DXB for 2026-06-05, 1 Adult)\n";

$flights = $service->search('DEL', 'DXB', '2026-06-05', 1, 'OneWay');

if (empty($flights)) {
    echo "FAILED: No flights returned. Check API credentials, IP whitelisting or Dates.\n";
    exit;
}

echo "SUCCESS: Found " . count($flights) . " flights.\n";
echo "Lowest Price: $" . $flights[0]['price'] . " " . $flights[0]['currency'] . "\n";
echo "Airline: " . $flights[0]['airline'] . " | Flight: " . $flights[0]['flight_number'] . "\n";

$flightId = $flights[0]['id'];

echo "\n2. Validating Fare (Revalidate)\n";
$priceData = $service->price($flightId);

if ($priceData['status'] !== 'available') {
    echo "FAILED: Fare Validation rejected by Trawex. Output:\n";
    print_r($priceData);
    exit;
}

echo "SUCCESS: Fare is Valid. Updated Price: $" . $priceData['total_price'] . " " . $priceData['currency'] . "\n";

echo "\n3. Booking Flight\n";

$passengers = [
    [
        'first_name' => 'Paul',
        'last_name' => 'Richard',
        'dob' => '1990-01-01',
        'title' => 'Mr',
        'email' => 'test@trawex.com',
        'phone' => '1234567890',
        'nationality' => 'IN'
    ]
];

try {
    $bookingData = $service->book($flightId, $passengers);
    echo "SUCCESS: Booking Completed!\n";
    print_r($bookingData);
} catch (\Exception $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
}

echo "\n--- TEST COMPLETE ---\n";
