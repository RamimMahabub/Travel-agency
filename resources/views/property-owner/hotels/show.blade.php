<x-pms-layout :pageTitle="$hotel->name" :pageSubtitle="ucfirst($hotel->type) . ' · ' . ($hotel->city ?? 'Location not set')">
    <x-slot:headerActions>
        <a href="{{ route('property-owner.hotels.edit', $hotel) }}" class="btn-secondary btn-sm"><i class="fas fa-edit"></i> Edit</a>
        @if($hotel->isDraft())
            <form method="POST" action="{{ route('property-owner.hotels.submit-approval', $hotel) }}" class="inline">
                @csrf
                <button type="submit" class="btn-primary btn-sm"><i class="fas fa-paper-plane"></i> Submit for Approval</button>
            </form>
        @endif
    </x-slot:headerActions>

    {{-- Status Banner --}}
    @if($hotel->admin_notes && $hotel->status === 'draft')
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-5 flex items-start gap-3">
            <i class="fas fa-exclamation-triangle text-status-pending mt-0.5"></i>
            <div>
                <p class="text-sm font-medium text-brand-black">Changes Requested by Admin</p>
                <p class="text-xs text-brand-text mt-1">{{ $hotel->admin_notes }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-5">
            {{-- Property Details Card --}}
            <div class="card card-body">
                <div class="flex items-center gap-3 mb-4">
                    <span class="badge-{{ $hotel->status === 'approved' ? 'confirmed' : ($hotel->status === 'pending_approval' ? 'pending' : 'info') }}">
                        {{ ucfirst(str_replace('_', ' ', $hotel->status)) }}
                    </span>
                    <div class="flex items-center gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star text-sm {{ $i <= $hotel->stars ? 'text-yellow-400' : 'text-brand-border' }}"></i>
                        @endfor
                    </div>
                </div>
                @if($hotel->short_description)
                    <p class="text-sm text-brand-text mb-3">{{ $hotel->short_description }}</p>
                @endif
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div><span class="text-brand-muted">Location:</span> {{ $hotel->city }}, {{ $hotel->country }}</div>
                    <div><span class="text-brand-muted">Check-in:</span> {{ $hotel->check_in_time }}</div>
                    <div><span class="text-brand-muted">Check-out:</span> {{ $hotel->check_out_time }}</div>
                    <div><span class="text-brand-muted">Address:</span> {{ $hotel->address_line_1 }}</div>
                </div>
            </div>

            {{-- Photos --}}
            <div class="card card-body">
                <h3 class="font-heading font-bold text-brand-black text-sm mb-3">Photos ({{ $hotel->photos->count() }})</h3>
                @if($hotel->photos->isNotEmpty())
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($hotel->photos as $photo)
                            <div class="aspect-[4/3] rounded-lg overflow-hidden bg-brand-surface">
                                <img src="{{ $photo->url }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-brand-muted">No photos uploaded yet.</p>
                @endif
            </div>

            {{-- Room Types --}}
            <div class="card card-body">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-heading font-bold text-brand-black text-sm">Room Types ({{ $hotel->roomTypes->count() }})</h3>
                    <a href="{{ route('property-owner.hotels.rooms.index', $hotel) }}" class="text-xs text-brand-primary hover:underline">Manage →</a>
                </div>
                @foreach($hotel->roomTypes as $room)
                    <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-brand-border' : '' }}">
                        <div>
                            <p class="text-sm font-medium text-brand-black">{{ $room->name }}</p>
                            <p class="text-xs text-brand-muted">{{ $room->inventory_count }} rooms · {{ $room->max_adults }} adults</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-brand-primary">${{ number_format($room->base_price_per_night, 0) }}</p>
                            <span class="badge-{{ $room->status === 'active' ? 'confirmed' : 'cancelled' }} text-[10px]">{{ ucfirst($room->status) }}</span>
                        </div>
                    </div>
                @endforeach
                @if($hotel->roomTypes->isEmpty())
                    <a href="{{ route('property-owner.hotels.rooms.create', $hotel) }}" class="btn-secondary w-full text-center"><i class="fas fa-plus"></i> Add Room Type</a>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-5">
            <div class="card card-body">
                <h3 class="font-heading font-bold text-brand-black text-sm mb-3">Quick Links</h3>
                <div class="space-y-2">
                    <a href="{{ route('property-owner.hotels.rooms.index', $hotel) }}" class="flex items-center gap-2 text-sm text-brand-text hover:text-brand-primary p-2 rounded-lg hover:bg-brand-surface transition-colors">
                        <i class="fas fa-bed w-5 text-center text-brand-muted"></i> Room Types
                    </a>
                    <a href="{{ route('property-owner.hotels.rate-rules.index', $hotel) }}" class="flex items-center gap-2 text-sm text-brand-text hover:text-brand-primary p-2 rounded-lg hover:bg-brand-surface transition-colors">
                        <i class="fas fa-tags w-5 text-center text-brand-muted"></i> Rate Rules
                    </a>
                    <a href="{{ route('property-owner.availability.index', $hotel) }}" class="flex items-center gap-2 text-sm text-brand-text hover:text-brand-primary p-2 rounded-lg hover:bg-brand-surface transition-colors">
                        <i class="fas fa-calendar w-5 text-center text-brand-muted"></i> Availability
                    </a>
                    <a href="{{ route('property-owner.bookings.index', ['property_id' => $hotel->id]) }}" class="flex items-center gap-2 text-sm text-brand-text hover:text-brand-primary p-2 rounded-lg hover:bg-brand-surface transition-colors">
                        <i class="fas fa-calendar-check w-5 text-center text-brand-muted"></i> Bookings
                    </a>
                </div>
            </div>
            <div class="card card-body">
                <h3 class="font-heading font-bold text-brand-black text-sm mb-2">Danger Zone</h3>
                <form method="POST" action="{{ route('property-owner.hotels.destroy', $hotel) }}" x-data @submit.prevent="if(confirm('Delete this property? This action cannot be undone.')) $el.submit()">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger btn-sm w-full"><i class="fas fa-trash"></i> Delete Property</button>
                </form>
            </div>
        </div>
    </div>
</x-pms-layout>
