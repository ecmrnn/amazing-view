<?php

namespace App\Livewire\App\Room;

use App\Services\AuthService;
use App\Services\RoomService;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ChangeStatus extends Component
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
            $service->changeStatus($this->room);

            $this->toast('Success!', description: 'Room status updated!');
            $this->dispatch('status-updated');
            $this->reset('password');
            return;
        }

        $this->addError('password', 'Password mismatched, try again!');
    }
    
    public function render()
    {
        return <<<'HTML'
        <form class="p-5 space-y-5" wire:submit="submit" x-on:status-updated.window="show = false">
            <hgroup>
                <h2 class="font-semibold">Change Room Status</h2>
                <p class="text-xs">Update the room status here</p>
            </hgroup>

            @if ($room->status == App\Enums\RoomStatus::UNAVAILABLE->value)
                <x-info-message>
                    <div>
                        <h3 class="font-semibold">Note!</h3>
                        <p class="text-xs">This room will be available for reservation</p>
                    </div>
                </x-info-message>
            @else
                <x-danger-message>
                    <div>
                        <h3 class="font-semibold">Note!</h3>
                        <p class="text-xs">This room will not be available for reservation</p>
                    </div>
                </x-danger-message>
            @endif

            <x-form.input-group>
                <x-form.input-label for='password'>Enter your password</x-form.input-label>
                <x-form.input-text type="password" id="password" name="password" label="Password" wire:model.live="password" />
                <x-form.input-error field="password" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='submit'>Updating room status, please wait</x-loading>

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button>Edit</x-primary-button>
            </div>
        </form>
        HTML;
    }
}
