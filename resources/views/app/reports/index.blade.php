<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between gap-3">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">
                    {{ __('Reports') }}
                </h1>
                <p class="text-xs">Manage your reports here</p>
            </hgroup>

            <x-primary-button class="text-xs" x-on:click="$dispatch('open-modal', 'generate-report')">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-plus">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                    <span>Generate Report</span>
                </div>
            </x-primary-button>
        </div>
    </x-slot:header>

    {{-- Report Cards --}}
    <livewire:app.cards.report-cards />

    {{-- Report  Table --}}
    <div class="p-5 bg-white rounded-lg">
        <livewire:tables.reports-table />
    </div>

    {{-- Generate Report Modal --}}
    <x-modal.full name="generate-report" maxWidth="sm">
        <div class="p-5 space-y-5">
            <hgroup>
                <h3 class="font-semibold text-center">Generate Report</h3>
                <p class="max-w-sm mx-auto text-xs text-center">Choose a report type you want to generate.</p>
            </hgroup>

            <div class="grid grid-cols-2 gap-3">
                <x-secondary-button class="flex flex-col items-center justify-center gap-3 p-3 rounded-md aspect-square" x-on:click="show = false; $dispatch('open-modal', 'reservation-summary')">
                    <div class="p-3 text-white bg-blue-500 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chart-pie"><path d="M21 12c.552 0 1.005-.449.95-.998a10 10 0 0 0-8.953-8.951c-.55-.055-.998.398-.998.95v8a1 1 0 0 0 1 1z"/><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/></svg>
                    </div>
                    <h4 class="text-xs font-semibold text-center">Reservation <br /> Summary</h4>
                </x-secondary-button>
                <x-secondary-button class="flex flex-col items-center justify-center gap-3 p-3 rounded-md aspect-square" x-on:click="show = false; $dispatch('open-modal', 'daily-reservations')">
                    <div class="p-3 text-white bg-blue-500 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-1"><path d="M11 14h1v4"/><path d="M16 2v4"/><path d="M3 10h18"/><path d="M8 2v4"/><rect x="3" y="4" width="18" height="18" rx="2"/></svg>
                    </div>
                    <h4 class="text-xs font-semibold text-center">Daily <br /> Reservations</h4>
                </x-secondary-button>
                <x-secondary-button class="flex flex-col items-center justify-center gap-3 p-3 rounded-md aspect-square" x-on:click="show = false; $dispatch('open-modal', 'occupancy-report')">
                    <div class="p-3 text-white bg-blue-500 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-folder-key"><circle cx="16" cy="20" r="2"/><path d="M10 20H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3.9a2 2 0 0 1 1.69.9l.81 1.2a2 2 0 0 0 1.67.9H20a2 2 0 0 1 2 2v2"/><path d="m22 14-4.5 4.5"/><path d="m21 15 1 1"/></svg>
                    </div>
                    <h4 class="text-xs font-semibold text-center">Occupancy <br /> Report</h4>
                </x-secondary-button>
                <x-secondary-button class="flex flex-col items-center justify-center gap-3 p-3 rounded-md aspect-square" x-on:click="show = false; $dispatch('open-modal', 'financial-report')">
                    <div class="p-3 text-white bg-blue-500 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-piggy-bank"><path d="M19 5c-1.5 0-2.8 1.4-3 2-3.5-1.5-11-.3-11 5 0 1.8 0 3 2 4.5V20h4v-2h3v2h4v-4c1-.5 1.7-1 2-2h2v-4h-2c0-1-.5-1.5-1-2V5z"/><path d="M2 9v1c0 1.1.9 2 2 2h1"/><path d="M16 11h.01"/></svg>
                    </div>
                    <h4 class="text-xs font-semibold text-center">Financial <br /> Report</h4>
                </x-secondary-button>
            </div>
        </div>
    </x-modal.full> 
</x-app-layout>
