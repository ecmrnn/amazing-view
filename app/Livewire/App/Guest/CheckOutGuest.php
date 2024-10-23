<?php

namespace App\Livewire\App\Guest;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Database\Eloquent\JsonEncodingException;
use Livewire\Component;

class CheckOutGuest extends Component
{
    public $send_invoice = true;
    public $reservation;

    public function mount(Reservation $reservation) {
        $this->reservation = $reservation;
    }

    public function checkOut() {
        $this->reservation->status = Reservation::STATUS_CHECKED_OUT;
        $this->reservation->save();

        // Update room status
        foreach ($this->reservation->rooms as $room) {
            $room->status = Room::STATUS_AVAILABLE;
            $room->save();
        }

        // Update amenity quantities
        foreach ($this->reservation->amenities as $amenity) {
            $amenity->quantity += $amenity->pivot->quantity;
            $amenity->save();
        }
        
        $this->dispatch('toast', json_encode([
            'message' => 'Success!',
            'type' => 'success',
            'description' => 'Guest checked-out'
        ]));

    }

    public function render()
    {
        return <<<'HTML'
            <div class="space-y-2">
                <div class="px-3 py-2 border rounded-md">
                    <x-form.input-checkbox checked="{{ $send_invoice }}" id="sendInvoice" label="Send invoice to {{ $reservation->email }}" />
                </div>

                <x-primary-button type="button" class="text-xs" x-on:click="$wire.checkOut(); show = false">Check-out Guest</x-primary-button>
            </div>
        HTML;
    }
}
