<div class="grid gap-5 place-items-center">
    <h2 class="font-semibold text-blue-500 text-1xl">Success!</h2>
    <h3 class="text-6xl font-semibold text-blue-500">{{ $reservation_rid }}</h3>
    <p class="max-w-sm text-xs text-center">Take note of your Reservation ID, you may use this to view or update your
        reservation.</p>

    {{-- Action --}}
    <div class="flex gap-1">
        <a href="{{ route('reservation') }}" wire:navigate>
            <x-secondary-button type="button">Start Again</x-secondary-button>
        </a>
        <a href="{{ route('search', ['rid' => $reservation_rid]) }}" wire:navigate>
            <x-primary-button type="button">View Reservation</x-primary-button>
        </a>
    </div>
</div>
