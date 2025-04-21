<?php

namespace App\Livewire\App\Reservation;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use App\Services\ReservationService;
use App\Traits\DispatchesToast;
use Livewire\Attributes\On;
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

    #[On('payment-added')]
    public function test() {
        $this->redirect(route('app.reservations.show', ['reservation' => $this->reservation->rid]), true);
    }

    public function render()
    {
        return view('livewire.app.reservation.show-reservation');
    }
}
