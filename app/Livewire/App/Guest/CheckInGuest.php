<?php

namespace App\Livewire\App\Guest;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Support\Carbon;
use Livewire\Component;

class CheckInGuest extends Component
{
    public $reservation;
    public $reservation_rid;
    public $date_in;
    public $date_out;
    public $first_name;
    public $last_name;
    public $status;

    public function messages() {
        return [
            'reservation.required' => 'Enter the guest\'s Reservation ID'
        ];
    }

    public function getReservation() {
        $this->validate([
            'reservation_rid' => 'required'
        ]);

        $this->reservation = Reservation::where('rid', $this->reservation_rid)->first();
        
        if (!empty($this->reservation)) {
            if ($this->reservation->status == Reservation::STATUS_CHECKED_IN) {
                $this->reservation = null;
                $this->dispatch('toast', json_encode(['message' => 'Already in', 'type' => 'info', 'description' => 'Guest already checked-in']));
            } else {
                // Initialize properties
                $this->date_in = Carbon::parse($this->reservation->date_in)->format('F j, Y');
                $this->date_out = Carbon::parse($this->reservation->date_out)->format('F j, Y');
                $this->first_name = $this->reservation->first_name;
                $this->last_name = $this->reservation->last_name;
                $this->status = $this->reservation->status;
            }    
        } else {
            $this->dispatch('toast', json_encode(['message' => 'Oof, not found!', 'type' => 'warning', 'description' => 'Reservation not found']));
        }
    }

    public function checkIn() {
        // If a reservation was found
        if (!empty($this->reservation)) {
            if ($this->reservation->status == Reservation::STATUS_CHECKED_IN) {
                $this->dispatch('toast', json_encode(['message' => 'Already in', 'type' => 'info', 'description' => 'Guest already checked-in']));
            } else {
                $this->reservation->status = Reservation::STATUS_CHECKED_IN;
                $this->reservation->save();

                foreach ($this->reservation->rooms as $room) {
                    $room->status = Room::STATUS_OCCUPIED;
                    $room->save();
                }

                foreach ($this->reservation->amenities as $amenity) {
                    $amenity->quantity -= $amenity->pivot->quantity;
                    $amenity->save();
                }
                
                $this->dispatch('pg:eventRefresh-GuestTable');
                $this->dispatch('guest-checked-in');
                $this->dispatch('toast', json_encode(['message' => 'Success!', 'type' => 'success', 'description' => 'Yay, guest checked-in!']));
            }
            // If the reservation is already checked-in
        } else {
            $this->dispatch('toast', json_encode(['message' => 'Oof, not found!', 'type' => 'warning', 'description' => 'Reservation not found']));
        }
    }

    public function render()
    {
        return <<<'HTML'
            <div class="space-y-2">
                <x-form.input-text label="Reservation ID" wire:model="reservation_rid" id="reservation" class="w-full" />
                <x-form.input-error field="reservation" />
                @if (!empty($reservation))
                    <div class="p-3 space-y-5 border border-gray-300 rounded-md ">
                        <hgroup class="flex justify-between">
                            <a href="{{ route('app.reservations.show', ['reservation' => $reservation->rid]) }}" wire:navigate.hover>
                                <h3 class="font-semibold">{{ $reservation->rid }}</h3>
                            </a>

                            <x-status type="reservation" :status="$status" />
                        </hgroup>

                        <div>
                            <p class="flex justify-between text-sm capitalize">Name: {{ $first_name . " " . $last_name}}</p>
                            <p class="flex justify-between text-sm">Check-in date: {{ $date_in }}</p>
                            <p class="flex justify-between text-sm">Check-out date: {{ $date_out }}</p>
                        </div>

                        <x-primary-button type="button" wire:click="checkIn">Check-in Guest</x-primary-button>
                    </div>
                @else
                    <x-primary-button type="button" wire:click="getReservation">Find Reservation</x-primary-button>
                @endif
            </div>
        HTML;
    }
}
