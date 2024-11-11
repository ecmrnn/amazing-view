<div class="grid grid-cols-2 gap-3 lg:gap-5 lg:grid-cols-4">
    <x-app.report
            title="Reservation Summary"
            description="Overview of total reservations over a period."
            x-on:click="$dispatch('open-modal', 'reservation-summary')"
        >
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chart-pie"><path d="M21 12c.552 0 1.005-.449.95-.998a10 10 0 0 0-8.953-8.951c-.55-.055-.998.398-.998.95v8a1 1 0 0 0 1 1z"/><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/></svg>
        </x-slot:icon>
    </x-app.report>
    <x-app.report
            title="Daily Reservations"
            description="Daily check-ins, check-outs, and cancellations."
            x-on:click="$dispatch('open-modal', 'daily-reservations')"
        >
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-1"><path d="M11 14h1v4"/><path d="M16 2v4"/><path d="M3 10h18"/><path d="M8 2v4"/><rect x="3" y="4" width="18" height="18" rx="2"/></svg>
        </x-slot:icon>
    </x-app.report>
    <x-app.report
            title="Occupancy Report"
            description="Room occupancy reates over a period."
            x-on:click="$dispatch('open-modal', 'occupancy-report')"
        >
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-folder-key"><circle cx="16" cy="20" r="2"/><path d="M10 20H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3.9a2 2 0 0 1 1.69.9l.81 1.2a2 2 0 0 0 1.67.9H20a2 2 0 0 1 2 2v2"/><path d="m22 14-4.5 4.5"/><path d="m21 15 1 1"/></svg>
        </x-slot:icon>
    </x-app.report>
    <x-app.report
            title="Financial Report"
            description="Total revenue, payments, and balances."
            x-on:click="$dispatch('open-modal', 'financial-report')"
        >
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-piggy-bank"><path d="M19 5c-1.5 0-2.8 1.4-3 2-3.5-1.5-11-.3-11 5 0 1.8 0 3 2 4.5V20h4v-2h3v2h4v-4c1-.5 1.7-1 2-2h2v-4h-2c0-1-.5-1.5-1-2V5z"/><path d="M2 9v1c0 1.1.9 2 2 2h1"/><path d="M16 11h.01"/></svg>
        </x-slot:icon>
    </x-app.report>

    {{-- Modals --}}
     <x-modal.full name="reservation-summary" maxWidth="sm">
        <div x-data="{ checked: false }">
            <livewire:app.reports.create-report.reservation-summary type="reservation summary" />
        </div>
    </x-modal.full> 

     <x-modal.full name="daily-reservations" maxWidth="sm">
        <div x-data="{ checked: false }">
            <livewire:app.reports.create-report.daily-reservations type="daily reservations" />
        </div>
    </x-modal.full> 

     <x-modal.full name="occupancy-report" maxWidth="sm">
        <div x-data="{ checked: false }">
            <livewire:app.reports.create-report.occupancy-report type="occupancy report" />
        </div>
    </x-modal.full> 

     <x-modal.full name="financial-report" maxWidth="sm">
        <div x-data="{ checked: false }">
            <livewire:app.reports.create-report.financial-report type="financial report" />
        </div>
    </x-modal.full> 
</div>