<?php

namespace App\Livewire\App\Reservation;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use App\Traits\DispatchesToast;
use Livewire\Component;

class ConfirmReservation extends Component
{
    use DispatchesToast;

    public $reservation;
    public $downpayment;

    public function mount($reservation, $downpayment)
    {
        $this->reservation = $reservation;
        $this->downpayment = $downpayment;
    }

    public function confirmReservation() {
        $this->reservation->status = ReservationStatus::CONFIRMED;
        $this->reservation->save();
        $this->toast('Success!', description: 'Reservation confirmed!');
        $this->dispatch('reservation-confirmed');
    }

    public function render()
    {
        return <<<'HTML'
        <div x-data="{ checked: false }" x-on:reservation-confirmed.window="show = false">
            <section class="p-5 space-y-5 bg-white">
                <hgroup>
                    <h2 class="font-semibold capitalize text">Payment upon Reservation</h2>
                    <p class="max-w-sm text-xs">Confirm that the payment made below are successful before confirming the reservation.</p>
                </hgroup>

                <div class="relative">
                    @if ($downpayment == null)
                        <div class="flex items-center justify-center w-full h-40 text-center border rounded-md text-slate-300 border-slate-200">
                            <p>No payment receipt uploaded.</p>
                        </div>
                    @else
                        <div class="w-full overflow-auto border rounded-md aspect-square border-slate-200">
                            <img src="{{ asset($downpayment) }}" alt="payment receipt" />
                        </div>

                        <div class="absolute flex gap-1 top-3 right-3">
                            <x-tooltip text="Download" dir="top">
                                <a x-ref="content" href="{{ asset($downpayment) }}" download="{{ $reservation->rid . ' - ' . 'Payment'}}">
                                    <x-icon-button>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-image-down"><path d="M10.3 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10l-3.1-3.1a2 2 0 0 0-2.814.014L6 21"/><path d="m14 19 3 3v-5.5"/><path d="m17 22 3-3"/><circle cx="9" cy="9" r="2"/></svg>
                                    </x-icon-button>
                                </a>
                            </x-tooltip>

                            <x-tooltip text="View" dir="top">
                                <a href="{{ asset($downpayment) }}" target="_blank" x-ref="content">
                                    <x-icon-button>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-external-link"><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                                    </x-icon-button>
                                </a>
                            </x-tooltip>
                        </div>
                    @endif
                </div>

                <div class="flex justify-end gap-1">
                    <x-secondary-button x-on:click="show = false">Close</x-secondary-button>
                    @if ($reservation->status == 1)
                        <x-primary-button wire:click="confirmReservation">Confirm Reservation</x-primary-button>
                    @endif
                </div>
            </section>
        </div>
        HTML;
    }
}
