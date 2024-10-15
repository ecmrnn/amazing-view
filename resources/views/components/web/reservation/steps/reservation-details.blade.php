{{-- Reservation Date & Guest Count --}}
<x-form.form-section class="grid lg:grid-cols-2">
    <x-form.form-header step="1" title="Reservation Date &amp; Guest Count" class="lg:col-span-2" />
    
    <div x-show="!can_select_a_room" x-collapse.duration.1000ms class="lg:grid-cols-2 lg:col-span-2">
        <x-form.form-body class="grid p-0 lg:grid-cols-2 lg:col-span-2">
            <div class="p-5 border-b border-dashed lg:border-r lg:border-b-0">
                <div
                     x-effect="date_in == '' ? date_out = '' : ''"
                     class="grid grid-cols-2 gap-2">
                    <x-form.input-group>
                        <x-form.input-label for="date_in">Check-in Date</x-form.input-label>
                        <x-form.input-date
                            wire:model.live="date_in"
                            x-model="date_in"
                            x-bind:min="`${min.getFullYear()}-${String(min.getMonth() + 1).padStart(2, '0')}-${String(min.getDate()).padStart(2, '0')}`"
                            id="date_in" class="block w-full" />
                        <x-form.input-error field="date_in" />
                    </x-form.input-group>
                    <x-form.input-group>
                        <x-form.input-label for="date_out">Check-Out Date</x-form.input-label>
                        <x-form.input-date x-bind:disabled="date_in == '' || date_in == null"
                            wire:model.live="date_out"
                            x-model="date_out"
                            x-bind:value="date_in == '' ? null : date_out" x-bind:min="date_in"
                            id="date_out" class="block w-full" />
                        <x-form.input-error field="date_out" />
                    </x-form.input-group>
                </div>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-2 gap-2">
                    <x-form.input-group>
                        <x-form.input-label for="adult_count">Number of Adults</x-form.input-label>
                        <x-form.input-number
                            wire:model.live="adult_count"
                            x-model="adult_count"
                            id="adult_count"
                            class="block w-full"
                            min="1" />
                            <x-form.input-error field="adult_count" />
                    </x-form.input-group>
                    <x-form.input-group>
                        <x-form.input-label for="children_count">Number of Children</x-form.input-label>
                        <x-form.input-number x-model="children_count" id="children_count"
                            wire:model.live="children_count"
                            class="block w-full" />
                        <x-form.input-error field="children_count" />
                    </x-form.input-group>
                </div>
            </div>
        </x-form.form-body>
    </div>
</x-form.form-section>

<x-line-vertical />

{{-- Buttons prior to selecting a room --}}
<div x-show="!can_select_a_room" class="flex items-center gap-5">
    <x-secondary-button class="block" wire:click="selectRoom()">Select a Room</x-secondary-button>
    <div wire:loading.delay wire:target="selectRoom" class="text-xs font-semibold">Loading our rooms, please wait...</div>
</div>

<x-secondary-button x-show="can_select_a_room" class="block capitalize" x-on:click="can_select_a_room = false">Edit reservation date &amp; guest count</x-secondary-button>

<x-line-vertical />

{{-- Select a Room --}}
<x-form.form-section>
    <x-form.form-header class="form-header" step="2" title="Select a Room" />

    <div x-show="can_select_a_room" x-collapse.duration.1000ms>
        <x-form.form-body>
            <x-form.input-error field="selected_rooms" class="p-5 pb-0"/>

            <div class="flex flex-col items-start justify-between gap-3 p-5 pb-0 lg:flex-row">
                <p class="max-w-sm text-sm">Browse our list of available rooms below or click the
                    &quot;<span class="font-semibold text-blue-500">Find me a Room</span>&quot;
                    button <span class="lg:hidden">below</span><span class="hidden lg:inline">on the
                        right</span> to find the room for you.</p>
                <x-primary-button type="button" wire:click="suggestRooms">Find me a Room</x-primary-button>
            </div>
            
            <div wire:loading.delay wire:target="suggestRooms" class="block px-5 py-3 m-5 mb-0 text-xs font-semibold border rounded-lg">Amazing rooms incoming!</div>

            @if (!empty($suggested_rooms))
            <div class="p-3 m-5 mb-0 space-y-5 bg-white border rounded-lg border-slate-200">
                <h3 class="text-lg font-semibold">Suggested Rooms</h3>
                <div class="grid-cols-3 gap-2 space-y-3 lg:space-y-0 lg:grid">
                    @forelse ($suggested_rooms as $room)
                        <x-web.reservation.step-1.suggested-room :key="$room->id" :selectedRooms="$selected_rooms" :room="$room" />
                    @empty
                        <div class="col-span-3 text-center text-zinc-800/50">No available rooms at the moment...</div>
                    @endforelse
                    </div>
                </div>
            @endif
            
            {{-- Room Categories --}}
            <div class="p-5 space-y-3">
                <h3 class="text-lg font-semibold">Our Rooms</h3>
                <div class="space-y-1">
                    @forelse ($room_types as $room)
                        <div key="{{ $room->id }}" class="flex items-start justify-between gap-5 p-3 bg-white border rounded-lg">
                            <div class="flex items-start w-full gap-5">
                                <div class="w-full max-w-[150px]">
                                    <x-img-lg src="{{ $room->image_1_path }}" />
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
            </div>
        </x-form.form-body>
    </div>
</x-form.form-section>

<x-line-vertical />

{{-- Add to your Stay --}}
<x-form.form-section>
    <x-form.form-header step="3" title="Add to your Stay" />

    <div x-show="can_select_a_room" x-collapse.duration.1000ms>
        <x-form.form-body>
            <div class="p-5 space-y-3">
                <p class="text-sm">Enhance your stay by availing our additional services.</p>
                <div class="grid gap-2 sm:grid-cols-2">
                    @forelse ($reservable_amenities as $amenity)
                        @php
                            $checked = false;
                            if ($selected_amenities->contains('id', $amenity->id)) {
                                $checked = true;
                            }
                        @endphp

                        <div key="{{ $amenity->id }}">
                            <x-form.checkbox-toggle
                                :checked="$checked"
                                id="amenity{{ $amenity->id }}"
                                name="amenity"
                                wire:click="toggleAmenity({{ $amenity->id }})">
                                <div class="px-3 py-2 select-none">
                                    <div class="w-full font-semibold capitalize text-md">{{ $amenity->name }}</div>
                                    <div class="w-full text-xs">Standard Fee: &#8369;{{ $amenity->price }}</div>
                                </div>
                            </x-form.checkbox-toggle>
                        </div>
                    @empty
                        <div class="col-span-2 text-center text-zinc-800/50">No reservable amenities...</div>
                    @endforelse
                </div>
            </div>
        </x-form.form-body>
    </div>
</x-form.form-section>

<x-line-vertical />

<x-primary-button
    x-on:click="() => { $nextTick(() => { $refs.form.scrollIntoView({ behavior: 'smooth' }); }); }"
    type="submit">Guest Details</x-primary-button>

{{-- Modal for viewing rooms --}}
<x-modal.full name="show-typed-rooms" maxWidth="lg">
    @if (!empty($selected_type))
        <div>
            <header class="flex items-center gap-3 p-5 border-b">
                <x-tooltip text="Back" dir="bottom">
                    <x-icon-button x-ref="content" x-on:click="show = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    </x-icon-button>
                </x-tooltip>
                <hgroup>
                    <h2 class="text-sm font-semibold capitalize">{{ $selected_type->name }}</h2>
                    <p class="text-xs text-zinc-800/50">Click a room to select</p>
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
                                    <x-img-lg src="{{ $thumbnail }}" class="w-full" />
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