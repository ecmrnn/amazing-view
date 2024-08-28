<x-guest-layout>
    {{-- Landing Page --}}
    <div class="p-5 bg-slate-50">
        <x-web.reservation.landing />
    </div>

    <div class="px-5">
        <section class="py-5 space-y-5 max-w-screen-xl mx-auto" id="form">
            {{-- Reservation steps --}}
            <div class="flex flex-col justify-between
                md:flex-row md:items-center gap-2 md:gap-5 ">
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
                    formatDate(date) {
                        let options = {year: 'numeric', month: 'long', day: 'numeric'};
                        return new Date(date).toLocaleDateString('en-US', options)
                    },
                }"
                class="grid md:grid-cols-3 gap-5">
                {{-- Forms --}}
                <form method="" action="" class="md:col-span-2">
                    {{-- Step 1: Reservation Details --}}
                    <div class="rounded-lg overflow-hidden border grid lg:grid-cols-2">
                        <div class="p-5 py-2 lg:col-span-2 border-b bg-slate-100">
                            <h2 class="text-lg font-semibold">Reservation Date &amp; Guest Count</h2>
                        </div>
                        <div class="p-5 lg:border-r border-dashed">
                            <div class="grid grid-cols-2 gap-2" x-effect="date_in == '' ? date_out = '' : ''">
                                <div class="space-y-3">
                                    <x-form.input-label for="date_in">Check-in Date</x-form.input-label>
                                    <x-form.input-date x-model="date_in" id="date_in" class="block w-full" />
                                </div>
                                <div class="space-y-3">
                                    <x-form.input-label for="date_out">Check-Out Date</x-form.input-label>
                                    <x-form.input-date
                                        x-bind:disabled="date_in == ''"
                                        x-bind:value="date_in == '' ? null : date_out"
                                        x-model="date_out"
                                        id="date_out" class="block w-full" />
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
        
                {{-- Summary --}}
                <aside class="p-5 pt-3 space-y-5 rounded-lg border">
                    <h2 class="text-lg font-semibold">Reservation Summary</h2>
        
                    <div>
                        <p class="font-semibold">Date and Time</p>
                        <div class="grid grid-cols-2 gap-2 mt-3">
                            <div class="px-3 py-2 border rounded-lg">
                                <p class="text-zinc-800/50 text-xs">Check-in</p>
                                <p x-text="date_in === '' ? 'Select a Date' : formatDate(date_in)" class="font-semibold line-clamp-1"></p>
                                <p class="text-zinc-800/50 text-xs">From: 2:00 PM</p>
                            </div>
                            <div class="px-3 py-2 border rounded-lg">
                                <p class="text-zinc-800/50 text-xs">Check-out</p>
                                <p x-text="date_out === '' ? 'Select a Date' : formatDate(date_out)" class="font-semibold line-clamp-1"></p>
                                <p class="text-zinc-800/50 text-xs">From: 12:00 PM</p>
                            </div>
                        </div>
                    </div>
                </aside>
            </article>
        </section>
    </div>
</x-guest-layout>