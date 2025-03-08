<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between gap-3">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">
                    {{ __('Reservations') }}
                </h1>
                <p class="text-xs">Manage your reservations here</p>
            </hgroup>
        </div>
    </x-slot:header>

    <livewire:app.reservation.show-reservation :reservation="$reservation" />

    @push('modals')
        {{-- Proof of image modal --}}
        <livewire:app.reservation.confirm-reservation :reservation="$reservation" />

        {{-- Modal for canceling reservation --}}
        <x-modal.full name="show-cancel-reservation" maxWidth="sm">
            <div x-data="{ reason: 'guest' }">
                <livewire:app.reservation.cancel-reservation :reservation="$reservation" />
            </div>
        </x-modal.full> 

        <x-modal.full name='show-checkout-reservation' maxWidth='sm'>
            <livewire:app.guest.check-out-guest :reservation="$reservation" />
        </x-modal.full>

        <x-modal.full name='show-payment-reservation' maxWidth='sm'>
            <livewire:app.invoice.create-payment :invoice="$reservation->invoice" />
        </x-modal.full>

        <x-modal.full name='show-reactivate-modal' maxWidth='sm'>
            <livewire:app.reservation.reactivate-reservation :reservation="$reservation" />
        </x-modal.full>

        <x-modal.full name='show-delete-reservation-modal' maxWidth='sm'>
            <livewire:app.reservation.delete-reservation :reservation="$reservation" />
        </x-modal.full>

        <x-modal.full name='show-send-email-modal' maxWidth='sm'>
            <livewire:app.reservation.send-reservation-email :reservation="$reservation" />
        </x-modal.full>

        <x-modal.full name="show-check-in-modal" maxWidth="sm">
            <livewire:app.guest.check-in-guest :reservation="$reservation" />
        </x-modal.full> 
    @endpush
</x-app-layout>  