<?php

namespace App\Livewire\App\Buildings;

use App\Models\Building;
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

    public function deleteBuilding() {
        $this->validate([
            'password' => $this->rules()['password']
        ]);

        $admin = Auth::user();

        if (Hash::check($this->password, $admin->password)) {
            // delete image
            Storage::disk('public')->delete($this->building->image);
            
            // delete building
            $this->building->delete();
            
            $this->toast('Building Deleted', 'success', 'building deleted successfully!');
            $this->dispatch('building-deleted');

            // reset
            $this->reset('password');
        } else {
            $this->toast('Deletion Failed', 'info', 'Incorrect password entered');
        }
    }

    public function render()
    {
        return <<<'HTML'
            <section class="p-5 space-y-5 bg-white" x-on:building-deleted.window="show = false">
                <hgroup>
                    <h2 class="font-semibold text-center text-red-500 capitalize">Delete Building</h2>
                    <p class="max-w-sm text-sm text-center">Are you sure you really want this building?</p>
                </hgroup>

                <div class="space-y-2">
                    <p class="text-xs">Enter your password to delete this building.</p>
                    <x-form.input-text wire:model.live="password" type="password" label="Password" id="delete-{{ $building->id }}-password" />
                    <x-form.input-error field="password" />
                </div>
                
                <div class="flex items-center justify-center gap-1">
                    <x-secondary-button type="button" x-on:click="show = false">No, Cancel</x-secondary-button>
                    <x-danger-button type="button" wire:click='deleteBuilding()'>Yes, Delete</x-danger-button>
                </div>
            </section>
        HTML;
    }
}
