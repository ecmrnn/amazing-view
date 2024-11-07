<?php

namespace App\Livewire\App\Room;

use App\Models\Building;
use App\Models\Room;
use App\Models\RoomType;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class CreateRoom extends Component
{
    use DispatchesToast, WithFilePond;
    
    public $room;
    #[Validate] public $room_type;
    #[Validate] public $building_id;
    #[Validate] public $room_number;
    #[Validate] public $floor_number = 1;
    #[Validate] public $min_capacity = 1;
    #[Validate] public $max_capacity = 1;
    #[Validate] public $rate = 1000;
    #[Validate] public $image_1_path;
    public $max_floor_number = 1;
    public $room_number_input;
    public $buildings;
    public $building;

    public function mount(RoomType $room) {
        $this->room = $room;
        $this->room_type = $room->id; 
        $this->buildings = Building::all();
    }

    public function rules() {
        return [
            'room_type' => 'required',
            'building' => 'nullable',
            'room_number' => 'required|unique:rooms,room_number',
            'floor_number' => 'required|numeric|min:1|lte:max_floor_number',
            'min_capacity' => 'required|numeric|min:1',
            'max_capacity' => 'required|numeric|gte:min_capacity',
            'rate' => 'required|numeric|min:1000',
            'image_1_path' => 'nullable|image|mimes:jpeg,jpg,png',
        ];
    }
    
    public function selectBuilding() {
        if (!empty($this->building_id)) {
            $this->building = Building::find($this->building_id);
            $this->max_floor_number = $this->building->floor_count;
        } else {
            $this->reset('building');
            $this->max_floor_number = 1;
        }

        if ($this->floor_number > $this->max_floor_number) {
            $this->floor_number = $this->max_floor_number;
        }
    }

    public function formatRoomNumber() {
        if (!empty($this->building)) {
            $this->room_number = $this->building->prefix . ' ' . $this->room_number_input;
        } else {
            $this->room_number = $this->room_number_input;
        }
    }

    public function submit() {
        $this->validate();

        if (!empty($this->image_1_path)) {
            $this->image_1_path = $this->image_1_path->store('rooms/', 'public');
        }

        Room::create([
            'room_type_id' => $this->room_type,
            'building_id' => $this->building_id,
            'room_number' => $this->room_number,
            'floor_number' => $this->floor_number,
            'min_capacity' => $this->min_capacity,
            'max_capacity' => $this->max_capacity,
            'rate' => $this->rate,
            'image_1_path' => $this->image_1_path,
        ]);

        $this->toast('Success!', 'success', 'Room added successfully!');
        $this->reset([
            // 'building_id',
            // 'room_number',
            'floor_number',
            'min_capacity',
            'max_capacity',
            'rate',
            // 'building',
            'max_floor_number',
            // 'room_number_input',
        ]);
        $this->dispatch('pond-reset');
        $this->dispatch('pg:eventRefresh-RoomTable');
        $this->dispatch('room-created');
    }

    public function render()
    {
        return view('livewire.app.room.create-room');
    }
}
