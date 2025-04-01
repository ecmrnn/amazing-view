<?php

namespace App\Livewire\App\Announcement;

use App\Services\AnnouncementService;
use App\Services\AuthService;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;

class DisableAnnouncement extends Component
{
    use DispatchesToast;
    
    public $announcement;
    #[Validate] public $password;

    public function rules() {
        return [
            'password' => 'required',
        ];
    }

    public function messages() {
        return [
            'password.required' => 'Enter your password',
        ];
    }

    public function submit() {
        $this->validate();

        $auth = new AuthService;

        if ($auth->validatePassword($this->password)) {
            $service = new AnnouncementService;
            $service->disable($this->announcement);

            $this->dispatch('announcement-disabled');
            $this->reset('password');
            $this->toast('Success', description: 'Announcement disabled!');
            return;
        }

        $this->addError('password', 'Password mismatched, try again!');
    }

    public function render()
    {
        return <<<'HTML'
        <form wire:submit="submit" class="p-5 space-y-5" x-on:announcement-disabled.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold text-red-500">Disable Announcement</h2>
                <p class="text-xs">This announcement will not be visible to the customers</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for='password-{{ $announcement->id }}'>Enter your password</x-form.input-label>
                <x-form.input-text wire:model.live="password" type="password" id="password-{{ $announcement->id }}" name="password-{{ $announcement->id }}" label="Password" />
                <x-form.input-error field="password" />
            </x-form.input-group>

            <div class="flex justify-end gap-1">
                <x-secondary-button type='button' x-on:click="show = false">Cancel</x-secondary-button>
                <x-danger-button type="submit">Disable</x-danger-button>
            </div>
        </form>
        HTML;
    }
}
