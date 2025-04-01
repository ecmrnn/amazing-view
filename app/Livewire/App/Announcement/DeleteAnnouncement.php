<?php

namespace App\Livewire\App\Announcement;

use App\Services\AnnouncementService;
use App\Services\AuthService;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;

class DeleteAnnouncement extends Component
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
            $announcement = $service->delete($this->announcement);

            if ($announcement) {
                $this->dispatch('announcement-deleted');
                $this->reset('password');
                $this->toast('Success', description: 'Announcement deleted!');
                return;
            }
        }

        $this->addError('password', 'Password mismatched, try again!');
    }

    public function render()
    {
        return <<<'HTML'
        <form wire:submit="submit" class="p-5 space-y-5" x-on:announcement-deleted.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold text-red-500">Delete Announcement</h2>
                <p class="text-xs">This announcement is about to be deleted</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for='password-delete-{{ $announcement->id }}'>Enter your password</x-form.input-label>
                <x-form.input-text wire:model.live="password" type="password" id="password-delete-{{ $announcement->id }}" name="password-delete-{{ $announcement->id }}" label="Password" />
                <x-form.input-error field="password" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='submit'>Deleteing announcement, please wait</x-loading>

            <div class="flex justify-end gap-1">
                <x-secondary-button type='button' x-on:click="show = false">Cancel</x-secondary-button>
                <x-danger-button type="submit">Delete</x-danger-button>
            </div>
        </form>
        HTML;
    }
}
