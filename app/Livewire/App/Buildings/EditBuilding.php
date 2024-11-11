<?php

namespace App\Livewire\App\Buildings;

use App\Models\Building;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class EditBuilding extends Component
{
    use DispatchesToast, WithFilePond;

    #[Validate] public $name;
    #[Validate] public $prefix;
    #[Validate] public $description = 'Add a brief description of your building';
    #[Validate] public $image;
    #[Validate] public $floor_count = 1;
    #[Validate] public $room_row_count = 2;
    #[Validate] public $room_col_count = 2;
    public $building;

    public function mount(Building $building) {
        $this->building = $building;
        $this->name = $building->name;
        $this->prefix = $building->prefix;
        $this->description = $building->description;
        $this->floor_count = $building->floor_count;
        $this->room_row_count = $building->room_row_count;
        $this->room_col_count = $building->room_col_count;
    }

    public function rules() {
        return [
            'name' => 'required',
            'prefix' => 'required|max:4',
            'description' => 'required|max:200',
            'image' => 'nullable|image',
            'floor_count' => 'required|integer|min:1',
            'room_row_count' => 'required|integer|min:1',
            'room_col_count' => 'required|integer|min:1',
        ];
    }

    public function messages() {
        return [
            'name.required' => 'Enter a name',
            'prefix.required' => 'Enter a prefix',
            'description.required' => 'Enter a description',
            'image.required' => 'Upload an image',
        ];
    }

    public function store() {
        $validated = $this->validate();

        if (!empty($this->image)) {
            // Delete previous image
            if (!empty($this->building->image)) {
                Storage::disk('public')->delete($this->building->image);
            }
            
            // Store the image in the disk
            $validated['image'] = $this->image->store('buildings', 'public');
            $this->building->image = $validated['image'];
        }

        // Update the building
        $this->building->name = $validated['name'];
        $this->building->prefix = $validated['prefix'];
        $this->building->description = $validated['description'];
        // $this->building->floor_count = $validated['floor_count'];
        // $this->building->room_row_count = $validated['room_row_count'];
        // $this->building->room_col_count = $validated['room_col_count'];
        $this->building->save();

        $this->toast('Building Edited!', 'success', 'Building details updated');
        $this->dispatch('building-edited');
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
            x-on:building-edited.window="show = false" class="p-5 space-y-5 bg-white">
            <div class="flex items-center gap-3">
                <x-tooltip text="Back" dir="bottom">
                    <x-icon-button x-ref="content" x-on:click="show = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    </x-icon-button>
                </x-tooltip>

                <hgroup>
                    <h2 class="font-semibold capitalize">Edit Building</h2>
                    <p class="text-xs">Edit building details here</p>
                </hgroup>
            </div>

            <div class="space-y-3">
                <div class="space-y-2">
                    <div>
                        <x-form.input-label for="name-{{ $building->id }}">Name, Prefix, &amp; Description</x-form.input-label>
                        <p class="text-xs">Give your building a new name, prefix, and a brief description</p>
                    </div>
                    {{-- Name --}}
                    <div class="grid grid-cols-3 gap-1">
                        <div class="col-span-2 space-y-2">
                            <x-form.input-text id="name-{{ $building->id }}" label="Name" wire:model.live='name' />
                            <x-form.input-error field="name" />
                        </div>
                        <div class="space-y-2">
                            <x-form.input-text id="prefix-{{ $building->id }}" class="uppercase" label="Prefix" wire:model.live='prefix' />
                            <x-form.input-error field="prefix" />
                        </div>
                    </div>
                    {{-- Description --}}
                    <div class="space-y-2">
                        <x-form.textarea id="description-{{ $building->id }}" name="description-{{ $building->id }}" label="description" wire:model.live='description' class="w-full" />
                        <x-form.input-error field="description" />
                    </div>
                </div>

                <!-- Image -->
                <div class="space-y-2">
                    <div>
                        <x-form.input-label for="image-{{ $building->id }}">Image</x-form.input-label>
                        <p class="text-xs">Upload a new image of your building</p>
                    </div>
                    <x-filepond::upload
                        wire:model.live="image"
                        placeholder="Drag & drop your image or <span class='filepond--label-action'> Browse </span>"
                    />
                    <x-form.input-error field="image" />
                </div>

                <x-primary-button class="text-xs" wire:click="store">
                    Edit Building
                </x-primary-button>
            </div>
        </section>
        HTML;
    }
}
