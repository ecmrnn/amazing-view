@props([
    'width' => '16',
    'height' => '16',
    'edit_link' => '',
    'view_link' => '',
])

@if ($row->id != Auth::user()->id)
    <div class="flex justify-end gap-1">
        <x-tooltip text="Edit" dir="top">
            <a x-ref="content" href="{{ route($edit_link, ['user' => $row->uid]) }}" wire:navigate.hover>
                <x-icon-button>
                    <svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>
                </x-icon-button>
            </a>
        </x-tooltip>

        <x-tooltip text="Deactivate" dir="top">
            <a x-ref="content" x-on:click="$dispatch('open-modal', 'deactivate-user-{{ $row->id }}')">
                <x-icon-button>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ban"><circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/></svg>
                </x-icon-button>
            </a>
        </x-tooltip>

        <x-tooltip text="View" dir="top">
            <a x-ref="content" href="{{ route($view_link, ['user' => $row->uid]) }}" wire:navigate.hover>
                <x-icon-button>
                    <svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                </x-icon-button>
            </a>
        </x-tooltip>

        {{-- Deactivate User Modal --}}
        <x-modal.full name="deactivate-user-{{ $row->id }}" maxWidth="sm">
            <form wire:submit='deactivateUser({{ $row->id }})' x-data="{ checked: false }">
                <section class="p-5 space-y-5" x-on:user-deactivated.window="show = false">
                    <hgroup>
                        <h2 class="text-lg font-semibold text-red-500">Deactivate User</h2>
                        <p class="text-sm">Are you sure you really want to deactivate <strong class="text-blue-500">{{ ucwords($row->first_name) }}</strong>?</p>
                    </hgroup>
        
                    <x-form.input-group>
                        <x-form.input-label for="password-deactivate-{{ $row->id }}">Enter your password</x-form.input-label>
                        <x-form.input-text wire:model="password" type="password" label="Password" id="password-deactivate-{{ $row->id }}" />
                        <x-form.input-error field="password" />
                    </x-form.input-group>
                    
                    <div class="flex justify-end gap-1">
                        <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                        <x-danger-button>Deactivate</x-danger-button>
                    </div>
                </section>
            </form>
        </x-modal.full> 
    </div>
@else
    <div class="flex justify-end gap-1">
        <x-icon-button disabled="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>
        </x-icon-button>

        <x-icon-button disabled="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ban"><circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/></svg>
        </x-icon-button>

        <x-icon-button disabled="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
        </x-icon-button>
    </div>
@endif
