<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between w-full">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">Reservations</h1>
                <p class="text-xs">Manage your reservations here</p>
            </hgroup>

            <div class="flex items-center">
                @can('create reservation')
                    <x-tooltip text="Create Reservation" dir="bottom">
                        <a x-ref="content" href="{{ route('app.reservations.create') }}" wire:navigate.hover>
                            <x-icon-button>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14" /><path d="M12 5v14" /></svg>
                            </x-icon-button>
                        </a>
                    </x-tooltip>
                @endcan

                <div class="w-[1px] h-1/2 bg-gray-300"></div>
            </div>
        </div>
    </x-slot:header>

    <livewire:app.reservation.show-reservations />
</x-app-layout>
