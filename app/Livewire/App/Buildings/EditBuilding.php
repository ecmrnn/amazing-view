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
            'description' => 'required|max:200',
            'image' => 'nullable|image|max:1024',
            'floor_count' => 'required|integer|min:1',
            'room_row_count' => 'required|integer|min:1',
            'room_col_count' => 'required|integer|min:1',
        ];
    }

    public function messages() {
        return [
            'name.required' => 'Enter a name',
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
        return view('livewire.app.buildings.edit-building');
    }
}
