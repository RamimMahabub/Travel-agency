<x-app-layout>
    <div x-data="flightSearch({{ Js::from($flights) }})" x-cloak>
    <style>[x-cloak] { display: none !important; }</style>
    <!-- Top Header mimicking ShareTrip -->
    <div class="bg-blue-50 py-4 border-b border-blue-200 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-gray-800 tracking-tight">{{ $search['origin'] ?? 'DAC' }} — {{ $search['destination'] ?? 'DXB' }}</h1>
                <p class="text-xs text-gray-500 font-medium mt-1 uppercase tracking-wide">
                    {{ str_replace('_', ' ', $search['trip_type'] ?? 'one way') }} • 
                    {{ \Carbon\Carbon::parse($search['date'] ?? now())->format('d M') }} 
                    @if(($search['trip_type'] ?? 'one_way') == 'round_way' && !empty($search['return_date']))
                        - {{ \Carbon\Carbon::parse($search['return_date'])->format('d M') }}
                    @endif
                    • {{ $search['passengers'] ?? 1 }} Traveller • {{ ucfirst(str_replace('_', ' ', $search['class'] ?? 'economy')) }}
                </p>
            </div>
            <button class="bg-[#FFEFE5] text-[#FF7A00] hover:bg-[#FFE0CC] font-bold text-sm px-6 py-2 rounded-lg transition">
                Modify
            </button>
        </div>
    </div>

    <div class="bg-[#F5F7FA] min-h-screen pb-12 pt-6 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6 items-start">
                <!-- Sidebar -->
                <div class="w-full lg:w-[280px] flex-shrink-0 space-y-4">
                    
                    <!-- Time Remaining -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 flex items-center justify-between">
                        <div class="flex items-center gap-2 text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="font-bold text-sm">Time Remaining</span>
                        </div>
                        <span class="font-bold text-blue-600 text-lg" x-text="formattedTime"></span>
                    </div>

                    <!-- Price Range -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-4 border-b border-gray-100 flex justify-between items-center cursor-pointer hover:bg-gray-50">
                            <h3 class="font-bold text-gray-800 text-sm">Price Range</h3>
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                        </div>
                        <div class="p-4">
                            <p class="text-xs text-gray-500 leading-relaxed mb-4">Starts from <b x-text="currency + ' ' + formatNumber(minPrice)"></b> - <b x-text="currency + ' ' + formatNumber(maxPrice)"></b> against your search. Price is subject to change.</p>
                            
                            <div class="relative w-full h-6 flex items-center mb-1 mt-2">
                                <!-- Background Track -->
                                <div class="absolute w-full h-1.5 bg-gray-200 rounded-full"></div>
                                
                                <!-- Active Fill Track -->
                                <div class="absolute h-1.5 bg-blue-600 rounded-full left-0 pointer-events-none" :style="'width: ' + ((selectedMaxPrice - minPrice) / Math.max(1, (maxPrice - minPrice))) * 100 + '%'"></div>
                                
                                <!-- Slidable Range Input -->
                                <input type="range" :min="minPrice" :max="maxPrice" step="1" x-model.number="selectedMaxPrice" class="absolute w-full h-1.5 appearance-none bg-transparent cursor-pointer z-10 price-slider m-0 outline-none">
                            </div>
                            
                            <div class="text-center text-xs font-bold text-gray-700 mt-3" x-text="currency + ' ' + formatNumber(minPrice) + ' - ' + currency + ' ' + formatNumber(selectedMaxPrice)"></div>
                        </div>
                    </div>

                    <!-- Flight Schedules -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-4 border-b border-gray-100 flex justify-between items-center cursor-pointer hover:bg-gray-50">
                            <h3 class="font-bold text-gray-800 text-sm">Flight Schedules</h3>
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                        </div>
                        <div class="p-4">
                            <div class="flex rounded bg-blue-50/50 p-1 mb-4">
                                <button @click="scheduleTab = 'departure'" :class="scheduleTab === 'departure' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'" class="flex-1 rounded font-bold text-xs py-2 transition">Departure</button>
                                <button @click="scheduleTab = 'arrival'" :class="scheduleTab === 'arrival' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'" class="flex-1 rounded font-bold text-xs py-2 transition">Arrival</button>
                            </div>
                            
                            <!-- Origin Departure -->
                            <div class="mb-5 border-b border-gray-100 pb-5">
                                <h4 class="font-bold text-gray-800 text-xs mb-3"><span x-text="scheduleTab === 'departure' ? 'Departure' : 'Arrival'"></span> {{ $search['origin'] ?? 'Origin' }}: Anytime</h4>
                                <div class="grid grid-cols-2 gap-2">
                                    <template x-for="block in [['00-06', '12 AM - 06 AM', 'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z'], ['06-12', '06 AM - 12 PM', 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z'], ['12-18', '12 PM - 06 PM', 'M3 15h18M5 15v-2a7 7 0 0114 0v2'], ['18-24', '06 PM - 12 AM', 'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z']]">
                                        <button @click="toggleTimeBlock('outbound', block[0])" :class="timeFilters.outbound.includes(block[0]) ? 'border-blue-600 bg-blue-50 text-blue-600 shadow-sm' : 'border-gray-200 text-gray-500 hover:border-blue-300 hover:bg-blue-50'" class="border rounded p-2 flex items-center justify-center gap-1.5 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="block[2]"></path></svg>
                                            <span class="text-[10px] font-bold" x-text="block[1]"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <!-- Destination Departure -->
                            <div>
                                <h4 class="font-bold text-gray-800 text-xs mb-3"><span x-text="scheduleTab === 'departure' ? 'Departure' : 'Arrival'"></span> {{ $search['destination'] ?? 'Destination' }}: Anytime</h4>
                                <div class="grid grid-cols-2 gap-2">
                                    <template x-for="block in [['00-06', '12 AM - 06 AM', 'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z'], ['06-12', '06 AM - 12 PM', 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z'], ['12-18', '12 PM - 06 PM', 'M3 15h18M5 15v-2a7 7 0 0114 0v2'], ['18-24', '06 PM - 12 AM', 'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z']]">
                                        <button @click="toggleTimeBlock('inbound', block[0])" :class="timeFilters.inbound.includes(block[0]) ? 'border-blue-600 bg-blue-50 text-blue-600 shadow-sm' : 'border-gray-200 text-gray-500 hover:border-blue-300 hover:bg-blue-50'" class="border rounded p-2 flex items-center justify-center gap-1.5 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="block[2]"></path></svg>
                                            <span class="text-[10px] font-bold" x-text="block[1]"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Airlines -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-4 border-b border-gray-100 flex justify-between items-center cursor-pointer hover:bg-gray-50">
                            <h3 class="font-bold text-gray-800 text-sm">Airlines</h3>
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                        </div>
                        <div class="p-4 space-y-3">
                            <template x-for="al in computedAirlines" :key="al.airline">
                                <label class="flex justify-between items-center cursor-pointer group">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" :value="al.airline" x-model="selectedAirlines" class="w-4 h-4 rounded text-blue-600 border-gray-300 focus:ring-blue-600">
                                        <span class="text-xs font-semibold text-gray-700" x-text="al.airline"></span>
                                    </div>
                                    <span class="text-xs font-semibold text-gray-400 group-hover:text-blue-600" x-text="currency + ' ' + formatNumber(al.price)"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <!-- Refundability -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                         <div class="p-4 border-b border-gray-100 flex justify-between items-center cursor-pointer hover:bg-gray-50">
                            <h3 class="font-bold text-gray-800 text-sm">Refundability</h3>
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                        </div>
                        <div class="p-4 space-y-3">
                            <label class="flex justify-between items-center cursor-pointer group">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" value="refundable" x-model="refundFilter" class="w-4 h-4 rounded text-blue-600 border-gray-300 focus:ring-blue-600">
                                    <span class="text-xs font-semibold text-gray-700">Refundable</span>
                                </div>
                                <span class="text-[10px] font-semibold text-green-600 bg-green-50 px-1.5 py-0.5 rounded">Refundable</span>
                            </label>
                            <label class="flex justify-between items-center cursor-pointer group">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" value="non_refundable" x-model="refundFilter" class="w-4 h-4 rounded text-blue-600 border-gray-300 focus:ring-blue-600">
                                    <span class="text-xs font-semibold text-gray-700">Non Refundable</span>
                                </div>
                                <span class="text-[10px] font-semibold text-red-500 bg-red-50 px-1.5 py-0.5 rounded">Non-Refund</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Main Results -->
                <div class="w-full flex-1 flex flex-col gap-4">
                    
                    <div>
                        <h2 class="text-lg font-bold text-[#1a2b49] mb-3"><span x-text="filteredFlights.length"></span> Available Flights <span class="text-xs text-gray-400 font-normal ml-2 float-right md:float-none mt-1.5">*Price Includes VAT & Tax</span></h2>
                        
                        <!-- Airline Filter Carousel -->
                        <div class="flex items-center gap-2 bg-white rounded-lg shadow-sm border border-gray-100 p-2 overflow-x-auto hide-scrollbar">
                            <button class="p-2 text-gray-400 hover:bg-gray-50 rounded hidden sm:block"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
                            <div class="flex flex-1 gap-2 min-w-max">
                                <template x-for="cal in computedAirlines" :key="cal.airline_code">
                                     <div @click="toggleAirline(cal.airline)" :class="selectedAirlines.includes(cal.airline) ? 'border-blue-600 bg-blue-50 text-blue-600' : 'border-gray-100 hover:bg-gray-50 text-gray-700'" class="px-5 py-2 border rounded cursor-pointer flex items-center gap-3 relative overflow-hidden transition">
                                         <div x-show="selectedAirlines.includes(cal.airline)" class="absolute left-0 top-0 w-1 h-full bg-blue-600"></div>
                                         <div class="w-6 h-6 rounded bg-white flex items-center justify-center font-bold text-[8px] text-gray-600 shadow-sm" x-text="cal.airline_code"></div>
                                         <div class="text-left leading-tight">
                                             <span class="block text-[10px] font-bold" x-text="cal.airline_code"></span>
                                             <span class="block text-xs font-bold" x-text="formatNumber(cal.price)"></span>
                                         </div>
                                     </div>
                                </template>
                            </div>
                            <button class="p-2 text-gray-400 hover:bg-gray-50 rounded hidden sm:block"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
                        </div>
                    </div>

                    <!-- Sort Tabs -->
                    <div class="flex bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden divide-x divide-gray-100">
                        <button @click="sortBy = 'cheapest'" :class="sortBy === 'cheapest' ? 'bg-blue-600 text-white shadow-inner' : 'bg-white text-gray-600 hover:bg-gray-50'" class="flex-1 font-bold py-3 px-4 flex justify-between items-center transition">
                            <span class="text-sm">Cheapest</span>
                            <span class="text-sm" x-text="formatNumber(minPrice)"></span>
                        </button>
                        <button @click="sortBy = 'earliest'" :class="sortBy === 'earliest' ? 'bg-blue-600 text-white shadow-inner' : 'bg-white text-gray-600 hover:bg-gray-50'" class="flex-1 font-bold py-3 px-4 flex justify-between items-center transition">
                            <span class="text-sm">Earliest</span>
                            <span class="text-sm" x-text="formatNumber(earliestPrice)"></span>
                        </button>
                        <button @click="sortBy = 'fastest'" :class="sortBy === 'fastest' ? 'bg-blue-600 text-white shadow-inner' : 'bg-white text-gray-600 hover:bg-gray-50'" class="flex-1 font-bold py-3 px-4 flex justify-between items-center transition">
                            <span class="text-sm">Fastest</span>
                            <span class="text-sm" x-text="formatNumber(fastestPrice)"></span>
                        </button>
                    </div>

                    <!-- Cards -->
                    <template x-for="(flight, index) in paginatedFlights" :key="flight.id"><div>
                        <!-- Inject Ad Banner mock after the first flight -->
                        <template x-if="index === 1">
                            <div class="w-full h-24 bg-gradient-to-r from-[#1E293B] to-[#0F172A] rounded-lg shadow-sm overflow-hidden flex items-center px-8 relative mt-2 mb-2">
                                <div class="absolute right-0 top-0 text-[10px] bg-white text-gray-600 px-2 py-0.5 rounded-bl">AD</div>
                                <div class="text-white">
                                    <h2 class="text-3xl font-light">Place Your <span class="bg-[#FF9900] text-white font-bold px-3 py-1 rounded">AD Here</span></h2>
                                </div>
                                <div class="ml-auto w-16 h-10 bg-white rounded flex items-center justify-center font-bold text-blue-600 italic">VISA</div>
                            </div>
                        </template>

                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 relative">
                            <!-- Ribbon -->
                            <template x-if="flight.is_best_deal">
                                <div class="absolute -top-[1px] -left-[1px] bg-green-500 text-white text-[10px] font-bold px-3 py-1 rounded-br-lg rounded-tl-lg shadow-sm z-10 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path></svg>
                                    Best Deal
                                </div>
                            </template>
                            <template x-if="!flight.is_best_deal && flight.is_preferred">
                                <div class="absolute -top-[1px] -left-[1px] bg-purple-500 text-white text-[10px] font-bold px-3 py-1 rounded-br-lg rounded-tl-lg shadow-sm z-10 flex items-center gap-1">
                                    Preferred Deal
                                </div>
                            </template>

                            <div class="flex flex-col md:flex-row pt-6 pb-2">
                                <!-- Flight Legs (Left Side) -->
                                <div class="w-full md:w-3/4 px-6 flex flex-col gap-6 border-b md:border-b-0 md:border-r border-gray-100 pb-4 md:pb-0">
                                    
                                    <!-- Outbound -->
                                    <div class="flex items-center gap-4">
                                        <div class="w-10">
                                            <div class="w-8 h-8 rounded bg-white flex items-center justify-center font-bold text-gray-500 text-xs shadow-sm border border-gray-100 overflow-hidden"><img :src="'https://images.kiwi.com/airlines/64/' + flight.airline_code + '.png'" @@error="$el.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(flight.airline) + '&color=1c56ef&background=F3F4F6&font-size=0.4'" class="w-full h-full object-contain p-1"></div>
                                        </div>
                                        <div class="flex-1 grid grid-cols-3 gap-2">
                                            <div>
                                                <p class="text-xs font-bold text-gray-800"><span x-text="flight.outbound.origin"></span> - <span x-text="flight.outbound.destination"></span></p>
                                                <p class="text-[10px] text-gray-500 truncate"><span x-text="flight.airline"></span></p>
                                                <p class="text-[10px] text-gray-400 mt-0.5"><span x-text="flight.outbound.duration"></span></p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-800"><span x-text="formatTime(flight.outbound.departure_time)"></span></p>
                                                <p class="text-[10px] text-gray-500"><span x-text="formatDate(flight.outbound.departure_time)"></span></p>
                                                <p class="text-[10px] text-gray-400 mt-0.5 truncate"><span x-text="flight.outbound.origin"></span> Airport</p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-800"><span x-text="formatTime(flight.outbound.arrival_time)"></span></p>
                                                <p class="text-[10px] text-gray-500"><span x-text="formatDate(flight.outbound.arrival_time)"></span></p>
                                                <p class="text-[10px] text-gray-400 mt-0.5 truncate"><span x-text="flight.outbound.destination"></span> Airport</p>
                                            </div>
                                        </div>
                                        <div class="w-16 text-right">
                                            <p class="text-[11px] font-bold text-gray-800"><span x-text="flight.outbound.stops == 0 ? 'Non-Stop' : flight.outbound.stops + ' Stop'"></span></p>
                                            <p class="text-[10px] text-gray-500"><span x-text="flight.outbound.destination"></span></p>
                                        </div>
                                    </div>

                                    <!-- Inbound -->
                                    <div class="flex items-center gap-4 mt-6 md:mt-4 pt-4 md:pt-0 border-t md:border-t-0 border-gray-100" x-show="flight.inbound">
                                        <div class="w-10">
                                            <div class="w-8 h-8 rounded bg-white flex items-center justify-center font-bold text-gray-500 text-xs shadow-sm border border-gray-100 overflow-hidden"><img :src="'https://images.kiwi.com/airlines/64/' + flight.airline_code + '.png'" @@error="$el.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(flight.airline) + '&color=1c56ef&background=F3F4F6&font-size=0.4'" class="w-full h-full object-contain p-1"></div>
                                        </div>
                                        <div class="flex-1 grid grid-cols-3 gap-2">
                                            <div>
                                                <p class="text-xs font-bold text-gray-800"><span x-text="flight.inbound ? flight.inbound.origin : ''"></span> - <span x-text="flight.inbound ? flight.inbound.destination : ''"></span></p>
                                                <p class="text-[10px] text-gray-500 truncate"><span x-text="flight.airline"></span></p>
                                                <p class="text-[10px] text-gray-400 mt-0.5"><span x-text="flight.inbound ? flight.inbound.duration : ''"></span></p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-800"><span x-text="flight.inbound ? formatTime(flight.inbound.departure_time) : ''"></span></p>
                                                <p class="text-[10px] text-gray-500"><span x-text="flight.inbound ? formatDate(flight.inbound.departure_time) : ''"></span></p>
                                                <p class="text-[10px] text-gray-400 mt-0.5 truncate" x-show="flight.inbound"><span x-text="flight.inbound.origin"></span> Airport</p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-800"><span x-text="flight.inbound ? formatTime(flight.inbound.arrival_time) : ''"></span></p>
                                                <p class="text-[10px] text-gray-500"><span x-text="flight.inbound ? formatDate(flight.inbound.arrival_time) : ''"></span></p>
                                                <p class="text-[10px] text-gray-400 mt-0.5 truncate" x-show="flight.inbound"><span x-text="flight.inbound.destination"></span> Airport</p>
                                            </div>
                                        </div>
                                        <div class="w-16 text-right">
                                            <p class="text-[11px] font-bold text-gray-800"><span x-text="flight.inbound ? (flight.inbound.stops == 0 ? 'Non-Stop' : flight.inbound.stops + ' Stop') : ''"></span></p>
                                            <p class="text-[10px] text-gray-500"><span x-text="flight.inbound ? flight.inbound.destination : ''"></span></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Pane (Right Side) -->
                                <div class="w-full md:w-1/4 px-6 flex flex-col justify-center items-end py-4 md:py-0">
                                    <p class="text-[10px] font-bold text-[#1882FF] tracking-wider mb-1 uppercase flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        Trawex
                                    </p>
                                    <h3 class="text-2xl font-bold text-gray-800"><span x-text="flight.currency + ' ' + formatNumber(flight.price)"></span></h3>
                                    <template x-if="flight.crossed_price">
                                        <p class="text-xs text-gray-400 line-through mb-3"><span x-text="flight.currency + ' ' + formatNumber(flight.crossed_price)"></span></p>
                                    </template>
                                    <template x-if="!flight.crossed_price">
                                        <div class="h-5"></div>
                                    </template>
                                    <a :href="'/booking/checkout/' + flight.id + '?passengers={{ $search['passengers'] ?? 1 }}'" class="bg-[#1882FF] hover:bg-blue-600 text-white font-bold py-2 px-6 rounded shadow-md hover:shadow-lg transition flex items-center justify-between w-28">
                                        Select
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Bottom Action Row -->
                            <div class="bg-gray-50 border-t border-gray-100 flex justify-between items-center px-4 py-2.5 rounded-b-lg">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-1.5 text-[10px] font-bold text-green-700 bg-green-100/50 px-2 py-0.5 rounded">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span x-text="flight.refundable ? 'Partially Refundable' : 'Non Refundable'"></span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-700 bg-white border border-gray-200 px-2 py-0.5 rounded shadow-sm">
                                        <span class="w-3 h-3 bg-[#FF9900] text-white rounded-full flex items-center justify-center text-[8px] font-black">C</span>
                                        <span x-text="flight.points"></span>
                                    </div>
                                </div>
                                <button class="text-xs font-bold text-blue-600 hover:text-blue-800 transition flex items-center gap-1">
                                    View Detail
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div></template>

                    <!-- Pagination Controls -->
                    <template x-if="totalPages > 1 && filteredFlights.length > 0">
                        <div class="flex justify-center items-center gap-2 mt-6 mb-8 pt-4 border-t border-gray-100">
                            <button @click="prevPage()" :disabled="currentPage === 1" :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed text-gray-400' : 'text-blue-600 hover:bg-blue-50'" class="px-5 py-2.5 border border-gray-200 rounded-lg bg-white font-bold text-sm transition shadow-sm">
                                Previous
                            </button>
                            <div class="px-4 py-2 text-sm font-bold text-gray-600 bg-white border border-gray-100 rounded-lg shadow-sm">
                                Page <span x-text="currentPage"></span> of <span x-text="totalPages"></span>
                            </div>
                            <button @click="nextPage()" :disabled="currentPage === totalPages" :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed text-gray-400' : 'text-blue-600 hover:bg-blue-50'" class="px-5 py-2.5 border border-gray-200 rounded-lg bg-white font-bold text-sm transition shadow-sm">
                                Next
                            </button>
                        </div>
                    </template>
                    
                    <template x-if="filteredFlights.length === 0">
                        <div class="bg-white p-16 rounded-xl shadow-sm text-center border border-gray-200">
                            <h3 class="text-xl font-bold text-gray-800">No flights found</h3>
                            <p class="text-gray-500 mt-2">Try adjusting your search criteria.</p>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        .price-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #2563eb;
            border: 2px solid white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.3);
            cursor: pointer;
        }
        .price-slider::-moz-range-thumb {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #2563eb;
            border: 2px solid white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.3);
            cursor: pointer;
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('flightSearch', (initialFlights) => ({
                flights: initialFlights || [],
                selectedAirlines: [],
                sortBy: 'cheapest',
                currency: '',
                refundFilter: [], // 'refundable' and/or 'non_refundable'
                
                scheduleTab: 'departure',
                timeFilters: {
                    outbound: [],
                    inbound: []
                },

                timeRemaining: 1759, // 29m 19s
                selectedMaxPrice: null,

                currentPage: 1,
                perPage: 20,

                init() {
                    if (this.flights.length > 0) {
                        this.currency = this.flights[0].currency;
                    }
                    this.$watch('sortBy', () => this.currentPage = 1);
                    this.$watch('selectedMaxPrice', () => this.currentPage = 1);
                    this.selectedMaxPrice = this.maxPrice;
                    
                    setInterval(() => {
                        if (this.timeRemaining > 0) this.timeRemaining--;
                    }, 1000);
                },

                get formattedTime() {
                    const m = Math.floor(this.timeRemaining / 60).toString().padStart(2, '0');
                    const s = (this.timeRemaining % 60).toString().padStart(2, '0');
                    return m + ':' + s;
                },

                get computedAirlines() {
                    const map = {};
                    this.flights.forEach(f => {
                        if (!map[f.airline]) {
                            map[f.airline] = f;
                        } else if (parseFloat(f.price) < parseFloat(map[f.airline].price)) {
                            map[f.airline] = f;
                        }
                    });
                    return Object.values(map).sort((a,b) => parseFloat(a.price) - parseFloat(b.price));
                },

                get minPrice() {
                    if (this.flights.length === 0) return 0;
                    return Math.min(...this.flights.map(f => parseFloat(f.price)));
                },

                get maxPrice() {
                    if (this.flights.length === 0) return 1000;
                    return Math.max(...this.flights.map(f => parseFloat(f.price)));
                },

                get earliestPrice() {
                    if (this.flights.length === 0) return 0;
                    return this.flights.reduce((earliest, f) => {
                        return new Date(f.outbound.departure_time) < new Date(earliest.outbound.departure_time) ? f : earliest;
                    }, this.flights[0]).price;
                },

                get fastestPrice() {
                    if (this.flights.length === 0) return 0;
                    return this.flights.reduce((fastest, f) => {
                        return this.getFlightDuration(f) < this.getFlightDuration(fastest) ? f : fastest;
                    }, this.flights[0]).price;
                },

                getFlightDuration(flight) {
                    const parseDuration = (dur) => {
                        if (!dur) return 0;
                        let days = 0, hours = 0, mins = 0;
                        const dMatch = dur.match(/(\d+)d/i);
                        const hMatch = dur.match(/(\d+)h/i);
                        const mMatch = dur.match(/(\d+)m/i);
                        if (dMatch) days = parseInt(dMatch[1]);
                        if (hMatch) hours = parseInt(hMatch[1]);
                        if (mMatch) mins = parseInt(mMatch[1]);
                        return (days * 24 * 60) + (hours * 60) + mins;
                    };
                    return parseDuration(flight.outbound.duration) + (flight.inbound ? parseDuration(flight.inbound.duration) : 0);
                },

                get filteredFlights() {
                    let result = [...this.flights];

                    if (this.selectedMaxPrice !== null) {
                        result = result.filter(f => parseFloat(f.price) <= this.selectedMaxPrice);
                    }

                    if (this.refundFilter.length > 0) {
                        result = result.filter(f => {
                            if (this.refundFilter.includes('refundable') && f.refundable) return true;
                            if (this.refundFilter.includes('non_refundable') && !f.refundable) return true;
                            return false;
                        });
                    }

                    if (this.selectedAirlines.length > 0) {
                        result = result.filter(f => this.selectedAirlines.includes(f.airline));
                    }
                    
                    if (this.timeFilters.outbound.length > 0 || this.timeFilters.inbound.length > 0) {
                        const checkTimeBlock = (isoStr, blocks) => {
                            if (blocks.length === 0) return true;
                            if (!isoStr) return false;
                            const hour = new Date(isoStr).getHours();
                            for (let b of blocks) {
                                if (b === '00-06' && hour >= 0 && hour < 6) return true;
                                if (b === '06-12' && hour >= 6 && hour < 12) return true;
                                if (b === '12-18' && hour >= 12 && hour < 18) return true;
                                if (b === '18-24' && hour >= 18 && hour < 24) return true;
                            }
                            return false;
                        };

                        result = result.filter(f => {
                            const outStr = this.scheduleTab === 'departure' ? f.outbound.departure_time : f.outbound.arrival_time;
                            const inStr = f.inbound ? (this.scheduleTab === 'departure' ? f.inbound.departure_time : f.inbound.arrival_time) : null;
                            
                            const outValid = checkTimeBlock(outStr, this.timeFilters.outbound);
                            const inValid = f.inbound ? checkTimeBlock(inStr, this.timeFilters.inbound) : true;
                            
                            return outValid && inValid;
                        });
                    }

                    if (this.sortBy === 'cheapest') {
                        result = result.sort((a, b) => parseFloat(a.price) - parseFloat(b.price));
                    } else if (this.sortBy === 'earliest') {
                        result = result.sort((a, b) => new Date(a.outbound.departure_time) - new Date(b.outbound.departure_time));
                    } else if (this.sortBy === 'fastest') {
                        result = result.sort((a, b) => this.getFlightDuration(a) - this.getFlightDuration(b));
                    }

                    return result;
                },

                get paginatedFlights() {
                    const start = (this.currentPage - 1) * this.perPage;
                    const end = start + this.perPage;
                    return this.filteredFlights.slice(start, end);
                },

                get totalPages() {
                    return Math.max(1, Math.ceil(this.filteredFlights.length / this.perPage));
                },

                nextPage() {
                    if (this.currentPage < this.totalPages) {
                        this.currentPage++;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },

                prevPage() {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },

                formatTime(datetimeStr) {
                    const dt = new Date(datetimeStr);
                    return dt.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                },

                formatDate(datetimeStr) {
                    const dt = new Date(datetimeStr);
                    return dt.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', weekday: 'long' });
                },

                formatNumber(num) {
                    return new Intl.NumberFormat('en-US').format(num);
                },
                
                toggleTimeBlock(leg, block) {
                    const idx = this.timeFilters[leg].indexOf(block);
                    if (idx > -1) {
                        this.timeFilters[leg].splice(idx, 1);
                    } else {
                        this.timeFilters[leg].push(block);
                    }
                    this.currentPage = 1;
                },
                
                toggleAirline(airline) {
                    const idx = this.selectedAirlines.indexOf(airline);
                    if (idx > -1) {
                        this.selectedAirlines.splice(idx, 1);
                    } else {
                        this.selectedAirlines.push(airline);
                    }
                    this.currentPage = 1;
                }
            }));
        });
    </script>
</x-app-layout>


