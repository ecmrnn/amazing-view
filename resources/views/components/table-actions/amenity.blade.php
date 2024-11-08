@props([
    'width' => '16',
    'height' => '16',
    'edit_link' => '',
])

<div wire:key="{{ $row->id }}" class="flex justify-end gap-1">
    <x-tooltip text="Edit" dir="top">
        <x-icon-button x-ref="content" wire:click='editAmenity({{ $row->id }})'>
            <svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"> <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" /> <path d="m15 5 4 4" /></svg>
        </x-icon-button>
    </x-tooltip>
    {{-- <a href="{{ route('app.amenity.edit', ['amenity' => $row->id]) }}" wire:navigate.hover>
    </a> --}}
    
    <x-tooltip text="Edit" dir="top">
        <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'delete-amenity-{{ $row->id }}-modal')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
        </x-icon-button>
    </x-tooltip>

    {{-- Modals --}}
    <x-modal.full name='edit-amenity-{{ $row->id }}' maxWidth='sm'>
        <form x-on:amenity-updated.window="show = false" wire:submit='updateAmenity({{ $row->id }})' class="p-5 space-y-5">
            <hgroup>
                <h2 class="font-semibold">Edit Service or Amenity</span></h2>
                <p class="text-xs">Fill up the form below to edit this amenity</p>
            </hgroup>
            
            <div class="p-3 space-y-3 border border-gray-300 rounded-md">
                <div class="space-y-3">
                    <div>
                        <x-form.input-label for="name-{{ $row->id }}">Name</x-form.input-label>
                        <p class="text-xs">Give your new service or amenity a name</p>
                    </div>
                    <x-form.input-text id="name-{{ $row->id }}" name="name-{{ $row->id }}" label="Amenity Name" wire:model.live="name" />
                    <x-form.input-error field="name" />
                </div>

                <div class="grid grid-cols-2 gap-3">
                    @if ($row->is_addons == 0)
                        <div class="space-y-3">
                            <x-form.input-label for="quantity">Quantity</x-form.input-label>
                            <x-form.input-number id="quantity" name="quantity" label="Quantity" x-model="quantity" wire:model.live="quantity" />
                            <x-form.input-error field="quantity" />
                        </div>
                    @endif

                    <div class="space-y-3">
                        <x-form.input-label for="price-{{ $row->id }}">Price</x-form.input-label>
                        <x-form.input-number id="price-{{ $row->id }}" name="price-{{ $row->id }}" label="price" x-model="price" wire:model.live="price" />
                        <x-form.input-error field="price" />
                    </div>
                </div>
            </div>

            <x-primary-button>
                Edit Item
            </x-primary-button>
        </form>
    </x-modal.full>

    <x-modal.full name='delete-amenity-{{ $row->id }}-modal' maxWidth='sm'>
        <section class="p-5 space-y-5 bg-white" x-on:amenity-deleted.window="show = false">
            <hgroup>
                <h2 class="font-semibold text-center text-red-500 capitalize">Delete Amenity</h2>
                <p class="max-w-sm text-sm text-center">Are you sure you really want to delete this amenity?</p>
            </hgroup>

            <div class="space-y-2">
                <p class="text-xs">Enter your password to delete this amenity.</p>
                <x-form.input-text wire:model.live="password" type="password" label="Password" id="delete-{{ $row->id }}-password" />
                <x-form.input-error field="password" />
            </div>
            
            <div class="flex items-center justify-center gap-1">
                <x-secondary-button type="button" x-on:click="show = false">No, Cancel</x-secondary-button>
                <x-danger-button type="button" wire:click='deleteAmenity({{ $row->id }})'>Yes, Delete</x-danger-button>
            </div>
        </section>
    </x-modal.full>
</div>
