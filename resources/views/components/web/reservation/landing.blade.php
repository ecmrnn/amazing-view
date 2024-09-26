<div class="max-w-screen-xl pt-40 pb-20 mx-auto space-y-5">
    <x-h1>
        Book a Room
    </x-h1>

    <p>
        Where every stay becomes a story, <br />
        welcome to your perfect escape!
    </p>

    <div class="flex gap-1">
        <a class="inline-block" href="#form">
            <x-primary-button>Get Started!</x-primary-button>
        </a>
        <a class="inline-block" href="{{ route('guest.search') }}" wire:navigate>
            <x-secondary-button>Find my Reservation</x-secondary-button>
        </a>
    </div>
</div>