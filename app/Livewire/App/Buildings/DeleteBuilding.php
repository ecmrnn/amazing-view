<?php

namespace App\Livewire\App\Buildings;

use App\Models\Building;
use App\Services\AuthService;
use App\Services\BuildingService;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;

class DeleteBuilding extends Component
{
    use DispatchesToast;
    
    #[Validate] public $password;
    public $building;

    public function mount(Building $building) {
        $this->building = $building;
    }

    public function rules() {
        return [
            'password' => 'required'
        ];
    }

    public function messages() {
        return [
            'password.required' => 'Enter your password',
        ];
    }

    public function deleteBuilding() {
        $this->validate([
            'password' => $this->rules()['password']
        ]);

        $auth = new AuthService;

        if ($auth->validatePassword($this->password)) {
            if ($this->building->rooms->count() == 0) {
                $service = new BuildingService;
                $service->delete($this->building);

                $this->toast('Building Deleted', 'success', 'building deleted successfully!');
                $this->dispatch('building-deleted');
                $this->reset('password');
                return;
            }

            $this->addError('password', 'Building has rooms, cannot be deleted');
        } 

        $this->addError('password', 'Password mismatched, try again!');
    }

    public function render()
    {
        return <<<'HTML'
            <form class="p-5 space-y-5" x-on:building-deleted.window="show = false" wire:submit="deleteBuilding">
                <hgroup>
                    <h2 class="text-lg font-semibold text-red-500">Delete Building</h2>
                    <p class="text-xs">Are you sure you really want to delete this building?</p>
                </hgroup>

                <x-form.input-group>
                    <x-form.input-label for="delete-{{ $building->id }}-password">Enter your password</x-form.input-label>
                    <x-form.input-text wire:model.live="password" type="password" label="Password" id="delete-{{ $building->id }}-password" />
                    <x-form.input-error field="password" />
                </x-form.input-group>

                <x-loading wire:loading wire:target='deleteBuilding'>Deleting building, please wait</x-loading>
                
                <div class="flex justify-end gap-1">
                    <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                    <x-danger-button>Delete</x-danger-button>
                </div>
            </form>
        HTML;
    }
}
