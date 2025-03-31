@props([
    'width' => '16',
    'height' => '16',
    'edit_link' => '',
])

<div class="flex justify-end gap-1">
    <x-tooltip text="Download" dir="top">
        <a x-ref="content" href="{{ asset('storage/' . $row->path) }}" download="{{ $row->name . ' - ' . $row->rid . '.' . $row->format }}" class="block">
            <x-icon-button>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-down"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M12 18v-6"/><path d="m9 15 3 3 3-3"/></svg>
            </x-icon-button>
        </a>
    </x-tooltip>

    <x-tooltip text="Delete" dir="top">
        <x-icon-button x-on:click="$dispatch('open-modal', 'delete-report-{{ $row->id }}')" x-ref="content">
            <svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
        </x-icon-button>
    </x-tooltip>

    <x-modal.full name='delete-report-{{ $row->id }}' maxWidth='sm'>
        <form class="p-5 space-y-5" x-on:submit.prevent="$dispatch('delete-report', { id: {{ $row->id  }}})">
            <hgroup>
                <h2 class="text-base font-semibold text-red-500 capitalize">Delete Report</h2>
                <p class="max-w-sm text-sm">You are about to delete this report, this action cannot be undone</p>
            </hgroup>

            <div class="space-y-2">
                <x-form.input-label for="password-{{ $row->id }}">Enter your password to delete this report</x-form.input-label>
                <x-form.input-text wire:model="password" type="password" label="Password" id="password-{{ $row->id }}" />
                <x-form.input-error field="password" />
            </div>
            
            <div class="flex items-center justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">No, Cancel</x-secondary-button>
                <x-danger-button type="submit">Yes, Delete</x-danger-button>
            </div>
        </form>
    </x-modal.full>
</div>
