<?php

namespace App\Livewire\App\Room;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class DeleteRoomImage extends Component
{
    public $image;

    public function submit() {
        if ($this->image) {
            if (Storage::exists('public/' . $this->image->path)) {
                Storage::disk('public')->delete($this->image->path);    
            }
            
            $this->image->delete();

            $this->dispatch('image-deleted');
        }
    }
    
    public function render()
    {
        return <<<'HTML'
        <div class="p-5 space-y-5" x-on:image-deleted.window="show = false">
            <hgroup>
                <h2 class='font-semibold'>Delete Image</h2>
                <p class='text-xs'>Are you sure you really want to delete this image? This action cannot be undone.</p>
            </hgroup>

            <x-img src="{{ $image->path }}" />

            <x-loading wire:loading wire:target='submit'>Deleting image, please wait</x-loading>

            <div class="flex justify-end gap-1">
                <x-secondary-button type='button' x-on:click="show = false">Cancel</x-secondary-button>
                <x-danger-button type="button" wire:click="submit">Delete</x-danger-button>
            </div>
        </div>    
        HTML;
    }
}
