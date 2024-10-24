<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between gap-3 p-5 py-3 bg-white rounded-lg">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">
                    {{ __('Billings') }}
                </h1>
                <p class="text-xs">Manage your billings here</p>
            </hgroup>

            @can('create billing')
                <a href="{{ route('app.billings.create') }}" wire:navigate.hover>
                    <x-primary-button class="text-xs">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            <span>Create Invoice</span>
                        </div>
                    </x-primary-button>
                </a>
            @endcan
        </div>
    </x-slot:header>

    <div class="p-3 space-y-5 bg-white rounded-lg sm:p-5">
        {{-- Invoice table --}}
        <livewire:tables.invoice-table />
    </div>
</x-app-layout>