<?php

namespace App\Livewire\App\Room;

use App\Models\Room;
use App\Services\AuthService;
use App\Services\RoomService;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;

class DeleteRoom extends Component
{
    use DispatchesToast;
    
    #[Validate] public $password;
    public $room;

    public function mount(Room $room) {
        $this->room = $room;
    }

    public function rules() {
        return [
            'password' => 'required'
        ];
    }

    public function deleteRoom() {
        $validated = $this->validate([
            'password' => $this->rules()['password']
        ]);

        $auth = new AuthService;

        if ($auth->validatePassword($this->password)) {
            $service = new RoomService;
            $service->delete($this->room, $validated);
            
            $this->toast('Room Deleted', 'success', 'Room deleted successfully!');
            $this->dispatch('room-deleted');
            $this->dispatch('pg:eventRefresh-RoomTable');
            $this->reset('password');
            return $this->redirectRoute('app.room.index', ['type' => $this->room->roomType->id], navigate: true);
        }

        $this->addError('password', 'Password mismatched, try again');
    }

    public function render()
    {
        return <<<'HTML'
            <form wire:submit="deleteRoom" class="p-5 space-y-5" x-on:room-deleted.window="show = false">
                <hgroup>
                    <h2 class="text-lg font-semibold text-red-500">Delete room</h2>
                    <p class="max-w-sm text-sm">Are you sure you really want this room?</p>
                </hgroup>

                <x-form.input-group>
                    <x-form.input-label for="password">Enter your password</x-form.input-label>
                    <x-form.input-text wire:model.live="password" type="password" label="Password" id="delete-{{ $room->id }}-password" />
                    <x-form.input-error field="password" />
                </x-form.input-group>

                <x-loading wire:loading wire:target='deleteRoom'>Deleting room, please wait</x-loading>
                
                <div class="flex justify-end gap-1">
                    <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                    <x-danger-button>Delete</x-danger-button>
                </div>
            </form>
        HTML;
    }
}
