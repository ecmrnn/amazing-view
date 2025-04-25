@props([
    'width' => '16',
    'height' => '16',
    'edit_link' => '',
])

<div wire:key="{{ $row->id }}" class="flex justify-end gap-1">
    @can('update room')
        <x-tooltip text="Edit" dir="top">
            <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'update-inclusion-{{ $row->id }}-modal')">
                <svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"> <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" /> <path d="m15 5 4 4" /></svg>
            </x-icon-button>
        </x-tooltip>

        <x-tooltip text="Delete" dir="top">
            <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'delete-inclusion-{{ $row->id }}-modal')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
            </x-icon-button>
        </x-tooltip>
    @endcan

    {{-- Modals --}}
    <x-modal.full name='update-inclusion-{{ $row->id }}-modal' maxWidth='sm'>
        <div x-data="{ name: @js($row->name) }" class="p-5 space-y-5" x-on:inclusion-updated.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold capitalize">Update inclusion</h2>
                <p class="text-xs">Update the name of your inclusion</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for='room-inclusion-{{ $row->id }}'>Enter a new name</x-form.input-label>
                <x-form.input-text x-model="name" id="room-inclusion-{{ $row->id }}" name="name" label="{{ $row->name }}" />
                <x-form.input-error field="name" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='updateInclusion'>Updating inclusion, please wait</x-loading>
            
            <div class="flex items-center justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button type="button" wire:loading.attr='disabled'
                    x-on:click="$wire.updateInclusion({{ $row->id }}, name)">
                    Save
                </x-primary-button>
            </div>
        </div>
    </x-modal.full>

    <x-modal.full name='delete-inclusion-{{ $row->id }}-modal' maxWidth='sm'>
        <div class="p-5 space-y-5" x-on:inclusion-deleted.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold text-red-500 capitalize">Delete inclusion</h2>
                <p class="text-xs">Are you sure you really want to delete this inclusion?</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for="delete-room-inclusion-{{ $row->id }}-password">Enter your password to delete this inclusion.</x-form.input-label>
                <x-form.input-text wire:model.live="password" type="password" label="Password" id="delete-room-inclusion-{{ $row->id }}-password" />
                <x-form.input-error field="password" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='deleteInclusion'>Deleting inclusion, please wait</x-loading>
            
            <div class="flex items-center justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-danger-button type="button" wire:loading.attr='disabled' wire:click='deleteInclusion({{ $row->id }})'>Delete</x-danger-button>
            </div>
        </div>
    </x-modal.full>
</div>
