<x-pms-layout pageTitle="Edit Property" :pageSubtitle="$hotel->name">
<div class="max-w-3xl">
    <form method="POST" action="{{ route('property-owner.hotels.update', $hotel) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')
        <div class="card card-body space-y-5">
            <h2 class="section-heading text-base">Basic Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="form-group md:col-span-2">
                    <label class="form-label">Property Name *</label>
                    <input type="text" name="name" class="form-input-styled" value="{{ old('name', $hotel->name) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Type *</label>
                    <select name="type" class="form-input-styled">
                        @foreach(\App\Models\Property::getTypes() as $type)
                            <option value="{{ $type }}" {{ $hotel->type === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Stars *</label>
                    <select name="stars" class="form-input-styled">
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ $hotel->stars == $i ? 'selected' : '' }}>{{ $i }} Star</option>
                        @endfor
                    </select>
                </div>
                <div class="form-group md:col-span-2">
                    <label class="form-label">Short Description</label>
                    <input type="text" name="short_description" class="form-input-styled" value="{{ old('short_description', $hotel->short_description) }}">
                </div>
                <div class="form-group md:col-span-2">
                    <label class="form-label">Full Description</label>
                    <textarea name="full_description" rows="4" class="form-input-styled">{{ old('full_description', $hotel->full_description) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Check-in Time</label>
                    <input type="text" name="check_in_time" class="form-input-styled" value="{{ old('check_in_time', $hotel->check_in_time) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Check-out Time</label>
                    <input type="text" name="check_out_time" class="form-input-styled" value="{{ old('check_out_time', $hotel->check_out_time) }}">
                </div>
            </div>
        </div>

        {{-- Location Section --}}
        <div class="card card-body space-y-6" x-data="mapPicker({{ old('latitude', $hotel->latitude ?? 23.8103) }}, {{ old('longitude', $hotel->longitude ?? 90.4125) }})">
            <div>
                <h2 class="section-heading text-base mb-1">Property Location</h2>
                <p class="text-sm text-brand-muted">Search for your property location</p>
            </div>

            {{-- Search Bar --}}
            <div class="relative">
                <div class="flex items-center w-full bg-gray-50 border border-gray-200 focus-within:border-brand-primary focus-within:bg-white focus-within:ring-1 focus-within:ring-brand-primary/20 rounded-lg px-4 py-3 transition-all">
                    <i class="fas fa-search text-gray-500 mr-3 text-lg"></i>
                    <input type="text" x-ref="searchInput" class="flex-grow bg-transparent border-none outline-none focus:ring-0 text-brand-black placeholder-gray-500 text-sm p-0" placeholder="Search for your property location">
                </div>
            </div>

            <div class="mt-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-7 gap-x-5">
                    <div class="relative md:col-span-2">
                        <label class="absolute -top-2.5 left-3 bg-white px-1.5 text-[11px] font-medium text-gray-500 z-10">Country/Region</label>
                        <input type="text" name="country" class="block w-full px-4 py-3.5 text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary transition-colors" required value="{{ old('country', $hotel->country) }}">
                    </div>
                    
                    <div class="relative">
                        <label class="absolute -top-2.5 left-3 bg-white px-1.5 text-[11px] font-medium text-gray-500 z-10">State/Province</label>
                        <input type="text" name="state" class="block w-full px-4 py-3.5 text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary transition-colors" value="{{ old('state') }}">
                    </div>
                    
                    <div class="relative">
                        <label class="absolute -top-2.5 left-3 bg-white px-1.5 text-[11px] font-medium text-gray-500 z-10">City</label>
                        <input type="text" name="city" class="block w-full px-4 py-3.5 text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary transition-colors" required value="{{ old('city', $hotel->city) }}">
                    </div>
                    
                    <div class="relative md:col-span-2">
                        <label class="absolute -top-2.5 left-3 bg-white px-1.5 text-[11px] font-medium text-gray-500 z-10">Street address in English</label>
                        <input type="text" name="address_line_1" class="block w-full px-4 py-3.5 text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary transition-colors" value="{{ old('address_line_1', $hotel->address_line_1) }}">
                    </div>
                    
                    <div class="relative md:col-span-2">
                        <label class="absolute -top-2.5 left-3 bg-white px-1.5 text-[11px] font-medium text-gray-500 z-10">Building, floor or unit number (optional)</label>
                        <input type="text" name="address_line_2" class="block w-full px-4 py-3.5 text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary transition-colors" value="{{ old('address_line_2') }}">
                    </div>
                    
                    <div class="relative md:col-span-2">
                        <label class="absolute -top-2.5 left-3 bg-white px-1.5 text-[11px] font-medium text-gray-500 z-10">ZIP/Postal code (optional)</label>
                        <input type="text" name="postal_code" class="block w-full px-4 py-3.5 text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary transition-colors" value="{{ old('postal_code') }}">
                    </div>
                </div>

                {{-- Hidden lat/lng fields --}}
                <input type="hidden" name="latitude" x-model="lat">
                <input type="hidden" name="longitude" x-model="lng">
            </div>

            {{-- Map Display --}}
            <div class="mt-8">
                <div class="rounded-xl overflow-hidden border border-gray-200 shadow-sm relative">
                    <div id="map" class="w-full z-10" style="height: 350px;" x-ref="mapContainer"></div>
                </div>
                <p class="text-[13px] text-gray-500 mt-3">Is this the correct location of your property? If not, drag the pin to the correct location.</p>
            </div>
        </div>
        <div class="card card-body">
            <h2 class="section-heading text-base">Add More Photos</h2>
            <input type="file" name="photos[]" multiple accept="image/*" class="form-input-styled">
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('property-owner.hotels.show', $hotel) }}" class="btn-ghost">Cancel</a>
            <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Save Changes</button>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('mapPicker', (initialLat, initialLng) => ({
        lat: initialLat,
        lng: initialLng,
        map: null,
        marker: null,
        initAutocomplete() {
            if (typeof google === 'undefined' || !google.maps.places) return;
            
            const autocomplete = new google.maps.places.Autocomplete(this.$refs.searchInput, {
                fields: ["address_components", "geometry", "name"],
            });
            
            autocomplete.addListener("place_changed", () => {
                const place = autocomplete.getPlace();
                if (!place.geometry || !place.geometry.location) return;
                
                // Update map
                this.lat = place.geometry.location.lat().toFixed(7);
                this.lng = place.geometry.location.lng().toFixed(7);
                
                if (this.map && this.marker) {
                    this.map.setCenter(place.geometry.location);
                    this.map.setZoom(17);
                    this.marker.setPosition(place.geometry.location);
                }
                
                // Parse address components
                let streetNumber = '';
                let route = '';
                let city = '';
                let state = '';
                let country = '';
                let postalCode = '';
                let neighborhood = '';

                for (const component of place.address_components) {
                    const componentType = component.types[0];
                    switch (componentType) {
                        case "street_number":
                            streetNumber = component.long_name;
                            break;
                        case "route":
                            route = component.long_name;
                            break;
                        case "locality":
                        case "postal_town":
                            city = component.long_name;
                            break;
                        case "administrative_area_level_1":
                            state = component.long_name;
                            break;
                        case "country":
                            country = component.long_name;
                            break;
                        case "postal_code":
                            postalCode = component.long_name;
                            break;
                        case "neighborhood":
                        case "sublocality":
                            neighborhood = component.long_name;
                            break;
                    }
                }

                const elCity = document.querySelector('input[name="city"]');
                const elState = document.querySelector('input[name="state"]');
                const elCountry = document.querySelector('input[name="country"]');
                const elPostal = document.querySelector('input[name="postal_code"]');
                const elAddress1 = document.querySelector('input[name="address_line_1"]');
                const elNeighborhood = document.querySelector('input[name="neighborhood"]');

                if (elCity) elCity.value = city;
                if (elState) elState.value = state;
                if (elCountry) elCountry.value = country;
                if (elPostal) elPostal.value = postalCode;
                if (elAddress1) elAddress1.value = `${streetNumber} ${route}`.trim();
                if (elNeighborhood) elNeighborhood.value = neighborhood;
            });
        },

        init() {
            this.$nextTick(() => {
                if (typeof google === 'undefined') {
                    console.error('Google Maps API not loaded.');
                    return;
                }
                
                this.initAutocomplete();
                
                const position = { lat: parseFloat(this.lat) || 23.8103, lng: parseFloat(this.lng) || 90.4125 };
                
                this.map = new google.maps.Map(this.$refs.mapContainer, {
                    center: position,
                    zoom: 13,
                    mapTypeControl: false,
                    streetViewControl: false,
                });

                this.marker = new google.maps.Marker({
                    position: position,
                    map: this.map,
                    draggable: true,
                    animation: google.maps.Animation.DROP
                });

                this.marker.addListener("dragend", () => {
                    const pos = this.marker.getPosition();
                    this.lat = pos.lat().toFixed(7);
                    this.lng = pos.lng().toFixed(7);
                });

                this.map.addListener("click", (e) => {
                    this.marker.setPosition(e.latLng);
                    this.lat = e.latLng.lat().toFixed(7);
                    this.lng = e.latLng.lng().toFixed(7);
                });

                this.$watch('lat', value => {
                    if (this.marker && !isNaN(value) && value !== '') {
                        const pos = new google.maps.LatLng(parseFloat(value), parseFloat(this.lng));
                        this.marker.setPosition(pos);
                        this.map.setCenter(pos);
                    }
                });

                this.$watch('lng', value => {
                    if (this.marker && !isNaN(value) && value !== '') {
                        const pos = new google.maps.LatLng(parseFloat(this.lat), parseFloat(value));
                        this.marker.setPosition(pos);
                        this.map.setCenter(pos);
                    }
                });
            });
        }
    }));
});
</script>
@endpush

</x-pms-layout>
