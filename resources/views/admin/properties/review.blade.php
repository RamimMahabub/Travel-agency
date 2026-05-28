<x-admin-layout>
    <x-slot name="pageTitle">Review Property: {{ $property->name }}</x-slot>
    <x-slot name="pageSubtitle">Check details and approve or reject</x-slot>
    
    <div class="mb-4">
        <a href="{{ route('admin.properties.index') }}" class="text-blue-600 hover:underline text-sm font-medium">
            <i class="fas fa-arrow-left mr-1"></i> Back to Properties
        </a>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-6">
                {{-- Property Details --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-4">Property Details</h3>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $property->name }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Type & Stars</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($property->type) }} - {{ $property->stars }} Stars</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Address</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $property->full_address }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $property->full_description }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- Room Types --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-4">Room Types</h3>
                        @foreach($property->roomTypes as $room)
                            <div class="mb-4 border-b pb-4">
                                <h4 class="font-medium text-gray-900">{{ $room->name }}</h4>
                                <p class="text-sm text-gray-600">Base Price: ${{ $room->base_price_per_night }} / Night</p>
                                <p class="text-sm text-gray-600">Inventory: {{ $room->inventory_count }} Rooms</p>
                                <p class="text-sm text-gray-600">Max Adults: {{ $room->max_adults }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Action Panel --}}
            <div class="space-y-6">
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                    <div class="p-6 text-slate-900">
                        <h3 class="text-lg font-bold mb-4 text-slate-800">Review Action</h3>

                        <form method="POST" action="{{ route('admin.properties.approve', $property) }}" class="mb-4">
                            @csrf
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 bg-green-600 border border-transparent rounded-lg font-bold text-sm text-white hover:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                                <i class="fas fa-check-circle mr-2"></i> Approve Property
                            </button>
                        </form>

                        <hr class="my-6 border-gray-100">

                        <form method="POST" action="{{ route('admin.properties.reject', $property) }}">
                            @csrf
                            <div class="mb-4">
                                <label for="reason" class="block font-medium text-sm text-slate-700 mb-2">Rejection Reason / Notes</label>
                                <textarea id="reason" name="reason" rows="3" class="mt-1 block w-full border border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-lg shadow-sm px-3 py-2 text-sm" placeholder="Why is this property being rejected?" required></textarea>
                            </div>
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 bg-red-50 border border-red-200 rounded-lg font-bold text-sm text-red-600 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                                <i class="fas fa-times-circle mr-2"></i> Reject Property
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
