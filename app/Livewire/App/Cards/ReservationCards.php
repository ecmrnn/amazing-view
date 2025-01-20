<?php

namespace App\Livewire\App\Cards;

use App\Models\Reservation;
use Livewire\Component;

class ReservationCards extends Component
{
    protected $listeners = ['status-changed' => '$refresh'];

    public function render()
    {
        $pending_reservations = Reservation::whereStatus(Reservation::STATUS_PENDING)->count();
        $confirmed_reservations = Reservation::whereStatus(Reservation::STATUS_CONFIRMED)->count();
        $completed_reservations = Reservation::whereStatus(Reservation::STATUS_COMPLETED)->count();
        $expired_reservations = Reservation::whereStatus(Reservation::STATUS_EXPIRED)->count();

        return view('livewire.app.cards.reservation-cards', [
            'pending_reservations' => $pending_reservations,
            'confirmed_reservations' => $confirmed_reservations,
            'completed_reservations' => $completed_reservations,
            'expired_reservations' => $expired_reservations,
        ]);
    }
}
