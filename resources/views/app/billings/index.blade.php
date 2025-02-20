<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between w-full">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">
                    {{ __('Billings') }}
                </h1>
                <p class="text-xs">Manage your billings here</p>
            </hgroup>

            @can('create billing')
                <div class="flex items-center">
                    <x-tooltip text="Create Invoice" dir="bottom">
                        <a x-ref="content" href="{{ route('app.billings.create') }}" wire:navigate.hover class="grid w-10 my-1 rounded-md aspect-square place-items-center hover:bg-slate-50 focus:text-zinc-800 focus:border-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14" /><path d="M12 5v14" /></svg>
                        </a>
                    </x-tooltip>

                    <div class="w-[1px] h-1/2 bg-gray-300"></div>
                </div>
            @endcan
        </div>
    </x-slot:header>

    <livewire:app.invoice.show-invoices />
</x-app-layout>