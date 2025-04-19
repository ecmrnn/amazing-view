<?php

namespace App\Livewire\App\Room;

use App\Models\Building;
use App\Models\BuildingSlot;
use App\Models\Room;
use App\Models\RoomType;
use App\Services\RoomService;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class CreateRoom extends Component
{
    use DispatchesToast, WithFilePond;
    
    public $step = 1;
    public $room;
    #[Validate] public $building;
    #[Validate] public $slot;

    #[Validate] public $room_type;
    #[Validate] public $room_number;
    #[Validate] public $floor_number = 1;
    #[Validate] public $min_capacity = 1;
    #[Validate] public $max_capacity = 1;
    #[Validate] public $rate = 1000;
    #[Validate] public $image;
    public $images;
    public $max_floor_number = 1;
    public $room_number_input;
    public $buildings;
    public $selected_building;
    public $selected_slot;
    public $slots;

    public function mount(RoomType $room) {
        $this->room = $room;
        $this->room_type = $room->id; 
        $this->buildings = Building::all();
        $this->building = Building::first()->id;
        $this->selected_building = Building::first();
        $this->max_floor_number = Building::first()->floor_count;
        $this->slots = Building::first()->slots;
        $this->images = collect();
    }

    public function rules() {
        return [
            'building' => 'required',
            'selected_slot' => 'required',
            'room_number' => 'required',
            'floor_number' => 'required|min:1',
            'min_capacity' => 'required|min:1',
            'max_capacity' => 'required|min:1',
            'rate' => 'required|integer',
            'image' => 'nullable|image|max:1024',
        ];
    }

    public function messages() {
        return [
            'selected_slot.required' => 'Select a slot',
        ];
    }

    public function saveImage() {
        $this->validate([
            'image' => $this->rules()['image'],
        ]);

        $this->image = $this->image->store('temp', 'public');
        $this->images = $this->images->push($this->image);

        $this->reset('image');
        $this->dispatch('pond-reset');
    }
    
    public function selectBuilding() {
        if (!empty($this->building)) {
            $this->selected_building = Building::find($this->building);
            $this->max_floor_number = $this->selected_building->floor_count;
        } else {
            $this->reset('selected_building');
            $this->max_floor_number = 1;
        }

        if ($this->floor_number > $this->max_floor_number) {
            $this->floor_number = $this->max_floor_number;
        }

        $this->slots = $this->selected_building->slots;
    }

    public function checkRoomNumber() {
        if ($this->building) {
            $count = Room::where('room_number', $this->selected_building->prefix . ' ' . $this->room_number)->count();
    
            if ($count > 0) {
                $this->addError('room_number', 'Room with the same number already exists');
            }
        }
    }

    public function checkBuilding() {
        $this->validate([
            'building' => $this->rules()['building'],
            'selected_slot' => $this->rules()['selected_slot'],
        ]);

        $this->step = 2;
    }

    public function selectSlot(BuildingSlot $slot) {
        $this->selected_slot = $slot;
    }

    public function submit() {
        $validated = $this->validate();
        $validated['room_type'] = $this->room_type;
        $validated['images'] = $this->images;

        $service = new RoomService;
        $room = $service->create($validated);

        $this->slots = $room->building->slots;

        $this->toast('Success!', 'success', 'Room added successfully!');
        $this->dispatch('pond-reset');
        $this->dispatch('pg:eventRefresh-RoomTable');
        $this->dispatch('room-created');
        $this->dispatch('pond-reset');

        $this->reset([
            'room_number_input',
            'room_number',
            'image',
            'floor_number',
            'min_capacity',
            'max_capacity',
            'step',
            'selected_slot',
            'rate',
        ]);

        $this->images = collect();
    }

    public function render()
    {
        return view('livewire.app.room.create-room');
    }
}
