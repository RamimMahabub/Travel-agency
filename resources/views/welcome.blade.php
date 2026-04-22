<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GHURI - OTA Platform</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-dark selection:bg-[#1882FF] selection:text-white">
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative flex justify-between h-20 items-center">
            <div class="flex items-center gap-2 cursor-pointer">
                <div class="font-poppins font-extrabold text-3xl tracking-tighter text-[#1a2b49]">
                    GHURI<span class="text-[#1882FF]">.</span>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-gray-600 hover:text-[#1882FF] px-4 py-2 transition border border-gray-200 rounded-md">Dashboard</a>
                    @else
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm font-semibold text-gray-600 hover:text-[#1882FF] px-4 py-2 transition border border-gray-200 rounded-md">Sign Up</a>
                        @endif
                        <a href="{{ route('login') }}" class="bg-[#1a2b49] hover:bg-[#2a3b59] text-white text-sm font-semibold py-2 px-6 rounded-md transition shadow-sm flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                            Login
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative isolate overflow-hidden h-[520px] md:h-[620px] w-full bg-[#F7C6D4]">
        <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(255,255,255,0.06)_0%,rgba(255,255,255,0.28)_100%)]"></div>
        <div class="absolute inset-0 hidden md:block w-full h-full bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('hero-bg.png') }}'); background-position: center top;"></div>
        <div class="absolute inset-0 block md:hidden w-full h-full bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('HERO PIC.png') }}'); background-position: center center;"></div>
    </div>

    <!-- Search Component Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-40 -mt-10 md:-mt-14 pb-8">
        <div class="bg-white rounded-[28px] shadow-[0_24px_70px_rgba(15,23,42,0.14)] relative p-3 md:p-5" x-data="{ tab: 'flights', tripType: 'one_way' }">
            
            <!-- Tabs inside the box like image -->
            <div class="flex justify-center -mt-8 md:-mt-9 mb-4">
                <div class="flex bg-white rounded-full shadow-lg border border-gray-100 overflow-hidden text-sm font-bold px-1 py-1">
                    <button @click="tab = 'flights'" :class="tab === 'flights' ? 'text-[#1882FF] bg-blue-50/70' : 'text-gray-500 hover:bg-gray-50'" class="px-6 py-3 rounded-full transition flex items-center gap-2 border-b-2" :class="tab === 'flights' ? 'border-[#1882FF]' : 'border-transparent'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 104 0 2 2 0 012-2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Flight
                    </button>
                    <button @click="tab = 'hotels'" :class="tab === 'hotels' ? 'text-[#1882FF] bg-blue-50/70' : 'text-gray-500 hover:bg-gray-50'" class="px-6 py-3 rounded-full transition flex items-center gap-2 border-b-2" :class="tab === 'hotels' ? 'border-[#1882FF]' : 'border-transparent'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg> Hotel
                    </button>
                    <button @click="tab = 'b2b'" :class="tab === 'b2b' ? 'text-[#1882FF] bg-blue-50/70' : 'text-gray-500 hover:bg-gray-50'" class="px-6 py-3 rounded-full transition flex items-center gap-2 border-b-2" :class="tab === 'b2b' ? 'border-[#1882FF]' : 'border-transparent'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg> B-2-B
                    </button>
                    <button @click="tab = 'insurance'" :class="tab === 'insurance' ? 'text-[#1882FF] bg-blue-50/70' : 'text-gray-500 hover:bg-gray-50'" class="px-6 py-3 rounded-full transition flex items-center gap-2 border-b-2" :class="tab === 'insurance' ? 'border-[#1882FF]' : 'border-transparent'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg> Insurance
                    </button>
                </div>
            </div>

            <!-- Tab Contents -->
            <div class="p-4 md:p-6 pt-0">
                <!-- Flights Search Form -->
                <div x-show="tab === 'flights'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    @if($errors->has('search'))
                        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700 font-medium">
                                        {{ $errors->first('search') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                    <form action="{{ route('flights.search') }}" method="GET">
                        
                        <!-- Trip Options (Pill shaped) -->
                        <div class="flex items-center gap-2 mb-6">
                            <div class="flex bg-[#1E204A] text-white rounded-md p-1 text-[11px] font-bold">
                                <label class="flex items-center px-4 py-1.5 cursor-pointer rounded transition-colors" :class="tripType === 'one_way' ? 'bg-[#3A3C6B]' : 'hover:bg-[#2A2C5B]'">
                                    <input type="radio" name="trip_type" value="one_way" x-model="tripType" class="hidden">
                                    <span>One Way</span>
                                </label>
                                <label class="flex items-center px-4 py-1.5 cursor-pointer rounded transition-colors" :class="tripType === 'round_way' ? 'bg-[#3A3C6B]' : 'hover:bg-[#2A2C5B]'">
                                    <input type="radio" name="trip_type" value="round_way" x-model="tripType" class="hidden">
                                    <span>Round Way</span>
                                </label>
                                <label class="flex items-center px-4 py-1.5 cursor-pointer rounded transition-colors" :class="tripType === 'multi_city' ? 'bg-[#3A3C6B]' : 'hover:bg-[#2A2C5B]'">
                                    <input type="radio" name="trip_type" value="multi_city" x-model="tripType" class="hidden">
                                    <span>Multi Way</span>
                                </label>
                            </div>
                        </div>

                        <!-- Row 1: From & To -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-2 relative z-10 mb-2">

                            <!-- From -->
                            <div class="relative border border-gray-200 rounded-xl bg-white p-3 hover:border-[#1882FF] transition group" x-data="airportSearch('DAC')" @click="$refs.inputFrom.focus()">
                                <label class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wide mb-1">From</label>
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-[#1882FF] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                    <div class="flex-1 min-w-0">
                                        <input type="hidden" name="origin" x-model="selectedCode">
                                        <input x-ref="inputFrom" type="text" x-model="search" @focus="clearSelection()" @input.debounce.300ms="filter" @click.away="open = false" placeholder="City or airport" class="w-full border-none p-0 focus:ring-0 font-bold text-sm text-[#1a2b49] bg-transparent" required autocomplete="off">
                                        <div class="text-[11px] text-gray-400 truncate mt-0.5" x-text="selectedCode ? '(' + selectedCode + ') ' + selectedDisplay : 'Select departure airport'"></div>

                                        <!-- Dropdown -->
                                        <div x-show="open" style="display:none;" class="absolute z-50 left-0 top-full min-w-[320px] mt-1 bg-white rounded-xl shadow-2xl border border-gray-100 max-h-72 overflow-y-auto">
                                            <template x-if="loading"><div class="px-4 py-3 text-sm text-gray-500">Searching...</div></template>
                                            <template x-if="!loading && search.length >= 2 && filtered.length === 0"><div class="px-4 py-3 text-sm text-gray-500">No airports found.</div></template>
                                            <template x-for="airport in filtered" :key="airport.code">
                                                <div @click.stop="select(airport)" class="px-4 py-3 hover:bg-blue-50 cursor-pointer flex items-center justify-between border-b border-gray-50 last:border-0 transition">
                                                    <div class="flex flex-col truncate">
                                                        <span class="font-bold text-[#1a2b49] text-sm" x-text="airport.city || airport.name"></span>
                                                        <span class="text-gray-400 text-[10px]" x-text="airport.name"></span>
                                                    </div>
                                                    <span class="font-bold text-[#1882FF] bg-blue-50 px-2 py-0.5 rounded text-xs ml-2 shrink-0" x-text="airport.code"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <!-- Swap Icon -->
                                <div class="absolute -right-4 top-1/2 -translate-y-1/2 z-20 hidden lg:flex items-center justify-center w-8 h-8 bg-white rounded-full shadow-md text-[#1882FF] cursor-pointer hover:bg-blue-50 border border-blue-100 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8M16 7l-3-3m3 3l-3 3M16 17H8m0 0l3 3m-3-3l3-3"></path></svg>
                                </div>
                            </div>

                            <!-- To -->
                            <div class="relative border border-gray-200 rounded-xl bg-white p-3 hover:border-[#1882FF] transition group lg:ml-2" x-data="airportSearch('DXB')" @click="$refs.inputTo.focus()">
                                <label class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wide mb-1">To</label>
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-[#1882FF] shrink-0 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                    <div class="flex-1 min-w-0">
                                        <input type="hidden" name="destination" x-model="selectedCode">
                                        <input x-ref="inputTo" type="text" x-model="search" @focus="clearSelection()" @input.debounce.300ms="filter" @click.away="open = false" placeholder="City or airport" class="w-full border-none p-0 focus:ring-0 font-bold text-sm text-[#1a2b49] bg-transparent" required autocomplete="off">
                                        <div class="text-[11px] text-gray-400 truncate mt-0.5" x-text="selectedCode ? '(' + selectedCode + ') ' + selectedDisplay : 'Select destination airport'"></div>

                                        <!-- Dropdown -->
                                        <div x-show="open" style="display:none;" class="absolute z-50 left-0 top-full min-w-[320px] mt-1 bg-white rounded-xl shadow-2xl border border-gray-100 max-h-72 overflow-y-auto">
                                            <template x-if="loading"><div class="px-4 py-3 text-sm text-gray-500">Searching...</div></template>
                                            <template x-if="!loading && search.length >= 2 && filtered.length === 0"><div class="px-4 py-3 text-sm text-gray-500">No airports found.</div></template>
                                            <template x-for="airport in filtered" :key="airport.code">
                                                <div @click.stop="select(airport)" class="px-4 py-3 hover:bg-blue-50 cursor-pointer flex items-center justify-between border-b border-gray-50 last:border-0 transition">
                                                    <div class="flex flex-col truncate">
                                                        <span class="font-bold text-[#1a2b49] text-sm" x-text="airport.city || airport.name"></span>
                                                        <span class="text-gray-400 text-[10px]" x-text="airport.name"></span>
                                                    </div>
                                                    <span class="font-bold text-[#1882FF] bg-blue-50 px-2 py-0.5 rounded text-xs ml-2 shrink-0" x-text="airport.code"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Row 2: Dates + Class/Pax + Search -->
                        <div class="flex flex-col sm:flex-row gap-2 relative z-[9]">

                            <!-- Departure Date -->
                            <div class="flex-1 border border-gray-200 rounded-xl bg-white p-3 hover:border-[#1882FF] transition group cursor-text" @click="$refs.inputDep.showPicker ? $refs.inputDep.showPicker() : $refs.inputDep.focus()">
                                <label class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wide mb-1">Journey Date</label>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-[#1882FF] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <input x-ref="inputDep" type="date" name="date" class="w-full border-none p-0 focus:ring-0 font-bold text-sm text-[#1a2b49] bg-transparent leading-none" required value="{{ \Carbon\Carbon::tomorrow()->toDateString() }}">
                                </div>
                            </div>

                            <!-- Return Date -->
                            <div class="flex-1 border border-gray-200 rounded-xl p-3 transition group cursor-pointer" :class="tripType === 'one_way' ? 'bg-gray-50 border-gray-100' : 'bg-white hover:border-[#1882FF]'" @click="if(tripType === 'one_way') tripType = 'round_way'; setTimeout(() => { if($refs.inputRet && $refs.inputRet.showPicker) $refs.inputRet.showPicker(); else if($refs.inputRet) $refs.inputRet.focus(); }, 50)">
                                <label class="block text-[10px] font-semibold uppercase tracking-wide mb-1" :class="tripType === 'one_way' ? 'text-gray-300' : 'text-gray-400'">Return Date</label>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 shrink-0 transition" :class="tripType === 'one_way' ? 'text-gray-300' : 'text-gray-400 group-hover:text-[#1882FF]'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <template x-if="tripType === 'one_way'">
                                        <span class="font-bold text-sm text-gray-300">Add Return</span>
                                    </template>
                                    <template x-if="tripType !== 'one_way'">
                                        <input x-ref="inputRet" type="date" name="return_date" class="w-full border-none p-0 focus:ring-0 font-bold text-sm text-[#1a2b49] bg-transparent leading-none" :required="tripType === 'round_way'" :value="'{{ \Carbon\Carbon::tomorrow()->addDays(2)->toDateString() }}'">
                                    </template>
                                </div>
                            </div>

                            <!-- Class & Travelers -->
                            <div class="sm:w-52 border border-gray-200 rounded-xl bg-white p-3 hover:border-[#1882FF] transition group">
                                <label class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wide mb-1">Class & Travelers</label>
                                <div class="flex items-center gap-2">
                                    <select name="class" class="flex-1 min-w-[90px] border-none py-0 pl-0 pr-6 focus:ring-0 text-sm font-bold text-[#1a2b49] bg-transparent cursor-pointer bg-[right_0_center]">
                                        <option value="economy">Economy</option>
                                        <option value="business">Business</option>
                                        <option value="first">First</option>
                                    </select>
                                    <span class="text-gray-300">|</span>
                                    <select name="passengers" class="w-20 border-none py-0 pl-0 pr-6 focus:ring-0 text-sm font-bold text-[#1a2b49] bg-transparent cursor-pointer bg-[right_0_center]">
                                        <option value="1">1 Pax</option>
                                        <option value="2">2 Pax</option>
                                        <option value="3">3 Pax</option>
                                        <option value="4">4 Pax</option>
                                        <option value="5">5 Pax</option>
                                        <option value="6">6 Pax</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Search Button -->
                            <div class="sm:w-36 flex">
                                <button type="submit" class="w-full bg-[#1882FF] hover:bg-[#1265CC] text-white font-bold text-sm tracking-wide rounded-xl transition shadow-md flex items-center justify-center gap-2 py-3 px-4">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    SEARCH
                                </button>
                            </div>
                        </div>
                    </form>
                <!-- B2B and Hotel Stubs -->
                <div x-show="tab !== 'flights'" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="text-center py-12 text-gray-500 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                        <svg class="h-10 w-10 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                        <h3 class="text-lg font-bold text-gray-700">Coming Soon</h3>
                        <p class="text-xs mt-1">This module is currently under development.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Trawex API Service Banners -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 pt-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Banner 1: Availability Search -->
            <div class="rounded-2xl overflow-hidden shadow-sm relative group cursor-pointer aspect-[2.5/1]">
                <div class="absolute inset-0 bg-gradient-to-r from-[#1A365D] to-[#2B6CB0]"></div>
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 mix-blend-overlay"></div>
                <div class="relative p-6 h-full flex flex-col justify-between">
                    <div>
                        <div class="text-white/80 text-[10px] font-bold tracking-widest uppercase mb-1">Trawex · Step 01</div>
                        <h3 class="text-white font-bold text-lg md:text-xl leading-tight">AVAILABILITY<br>SEARCH</h3>
                        <p class="text-white/70 text-[10px] mt-1">aeroVE5/availability endpoint</p>
                    </div>
                    <div class="mt-auto">
                        <span class="inline-block bg-[#FFB700] text-[#1a2b49] text-[10px] font-black px-3 py-1 rounded">ACTIVE</span>
                    </div>
                </div>
                <!-- Plane Graphic -->
                <div class="absolute right-[-10%] bottom-0 w-2/3 h-full opacity-60 mix-blend-screen transition-transform duration-500 group-hover:translate-x-2 group-hover:-translate-y-2">
                    <svg viewBox="0 0 24 24" fill="none" stroke="white" class="w-full h-full object-contain"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.8" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </div>
            </div>

            <!-- Banner 2: Fare Revalidation -->
            <div class="rounded-2xl overflow-hidden shadow-sm relative group cursor-pointer aspect-[2.5/1]">
                <div class="absolute inset-0 bg-gradient-to-r from-[#065F46] to-[#059669]"></div>
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 mix-blend-overlay"></div>
                <div class="relative p-6 h-full flex flex-col justify-between">
                    <div>
                        <div class="text-white/80 text-[10px] font-bold tracking-widest uppercase mb-1">Trawex · Step 02</div>
                        <h3 class="text-white font-bold text-lg md:text-xl leading-tight">FARE<br>REVALIDATION</h3>
                        <p class="text-white/70 text-[10px] mt-1">aeroVE5/revalidate endpoint</p>
                    </div>
                    <div class="mt-auto">
                        <span class="inline-block bg-white text-[#065F46] text-[10px] font-black px-3 py-1 rounded">LIVE</span>
                    </div>
                </div>
                <!-- Check icon graphic -->
                <div class="absolute right-4 bottom-4 w-20 h-20 opacity-20 mix-blend-screen transition-transform duration-500 group-hover:scale-110">
                    <svg viewBox="0 0 24 24" fill="none" stroke="white" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <!-- Banner 3: Flight Booking -->
            <div class="rounded-2xl overflow-hidden shadow-sm relative group cursor-pointer aspect-[2.5/1]">
                <div class="absolute inset-0 bg-gradient-to-r from-[#581C87] to-[#7C3AED]"></div>
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/diagmonds-light.png')] opacity-20 mix-blend-overlay"></div>
                <div class="relative p-6 h-full flex flex-col justify-between">
                    <div>
                        <div class="text-white/80 text-[10px] font-bold tracking-widest uppercase mb-1">Trawex · Step 03</div>
                        <h3 class="text-white font-bold text-lg md:text-xl leading-tight">FLIGHT<br>BOOKING</h3>
                        <p class="text-white/70 text-[10px] mt-1">aeroVE5/booking endpoint</p>
                    </div>
                    <div class="mt-auto flex justify-between items-center">
                        <span class="inline-block bg-[#FFB700] text-[#1a2b49] text-[10px] font-black px-3 py-1 rounded">CONNECTED</span>
                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </div>
                <!-- Ticket graphic -->
                <div class="absolute right-4 bottom-4 w-20 h-20 opacity-20 mix-blend-screen transition-transform duration-500 group-hover:scale-110">
                    <svg viewBox="0 0 24 24" fill="none" stroke="white" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="pb-24 bg-gray-50 relative overflow-hidden">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl font-poppins font-bold text-[#1a2b49] mb-2">Why Choose Us</h2>
                <p class="text-sm text-gray-500">Powered exclusively by the Trawex API â€” global content, real-time pricing, and seamless booking.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Feature 1 -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition duration-300 group text-center">
                    <div class="w-12 h-12 mx-auto text-[#1882FF] mb-4 transform group-hover:scale-110 transition duration-300">
                        <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 104 0 2 2 0 012-2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-base font-bold mb-2 text-[#1a2b49]">Global Flight Content</h3>
                    <p class="text-gray-500 text-xs leading-relaxed">Access worldwide airline inventory through Trawex's GDS-connected availability search.</p>
                </div>
                <!-- Feature 2 -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition duration-300 group text-center">
                    <div class="w-12 h-12 mx-auto text-[#1882FF] mb-4 transform group-hover:scale-110 transition duration-300">
                        <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-base font-bold mb-2 text-[#1a2b49]">Real-Time Revalidation</h3>
                    <p class="text-gray-500 text-xs leading-relaxed">Trawex revalidates fares before payment, ensuring price accuracy and reducing failed bookings.</p>
                </div>
                <!-- Feature 3 -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition duration-300 group text-center">
                    <div class="w-12 h-12 mx-auto text-[#1882FF] mb-4 transform group-hover:scale-110 transition duration-300">
                        <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                    </div>
                    <h3 class="text-base font-bold mb-2 text-[#1a2b49]">Instant E-Ticketing</h3>
                    <p class="text-gray-500 text-xs leading-relaxed">Book and receive PNR/UniqueID confirmation via Trawex's booking endpoint instantly.</p>
                </div>
                <!-- Feature 4 -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition duration-300 group text-center">
                    <div class="w-12 h-12 mx-auto text-[#1882FF] mb-4 transform group-hover:scale-110 transition duration-300">
                        <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-base font-bold mb-2 text-[#1a2b49]">Sandbox + Production</h3>
                    <p class="text-gray-500 text-xs leading-relaxed">Trawex supports both sandbox testing and live production environments for safer deployments.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-[#2B2B68] text-white py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex flex-col items-center md:items-start gap-1">
                <div class="flex items-center gap-2">
                    <div class="font-poppins font-extrabold text-2xl tracking-tight text-white">GHURI<span class="text-[#FFB700]">.</span></div>
                </div>
                <p class="text-xs text-white/60 max-w-xs text-center md:text-left mt-2 leading-relaxed">
                    Smart bookings, competitive deals, and reliable support - all in one platform.
                </p>
                <p class="text-[10px] text-white/40 mt-4">
                    &copy; {{ date('Y') }} GHURI OTA. All rights reserved.
                </p>
            </div>
            
            <div class="flex flex-wrap justify-center md:justify-end items-center gap-8">
                <div class="flex flex-col items-center gap-2">
                    <span class="text-[9px] text-white/50 uppercase tracking-widest font-bold">Verified By</span>
                    <div class="flex items-center gap-2 bg-white/10 px-3 py-2 rounded-full border border-white/5">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        <span class="font-bold text-xs">DigiCert</span>
                    </div>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <span class="text-[9px] text-white/50 uppercase tracking-widest font-bold">Authorized By</span>
                    <div class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-full border border-white/5">
                        <span class="font-black text-xs italic tracking-wider">IATA</span>
                    </div>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <span class="text-[9px] text-white/50 uppercase tracking-widest font-bold">Member of</span>
                    <div class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-full border border-white/5">
                        <span class="font-black text-xs tracking-wider">BASIS</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('airportSearch', (initialCode) => ({
                open: false,
                search: '',
                selectedCode: initialCode,
                selectedDisplay: '',
                filtered: [],
                loading: false,

                clearSelection() {
                    this.open = true;
                    this.search = '';
                    this.selectedCode = '';
                    this.selectedDisplay = '';
                    this.filtered = [];
                    this.loading = false;
                },
                
                init() {
                    if (initialCode) {
                        fetch(`/ajax/airports/search?q=${initialCode}`)
                            .then(res => res.json())
                            .then(data => {
                                if(data.length > 0) {
                                    this.search = data[0].display_name;
                                    this.selectedDisplay = data[0].display_name;
                                    this.filtered = data;
                                }
                            });
                    }
                },
                
                filter() {
                    this.open = true;
                    if (this.search.length < 2) {
                        this.filtered = [];
                        return;
                    }
                    this.loading = true;
                    fetch(`/ajax/airports/search?q=${this.search}`)
                        .then(res => res.json())
                        .then(data => {
                            this.filtered = data;
                            this.loading = false;
                        });
                },
                
                select(airport) {
                    this.selectedCode = airport.code;
                    this.search = airport.display_name;
                    this.selectedDisplay = airport.display_name;
                    this.open = false;
                }
            }));
        });
    </script>
</body>
</html>

