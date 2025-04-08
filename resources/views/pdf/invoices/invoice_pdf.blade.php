<x-pdf-layout>
    <x-slot:title>{{ $invoice->iid . ' - Reservation Summary' }}</x-slot:title>

    {{-- Page 1 --}}
    <div class="space-y-5">
        <header class="flex items-start justify-between">
            <div class="flex items-center gap-5">
                <x-application-logo width="w-14" />
    
                <div>
                    <h1 class="text-base font-semibold">Amazing View Mountain Resort</h1>
                    <p class="text-xs">Little Baguio, Paagahan Mabitac, Laguna, Philippines</p>
                </div>
            </div>
    
            @if ($invoice->issue_date)
                <div>
                    <p class="text-base font-semibold text-right text-blue-800">{{ $invoice->iid }}</p>
                    <p class="text-xs text-right">Invoice ID</p>
                </div>
            @else
                <div>
                    <p class="text-base font-semibold text-right text-blue-800">Running Bill</p>
                    <p class="text-xs text-right">Preview</p>
                </div>
            @endif
        </header>

        <div>
            @if ($invoice->issue_date)
                <h2><strong>Date Issued:</strong> {{ date_format(date_create($invoice->issue_date), 'F j, Y') }}</h2>
            @else
                <h2><strong>Date Printed:</strong> {{ now()->format('F j, Y') }}</h2>
            @endif
        </div>

        <div class="grid grid-cols-2 gap-5">
            <div class="p-5 space-y-5 border rounded-lg bg-slate-50 border-slate-200">
                <div>
                    <h2 class="text-xs font-semibold">Bill From</h2>
                    <p class="text-lg font-semibold capitalize">Amazing View Mountain Resort</p>
                </div>
            
                <div>
                    <p>{{ $settings['site_phone'] }}</p>
                    <p>{{ $settings['site_email'] }}</p>
                    <p class="capitalize">Little Baguio, Paagahan Mabitac, Laguna, Philippines</p>
                </div>
            </div>
            
            <div class="p-5 space-y-5 border rounded-lg bg-slate-50 border-slate-200">
                <div>
                    <h2 class="text-xs font-semibold">Bill to</h2>
                    <p class="text-lg font-semibold capitalize">{{ $invoice->reservation->user->first_name . ' ' . $invoice->reservation->user->last_name }}</p>
                </div>
            
                <div>
                    <p>{{ $invoice->reservation->user->phone }}</p>
                    <p>{{ $invoice->reservation->user->email }}</p>
                    <p class="capitalize">{{ $invoice->reservation->user->address }}</p>
                </div>
            </div>
        </div>

        <livewire:app.reservation-breakdown :reservation="$invoice->reservation" />
    </div>

    @if (!$invoice->issue_date)
        @pageBreak

        {{-- Page 2 --}}
        <div class="space-y-5">
            <header class="flex items-start justify-between">
                <div class="flex items-center gap-5">
                    <x-application-logo width="w-14" />
        
                    <div>
                        <h1 class="text-base font-semibold">Amazing View Mountain Resort</h1>
                        <p class="text-xs">Little Baguio, Paagahan Mabitac, Laguna, Philippines</p>
                    </div>
                </div>
        
                @if ($invoice->issue_date)
                    <div>
                        <p class="text-base font-semibold text-right text-blue-800">{{ $invoice->iid }}</p>
                        <p class="text-xs text-right">Invoice ID</p>
                    </div>
                @else
                    <div>
                        <p class="text-base font-semibold text-right text-blue-800">Running Bill</p>
                        <p class="text-xs text-right">Preview</p>
                    </div>
                @endif
            </header>

            <h2 class="font-semibold">Payment History</h2>

            <div class="overflow-auto border rounded-md border-slate-200">
                <div class="min-w-[600px]">
                    <div class="grid grid-cols-5 px-5 py-2 text-sm font-semibold border-b bg-slate-50 text-zinc-800/60 border-slate-200">
                        <p>No.</p>
                        <p>Amount</p>
                        <p>Payment Method</p>
                        <p>Reference No.</p>
                        <p>Payment Date</p>
                    </div>
            
                    <div>
                        <?php $counter = 0; ?>
                        @foreach ($invoice->payments as $payment)
                            <div wire:key="{{ $payment->id }}" class="grid items-center grid-cols-5 px-5 py-1 text-sm border-b border-dashed hover:bg-slate-50 last:border-b-0 border-slate-200">
                                <p class="py-2 font-semibold text-zinc-800/50">{{ ++$counter }}</p>
                                <p class="py-2 capitalize"><x-currency />{{ number_format($payment->amount, 2) }}</p>
                                <p class="py-2 capitalize">{{ $payment->payment_method }}</p>
                                <p class="py-2 ">{{ $payment->transaction_id ?? '---' }}</p>
                                <p class="py-2 ">{{ date_format(date_create($payment->payment_date), 'F j, Y') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-5 px-5 py-3 border-t border-slate-200">
                    <strong>Total</strong>
                    <strong><x-currency />{{ number_format($invoice->payments()->sum('amount'), 2) }}</strong>
                </div>
            </div>
        </div>
    @endif
</x-pdf-layout>