<?php

namespace App\Http\Controllers\PropertyOwner;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HotelController extends Controller
{
    public function index()
    {
        $properties = Property::where('owner_id', Auth::id())
            ->withCount(['roomTypes', 'hotelBookings', 'reviews'])
            ->latest()
            ->get();

        return view('property-owner.hotels.index', compact('properties'));
    }

    public function create()
    {
        return view('property-owner.hotels.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:hotel,resort,villa,hostel,apartment,guesthouse',
            'stars' => 'required|integer|min:1|max:5',
            'short_description' => 'nullable|string|max:255',
            'full_description' => 'nullable|string',
            'check_in_time' => 'required|string',
            'check_out_time' => 'required|string',
            'languages_spoken' => 'nullable|array',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'neighborhood' => 'nullable|string|max:100',
            'airport_distance' => 'nullable|string|max:50',
            'beach_distance' => 'nullable|string|max:50',
            'city_center_distance' => 'nullable|string|max:50',
            'amenities' => 'nullable|array',
            'cancellation_policy' => 'nullable|array',
            'children_policy' => 'nullable|string',
            'pet_policy' => 'nullable|string',
            'payment_policy' => 'nullable|string',
            'extra_bed_policy' => 'nullable|string',
            'early_checkin_policy' => 'nullable|string',
            'late_checkout_policy' => 'nullable|string',
            'photos' => 'nullable|array',
            'photos.*' => 'image|max:5120',
            'rooms' => 'required|array|min:1',
            'rooms.*.name' => 'required|string|max:255',
            'rooms.*.size_sqm' => 'nullable|integer|min:1|max:99999',
            'rooms.*.max_adults' => 'required|integer|min:1|max:99',
            'rooms.*.bathrooms' => 'nullable|integer|min:0|max:99',
            'rooms.*.base_price_per_night' => 'required|numeric|min:0|max:999999',
            'rooms.*.breakfast' => 'required|in:yes,no',
        ]);

        $validated['owner_id'] = Auth::id();
        $validated['status'] = 'draft';

        // No manual json_encode needed since Eloquent casts these to 'array' in the Property model.

        // Remove extra fields from validated before creating property
        $photos = $validated['photos'] ?? [];
        unset($validated['photos']);
        $roomsData = $validated['rooms'] ?? [];
        unset($validated['rooms']);

        $property = Property::create($validated);

        // Handle photo uploads
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $path = $photo->storeOnCloudinary('properties/' . $property->id)->getSecurePath();
                PropertyPhoto::create([
                    'property_id' => $property->id,
                    'file_path' => $path,
                    'category' => $request->input("photo_categories.{$index}", 'exterior'),
                    'sort_order' => $index,
                    'is_cover' => $index === 0,
                ]);
            }
        }
        // Handle Rooms Creation
        foreach ($roomsData as $roomData) {
            $roomAmenities = [];
            if (($roomData['breakfast'] ?? 'no') === 'yes') {
                $roomAmenities[] = 'breakfast';
            }
            // If you want to store bathrooms, could push to amenities or extend migration later.
            
            \App\Models\RoomType::create([
                'property_id' => $property->id,
                'name' => $roomData['name'],
                'size_sqm' => $roomData['size_sqm'] ?? null,
                'max_adults' => $roomData['max_adults'] ?? 1,
                'base_price_per_night' => $roomData['base_price_per_night'] ?? 0,
                'amenities' => $roomAmenities,
                'inventory_count' => 1,
                'status' => 'active'
            ]);
        }

        return redirect()->route('property-owner.hotels.show', $property)
            ->with('success', 'Property & initial rooms created successfully! You can now add room photos to complete your listing.');
    }

    public function show(Property $hotel)
    {
        $this->authorizeProperty($hotel);

        $hotel->load([
            'photos',
            'roomTypes' => fn($q) => $q->withCount('photos'),
            'roomTypes.ratePlans',
        ]);

        return view('property-owner.hotels.show', compact('hotel'));
    }

    public function edit(Property $hotel)
    {
        $this->authorizeProperty($hotel);
        $hotel->load('photos');

        return view('property-owner.hotels.edit', compact('hotel'));
    }

    public function update(Request $request, Property $hotel)
    {
        $this->authorizeProperty($hotel);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:hotel,resort,villa,hostel,apartment,guesthouse',
            'stars' => 'required|integer|min:1|max:5',
            'short_description' => 'nullable|string|max:255',
            'full_description' => 'nullable|string',
            'check_in_time' => 'required|string',
            'check_out_time' => 'required|string',
            'address_line_1' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
        ]);

        $hotel->update($validated);

        // Handle new photo uploads
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $path = $photo->storeOnCloudinary('properties/' . $hotel->id)->getSecurePath();
                PropertyPhoto::create([
                    'property_id' => $hotel->id,
                    'file_path' => $path,
                    'category' => $request->input("photo_categories.{$index}", 'exterior'),
                    'sort_order' => $hotel->photos()->count() + $index,
                ]);
            }
        }

        return redirect()->route('property-owner.hotels.show', $hotel)
            ->with('success', 'Property updated successfully.');
    }

    public function destroy(Property $hotel)
    {
        $this->authorizeProperty($hotel);

        // Note: For Cloudinary, we'd need to parse the public ID to delete it,
        // or just let it stay in Cloudinary for now since we store the full URL.
        // If we strictly wanted to delete, we could do:
        // Cloudinary::destroy($publicId);
        // We will just let them be orphaned for now or handle cleanup separately.

        $hotel->delete();

        return redirect()->route('property-owner.hotels.index')
            ->with('success', 'Property deleted successfully.');
    }

    public function submitForApproval(Property $hotel)
    {
        $this->authorizeProperty($hotel);

        // Validate completeness
        $errors = [];
        if (!$hotel->name) $errors[] = 'Property name is required';
        if ($hotel->photos()->count() < 1) $errors[] = 'At least 1 photo is required';
        if ($hotel->roomTypes()->count() < 1) $errors[] = 'At least 1 room type is required';
        if (!$hotel->city) $errors[] = 'City is required';

        if (!empty($errors)) {
            return back()->with('error', 'Cannot submit: ' . implode(', ', $errors));
        }

        $hotel->update(['status' => 'pending_approval']);

        return back()->with('success', 'Property submitted for approval! Our team will review it shortly.');
    }

    private function authorizeProperty(Property $hotel): void
    {
        if ($hotel->owner_id !== Auth::id()) {
            abort(403, 'You are not authorized to access this property.');
        }
    }
}
