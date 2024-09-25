{{-- @props([
    'roomTypes',
    'availableRooms' => [],
    'selectedRooms' => [],
    'suggestedRooms' => [],
    'reservableAmenities' => [],
    'roomTypeName' => '',
]) --}}

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
            <div class="p-5 m-5 space-y-5 bg-white border rounded-lg border-slate-200">
                <h3 class="text-lg font-semibold">Suggested Rooms</h3>
                <div class="grid-cols-3 gap-2 space-y-3 lg:space-y-0 lg:grid">
                    @forelse ($suggested_rooms as $room)
                        {{-- <p>{{ $room->room_number }}</p> --}}
                        <x-web.reservation.step-1.suggested-room :key="$room->id" :selectedRooms="$selected_rooms" :room="$room" />
                    @empty
                        <div class="col-span-3 text-center text-zinc-800/50">No available rooms at the moment...</div>
                    @endforelse
                    </div>
                    {{-- {{ $suggested_rooms->links() }} --}}
                </div>
            @endif
            
            {{-- Room Categories --}}
            <div class="p-5 m-5 space-y-5 bg-white border rounded-lg border-slate-200">

                <h3 class="text-lg font-semibold">Our Rooms</h3>
                <div class="grid gap-2 sm:grid-cols-2 md:grid-cols-1">
                    @forelse ($room_types as $room)
                        <x-web.reservation.step-1.room-category :key="$room->id" :room="$room" />
                    @empty
                        <div class="p-5 text-center text-zinc-800/50">Oof! No rooms found in the system yet.</div>
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
                        <div key="{{ $amenity->id }}">
                            <x-form.checkbox-toggle id="amenity{{ $amenity->id }}" name="amenity" wire:click="toggleAmenity({{ $amenity->id }})">
                                <div class="select-none">
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
    type="submit">Next: Guest Details</x-primary-button>

{{-- Available Rooms Modal --}}
<x-modal.full name="show-available-rooms">
    <div class="space-y-3">
        <div class="flex items-start justify-between gap-3">
            <div wire:loading.delay wire:target="getAvailableRooms" class="self-center text-sm font-semibold">We are loading your amazing rooms...</div>
            
            <hgroup wire:loading.remove wire:target="getAvailableRooms">
                <h2 class="text-lg font-semibold capitalize">{{ $room_type_name }}</h2>
                @if (count($available_rooms) > 0)
                    <p class="text-sm">Here are the available <span class="font-semibold text-blue-500 capitalize">{{ $room_type_name }}</span> rooms.</p>
                @endif
            </hgroup>

            <x-close-button x-on:click="show = false" />
        </div>

        <div class="border divide-y rounded-lg divide-dashed *:p-3" wire:loading.remove wire:target="getAvailableRooms">
            @forelse ($available_rooms as $room)
                <x-web.reservation.step-1.available-room :key="$room->id" :selectedRooms="$selected_rooms" :room="$room" />
            @empty
                <div class="text-sm font-semibold text-center text-zinc-800/50">No available rooms for this category.</div>  
            @endforelse 
        </div>
    </div>
</x-modal.full>