<form x-data="{ image_count: @entangle('image_count') }" wire:submit='submit' class="max-w-screen-lg mx-auto space-y-5">
    <div class="flex items-center justify-between gap-5 p-5 bg-white border rounded-lg border-slate-200">
        <div class="flex items-center gap-3 sm:gap-5">
            <x-tooltip text="Back" dir="bottom">
                <a x-ref="content" href="{{ route('app.rooms.index')}}" wire:navigate>
                    <x-icon-button>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    </x-icon-button>
                </a>
            </x-tooltip>
        
            <div>
                <h2 class="text-lg font-semibold">Create Room Type</h2>
                <p class="max-w-sm text-xs">Add a new room type here</p>
            </div>
        </div>

        <x-actions>
            <x-action-button x-on:click="$dispatch('open-modal', 'reset-modal')" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                <p>Reset</p>
            </x-action-button>
        </x-actions>
    </div>
    
    {{-- General Room Details --}}
    <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
        <hgroup>
            <h2 class="font-semibold">General Room Details</h2>
            <p class="text-xs">Enter room details here</p>
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
            <x-form.input-label for="description">Description</x-form.input-label>
            <x-form.textarea id="description" name="description" label="description" class="w-full" wire:model.live="description" />
            <x-form.input-error field="description" />
        </x-form.input-group>

        <div class="space-y-3">
            <div class="flex gap-1">
                <div class="space-y-3">
                    <x-form.input-label for="min_rate">Minimum Rate</x-form.input-label>
                    <x-form.input-currency id="min_rate" name="min_rate" wire:model.live='min_rate' />
                    <x-form.input-error field="min_rate" />
                </div>
                <div class="space-y-3">
                    <x-form.input-label for="max_rate">Maximum Rate</x-form.input-label>
                    <x-form.input-currency id="max_rate" name="max_rate" wire:model.live='max_rate' />
                    <x-form.input-error field="max_rate" />
                </div>
            </div>
            <p class="max-w-xs text-xs">Enter the <strong class="text-blue-500">minimum</strong> and <strong class="text-blue-500">maximum</strong> amount of room rate for this new room</p>
        </div>
    </div>

    <div class="p-5 bg-white border rounded-lg border-slate-200">
        <div>
            <x-form.input-label for="image_1_path">Thumbnail</x-form.input-label>
            <p class="text-xs">Upload an image for your thumbnail</p>
        </div>

        <div class="grid gap-5 mt-5 sm:grid-cols-2 xl:grid-cols-4">
            <div>
                <x-filepond::upload
                    wire:model.live="image_1_path"
                    placeholder="Room Thumbnail"
                />
                <x-form.input-error field="image_1_path" />
            </div>

            <div>
                <x-filepond::upload
                    wire:model.live="image_2_path"
                    placeholder="Room Image"
                />
                <x-form.input-error field="image_2_path" />
            </div>

            <div>
                <x-filepond::upload
                    wire:model.live="image_3_path"
                    placeholder="Room Image"
                />
                <x-form.input-error field="image_3_path" />
            </div>
            
            <div>
                <x-filepond::upload
                    wire:model.live="image_4_path"
                    placeholder="Room Image"
                />
                <x-form.input-error field="image_4_path" />
            </div>
        </div>

        <x-note>
            <div class="max-w-xs">Upload a thumbnail and three high definition images that will showcase your new room here</div>
        </x-note>
    </div>

    <x-primary-button>Add Room Type</x-primary-button> 
</form>