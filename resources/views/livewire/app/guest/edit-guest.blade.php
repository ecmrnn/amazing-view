<form x-data="{
    {{-- Reservation Details --}}
    date_in: $wire.entangle('date_in'),
    date_out: $wire.entangle('date_out'),
    adult_count: $wire.entangle('adult_count'),
    children_count: $wire.entangle('children_count'),
    capacity: $wire.entangle('capacity'),

    {{-- Guest Details --}}
    first_name: $wire.entangle('first_name'),
    last_name: $wire.entangle('last_name'),
    email: $wire.entangle('email'),
    phone: $wire.entangle('phone'),
    address: $wire.entangle('address'),
    region: $wire.entangle('region'),
    province: $wire.entangle('province'),
    city: $wire.entangle('city'),
    district: $wire.entangle('district'),
    baranggay: $wire.entangle('baranggay'),
    street: $wire.entangle('street'),

    {{-- Operations --}}
    is_map_view: $wire.entangle('is_map_view'),
    night_count: $wire.entangle('night_count'),
    additional_amenity_quantity: $wire.entangle('additional_amenity_quantity'),

    formatDate(date) {
        let options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(date).toLocaleDateString('en-US', options)
    },

    edit_reservation: false,
    edit_guest: false,
    edit_rooms: false,
    edit_amenities: false,
}" wire:submit="submit()">
@csrf

<div class="relative flex flex-col gap-5 lg:flex-row">
    <section class="w-full space-y-5">
        {{-- Late Notice --}}
        <section class="flex flex-col justify-between px-3 py-2 text-red-500 border border-red-500 rounded-lg bg-red-50 md:flex-row">
            <div>
                <p class="text-sm font-semibold">Late Checkout Notice!</p>
                <p class="text-xs">
                    This guest is expected to be checked-out on <strong>{{ date_format(date_create($date_out), 'F j, Y') }}</strong>.
                    <br />
                </p>
            </div>

            <x-danger-button x-on:click="$dispatch('open-modal', 'show-check-out-modal')" type="button" class="text-xs">Check-out Guest</x-danger-button>
        </section>
        {{-- Reservation Details --}}
        <section class="p-3 space-y-5 border rounded-lg sm:p-5">
            <hgroup>
                <h3 class="font-semibold">Reservation</h3>
                <p class="max-w-sm text-xs">Modify the reservation field you want to update then click the <strong class="text-blue-500">Save Button</strong> to save your changes.</p>
            </hgroup>
            
            <div class="grid items-start gap-3 sm:grid-cols-2 xl:grid-cols-4">
                <div class="grid space-y-1">
                    <x-form.input-label for="date_in">Check-in Date</x-form.input-label>
                    <x-form.input-date
                        wire:model.live="date_in"
                        x-model="date_in"
                        min="{{ $min_date }}"
                        id="date_in"
                        disabled />
                    <x-form.input-error field="date_in" />
                </div>
                <div class="grid space-y-1">
                    <x-form.input-label for="date_out">Check-out Date</x-form.input-label>
                    <x-form.input-date
                        wire:model.live="date_out"
                        x-model="date_out"
                        min="{{ $date_in }}"
                        id="date_out" />
                    <x-form.input-error field="date_out" />
                </div>
                <div class="grid space-y-1">
                    <x-form.input-label for="adult_count">Number of Adults</x-form.input-label>
                    <x-form.input-number wire:model.live="adult_count" x-model="adult_count"
                        id="adult_count" min="1" />
                    <x-form.input-error field="adult_count" />
                </div>
                <div class="grid space-y-1">
                    <x-form.input-label for="children_count">Number of Children</x-form.input-label>
                    <x-form.input-number x-model="children_count" id="children_count"
                        wire:model.live="children_count" />
                    <x-form.input-error field="children_count" />
                </div>
            </div>
        </section>

        {{-- Guest Details --}}
        <section class="p-3 space-y-5 border rounded-lg sm:p-5">
            <hgroup>
                <h3 class="font-semibold">Guest Details</h3>
                <p class="max-w-sm text-xs">Modify the reservation field you want to update then click the <strong class="text-blue-500">Save Button</strong> to save your changes.</p>
            </hgroup>

            {{-- First & Last name --}}
            <div class="grid items-start gap-3 sm:grid-cols-2">
                <div class="space-y-1">
                    <x-form.input-text class="capitalize" wire:model.live='first_name' x-model="first_name"
                        id="first_name" label="First Name" />
                    <x-form.input-error field="first_name" />
                </div>
                <div class="space-y-1">
                    <x-form.input-text class="capitalize" wire:model.live='last_name' x-model="last_name"
                        id="last_name" label="Last Name" />
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

        {{-- Room & Additional Details --}}
        <section class="p-3 space-y-5 border rounded-lg sm:p-5">
            <div>
                {{-- header --}}
                <div class="flex flex-col items-start justify-between gap-5 sm:flex-row">
                    <hgroup>
                        <h3 class="font-semibold">Rooms</h3>
                        <p class="max-w-sm text-xs">Modify the reservation field you want to update then click the <strong class="text-blue-500">Save Button</strong> to save your changes.</p>
                    </hgroup>
    
                    <div class="flex border divide-x rounded-lg">
                        <x-tooltip text="List View" dir="bottom">
                            <x-icon-button x-on:click="is_map_view = !is_map_view" x-ref="content"
                                x-bind:disabled="!is_map_view" class="flex gap-2 m-1 border-transparent">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-list-collapse">
                                    <path d="m3 10 2.5-2.5L3 5" />
                                    <path d="m3 19 2.5-2.5L3 14" />
                                    <path d="M10 6h11" />
                                    <path d="M10 12h11" />
                                    <path d="M10 18h11" />
                                </svg>
                                <p class="text-xs font-semibold sm:hidden">List View</p>
                            </x-icon-button>
                        </x-tooltip>
                        <x-tooltip text="Map View" dir="bottom">
                            <x-icon-button x-on:click="is_map_view = !is_map_view" x-ref="content"
                                x-bind:disabled="is_map_view" class="flex gap-2 m-1 border-transparent">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-map">
                                    <path
                                        d="M14.106 5.553a2 2 0 0 0 1.788 0l3.659-1.83A1 1 0 0 1 21 4.619v12.764a1 1 0 0 1-.553.894l-4.553 2.277a2 2 0 0 1-1.788 0l-4.212-2.106a2 2 0 0 0-1.788 0l-3.659 1.83A1 1 0 0 1 3 19.381V6.618a1 1 0 0 1 .553-.894l4.553-2.277a2 2 0 0 1 1.788 0z" />
                                    <path d="M15 5.764v15" />
                                    <path d="M9 3.236v15" />
                                </svg>
                                <p class="text-xs font-semibold sm:hidden">Map View</p>
                            </x-icon-button>
                        </x-tooltip>
                    </div>
                </div>
            </div>
            
            <x-form.input-error field="selected_rooms" />
    
            {{-- 1. List View --}}
            <template x-if="!is_map_view">
                <div class="space-y-1">
                    @forelse ($rooms as $room)
                        <div key="{{ $room->id }}" class="flex items-start justify-between gap-5 p-3 border rounded-lg">
                            <div class="flex items-start w-full gap-5">
                                <div class="w-full max-w-[150px]">
                                    <x-img src="{{ $room->image_1_path }}" />
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold">{{ $room->name }}</h3>
                                    <p class="max-w-xs text-xs">{{ $room->description }}</p>
                                </div>
                            </div>
    
                            <x-secondary-button class="flex-shrink-0 text-xs" x-on:click="$dispatch('open-modal', 'show-typed-rooms')" wire:click="viewRooms({{ $room->id }})">
                                View Rooms
                            </x-secondary-button>
                        </div>
                    @empty
                        <div class="border rounded-lg">
                            <x-table-no-data.rooms />
                        </div>
                    @endforelse
                </div>
            </template>
    
            {{-- 2. Map View --}}
            <template x-if="is_map_view">
                <div class="grid grid-cols-3 gap-1 p-3 rounded-lg place-items-start lg:grid-cols-5 min-h-80 bg-gradient-to-tr from-teal-500/20 to-teal-600/20">
                    @forelse ($buildings as $building)
                        <button type="button" key="{{ $building->id }}" class="w-full" x-on:click.prevent="$dispatch('open-modal', 'show-building-rooms')"
                            wire:click="selectBuilding({{ $building->id }})">
                            <div
                                class="relative grid w-full font-semibold bg-white border rounded-lg aspect-square place-items-center">
                                <div class="text-center">
                                    <p>{{ $building->name }}</p>
                                    <p class="text-xs text-zinc-800/50">{{ $building->floor_count }}
                                        @if ($building->floor_count > 1)
                                            Floors
                                        @else
                                            Floor
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </button>
                    @empty
                        
                    @endforelse
                </div>
            </template>
    
            {{-- View Selected Rooms --}}
            <x-secondary-button x-on:click="$dispatch('open-modal', 'show-selected-rooms')">View Selected Rooms</x-secondary-button>
            <p class="max-w-xs text-xs">If you wish to <strong class="font-semibold text-red-500">remove</strong> or view all the selected rooms, click the button above.</p>
        </section>

        {{-- Additional Details (Optional) --}}
        <section class="p-3 space-y-5 border rounded-lg sm:p-5">
            <hgroup>
                <h3 class="font-semibold">Amenities</h3>
                <p class="max-w-sm text-xs">Modify the reservation field you want to update then click the <strong class="text-blue-500">Save Button</strong> to save your changes.</p>
            </hgroup>   

            <div class="grid gap-1 sm:grid-cols-2 lg:grid-cols-4">
                @forelse ($addons as $addon)
                    @php
                        $checked = false;

                        if ($selected_amenities->contains('id', $addon->id)) {
                            $checked = true;
                        }
                    @endphp
                    <div key="{{ $addon->id }}">
                        <x-form.checkbox-toggle :checked="$checked" id="amenity{{ $addon->id }}" name="amenity" wire:click="toggleAmenity({{ $addon->id }})">
                            <div class="px-3 py-2 select-none">
                                <div class="w-full font-semibold capitalize text-md">
                                    {{ $addon->name }}
                                </div>
                                <div class="w-full text-xs">Standard Fee: &#8369;{{ $addon->price }}
                                </div>
                            </div>
                        </x-form.checkbox-toggle>
                    </div>
                @empty
                    <div
                        class="py-5 text-sm font-semibold text-center border rounded-lg sm:col-span-2 lg:col-span-4 text-zinc-800/50">
                        No add ons yet...</div>
                @endforelse
            </div>

            <div class="p-3 bg-white border rounded-md">
                @if (!empty($available_amenities))
                    <div x-data="{ 
                            additonal_amenity: $wire.entangle('additional_amenity_id'),
                            additional_amenity_quantity: $wire.entangle('additional_amenity_quantity'),
                        }" class="sm:space-y-3">
                        <div class="hidden gap-1 text-sm sm:grid-cols-3 sm:grid">
                            <strong>Amenity</strong>
                            <strong>Quantity &amp; Price</strong>
                            <strong>Total</strong>
                        </div>
            
                        <div class="space-y-1">
                            {{-- Selected Additional Amenities --}}
                            <div class="grid gap-1 sm:grid-cols-3">
                                @if ($additional_amenities->count() > 0)
                                    @foreach ($additional_amenities as $amenity)
                                        @if ($amenity->is_addons == 0)
                                            @php
                                                $quantity = 0;

                                                foreach ($additional_amenity_quantities as $selected_amenity) {
                                                    if ($selected_amenity['amenity_id'] == $amenity->id) {
                                                        $quantity = $selected_amenity['quantity'];
                                                    }
                                                }
                                            @endphp
                                            
                                            <x-form.select disabled>
                                                <option>{{ $amenity->name }}</option>
                                            </x-form.select>

                                            <div class="grid gap-1 sm:grid-cols-2">
                                                <div>
                                                    <input disabled class="text-xs py-2 sm:py-0 disabled:opacity-50 w-full h-full px-2.5 border outline-0 border-slate-200 rounded-lg focus:outline-none focus:ring-0 focus:border-blue-600" value="{{ $quantity }}" />
                                                </div>
                                                <p class="grid px-2 text-xs border rounded-lg place-items-center">
                                                    <span>
                                                        <x-currency />{{ number_format($amenity->price, 2) }}
                                                    </span>
                                                </p>
                                            </div>
                                            
                                            <div class="flex gap-1">
                                                <p class="grid w-full px-2 py-2 text-xs font-semibold text-blue-500 border border-blue-500 rounded-lg sm:py-0 place-items-center">
                                                    <span>
                                                        <x-currency />{{ number_format($amenity->price * $quantity, 2) }}
                                                    </span>
                                                </p>
                                                <x-tooltip text="Remove Amenity" dir="left">
                                                    <x-secondary-button x-ref="content" type="button" class="p-0 text-xs" wire:click="removeAmenity({{ $amenity->id }})">
                                                        <svg class="mx-auto" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                                    </x-secondary-button>
                                                </x-tooltip>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            
                            {{-- Add Additional Amenity --}}
                            @if ($additional_amenities->count() != $available_amenities->count())
                                <div class="grid-cols-3 gap-1 space-y-1 sm:space-y-0 sm:grid">
                                    <p class="text-xs font-semibold sm:hidden">Amenity</p>
                                    <x-form.select
                                        x-model="additonal_amenity"
                                        wire:model.live="additional_amenity_id"
                                        x-on:change="$wire.selectAmenity(additonal_amenity)"
                                        >
                                        <option value="">Select an Amenity</option>
                                        @foreach ($available_amenities as $amenity)
                                            @if (!$additional_amenities->contains('id', $amenity->id) && !$selected_amenities->contains('id', $amenity->id))
                                                <option value="{{ $amenity->id }}">{{ $amenity->name }}</option>
                                            @endif
                                        @endforeach
                                    </x-form.select>
            
                                    <div class="grid grid-cols-2 gap-1">
                                        <p class="text-xs font-semibold sm:hidden">Quantity</p>
                                        <p class="text-xs font-semibold sm:hidden">Price</p>
            
                                        <div>
                                            <input
                                                x-on:change="$wire.getTotal()"
                                                x-model="additional_amenity_quantity"
                                                wire:model.live="additional_amenity_quantity"
                                                type="number"
                                                min="1"
                                                @if (!empty($additional_amenity_id))
                                                    max="{{ $additional_amenity->quantity }}"
                                                @endif
                                                value="1"
                                                class="text-xs w-full h-full px-2.5 py-2 sm:py-0 border outline-0 border-slate-200 rounded-lg focus:outline-none focus:ring-0 focus:border-blue-600" />
                                        </div>
                                        <p class="grid px-2 py-2 text-xs border rounded-lg border-slate-200 sm:py-0 place-items-center">
                                            @if (!empty($additional_amenity_id))
                                                <span>
                                                    <x-currency />{{ number_format($additional_amenity->price, 2) }}
                                                </span>
                                            @else
                                                <span>
                                                    <x-currency />0.00
                                                </span>
                                            @endif
                                        </p>
                                    </div>
            
                                    <p class="text-xs font-semibold sm:hidden">Total</p>
            
                                    <div class="flex flex-col gap-1 sm:flex-row">
                                        <p class="grid w-full px-2 py-2 text-xs font-semibold text-blue-500 border border-blue-500 rounded-lg sm:py-0 place-items-center">
                                            @if (!empty($additional_amenity_id))
                                                <span>
                                                    <x-currency />{{ number_format($additional_amenity_total, 2) }}
                                                </span>
                                            @else
                                                <span>
                                                    <x-currency />0.00
                                                </span>
                                            @endif
                                        </p>
                                        <x-tooltip text="Add Amenity" dir="left">
                                            <x-primary-button x-ref="content" type="button" class="flex items-center justify-center w-full gap-3 p-0 text-sm sm:text-xs sm:w-min" wire:click="addAmenity()">
                                                <svg class="hidden mx-auto sm:block" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check"><path d="M20 6 9 17l-5-5"/></svg>
                                                <p class="sm:hidden">Add Amenity</p>
                                            </x-primary-button>
                                        </x-tooltip>
                                    </div>
            
                                    {{-- Errors --}}
                                    <div class="grid-cols-3 col-span-3 gap-1 space-y-1 sm:grid sm:space-y-0">
                                        <div>
                                            <x-form.input-error field="additional_amenity" />
                                        </div>
                                        <div>
                                            <x-form.input-error field="additional_amenity_quantity" />
                                        </div>
                                    </div>
                                </div>
                            @else
                                <p class="text-xs font-semibold">
                                    Selected all available amenities
                                </p>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="pt-2 font-semibold text-center">
                        No amenities yet.
                    </div>
                @endif
            </div>
        </section>

        {{-- Save Changes button --}}
        <x-primary-button type="button" wire:click='update()'>Save Changes</x-primary-button>
    </section>

    <section class="sticky self-start w-full overflow-auto border rounded-lg top-5">
        @include('components.app.reservation.summary', [
            'reservation' => $reservation,
            'selected_amenities' => $selected_amenities,
            'selected_rooms' => $selected_rooms,
        ])
    </section>
</div>

{{-- Modal for showing building's rooms --}}
<x-modal.full name="show-building-rooms" maxWidth="lg">
    @if (!empty($selected_building))
        <div x-data="{ floor_number: $wire.entangle('floor_number'),
            floor_count: $wire.entangle('floor_count'),
            column_count: $wire.entangle('column_count'),
            }">
            <header class="flex items-center gap-3 p-5 border-b">
                <x-tooltip text="Back" dir="bottom">
                    <x-icon-button x-ref="content" x-on:click="show = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    </x-icon-button>
                </x-tooltip>
                <hgroup>
                    <h2 class="text-sm font-semibold capitalize">{{ $selected_building->name }} Building</h2>
                    <p class="text-xs text-zinc-800">Click a room to reserve</p>
                </hgroup>
            </header>
            
            {{-- Room List --}}
            <section class="grid p-5 overflow-auto max-h-80 bg-slate-100/50 gap-x-1 gap-y-5"
                @if ($available_rooms->isNotEmpty())
                    style="grid-template-columns: repeat({{ $column_count }}, 1fr)"
                @endif
                @can('update room')
                    x-sort
                @endcan>

                <div wire:loading.delay wire:target='selectBuilding' class="py-5 text-sm font-semibold text-center bg-white border rounded-lg">
                    Amazing rooms incoming!
                </div>

                @forelse ($available_rooms as $room)
                    @php
                        $checked = false;
                        $disabled = false;
                        $reserved = false;
                        if ($selected_rooms->contains('id', $room->id)) {
                            $checked = true;
                        }
                        if ($room->status == \App\Models\Room::STATUS_UNAVAILABLE) {
                            $disabled = true;
                        }
                        if (in_array($room->id, $reserved_rooms)) {
                            $reserved = true;
                        }
                    @endphp
                    <x-form.checkbox-toggle
                        :reserved="$reserved"
                        :disabled="$disabled"
                        :checked="$checked"
                        id="room-{{ $room->id }}"
                        x-on:click="$wire.toggleRoom({{ $room->id }})"
                        class="select-none"
                        >
                        <div class="grid w-full rounded-lg select-none min-w-28 place-items-center aspect-square">
                            <div>
                                <p class="text-xs font-semibold text-center">{{ $room->building->name }}</p>
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
                    <x-icon-button x-ref="content" x-on:click="$wire.upFloor()" x-bind:disabled="floor_number == floor_count">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-from-dot"><path d="m5 9 7-7 7 7"/><path d="M12 16V2"/><circle cx="12" cy="21" r="1"/></svg>
                    </x-icon-button>
                </x-tooltip>
                <x-tooltip text="Down">
                    <x-icon-button x-ref="content" x-on:click="$wire.downFloor()" x-bind:disabled="floor_number == 1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-down-to-dot"><path d="M12 2v14"/><path d="m19 9-7 7-7-7"/><circle cx="12" cy="21" r="1"/></svg>
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
        <div x-data="{ floor_number: $wire.entangle('floor_number'),
            floor_count: $wire.entangle('floor_count'),
            column_count: $wire.entangle('column_count'),
            }">
            <header class="flex items-center gap-3 p-5 border-b">
                <x-tooltip text="Back" dir="bottom">
                    <x-icon-button x-ref="content" x-on:click="show = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    </x-icon-button>
                </x-tooltip>
                <hgroup>
                    <h2 class="text-sm font-semibold capitalize">{{ $selected_type->name }}</h2>
                    <p class="text-xs text-zinc-800">Click a room to reserve</p>
                </hgroup>
            </header>
            
            {{-- Room List --}}
            <section class="grid gap-1 p-5 bg-slate-100/50">

                <div wire:loading.delay wire:target='selectBuilding' class="py-5 text-sm font-semibold text-center bg-white border rounded-lg">
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
                        <div x-data="{ show_rooms: false }" class="flex items-start justify-between gap-3 p-3 bg-white border rounded-md">
                            <div class="flex w-full gap-3">
                                <div class="max-w-[150px] w-full relative">
                                    <x-img src="{{ $thumbnail }}" class="w-full" />
                                    @if ($selected_room_count > 0)
                                        <p class="absolute px-2 py-1 text-xs font-semibold text-white bg-blue-500 rounded-md top-1 left-1 w-max">{{ $selected_room_count }} Selected</p>
                                    @endif
                                </div>

                                <hgroup>
                                    <h3 class="font-semibold">For {{ $capacity }} Guests</h3>
                                    <p class="text-xs text-zinc-800/50">
                                        @if ($rooms->count() > 1)
                                            <span>Available Rooms: {{ $rooms->count() }}</span>
                                        @else
                                            <span>Available Room: {{ $rooms->count() }}</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-zinc-800/50">Average Rate: <x-currency />{ number_format($average_rate, 2) }}</p>
                                </hgroup>
                            </div>
                            
                            <div class="min-w-max">
                                @if ($selected_room_count == $rooms->count())
                                    <x-secondary-button disabled class="text-xs">
                                        All Room Selected
                                    </x-secondary-button>    
                                @else
                                    <x-primary-button type="button" class="text-xs" wire:click="addRoom({{ json_encode($rooms->pluck('id')) }})">
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

{{-- Modal for viewing selected rooms --}}
<x-modal.full name="show-selected-rooms" maxWidth="lg">
    @if (!empty($selected_rooms))
        <div x-data="{ floor_number: $wire.entangle('floor_number'),
            floor_count: $wire.entangle('floor_count'),
            column_count: $wire.entangle('column_count'),
            }">
            <header class="flex items-center gap-3 p-5 border-b">
                <x-tooltip text="Back" dir="bottom">
                    <x-icon-button x-ref="content" x-on:click="show = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    </x-icon-button>
                </x-tooltip>
                <hgroup>
                    <h2 class="text-sm font-semibold capitalize">Selected Rooms</h2>
                    <p class="text-xs">Manage your selected rooms</p>
                </hgroup>
            </header>
            
            {{-- Room List --}}
            <section class="grid gap-1 p-5 bg-slate-100/50">

                <div wire:loading.delay wire:target='selectBuilding' class="py-5 text-sm font-semibold text-center bg-white border rounded-lg">
                    Amazing rooms incoming!
                </div>

                <div class="space-y-1">
                    @forelse ($selected_rooms as $room)
                        <div key="{{ $room->id }}" class="flex items-start justify-between gap-3 p-3 bg-white border rounded-md">
                            <div class="flex items-center w-full gap-3">
                                <div class="hidden w-full sm:block max-w-20">
                                    <x-img src="{{ $room->image_1_path }}" />
                                </div>
                                <hgroup>
                                    <h3 class="text-sm font-semibold">{{ $room->roomType->name }} {{ $room->room_number }}</h3>
                                    <p class="text-xs"><x-currency />{ number_format($room->rate, 2) }}</p>
                                </hgroup>
                            </div>

                            <x-tooltip text="Remove Room" dir="left">
                                <x-danger-button x-ref="content" type="button" class="text-xs min-w-max" wire:click="removeRoom({{ $room->id }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                </x-danger-button>
                            </x-tooltip>
                        </div>
                    @empty
                        <div class="py-10 text-sm font-semibold text-center bg-white border rounded-lg">
                            Select a room
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    @endif
</x-modal.full>
</form>