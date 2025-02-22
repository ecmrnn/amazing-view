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
    class="relative w-full max-w-screen-lg mx-auto space-y-5 rounded-lg">
    <div class="flex items-start justify-between p-5 bg-white border rounded-lg border-slate-200">
        <div class="flex items-center justify-between gap-3 sm:items-start">
            <div class="flex items-center gap-3 sm:gap-5">
                <x-tooltip text="Back" dir="bottom">
                    <a x-ref="content" href="{{ route('app.billings.index')}}" wire:navigate>
                        <x-icon-button>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                        </x-icon-button>
                    </a>
                </x-tooltip>
        
                <div>
                    <h2 class="text-lg font-semibold">{{ $invoice->iid }}</h2>
                    <p class="max-w-sm text-xs">Confirm and view invoice.</p>
                </div>
            </div>
        </div>

        <x-actions>
            <div class="space-y-1">
                <a href="{{ route('app.billings.edit', ['billing' => $invoice->iid]) }}" wire:navigate>
                    <button type="button" class="flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold rounded-md hover:bg-slate-50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings-2"><path d="M20 7h-9"/><path d="M14 17H5"/><circle cx="17" cy="17" r="3"/><circle cx="7" cy="7" r="3"/></svg>
                        <p>Edit</p>
                    </button>
                </a>
                @if ($invoice->balance > 0)
                    <button type="button" class="flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold rounded-md hover:bg-slate-50" x-on:click="$dispatch('open-modal', 'show-add-payment'); dropdown = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wallet"><path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/></svg>
                        <p>Add Payment</p>
                    </button>
                @endif
            </div>
        </x-actions>
    </div>

    <div class="space-y-5">
        {{-- Cards --}}
        <div class="rounded-md">
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

        <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
            <hgroup>
                <h3 class="font-semibold">Payments</h3>
                <p class="text-xs">Track all the payments made on this invoice.</p>
            </hgroup>
            {{-- Payments Table --}}
            <livewire:tables.invoice-payment-table :invoice="$invoice" />
        </div>
    </div>

    <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
        <hgroup>
            <h2 class="font-semibold">Invoice Details</h2>
            <p class="text-xs">Confirm invoice details</p>
        </hgroup>

        <div class="grid gap-5 p-5 border rounded-md border-slate-200 sm:grid-cols-3">
            <div>
                <h2 class="font-semibold">{{ $invoice->reservation->rid }}</h2>
                <p class="text-xs">Reservation ID</p>
            </div>
            <div>
                <h2 class="font-semibold">{{ $invoice->reservation->email }}</h2>
                <p class="text-xs">Invoice Recepient</p>
            </div>
            <div>
                <h2 class="font-semibold">{{ date_format(date_create($invoice->due_date), 'F j, Y') }}</h2>
                <p class="text-xs">Due Date 
                    @switch($remaining_days)
                        @case(0)
                            &lpar;Today&rpar;
                            @break
                        @case(1)
                            &lpar;Tomorrow&rpar;
                            @break
                        @default
                            &lpar;{{ $remaining_days }} days remaining&rpar;
                    @endswitch
                </p>
            </div>
        </div>

        <livewire:app.reservation-breakdown :reservation="$invoice->reservation" />
    </div>
    
    {{-- <section class="self-start w-full overflow-auto border rounded-lg sm:sticky top-5">
        @include('components.app.billing.summary')
    </section> --}}
</section>