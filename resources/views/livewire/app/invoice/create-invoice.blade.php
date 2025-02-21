<form class="relative w-full max-w-screen-lg mx-auto space-y-5 rounded-lg">
    <div class="flex items-center justify-between gap-5 p-5 bg-white border rounded-lg border-slate-200">
        <div class="flex items-center sm:gap-5">
            <x-tooltip text="Back" dir="bottom">
                <a x-ref="content" href="{{ route('app.billings.index')}}" wire:navigate>
                    <x-icon-button>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    </x-icon-button>
                </a>
            </x-tooltip>
        
            <div>
                <h2 class="text-lg font-semibold">Create Invoice</h2>
                <p class="max-w-sm text-xs">Create invoice for guests here.</p>
            </div>
        </div>

        <x-actions>
            <button type="button" x-on:click="$dispatch('open-modal', 'reset-invoice-modal'); dropdown = false" class="flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold rounded-md hover:bg-slate-50">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                <p>Reset</p>
            </button>
        </x-actions>
    </div>
    
    <section class="space-y-5">
        @if (!empty($reservation))
            <div class="p-5 bg-white border rounded-lg border-slate-200">
                <div>
                    <h2 class="font-semibold">{{ $reservation->rid }}</h2>
                    <p class="text-xs">Reservation ID</p>
                </div>
            </div>
        @else
            <div class="flex flex-col justify-between gap-5 p-5 bg-white border rounded-lg border-slate-200 md:flex-row">
                <hgroup>
                    <h2 class="font-semibold">Find Reservation</h2>
                    <p class="text-xs">Enter the Reservation ID</p>
                </hgroup>
                
                <div class="flex items-stretch gap-1">
                    <div>
                        <x-form.input-text class="w-min" wire:model='reservation_id' id="reservation_id" class="w-min" label="Reservation ID" />
                        <x-form.input-error field="reservation_id" />
                    </div>
                    
                    <x-primary-button type="button" wire:click="findReservation">Find</x-primary-button>
                </div>
            </div>
        @endif

        @include('components.app.billing.invoice-details')

        <x-primary-button type="button" x-on:click="$dispatch('open-modal', 'show-invoice-confirmation')">
            Create Invoice
        </x-primary-button>
    </section>
    
    {{-- Modal for confirming invoice --}}
    <x-modal.full name="show-invoice-confirmation" maxWidth="sm">
        <div x-data="{ checked: false }">
            <section class="p-5 space-y-5 bg-white">
                <hgroup>
                    <h2 class="text-lg font-semibold capitalize">Invoice Confirmation</h2>
                    <p class="max-w-sm text-xs text-zinc-800">Confirm that the invoice details entered are correct.</p>
                </hgroup>

                <div class="px-3 py-2 border border-gray-300 rounded-md">
                    <x-form.input-checkbox x-model="checked" id="checked" label="The information I have provided is true and correct." />
                </div>
                
                <div class="flex items-center justify-end gap-1">
                    <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                    <x-primary-button type="button" x-bind:disabled="!checked" x-on:click="$wire.store(); show = false;">
                        Submit Invoice
                    </x-primary-button>
                </div>
            </section>
        </div>
    </x-modal.full>   

    <x-modal.full name='reset-invoice-modal' maxWidth='sm'>
        <div class="p-5 space-y-5">
            <hgroup>
                <h3 class="text-lg font-semibold">Reset Invoice</h3>
                <p class="text-sm">Are you sure you want to reset your invoice?</p>
            </hgroup>
    
            <div class="flex justify-end gap-1 mt-5">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-danger-button type="button" x-on:click="show = false; $wire.resetInvoice()">Reset</x-danger-button>
            </div>
        </div>
    </x-modal.full>
</form>
