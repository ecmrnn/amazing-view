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
                            x-bind:min="`${min.getFullYear()}-${String(min.getMonth() + 1).padStart(2, '0')}-${String(min.getDate()).padStart(2, '0')}`"
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

                <div class="flex items-center gap-3">
                    <x-primary-button type="button" x-on:click="$wire.guestDetails()">Guest Details</x-primary-button>
                    <p class="max-w-sm text-xs font-semibold" wire:loading.delay wire:target="guestDetails()">Please wait while we load the next form.</p>
                </div>
            </div>
        </x-form.form-body>
    </div>
</x-form.form-section>