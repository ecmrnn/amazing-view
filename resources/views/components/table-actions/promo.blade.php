@props([
    'width' => '16',
    'height' => '16',
])

<div wire:key="{{ $row->id }}" class="flex justify-end gap-1">
    <x-tooltip text="Edit" dir="top">
        <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'edit-promo-{{ $row->id }}')">
            <svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"> <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" /> <path d="m15 5 4 4" /></svg>
        </x-icon-button>
    </x-tooltip>

    @if ($row->status == App\Enums\PromoStatus::ACTIVE->value)
        <x-tooltip text="Disable" dir="top">
            <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'disable-promo-{{ $row->id }}')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ban-icon lucide-ban"><circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/></svg>
            </x-icon-button>
        </x-tooltip>
    @else
        <x-tooltip text="Enable" dir="top">
            <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'enable-promo-{{ $row->id }}')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check"><path d="M20 6 9 17l-5-5"/></svg>
            </x-icon-button>
        </x-tooltip>
    @endif
    
    @if ($row->reservations->count() > 0)
        <x-icon-button x-ref="content" disabled>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
        </x-icon-button>
    @else
        <x-tooltip text="Delete" dir="top">
            <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'delete-promo-{{ $row->id }}-modal')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
            </x-icon-button>
        </x-tooltip>
    @endif

    {{-- Modals --}}
    <x-modal.full name='edit-promo-{{ $row->id }}' maxWidth='sm'>
        <div x-data="{
                id: @js($row->id),
                name: @js($row->name),
                code: @js($row->code),
                amount: @js((int) $row->amount),
                start_date: @js($row->start_date),
                end_date: @js($row->end_date),
            }" x-on:promo-updated.window="show = false" class="p-5 space-y-5">
            <hgroup>
                <h2 class="text-lg font-semibold">Edit Promo</h2>
                <p class="text-xs">Fill up the form below to edit this promo</p>
            </hgroup>
            
            <x-form.input-group>
                <x-form.input-label for='name-{{ $row->id }}'>Promo Name</x-form.input-label>
                <x-form.input-text id="name-{{ $row->id }}" name="name" label="Promo name" x-model="name" />
                <x-form.input-error field="name" />
            </x-form.input-group>

            <x-form.input-group>
                <x-form.input-label for='code-{{ $row->id }}'>Promo Code</x-form.input-label>
                <x-form.input-text id="code-{{ $row->id }}" name="code" label="AMAZING!" disabled x-model="code" />
                <x-form.input-error field="code" />
            </x-form.input-group>

            <x-form.input-group>
                <x-form.input-label for='amount-{{ $row->id }}'>Discount Amount (Fixed)</x-form.input-label>
                <x-form.input-currency id="amount-{{ $row->id }}" name="amount" x-model="amount" />
                <x-form.input-error field="amount" />
            </x-form.input-group>

            <div class="grid grid-cols-2 gap-5">
                <x-form.input-group>
                    <x-form.input-label for='start_date-{{ $row->id }}'>Promo Starts</x-form.input-label>
                    <x-form.input-date id="start_date-{{ $row->id }}" name="start_date" class="w-full" x-model="start_date" min="{{ $min_date }}" />
                    <x-form.input-error field="start_date" />
                </x-form.input-group>

                <x-form.input-group>
                    <x-form.input-label for='end_date-{{ $row->id }}'>Promo Ends</x-form.input-label>
                    <x-form.input-date id="end_date-{{ $row->id }}" name="end_date" class="w-full" x-model="end_date" x-bind:min="start_date" />
                    <x-form.input-error field="end_date" />
                </x-form.input-group>
            </div>

            <x-loading wire:loading wire:target='updatePromo'>Updating promo, please wait</x-loading>

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button type="button" wire:loading.attr='disabled'
                    x-on:click="$wire.updatePromo({
                        'id': id,
                        'name': name,
                        'amount': amount,
                        
                        'start_date': start_date,
                        'end_date': end_date
                    })">
                    Edit
                </x-primary-button>
            </div>
        </div>
    </x-modal.full>

    <x-modal.full name='delete-promo-{{ $row->id }}-modal' maxWidth='sm'>
        <form class="p-5 space-y-5" x-on:promo-deleted.window="show = false" wire:submit='deletePromo({{ $row->id }})'>
            <hgroup>
                <h2 class="text-lg font-semibold text-red-500">Delete Promo</h2>
                <p class="text-xs">Are you sure you really want to delete this promo?</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for="delete-{{ $row->id }}-password">Enter your password</x-form.input-label>
                <x-form.input-text wire:model.live="password" type="password" label="Password" id="delete-{{ $row->id }}-password" />
                <x-form.input-error field="password" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='deletePromo'>Deleting amenity, please wait</x-loading>
            
            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-danger-button>Delete</x-danger-button>
            </div>
        </form>
    </x-modal.full>

    <x-modal.full name='disable-promo-{{ $row->id }}' maxWidth='sm'>
        <form class="p-5 space-y-5" wire:submit='toggleStatus({{ $row->id }})' x-on:promo-status-changed.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold text-red-500">Disable Promo</h2>
                <p class="text-xs">Are you sure you really want to disable this promo?</p>
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

    <x-modal.full name='enable-promo-{{ $row->id }}' maxWidth='sm'>
        <form class="p-5 space-y-5" wire:submit='toggleStatus({{ $row->id }})' x-on:promo-status-changed.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold">Enable Promo</h2>
                <p class="text-xs">Are you sure you really want to enable this promo?</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for="enable-{{ $row->id }}-password">Enter your password</x-form.input-label>
                <x-form.input-text wire:model.live="password" type="password" label="Password" id="enable-{{ $row->id }}-password" />
                <x-form.input-error field="password" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='toggleStatus'>Enabling promo, please wait</x-loading>
            
            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button>Enable</x-primary-button>
            </div>
        </form>
    </x-modal.full>
</div>
