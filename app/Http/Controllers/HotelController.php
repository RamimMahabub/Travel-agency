<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Review;
use App\Services\AvailabilityService;
use App\Services\PricingService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function __construct(
        protected AvailabilityService $availabilityService,
        protected PricingService $pricingService
    ) {}

    public function show(Property $property, Request $request)
    {
        if (!$property->isApproved()) {
            abort(404);
        }

        $property->load([
            'photos',
            'activeRoomTypes.photos',
            'activeRoomTypes.activeRatePlans',
        ]);

        $checkIn = $request->filled('check_in') ? Carbon::parse($request->check_in) : Carbon::tomorrow();
        $checkOut = $request->filled('check_out') ? Carbon::parse($request->check_out) : Carbon::tomorrow()->addDay();
        $guests = $request->get('guests', 2);

        // Calculate availability and prices for each room type
        $roomsData = [];
        foreach ($property->activeRoomTypes as $roomType) {
            $available = $this->availabilityService->checkAvailability(
                $roomType->id, $checkIn, $checkOut
            );

            $pricing = $this->pricingService->calculateStayPrice(
                $roomType->id, $checkIn, $checkOut
            );

            $roomsData[$roomType->id] = [
                'available' => $available,
                'pricing' => $pricing,
            ];
        }

        // Reviews
        $reviews = Review::where('property_id', $property->id)
            ->published()
            ->with('guest')
            ->latest()
            ->paginate(10);

        $reviewStats = [
            'average' => $property->average_rating,
            'count' => $property->review_count,
            'cleanliness' => Review::where('property_id', $property->id)->published()->avg('cleanliness_score'),
            'location' => Review::where('property_id', $property->id)->published()->avg('location_score'),
            'service' => Review::where('property_id', $property->id)->published()->avg('service_score'),
            'value' => Review::where('property_id', $property->id)->published()->avg('value_score'),
            'facilities' => Review::where('property_id', $property->id)->published()->avg('facilities_score'),
        ];

        return view('hotels.show', compact(
            'property', 'roomsData', 'reviews', 'reviewStats',
            'checkIn', 'checkOut', 'guests'
        ));
    }
}
