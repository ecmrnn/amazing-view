<?php

namespace App\Livewire\App\Reservation;

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
    ];

    public $reservation;

    public function downloadPdf() {
        $service = new ReservationService;
        $image = $service->downloadPdf($this->reservation);

        if (!$image) {
            $this->toast('Generating PDF', 'info', 'Please wait for a few seconds, ');
        } else {
            $this->toast('Downloading PDF', description: 'Stay online while we download your file!');
            return $image;
        }
    }

    public function mount(Reservation $reservation) {
        $this->reservation = $reservation;    
    }

    public function render()
    {
        return view('livewire.app.reservation.show-reservation');
    }
}
