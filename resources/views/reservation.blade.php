<x-guest-layout>
    {{-- Landing Page --}}
    <div class="px-5 bg-slate-50">
        <x-web.reservation.landing />
    </div>

    <div 
        x-data="{ step: $store.step.count }"
        id="form" class="min-h-screen px-5 pb-20">
        <section x-ref="form">
            <livewire:guest.reservation-form />
        </section>
    </div>
</x-guest-layout>
