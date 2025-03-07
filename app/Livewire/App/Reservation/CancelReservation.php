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
    #[Validate] public $refund_amount;
    #[Validate] public $canceled_at;
    public $max_amount = 0;
    public $reservation;

    public function rules() {
        return [
            'reason' => 'required|max:100',
            'canceled_by' => 'required',
            'canceled_at' => 'required|date',
            'refund_amount' => 'required|integer|lte:max_amount',
        ];
    }
    public function messages() {
        return [
            'reason.required' => 'Enter the reason why the ' . $this->canceled_by . ' wants to cancel',
            'canceled_by.required' => 'Choose which party wants to cancel the reservation.',
            'refund_amount.required' => 'Enter a refund amount',
        ];
    }

    public function mount(Reservation $reservation) {
        $this->reservation = $reservation;
        $this->canceled_at = Carbon::now()->format('Y-m-d');
        $this->calculateRefundAmount();
    }

    public function calculateRefundAmount() {
        $date_in = $this->reservation->date_in;
        $date_diff = Carbon::parse($this->canceled_at)->diffInDays($date_in);
        
        $this->max_amount = 0;
        
        if ($this->reservation->invoice->payments->count() > 0) {
            foreach ($this->reservation->invoice->payments->pluck('amount') as $payment) {
                $this->max_amount += $payment;
            }
        }

        if ($this->max_amount > 0) {
            if ($date_diff >= 7) {
                $this->refund_amount = $this->max_amount * 1;
            } else {
                if ($date_diff > 0) {
                    $this->refund_amount = $this->max_amount * .5;
                } else {
                    $this->refund_amount = 0;
                }
            }
        } else {
            $this->refund_amount = 0;
        }
    }

    public function cancel() {
        $validated = $this->validate([
            'reason' => $this->rules()['reason'],
            'canceled_by' => $this->rules()['canceled_by'],
            'refund_amount' => $this->rules()['refund_amount'],
        ]);

        if ($this->refund_amount > $this->max_amount) {
            $this->addError('refund_amount', 'The maximum refund amount is ' . $this->max_amount);
            return;
        }

        $service = new ReservationService;
        $service->cancel($this->reservation, $validated);

        $this->dispatch('reservation-canceled');
        $this->toast('Reservation Cancelled', 'success', 'Successfully cancelled the reservation');
    }

    public function render()
    {

        return <<<'HTML'
        <section x-data="{ refund_amount: @entangle('refund_amount'), reason: 'guest' }" class="p-5 space-y-5 bg-white" x-on:reservation-canceled.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold capitalize">Reservation Cancellation</h2>
                <p class="max-w-sm text-xs">Are you sure you really want to cancel this reservation?</p>
            </hgroup>

            <x-note>The refund amount is automatically calculated based on the date of cancellation and check-in date.</x-note>
            @if ($max_amount > 0)
                <x-note>The maximum refund amount is based on the confirmed payments made: <x-currency />{{ number_format($max_amount, 2) }}</x-note>
            @endif

            <x-form.input-label for="canceled_by">Who wants to cancel?</x-form.input-label>

            <div class="px-3 py-2 border border-gray-300 rounded-md">
                <x-form.input-radio name="canceled_by" wire:model.live="canceled_by" value="guest" id="guest" label="The guest want to cancel" />
                <x-form.input-radio name="canceled_by" wire:model.live="canceled_by" value="management" id="management" label="The management want to cancel" />    
                <x-form.input-error field="canceled_by" />
            </div>

            <div class="grid grid-cols-2 gap-5">
                <x-form.input-group>
                    <x-form.input-label for='canceled_at'>Date of cancellation</x-form.input-label>
                    <x-form.input-date wire:model.live="canceled_at" id="canceled_at" name="canceled_at" x-on:change="$wire.calculateRefundAmount()" />
                    <x-form.input-error field="canceled_at" />
                </x-form.input-group>
                <x-form.input-group>
                    <x-form.input-label for='refund_amount'>Enter refund amount</x-form.input-label>
                    <x-form.input-number x-model="refund_amount" max="{{ $max_amount }}" wire:model.live="refund_amount" id="refund_amount" name="refund_amount" label="Refund Amount" />
                    <x-form.input-error field="refund_amount" />
                </x-form.input-group>
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
