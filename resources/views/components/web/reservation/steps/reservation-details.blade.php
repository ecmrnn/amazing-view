{{-- Loader --}}
<div class="fixed !m-0 top-0 left-0 z-[9999] w-screen h-screen bg-white place-items-center" wire:loading.delay.long wire:target='submit'>
    <div class="grid h-screen place-items-center">
        <div>
            <p class="text-2xl font-bold text-center">Loading, please wait</p>
            <p class="mb-4 text-xs font-semibold text-center">Preparing amazing things for you~</p>
            <svg class="mx-auto animate-spin" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-loader-circle"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
        </div>
    </div>
</div>

<div class="p-5 space-y-5 bg-white rounded-lg shadow-sm">
    {{-- Step Header --}}
    <div class="flex items-start justify-between">
        <div class="flex flex-col items-start gap-3 sm:gap-5 sm:flex-row">
            <div class="grid w-full text-white bg-blue-500 rounded-md aspect-square max-w-20 place-items-center">
                <p class="text-5xl font-bold">1</p>
            </div>
            <div>
                <p class="text-lg font-bold">Reservation Details</p>
                <p class="max-w-sm text-sm leading-tight">Pick your reservation date, enter guest count, choose a room, as well as addons to avail!</p>
            </div>
        </div>

        <button :class="reservation_type != null ? 'scale-100' : 'scale-0'" type="button" x-on:click="$dispatch('open-modal', 'reset-reservation-modal')" class="flex items-center gap-2 text-xs font-semibold text-red-500 transition-all duration-200 ease-in-out w-max">
            <p>Reset</p>
            <svg class="text-red-500" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
        </button>
    </div>

    <template x-if='reservation_type == null'>
        <x-form.form-section>
            <x-form.form-header title='Reservation Type' subtitle="Choose a Reservation Type" />
        
            <x-form.form-body>
                <div class="col-span-3 p-5 pt-0">
                    <div class="grid gap-5 md:grid-cols-2">
                        <button x-on:click="$wire.set('reservation_type', 'day tour')" type='button' class="p-5 text-left transition-all duration-200 ease-in-out border border-transparent rounded-md shadow-sm bg-slate-50 hover:border-blue-500 hover:bg-blue-50 hover:text-blue-800">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sun-medium"><circle cx="12" cy="12" r="4"/><path d="M12 3v1"/><path d="M12 20v1"/><path d="M3 12h1"/><path d="M20 12h1"/><path d="m18.364 5.636-.707.707"/><path d="m6.343 17.657-.707.707"/><path d="m5.636 5.636.707.707"/><path d="m17.657 17.657.707.707"/></svg>
                            <strong class="block mt-3">Day Tour</strong>
                            <p class="text-sm">Perfect for a quick staycation <br /> from <time datetime="8:00">8:00 AM</time> to <time datetime="18:00">6:00 PM</time></p>
                        </button>
                        <button x-on:click="$wire.set('reservation_type', 'overnight')" type='button' class="p-5 text-left transition-all duration-200 ease-in-out border border-transparent rounded-md shadow-sm bg-slate-50 hover:border-blue-500 hover:bg-blue-50 hover:text-blue-800"">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-moon"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
                            <strong class="block mt-3">Overnight</strong>
                            <p class="text-sm">Ideal for a relaxing overnight stay <br /> from <time datetime="14:00">2:00 PM</time> to <time datetime="12:00">12:00 PM</time></p>
                        </button>
                    </div>
                </div>
            </x-form.form-body>
        </x-form.form-section>
    </template>

    <template x-if='reservation_type != null'>
        <div class="space-y-5">
            <p class="text-sm">Current reservation is for <span x-text='reservation_type'></span> stays, <button class="font-semibold" type="button" wire:click='updateReservationType'>click here to change.</button></p>
            
            {{-- Reservation Date & Guest Count --}}
            <x-form.form-section class="grid lg:grid-cols-2">
                <div class="relative lg:col-span-2">
                    <x-form.form-header title="Reservation Date &amp; Guest Count" subtitle="Select dates and enter number of guests" class="lg:col-span-2" />
            
                    <button type="button"
                        :class="can_select_a_room ? 'scale-100' : 'scale-0'"
                        class="absolute right-0 px-5 py-2 transition-all duration-200 ease-in-out -translate-y-1/2 top-1/2"
                        x-on:click="can_select_a_room = false">
                        <p class="text-xs font-semibold">Edit</p>
                    </button>
                </div>
            
                <div x-show="!can_select_a_room" x-collapse.duration.1000ms class="lg:grid-cols-2 lg:col-span-2">
                    <x-form.form-body class="grid gap-5 p-5 pt-0 md:grid-cols-2 lg:col-span-2">
                        <div
                            x-effect="date_in == '' ? date_out = '' : '';"
                            class="grid gap-5"
                            x-bind:class="{
                                'sm:grid-cols-2': reservation_type == 'overnight',
                                'grid-cols-1': reservation_type == 'day tour'
                            }">
                            <x-form.input-group>
                                <x-form.input-label for="date_in">Check-in Date</x-form.input-label>
                                <x-form.input-date
                                    wire:model.live="date_in"
                                    x-model="date_in"
                                    x-bind:min="min_date_in"
                                    name="date_in"
                                    x-on:input="$wire.setMinDateOut($event.target.value); if (reservation_type == 'day tour') date_out = date_in;"
                                    id="date_in" class="block w-full" />
                                <x-form.input-error field="date_in" />
                            </x-form.input-group>
                            <div x-show='reservation_type == "overnight"'>
                                <x-form.input-group>
                                    <x-form.input-label for="date_out">Check-Out Date</x-form.input-label>
                                    <x-form.input-date x-bind:disabled="date_in == '' || date_in == null"
                                        wire:model.live="date_out"
                                        x-model="date_out"
                                        x-bind:value="date_in == '' ? null : date_out"
                                        x-bind:min="min_date_out"
                                        x-bind:max="max_date"
                                        name="date_out"
                                        id="date_out" class="block w-full" />
                                    <x-form.input-error field="date_out" />
                                </x-form.input-group>
                            </div>
                        </div>
            
                        <div class="grid gap-5 sm:grid-cols-2">
                            <x-form.input-group>
                                <x-form.input-label for="adult_count">Number of Adults</x-form.input-label>
                                <x-form.input-number
                                    wire:model.live="adult_count"
                                    x-model="adult_count"
                                    max="30"
                                    id="adult_count"
                                    name="number_of_adults"
                                    class="block w-full"
                                    min="1" />
                                <x-form.input-error field="adult_count" />
                            </x-form.input-group>
        
                            <x-form.input-group>
                                <x-form.input-label for="children_count">Number of Children</x-form.input-label>
                                <x-form.input-number x-model="children_count" id="children_count"
                                    wire:model.live="children_count"
                                    max="30"
                                    class="block w-full" />
                                <x-form.input-error field="children_count" />
                            </x-form.input-group>
                        </div>
            
                        <div class="flex flex-col gap-5 md:items-center md:flex-row md:col-span-2">
                            <x-primary-button type="button" class="block" wire:click="selectRoom()">Select Accommodation</x-primary-button>
                            <x-loading wire:loading.delay wire:target="selectRoom" class="text-xs font-semibold">Loading our accommodations, please wait...</x-loading>
                        </div>
                    </x-form.form-body>
                </div>
            </x-form.form-section>
            
            {{-- Select a Room --}}
            <x-form.form-section>
                <x-form.form-header class="form-header" title="Select Accommodation" subtitle="Choose which rooms to reserve" />
            
                <div x-show="can_select_a_room" x-collapse.duration.1000ms>
                    <x-form.form-body>
                        <div class="flex flex-col items-start justify-between gap-5 px-5 lg:flex-row">
                            <p class="max-w-sm text-sm">Browse our list of available rooms below or click the
                                &quot;<span class="font-semibold text-blue-500">Find me a Room</span>&quot;
                                button <span class="lg:hidden">below</span><span class="hidden lg:inline">on the
                                    right</span> to find the room for you.</p>
                            <x-primary-button class="text-xs" type="button" wire:click="suggestRooms">Find me a Room</x-primary-button>
                        </div>
            
                        <x-loading wire:loading.delay wire:target="suggestRooms" class="block w-full px-5 py-3 m-5 mb-0 text-xs font-semibold border rounded-md">Amazing rooms incoming!</x-loading>
            
                        @if (!empty($suggested_rooms))
                            <div class="p-5 m-5 mb-0 space-y-5 bg-white border rounded-lg border-slate-200">
                                <div class="grid gap-5 md:grid-cols-3">
                                    @forelse ($suggested_rooms as $key => $room)
                                        <div key="{{ $key }}" class="gap-3 border-slate-200 lg:space-y-2">
                                            <x-img class="w-full" src="{{ $room->image_1_path }}" />
                
                                            <div class="w-full space-y-2">
                                                <hgroup>
                                                    <h4 class="font-semibold capitalize text-md">Good for {{ $room->max_capacity }} guests</h4>
                                                    <p class="text-sm font-semibold"><x-currency />{{ $room->rate }} &#47; night</p>
                                                </hgroup>
                
                                                <p class="text-xs text-zinc-800/50">{{ $room->roomType->name }}</p>
                
                                                <div class="flex flex-col gap-1 sm:flex-row lg:flex-col xl:flex-row">
                                                    {{-- Identify if the room is already selected or not --}}
                                                    @if ($selected_rooms->contains('id', $room->id))
                                                        <x-secondary-button type="button" wire:click="removeRoom({{ $room->id }})">Remove Room</x-secondary-button>
                                                    @else
                                                        <x-primary-button type="button" wire:click="addRoom({{ json_encode(array($room->id)) }})">Book this Room</x-primary-button>
                                                    @endif
                
                                                    <x-secondary-button type="button" wire:click="viewRoom({{ $room->id }})">Details</x-secondary-button>
                                                </div>
                                            </div>

                                            <x-modal.full name='view-room-{{ $room->id }}' maxWidth='md'>
                                                <div class="p-5 space-y-5">
                                                    <x-img src="{{ $room->image_1_path }}" />

                                                    <div class="flex items-start justify-between">
                                                        <div>
                                                            <h3 class="text-lg font-semibold">{{ $room->roomType->name }} {{ $room->room_number }}</h3>
                                                            <p class="text-sm font-semibold"><x-currency />{{ $room->rate }} &#47; night</p>
                                                        </div>

                                                        @if ($selected_rooms->contains('id', $room->id))
                                                            <x-secondary-button type="button" class="text-xs" wire:click="removeRoom({{ $room->id }})">Remove Room</x-secondary-button>
                                                        @else
                                                            <x-primary-button type="button" class="text-xs" wire:click="addRoom({{ json_encode(array($room->id)) }})">Book this Room</x-primary-button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </x-modal.full>
                                        </div>
                                    @empty
                                        <div class="col-span-3 text-center text-zinc-800/50">No available rooms at the moment...</div>
                                    @endforelse
                                </div>
                            </div>
                        @endif
            
                        {{-- Room Categories --}}
                        <div x-data="{ show: true }" class="p-5 space-y-5">
                            <h3 class="font-semibold">Our Rooms</h3>
                            <x-form.input-error field="selected_rooms" />
            
                            <div class="space-y-5">
                                @forelse ($room_types as $room)
                                    <div key="{{ $room->id }}" class="flex flex-col items-start justify-between gap-3 p-3 bg-white border rounded-lg shadow-sm sm:flex-row">
                                        <div class="flex flex-col items-start w-full gap-3 sm:flex-row">
                                            <div class="w-full lg:max-w-[150px]">
                                                <x-img src="{{ $room->image_1_path }}" />
                                            </div>
                                            <div>
                                                <h3 class="text-sm font-semibold">{{ $room->name }}</h3>
                                                <p class="max-w-xs text-xs line-clamp-3">{{ $room->description }}</p>
                                            </div>
                                        </div>
            
                                        <x-secondary-button class="flex-shrink-0 text-xs" wire:click="viewRooms({{ $room->id }})">
                                            View Rooms
                                        </x-secondary-button>
                                    </div>
                                @empty
                                    <div class="border rounded-lg">
                                        <x-table-no-data.rooms />
                                    </div>
                                @endforelse
                            </div>
            
                            @if ($selected_rooms->count() > 0)
                                <div class="flex items-center justify-between">
                                    <h3 class="flex items-center gap-3 font-semibold">
                                        <p>Selected Rooms</p>
                                        <div class="px-2 py-1 text-xs text-blue-800 border border-blue-500 rounded-md bg-blue-50 aspect-square">{{ $selected_rooms->count() }}</div>
                                    </h3>
                                    <button type="button" x-on:click="show = false" x-show="show" class="text-xs font-semibold text-blue-500">Hide Rooms</button>
                                    <button type="button" x-on:click="show = true" x-show="!show" class="text-xs font-semibold text-blue-500">Show Rooms</button>
                                </div>
            
                                <div x-show="show" class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                    @forelse ($selected_rooms as $room)
                                    <div wire:key="{{ $room->id }}" class="relative flex items-center gap-2 px-3 py-2 bg-white border rounded-lg border-slate-200">
                                        {{-- Room Details --}}
                                        <div>
                                            <p class="font-semibold capitalize border-r border-dashed line-clamp-1">{{ $room->roomType->name }}</p>
                                            <p class="text-sm">
                                                {{ $room->room_number }}: &#8369;{{ $room->rate }} &#47; night</p>
                                            <p class="text-xs text-zinc-800">Good for {{ $room->max_capacity }} guests.</p>
                                        </div>
                                        {{-- Remove Room button --}}
                                        <button
                                            type="button"
                                            class="absolute text-xs font-semibold text-red-500 top-2 right-3"
                                            wire:click="removeRoom({{ $room->id }})">
                                            <span wire:loading.remove wire:target="removeRoom({{ $room->id }})">Remove</span>
                                            <x-loading wire:loading wire:target="removeRoom({{ $room->id }})">Removing</x-loading>
                                        </button>
                                    </div>
                                    @empty
                                        <div class="border rounded-lg">
                                            <x-table-no-data.rooms />
                                        </div>
                                    @endforelse
                                </div>
                            @endif
                        </div>
                    </x-form.form-body>
                </div>
            </x-form.form-section>
            
            {{-- Add to your Stay --}}
            <x-form.form-section>
                <x-form.form-header title="Add to your Stay" subtitle="Avail our additional services" />
            
                <div x-show="can_select_a_room" x-collapse.duration.1000ms>
                    <x-form.form-body>
                        <div class="p-5 pt-0 space-y-5">
                            <p class="text-sm">Enhance your stay by availing our additional services!</p>
            
                            <div class="grid gap-5 sm:grid-cols-2">
                                @forelse ($additional_services as $service)
                                    @php
                                        $checked = false;

                                        if ($selected_services->contains('id', $service->id)) {
                                            $checked = true;
                                        }
                                    @endphp
            
                                    <div key="{{ $service->id }}">
                                        <x-form.checkbox-toggle
                                            :checked="$checked"
                                            id="services-{{ $service->id }}"
                                            name="services-"
                                            wire:click="toggleService({{ $service->id }})">
                                            <div class="px-3 py-2 select-none">
                                                <div class="w-full font-semibold capitalize text-md">{{ $service->name }}</div>
                                                <div class="w-full text-xs">Standard Fee: &#8369;{{ $service->price }}</div>
                                            </div>
                                        </x-form.checkbox-toggle>
                                    </div>
                                @empty
                                    <div class="col-span-2 text-center text-zinc-800/50">No Additional Services...</div>
                                @endforelse
                            </div>
                        </div>
                    </x-form.form-body>
                </div>
            </x-form.form-section>
            
            <x-primary-button
                x-bind:class="!can_select_a_room ? 'h-0 overflow-hidden scale-0' : 'h-full scale-100'"
                x-on:click="() => { $nextTick(() => { $refs.form.scrollIntoView({ behavior: 'smooth' }); }); }"
                wire:click='submit'>
                Continue
            </x-primary-button>
        </div>
    </template>
</div>

{{-- Modal for viewing rooms --}}
<x-modal.full name="show-typed-rooms" maxWidth="lg">
    @if (!empty($selected_type))
        <div class="p-5 space-y-5">
            <hgroup>
                <h2 class="text-lg font-semibold capitalize">{{ $selected_type->name }}</h2>
                <p class="text-xs">Click a room to reserve</p>
            </hgroup>
            
            {{-- Room List --}}
            <section class="grid flex-grow h-full gap-1 bg-slate-100/50">
                <x-loading wire:loading.delay wire:target='selectBuilding' class="py-5 text-sm font-semibold text-center bg-white border rounded-lg">Amazing rooms incoming!</x-loading>
                <div class="space-y-2">
                    @forelse ($available_room_types as $capacity => $rooms)
                        @php
                            $rate_sum = 0;
                            $selected_room_count = 0;
                            foreach ($rooms as $room) {
                                $rate_sum += $room->rate;
                                $thumbnail = $room->roomType->image_1_path;
                                if ($selected_rooms->contains('id', $room->id)) {
                                    $selected_room_count++;
                                }
                            }
                            $average_rate = $rate_sum / intval($rooms->count());
                        @endphp
                        <div x-data="{ show_rooms: false }" class="flex flex-col items-start justify-between gap-3 p-3 bg-white border rounded-md sm:flex-row">
                            <div class="flex flex-col w-full gap-3 sm:flex-row">
                                <div class="sm:max-w-[150px] w-full relative">
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
                                    <p class="text-xs text-zinc-800/50">Average Rate: <x-currency />{{ number_format($average_rate, 2) }}</p>
                                </hgroup>
                            </div>
            
                            <div class="min-w-max">
                                @if ($selected_room_count == $rooms->count())
                                    <x-secondary-button disabled class="text-xs">
                                        No Remaining Rooms
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