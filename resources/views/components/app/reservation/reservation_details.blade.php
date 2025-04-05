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

        <div class="flex flex-col-reverse gap-5 md:justify-between md:flex-row">
            <div class="flex items-center gap-3">
                <x-primary-button type="button" wire:click='selectRoom'>Select Room</x-primary-button>
                <x-loading wire:loading.delay wire:target="selectRoom">Please wait while we load the next form.</x-loading>
            </div>
            
            <div class="flex flex-row-reverse items-center self-start gap-3 md:flex-row" x-on:click="$dispatch('open-modal', 'show-discounts-modal')">
                <div>
                    <p class="text-sm font-semibold md:text-right">Apply Discounts</p>
                    <p class="text-xs md:text-right">Save a peso, apply discount!</p>
                </div>

                <x-icon-button>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-percent-icon lucide-badge-percent"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="m15 9-6 6"/><path d="M9 9h.01"/><path d="M15 15h.01"/></svg>
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
                <p class="font-semibold">
                    {{ $adult_count > 1 ? $adult_count . ' Adults' : $adult_count . ' Adult' }}
                    @if ($children_count > 0)
                        {{ ' & ' }}
                        {{ $children_count > 1 ?  $children_count . ' Children' : $children_count . ' Child' }}
                    @endif
                </p>
                <p class="text-xs">Total Number of Guests</p>
            </div>
            @if ($senior_count > 0 || $pwd_count > 0)
                <div>
                    <p class="font-semibold">
                        @if ($senior_count > 0)
                            {{ $senior_count > 1 ?  $senior_count . ' Seniors' : $senior_count . ' Senior' }}
                        @endif
                        @if ($pwd_count > 0)
                            @if ($senior_count > 0)
                                {{ ' & ' }}
                            @endif
                            {{ $pwd_count > 1 ?  $pwd_count . ' PWDs' : $pwd_count . ' PWD' }}
                        @endif
                    </p>
                    <p class="text-xs">Seniors and PWDs</p>
                </div>
            @endif
        </div>
    </div>
</div>

<x-modal.full name='show-discounts-modal' maxWidth='sm'>
    <div x-data="{ discount: '' }" class="p-5" x-on:discount-applied.window="show = false">
        <div x-show="discount == ''" class="space-y-5">
            <hgroup>
                <h2 class='font-semibold'>Apply Discounts</h2>
                <p class='text-xs'>Select what type of discounts you want to apply</p>
            </hgroup>
            
            <button type="button" class="inline-flex items-center w-full gap-5 p-5 text-left bg-white border rounded-md border-slate-200" x-on:click="discount = 'promo'">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ticket-percent-icon lucide-ticket-percent"><path d="M2 9a3 3 0 1 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 1 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M9 9h.01"/><path d="m15 9-6 6"/><path d="M15 15h.01"/></svg>
                
                <div>
                    <h3 class="font-semibold">Promo Code</h3>
                    <p class="text-xs">Enter your promo code</p>
                </div>
            </button>

            <button type="button" class="inline-flex items-center w-full gap-5 p-5 text-left bg-white border rounded-md border-slate-200" x-on:click="discount = 'discount'">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-accessibility-icon lucide-accessibility"><circle cx="16" cy="4" r="1"/><path d="m18 19 1-7-6 1"/><path d="m5 8 3-3 5.5 3-2.36 3.5"/><path d="M4.24 14.5a5 5 0 0 0 6.88 6"/><path d="M13.76 17.5a5 5 0 0 0-6.88-6"/></svg>

                <div>
                    <h3 class="font-semibold">Senior and PWDs</h3>
                    <p class="text-xs">Avail 20&#37; discount</p>
                </div>
            </button>

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
            </div>
        </div>

        <div x-show="discount == 'promo'" class="space-y-5">
            <hgroup>
                <h2 class='font-semibold'>Promo Code</h2>
                <p class='text-xs'>Enter your promo code to avail discounts!</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for='promo_code'>Enter promo code here</x-form.input-label>
                <x-form.input-text id="promo_code" name="promo_code" label="Promo Code" />
                <x-form.input-error field="promo_code" />
            </x-form.input-group>

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="discount = ''">Cancel</x-secondary-button>
                <x-primary-button type="button" wire:click='applyPromo'>Apply</x-primary-button>
            </div>
        </div>

        <div x-show="discount == 'discount'" class="space-y-5">
            <hgroup>
                <h2 class="font-semibold">Senior and PWDs</h2>
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

            <x-form.input-group>
                <x-filepond::upload
                    wire:model="discount_attachment"
                    placeholder="Drag & drop your image or <span class='filepond--label-action'> Browse </span>"
                />
                <x-form.input-error field="discount_attachment" />
                <p class="max-w-sm text-xs">Please upload an image &lpar;<strong class="text-blue-500">JPG, JPEG, PNG</strong>&rpar; of the payment slip for your down payment. Maximum image size &lpar;<strong class="text-blue-500">3MB or 3000kb</strong>&rpar;</p>
            </x-form.input-group>
            
            <x-form.input-error field="senior_count" />
            <x-form.input-error field="pwd_count" />

            <x-note>This will not be immediately applied and will require confirmation first upon your arrival at the resort.</x-note>

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="discount = ''">Cancel</x-secondary-button>
                <x-primary-button type="button" wire:click='applyDiscount'>Save</x-primary-button>
            </div>
        </div>
    </div>
</x-modal.full>