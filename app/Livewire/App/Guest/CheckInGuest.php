<?php

namespace App\Livewire\App\Guest;

use App\Enums\ReservationStatus;
use App\Enums\RoomStatus;
use App\Models\Reservation;
use App\Models\Room;
use App\Services\ReservationService;
use App\Traits\DispatchesToast;
use Illuminate\Support\Carbon;
use Livewire\Component;

class CheckInGuest extends Component
{
    use DispatchesToast;

    public $reservation;
    public $reservation_rid;
    public $date_in;
    public $date_out;

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
            if ($this->reservation->status != ReservationStatus::CONFIRMED->value) {
                $this->reservation = null;
                $this->toast('Check-in Failed', 'warning', 'Reservation status must be confirmed');
            } else {
                $this->date_in = $this->reservation->date_in;
                $this->date_out = $this->reservation->date_out;
                
                if ($this->date_in != Carbon::now()->format('Y-m-d')) {
                    $this->reservation = null;
                    $this->toast('Check-in Failed', 'warning', 'Reservation check-in date is not until ' . date_format(date_create($this->date_in), 'F j, Y') . '!');
                }
            }
        } else {
            $this->toast('Oof, not found!', 'warning', 'Reservation not found!');
        }
    }

    public function checkIn() {
        // If a reservation was found
        if (!empty($this->reservation)) {
            if ($this->reservation->status == ReservationStatus::CHECKED_IN) {
                $this->toast('Already in', 'info', 'Guest already checked-in');
            } else {
                $service = new ReservationService;
                $service->checkIn($this->reservation);

                $this->reset();
                $this->dispatch('pg:eventRefresh-GuestTable');
                $this->dispatch('guest-checked-in');
                $this->toast('Success!', description: 'Success, guest checked-in!');
            }
            // If the reservation is already checked-in
        } else {
            $this->toast('Oof, not found!', 'warning', 'Reservation not found!');
        }
    }

    public function render()
    {
        return <<<'HTML'
            <div class="p-5 space-y-5 bg-white" x-on:guest-checked-in.window="show = false">
                <hgroup>
                    <h2 class="font-semibold capitalize">Check-in Guest</h2>
                    <p class="max-w-sm text-sm">Enter the <strong class="text-blue-500">Reservation ID</strong> of the guest you want to check-in</p>
                </hgroup>

                @if (!empty($reservation))
                    <div class="flex items-center w-full gap-3 px-3 py-2 text-xs border rounded-md border-emerald-500 bg-emerald-200/50">
                        <svg class="self-start flex-shrink-0 text-emerald-800" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-check"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="m9 12 2 2 4-4"/></svg>
                        <p class="text-emerald-800">Ready for check-in!</p>
                    </div>

                    <div class="p-5 space-y-5 border rounded-md border-slate-200 ">
                        <hgroup class="flex justify-between">
                            <h3 class="font-semibold">{{ $reservation->rid }}</h3>

                            <x-status type="reservation" :status="$reservation->status" />
                            <!-- <a href="{{ route('app.reservations.show', ['reservation' => $reservation->rid]) }}" wire:navigate.hover>
                                View Reservation
                            </a> -->
                        </hgroup>
                    </div>

                    <div class="p-5 space-y-5 border rounded-md border-slate-200">
                        <div>
                            <p class="font-semibold capitalize">{{ $reservation->first_name . ' ' . $reservation->last_name }}</p>
                            <p class="flex justify-between text-sm capitalize">Name</p>
                        </div>

                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <p class="font-semibold capitalize">{{ date_format(date_create($date_in), 'F j, Y') }}</p>
                                <p class="flex justify-between text-sm capitalize">Check-in Date</p>
                            </div>
                            <div>
                                <p class="font-semibold capitalize">{{ date_format(date_create($date_out), 'F j, Y') }}</p>
                                <p class="flex justify-between text-sm capitalize">Check-out Date</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <x-loading wire:loading wire:target="checkIn">Checking-in the guest</x-loading>
                        
                        <div class="flex gap-1 ml-auto">
                            <x-secondary-button type="button" x-on:click="show = false; $wire.set('reservation', null)">Cancel</x-secondary-button>
                            <x-primary-button type="button" wire:click="checkIn">Check-in</x-primary-button>
                        </div>
                    </div>
                @else
                    <x-form.input-text label="Reservation ID" wire:model="reservation_rid" id="reservation" class="w-full" />
                    <x-form.input-error field="reservation" />
                    
                    <div class="flex items-center justify-between">
                    <x-loading wire:loading wire:target="getReservation">Finding reservation</x-loading>

                        <div class="flex gap-1 ml-auto">
                            <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                            <x-primary-button type="button" wire:click="getReservation">Find</x-primary-button>
                        </div>
                    </div>
                @endif
            </div>
        HTML;
    }
}
