<section
    x-data="{
    date_in: $wire.entangle('date_in'),
    date_out: $wire.entangle('date_out'),
    adult_count: $wire.entangle('adult_count'),
    children_count: $wire.entangle('children_count'),
    night_count: $wire.entangle('night_count'),

    first_name: $wire.entangle('first_name'),
    last_name: $wire.entangle('last_name'),
    email: $wire.entangle('email'),
    phone: $wire.entangle('phone'),
    address: $wire.entangle('address'),

    vat: $wire.entangle('vat'),
    vatable_sales: $wire.entangle('vatable_sales'),
    net_total: $wire.entangle('net_total'),

    reservation_id: '',
    rid: $wire.entangle('rid'),

    issue_date: $wire.entangle('issue_date'),
    due_date: $wire.entangle('due_date'),

    formatDate(date) {
            let options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(date).toLocaleDateString('en-US', options)
        }
    }"
    class="grid grid-cols-1 gap-3 lg:grid-cols-2 sm:gap-5">
    <div class="self-start space-y-5">
        
        {{-- Invoice Details --}}
        <x-form.form-section>
            <x-form.form-header step="1" title="Invoice Details" />
            
            <x-form.form-body>
                <div class="p-5 space-y-3">
                    <hgroup>
                        <h3 class="font-semibold">{{ $invoice->iid }}</h3>
                        <p class="text-xs">Invoice ID</p>
                    </hgroup>

                    <div class="flex flex-col gap-1 sm:flex-row">
                        <div class="space-y-1 sm:w-min">
                            <x-form.input-label>Issue Date</x-form.input-label>
                            <x-form.input-date x-model="issue_date" x-bind:min="date_today" class="w-full sm:w-min" />
                        </div>
                        <div class="space-y-1 sm:w-min">
                            <x-form.input-label>Due Date</x-form.input-label>
                            <x-form.input-date x-model="due_date" x-bind:min="issue_date" class="w-full sm:w-min" />
                        </div>
                    </div>

                    @include('components.web.reservation.add-amenity')

                    <x-primary-button type="button" class="text-xs" wire:click="updateAmenity">Save Changes</x-primary-button>
                </div>
            </x-form.form-body>
        </x-form.form-section>
    </div>
    
    <section class="self-start w-full overflow-auto border border-gray-300 rounded-lg sm:sticky top-5">
        @include('components.app.billing.summary')
    </section>
</section>