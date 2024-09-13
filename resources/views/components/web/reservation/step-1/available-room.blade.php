@props(['room' => []])

<div class="grid items-start gap-3 md:grid-cols-4">
    <div class="w-full md:max-w-[250px]">
        <x-img-gallery
            :image_1_path="$room->image_1_path"
            :image_2_path="$room->image_2_path"
            :image_3_path="$room->image_3_path"
            :image_4_path="$room->image_4_path"
        />
    </div>

    <div class="flex-shrink-0 space-y-2">
        <h3 class="font-semibold">{{ $room->room_number }}</h3>
        <p class="text-xs text-zinc-800/50">Here is everything you need to know about this room.</p>

        <div>
            <p class="text-xs"><strong>Capacity</strong>: {{ $room->min_capacity }} to {{ $room->max_capacity }} person</p>
            <p class="text-xs"><strong>Rate</strong>: <x-currency />{{ $room->rate }} / night</p>
        </div>
    </div>

    <div class="w-full space-y-2">
        <h3 class="font-semibold">Amenities</h3>
        <p class="text-xs text-zinc-800/50">This room includes the following amenities.</p>
        <ul class="grid grid-cols-2 text-xs">
            @forelse ($room->amenities as $amenity)
                <li class="flex items-center gap-2 capitalize line-clamp-1"><x-line class="bg-zinc-800/50" />{{ $amenity->name }}</li>                
            @empty
                <li class="text-xs text-zinc-800/50">This room has no amenities.</li>
            @endforelse
        </ul>
    </div>

    <div class="flex md:justify-end">
        <x-secondary-button type="button" wire:click='addRoom(({{ $room->id }}))' class="flex-shrink-0">Book this Room</x-secondary-button>
    </div>
</div>
