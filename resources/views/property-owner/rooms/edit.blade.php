<x-pms-layout pageTitle="Edit Room Type" :pageSubtitle="$room->name . ' — ' . $hotel->name">
<div class="max-w-3xl">
    <form method="POST" action="{{ route('property-owner.hotels.rooms.update', [$hotel, $room]) }}" class="space-y-6">
        @csrf @method('PUT')
        <div class="card card-body space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="form-group md:col-span-2">
                    <label class="form-label">Room Name *</label>
                    <input type="text" name="name" class="form-input-styled" value="{{ old('name', $room->name) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Size (sqm)</label>
                    <input type="number" name="size_sqm" class="form-input-styled" value="{{ old('size_sqm', $room->size_sqm) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Floor Level</label>
                    <input type="text" name="floor_level" class="form-input-styled" value="{{ old('floor_level', $room->floor_level) }}">
                </div>
                <div class="form-group"><label class="form-label">Max Adults *</label><input type="number" name="max_adults" class="form-input-styled" value="{{ $room->max_adults }}" required></div>
                <div class="form-group"><label class="form-label">Max Children</label><input type="number" name="max_children" class="form-input-styled" value="{{ $room->max_children }}"></div>
                <div class="form-group"><label class="form-label">Max Infants</label><input type="number" name="max_infants" class="form-input-styled" value="{{ $room->max_infants }}"></div>
                <div class="form-group"><label class="form-label">Base Price *</label><input type="number" name="base_price_per_night" step="0.01" class="form-input-styled" value="{{ $room->base_price_per_night }}" required></div>
                <div class="form-group"><label class="form-label">Inventory *</label><input type="number" name="inventory_count" class="form-input-styled" value="{{ $room->inventory_count }}" required></div>
            </div>
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('property-owner.hotels.rooms.index', $hotel) }}" class="btn-ghost">Cancel</a>
            <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Save Changes</button>
        </div>
    </form>
</div>
</x-pms-layout>
