<div class="flex flex-col gap-5 md:flex-row">
    <aside class="space-y-5 shrink-0 min-w-52">
        <p class="text-xs font-semibold">Filter Billing Status</p>

        <ul>
            <li>
                <x-side-nav-link :status="null" href="{{ route('app.billings.index') }}">
                    <div class="flex items-center gap-1">
                        <span>All Billings</span>
                        @if ($invoice_by_status['all'] > 0)
                            <div class="text-xs">( {{ $invoice_by_status['all'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
            <li class="flex items-center gap-2 py-3 text-xs font-semibold">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rectangle-ellipsis"><rect width="20" height="12" x="2" y="6" rx="2"/><path d="M12 12h.01"/><path d="M17 12h.01"/><path d="M7 12h.01"/></svg>
                <span>In Progress</span>
            </li>
            <li>
                <x-side-nav-link :status="\App\Enums\InvoiceStatus::PARTIAL->value" href="{{ route('app.billings.index', ['status' => \App\Enums\InvoiceStatus::PARTIAL->value]) }}">
                    <div class="flex items-center gap-1">
                        <span>Partial</span>
                        @if ($invoice_by_status['partial'] > 0)
                            <div class="text-xs">( {{ $invoice_by_status['partial'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
            <li>
                <x-side-nav-link :status="\App\Enums\InvoiceStatus::PENDING->value" href="{{ route('app.billings.index', ['status' => \App\Enums\InvoiceStatus::PENDING->value]) }}">
                    <div class="flex items-center gap-1">
                        <span>Pending</span>
                        @if ($invoice_by_status['pending'] > 0)
                            <div class="text-xs">( {{ $invoice_by_status['pending'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
            <li class="flex items-center gap-2 py-3 text-xs font-semibold">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-check"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="m9 12 2 2 4-4"/></svg>
                <span>Finalized</span>
            </li>
            <li>
                <x-side-nav-link :status="\App\Enums\InvoiceStatus::PAID->value" href="{{ route('app.billings.index', ['status' => \App\Enums\InvoiceStatus::PAID->value]) }}">
                    <div class="flex items-center gap-1">
                        <span>Paid</span>
                        @if ($invoice_by_status['paid'] > 0)
                            <div class="text-xs">( {{ $invoice_by_status['paid'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
            <li class="flex items-center gap-2 py-3 text-xs font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                <span>Problematic</span>
            </li>
            <li>
                <x-side-nav-link :status="\App\Enums\InvoiceStatus::DUE->value" href="{{ route('app.billings.index', ['status' => \App\Enums\InvoiceStatus::DUE->value]) }}">
                    <div class="flex items-center gap-1">
                        <span>Due</span>
                        @if ($invoice_by_status['due'] > 0)
                            <div class="text-xs">( {{ $invoice_by_status['due'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
            <li>
                <x-side-nav-link :status="\App\Enums\InvoiceStatus::CANCELED->value" href="{{ route('app.billings.index', ['status' => \App\Enums\InvoiceStatus::CANCELED->value]) }}">
                    <div class="flex items-center gap-1">
                        <span>Canceled</span>
                        @if ($invoice_by_status['canceled'] > 0)
                            <div class="text-xs">( {{ $invoice_by_status['canceled'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
        </ul>
    </aside>

    <div class="w-full space-y-5 overflow-x-hidden">
        <livewire:app.cards.invoice-cards />
    
        <div class="p-5 bg-white border rounded-lg border-slate-200">
    
            @if ($invoice_count > 0)
                <livewire:tables.invoice-table />
            @else
                <div class="font-semibold text-center">
                    <x-table-no-data.invoice />
                </div>
            @endif
        </div>
    </div>
</div>