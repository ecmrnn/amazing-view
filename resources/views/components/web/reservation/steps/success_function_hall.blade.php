<div class="grid gap-5 place-items-center">
    <div>
        <h2 class="text-3xl font-semibold text-center text-blue-500">Success!</h2>
        <p class="max-w-xs mx-auto text-sm text-center">Kindly wait for our representative to contact you with regards to your reservation.</p>
    </div>

    {{-- Action --}}
    <a href="{{ route('guest.function-hall') }}" wire:navigate>
        <x-primary-button type="button">Start Again</x-primary-button>
    </a>
</div>