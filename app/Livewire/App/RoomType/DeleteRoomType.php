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

    public function mount(RoomType $room_type) {
        $this->room_type = $room_type;
    }

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
        <div>
            <section class="p-5 space-y-5 bg-white" x-on:room-type-deleted.window="show = false">
                <hgroup>
                    <h2 class="font-semibold text-center text-red-500 capitalize">Delete Room Type</h2>
                    <p class="max-w-sm text-sm text-center">Are you sure you really want this room type?</p>
                </hgroup>

                <div class="space-y-2">
                    <p class="text-xs">Enter your password to delete this room type.</p>
                    <x-form.input-text wire:model.live="password" type="password" label="Password" id="delete-{{ $room_type->id }}-password" />
                    <x-form.input-error field="password" />
                </div>
                
                <div class="flex items-center justify-center gap-1">
                    <x-secondary-button type="button" x-on:click="show = false">No, Cancel</x-secondary-button>
                    <x-danger-button type="button" wire:click='deleteRoomType()'>Yes, Delete</x-danger-button>
                </div>
            </section>
        </div>
        HTML;
    }
}
