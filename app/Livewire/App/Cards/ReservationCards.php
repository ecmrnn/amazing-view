<?php

namespace App\Livewire\App\Cards;

use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class ReservationCards extends Component
{
    protected $listeners = ['status-changed' => '$refresh'];

    public function render()
    {
        $reservation_count = Reservation::select(DB::raw('count(*) as count, status'))
            ->groupBy('status')
            ->get();

        return view('livewire.app.cards.reservation-cards', [
            'pending_reservations' => $reservation_count[Reservation::STATUS_PENDING]->count,
            'confirmed_reservations' => $reservation_count[Reservation::STATUS_CONFIRMED]->count,
            'completed_reservations' => $reservation_count[Reservation::STATUS_COMPLETED]->count,
            'expired_reservations' => $reservation_count[Reservation::STATUS_EXPIRED]->count,
        ]);
    }
}
