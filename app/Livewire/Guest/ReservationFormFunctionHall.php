<?php

namespace App\Livewire\Guest;

use App\Models\FunctionHallReservations;
use App\Traits\DispatchesToast;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ReservationFormFunctionHall extends Component
{
    use DispatchesToast;

    public $step = 1;
    public $min_date;
    #[Validate] public $event_name;
    #[Validate] public $event_description = 'Describe the event or occasion you want to celebrate here!';
    #[Validate] public $reservation_date;
    #[Validate] public $first_name;
    #[Validate] public $last_name;
    #[Validate] public $email;

    protected $rules = [
        'event_name' => 'required|string',
        'event_description' => 'required|string',
        'reservation_date' => 'required|date|after_or_equal:min_date',
        'first_name' => 'required|string',
        'last_name' => 'required|string',
        'email' => 'required|email:rfc,dns',
    ];

    public function submit() {
        switch ($this->step) {
            case 1:
                $this->validate();
                $this->step = 2;
                $this->toast('Success!', description: 'Next, confirmation of reservation.');
                break;
            case 2:
                $this->step = 3;
                break;
        }
    }

    public function store() {
        $this->validate();

        FunctionHallReservations::create([
            'event_name' => $this->event_name,
            'event_description' => $this->event_description,
            'reservation_date' => $this->reservation_date,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
        ]);

        // Mail::to($this->email)->send(new \App\Mail\ReservationFormFunctionHall($data));
        $this->toast('Success!', description: 'Your reservation has been successfully submitted.');
        $this->dispatch('reservation-successful');
    }

    public function goToStep($step) {
        $this->step = $step;
    }

    public function resetReservation() {
        $this->reset();
        $this->min_date = Carbon::now()->addDays(5)->format('Y-m-d');
        sleep(2);
    }

    public function mount()
    {
        $this->min_date = Carbon::now()->addDays(5)->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.guest.reservation-form-function-hall');
    }
}
