<?php

namespace App\Livewire\App\Guest;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use App\Traits\DispatchesToast;
use Carbon\Carbon;
use Livewire\Attributes\Url;
use Livewire\Component;

class ShowReservationsToday extends Component
{
    use DispatchesToast;

    public $reservations;
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
        'rescheduled' => 0,
        'no-show' => 0,
    ];
    public $reservation_count;
    #[Url] public $status;

    public function mount() {
        $this->getReservationCount();
    }

    public function getReservationCount() {
        $statuses = [
            'awaiting_payment' => ReservationStatus::AWAITING_PAYMENT->value,
            'pending' => ReservationStatus::PENDING->value,
            'confirmed' => ReservationStatus::CONFIRMED->value,
            'checked-in' => ReservationStatus::CHECKED_IN->value,
            'checked-out' => ReservationStatus::CHECKED_OUT->value,
            'completed' => ReservationStatus::COMPLETED->value,
            'canceled' => ReservationStatus::CANCELED->value,
            'expired' => ReservationStatus::EXPIRED->value,
            'rescheduled' => ReservationStatus::RESCHEDULED->value,
            'no-show' => ReservationStatus::NO_SHOW->value,
        ];

        $counts = Reservation::selectRaw('status, COUNT(*) as count')
            ->whereDate('date_in', '<=', Carbon::today())
            ->whereDate('date_out', '>=', Carbon::today())
            ->whereIn('status', $statuses)
            ->groupBy('status')
            ->pluck('count', 'status');

        foreach ($statuses as $key => $status) {
            $this->reservation_by_status[$key] = $counts->get($status, 0);
        }
        
        $this->reservation_by_status['all'] = $counts->sum();
        $this->reservation_count = $this->status == '' 
            ? Reservation::whereDate('date_in', '<=', Carbon::today())
                ->whereDate('date_out', '>=', Carbon::today())
                ->count() 
            : Reservation::whereDate('date_in', '<=', Carbon::today())
                ->whereDate('date_out', '>=', Carbon::today())
                ->whereStatus($this->status)->count();
    }

    public function render()
    {
        return view('livewire.app.guest.show-reservations-today');
    }
}
