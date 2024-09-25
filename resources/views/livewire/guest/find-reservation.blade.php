<div>
    <form wire:submit="submit" class="flex gap-1 mb-5">
        <x-form.input-text wire:model="reservation_id" label="Reservation ID" id="reservation_id" />
        <x-primary-button type="submit" class="self-stretch">Find my Reservation</x-primary-button>
    </form>

    {{-- Reservation Details --}}
    @if (!empty($reservation_id))
        @if (!empty($reservation))
            <div class="space-y-5">
                <p class="text-xs">Reservation found!</p>
                
                <div class="p-5 space-y-3 border rounded-lg shadow-lg">
                    <hgroup class="flex justify-between">
                        <h3 class="text-xl font-semibold text-blue-500">{{ $reservation->rid }}</h3>
        
                        <div class="space-x-3">
                            <strong class="text-xs">Status: </strong>
                            <strong class="px-3 py-2 text-xs font-semibold text-white border border-orange-500 rounded-full bg-orange-500/75">{{ $reservation->status }}</strong>
                        </div>
                    </hgroup>
        
                    {{-- Summary --}}
                    <div class="grid border rounded-lg md:grid-cols-2">
                        {{-- Guest Details --}}
                        <div class="px-3 py-2 space-y-2 border-b border-dashed md:border-b-0 md:border-r">
                            <h4 class="text-lg font-semibold">Guest Details</h4>
                            <div class="space-y-1 text-sm">
                                <p class="flex items-center gap-3 capitalize"><span class="material-symbols-outlined">ar_on_you</span><span>{{ $reservation->first_name . " " . $reservation->last_name }}</span></p>
                                <p class="flex items-center gap-3 capitalize"><span class="material-symbols-outlined">cottage</span><span>{{ $reservation->address }}</span></p>
                                <p class="flex items-center gap-3"><span class="material-symbols-outlined">call</span><span>{{ $reservation->phone }}</span></p>
                                <p class="flex items-center gap-3"><span class="material-symbols-outlined">mail</span><span>{{ $reservation->email }}</span></p>
                            </div>
                        </div>

                        {{-- Reservation Details --}}
                        <div class="px-3 py-2 space-y-2">
                            <h4 class="text-lg font-semibold">Reservation Details</h4>
                            <div class="space-y-1 text-sm">
                                <p class="flex items-center gap-3"><span class="material-symbols-outlined">airline_seat_flat</span><span>Overnight</span></p>
                                <p class="flex items-center gap-3"><span class="material-symbols-outlined">acute</span><span>2:00 PM - 12:00 PM</span></p>
                                <p class="flex items-center gap-3"><span class="material-symbols-outlined">face</span><span>{{ $reservation->adult_count }} Adults, {{ $reservation->children_count }} Children</span></p>
                                <p class="flex items-center gap-3"><span class="material-symbols-outlined">door_front</span>
                                    <span class="space-x-1">
                                        @foreach ($selected_rooms as $room)
                                            <span key="{{ $room->id }}" class="inline-block px-2 py-1 font-semibold capitalize rounded-md bg-slate-200">
                                                {{ $room->roomType->name . " " . $room->room_number }}
                                            </span>
                                        @endforeach
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Reservation Breakdown --}}
                    <div class="px-3 my-5 space-y-3">
                        <div class="flex items-center gap-5">
                            <h3 class="text-lg font-semibold">Reservation Breakdown</h3>
                            <x-line class="bg-zinc-800/50" />
                        </div>

                        <div class="gap-5 text-sm md:flex">
                            <p class="flex items-center gap-3"><span class="material-symbols-outlined">calendar_month</span><span><strong>Check in: </strong>{{ date_format(date_create($reservation->date_in),"F j, Y") }}</span></p>
                            <p class="flex items-center gap-3"><span class="material-symbols-outlined">calendar_month</span><span><strong>Check out: </strong>{{ date_format(date_create($reservation->date_out),"F j, Y") }}</span></p>
                        </div>
                    </div>

                    <div class="px-3 py-2 space-y-3 border rounded-lg">
                        <div class="flex justify-between">
                            <p class="font-semibold">Description</p>
                            <p class="font-semibold">Amount</p>
                        </div>
    
                        {{-- Bills to Pay --}}
                        <div class="pt-3 border-t border-dashed">
                            @foreach ($selected_rooms as $room)
                                <div key="{{ $room->id }}" class="flex justify-between px-3 py-1 text-sm transition-all duration-200 ease-in-out rounded-lg hover:bg-slate-100">
                                    <p class="capitalize">{{ $room->roomType->name . " " . $room->room_number }}</p>
                                    <p>{{ $room->rate }}</p>
                                </div>
                            @endforeach
    
                            <div class="flex items-center px-3 py-1">
                                <x-line class="bg-zinc-800/50" />
                            </div>
    
                            @forelse ($selected_amenities as $amenity)
                                <div key="{{ $amenity->id }}" class="flex justify-between px-3 py-1 text-sm transition-all duration-200 ease-in-out rounded-lg hover:bg-slate-100">
                                    <p class="capitalize">{{ $amenity->name }}</p>
                                    <p>{{ $amenity->price }}</p>
                                </div>
                            @empty
                                <div class="px-3 py-1 text-sm text-zinc-800/50">
                                    <p>No selected amenities</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
    
                    <div class="flex justify-end gap-5 px-6">
                        <div class="text-sm text-right">
                            <p class="font-semibold">Sub-Total</p>
                            <p class="">12% VAT</p>
                            <p class="font-semibold text-blue-500">Net Total</p>
                        </div>
                        <div class="text-sm text-right">
                            {{-- <p class="font-semibold">{{ number_format($sub_total, 2) }}</p>
                            <p class="">{{ number_format($vat, 2) }}</p>
                            <p class="font-semibold text-blue-500">{{ number_format($net_total, 2) }}</p> --}}
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="mt-5">
                <p class="text-2xl font-semibold text-red-500">Oh no!</p>
                <p class="">There are no reservation with the ID <strong class="font-semibold text-blue-500">&quot;{{ $reservation_id }}&quot;</strong></p>
            </div>
        @endif
    @endif
</div>