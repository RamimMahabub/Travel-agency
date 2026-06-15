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
            <div class="relative" @click.away="searchResults = []">
                <div class="flex items-center w-full bg-gray-50 border border-gray-200 focus-within:border-brand-primary focus-within:bg-white focus-within:ring-1 focus-within:ring-brand-primary/20 rounded-lg px-4 py-3 transition-all">
                    <i class="fas fa-search text-gray-500 mr-3 text-lg"></i>
                    <input type="text" x-model="searchQuery" @input.debounce.500ms="searchLocation()" class="flex-grow bg-transparent border-none outline-none focus:ring-0 text-brand-black placeholder-gray-500 text-sm p-0" placeholder="Search for your property location">
                    <i class="fas fa-spinner fa-spin text-brand-primary ml-2" x-show="isSearching" style="display: none;"></i>
                </div>
                
                {{-- Search Results Dropdown --}}
                <div x-show="searchResults.length > 0" style="display: none;" x-transition class="absolute z-[100] w-full bg-white mt-2 rounded-xl shadow-xl border border-gray-100 max-h-64 overflow-y-auto custom-scrollbar">
                    <template x-for="result in searchResults" :key="result.place_id">
                        <div @click="selectLocation(result)" class="px-5 py-3 hover:bg-brand-primary/10 cursor-pointer border-b border-gray-50 last:border-0 transition-colors text-left flex items-start gap-3">
                            <i class="fas fa-map-marker-alt text-brand-primary mt-1"></i>
                            <div>
                                <div class="font-bold text-sm text-brand-black" x-text="result.display_name.split(',')[0]"></div>
                                <div class="text-xs text-gray-500 mt-0.5" x-text="result.display_name"></div>
                            </div>
                        </div>
                    </template>
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
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('mapPicker', (initialLat, initialLng) => ({
        lat: initialLat,
        lng: initialLng,
        map: null,
        marker: null,
        searchQuery: '',
        searchResults: [],
        isSearching: false,
        
        async searchLocation() {
            if (this.searchQuery.length < 3) {
                this.searchResults = [];
                return;
            }
            this.isSearching = true;
            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.searchQuery)}&limit=5&addressdetails=1`);
                this.searchResults = await response.json();
            } catch (e) {
                console.error('Search failed', e);
            } finally {
                this.isSearching = false;
            }
        },

        selectLocation(result) {
            this.searchQuery = result.display_name.split(',')[0];
            this.searchResults = [];
            
            const lat = parseFloat(result.lat);
            const lng = parseFloat(result.lon);
            
            this.lat = lat.toFixed(7);
            this.lng = lng.toFixed(7);
            
            if (this.map && this.marker) {
                this.map.setView([lat, lng], 17);
                this.marker.setLatLng([lat, lng]);
            }
            
            if (result.address) {
                const addr = result.address;
                const elCity = document.querySelector('input[name="city"]');
                const elState = document.querySelector('input[name="state"]');
                const elCountry = document.querySelector('input[name="country"]');
                const elPostal = document.querySelector('input[name="postal_code"]');
                const elAddress1 = document.querySelector('input[name="address_line_1"]');
                const elNeighborhood = document.querySelector('input[name="neighborhood"]');
                
                if (elCity) elCity.value = addr.city || addr.town || addr.village || addr.county || '';
                if (elState) elState.value = addr.state || addr.region || '';
                if (elCountry) elCountry.value = addr.country || '';
                if (elPostal) elPostal.value = addr.postcode || '';
                
                const street = addr.road || '';
                const houseNumber = addr.house_number || '';
                if (elAddress1 && street) elAddress1.value = `${houseNumber} ${street}`.trim();
                
                if (elNeighborhood) elNeighborhood.value = addr.neighbourhood || addr.suburb || '';
            }
        },

        init() {
            this.$nextTick(() => {
                if (typeof L === 'undefined') {
                    console.error('Leaflet API not loaded.');
                    return;
                }
                
                const position = [parseFloat(this.lat) || 23.8103, parseFloat(this.lng) || 90.4125];
                
                this.map = L.map(this.$refs.mapContainer, {
                    center: position,
                    zoom: 13,
                    zoomControl: true,
                    scrollWheelZoom: true
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(this.map);

                this.marker = L.marker(position, { draggable: true }).addTo(this.map);

                this.marker.on("dragend", (e) => {
                    const pos = e.target.getLatLng();
                    this.lat = pos.lat.toFixed(7);
                    this.lng = pos.lng.toFixed(7);
                });

                this.map.on("click", (e) => {
                    this.marker.setLatLng(e.latlng);
                    this.lat = e.latlng.lat.toFixed(7);
                    this.lng = e.latlng.lng.toFixed(7);
                });

                this.$watch('lat', value => {
                    if (this.marker && !isNaN(value) && value !== '') {
                        const pos = [parseFloat(value), parseFloat(this.lng)];
                        this.marker.setLatLng(pos);
                        this.map.setView(pos);
                    }
                });

                this.$watch('lng', value => {
                    if (this.marker && !isNaN(value) && value !== '') {
                        const pos = [parseFloat(this.lat), parseFloat(value)];
                        this.marker.setLatLng(pos);
                        this.map.setView(pos);
                    }
                });
            });
        }
    }));
});
</script>
@endpush

</x-pms-layout>
