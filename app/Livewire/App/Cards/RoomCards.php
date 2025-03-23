<?php

namespace App\Livewire\App\Cards;

use App\Enums\RoomStatus;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use phpDocumentor\Reflection\Types\This;

class RoomCards extends Component
{
    protected $listeners = [
        'room-created' => '$refresh',
        'room-deleted' => '$refresh',
        'rooms-restored' => '$refresh',
    ];
    
    public $available_rooms = 0;
    public $occupied_rooms = 0;
    public $reserved_rooms = 0;
    public $total_rooms_count = 0;
    public $roomType;

    public function mount(RoomType $roomType) {
        $this->roomType = $roomType;
    }
    
    public function render()
    {
        $this->available_rooms = Room::whereBelongsTo($this->roomType)->whereStatus(RoomStatus::AVAILABLE)->count();
        $this->occupied_rooms = Room::whereBelongsTo($this->roomType)->whereStatus(RoomStatus::OCCUPIED)->count();
        $this->reserved_rooms = Room::whereBelongsTo($this->roomType)->whereStatus(RoomStatus::RESERVED)->count();
        $this->total_rooms_count = Room::whereBelongsTo($this->roomType)->count();

        return view('livewire.app.cards.room-cards');
    }
}
