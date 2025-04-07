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
    <div class="flex items-center justify-between p-5 bg-white border rounded-lg border-slate-200">
        <div class="flex items-center justify-between gap-3 sm:items-start">
            <div class="flex items-center gap-3 sm:gap-5">
                @php
                    if (Auth::user()->role == App\Enums\UserRole::GUEST->value) {
                        $route = route('app.billings.guest-billings', ['user' => Auth::user()->id]);
                    } else {
                        $route = route('app.billings.index');
                    }
                @endphp
                <x-tooltip text="Back" dir="bottom">
                    <a x-ref="content" href="{{ $route }}" wire:navigate>
                        <x-icon-button>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                        </x-icon-button>
                    </a>
                </x-tooltip>
        
                <div>
                    <h2 class="text-lg font-semibold">Billing Details</h2>
                    <p class="max-w-sm text-xs">Confirm and view billing</p>
                </div>
            </div>
        </div>

        @hasanyrole(['admin', 'receptionist'])
            <x-actions>
                <div class="space-y-1">
                    @if (in_array($invoice->status, [
                            App\Enums\InvoiceStatus::PARTIAL->value,
                            App\Enums\InvoiceStatus::PENDING->value,
                            App\Enums\InvoiceStatus::PAID->value,
                        ])) 
                        <a href="{{ route('app.billings.edit', ['billing' => $invoice->iid]) }}" wire:navigate>
                            <button type="button" class="flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold rounded-md hover:bg-slate-50">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings-2"><path d="M20 7h-9"/><path d="M14 17H5"/><circle cx="17" cy="17" r="3"/><circle cx="7" cy="7" r="3"/></svg>
                                <p>Edit</p>
                            </button>
                        </a>
                        @if ($invoice->balance == 0)
                            <button type="button" class="flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold rounded-md hover:bg-slate-50" x-on:click="$dispatch('open-modal', 'issue-invoice'); dropdown = false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-receipt-text"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M14 8H8"/><path d="M16 12H8"/><path d="M13 16H8"/></svg>
                                <p>Issue Invoice</p>
                            </button>
                        @endif
                        @if ($invoice->balance > 0)
                            <button type="button" class="flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold rounded-md hover:bg-slate-50" x-on:click="$dispatch('open-modal', 'show-add-payment'); dropdown = false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wallet"><path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/></svg>
                                <p>Add Payment</p>
                            </button>
                        @endif
                    @else
                        <button wire:click='download' type="button" class="flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold rounded-md hover:bg-slate-50">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                            <p>Download Invoice</p>
                        </button>
                    @endif
                </div>
            </x-actions>
        @endhasanyrole
    </div>

    <div class="space-y-5">
        {{-- Cards --}}
        <div class="rounded-md">
            @if ($invoice->balance >= 0)
                <x-app.card
                    label="Remaining Balance"
                    :hasLink="false"
                    >
                    <x-slot:data>
                        <x-currency />{{ number_format(ceil($invoice->balance), 2) }}
                    </x-slot:data>

                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wallet"><path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/></svg>
                    </x-slot:icon>
                </x-app.card>
            @else
                <x-app.card
                    label="Refundable Amount"
                    :hasLink="false"
                    >
                    <x-slot:data>
                        <x-currency />{{ number_format(abs(ceil($invoice->balance)), 2) }}
                    </x-slot:data>
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-banknote-arrow-down-icon lucide-banknote-arrow-down"><path d="M12 18H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5"/><path d="m16 19 3 3 3-3"/><path d="M18 12h.01"/><path d="M19 16v6"/><path d="M6 12h.01"/><circle cx="12" cy="12" r="2"/></svg>
                    </x-slot:icon>
                </x-app.card>
            @endif
        </div>

        <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
            <hgroup>
                <h3 class="font-semibold">Payments</h3>
                <p class="text-xs">Track all the payments made on this bill</p>
            </hgroup>
            @if ($payment_count > 0)
                {{-- Payments Table --}}
                <livewire:tables.invoice-payment-table :invoice="$invoice" />
            @else
                <div class="py-5 font-semibold text-center border rounded-md border-slate-200">
                    <x-table-no-data.invoice-payment />
                </div>
            @endif
        </div>
    </div>

    <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
        <div class="flex items-start justify-between">
            <hgroup>
                <h2 class="font-semibold">Billing Details</h2>
                <p class="text-xs">Confirm billing details here</p>
            </hgroup>

            <x-status type="invoice" :status="$invoice->status"></x-status>
        </div>

            @php
                if (Auth::user()->role == App\Enums\UserRole::GUEST->value) {
                    $route = route('app.reservations.show-guest-reservations', ['reservation' => $invoice->reservation->rid]);
                } else {
                    $route = route('app.reservations.show', ['reservation' => $invoice->reservation->rid]);
                }
            @endphp

        <div class="flex flex-col gap-5 p-5 border rounded-md border-slate-200 sm:flex-row">
            <div class="w-full">
                <a href="{{ $route }}" wire:navigate>
                    <h2 class="font-semibold text-blue-500">{{ $invoice->reservation->rid }}</h2>
                </a>
                <p class="text-xs">Reservation ID</p>
            </div>
            <div class="w-full">
                <h2 class="font-semibold">{{ $invoice->reservation->user->email }}</h2>
                <p class="text-xs">Billing Recepient</p>
            </div>
            @if ($invoice->status != App\Enums\InvoiceStatus::ISSUED->value)
                <div class="w-full">
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
            @endif
        </div>

        <livewire:app.reservation-breakdown :reservation="$invoice->reservation" />
    </div>
</section>