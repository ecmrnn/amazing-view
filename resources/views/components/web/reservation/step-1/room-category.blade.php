@props([
    'room' => '',
    'key' => '',
])

<div key="{{ $key }}" class="flex flex-col gap-3 lg:flex-row">
    <div class="relative w-full lg:max-w-xs">
        <x-img-lg class="w-full" src="{{ $room->image_1_path }}" />
        <div class="absolute px-3 py-2 text-xs font-semibold rounded-lg bg-white/90 backdrop-blur-md top-2 left-2">
            {{--  --}}
        </div>
    </div>

    <div class="w-full">
        <div class="flex items-start justify-between gap-3 mb-3">
            <hgroup>
                <h3 class="text-lg font-semibold capitalize">{{ $room->name }}</h3>
                <p class="text-sm font-semibold"><x-currency />{{ $room->min_rate }} to <x-currency />{{ $room->max_rate }} &#47; night</p>
            </hgroup>
            
            <x-secondary-button 
                x-on:click.prevent="$dispatch('open-modal', 'show-available-rooms')"
                wire:click="getAvailableRooms({{ $room->id }})">
                View Rooms
            </x-secondary-button>
        </div>

        <p class="text-sm first-letter:capitalize">{{ $room->description }}</p>
    </div>
</div>