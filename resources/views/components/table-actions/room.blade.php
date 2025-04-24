@props([
    'width' => '16',
    'height' => '16',
    'edit_link' => '',
])

<div wire:key="{{ $row->id }}" class="flex justify-end gap-1">
    @can('update room')
        <a href="{{ route('app.room.edit', ['type' => $row->roomType->id, 'room' => $row->id]) }}" wire:navigate.hover>
            <x-tooltip text="Edit" dir="top">
                <x-icon-button x-ref="content">
                    <svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"> <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" /> <path d="m15 5 4 4" /></svg>
                </x-icon-button>
            </x-tooltip>
        </a>
    @endcan

    {{-- Modals --}}
    {{-- <x-modal.full name='delete-room-{{ $row->id }}-modal' maxWidth='sm'>
        <section class="p-5 space-y-5 bg-white" x-on:room-deleted.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold text-red-500 capitalize">Delete room</h2>
                <p class="text-xs">Are you sure you really want to delete this room?</p>
            </hgroup>

            <div class="space-y-2">
                <p class="text-xs">Enter your password to delete this room.</p>
                <x-form.input-text wire:model.live="password" type="password" label="Password" id="delete-{{ $row->id }}-password" />
                <x-form.input-error field="password" />
            </div>
            
            <div class="flex items-center justify-center gap-1">
                <x-secondary-button type="button" x-on:click="show = false">No, Cancel</x-secondary-button>
                <x-danger-button type="button" wire:click='deleteRoom({{ $row->id }})'>Yes, Delete</x-danger-button>
            </div>
        </section>
    </x-modal.full> --}}
</div>
