@props([
    'capacity' => '',
    'selectedRooms' => '',
    'selectedAmenities' => '',
    'step',
])

<aside class="sticky self-start p-5 space-y-5 bg-white rounded-lg shadow-sm top-5">
    <h2 class="text-lg font-semibold">Reservation Summary</h2>

    <div>
        {{-- Date and Time --}}
        <h3 class="font-semibold">Date and Time</h3>
        <div class="grid grid-cols-2 gap-2 mt-3 md:grid-cols-1 lg:grid-cols-2">
            <div class="px-3 py-2 border rounded-lg border-slate-200">
                <p class="text-xs text-zinc-800">Check-in</p>
                <p x-text="date_in === null || date_in === '' ? 'Select a Date' : formatDate(date_in)"
                    class="font-semibold line-clamp-1"></p>
                    <p x-show="reservation_type == 'day_tour'" class="text-xs text-zinc-800">From: 8:00 AM</p>
                    <p x-show="reservation_type != 'day_tour'" class="text-xs text-zinc-800">From: 2:00 PM</p>
            </div>
            <div class="px-3 py-2 border rounded-lg border-slate-200">
                <p class="text-xs text-zinc-800">Check-out</p>
                <p x-text="date_out === null || date_in === '' ? 'Select a Date' : formatDate(date_out)"
                    class="font-semibold line-clamp-1"></p>
                    <p x-show="reservation_type == 'day_tour'" class="text-xs text-zinc-800">To: 6:00 PM</p>
                    <p x-show="reservation_type != 'day_tour'" class="text-xs text-zinc-800">To: 12:00 PM</p>
            </div>
        </div>

        {{-- Number of Guests --}}
        <div class="pt-2">
            <p class="flex justify-between text-sm"><span>Adults:</span> <span x-text="adult_count"></span> </p>
            <p class="flex justify-between text-sm"><span>Children:</span> <span x-text="children_count"></span> </p>
        </div>

        {{-- Selected Rooms --}}
        @if (count($selectedRooms) > 0)
            <div class="mt-3 space-y-3">
                <hgroup>
                    <h3 class="font-semibold">
                        @if (count($selectedRooms) == 1)
                            Room
                        @else
                            Rooms
                        @endif
                    </h3>
                </hgroup>

                {{-- Note message for capacity --}}
                <template x-if="capacity < Number(adult_count) + Number(children_count)">
                    <p class="px-3 py-2 text-sm text-red-500 border border-red-500 rounded-lg bg-red-50"><span class="font-semibold">Note:</span> The capacity of the reserved room&#47s cannot accommodate the number of guests. You might need to reserve another room.</p>
                </template>

                <div class="space-y-2">
                    @foreach ($selectedRooms as $room)
                        <div wire:key="{{ $room->id }}" class="relative flex items-center gap-2 px-3 py-2 border rounded-lg border-slate-200">
                            {{-- Room Details --}}
                            <div>
                                <p class="font-semibold capitalize border-r border-dashed line-clamp-1">{{ $room->roomType->name }}</p>
                                <p class="text-sm">
                                    <span class="uppercase">{{ $room->building->prefix }}</span>
                                    {{ $room->room_number }}: &#8369;{{ $room->rate }} &#47; night</p>
                                <p class="text-xs text-zinc-800">Good for {{ $room->max_capacity }} guests.</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if (count($selectedAmenities) > 0)
            <div class="mt-3 space-y-3">
                <h3 class="font-semibold">Added to your stay</h3>

                <div>
                    @foreach ($selectedAmenities as $amenity)
                        <div key="{{ $amenity->id }}" class="flex justify-between text-sm capitalize">
                            <p>{{ $amenity->name }}</p>
                            <p>
                                <x-currency /> <span>{{ $amenity->price }}</span>
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</aside>
