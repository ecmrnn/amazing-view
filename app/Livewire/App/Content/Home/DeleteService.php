<?php

namespace App\Livewire\App\Content\Home;

use App\Models\FeaturedService;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;

class DeleteService extends Component
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

    public function deleteService() {
        $this->validate([
            'password' => $this->rules()['password']
        ]);

        $admin = Auth::user();

        if (Hash::check($this->password, $admin->password)) {
            // delete image
            Storage::disk('public')->delete($this->service->image);
            
            // delete service
            $this->service->delete();
            
            $this->toast('Service Deleted', 'success', 'Service deleted successfully!');
            $this->dispatch('service-deleted');
            $this->reset('password');
        } else {
            $this->addError('password', 'Password mismatched, try again');
        }
    }

    public function render()
    {
        return <<<'HTML'
        <form wire:submit="deleteService" class="p-5 space-y-5" x-on:service-deleted.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold text-red-500">Delete Service</h2>
                <p class="max-w-sm text-sm">Are you sure you really want this service?</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for="delete-{{ $service->id }}-password">Enter your password to delete</x-form.input-label>
                <x-form.input-text wire:model.live="password" type="password" label="Password" id="delete-{{ $service->id }}-password" />
                <x-form.input-error field="password" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='deleteService'>Deleting service, please wait</x-loading>
            
            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-danger-button type="submit">Delete</x-danger-button>
            </div>
        </form>
        HTML;
    }
}
