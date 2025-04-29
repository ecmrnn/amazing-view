<?php

namespace App\Livewire\App\Invoice;

use App\Enums\ReservationStatus;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Reservation;
use App\Services\BillingService;
use App\Traits\DispatchesToast;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
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
    #[Validate] public $amount = 500;
    
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

    public function changePurpose() {
        if ((float) $this->amount == (float) $this->invoice->balance) {
            $this->purpose = 'full payment';
        } else {
            $this->purpose = 'partial';
        }
    }

    public function store() {
        $user = Auth::user();
        if ($user->hasRole('guest')) {
            $validated = $this->validate([
                'payment_date' => $this->rules()['payment_date'],
                'payment_method' => $this->rules()['payment_method'],
                'proof_image_path' => $this->rules()['proof_image_path'],
                'purpose' => $this->rules()['purpose'],
                'amount' => $this->rules()['amount'],
                'proof_image_path' => $this->rules()['proof_image_path'],
            ]);
        } else {
            $validated = $this->validate([
                'payment_date' => $this->rules()['payment_date'],
                'payment_method' => $this->rules()['payment_method'],
                'purpose' => $this->rules()['purpose'],
                'proof_image_path' => $this->rules()['proof_image_path'],
                'transaction_id' => $this->rules()['transaction_id'],
                'amount' => $this->rules()['amount'],
                'proof_image_path' => $this->rules()['proof_image_path'],
            ]);
        }

        $billing = new BillingService;
        $billing->addPayment($this->invoice, $validated);

        $this->dispatch('payment-added');
        $this->dispatch('status-changed');
        $this->dispatch('pg:eventRefresh-ReservationTable');
        $this->dispatch('pg:eventRefresh-InvoicePaymentTable');
        $this->toast('Success', 'success', 'Yay, payment added!');
        $this->reset('payment_method', 'proof_image_path', 'transaction_id', 'amount');
    }

    public function render()
    {
        return <<<'HTML'
            <form wire:submit="store" x-data="{ payment_method: @entangle('payment_method') }" x-on:payment-added.window="show = false" class="p-5 space-y-5">
                <hgroup>
                    <h2 class="text-lg font-semibold capitalize">Add Payment</h2>
                    <p class="text-xs">Enter the payment details made by the guest.</p>
                </hgroup>

                <div class="space-y-3">
                    <div class="space-y-2">
                        <x-form.input-label for="create_payment_date">Payment Date</x-form.input-label>
                        <x-form.input-date wire:model="payment_date" id="create_payment_date" class="w-full" />
                        <x-form.input-error field="payment_date" />
                    </div>

                    <div class="p-5 space-y-5 bg-white border rounded-md border-slate-200">
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
                        <!-- <div x-show="payment_method != 'cash'">
                            </div> -->
                            <x-form.input-error field="transaction_id" />
                        <x-form.input-error field="downpayment" />
                    </div>
                    <div x-show="payment_method != 'cash'" class="space-y-3">
                        <x-form.input-label for="transaction_id">Enter Reference No.</x-form.input-label>

                        <template x-if="payment_method == 'gcash'">
                            <x-form.input-text wire:model.live='transaction_id'
                                x-mask:dynamic="$input.startsWith('00')
                                ? '9999 999 999999'
                                : '**** **** **** **** **** **** **** **** **** ****'"
                                label="Reference No." id="transaction_id" />
                        </template>

                        <template x-if="payment_method == 'bank'">
                            <x-form.input-text wire:model.live='transaction_id' x-mask="**** **** **** **** **** **** **** **** **** ****" label="Reference No." id="transaction_id" />
                        </template>
                        <x-filepond::upload
                        wire:model.live="proof_image_path"
                        placeholder="Drag & drop your image or <span class='filepond--label-action'> Browse </span>"
                        />
                        <p class="max-w-sm text-xs">Please upload an image &lpar;<strong class="text-blue-500">JPG, JPEG, PNG</strong>&rpar; of the payment slip for your down payment. Maximum image size &lpar;<strong class="text-blue-500">1MB or 1024KB</strong>&rpar;</p>
                    </div>

                    <x-form.input-error x-show="payment_method != 'cash'" field="proof_image_path" />
                    
                    <x-form.input-group>
                        <x-form.input-label for="create_amount-{{ $invoice->id }}">Enter the amount paid</x-form.input-label>
                        <x-form.input-currency wire:model.live.debounce.150ms='amount' id="create_amount-{{ $invoice->id }}" wire:input="changePurpose()" class="w-full" />
                        <x-form.input-error field="amount" />
                    </x-form.input-group>

                    <x-form.input-group>
                        <x-form.input-label for='create_purpose'>Select purpose of payment</x-form.input-label>
                        <x-form.select wire:model.live="purpose" id="create_purpose">
                            <option value="downpayment">Down Payment</option>
                            <option value="security deposit">Security Deposit</option>
                            <option value="partial">Partial Payment</option>
                            <option value="full payment">Full Payment</option>
                        </x-form.select>
                        <x-form.input-error field="purpose" />
                    </x-form.input-group>
                </div>

                <x-loading wire:loading wire:target='store'>Submitting payment, please wait</x-loading>
                
                <div class="flex items-center justify-end gap-1">
                    <x-secondary-button type="button" x-on:click="$dispatch('cancel-confirmation'); show = false">Cancel</x-secondary-button>
                    <x-primary-button>Submit</x-primary-button>
                </div>
            </form>
        HTML;
    }
}
