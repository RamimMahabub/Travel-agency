<x-pms-layout pageTitle="Add New Property" pageSubtitle="Complete the setup wizard to list your property">

<div x-data="propertyWizard()" class="max-w-4xl mx-auto">

    {{-- Wizard Progress --}}
    <div class="card card-body mb-6">
        <div class="flex items-center justify-between">
            <template x-for="(step, index) in steps" :key="index">
                <div class="flex items-center" :class="index < steps.length - 1 ? 'flex-1' : ''">
                    <div class="wizard-step" :class="{ 'active': currentStep === index, 'completed': currentStep > index }">
                        <div class="step-number">
                            <template x-if="currentStep > index"><i class="fas fa-check text-[10px]"></i></template>
                            <template x-if="currentStep <= index"><span x-text="index + 1"></span></template>
                        </div>
                        <span class="hidden sm:inline text-xs" x-text="step"></span>
                    </div>
                    <div x-show="index < steps.length - 1" class="wizard-connector flex-1" :class="{ 'completed': currentStep > index }"></div>
                </div>
            </template>
        </div>
    </div>

    <form method="POST" action="{{ route('property-owner.hotels.store') }}" enctype="multipart/form-data" @submit="isSubmitting = true">
        @csrf

        {{-- Step 1: Basic Info --}}
        <div x-show="currentStep === 0" x-transition:enter="animate-slide-right" class="card card-body space-y-5">
            <h2 class="section-heading"><i class="fas fa-info-circle text-brand-primary mr-2"></i>Basic Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="form-group md:col-span-2">
                    <label class="form-label">Property Name *</label>
                    <input type="text" name="name" class="form-input-styled" placeholder="e.g., Grand Azure Hotel & Spa" required value="{{ old('name') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Property Type *</label>
                    <select name="type" class="form-input-styled" required>
                        <option value="">Select type...</option>
                        @foreach(\App\Models\Property::getTypes() as $type)
                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Star Rating *</label>
                    <div class="star-rating mt-2" x-data="{ rating: {{ old('stars', 3) }} }">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" @click="rating = {{ $i }}" class="star" :class="rating >= {{ $i }} ? 'filled' : 'empty'">
                                <svg viewBox="0 0 20 20" fill="currentColor" class="w-7 h-7"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </button>
                        @endfor
                        <input type="hidden" name="stars" :value="rating">
                    </div>
                </div>

                <div class="form-group md:col-span-2">
                    <label class="form-label">Short Description</label>
                    <input type="text" name="short_description" class="form-input-styled" maxlength="255" placeholder="A brief tagline for your property (max 255 chars)" value="{{ old('short_description') }}">
                </div>

                <div class="form-group md:col-span-2">
                    <label class="form-label">Full Description</label>
                    <textarea name="full_description" rows="4" class="form-input-styled" placeholder="Detailed description of your property...">{{ old('full_description') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Check-in Time *</label>
                    <select name="check_in_time" class="form-input-styled">
                        @for($h = 0; $h < 24; $h++)
                            <option value="{{ sprintf('%02d:00', $h) }}" {{ old('check_in_time', '14:00') === sprintf('%02d:00', $h) ? 'selected' : '' }}>
                                {{ sprintf('%02d:00', $h) }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Check-out Time *</label>
                    <select name="check_out_time" class="form-input-styled">
                        @for($h = 0; $h < 24; $h++)
                            <option value="{{ sprintf('%02d:00', $h) }}" {{ old('check_out_time', '12:00') === sprintf('%02d:00', $h) ? 'selected' : '' }}>
                                {{ sprintf('%02d:00', $h) }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>

        {{-- Step 2: Location --}}
        <div x-show="currentStep === 1" x-transition:enter="animate-slide-right" class="card card-body space-y-6" x-data="mapPicker({{ old('latitude', 23.8103) }}, {{ old('longitude', 90.4125) }})">
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
                <h3 class="text-[15px] font-bold text-brand-black mb-5">Property location</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-7 gap-x-5">
                    <div class="relative md:col-span-2">
                        <label class="absolute -top-2.5 left-3 bg-white px-1.5 text-[11px] font-medium text-gray-500 z-10">Country/Region</label>
                        <input type="text" name="country" class="block w-full px-4 py-3.5 text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary transition-colors" required value="{{ old('country') }}">
                    </div>
                    
                    <div class="relative">
                        <label class="absolute -top-2.5 left-3 bg-white px-1.5 text-[11px] font-medium text-gray-500 z-10">State/Province</label>
                        <input type="text" name="state" class="block w-full px-4 py-3.5 text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary transition-colors" value="{{ old('state') }}">
                    </div>
                    
                    <div class="relative">
                        <label class="absolute -top-2.5 left-3 bg-white px-1.5 text-[11px] font-medium text-gray-500 z-10">City</label>
                        <input type="text" name="city" class="block w-full px-4 py-3.5 text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary transition-colors" required value="{{ old('city') }}">
                    </div>
                    
                    <div class="relative md:col-span-2">
                        <label class="absolute -top-2.5 left-3 bg-white px-1.5 text-[11px] font-medium text-gray-500 z-10">Street address in English</label>
                        <input type="text" name="address_line_1" class="block w-full px-4 py-3.5 text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary transition-colors" value="{{ old('address_line_1') }}">
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
                <input type="hidden" name="neighborhood" value="{{ old('neighborhood') }}">
                <input type="hidden" name="airport_distance" value="{{ old('airport_distance') }}">
                <input type="hidden" name="beach_distance" value="{{ old('beach_distance') }}">
                <input type="hidden" name="city_center_distance" value="{{ old('city_center_distance') }}">
            </div>

            {{-- Map Display --}}
            <div class="mt-8">
                <div class="rounded-xl overflow-hidden border border-gray-200 shadow-sm relative">
                    <div id="map" class="w-full z-10" style="height: 350px;" x-ref="mapContainer"></div>
                </div>
                <p class="text-[13px] text-gray-500 mt-3">Is this the correct location of your property? If not, drag the pin to the correct location.</p>
            </div>
        </div>

        {{-- Step 3: Photos --}}
        <div x-show="currentStep === 2" x-transition:enter="animate-slide-right" class="card card-body space-y-5">
            <h2 class="section-heading"><i class="fas fa-camera text-brand-primary mr-2"></i>Photos</h2>

            <div x-data="photoUploader()" class="space-y-4">
                <div class="photo-upload-zone"
                     @dragover.prevent="isDragging = true"
                     @dragleave.prevent="isDragging = false"
                     @drop.prevent="handleDrop($event)"
                     :class="{ 'dragging': isDragging }"
                     @click="$refs.fileInput.click()">
                    <i class="fas fa-cloud-upload-alt text-4xl text-brand-muted mb-3"></i>
                    <p class="text-sm font-medium text-brand-black">Drag & drop photos here</p>
                    <p class="text-xs text-brand-muted mt-1">or click to browse · JPEG, PNG · Max 5MB each</p>
                    <input type="file" name="photos[]" multiple accept="image/*" x-ref="fileInput" class="hidden" @change="handleFiles($event)">
                </div>

                {{-- Preview Grid --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3" x-show="previews.length > 0">
                    <template x-for="(preview, index) in previews" :key="index">
                        <div class="photo-thumbnail">
                            <img :src="preview.url" :alt="'Photo ' + (index + 1)">
                            <div class="photo-overlay">
                                <select :name="'photo_categories[' + index + ']'" class="text-[10px] bg-white rounded px-1 py-0.5">
                                    <option value="exterior">Exterior</option>
                                    <option value="lobby">Lobby</option>
                                    <option value="room">Room</option>
                                    <option value="bathroom">Bathroom</option>
                                    <option value="pool">Pool</option>
                                    <option value="restaurant">Restaurant</option>
                                    <option value="view">View</option>
                                </select>
                                <button type="button" @click="removePhoto(index)" class="text-white hover:text-red-300">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- Step 4: Amenities --}}
        <div x-show="currentStep === 3" x-transition:enter="animate-slide-right" class="card card-body space-y-5">
            <h2 class="section-heading"><i class="fas fa-concierge-bell text-brand-primary mr-2"></i>Amenities</h2>

            @php
            $amenityGroups = [
                'Connectivity' => ['Wi-Fi (Free)' => 'wifi_free', 'Wi-Fi (Paid)' => 'wifi_paid', 'Business Center' => 'business_center'],
                'Recreation' => ['Swimming Pool' => 'pool', 'Gym / Fitness' => 'gym', 'Spa' => 'spa', 'Tennis Court' => 'tennis', 'Kids Club' => 'kids_club'],
                'Food & Drink' => ['Restaurant' => 'restaurant', 'Bar / Lounge' => 'bar', 'Room Service' => 'room_service', 'Breakfast Included' => 'breakfast'],
                'Transport' => ['Free Parking' => 'parking_free', 'Paid Parking' => 'parking_paid', 'Airport Shuttle' => 'airport_shuttle', 'EV Charging' => 'ev_charging'],
                'Services' => ['24hr Front Desk' => 'front_desk_24h', 'Concierge' => 'concierge', 'Laundry' => 'laundry', 'Currency Exchange' => 'currency_exchange'],
                'Policies' => ['Pet-Friendly' => 'pet_friendly', 'Wheelchair Accessible' => 'wheelchair', 'Non-Smoking' => 'non_smoking'],
            ];
            $amenityIcons = [
                'wifi_free' => 'fa-wifi', 'wifi_paid' => 'fa-wifi', 'business_center' => 'fa-briefcase',
                'pool' => 'fa-person-swimming', 'gym' => 'fa-dumbbell', 'spa' => 'fa-spa', 'tennis' => 'fa-table-tennis-paddle-ball', 'kids_club' => 'fa-children',
                'restaurant' => 'fa-utensils', 'bar' => 'fa-martini-glass', 'room_service' => 'fa-bell-concierge', 'breakfast' => 'fa-mug-hot',
                'parking_free' => 'fa-square-parking', 'parking_paid' => 'fa-square-parking', 'airport_shuttle' => 'fa-van-shuttle', 'ev_charging' => 'fa-charging-station',
                'front_desk_24h' => 'fa-clock', 'concierge' => 'fa-bell-concierge', 'laundry' => 'fa-shirt', 'currency_exchange' => 'fa-money-bill-transfer',
                'pet_friendly' => 'fa-paw', 'wheelchair' => 'fa-wheelchair', 'non_smoking' => 'fa-ban-smoking',
            ];
            @endphp

            @foreach($amenityGroups as $group => $items)
                <div class="mb-4">
                    <h4 class="text-sm font-bold text-brand-black mb-3">{{ $group }}</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($items as $label => $key)
                            <label class="amenity-tag cursor-pointer transition-all duration-200 hover:bg-brand-light" x-data="{ checked: false }" :class="{ 'active': checked }">
                                <input type="checkbox" name="amenities[{{ $group }}][]" value="{{ $key }}" class="hidden" x-model="checked">
                                <i class="fas {{ $amenityIcons[$key] ?? 'fa-check' }} text-xs"></i>
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Step 5: Rooms --}}
        <div x-show="currentStep === 4" x-transition:enter="animate-slide-right" class="space-y-6" x-data="roomManager()">
            <div>
                <h2 class="text-[22px] font-bold text-brand-black mb-1">Setup your rooms & rates</h2>
                <div class="bg-blue-50/50 text-blue-800 text-[13px] px-4 py-3 rounded-lg border border-blue-100 flex items-start gap-3 mt-4">
                    <i class="fas fa-lightbulb text-blue-600 mt-0.5"></i>
                    <div>Tip: Start with a single room. Easily add more after completing your listing.</div>
                </div>
            </div>

            <div class="space-y-6">
                <template x-for="(room, index) in rooms" :key="index">
                    <div>
                        <h3 class="text-lg font-bold text-brand-black mb-4">Room <span x-text="index + 1"></span></h3>
                        
                        <div class="card card-body bg-gray-50/50 border border-gray-100 shadow-sm">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-4 mb-6">
                                <div class="relative" x-data="{ dropdownOpen: false, searchQuery: '' }" @click.away="dropdownOpen = false">
                                    <label class="absolute -top-2.5 left-3 bg-white px-1.5 text-[11px] font-medium text-gray-500 z-10">Room type</label>
                                    
                                    <input type="hidden" :name="'rooms['+index+'][name]'" x-model="room.name" required>
                                    
                                    <button type="button" @click="dropdownOpen = !dropdownOpen; if(dropdownOpen) { setTimeout(() => $refs.searchInput.focus(), 50) }" class="w-full flex items-center justify-between px-4 py-3.5 text-sm bg-white rounded-lg border border-gray-300 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary transition-colors text-left" :class="!room.name ? 'text-gray-500' : 'text-gray-900'">
                                        <span x-text="room.name || 'Select room type'"></span>
                                        <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform" :class="dropdownOpen ? 'rotate-180' : ''"></i>
                                    </button>

                                    <div x-show="dropdownOpen" x-transition style="display: none;" class="absolute z-50 w-full mt-2 bg-white border border-gray-200 rounded-xl shadow-[0_10px_25px_-5px_rgba(0,0,0,0.1)] overflow-hidden">
                                        <div class="p-3 border-b border-gray-100 bg-white">
                                            <div class="relative">
                                                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-brand-primary text-sm"></i>
                                                <input type="text" x-model="searchQuery" x-ref="searchInput" class="w-full pl-9 pr-4 py-2.5 text-sm border-2 border-brand-primary/40 rounded-full focus:outline-none focus:border-brand-primary bg-blue-50/20 text-brand-black placeholder-brand-primary/60 transition-colors" placeholder="Search or type custom name">
                                            </div>
                                        </div>
                                        
                                        <div class="max-h-[280px] overflow-y-auto custom-scrollbar p-2">
                                            <div class="text-[11px] text-gray-400 font-semibold mb-2 px-2 uppercase tracking-wide">Most used room types</div>
                                            
                                            <template x-for="type in predefinedRoomTypes.filter(t => t.toLowerCase().includes(searchQuery.toLowerCase()))" :key="type">
                                                <label class="flex items-center gap-3 px-3 py-2.5 hover:bg-gray-50 rounded-lg cursor-pointer transition-colors group">
                                                    <input type="radio" :name="'room_type_select_'+index" :value="type" x-model="room.name" @change="dropdownOpen = false; searchQuery = ''" class="text-blue-600 focus:ring-blue-500 w-4 h-4 border-gray-300">
                                                    <span class="text-[13px] text-gray-700 group-hover:text-brand-black" x-text="type"></span>
                                                </label>
                                            </template>
                                            
                                            <!-- Custom name option -->
                                            <div x-show="searchQuery.length > 0 && !predefinedRoomTypes.some(t => t.toLowerCase() === searchQuery.toLowerCase())" class="px-2 py-1 mt-1 border-t border-gray-100 pt-2">
                                                <label class="flex items-center gap-3 px-3 py-2.5 bg-blue-50/50 hover:bg-blue-50 rounded-lg cursor-pointer transition-colors group border border-blue-100">
                                                    <input type="radio" :name="'room_type_select_'+index" :value="searchQuery" x-model="room.name" @change="dropdownOpen = false; searchQuery = ''" class="text-blue-600 focus:ring-blue-500 w-4 h-4 border-blue-300">
                                                    <span class="text-[13px] text-blue-800 font-medium group-hover:text-blue-900">
                                                        Use custom name: "<span x-text="searchQuery"></span>"
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="relative flex items-center">
                                    <label class="absolute -top-2.5 left-3 bg-white px-1.5 text-[11px] font-medium text-gray-500 z-10">Room size</label>
                                    <input type="number" :name="'rooms['+index+'][size_sqm]'" x-model="room.size" min="1" class="block w-full pl-4 pr-12 py-3.5 text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary transition-colors">
                                    <span class="absolute right-4 text-gray-400 text-sm pointer-events-none">sqm</span>
                                </div>
                            </div>

                            <div class="space-y-1 mb-8">
                                <div class="flex items-center justify-between py-3 border-b border-gray-200">
                                    <div class="text-[15px] text-brand-black">Total occupancy limit</div>
                                    <div class="flex items-center gap-4">
                                        <button type="button" @click="if(room.occupancy > 1) room.occupancy--" class="w-8 h-8 rounded-full border border-blue-200 text-blue-600 flex items-center justify-center hover:bg-blue-50 transition-colors">
                                            <i class="fas fa-minus text-[10px]"></i>
                                        </button>
                                        <input type="hidden" :name="'rooms['+index+'][max_adults]'" :value="room.occupancy">
                                        <span class="text-base font-bold w-4 text-center text-brand-black" x-text="room.occupancy"></span>
                                        <button type="button" @click="room.occupancy++" class="w-8 h-8 rounded-full border border-blue-200 text-blue-600 flex items-center justify-center hover:bg-blue-50 transition-colors">
                                            <i class="fas fa-plus text-[10px]"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between py-3 border-b border-gray-200">
                                    <div class="text-[15px] text-brand-black">Bathrooms</div>
                                    <div class="flex items-center gap-4">
                                        <button type="button" @click="if(room.bathrooms > 0) room.bathrooms--" class="w-8 h-8 rounded-full border border-blue-200 text-blue-600 flex items-center justify-center hover:bg-blue-50 transition-colors">
                                            <i class="fas fa-minus text-[10px]"></i>
                                        </button>
                                        <input type="hidden" :name="'rooms['+index+'][bathrooms]'" :value="room.bathrooms">
                                        <span class="text-base font-bold w-4 text-center text-brand-black" x-text="room.bathrooms"></span>
                                        <button type="button" @click="room.bathrooms++" class="w-8 h-8 rounded-full border border-blue-200 text-blue-600 flex items-center justify-center hover:bg-blue-50 transition-colors">
                                            <i class="fas fa-plus text-[10px]"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="relative">
                                <label class="absolute -top-2.5 left-3 bg-white px-1.5 text-[11px] font-medium text-gray-500 z-10">Minimum room rate</label>
                                <div class="relative">
                                    <input type="number" :name="'rooms['+index+'][base_price_per_night]'" x-model="room.price" min="0" step="0.01" class="block w-full pl-4 pr-24 py-3.5 text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary transition-colors" required>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-sm">USD / night</span>
                                    </div>
                                </div>
                                <p class="text-[11px] text-gray-500 mt-2 ml-1">Set your lowest possible rate for this room (not including promotions, taxes, and/or other fees)</p>
                            </div>
                            
                            <div class="mt-8 pt-6 border-t border-gray-200">
                                <h4 class="text-base font-bold text-brand-black mb-3">Breakfast</h4>
                                <p class="text-[13px] text-gray-500 mb-4">Do you provide breakfast at the property?</p>
                                <div class="space-y-3">
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="radio" :name="'rooms['+index+'][breakfast]'" value="yes" x-model="room.breakfast" class="text-blue-600 focus:ring-blue-500 w-4 h-4 border-gray-300">
                                        <span class="text-sm text-brand-black">Yes</span>
                                    </label>
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="radio" :name="'rooms['+index+'][breakfast]'" value="no" x-model="room.breakfast" class="text-blue-600 focus:ring-blue-500 w-4 h-4 border-gray-300">
                                        <span class="text-sm text-brand-black">No</span>
                                    </label>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end" x-show="rooms.length > 1">
                                <button type="button" @click="removeRoom(index)" class="text-sm text-red-500 hover:text-red-700 font-medium transition-colors">
                                    <i class="fas fa-trash-alt mr-1"></i> Remove Room
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="pt-4 border-b border-gray-100 pb-8">
                <button type="button" @click="addRoom()" class="inline-flex items-center px-5 py-2.5 border border-blue-200 text-sm font-medium rounded-full text-blue-600 bg-white hover:bg-blue-50 hover:border-blue-300 focus:outline-none transition-all shadow-sm">
                    <i class="fas fa-plus mr-2"></i> Add unit
                </button>
            </div>
            
            <p class="text-xs text-brand-muted">
                Availability for each room is set to 1 for the next 90 days using the minimum nightly rates you set. You can change this in the Availability Center or Calendar once you have completed listing your property.
            </p>
        </div>

        {{-- Step 6: Policies --}}
        <div x-show="currentStep === 5" x-transition:enter="animate-slide-right" class="card card-body space-y-5">
            <h2 class="section-heading"><i class="fas fa-file-contract text-brand-primary mr-2"></i>Policies</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="form-group">
                    <label class="form-label">Cancellation Policy</label>
                    <select name="cancellation_policy[type]" class="form-input-styled">
                        <option value="free">Free cancellation (before check-in)</option>
                        <option value="partial">Partial refund</option>
                        <option value="non_refundable">Non-refundable</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Free Cancel Days Before Check-in</label>
                    <input type="number" name="cancellation_policy[free_cancel_days]" class="form-input-styled" value="3" min="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Children Policy</label>
                    <select name="children_policy" class="form-input-styled">
                        <option value="allowed">Allowed</option>
                        <option value="not_allowed">Not Allowed</option>
                        <option value="allowed_extra_bed">Allowed with Extra Bed</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Pet Policy</label>
                    <select name="pet_policy" class="form-input-styled">
                        <option value="not_allowed">Not Allowed</option>
                        <option value="allowed">Allowed</option>
                        <option value="allowed_with_fee">Allowed with Fee</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Payment Policy</label>
                    <select name="payment_policy" class="form-input-styled">
                        <option value="online">Pay Online</option>
                        <option value="at_hotel">Pay at Hotel</option>
                        <option value="both">Both</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Extra Bed Policy</label>
                    <textarea name="extra_bed_policy" rows="2" class="form-input-styled" placeholder="e.g., Extra bed available for $25/night">{{ old('extra_bed_policy') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Step 7: Review & Submit --}}
        <div x-show="currentStep === 6" x-transition:enter="animate-slide-right" class="card card-body space-y-5">
            <h2 class="section-heading"><i class="fas fa-clipboard-check text-brand-primary mr-2"></i>Review & Submit</h2>

            <div class="bg-brand-surface rounded-xl p-6">
                <p class="text-sm text-brand-text mb-4">Please review your property details before saving. You can edit everything later.</p>

                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-sm">
                        <i class="fas fa-check-circle text-status-confirmed"></i>
                        <span>Basic information will be saved</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <i class="fas fa-info-circle text-status-info"></i>
                        <span>Property will be saved as <strong>Draft</strong></span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <i class="fas fa-exclamation-circle text-status-pending"></i>
                        <span>Add room types after saving, then submit for approval</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Navigation Buttons --}}
        <div class="flex items-center justify-between mt-6">
            <button type="button" @click="prevStep()" x-show="currentStep > 0" class="btn-ghost">
                <i class="fas fa-chevron-left"></i> Previous
            </button>
            <div x-show="currentStep === 0"></div>

            <button type="button" @click="nextStep()" x-show="currentStep < steps.length - 1" class="btn-primary">
                Next <i class="fas fa-chevron-right"></i>
            </button>

            <button type="submit" x-show="currentStep === steps.length - 1" class="btn-primary btn-lg" :disabled="isSubmitting">
                <i class="fas fa-save" x-show="!isSubmitting"></i>
                <i class="fas fa-spinner fa-spin" x-show="isSubmitting"></i>
                Save Property
            </button>
        </div>
    </form>
</div>

<script>
function propertyWizard() {
    return {
        currentStep: 0,
        isSubmitting: false,
        steps: ['Basic Info', 'Location', 'Photos', 'Amenities', 'Rooms', 'Policies', 'Review'],
        nextStep() { if (this.currentStep < this.steps.length - 1) this.currentStep++ },
        prevStep() { if (this.currentStep > 0) this.currentStep-- },
    }
}

function photoUploader() {
    return {
        previews: [],
        isDragging: false,
        handleFiles(event) {
            const files = event.target.files;
            for (let file of files) {
                this.previews.push({ url: URL.createObjectURL(file), file: file });
            }
        },
        handleDrop(event) {
            this.isDragging = false;
            const files = event.dataTransfer.files;
            const input = this.$refs.fileInput;
            const dt = new DataTransfer();
            // Merge existing + new
            if (input.files) for (let f of input.files) dt.items.add(f);
            for (let f of files) { dt.items.add(f); this.previews.push({ url: URL.createObjectURL(f), file: f }); }
            input.files = dt.files;
        },
        removePhoto(index) {
            URL.revokeObjectURL(this.previews[index].url);
            this.previews.splice(index, 1);
        },
    }
}

function roomManager() {
    return {
        predefinedRoomTypes: [
            'Classic Double or Twin', 'Deluxe', 'Deluxe Double or Twin',
            'Deluxe Suite', 'Double Room', 'Family Room', 'Junior Suite',
            'Standard City View', 'Standard Room', 'Studio', 'Villa',
            'Executive Room', 'Presidential Suite', 'Honeymoon Suite',
            'Single Room', 'Triple Room', 'Quadruple Room'
        ],
        rooms: [
            { name: '', size: '', occupancy: 1, bathrooms: 0, price: '', breakfast: 'no' }
        ],
        addRoom() {
            this.rooms.push({ name: '', size: '', occupancy: 1, bathrooms: 0, price: '', breakfast: 'no' });
        },
        removeRoom(index) {
            this.rooms.splice(index, 1);
        }
    }
}
</script>

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

        async reverseGeocode(lat, lng) {
            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`);
                const result = await response.json();
                if (result && result.address) {
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
            } catch (e) {
                console.error('Reverse geocoding failed', e);
            }
        },

        initMap() {
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

            this.marker.on("dragend", async (e) => {
                const pos = e.target.getLatLng();
                this.lat = pos.lat.toFixed(7);
                this.lng = pos.lng.toFixed(7);
                await this.reverseGeocode(this.lat, this.lng);
            });

            this.map.on("click", async (e) => {
                this.marker.setLatLng(e.latlng);
                this.lat = e.latlng.lat.toFixed(7);
                this.lng = e.latlng.lng.toFixed(7);
                await this.reverseGeocode(this.lat, this.lng);
            });
        },

        init() {
            // Bulletproof visibility check: initialize map when container becomes visible
            const visibilityCheck = setInterval(() => {
                const el = this.$refs.mapContainer;
                if (el && el.offsetHeight > 0) {
                    if (!this.map) {
                        this.initMap();
                    } else {
                        this.map.invalidateSize();
                        this.map.setView([parseFloat(this.lat), parseFloat(this.lng)]);
                    }
                }
            }, 250);

            // Also listen to window resize just in case
            window.addEventListener('resize', () => {
                if (this.map && this.$refs.mapContainer.offsetHeight > 0) {
                    this.map.invalidateSize();
                }
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
        }
    }));
});
</script>
@endpush

</x-pms-layout>
