<?php

namespace App\Livewire\App\Invoice;

use App\Models\Invoice;
use App\Services\AuthService;
use App\Services\BillingService;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Symfony\Component\Console\Descriptor\Descriptor;

class RetractWaive extends Component
{
    use DispatchesToast;

    protected $listeners = [
        'bill-waived' => 'refreshWaive',
    ];

    #[Validate] public $amount;
    #[Validate] public $password;
    public $invoice;

    public function rules() {
        return [
            'amount' => 'required|integer|gt:0',
            'password' => 'required',
        ];
    }

    public function messages() {
        return [
            'amount.required' => 'Enter an amount',
            'password.required' => 'Enter your password',
        ];
    }

    public function mount(Invoice $invoice) {
        $this->invoice = $invoice;
        $this->amount = (int) $invoice->waive_amount;
    }

    public function refreshWaive() {
        $this->amount = (int) $this->invoice->waive_amount;
    }

    public function submit() {
        $this->validate();

        $auth = new AuthService;

        if ($auth->validatePassword($this->password)) {
            $service = new BillingService;
            $invoice = $service->retractWaive($this->invoice, $this->amount);

            if ($invoice) {
                $this->amount = $invoice->waive_amount;
                $this->toast('Success!', description: 'Waive retracted successfully!');
                $this->dispatch('waive-retracted');
                $this->reset('password');
                return;
            }
        }

        $this->addError('password', 'Password mismatched, try again!');
    }

    public function render()
    {
        return <<<'HTML'
        <form x-data="{ amount: @entangle('amount') }" wire:submit="submit" class="p-5 space-y-5" x-on:waive-retracted.window="show = false">
            <hgroup>
                <h2 class='font-semibold'>Retract Waive</h2>
                <p class='text-xs'>Retract a certain amount from the waived bill</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for='amount-retract'>Enter amount to retract</x-form.input-label>
                <x-form.input-currency x-model="amount" id="amount-retract" name="amount-retract" />
                <x-form.input-error field="amount-retract" />
            </x-form.input-group>

            <x-form.input-group>
                <x-form.input-label for='password-retract'>Enter your password</x-form.input-label>
                <x-form.input-text type="password" id="password-retract" name="password-retract" label="Password" wire:model.live="password" />
                <x-form.input-error field="password" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='submit'>Retracting waive, please wait</x-loading>

            <div class="flex justify-end gap-1">
                <x-secondary-button type='button' x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button>Retract</x-primary-button>
            </div>
        </form>
        HTML;
    }
}
