<form x-data="{
        min_capacity: @entangle('min_capacity'),
        max_capacity: @entangle('max_capacity'),
    }" wire:submit="submit" class="grid grid-cols-2 gap-5">
    <div class="space-y-5">
        <!-- Building -->
        <div class="space-y-3">
            <div class="flex gap-3 p-3 border rounded-lg border-slate-200">
                <p class="pl-1 text-lg font-semibold text-blue-500">{{ $room->floor_number . 'F' }}</p>
                <p class="text-lg font-semibold">{{ $room->building->name }}</p>
            </div> 
        </div>

        <div class="space-y-3">
            <h3 class="text-sm font-semibold">General Room Details</h3>
            <div class="p-3 space-y-3 border rounded-lg border-slate-200">
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

        <x-primary-button>Edit Room</x-primary-button>
    </div>
    {{-- Note --}}
</form>