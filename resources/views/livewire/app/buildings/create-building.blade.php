<form wire:submit='store' class="max-w-screen-lg mx-auto space-y-5">
    <div class="flex items-center justify-between p-5 bg-white border rounded-lg border-slate-200">
        <div class="flex items-center gap-3">
            <a href="{{ route('app.buildings.index') }}" wire:navigate>
                <x-tooltip text="Back" dir="bottom">
                    <x-icon-button x-ref="content">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    </x-icon-button>
                </x-tooltip>
            </a>

            <hgroup>
                <h2 class="font-semibold capitalize">Add Building</h2>
                <p class="text-xs">Add a newly established building here</p>
            </hgroup>
        </div>

        <x-actions>
            <x-action-button>
                <p>Reset</p>
            </x-action-button>
        </x-actions>
    </div>

    <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
        <hgroup>
            <h2 class="font-semibold">General Building Details</h2>
            <p class="text-xs">Enter the details of your building here</p>
        </hgroup>

        <div class="grid gap-5 md:grid-cols-2">
            <div class="space-y-5">
                <x-form.input-group>
                    <div>
                        <x-form.input-label for="name">Name &amp; Prefix</x-form.input-label>
                        <p class="text-xs">Give your new building a name, prefix, and a brief description</p>
                    </div>
                    {{-- Name --}}
                    <div class="grid grid-cols-3 gap-2">
                        <div class="col-span-2 space-y-2">
                            <x-form.input-text id="name" label="Name" wire:model.live='name' />
                            <x-form.input-error field="name" />
                        </div>
                        <div class="space-y-2">
                            <x-form.input-text id="prefix" label="Prefix" wire:model.live='prefix' />
                            <x-form.input-error field="prefix" />
                        </div>
                    </div>
                </x-form.input-group>

                <x-form.input-group>
                    <x-form.input-label for='description'>Building Description</x-form.input-label>
                    <x-form.textarea id="description" name="description" label="description" wire:model.live='description' class="w-full" />
                    <x-form.input-error field="description" />
                </x-form.input-group>
                
                <x-form.input-group>
                    <div>
                        <x-form.input-label for="image">Image</x-form.input-label>
                        <p class="text-xs">Upload an image of your new building</p>
                    </div>
                    <x-filepond::upload
                        wire:model.live="image"
                        placeholder="Drag & drop your image or <span class='filepond--label-action'> Browse </span>"
                    />
                    <x-form.input-error field="image" />
                </x-form.input-group>
                
                <x-note>Upload an image of your new established building here, maximum size: 1024MB</x-note>
            </div>
        </div>
    </div>

    <div x-data="{
            floor_count: @entangle('floor_count'),
            room_row_count: @entangle('room_row_count'),
            room_col_count: @entangle('room_col_count'),
        }"
        class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
        <hgroup>
            <h2 class="font-semibold">Building Layout</h2>
            <p class="text-xs">Modify how the layout of your building looks like</p>
        </hgroup>

        <div class="grid gap-5 md:grid-cols-2">
            <div class="space-y-5">
                {{-- Inputs --}}
                <x-form.input-group>
                    <x-form.input-label for='floor_count'>Floor Count</x-form.input-label>
                    <x-form.input-number min="1" x-model="floor_count" id="floor_count" name="floor_count" label="Floor Count" />
                    <x-form.input-error field="floor_count" />
                </x-form.input-group>

                <div class="grid grid-cols-2 gap-5">
                    <x-form.input-group>
                        <x-form.input-label for='room_row_count'>Room Row Count</x-form.input-label>
                        <x-form.input-number min="2" x-model="room_row_count" id="room_row_count" name="room_row_count" label="Floor Count" />
                        <x-form.input-error field="room_row_count" />
                    </x-form.input-group>
                    <x-form.input-group>
                        <x-form.input-label for='room_col_count'>Room Column Count</x-form.input-label>
                        <x-form.input-number min="2" x-model="room_col_count" id="room_col_count" name="room_col_count" label="Floor Count" />
                        <x-form.input-error field="room_col_count" />
                    </x-form.input-group>
                </div>
            </div>
            
            {{-- Visuals --}}
            <div class="space-y-2">
                <p class="text-xs font-semibold">Building Preview</p>
                
                <div class="p-5 space-y-5 border rounded-md border-slate-200">
                    <div class="px-3 py-2 text-xs bg-white border rounded-md border-slate-200">
                        Floors/Storey: <span x-text="floor_count"></span>
                    </div>

                    <div class="overflow-auto max-h-56">
                        <template x-for="row in room_row_count">
                            <div class="grid gap-1 mt-1 first-of-type:mt-0" :style="'grid-template-columns: repeat(' + room_col_count +  ', 1fr)'">
                                <template x-for="col in room_col_count">
                                    <div class="grid text-xs font-semibold border border-dashed rounded-md min-w-8 bg-slate-50 aspect-square border-slate-200 place-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-door-closed"><path d="M18 20V6a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v14"/><path d="M2 20h20"/><path d="M14 12v.01"/></svg>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-primary-button>Add Building</x-primary-button>
</form>