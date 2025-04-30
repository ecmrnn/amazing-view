<div x-data="{ step: @entangle('step'), editing: false }">
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
                    <h2 class="text-lg font-semibold">Check-in Guest</h2>
                    <p class="max-w-sm text-xs">Confirm and review guest&apos;s reservation</p>
                </div>
            </div>
        </div>

        @if ($step <= 3)
            {{-- Steps --}}
            <div x-ref="form" class="flex flex-col items-start gap-5 mb-10 sm:flex-row">
                <x-web.reservation.steps step="1" currentStep="{{ $step }}" name="Reservation Details" />
                @if (($reservation->senior_count > 0 || $reservation->pwd_count > 0) && $reservation->rooms->count() > 1)
                    <x-web.reservation.steps step="2" currentStep="{{ $step }}" name="Senior and PWD Rooms" />
                    <x-web.reservation.steps step="3" currentStep="{{ $step }}" name="Payment Settlement" />
                @else
                    <x-web.reservation.steps step="2" currentStep="{{ $step - 1 }}" name="Payment Settlement" />
                @endif
            </div>
        @endif

        <div x-show="step == 1" x-cloak class="space-y-5">
            <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
                <div class="flex items-start justify-between">
                    <hgroup>
                        <h2 class="font-semibold">{{ $reservation->rid }}</h2>
                        <p class="text-xs">Reservation ID</p>
                    </hgroup>

                    <a href="{{ route('app.reservations.edit', ['reservation' => $reservation->rid]) }}" wire:navigate class="text-xs font-semibold text-blue-500">
                        Edit Reservation
                    </a>
                </div>

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

                <div class="space-y-5">
                    <hgroup>
                        <h2 class='font-semibold'>Rooms Reserved</h2>
                        <p class='text-xs'>Verify the rooms selected</p>
                    </hgroup>
        
                    <div class="grid gap-5 sm:grid-cols-2">
                        @forelse ($reservation->rooms as $room)
                            <div class="px-3 py-2 border rounded-md border-slate-200">
                                <p class="font-semibold">{{ $room->room_number }}</p>
                                <p class="text-xs">Room Rate: <x-currency />{{ number_format($room->pivot->rate, 2) }}/night</p>
                                <p class="text-xs">Good for {{ $room->max_capacity }} guests</p>
                            </div>
                        @empty
                            <div class="py-5 text-sm font-semibold text-center border rounded-lg sm:col-span-2">
                                No rooms yet...
                            </div>
                        @endforelse
                    </div>
                </div>

                @if ($reservation->discounts->count() > 0)
                    <div class="space-y-5">
                        {{-- Discount Attachments --}}
                        <hgroup>
                            <h2 class='font-semibold'>Discount Attachments</h2>
                            <p class='text-xs'>Review uploaded IDs here</p>
                        </hgroup>

                        <div class="grid grid-cols-2 gap-5 sm:grid-cols-4">
                            @foreach ($reservation->discounts->first()->attachments as $attachment)
                                <div>
                                    <x-img src="{{ $attachment->image }}" :zoomable="true" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex justify-end">
                @if (($reservation->senior_count > 0 || $reservation->pwd_count > 0) && $reservation->rooms->count() > 1)
                    <x-primary-button type="button" x-on:click="() => { $nextTick(() => { $refs.form.scrollIntoView({ behavior: 'smooth' }); }); $wire.set('step', 2)}">Continue</x-primary-button>
                @else
                    <x-primary-button type="button" x-on:click="() => { $nextTick(() => { $refs.form.scrollIntoView({ behavior: 'smooth' }); }); $wire.set('step', 3)}">Continue</x-primary-button>
                @endif
            </div>
        </div>

        <div x-show="step == 2" x-cloak class="space-y-5">
            <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
                <hgroup>
                    <h2 class='font-semibold'>Guest Summary</h2>
                    <p class='text-xs'></p>
                </hgroup>   
                
                <div class="grid gap-5 p-5 border rounded-md md:grid-cols-3 border-slate-200">
                    <div>
                        <p class="px-3 py-2 text-sm font-semibold text-white bg-blue-500 rounded-md">{{ $reservation->adult_count + $reservation->children_count }} Guests to assign</p>
                    </div>
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
                </div>
            </div>

            <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
                <hgroup>
                    <h2 class='font-semibold'>Room Assignment</h2>
                    <p class='text-xs'>This reservation has a senior or PWD, select which room they would stay.</p>
                </hgroup>

                <x-table.table headerCount="5">
                    <x-slot:headers>
                        <p>Room No.</p>
                        <p>Max Capacity</p>
                        <p>Rate</p>
                        <div class="flex col-span-2">
                            <p class="w-full">Regular Guests</p>
                            <p class="w-full">Seniors and PWDs</p>
                            <div class="h-0 opacity-0">
                                <x-icon-button class="w-max"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round-pen-icon lucide-user-round-pen"><path d="M2 21a8 8 0 0 1 10.821-7.487"/><path d="M21.378 16.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"/><circle cx="10" cy="8" r="5"/></svg></x-icon-button>
                            </div>
                        </div>
                    </x-slot:headers>

                    <div id='{{ $collection_id }}'>
                        @foreach ($room_guests as $room)
                            <div x-data="{ 
                                    id: @js($room['id']),
                                    guests: @js($room['guests']),
                                    disc_guests: @js($room['disc_guests']),
                                    capacity: @js($room['max_capacity']),
                                    can_edit: false,
                                }"
                                x-on:guest-saved.window="can_edit = false; editing = false"
                                class="grid grid-cols-5 px-5 py-3 text-sm border-t border-solid first:border-t-0 hover:bg-slate-50 border-slate-200">
                                <p>{{ $room['room_number'] }}</p>
                                <p>{{ $room['max_capacity'] }} Guests</p>
                                <p><x-currency />{{ number_format($room['rate'], 2) }}</p>
                                <div class="flex col-span-2 gap-1">
                                    <div class="w-full" x-show="can_edit">
                                        <x-form.input-number x-model="guests" id="guests_{{ $room['id'] }}" name="regular guest"/>
                                    </div>
                                    <div class="w-full" x-show="!can_edit">
                                        <span x-show="guests == 0" class="text-sm opacity-50">No guest here</span>
                                        <span x-show="guests == 1"><span x-text="guests"></span> Guest</span>
                                        <span x-show="guests > 1"><span x-text="guests"></span> Guests</span>
                                    </div>
                                    <div class="w-full" x-show="can_edit">
                                        <x-form.input-number x-model="disc_guests" id="disc_guests_{{ $room['id'] }}" name="special guest"/>
                                    </div>
                                    <div class="w-full" x-show="!can_edit">
                                        <span x-show="disc_guests == 0" class="text-sm opacity-50">No guest here</span>
                                        <span x-show="disc_guests == 1"><span x-text="disc_guests"></span> Guest</span>
                                        <span x-show="disc_guests > 1"><span x-text="disc_guests"></span> Guests</span>
                                    </div>
                                    {{-- Edit --}}<x-icon-button x-bind:disabled="editing ? true : false" x-on:click="can_edit = true; editing = true;" x-show="!can_edit" class="flex-shrink-0 w-max"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil-icon lucide-pencil"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg></x-icon-button>
                                    {{-- Save --}} <x-icon-button x-on:click="$wire.saveGuests({{ $room['id'] }}, guests, disc_guests);" x-show="can_edit" class="flex-shrink-0 w-max"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check"><path d="M20 6 9 17l-5-5"/></svg></x-icon-button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-table.table>

                <x-form.input-error field="guests" />
                <x-form.input-error field="disc_guests" />
            </div>

            <div class="flex justify-end gap-1">
                <x-secondary-button type='button' wire:click='goToStep(1)'>Back</x-secondary-button>
                <x-primary-button type="button" wire:loading.attr='disabled' wire:click='validateSeniorPwd'>Continue</x-primary-button>
            </div>
        </div>

        <div x-show="step == 3" x-cloak class="space-y-5">
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
                            <x-currency />{{ number_format($reservation->invoice->balance, 2) }}
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
                <hgroup id='{{ $breakdown_id }}'>
                    <h2 class='font-semibold'>Reservation Breakdown</h2>
                    <p class='text-xs'>Review reservation details here</p>
                </hgroup>
                
                <livewire:app.reservation-breakdown :reservation="$reservation" />
            </div>
                
            <div class="flex justify-end gap-1">
                @if (($reservation->senior_count > 0 || $reservation->pwd_count > 0) && $reservation->rooms->count() > 1)
                    <x-secondary-button x-on:click="() => { $nextTick(() => { $refs.form.scrollIntoView({ behavior: 'smooth' }); }); }" type="button" wire:click="goToStep(2)">Back</x-secondary-button>
                @else
                    <x-secondary-button x-on:click="() => { $nextTick(() => { $refs.form.scrollIntoView({ behavior: 'smooth' }); }); }" type="button" wire:click="goToStep(1)">Back</x-secondary-button>
                @endif
                <x-primary-button x-on:click="() => { $nextTick(() => { $refs.form.scrollIntoView({ behavior: 'smooth' }); }); }" type="button" wire:click='validatePayment' wire:loading.attr='disabled'>Check In</x-primary-button>
            </div>
        </div>
    </form>

    <x-modal.full name='bulk-assign-guests' maxWidth='sm'>
        <form wire:submit='bulkAssign' class="p-5 space-y-5" x-on:bulk-assigned.window="show = false">
            <hgroup>
                <h2 class='font-semibold'>Assign Guests</h2>
                <p class='text-xs'>Not all guests are assigned to a room, click the continue button to assign them to available rooms</p>
            </hgroup>

            <x-loading wire:loading wire:target='bulkAssign'>Processing request, please wait</x-loading>

            <div class="flex justify-end">
                <x-secondary-button type='button' x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button>Continue</x-primary-button>
            </div>
        </form>
    </x-modal.full>

    <x-modal.full name='add-payment-modal' maxWidth='sm'>
        <livewire:app.invoice.create-payment :invoice="$reservation->invoice" />
    </x-modal.full>

    <x-modal.full name='show-checkin-confirmation' maxWidth='sm'>
        <form wire:submit='checkin' class="p-5 space-y-5" x-on:checked-in.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold">Check-In Confirmation</h2>
                <p class="text-xs">You are about to check-in this rooms, are you sure?</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for='password-checkout'>Enter your password</x-form.input-label>
                <x-form.input-text type="password" wire:model.live='password' id="password-checkout" name="password-checkout" label="Password" />
                <x-form.input-error field="password" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='checkout'>Processing checkin, please wait</x-loading>

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button type="submit">Check-in</x-primary-button>
            </div>
        </form>
    </x-modal.full>
</div>
