<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Checkout Process
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('booking.store') }}" method="POST">
                @csrf
                <input type="hidden" name="flight_id" value="{{ $flightId }}">
                
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-6">
                    <div class="p-6 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-xl font-bold text-dark mb-1">Flight Information</h3>
                        <p class="text-gray-500 text-sm">Flight ID: {{ $flightId }}</p>
                    </div>
                </div>

                <!-- Passengers Loop -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-6 p-6">
                    <h3 class="text-xl font-bold text-dark mb-4">Passenger Details</h3>
                    @for($i = 0; $i < $passengers; $i++)
                        <div class="border border-gray-100 p-4 rounded-xl mb-4 bg-gray-50">
                            <h4 class="font-semibold mb-3">Traveler {{ $i + 1 }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                    <input type="text" name="first_name[]" class="w-full border-gray-300 focus:border-primary focus:ring-primary rounded-lg shadow-sm" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                    <input type="text" name="last_name[]" class="w-full border-gray-300 focus:border-primary focus:ring-primary rounded-lg shadow-sm" required>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>

                <!-- Payment Selection -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-6 p-6">
                    <h3 class="text-xl font-bold text-dark mb-4">Payment Selection</h3>
                    
                    <div class="text-2xl font-bold text-red-600 mb-6 pb-6 border-b border-gray-100">
                        Total Amount: {{ number_format($priceInfo['total_price'], 2) }} {{ $priceInfo['currency'] }}
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="border border-gray-200 p-4 rounded-xl cursor-pointer hover:border-primary transition group">
                            <input type="radio" name="payment_method" value="card" class="text-primary hidden peer" checked>
                            <div class="peer-checked:font-bold peer-checked:text-primary text-center">
                                Credit / Debit Card
                            </div>
                        </label>
                        <label class="border border-gray-200 p-4 rounded-xl cursor-pointer hover:border-primary transition group">
                            <input type="radio" name="payment_method" value="bkash" class="text-primary hidden peer">
                            <div class="peer-checked:font-bold peer-checked:text-primary text-center">
                                bKash
                            </div>
                        </label>
                        <label class="border border-gray-200 p-4 rounded-xl cursor-pointer hover:border-primary transition group">
                            <input type="radio" name="payment_method" value="nagad" class="text-primary hidden peer">
                            <div class="peer-checked:font-bold peer-checked:text-primary text-center">
                                Nagad
                            </div>
                        </label>
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="bg-primary hover:bg-red-800 text-white font-bold py-3 px-8 rounded-lg shadow-lg text-lg transition">
                        Confirm & Pay
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
