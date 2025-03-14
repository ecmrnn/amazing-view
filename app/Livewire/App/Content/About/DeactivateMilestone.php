<?php

namespace App\Livewire\App\Content\About;

use App\Services\AuthService;
use App\Services\MilestoneService;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;

class DeactivateMilestone extends Component
{
    use DispatchesToast;
    
    #[Validate] public $password;
    public $milestone;

    public function rules() {
        return [
            'password' => 'required',
        ];
    }

    public function deactivate() {
        $this->validate();

        $auth = new AuthService;

        if ($auth->validatePassword($this->password)) {
            $service = new MilestoneService;
            $service->toggleStatus($this->milestone);

            $this->toast('Success', description: 'Milestone deactivated successfully!');
            $this->dispatch('milestone-deactivated');
            $this->reset('password');
        } else {
            $this->addError('password', 'Password mismatched, try again');
        }
        
    }

    public function render()
    {
        return <<<'HTML'
        <form class="p-5 space-y-5" wire:submit="deactivate" x-on:milestone-deactivated.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold text-red-500">Deactivate Milestone</h2>
                <p class="text-xs">Enter your password to deactivate this milestone</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for='deactivate-password-{{ $milestone->id }}'></x-form.input-label>
                <x-form.input-text wire:model.live="password" type="password" id="deactivate-password-{{ $milestone->id }}" name="deactivate-password-{{ $milestone->id }}" label="Password" />
                <x-form.input-error field="password" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='deactivate'>Deactivating milestone, please wait</x-loading>

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-danger-button type="submit">Deactivate</x-danger-button>
            </div>
        </form>
        HTML;
    }
}
