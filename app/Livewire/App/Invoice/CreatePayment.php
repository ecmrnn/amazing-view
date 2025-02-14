<?php

namespace App\Livewire\App\Invoice;

use App\Enums\ReservationStatus;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Reservation;
use App\Traits\DispatchesToast;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class CreatePayment extends Component
{
    use WithFilePond, DispatchesToast;
    
    public $invoice;
    public $reservation;
    #[Validate] public $proof_image_path;
    #[Validate] public $payment_date;
    #[Validate] public $payment_method = 'cash';
    #[Validate] public $transaction_id;
    #[Validate] public $purpose;
    #[Validate] public $amount = 0;
    
    public function mount(Invoice $invoice) {
        $this->invoice = $invoice;
        $this->payment_date = Carbon::now()->format('Y-m-d');
        $this->reservation = $invoice->reservation;
    }

    public function rules() {
        $rules = InvoicePayment::rules();
        $min = $this->invoice->balance > 500 ? 500 : 1;
        $rules['amount'] = 'required|numeric|min:' . $min . '|max:' . $this->invoice->balance;
        return $rules;
    }

    public function messages() {
        $messages = InvoicePayment::messages();
        $messages['amount.min'] = $this->invoice->balance > 500 ? 'The minimum amount is 500.00' : 'The minimum amount is 1.00';
        
        return $messages;
    }

    public function store() {
        $this->validate([
            'payment_date' => $this->rules()['payment_date'],
            'payment_method' => $this->rules()['payment_method'],
            'proof_image_path' => $this->rules()['proof_image_path'],
            'transaction_id' => $this->rules()['transaction_id'],
            'amount' => $this->rules()['amount'],
            'proof_image_path' => $this->rules()['proof_image_path'],
        ]);

        if ($this->reservation->status == ReservationStatus::AWAITING_PAYMENT->value) {
            $this->reservation->status = ReservationStatus::PENDING->value;
            $this->reservation->save();

            $this->dispatch('status-changed');
            $this->dispatch('pg:eventRefresh-ReservationTable');
        }

        if (!empty($this->proof_image_path)) {
            $this->proof_image_path = $this->proof_image_path->store('payments', 'public');
        }  
        
        $this->invoice->payments()->create([
            'transaction_id' => $this->transaction_id,
            'amount' => $this->amount,
            'payment_date' => $this->payment_date,
            'payment_method' => $this->payment_method,
            'proof_image_path' => $this->proof_image_path,
        ]);
        
        $this->invoice->balance -= $this->amount;

        if ($this->invoice->balance == 0) {
            $this->invoice->status = Invoice::STATUS_PAID;
        }

        $this->invoice->save();

        $this->reset('payment_method', 'proof_image_path', 'transaction_id', 'amount');
        $this->dispatch('payment-added');
        $this->dispatch('pg:eventRefresh-InvoicePaymentTable');
        $this->toast('Success', 'success', 'Yay, payment added!');
    }

    public function render()
    {
        return <<<'HTML'
            <section x-data="{ payment_method: @entangle('payment_method'), checked: false }" x-on:payment-added.window="show = false" class="p-5 space-y-5 bg-white">
                <hgroup>
                    <h2 class="text-lg font-semibold capitalize">Add Payment</h2>
                    <p class="text-sm">Enter the payment details made by the guest.</p>
                </hgroup>

                <div class="space-y-3">
                    <div class="space-y-2">
                        <x-form.input-label for="payment_date">Payment Date</x-form.input-label>
                        <x-form.input-date wire:model="payment_date" id="payment_date" class="w-full" />
                        <x-form.input-error field="payment_date" />
                    </div>

                    <div class="p-3 space-y-3 bg-white border rounded-lg border-slate-200">
                        <hgroup>
                            <h3 class="text-sm font-semibold">Payment Methods</h3>
                            <p class="text-xs text-zinc-800">Select how the customer wants to pay</p>
                        </hgroup>
                        {{-- Payment methods --}}
                        <div class="grid space-y-2">
                            <x-form.input-radio x-model="payment_method" wire:model.live="payment_method" name="payment_method" value="cash" id="cash" label="Cash" />
                            <x-form.input-radio x-model="payment_method" wire:model.live="payment_method" name="payment_method" value="gcash" id="gcash" label="GCash" />
                            <x-form.input-radio x-model="payment_method" wire:model.live="payment_method" name="payment_method" value="bank" id="bank" label="Bank Transfer" />
                        </div>
                        <x-form.input-error x-show="payment_method != 'cash'" field="transaction_id" />
                        <x-form.input-error field="downpayment" />
                        
                        <div x-show="payment_method != 'cash'">
                            <x-form.input-text wire:model.live='transaction_id' label="Reference No." id="transaction_id" />
                        </div>
                    </div>
                    <div x-show="payment_method != 'cash'">
                        <x-filepond::upload
                        wire:model.live="proof_image_path"
                        placeholder="Drag & drop your image or <span class='filepond--label-action'> Browse </span>"
                        />
                        <p class="max-w-sm text-xs">Please upload an image &lpar;<strong class="text-blue-500">JPG, JPEG, PNG</strong>&rpar; of the payment slip for your down payment. Maximum image size &lpar;<strong class="text-blue-500">1MB or 1024KB</strong>&rpar;</p>
                    </div>
                    
                    <x-form.input-error x-show="payment_method != 'cash'" field="proof_image_path" />
                    
                    <x-form.input-group>
                        <x-form.input-label for="amount">Enter the amount paid</x-form.input-label>
                        <x-form.input-currency wire:model.live='amount' id="amount" class="w-full" />
                        <x-form.input-error field="amount" />
                    </x-form.input-group>

                    <x-form.input-group>
                        <x-form.input-label for='purpose'>Select purpose of payment</x-form.input-label>
                        <x-form.select>
                            <option value="downpayment">Down Payment</option>
                            <option value="security deposit">Security Deposit</option>
                            <option value="partial">Partial Payment</option>
                            <option value="full payment">Full Payment</option>
                        </x-form.select>
                        <x-form.input-error field="purpose" />
                    </x-form.input-group>

                    <div class="px-3 py-2 border rounded-md border-slate-200">
                        <x-form.input-checkbox x-model="checked" id="checked" label="The information I have provided is true and correct." />
                    </div>
                </div>
                
                <div class="flex items-center justify-end gap-1">
                    <x-secondary-button type="button" x-on:click="$dispatch('cancel-confirmation'); show = false">Cancel</x-secondary-button>
                    <x-primary-button type="button" x-bind:disabled="!checked" wire:click="store">Submit Payment</x-primary-button>
                </div>
            </section>
        HTML;
    }
}
