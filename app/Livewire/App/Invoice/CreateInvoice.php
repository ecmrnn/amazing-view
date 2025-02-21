<?php

namespace App\Livewire\App\Invoice;

use App\Models\Amenity;
use App\Models\Discount;
use App\Models\Invoice;
use App\Models\Reservation;
use App\Traits\DispatchesToast;
use Carbon\Carbon;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateInvoice extends Component
{
    use DispatchesToast;

    public $reservation;
    public $reservation_id;
    #[Url] public $rid;
    #[Validate] public $email;
    #[Validate] public $issue_date;
    #[Validate] public $due_date;

    public function rules() {
        return [
            'email' => 'required|email',
            'issue_date' => 'nullable|date',
            'due_date' => 'required|date',
        ];
    }

    public function findReservation() {
        if (empty($this->reservation_id)) {
            $this->toast('Oops, Missing Input!', 'warning', 'Reservation ID is required');
            return;
        }

        $this->reservation = Reservation::where('rid', $this->reservation_id)->first();

        if ($this->reservation) {
            $date_out = $this->reservation->resched_date_out ?? $this->reservation->date_out;

            $this->email = $this->reservation->email;
            $this->due_date = Carbon::parse($date_out)->addWeek()->format('Y-m-d');
            $this->toast('Success!', description: 'Reservation found!');
            $this->dispatch('reservation-found', $this->reservation);
        } else {
            $this->toast('Oops, Reservation Not Found!', 'warning', 'Reservation ID not found');
        }
    }

    public function resetInvoice() {
        $this->reset();
        $this->dispatch('reset-invoice');
    }

    public function render()
    {
        return view('livewire.app.invoice.create-invoice');
    }
}
