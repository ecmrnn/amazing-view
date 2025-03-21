<?php

namespace App\Livewire\App\RoomType;

use App\Models\RoomType;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;

class DeleteRoomType extends Component
{
    use DispatchesToast;
    
    #[Validate] public $password;
    public $room_type;

    public function rules() {
        return [
            'password' => 'required'
        ];
    }

    public function deleteRoomType() {
        $this->validate([
            'password' => $this->rules()['password']
        ]);

        $admin = Auth::user();

        if (Hash::check($this->password, $admin->password)) {
            // delete image
            Storage::disk('public')->delete($this->room_type->image_1_path);
            Storage::disk('public')->delete($this->room_type->image_2_path);
            Storage::disk('public')->delete($this->room_type->image_3_path);
            Storage::disk('public')->delete($this->room_type->image_4_path);
            
            // delete room_type
            $this->room_type->delete();
            
            $this->toast('Room Type Deleted', 'success', 'Room type deleted successfully!');
            $this->dispatch('room-type-deleted');

            // reset
            $this->reset('password');
        } else {
            $this->toast('Deletion Failed', 'info', 'Incorrect password entered');
        }
    }

    public function render()
    {
        return <<<'HTML'
        <form wire:submit="deleteRoomType" class="p-5 space-y-5" x-on:room-type-deleted.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold text-red-500">Delete Room Type</h2>
                <p class="text-sm">Are you sure you really want this room type?</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for="delete-{{ $room_type->id }}-password">Enter your password</x-form.input-label>
                <x-form.input-text wire:model.live="password" type="password" label="Password" id="delete-{{ $room_type->id }}-password" name="delete-{{ $room_type->id }}-password" />
                <x-form.input-error field="password" />
            </x-form.input-group>
            
            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-danger-button>Delete</x-danger-button>
            </div>
        </form>
        HTML;
    }
}
