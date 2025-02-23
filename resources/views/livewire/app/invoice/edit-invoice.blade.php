<section
    x-data="{
        issue_date: $wire.entangle('issue_date'),
        due_date: $wire.entangle('due_date'),
    }" class="relative w-full max-w-screen-lg mx-auto space-y-5 rounded-lg">
    <div class="flex items-center justify-between gap-3 p-5 bg-white border rounded-lg sm:items-start border-slate-200">
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
    </div>

    
    <section x-data="{ show: false }" class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
        <div class="flex items-center gap-5">
            <div class="grid font-bold text-white bg-blue-500 rounded-md aspect-square w-14 place-items-center">
                <p class="text-xl">{{ ucwords($invoice->reservation->first_name[0]) . ucwords($invoice->reservation->last_name[0]) }}</p>
            </div>

            <hgroup>
                <h2 class="text-lg font-semibold capitalize">
                    {{ $invoice->reservation->first_name . ' ' . $invoice->reservation->last_name }}</h2>
                <p class="text-xs">Full Name</p>
            </hgroup>
        </div>

        <div class="grid gap-5 lg:grid-cols-2">
            <div class="grid gap-5 p-5 border rounded-md lg:grid-cols-2 border-slate-200">
                <div>
                    <p class="font-semibold">{{ $invoice->reservation->email }}</p>
                    <p class="text-xs">Email</p>
                </div>

                <div>
                    <p class="font-semibold">{{ $invoice->reservation->phone }}</p>
                    <p class="text-xs">Contact No.</p>
                </div>
                <div class="lg:hidden">
                    <p class="font-semibold">{{ $invoice->reservation->address }}</p>
                    <p class="text-xs">Address</p>
                </div>
            </div>

            <div class="hidden p-5 border rounded-md lg:grid lg:grid-cols-2 border-slate-200">
                <div class="lg:col-span-2">
                    <p class="font-semibold">{{ $invoice->reservation->address }}</p>
                    <p class="text-xs">Address</p>
                </div>
            </div>
        </div>
    </section>
    
    <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
        <div class="flex items-start justify-between">
            <hgroup>
                <h2 class="font-semibold">Invoice Details</h2>
                <p class="text-xs">Edit invoice details here</p>
            </hgroup>

            <x-status type="invoice" :status="$invoice->status"></x-status>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 md:grid-cols-3">
            <x-form.input-group>
                <x-form.input-label for='issue_date'>Issue Date</x-form.input-label>
                <x-form.input-date wire:model.live='issue_date' id="issue_date" name="issue_date" label="issue_date" class="w-full" />
                <x-form.input-error field="issue_date" />
            </x-form.input-group>

            <x-form.input-group>
                <x-form.input-label for='due_date'>Due Date</x-form.input-label>
                <x-form.input-date wire:model.live='due_date' id="due_date" name="due_date" label="due_date" class="w-full" />
                <x-form.input-error field="due_date" />
            </x-form.input-group>
        </div>
    </div>

    <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
        <livewire:app.invoice.add-item :invoice="$invoice->id" />
    </div>

    <x-primary-button type="button" wire:click="update">Save Changes</x-primary-button>
</section>