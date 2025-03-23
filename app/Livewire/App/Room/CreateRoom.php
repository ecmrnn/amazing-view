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
    #[Validate] public $building;
    #[Validate] public $room_number;
    #[Validate] public $floor_number = 1;
    #[Validate] public $min_capacity = 1;
    #[Validate] public $max_capacity = 1;
    #[Validate] public $rate = 1000;
    #[Validate] public $image_1_path;
    public $max_floor_number = 1;
    public $room_number_input;
    public $buildings;
    public $selected_building;

    public function mount(RoomType $room) {
        $this->room = $room;
        $this->room_type = $room->id; 
        $this->buildings = Building::all();
    }

    public function rules() {
        return Room::rules();
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
    }

    public function checkRoomNumber() {
        if ($this->building) {
            $count = Room::where('room_number', $this->selected_building->prefix . ' ' . $this->room_number)->count();
    
            if ($count > 0) {
                $this->addError('room_number', 'Room with the same number already exists');
            }
        }
    }

    public function submit() {
        $this->validate();

        if (!empty($this->image_1_path)) {
            $this->image_1_path = $this->image_1_path->store('rooms', 'public');
        }

        Room::create([
            'room_type_id' => $this->room_type,
            'building_id' => $this->building,
            'room_number' => $this->room_number,
            'floor_number' => $this->floor_number,
            'min_capacity' => $this->min_capacity,
            'max_capacity' => $this->max_capacity,
            'rate' => $this->rate,
            'image_1_path' => $this->image_1_path,
        ]);

        $this->toast('Success!', 'success', 'Room added successfully!');
        $this->reset([
            'room_number',
            'room_number_input',
            'floor_number',
            'min_capacity',
            'max_capacity',
            'rate',
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
