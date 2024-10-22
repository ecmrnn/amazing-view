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
                    <span>Check-in Guests</span>
                </div>
            </x-primary-button>
            {{-- @can('create guest')
            @endcan --}}
        </div>
    </x-slot:header>

    <div class="p-3 space-y-5 bg-white rounded-lg sm:p-5">
        <hgroup>
            <h2 class="text-lg font-semibold">Guests</h2>
            <p class="max-w-sm text-xs">Manage all your guests using the table below.</p>
        </hgroup>
    
        {{-- Guest Table --}}
        <livewire:tables.guest-table />
    </div>

    {{-- Modal for confirming reservation --}}
    <x-modal.full name="show-check-in-modal" maxWidth="sm">
        <div x-data="{ checked: false }">
            <section class="p-5 space-y-5 bg-white">
                <hgroup>
                    <h2 class="font-semibold capitalize">Check-in Guest</h2>
                    <p class="max-w-sm text-sm">Enter the <strong class="text-blue-500">Reservation ID</strong> of the guest you want to check-in.</p>
                </hgroup>

                {{-- <div class="px-3 py-2 border rounded-md">
                    <x-form.input-checkbox x-model="checked" id="checked" label="The information I have provided is true and correct." />
                </div> --}}

                <div class="space-y-1">
                    {{-- <x-form.input-label id="note">Add a note &lpar;optional&rpar;</x-form.input-label> --}}
                    <x-form.input-text label="Reservation ID" id="note" class="w-full" />
                    <x-form.input-error field="note" />
                </div>
                
                <div class="flex gap-1">
                    <x-secondary-button x-on:click="show = false">Cancel</x-secondary-button>
                    <x-primary-button x-on:click="toast('Success', { type: 'success', description: 'Yay, Reservation Created!' })">Check-in Guest</x-primary-button>
                </div>
            </section>
        </div>
    </x-modal.full> 
</x-app-layout>