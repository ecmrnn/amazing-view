@props([
    'key' => '',
    'room' => '',
])

<div key="{{ $key }}" class="flex gap-3 border-slate-200 lg:block lg:space-y-2">
    <x-img-lg class="w-full max-w-[200px] lg:max-w-full" src="{{ $room->image_1_path }}" />

    <div class="w-full space-y-2">
        <hgroup>
            <h4 class="font-semibold capitalize text-md">{{ $room->type->name }}: Room {{ $room->room_number }}</h4>
            <p class="text-sm font-semibold">&#8369;{{ $room->rate }} &#47; night</p>
        </hgroup>

        <p class="text-xs text-zinc-800/50">Good for {{ $room->max_capacity }} guests.</p>

        <div class="flex flex-col gap-1 sm:flex-row lg:flex-col xl:flex-row">
            {{-- @if ()
                
            @endif --}}
            <x-primary-button type="button" x-on:click="$wire.addRoom({{ $room->id }})">Book this Room</x-primary-button>
            <x-secondary-button type="button">Details</x-secondary-button>
        </div>
    </div>
</div>