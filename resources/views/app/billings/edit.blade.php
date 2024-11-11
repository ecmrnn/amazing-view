<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between gap-3">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">
                    {{ __('Billings') }}
                </h1>
                <p class="text-xs">Manage your billings here</p>
            </hgroup>
        </div>
    </x-slot:header>

    <div class="p-3 space-y-5 bg-white rounded-lg sm:p-5">
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
                    <p class="max-w-sm text-xs">Modify and update this invoice.</p>
                </div>
            </div>
            {{-- Actions --}}
            <div class="flex items-start gap-1">
                {{-- <x-secondary-button class="hidden text-xs md:block" x-on:click="alert('Downloading PDF... soon...')">Download PDF</x-secondary-button>
                <x-icon-button class="md:hidden" x-on:click="alert('Downloading PDF... soon...')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                </x-icon-button>

                @if (empty($reservation->invoice))
                    <a href="{{ route('app.billings.create', ['rid' => $reservation->rid]) }}" wire:navigate>
                        <x-primary-button class="hidden text-xs md:block">Create Invoice</x-primary-button>
                    </a>
                @else
                    <a href="{{ route('app.billings.index', ['rid' => $reservation->rid]) }}" wire:navigate>
                        <x-secondary-button class="hidden text-xs md:block">View Invoice</x-secondary-button>
                    </a>
                @endif
                <a href="{{ route('app.billings.create', ['rid' => $reservation->rid]) }}" wire:navigate>
                    <x-icon-button class="md:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-receipt-text"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M14 8H8"/><path d="M16 12H8"/><path d="M13 16H8"/></svg>
                    </x-icon-button>
                </a> --}}
            </div>
        </div>
        
        <livewire:app.invoice.edit-invoice :invoice="$invoice" />
    </div>
</x-app-layout>  