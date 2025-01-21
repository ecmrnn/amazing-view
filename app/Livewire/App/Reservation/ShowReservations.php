<?php

namespace App\Livewire\App\Reservation;

use App\Models\Reservation;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

class ShowReservations extends Component
{
    public $reservation_by_status = [
        'all' => 0,
        'awaiting_payment' => 0,
        'pending' => 0,
        'confirmed' => 0,
        'checked-in' => 0,
        'checked-out' => 0,
        'completed' => 0,
        'canceled' => 0,
        'expired' => 0,
    ];
    public $reservation_count;
    #[Url] public $status;

    public function mount() {
        $this->getReservationCount();
    }

    #[On('status-changed')]
    public function getReservationCount() {
        $statuses = [
            'awaiting_payment' => Reservation::STATUS_AWAITING_PAYMENT,
            'pending' => Reservation::STATUS_PENDING,
            'confirmed' => Reservation::STATUS_CONFIRMED,
            'checked-in' => Reservation::STATUS_CHECKED_IN,
            'checked-out' => Reservation::STATUS_CHECKED_OUT,
            'completed' => Reservation::STATUS_COMPLETED,
            'canceled' => Reservation::STATUS_CANCELED,
            'expired' => Reservation::STATUS_EXPIRED,
        ];

        $counts = Reservation::selectRaw('status, COUNT(*) as count')
            ->whereIn('status', $statuses)
            ->groupBy('status')
            ->pluck('count', 'status');

        foreach ($statuses as $key => $status) {
            $this->reservation_by_status[$key] = $counts->get($status, 0);
        }
        
        $this->reservation_by_status['all'] = $counts->sum();
        $this->reservation_count = $this->status == '' ? Reservation::count() : Reservation::whereStatus($this->status)->count();
    }

    public function render()
    {
        return view('livewire.app.reservation.show-reservations');
    }
}
