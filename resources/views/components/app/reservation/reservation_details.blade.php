<x-form.form-section>
    {{-- Reservation Details --}}
    <x-form.form-header step="1" title="Reservation Details" />

    <div x-show="!can_enter_guest_details" x-collapse.duration.1000ms>
        <x-form.form-body>
            <div class="p-5 space-y-3">
                <div x-effect="date_in == '' ? date_out = '' : ''"
                    class="grid items-start gap-3 sm:grid-cols-2 xl:grid-cols-4">
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
                        <x-primary-button type="button" x-on:click="$wire.guestDetails()">Guest Details</x-primary-button>
                        <p class="max-w-sm text-xs font-semibold" wire:loading.delay wire:target="guestDetails()">Please wait while we load the next form.</p>
                    </div>

                    <x-secondary-button x-on:click="$dispatch('open-modal', 'pwd-senior-modal');">Add Senior or PWD</x-secondary-button>
                </div>
            </div>
        </x-form.form-body>
    </div>
</x-form.form-section>

<x-modal.full name='pwd-senior-modal' maxWidth='sm'>
    <div class="p-5 space-y-5">
        <hgroup>
            <h3 class="font-bold">Add Senior and PWD</h3>
            <p class="text-xs">If your fellow guest are senior or with disability</p>
        </hgroup>

        <div class="space-y-3">
            <div class="grid grid-cols-2 gap-3">
                <x-form.input-group>
                    <x-form.input-label for="senior_count">Number of Seniors</x-form.input-label>
                    <x-form.input-number x-model="senior_count" id="senior_count" 
                        max="{{ $max_senior_count }}"
                        wire:model.live="senior_count"
                        x-on:change.window="setMaxSeniorCount()"
                        class="block w-full" />
                    <x-form.input-error field="senior_count" />
                </x-form.input-group>

                <x-form.input-group>
                    <x-form.input-label for="pwd_count">Number of PWD</x-form.input-label>
                    <x-form.input-number x-model="pwd_count" id="pwd_count" max="{{ ($adult_count - $senior_count) + $children_count }}"
                        wire:model.live="pwd_count"
                        x-on:change.window="setMaxSeniorCount()"
                        class="block w-full" />
                    <x-form.input-error field="pwd_count" />
                </x-form.input-group>
            </div>
            
            <div class="p-3 space-y-3 border border-gray-300 rounded-lg">
                <h4 class="text-sm font-bold">Guest Summary</h4>

                <div>
                    <p class="text-xs">
                        Adults: <span x-text="adult_count"></span> Adult<span x-show="adult_count > 1">s</span>
                        <span x-show="senior_count > 0">&lpar;<span x-text="senior_count"></span>  Senior<span x-show="senior_count > 1">s</span>&rpar;</span>
                    </p>
                    <p class="text-xs">Children: <span x-text="children_count"></span> Child<span x-show="children_count > 1">ren</span></p>
                    <p class="text-xs">PWD: <span x-text="pwd_count"></span> PWD<span x-show="pwd_count > 1">s</span></p>
                    <p class="text-xs"><strong class="text-blue-500">Total Guests: {{ $adult_count + $children_count }}</strong></p>
                </div>
            </div>
        </div>
    </div>
</x-modal.full>