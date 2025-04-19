<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between w-full">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">
                    {{ __('Billings') }}
                </h1>
                <p class="text-xs">Manage your billings here</p>
            </hgroup>
        </div>
    </x-slot:header>

    <livewire:app.invoice.show-invoices />
</x-app-layout>