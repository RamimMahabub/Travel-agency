<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $property->name }} — {{ config('app.name') }}</title>
    <meta name="description" content="{{ $property->short_description ?? $property->name . ' in ' . $property->city }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:ital,wght@0,400;0,500;0,600;0,700;1,400&family=JetBrains+Mono:wght@400;500;600&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body antialiased bg-brand-surface">

    {{-- Sticky Header --}}
    <header class="bg-white border-b border-brand-border sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-14">
            <a href="{{ route('hotels.search') }}" class="flex items-center gap-2 text-brand-text hover:text-brand-primary transition-colors">
                <i class="fas fa-arrow-left"></i>
                <span class="text-sm">Back to results</span>
            </a>
            <div class="hidden md:flex items-center gap-6">
                <a href="#overview" class="text-sm text-brand-text hover:text-brand-primary">Overview</a>
                <a href="#rooms" class="text-sm text-brand-text hover:text-brand-primary">Rooms</a>
                <a href="#location" class="text-sm text-brand-text hover:text-brand-primary">Location</a>
                <a href="#reviews" class="text-sm text-brand-text hover:text-brand-primary">Reviews</a>
            </div>
            <div class="flex items-center gap-2">
                @if($property->average_rating)
                    <div class="guest-score text-xs {{ $property->average_rating >= 8 ? 'excellent' : 'good' }}">
                        {{ number_format($property->average_rating, 1) }}
                    </div>
                @endif
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        {{-- Photo Gallery --}}
        <div class="grid grid-cols-4 grid-rows-2 gap-2 rounded-2xl overflow-hidden h-[420px] mb-8 animate-fade-in">
            @if($property->photos->isNotEmpty())
                {{-- Hero Image --}}
                <a href="{{ $property->photos[0]->url }}" class="glightbox col-span-2 row-span-2 relative group cursor-pointer overflow-hidden block" data-gallery="hotel-gallery">
                    <img src="{{ $property->photos[0]->url }}" alt="{{ $property->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent pointer-events-none"></div>
                </a>
                @foreach($property->photos->skip(1)->take(4) as $photo)
                    <a href="{{ $photo->url }}" class="glightbox relative group cursor-pointer overflow-hidden block" data-gallery="hotel-gallery">
                        <img src="{{ $photo->url }}" alt="{{ $property->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </a>
                @endforeach
                {{-- Hidden Remaining Photos --}}
                @foreach($property->photos->skip(5) as $photo)
                    <a href="{{ $photo->url }}" class="glightbox hidden" data-gallery="hotel-gallery"></a>
                @endforeach
            @else
                <div class="col-span-4 row-span-2 bg-gradient-to-br from-brand-light to-white flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-hotel text-6xl text-brand-border mb-3"></i>
                        <p class="text-brand-muted text-sm">No photos available</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-8">

                {{-- Overview Section --}}
                <section id="overview" class="animate-slide-up">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                @for($i = 1; $i <= $property->stars; $i++)
                                    <i class="fas fa-star text-yellow-400 text-sm"></i>
                                @endfor
                                <span class="badge bg-brand-surface text-brand-text text-[10px]">{{ ucfirst($property->type) }}</span>
                            </div>
                            <h1 class="font-heading text-3xl font-bold text-brand-black mb-1">{{ $property->name }}</h1>
                            <p class="text-sm text-brand-muted">
                                <i class="fas fa-map-marker-alt text-brand-primary"></i>
                                {{ $property->full_address }}
                            </p>
                        </div>
                    </div>

                    @if($property->short_description)
                        <p class="text-brand-text text-sm leading-relaxed mb-4">{{ $property->short_description }}</p>
                    @endif

                    {{-- Highlights --}}
                    @if($property->amenities)
                        <div class="flex flex-wrap gap-2 mb-4">
                            @php $topAmenities = collect($property->amenities)->flatten()->take(6); @endphp
                            @foreach($topAmenities as $amenity)
                                <span class="amenity-tag active">
                                    <i class="fas fa-check text-[10px]"></i>
                                    {{ str_replace('_', ' ', ucfirst($amenity)) }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    {{-- Check-in/out --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-brand-surface rounded-xl p-4">
                            <p class="text-xs text-brand-muted mb-1">Check-in</p>
                            <p class="text-sm font-semibold text-brand-black">From {{ $property->check_in_time }}</p>
                        </div>
                        <div class="bg-brand-surface rounded-xl p-4">
                            <p class="text-xs text-brand-muted mb-1">Check-out</p>
                            <p class="text-sm font-semibold text-brand-black">Until {{ $property->check_out_time }}</p>
                        </div>
                    </div>

                    @if($property->full_description)
                        <div class="mt-6">
                            <h3 class="section-heading text-base">About this property</h3>
                            <p class="text-sm text-brand-text leading-relaxed">{{ $property->full_description }}</p>
                        </div>
                    @endif
                </section>

                {{-- Rooms & Rates --}}
                <section id="rooms" class="animate-slide-up">
                    <h2 class="section-heading">Rooms & Rates</h2>

                    <div class="space-y-4">
                        @foreach($property->activeRoomTypes as $roomType)
                            @php $data = $roomsData[$roomType->id] ?? ['available' => 0, 'pricing' => ['total' => 0, 'nightly_rate' => 0, 'nights' => 1]]; @endphp
                            <div class="card overflow-hidden">
                                <div class="flex flex-col md:flex-row">
                                    {{-- Room Photo --}}
                                    <div class="md:w-56 h-40 md:h-auto flex-shrink-0 bg-brand-surface relative overflow-hidden">
                                        @if($roomType->photos->isNotEmpty())
                                            <a href="{{ $roomType->photos[0]->url }}" class="glightbox block w-full h-full" data-gallery="room-gallery-{{ $roomType->id }}">
                                                <img src="{{ $roomType->photos[0]->url }}" alt="{{ $roomType->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform">
                                            </a>
                                            @foreach($roomType->photos->skip(1) as $photo)
                                                <a href="{{ $photo->url }}" class="glightbox hidden" data-gallery="room-gallery-{{ $roomType->id }}"></a>
                                            @endforeach
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <i class="fas fa-bed text-3xl text-brand-border"></i>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Room Details --}}
                                    <div class="flex-1 p-5">
                                        <div class="flex items-start justify-between mb-3">
                                            <div>
                                                <h3 class="font-heading font-bold text-brand-black">{{ $roomType->name }}</h3>
                                                <div class="flex items-center gap-4 mt-1 text-xs text-brand-muted">
                                                    @if($roomType->size_sqm)
                                                        <span><i class="fas fa-expand"></i> {{ $roomType->size_sqm }} sqm</span>
                                                    @endif
                                                    <span><i class="fas fa-user"></i> {{ $roomType->max_adults }} adults</span>
                                                    @if($roomType->max_children > 0)
                                                        <span><i class="fas fa-child"></i> {{ $roomType->max_children }} children</span>
                                                    @endif
                                                    <span><i class="fas fa-bed"></i> {{ $roomType->bed_config_display }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Room Amenities --}}
                                        @if($roomType->amenities)
                                            <div class="flex flex-wrap gap-1.5 mb-3">
                                                @foreach(array_slice($roomType->amenities, 0, 6) as $amenity)
                                                    <span class="text-[10px] px-2 py-0.5 bg-brand-surface rounded-full text-brand-text">{{ $amenity }}</span>
                                                @endforeach
                                            </div>
                                        @endif

                                        {{-- Rate Plans --}}
                                        @foreach($roomType->activeRatePlans as $plan)
                                            <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-brand-border' : '' }}">
                                                <div>
                                                    <span class="text-sm font-medium text-brand-black">{{ $plan->plan_display_name }}</span>
                                                    @if($plan->price_supplement_per_adult > 0)
                                                        <span class="text-xs text-brand-muted">(+${{ number_format($plan->price_supplement_per_adult, 0) }}/adult)</span>
                                                    @endif
                                                </div>
                                                <div class="flex items-center gap-4">
                                                    <div class="text-right">
                                                        <p class="text-lg font-heading font-bold text-brand-black">
                                                            ${{ number_format($data['pricing']['nightly_rate'] + $plan->price_supplement_per_adult, 0) }}
                                                        </p>
                                                        <p class="text-[10px] text-brand-muted">per night</p>
                                                    </div>
                                                    @if($data['available'] > 0)
                                                        <a href="{{ route('hotels.book.step1', ['property' => $property, 'roomType' => $roomType, 'check_in' => $checkIn->format('Y-m-d'), 'check_out' => $checkOut->format('Y-m-d'), 'adults' => $guests, 'rate_plan_id' => $plan->id]) }}" class="btn-primary btn-sm">
                                                            Reserve
                                                        </a>
                                                    @else
                                                        <span class="badge-cancelled">Sold Out</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach

                                        {{-- Availability Warning --}}
                                        @if($data['available'] > 0 && $data['available'] <= 3)
                                            <p class="text-xs text-status-cancelled font-medium mt-2">
                                                <i class="fas fa-fire"></i> Only {{ $data['available'] }} room{{ $data['available'] > 1 ? 's' : '' }} left!
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- Location --}}
                <section id="location" class="animate-slide-up">
                    <h2 class="section-heading">Location</h2>
                    <div class="card card-body">
                        <div class="bg-brand-surface rounded-xl p-8 text-center mb-4">
                            <i class="fas fa-map text-4xl text-brand-muted mb-2"></i>
                            <p class="text-sm text-brand-muted">{{ $property->full_address }}</p>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @if($property->airport_distance)
                                <div class="text-center p-3 bg-brand-surface rounded-xl">
                                    <i class="fas fa-plane text-brand-primary mb-1"></i>
                                    <p class="text-xs font-medium text-brand-black">{{ $property->airport_distance }}</p>
                                    <p class="text-[10px] text-brand-muted">to Airport</p>
                                </div>
                            @endif
                            @if($property->beach_distance)
                                <div class="text-center p-3 bg-brand-surface rounded-xl">
                                    <i class="fas fa-umbrella-beach text-brand-primary mb-1"></i>
                                    <p class="text-xs font-medium text-brand-black">{{ $property->beach_distance }}</p>
                                    <p class="text-[10px] text-brand-muted">to Beach</p>
                                </div>
                            @endif
                            @if($property->city_center_distance)
                                <div class="text-center p-3 bg-brand-surface rounded-xl">
                                    <i class="fas fa-city text-brand-primary mb-1"></i>
                                    <p class="text-xs font-medium text-brand-black">{{ $property->city_center_distance }}</p>
                                    <p class="text-[10px] text-brand-muted">to City Center</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </section>

                {{-- Reviews --}}
                <section id="reviews" class="animate-slide-up">
                    <h2 class="section-heading">Guest Reviews</h2>

                    {{-- Review Summary --}}
                    @if($reviewStats['average'])
                        <div class="card card-body mb-5">
                            <div class="flex items-center gap-6">
                                <div class="text-center">
                                    <div class="guest-score text-xl w-16 h-16 rounded-xl {{ $reviewStats['average'] >= 8 ? 'excellent' : 'good' }}">
                                        {{ number_format($reviewStats['average'], 1) }}
                                    </div>
                                    <p class="text-xs text-brand-muted mt-1">{{ $reviewStats['count'] }} reviews</p>
                                </div>
                                <div class="flex-1 grid grid-cols-2 gap-x-8 gap-y-2">
                                    @foreach(['cleanliness' => 'Cleanliness', 'location' => 'Location', 'service' => 'Service', 'value' => 'Value', 'facilities' => 'Facilities'] as $key => $label)
                                        @if($reviewStats[$key])
                                            <div>
                                                <div class="flex items-center justify-between mb-0.5">
                                                    <span class="text-xs text-brand-text">{{ $label }}</span>
                                                    <span class="text-xs font-bold text-brand-black">{{ number_format($reviewStats[$key], 1) }}</span>
                                                </div>
                                                <div class="w-full h-1.5 bg-brand-surface rounded-full overflow-hidden">
                                                    <div class="h-full rounded-full bg-brand-primary" style="width: {{ ($reviewStats[$key] / 10) * 100 }}%"></div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Review List --}}
                    <div class="space-y-3">
                        @forelse($reviews as $review)
                            <div class="card card-body">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-brand-surface flex items-center justify-center">
                                            <span class="text-sm font-bold text-brand-text">{{ substr($review->guest->name ?? 'G', 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-brand-black">{{ $review->guest->name ?? 'Guest' }}</p>
                                            <p class="text-xs text-brand-muted">{{ $review->created_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="guest-score text-xs w-8 h-8 rounded-md {{ $review->overall_score >= 8 ? 'excellent' : ($review->overall_score >= 6 ? 'good' : 'average') }}">
                                        {{ number_format($review->overall_score, 1) }}
                                    </div>
                                </div>
                                @if($review->comment)
                                    <p class="text-sm text-brand-text leading-relaxed">{{ $review->comment }}</p>
                                @endif
                                @if($review->hotel_response)
                                    <div class="mt-3 pl-4 border-l-2 border-brand-primary bg-brand-light rounded-r-lg p-3">
                                        <p class="text-xs font-medium text-brand-black mb-1"><i class="fas fa-reply text-brand-primary"></i> Hotel Response</p>
                                        <p class="text-xs text-brand-text">{{ $review->hotel_response }}</p>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="card card-body text-center py-8">
                                <i class="fas fa-star text-3xl text-brand-border mb-2"></i>
                                <p class="text-sm text-brand-muted">No reviews yet</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4">{{ $reviews->links() }}</div>
                </section>
            </div>

            {{-- Sticky Booking Widget --}}
            <div class="hidden lg:block">
                <div class="booking-widget animate-slide-up">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-xs text-brand-muted">From</p>
                            <div class="total-price">${{ number_format($property->lowest_price ?? 0, 0) }}</div>
                            <p class="text-xs text-brand-muted">per night</p>
                        </div>
                        @if($property->average_rating)
                            <div class="guest-score {{ $property->average_rating >= 8 ? 'excellent' : 'good' }}">
                                {{ number_format($property->average_rating, 1) }}
                            </div>
                        @endif
                    </div>

                    <form action="{{ route('hotels.search') }}" method="GET" class="space-y-3">
                        <div class="form-group">
                            <label class="form-label text-xs">Check-in</label>
                            <input type="date" name="check_in" value="{{ $checkIn->format('Y-m-d') }}" class="form-input-styled text-sm">
                        </div>
                        <div class="form-group">
                            <label class="form-label text-xs">Check-out</label>
                            <input type="date" name="check_out" value="{{ $checkOut->format('Y-m-d') }}" class="form-input-styled text-sm">
                        </div>
                        <div class="form-group">
                            <label class="form-label text-xs">Guests</label>
                            <select name="guests" class="form-input-styled text-sm">
                                @for($g = 1; $g <= 10; $g++)
                                    <option value="{{ $g }}" {{ $guests == $g ? 'selected' : '' }}>{{ $g }} {{ $g === 1 ? 'Guest' : 'Guests' }}</option>
                                @endfor
                            </select>
                        </div>
                    </form>

                    <div class="mt-4 pt-4 border-t border-brand-border">
                        <a href="#rooms" class="btn-primary w-full text-center btn-lg">
                            <i class="fas fa-bed"></i> See Available Rooms
                        </a>
                    </div>

                    @if($property->cancellation_policy && ($property->cancellation_policy['type'] ?? '') === 'free')
                        <div class="mt-3 flex items-center gap-2 text-xs text-status-confirmed">
                            <i class="fas fa-shield-check"></i>
                            <span>Free cancellation available</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lightbox = GLightbox({
                selector: '.glightbox',
                touchNavigation: true,
                loop: true,
            });
        });
    </script>
</body>
</html>
