<?php

namespace App\Livewire\App\Content\Home;

use App\Enums\FeaturedServiceStatus;
use App\Models\FeaturedService;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Component;

class DeactivateService extends Component
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

    public function deactivateService() {
        $this->validate([
            'password' => $this->rules()['password']
        ]);

        $admin = Auth::user();

        if (Hash::check($this->password, $admin->password)) {
            // delete service
            $count = FeaturedService::count();
            
            if ($count <= 3) {
                $this->toast('Deactivate Failed', 'info', 'Minimum of three services must be featured.');
                return;
            }

            $this->service->status = FeaturedServiceStatus::INACTIVE->value;
            $this->service->save();
            
            $this->toast('Service Deactivated', description: 'Service deactivated successfully!');
            $this->dispatch('service-hidden');
            $this->reset('password');
        } else {
            $this->addError('password', 'Password mismatched, try again');
        }
    }

    public function render()
    {
        return <<<'HTML'
        <form wire:submit="deactivateService" class="p-5 space-y-5" x-on:service-hidden.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold text-red-500">Deactivate Service</h2>
                <p class="max-w-sm text-sm">Are you sure you really want to deactivate this service?</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for="deactivate-{{ $service->id }}-password">Enter your password to deactivate</x-form.input-label>
                <x-form.input-text wire:model.live="password" type="password" label="Password" id="deactivate-{{ $service->id }}-password" />
                <x-form.input-error field="password" />
            </x-form.input-group>
            
            <x-loading wire:loading wire:target="deactivateService">Deactivating service, please wait</x-loading>
            
            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-danger-button type="submit">Deactivate</x-danger-button>
            </div>
        </form>
        HTML;
    }
}
