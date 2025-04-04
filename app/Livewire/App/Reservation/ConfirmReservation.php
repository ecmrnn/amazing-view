<?php

namespace App\Livewire\App\Reservation;

use App\Enums\ReservationStatus;
use App\Mail\Reservation\Confirmed;
use App\Models\Reservation;
use App\Services\BillingService;
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
    public $total_amount;
    public $discount;
    public $senior_count;
    public $pwd_count;
    public $adult_count;
    public $children_count;

    public $promo;
    public $is_valid = false;
    public $can_confirm = false;
    public $can_discard = false;
    #[Validate] public $amount = 0;
    #[Validate] public $transaction_id;
    #[Validate] public $payment_date;
    #[Validate] public $password;

    public function rules() {
        return [
            'amount' => 'required|integer|gt:500|max:' . ceil($this->total_amount),
            'transaction_id' => 'nullable',
            'payment_date' => 'date|required',
            'password' => 'required',
        ];
    }

    public function messages() {
        return [
            'password.required' => 'Enter your password',
        ];
    }

    public function mount($reservation)
    {
        $this->reservation = $reservation;
        $this->total_amount = $reservation->invoice->total_amount;
        $this->payment = $reservation->invoice->payments()->wherePurpose('downpayment')->first();
        $this->discount = $reservation->discounts()->whereDescription('Senior and PWD discount')->first();
        $this->senior_count = $reservation->senior_count;
        $this->pwd_count = $reservation->pwd_count;
        $this->adult_count = $reservation->adult_count;
        $this->children_count = $reservation->children_count;

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

        $this->is_valid = true;
        $this->can_confirm = true;
        $this->can_discard = false;
    }
    
    public function discard() {
        $this->is_valid = true;
        $this->can_discard = true;
        $this->can_confirm = false;
    }

    public function discardPayment() {
        $service = new BillingService;
        $service->discardPayment($this->payment);

        $this->toast('Success!', description: 'The payment has been discarded');
        $this->dispatch('reservation-confirmed');
    }

    public function confirm() {
        $data = [
            'amount' => $this->amount,
            'transaction_id' => $this->transaction_id,
            'payment_date' => $this->payment_date,
            'orid' => $this->payment->orid,
            'senior_count' => $this->senior_count,
            'pwd_count' => $this->pwd_count,
        ];

        // Validate senior and pwd count
        if ($this->senior_count > 0 || $this->pwd_count > 0) {
            $this->validate([
                'senior_count' => 'nullable|lte:adult_count|integer',
                'pwd_count' => 'nullable|integer',
            ]);
            
            if ($this->senior_count + $this->pwd_count > $this->adult_count + $this->children_count) {
                $this->addError('pwd_count', 'Total Seniors and PWDs cannot exceed total guests');
                return false;
            }
        }
        
        $service = new ReservationService;
        $service->confirm($this->reservation, $data);

        // Send email about confirmed reservation
        Mail::to($this->reservation->user->email)->queue(new Confirmed($this->reservation));

        $this->toast('Success!', description: 'Reservation confirmed!');
        $this->dispatch('reservation-confirmed');
    }

    public function render()
    {
        return <<<'HTML'
        <div>
            <x-modal.full name="show-downpayment-modal" maxWidth="sm">
                <form wire:submit="confirm" x-data="{ checked: false, amount: @entangle('amount') }" x-on:reservation-confirmed.window="show = false">
                    @if(!$is_valid)
                        <section class="p-5 space-y-5">
                            <hgroup>
                                <h2 class="font-semibold capitalize text">Confirm Reservation</h2>
                                <p class="max-w-sm text-xs">Confirm that the payment made below are successful before confirming the reservation.</p>
                            </hgroup>
                            <div class="relative space-y-5">
                                @if (!empty($payment))
                                    @if (!empty($payment->proof_image_path))
                                        <div class="w-full overflow-auto border rounded-md max-h-96 border-slate-200">
                                            <img src="{{ asset('storage/' . $payment->proof_image_path) }}" alt="payment receipt" class="h-full" />
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
                            
                            <div class="flex justify-between gap-1">
                                <x-danger-button type="button" wire:click="discard" wire:loading.attr="disabled">Discard</x-danger-button>
                                
                                <div class="flex gap-1">
                                    <x-secondary-button type="button" x-on:click="show = false">Close</x-secondary-button>
                                    <x-primary-button type="button" wire:loading.attr="disabled" wire:click="validateReservation">Confirm</x-primary-button>
                                </div>
                            </div>
                        </section>
                    @else
                        @if ($can_confirm)
                            <div class="p-5 space-y-5" x-on:reservation-confirmed.window="show = false">
                                <hgroup>
                                    <h2 class="font-semibold">Confirm Reservation</h2>
                                    <p class="text-xs">This reservation is about to be confirmed</p>
                                </hgroup>

                                @if ($discount)
                                    <div class="p-5 space-y-5 bg-white border rounded-md border-slate-200">
                                        <hgroup>
                                            <h2 class='text-sm font-semibold'>Discounts Applied</h2>
                                            <p class='text-xs'>Verify if the applied discount is valid</p>
                                        </hgroup>

                                        <div>
                                            @if ($discount->image)
                                                <x-img src="{{ $discount->image }}" />
                                            @endif

                                            <div class="flex items-center justify-between mt-5">
                                                <div>
                                                    <p class="text-sm font-semibold">{{ $discount->description }}</p>
                                                    <p class="text-xs">Amount: <x-currency />{{ number_format($discount->amount, 2) }}</p>
                                                </div>

                                                @if ($discount->image)
                                                    <x-tooltip text="Download Image" dir="left">
                                                        <a href="{{ asset('storage/' . $discount->image) }}" download>
                                                            <x-icon-button x-ref="content">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-image-down-icon lucide-image-down"><path d="M10.3 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10l-3.1-3.1a2 2 0 0 0-2.814.014L6 21"/><path d="m14 19 3 3v-5.5"/><path d="m17 22 3-3"/><circle cx="9" cy="9" r="2"/></svg>
                                                            </x-icon-button>
                                                        </a>
                                                    </x-tooltip>
                                                @endif
                                            </div>
                                        </div>
 
                                        <div x-data="{ senior_count: @entangle('senior_count'), pwd_count: @entangle('pwd_count') }">
                                            <div class="grid grid-cols-2 gap-5">
                                                <x-form.input-group>
                                                    <x-form.input-label for='senior_count'>Seniors</x-form.input-label>
                                                    <x-form.input-number x-model="senior_count" id="senior_count" name="senior_count" label="Senior Count" />
                                                </x-form.input-group>

                                                <x-form.input-group>
                                                    <x-form.input-label for='pwd_count'>PWDs</x-form.input-label>
                                                    <x-form.input-number x-model="pwd_count" id="pwd_count" name="pwd_count" label="Senior Count" />
                                                </x-form.input-group>
                                            </div>

                                            <div class="mt-2">
                                                <x-form.input-error field="senior_count" />
                                                <x-form.input-error field="pwd_count" />
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <x-loading wire:loading wire:target="confirm">Confirming reservation, please wait</x-loading>

                                <div class="flex justify-end gap-1">
                                    <x-secondary-button type="button" x-on:click="$wire.set('is_valid', false)">Back</x-secondary-button>
                                    <x-primary-button type="submit">Confirm</x-primary-button>
                                </div>
                            </div>
                        @else
                            <div class="p-5 space-y-5" x-on:reservation-confirmed.window="show = false">
                                <hgroup>
                                    <h2 class="font-semibold">Discard Payment</h2>
                                    <p class="text-xs">This payment is about to be discarded</p>
                                </hgroup>

                                <x-note>Discarding the payment for this reservation will revert its status to <strong>Awaiting Payment</strong>. Notify the guest to send another payment.</x-note>

                                <x-loading wire:loading wire:target="discard">Discarding payment, please wait</x-loading>

                                <div class="flex justify-end gap-1">
                                    <x-secondary-button type="button" x-on:click="$wire.set('is_valid', false)">Back</x-secondary-button>
                                    <x-danger-button type="button" wire:click="discardPayment" wire:loading.attr="disabled">Discard</x-danger-button>
                                </div>
                            </div>
                        @endif
                    @endif
                </form>
            </x-modal.full> 
        </div>
        HTML;
    }
}
