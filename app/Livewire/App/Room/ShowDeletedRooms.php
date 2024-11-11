<?php

namespace App\Livewire\App\Room;

use App\Models\Room;
use App\Models\RoomType;
use App\Traits\DispatchesToast;
use Livewire\Component;

class ShowDeletedRooms extends Component
{
    use DispatchesToast;

    protected $listeners = [
        'room-deleted' => '$refresh',
        'rooms-restored' => '$refresh',
    ];

    public $deleted_rooms;
    public $deleted_rooms_count;
    public $selected_rooms;
    public $room;

    public function mount(RoomType $room) {
        $this->room = $room;
        $this->selected_rooms = collect();
        
    }

    public function toggleRoom($room) {
        
        if ($this->selected_rooms->contains('id', $room)) {
            $this->selected_rooms = $this->selected_rooms->reject(function ($room_loc) use ($room) {
                return $room_loc->id == $room;
            });
        } else {
            $selected_room = Room::withTrashed()->find($room);
            $this->selected_rooms->push($selected_room);
        }
    }

    public function submit() {
        $this->validate([
            'selected_rooms' => 'required',
        ], [
            'selected_rooms.required' => 'Select a room to restore',
        ]);

        foreach ($this->selected_rooms as $rooms) {
            $rooms->restore();
        }

        $this->selected_rooms->count() > 1
            ? $this->toast('Success!', 'success', 'Rooms restored successfully!')
            : $this->toast('Success!', 'success', 'Room restored successfully!');
        $this->selected_rooms = collect();
        $this->dispatch('rooms-restored');
        $this->dispatch('pg:eventRefresh-RoomTable');
    }
    
    public function render()
    {
        $this->deleted_rooms = Room::onlyTrashed()
            ->whereBelongsTo($this->room)
            ->get();
        $this->deleted_rooms_count = Room::onlyTrashed()
            ->whereBelongsTo($this->room)
            ->count();

        return view('livewire.app.room.show-deleted-rooms');
    }
}
