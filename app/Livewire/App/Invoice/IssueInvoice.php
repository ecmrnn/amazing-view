<?php

namespace App\Livewire\App\Invoice;

use App\Models\Invoice;
use App\Services\AuthService;
use App\Services\BillingService;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;

class IssueInvoice extends Component
{
    use DispatchesToast;

    public $invoice;
    #[Validate] public $password;

    public function mount(Invoice $invoice) {
        $this->invoice = $invoice;    
    }

    public function rules() {
        return [
            'password' => 'required',
        ];
    }

    public function issue() {
        $this->validate([
            'password' => $this->rules()['password'],
        ]);
        
        $auth = new AuthService;

        if ($auth->validatePassword($this->password)) {
            $billing = new BillingService;
            $billing->issueInvoice($this->invoice);

            $this->dispatch('invoice-issued');
            $this->toast('Success!', description: 'Invoice issued, generating PDF now!');
        } else {
            $this->addError('password', 'Password mismatched, try again');
        }
    }

    public function render()
    {
        return <<<'HTML'
        <form wire:submit="issue" class="p-5 space-y-5" x-on:invoice-issued.window="show = false">
            <hgroup>
                <h2 class="font-semibold">Issue Invoice</h2>
                <p class="text-xs">Enter your password to issue an invoice to this bill</p>
            </hgroup>

            <div>
                <x-form.input-group>
                    <x-form.input-label for='password'>Enter your password</x-form.input-label>
                    <x-form.input-text type="password" wire:model.live="password" id="password" name="password" label="Password" />
                    <x-form.input-error field="password" />
                </x-form.input-group>
            </div>

            <x-note>Issuing this invoice will lock the associated billing record.</x-note>

            <x-loading wire:loading wire:target="issue">Generating invoice, please wait</x-loading>

            <div class="flex justify-end gap-1">
                <x-secondary-button x-on:click="show = false" type="button">Cancel</x-secondary-button>
                <x-primary-button type="submit">Issue</x-primary-button>
            </div>
        </form>
        HTML;
    }
}
