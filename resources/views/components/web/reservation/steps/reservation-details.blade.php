{{-- Loader --}}
<div class="fixed top-0 left-0 z-50 w-screen h-screen bg-white place-items-center" wire:loading.delay.long wire:target='submit'>
    <div class="grid h-screen place-items-center">
        <div>
            <p class="text-2xl font-bold text-center">Loading, please wait</p>
            <p class="mb-4 text-xs font-semibold text-center">Preparing amazing things for you~</p>
            <svg class="mx-auto animate-spin" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-loader-circle"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
        </div>
    </div>
</div>

<div class="space-y-5">
    {{-- Reservation Date & Guest Count --}}
    <x-form.form-section class="grid lg:grid-cols-2">
        <div class="relative lg:col-span-2">
            <x-form.form-header step="1" title="Reservation Date &amp; Guest Count" class="lg:col-span-2" />
    
            <button type="button"
                :class="can_select_a_room ? 'scale-100' : 'scale-0'"
                class="absolute right-0 px-5 py-2 transition-all duration-200 ease-in-out -translate-y-1/2 top-1/2"
                x-on:click="can_select_a_room = false">
                <p class="text-xs font-semibold">Edit</p>
            </button>
        </div>
    
        <div x-show="!can_select_a_room" x-collapse.duration.1000ms class="lg:grid-cols-2 lg:col-span-2">
            <x-form.form-body class="grid p-0 lg:grid-cols-2 lg:col-span-2">
                <div class="px-5 border-b border-dashed lg:border-r lg:border-b-0">
                    <div
                         x-effect="date_in == '' ? date_out = '' : '';"
                         class="grid grid-cols-2 gap-2">
                        <x-form.input-group>
                            <x-form.input-label for="date_in">Check-in Date</x-form.input-label>
                            <x-form.input-date
                                wire:model.live="date_in"
                                x-model="date_in"
                                x-bind:min="`${min.getFullYear()}-${String(min.getMonth() + 1).padStart(2, '0')}-${String(min.getDate() + 1).padStart(2, '0')}`"
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
    
                <div class="px-5">
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
    
                <div class="flex justify-between p-5 pt-5 lg:col-span-2">
                    <div class="flex items-center gap-5">
                        <x-primary-button type="button" class="block" wire:click="selectRoom()">Select a Room</x-primary-button>
                        <div wire:loading.delay wire:target="selectRoom" class="text-xs font-semibold">Loading our rooms, please wait...</div>
                    </div>
    
                    <div class="flex gap-3">
                        <p class="hidden max-w-xs text-xs text-right md:block">If you have a Senior or PWD fellow guest, click the button here</p>
                        <x-secondary-button class="text-xs" x-on:click="$dispatch('open-modal', 'pwd-senior-modal');">
                            Add Senior or PWD
                        </x-secondary-button>
                    </div>
                </div>
            </x-form.form-body>
        </div>
    </x-form.form-section>
    
    {{-- Select a Room --}}
    <x-form.form-section>
        <x-form.form-header class="form-header" step="2" title="Select a Room" />
    
        <div x-show="can_select_a_room" x-collapse.duration.1000ms>
            <x-form.form-body>
                <div class="flex flex-col items-start justify-between gap-3 px-5 lg:flex-row">
                    <p class="max-w-sm text-sm">Browse our list of available rooms below or click the
                        &quot;<span class="font-semibold text-blue-500">Find me a Room</span>&quot;
                        button <span class="lg:hidden">below</span><span class="hidden lg:inline">on the
                            right</span> to find the room for you.</p>
                    <x-primary-button class="text-xs" type="button" wire:click="suggestRooms">Find me a Room</x-primary-button>
                </div>
    
                <div wire:loading.delay wire:target="suggestRooms" class="block px-5 py-3 m-5 mb-0 text-xs font-semibold border rounded-lg">Amazing rooms incoming!</div>
    
                @if (!empty($suggested_rooms))
                <div class="p-5 m-5 mb-0 space-y-5 bg-white border rounded-lg border-slate-200">
                    <div class="grid-cols-3 gap-2 space-y-5 lg:space-y-0 lg:grid">
                        @forelse ($suggested_rooms as $key => $room)
                            <div key="{{ $key }}" class="flex gap-3 border-slate-200 lg:block lg:space-y-2">
                                <x-img-lg class="w-full max-w-[200px] lg:max-w-full" src="{{ asset('storage/' . $room->image_1_path) }}" />
    
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
                                        <div class="w-full rounded-md aspect-video" 
                                            style="background-image: url('{{ asset('storage/' . $room->image_1_path) }}');
                                                background-position: center;
                                                background-size: cover;"></div>

                                        <div class="flex items-start justify-between">
                                            <div>
                                                <h3 class="text-lg font-semibold">{{ $room->roomType->name }} {{ $room->building->prefix . ' ' . $room->room_number }}</h3>
                                                <p class="text-sm font-semibold"><x-currency />{{ $room->rate }} &#47; night</p>
                                            </div>

                                            @if ($selected_rooms->contains('id', $room->id))
                                                <x-secondary-button type="button" class="text-xs" wire:click="removeRoom({{ $room->id }})">Remove Room</x-secondary-button>
                                            @else
                                                <x-primary-button type="button" class="text-xs" wire:click="addRoom({{ json_encode(array($room->id)) }})">Book this Room</x-primary-button>
                                            @endif
                                        </div>

                                        <ul class="grid grid-cols-2 gap-1">
                                            @if ($room->amenities->count() > 0)
                                                <div class="flex items-center col-span-2 gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles"><path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/><path d="M20 3v4"/><path d="M22 5h-4"/><path d="M4 17v2"/><path d="M5 18H3"/></svg>
                                                    <p class="font-semibold">Amenities</p>
                                                </div>
                                            @endif
                                            
                                            @forelse ($room->amenities as $amenity)
                                                <li class="px-3 py-2 text-sm border rounded-md">{{ $amenity->name }}</li>
                                            @empty
                                                <p class="col-span-2 py-3 text-sm font-semibold text-center border rounded-md opacity-50">This room has no amenity.</p>
                                            @endforelse
                                        </ul>
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
                <div x-data="{ show: true }" class="p-5 space-y-3">
                    <h3 class="font-semibold">Our Rooms</h3>
                    <x-form.input-error field="selected_rooms" />
    
                    <div class="space-y-1">
                        @forelse ($room_types as $room)
                            <div key="{{ $room->id }}" class="flex flex-col items-start justify-between gap-3 p-3 bg-white border rounded-lg shadow-sm sm:flex-row">
                                <div class="flex flex-col items-start w-full gap-3 sm:flex-row">
                                    <div class="w-full lg:max-w-[150px]">
                                        <x-img-lg src="{{ $room->image_1_path }}" />
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
                            <h3 class="font-semibold">Selected Rooms</h3>
                            <button type="button" x-on:click="show = false" x-show="show" class="text-xs font-semibold text-blue-500">Hide Rooms</button>
                            <button type="button" x-on:click="show = true" x-show="!show" class="text-xs font-semibold text-blue-500">Show Rooms</button>
                        </div>
    
                        <div x-show="show" class="grid grid-cols-2 gap-1">
                            @forelse ($selected_rooms as $room)
                            <div wire:key="{{ $room->id }}" class="relative flex items-center gap-2 px-3 py-2 bg-white border rounded-lg border-slate-200">
                                {{-- Room Details --}}
                                <div>
                                    <p class="font-semibold capitalize border-r border-dashed line-clamp-1">{{ $room->roomType->name }}</p>
                                    <p class="text-sm">
                                        <span class="uppercase">{{ $room->building->prefix }}</span>
                                        {{ $room->room_number }}: &#8369;{{ $room->rate }} &#47; night</p>
                                    <p class="text-xs text-zinc-800">Good for {{ $room->max_capacity }} guests.</p>
                                </div>
                                {{-- Remove Room button --}}
                                <button
                                    type="button"
                                    class="absolute text-xs font-semibold text-red-500 top-2 right-3"
                                    wire:click="removeRoom({{ $room->id }})">
                                    <span wire:loading.remove wire:target="removeRoom({{ $room->id }})">Remove</span>
                                    <span wire:loading wire:target="removeRoom({{ $room->id }})">Removing</span>
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
        <x-form.form-header step="3" title="Add to your Stay" />
    
        <div x-show="can_select_a_room" x-collapse.duration.1000ms>
            <x-form.form-body>
                <div class="p-5 pt-0 space-y-3">
                    <p class="text-sm">Enhance your stay by availing our additional services</p>
    
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
    
    <x-primary-button
        x-on:click="() => { $nextTick(() => { $refs.form.scrollIntoView({ behavior: 'smooth' }); }); }"
        type="submit">Guest Details
    </x-primary-button>
</div>

{{-- Modal for viewing rooms --}}
<x-modal.full name="show-typed-rooms" maxWidth="lg">
    @if (!empty($selected_type))
        <div class="flex flex-col min-h-screen sm:min-h-0">
            <header class="sticky top-0 flex items-center gap-3 p-5 border-b">
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
            <section class="grid flex-grow h-full gap-1 p-5 bg-slate-100/50">
                <div wire:loading.delay wire:target='selectBuilding' class="py-5 text-sm font-semibold text-center bg-white border rounded-lg">
                    Amazing rooms incoming!
                </div>
                <div class="space-y-1">
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
                                    <x-img-lg src="{{ asset('storage/' . $thumbnail) }}" class="w-full" />
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

<x-modal.full name='pwd-senior-modal' maxWidth='sm'>
    <div class="p-5 space-y-5">
        <hgroup>
            <h3 class="font-bold">Add Senior or PWD</h3>
            <p class="text-xs">If your fellow guest are senior or with disability</p>
        </hgroup>

        <div class="space-y-3">
            {{-- <x-note>The maximum number of senior and PWD is based on the number of adults and children you have entered respectively</x-note> --}}
            <div class="grid grid-cols-2 gap-3">
                <x-form.input-group>
                    <x-form.input-label for="senior_count">Number of Seniors</x-form.input-label>
                    <x-form.input-number 
                        max="{{ $max_senior_count }}"
                        x-model="senior_count" id="senior_count" 
                        wire:model.live="senior_count"
                        x-on:change.window="setMaxSeniorCount()"
                        class="block w-full" />
                    <x-form.input-error field="senior_count" />
                </x-form.input-group>

                <x-form.input-group>
                    <x-form.input-label for="pwd_count">Number of PWD</x-form.input-label>
                    <x-form.input-number 
                        max="{{ ($adult_count - $senior_count) + $children_count }}"
                        x-model="pwd_count" 
                        id="pwd_count"
                        wire:model.live="pwd_count"
                        x-on:change.window="setMaxSeniorCount()"
                        class="block w-full" />
                    <x-form.input-error field="pwd_count" />
                </x-form.input-group>
            </div>
            
            <div class="p-3 space-y-3 border border-gray-300 rounded-lg">
                <h4 class="text-sm font-bold">Guest Summary</h4>

                <div>
                    <p class="text-sm">
                        Adults: <span x-text="adult_count"></span> Adult<span x-show="adult_count > 1">s</span>
                        <span x-show="senior_count > 0">&lpar;<span x-text="senior_count"></span>  Senior<span x-show="senior_count > 1">s</span>&rpar;</span>
                    </p>
                    <p class="text-sm">Children: <span x-text="children_count"></span> Child<span s-how="children_count > 1">ren</span></p>
                    <p class="text-sm">PWD: <span x-text="pwd_count"></span> PWD<span x-show="pwd_count > 1">s</span></p>
                    <p class="text-sm"><strong class="text-blue-500">Total Guests: {{ $adult_count + $children_count }}</strong></p>
                </div>
            </div>
        </div>
    </div>
</x-modal.full>