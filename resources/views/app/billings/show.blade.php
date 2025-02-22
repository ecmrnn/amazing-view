<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between w-full gap-5">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">
                    {{ __('Billings') }}
                </h1>
                <p class="text-xs">Manage your billings here</p>
            </hgroup>

            @if ($invoice->balance > 0)
                @can('create billing')
                    <div class="flex items-center">
                        <x-tooltip text="Add Payment" dir="bottom">
                            <button x-ref="content" x-on:click="$dispatch('open-modal', 'show-add-payment')" wire:navigate.hover class="grid w-10 my-1 rounded-md aspect-square place-items-center hover:bg-slate-50 focus:text-zinc-800 focus:border-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14" /><path d="M12 5v14" /></svg>
                            </button>
                        </x-tooltip>

                        <div class="w-[1px] h-1/2 bg-gray-300"></div>
                    </div>
                @endcan
            @endif
        </div>
    </x-slot:header>

    <div>
        <livewire:app.invoice.show-invoice :invoice="$invoice" />

        {{-- Modal for confirming reservation --}}
        <x-modal.full name="show-add-payment" maxWidth="sm">
            <div>
                <livewire:app.invoice.create-payment :invoice="$invoice" />
            </div>
        </x-modal.full> 
    </div>
</x-app-layout>  