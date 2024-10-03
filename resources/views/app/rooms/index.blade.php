<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between gap-3 p-5 py-3 bg-white rounded-lg">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">
                    {{ __('Rooms') }}
                </h1>
                <p class="text-xs">Manage your rooms here</p>
            </hgroup>

            @can('create room')
                <x-primary-button class="text-xs">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                        <span>Add Room</span>
                    </div>
                </x-primary-button>
            @endcan
        </div>
    </x-slot:header>

    <div class="p-3 space-y-5 bg-white rounded-lg sm:p-5">
        <div>
            <h2 class="text-lg font-semibold">Rooms</h2>
            <p class="max-w-sm text-xs">Manage all your rooms using the table below.</p>
        </div>
    
        {{-- Room Table --}}
        <livewire:tables.room-table />
    </div>
</x-app-layout>