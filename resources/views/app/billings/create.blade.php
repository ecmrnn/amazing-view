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

    <div>
        <livewire:app.invoice.create-invoice />
    </div>
</x-app-layout>  