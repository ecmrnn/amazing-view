<?php

namespace App\Livewire\App\Content\Home;

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
            // delete service
            $this->service->status = FeaturedService::STATUS_ACTIVE;
            $this->service->save();

            $this->toast('Service Deactivated', 'success', 'Service deactivated successfully!');
            $this->dispatch('service-hidden');

            // reset
            $this->reset('password');
        } else {
            $this->toast('Activating Service Failed', 'info', 'Incorrect password entered');
        }
    }

    public function render()
    {
        return <<<'HTML'
        <div>
            <section class="p-5 space-y-5 bg-white" x-on:service-hidden.window="show = false">
                <hgroup>
                    <h2 class="font-semibold text-center capitalize">Activate Service</h2>
                    <p class="max-w-sm text-sm text-center">You are about to activate this service</p>
                </hgroup>

                <div class="space-y-2">
                    <p class="text-xs">Enter your password to activate this service.</p>
                    <x-form.input-text wire:model.live="password" type="password" label="Password" id="activate-{{ $service->id }}-password" />
                    <x-form.input-error field="password" />
                </div>
                
                <div class="flex items-center justify-center gap-1">
                    <x-secondary-button type="button" x-on:click="show = false">No, Cancel</x-secondary-button>
                    <x-primary-button type="button" wire:click='activateService()'>Yes, Activate</x-primary-button>
                </div>
            </section>
        </div>
        HTML;
    }
}
