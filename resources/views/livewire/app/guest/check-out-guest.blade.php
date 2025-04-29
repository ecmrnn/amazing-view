<div>
    <form wire:submit='submit' class="relative w-full max-w-screen-lg mx-auto space-y-5 rounded-lg">
        <div class="flex items-center justify-between p-5 bg-white border rounded-lg border-slate-200">
            <div class="flex items-center gap-3 sm:gap-5">
                <x-tooltip text="Back" dir="bottom">
                    <a x-ref="content" href="{{ route('app.guests.index' )}}" wire:navigate>
                        <x-icon-button>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                        </x-icon-button>
                    </a>
                </x-tooltip>
        
                <div>
                    <h2 class="text-lg font-semibold">Check-out Guest</h2>
                    <p class="max-w-sm text-xs">Confirm and review guest&apos;s reservation</p>
                </div>
            </div>
        </div>
        
        @if ($step <= 3)
            {{-- Steps --}}
            <div x-ref="form" class="flex flex-col items-start gap-5 mb-10 sm:flex-row">
                <x-web.reservation.steps step="1" currentStep="{{ $step }}" name="Reservation Details" />
                <x-web.reservation.steps step="2" currentStep="{{ $step }}" name="Room Confirmation" />
                <x-web.reservation.steps step="3" currentStep="{{ $step }}" name="Payment Settlement" />
            </div>
        @endif

        @switch($step)
            @case(1)
                {{-- Reservation Details --}}
                <div class="space-y-5">
                    <div class="p-5 bg-white border rounded-lg border-slate-200">
                        <div class="space-y-5">
                            <hgroup>
                                <h2 class="font-semibold">{{ $reservation->rid }}</h2>
                                <p class="text-xs">Reservation ID</p>
                            </hgroup>
        
                            {{-- Reservation Details --}}
                            <div class="grid grid-cols-1 gap-3 p-5 border rounded-md sm:grid-cols-2 border-slate-200">
                                {{-- Check-in date --}}
                                <div class="flex items-center gap-3">
                                    <x-icon>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-arrow-up-icon lucide-calendar-arrow-up"><path d="m14 18 4-4 4 4"/><path d="M16 2v4"/><path d="M18 22v-8"/><path d="M21 11.343V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h9"/><path d="M3 10h18"/><path d="M8 2v4"/></svg>
                                    </x-icon>
                                    <div>
                                        <p class="text-sm font-semibold">{{ date_format(date_create($reservation->date_in), 'F j, Y') . ' at ' . date_format(date_create($reservation->time_in), 'g:i A') }}</p>
                                        <p class="text-xs">Check-in date and time</p>
                                    </div>
                                </div>
                                {{-- Check-in date --}}
                                <div class="flex items-center gap-3">
                                    <x-icon>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-arrow-down-icon lucide-calendar-arrow-down"><path d="m14 18 4 4 4-4"/><path d="M16 2v4"/><path d="M18 14v8"/><path d="M21 11.354V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h7.343"/><path d="M3 10h18"/><path d="M8 2v4"/></svg>
                                    </x-icon>
                                    <div>
                                        <p class="text-sm font-semibold">{{ date_format(date_create($reservation->date_out), 'F j, Y') . ' at ' . date_format(date_create($reservation->time_out), 'g:i A') }}</p>
                                        <p class="text-xs">Check-out date and time</p>
                                    </div>
                                </div>
                                {{-- Total number of guests --}}
                                <div class="flex items-center gap-3">
                                    <x-icon>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    </x-icon>
                                    <div>
                                        <p class="text-sm font-semibold">
                                            {{ $reservation->adult_count > 1 ? $reservation->adult_count . ' Adults' : $reservation->adult_count . ' Adult' }}
                                            @if ($reservation->children_count > 0)
                                                {{ ' & ' }}
                                                {{ $reservation->children_count > 1 ?  $reservation->children_count . ' Children' : $reservation->children_count . ' Child' }}
                                            @endif    
                                        </p>
                                        <p class="text-xs">Total number of guests</p>
                                    </div>
                                </div>
                                @if ($reservation->senior_count > 0 || $reservation->pwd_count > 0)
                                    {{-- Total number of seniors and pwds --}}
                                    <div class="flex items-center gap-3">
                                        <x-icon>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-accessibility-icon lucide-accessibility"><circle cx="16" cy="4" r="1"/><path d="m18 19 1-7-6 1"/><path d="m5 8 3-3 5.5 3-2.36 3.5"/><path d="M4.24 14.5a5 5 0 0 0 6.88 6"/><path d="M13.76 17.5a5 5 0 0 0-6.88-6"/></svg>
                                        </x-icon>
                                        <div>
                                            <p class="text-sm font-semibold">
                                                @if ($reservation->senior_count > 0)
                                                    {{ $reservation->senior_count > 1 ?  $reservation->senior_count . ' Seniors' : $reservation->senior_count . ' Senior' }}
                                                @endif
                                                @if ($reservation->pwd_count > 0)
                                                    @if ($reservation->senior_count > 0)
                                                        {{ ' & ' }}
                                                    @endif
                                                    {{ $reservation->pwd_count > 1 ?  $reservation->pwd_count . ' PWDs' : $reservation->pwd_count . ' PWD' }}
                                                @endif   
                                            </p>
                                            <p class="text-xs">Total number of guests</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
                        <hgroup>
                            <h2 class="font-semibold">Reservation Breakdown</h2>
                            <p class="text-xs">Review reservation bills</p>
                        </hgroup>
                        <livewire:app.reservation-breakdown :reservation="$reservation" />
                    </div>
        
                    <div class="flex justify-end">
                        <x-primary-button type="submit" x-on:click="() => { $nextTick(() => { $refs.form.scrollIntoView({ behavior: 'smooth' }); }); }">Continue</x-primary-button>
                    </div>
                </div>
                @break
            @case(2)
                {{-- Room Confirmation --}}
                <div class="space-y-5">
                    <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
                        <div class="flex items-start justify-between">
                            <hgroup>
                                <h2 class="font-semibold">Rooms Reserved</h2>
                                <p class="text-xs">Select a room to checkout</p>
                            </hgroup>
                            @if ($selected_rooms->count() == $checked_in_rooms)
                                <x-secondary-button type="button" wire:click='deselectAll'>Deselect all</x-secondary-button>
                            @else
                                <x-primary-button type="button" wire:click='selectAll'>Select all</x-primary-button>
                            @endif
                        </div>
                        <x-form.input-error field="selected_rooms" />
                        <div class="space-y-5">

                            {{-- Desktop View --}}
                            <div class="hidden gap-5 sm:grid sm:grid-cols-2 lg:grid-cols-3">
                                @foreach ($reservation->rooms as $room)
                                    @php
                                        $checked = (bool) $selected_rooms->contains('id', $room->id);
                                    @endphp
                                    <div wire:key='grid-{{ $room->id . $unique_id }}'
                                        @class([
                                            'p-5 space-y-5 border rounded-md border-slate-200',
                                            'bg-slate-50' => $room->pivot->status != App\Enums\ReservationStatus::CHECKED_IN->value
                                        ])>
                                        <x-form.input-checkbox
                                            x-bind:checked="{{ $checked }}"
                                            x-bind:disabled="{{ $room->pivot->status != App\Enums\ReservationStatus::CHECKED_IN->value }}" id="room-{{ $room->id }}"
                                            label="Check-out this room" wire:click='toggleRoom({{ $room->id }})'
                                        />
        
                                        <x-img src="{{ $room->image_1_path }}"
                                            @class(['opacity-50 grayscale' => $room->pivot->status != App\Enums\ReservationStatus::CHECKED_IN->value])
                                        />
                                        
                                        <div class="flex items-start justify-between">
                                            <hgroup>
                                                <h3 class="font-semibold">{{ $room->room_number }}</h3>
                                                <p class="text-xs">Rate: <x-currency />{{ number_format($room->rate, 2) }}</p>
                                            </hgroup>
                                            <x-status type="reservation" status="{{ $room->pivot->status }}" />
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            {{-- Mobile View Gallery --}}
                            <div class="space-y-5 sm:hidden">
                                <div class="p-5 space-y-5 border rounded-md border-slate-200">
                                    @foreach ($reservation->rooms as $key => $room)
                                        @if ($key == $gallery_index)
                                            <div wire:key='gallery-{{ $room->id . $unique_id}}'>
                                                <div class="grid gap-5 md:grid-cols-3">
                                                    <div class="md:col-span-3">
                                                        <x-form.input-checkbox
                                                            x-bind:checked="{{ $selected_rooms->contains('id', $room->id) }}"
                                                            x-bind:disabled="{{ $room->pivot->status != App\Enums\ReservationStatus::CHECKED_IN->value }}" id="gallery-{{ $room->id }}"
                                                            label="Check-out this room" wire:click='toggleRoom({{ $room->id }})'
                                                        />
                                                    </div>
        
                                                    <div class="grid gap-5 md:col-span-2 md:grid-cols-2">
                                                        <img src="{{ asset('storage/' . $room->image_1_path) }}" alt="room" class="object-cover object-center rounded-md aspect-video">
        
                                                        <div class="flex items-start justify-between">
                                                            <hgroup>
                                                                <h3 class="font-semibold">{{ $room->room_number }}</h3>
                                                                <p class="text-xs">Rate: <x-currency />{{ number_format($room->rate, 2) }}</p>
                                                            </hgroup>
                                                            <x-status type="reservation" status="{{ $room->pivot->status }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                {{-- Gallery navigation --}}
                                <div class="flex items-center justify-between gap-10">
                                    <x-tooltip text="Previous" dir="top">
                                        <x-icon-button type="button" x-ref="content" wire:click='previousRoom'>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left"><path d="m15 18-6-6 6-6"/></svg>
                                        </x-icon-button>
                                    </x-tooltip>
        
                                    <div class="flex gap-1">
                                        @foreach ($reservation->rooms as $key => $room)
                                            <button wire:key='{{ $key }}' type="button"
                                                wire:click='jumpRoom({{ $key }})'
                                                @class([
                                                    'inline-block w-2 p-1 rounded-full aspect-square',
                                                    'bg-blue-500' => $key == $gallery_index,
                                                    'bg-slate-200' => $key != $gallery_index,
                                                    ])>
                                            </button>
                                        @endforeach
                                    </div>
                                    <x-tooltip text="Next" dir="top">
                                        <x-icon-button type="button" x-ref="content" wire:click='nextRoom'>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right"><path d="m9 18 6-6-6-6"/></svg>
                                        </x-icon-button>
                                    </x-tooltip>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-5 bg-white border rounded-lg border-slate-200">
                        <livewire:app.invoice.add-item :invoice="$reservation->invoice->id" />
                    </div>
                    <div class="flex justify-end gap-1">
                        <x-secondary-button x-on:click="() => { $nextTick(() => { $refs.form.scrollIntoView({ behavior: 'smooth' }); }); }" type="button" x-on:click="$wire.set('step', 1)">Back</x-secondary-button>
                        <x-primary-button x-on:click="() => { $nextTick(() => { $refs.form.scrollIntoView({ behavior: 'smooth' }); }); }" type="submit">Continue</x-primary-button>
                    </div>
                </div>
                @break
            @case(3)
                <div class="space-y-5">
                    <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
                        <div class="flex items-start justify-between">
                            <hgroup>
                                <h2 class="font-semibold">Payment Settlement</h2>
                                <p class="text-xs">Settle payments here</p>
                            </hgroup>
                            <button type="button" class="text-xs font-semibold text-blue-500" x-on:click="$dispatch('open-modal', 'add-payment-modal')">Add Payment</button>
                        </div>
                        {{-- Remaining Balance --}}
                        @if ((int) $reservation->invoice->balance >= 0)
                            <x-app.card
                                label="Remaining Balance"
                                :hasLink="false"
                                >
                                <x-slot:data>
                                    <x-currency />{{ number_format(ceil($reservation->invoice->balance), 2) }}
                                </x-slot:data>
                                <x-slot:icon>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wallet"><path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/></svg>
                                </x-slot:icon>
                            </x-app.card>
                        @else
                            <x-app.card
                                label="Refundable Amount"
                                :hasLink="false"
                                >
                                <x-slot:data>
                                    <x-currency />{{ number_format(abs(ceil($reservation->invoice->balance)), 2) }}
                                </x-slot:data>
                                <x-slot:icon>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-banknote-arrow-down-icon lucide-banknote-arrow-down"><path d="M12 18H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5"/><path d="m16 19 3 3 3-3"/><path d="M18 12h.01"/><path d="M19 16v6"/><path d="M6 12h.01"/><circle cx="12" cy="12" r="2"/></svg>
                                </x-slot:icon>
                            </x-app.card>
                        @endif
                        
                        <livewire:tables.invoice-payment-table :invoice="$reservation->invoice" />
                    </div>
                    
                    <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
                        <hgroup>
                            <h2 class="font-semibold">Rooms to be Checked-out</h2>
                            <p class="text-xs">Confirm selected rooms</p>
                        </hgroup>
        
                        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($selected_rooms as $room)
                                <div wire:key='selected-{{ $room->id }}' class="p-5 space-y-5 border rounded-md border-slate-200">
                                    <x-img src="{{ $room->image_1_path }}" />
                                    <div class="flex items-start justify-between gap-5">
                                        <hgroup>
                                            <h3 class="font-semibold">{{ $room->room_number }}</h3>
                                            <p class="text-xs">Rate: <x-currency />{{ number_format($room->rate, 2) }}</p>
                                        </hgroup>

                                        <di class="flex">
                                            @if ($room->amenities->count() > 0)
                                                <x-tooltip text="Amenities" dir="top">
                                                    <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'show-amenity-modal-{{ $room->id }}')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-concierge-bell"><path d="M3 20a1 1 0 0 1-1-1v-1a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v1a1 1 0 0 1-1 1Z"/><path d="M20 16a8 8 0 1 0-16 0"/><path d="M12 4v4"/><path d="M10 4h4"/></svg>
                                                    </x-icon-button>
                                                </x-tooltip>
                                            @endif
                                            @if ($room->itemsForInvoice($reservation->invoice->id)->count() > 0)
                                                <x-tooltip text="Others" dir="top">
                                                    <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'show-items-modal-{{ $room->id }}')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-archive"><rect width="20" height="5" x="2" y="3" rx="1"/><path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"/><path d="M10 12h4"/></svg>
                                                    </x-icon-button>
                                                </x-tooltip>
                                            @endif
                                        </di>
                                    </div>

                                    <x-modal.full name='show-amenity-modal-{{ $room->id }}' maxWidth='md'>
                                        <div class="p-5 space-y-5">
                                            <hgroup>
                                                <h2 class="text-lg font-semibold">Room Amenities</h2>
                                                <p class="text-xs">Here are the availed amenities for room {{ $room->room_number }}</p>
                                            </hgroup>
                                            <div class="overflow-hidden bg-white border rounded-md border-slate-200">
                                                <div class="grid grid-cols-4 px-5 py-3 text-sm font-semibold text-zinc-800/60 bg-slate-50">
                                                    <p>Name</p>
                                                    <p class="text-center">Qty</p>
                                                    <p class="text-right">Price</p>
                                                    <p class="text-right">Total</p>
                                                </div>
        
                                                @foreach ($room->amenitiesForReservation($reservation->id)->get() as $amenity)
                                                    <div class="grid grid-cols-4 px-5 py-3 text-sm border-t border-slate-200 first:border-t-0">
                                                        <p>{{ $amenity->name }}</p>
                                                        <p class="text-center">{{ $amenity->pivot->quantity }}</p>
                                                        <p class="text-right"><x-currency />{{ number_format($amenity->pivot->price, 2) }}</p>
                                                        <p class="text-right"><x-currency />{{ number_format($amenity->pivot->price * $amenity->pivot->quantity, 2) }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </x-modal.full>

                                    <x-modal.full name='show-items-modal-{{ $room->id }}' maxWidth='md'>
                                        <div class="p-5 space-y-5">
                                            <hgroup>
                                                <h2 class="text-lg font-semibold">Room Items</h2>
                                                <p class="text-xs">Here are the additional items for room {{ $room->room_number }}</p>
                                            </hgroup>
                                            <div class="overflow-hidden bg-white border rounded-md border-slate-200">
                                                <div class="grid grid-cols-4 px-5 py-3 text-sm font-semibold text-zinc-800/60 bg-slate-50">
                                                    <p>Name</p>
                                                    <p class="text-center">Qty</p>
                                                    <p class="text-right">Price</p>
                                                    <p class="text-right">Total</p>
                                                </div>
        
                                                @foreach ($room->itemsForInvoice($reservation->invoice->id)->get() as $item)
                                                    <div class="grid grid-cols-4 px-5 py-3 text-sm border-t border-slate-200 first:border-t-0">
                                                        <p>{{ $item->name }}</p>
                                                        <p class="text-center">{{ $item->quantity }}</p>
                                                        <p class="text-right"><x-currency />{{ number_format($item->price, 2) }}</p>
                                                        <p class="text-right"><x-currency />{{ number_format($item->total, 2) }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </x-modal.full>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
                        <hgroup>
                            <h2 class="font-semibold">Reservation Breakdown</h2>
                            <p class="text-xs">Review reservation bills</p>
                        </hgroup>
                        <livewire:app.reservation-breakdown :reservation="$reservation" />
                    </div>
                    
                    <div class="flex justify-end gap-1">
                        <x-secondary-button x-on:click="() => { $nextTick(() => { $refs.form.scrollIntoView({ behavior: 'smooth' }); }); }" type="button" x-on:click="$wire.set('step', 2)">Back</x-secondary-button>
                        <x-primary-button x-on:click="() => { $nextTick(() => { $refs.form.scrollIntoView({ behavior: 'smooth' }); }); }" type="submit">Check Out</x-primary-button>
                    </div>
                </div>
                @break
            @default
                <div class="p-5 space-y-3 text-center text-green-800 border border-green-500 rounded-lg bg-green-50">
                    <hgroup>
                        <p class="text-2xl">🎉</p>
                        <h2 class="text-lg font-semibold">Success!</h2>
                        @if ($selected_rooms->count() > 1)
                            <p class="text-xs">Rooms checked-out successfully!</p>
                        @else
                            <p class="text-xs">Room checked-out successfully!</p>
                        @endif
                    </hgroup>

                    <div class="mx-auto">
                        <a href="{{ route('app.guests.index') }}" wire:navigate>
                            <x-primary-button type="button">Back to Guests</x-primary-button>
                        </a>
                    </div>
                </div>
        @endswitch
    </form>

    <x-modal.full name='add-payment-modal' maxWidth='sm'>
        <livewire:app.invoice.create-payment :invoice="$reservation->invoice" />
    </x-modal.full>

    <x-modal.full name='show-checkout-confirmation' maxWidth='sm'>
        <form wire:submit='checkout' class="p-5 space-y-5" x-on:checked-out.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold">Check-out Confirmation</h2>
                <p class="text-xs">You are about to check-out this rooms, are you sure?</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for='password-checkout'>Enter your password</x-form.input-label>
                <x-form.input-text type="password" wire:model.live='password' id="password-checkout" name="password-checkout" label="Password" />
                <x-form.input-error field="password" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='checkout'>Processing checkout, please wait</x-loading>

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button type="submit">Check-out</x-primary-button>
            </div>
        </form>
    </x-modal.full>
</div>
