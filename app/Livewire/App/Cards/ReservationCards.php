<?php

namespace App\Livewire\App\Cards;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use Livewire\Component;

class ReservationCards extends Component
{
    protected $listeners = ['status-changed' => '$refresh'];

    public function render()
    {
        $pending_reservations = Reservation::whereStatus(ReservationStatus::PENDING)->count();
        $confirmed_reservations = Reservation::whereStatus(ReservationStatus::CONFIRMED)->count();
        $completed_reservations = Reservation::whereStatus(ReservationStatus::COMPLETED)->count();
        $expired_reservations = Reservation::whereStatus(ReservationStatus::EXPIRED)->count();

        return view('livewire.app.cards.reservation-cards', [
            'pending_reservations' => $pending_reservations,
            'confirmed_reservations' => $confirmed_reservations,
            'completed_reservations' => $completed_reservations,
            'expired_reservations' => $expired_reservations,
        ]);
    }
}
