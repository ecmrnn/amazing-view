<form x-data="{ image_count: @entangle('image_count') }" wire:submit='submit' class="max-w-screen-lg mx-auto space-y-5">
    <div class="flex items-center justify-between gap-5 p-5 bg-white border rounded-lg border-slate-200">
        <div class="flex items-center gap-5">
            <x-tooltip text="Back" dir="bottom">
                <a x-ref="content" href="{{ route('app.rooms.index')}}" wire:navigate>
                    <x-icon-button>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    </x-icon-button>
                </a>
            </x-tooltip>
            <hgroup>
                <h2 class="text-lg font-semibold">Edit {{ $room_type->name }}</h2>
                <p class="max-w-sm text-xs">Update {{ $room_type->name }} details here</p>
            </hgroup>
        </div>

        @if ($room_type->rooms->count() == 0)
            <x-actions>
                <x-action-button x-on:click="$dispatch('open-modal', 'delete-room-type-modal')" type="button" class="text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                    <p>Delete Room Type</p>
                </x-action-button>
            </x-actions>
        @endif
    </div>

    <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
        <hgroup>
            <h2 class="font-semibold">General Room Details</h2>
            <p class="text-xs">Update room details here</p>
        </hgroup>
        
        <x-form.input-group>
            <div>
                <x-form.input-label for="name">Room Name & Description</x-form.input-label>
                <p class="text-xs">Enter the name and brief description of your new room</p>
            </div>
            
            <x-form.input-text id="name" name="name" label="Name" wire:model.live='name' />
            <x-form.input-error field="name" />
        </x-form.input-group>

        <x-form.input-group>
            <x-form.textarea id="description" name="description" label="description" class="w-full" wire:model.live="description" />
            <x-form.input-error field="description" />
        </x-form.input-group>

        <x-form.input-group>
            <div class="flex gap-1">
                <x-form.input-group>
                    <x-form.input-label for="min_rate">Minimum Rate</x-form.input-label>
                    <x-form.input-currency id="min_rate" name="min_rate" wire:model.live='min_rate' />
                    <x-form.input-error field="min_rate" />
                </x-form.input-group>
                
                <x-form.input-group>
                    <x-form.input-label for="max_rate">Maximum Rate</x-form.input-label>
                    <x-form.input-currency id="max_rate" name="max_rate" wire:model.live='max_rate' />
                    <x-form.input-error field="max_rate" />
                </x-form.input-group>
            </div>
            <p class="max-w-xs text-xs">Enter the <strong class="text-blue-500">minimum</strong> and <strong class="text-blue-500">maximum</strong> amount of room rate for this new room</p>
        </x-form.input-group>
    </div>

    <div class="p-5 bg-white border rounded-lg border-slate-200">
        <hgroup>
            <h2 class="font-semibold">Image Gallery</h2>
            <p class="text-xs">Upload an image for your thumbnail and rooms</p>
        </hgroup>

        <div class="grid gap-2 mt-5 sm:grid-cols-2 xl:grid-cols-4">
            <x-form.input-group>
                <x-img src="{{ $temp_image_1_path }}" :zoomable="true" />
                
                <x-filepond::upload
                    wire:model.live="image_1_path"
                    placeholder="<span class='filepond--label-action'> Browse </span> Room Thumbnail"
                />
                <x-form.input-error field="image_1_path" />
            </x-form.input-group>

            <x-form.input-group>
                <x-img src="{{ $temp_image_2_path }}" :zoomable="true" />

                <x-filepond::upload
                    wire:model.live="image_2_path"
                    placeholder="<span class='filepond--label-action'> Browse </span> Room Image"
                />
                <x-form.input-error field="image_2_path" />
            </x-form.input-group>

            <x-form.input-group>
                <x-img src="{{ $temp_image_3_path }}" :zoomable="true" />

                <x-filepond::upload
                    wire:model.live="image_3_path"
                    placeholder="<span class='filepond--label-action'> Browse </span> Room Image"
                />
                <x-form.input-error field="image_3_path" />
            </x-form.input-group>
            
            <x-form.input-group>
                <x-img src="{{ $temp_image_4_path }}" :zoomable="true" />

                <x-filepond::upload
                    wire:model.live="image_4_path"
                    placeholder="<span class='filepond--label-action'> Browse </span> Room Image"
                />
                <x-form.input-error field="image_4_path" />
            </x-form.input-group>
        </div>

        <x-note>
            <div class="max-w-xs">Upload a thumbnail and three high definition images that will showcase your new room here</div>
        </x-note>
    </div>

    <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
        <div class="flex items-start justify-between gap-5">
            <hgroup>
                <h2 class='font-semibold'>Inclusions</h2>
                <p class='text-xs'>Enter the general inclusions for this room type</p>
            </hgroup>

            <button type="button" class="text-xs font-semibold text-blue-500" x-on:click="$dispatch('open-modal', 'add-inclusion-modal')">Add Inclusion</button>
        </div>

        @if ($room_type->inclusions->count() > 0)
            <livewire:tables.room-type-inclusions-table :room_type="$room_type" />
        @else
            <div class="font-semibold text-center border rounded-md border-slate-200s">
                <x-table-no-data.room_type_inclusion />
            </div>
        @endif
    </div>

    <x-primary-button>Edit Room Type</x-primary-button> 
</form>