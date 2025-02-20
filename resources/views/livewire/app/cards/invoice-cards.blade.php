<div class="grid gap-3 md:grid-cols-2 lg:gap-5 lg:grid-cols-4">
    <x-app.card
        label="Total Invoice Amount"
        :hasLink="false"
        >
        <x-slot:data>
            <x-currency /> {{ number_format($total_invoice_amount, 2) }}
        </x-slot:data>
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-piggy-bank"><path d="M19 5c-1.5 0-2.8 1.4-3 2-3.5-1.5-11-.3-11 5 0 1.8 0 3 2 4.5V20h4v-2h3v2h4v-4c1-.5 1.7-1 2-2h2v-4h-2c0-1-.5-1.5-1-2V5z"/><path d="M2 9v1c0 1.1.9 2 2 2h1"/><path d="M16 11h.01"/></svg>
        </x-slot:icon>
    </x-app.card>
    <x-app.card
        label="Total Paid Amount"
        :hasLink="false"
        >
        <x-slot:data>
            <x-currency /> {{ number_format($total_paid_amount, 2) }}
        </x-slot:data>
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-coins"><circle cx="8" cy="8" r="6"/><path d="M18.09 10.37A6 6 0 1 1 10.34 18"/><path d="M7 6h1v4"/><path d="m16.71 13.88.7.71-2.82 2.82"/></svg>
        </x-slot:icon>
    </x-app.card>
    <x-app.card
        label="Total Balance"
        :hasLink="false"
        >
        <x-slot:data>
            <x-currency /> {{ number_format($total_balance, 2) }}
        </x-slot:data>
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hand-coins"><path d="M11 15h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 17"/><path d="m7 21 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9"/><path d="m2 16 6 6"/><circle cx="16" cy="9" r="2.9"/><circle cx="6" cy="5" r="3"/></svg>
        </x-slot:icon>
    </x-app.card>
    <x-app.card
        :data="$overdue_invoices"
        label="Overdue Invoices"
        :hasLink="false"
        >
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
        </x-slot:icon>
    </x-app.card>
</div>