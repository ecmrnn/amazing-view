<?php

namespace App\Livewire\Guest;

use Carbon\Carbon;
use Livewire\Component;

class ReservationFormFunctionHall extends Component
{
    public $step = 1;
    public $min_date;

    public function mount()
    {
        $this->min_date = Carbon::now()->addDays(5)->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.guest.reservation-form-function-hall');
    }
}
