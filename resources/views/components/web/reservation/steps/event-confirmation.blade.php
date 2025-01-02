{{-- Loader --}}
<div class="fixed top-0 left-0 z-50 w-screen h-screen bg-white place-items-center" wire:loading.delay.long wire:target='submit'>
    <div class="grid h-screen place-items-center">
        <div>
            <p class="text-2xl font-bold text-center">Loading, please wait</p>
            <p class="mb-4 text-xs font-semibold text-center">Confirming your amazing reservation~</p>
            <svg class="mx-auto animate-spin" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-loader-circle"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
        </div>
    </div>
</div>

<div class="p-5 space-y-5 bg-white rounded-lg shadow-sm">
    {{-- Step Header --}}
    <div class="flex items-start justify-between">
        <div class="flex flex-col items-start gap-3 sm:gap-5 sm:flex-row">
            <div class="grid w-full text-white bg-blue-500 rounded-md aspect-square max-w-20 place-items-center">
                <p class="text-5xl font-bold">2</p>
            </div>
            <div>
                <p class="text-lg font-bold">Confirmation</p>
                <p class="max-w-sm text-sm leading-tight">Kindly double check and confirm your information as this will be used to process your reservation.</p>
            </div>
        </div>

        <button :class="reservation_type != null ? 'scale-100' : 'scale-0'" type="button" x-on:click="$dispatch('open-modal', 'reset-reservation-modal')" class="flex items-center gap-2 text-xs font-semibold text-red-500 transition-all duration-200 ease-in-out w-max">
            <p>Reset</p>
            <svg class="text-red-500" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
        </button>
    </div>

    <div class="grid grid-cols-1 overflow-hidden text-sm border rounded-md shadow-sm sm:grid-cols-3 border-slate-200">
        <h3 class="px-5 py-3 text-lg font-semibold border-b sm:col-span-3 bg-slate-50 border-slate-200">Event Details</h3>

        <p class="px-5 pt-3 font-semibold align-top border-r border-dashed border-slate-200">Event Name</p>
        <p class="px-5 pt-3 sm:col-span-2">{{ $event_name }}</p>

        <p class="px-5 pt-3 font-semibold align-top border-r border-dashed border-slate-200">Event Description</p>
        <p class="px-5 pt-3 sm:col-span-2">{{ $event_description }}</p>

        <p class="px-5 py-3 font-semibold align-top border-r border-dashed border-slate-200">Reservation Date</p>
        <p class="px-5 py-3 sm:col-span-2">{{ date_format(date_create($reservation_date), 'F j, Y') }}</p>
    </div>

    <div class="grid grid-cols-1 overflow-hidden text-sm border rounded-md shadow-sm sm:grid-cols-3 border-slate-200">
        <h3 class="px-5 py-3 text-lg font-semibold border-b sm:col-span-3 bg-slate-50 border-slate-200">Contact Details</h3>

        <p class="px-5 pt-3 font-semibold align-top border-r border-dashed border-slate-200">Your Name</p>
        <p class="px-5 pt-3 capitalize sm:col-span-2">{{ $first_name . ' ' . $last_name }}</p>

        <p class="px-5 py-3 font-semibold align-top border-r border-dashed border-slate-200">Email Address</p>
        <p class="px-5 py-3 sm:col-span-2">{{ $email }}</p>
    </div>

    <div class="flex gap-1">
        <x-secondary-button wire:click="goToStep(1)">
            Back
        </x-secondary-button>
        <x-primary-button type='button' x-on:click="() => { $nextTick(() => { $refs.form.scrollIntoView({ behavior: 'smooth' }); }); }; $dispatch('open-modal', 'show-reservation-confirmation')">
            Submit
        </x-primary-button>
    </div>

    <x-modal.full name="show-reservation-confirmation" maxWidth="lg">
        <div x-data="{ toc: false }">
            <hgroup class="p-5">
                <h2 class="text-lg font-semibold">Reservation Confirmation</h2>
                <p class="max-w-sm text-sm">Confirm that the reservation details entered are correct.</p>
            </hgroup>
            
            <section class="p-5 space-y-3 bg-slate-100/50">
                <x-form.input-checkbox x-model="toc" id="toc" label="I, {{ ucwords(strtolower($first_name)) . ' ' . ucwords(strtolower($last_name)) }}, ensure that the information I provided is true and correct. I also give consent to Amazing View Mountain Resort to collect and manage my data." />
            </section>

            <footer x-show="toc" class="flex justify-end gap-3 p-5 bg-white border-t">
                <x-secondary-button x-on:click="show = false">
                    Cancel
                </x-secondary-button>
                <x-primary-button x-on:click="$wire.store(); show = false;">
                    Submit Reservation
                </x-primary-button>
            </footer>
        </div>
    </x-modal.full> 
</div>
