<?php

namespace App\Livewire\App\Services;

use App\Services\AdditionalServiceHandler;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateService extends Component
{
    use DispatchesToast;

    #[Validate] public $name;
    #[Validate] public $description = 'Amazing service, coming right up!';
    #[Validate] public $price = 500;

    public function rules() {
        return [
            'name' => 'required|min:5|unique:additional_services,name',
            'description' => 'required|max:200',
            'price' => 'required|integer|min:1',
        ];
    }

    public function messages() {
        return [
            'name.required' => 'Enter the name of your service',
            'name.unique' => 'Service exists already',
            'name.min' => 'Name must be atleast 5 characters',
        ];
    }

    public function submit() {
        $validated = $this->validate();
        
        $handler = new AdditionalServiceHandler;
        $handler->create($validated);

        $this->reset();
        $this->toast('Success!', description: 'Service created successfully!');
        $this->dispatch('service-created');
        $this->dispatch('pg:eventRefresh-ServicesTable');
    }

    public function render()
    {
        return <<<'HTML'
        <form x-data="{
                price: @entangle('price')
            }" class="p-5 space-y-5" wire:submit="submit" x-on:service-created.window="show = false">
            <hgroup>
                <h2 class="font-semibold">Create Service</h2>
                <p class="text-xs">Fill up this form to create a new service</p>
            </hgroup>

            <x-form.input-group>
                <div>
                    <x-form.input-label for='name'>Name</x-form.input-label>
                    <p class="text-xs">Give your service a name</p>
                </div>
                <x-form.input-text id="name" name="name" label="Name" wire:model.live="name" />
                <x-form.input-error field="name" />
            </x-form.input-group>

            <x-form.input-group>
                <div>
                    <x-form.input-label for='description'>Description</x-form.input-label>
                    <p class="text-xs">Give your service a helpful description</p>
                </div>
                <x-form.textarea id="description" name="description" label="description" wire:model.live="description" class="w-full" />
                <x-form.input-error field="description" />
            </x-form.input-group>

            <x-form.input-group>
                <x-form.input-label for='price'>Price</x-form.input-label>
                <x-form.input-number id="price" name="price" label="price" x-model="price" />
                <x-form.input-error field="price" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='submit'>Creating new service, please wait</x-loading>

            <div class="flex justify-end gap-1">
                <x-secondary-button x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button>Add</x-primary-button>
            </div>
        </form>
        HTML;
    }
}
