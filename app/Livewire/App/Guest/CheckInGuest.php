<?php

namespace App\Livewire\App\Guest;

use App\Models\Reservation;
use App\Models\Room;
use App\Services\AuthService;
use App\Services\BillingService;
use App\Services\ReservationService;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;
use phpDocumentor\Reflection\Types\This;

class CheckInGuest extends Component
{
    use DispatchesToast;

    protected $listeners = [
        'bulk-assigned' => '$refresh',
        'payment-added' => '$refresh',
        'payment-deleted' => '$refresh',
        'pwd-senior-updated' => '$refresh',
    ];

    public $step = 1;
    public $reservation;
    public $guests;
    public $disc_guests;
    public $remaining_guests;
    public $remaining_disc_guests;
    public $room_guests; /* Collection */
    public $collection_id;
    public $breakdown_id;
    #[Validate] public $password;

    public function rules() {
        return [
            'password' => 'required',
        ];
    }

    public function messages() {
        return [
            'password.required' => 'Enter your password',
        ];
    }

    public function mount(Reservation $reservation) {
        $this->reservation = $reservation;
        $this->remaining_guests = ($reservation->adult_count + $reservation->children_count) - ($reservation->senior_count + $reservation->pwd_count);
        $this->remaining_disc_guests = $reservation->senior_count + $reservation->pwd_count;
        $this->room_guests = collect();
        $this->collection_id = uniqid();

        if ($this->reservation->rooms->count() > 1) {
            foreach ($this->reservation->rooms as $room) {
                $guests = $room->pivot->regular_guest_count > 0 ? $room->pivot->regular_guest_count : 0;
                $disc_guests = $room->pivot->discountable_guest_count > 0 ? $room->pivot->discountable_guest_count : 0;

                $this->room_guests->push([
                    'id' => $room->id,
                    'room_number' => $room->room_number,
                    'max_capacity' => $room->max_capacity,
                    'rate' => $room->pivot->rate,
                    'guests' => $guests,
                    'disc_guests' => $disc_guests,
                ]);
            }
        } else {
            $room = $this->reservation->rooms->first();
            $disc_guests = $this->reservation->senior_count + $this->reservation->pwd_count;
            $guests = ($this->reservation->adult_count + $this->reservation->children_count) - $disc_guests;

            if ($room) {
                $this->room_guests->push([
                    'id' => $room->id,
                    'room_number' => $room->room_number,
                    'max_capacity' => $room->max_capacity,
                    'rate' => $room->pivot->rate,
                    'guests' => $guests,
                    'disc_guests' => $disc_guests,
                ]);
            }
        }
    }

    public function goToStep($step) {
        $this->step = $step;
    }

    public function saveGuests(Room $room, $guests, $disc_guests) {
        if ($guests + $disc_guests > $room->max_capacity) {
            $this->toast('Maximum Capacity Reached', 'info', 'Maximum capacity of this room is ' . $room->max_capacity);
            return;
        }

        // Get the guests in
        $guests_in = $this->room_guests->map(function ($_room) use ($room) {
            if ($_room['id'] != $room->id) {
                return $_room;
            }
        })->sum('guests');

        $disc_guests_in = $this->room_guests->map(function ($_room) use ($room) {
            if ($_room['id'] != $room->id) {
                return $_room;
            }
        })->sum('disc_guests');

        if ($guests_in + $guests > $this->remaining_guests) {
            $this->toast('Insufficient Guest Count', 'info', 'Remaining guests are ' . $this->remaining_guests - $guests_in);
            return;
        }

        if ($disc_guests_in + $disc_guests > $this->remaining_disc_guests) {
            $this->toast('Insufficient Guest Count', 'info', 'Remaining Senior or PWD guests are ' . $this->remaining_disc_guests - $disc_guests_in);
            return;
        }
        
        $this->room_guests = $this->room_guests->map(function ($_room) use ($room, $guests, $disc_guests) {
            if ($_room['id'] == $room->id) {
                if ($_room['guests'] != $guests) {
                    if ($_room['guests'] > $guests) {
                        $_room['guests'] = $_room['guests'] - ($_room['guests'] - $guests);
                    } else {
                        $_room['guests'] = $_room['guests'] + ( $guests - $_room['guests']);
                    }
                }

                if ($_room['disc_guests'] != $disc_guests) {
                    if ($_room['disc_guests'] > $disc_guests) {
                        $_room['disc_guests'] = $_room['disc_guests'] - ($_room['disc_guests'] - $disc_guests);
                    } else {
                        $_room['disc_guests'] = $_room['disc_guests'] + ( $disc_guests - $_room['disc_guests']);
                    }
                }
            }
            return $_room;
        });

        $this->dispatch('guest-saved');
    }

    public function bulkAssign() {
        foreach ($this->room_guests as $key) {
            $room = Room::find($key['id']);
            $max_capacity = $room->max_capacity;

            // Check how many remaining guest can fit into each room
            $guests = $key['guests'];
            $disc_guests = $key['disc_guests'];
            $capacity = $max_capacity - ($guests + $disc_guests);
            
            // Update the collection if capacity is greater than 0
            if ($capacity > 0) {
                // Calculate how many guest can fit into this room
                $remaining_guests = $this->remaining_guests - $this->room_guests->sum('guests');
                $guests = $capacity > $remaining_guests 
                    ? $remaining_guests
                    : $capacity;
                
                $this->room_guests = $this->room_guests->map(function ($_room) use ($room, $guests) {
                    if ($_room['id'] == $room->id) {
                        $_room['guests'] += $guests;
                    }
                    return $_room;
                });
            }
        }

        $this->collection_id = uniqid();
        $this->toast('Success!', description: 'Guest bulk assigned!');
        $this->dispatch('bulk-assigned');
    }

    public function validateSeniorPwd() {
        // Get the guests in
        $guests_in = $this->room_guests->sum('guests');
        $disc_guests_in = $this->room_guests->sum('disc_guests');

        // Verify that all guests are assigned to a specific room
        if ( $disc_guests_in < $this->remaining_disc_guests) {
            $this->toast('Assign Senior and PWDs', 'info', 'Assign seniors and PWDs to their respective rooms');
            return;
        }
        
        // Check if all the guests are assigned to a room
        if ( $guests_in < $this->remaining_guests) {
            $this->dispatch('open-modal', 'bulk-assign-guests');
            return;
        }

        // Store assigned guests to database
        foreach ($this->reservation->rooms as $room) {
            $_room = $this->room_guests->first(function ($_room) use ($room) {
                if ($_room['id'] == $room->id) {
                    return $_room;
                }
            });

            $room->pivot->regular_guest_count = $_room['guests'];
            $room->pivot->discountable_guest_count = $_room['disc_guests'];
            $room->pivot->save();
        }

        // Update the invoice
        $billing = new BillingService;
        $taxes = $billing->taxes($this->reservation->fresh());
        $payments = $this->reservation->invoice->payments->sum('amount');
        $waive = $this->reservation->invoice->waive_amount;
        
        $this->reservation->invoice->sub_total = $taxes['net_total'];
        $this->reservation->invoice->total_amount = $taxes['net_total'];
        $this->reservation->invoice->balance = $taxes['net_total'] - $payments;
        
        // Apply waived amount
        if ($this->reservation->invoice->balance >= $waive) {
            $this->reservation->invoice->balance -=  $waive;
        } else {
            $this->reservation->invoice->balance = 0;
        }
        
        $this->reservation->invoice->save();

        $this->breakdown_id = uniqid();
        $this->step = 3;
        $this->dispatch('pwd-senior-updated', ['reservation' => $this->reservation]);
        return;
    }

    public function validatePayment() {
        if ($this->reservation->invoice->balance > 0) {
            $this->toast('Settle Payment First', 'warning', 'This reservation has outstanding balance.');
            return;
        } else {
            $this->dispatch('open-modal', 'show-checkin-confirmation');
        }
    }

    public function checkin() {
        $service = new ReservationService;
        $service->checkIn($this->reservation);
        
        $this->toast('Success!', description: 'Guest checked-in!');
        $this->dispatch('checked-in');

        sleep(5);
        $this->redirect(route('app.guests.index'), true);
        return;
    }

    public function submit() {
        // For deleting payment kailangan nag error di ko alam kung bakit...
    }

    public function render()
    {
        return view('livewire.app.guest.check-in-guest');
    }
}
