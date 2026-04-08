<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Flight Results: {{ $search['origin'] }} to {{ $search['destination'] }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Filters Sidebar -->
                <div class="w-full md:w-1/4 bg-white p-6 rounded-2xl shadow-sm self-start">
                    <h3 class="font-bold text-lg mb-4 text-dark">Filters</h3>
                    
                    <div class="mb-6">
                        <h4 class="font-semibold mb-2">Stops</h4>
                        <label class="flex items-center gap-2 mb-2">
                            <input type="checkbox" class="rounded text-primary border-gray-300 focus:ring-primary" checked> Non-stop
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="rounded text-primary border-gray-300 focus:ring-primary" checked> 1 Stop
                        </label>
                    </div>

                    <div>
                        <h4 class="font-semibold mb-2">Airlines</h4>
                        @foreach(collect($flights)->pluck('airline')->unique() as $airline)
                            <label class="flex items-center gap-2 mb-2">
                                <input type="checkbox" class="rounded text-primary border-gray-300 focus:ring-primary" checked> {{ $airline }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Results List -->
                <div class="w-full md:w-3/4 space-y-4">
                    @forelse($flights as $flight)
                        <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition flex flex-col md:flex-row items-center justify-between border border-gray-100">
                            <div class="flex-1 flex items-center justify-between w-full md:w-auto">
                                <div class="text-center md:text-left">
                                    <p class="font-bold text-lg">{{ \Carbon\Carbon::parse($flight['departure_time'])->format('H:i') }}</p>
                                    <p class="text-gray-500 text-sm">{{ $flight['origin'] }}</p>
                                </div>
                                
                                <div class="px-8 text-center flex-1">
                                    <p class="text-xs text-gray-400 mb-1">{{ $flight['duration'] }} • {{ $flight['stops'] == 0 ? 'Non-stop' : $flight['stops'].' Stop' }}</p>
                                    <div class="relative flex items-center justify-center">
                                        <div class="border-t-2 border-gray-300 w-full"></div>
                                        <svg class="w-5 h-5 text-gray-400 absolute bg-white px-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                    </div>
                                </div>

                                <div class="text-center md:text-right">
                                    <p class="font-bold text-lg">{{ \Carbon\Carbon::parse($flight['arrival_time'])->format('H:i') }}</p>
                                    <p class="text-gray-500 text-sm">{{ $flight['destination'] }}</p>
                                </div>
                            </div>
                            
                            <div class="flex-1/4 mt-6 md:mt-0 md:ml-8 text-center md:text-right border-t md:border-t-0 md:border-l border-gray-100 pt-4 md:pt-0 md:pl-8 flex flex-col justify-center items-center md:items-end">
                                <p class="text-xs text-gray-500 mb-1">{{ $flight['airline'] }} ({{ $flight['flight_number'] }})</p>
                                <p class="text-2xl font-bold text-dark mb-3">{{ number_format($flight['price'], 2) }} <span class="text-sm font-normal">{{ $flight['currency'] }}</span></p>
                                <a href="{{ route('booking.checkout', ['flightId' => $flight['id'], 'passengers' => $search['passengers']]) }}" class="bg-primary hover:bg-red-800 text-white font-bold py-2 px-6 rounded-lg transition inline-block">
                                    Select
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white p-12 rounded-2xl shadow-sm text-center">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h3 class="text-xl font-bold text-gray-700">No flights found</h3>
                            <p class="text-gray-500 mt-2">Try adjusting your search criteria and date.</p>
                            <a href="/" class="text-primary hover:underline mt-4 inline-block font-semibold">Return to Search</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
