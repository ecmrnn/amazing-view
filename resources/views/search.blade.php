<x-guest-layout>
    {{-- Landing Page --}}
    <x-slot:hero>
        <div class="py-20">
            <div class="max-w-screen-xl mx-auto space-y-5">
                <hgroup class="space-y-5 text-center">
                    <x-h1>
                        Already have a reservation?
                    </x-h1>
                    <p class="max-w-sm mx-auto">Enter your reservation ID below to view the status of your reservation.</p>
                </hgroup>
        
                <livewire:guest.find-reservation />
            </div>
        </div>
    </x-slot:hero>
</x-guest-layout>
