<?php

namespace App\Livewire\App\Cards;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use phpDocumentor\Reflection\Types\This;

class RoomCards extends Component
{
    public $available_rooms = 0;
    public $occupied_rooms = 0;
    public $removed_rooms = 0;
    public $total_rooms_count = 0;
    public $roomType;

    public function mount(RoomType $roomType) {
        $this->roomType = $roomType;
    }
    
    public function render()
    {
        $this->available_rooms = Room::whereRoomTypeId($this->roomType->id)->whereStatus(Room::STATUS_AVAILABLE)->count();
        $this->occupied_rooms = Room::whereRoomTypeId($this->roomType->id)->whereStatus(Room::STATUS_OCCUPIED)->count();
        $this->removed_rooms = Room::onlyTrashed()->whereRoomTypeId($this->roomType->id)->count();
        $this->total_rooms_count = Room::whereRoomTypeId($this->roomType->id)->count();

        return view('livewire.app.cards.room-cards');
    }
}
