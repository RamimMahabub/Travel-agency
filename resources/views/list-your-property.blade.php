<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>List Your Property on GHURI – Reach Thousands of Travelers</title>
    <meta name="description" content="Partner with GHURI and list your hotel, resort, or homestay. Reach thousands of travelers across Bangladesh and beyond. Get your first booking fast.">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-outfit { font-family: 'Outfit', sans-serif; }

        /* Animated gradient hero */
        .lyp-hero {
            background: linear-gradient(135deg, #1a0305 0%, #6b0611 35%, #d00e15 65%, #ff4b55 100%);
            position: relative;
            overflow: hidden;
        }
        .lyp-hero::before {
            content: '';
            position: absolute;
            width: 700px; height: 700px;
            background: radial-gradient(circle, rgba(255,75,85,0.25) 0%, transparent 70%);
            top: -200px; right: -150px;
            border-radius: 50%;
            animation: pulse-glow 6s ease-in-out infinite;
        }
        .lyp-hero::after {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(255,255,255,0.07) 0%, transparent 70%);
            bottom: -100px; left: -100px;
            border-radius: 50%;
            animation: pulse-glow 8s ease-in-out infinite reverse;
        }
        @keyframes pulse-glow {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.15); opacity: 0.7; }
        }

        /* Floating city illustration */
        .city-illustration {
            position: relative;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            gap: 8px;
        }
        .building {
            border-radius: 6px 6px 0 0;
            animation: float-building 4s ease-in-out infinite;
        }
        @keyframes float-building {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        /* Step cards */
        .step-card {
            background: white;
            border: 1px solid #f1f5f9;
            border-radius: 20px;
            padding: 28px 24px;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .step-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #fff5f5 0%, #fff 100%);
            opacity: 0;
            transition: opacity 0.35s;
        }
        .step-card:hover {
            border-color: #d00e15;
            box-shadow: 0 20px 60px rgba(208,14,21,0.12);
            transform: translateY(-6px);
        }
        .step-card:hover::before { opacity: 1; }
        .step-number {
            width: 44px; height: 44px;
            background: #fff5f5;
            border: 2px solid #d00e15;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800;
            color: #d00e15;
            font-size: 16px;
            font-family: 'Outfit', sans-serif;
            flex-shrink: 0;
            position: relative;
            z-index: 1;
            transition: all 0.35s;
        }
        .step-card:hover .step-number {
            background: #d00e15;
            color: white;
        }

        /* Benefit cards */
        .benefit-card {
            border-radius: 20px;
            padding: 32px 28px;
            border: 1px solid #f1f5f9;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .benefit-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 60px rgba(0,0,0,0.08);
        }

        /* Stats banner */
        .stat-item {
            text-align: center;
            padding: 24px 16px;
        }
        .stat-number {
            font-family: 'Outfit', sans-serif;
            font-size: 2.5rem;
            font-weight: 900;
            color: #d00e15;
            line-height: 1;
        }

        /* CTA button */
        .cta-btn-primary {
            background: white;
            color: #d00e15;
            font-weight: 700;
            padding: 16px 40px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 1rem;
            transition: all 0.3s;
            text-decoration: none;
            box-shadow: 0 4px 24px rgba(0,0,0,0.15);
        }
        .cta-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.25);
            background: #fff5f5;
        }
        .cta-btn-secondary {
            background: transparent;
            color: white;
            font-weight: 600;
            padding: 16px 36px;
            border-radius: 50px;
            border: 2px solid rgba(255,255,255,0.5);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 1rem;
            transition: all 0.3s;
            text-decoration: none;
        }
        .cta-btn-secondary:hover {
            border-color: white;
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
        }

        /* Navbar */
        .lyp-nav {
            background: rgba(26, 3, 5, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        /* FAQ */
        .faq-item { border-bottom: 1px solid #f1f5f9; }
        .faq-item:last-child { border-bottom: none; }
    </style>
</head>
<body class="bg-white text-[#19100F]">

    {{-- ============================================================
         NAVBAR
    ============================================================ --}}
    <nav class="lyp-nav fixed top-0 inset-x-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-18 py-4">
            {{-- Logo --}}
            <a href="{{ url('/') }}" class="font-outfit font-extrabold text-2xl tracking-tighter text-white">
                GHURI<span class="text-[#ff4b55]">.</span>
            </a>

            {{-- Nav actions --}}
            <div class="flex items-center gap-3">
                @auth
                    @if(auth()->user()->isPropertyOwner())
                        <a href="{{ route('property-owner.dashboard') }}"
                           class="text-sm font-semibold text-white/80 hover:text-white transition px-4 py-2 rounded-lg hover:bg-white/10">
                            My Dashboard
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-semibold text-white/60 hover:text-white transition">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="text-sm font-semibold text-white/80 hover:text-white px-4 py-2 rounded-lg transition hover:bg-white/10">
                        Sign in
                    </a>
                    <a href="{{ route('register') }}"
                       class="bg-[#d00e15] hover:bg-[#A90B16] text-white text-sm font-bold py-2.5 px-6 rounded-xl transition shadow-md">
                        List your property
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ============================================================
         HERO SECTION
    ============================================================ --}}
    <section class="lyp-hero min-h-[88vh] flex items-center pt-20 pb-16 px-4">
        <div class="max-w-6xl mx-auto w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                {{-- Left: Copy --}}
                <div class="relative z-10">
                    {{-- Pill badge --}}
                    <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-2 mb-6 backdrop-blur-sm">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                        <span class="text-white/90 text-xs font-semibold tracking-wide uppercase">Now Open for Partners</span>
                    </div>

                    <h1 class="font-outfit font-black text-4xl sm:text-5xl lg:text-6xl text-white leading-[1.1] mb-6">
                        List your<br>
                        <span class="text-transparent bg-clip-text" style="background: linear-gradient(90deg, #ffb3b7, #ffd6d8);">property</span><br>
                        on <span class="text-white">GHURI</span><span class="text-[#ff4b55]">.</span>
                    </h1>

                    <p class="text-white/75 text-lg leading-relaxed mb-4 max-w-md">
                        Join hundreds of hotels, resorts, and homestays across Bangladesh and beyond. Reach thousands of verified travelers every day.
                    </p>

                    <ul class="space-y-2 mb-8">
                        <li class="flex items-center gap-3 text-white/90 text-sm font-medium">
                            <svg class="w-5 h-5 text-green-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Get your first booking within days
                        </li>
                        <li class="flex items-center gap-3 text-white/90 text-sm font-medium">
                            <svg class="w-5 h-5 text-green-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Full control over pricing, availability & rules
                        </li>
                        <li class="flex items-center gap-3 text-white/90 text-sm font-medium">
                            <svg class="w-5 h-5 text-green-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Real-time analytics & booking dashboard
                        </li>
                        <li class="flex items-center gap-3 text-white/90 text-sm font-medium">
                            <svg class="w-5 h-5 text-green-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Zero upfront cost — commission based only
                        </li>
                    </ul>

                    <div class="flex flex-col sm:flex-row gap-4">
                        @auth
                            @if(auth()->user()->isPropertyOwner())
                                <a href="{{ route('property-owner.hotels.create') }}" class="cta-btn-primary">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Add Your Property
                                </a>
                                <a href="{{ route('property-owner.dashboard') }}" class="cta-btn-secondary">
                                    Go to Dashboard
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="cta-btn-primary">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    List your property
                                </a>
                                <a href="{{ route('login') }}" class="cta-btn-secondary">
                                    I already have an account
                                </a>
                            @endif
                        @else
                            <a href="{{ route('register') }}" class="cta-btn-primary">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                List your property
                            </a>
                            <a href="{{ route('login') }}" class="cta-btn-secondary">
                                I already have an account
                            </a>
                        @endauth
                    </div>
                </div>

                {{-- Right: Animated City Illustration --}}
                <div class="relative z-10 flex justify-center items-end lg:justify-end">
                    <div class="city-illustration" style="height: 320px; align-items: flex-end;">
                        {{-- Building 1 - small house --}}
                        <div class="building" style="width:60px;height:90px;background:linear-gradient(180deg,#ff9aa0,#f06070);animation-delay:0s;position:relative;">
                            <div style="position:absolute;top:-20px;left:0;right:0;height:20px;background:#ff6b7a;clip-path:polygon(50% 0%,100% 100%,0% 100%);"></div>
                            <div style="position:absolute;top:12px;left:10px;width:16px;height:18px;background:rgba(255,255,255,0.4);border-radius:2px;"></div>
                            <div style="position:absolute;top:12px;right:10px;width:16px;height:18px;background:rgba(255,255,255,0.4);border-radius:2px;"></div>
                            <div style="position:absolute;bottom:0;left:50%;transform:translateX(-50%);width:20px;height:30px;background:rgba(255,255,255,0.3);border-radius:2px 2px 0 0;"></div>
                        </div>
                        {{-- Building 2 - medium office --}}
                        <div class="building" style="width:80px;height:160px;background:linear-gradient(180deg,#e8b4b8,#c97b85);animation-delay:-1s;position:relative;">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;padding:10px 10px 0;">
                                <div style="height:14px;background:rgba(255,255,255,0.5);border-radius:2px;"></div>
                                <div style="height:14px;background:rgba(255,255,255,0.5);border-radius:2px;"></div>
                                <div style="height:14px;background:rgba(255,255,255,0.5);border-radius:2px;"></div>
                                <div style="height:14px;background:rgba(255,255,255,0.5);border-radius:2px;"></div>
                                <div style="height:14px;background:rgba(255,255,255,0.5);border-radius:2px;"></div>
                                <div style="height:14px;background:rgba(255,255,255,0.5);border-radius:2px;"></div>
                                <div style="height:14px;background:rgba(255,255,255,0.3);border-radius:2px;"></div>
                                <div style="height:14px;background:rgba(255,255,255,0.5);border-radius:2px;"></div>
                            </div>
                            <div style="position:absolute;bottom:0;left:50%;transform:translateX(-50%);width:28px;height:40px;background:rgba(255,255,255,0.25);border-radius:2px 2px 0 0;"></div>
                        </div>
                        {{-- Building 3 - tall hotel (main) --}}
                        <div class="building" style="width:100px;height:240px;background:linear-gradient(180deg,#d00e15,#8b0009);animation-delay:-0.5s;position:relative;border-radius:4px 4px 0 0;">
                            <div style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);width:8px;height:12px;background:#ff4b55;border-radius:2px 2px 0 0;"></div>
                            <div style="position:absolute;top:10px;left:50%;transform:translateX(-50%);font-family:'Outfit',sans-serif;font-weight:900;color:rgba(255,255,255,0.9);font-size:11px;letter-spacing:1px;">GHURI</div>
                            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:6px;padding:30px 8px 0;">
                                @for($i=0;$i<18;$i++)
                                <div style="height:12px;background:rgba(255,255,255,{{ $i % 3 == 1 ? '0.6' : '0.25' }});border-radius:1px;"></div>
                                @endfor
                            </div>
                            <div style="position:absolute;bottom:0;left:50%;transform:translateX(-50%);width:36px;height:50px;background:rgba(255,255,255,0.2);border-radius:2px 2px 0 0;"></div>
                        </div>
                        {{-- Building 4 - medium --}}
                        <div class="building" style="width:75px;height:130px;background:linear-gradient(180deg,#fca5a8,#e57b85);animation-delay:-2s;position:relative;">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;padding:8px 8px 0;">
                                <div style="height:12px;background:rgba(255,255,255,0.45);border-radius:2px;"></div>
                                <div style="height:12px;background:rgba(255,255,255,0.45);border-radius:2px;"></div>
                                <div style="height:12px;background:rgba(255,255,255,0.45);border-radius:2px;"></div>
                                <div style="height:12px;background:rgba(255,255,255,0.25);border-radius:2px;"></div>
                                <div style="height:12px;background:rgba(255,255,255,0.45);border-radius:2px;"></div>
                                <div style="height:12px;background:rgba(255,255,255,0.45);border-radius:2px;"></div>
                            </div>
                            <div style="position:absolute;bottom:0;left:50%;transform:translateX(-50%);width:22px;height:32px;background:rgba(255,255,255,0.25);border-radius:2px 2px 0 0;"></div>
                        </div>
                        {{-- Building 5 - small house --}}
                        <div class="building" style="width:55px;height:80px;background:linear-gradient(180deg,#fbbfc2,#e8a0a5);animation-delay:-3s;position:relative;">
                            <div style="position:absolute;top:-18px;left:0;right:0;height:18px;background:#f5949a;clip-path:polygon(50% 0%,100% 100%,0% 100%);"></div>
                            <div style="position:absolute;top:10px;left:8px;width:14px;height:16px;background:rgba(255,255,255,0.4);border-radius:2px;"></div>
                            <div style="position:absolute;top:10px;right:8px;width:14px;height:16px;background:rgba(255,255,255,0.4);border-radius:2px;"></div>
                            <div style="position:absolute;bottom:0;left:50%;transform:translateX(-50%);width:16px;height:26px;background:rgba(255,255,255,0.3);border-radius:2px 2px 0 0;"></div>
                        </div>
                    </div>
                    {{-- Ground line --}}
                    <div style="position:absolute;bottom:0;left:0;right:0;height:4px;background:rgba(255,255,255,0.15);border-radius:2px;"></div>
                </div>

            </div>
        </div>
    </section>

    {{-- ============================================================
         STATS STRIP
    ============================================================ --}}
    <section class="bg-white border-b border-gray-100 py-10">
        <div class="max-w-5xl mx-auto px-4 grid grid-cols-2 md:grid-cols-4 divide-x divide-gray-100">
            <div class="stat-item">
                <div class="stat-number">500+</div>
                <div class="text-sm text-gray-500 font-medium mt-1">Partner Properties</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">50K+</div>
                <div class="text-sm text-gray-500 font-medium mt-1">Monthly Travelers</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">3 Days</div>
                <div class="text-sm text-gray-500 font-medium mt-1">Avg. First Booking</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">Free</div>
                <div class="text-sm text-gray-500 font-medium mt-1">To Get Started</div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         HOW IT WORKS — 4 STEPS
    ============================================================ --}}
    <section class="py-20 px-4 bg-gray-50">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-14">
                <div class="inline-block bg-[#fff5f5] text-[#d00e15] text-xs font-bold px-4 py-1.5 rounded-full mb-4 uppercase tracking-wider">Simple & Fast</div>
                <h2 class="font-outfit font-black text-3xl sm:text-4xl text-[#19100F] mb-3">All you have to do</h2>
                <p class="text-gray-500 text-base max-w-lg mx-auto">From signup to your first booking in just 4 easy steps. No tech skills needed.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 relative">
                {{-- Connector line (desktop) --}}
                <div class="hidden lg:block absolute top-[42px] left-[calc(12.5%+22px)] right-[calc(12.5%+22px)] h-0.5 bg-gradient-to-r from-[#d00e15]/20 via-[#d00e15]/60 to-[#d00e15]/20 z-0"></div>

                {{-- Step 1 --}}
                <div class="step-card relative z-10">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="step-number">1</div>
                        <div class="pt-1">
                            <h3 class="font-outfit font-bold text-base text-[#19100F] relative z-1">Create Account</h3>
                        </div>
                    </div>
                    <p class="text-gray-500 text-sm leading-relaxed relative z-1">
                        <a href="{{ route('register') }}" class="text-[#d00e15] font-semibold hover:underline">Sign up for free</a> or <a href="{{ route('login') }}" class="text-[#d00e15] font-semibold hover:underline">log in</a> to your existing GHURI account. Your property owner profile will be set up automatically.
                    </p>
                    <div class="mt-4 flex justify-center relative z-1">
                        <div class="w-14 h-14 bg-[#fff5f5] rounded-2xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-[#d00e15]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="step-card relative z-10">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="step-number">2</div>
                        <div class="pt-1">
                            <h3 class="font-outfit font-bold text-base text-[#19100F] relative z-1">Add Your Property</h3>
                        </div>
                    </div>
                    <p class="text-gray-500 text-sm leading-relaxed relative z-1">
                        Fill in your property details — name, location, description, amenities, and photos. Set up room types, pricing, and availability.
                    </p>
                    <div class="mt-4 flex justify-center relative z-1">
                        <div class="w-14 h-14 bg-[#fff5f5] rounded-2xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-[#d00e15]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                    </div>
                </div>

                {{-- Step 3 --}}
                <div class="step-card relative z-10">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="step-number">3</div>
                        <div class="pt-1">
                            <h3 class="font-outfit font-bold text-base text-[#19100F] relative z-1">Get Approved</h3>
                        </div>
                    </div>
                    <p class="text-gray-500 text-sm leading-relaxed relative z-1">
                        Submit your property for review. Our team at GHURI verifies your listing for quality. Approval typically takes 24–48 hours.
                    </p>
                    <div class="mt-4 flex justify-center relative z-1">
                        <div class="w-14 h-14 bg-[#fff5f5] rounded-2xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-[#d00e15]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>

                {{-- Step 4 --}}
                <div class="step-card relative z-10">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="step-number">4</div>
                        <div class="pt-1">
                            <h3 class="font-outfit font-bold text-base text-[#19100F] relative z-1">Go Live & Earn</h3>
                        </div>
                    </div>
                    <p class="text-gray-500 text-sm leading-relaxed relative z-1">
                        Your property goes live on GHURI in front of thousands of travelers. Manage bookings, respond to reviews, and grow your revenue.
                    </p>
                    <div class="mt-4 flex justify-center relative z-1">
                        <div class="w-14 h-14 bg-[#fff5f5] rounded-2xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-[#d00e15]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CTA under steps --}}
            <div class="text-center mt-12">
                @auth
                    @if(auth()->user()->isPropertyOwner())
                        <a href="{{ route('property-owner.hotels.create') }}"
                           class="inline-flex items-center gap-3 bg-[#d00e15] hover:bg-[#A90B16] text-white font-bold text-base px-10 py-4 rounded-2xl transition shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Add Your Property Now
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center gap-3 bg-[#d00e15] hover:bg-[#A90B16] text-white font-bold text-base px-10 py-4 rounded-2xl transition shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Get Started for Free
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-3 bg-[#d00e15] hover:bg-[#A90B16] text-white font-bold text-base px-10 py-4 rounded-2xl transition shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Get Started for Free
                    </a>
                @endauth
                <p class="text-gray-400 text-sm mt-3">No credit card required. Free to list.</p>
            </div>
        </div>
    </section>

    {{-- ============================================================
         WHY GHURI — BENEFITS
    ============================================================ --}}
    <section class="py-20 px-4 bg-white">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-14">
                <div class="inline-block bg-[#fff5f5] text-[#d00e15] text-xs font-bold px-4 py-1.5 rounded-full mb-4 uppercase tracking-wider">Why GHURI?</div>
                <h2 class="font-outfit font-black text-3xl sm:text-4xl text-[#19100F] mb-3">Everything you need to grow</h2>
                <p class="text-gray-500 text-base max-w-lg mx-auto">GHURI gives property owners powerful tools to manage, market, and maximize revenue — all in one dashboard.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Benefit 1 --}}
                <div class="benefit-card bg-white">
                    <div class="w-12 h-12 bg-[#fff5f5] rounded-2xl flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-[#d00e15]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <h3 class="font-outfit font-bold text-lg text-[#19100F] mb-2">Data-Rich Analytics</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Access detailed performance insights — occupancy rates, revenue trends, guest reviews — to refine your strategy and stay competitive.
                    </p>
                </div>

                {{-- Benefit 2 --}}
                <div class="benefit-card bg-white">
                    <div class="w-12 h-12 bg-[#fff5f5] rounded-2xl flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-[#d00e15]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="font-outfit font-bold text-lg text-[#19100F] mb-2">Availability Calendar</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Manage your room availability with a powerful bulk-update calendar. Block dates, set seasonal pricing, and sync across room types instantly.
                    </p>
                </div>

                {{-- Benefit 3 --}}
                <div class="benefit-card bg-white">
                    <div class="w-12 h-12 bg-[#fff5f5] rounded-2xl flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-[#d00e15]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h3 class="font-outfit font-bold text-lg text-[#19100F] mb-2">Flexible Pricing Rules</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Set custom rate rules per room type — minimum stay, advance purchase, seasonal rates, and more. You're always in control.
                    </p>
                </div>

                {{-- Benefit 4 --}}
                <div class="benefit-card bg-white">
                    <div class="w-12 h-12 bg-[#fff5f5] rounded-2xl flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-[#d00e15]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    </div>
                    <h3 class="font-outfit font-bold text-lg text-[#19100F] mb-2">Guest Reviews & Reputation</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Respond to guest reviews directly from your dashboard. Build trust and improve your property's ranking on GHURI search results.
                    </p>
                </div>

                {{-- Benefit 5 --}}
                <div class="benefit-card bg-white">
                    <div class="w-12 h-12 bg-[#fff5f5] rounded-2xl flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-[#d00e15]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    </div>
                    <h3 class="font-outfit font-bold text-lg text-[#19100F] mb-2">Promotions & Deals</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Create special promotions and discount offers to attract more travelers during low season or for direct bookings.
                    </p>
                </div>

                {{-- Benefit 6 --}}
                <div class="benefit-card bg-white">
                    <div class="w-12 h-12 bg-[#fff5f5] rounded-2xl flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-[#d00e15]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <h3 class="font-outfit font-bold text-lg text-[#19100F] mb-2">Dedicated Partner Support</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Our GHURI partner support team is here to help you onboard, optimize listings, and resolve any issues — fast and professionally.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         PROPERTY TYPES
    ============================================================ --}}
    <section class="py-16 px-4 bg-gray-50">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-10">
                <h2 class="font-outfit font-black text-2xl sm:text-3xl text-[#19100F] mb-2">What can you list?</h2>
                <p class="text-gray-500 text-sm">GHURI welcomes all types of accommodation.</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @php
                $propertyTypes = [
                    ['label' => 'Hotels', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                    ['label' => 'Resorts', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['label' => 'Apartments', 'icon' => 'M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z'],
                    ['label' => 'Guesthouses', 'icon' => 'M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'],
                    ['label' => 'Homestays', 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                    ['label' => '& More', 'icon' => 'M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z'],
                ];
                @endphp
                @foreach($propertyTypes as $type)
                <div class="bg-white rounded-2xl p-5 text-center border border-gray-100 hover:border-[#d00e15]/30 hover:shadow-md transition-all group cursor-default">
                    <div class="w-10 h-10 bg-[#fff5f5] rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-[#d00e15] transition-colors">
                        <svg class="w-5 h-5 text-[#d00e15] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $type['icon'] }}"/>
                        </svg>
                    </div>
                    <span class="text-sm font-bold text-[#19100F]">{{ $type['label'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================================
         FAQ
    ============================================================ --}}
    <section class="py-20 px-4 bg-white">
        <div class="max-w-3xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="font-outfit font-black text-3xl text-[#19100F] mb-3">Frequently asked questions</h2>
                <p class="text-gray-500 text-sm">Everything you need to know before listing your property.</p>
            </div>

            <div class="space-y-0 border border-gray-100 rounded-2xl overflow-hidden shadow-sm" x-data="{ open: null }">
                @php
                $faqs = [
                    ['q' => 'Is it free to list my property on GHURI?', 'a' => 'Yes! Listing your property on GHURI is completely free. We operate on a commission-only model, meaning we only earn when you earn. There are no upfront fees or monthly charges.'],
                    ['q' => 'How long does approval take?', 'a' => 'Our team reviews submissions within 24–48 business hours. We check property details, photos, and room information to ensure quality for our travelers. You\'ll receive an email notification once approved.'],
                    ['q' => 'What commission does GHURI charge?', 'a' => 'Our standard commission rate is competitive with industry norms. The exact percentage is shared during your onboarding process. You keep the majority of each booking revenue.'],
                    ['q' => 'Can I manage multiple properties?', 'a' => 'Absolutely! Your GHURI Property Dashboard supports managing multiple properties under one account. Each property has its own rooms, availability, and pricing settings.'],
                    ['q' => 'How do I receive payment for bookings?', 'a' => 'GHURI processes guest payments and transfers your earnings (minus commission) directly to your registered bank account. Payout schedules and methods are configured in your dashboard settings.'],
                    ['q' => 'What if I need help setting up my listing?', 'a' => 'Our dedicated partner support team is available to assist you. You can reach us via email or the support section in your dashboard. We\'re committed to helping you succeed on GHURI.'],
                ];
                @endphp

                @foreach($faqs as $i => $faq)
                <div class="faq-item" x-data="{ id: {{ $i }} }">
                    <button
                        @click="$dispatch('faq-toggle', { id: id }); open = open === id ? null : id"
                        class="w-full text-left px-6 py-5 flex items-center justify-between gap-4 hover:bg-gray-50 transition-colors"
                        x-on:faq-toggle.window="if($event.detail.id !== id) open = null"
                    >
                        <span class="font-semibold text-[#19100F] text-sm">{{ $faq['q'] }}</span>
                        <svg class="w-5 h-5 text-[#d00e15] shrink-0 transition-transform duration-300" :class="open === id ? 'rotate-45' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                    <div x-show="open === id" x-collapse style="display:none;" class="px-6 pb-5">
                        <p class="text-gray-500 text-sm leading-relaxed">{{ $faq['a'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================================
         FINAL CTA BANNER
    ============================================================ --}}
    <section class="py-20 px-4" style="background: linear-gradient(135deg, #1a0305 0%, #6b0611 40%, #d00e15 100%);">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="font-outfit font-black text-3xl sm:text-5xl text-white mb-4 leading-tight">
                Ready to grow<br>your business?
            </h2>
            <p class="text-white/70 text-base mb-10 max-w-lg mx-auto">
                Join GHURI's growing network of property partners. It takes less than 10 minutes to set up your first listing.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    @if(auth()->user()->isPropertyOwner())
                        <a href="{{ route('property-owner.hotels.create') }}" class="cta-btn-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Add Your Property
                        </a>
                        <a href="{{ route('property-owner.dashboard') }}" class="cta-btn-secondary">
                            Go to Dashboard →
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="cta-btn-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            List your property — it's free
                        </a>
                        <a href="{{ route('login') }}" class="cta-btn-secondary">
                            Sign in to existing account
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="cta-btn-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        List your property — it's free
                    </a>
                    <a href="{{ route('login') }}" class="cta-btn-secondary">
                        Sign in to existing account
                    </a>
                @endauth
            </div>
        </div>
    </section>

    {{-- ============================================================
         FOOTER (minimal)
    ============================================================ --}}
    <footer class="bg-[#1a0305] text-white/50 py-8 px-4">
        <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
            <div class="font-outfit font-extrabold text-xl text-white">GHURI<span class="text-[#ff4b55]">.</span></div>
            <div>© {{ date('Y') }} GHURI. All rights reserved.</div>
            <div class="flex gap-6">
                <a href="{{ url('/') }}" class="hover:text-white transition">Home</a>
                <a href="{{ route('hotels.search') }}" class="hover:text-white transition">Find Hotels</a>
                <a href="{{ route('login') }}" class="hover:text-white transition">Sign In</a>
            </div>
        </div>
    </footer>

</body>
</html>
