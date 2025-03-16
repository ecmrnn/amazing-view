@props(['width' => 'w-12'])

<a href="{{ route('guest.home') }}" wire:navigate {{ $attributes->merge(['class' => 'inline-block overflow-hidden border border-transparent rounded-lg focus:outline-none focus:ring-0 focus:border-blue-600 ' . $width]) }} wire:navigate>
    <img src="{{ asset('storage/' . Arr::get($settings, 'site_logo', 'global/application-logo.png')) }}" alt="Alternative Logo" class="object-cover object-center aspect-square">
</a>