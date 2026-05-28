<x-customer-layout>
    {{-- Welcome Section --}}
    <div class="mb-8 animate-slide-up">
        <h1 class="text-3xl font-heading font-bold text-gray-900">Welcome back, {{ explode(' ', Auth::user()->name ?? 'Traveler')[0] }}! 🌍</h1>
        <p class="text-gray-500 mt-1">Ready for your next adventure?</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Left Column: Main Dashboard Content (Flights & Hotels) --}}
        <div class="lg:col-span-2 space-y-8">
            
            {{-- Quick Search Widget --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 animate-slide-up" style="animation-delay: 0.1s;">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold font-heading">Book a Trip</h2>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('flights.search') }}" class="flex flex-col items-center justify-center p-6 bg-brand-background rounded-xl hover:bg-brand-primary/5 hover:border-brand-primary/20 border border-transparent transition-all group">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm mb-3 group-hover:text-brand-primary text-gray-600 transition-colors">
                            <i class="fas fa-plane-departure text-xl"></i>
                        </div>
                        <span class="font-semibold text-gray-800">Flights</span>
                    </a>
                    <a href="{{ route('hotels.search') }}" class="flex flex-col items-center justify-center p-6 bg-brand-background rounded-xl hover:bg-brand-primary/5 hover:border-brand-primary/20 border border-transparent transition-all group">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm mb-3 group-hover:text-brand-primary text-gray-600 transition-colors">
                            <i class="fas fa-bed text-xl"></i>
                        </div>
                        <span class="font-semibold text-gray-800">Hotels</span>
                    </a>
                </div>
            </div>

            {{-- Recent Trips Section --}}
            <div x-data="{ tab: 'flights' }" class="animate-slide-up" style="animation-delay: 0.2s;">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold font-heading">Recent Trips</h2>
                    
                    {{-- Tabs --}}
                    <div class="flex bg-gray-100 p-1 rounded-lg">
                        <button @click="tab = 'flights'" :class="tab === 'flights' ? 'bg-white shadow-sm font-semibold' : 'text-gray-500 hover:text-gray-700'" class="px-4 py-1.5 text-sm rounded-md transition-all">Flights</button>
                        <button @click="tab = 'hotels'" :class="tab === 'hotels' ? 'bg-white shadow-sm font-semibold' : 'text-gray-500 hover:text-gray-700'" class="px-4 py-1.5 text-sm rounded-md transition-all">Hotels</button>
                    </div>
                </div>

                {{-- Flights Tab --}}
                <div x-show="tab === 'flights'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    @php
                        $flightBookings = auth()->user()->bookings()->latest()->take(3)->get();
                    @endphp

                    @if($flightBookings->isEmpty())
                        <div class="bg-white p-10 rounded-2xl shadow-sm border border-gray-100 text-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-ticket-alt text-gray-300 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 mb-4 font-medium">No flight bookings yet.</p>
                            <a href="{{ route('flights.search') }}" class="text-brand-primary font-semibold hover:underline">Search Flights →</a>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($flightBookings as $booking)
                                <a href="{{ route('booking.show', $booking->id) }}" class="block bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-brand-primary/30 transition-all group">
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-xl bg-brand-primary/10 flex items-center justify-center text-brand-primary shrink-0">
                                                <i class="fas fa-plane"></i>
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $booking->status == 'confirmed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                                        {{ strtoupper($booking->status) }}
                                                    </span>
                                                    <span class="text-xs text-gray-500 font-mono">{{ $booking->api_reference_id }}</span>
                                                </div>
                                                <p class="font-bold text-gray-900 group-hover:text-brand-primary transition-colors">Flight Booking</p>
                                                <p class="text-xs text-gray-500 mt-1"><i class="far fa-calendar-alt mr-1"></i> {{ $booking->created_at->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-lg text-gray-900">{{ number_format($booking->total_amount, 0) }} <span class="text-xs text-gray-500">BDT</span></p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                            @if(auth()->user()->bookings()->count() > 3)
                                <div class="text-center mt-4">
                                    <a href="#" class="text-sm font-semibold text-brand-primary hover:underline">View all flights</a>
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
                        <div class="bg-white p-10 rounded-2xl shadow-sm border border-gray-100 text-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-hotel text-gray-300 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 mb-4 font-medium">No hotel bookings yet.</p>
                            <a href="{{ route('hotels.search') }}" class="text-brand-primary font-semibold hover:underline">Browse Hotels →</a>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($hotelBookings as $booking)
                                <a href="{{ route('my-bookings.show', $booking->id) }}" class="block bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-brand-primary/30 transition-all group">
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-center gap-4">
                                            <div class="w-16 h-16 rounded-xl bg-gray-200 overflow-hidden shrink-0">
                                                @if($booking->property->photos && count($booking->property->photos) > 0)
                                                    <img src="{{ Storage::url($booking->property->photos[0]) }}" class="w-full h-full object-cover" alt="Hotel">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100"><i class="fas fa-building text-xl"></i></div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $booking->status == 'confirmed' ? 'bg-green-100 text-green-700' : ($booking->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                                                        {{ strtoupper($booking->status) }}
                                                    </span>
                                                </div>
                                                <p class="font-bold text-gray-900 group-hover:text-brand-primary transition-colors line-clamp-1">{{ $booking->property->name ?? 'Hotel Stay' }}</p>
                                                <p class="text-xs text-gray-500 mt-1"><i class="far fa-calendar-check mr-1"></i> {{ \Carbon\Carbon::parse($booking->check_in)->format('M d') }} - {{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                            @if(auth()->user()->hotelBookings()->count() > 3)
                                <div class="text-center mt-4">
                                    <a href="{{ route('my-bookings.index') }}" class="text-sm font-semibold text-brand-primary hover:underline">View all hotel stays</a>
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
                
                <div class="relative z-10 flex justify-between items-start mb-8">
                    <div class="text-white/80 text-sm font-medium">Ghuri Wallet</div>
                    <i class="fas fa-wallet text-white/50 text-xl"></i>
                </div>
                
                <div class="relative z-10">
                    <p class="text-white/60 text-xs mb-1">Available Balance</p>
                    <div class="flex items-baseline gap-1">
                        <span class="text-3xl font-bold text-white tracking-tight">0</span>
                        <span class="text-white/60 font-medium">.00 BDT</span>
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
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-heading font-bold text-lg">Special Offers</h3>
                    <span class="text-xs font-bold text-brand-primary bg-brand-primary/10 px-2 py-1 rounded-md">New</span>
                </div>
                
                <div class="space-y-4">
                    <!-- Offer 1 -->
                    <div class="relative rounded-xl overflow-hidden group cursor-pointer h-32">
                        <img src="https://images.unsplash.com/photo-1540541338287-41700207dee6?q=80&w=2070&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="Resort">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-4">
                            <span class="bg-brand-primary text-white text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider mb-1 inline-block">20% OFF</span>
                            <p class="text-white font-bold text-sm leading-tight">Summer getaway to Maldives</p>
                        </div>
                    </div>

                    <!-- Offer 2 -->
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center shrink-0">
                            <i class="fas fa-plane-arrival"></i>
                        </div>
                        <div>
                            <p class="font-bold text-sm text-gray-900">Fly to Dubai</p>
                            <p class="text-xs text-gray-500">Earn double points on Emirates</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Profile Settings Shortcut --}}
            <a href="{{ route('profile.edit') }}" class="flex items-center justify-between p-4 bg-white rounded-2xl shadow-sm border border-gray-100 hover:border-gray-300 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center text-gray-600">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-sm text-gray-900">Profile Settings</p>
                        <p class="text-xs text-gray-500">Manage account details</p>
                    </div>
                </div>
                <i class="fas fa-chevron-right text-gray-400 text-sm"></i>
            </a>

        </div>
    </div>
</x-customer-layout>
