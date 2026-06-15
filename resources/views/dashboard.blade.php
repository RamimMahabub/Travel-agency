<x-customer-layout>
    <x-slot name="pageTitle">Dashboard</x-slot>
    <x-slot name="pageSubtitle">Overview of your bookings and activities</x-slot>

    @php
        $totalBookings = auth()->user()->bookings()->count() + auth()->user()->hotelBookings()->count();
        $upcomingTrips = auth()->user()->bookings()->where('status', 'confirmed')->count() + auth()->user()->hotelBookings()->where('status', 'confirmed')->count();
    @endphp

    {{-- Top KPIs Row --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- KPI 1 --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-center relative overflow-hidden group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center transition-transform group-hover:scale-110">
                    <i class="fas fa-ticket-alt text-xl"></i>
                </div>
                <span class="bg-green-50 text-green-700 text-xs font-bold px-2 py-1 rounded-full"><i class="fas fa-arrow-up mr-1"></i> New</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ $totalBookings }}</h3>
            <p class="text-sm text-gray-500 font-medium">Total Bookings</p>
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-blue-50/50 rounded-full blur-xl group-hover:bg-blue-100 transition-colors"></div>
        </div>

        {{-- KPI 2 --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-center relative overflow-hidden group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center transition-transform group-hover:scale-110">
                    <i class="fas fa-plane-arrival text-xl"></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ $upcomingTrips }}</h3>
            <p class="text-sm text-gray-500 font-medium">Upcoming Trips</p>
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-green-50/50 rounded-full blur-xl group-hover:bg-green-100 transition-colors"></div>
        </div>

        {{-- KPI 3 --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-center relative overflow-hidden group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center transition-transform group-hover:scale-110">
                    <i class="fas fa-coins text-xl"></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-1">1,250</h3>
            <p class="text-sm text-gray-500 font-medium">Reward Points</p>
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-purple-50/50 rounded-full blur-xl group-hover:bg-purple-100 transition-colors"></div>
        </div>

        {{-- KPI 4 --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-center relative overflow-hidden group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center transition-transform group-hover:scale-110">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-1">0 <span class="text-lg text-gray-500 font-normal">BDT</span></h3>
            <p class="text-sm text-gray-500 font-medium">Wallet Balance</p>
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-orange-50/50 rounded-full blur-xl group-hover:bg-orange-100 transition-colors"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Left Column: Main Dashboard Content --}}
        <div class="lg:col-span-2 space-y-8">
            
            {{-- Quick Search Widget --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 animate-slide-up" style="animation-delay: 0.1s;">
                <h2 class="text-lg font-bold font-heading mb-6 text-gray-900">Start Planning</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Flights Card --}}
                    <a href="{{ route('flights.search') }}" class="group relative overflow-hidden rounded-xl border border-gray-100 bg-gray-50 p-6 transition-all hover:bg-white hover:shadow-md hover:border-brand-primary/30 flex items-center gap-5">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600 transition-transform group-hover:scale-110">
                            <i class="fas fa-plane-departure text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-base">Search Flights</h3>
                            <p class="text-xs text-gray-500">Find the best airlines</p>
                        </div>
                        <i class="fas fa-arrow-right absolute right-6 text-gray-300 opacity-0 transition-all group-hover:opacity-100 group-hover:translate-x-2 group-hover:text-brand-primary"></i>
                    </a>

                    {{-- Hotels Card --}}
                    <a href="{{ route('hotels.search') }}" class="group relative overflow-hidden rounded-xl border border-gray-100 bg-gray-50 p-6 transition-all hover:bg-white hover:shadow-md hover:border-brand-primary/30 flex items-center gap-5">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-teal-100 text-teal-600 transition-transform group-hover:scale-110">
                            <i class="fas fa-bed text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-base">Browse Hotels</h3>
                            <p class="text-xs text-gray-500">Book perfect stays</p>
                        </div>
                        <i class="fas fa-arrow-right absolute right-6 text-gray-300 opacity-0 transition-all group-hover:opacity-100 group-hover:translate-x-2 group-hover:text-brand-primary"></i>
                    </a>
                </div>
            </div>

            {{-- Recent Trips Section --}}
            <div x-data="{ tab: 'flights' }" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 animate-slide-up" style="animation-delay: 0.2s;">
                <div class="flex items-center justify-between mb-6 border-b border-gray-50 pb-4">
                    <h2 class="text-lg font-bold font-heading text-gray-900">Recent Bookings</h2>
                    
                    {{-- Tabs --}}
                    <div class="flex bg-gray-50 p-1 rounded-lg border border-gray-100">
                        <button @click="tab = 'flights'" :class="tab === 'flights' ? 'bg-white shadow-sm font-semibold text-brand-primary' : 'text-gray-500 hover:text-gray-700'" class="px-4 py-1.5 text-xs rounded-md transition-all">Flights</button>
                        <button @click="tab = 'hotels'" :class="tab === 'hotels' ? 'bg-white shadow-sm font-semibold text-brand-primary' : 'text-gray-500 hover:text-gray-700'" class="px-4 py-1.5 text-xs rounded-md transition-all">Hotels</button>
                    </div>
                </div>

                {{-- Flights Tab --}}
                <div x-show="tab === 'flights'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    @php
                        $flightBookings = auth()->user()->bookings()->latest()->take(3)->get();
                    @endphp

                    @if($flightBookings->isEmpty())
                        <div class="py-12 text-center flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-ticket-alt text-gray-300 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 mb-4 text-sm font-medium">You have no upcoming or past flights.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($flightBookings as $booking)
                                <a href="{{ route('booking.show', $booking->id) }}" class="block bg-gray-50 p-4 rounded-xl border border-gray-100 hover:bg-white hover:shadow-sm hover:border-brand-primary/30 transition-all group">
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-lg bg-brand-primary/10 flex items-center justify-center text-brand-primary shrink-0">
                                                <i class="fas fa-plane"></i>
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $booking->status == 'confirmed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                                        {{ strtoupper($booking->status) }}
                                                    </span>
                                                    <span class="text-xs text-gray-500 font-mono">{{ $booking->api_reference_id }}</span>
                                                </div>
                                                <p class="font-bold text-gray-900 group-hover:text-brand-primary text-sm transition-colors">Flight Booking</p>
                                                <p class="text-[11px] text-gray-500 mt-0.5"><i class="far fa-calendar-alt mr-1"></i> {{ $booking->created_at->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-gray-900">{{ number_format($booking->total_amount, 0) }} <span class="text-xs text-gray-500">BDT</span></p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                            @if(auth()->user()->bookings()->count() > 3)
                                <div class="text-center pt-2 border-t border-gray-50 mt-4">
                                    <a href="{{ route('my-bookings.index') }}" class="text-xs font-semibold text-brand-primary hover:underline">View all flights</a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Hotels Tab --}}
                <div x-show="tab === 'hotels'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    @php
                        $hotelBookings = auth()->user()->hotelBookings()->with('property')->latest()->take(3)->get();
                    @endphp

                    @if($hotelBookings->isEmpty())
                        <div class="py-12 text-center flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-hotel text-gray-300 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 mb-4 text-sm font-medium">You have no hotel reservations yet.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($hotelBookings as $booking)
                                <a href="{{ route('my-bookings.show', $booking->id) }}" class="block bg-gray-50 p-4 rounded-xl border border-gray-100 hover:bg-white hover:shadow-sm hover:border-brand-primary/30 transition-all group">
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-lg bg-gray-200 overflow-hidden shrink-0">
                                                @if($booking->property->photos && count($booking->property->photos) > 0)
                                                    <img src="{{ Storage::url($booking->property->photos[0]) }}" class="w-full h-full object-cover" alt="Hotel">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100"><i class="fas fa-building text-lg"></i></div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $booking->status == 'confirmed' ? 'bg-green-100 text-green-700' : ($booking->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                                                        {{ strtoupper($booking->status) }}
                                                    </span>
                                                </div>
                                                <p class="font-bold text-gray-900 text-sm group-hover:text-brand-primary transition-colors line-clamp-1">{{ $booking->property->name ?? 'Hotel Stay' }}</p>
                                                <p class="text-[11px] text-gray-500 mt-0.5"><i class="far fa-calendar-check mr-1"></i> {{ \Carbon\Carbon::parse($booking->check_in)->format('M d') }} - {{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                            @if(auth()->user()->hotelBookings()->count() > 3)
                                <div class="text-center pt-2 border-t border-gray-50 mt-4">
                                    <a href="{{ route('my-bookings.index') }}" class="text-xs font-semibold text-brand-primary hover:underline">View all hotel stays</a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column: Wallet & Offers --}}
        <div class="space-y-6 animate-slide-up" style="animation-delay: 0.3s;">
            
            {{-- Wallet Card (Premium UI) --}}
            <div class="bg-gradient-to-br from-[#19100F] to-[#2D1B1D] rounded-3xl p-6 relative overflow-hidden shadow-xl shadow-brand-black/20">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-brand-primary rounded-full opacity-20 blur-2xl"></div>
                
                <div class="relative z-10 flex justify-between items-start mb-6">
                    <div class="text-white/80 text-sm font-medium">Ghuri Wallet</div>
                    <i class="fas fa-wallet text-white/50 text-xl"></i>
                </div>
                
                <div class="relative z-10">
                    <p class="text-white/60 text-xs mb-1">Available Balance</p>
                    <div class="flex items-baseline gap-1">
                        <span class="text-3xl font-bold text-white tracking-tight">0</span>
                        <span class="text-white/60 font-medium text-sm">.00 BDT</span>
                    </div>
                </div>

                <div class="relative z-10 mt-6 flex gap-3">
                    <button class="flex-1 bg-white/10 hover:bg-white/20 text-white text-xs font-semibold py-2.5 rounded-xl backdrop-blur-sm transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-plus"></i> Add Funds
                    </button>
                    <button class="flex-1 bg-white hover:bg-gray-50 text-brand-black text-xs font-semibold py-2.5 rounded-xl transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-history"></i> History
                    </button>
                </div>
            </div>

            {{-- Offers Carousel/List --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex justify-between items-center mb-4 border-b border-gray-50 pb-3">
                    <h3 class="font-heading font-bold text-gray-900 text-sm tracking-wide uppercase">Special Offers</h3>
                    <span class="text-[10px] font-bold text-white bg-brand-primary px-2 py-0.5 rounded-full">NEW</span>
                </div>
                
                <div class="space-y-4">
                    <!-- Offer 1 -->
                    <div class="relative rounded-xl overflow-hidden group cursor-pointer h-32 shadow-sm">
                        <img src="https://images.unsplash.com/photo-1540541338287-41700207dee6?q=80&w=2070&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="Resort">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-4 w-full">
                            <span class="bg-white text-brand-primary text-[10px] font-extrabold px-2 py-0.5 rounded uppercase tracking-wider mb-1 inline-block shadow-sm">20% OFF</span>
                            <p class="text-white font-bold text-sm leading-tight drop-shadow-md">Summer getaway to Maldives</p>
                        </div>
                    </div>

                    <!-- Offer 2 -->
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl hover:bg-gray-100 border border-gray-100 hover:border-gray-200 transition-colors cursor-pointer">
                        <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center shrink-0">
                            <i class="fas fa-plane-arrival"></i>
                        </div>
                        <div>
                            <p class="font-bold text-sm text-gray-900">Fly to Dubai</p>
                            <p class="text-[11px] text-gray-500">Earn double points on Emirates</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-customer-layout>
