<?php

namespace App\Livewire\App\Reservation;

use App\Models\Reservation;
use Livewire\Component;

class ShowReservation extends Component
{
    protected $listeners = [
        'reservation-canceled' => '$refresh',
        'reservation-confirmed' => '$refresh',
        'guest-checked-out' => '$refresh',
    ];

    public $reservation;

    public function mount(Reservation $reservation) {
        $this->reservation = $reservation;    
    }

    public function render()
    {
        return view('livewire.app.reservation.show-reservation');
    }
}
