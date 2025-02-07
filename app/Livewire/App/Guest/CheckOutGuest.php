<?php

namespace App\Livewire\App\Guest;

use App\Enums\ReservationStatus;
use App\Enums\RoomStatus;
use App\Models\Reservation;
use App\Services\ReservationService;
use App\Traits\DispatchesToast;
use Livewire\Component;

class CheckOutGuest extends Component
{
    use DispatchesToast;

    public $send_invoice = true;
    public $reservation;

    public function mount(Reservation $reservation) {
        $this->reservation = $reservation;
    }

    public function checkOut() {
        $service = new ReservationService;
        $service->checkOut($this->reservation);
        $this->toast('Success!', description: 'Guest checked-out');
        $this->dispatch('guest-checked-out');
    }

    public function render()
    {
        return <<<'HTML'
            <div class="p-5 space-y-5" x-on:guest-checked-out.window="show = false">
                <hgroup>
                    <h2 class="text-lg font-semibold capitalize">Check-out Guest</h2>
                    <p class="text-sm">Are you sure you really want to check-out this guest?</p>
                </hgroup>

                <div class="p-5 border rounded-md border-slate-200">
                    <div>
                        <h3 class="font-semibold">{{ $reservation->rid }}</h3>
                        <p class="text-xs">Reservation ID</p>
                    </div>
                </div>

                <div class="p-5 border rounded-md border-slate-200">
                    <div>
                        <h3 class="font-semibold capitalize">{{ $reservation->first_name . ' ' . $reservation->last_name}}</h3>
                        <p class="text-xs">Name</p>
                    </div>
                </div>

                <div class="flex justify-end gap-1">
                    <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                    <x-primary-button type="button" wire:click="checkOut">Check-out</x-primary-button>
                </div>
            </div>
        HTML;
    }
}
