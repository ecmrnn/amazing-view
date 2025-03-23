<?php

namespace App\Livewire\App\Room;

use Livewire\Component;

class ShowRooms extends Component
{
    protected $listeners = [
        'room-created' => '$refresh',
    ];
    
    public $room;

    public function render()
    {
        return view('livewire.app.room.show-rooms');
    }
}
