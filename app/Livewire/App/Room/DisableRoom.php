<?php

namespace App\Livewire\App\Room;

use App\Services\AuthService;
use App\Services\RoomService;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;

class DisableRoom extends Component
{
    use DispatchesToast;

    public $room;
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
            $service = new RoomService;
            $service->disable($this->room);

            $this->toast('Success!', description: 'Room disabled!');
            $this->dispatch('room-disabled');
            $this->reset('password');
            return;
        }

        $this->addError('password', 'Password mismatched, try again!');
    }

    public function render()
    {
        return <<<'HTML'
        <form class="p-5 space-y-5" wire:submit="submit" x-on:room-disabled.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold text-red-500">Disable Room</h2>
                <p class="text-xs">This room will not be able to be reserved</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for='password'>Enter your password</x-form.input-label>
                <x-form.input-text type="password" id="password" name="password" label="Password" wire:model.live="password" />
                <x-form.input-error field="password" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='submit'>Disabling room, please wait</x-loading>

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-danger-button>Disable</x-danger-button>
            </div>
        </form>
        HTML;
    }
}
