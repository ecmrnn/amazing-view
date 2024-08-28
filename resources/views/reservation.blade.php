<x-guest-layout>
    {{-- Landing Page --}}
    <div class="p-5 bg-slate-50">
        <x-web.reservation.landing />
    </div>

    <section class="py-5 space-y-5 max-w-screen-xl mx-auto" id="form">
        {{-- Reservation steps --}}
        <div class="flex items-center justify-between gap-5">
            <x-web.reservation.steps :step="1" :currentStep="1" icon="bed" name="Reservation Details" />
            <div class="h-[1px] border-b border-dashed w-full"></div>
            <x-web.reservation.steps :step="2" :currentStep="1" icon="face" name="Guest Details" />
            <div class="h-[1px] border-b border-dashed w-full"></div>
            <x-web.reservation.steps :step="3" :currentStep="1" icon="receipt" name="Payment" />
        </div>

        <article class="grid grid-cols-3 gap-5">
            {{-- Forms --}}
            <div class="col-span-2">
                {{-- Step 1: Reservation Details --}}
                <div class="rounded-lg border grid grid-cols-2">
                    <div class="p-5 border-r border-dashed">
                        <h2 class="text-lg font-semibold">Reservation Date</h2>
                        <x-form.input-date />
                    </div>
                </div>

            </div>
            
            {{-- Summary --}}
            <aside class="p-5 space-y-5 rounded-lg border">
                <h2 class="text-lg font-semibold">Reservation Summary</h2>
                
                <div>
                    <p class="font-semibold">Date and Time</p>

                    <div class="grid grid-cols-2 gap-2 mt-3">
                        <div class="px-3 py-2 border rounded-lg">
                            <p class="text-zinc-800/50 text-xs">Check-in</p>
                            <p class="font-semibold">01/10/2024</p>
                            <p class="text-zinc-800/50 text-xs">From: 2:00 PM</p>
                        </div>

                        <div class="px-3 py-2 border rounded-lg">
                            <p class="text-zinc-800/50 text-xs">Check-out</p>
                            <p class="font-semibold">01/11/2024</p>
                            <p class="text-zinc-800/50 text-xs">From: 12:00 PM</p>
                        </div>
                    </div>
                </div>
            </aside>
        </article>
    </section>
</x-guest-layout>