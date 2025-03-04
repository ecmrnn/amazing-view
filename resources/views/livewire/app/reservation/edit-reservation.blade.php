<form x-data="{
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
        {{-- additional_amenity_quantity: $wire.entangle('additional_amenity_quantity'), --}}

        formatDate(date) {
            let options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(date).toLocaleDateString('en-US', options)
        },
}" wire:submit="submit()">
    @csrf

    <section class="relative w-full max-w-screen-lg mx-auto space-y-5 rounded-lg">
        <div class="flex items-center gap-5 p-5 bg-white border rounded-lg border-slate-200">
            <x-back />

            <div>
                <h2 class="text-lg font-semibold">Edit Reservation</h2>
                <p class="max-w-sm text-xs">Update reservation details here</p>
            </div>
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

        {{-- Reservation Details --}}
        <section class="p-5 space-y-5 bg-white border rounded-lg">
            <div class="flex items-start justify-between">
                <hgroup>
                    <h3 class="font-semibold">Reservation Details</h3>
                    <p class="max-w-sm text-xs">Click the <strong class="text-blue-500">Edit</strong> button on the
                        right of the reservation id to edit the details of this reservation.</p>
                </hgroup>

                <div class="flex-shrink-0">
                    <x-status :status="$reservation->status" type="reservation" />
                </div>
            </div>

            <div class="space-y-5">
                <div class="p-5 space-y-5 border rounded-md border-slate-200">
                    <div class="flex items-start justify-between gap-5">
                        <div>
                            <p class="font-semibold">{{ $reservation->rid }}</p>
                            <p class="text-xs">Reservation ID</p>
                        </div>
                        @if ($reservation->status != App\Enums\ReservationStatus::CANCELED->value)
                            <button x-on:click="$dispatch('open-modal', 'edit-reservation-details')" type="button"
                                class="text-xs font-semibold text-blue-500">Edit</button>
                        @endif
                    </div>

                    {{-- Reservation Details --}}
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div class="grid gap-5 p-5 border rounded-md sm:grid-cols-2 border-slate-200">
                            <div>
                                @if (!empty($reservation->resched_date_in))
                                    <p class="font-semibold">
                                        {{ date_format(date_create($reservation->resched_date_in), 'F j, Y') }}</p>
                                @else
                                    <p class="font-semibold">
                                        {{ date_format(date_create($reservation->date_in), 'F j, Y') }}</p>
                                @endif
                                <p class="text-xs">Check-in Date</p>
                            </div>
                            <div>
                                @if (!empty($reservation->resched_date_out))
                                    <p class="font-semibold">
                                        {{ date_format(date_create($reservation->resched_date_out), 'F j, Y') }}</p>
                                @else
                                    <p class="font-semibold">
                                        {{ date_format(date_create($reservation->date_out), 'F j, Y') }}</p>
                                @endif
                                <p class="text-xs">Check-out Date</p>
                            </div>
                        </div>
                        <div class="grid gap-5 p-5 border rounded-md sm:grid-cols-2 border-slate-200">
                            <div>
                                <p class="font-semibold">{{ $reservation->adult_count }}</p>
                                <p class="text-xs">Number of Adults</p>
                            </div>
                            <div>
                                <p class="font-semibold">{{ $reservation->children_count }}</p>
                                <p class="text-xs">Number of Children</p>
                            </div>
                            @if ($reservation->senior_count > 0)
                                <div>
                                    <p class="font-semibold">{{ $reservation->senior_count }}</p>
                                    <p class="text-xs">Number of Senior</p>
                                </div>
                            @endif
                            @if ($reservation->pwd_count > 0)
                                <div>
                                    <p class="font-semibold">{{ $reservation->pwd_count }}</p>
                                    <p class="text-xs">Number of PWD</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Rooms --}}
                <div class="space-y-5">
                    <p class="font-semibold">Rooms</p>

                    <div class="grid gap-5 sm:grid-cols-2">
                        @forelse ($reservation->rooms as $room)
                            <div class="grid grid-cols-2 gap-5 p-5 border rounded-md border-slate-200">
                                <div>
                                    <p class="font-semibold">{{ $room->building->prefix }} {{ $room->room_number }}
                                    </p>
                                    <p class="text-xs">Room Number</p>
                                </div>

                                <div>
                                    <p class="font-semibold"><x-currency />{{ number_format($room->rate, 2) }}</p>
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

        {{-- Guest Details --}}
        <section class="p-5 space-y-5 bg-white border rounded-lg">
            <hgroup>
                <h3 class="font-semibold">Guest Details</h3>
                <p class="max-w-sm text-xs">Modify the reservation field you want to update then click the <strong
                        class="text-blue-500">Save Button</strong> to save your changes.</p>
            </hgroup>

            {{-- First & Last name --}}
            <div class="grid items-start gap-5 sm:grid-cols-2">
                <div class="space-y-1">
                    <x-form.input-text class="capitalize" wire:model.live='first_name' x-model="first_name"
                        id="first_name" label="First Name" />
                    <x-form.input-error field="first_name" />
                </div>
                <div class="space-y-1">
                    <x-form.input-text class="capitalize" wire:model.live='last_name' x-model="last_name" id="last_name"
                        label="Last Name" />
                    <x-form.input-error field="last_name" />
                </div>
                <div class="space-y-1">
                    <x-form.input-text wire:model.live='phone' id="phone" label="Contact Number" />
                    <x-form.input-error field="phone" />
                </div>
                <div class="space-y-1">
                    <x-form.input-text wire:model.live='email' id="email" label="Email" />
                    <x-form.input-error field="email" />
                </div>
                <div class="sm:col-span-2">
                    <x-form.input-text wire:model.live='address' id="address" label="Address" />
                    <x-form.input-error field="address" />
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
                            <div wire:key='{{ $key }}'>
                                <div x-data="{ quantity: @js($amenity['quantity']) }" class="grid items-center grid-cols-7 px-5 py-1 text-sm border-t border-solid hover:bg-slate-50 border-slate-200"
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
                                    >
                                    <p class="font-semibold opacity-50">{{ ++$counter }}</p>
                                    <p>{{ $amenity['room_number'] }}</p>
                                    <p>{{ $amenity['name'] }}</p>
                                    <x-form.input-number x-model="quantity" max="{{ $amenity['max'] }}" min="1" id="quantity" name="quantity" class="text-center" />
                                    <p class="text-right"><x-currency />{{ number_format($amenity['price'], 2) }}</p>
                                    <p class="text-right"><x-currency />{{ number_format($amenity['quantity'] * $amenity['price'], 2) }}</p>
                                    <div class="ml-auto text-right w-max">
                                        <x-tooltip text="Remove" dir="left">
                                            <x-icon-button x-ref="content" type="button"
                                                x-on:click="$dispatch('open-modal', 'remove-amenity-modal-{{ $key }}')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18" /><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" /><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" /><line x1="10" x2="10" y1="11" y2="17" /><line x1="14" x2="14" y1="11" y2="17" /></svg>
                                            </x-icon-button>
                                        </x-tooltip>
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
                    <div class="py-10 space-y-3 text-center border rounded-md sm:col-span-2 lg:col-span-4 border-slate-200">
                        <svg class="mx-auto text-zinc-200" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-notebook"><path d="M2 6h4" /><path d="M2 10h4" /><path d="M2 14h4" /><path d="M2 18h4" /><rect width="16" height="20" x="4" y="2" rx="2" /><path d="M16 2v20" /></svg>

                        <p class="text-sm font-semibold">No amenity selected!</p>

                        <x-primary-button type='button'
                            x-on:click="$dispatch('open-modal', 'add-amenity-modal')">
                            Add Amenity
                        </x-primary-button>
                    </div>
                @endif
            </section>

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
                                        <svg class="transition-all duration-200 ease-in-out opacity-50 group-hover:opacity-100"
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-trash-2">
                                            <path d="M3 6h18" />
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                            <line x1="10" x2="10" y1="11" y2="17" />
                                            <line x1="14" x2="14" y1="11" y2="17" />
                                        </svg>
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
                        <div class="py-10 space-y-3 text-center border rounded-md sm:col-span-2 border-slate-200">
                            <svg class="mx-auto text-zinc-200" xmlns="http://www.w3.org/2000/svg" width="48"
                                height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-car-front">
                                <path d="m21 8-2 2-1.5-3.7A2 2 0 0 0 15.646 5H8.4a2 2 0 0 0-1.903 1.257L5 10 3 8" />
                                <path d="M7 14h.01" />
                                <path d="M17 14h.01" />
                                <rect width="18" height="8" x="3" y="10" rx="2" />
                                <path d="M5 18v2" />
                                <path d="M19 18v2" />
                            </svg>

                            <p class="text-sm font-semibold">No car added!</p>

                            <x-primary-button type='button' x-on:click="$dispatch('open-modal', 'add-car-modal')">
                                Add Car
                            </x-primary-button>
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- Save Changes button --}}
            <div class="flex items-center gap-5">
                <x-primary-button type="button" wire:click='update'>Save Changes</x-primary-button>
                <x-loading wire:target='update' wire:loading.delay>Updating your reservation, please wait</x-loading>
            </div>
        @endif

        @if (
            $reservation->status == App\Enums\ReservationStatus::PENDING->value ||
                $reservation->status == App\Enums\ReservationStatus::AWAITING_PAYMENT->value ||
                $reservation->status == App\Enums\ReservationStatus::CONFIRMED->value)
            {{-- Cancel reservation --}}
            <section class="p-3 space-y-5 border border-red-500 rounded-lg bg-red-200/50 sm:p-5">
                <hgroup>
                    <h3 class="font-semibold text-red-500">Cancel Reservation</h3>
                    <p class="max-w-sm text-xs">If you need to cancel the reservation, click the button below.</p>
                </hgroup>

                <div>
                    <x-danger-button type="button"
                        x-on:click="$dispatch('open-modal', 'show-cancel-reservation')">Cancel
                        Reservation</x-danger-button>
                </div>
            </section>
        @endif
    </section>

    <x-modal.drawer name='edit-reservation-details' maxWidth='xl'>
        <div class="p-5 space-y-5" x-on:reservation-details-updated.window="show = false">
            <livewire:app.reservation.edit-reservation-details :reservation="$reservation" />
        </div>
    </x-modal.drawer>

    {{-- Modal for confirming reservation --}}
    <x-modal.full name="show-cancel-reservation" maxWidth="sm">
        <div x-data="{ reason: 'guest' }">
            <livewire:app.reservation.cancel-reservation :reservation="$reservation" />
        </div>
    </x-modal.full>

    {{-- Modal for showing building's rooms --}}
    <x-modal.full name="show-building-rooms" maxWidth="lg">
        @if (!empty($selected_building))
            <div x-data="{
                floor_number: $wire.entangle('floor_number'),
                floor_count: $wire.entangle('floor_count'),
                column_count: $wire.entangle('column_count'),
            }" wire:key="modal-{{ $modal_key }}">
                <header class="flex items-center gap-3 p-5 border-b">
                    <x-tooltip text="Back" dir="bottom">
                        <x-icon-button x-ref="content" x-on:click="show = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left">
                                <path d="m12 19-7-7 7-7" />
                                <path d="M19 12H5" />
                            </svg>
                        </x-icon-button>
                    </x-tooltip>

                    <hgroup>
                        <h2 class="text-lg font-semibold capitalize">{{ $selected_building->name }} Building</h2>
                        <p class="text-xs text-zinc-800">Click a room to reserve</p>
                    </hgroup>
                </header>

                {{-- Room List --}}
                <section class="grid p-5 overflow-auto max-h-80 bg-slate-100/50 gap-x-1 gap-y-5"
                    @if ($available_rooms->isNotEmpty()) style="grid-template-columns: repeat({{ $column_count }}, 1fr)" @endif
                    @can('update room')
                        x-sort
                    @endcan>

                    <div wire:loading.delay wire:target='selectBuilding'
                        class="py-5 text-sm font-semibold text-center bg-white border rounded-lg">
                        Amazing rooms incoming!
                    </div>

                    @forelse ($available_rooms as $room)
                        @php
                            $checked = false;
                            $disabled = false;
                            $reserved = false;
                            if ($selected_rooms->contains('id', $room->id)) {
                                $checked = true;
                            } elseif ($room->status == \App\Enums\RoomStatus::UNAVAILABLE) {
                                $disabled = true;
                            } elseif (in_array($room->id, $reserved_rooms)) {
                                $reserved = true;
                            }
                        @endphp
                        <x-form.checkbox-toggle :reserved="$reserved" :disabled="$disabled" :checked="$checked"
                            id="room-{{ $room->id }}"
                            x-on:click="$dispatch('add-room', { room: {{ $room->id }} })" class="select-none">
                            <div class="grid w-full rounded-lg select-none min-w-28 place-items-center aspect-square">
                                <div>
                                    <p class="text-xs font-semibold text-center">{{ $room->building->prefix }}</p>
                                    <p class="text-lg font-semibold text-center">{{ $room->room_number }}</p>
                                </div>
                            </div>
                        </x-form.checkbox-toggle>
                    @empty
                        <div class="py-5 text-sm font-semibold text-center bg-white border rounded-lg">
                            No rooms assigned to this floor
                        </div>
                    @endforelse
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
            <div>
                <header class="flex items-center gap-3 p-5 border-b">
                    <x-tooltip text="Back" dir="bottom">
                        <x-icon-button x-ref="content" x-on:click="show = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left">
                                <path d="m12 19-7-7 7-7" />
                                <path d="M19 12H5" />
                            </svg>
                        </x-icon-button>
                    </x-tooltip>
                    <hgroup>
                        <h2 class="text-sm font-semibold capitalize">{{ $selected_type->name }}</h2>
                        <p class="text-xs text-zinc-800">Click a room to reserve</p>
                    </hgroup>
                </header>

                {{-- Room List --}}
                <section class="grid gap-1 p-5 bg-slate-100/50">

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
                                        <x-img-lg src="{{ $thumbnail }}" class="w-full" />
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
                            <x-table-no-data.rooms />
                        @endforelse
                    </div>
                </section>
            </div>
        @endif
    </x-modal.full>

    <x-modal.full name='show-discounts-modal' maxWidth='sm'>
        <div class="p-5 space-y-5" x-on:apply-discount.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold">Apply Discounts</h2>
                <p class="text-xs">The number of seniors and PWDs are limited to the number of guests you have.</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for='senior_count'>Number of Seniors</x-form.input-label>
                <x-form.input-number x-model="senior_count" id="senior_count" name="senior_count" label="Seniors" />
                <x-form.input-error field="senior_count" />
            </x-form.input-group>

            <x-form.input-group>
                <x-form.input-label for='pwd_count'>Number of PWDs</x-form.input-label>
                <x-form.input-number x-model="pwd_count" id="pwd_count" name="pwd_count" label="PWD" />
                <x-form.input-error field="pwd_count" />
            </x-form.input-group>

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button type="button" wire:click='applyDiscount'>Save</x-primary-button>
            </div>
        </div>
    </x-modal.full>

    <x-modal.full name='add-amenity-modal' maxWidth='sm'>
        <div class="p-5 space-y-5" x-data="{ quantity: @entangle('quantity'), max_quantity: @entangle('max_quantity') }" x-on:amenity-added.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold">Add Amenity</h2>
                <p class="text-xs">Select an amenity you want to add then enter their quantity.</p>
            </hgroup>

            <x-form.input-group>
                <x-form.select wire:model.live='amenity' x-on:change="$wire.selectAmenity()">
                    <option value="">Select an Amenity</option>
                    @foreach ($available_amenities as $amenity)
                        @if ($selected_amenities->doesntContain(function ($_amenity) use ($amenity, $reservation, $amenity_room_id) {
                            return $_amenity['room_number'] == $reservation->rooms->get($amenity_room_id)->building->prefix . ' ' . $reservation->rooms->get($amenity_room_id)->room_number && $_amenity['id'] == $amenity['id'];
                        }))
                            <option value="{{ $amenity->id }}">{{ $amenity->name }}</option>
                        @endif
                    @endforeach
                </x-form.select>
                <x-form.input-error field="amenity" />
            </x-form.input-group>

            <div class="space-y-2">
                <x-form.input-group>
                    <x-form.input-number x-model="quantity" wire:model.live='quantity' :max="$max_quantity" id="quantity"
                        name="quantity" label="Quantity" />
                    <x-form.input-error field="quantity" />
                </x-form.input-group>

                <p x-show="max_quantity > 0" class="text-xs">Remaining stock: <span x-text="max_quantity"></span></p>
            </div>

            {{-- Select Room --}}
            <div class="space-y-5">
                @if ($reservation->rooms->count() > 1)
                    <hgroup>
                        <h2 class="font-semibold">Select a room</h2>
                        <p class="text-xs">Choose from which of the reserved rooms to apply the selected amenity.</p>
                    </hgroup>

                    <div class="p-5 space-y-5 bg-white border rounded-md border-slate-200">
                        <img src="{{ asset('storage/' . $reservation->rooms->get($amenity_room_id)->image_1_path) }}" class="object-cover object-center rounded-md aspect-video" />
                        
                        <div class="flex items-start justify-between">
                            <hgroup>
                                <h2 class="text-sm font-semibold">{{ $reservation->rooms->get($amenity_room_id)->building->prefix . ' ' . $reservation->rooms->get($amenity_room_id)->room_number }}</h2>
                                <p class="text-xs">Capacity: {{ $reservation->rooms->get($amenity_room_id)->max_capacity }}</p>
                            </hgroup>

                            <x-status type="room" :status="$reservation->rooms->get($amenity_room_id)->status" />
                        </div>

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
                        <img src="{{ asset('storage/' . $reservation->rooms->get($amenity_room_id)->image_1_path) }}" class="object-cover object-center rounded-md aspect-video" />
                        
                        <hgroup>
                            <h2 class="text-sm font-semibold">{{ $reservation->rooms->get($amenity_room_id)->building->prefix . ' ' . $reservation->rooms->get($amenity_room_id)->room_number }}</h2>
                            <p class="text-xs">Capacity: {{ $reservation->rooms->get($amenity_room_id)->max_capacity }}</p>
                        </hgroup>
                    </div>
                @endif
            </div>

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button type="button" wire:click='addAmenity'>Add Amenity</x-primary-button>
            </div>
        </div>
    </x-modal.full>

    <x-modal.full name='add-car-modal' maxWidth='sm'>
        <div class="p-5 space-y-5" x-data="{ quantity: @entangle('quantity'), max_quantity: @entangle('max_quantity') }" x-on:car-added.window="show = false">
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
                <x-primary-button type="button" wire:click='addCar'>Add Car</x-primary-button>
            </div>
        </div>
    </x-modal.full>
</form>
