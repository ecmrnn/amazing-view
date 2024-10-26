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
                    <svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-pencil">
                        <path
                            d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                        <path d="m15 5 4 4" />
                    </svg>
                </x-icon-button>
            </a>
        </x-tooltip>

        <x-tooltip text="Deactivate" dir="top">
            <a x-ref="content" x-on:click="$dispatch('open-modal', 'deactivate-user-{{ $row->id }}')">
                <x-icon-button>
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
            </a>
        </x-tooltip>

        <x-tooltip text="View" dir="top">
            <a x-ref="content" href="{{ route($view_link, ['user' => $row->uid]) }}" wire:navigate.hover>
                <x-icon-button>
                    <svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-eye">
                        <path
                            d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </x-icon-button>
            </a>
        </x-tooltip>

        {{-- Deactivate User Modal --}}
        <x-modal.full name="deactivate-user-{{ $row->id }}" maxWidth="sm">
            <div x-data="{ checked: false }">
                <section class="p-5 space-y-5 bg-white" x-on:user-deactivated.window="show = false">
                    <hgroup>
                        <h2 class="font-semibold text-center text-red-500 capitalize">Deactivate User</h2>
                        <p class="max-w-sm text-sm text-center">Are you sure you really want to deactivate <strong class="text-blue-500">{{ ucwords($row->first_name) }}</strong>?</p>
                    </hgroup>

                    <div class="space-y-2">
                        <p class="text-xs">Enter your password to deactivate this user.</p>
                        <x-form.input-text wire:model="password" type="password" label="Password" id="password-{{ $row->id }}" />
                        <x-form.input-error field="password" />
                    </div>
                    
                    <div class="flex items-center justify-center gap-1">
                        <x-secondary-button type="button" x-on:click="show = false">No, Cancel</x-secondary-button>
                        <x-danger-button type="button" wire:click='deactivateUser({{ $row->id }})'>Yes, Deactivate</x-danger-button>
                    </div>
                </section>
            </div>
        </x-modal.full> 
    </div>
@endif
