@props([
    'addons' => [],
    'available_rooms' => [],
    'available_room_types' => [],
    'buildings' => [],
    'column_count' => '',
    'rooms' => [],
    'reserved_rooms' => [],
    'selected_building' => '',
    'selected_rooms' => '',
])

<x-form.form-section class="relative h-min">
    {{-- Select Room --}}
    <x-form.form-header title="Select a Room" subtitle="Choose which rooms to reserve" />

    <div x-show="can_select_room" x-collapse.duration.1000ms>
        <x-form.form-body>
            <div class="p-5 pt-0 space-y-5">
                <div>
                    {{-- header --}}
                    <div class="flex flex-col items-start justify-between gap-5 sm:flex-row">
                        <hgroup>
                            <h2 class="text-sm font-semibold">Available Rooms</h2>
                            <p class="max-w-xs text-xs">Below are the list of available rooms between
                                {{ date_format(date_create($date_in), 'F j, Y') }} and {{ date_format(date_create($date_out), 'F j, Y') }}.
                            </p>
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
                </template>

                {{-- 2. Map View --}}
                <template x-if="is_map_view">
                    <div class="grid grid-cols-3 gap-1 p-3 rounded-lg place-items-start lg:grid-cols-5 min-h-80 bg-gradient-to-tr from-teal-500/20 to-teal-600/20">
                        @forelse ($buildings as $building)
                            <button type="button" key="{{ $building->id }}" class="w-full" wire:click="selectBuilding({{ $building->id }})">
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
                @if ($selected_rooms->count() > 0)
                    <div x-data="{ hide: true }" class="space-y-5">
                        <div class="flex items-center justify-between">
                            <h3 class="flex items-center gap-3 font-semibold">
                                <p>Selected Rooms</p>
                                <div class="px-2 py-1 text-xs text-blue-800 border border-blue-500 rounded-md bg-blue-50 aspect-square">{{ $selected_rooms->count() }}</div>
                            </h3>
                            <button type="button" x-on:click="hide = false" x-show="hide" class="text-xs font-semibold text-blue-500">Hide Rooms</button>
                            <button type="button" x-on:click="hide = true" x-show="!hide" class="text-xs font-semibold text-blue-500">Show Rooms</button>
                        </div>
                        
                        <div x-show="hide" class="grid gap-5 sm:grid-cols-2">
                            @forelse ($selected_rooms as $room)
                                <div wire:key="{{ $room->id }}" class="relative flex items-center gap-2 px-3 py-2 bg-white border rounded-lg border-slate-200">
                                    {{-- Room Details --}}
                                    <div>
                                        <p class="font-semibold capitalize border-r border-dashed line-clamp-1">{{ $room->room_number}}</p>
                                        <p class="text-sm">Room Rate: <x-currency />{{ $room->rate }} &#47; night</p>
                                        <p class="text-sm text-zinc-800">Good for {{ $room->max_capacity }} guests.</p>
                                    </div>
                                    {{-- Remove Room button --}}
                                    <button
                                        type="button"
                                        class="absolute text-xs font-semibold text-red-500 top-2 right-3"
                                        wire:click="removeRoom({{ $room }})">
                                        <span wire:loading.remove wire:target="removeRoom({{ $room }})">Remove</span>
                                        <span wire:loading wire:target="removeRoom({{ $room }})">Removing</span>
                                    </button>
                                </div>
                            @empty
                                <div class="border rounded-lg">
                                    <x-table-no-data.rooms />
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>
        </x-form.form-body>
    </div>
</x-form.form-section>

{{-- Modal for showing building's rooms --}}
<x-modal.full name="show-building-rooms" maxWidth="lg">
    @if (!empty($selected_building))
        <div x-data="{ floor_number: $wire.entangle('floor_number'),
            floor_count: $wire.entangle('floor_count'),
            column_count: $wire.entangle('column_count'),
            }" wire:key="modal-{{ $modal_key }}">
            <hgroup class="p-5">
                <h2 class="text-lg font-semibold capitalize">{{ $selected_building->name }} Building</h2>
                <p class="text-xs text-zinc-800">Click a room to reserve</p>
            </hgroup>

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
                                x-on:click="$wire.toggleRoom({{ $slot->room->id }})"
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
            <footer class="flex gap-1 p-5 bg-white border-t">
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
            <hgroup class="p-5 pb-0">
                <h2 class="text-lg font-semibold capitalize">{{ $selected_type->name }}</h2>
                <p class="text-xs text-zinc-800">Click a room to reserve</p>
            </hgroup>
            
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
                        <div class="py-5 text-sm font-semibold text-center bg-white border rounded-lg">
                            No available rooms for this type of room
                        </div>
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
                    <p class="text-xs text-zinc-800/50">Manage your selected rooms</p>
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
                                    <p class="text-xs"><x-currency />{{ number_format($room->rate, 2) }}</p>
                                </hgroup>
                            </div>

                            <x-tooltip text="Remove Room" dir="left">
                                <x-danger-button type="button" x-ref="content" class="text-xs min-w-max" wire:click="removeRoom({{ $room->id }})">
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