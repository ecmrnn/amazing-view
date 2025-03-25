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
            'room_row_count' => 'required|integer|min:2',
            'room_col_count' => 'required|integer|min:2',
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
        return view('livewire.app.buildings.create-building');
    }
}
