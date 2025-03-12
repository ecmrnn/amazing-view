<?php

namespace App\Livewire\App\Reservation;

use App\Enums\ReservationStatus;
use App\Mail\Reservation\Confirmed;
use App\Models\Reservation;
use App\Services\ReservationService;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Attribute\On;

class ConfirmReservation extends Component
{
    use DispatchesToast;

    protected $listeners = [
        'payment-added' => '$refresh',
    ];

    public $reservation;
    public $payment;
    public $can_confirm = false;
    #[Validate] public $amount = 0;
    #[Validate] public $transaction_id;
    #[Validate] public $payment_date;

    public function rules() {
        return [
            'amount' => 'required|integer|gt:500',
            'transaction_id' => 'nullable',
            'payment_date' => 'date|required',
        ];
    }

    public function mount($reservation)
    {
        $this->reservation = $reservation;
        $this->payment = $reservation->invoice->payments()->wherePurpose('downpayment')->first();

        if ($this->payment) {
            $this->amount = (int) $this->payment->amount;
            $this->payment_date = $this->payment->payment_date;
            $this->transaction_id = $this->payment->transaction_id;
        }
    }

    public function validateReservation() {
        $this->validate([
            'amount' => $this->rules()['amount'],
            'transaction_id' => $this->rules()['transaction_id'],
            'payment_date' => $this->rules()['payment_date'],
        ]);

        dd($this->amount);

        $this->can_confirm = true;
    }

    public function confirm() {
        $data = [
            'amount' => $this->amount,
            'transaction_id' => $this->transaction_id,
            'payment_date' => $this->payment_date,
            'orid' => $this->payment->orid
        ];
        
        $service = new ReservationService;
        $service->confirm($this->reservation, $data);

        // Send email about confirmed reservation
        // Mail::to($this->reservation->email)->queue(new Confirmed($this->reservation));

        $this->toast('Success!', description: 'Reservation confirmed!');
        $this->dispatch('reservation-confirmed');
    }

    public function render()
    {
        return <<<'HTML'
        <div>
            <x-modal.full name="show-downpayment-modal" maxWidth="sm">
                <div x-data="{ checked: false, amount: @entangle('amount') }" x-on:reservation-confirmed.window="show = false">
                    @if(! $can_confirm)
                        <section class="p-5 space-y-5">
                            <hgroup>
                                <h2 class="font-semibold capitalize text">Confirm Reservation</h2>
                                <p class="max-w-sm text-xs">Confirm that the payment made below are successful before confirming the reservation.</p>
                            </hgroup>
                            <div class="relative space-y-5">
                                @if (!empty($payment))
                                    @if (!empty($payment->proof_image_path))
                                        <div class="w-full overflow-auto border rounded-md aspect-square border-slate-200">
                                            <img src="{{ asset($payment->proof_image_path) }}" alt="payment receipt" />
                                        </div>
                                        <div class="absolute top-0 flex gap-1 right-3">
                                            <x-tooltip text="Download" dir="top">
                                                <a x-ref="content" href="{{ asset($payment->proof_image_path) }}" download="{{ $reservation->rid . ' - ' . 'Payment'}}">
                                                    <x-icon-button class="bg-white">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-image-down"><path d="M10.3 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10l-3.1-3.1a2 2 0 0 0-2.814.014L6 21"/><path d="m14 19 3 3v-5.5"/><path d="m17 22 3-3"/><circle cx="9" cy="9" r="2"/></svg>
                                                    </x-icon-button>
                                                </a>
                                            </x-tooltip>
                                            <x-tooltip text="View" dir="top">
                                                <a href="{{ asset($payment->proof_image_path) }}" target="_blank" x-ref="content">
                                                    <x-icon-button class="bg-white">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-external-link"><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                                                    </x-icon-button>
                                                </a>
                                            </x-tooltip>
                                        </div>
                                    @endif
                                    @if ($payment->amount > 0)
                                        <div class="p-5 bg-white border rounded-md border-slate-200">
                                            <p class="text-base font-semibold"><x-currency />{{ number_format($payment->amount, 2) }}</p>
                                            <p class="text-xs">Amount Paid</p>
                                        </div>
                                    @endif
                                @else
                                    <div class="flex items-center justify-center w-full h-40 text-center border rounded-md border-slate-200">
                                        <p>No payment receipt uploaded.</p>
                                    </div>
                                @endif
                    
                                <div class="flex gap-5">
                                    @if (!empty($payment->proof_image_path))
                                        <x-form.input-group class="w-full">
                                            <x-form.input-label for='transaction_id'>Reference ID</x-form.input-label>
                                            <x-form.input-text wire:model.live='transaction_id' id="transaction_id" class="w-full" label="Reference ID" />
                                            <x-form.input-error field="transaction_id" />
                                        </x-form.input-group>
                                    @endif
                                    <x-form.input-group class="w-full">
                                        <x-form.input-label for='payment_date'>Date of Payment</x-form.input-label>
                                        <x-form.input-date wire:model.live='payment_date' id="payment_date" class="w-full" />
                                        <x-form.input-error field="payment_date" />
                                    </x-form.input-group>
                                </div>
                                <x-form.input-group>
                                    <x-form.input-label for='amount'>Confirm and enter the amount paid</x-form.input-label>
                                    <x-form.input-currency x-model="amount" wire:model.live='amount' id="amount" class="w-full" />
                                    <x-form.input-error field="amount" />
                                </x-form.input-group>
                            </div>

                            <x-loading wire:loading wire:target="validateReservation">Checking payment details</x-loading>
                            
                            <div class="flex justify-end gap-1">
                                <x-secondary-button type="button" x-on:click="show = false">Close</x-secondary-button>
                                @if ($reservation->status == 1)
                                    <x-primary-button type="button" wire:click="validateReservation">Confirm</x-primary-button>
                                @endif
                            </div>
                        </section>
                    @else
                        <div class="p-5 space-y-5" x-on:reservation-confirmed.window="show = false">
                            <hgroup>
                                <h2 class="font-semibold">Confirm Reservation</h2>
                                <p class="text-xs">This reservation is about to be confirmed</p>
                            </hgroup>

                            <x-loading wire:loading wire:target="confirm">Confirming reservation, please wait</x-loading>

                            <div class="flex justify-end gap-1">
                                <x-secondary-button type="button" x-on:click="$wire.set('can_confirm', false)">Back</x-secondary-button>
                                <x-primary-button type="button" wire:click="confirm">Confirm </x-primary-button>
                            </div>
                        </div>
                    @endif
                </div>
            </x-modal.full> 
        </div>
        HTML;
    }
}
