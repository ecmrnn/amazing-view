@props(['width' => 'w-12'])

<a href="{{ route('guest.home') }}" {{ $attributes->merge(['class' => 'inline-block overflow-hidden border border-transparent rounded-lg focus:outline-none focus:ring-0 focus:border-blue-600 aspect-square ' . $width]) }} wire:navigate>
    <img src="{{ asset('storage/global/application-logo.png') }}" alt="Alternative Logo">
</a>