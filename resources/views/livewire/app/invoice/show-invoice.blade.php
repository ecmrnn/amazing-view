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
    <div class="space-y-5">
        {{-- Cards --}}
        <div class="border rounded-md border-grary-300">
            <x-app.card
                label="Remaining Balance"
                :hasLink="false"
                >
                <x-slot:data>
                    <x-currency /> {{ number_format($invoice->balance, 2) }}
                </x-slot:data>

                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wallet"><path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/></svg>
                </x-slot:icon>
            </x-app.card>
        </div>

        <hgroup>
            <h3 class="font-semibold">Payments</h3>
            <p class="text-sm">Track all the payments made on this invoice.</p>
        </hgroup>

        {{-- Payments Table --}}
        <livewire:tables.invoice-payment-table :invoice="$invoice" />
    </div>
    
    <section class="self-start w-full overflow-auto border rounded-lg sm:sticky top-5">
        @include('components.app.billing.summary')
    </section>
</section>