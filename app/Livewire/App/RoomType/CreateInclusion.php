<?php

namespace App\Livewire\App\RoomType;

use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateInclusion extends Component
{
    use DispatchesToast;
    
    #[Validate] public $room_type;
    #[Validate] public $name;

    public function rules() {
        return [
            'room_type' => 'required',
            'name' => 'required|max:50',
        ];
    }

    public function store() {
        $this->validate();
        
        $inclusion = $this->room_type->inclusions()->create([
            'name' => $this->name,
        ]);

        if ($inclusion) {
            $this->toast('Success!', description: 'Inclusion created!');
            $this->dispatch('inclusion-created');
            $this->dispatch('pg:eventRefresh-RoomTypeInclusionsTable');
            $this->reset('name');
        }
    }

    public function render()
    {
        return <<<'HTML'
        <form class="p-5 space-y-5" wire:submit='store' x-on:inclusion-created.window="show = false">
            <hgroup>
                <h2 class='text-lg font-semibold'>Add Inclusion</h2>
                <p class='text-xs'>Describe your new inclusion for this room type</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for='name'>Inclusion name</x-form.input-label>
                <x-form.input-text wire:model.live="name" id="name" name="name" label="Short description: 1 bed, etc..." />
                <x-form.input-error field="name" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='store'>Adding inclusion, please wait</x-loading>

            <div class="flex justify-end gap-1">
                <x-secondary-button type='button' x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button>Save</x-primary-button>
            </div>
        </form>
        HTML;
    }
}
