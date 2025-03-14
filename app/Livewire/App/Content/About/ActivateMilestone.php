<?php

namespace App\Livewire\App\Content\About;

use App\Services\AuthService;
use App\Services\MilestoneService;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ActivateMilestone extends Component
{
    use DispatchesToast;
    
    #[Validate] public $password;
    public $milestone;

    public function rules() {
        return [
            'password' => 'required',
        ];
    }

    public function activate() {
        $this->validate();

        $auth = new AuthService;

        if ($auth->validatePassword($this->password)) {
            $service = new MilestoneService;
            $service->toggleStatus($this->milestone);

            $this->toast('Success', description: 'Milestone activated successfully!');
            $this->dispatch('milestone-activated');
            $this->reset('password');
        } else {
            $this->addError('password', 'Password mismatched, try again');
        }
        
    }

    public function render()
    {
        return <<<'HTML'
        <form class="p-5 space-y-5" wire:submit="activate" x-on:milestone-activated.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold">Activate Milestone</h2>
                <p class="text-xs">Enter your password to activate this milestone</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for='activate-password-{{ $milestone->id }}'></x-form.input-label>
                <x-form.input-text wire:model.live="password" type="password" id="activate-password-{{ $milestone->id }}" name="activate-password-{{ $milestone->id }}" label="Password" />
                <x-form.input-error field="password" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='activate'>Activating milestone, please wait</x-loading>

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button type="submit">Activate</x-primary-button>
            </div>
        </form>
        HTML;
    }
}
