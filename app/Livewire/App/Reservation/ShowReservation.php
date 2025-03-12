<?php

namespace App\Livewire\App\Reservation;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use App\Services\ReservationService;
use App\Traits\DispatchesToast;
use Livewire\Component;

class ShowReservation extends Component
{
    use DispatchesToast;

    protected $listeners = [
        'reservation-canceled' => '$refresh',
        'reservation-confirmed' => '$refresh',
        'guest-checked-out' => '$refresh',
        'payment-added' => '$refresh',
        'reservation-reactivated' => '$refresh',
        'guest-checked-in' => '$refresh',
    ];

    public $reservation;
    public $new_reservation;

    public function downloadPdf()
    {
        $service = new ReservationService;
        $pdf = $service->downloadPdf($this->reservation);

        if (!$pdf) {
            $this->toast('Generating PDF', 'info', 'Please wait for a few seconds and then try again.');
        } else {
            $this->toast('Downloading PDF', description: 'Stay online while we download your file!');
            return $pdf;
        }
    }

    public function mount(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function render()
    {
        return view('livewire.app.reservation.show-reservation');
    }
}
