<?php

namespace App\Livewire\App\Cards;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use phpDocumentor\Reflection\Types\This;

class RoomTypeCards extends Component
{
    public $available_rooms;
    public $occupied_rooms;
    public $reserved_rooms;
    public $total_rooms_count;
    
    public function render()
    {
        $this->available_rooms = Room::whereStatus(Room::STATUS_AVAILABLE)->count();
        $this->occupied_rooms = Room::whereStatus(Room::STATUS_OCCUPIED)->count();
        $this->reserved_rooms = Room::whereStatus(Room::STATUS_RESERVED)->count();
        $this->total_rooms_count = Room::count();

        return view('livewire.app.cards.room-type-cards');
    }
}
