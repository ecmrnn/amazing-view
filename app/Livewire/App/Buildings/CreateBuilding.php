<?php

namespace App\Livewire\App\Buildings;

use App\Models\Building;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class CreateBuilding extends Component
{
    use DispatchesToast, WithFilePond;

    #[Validate] public $name;
    #[Validate] public $prefix;
    #[Validate] public $description = 'Add a brief description of your building';
    #[Validate] public $image;
    #[Validate] public $floor_count = 1;
    #[Validate] public $room_row_count = 2;
    #[Validate] public $room_col_count = 2;

    public function rules() {
        return [
            'name' => 'required',
            'prefix' => 'required|max:4|unique:buildings,prefix',
            'description' => 'required|max:200',
            'image' => 'required',
            'floor_count' => 'required|integer|min:1',
            'room_row_count' => 'required|integer|min:1',
            'room_col_count' => 'required|integer|min:1',
        ];
    }

    public function messages() {
        return [
            'name.required' => 'Enter a name',
            'prefix.required' => 'Enter a prefix',
            'prefix.unique' => 'Prefix is used',
            'description.required' => 'Enter a description',
            'image.required' => 'Upload an image',
        ];
    }

    public function store() {
        $validated = $this->validate();

        // Store the image in the disk
        $validated['image'] = $this->image->store('buildings', 'public');

        // Store the data in database
        Building::create($validated);
        $this->toast('Building Created!', 'success', 'New building added successfully');
        $this->reset();
        $this->dispatch('building-created');
        $this->dispatch('pond-reset');
    }

    public function render()
    {
        return <<<'HTML'
        <section x-data="{
                floor_count: @entangle('floor_count'),
                room_row_count: @entangle('room_row_count'),
                room_col_count: @entangle('room_col_count'),
            }"
            x-on:building-created.window="show = false" class="p-5 space-y-5 bg-white">
            <div class="flex items-center gap-3">
                <x-tooltip text="Back" dir="bottom">
                    <x-icon-button x-ref="content" x-on:click="show = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    </x-icon-button>
                </x-tooltip>

                <hgroup>
                    <h2 class="font-semibold capitalize">Add Building</h2>
                    <p class="text-xs">Add a newly established building here</p>
                </hgroup>
            </div>

            <div class="space-y-3">
                <div class="space-y-2">
                    <div>
                        <x-form.input-label for="name">Name, Prefix, &amp; Description</x-form.input-label>
                        <p class="text-xs">Give your new building a name, prefix, and a brief description</p>
                    </div>
                    {{-- Name --}}
                    <div class="grid grid-cols-3 gap-1">
                        <div class="col-span-2 space-y-2">
                            <x-form.input-text id="name" label="Name" wire:model.live='name' />
                            <x-form.input-error field="name" />
                        </div>
                        <div class="space-y-2">
                            <x-form.input-text id="prefix" class="uppercase" label="Prefix" wire:model.live='prefix' />
                            <x-form.input-error field="prefix" />
                        </div>
                    </div>
                    {{-- Description --}}
                    <div class="space-y-2">
                        <x-form.textarea id="description" name="description" label="description" wire:model.live='description' class="w-full" />
                        <x-form.input-error field="description" />
                    </div>
                </div>

                <!-- Image -->
                <div class="space-y-2">
                    <div>
                        <x-form.input-label for="image">Image</x-form.input-label>
                        <p class="text-xs">Upload an image of your new building</p>
                    </div>
                    <x-filepond::upload
                        wire:model.live="image"
                        placeholder="Drag & drop your image or <span class='filepond--label-action'> Browse </span>"
                    />
                    <x-form.input-error field="image" />
                </div>

                <x-note>
                    <div>Define how many floors does your new building have in the <strong>Floors Count</strong> field. The room <strong>rows</strong> and <strong>columns</strong> count will be used to layout the building when making a reservation.</div>
                </x-note>

                <div class="space-y-2">
                    <x-form.input-label for="floor_count">Floors Count</x-form.input-label>
                    <x-form.input-number id="floor_count" min="1" name="floor_count" label="Floor Count" wire:model.live="floor_count" x-model="floor_count" />
                    <x-form.input-error field="floor_count" />
                </div>

                <div class="grid grid-cols-2 gap-1">
                    <div class="space-y-2">
                        <x-form.input-label for="room_row_count">Room Rows Count</x-form.input-label>
                        <x-form.input-number id="room_row_count" min="2" name="room_row_count" label="Room Row Count" wire:model.live="room_row_count" x-model="room_row_count" />
                        <x-form.input-error field="room_row_count" />
                    </div>
                    <div class="space-y-2">
                        <x-form.input-label for="room_col_count">Room Columns Count</x-form.input-label>
                        <x-form.input-number id="room_col_count" min="2" name="room_col_count" label="Room Column Count" wire:model.live="room_col_count" x-model="room_col_count" />
                        <x-form.input-error field="room_col_count" />
                    </div>
                </div>

                <x-primary-button class="text-xs" wire:click="store">
                    Add Building
                </x-primary-button>
            </div>
        </section>
        HTML;
    }
}
