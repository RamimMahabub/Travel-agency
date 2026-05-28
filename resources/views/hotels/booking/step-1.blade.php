<x-guest-layout>
<div class="max-w-4xl mx-auto py-8 px-4">
    {{-- Progress Steps --}}
    <div class="flex items-center justify-center gap-0 mb-10">
        <div class="wizard-step active"><div class="step-number">1</div><span class="text-xs hidden sm:inline">Review</span></div>
        <div class="wizard-connector"></div>
        <div class="wizard-step"><div class="step-number">2</div><span class="text-xs hidden sm:inline">Details</span></div>
        <div class="wizard-connector"></div>
        <div class="wizard-step"><div class="step-number">3</div><span class="text-xs hidden sm:inline">Payment</span></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2">
            <div class="card card-body">
                <h2 class="section-heading">Review Your Booking</h2>

                {{-- Property Info --}}
                <div class="flex items-start gap-4 mb-6 pb-6 border-b border-brand-border">
                    <div class="w-24 h-20 rounded-lg overflow-hidden bg-brand-surface flex-shrink-0">
                        <img src="{{ $property->cover_photo_url }}" alt="{{ $property->name }}" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-brand-black">{{ $property->name }}</h3>
                        <div class="flex items-center gap-1 mt-0.5">
                            @for($i = 1; $i <= $property->stars; $i++)
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                            @endfor
                        </div>
                        <p class="text-xs text-brand-muted mt-1"><i class="fas fa-map-marker-alt text-brand-primary"></i> {{ $property->city }}, {{ $property->country }}</p>
                    </div>
                </div>

                {{-- Booking Details --}}
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-brand-surface rounded-xl p-4">
                        <p class="text-xs text-brand-muted">Check-in</p>
                        <p class="font-heading font-bold text-brand-black">{{ $checkIn->format('D, M d, Y') }}</p>
                        <p class="text-xs text-brand-muted">from {{ $property->check_in_time }}</p>
                    </div>
                    <div class="bg-brand-surface rounded-xl p-4">
                        <p class="text-xs text-brand-muted">Check-out</p>
                        <p class="font-heading font-bold text-brand-black">{{ $checkOut->format('D, M d, Y') }}</p>
                        <p class="text-xs text-brand-muted">until {{ $property->check_out_time }}</p>
                    </div>
                </div>

                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-brand-muted">Room Type</span>
                        <span class="font-medium text-brand-black">{{ $roomType->name }}</span>
                    </div>
                    @if($ratePlan)
                    <div class="flex justify-between text-sm">
                        <span class="text-brand-muted">Meal Plan</span>
                        <span class="font-medium text-brand-black">{{ $ratePlan->plan_display_name }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-sm">
                        <span class="text-brand-muted">Guests</span>
                        <span class="font-medium text-brand-black">{{ $adults }} adults{{ $children > 0 ? ', ' . $children . ' children' : '' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-brand-muted">Duration</span>
                        <span class="font-medium text-brand-black">{{ $pricing['nights'] }} night{{ $pricing['nights'] > 1 ? 's' : '' }}</span>
                    </div>
                </div>

                @if($property->cancellation_policy && ($property->cancellation_policy['type'] ?? '') === 'free')
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-sm text-status-confirmed">
                        <i class="fas fa-shield-check mr-1"></i>
                        Free cancellation before check-in
                    </div>
                @endif
            </div>
        </div>

        {{-- Price Sidebar --}}
        <div>
            <div class="card card-body sticky top-24">
                <h3 class="font-heading font-bold text-brand-black text-sm mb-4">Price Summary</h3>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-brand-muted">${{ number_format($pricing['nightly_rate'], 2) }} × {{ $pricing['nights'] }} night{{ $pricing['nights'] > 1 ? 's' : '' }}</span>
                        <span class="text-brand-black">${{ number_format($pricing['subtotal'], 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-brand-muted">Taxes & fees</span>
                        <span class="text-brand-black">${{ number_format($pricing['taxes'] + $pricing['fees'], 2) }}</span>
                    </div>
                    @if($pricing['discount'] > 0)
                        <div class="flex justify-between text-status-confirmed">
                            <span>Discount</span>
                            <span>-${{ number_format($pricing['discount'], 2) }}</span>
                        </div>
                    @endif
                </div>

                <div class="flex justify-between mt-4 pt-4 border-t border-brand-border">
                    <span class="font-heading font-bold text-brand-black">Total</span>
                    <span class="text-2xl font-heading font-bold text-brand-black">${{ number_format($pricing['total'], 2) }}</span>
                </div>

                <form method="POST" action="{{ route('hotels.book.step2') }}" class="mt-6">
                    @csrf
                    <input type="hidden" name="property_id" value="{{ $property->id }}">
                    <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">
                    <input type="hidden" name="check_in" value="{{ $checkIn->format('Y-m-d') }}">
                    <input type="hidden" name="check_out" value="{{ $checkOut->format('Y-m-d') }}">
                    <input type="hidden" name="adults" value="{{ $adults }}">
                    <input type="hidden" name="children" value="{{ $children }}">
                    <input type="hidden" name="rate_plan_id" value="{{ $ratePlan?->id }}">

                    <button type="submit" class="btn-primary w-full btn-lg">
                        Continue to Guest Details <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</x-guest-layout>
