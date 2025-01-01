{{-- Loader --}}
<div class="fixed top-0 left-0 z-50 w-screen h-screen bg-white place-items-center" wire:loading.delay.long wire:target='submit'>
    <div class="grid h-screen place-items-center">
        <div>
            <p class="text-2xl font-bold text-center">Loading, please wait</p>
            <p class="mb-4 text-xs font-semibold text-center">Processing your amazing reservation~</p>
            <svg class="mx-auto animate-spin" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-loader-circle"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
        </div>
    </div>
</div>

<div class="p-5 space-y-5 bg-white border rounded-lg shadow-sm border-slate-200">
    {{-- Step Header --}}
    <div class="flex items-start justify-between">
        <div class="flex flex-col items-start gap-3 sm:gap-5 sm:flex-row">
            <div class="grid w-full text-white bg-blue-500 rounded-md aspect-square max-w-20 place-items-center">
                <p class="text-5xl font-bold">1</p>
            </div>
            <div>
                <p class="text-lg font-bold">Event Details</p>
                <p class="max-w-sm text-sm leading-tight">Enter the event or occupation to be celebrated, number of guests, date of event, as well as your contact details!</p>
            </div>
        </div>

        <button :class="reservation_type != null ? 'scale-100' : 'scale-0'" type="button" x-on:click="$dispatch('open-modal', 'reset-reservation-modal')" class="flex items-center gap-2 text-xs font-semibold text-red-500 transition-all duration-200 ease-in-out w-max">
            <p>Reset</p>
            <svg class="text-red-500" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
        </button>
    </div>

    <x-form.form-section>
        <x-form.form-header title='Event Details' />
    
        <x-form.form-body>
            <div class="p-5 pt-0 space-y-3">
                <x-form.input-group>
                    <x-form.input-label for='event_name'>Event Name and Description</x-form.input-label>
                    <x-form.input-text id="event_name" name="event_name" label="Event Name" class="w-full sm:w-max" />
                    <x-form.input-error field="event_name" />
                </x-form.input-group>
                
                <x-form.textarea id="event_description" name="event_description" label="Event Description" class="w-full">
                    Describe the event or occasion you want to celebrate here!
                </x-form.textarea>

                <x-form.input-group>
                    <x-form.input-label for='reservation_date'>Reservation Date</x-form.input-label>
                    <x-form.input-date id="reservation_date" name="reservation_date" label="Reservation Date" min="{{ $min_date }}" />
                    <x-form.input-error field="reservation_date" />
                </x-form.input-group>
            </div>
        </x-form.form-body>
    </x-form.form-section>
    
    <x-form.form-section>
        <x-form.form-header title='Contact Details' />
    
        <x-form.form-body>
            <div class="p-5 pt-0 space-y-3">
                <div class="grid items-end grid-cols-1 gap-3 sm:grid-cols-3">
                    <x-form.input-group>
                        <x-form.input-label for="first_name">Enter your name here</x-form.input-label>
                        <x-form.input-text id="first_name" name="first_name" label="First Name" />
                        <x-form.input-error field="first_name" />
                    </x-form.input-group>

                    <x-form.input-group>
                        <x-form.input-text id="last_name" name="last_name" label="Last Name" />
                        <x-form.input-error field="last_name" />
                    </x-form.input-group>

                    <x-form.input-group>
                        <x-form.input-label for='email'>Email Address</x-form.input-label>
                        <x-form.input-text type="email" id="email" name="email" label="Email" class="w-full" />
                        <x-form.input-error field="email" />
                    </x-form.input-group>
                </div>
            </div>
        </x-form.form-body>
    </x-form.form-section>

    <x-primary-button>Continue</x-primary-button>
</div>
