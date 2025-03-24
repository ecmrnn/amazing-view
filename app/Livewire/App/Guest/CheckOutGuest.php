<?php

namespace App\Livewire\App\Guest;

use App\Enums\ReservationStatus;
use App\Models\Room;
use App\Services\AuthService;
use App\Services\ReservationService;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CheckOutGuest extends Component
{
    use DispatchesToast;

    protected $listeners = [
        'deselected-all' => '$refresh',
        'payment-added' => '$refresh',
        'payment-deleted' => '$refresh',
    ];

    #[Validate] public $password;
    public $reservation;
    public $checked_in_rooms;
    public $unique_id = '';
    public $step = 1;
    public $gallery_index = 0;
    #[Validate] public $selected_rooms;

    public function mount() {
        $this->selected_rooms = collect();
        $this->checked_in_rooms = $this->reservation->rooms()->where('room_reservations.status', ReservationStatus::CHECKED_IN)->count();

        $room = $this->reservation->rooms()->find(1);
    }

    public function rules() {
        return [
            'selected_rooms' => 'required',
            'password' => 'required',
        ];
    }

    public function messages() {
        return [
            'selected_rooms.required' => 'Select a room to check-out',
        ];
    }

    public function goToStep($step) {
        $this->step = $step;
    }

    public function deselectAll() {
        foreach ($this->reservation->rooms as $room) {
            $this->selected_rooms = $this->selected_rooms->reject(function ($r) use ($room) {
                return $r->id == $room->id;
            });
        }

        $this->unique_id = uniqid();
        $this->toast('Deselected Rooms', description: 'All rooms are deselected!');
    }

    public function selectAll() {
        foreach ($this->reservation->rooms as $room) {
            if (!$this->selected_rooms->contains('id', $room->id) && $room->pivot->status == ReservationStatus::CHECKED_IN->value) {
                $this->selected_rooms->push($room);
            }
        }

        $this->unique_id = uniqid();
        $this->toast('Selected All Rooms', description: 'All rooms are selected for check-out!');
    }

    public function toggleRoom(Room $room) {
        if (!$this->selected_rooms->contains('id', $room->id)) {
            $this->selected_rooms->push($room);
        } else {
            $this->selected_rooms = $this->selected_rooms->reject(function ($r) use ($room) {
                return $r->id == $room->id;
            });
        }
    }

    public function previousRoom() {
        if ($this->gallery_index == 0) {
            $this->gallery_index = $this->reservation->rooms->count() - 1;
        } else {
            $this->gallery_index--;
        }
    }

    public function jumpRoom($index) {
        $this->gallery_index = $index;
    }

    public function nextRoom() {
        if ($this->gallery_index < $this->reservation->rooms->count() - 1) {
            $this->gallery_index++;
        } else {
            $this->gallery_index = 0;
        }
    }

    public function submit() {
        switch ($this->step) {
            case 1:
                $this->step++;
                break;
            case 2:
                $this->validate(['selected_rooms' => 'required']);
                $this->step++;
                break;
            default:
                // Final checkout
                if ($this->selected_rooms->count() == $this->checked_in_rooms && $this->reservation->invoice->balance > 0) {
                    $this->toast('Settle Payment First', 'warning', 'This reservation has outstanding balance.');
                } else {
                    $this->dispatch('open-modal', 'show-checkout-confirmation');
                }
                break;
        }

        $this->resetErrorBag();
    }

    public function checkout() {
        $this->validate([
            'password' => $this->rules()['password'],
        ]);

        $auth = new AuthService;
        
        if ($auth->validatePassword($this->password)) {
            $service = new ReservationService;
            $service->checkOut($this->reservation, $this->selected_rooms);

            $description = 'Room checked out successfully!';

            if ($this->selected_rooms->count() > 1) {
                $description = 'Rooms checked out successfully!';
            }

            $this->step++;

            $this->toast('Check-out Success!', description: $description);
            $this->dispatch('checked-out');
            return;
        } 

        $this->addError('password', 'Password mismatched, try again');
    }

    public function render()
    {
        return view('livewire.app.guest.check-out-guest');
    }
}
