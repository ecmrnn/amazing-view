<form x-data="{
        min_capacity: @entangle('min_capacity'),
        max_capacity: @entangle('max_capacity'),
        floor_number: @entangle('floor_number'),
        step: @entangle('step'),
    }" class="p-5 space-y-5" x-on:room-created.window="show = false" wire:submit='submit'>
    <hgroup>
        <h2 class="text-lg font-bold">Add Room</h2>
        <p class="text-xs">Fill up this form to add a new <strong class="text-blue-500">{{ $room->name }}</strong> room</p>
    </hgroup>

    <!-- Building -->
    <div x-show="step == 1" class="space-y-5">
        <hgroup>
            <h3 class="text-sm font-semibold">Building Details</h3>
            <p class="text-xs">Identify which building the room belongs to</p>
        </hgroup>

        <div class="grid grid-cols-2 gap-5 p-5 bg-white border rounded-md border-slate-200">
            <x-form.input-group>
                <div>
                    <x-form.input-label for="building">Select a Building</x-form.input-label>
                    <p class="text-xs">Choose a building that this room belongs to</p>
                </div>
                <x-form.select class="flex-grow" id="building" name="building" wire:model.live="building" wire:change="selectBuilding()">
                    @foreach($buildings as $bldg)
                        <option value="{{ $bldg->id }}">{{ $bldg->name }}</option>
                    @endforeach
                </x-form.select>
                <x-form.input-error field="building" />
            </x-form.input-group>

            <x-form.input-group>
                <div>
                    <x-form.input-label for="floor_number">Floor</x-form.input-label>
                    <p class="text-xs">Enter the floor number of this room. Max: {{ $max_floor_number }}</p>
                </div>

                <x-form.input-number id="floor_number" max="{{ $max_floor_number }}" min="1" name="floor_number" wire:model.live="floor_number" x-model="floor_number" />
                <x-form.input-error field="floor_number" />
            </x-form.input-group>
        </div>

        <div class="p-5 space-y-5 border rounded-md border-slate-200">
            <x-form.input-group>
                <div>
                    <x-form.input-label for='selected_slot'>Building Slot</x-form.input-label>
                    <p class="text-xs">Select a slot in the building for your room</p>
                </div>
                <x-form.input-error field="selected_slot" />
            </x-form.input-group>

            <div class="overflow-auto max-h-56">
                @php
                    $floor_slots = $slots->filter(function ($slot) {
                        return $slot->floor == $this->floor_number;
                    })    
                @endphp

                <div class="grid gap-1 mt-1 text-xs font-semibold first-of-type:mt-0" style="grid-template-columns: repeat({{ $floor_slots->max('col') }}, 1fr)">
                    @foreach ($floor_slots as $slot)
                        @if ($slot->room_id)
                        <div wire:key='na-{{ $slot->id }}' class="grid border rounded-md aspect-square bg-slate-50 border-slate-200 place-items-center" type="button">{{ $slot->room->room_number }}</div>
                        @else
                            <button
                                wire:key='a-{{ $slot->id }}'
                                @class([
                                    'border rounded-md aspect-square',
                                    'bg-blue-50 border-blue-500 text-blue-800' => $slot == $selected_slot ?? null,
                                    'border-dashed border-slate-200' => $slot != $selected_slot ?? null,
                                ])
                                wire:click='selectSlot({{ $slot->id }})' type="button">
                                Available
                            </button>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <x-loading wire:loading wire:target='checkBuilding'>Checking form, please wait</x-loading>

        <div class="flex justify-end gap-1">
            <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
            <x-primary-button type="button" wire:loading.attr='disabled' wire:click='checkBuilding()'>Continue</x-primary-button>
        </div>
    </div>

    <div x-show="step == 2" class="space-y-5">
        <h3 class="text-sm font-semibold">General Room Details</h3>

        <div class="p-5 space-y-5 bg-white border rounded-md border-slate-200">
            <!-- Room Number -->
            <x-form.input-group>
                <div>
                    <x-form.input-label for="room_number">Room Number</x-form.input-label>
                    <p class="text-xs">Enter the unique room number of this room</p>
                </div>

                <div class="flex w-full gap-1">
                    @if ($selected_building)
                        <div class="grid px-3 text-sm font-semibold border rounded-md border-slate-200 place-items-center text-zinc-800/75">{{ $selected_building->prefix  }}</div>
                    @endif
                    <x-form.input-text id="room_number" name="room_number" label="Room Number" wire:model.live='room_number' x-on:keypress="$wire.checkRoomNumber()" />
                </div>
                <x-form.input-error field="room_number" />
            </x-form.input-group>
            <!-- Capacity -->
            <div class="grid grid-cols-2 gap-3">
                <x-form.input-group>
                    <x-form.input-label for="min_capacity">Min. Capacity</x-form.input-label>
                    <x-form.input-number id="min_capacity" min="1" name="min_capacity" wire:model.live="min_capacity" x-model="min_capacity" />
                    <x-form.input-error field="min_capacity" />
                </x-form.input-group>

                <x-form.input-group>
                    <x-form.input-label for="max_capacity">Max. Capacity</x-form.input-label>
                    <x-form.input-number id="max_capacity" min="{{ $min_capacity }}" name="max_capacity" wire:model.live="max_capacity" x-model="max_capacity" />
                    <x-form.input-error field="max_capacity" />
                </x-form.input-group>
            </div>
            
            <!-- Image -->
            <x-form.input-group>
                <div>
                    <x-form.input-label for="image_1_path">Image</x-form.input-label>
                    <p class="text-xs">Upload an image of your new room here</p>
                </div>
        
                <x-filepond::upload
                    wire:model.live="image_1_path"
                    placeholder="Drag & drop your image or <span class='filepond--label-action'> Browse </span>"
                />

                <x-form.input-error field="image_1_path" />
            </x-form.input-group>

            {{-- Rate --}}
            <x-form.input-group>
                <div>
                    <x-form.input-label for="rate">Room Rate</x-form.input-label>
                    <p class="text-xs">Enter a price on how much you are going to charge for this room</p>
                </div>
                
                <x-form.input-currency id="rate" name="rate" wire:model.live='rate' class="w-min" />
                <x-form.input-error field="rate" />
            </x-form.input-group>
        </div>
        
        <div class="flex justify-end gap-1">
            <x-secondary-button type="button" x-on:click="$wire.set('step', 1)">Back</x-secondary-button>
            <x-primary-button>Add Room</x-primary-button>
        </div>
    </div>

</form>
