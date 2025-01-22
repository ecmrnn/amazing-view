<?php

namespace App\Livewire\Guest;

use App\Models\Reservation;
use Carbon\Carbon;
use Livewire\Component;

class Success extends Component
{
    public $reservation;
    public $expires_at;

    public function mount($reservation) {
        $this->reservation = Reservation::whereRid($reservation)->first();
        
        if (!empty($this->reservation->expires_at)) {
            $this->expires_at = Carbon::createFromFormat('Y-m-d H:i:s', $this->reservation->expires_at)->format('F d, Y \a\t h:i A');
        }
    }

    public function render()
    {
        return <<<'HTML'
        <div class="grid gap-5 place-items-center">
            <div>
                <h2 class="text-3xl font-semibold text-center text-blue-500"><br /> {{ $reservation->rid }}</h2>
                <p class="text-sm text-center">Reservation ID</p>
            </div>
            
            @empty ($reservation->status == App\Enums\ReservationStatus::PENDING)
                <div class="max-w-sm mx-auto space-y-2">
                    <p class="text-sm text-center">Your reservation is only valid until <br /> <strong class="text-red-500">{{ $expires_at }}</strong></p>
                    <p class="px-3 py-2 text-sm text-red-500 border border-red-500 rounded-lg bg-red-50"><span class="font-semibold">Note:</span> Failure to pay the downpayment within the said date and time would result into your reservation be discarded.</p>
                </div>
            @endempty

            <div class="max-w-sm">
                <p class="text-sm text-center">Take note of your Reservation ID, you may use this to view or update your reservation.</p>

                @empty ($reservation->status == App\Enums\ReservationStatus::PENDING)
                    <p class="text-sm text-center"><br/> If you wish to send your payment <a href="{{ route('guest.search', ['rid' => $reservation->rid]) }}" class="text-blue-500 underline underline-offset-2" wire:navigate>click here.</a></p>
                @endempty
            </div>

            {{-- Action --}}
            <div class="flex gap-1 mx-auto">
                <a href="{{ route('guest.reservation') }}" wire:navigate>
                    <x-secondary-button type="button">Start Again</x-secondary-button>
                </a>
                <a href="{{ route('guest.search', ['rid' => $reservation->rid]) }}" wire:navigate>
                    <x-primary-button type="button">View Reservation</x-primary-button>
                </a>
            </div>
        </div>
        HTML;
    }
}
