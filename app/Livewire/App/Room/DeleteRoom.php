<?php

namespace App\Livewire\App\Room;

use App\Models\Room;
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
        $this->validate([
            'password' => $this->rules()['password']
        ]);

        $admin = Auth::user();

        if (Hash::check($this->password, $admin->password)) {
            if (!empty($this->room->image_1_path)) {
                // delete image
                Storage::disk('public')->delete($this->room->image_1_path);
            }
            
            // delete room
            $this->room->delete();
            
            $this->toast('Room Deleted', 'success', 'Room deleted successfully!');
            $this->dispatch('room-deleted');
            $this->dispatch('pg:eventRefresh-RoomTable');

            // reset
            $this->reset('password');
        } else {
            $this->toast('Deletion Failed', 'info', 'Incorrect password entered');
        }
    }

    public function render()
    {
        return <<<'HTML'
            <section class="p-5 space-y-5 bg-white" x-on:room-deleted.window="show = false">
                <hgroup>
                    <h2 class="font-semibold text-center text-red-500 capitalize">Delete room</h2>
                    <p class="max-w-sm text-sm text-center">Are you sure you really want this room?</p>
                </hgroup>

                <div class="space-y-2">
                    <p class="text-xs">Enter your password to delete this room.</p>
                    <x-form.input-text wire:model.live="password" type="password" label="Password" id="delete-{{ $room->id }}-password" />
                    <x-form.input-error field="password" />
                </div>
                
                <div class="flex items-center justify-center gap-1">
                    <x-secondary-button type="button" x-on:click="show = false">No, Cancel</x-secondary-button>
                    <x-danger-button type="button" wire:click='deleteRoom()'>Yes, Delete</x-danger-button>
                </div>
            </section>
        HTML;
    }
}
