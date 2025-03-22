<?php

namespace App\Livewire\App\RoomType;

use App\Models\RoomType;
use App\Services\AuthService;
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

        $auth = new AuthService;
        
        if ($auth->validatePassword($this->password)) {
            if ($this->room_type->rooms->count() > 0) {
                $this->toast('Deletion Failed', 'warning', 'Room type has rooms, cannot delete');
                $this->dispatch('room-type-deleted');
                $this->reset('password');
                return;
            }

            // Delete images
            if ($this->room_type->image_1_path) {
                Storage::disk('public')->delete($this->room_type->image_1_path);
            }
            
            if ($this->room_type->image_2_path) {
                Storage::disk('public')->delete($this->room_type->image_2_path);
            }

            if ($this->room_type->image_3_path) {
                Storage::disk('public')->delete($this->room_type->image_3_path);
            }

            if ($this->room_type->image_4_path) {
                Storage::disk('public')->delete($this->room_type->image_4_path);
            }
            
            $this->toast('Success', description: 'Room type deleted successfully!');
            $this->dispatch('room-type-deleted');
            $this->reset('password');
            return $this->room_type->delete();
        }

        $this->addError('password', 'Password mismatched, try again!');
        $this->reset('password');
    }

    public function render()
    {
        return <<<'HTML'
        <form wire:submit="deleteRoomType" class="p-5 space-y-5" x-on:room-type-deleted.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold text-red-500">Delete Room Type</h2>
                <p class="text-xs">Are you sure you really want to delete this room type?</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for="delete-{{ $room_type->id }}-password">Enter your password</x-form.input-label>
                <x-form.input-text wire:model.live="password" type="password" label="Password" id="delete-{{ $room_type->id }}-password" name="delete-{{ $room_type->id }}-password" />
                <x-form.input-error field="password" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='deleteRoomType'>Deleting room type</x-loading>
            
            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-danger-button>Delete</x-danger-button>
            </div>
        </form>
        HTML;
    }
}
