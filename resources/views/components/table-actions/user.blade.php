@props([
    'width' => '16',
    'height' => '16',
])

@if ($row->id != Auth::user()->id)
    <div class="flex justify-end gap-1">
        <x-tooltip text="Edit" dir="top">
            <a x-ref="content" href="{{ route('app.users.edit', ['user' => $row->uid]) }}" wire:navigate.hover>
                <x-icon-button>
                    <svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>
                </x-icon-button>
            </a>
        </x-tooltip>

        @if ($row->status == App\Enums\UserStatus::INACTIVE->value)
            <x-tooltip text="Activate" dir="top">
                <x-icon-button type="button" x-ref="content" x-on:click="$dispatch('open-modal', 'activate-user-{{ $row->id }}')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check"><path d="M20 6 9 17l-5-5"/></svg>
                </x-icon-button>
            </x-tooltip>
        @else
            <x-tooltip text="Deactivate" dir="top">
                <x-icon-button type="button" x-ref="content" x-on:click="$dispatch('open-modal', 'deactivate-user-{{ $row->id }}')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ban"><circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/></svg>
                </x-icon-button>
            </x-tooltip>
        @endif

        <x-tooltip text="View" dir="top">
            <a x-ref="content" href="{{ route('app.users.show', ['user' => $row->uid]) }}" wire:navigate.hover>
                <x-icon-button>
                    <svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                </x-icon-button>
            </a>
        </x-tooltip>

        {{-- Deactivate User Modal --}}
        <x-modal.full name="deactivate-user-{{ $row->id }}" maxWidth="sm">
            <form wire:submit='deactivateUser({{ $row->id }})'>
                <section class="p-5 space-y-5" x-on:user-status-changed.window="show = false">
                    <hgroup>
                        <h2 class="text-lg font-semibold text-red-500">Deactivate User</h2>
                        <p class="text-xs">This user is about to lose access to the system</p>
                    </hgroup>
        
                    <x-form.input-group>
                        <x-form.input-label for="password-deactivate-{{ $row->id }}">Enter your password</x-form.input-label>
                        <x-form.input-text wire:model="password" type="password" label="Password" id="password-deactivate-{{ $row->id }}" />
                        <x-form.input-error field="password" />
                    </x-form.input-group>

                    <x-loading wire:loading wire:target='deactivateUser'>Deactivating user, please wait</x-loading>
                    
                    <div class="flex justify-end gap-1">
                        <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                        <x-danger-button>Deactivate</x-danger-button>
                    </div>
                </section>
            </form>
        </x-modal.full> 

        <x-modal.full name="activate-user-{{ $row->id }}" maxWidth="sm">
            <form wire:submit='activateUser({{ $row->id }})'>
                <section class="p-5 space-y-5" x-on:user-status-changed.window="show = false">
                    <hgroup>
                        <h2 class="text-lg font-semibold">Activate User</h2>
                        <p class="text-xs">This user is about to gain access to your system.</p>
                    </hgroup>
        
                    <x-form.input-group>
                        <x-form.input-label for="password-activate-{{ $row->id }}">Enter your password</x-form.input-label>
                        <x-form.input-text wire:model="password" type="password" label="Password" id="password-activate-{{ $row->id }}" />
                        <x-form.input-error field="password" />
                    </x-form.input-group>

                    <x-loading wire:loading wire:target='activateUser'>Activating user, please wait</x-loading>
                    
                    <div class="flex justify-end gap-1">
                        <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                        <x-primary-button>Activate</x-primary-button>
                    </div>
                </section>
            </form>
        </x-modal.full> 
    </div>
@else
    <div class="flex justify-end gap-1">
        <x-tooltip text="Edit" dir="top">
            <x-icon-button type="button" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>
            </x-icon-button>
        </x-tooltip>

        <x-tooltip text="Deactivate" dir="top">
            <x-icon-button type="button" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ban"><circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/></svg>
            </x-icon-button>
        </x-tooltip>

        <x-tooltip text="View" dir="top">
            <x-icon-button type="button" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
            </x-icon-button>
        </x-tooltip>
    </div>
@endif

