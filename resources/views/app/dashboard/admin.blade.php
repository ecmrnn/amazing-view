<x-app-layout>
    <x-slot:header>
        <hgroup>
            <h1 class="text-xl font-bold leading-tight text-gray-800">
                {{ __('Dashboard') }}
            </h1>
            <p class="text-xs">Keep track of your records</p>
        </hgroup>
    </x-slot:header>

    {{-- Cards --}}
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:gap-5 lg:grid-cols-4">
        <x-app.card
            label="Monthly Revenue"
            href="app.billings.index"
            >
            <x-slot:data>
                <x-currency />{{ number_format($monthly_revenue->revenue, 2) }}
            </x-slot:data>
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-percent"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="M9 9h.01"/><path d="M15 15h.01"/></svg>
            </x-slot:icon>
        </x-app.card>

        <x-app.card
            label="Outstanding Balances"
            href="app.billings.index"
            >
            <x-slot:data>
                <x-currency />{{ number_format($outstanding_balance->balance, 2) }}
            </x-slot:data>
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hand-coins"><path d="M11 15h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 17"/><path d="m7 21 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9"/><path d="m2 16 6 6"/><circle cx="16" cy="9" r="2.9"/><circle cx="6" cy="5" r="3"/></svg>
            </x-slot:icon>
        </x-app.card>

        <x-app.card
            :data="$monthly_new_guests->new_guests"
            label="Monthly New Guests"
            href="app.guests.index"
            >
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-smile-plus"><path d="M22 11v1a10 10 0 1 1-9-10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" x2="9.01" y1="9" y2="9"/><line x1="15" x2="15.01" y1="9" y2="9"/><path d="M16 5h6"/><path d="M19 2v6"/></svg>
            </x-slot:icon>
        </x-app.card>

        <x-app.card
            :data="$monthly_reservations->reservation_count"
            label="Monthly Reservation"
            href="app.reservations.index"
            >
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-notebook"><path d="M2 6h4"/><path d="M2 10h4"/><path d="M2 14h4"/><path d="M2 18h4"/><rect width="16" height="20" x="4" y="2" rx="2"/><path d="M16 2v20"/></svg>
            </x-slot:icon>
        </x-app.card>
    </div>

    {{-- Charts --}}
    <div class="grid gap-3 lg:grid-cols-2 lg:gap-5">
        <div class="p-5 h-[320px] bg-white border border-slate-200 rounded-lg flex flex-col gap-3 sm:gap-5">
            <div>
                <h2 class="text-lg font-semibold">Monthly Reservations</h2>
                <p class="max-w-sm text-xs">Monitor your reservation rate using the line graph below</p>
            </div>

            {{-- Area chart goes here... --}}
            <div class="relative flex-grow w-full">
                <livewire:livewire-area-chart
                    key="{{ $area_chart_reservation->reactiveKey() }}"
                    :area-chart-model="$area_chart_reservation"
                />
            </div>
        </div>

        <div class="p-5 h-[320px] bg-white border border-slate-200 rounded-lg flex flex-col gap-3 sm:gap-5">
            <div>
                <h2 class="text-lg font-semibold">Monthly Sales</h2>
                <p class="max-w-sm text-xs">Monitor your monthly sales using the line graph below</p>
            </div>

            {{-- Pie chart goes here... --}}
            <div class="flex-grow">
                <livewire:livewire-area-chart
                    key="{{ $area_chart_sales->reactiveKey() }}"
                    :area-chart-model="$area_chart_sales"
                />
            </div>
        </div>
    </div>

    {{-- Recent Reservations (Pending) --}}
    <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
        <div>
            <h2 class="text-lg font-semibold">Pending Reservations</h2>
            <p class="max-w-sm text-xs">The table below are the lists of your pending reservations</p>
        </div>
        
        @if ($reservation_count > 0)
            {{-- Reservation Table --}}
            <livewire:tables.dashboard-reservation-table />
        @else
            <div class="font-semibold text-center border rounded-md border-slate-200s">
                <x-table-no-data.reservations />
            </div>
        @endif
    </div>
</x-app-layout>
