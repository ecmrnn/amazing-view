<form x-data="{
    date_today: $wire.entangle('date_today'),
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
    class="grid gap-5 lg:grid-cols-2">
    @csrf
    <section>
        <x-form.form-section>
            <x-form.form-header step="1" title="Reservation Details" />

            <div x-show="rid == null" x-collapse.duration.1000ms>
                <x-form.form-body>
                    <div class="p-5 space-y-3">
                        <hgroup>
                            <h2 class="text-sm font-semibold">Already have a reservation?</h2>
                            <p class="text-xs">Enter the reservation ID below.</p>
                        </hgroup>
                        <div class="flex flex-col items-start gap-1 sm:flex-row sm:items-stretch">
                            <x-form.input-text x-on:keyup.enter.prevent="$wire.getReservation(reservation_id)"  x-model="reservation_id" id="reservation_id" form="reservation_id_form" class="w-min" label="Reservation ID" />
                            <x-primary-button type="button" x-on:click="$wire.getReservation(reservation_id)">Find Reservation</x-primary-button>
                        </div>
                    </div>
                </x-form.form-body>
            </div>
        </x-form.form-section>

        <x-line-vertical /> 

        @include('components.app.billing.invoice-details')
    </section>

    <section class="self-start w-full overflow-auto border rounded-lg sm:sticky top-5">
        @include('components.app.billing.summary')
    </section>
    
    {{-- Modal for confirming invoice --}}
    <x-modal.full name="show-invoice-confirmation" maxWidth="sm">
        <div x-data="{ checked: false }">
            <section class="p-5 space-y-5 bg-white">
                <hgroup>
                    <h2 class="text-sm font-semibold text-center capitalize">Invoice Confirmation</h2>
                    <p class="max-w-sm text-xs text-center text-zinc-800/50">Confirm that the invoice details entered are correct.</p>
                </hgroup>

                <div class="px-3 py-2 border rounded-md">
                    <x-form.input-checkbox x-model="checked" id="checked" label="The information I have provided is true and correct." />
                </div>
                
                <div class="flex items-center justify-center gap-1">
                    <x-secondary-button x-on:click="show = false">Cancel</x-secondary-button>
                    <x-primary-button x-bind:disabled="!checked" class="text-xs" x-on:click="$wire.store(); show = false;">
                        Submit Invoice
                    </x-primary-button>
                </div>
            </section>
        </div>
    </x-modal.full> 
</form>
