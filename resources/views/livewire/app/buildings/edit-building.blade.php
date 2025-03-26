<section x-data="{
        floor_count: @entangle('floor_count'),
        room_row_count: @entangle('room_row_count'),
        room_col_count: @entangle('room_col_count'),
    }"
    x-on:building-edited.window="show = false" class="max-w-screen-lg mx-auto space-y-5">
    <div class="flex items-center justify-between p-5 bg-white border rounded-lg border-slate-200">
        <div class="flex items-center gap-5">
            <a href="{{ route('app.buildings.index') }}" wire:navigate>
                <x-tooltip text="Back" dir="bottom">
                    <x-icon-button x-ref="content">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    </x-icon-button>
                </x-tooltip>
            </a>
            <hgroup>
                <h2 class="font-semibold capitalize">Edit <span class="capitalize">{{ $building->name }}</span></h2>
                <p class="text-xs">Edit building details here</p>
            </hgroup>
        </div>

        <x-actions>
            <div class="space-y-1">
                <x-action-button x-on:click="$dispatch('open-modal', 'enable-rooms-modal'); dropdown = false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-icon lucide-circle-check"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                    <p>Enable Rooms</p>
                </x-action-button>
                <x-action-button x-on:click="$dispatch('open-modal', 'disable-rooms-modal'); dropdown = false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-off-icon lucide-circle-off"><path d="m2 2 20 20"/><path d="M8.35 2.69A10 10 0 0 1 21.3 15.65"/><path d="M19.08 19.08A10 10 0 1 1 4.92 4.92"/></svg>
                    <p>Disable Rooms</p>
                </x-action-button>
            </div>
        </x-actions>
    </div>

    <div class="p-5 bg-white border rounded-lg border-slate-200">
        <div class="w-full space-y-5 md:w-1/2">
            <hgroup>
                <h2 class="font-semibold capitalize">General Building Details</h2>
                <p class="text-xs">Edit building details here</p>
            </hgroup>
            
            <x-form.input-group>
                <div>
                    <x-form.input-label for="name">Building Name</x-form.input-label>
                    <p class="text-xs">Update the name of your building</p>
                </div>

                {{-- Name --}}
                <div class="flex gap-2">
                    <div class="max-w-12">
                        <x-form.input-text id="prefix" class="text-center uppercase" label="Prefix" wire:model.live='prefix' disabled />    
                    </div>

                    <div class="w-full space-y-2">
                        <x-form.input-text id="name" label="Name" wire:model.live='name' class="w-full" />
                        <x-form.input-error field="name" />
                    </div>
                </div>
            </x-form.input-group>

            <x-form.input-group>
                <x-form.input-label for="description">Building Description</x-form.input-label>
                <x-form.textarea id="description" name="description" label="description" wire:model.live='description' class="w-full" />
                <x-form.input-error field="description" />
            </x-form.input-group>
            
            <!-- Image -->
            <x-form.input-group>
                <div>
                    <x-form.input-label for="image">Image</x-form.input-label>
                    <p class="text-xs">Upload a new image of your building</p>
                </div>

                <x-img src="{{ $building->image }}" />
                    
                <x-filepond::upload
                    wire:model.live="image"
                    placeholder="Drag & drop your image or <span class='filepond--label-action'> Browse </span>"
                />

                <x-form.input-error field="image" />
            </x-form.input-group>

            <x-note>Upload an image of your building, maximum file size: 1024MB</x-note>
        </div>
    </div>

    <x-primary-button class="text-xs" wire:click="store">
        Edit Building
    </x-primary-button>
</section>