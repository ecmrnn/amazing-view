@props([
    'width' => '16',
    'height' => '16',
])

<div wire:key="{{ $row->id }}" class="flex justify-end gap-1">
    <x-tooltip text="Edit" dir="top">
        <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'edit-amenity-{{ $row->id }}')">
            <svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"> <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" /> <path d="m15 5 4 4" /></svg>
        </x-icon-button>
    </x-tooltip>

    @if ($row->status == App\Enums\AmenityStatus::ACTIVE->value)
        <x-tooltip text="Disable" dir="top">
            <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'disable-amenity-{{ $row->id }}')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ban-icon lucide-ban"><circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/></svg>
            </x-icon-button>
        </x-tooltip>
    @else
        <x-tooltip text="Enable" dir="top">
            <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'enable-amenity-{{ $row->id }}')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check"><path d="M20 6 9 17l-5-5"/></svg>
            </x-icon-button>
        </x-tooltip>
    @endif
    
    @if ($row->rooms->count() > 0)
        <x-icon-button x-ref="content" disabled>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
        </x-icon-button>
    @else
        <x-tooltip text="Delete" dir="top">
            <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'delete-amenity-{{ $row->id }}-modal')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
            </x-icon-button>
        </x-tooltip>
    @endif

    {{-- Modals --}}
    <x-modal.full name='edit-amenity-{{ $row->id }}' maxWidth='sm'>
        <div x-data="{
                id: @js($row->id),
                name: @js($row->name),
                quantity: @js($row->quantity),
                price: @js((int) $row->price),
            }" x-on:amenity-updated.window="show = false" class="p-5 space-y-5">
            <hgroup>
                <h2 class="font-semibold">Edit Amenity</span></h2>
                <p class="text-xs">Fill up the form below to edit this amenity</p>
            </hgroup>
            
            <div class="p-5 space-y-5 bg-white border rounded-md border-slate-200">
                <x-form.input-group>
                    <div>
                        <x-form.input-label for="name-{{ $row->id }}">Name</x-form.input-label>
                        <p class="text-xs">Give amenity a new name</p>
                    </div>
                    
                    <x-form.input-text id="name-{{ $row->id }}" name="name-{{ $row->id }}" label="Amenity Name" x-model="name" />
                    <x-form.input-error field="name" />
                </x-form.input-group>

                <x-form.input-group>
                    <x-form.input-label for="quantity-{{ $row->id }}">Quantity</x-form.input-label>
                    <x-form.input-number id="quantity-{{ $row->id }}" name="quantity-{{ $row->id }}" label="Quantity" x-model="quantity" />
                    <x-form.input-error field="quantity" />
                </x-form.input-group>
                
                <x-form.input-group>
                    <x-form.input-label for="price-{{ $row->id }}">Price</x-form.input-label>
                    <x-form.input-currency id="price-{{ $row->id }}" name="price-{{ $row->id }}" label="price" x-model="price" />
                    <x-form.input-error field="price" />
                </x-form.input-group>
            </div>

            <x-loading wire:loading wire:target='updateAmenity'>Updating amenity, please wait</x-loading>

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button type="button" wire:loading.attr='disabled' x-on:click="$wire.updateAmenity({ 'id': id, 'name': name, 'quantity': quantity, 'price': price })">Edit</x-primary-button>
            </div>
        </div>
    </x-modal.full>

    <x-modal.full name='delete-amenity-{{ $row->id }}-modal' maxWidth='sm'>
        <form class="p-5 space-y-5" x-on:amenity-deleted.window="show = false" wire:submit='deleteAmenity({{ $row->id }})'>
            <hgroup>
                <h2 class="text-lg font-semibold text-red-500">Delete Amenity</h2>
                <p class="text-xs">Are you sure you really want to delete this amenity?</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for="delete-{{ $row->id }}-password">Enter your password</x-form.input-label>
                <x-form.input-text wire:model.live="password" type="password" label="Password" id="delete-{{ $row->id }}-password" />
                <x-form.input-error field="password" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='deleteAmenity'>Deleting amenity, please wait</x-loading>
            
            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-danger-button>Delete</x-danger-button>
            </div>
        </form>
    </x-modal.full>

    <x-modal.full name='disable-amenity-{{ $row->id }}' maxWidth='sm'>
        <form class="p-5 space-y-5" wire:submit='toggleStatus({{ $row->id }})' x-on:amenity-status-changed.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold text-red-500">Disable Amenity</h2>
                <p class="text-xs">Are you sure you really want to disable this amenity?</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for="disable-{{ $row->id }}-password">Enter your password</x-form.input-label>
                <x-form.input-text wire:model.live="password" type="password" label="Password" id="disable-{{ $row->id }}-password" />
                <x-form.input-error field="password" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='toggleStatus'>Disabling amenity, please wait</x-loading>
            
            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-danger-button>Disable</x-danger-button>
            </div>
        </form>
    </x-modal.full>

    <x-modal.full name='enable-amenity-{{ $row->id }}' maxWidth='sm'>
        <form class="p-5 space-y-5" wire:submit='toggleStatus({{ $row->id }})' x-on:amenity-status-changed.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold">Enable Amenity</h2>
                <p class="text-xs">Are you sure you really want to enable this amenity?</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for="enable-{{ $row->id }}-password">Enter your password</x-form.input-label>
                <x-form.input-text wire:model.live="password" type="password" label="Password" id="enable-{{ $row->id }}-password" />
                <x-form.input-error field="password" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='toggleStatus'>Enabling amenity, please wait</x-loading>
            
            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button>Enable</x-primary-button>
            </div>
        </form>
    </x-modal.full>
</div>
