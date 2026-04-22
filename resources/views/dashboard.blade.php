<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-light min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            <h3 class="text-2xl font-bold mb-6 text-dark">My Bookings</h3>

            @php
                $bookings = auth()->user()->bookings()->latest()->get();
            @endphp

            @if($bookings->isEmpty())
                <div class="bg-white p-12 rounded-2xl shadow-sm text-center">
                    <p class="text-gray-500 mb-4">You have no bookings yet.</p>
                    <a href="/" class="bg-primary text-white px-6 py-2 rounded-lg font-semibold inline-block">Start Exploring</a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($bookings as $booking)
                        <a href="{{ route('booking.show', $booking->id) }}" class="block bg-white p-6 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold font-poppins {{ $booking->status == 'confirmed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ strtoupper($booking->status) }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">Booking Reference</p>
                                    <p class="font-mono font-bold text-lg text-primary">{{ $booking->api_reference_id }}</p>
                                </div>
                            </div>
                            
                            <hr class="border-gray-100 mb-4">
                            
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="text-gray-500 text-sm">Date</p>
                                    <p class="font-semibold">{{ $booking->created_at->format('M d, Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-gray-500 text-sm">Total Paid</p>
                                    <p class="font-bold text-xl">{{ number_format($booking->total_amount, 2) }} BDT</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
