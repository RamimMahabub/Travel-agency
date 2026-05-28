<x-pms-layout pageTitle="Availability Calendar" :pageSubtitle="$hotel->name">

<div class="space-y-5">
    {{-- Month Navigation --}}
    <div class="card card-body flex items-center justify-between">
        <a href="{{ route('property-owner.availability.index', ['hotel' => $hotel, 'month' => $startDate->copy()->subMonth()->format('Y-m')]) }}" class="btn-ghost btn-sm">
            <i class="fas fa-chevron-left"></i> Previous
        </a>
        <h2 class="font-heading font-bold text-brand-black text-lg">{{ $startDate->format('F Y') }}</h2>
        <a href="{{ route('property-owner.availability.index', ['hotel' => $hotel, 'month' => $startDate->copy()->addMonth()->format('Y-m')]) }}" class="btn-ghost btn-sm">
            Next <i class="fas fa-chevron-right"></i>
        </a>
    </div>

    <div class="flex gap-5">
        {{-- Calendar Grid --}}
        <div class="flex-1 overflow-x-auto">
            <div class="card overflow-hidden min-w-[800px]">
                <table class="w-full text-xs border-collapse">
                    <thead>
                        <tr>
                            <th class="sticky left-0 bg-brand-surface px-3 py-2 text-left text-brand-black font-semibold border-b border-r border-brand-border z-10 min-w-[140px]">Room Type</th>
                            @foreach($dates as $date)
                                <th class="px-1 py-2 text-center border-b border-brand-border font-medium {{ in_array($date->dayOfWeek, [5, 6]) ? 'bg-yellow-50' : 'bg-brand-surface' }}">
                                    <div class="text-[10px] text-brand-muted">{{ $date->format('D') }}</div>
                                    <div class="{{ $date->isToday() ? 'text-brand-primary font-bold' : 'text-brand-black' }}">{{ $date->format('d') }}</div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roomTypes as $roomType)
                            <tr>
                                <td class="sticky left-0 bg-white px-3 py-2 border-b border-r border-brand-border z-10">
                                    <p class="font-medium text-brand-black">{{ $roomType->name }}</p>
                                    <p class="text-[10px] text-brand-muted">{{ $roomType->inventory_count }} rooms · ${{ number_format($roomType->base_price_per_night, 0) }}/night</p>
                                </td>
                                @foreach($dates as $date)
                                    @php
                                        $dateStr = $date->format('Y-m-d');
                                        $cell = $calendarData[$roomType->id][$dateStr] ?? null;
                                        $available = $cell ? $cell['available'] : $roomType->inventory_count;
                                        $total = $cell ? $cell['total'] : $roomType->inventory_count;
                                        $price = $cell ? $cell['price'] : $roomType->base_price_per_night;
                                        $isClosed = $cell ? $cell['is_closed'] : false;
                                        $isWeekend = $cell ? $cell['is_weekend'] : in_array($date->dayOfWeek, [5, 6]);
                                    @endphp
                                    <td class="avail-cell {{ $isClosed ? 'blocked' : ($available <= 0 ? 'sold-out' : ($isWeekend ? 'weekend' : '')) }}" title="{{ $dateStr }}">
                                        @if(!$isClosed)
                                            <div class="avail-count">{{ $available }}/{{ $total }}</div>
                                            <div class="avail-price">${{ number_format($price, 0) }}</div>
                                        @else
                                            <div class="text-[10px]">Closed</div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Legend --}}
            <div class="flex items-center gap-4 mt-3 text-xs text-brand-muted">
                <span><span class="inline-block w-4 h-3 bg-white border border-brand-border rounded mr-1"></span> Available</span>
                <span><span class="inline-block w-4 h-3 bg-yellow-50 border border-brand-border rounded mr-1"></span> Weekend</span>
                <span><span class="inline-block w-4 h-3 bg-brand-primary rounded mr-1"></span> Sold Out</span>
                <span><span class="inline-block w-4 h-3 rounded mr-1" style="background: repeating-linear-gradient(45deg,#F5F5F5,#F5F5F5 2px,#E0E0E0 2px,#E0E0E0 4px)"></span> Blocked</span>
            </div>
        </div>

        {{-- Bulk Editor Panel --}}
        <div class="w-80 flex-shrink-0 hidden xl:block">
            <div class="card card-body sticky top-24">
                <h3 class="font-heading font-bold text-brand-black text-sm mb-4"><i class="fas fa-edit text-brand-primary mr-2"></i>Bulk Update</h3>

                <form method="POST" action="{{ route('property-owner.availability.bulk-update', $hotel) }}" class="space-y-4">
                    @csrf

                    <div class="form-group">
                        <label class="form-label text-xs">Date Range</label>
                        <input type="date" name="start_date" class="form-input-styled text-sm mb-1" required>
                        <input type="date" name="end_date" class="form-input-styled text-sm" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label text-xs">Apply to Room Types</label>
                        @foreach($roomTypes as $rt)
                            <label class="flex items-center gap-2 text-sm mb-1">
                                <input type="checkbox" name="room_type_ids[]" value="{{ $rt->id }}" class="rounded border-brand-border text-brand-primary" checked>
                                {{ $rt->name }}
                            </label>
                        @endforeach
                    </div>

                    <div class="form-group">
                        <label class="form-label text-xs">Action</label>
                        <select name="action" class="form-input-styled text-sm" x-data="{ action: 'set_price' }" x-model="action" @change="action = $event.target.value">
                            <option value="set_price">Set Custom Price</option>
                            <option value="block">Block Dates</option>
                            <option value="unblock">Unblock Dates</option>
                            <option value="close">Close Dates</option>
                            <option value="open">Open Dates</option>
                            <option value="set_min_stay">Set Min Stay</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label text-xs">Value</label>
                        <input type="number" name="value" class="form-input-styled text-sm" step="0.01" placeholder="Enter value">
                    </div>

                    <button type="submit" class="btn-primary w-full"><i class="fas fa-save"></i> Apply Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
</x-pms-layout>
