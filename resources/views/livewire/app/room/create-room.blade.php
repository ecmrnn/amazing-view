<form x-data="{
    min_capacity: @entangle('min_capacity'),
    max_capacity: @entangle('max_capacity'),
    floor_number: @entangle('floor_number'),
}" class="p-5 space-y-5 bg-white rounded-lg" x-on:room-created.window="show = false" wire:submit='submit'>
    <hgroup>
        <h2 class="text-lg font-bold">Add Room</h2>
        <p class="text-xs">Fill up this form to add a new <strong class="text-blue-500">{{ $room->name }}</strong> room</p>
    </hgroup>

    <!-- Building -->
    <div class="space-y-3">
        <h3 class="text-sm font-semibold">Building Details</h3>
        <div class="flex gap-3 p-3 border border-gray-300 rounded-lg">
            <div class="flex flex-col self-start w-full gap-3">
                <div>
                    <x-form.input-label for="building_id">Select a Building</x-form.input-label>
                    <p class="text-xs">Choose a building that this room belongs to</p>
                </div>
                <x-form.select class="flex-grow" id="building_id" name="building_id" wire:model.live="building_id" wire:change="selectBuilding()">
                    <option value="">Select a Building</option>
                    @foreach($buildings as $bldg)
                        <option value="{{ $bldg->id }}">{{ $bldg->name }}</option>
                    @endforeach
                </x-form.select>
            </div>
            <div class="w-full space-y-3">
                <div>
                    <x-form.input-label for="floor_number">Floor</x-form.input-label>
                    <p class="text-xs">Enter the floor number of this room. Max: {{ $max_floor_number }}</p>
                </div>
                <x-form.input-number id="floor_number" max="{{ $max_floor_number }}" min="1" name="floor_number" wire:model.live="floor_number" x-model="floor_number" />
                <x-form.input-error field="floor_number" />
            </div>
        </div>
    </div>

    <div class="space-y-3">
        <h3 class="text-sm font-semibold">General Room Details</h3>
        <div class="p-3 space-y-3 border border-gray-300 rounded-lg">
            <!-- Room Number -->
            <div class="space-y-3">
                <div>
                    <x-form.input-label for="room_number">Room Number</x-form.input-label>
                    <p class="text-xs">Enter the unique room number of this room</p>
                </div>

                <div class="flex w-full gap-1">
                    @if (!empty($building))
                        <div class="grid px-3 text-sm font-semibold border border-gray-300 rounded-md place-items-center text-zinc-800/75">{{ $building->prefix  }}</div>
                    @endif
                    <x-form.input-text id="room_number" name="room_number" label="Room Number" wire:model.live='room_number_input' x-on:keypress="$wire.formatRoomNumber()" />
                </div>
                <x-form.input-error field="room_number" />
            </div>
            <!-- Capacity -->
            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-3">
                    <x-form.input-label for="min_capacity">Min. Capacity</x-form.input-label>
                    <x-form.input-number id="min_capacity" min="1" name="min_capacity" wire:model.live="min_capacity" x-model="min_capacity" />
                    <x-form.input-error field="min_capacity" />
                </div>
                <div class="space-y-3">
                    <x-form.input-label for="max_capacity">Max. Capacity</x-form.input-label>
                    <x-form.input-number id="max_capacity" min="{{ $min_capacity }}" name="max_capacity" wire:model.live="max_capacity" x-model="max_capacity" />
                    <x-form.input-error field="max_capacity" />
                </div>
            </div>
            
            <!-- Image -->
            <div class="space-y-3">
                <div>
                    <x-form.input-label for="image_1_path">Image</x-form.input-label>
                    <p class="text-xs">Upload an image of your new room here</p>
                </div>
        
                <x-filepond::upload
                    wire:model.live="image_1_path"
                    placeholder="Drag & drop your image or <span class='filepond--label-action'> Browse </span>"
                />

                <x-form.input-error field="image_1_path" />
            </div>

            {{-- Rate --}}
            <div class="space-y-3">
                <div>
                    <x-form.input-label for="rate">Room Rate</x-form.input-label>
                    <p class="text-xs">Enter a price on how much you are going to charge for this room</p>
                </div>
                
                <x-form.input-currency id="rate" name="rate" wire:model.live='rate' class="w-min" />
                <x-form.input-error field="rate" />
            </div>
        </div>
    </div>

    <x-primary-button>
        Add Room
    </x-primary-button>
</form>
