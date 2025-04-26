<x-app-layout>
    <x-slot:header>
        <hgroup>
            <h1 class="text-xl font-bold leading-tight text-gray-800">Dashboard</h1>
            <p class="text-xs">Keep track of your records</p>
        </hgroup>
    </x-slot:header>

    {{-- Cards --}}
    <div class="grid grid-cols-2 gap-3 lg:gap-5 lg:grid-cols-4">
        <x-app.card
            :data="$guest_in"
            label="Guest in"
            href="app.guests.index"
            >
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-round"><path d="M18 21a8 8 0 0 0-16 0"/><circle cx="10" cy="8" r="5"/><path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"/></svg>
            </x-slot:icon>
        </x-app.card>
        <x-app.card
            :data="$pending_reservations"
            label="Pending Reservations"
            href="app.reservations.index"
            >
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
            </x-slot:icon>
        </x-app.card>
        <x-app.card
            :data="$available_rooms"
            label="Available Rooms"
            href="app.rooms.index"
            >
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-door-open"><path d="M13 4h3a2 2 0 0 1 2 2v14"/><path d="M2 20h3"/><path d="M13 20h9"/><path d="M10 12v.01"/><path d="M13 4.562v16.157a1 1 0 0 1-1.242.97L5 20V5.562a2 2 0 0 1 1.515-1.94l4-1A2 2 0 0 1 13 4.561Z"/></svg>
            </x-slot:icon>
        </x-app.card>
        <x-app.card
            :data="$incoming_guests"
            label="Incoming Guests"
            href="app.guests.index"
            >
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </x-slot:icon>
        </x-app.card>
    </div>

    {{-- Quick actions --}}
    <div class="space-y-5">
        <div class="flex items-center gap-5">
            <x-icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap-icon lucide-zap"><path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/></svg>
            </x-icon>
            
            <hgroup>
                <h2 class='font-semibold'>Quick Actions</h2>
                <p class='text-xs'>What do you need to do?</p>
            </hgroup>
        </div>

        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-4">
            <x-quick-action href="{{ route('app.reservations.create') }}">
                <div class="flex items-center gap-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-door-open-icon lucide-door-open"><path d="M13 4h3a2 2 0 0 1 2 2v14"/><path d="M2 20h3"/><path d="M13 20h9"/><path d="M10 12v.01"/><path d="M13 4.562v16.157a1 1 0 0 1-1.242.97L5 20V5.562a2 2 0 0 1 1.515-1.94l4-1A2 2 0 0 1 13 4.561Z"/></svg>
        
                    <div>
                        <h3 class="font-semibold">Create a Reservation</h3>
                        <p class="text-xs">Book a room here</p>
                    </div>
                </div>
            </x-quick-action>
        
            <x-quick-action x-on:click="$dispatch('open-modal', 'find-reservation-modal')">
                <div class="flex items-center gap-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        
                    <div>
                        <h3 class="font-semibold">Find a Reservation</h3>
                        <p class="text-xs">Request the Reservation ID of the guest</p>
                    </div>
                </div>
           </x-quick-action>
           
           <x-quick-action x-on:click="$dispatch('open-modal', 'find-room-modal')">
                <div class="flex items-center gap-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-door-closed-icon lucide-door-closed"><path d="M18 20V6a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v14"/><path d="M2 20h20"/><path d="M14 12v.01"/></svg>
        
                    <div>
                        <h3 class="font-semibold">Find a Room</h3>
                        <p class="text-xs">Find available room for a guest</p>
                    </div>
                </div>
           </x-quick-action>
           
           <x-quick-action x-on:click="$dispatch('open-modal', 'check-in-modal')">
                <div class="flex items-center gap-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-receipt-text-icon lucide-receipt-text"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M14 8H8"/><path d="M16 12H8"/><path d="M13 16H8"/></svg>
        
                    <div>
                        <h3 class="font-semibold">Check-in Guest</h3>
                        <p class="text-xs">Request the Reservation ID of the guest</p>
                    </div>
                </div>
           </x-quick-action>

            <x-modal.full name='find-reservation-modal' maxWidth='sm'>
                <livewire:app.reservation.find-reservation />
            </x-modal.full>
            
            <x-modal.full name='find-room-modal' maxWidth='sm'>
                <livewire:app.room.find-room />
            </x-modal.full>

            <x-modal.full name='check-in-modal' maxWidth='sm'>
                <livewire:app.guest.check-in-guest />
            </x-modal.full>
        </div>
    </div>

    {{-- Pending Reservations --}}
    <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
        <div>
            <h2 class="text-lg font-semibold">Pending Reservations</h2>
            <p class="max-w-sm text-xs">The table below are the lists of your pending reservations</p>
        </div>

        {{-- Reservation Table --}}
        @if ($pending_reservations > 0)
            <livewire:tables.dashboard-reservation-table />
        @else
            <div class="font-semibold text-center border rounded-md border-slate-200s">
                <x-table-no-data.reservations />
            </div>
        @endif
    </div>
</x-app-layout>
