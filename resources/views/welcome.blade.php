<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GHURI - OTA Platform</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="preload" as="image" href="{{ asset('hero-pic-optimized.webp') }}" media="(min-width: 768px)">
    <link rel="preload" as="image" href="{{ asset('hero-pic-mobile.webp') }}" media="(max-width: 767px)">
    <style>
        .search-overlap {
            margin-top: -11rem;
        }

        @media (min-width: 768px) {
            .search-overlap {
                margin-top: -11rem;
            }
        }

        .neon-search-card {
            border: 2px solid transparent;
            background-image:
                linear-gradient(#fff, #fff),
                linear-gradient(120deg, #1882ff, #39f4ff, #ff4fd8, #1882ff, #39f4ff);
            background-origin: border-box;
            background-clip: padding-box, border-box;
            background-size: 100% 100%, 300% 300%;
            animation: neon-border-flow 18s cubic-bezier(0.42, 0, 0.2, 1) infinite;
        }

        .neon-search-card::before {
            content: "";
            position: absolute;
            inset: -3px;
            border-radius: inherit;
            background: linear-gradient(120deg, rgba(24, 130, 255, 0.45), rgba(57, 244, 255, 0.35), rgba(255, 79, 216, 0.4), rgba(24, 130, 255, 0.45));
            background-size: 300% 300%;
            filter: blur(22px);
            opacity: 0.28;
            z-index: -1;
            animation: neon-border-flow 18s cubic-bezier(0.42, 0, 0.2, 1) infinite;
        }

        @keyframes neon-border-flow {
            0% {
                background-position: 0% 50%;
            }

            100% {
                background-position: 300% 50%;
            }
        }

        .neon-navbar {
            position: relative;
            border-bottom: 1px solid rgba(24, 130, 255, 0.45) !important;
            box-shadow:
                0 1px 0 rgba(24, 130, 255, 0.35),
                0 10px 26px rgba(24, 130, 255, 0.12);
            overflow: visible;
        }

        .neon-navbar::before {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: -2px;
            height: 10px;
            background: linear-gradient(90deg, rgba(24, 130, 255, 0.48), rgba(57, 244, 255, 0.6), rgba(255, 79, 216, 0.42), rgba(24, 130, 255, 0.48));
            filter: blur(8px);
            opacity: 0.9;
            pointer-events: none;
            z-index: 0;
        }

        .neon-navbar::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: -1px;
            height: 2px;
            background: linear-gradient(90deg, #1882ff, #39f4ff, #ff4fd8, #1882ff);
            background-size: 240% 100%;
            box-shadow: 0 0 12px rgba(24, 130, 255, 0.8), 0 0 24px rgba(57, 244, 255, 0.45);
            animation: neon-navbar-flow-strong 14s cubic-bezier(0.42, 0, 0.2, 1) infinite;
            pointer-events: none;
            z-index: 1;
        }

        .neon-logo {
            position: relative;
            display: inline-block;
            line-height: 1;
            isolation: isolate;
            text-shadow: none !important;
            filter: none !important;
            animation: none !important;
        }

        .neon-logo::before {
            content: attr(data-text);
            position: absolute;
            inset: 0;
            z-index: -1;
            transform: scale(1.055);
            transform-origin: center;
            color: transparent;
            -webkit-text-fill-color: transparent;
            background: linear-gradient(90deg, #1882ff, #39f4ff, #ff4fd8, #1882ff);
            background-size: 240% 100%;
            -webkit-background-clip: text;
            background-clip: text;
            animation: logo-border-flow 11s cubic-bezier(0.42, 0, 0.2, 1) infinite;
        }

        .neon-logo .logo-dot {
            position: relative;
            z-index: 2;
        }

        @keyframes neon-navbar-flow-strong {
            0% {
                background-position: 0% 50%;
            }

            100% {
                background-position: 240% 50%;
            }
        }

        @keyframes logo-border-flow {
            0% {
                background-position: 0% 50%;
            }

            100% {
                background-position: 240% 50%;
            }
        }
    </style>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-dark selection:bg-[#1882FF] selection:text-white">
    <!-- Navigation -->
    <nav class="neon-navbar sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-gray-100 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative flex justify-between h-20 items-center">
            <div class="flex items-center gap-2 cursor-pointer">
                <div class="neon-logo font-poppins font-extrabold text-3xl tracking-tighter text-[#1a2b49]" data-text="GHURI">
                    GHURI<span class="logo-dot text-[#1882FF]">.</span>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                @if (Route::has('login'))
                    @auth
                        @php
                            $user = auth()->user();
                            $isInternalUser = $user && $user->isInternalUser();
                        @endphp
                        @if ($isInternalUser)
                            <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-gray-600 hover:text-[#1882FF] px-4 py-2 transition border border-gray-200 rounded-md">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-sm font-semibold text-gray-600 hover:text-[#1882FF] px-4 py-2 transition border border-gray-200 rounded-md">Logout</button>
                            </form>
                        @else
                            <div x-data="{ open:false }" class="relative">
                                <button @click="open = !open" type="button" class="flex items-center gap-2 rounded-full border border-gray-200 bg-white px-2 py-1.5 shadow-sm hover:border-[#1882FF]/40 transition">
                                    <span class="w-8 h-8 rounded-full bg-[#EAF3FF] text-[#1a2b49] font-bold text-sm flex items-center justify-center">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                    <svg class="w-4 h-4 text-[#1882FF] transition" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div
                                    x-show="open"
                                    @click.away="open = false"
                                    x-transition
                                    style="display:none;"
                                    class="absolute right-0 mt-2 w-64 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden"
                                >
                                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
                                        <p class="text-sm font-bold text-[#1a2b49] truncate">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                                    </div>

                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">Booking History</a>
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">Account</a>
                                    <button type="button" class="w-full text-left px-4 py-2.5 text-sm text-gray-400 cursor-default">My Wishlist</button>
                                    <button type="button" class="w-full text-left px-4 py-2.5 text-sm text-gray-400 cursor-default">Settings</button>

                                    <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">Logout</button>
                                    </form>
                                </div>
                            </div>
                        @endif
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
    <div data-hero-3d class="hero-3d relative isolate overflow-hidden h-[380px] md:h-[500px] w-full bg-[#F7C6D4]">
        <picture class="absolute inset-0 w-full h-full hero-3d-bg">
            <source media="(min-width: 768px)" srcset="{{ asset('hero-pic-optimized.webp') }}" type="image/webp">
            <source srcset="{{ asset('hero-pic-mobile.webp') }}" type="image/webp">
            <img
                src="{{ asset('hero-pic.webp') }}?v={{ time() }}"
                alt="Travel hero background"
                class="w-full h-full object-cover object-top md:object-bottom"
                loading="eager"
                fetchpriority="high"
                decoding="async"
            >
        </picture>
        <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(255,255,255,0.06)_0%,rgba(255,255,255,0.28)_100%)] hero-3d-overlay"></div>
        <div class="hero-3d-glow" aria-hidden="true"></div>
        <canvas class="hero-3d-canvas" aria-hidden="true"></canvas>
    </div>

    <!-- Search Component Container -->
    <div class="search-overlap max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-40 pb-8">
        <div class="neon-search-card bg-white rounded-[28px] shadow-[0_24px_70px_rgba(15,23,42,0.14)] relative isolate p-3 md:p-5" x-data="{ tab: 'flights', tripType: 'one_way' }">
            
            <!-- Tabs inside the box like image -->
            <div class="flex justify-center -mt-8 md:-mt-9 mb-4">
                <div class="flex bg-white rounded-full shadow-lg border border-gray-100 overflow-hidden text-sm font-bold px-1 py-1">
                    <button @click="tab = 'flights'" :class="tab === 'flights' ? 'text-[#1882FF] bg-blue-50/70' : 'text-gray-500 hover:bg-gray-50'" class="px-6 py-3 rounded-full transition flex items-center gap-2 border-b-2" :class="tab === 'flights' ? 'border-[#1882FF]' : 'border-transparent'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 104 0 2 2 0 012-2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Flight
                    </button>
                    <button @click="tab = 'hotels'" :class="tab === 'hotels' ? 'text-[#1882FF] bg-blue-50/70' : 'text-gray-500 hover:bg-gray-50'" class="px-6 py-3 rounded-full transition flex items-center gap-2 border-b-2" :class="tab === 'hotels' ? 'border-[#1882FF]' : 'border-transparent'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg> Hotel
                    </button>
                </div>
            </div>

            <!-- Tab Contents -->
            <div class="p-4 md:p-6 pt-0">
                <!-- Flights Search Form -->
                <div x-show="tab === 'flights'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    @if($errors->any())
                        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <ul class="text-sm text-red-700 font-medium list-disc ml-4">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
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

                        <!-- Row 1: From & To & Dates & Search -->
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-2 relative z-10 mb-2">
                            
                            <!-- From/To Group (takes up more space) -->
                            <div class="col-span-1 lg:col-span-5 grid grid-cols-1 sm:grid-cols-2 gap-2 relative">
                                <!-- From -->
                                <div class="relative border border-gray-200 rounded-xl bg-white p-3 hover:border-[#1882FF] transition group" x-data="airportSearch('DAC')" @click="$refs.inputFrom.focus()">
                                    <label class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wide mb-1">From</label>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-[#1882FF] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                        <div class="flex-1 min-w-0">
                                            <input type="hidden" name="origin" x-model="selectedCode">
                                            <input x-ref="inputFrom" type="text" x-model="search" @focus="clearSelection()" @input.debounce.300ms="filter" @click.away="open = false" placeholder="City or airport" class="w-full border-none p-0 focus:ring-0 font-bold text-[11px] xl:text-[12px] text-[#1a2b49] bg-transparent truncate" required autocomplete="off">
                                            <div class="text-[9px] text-gray-400 truncate mt-0.5" x-text="selectedCode ? '(' + selectedCode + ') ' + selectedDisplay : 'Select departure'"></div>

                                            <!-- Dropdown -->
                                            <div x-show="open" style="display:none;" class="absolute z-50 left-0 top-full min-w-[300px] mt-1 bg-white rounded-xl shadow-2xl border border-gray-100 max-h-72 overflow-y-auto">
                                                <template x-if="loading"><div class="px-4 py-3 text-sm text-gray-500">Searching...</div></template>
                                                <template x-if="!loading && search.length >= 2 && filtered.length === 0"><div class="px-4 py-3 text-sm text-gray-500">No airports found.</div></template>
                                                <template x-for="airport in filtered" :key="airport.code">
                                                    <div @click.stop="select(airport)" class="px-4 py-3 hover:bg-blue-50 cursor-pointer flex items-center justify-between border-b border-gray-50 last:border-0 transition">
                                                        <div class="flex flex-col truncate min-w-0">
                                                            <span class="font-bold text-[#1a2b49] text-sm truncate" x-text="airport.display_name"></span>
                                                            <span class="text-gray-400 text-[10px] truncate" x-text="airport.subtitle || airport.name"></span>
                                                        </div>
                                                        <span class="font-bold text-[#1882FF] bg-blue-50 px-2 py-0.5 rounded text-xs ml-2 shrink-0" x-text="airport.code"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Swap Icon -->
                                    <div class="absolute -right-3 top-1/2 -translate-y-1/2 z-20 hidden sm:flex items-center justify-center w-6 h-6 bg-white rounded-full shadow-md text-[#1882FF] cursor-pointer hover:bg-blue-50 border border-blue-100 transition">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8M16 7l-3-3m3 3l-3 3M16 17H8m0 0l3 3m-3-3l3-3"></path></svg>
                                    </div>
                                </div>

                                <!-- To -->
                                <div class="relative border border-gray-200 rounded-xl bg-white p-3 hover:border-[#1882FF] transition group sm:ml-1" x-data="airportSearch('DXB')" @click="$refs.inputTo.focus()">
                                    <label class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wide mb-1">To</label>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-[#1882FF] shrink-0 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                        <div class="flex-1 min-w-0">
                                            <input type="hidden" name="destination" x-model="selectedCode">
                                            <input x-ref="inputTo" type="text" x-model="search" @focus="clearSelection()" @input.debounce.300ms="filter" @click.away="open = false" placeholder="City or airport" class="w-full border-none p-0 focus:ring-0 font-bold text-[11px] xl:text-[12px] text-[#1a2b49] bg-transparent truncate" required autocomplete="off">
                                            <div class="text-[9px] text-gray-400 truncate mt-0.5" x-text="selectedCode ? '(' + selectedCode + ') ' + selectedDisplay : 'Select destination'"></div>

                                            <!-- Dropdown -->
                                            <div x-show="open" style="display:none;" class="absolute z-50 left-0 top-full min-w-[300px] mt-1 bg-white rounded-xl shadow-2xl border border-gray-100 max-h-72 overflow-y-auto">
                                                <template x-if="loading"><div class="px-4 py-3 text-sm text-gray-500">Searching...</div></template>
                                                <template x-if="!loading && search.length >= 2 && filtered.length === 0"><div class="px-4 py-3 text-sm text-gray-500">No airports found.</div></template>
                                                <template x-for="airport in filtered" :key="airport.code">
                                                    <div @click.stop="select(airport)" class="px-4 py-3 hover:bg-blue-50 cursor-pointer flex items-center justify-between border-b border-gray-50 last:border-0 transition">
                                                        <div class="flex flex-col truncate min-w-0">
                                                            <span class="font-bold text-[#1a2b49] text-sm truncate" x-text="airport.display_name"></span>
                                                            <span class="text-gray-400 text-[10px] truncate" x-text="airport.subtitle || airport.name"></span>
                                                        </div>
                                                        <span class="font-bold text-[#1882FF] bg-blue-50 px-2 py-0.5 rounded text-xs ml-2 shrink-0" x-text="airport.code"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Dates Group -->
                            <div class="col-span-1 lg:col-span-3 grid grid-cols-2 gap-2 relative z-[9]">
                                <!-- Departure Date -->
                                <div class="col-span-1 border border-gray-200 rounded-xl bg-white p-2 hover:border-[#1882FF] transition group cursor-text" @click="$refs.inputDep.showPicker ? $refs.inputDep.showPicker() : $refs.inputDep.focus()">
                                    <label class="block text-[9px] font-semibold text-gray-400 uppercase tracking-wide mb-1">Journey Date</label>
                                    <div class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-gray-400 group-hover:text-[#1882FF] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <input x-ref="inputDep" type="date" name="date" class="w-full border-none p-0 focus:ring-0 font-bold text-[10px] xl:text-[11px] text-[#1a2b49] bg-transparent leading-none [&::-webkit-calendar-picker-indicator]:hidden" required value="{{ \Carbon\Carbon::tomorrow()->toDateString() }}">
                                    </div>
                                </div>

                                <!-- Return Date -->
                                <div class="col-span-1 border border-gray-200 rounded-xl p-2 transition group cursor-pointer flex flex-col justify-center" :class="tripType === 'one_way' ? 'bg-gray-50 border-gray-100' : 'bg-white hover:border-[#1882FF]'" @click="if(tripType === 'one_way') tripType = 'round_way'; setTimeout(() => { if($refs.inputRet && $refs.inputRet.showPicker) $refs.inputRet.showPicker(); else if($refs.inputRet) $refs.inputRet.focus(); }, 50)">
                                    <div class="flex items-center gap-1.5" x-show="tripType === 'one_way'">
                                        <svg class="w-4 h-4 text-[#1882FF] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        <span class="font-bold text-[10px] xl:text-[11px] text-[#1a2b49]">Add Return</span>
                                    </div>
                                    <div x-show="tripType !== 'one_way'" style="display:none;" class="w-full">
                                        <label class="block text-[9px] font-semibold text-gray-400 uppercase tracking-wide mb-1">Return Date</label>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-gray-400 group-hover:text-[#1882FF] shrink-0 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <input x-ref="inputRet" type="date" name="return_date" class="w-full border-none p-0 focus:ring-0 font-bold text-[10px] xl:text-[11px] text-[#1a2b49] bg-transparent leading-none [&::-webkit-calendar-picker-indicator]:hidden" :required="tripType === 'round_way'" :disabled="tripType === 'one_way'" :value="'{{ \Carbon\Carbon::tomorrow()->addDays(2)->toDateString() }}'">
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- Close Dates Group -->

                            <!-- Class & Travelers + Search Button -->
                            <div class="col-span-1 lg:col-span-4 flex gap-2 w-full">
                                <!-- Class & Travelers -->
                                <div class="flex-1 border border-gray-200 rounded-xl bg-white p-2 xl:p-3 hover:border-[#1882FF] transition group flex flex-col justify-center">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            <select name="class" class="flex-1 border-none p-0 focus:ring-0 text-[11px] xl:text-[12px] font-bold text-gray-500 bg-transparent cursor-pointer bg-none">
                                                <option value="economy">Economy</option>
                                                <option value="business">Business</option>
                                                <option value="first">First</option>
                                            </select>
                                        </div>
                                        <div class="h-px w-full bg-gray-100 my-0.5"></div>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                            <select name="passengers" class="flex-1 border-none p-0 focus:ring-0 text-[11px] xl:text-[12px] font-bold text-[#1a2b49] bg-transparent cursor-pointer bg-none">
                                                <option value="1">1 Traveler</option>
                                                <option value="2">2 Travelers</option>
                                                <option value="3">3 Travelers</option>
                                                <option value="4">4 Travelers</option>
                                                <option value="5">5 Travelers</option>
                                                <option value="6">6+ Travelers</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Search Button -->
                                <button type="submit" class="w-[90px] lg:w-[110px] bg-[#1a2b49] hover:bg-[#2a3b59] text-white font-bold text-[13px] tracking-wide rounded-xl transition shadow-md flex flex-col items-center justify-center gap-1 p-2">
                                    <span class="tracking-widest">SEARCH</span>
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Hotel Stubs -->
                <div x-show="tab !== 'flights'" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="text-center py-12 text-gray-500 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                        <svg class="h-10 w-10 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <h3 class="text-lg font-bold text-gray-700">Coming Soon</h3>
                        <p class="text-xs mt-1">Hotel booking facilities will be available soon.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- GHURI Service Banners -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 pt-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Promo 1 - Flight -->
            <div class="rounded-2xl overflow-hidden shadow-md relative group cursor-pointer h-56 md:h-72 bg-[#0f172a]">
                <img src="/flight-card.webp" alt="Airplane wing in the sky" loading="lazy" decoding="async" class="absolute inset-0 h-full w-full object-cover object-[70%_50%] transition-transform duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-r from-[#0f172a]/20 via-transparent to-transparent"></div>
                <div class="relative p-6 h-full flex flex-col justify-between">
                    <div>
                        <h3 class="text-white text-xl md:text-3xl font-extrabold mb-1 drop-shadow-lg">Fly to Your Dream Destinations</h3>
                        <p class="text-white/95 text-sm md:text-base font-bold drop-shadow-md">Explore hundreds of routes at the best prices.</p>
                    </div>
                    <div>
                        <a href="#" class="inline-block bg-white text-[#1a2b49] px-5 py-2.5 rounded-full font-semibold shadow-sm">Book Flights</a>
                    </div>
                </div>
            </div>

            <!-- Promo 2 - Hotel -->
            <div class="rounded-2xl overflow-hidden shadow-md relative group cursor-pointer h-56 md:h-72 bg-[#0f172a]">
                <img src="/hotel-card.webp" alt="Comfortable hotel room" loading="lazy" decoding="async" class="absolute inset-0 h-full w-full object-cover object-center transition-transform duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-r from-[#0f172a]/25 via-transparent to-transparent"></div>
                <div class="relative p-6 h-full flex flex-col justify-between">
                    <div>
                        <h3 class="text-white text-xl md:text-3xl font-extrabold mb-1 drop-shadow-lg">Comfortable Stays, <span class="bg-[#1882FF] text-white px-2 py-1 rounded">Unforgettable</span> Memories</h3>
                        <p class="text-white text-sm md:text-base font-bold drop-shadow-md">Find the perfect hotel for every trip.</p>
                    </div>
                    <div>
                        <a href="#" class="inline-block bg-white text-[#1a2b49] px-5 py-2.5 rounded-full font-semibold shadow-sm">Book Hotels</a>
                    </div>
                </div>
            </div>

            <!-- Promo 3 - Discount -->
            <div class="rounded-2xl overflow-hidden shadow-md relative group cursor-pointer h-56 md:h-72 bg-[#dbeafe]">
                <img src="/discount-card.webp" alt="Discount ticket illustration" loading="lazy" decoding="async" class="absolute inset-0 h-full w-full object-cover object-center transition-transform duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-r from-white/30 via-white/10 to-transparent"></div>
                <div class="relative p-6 h-full flex flex-col justify-between">
                    <div>
                        <h3 class="text-[#1a2b49] text-xl md:text-3xl font-extrabold mb-1 drop-shadow-2xl">Best Prices Every Time</h3>
                        <p class="text-[#1a2b49] text-sm md:text-base font-bold drop-shadow-lg">We bring you the best deals so you can travel more.</p>
                    </div>
                    <div>
                        <span class="inline-block bg-white text-[#1a2b49] px-5 py-2.5 rounded-full font-semibold shadow-sm">Learn More</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Airlines Showcase Section -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="bg-white rounded-[28px] shadow-[0_4px_30px_rgba(15,23,42,0.04)] border border-gray-50 p-8 md:p-12">
            <div class="text-center max-w-3xl mx-auto mb-10">
                <h2 class="text-2xl md:text-[28px] font-poppins font-bold text-[#1a2b49] mb-4 tracking-tight">Search Top Airlines</h2>
                <p class="text-sm text-gray-500 leading-relaxed font-medium">
                    GHURI's user-friendly platform, powered by GHURI technology, connects you to top airlines instantly. Enjoy a comfortable and hassle-free journey on any destination and get tickets of top airlines easily.
                </p>
            </div>
            
            @php
            $topAirlines = [
                ['name' => 'Biman Bangladesh Airlines', 'iata' => 'BG'],
                ['name' => 'US-Bangla Airlines', 'iata' => 'BS'],
                ['name' => 'NOVOAIR', 'iata' => 'VQ'],
                ['name' => 'Air Astra', 'iata' => '2A'],
                ['name' => 'Emirates', 'iata' => 'EK'],
                ['name' => 'Singapore Airlines', 'iata' => 'SQ'],
                ['name' => 'Malaysia Airlines', 'iata' => 'MH'],
                ['name' => 'Qatar Airways', 'iata' => 'QR'],
                ['name' => 'Saudia Airlines', 'iata' => 'SV'],
                ['name' => 'Air India', 'iata' => 'AI'],
                ['name' => 'Gulf Air', 'iata' => 'GF'],
                ['name' => 'Turkish Airlines', 'iata' => 'TK'],
                ['name' => 'Thai Airways International', 'iata' => 'TG'],
                ['name' => 'Cathay Pacific Airways', 'iata' => 'CX'],
                ['name' => 'China Southern Airlines', 'iata' => 'CZ'],
                ['name' => 'SriLankan Airlines', 'iata' => 'UL'],
                ['name' => 'AirAsia', 'iata' => 'AK'],
                ['name' => 'Batik Air', 'iata' => 'ID'],
                ['name' => 'IndiGo', 'iata' => '6E'],
                ['name' => 'Air Arabia', 'iata' => 'G9'],
            ];
            @endphp
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-8 gap-y-6">
                @foreach($topAirlines as $airline)
                <a href="javascript:void(0)" class="flex items-center justify-between py-2 px-1 hover:bg-gray-50/50 rounded-lg transition-colors group">
                    <div class="flex items-center gap-4">
                        <img src="https://images.kiwi.com/airlines/64/{{ $airline['iata'] }}.png" alt="{{ $airline['name'] }}" class="w-8 h-8 object-contain">
                        <span class="text-sm font-bold text-[#1a2b49] group-hover:text-[#1882FF] transition-colors">{{ $airline['name'] }}</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-gray-300 group-hover:text-[#1882FF] transition-colors shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="pb-24 bg-gray-50 relative overflow-hidden">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl font-poppins font-bold text-[#1a2b49] mb-2">Why Choose Us</h2>
                <p class="text-sm text-gray-500">Powered exclusively by GHURI technology - global content, real-time pricing, and seamless booking.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Feature 1 -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition duration-300 group text-center">
                    <div class="w-12 h-12 mx-auto text-[#1882FF] mb-4 transform group-hover:scale-110 transition duration-300">
                        <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 104 0 2 2 0 012-2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-base font-bold mb-2 text-[#1a2b49]">Global Flight Content</h3>
                    <p class="text-gray-500 text-xs leading-relaxed">Access worldwide airline inventory through GHURI's GDS-connected availability search.</p>
                </div>
                <!-- Feature 2 -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition duration-300 group text-center">
                    <div class="w-12 h-12 mx-auto text-[#1882FF] mb-4 transform group-hover:scale-110 transition duration-300">
                        <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-base font-bold mb-2 text-[#1a2b49]">Real-Time Revalidation</h3>
                    <p class="text-gray-500 text-xs leading-relaxed">GHURI revalidates fares before payment, ensuring price accuracy and reducing failed bookings.</p>
                </div>
                <!-- Feature 3 -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition duration-300 group text-center">
                    <div class="w-12 h-12 mx-auto text-[#1882FF] mb-4 transform group-hover:scale-110 transition duration-300">
                        <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                    </div>
                    <h3 class="text-base font-bold mb-2 text-[#1a2b49]">Instant E-Ticketing</h3>
                    <p class="text-gray-500 text-xs leading-relaxed">Book and receive PNR/UniqueID confirmation via GHURI's booking endpoint instantly.</p>
                </div>
                <!-- Feature 4 -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition duration-300 group text-center">
                    <div class="w-12 h-12 mx-auto text-[#1882FF] mb-4 transform group-hover:scale-110 transition duration-300">
                        <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-base font-bold mb-2 text-[#1a2b49]">Sandbox + Production</h3>
                    <p class="text-gray-500 text-xs leading-relaxed">GHURI supports both sandbox testing and live production environments for safer deployments.</p>
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
                        fetch(`/ajax/airports/search?q=${encodeURIComponent(initialCode)}`)
                            .then(res => res.json())
                            .then(data => {
                                if(data.length > 0) {
                                    this.search = data[0].display_name;
                                    this.selectedDisplay = data[0].display_name;
                                    this.selectedCode = data[0].code;
                                    this.filtered = [];
                                }
                            })
                            .catch(() => {
                                this.search = initialCode;
                            });
                    }
                },

                filter() {
                    this.open = true;
                    if (this.search.length < 1) {
                        this.filtered = [];
                        return;
                    }
                    this.loading = true;
                    fetch(`/ajax/airports/search?q=${encodeURIComponent(this.search)}`)
                        .then(res => res.json())
                        .then(data => {
                            this.filtered = data;
                            this.loading = false;
                        })
                        .catch(() => {
                            this.filtered = [];
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
