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
                <h2 class="text-lg font-semibold">Edit Billing</h2>
                <p class="max-w-sm text-xs">Modify and update this billing</p>
            </div>
        </div>
    </div>

    
    <section x-data="{ show: false }" class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
        <div class="flex items-center gap-5">
            <div class="grid font-bold text-white bg-blue-500 rounded-md aspect-square w-14 place-items-center">
                <p class="text-xl">{{ ucwords($invoice->reservation->user->first_name[0]) . ucwords($invoice->reservation->user->last_name[0]) }}</p>
            </div>

            <hgroup>
                <h2 class="text-lg font-semibold capitalize">
                    {{ $invoice->reservation->user->first_name . ' ' . $invoice->reservation->user->last_name }}</h2>
                <p class="text-xs">Full Name</p>
            </hgroup>
        </div>

        <div class="grid gap-5 lg:grid-cols-2">
            <div class="grid gap-5 p-5 border rounded-md lg:grid-cols-2 border-slate-200">
                <div>
                    <p class="font-semibold">{{ $invoice->reservation->user->email }}</p>
                    <p class="text-xs">Email</p>
                </div>

                <div>
                    <p class="font-semibold">{{ $invoice->reservation->user->phone }}</p>
                    <p class="text-xs">Contact No.</p>
                </div>
                <div class="lg:hidden">
                    <p class="font-semibold">{{ $invoice->reservation->user->address }}</p>
                    <p class="text-xs">Address</p>
                </div>
            </div>

            <div class="hidden p-5 border rounded-md lg:grid lg:grid-cols-2 border-slate-200">
                <div class="lg:col-span-2">
                    <p class="font-semibold">{{ $invoice->reservation->user->address }}</p>
                    <p class="text-xs">Address</p>
                </div>
            </div>
        </div>
    </section>
    
    <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
        <div class="flex items-start justify-between">
            <hgroup>
                <h2 class="font-semibold">Billing Details</h2>
                <p class="text-xs">Edit billing details here</p>
            </hgroup>

            <x-status type="invoice" :status="$invoice->status"></x-status>
        </div>
    </div>

    <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
        <livewire:app.invoice.add-item :invoice="$invoice->id" />
    </div>

    <x-primary-button type="button" wire:click="update">Save Changes</x-primary-button>
</section>