@props([
    'width' => '16',
    'height' => '16',
    'edit_link' => '',
])

<div class="flex justify-end gap-1">
    <x-tooltip text="Download" dir="top">
        <a x-ref="content" href="{{ asset('storage/' . $row->path) }}" download="{{ $row->name . ' - ' . $row->rid . '.' . $row->format }}" class="p-2 bg-white shadow-md border border-slate-200 text-zinc-500 rounded-md hover:translate-y-[2px] hover:shadow-none  disabled:opacity-25 transition-all ease-in-out duration-200 disabled:translate-y-[2px] disabled:shadow-none block">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                <path
                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                <path d="m15 5 4 4" />
            </svg>
        </a>
    </x-tooltip>

    <x-tooltip text="Delete" dir="top">
        <x-icon-button x-on:click="$dispatch('open-modal', 'delete-report-{{ $row->id }}')" x-ref="content">
            <svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="lucide lucide-trash-2">
                <path d="M3 6h18" />
                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                <line x1="10" x2="10" y1="11" y2="17" />
                <line x1="14" x2="14" y1="11" y2="17" />
            </svg>
        </x-icon-button>
    </x-tooltip>

    <x-modal.full name='delete-report-{{ $row->id }}' maxWidth='sm'>
        <form class="p-5 space-y-5 bg-white" x-on:submit.prevent="$dispatch('delete-report', { id: {{ $row->id  }}})">
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
