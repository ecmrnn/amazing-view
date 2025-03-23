@props([
    'capacity' => '',
    'selectedRooms' => '',
    'selectedAmenities' => '',
    'night_count' => '',
    'step',
])

<aside class="sticky self-start p-5 space-y-5 bg-white rounded-lg shadow-sm top-5">
    <hgroup>
        <h2 class="text-lg font-semibold">Reservation Summary</h2>
        <p class="text-sm">View the summary of your reservavtion here</p>
    </hgroup>

    <div>
        {{-- Date and Time --}}
        <h3 class="font-semibold">Date and Time</h3>
        <div class="grid grid-cols-2 gap-2 mt-3 md:grid-cols-1 lg:grid-cols-2">
            <div class="px-3 py-2 border rounded-lg border-slate-200">
                <p class="text-xs text-zinc-800">Check-in</p>
                <p x-text="date_in === null || date_in === '' ? 'Select a Date' : formatDate(date_in)"
                    class="font-semibold line-clamp-1"></p>
                    <p x-show="reservation_type == 'day tour'" class="text-xs text-zinc-800">From: 8:00 AM</p>
                    <p x-show="reservation_type != 'day tour'" class="text-xs text-zinc-800">From: 2:00 PM</p>
            </div>
            <div class="px-3 py-2 border rounded-lg border-slate-200">
                <p class="text-xs text-zinc-800">Check-out</p>
                <p x-text="date_out === null || date_in === '' ? 'Select a Date' : formatDate(date_out)"
                    class="font-semibold line-clamp-1"></p>
                    <p x-show="reservation_type == 'day tour'" class="text-xs text-zinc-800">To: 6:00 PM</p>
                    <p x-show="reservation_type != 'day tour'" class="text-xs text-zinc-800">To: 12:00 PM</p>
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
                        <div wire:key="{{ $room->id }}" class="relative gap-2 px-3 py-2 border rounded-lg border-slate-200">
                            {{-- Room Details --}}
                            <div>
                                <p class="font-semibold capitalize border-r border-dashed line-clamp-1">{{ $room->roomType->name }}</p>
                                <p class="text-sm">
                                    <span class="uppercase">{{ $room->building->name }}</span>
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
                                <x-currency />{{ $amenity->price }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    @php
        $total = 0;
    @endphp

    @if ($selectedRooms->count() > 0)
        @foreach ($selectedRooms as $room)
            @php
                $total = $total + ($room->rate * $night_count);
            @endphp
        @endforeach
    @endif

    @if ($selectedAmenities->count() > 0)
        @foreach ($selectedAmenities as $amenity)
            @php
                $total = $total + $amenity->price;
            @endphp
        @endforeach
    @endif

    @if ($total > 0)
        <div class="relative flex items-center justify-between gap-2 px-3 py-2 text-sm text-blue-800 border border-blue-500 rounded-lg bg-blue-50">
            <p class="font-semibold">Total</p>

            <p><x-currency />{{ number_format($total, 2) }}</p>
        </div>
    @endif
</aside>
