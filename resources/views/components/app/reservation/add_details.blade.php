@props([
    'services' => [],
])

<section class="p-5 space-y-5 bg-white border rounded-lg">
    <hgroup>
        <h3 class="font-semibold">Additional Services</h3>
        <p class="max-w-sm text-xs">Select a service the guest wants to avail then click the <strong class="text-blue-500">Save Button</strong> to save your changes.</p>
    </hgroup>   

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @forelse ($services as $service)
            @php
                $checked = false;

                if ($selected_services->contains('id', $service->id)) {
                    $checked = true;
                }
            @endphp
            
            <div wire:key="{{ $service->id }}">
                <x-form.checkbox-toggle :checked="$checked" id="service-{{ $service->id }}" name="service" wire:click="toggleService({{ $service->id }})" x-on:reset-reservation.window="$el.checked = false">
                    <div class="px-3 py-2 select-none">
                        <div class="w-full font-semibold capitalize text-md">
                            {{ $service->name }}
                        </div>

                        <div class="w-full text-xs">
                            Standard Fee: &#8369;{{ $service->price }}
                        </div>
                    </div>
                </x-form.checkbox-toggle>
            </div>
        @empty
            <div class="py-5 text-sm font-semibold text-center border rounded-lg sm:col-span-2 lg:col-span-4 text-zinc-800/50">No services yet...</div>
        @endforelse
    </div>
</section>

<section class="p-5 space-y-5 bg-white border rounded-lg">
    <div class="flex items-start justify-between">
        <hgroup>
            <h3 class="font-semibold">Cars</h3>
            <p class="max-w-sm text-xs">Click the <strong class="text-blue-500">Add Car</strong> button on the right to enter a new vehicle for the guest.</p>
        </hgroup>

        <button type="button" class="text-xs font-semibold text-blue-500" x-on:click="$dispatch('open-modal', 'add-car-modal')">Add Car</button>
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        @forelse ($cars as $car)
            <div wire:key='{{ $car['plate_number'] }}'>
                <div class="flex items-center justify-between w-full px-3 py-2 border rounded-md select-none border-slate-200">
                    <div>
                        <p class="font-semibold">{{ $car['plate_number'] }}</p>
                        <p class="text-xs">Make: {{ $car['make'] }}</p>
                        <p class="text-xs">Color &amp; Model: {{ $car['color'] . ' ' . $car['model'] }}</p>
                    </div>

                    <x-tooltip text="Remove Car" dir="top">
                        <button x-ref="content" type="button" class="p-3 group" x-on:click="$dispatch('open-modal', 'remove-car-modal-{{ $car['plate_number'] }}')">
                            <svg class="transition-all duration-200 ease-in-out opacity-50 group-hover:opacity-100" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                        </button>
                    </x-tooltip>
                </div>

                <x-modal.full name="remove-car-modal-{{ $car['plate_number'] }}" maxWidth='sm'>
                    <div class="p-5 space-y-5" x-on:car-removed.window="show = false">
                        <div>
                            <h2 class="text-lg font-semibold text-red-500">Remove Car</h2>
                            <p class="text-xs">Are you sure you really want to remove <strong>{{ $car['plate_number'] }}</strong>?</p>
                        </div>
            
                        <div class="flex justify-end gap-1">
                            <x-secondary-button type="button" x-on:click="show = false">No, cancel</x-secondary-button>
                            <x-danger-button type="button" wire:click="removeCar('{{ $car['plate_number'] }}')">Yes, remove</x-danger-button>
                        </div>
                    </div>
                </x-modal.full>
            </div>
        @empty
            <div class="py-10 space-y-3 text-center border rounded-md sm:col-span-2 border-slate-200">
                <svg class="mx-auto text-zinc-200" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-car-front"><path d="m21 8-2 2-1.5-3.7A2 2 0 0 0 15.646 5H8.4a2 2 0 0 0-1.903 1.257L5 10 3 8"/><path d="M7 14h.01"/><path d="M17 14h.01"/><rect width="18" height="8" x="3" y="10" rx="2"/><path d="M5 18v2"/><path d="M19 18v2"/></svg>
            
                <p class="text-sm font-semibold">No car added!</p>
            
                <x-primary-button type='button' x-on:click="$dispatch('open-modal', 'add-car-modal')">
                    Add Car
                </x-primary-button>
            </div>                
        @endforelse
    </div>
</section>

<x-modal.full name='add-car-modal' maxWidth='sm'>
    <div class="p-5 space-y-5" x-data="{ quantity: @entangle('quantity'), max_quantity: @entangle('max_quantity')}" x-on:car-added.window="show = false">
        <hgroup>
            <h2 class="text-lg font-semibold">Add Car</h2>
            <p class="text-xs">Enter the vehicle details below.</p>
        </hgroup>

        <x-form.input-group>
            <x-form.input-text id="plate_number" class="uppercase" name="plate_number" label="Plate Number" wire:model="plate_number" />
            <x-form.input-error field="plate_number" />
        </x-form.input-group>

        <x-form.input-group>
            <x-form.input-text id="make" name="make" class="capitalize" label="Brand / Make" wire:model="make" />
            <x-form.input-error field="make" />
        </x-form.input-group>

        <x-form.input-group>
            <x-form.input-text id="model" name="model" class="capitalize" label="Model" wire:model="model" />
            <x-form.input-error field="model" />
        </x-form.input-group>

        <x-form.input-group>
            <x-form.input-text id="color" name="color" class="capitalize" label="Color" wire:model="color" />
            <x-form.input-error field="color" />
        </x-form.input-group>

        <div class="flex justify-end gap-1">
            <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
            <x-primary-button type="button" wire:click='addCar'>Add Car</x-primary-button>
        </div>
    </div>
</x-modal.full>