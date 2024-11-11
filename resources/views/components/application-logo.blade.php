<a href="{{ route('guest.home') }}" {{ $attributes->merge(['class' => 'block overflow-hidden border border-transparent rounded-lg focus:outline-none focus:ring-0 focus:border-blue-600']) }} wire:navigate>
    <img src="{{ asset('storage/global/application-logo.png') }}" alt="Alternative Logo" class="w-12 aspect-square">
</a>