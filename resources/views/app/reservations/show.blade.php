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
        <x-modal.full name="show-downpayment-modal" maxWidth="sm">
            <livewire:app.reservation.confirm-reservation :reservation="$reservation" />
        </x-modal.full> 

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
    @endpush
</x-app-layout>  