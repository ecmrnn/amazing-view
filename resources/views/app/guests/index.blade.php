<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between w-full">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">
                    {{ __('Guests') }}
                </h1>
                <p class="text-xs">Manage your guests here</p>
            </hgroup>

            <div class="flex items-center">
                @can('create reservation')
                    <x-tooltip text="Check-in Guest" dir="bottom">
                        <button x-ref="content" x-on:click="$dispatch('open-modal', 'show-check-in-modal')" class="grid w-10 my-1 rounded-md aspect-square place-items-center hover:bg-slate-50 focus:text-zinc-800 focus:border-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-in"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
                        </button>
                    </x-tooltip>
                @endcan

                <div class="w-[1px] h-1/2 bg-gray-300"></div>
            </div>
        </div>
    </x-slot:header>

    <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
        {{-- Guest Table --}}
        <livewire:tables.guest-table />
    </div>

    {{-- Modal for confirming reservation --}}
    <x-modal.full name="show-check-in-modal" maxWidth="md">
        <livewire:app.guest.check-in-guest />
    </x-modal.full> 
</x-app-layout>