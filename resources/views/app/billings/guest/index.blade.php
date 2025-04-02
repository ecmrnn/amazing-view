<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between w-full">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">Billings</h1>
                <p class="text-xs">View your billings here</p>
            </hgroup>
        </div>
    </x-slot:header>

    <div class="max-w-screen-lg p-5 mx-auto space-y-5 bg-white border rounded-lg border-slate-200">
        <hgroup>
            <h2 class='font-semibold'>Billings</h2>
            <p class='text-xs'>View all your billings here</p>
        </hgroup>

        @if ($user->reservations->count() > 0)
            <livewire:tables.guest-reservation-table :user="$user" />
        @else
            <div class="py-10 space-y-3 font-semibold text-center text-blue-800 border border-blue-500 border-dashed rounded-md bg-blue-50">
                <svg class="mx-auto text-blue-200" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-notebook"><path d="M2 6h4"/><path d="M2 10h4"/><path d="M2 14h4"/><path d="M2 18h4"/><rect width="16" height="20" x="4" y="2" rx="2"/><path d="M16 2v20"/></svg>
            
                <p class="text-sm">No reservations found!</p>
            
                <a class="inline-block text-xs" href="{{ route('guest.reservation') }}" wire:navigate.hover>
                    <x-primary-button>
                        Create Reservation
                    </x-primary-button>
                </a>
            </div>
        @endif
    </div>
</x-app-layout>