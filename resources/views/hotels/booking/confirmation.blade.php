<x-guest-layout>
<div class="max-w-2xl mx-auto py-12 px-4 text-center">
    {{-- Success Animation --}}
    <div class="animate-check-bounce mb-6">
        <div class="w-24 h-24 rounded-full bg-green-100 mx-auto flex items-center justify-center">
            <i class="fas fa-check text-4xl text-status-confirmed"></i>
        </div>
    </div>

    <h1 class="font-heading text-3xl font-bold text-brand-black mb-2 animate-fade-in">Booking Confirmed!</h1>
    <p class="text-brand-muted mb-8 animate-fade-in">Your reservation has been received and is being processed.</p>

    {{-- Booking Reference --}}
    <div class="card card-body inline-block mb-8 animate-scale-in">
        <p class="text-xs text-brand-muted uppercase tracking-wider mb-1">Booking Reference</p>
        <p class="text-3xl font-mono font-bold text-brand-black tracking-wider">{{ $booking->booking_ref }}</p>
    </div>

    {{-- Booking Summary --}}
    <div class="card card-body text-left max-w-md mx-auto mb-8 animate-slide-up">
        <div class="space-y-3">
            <div class="flex justify-between text-sm">
                <span class="text-brand-muted">Property</span>
                <span class="font-medium text-brand-black">{{ $booking->property->name }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-brand-muted">Room</span>
                <span class="font-medium text-brand-black">{{ $booking->roomType->name }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-brand-muted">Check-in</span>
                <span class="font-medium text-brand-black">{{ $booking->check_in->format('D, M d, Y') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-brand-muted">Check-out</span>
                <span class="font-medium text-brand-black">{{ $booking->check_out->format('D, M d, Y') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-brand-muted">Guests</span>
                <span class="font-medium text-brand-black">{{ $booking->adults }} adults{{ $booking->children > 0 ? ', ' . $booking->children . ' children' : '' }}</span>
            </div>
            <div class="flex justify-between text-sm pt-3 border-t border-brand-border">
                <span class="font-heading font-bold text-brand-black">Total Paid</span>
                <span class="font-heading font-bold text-xl text-brand-black">${{ number_format($booking->total, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-center gap-3 animate-slide-up">
        <a href="{{ route('my-bookings.show', $booking) }}" class="btn-primary">
            <i class="fas fa-calendar-check"></i> Manage Booking
        </a>
        <a href="{{ route('hotels.search') }}" class="btn-secondary">
            <i class="fas fa-search"></i> Continue Exploring
        </a>
    </div>
</div>
</x-guest-layout>
