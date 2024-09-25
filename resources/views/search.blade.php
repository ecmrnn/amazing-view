<x-guest-layout>
    {{-- Landing Page --}}
    <div class="px-5">
        <div class="max-w-screen-xl pt-40 pb-20 mx-auto space-y-5">
            <hgroup class="space-y-5">
                <x-h1>
                    Already have a reservation?
                </x-h1>
                <p class="max-w-sm">Enter your reservation ID below to view the status of your reservation.</p>
            </hgroup>
        
            <livewire:guest.find-reservation />
        </div>
    </div>
</x-guest-layout>
