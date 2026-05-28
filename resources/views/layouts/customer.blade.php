<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Traveler Dashboard' }} — {{ config('app.name', 'GhuriTravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body antialiased bg-[#F8F9FA] text-brand-text">
    
    {{-- Top Navigation --}}
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-brand-primary rounded-lg flex items-center justify-center">
                            <i class="fas fa-plane text-white text-sm"></i>
                        </div>
                        <span class="font-heading font-bold text-xl text-brand-black tracking-tight">GhuriTravel</span>
                    </a>
                    
                    {{-- Desktop Menu --}}
                    <div class="hidden sm:ml-10 sm:flex sm:space-x-8">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-brand-primary text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium transition-colors">
                            Dashboard
                        </a>
                        <a href="{{ route('my-bookings.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('my-bookings.*') ? 'border-brand-primary text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium transition-colors">
                            My Trips
                        </a>
                        <a href="/" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 text-sm font-medium transition-colors">
                            Offers
                        </a>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    {{-- Wallet / Points --}}
                    <div class="hidden md:flex items-center gap-2 bg-gray-50 px-3 py-1.5 rounded-full border border-gray-100">
                        <i class="fas fa-coins text-[#E2B75A]"></i>
                        <span class="text-sm font-bold text-gray-700">1,250 Pts</span>
                    </div>

                    {{-- Profile Dropdown (Simplified for layout) --}}
                    <div class="flex items-center gap-3 pl-4 border-l border-gray-100">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-brand-black leading-none">{{ Auth::user()->name ?? 'Traveler' }}</p>
                            <p class="text-[11px] text-gray-500 mt-1 leading-none">Silver Member</p>
                        </div>
                        <div class="w-9 h-9 rounded-full bg-brand-primary/10 text-brand-primary flex items-center justify-center font-bold">
                            {{ substr(Auth::user()->name ?? 'T', 0, 1) }}
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="ml-2">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-brand-primary transition-colors">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Layout Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-3 animate-fade-in">
                <i class="fas fa-check-circle text-lg"></i>
                <p class="font-medium text-sm">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Slot Content --}}
        <main>
            {{ $slot }}
        </main>
    </div>

    @stack('scripts')
</body>
</html>
