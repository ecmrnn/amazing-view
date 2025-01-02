<div class="max-w-screen-xl py-10 mx-auto space-y-5">
    <div class="flex items-start mb-10 lg:gap-5">
        <x-web.reservation.steps step="1" currentStep="{{ $step }}" icon="bed" name="Event Details" />
        <x-web.reservation.steps step="2" currentStep="{{ $step }}" icon="face" name="Confirmation" />
    </div>

    <section class="grid grid-cols-1 gap-5 md:grid-cols-3">
        <form wire:submit='submit' class="md:col-span-2">
            @switch($step)
                @case(1)
                    @include('components.web.reservation.steps.event-details')
                    @break
                @case(2)
                    @include('components.web.reservation.steps.event-confirmation')
                    @break
                {{-- @default --}}
            @endswitch
        </form>

        <aside class="self-start p-5 space-y-3 bg-white rounded-lg shadow-sm">
            <div>
                <h3 class="text-lg font-semibold">Reminders!</h3>
                <p class="text-sm">A few reminders when reserving a function hall:</p>
            </div>
            
            <ul class="space-y-3 text-sm list-inside">
                <li class="flex gap-3 p-3 rounded-md bg-slate-50">
                    <svg class="mt-1 shrink-0" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-chart-gantt"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 8h7"/><path d="M8 12h6"/><path d="M11 16h5"/></svg>
                    <p>Kindly provide as much detail as possible about your event to help us better understand your needs and ensure a smooth planning process.</p>
                </li>
                <li class="flex gap-3 p-3 rounded-md bg-slate-50">
                    <svg class="mt-1 shrink-0" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone-call"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/><path d="M14.05 2a9 9 0 0 1 8 7.94"/><path d="M14.05 6A5 5 0 0 1 18 10"/></svg>
                    <p>Please ensure your contact information is accurate. One of our representatives will reach out within 24-48 hours to assist you with your reservation.</p>
                </li>
                <li class="flex gap-3 p-3 rounded-md bg-slate-50">
                    <svg class="mt-1 shrink-0" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-clock"><path d="M21 7.5V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h3.5"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h5"/><path d="M17.5 17.5 16 16.3V14"/><circle cx="16" cy="16" r="6"/></svg>
                    <p>Reservations are subject to availability. Submitting this form does not guarantee booking confirmation until processed by one of our representative.</p>
                </li>
            </ul>
        </aside>
    </section>

    <x-modal.full name='reset-reservation-modal' maxWidth='sm'>
        <div class="p-5 space-y-5">
            <h3 class="text-lg font-semibold">Reset Reservation</hjson</h3>
            <p class="text-sm">Are you sure you want to reset your reservation?</p>
    
            <div class="flex justify-end gap-1 mt-5">
                <x-secondary-button x-on:click="show = false">Cancel</x-secondary-button>
                <x-danger-button x-on:click="show = false; $wire.resetReservation()">Reset</x-danger-button>
            </div>
        </div>
    </x-modal.full>

    {{-- Loader for reset reservation --}}
    <div class="fixed top-0 left-0 z-50 w-screen h-screen bg-white place-items-center" wire:loading.delay.long wire:target='resetReservation'>
        <div class="grid h-screen place-items-center">
            <div>
                <p class="text-2xl font-bold text-center">Resetting Forms</p>
                <p class="mb-4 text-xs font-semibold text-center">Clearing calendars, please wait...</p>
                <svg class="mx-auto animate-spin" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-loader-circle"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
            </div>
        </div>
    </div>
</div>
