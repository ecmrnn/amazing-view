<div wire:key="{{ $row->id }}" class="flex justify-end gap-1">
    <a href="{{ route('app.buildings.edit', ['building' => $row->id]) }}" wire:navigate.hover>
        <x-tooltip text="Edit" dir="top">
            <x-icon-button x-ref="content">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"> <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" /> <path d="m15 5 4 4" /></svg>
            </x-icon-button>
        </x-tooltip>
    </a>
    
    <x-tooltip text="Edit" dir="top">
        <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'delete-room-{{ $row->id }}-modal')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
        </x-icon-button>
    </x-tooltip>

    <a href="{{ route('app.room.index', ['type' => $row->id]) }}" wire:navigate.hover>
        <x-tooltip text="Rooms" dir="top">
            <x-icon-button x-ref="content">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
            </x-icon-button>
        </x-tooltip>
    </a>

    {{-- Modals --}}
    <x-modal.full name='delete-room-{{ $row->id }}-modal' maxWidth='sm'>
        <form class="p-5 space-y-5" x-on:room-type-deleted.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold text-red-500">Delete Room Type</h2>
                <p class="text-sm">Are you sure you really want to delete this room?</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for="delete-table-{{ $row->id }}-password">Enter your password</x-form.input-label>
                <x-form.input-text wire:model.live="password" type="password" label="Password" id="delete-table-{{ $row->id }}-password" />
                <x-form.input-error field="password" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='deleteRoomType'>Processing your request, please wait</x-loading>
            
            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-danger-button type="button" wire:loading.attr='disabled' wire:click='deleteRoomType({{ $row->id }})'>Delete</x-danger-button>
            </div>
        </form>
    </x-modal.full>
</div>
