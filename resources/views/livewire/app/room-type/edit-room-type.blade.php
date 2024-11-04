<form x-data="{ image_count: @entangle('image_count') }" wire:submit='submit' class="grid grid-cols-1 gap-5 lg:grid-cols-3">
    <section class="space-y-5 lg:col-span-2">
        <x-form.form-section>
            <x-form.form-header :collapsable="false" step='1' title='General Room Information' />
            
            <x-form.form-body>
                <div class="p-5 space-y-3">
                    <div class="space-y-3">
                        <div>
                            <x-form.input-label for="name">Room Name & Description</x-form.input-label>
                            <p class="text-xs">Enter the name and brief description of your new room</p>
                        </div>
                        
                        <x-form.input-text id="name" name="name" label="Name" wire:model.live='name' />
                        <x-form.input-error field="name" />
                    </div>

                    <div class="space-y-3">
                        <x-form.textarea id="description" name="description" label="description" class="w-full" wire:model.live="description" />
                        <x-form.input-error field="description" />
                    </div>

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
            </x-form.form-body>
        </x-form.form-section>

        <x-primary-button class="hidden lg:block">Edit Room Type</x-primary-button> 
    </section>

    <aside>
        <x-form.form-section>
            <x-form.form-header :collapsable="false" step='2' title='Image Gallery' />
            
            <x-form.form-body>
                <div class="p-5 space-y-3">
                    <div class="space-y-3">
                        <div>
                            <x-form.input-label for="image_1_path">Gallery</x-form.input-label>
                            <p class="text-xs">Here are the current images for this room</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <x-img-lg src="{{ asset('storage/' . $temp_image_1_path) }}" />
                            <x-img-lg src="{{ asset('storage/' . $temp_image_2_path) }}" />
                            <x-img-lg src="{{ asset('storage/' . $temp_image_3_path) }}" />
                            <x-img-lg src="{{ asset('storage/' . $temp_image_4_path) }}" />
                        </div>

                        <div>
                            <x-form.input-label for="image_1_path">Update Thumbnail &amp; Images</x-form.input-label>
                            <p class="text-xs">Update your thumbnail and images here</p>
                        </div>

                        <x-note>
                            <div class="max-w-xs">Upload a thumbnail and three high definition images that will showcase your new room here</div>
                        </x-note>

                        <x-filepond::upload
                            wire:model.live="image_1_path"
                            placeholder="Drag & drop your image or <span class='filepond--label-action'> Browse </span>"
                        />
                        <x-form.input-error field="image_1_path" />
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <x-filepond::upload
                                wire:model.live="image_2_path"
                                placeholder="Image 2"
                            />
                            <x-form.input-error field="image_2_path" />
                        </div>
                        <div>
                            <x-filepond::upload
                                wire:model.live="image_3_path"
                                placeholder="Image 3"
                            />
                            <x-form.input-error field="image_3_path" />
                        </div>
                        <div>
                            <x-filepond::upload
                                wire:model.live="image_4_path"
                                placeholder="Image 4"
                            />
                            <x-form.input-error field="image_4_path" />
                        </div>
                    </div>
                </div>
            </x-form.form-body>
        </x-form.form-section>
    </aside>

    <div class="lg:hidden">
        <x-primary-button>Edit Room Type</x-primary-button> 
    </div>
</form>