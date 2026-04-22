<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TrawexFlightService implements FlightServiceInterface
{
    protected string $baseUrl;
    protected string $userId;
    protected string $userPassword;
    protected string $access;

    public function __construct()
    {
        $this->baseUrl = config('trawex.url') ?? '';
        $this->userId = config('trawex.user_id') ?? '';
        $this->userPassword = config('trawex.password') ?? '';
        $this->access = config('trawex.access') ?? '';
    }

    /**
     * Search Flights
     */
    public function search(
        string $origin,
        string $destination,
        string $date,
        int $passengers,
        string $tripType = 'one_way',
        ?string $returnDate = null,
        string $cabinClass = 'economy'
    ): array
    {
        if (empty($this->userId)) {
            // Failsafe empty array if user hasn't setup keys yet
            return [];
        }

        $journeyType = 'OneWay';
        if ($tripType === 'round_way' || $tripType === 'round_trip' || $returnDate !== null) {
            $journeyType = 'Return';
        }

        $originDestInfo = [
            'departureDate' => $date,
            'airportOriginCode' => $origin,
            'airportDestinationCode' => $destination,
        ];

        if ($journeyType === 'Return') {
            $originDestInfo['returnDate'] = $returnDate;
        }

        $classMap = [
            'economy' => 'Economy',
            'premium_economy' => 'PremiumEconomy',
            'business' => 'Business',
            'first' => 'First',
        ];

        $payload = [
            'user_id' => $this->userId,
            'user_password' => $this->userPassword,
            'access' => $this->access,
            'ip_address' => config('trawex.ip_address'),
            'requiredCurrency' => 'USD',
            'journeyType' => $journeyType,
            'OriginDestinationInfo' => [$originDestInfo],
            'class' => $classMap[$cabinClass] ?? 'Economy',
            'adults' => $passengers, // Assuming all requested passengers are adults
            'childs' => 0,
            'infants' => 0,
        ];

        $response = Http::withoutVerifying()->connectTimeout(10)->timeout(25)->post($this->baseUrl . '/aeroVE5/availability', $payload);

        if ($response->failed()) {
            Log::error('Trawex Flight Availability Search Failed: ' . $response->body());
            return []; // Return empty on failure for frontend stability
        }

        $data = $response->json();
        $mappedFlights = [];
        
        $searchResponse = $data['AirSearchResponse'] ?? null;
        if (!$searchResponse || !isset($searchResponse['AirSearchResult']['FareItineraries'])) {
            Log::info("Trawex API returned empty results or unexpected structure.", ['json' => $data]);
            return [];
        }

        $sessionId = $searchResponse['session_id'] ?? null;
        $itineraries = $searchResponse['AirSearchResult']['FareItineraries'];

        if (!$sessionId || !is_array($itineraries)) {
            return [];
        }

        foreach ($itineraries as $index => $offerWrapper) {
            $fareItin = $offerWrapper['FareItinerary'] ?? null;
            if (!$fareItin) continue;

            $fareInfo = $fareItin['AirItineraryFareInfo'] ?? [];
            $fareSourceCode = $fareInfo['FareSourceCode'] ?? null;
            
            // Generate a unique MD5 hash as our internal flight ID
            $flightId = md5($sessionId . $fareSourceCode . $index);
            
            // Cache the session_id along with the itinerary data
            Cache::put('trawex_offer_' . $flightId, [
                'session_id' => $sessionId,
                'FareItinerary' => $fareItin
            ], now()->addHours(2));

            $totalFares = $fareInfo['ItinTotalFares']['TotalFare'] ?? [];
            $price = $totalFares['Amount'] ?? 0;
            $currency = $totalFares['CurrencyCode'] ?? 'USD';

            $options = $fareItin['OriginDestinationOptions'] ?? [];
            $outboundOptions = $options[0]['OriginDestinationOption'] ?? [];
            $inboundOptions = $options[1]['OriginDestinationOption'] ?? [];

            $normalizeSegment = function ($segmentData): array {
                return $segmentData['FlightSegment'] ?? $segmentData;
            };

            $outboundSegments = collect($outboundOptions)
                ->map(fn ($opt) => $normalizeSegment($opt))
                ->filter(fn ($seg) => is_array($seg) && !empty($seg))
                ->values();

            if ($outboundSegments->isEmpty()) {
                continue;
            }

            $inboundSegments = collect($inboundOptions)
                ->map(fn ($opt) => $normalizeSegment($opt))
                ->filter(fn ($seg) => is_array($seg) && !empty($seg))
                ->values();

            $firstOut = $outboundSegments->first();
            $lastOut = $outboundSegments->last();

            $airlineCode = $firstOut['MarketingAirlineCode'] ?? 'XX';
            $airline = $firstOut['MarketingAirlineName'] ?? $airlineCode;
            $flightNumber = $firstOut['FlightNumber'] ?? 'Unknown';

            $departure = $firstOut['DepartureDateTime'] ?? Carbon::parse($date)->setTime(8, 0)->toDateTimeString();
            $arrival = $lastOut['ArrivalDateTime'] ?? Carbon::parse($date)->setTime(10, 0)->toDateTimeString();

            $outboundDurationMins = (int) collect($outboundSegments)->sum(fn ($seg) => (int) ($seg['JourneyDuration'] ?? 0));
            if ($outboundDurationMins <= 0) {
                $outboundDurationMins = Carbon::parse($arrival)->diffInMinutes(Carbon::parse($departure));
            }

            $durationStr = intdiv((int) $outboundDurationMins, 60) . 'h ' . ($outboundDurationMins % 60) . 'm';
            $outboundStops = max(0, count($outboundSegments) - 1);

            $inbound = null;
            if ($journeyType === 'Return' && $inboundSegments->isNotEmpty()) {
                $firstIn = $inboundSegments->first();
                $lastIn = $inboundSegments->last();
                $inDep = $firstIn['DepartureDateTime'] ?? ($returnDate ? Carbon::parse($returnDate)->setTime(8, 0)->toDateTimeString() : null);
                $inArr = $lastIn['ArrivalDateTime'] ?? ($returnDate ? Carbon::parse($returnDate)->setTime(10, 0)->toDateTimeString() : null);
                $inDurationMins = (int) collect($inboundSegments)->sum(fn ($seg) => (int) ($seg['JourneyDuration'] ?? 0));
                if ($inDurationMins <= 0 && $inDep && $inArr) {
                    $inDurationMins = Carbon::parse($inArr)->diffInMinutes(Carbon::parse($inDep));
                }

                $inbound = [
                    'origin' => $firstIn['DepartureAirportLocationCode'] ?? $destination,
                    'destination' => $lastIn['ArrivalAirportLocationCode'] ?? $origin,
                    'departure_time' => $inDep,
                    'arrival_time' => $inArr,
                    'duration' => intdiv((int) $inDurationMins, 60) . 'h ' . ($inDurationMins % 60) . 'm',
                    'stops' => max(0, count($inboundSegments) - 1),
                    'layover' => null,
                ];
            }

            $mappedFlights[] = [
                'id' => $flightId,
                'airline' => $airline,
                'airline_code' => $airlineCode,
                'flight_number' => $flightNumber,
                'origin' => $origin,
                'destination' => $destination,
                'departure_time' => $departure,
                'arrival_time' => $arrival,
                'duration' => $durationStr,
                'stops' => $outboundStops,
                'price' => floatval($price),
                'currency' => $currency,
                'crossed_price' => round(floatval($price) * 1.08, 2),
                'refundable' => strtolower((string) ($fareItin['FareType'] ?? '')) !== 'non_refundable',
                'points' => 25,
                'outbound' => [
                    'origin' => $firstOut['DepartureAirportLocationCode'] ?? $origin,
                    'destination' => $lastOut['ArrivalAirportLocationCode'] ?? $destination,
                    'departure_time' => $departure,
                    'arrival_time' => $arrival,
                    'duration' => $durationStr,
                    'stops' => $outboundStops,
                    'layover' => null,
                ],
                'inbound' => $inbound,
            ];
        }

        return collect($mappedFlights)->sortBy('price')->values()->toArray();
    }

    /**
     * Price a specific flight / Validate Fare Method
     */
    public function price(string $flightId): array
    {
        $cachedData = Cache::get('trawex_offer_' . $flightId);
        
        if (!$cachedData || !isset($cachedData['session_id']) || !isset($cachedData['FareItinerary'])) {
            return [
                'total_price' => 0,
                'currency' => 'USD',
                'status' => 'expired'
            ];
        }

        $sessionId = $cachedData['session_id'];
        $fareSourceCode = $cachedData['FareItinerary']['AirItineraryFareInfo']['FareSourceCode'] ?? '';

        $payload = [
            'session_id' => $sessionId,
            'fare_source_code' => $fareSourceCode
        ];

        $response = Http::withoutVerifying()->connectTimeout(10)->timeout(25)->post($this->baseUrl . '/aeroVE5/revalidate', $payload);

        if ($response->failed()) {
            Log::error('Trawex Fare Validation Failed: ' . $response->body());
            return [
                'total_price' => 0,
                'currency' => 'USD',
                'status' => 'expired'
            ];
        }

        $data = $response->json();
        $result = $data['AirRevalidateResponse']['AirRevalidateResult'] ?? null;

        // Verify if the fare is still valid
        if (!$result || ($result['IsValid'] ?? false) == false || ($result['IsValid'] ?? 'false') === 'false') {
            return [
                'total_price' => 0,
                'currency' => 'USD',
                'status' => 'expired'
            ];
        }

        // Successfully revalidated, fetch the latest price elements
        $validatedItin = $result['FareItineraries']['FareItinerary'] ?? [];
        $totalFare = $validatedItin['AirItineraryFareInfo']['ItinTotalFares']['TotalFare'] ?? [];
        $priceAmount = $totalFare['Amount'] ?? 0;
        $currency = $totalFare['CurrencyCode'] ?? 'USD';

        // Re-cache the validated result and any new FareSourceCode if updated
        if (isset($validatedItin['AirItineraryFareInfo']['FareSourceCode'])) {
            $cachedData['FareItinerary'] = $validatedItin;
            Cache::put('trawex_offer_' . $flightId, $cachedData, now()->addHours(2));
        }

        return [
            'total_price' => floatval($priceAmount),
            'currency' => $currency,
            'status' => 'available'
        ];
    }

    /**
     * Book the Flight
     */
    public function book(string $flightId, array $passengerDetails): array
    {
        $cachedData = Cache::get('trawex_offer_' . $flightId);
        
        if (!$cachedData || !isset($cachedData['session_id']) || !isset($cachedData['FareItinerary'])) {
            throw new \Exception("Flight offer has expired.");
        }

        $sessionId = $cachedData['session_id'];
        $fareItin = $cachedData['FareItinerary'];
        $fareSourceCode = $fareItin['AirItineraryFareInfo']['FareSourceCode'] ?? '';
        $fareType = $fareItin['FareType'] ?? 'Public';

        $titles = [];
        $firstNames = [];
        $lastNames = [];
        $dobs = [];
        $nationalities = [];

        foreach ($passengerDetails as $p) {
            $titles[] = $p['title'] ?? 'Mr';
            $firstNames[] = $p['first_name'] ?? 'Unknown';
            $lastNames[] = $p['last_name'] ?? 'Unknown';
            $dobs[] = $p['dob'] ?? '1990-01-01';
            $nationalities[] = $p['nationality'] ?? 'IN';
        }

        $paxInfo = [
            'clientRef' => 'SYS_GEN_' . strtoupper(Str::random(6)),
            'customerEmail' => $passengerDetails[0]['email'] ?? 'test@ramim.dev',
            'customerPhone' => $passengerDetails[0]['phone'] ?? '1234567890',
            'paxDetails' => [
                [
                    'adult' => [
                        'title' => $titles,
                        'firstName' => $firstNames,
                        'lastName' => $lastNames,
                        'dob' => $dobs,
                        'nationality' => $nationalities
                    ]
                ]
            ]
        ];

        $payload = [
            'flightBookingInfo' => [
                'flight_session_id' => $sessionId,
                'fare_source_code' => $fareSourceCode,
                'IsPassportMandatory' => 'false',
                'fareType' => $fareType,
                'areaCode' => '080',
                'countryCode' => '91'
            ],
            'paxInfo' => $paxInfo
        ];

        $response = Http::withoutVerifying()->connectTimeout(10)->timeout(25)->post($this->baseUrl . '/aeroVE5/booking', $payload);

        if ($response->failed()) {
            Log::error('Trawex Booking Failed: ' . $response->body());
            throw new \Exception('Failed to process Trawex booking.');
        }

        $data = $response->json();
        $result = $data['BookFlightResponse']['BookFlightResult'] ?? null;

        $successRaw = $result['Success'] ?? false;
        $isSuccess = ($successRaw === true || strtolower(trim((string)$successRaw)) === 'true');

        if (!$result || !$isSuccess) {
             $errorMsg = 'Booking rejected by provider.';
             if (isset($result['Errors'][0]['Errors']['ErrorMessage'])) {
                 $errorMsg = $result['Errors'][0]['Errors']['ErrorMessage'];
             } else if (isset($result['Errors']['ErrorMessage'])) {
                 $errorMsg = $result['Errors']['ErrorMessage'];
             }
             throw new \Exception($errorMsg);
        }

        $status = strtolower($result['Status'] ?? 'pending');
        
        return [
            'api_reference_id' => (!empty($result['UniqueID']) ? $result['UniqueID'] : ('PNR' . strtoupper(Str::random(6)))),
            'status' => $status
        ];
    }
}
