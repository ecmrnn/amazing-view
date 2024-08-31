<x-guest-layout>
    {{-- Landing Page --}}
     <div class="px-5 bg-slate-50">
        <x-web.reservation.landing />
    </div>

    <div class="min-h-screen px-5">
        <section class="max-w-screen-xl py-5 mx-auto space-y-5" id="form">
            {{-- Reservation steps --}}
            <div class="flex flex-col justify-between gap-2 md:flex-row md:items-center md:gap-5 ">
                <x-web.reservation.steps :step="1" :currentStep="1" icon="bed" name="Reservation Details" />
                <div class="h-[1px] hidden md:block border-b border-dashed w-full"></div>
                <x-web.reservation.steps :step="2" :currentStep="1" icon="face" name="Guest Details" />
                <div class="h-[1px] hidden md:block border-b border-dashed w-full"></div>
                <x-web.reservation.steps :step="3" :currentStep="1" icon="receipt" name="Payment" />
            </div>

            <article x-data="{ 
                    min: new Date(),
                    date_in: '',
                    date_out: '',
                    adult_count: 1,
                    children_count: 0,
                    formatDate(date) {
                        let options = {year: 'numeric', month: 'long', day: 'numeric'};
                        return new Date(date).toLocaleDateString('en-US', options)
                    },
                }"
                class="grid gap-5 md:grid-cols-3">
                {{-- Forms --}}
                <form method="" action="" class="md:col-span-2">
                    {{-- Step 1: Reservation Details --}}
                    <x-form.form-section class="grid lg:grid-cols-2">
                        <x-form.form-header step="1" title="Reservation Date &amp; Guest Count" class="lg:col-span-2" />

                        <x-form.form-body class="grid lg:grid-cols-2 lg:col-span-2">
                            <div class="p-5 border-b border-dashed lg:border-r lg:border-b-0">
                                <div class="grid grid-cols-2 gap-2" x-effect="date_in == '' ? date_out = '' : ''">
                                    <x-form.input-group>
                                        <x-form.input-label for="date_in">Check-in Date</x-form.input-label>
                                        <x-form.input-date
                                            x-model="date_in"
                                            x-bind:min="`${min.getFullYear()}-${String(min.getMonth() + 1).padStart(2, '0')}-${String(min.getDate()).padStart(2, '0')}`"
                                            id="date_in" class="block w-full" />
                                    </x-form.input-group>
                                    <x-form.input-group>
                                        <x-form.input-label for="date_out">Check-Out Date</x-form.input-label>
                                        <x-form.input-date
                                            x-bind:disabled="date_in == ''"
                                            x-bind:value="date_in == '' ? null : date_out"
                                            x-bind:min="date_in"
                                            x-model="date_out"
                                            id="date_out" class="block w-full" />
                                    </x-form.input-group>
                                </div>
                            </div>
                            
                            <div class="p-5">
                                <div class="grid grid-cols-2 gap-2">
                                    <x-form.input-group>
                                        <x-form.input-label for="adult_count">Number of Adults</x-form.input-label>
                                        <x-form.input-number x-model="adult_count" min="1" id="adult_count" class="block w-full" />
                                    </x-form.input-group>
                                    <x-form.input-group>
                                        <x-form.input-label for="children_count">Number of Children</x-form.input-label>
                                        <x-form.input-number x-model="children_count" id="children_count" class="block w-full" />
                                    </x-form.input-group>
                                </div>
                            </div>
                        </x-form.form-body>
                    </x-form.form-section>

                    <x-line-vertical />

                    <x-form.form-section>
                        <x-form.form-header class="form-header" step="2" title="Select a Room" />
                        
                        <x-form.form-body class="p-5">
                            <p class="text-sm">Lorem ipsum dolor sit amet consectetur adipisicing elit. Sint, iusto!</p>
                            <h3 class="my-5 text-lg font-semibold">Room Categories</h3>
                        
                            <div class="grid gap-2 p-3 border rounded-lg sm:grid-cols-2 md:grid-cols-1">
                                {{-- Room Categories --}}
                                <x-web.reservation.step-1.room-category />
                                <x-web.reservation.step-1.room-category />
                                <x-web.reservation.step-1.room-category />
                                <x-web.reservation.step-1.room-category />
                            </div>
                        </x-form.form-body>
                    </x-form.form-section>
                </form>
        
                {{-- Summary --}}
                <aside class="self-start p-5 pt-3 space-y-5 border rounded-lg ">
                    <h2 class="text-lg font-semibold">Reservation Summary</h2>

                    <div>
                        <p class="font-semibold">Date and Time</p>
                        <div class="grid grid-cols-2 gap-2 mt-3 md:grid-cols-1 lg:grid-cols-2">
                            <div class="px-3 py-2 border rounded-lg">
                                <p class="text-xs text-zinc-800/50">Check-in</p>
                                <p x-text="date_in === '' ? 'Select a Date' : formatDate(date_in)" class="font-semibold line-clamp-1"></p>
                                <p class="text-xs text-zinc-800/50">From: 2:00 PM</p>
                            </div>
                            <div class="px-3 py-2 border rounded-lg">
                                <p class="text-xs text-zinc-800/50">Check-out</p>
                                <p x-text="date_out === '' ? 'Select a Date' : formatDate(date_out)" class="font-semibold line-clamp-1"></p>
                                <p class="text-xs text-zinc-800/50">From: 12:00 PM</p>
                            </div>
                        </div>
                    </div>
                </aside>
            </article>
        </section>
    </div>
</x-guest-layout>