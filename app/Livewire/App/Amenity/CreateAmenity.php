<?php

namespace App\Livewire\App\Amenity;

use App\Models\Amenity;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateAmenity extends Component
{
    use DispatchesToast;

    #[Validate] public $name;
    #[Validate] public $quantity = 1;
    #[Validate] public $price = 1;
    #[Validate] public $type = 'is_reservable';

    public function rules() {
        return [
            'name' => 'required',
            'quantity' => 'integer|required|min:1',
            'price' => 'integer|required|min:1',
            'type' => 'required',
        ];
    }

    public function submit() {
        $this->validate();
        
        $is_reservable = 0;
        $is_addons = 0;

        if ($this->type == 'is_reservable') {
            $is_reservable = 1;
            $this->quantity = 1;
        } else {
            $is_addons = 1;
        }

        Amenity::create([
            'name' => $this->name,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'is_reservable' => $is_reservable,
            'is_addons' => $is_addons,
        ]);

        $this->reset();
        $this->toast('Success!', 'success', 'Amenity added successfully!');
        $this->dispatch('amenity-created');
        $this->dispatch('pg:eventRefresh-AmenityTable');
    }

    public function render()
    {
        return <<<'HTML'
            <form x-data="{
                    type: @entangle('type'),
                    quantity: @entangle('quantity'),
                    price: @entangle('price')
                }" wire:submit="submit" class="p-5 space-y-5" x-on:amenity-created.window="show = false">
                <hgroup>
                    <h2 class="font-semibold">Add Service or Amenity</h2>
                    <p class="text-xs">Fill up the form below to add an amenity</p>
                </hgroup>
                
                <div class="grid gap-2 p-3 border border-gray-300 rounded-md">
                    <x-form.input-label for="is_reservable">Choose an item you want to add</x-form.input-label>
                    <x-form.input-radio id="is_reservable" value="is_reservable" name="type" label="Amenity" wire:model.live="type" />
                    <x-form.input-radio id="is_addons" value="is_addons" name="type" label="Service" wire:model.live="type" />
                    <x-form.input-error field="type" />
                </div>

                <div class="p-3 space-y-3 border border-gray-300 rounded-md">
                    <div class="space-y-3">
                        <div>
                            <x-form.input-label for="name">Name</x-form.input-label>
                            <p class="text-xs">Give your new service or amenity a name</p>
                        </div>
                        <x-form.input-text id="name" name="name" label="Amenity Name" wire:model.live="name" />
                        <x-form.input-error field="name" />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div x-show="type == 'is_reservable'" class="space-y-3">
                            <x-form.input-label for="quantity">Quantity</x-form.input-label>
                            <x-form.input-number id="quantity" name="quantity" label="Quantity" x-model="quantity" wire:model.live="quantity" />
                            <x-form.input-error field="quantity" />
                        </div>
                        
                        <div class="space-y-3">
                            <x-form.input-label for="price">Price</x-form.input-label>
                            <x-form.input-number id="price" name="price" label="price" x-model="price" wire:model.live="price" />
                            <x-form.input-error field="price" />
                        </div>
                    </div>
                </div>

                <x-primary-button>
                    Add Item
                </x-primary-button>
            </form>
        HTML;
    }
}
