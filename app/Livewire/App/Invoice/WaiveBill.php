<?php

namespace App\Livewire\App\Invoice;

use App\Models\Invoice;
use App\Services\AuthService;
use App\Services\BillingService;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;

class WaiveBill extends Component
{
    use DispatchesToast;

    protected $listeners = [
        'waive-retracted' => 'refreshRetract',
    ];

    #[Validate] public $amount = 0;
    #[Validate] public $reason;
    #[Validate] public $password;
    public $invoice;
    public $balance;

    public function mount(Invoice $invoice) {
        $this->balance = $invoice->balance;
        $this->amount = (int) $invoice->balance;
        $this->reason = $invoice->waive_reason;
    }

    public function refreshRetract() {
        $this->balance = $this->invoice->balance;
        $this->amount = (int) $this->invoice->balance;
    }

    public function rules() {
        return [
            'amount' => 'required|integer|min:1|lte:balance',
            'reason' => 'required|string|max:50',
            'password' => 'required',
        ];
    }

    public function messages() {
        return [
            'amount.required' => 'Enter an amount',
            'password.required' => 'Enter your password',
            'reason.required' => 'Enter a reason',
        ];
    }

    public function submit() {
        $validated = $this->validate();

        $auth = new AuthService;

        if ($auth->validatePassword($this->password)) {
            $service = new BillingService;
            $invoice = $service->waive($this->invoice, $validated);

            if ($invoice) {
                $this->toast('Balance Waived', description: 'Balance has been waived successfully!');
                $this->dispatch('bill-waived');

                $this->amount = $invoice->balance;
                $this->reset('password');
                return;
            }
        }

        $this->addError('password', 'Password mismatched, try again!');
    }

    public function render()
    {
        return <<<'HTML'
        <form x-data="{ amount: @entangle('amount') }" wire:submit="submit" x-on:bill-waived.window="show = false" class="p-5 space-y-5">
            <hgroup>
                <h2 class='font-semibold'>Waive Balance</h2>
                <p class='text-xs'>Modify the balance directly here</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for='amount'>Enter the amount you want to waive</x-form.input-label>
                <x-form.input-currency id="amount" name="amount" x-model="amount"  autocomplete="off" />
                <x-form.input-error field="amount" />
            </x-form.input-group>

            <x-form.input-group>
                <x-form.input-label for='reason'>Enter the reason for the waive</x-form.input-label>
                <x-form.input-text id="reason" name="reason" label="Reason" wire:model.live="reason" />
                <x-form.input-error field="reason" />
            </x-form.input-group>

            <x-form.input-group>
                <x-form.input-label for='password-waive'>Enter your password</x-form.input-label>
                <x-form.input-text type="password" id="password-waive" name="password-waive" label="Password" wire:model.live="password" />
                <x-form.input-error field="password" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='submit'>Waiving balance, please wait</x-loading>

            <div class="flex justify-end gap-1">
                <x-secondary-button type='button' x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button>Waive</x-primary-button>
            </div>
        </form>
        HTML;
    }
}
