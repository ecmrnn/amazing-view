<?php

namespace App\Livewire\Guest;

use App\Models\Reservation;
use App\Models\RoomReservation;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class FindReservation extends Component
{

    public $reservation_id;
    public $reservation = [];
    public $selected_rooms;
    public $selected_amenities = [];

    public function mount() {
        $this->reservation = new Collection;
        $this->selected_rooms = new Collection;
    }

    public function rules() {
        return [
            'reservation_id' => 'required',
        ];
    }

    public function submit() {
        $this->validate(['reservation_id' => $this->rules()['reservation_id']]);

        $this->reservation = Reservation::where('rid', $this->reservation_id)->first();
        
        if ($this->reservation != null) {
            $this->selected_rooms = Reservation::find($this->reservation['id'])->rooms;
            $this->selected_amenities = Reservation::find($this->reservation['id'])->amenities;
        }
    }

    public function render()
    {
        return view('livewire.guest.find-reservation');
    }
}
