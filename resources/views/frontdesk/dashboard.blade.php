<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between p-5 py-3 bg-white rounded-lg">
            <h1 class="text-xl font-bold leading-tight text-gray-800">
                {{ __('Dashboard') }}
            </h1>

            <div class="hidden text-right sm:block">
                <p class="text-sm font-bold capitalize">{{ Auth::user()->first_name . " " . Auth::user()->last_name }}</p>
                <p class="text-xs">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </x-slot>

    {{-- Cards --}}
    <div class="grid grid-cols-2 gap-3 lg:gap-5 lg:grid-cols-4">
        <x-app.card
            data="29"
            label="Guest in"
            href="dashboard"
            >
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-round"><path d="M18 21a8 8 0 0 0-16 0"/><circle cx="10" cy="8" r="5"/><path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"/></svg>
            </x-slot:icon>
        </x-app.card>
        <x-app.card
            data="{{ $pending_reservations }}"
            label="Pending Reservations"
            href="dashboard"
            >
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
            </x-slot:icon>
        </x-app.card>
        <x-app.card
            data="{{ $available_rooms }}"
            label="Availabe Rooms"
            href="dashboard"
            >
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-door-open"><path d="M13 4h3a2 2 0 0 1 2 2v14"/><path d="M2 20h3"/><path d="M13 20h9"/><path d="M10 12v.01"/><path d="M13 4.562v16.157a1 1 0 0 1-1.242.97L5 20V5.562a2 2 0 0 1 1.515-1.94l4-1A2 2 0 0 1 13 4.561Z"/></svg>
            </x-slot:icon>
        </x-app.card>
        <x-app.card
            data="2"
            label="Due Invoices"
            href="dashboard"
            >
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-banknote"><rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg>
            </x-slot:icon>
        </x-app.card>
    </div>

    {{-- Charts --}}
    <div class="grid gap-3 lg:grid-cols-2 lg:gap-5">
        <div class="p-3 sm:p-5 h-[320px] bg-white rounded-lg flex flex-col gap-3 sm:gap-5">
            <div>
                <h2 class="text-lg font-semibold">Reservation Rate</h2>
                <p class="max-w-sm text-xs">Monitor your reservation rate using the line graph below.</p>
            </div>

            {{-- Area chart goes here... --}}
            <div class="relative flex-grow w-full">
                <livewire:livewire-area-chart
                    key="{{ $area_chart->reactiveKey() }}"
                    :area-chart-model="$area_chart"
                />
            </div>
        </div>

        <div class="p-3 sm:p-5 h-[320px] bg-white rounded-lg flex flex-col gap-3 sm:gap-5">
            <div>
                <h2 class="text-lg font-semibold">Room Occupancy Rate</h2>
                <p class="max-w-sm text-xs">Monitor your room's statuses using the graph below.</p>
            </div>

            {{-- Pie chart goes here... --}}
            <div class="flex-grow">
                <livewire:livewire-column-chart
                    key="{{ $column_chart->reactiveKey() }}"
                    :column-chart-model="$column_chart"
                />
            </div>
        </div>
    </div>

    {{-- Recent Reservations (Pending and Confirmed) --}}
    <div class="p-3 space-y-5 bg-white rounded-lg sm:p-5">
        <div>
            <h2 class="text-lg font-semibold">Pending and Confirmed Reservations</h2>
            <p class="max-w-sm text-xs">The table below are the lists of your pending and confirmed reservations.</p>
        </div>

        {{-- Reservation Table --}}
        <livewire:dashboard-reservation-table />
    </div>
</x-app-layout>
