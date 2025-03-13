<?php

namespace App\Livewire\App\Content\Home;

use App\Enums\FeaturedServiceStatus;
use App\Models\FeaturedService;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ActivateService extends Component
{
    use DispatchesToast;
    
    #[Validate] public $password;
    public $service;

    public function mount(FeaturedService $service) {
        $this->service = $service;
    }

    public function rules() {
        return [
            'password' => 'required'
        ];
    }

    public function activateService() {
        $this->validate([
            'password' => $this->rules()['password']
        ]);

        $admin = Auth::user();

        if (Hash::check($this->password, $admin->password)) {
            $this->service->status = FeaturedServiceStatus::ACTIVE->value;
            $this->service->save();

            $this->toast('Service Deactivated', 'success', 'Service deactivated successfully!');
            $this->dispatch('service-hidden');

            // reset
            $this->reset('password');
        } else {
            $this->addError('password', 'Password mismatched, try again');
        }
    }

    public function render()
    {
        return <<<'HTML'
        <form wire:submit="activateService" class="p-5 space-y-5" x-on:service-hidden.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold">Activate Service</h2>
                <p class="text-sm">You are about to activate this service</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for="activate-{{ $service->id }}-password">Enter your password to activate</x-form.input-label>
                <x-form.input-text wire:model.live="password" type="password" label="Password" id="activate-{{ $service->id }}-password" />
                <x-form.input-error field="password" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='activateService'>Activating service, please wait</x-loading>
            
            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button type="submit">Activate</x-primary-button>
            </div>
        </form>
        HTML;
    }
}
