<div>
    <div x-show="!can_select_room" class="p-5 space-y-5 border rounded-md border-slate-200">
        <div x-effect="date_in == '' ? date_out = '' : ''" class="grid items-start gap-5 sm:grid-cols-2 xl:grid-cols-4">
            <div class="grid space-y-1">
                <x-form.input-label for="date_in">Check-in Date</x-form.input-label>
                <x-form.input-date
                    wire:model.live="date_in"
                    x-model="date_in"
                    min="{{ $min_date }}"
                    id="date_in" />
                <x-form.input-error field="date_in" />
            </div>
            <div class="grid space-y-1">
                <x-form.input-label for="date_out">Check-out Date</x-form.input-label>
                <x-form.input-date
                    x-bind:disabled="date_in == '' || date_in == null"
                    wire:model.live="date_out"
                    x-model="date_out"
                    x-bind:value="date_in == '' ? null : date_out" x-bind:min="date_in"
                    id="date_out" />
                <x-form.input-error field="date_out" />
            </div>
            <div class="grid space-y-1">
                <x-form.input-label for="adult_count">Number of Adults</x-form.input-label>
                <x-form.input-number wire:model.live="adult_count" x-model="adult_count"
                    id="adult_count" min="1" />
                <x-form.input-error field="adult_count" />
            </div>
            <div class="grid space-y-1">
                <x-form.input-label for="children_count">Number of Children</x-form.input-label>
                <x-form.input-number x-model="children_count" id="children_count"
                    wire:model.live="children_count" />
                <x-form.input-error field="children_count" />
            </div>
        </div>

        <div class="flex justify-between gap-1">
            <div class="flex items-center gap-3">
                <x-primary-button type="button" wire:click='selectRoom'>Select Room</x-primary-button>
                <x-loading wire:loading.delay wire:target="selectRoom">Please wait while we load the next form.</x-loading>
            </div>
            
            <div class="flex items-center gap-3">
                <div x-on:click="$dispatch('open-modal', 'show-discounts-modal')">
                    <p class="text-sm font-semibold text-right">Apply Discounts</p>
                    <p class="text-xs text-right">For Senior and PWD Guests</p>
                </div>
                <x-icon-button x-on:click="$dispatch('open-modal', 'show-discounts-modal')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-accessibility"><circle cx="16" cy="4" r="1"/><path d="m18 19 1-7-6 1"/><path d="m5 8 3-3 5.5 3-2.36 3.5"/><path d="M4.24 14.5a5 5 0 0 0 6.88 6"/><path d="M13.76 17.5a5 5 0 0 0-6.88-6"/></svg>
                </x-icon-button>
            </div>
        </div>
    </div>

    {{-- Entered reservation details --}}
    <div x-show="can_select_room" class="grid gap-5 sm:grid-cols-2">
        <div class="grid gap-5 p-5 border rounded-md sm:grid-cols-2 border-slate-200">
            <div>
                <p class="font-semibold">{{ date_format(date_create($date_in), 'F j, Y') }}</p>
                <p class="text-xs">Check-in Date</p>
            </div>
            <div>
                <p class="font-semibold">{{ date_format(date_create($date_out), 'F j, Y') }}</p>
                <p class="text-xs">Check-out Date</p>
            </div>
        </div>
        <div class="grid gap-5 p-5 border rounded-md sm:grid-cols-2 border-slate-200">
            <div>
                <p class="font-semibold">{{ $adult_count }}</p>
                <p class="text-xs">Number of Adults</p>
            </div>
            <div>
                <p class="font-semibold">{{ $children_count }}</p>
                <p class="text-xs">Number of Children</p>
            </div>
            @if ($senior_count > 0)
                <div>
                    <p class="font-semibold">{{ $senior_count }}</p>
                    <p class="text-xs">Number of Seniors</p>
                </div>
            @endif
            @if ($pwd_count > 0)
                <div>
                    <p class="font-semibold">{{ $pwd_count }}</p>
                    <p class="text-xs">Number of PWDs</p>
                </div>
            @endif
        </div>
    </div>
</div>

<x-modal.full name='show-discounts-modal' maxWidth='sm'>
    <div x-data="{ discount: '' }" class="p-5 space-y-5" x-on:discount-applied.window="show = false">
        <div x-show="discount == ''">
            Hello!s
        </div>

        {{-- Promos --}}
        <div x-show="discount == 'promo'">
        </div>

        {{-- Discounts --}}
        <div x-show="discount == 'discount'">
            <hgroup>
                <h2 class="text-lg font-semibold">Apply Discounts</h2>
                <p class="text-xs">The number of seniors and PWDs are limited to the number of guests you have.</p>
            </hgroup>
    
            <div class="grid grid-cols-2 gap-5">
                <x-form.input-group>
                    <x-form.input-label for='senior_count'>Number of Seniors</x-form.input-label>
                    <x-form.input-number x-model="senior_count" id="senior_count" name="senior_count" label="Seniors" />
                </x-form.input-group>
                
                <x-form.input-group>
                    <x-form.input-label for='pwd_count'>Number of PWDs</x-form.input-label>
                    <x-form.input-number x-model="pwd_count" id="pwd_count" name="pwd_count" label="PWD" />
                </x-form.input-group>
            </div>
    
            <x-form.input-error field="senior_count" />
            <x-form.input-error field="pwd_count" />
    
            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button type="button" wire:click='applyDiscount'>Save</x-primary-button>
            </div>
        </div>
    </div>
</x-modal.full>