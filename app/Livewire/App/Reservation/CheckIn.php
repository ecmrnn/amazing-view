<?php

namespace App\Livewire\App\Reservation;

use App\Enums\ReservationStatus;
use App\Http\Controllers\DateController;
use App\Models\Reservation;
use App\Traits\DispatchesToast;
use Carbon\Carbon;
use Livewire\Component;
use Nette\Utils\Random;

class CheckIn extends Component
{
    use DispatchesToast;

    public $reservation_id;
    public $placeholder;

    public function submit() {
        $this->validate([
            'reservation_id' => 'required',
        ]);

        $reservation = Reservation::whereRid($this->reservation_id)->first();

        if ($reservation) {
            if ($reservation->date_in == DateController::today() && $reservation->status == ReservationStatus::CONFIRMED->value) {
                return $this->redirect(route('app.reservation.check-in', ['reservation' => $this->reservation_id]), true);
            }

            $this->toast('Check-in Failed', 'warning', 'Reservation is valid for check-in. Check status or check-in date.');
            return;
        } 

        $this->toast('Check-in Failed', 'warning', 'Reservation does not exists');
    }

    public function render()
    {
        $this->placeholder = 'R' . Carbon::now()->format('ymd') . Random::generate(3, '0-9');
        
        return <<<'HTML'
        <form wire:submit="submit" class="p-5 space-y-5">
            <hgroup>
                <h2 class='font-semibold'>Check-in Guest</h2>
                <p class='text-xs'>Request the reservation ID of the guest</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for='reservation_id'>Reservation ID</x-form.input-label>
                <x-form.input-text wire:model.live="reservation_id" id="reservation_id" name="reservation_id" label="{{ $placeholder }}" />
                <x-form.input-error field="reservation_id" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='submit'>Loading, please wait</x-loading>

            <div class="flex justify-end gap-1">
                <x-secondary-button type='button'>Cancel</x-secondary-button>
                <x-primary-button>Check-in</x-primary-button>
            </div>
        </form>
        HTML;
    }
}
