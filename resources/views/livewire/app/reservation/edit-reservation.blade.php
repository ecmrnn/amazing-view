<div x-data="{
    {{-- Reservation Details --}}
    date_in: $wire.entangle('date_in'),
        date_out: $wire.entangle('date_out'),
        adult_count: $wire.entangle('adult_count'),
        senior_count: $wire.entangle('senior_count'),
        pwd_count: $wire.entangle('pwd_count'),
        children_count: $wire.entangle('children_count'),
        capacity: $wire.entangle('capacity'),

        {{-- Guest Details --}}
    first_name: $wire.entangle('first_name'),
        last_name: $wire.entangle('last_name'),
        email: $wire.entangle('email'),
        phone: $wire.entangle('phone'),
        address: $wire.entangle('address'),

        {{-- Operations --}}
    night_count: $wire.entangle('night_count'),

        formatDate(date) {
            let options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(date).toLocaleDateString('en-US', options)
        },
}">
    @csrf

    <section class="relative w-full max-w-screen-lg mx-auto space-y-5 rounded-lg">
        <div class="flex items-center justify-between p-5 bg-white border rounded-lg border-slate-200">
            <div class="flex items-center gap-5">
                <x-tooltip text="Back" dir="bottom">
                    <a x-ref="content" href="{{ route('app.reservations.index')}}" wire:navigate>
                        <x-icon-button>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                        </x-icon-button>
                    </a>
                </x-tooltip>

                <div>
                    <h2 class="text-lg font-semibold">Edit Reservation</h2>
                    <p class="max-w-sm text-xs">Update reservation details here</p>
                </div>
            </div>

            <x-actions>
                <div class="space-y-1">
                    @can('reschedule reservation')
                        @if (in_array($reservation->status, [
                            App\Enums\ReservationStatus::CONFIRMED->value,
                        ]) && $can_reschedule)
                            <button type="button" class="flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold rounded-md hover:bg-slate-50" x-on:click="$dispatch('open-modal', 'reschedule-reservation'); dropdown = false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-clock"><path d="M21 7.5V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h3.5"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h5"/><path d="M17.5 17.5 16 16.3V14"/><circle cx="16" cy="16" r="6"/></svg>
                                <p>Reschedule</p>
                            </button>
                        @endif
                    @endcan
                    
                    <button type="button" class="flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold rounded-md hover:bg-slate-50" x-on:click="$dispatch('open-modal', 'show-edit-reservation-details'); dropdown = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-door-open"><path d="M13 4h3a2 2 0 0 1 2 2v14"/><path d="M2 20h3"/><path d="M13 20h9"/><path d="M10 12v.01"/><path d="M13 4.562v16.157a1 1 0 0 1-1.242.97L5 20V5.562a2 2 0 0 1 1.515-1.94l4-1A2 2 0 0 1 13 4.561Z"/></svg>
                        <p>Rooms &amp; Guests</p>
                    </button>
                    @if ($reservation->status == App\Enums\ReservationStatus::CHECKED_IN->value)
                        <button type="button" class="flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold rounded-md hover:bg-slate-50" x-on:click="$dispatch('open-modal', 'add-amenity-modal'); dropdown = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-concierge-bell"><path d="M3 20a1 1 0 0 1-1-1v-1a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v1a1 1 0 0 1-1 1Z"/><path d="M20 16a8 8 0 1 0-16 0"/><path d="M12 4v4"/><path d="M10 4h4"/></svg>
                            <p>Add Amenity</p>
                        </button>
                    @endif
                    <button type="button" class="flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold rounded-md hover:bg-slate-50" x-on:click="$dispatch('open-modal', 'add-car-modal'); dropdown = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-car-front"><path d="m21 8-2 2-1.5-3.7A2 2 0 0 0 15.646 5H8.4a2 2 0 0 0-1.903 1.257L5 10 3 8"/><path d="M7 14h.01"/><path d="M17 14h.01"/><rect width="18" height="8" x="3" y="10" rx="2"/><path d="M5 18v2"/><path d="M19 18v2"/></svg>
                        <p>Add Car</p>
                    </button>
                </div>
            </x-actions>
        </div>

        {{-- For canceled resrvations --}}
        @if (!empty($canceled_reservation))
            <div class="p-5 text-red-500 border border-red-500 rounded-lg bg-red-50">
                <h2 class="text-lg font-semibold">Reservation Canceled</h2>
                <p class="text-sm">Canceled by: <span class="capitalize">{{ $canceled_reservation->canceled_by }}</span>
                </p>
                <p class="text-sm">Reason: {{ $canceled_reservation->reason }}</p>
            </div>
        @endif

        {{-- Exipred Reservation --}}
        @if ($reservation->status == \App\Enums\ReservationStatus::EXPIRED->value)
            <x-danger-message>
                <div>
                    <h2 class="font-semibold">This reservation has expired!</h2>
                    <p class="text-xs">Expiration date:
                        {{ date_format(date_create($reservation->expires_at), 'F j, Y \a\t h:i A') }}</p>
                </div>
            </x-danger-message>
        @endif

        {{-- Awaiting Payment Reservations --}}
        @if ($reservation->status == \App\Enums\ReservationStatus::AWAITING_PAYMENT->value)
            <x-warning-message>
                <div>
                    <h2 class="font-semibold">This reservation is awaiting payment!</h2>
                    <p class="text-xs">Expiration date:
                        {{ date_format(date_create($reservation->expires_at), 'F j, Y \a\t h:i A') }}</p>
                </div>
            </x-warning-message>
        @endif

        <section x-data="{ show: false }" class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
            <div class="flex items-start justify-between">
                <div class="flex items-center w-full gap-5">
                    <div class="grid font-bold text-white bg-gradient-to-r from-blue-500 to-blue-600 rounded-md aspect-square w-full max-w-[50px] place-items-center">
                        <p class="text-xl">{{ ucwords($reservation->user->first_name[0]) . ucwords($reservation->user->last_name[0]) }}</p>
                    </div>
                    <hgroup>
                        <h2 class="overflow-hidden text-lg font-semibold capitalize text-ellipsis whitespace-nowrap">
                            {{ $reservation->user->first_name . ' ' . $reservation->user->last_name }}</h2>
                        <p class="text-xs">Full Name</p>
                    </hgroup>
                </div>

                <a href="{{ route('app.users.edit', ['user' => $reservation->user->uid]) }}" wire:navigate>
                    <button type="button" class="text-xs font-semibold text-blue-500">Edit</button>
                </a>
            </div>
    
            <div class="grid grid-cols-1 gap-3 p-5 border rounded-md border-slate-200 sm:grid-cols-2">
                {{-- Email Address --}}
                <div class="flex items-center gap-3">
                    <x-icon>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-at-sign-icon lucide-at-sign"><circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"/></svg>
                    </x-icon>
                    <div>
                        <p class="text-sm font-semibold">{{ $reservation->user->email }}</p>
                        <p class="text-xs">Email Address</p>
                    </div>
                </div>
                {{-- Phone --}}
                <div class="flex items-center gap-3">
                    <x-icon>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-smartphone"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/></svg>
                    </x-icon>
                    <div>
                        <p class="text-sm font-semibold">{{ substr($reservation->user->phone, 0, 4) . ' ' . substr($reservation->user->phone, 4, 3) . ' ' . substr($reservation->user->phone, 7) }}</p>
                        <p class="text-xs">Phone</p>
                    </div>
                </div>
                {{-- Address --}}
                @if ($reservation->user->address)
                    <div class="flex items-center gap-3 sm:col-span-2">
                        <x-icon>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pinned"><path d="M18 8c0 3.613-3.869 7.429-5.393 8.795a1 1 0 0 1-1.214 0C9.87 15.429 6 11.613 6 8a6 6 0 0 1 12 0"/><circle cx="12" cy="8" r="2"/><path d="M8.714 14h-3.71a1 1 0 0 0-.948.683l-2.004 6A1 1 0 0 0 3 22h18a1 1 0 0 0 .948-1.316l-2-6a1 1 0 0 0-.949-.684h-3.712"/></svg>
                        </x-icon>
                        <div>
                            <p class="text-sm font-semibold line-clamp-1 hover:line-clamp-none">{{ $reservation->user->address }}</p>
                            <p class="text-xs">Address</p>
                        </div>
                    </div>
                @endif
            </div>
        </section>

        {{-- Reservation Details --}}
        <section class="p-5 space-y-5 bg-white border rounded-lg">
            <div class="flex items-start justify-between">
                <hgroup>
                    <h3 class="font-semibold">Reservation Details</h3>
                    <p class="max-w-sm text-xs">Click the <strong class="text-blue-500">Edit</strong> button on the right of the reservation id to edit the details of this reservation.</p>
                </hgroup>

                <div class="flex-shrink-0">
                    <x-status :status="$reservation->status" type="reservation" />
                </div>
            </div>

            <div class="space-y-5">
                <div class="space-y-5">
                    <div class="flex items-start justify-between gap-5">
                        <div>
                            <p class="font-semibold">{{ $reservation->rid }}</p>
                            <p class="text-xs">Reservation ID</p>
                        </div>
                        
                        <button x-on:click="$dispatch('open-modal', 'show-edit-reservation-details')" type="button" class="text-xs font-semibold text-blue-500">Edit</button>
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
                </div>

                {{-- Rooms --}}
                <div class="space-y-5">
                    <p class="font-semibold">Rooms</p>

                    <div class="grid gap-5 sm:grid-cols-2">
                        @forelse ($reservation->rooms as $room)
                            <div class="grid grid-cols-2 gap-5 p-5 border rounded-md border-slate-200">
                                <div>
                                    <p class="font-semibold">{{ $room->room_number }}
                                    </p>
                                    <p class="text-xs">Room Number</p>
                                </div>

                                <div>
                                    <p class="font-semibold"><x-currency />{{ number_format($room->pivot->rate, 2) }}</p>
                                    <p class="text-xs">Room Rate</p>
                                </div>
                            </div>
                        @empty
                            <div class="py-5 text-sm font-semibold text-center border rounded-lg sm:col-span-2">
                                No rooms yet...
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        @if ($reservation->status != App\Enums\ReservationStatus::CANCELED->value)
            {{-- Additional Details (Optional) --}}
            <section class="p-5 space-y-5 bg-white border rounded-lg">
                <hgroup>
                    <h3 class="font-semibold">Additional Services</h3>
                    <p class="max-w-sm text-xs">Select a service the guest wants to avail then click the <strong
                            class="text-blue-500">Save Button</strong> to save your changes.</p>
                </hgroup>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    @forelse ($services as $service)
                        @php
                            $checked = false;

                            if ($selected_services->contains('id', $service->id)) {
                                $checked = true;
                            }
                        @endphp

                        <div key="{{ $service->id }}">
                            <x-form.checkbox-toggle :checked="$checked" id="service-{{ $service->id }}" name="service"
                                wire:click="toggleService({{ $service->id }})">
                                <div class="px-3 py-2 select-none">
                                    <div class="w-full font-semibold capitalize text-md">
                                        {{ $service->name }}
                                    </div>

                                    <div class="w-full text-xs">
                                        Standard Fee: &#8369;{{ $service->price }}
                                    </div>
                                </div>
                            </x-form.checkbox-toggle>
                        </div>
                    @empty
                        <div
                            class="py-5 text-sm font-semibold text-center border rounded-lg sm:col-span-2 lg:col-span-4 text-zinc-800/50">
                            No services yet...</div>
                    @endforelse
                </div>
            </section>

            @if ($reservation->status == App\Enums\ReservationStatus::CHECKED_IN->value)
                <section class="p-5 space-y-5 bg-white border rounded-lg">
                    <div class="flex items-start justify-between">
                        <hgroup>
                            <h3 class="font-semibold">Amenities</h3>
                            <p class="max-w-sm text-xs">Click the <strong class="text-blue-500">Add Amenity</strong>
                                button on the right to select an amenity you want to add.</p>
                        </hgroup>

                        <button type="button" class="text-xs font-semibold text-blue-500"
                            x-on:click="$dispatch('open-modal', 'add-amenity-modal')">Add Amenity</button>
                    </div>
                    
                    @php $counter = 0; @endphp
                    @if ($selected_amenities->count() > 0)
                        <x-table.table headerCount="7">
                            <x-slot:headers>
                                <p>No.</p>
                                <p>Room No.</p>
                                <p>Amenity</p>
                                <p class="text-center">Quantity</p>
                                <p class="text-right">Price</p>
                                <p class="text-right">Total</p>
                                <p></p>
                            </x-slot:headers>
                            
                            @foreach ($selected_amenities as $key => $amenity)
                                <div wire:key='amenity-{{ $key }}' >
                                    <div x-data="{ quantity: @js($amenity['quantity']) }"
                                            x-init="
                                            let timeout;
                                            $watch('quantity', (value) => {
                                                clearTimeout(timeout); // Cancel the previous request if another change happens quickly
                                                timeout = setTimeout(() => { 
                                                    if (value > 0) {
                                                        @this.call('updateQuantity', '{{ $amenity['id'] }}', value, '{{ $amenity['room_number'] }}');
                                                    }
                                                }, 300); // Adjust debounce delay (300ms is a good default)
                                            })"
                                            class="grid items-center grid-cols-7 px-5 py-1 text-sm hover:bg-slate-50"
                                        >
                                        <p class="font-semibold opacity-50">{{ ++$counter }}</p>
                                        <p>{{ $amenity['room_number'] }}</p>
                                        <p>{{ $amenity['name'] }}</p>
                                        @if (in_array(Arr::get($amenity, 'status', null), [
                                            App\Enums\ReservationStatus::CONFIRMED->value,
                                            App\Enums\ReservationStatus::CHECKED_IN->value,
                                        ]))
                                            <x-form.input-number x-model="quantity" max="{{ $amenity['max'] }}" min="1" id="quantity" name="quantity" class="text-center" />
                                        @else
                                            <p class="text-center">{{ $amenity['quantity'] }}</p>
                                        @endif
                                        <p class="text-right"><x-currency />{{ number_format($amenity['price'], 2) }}</p>
                                        <p class="text-right"><x-currency />{{ number_format($amenity['quantity'] * $amenity['price'], 2) }}</p>
                                        <div class="ml-auto text-right w-max">
                                            @if (Arr::get($amenity, 'status', null) == App\Enums\ReservationStatus::CHECKED_OUT->value)
                                                <x-icon-button type="button"
                                                    class="opacity-0"
                                                    x-on:click="$dispatch('open-modal', 'remove-amenity-modal-{{ $key }}')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18" /><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" /><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" /><line x1="10" x2="10" y1="11" y2="17" /><line x1="14" x2="14" y1="11" y2="17" /></svg>
                                                </x-icon-button>
                                            @else
                                                <x-tooltip text="Remove" dir="left">
                                                    <x-icon-button x-ref="content" type="button"
                                                        x-on:click="$dispatch('open-modal', 'remove-amenity-modal-{{ $key }}')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18" /><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" /><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" /><line x1="10" x2="10" y1="11" y2="17" /><line x1="14" x2="14" y1="11" y2="17" /></svg>
                                                    </x-icon-button>
                                                </x-tooltip>
                                            @endif
                                        </div>
                                    </div>

                                    <x-modal.full name="remove-amenity-modal-{{ $key }}" maxWidth='sm'>
                                        <div class="p-5 space-y-5" x-on:amenity-removed.window="show = false">
                                            <div>
                                                <h2 class="text-lg font-semibold text-red-500">Remove Amenity</h2>
                                                <p class="text-xs">Are you sure you really want to remove
                                                    <strong>{{ $amenity['name'] }}</strong> as an amenity?
                                                </p>
                                            </div>
                                            <div class="flex justify-end gap-1">
                                                <x-secondary-button type="button" x-on:click="show = false">No,
                                                    cancel</x-secondary-button>
                                                <x-danger-button type="button"
                                                    wire:click="removeAmenity({{ $amenity['id'] }}, '{{ $amenity['room_number'] }}')">Yes,
                                                    remove</x-danger-button>
                                            </div>
                                        </div>
                                    </x-modal.full>
                                </div>
                            @endforeach
                        </x-table.table>
                    @else
                        <x-table-no-data.amenity />
                    @endif
                </section>
            @endif

            <section class="p-5 space-y-5 bg-white border rounded-lg">
                <div class="flex items-start justify-between">
                    <hgroup>
                        <h3 class="font-semibold">Cars</h3>
                        <p class="max-w-sm text-xs">Click the <strong class="text-blue-500">Add Car</strong> button on
                            the right to enter a new vehicle for the guest.</p>
                    </hgroup>

                    <button type="button" class="text-xs font-semibold text-blue-500"
                        x-on:click="$dispatch('open-modal', 'add-car-modal')">Add Car</button>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    @forelse ($cars as $car)
                        <div wire:key='{{ $car['plate_number'] }}'>
                            <div
                                class="flex items-center justify-between w-full px-3 py-2 border rounded-md select-none border-slate-200">
                                <div>
                                    <p class="font-semibold">{{ $car['plate_number'] }}</p>
                                    <p class="text-xs">Make: {{ $car['make'] }}</p>
                                    <p class="text-xs">Color &amp; Model: {{ $car['color'] . ' ' . $car['model'] }}
                                    </p>
                                </div>

                                <x-tooltip text="Remove Car" dir="top">
                                    <button x-ref="content" type="button" class="p-3 group"
                                        x-on:click="$dispatch('open-modal', 'remove-car-modal-{{ $car['plate_number'] }}')">
                                        <svg class="transition-all duration-200 ease-in-out opacity-50 group-hover:opacity-100" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18" /><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" /><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" /><line x1="10" x2="10" y1="11" y2="17" /><line x1="14" x2="14" y1="11" y2="17" /></svg>
                                    </button>
                                </x-tooltip>
                            </div>

                            <x-modal.full name="remove-car-modal-{{ $car['plate_number'] }}" maxWidth='sm'>
                                <div class="p-5 space-y-5" x-on:car-removed.window="show = false">
                                    <div>
                                        <h2 class="text-lg font-semibold text-red-500">Remove Car</h2>
                                        <p class="text-xs">Are you sure you really want to remove
                                            <strong>{{ $car['plate_number'] }}</strong>?
                                        </p>
                                    </div>

                                    <div class="flex justify-end gap-1">
                                        <x-secondary-button type="button" x-on:click="show = false">No,
                                            cancel</x-secondary-button>
                                        <x-danger-button type="button"
                                            wire:click="removeCar('{{ $car['plate_number'] }}')">Yes,
                                            remove</x-danger-button>
                                    </div>
                                </div>
                            </x-modal.full>
                        </div>
                    @empty
                        <div class="py-10 space-y-3 font-semibold text-center text-blue-800 border border-blue-500 border-dashed rounded-md sm:col-span-2 bg-blue-50">
                            <svg class="mx-auto text-blue-200" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-car-front"><path d="m21 8-2 2-1.5-3.7A2 2 0 0 0 15.646 5H8.4a2 2 0 0 0-1.903 1.257L5 10 3 8"/><path d="M7 14h.01"/><path d="M17 14h.01"/><rect width="18" height="8" x="3" y="10" rx="2"/><path d="M5 18v2"/><path d="M19 18v2"/></svg>
                        
                            <p class="text-sm">No Car Added!</p>
                        
                            <x-primary-button type="button" x-on:click="$dispatch('open-modal', 'add-car-modal')">
                                Add Car
                            </x-primary-button>
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- Save Changes button --}}
            <div class="flex items-center gap-5">
                <x-primary-button type="button" wire:loading.attr='disabled' wire:click='update'>Save Changes</x-primary-button>
                <x-loading wire:target='update' wire:loading.delay>Updating your reservation, please wait</x-loading>
            </div>
        @endif
    </section>

    <x-modal.drawer name='reschedule-reservation' maxWidth='xl'>
        <livewire:app.reservation.reschedule-reservation :reservation="$reservation" />
    </x-modal.drawer>

    {{-- Modal for confirming reservation --}}
    <x-modal.full name="show-cancel-reservation" maxWidth="sm">
        <livewire:app.reservation.cancel-reservation :reservation="$reservation" />
    </x-modal.full>

    {{-- Modal for showing building's rooms --}}
    <x-modal.full name="show-building-rooms" maxWidth="lg">
        @if (!empty($selected_building))
            <div x-data="{
                floor_number: $wire.entangle('floor_number'),
                floor_count: $wire.entangle('floor_count'),
                column_count: $wire.entangle('column_count'),
                }" wire:key="modal-{{ $modal_key }}">
                <div class="p-5">
                    <hgroup>
                        <h2 class="text-lg font-semibold capitalize">{{ $selected_building->name }} Building</h2>
                        <p class="text-xs text-zinc-800">Click a room to reserve</p>
                    </hgroup>
                </div>

                <div class="grid grid-cols-4 gap-3 p-3 mx-5 mb-5 bg-white border rounded-md border-slate-200">
                    <div class="flex items-center gap-3 text-xs font-semibold">
                        <div class="border border-blue-500 rounded-md size-3 bg-blue-50"></div>
                        <p>Selected</p>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-semibold">
                        <div class="border border-green-500 rounded-md size-3 bg-green-50"></div>
                        <p>Reserved</p>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-semibold">
                        <div class="border border-red-500 rounded-md size-3 bg-red-50"></div>
                        <p>Unavailable</p>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-semibold">
                        <div class="border rounded-md border-slate-200 size-3"></div>
                        <p>Available</p>
                    </div>
                </div>

                {{-- Room List --}}
                <section class="grid p-5 pt-0 overflow-auto max-h-80 bg-slate-100/50 gap-x-1 gap-y-5" style="grid-template-columns: repeat({{ $column_count }}, 1fr)">
                    <x-loading wire:loading wire:target='selectBuilding'>Amazing rooms incoming!</x-loading>

                    @php
                        $floor_slots = $slots->filter(function ($slot) {
                            return $slot->floor == $this->floor_number;
                        })    
                    @endphp

                    @foreach ($floor_slots as $slot)
                        @if ($slot->room_id)
                            @php
                                $checked = false;
                                $disabled = false;
                                $reserved = false;
                                if ($selected_rooms->contains('id', $slot->room->id)) {
                                    $checked = true;
                                }
                                elseif (in_array($slot->room->status, [
                                    \App\Enums\RoomStatus::UNAVAILABLE->value,
                                    \App\Enums\RoomStatus::DISABLED->value,
                                ])) {
                                    $disabled = true;
                                }
                                elseif (in_array($slot->room->id, $reserved_rooms)) {
                                    $reserved = true;
                                }
                            @endphp
                            
                            <div class="space-y-1 group">
                                <x-form.checkbox-toggle
                                    :reserved="$reserved"
                                    :disabled="$disabled"
                                    :checked="$checked"
                                    id="room-{{ $slot->room->id }}"
                                    x-on:click="$dispatch('add-room', { room: {{ $slot->room->id }} })"
                                    class="select-none"
                                    >
                                    <div class="grid w-full rounded-md select-none min-w-28 place-items-center aspect-square">
                                        <div class="text-center">
                                            <p class="font-semibold">{{ $slot->room->room_number }}</p>
                                        </div>
                                    </div>
                                </x-form.checkbox-toggle>

                                <div class="px-2 py-1 text-xs transition-all duration-200 ease-in-out bg-white border rounded-md opacity-50 group-hover:opacity-100 border-slate-200">
                                    <p">Rate: <x-currency />{{ number_format($slot->room->rate, 2) }}</p>
                                    <p">Capacity: {{ $slot->room->max_capacity }}</p>
                                </div>
                            </div>
                        @else
                            <div class="grid w-full border border-dashed rounded-md select-none min-w-28 place-items-center aspect-square border-slate-200 bg-slate-50 text-slate-200">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-archive-icon lucide-archive"><rect width="20" height="5" x="2" y="3" rx="1"/><path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"/><path d="M10 12h4"/></svg>
                            </div>
                        @endif
                    @endforeach
                </section>

                {{-- Floor Navigation --}}
                <footer class="flex gap-1 p-5 border-t">
                    <x-tooltip text="Up">
                        <x-icon-button x-ref="content" x-on:click="$wire.upFloor()"
                            x-bind:disabled="floor_number == floor_count">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-arrow-up-from-dot">
                                <path d="m5 9 7-7 7 7" />
                                <path d="M12 16V2" />
                                <circle cx="12" cy="21" r="1" />
                            </svg>
                        </x-icon-button>
                    </x-tooltip>
                    <x-tooltip text="Down">
                        <x-icon-button x-ref="content" x-on:click="$wire.downFloor()"
                            x-bind:disabled="floor_number == 1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-arrow-down-to-dot">
                                <path d="M12 2v14" />
                                <path d="m19 9-7 7-7-7" />
                                <circle cx="12" cy="21" r="1" />
                            </svg>
                        </x-icon-button>
                    </x-tooltip>
                    <div class="grid px-3 text-xs font-semibold border rounded-lg place-items-center">
                        <div><span x-text="floor_number"></span>F &#47; <span x-text="floor_count"></span>F</div>
                    </div>
                </footer>
            </div>
        @endif
    </x-modal.full>

    {{-- Modal for viewing rooms --}}
    <x-modal.full name="show-typed-rooms" maxWidth="lg">
        @if (!empty($selected_type))
            <div class="p-5 space-y-5">
                <hgroup>
                    <h2 class="text-lg font-semibold capitalize">{{ $selected_type->name }}</h2>
                    <p class="text-xs text-zinc-800">Click a room to reserve</p>
                </hgroup>

                {{-- Room List --}}
                <section class="grid gap-1 bg-slate-100/50">

                    <div wire:loading.delay wire:target='selectBuilding'
                        class="py-5 text-sm font-semibold text-center bg-white border rounded-lg">
                        Amazing rooms incoming!
                    </div>

                    <div class="space-y-1">
                        @forelse ($available_room_types as $capacity => $rooms)
                            @php
                                $rate_sum = 0;
                                $thumbnail = '';
                                $selected_room_count = 0;
                                foreach ($rooms as $room) {
                                    $rate_sum += $room->rate;
                                    $thumbnail = $room->image_1_path;

                                    if ($selected_rooms->contains('id', $room->id)) {
                                        $selected_room_count++;
                                    }
                                }
                                $average_rate = $rate_sum / intval($rooms->count());
                            @endphp
                            <div x-data="{ show_rooms: false }"
                                class="flex items-start justify-between gap-3 p-3 bg-white border rounded-md">
                                <div class="flex w-full gap-3">
                                    <div class="max-w-[150px] w-full relative">
                                        <x-img src="{{ $thumbnail }}" class="w-full" />
                                        @if ($selected_room_count > 0)
                                            <p
                                                class="absolute px-2 py-1 text-xs font-semibold text-white bg-blue-500 rounded-md top-1 left-1 w-max">
                                                {{ $selected_room_count }} Selected</p>
                                        @endif
                                    </div>

                                    <hgroup>
                                        <h3 class="font-semibold">For {{ $capacity }} Guests</h3>
                                        <p class="text-xs">
                                            @if ($rooms->count() > 1)
                                                <span>Available Rooms: {{ $rooms->count() }}</span>
                                            @else
                                                <span>Available Room: {{ $rooms->count() }}</span>
                                            @endif
                                        </p>
                                        <p class="text-xs">Average Rate:
                                            <x-currency />{{ number_format($average_rate, 2) }}
                                        </p>
                                    </hgroup>
                                </div>

                                <div class="min-w-max">
                                    @if ($selected_room_count == $rooms->count())
                                        <x-secondary-button disabled class="text-xs">
                                            All Room Selected
                                        </x-secondary-button>
                                    @else
                                        <x-primary-button type="button" class="text-xs"
                                            wire:click="addRoom({{ json_encode($rooms->pluck('id')) }})">
                                            Add Room
                                        </x-primary-button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center">
                                <x-table-no-data.rooms />
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>
        @endif
    </x-modal.full>

    {{-- Modal for editing rooms and guests --}}
    <x-modal.drawer name='show-edit-reservation-details' maxWidth='xl'>
        <livewire:app.reservation.edit-reservation-details :reservation="$reservation" />
    </x-modal.drawer>

    {{-- For applying discounts on guests --}}
    <x-modal.full name='show-discounts-modal' maxWidth='sm' :click_outside="false">
        <div x-data="{ discount: '' }" class="p-5" x-on:discount-applied.window="show = false">
            <div x-show="discount == ''" class="space-y-5">
                <hgroup>
                    <h2 class='font-semibold'>Apply Discounts</h2>
                    <p class='text-xs'>Select what type of discounts you want to apply</p>
                </hgroup>
                
                @if (!$reservation->promo)
                    <button type="button" class="inline-flex items-center w-full gap-5 p-5 text-left bg-white border rounded-md border-slate-200" x-on:click="discount = 'promo'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ticket-percent-icon lucide-ticket-percent"><path d="M2 9a3 3 0 1 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 1 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M9 9h.01"/><path d="m15 9-6 6"/><path d="M15 15h.01"/></svg>
                        
                        <div>
                            <h3 class="font-semibold">Promo Code</h3>
                            <p class="text-xs">Enter your promo code</p>
                        </div>
                    </button>
                @endif
    
                <button type="button" class="inline-flex items-center w-full gap-5 p-5 text-left bg-white border rounded-md border-slate-200" x-on:click="discount = 'discount'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-accessibility-icon lucide-accessibility"><circle cx="16" cy="4" r="1"/><path d="m18 19 1-7-6 1"/><path d="m5 8 3-3 5.5 3-2.36 3.5"/><path d="M4.24 14.5a5 5 0 0 0 6.88 6"/><path d="M13.76 17.5a5 5 0 0 0-6.88-6"/></svg>
    
                    <div>
                        <h3 class="font-semibold">Senior and PWDs</h3>
                        <p class="text-xs">Avail 20&#37; discount</p>
                    </div>
                </button>
    
                <div class="flex justify-end gap-1">
                    <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                </div>
            </div>
    
            <div x-show="discount == 'promo'" class="space-y-5">
                <hgroup>
                    <h2 class='font-semibold'>Promo Code</h2>
                    <p class='text-xs'>Enter your promo code to avail discounts!</p>
                </hgroup>
    
                <x-form.input-group>
                    <x-form.input-label for='promo_code'>Enter promo code here</x-form.input-label>
                    <x-form.input-text id="promo_code" name="promo_code" label="Promo Code" class="uppercase" wire:model.live='promo_code' />
                    <x-form.input-error field="promo_code" />
                </x-form.input-group>
    
                <x-loading wire:loading wire:target='applyPromo'>Checking promo, please wait</x-loading>
    
                <div class="flex justify-end gap-1">
                    <x-secondary-button type="button" x-on:click="discount = ''">Cancel</x-secondary-button>
                    <x-primary-button type="button" wire:click='applyPromo'>Apply</x-primary-button>
                </div>
            </div>
    
            <div x-show="discount == 'discount'" class="space-y-5">
                <hgroup>
                    <h2 class="font-semibold">Senior and PWDs</h2>
                    <p class="text-xs">The number of seniors and PWDs are limited to the number of guests you have.</p>
                </hgroup>
    
                <div class="grid grid-cols-2 gap-5">
                    <x-form.input-group>
                        <x-form.input-label for='senior_count'>Number of Seniors</x-form.input-label>
                        <x-form.input-number x-model="senior_count" id="senior_count" name="senior_count" label="Seniors" />
                    </x-form.input-group>
                
                    <x-form.input-group>
                        <x-form.input-label for='pwd_count'>Number of PWDs</x-form.input-label>
                        <x-form.input-number x-model="pwd_count" id="pwd_count" name="pwd_count" label="PWD" />
                    </x-form.input-group>
                </div>
    
                <x-form.input-group>
                    <x-filepond::upload
                        wire:model="discount_attachments"
                        multiple
                        placeholder="Drag & drop your image or <span class='filepond--label-action'> Browse </span>"
                    />
                    <x-form.input-error field="discount_attachments" />
                    <p class="max-w-sm text-xs">Please upload an image &lpar;<strong class="text-blue-500">JPG, JPEG, PNG</strong>&rpar; of the payment slip for your down payment. Maximum image size &lpar;<strong class="text-blue-500">3MB or 3000kb</strong>&rpar;</p>
                </x-form.input-group>
                
                <x-form.input-error field="senior_count" />
                <x-form.input-error field="pwd_count" />
    
                <x-note>This will not be immediately applied and will require confirmation first upon your arrival at the resort.</x-note>
    
                <div class="flex justify-end gap-1">
                    <x-secondary-button type="button" x-on:click="discount = ''">Cancel</x-secondary-button>
                    <x-primary-button type="button" wire:click='applyDiscount'>Save</x-primary-button>
                </div>
            </div>
        </div>
    </x-modal.full>

    {{-- Modal for adding amenity --}}
    <x-modal.full name='add-amenity-modal' maxWidth='sm'>
        <form wire:submit='addAmenity' class="p-5 space-y-5" x-data="{ quantity: @entangle('quantity'), max_quantity: @entangle('max_quantity') }" x-on:amenity-added.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold">Add Amenity</h2>
                <p class="text-xs">Select an amenity you want to add then enter their quantity.</p>
            </hgroup>

            <div class="grid grid-cols-2 gap-5 p-5 bg-white border rounded-md border-slate-200">
                <x-form.input-group>
                    <x-form.input-label for="amenity">Select an Amenity</x-form.input-label>
                    <x-form.select id="amenity" wire:model.live='amenity' x-on:change="$wire.selectAmenity()">
                        <option value="">Select Amenity</option>
                        @foreach ($available_amenities as $amenity)
                            @if ($selected_amenities->doesntContain(function ($_amenity) use ($amenity, $reservation, $amenity_room_id) {
                                return $_amenity['room_number'] == $reservation->rooms->get($amenity_room_id)->room_number && $_amenity['id'] == $amenity['id'];
                            }))
                                <option value="{{ $amenity->id }}">{{ $amenity->name }}</option>
                            @endif
                        @endforeach
                    </x-form.select>
                    <x-form.input-error field="amenity" />
                </x-form.input-group>
                <div class="space-y-2">
                    <x-form.input-group>
                        <x-form.input-label for="quantity">Quantity</x-form.input-label>
                        <x-form.input-number x-model="quantity" wire:model.live='quantity' :max="$max_quantity" id="quantity"
                            name="quantity" label="Quantity" />
                        <x-form.input-error field="quantity" />
                    </x-form.input-group>
                    <p x-show="max_quantity > 0" class="text-xs">Remaining stock: <span x-text="max_quantity"></span></p>
                </div>
            </div>

            {{-- Select Room --}}
            <div class="space-y-5">
                @if ($reservation->rooms->count() > 1)
                    <hgroup>
                        <h2 class="font-semibold">Select a room</h2>
                        <p class="text-xs">Choose from which of the reserved rooms to apply the selected amenity.</p>
                    </hgroup>

                    <div class="p-5 space-y-5 bg-white border rounded-md border-slate-200">
                        @foreach ($reservation->rooms as $key => $room)
                            @if ($key == $amenity_room_id)
                                <div wire:key='gallery-{{ $room->id}}'>
                                    <div class="space-y-5">
                                        <x-img src="{{ $room->image_1_path }}" alt="room" />

                                        <div class="flex items-start justify-between">
                                            <hgroup>
                                                <h3 class="font-semibold">{{ $room->room_number }}</h3>
                                                <p class="text-xs">Rate: <x-currency />{{ number_format($room->rate, 2) }}</p>
                                            </hgroup>
                                            <x-status type="reservation" status="{{ $room->pivot->status }}" />
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        {{-- Gallery Navigation --}}
                        <div class="flex items-center justify-between">
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
                                            'bg-blue-500' => $key == $amenity_room_id,
                                            'bg-slate-200' => $key != $amenity_room_id,
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
                @else
                    <hgroup>
                        <h2 class="font-semibold">Selected Room</h2>
                        <p class="text-xs">The selected amenity will be applied to the room below.</p>
                    </hgroup>

                    <div class="p-5 space-y-5 bg-white border rounded-md border-slate-200">
                        <x-img src="{{ $reservation->rooms->get($amenity_room_id)->image_1_path ?? '' }}" />
                        
                        <hgroup>
                            <h2 class="text-sm font-semibold">{{ $reservation->rooms->get($amenity_room_id)->room_number }}</h2>
                            <p class="text-xs">Capacity: {{ $reservation->rooms->get($amenity_room_id)->max_capacity }}</p>
                        </hgroup>
                    </div>
                @endif
            </div>

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button type="submit">Add Amenity</x-primary-button>
            </div>
        </form>
    </x-modal.full>

    {{-- Modal for adding cars --}}
    <x-modal.full name='add-car-modal' maxWidth='sm'>
        <form wire:submit='addCar' class="p-5 space-y-5" x-data="{ quantity: @entangle('quantity'), max_quantity: @entangle('max_quantity') }" x-on:car-added.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold">Add Car</h2>
                <p class="text-xs">Enter the vehicle details below.</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-text id="plate_number" class="uppercase" name="plate_number" label="Plate Number"
                    wire:model="plate_number" />
                <x-form.input-error field="plate_number" />
            </x-form.input-group>

            <x-form.input-group>
                <x-form.input-text id="make" name="make" class="capitalize" label="Brand / Make"
                    wire:model="make" />
                <x-form.input-error field="make" />
            </x-form.input-group>

            <x-form.input-group>
                <x-form.input-text id="model" name="model" class="capitalize" label="Model"
                    wire:model="model" />
                <x-form.input-error field="model" />
            </x-form.input-group>

            <x-form.input-group>
                <x-form.input-text id="color" name="color" class="capitalize" label="Color"
                    wire:model="color" />
                <x-form.input-error field="color" />
            </x-form.input-group>

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button type="submit">Add Car</x-primary-button>
            </div>
        </form>
    </x-modal.full>
</div>
