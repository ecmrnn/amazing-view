<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between gap-3 p-5 py-3 bg-white rounded-lg">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">
                    {{ __('Guests') }}
                </h1>
                <p class="text-xs">Manage your guests here</p>
            </hgroup>

            <x-primary-button class="text-xs" x-on:click="$dispatch('open-modal', 'show-check-in-modal')">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-in"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
                    <span>Check-in Guest</span>
                </div>
            </x-primary-button>
            {{-- @can('create guest')
            @endcan --}}
        </div>
    </x-slot:header>

    <div class="p-3 space-y-5 bg-white rounded-lg sm:p-5">
        {{-- Guest Table --}}
        <livewire:tables.guest-table />
    </div>

    {{-- Modal for confirming reservation --}}
    <x-modal.full name="show-check-in-modal" maxWidth="sm">
        <div x-data="{ checked: false }" x-on:guest-checked-in.window="show = false">
            <section class="p-5 space-y-5 bg-white">
                <hgroup>
                    <h2 class="font-semibold capitalize">Check-in Guest</h2>
                    <p class="max-w-sm text-sm">Enter the <strong class="text-blue-500">Reservation ID</strong> of the guest you want to check-in.</p>
                </hgroup>

                <livewire:app.guest.check-in-guest />
            </section>
        </div>
    </x-modal.full> 
</x-app-layout>