<div>
    <form wire:submit="submit" class="relative flex gap-1 mb-5">
        <div><x-form.input-search wire:model="reservation_id" label="Reservation ID" id="reservation_id" /></div>
        <x-primary-button type="submit">Find my Reservation</x-primary-button>
        <div class="absolute -bottom-5">
            <x-form.input-error field="reservation_id" />
        </div>
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
                            <x-status type="reservation" :status="$reservation->status" />
                        </div>
                    </hgroup>
        
                    {{-- Summary --}}
                    <div class="grid border rounded-lg md:grid-cols-2">
                        {{-- Guest Details --}}
                        <div class="px-3 py-2 space-y-2 border-b border-dashed md:border-b-0 md:border-r">
                            <h4 class="text-lg font-semibold">Guest Details</h4>
                            <div class="space-y-1 text-sm">
                                <p class="flex items-center gap-3 capitalize">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/></svg>
                                    <span>{{ $reservation->first_name . " " . $reservation->last_name }}</span></p>
                                <p class="flex items-center gap-3 capitalize">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                                    <span>{{ $reservation->address }}</span></p>
                                <p class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-smartphone"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/></svg>
                                    <span>{{ $reservation->phone }}</span></p>
                                <p class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                    <span>{{ $reservation->email }}</span></p>
                            </div>
                        </div>

                        {{-- Reservation Details --}}
                        <div class="px-3 py-2 space-y-2">
                            <h4 class="text-lg font-semibold">Reservation Details</h4>
                            <div class="space-y-1 text-sm">
                                <p class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bed"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
                                    <span>Overnight</span></p>
                                <p class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alarm-clock"><circle cx="12" cy="13" r="8"/><path d="M12 9v4l2 2"/><path d="M5 3 2 6"/><path d="m22 6-3-3"/><path d="M6.38 18.7 4 21"/><path d="M17.64 18.67 20 21"/></svg>
                                    <span>2:00 PM - 12:00 PM</span></p>
                                <p class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-baby"><path d="M9 12h.01"/><path d="M15 12h.01"/><path d="M10 16c.5.3 1.2.5 2 .5s1.5-.2 2-.5"/><path d="M19 6.3a9 9 0 0 1 1.8 3.9 2 2 0 0 1 0 3.6 9 9 0 0 1-17.6 0 2 2 0 0 1 0-3.6A9 9 0 0 1 12 3c2 0 3.5 1.1 3.5 2.5s-.9 2.5-2 2.5c-.8 0-1.5-.4-1.5-1"/></svg>
                                    <span>{{ $reservation->adult_count }} Adults, {{ $reservation->children_count }} Children</span></p>
                                <p class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-door-closed"><path d="M18 20V6a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v14"/><path d="M2 20h20"/><path d="M14 12v.01"/></svg>
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
                            <p class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-arrow-up"><path d="m14 18 4-4 4 4"/><path d="M16 2v4"/><path d="M18 22v-8"/><path d="M21 11.343V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h9"/><path d="M3 10h18"/><path d="M8 2v4"/></svg>
                                <span><strong>Check in: </strong>{{ date_format(date_create($reservation->date_in),"F j, Y") }}</span></p>
                            <p class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-arrow-down"><path d="m14 18 4 4 4-4"/><path d="M16 2v4"/><path d="M18 14v8"/><path d="M21 11.354V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h7.343"/><path d="M3 10h18"/><path d="M8 2v4"/></svg>
                                <span><strong>Check out: </strong>{{ date_format(date_create($reservation->date_out),"F j, Y") }}</span></p>
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
                                    <p>{{ $room->rate }}
                                        @if ($night_count > 1)
                                            {{ " x " . $night_count }}
                                        @endif
                                    </p>
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
                            <p class="font-semibold">{{ number_format($sub_total, 2) }}</p>
                            <p class="">{{ number_format($vat, 2) }}</p>
                            <p class="font-semibold text-blue-500">{{ number_format($net_total, 2) }}</p>
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