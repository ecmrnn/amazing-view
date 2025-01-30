<?php

namespace App\Livewire\App\Reservation;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use App\Services\ReservationService;
use App\Traits\DispatchesToast;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CancelReservation extends Component
{
    use DispatchesToast;

    #[Validate] public $reason;
    #[Validate] public $canceled_by = 'guest';
    public $reservation;

    public function rules() {
        return [
            'reason' => 'required|max:100',
            'canceled_by' => 'required',
        ];
    }
    public function messages() {
        return [
            'reason.required' => 'Enter the reason why the ' . $this->canceled_by . ' wants to cancel',
            'canceled_by.required' => 'Choose which party wants to cancel the reservation.',
        ];
    }

    public function mount(Reservation $reservation) {
        $this->reservation = $reservation;
    }

    public function cancel() {
        $validated = $this->validate([
            'reason' => $this->rules()['reason'],
            'canceled_by' => $this->rules()['canceled_by'],
        ]);

        $service = new ReservationService;
        $service->cancel($this->reservation, $validated);

        $this->dispatch('reservation-canceled');
        $this->toast('Reservation Cancelled', 'success', 'Successfully cancelled the reservation');
    }

    public function render()
    {

        return <<<'HTML'
        <section class="p-5 space-y-5 bg-white" x-on:reservation-canceled.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold capitalize">Reservation Cancellation</h2>
                <p class="max-w-sm text-xs">Are you sure you really want to cancel this reservation?</p>
            </hgroup>

            <x-form.input-label for="reason">Who wants to cancel?</x-form.input-label>

            <div class="px-3 py-2 border border-gray-300 rounded-md">
                <x-form.input-radio name="canceled_by" wire:model.live="canceled_by" value="guest" id="guest" label="The guest want to cancel" />
                <x-form.input-radio name="canceled_by" wire:model.live="canceled_by" value="management" id="management" label="The management want to cancel" />    
                <x-form.input-error field="canceled_by" />
            </div>

            <x-form.input-group>
                <x-form.input-label for='reason'>Reason for cancellation</x-form.input-label>
                <x-form.input-text wire:model.live="reason" id="reason" name="reason" label="Reason" />
                <x-form.input-error field="reason" />
            </x-form.input-group>
            
            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-danger-button type="button" wire:click="cancel">Cancel Reservation</x-danger-button>
            </div>
        </section>
        HTML;
    }
}
