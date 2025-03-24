@props(['width' => 'w-12'])

@if (Storage::exists('public/' . $settings['site_logo'] ?? '') && $settings['site_logo'])
    <a href="{{ route('guest.home') }}" wire:navigate {{ $attributes->merge(['class' => 'inline-block overflow-hidden border border-transparent rounded-lg focus:outline-none focus:ring-0 focus:border-blue-600 ' . $width]) }} wire:navigate>
        <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo" class="object-cover object-center aspect-square">
    </a>
@else
    <a href="{{ route('guest.home') }}" wire:navigate  {{ $attributes->merge(['class' => 'aspect-square grid place-items-center border border-slate-200 bg-slate-50 rounded-full inline-block text-slate-200 ' . $width]) }}>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-image-off"><line x1="2" x2="22" y1="2" y2="22"/><path d="M10.41 10.41a2 2 0 1 1-2.83-2.83"/><line x1="13.5" x2="6" y1="13.5" y2="21"/><line x1="18" x2="21" y1="12" y2="15"/><path d="M3.59 3.59A1.99 1.99 0 0 0 3 5v14a2 2 0 0 0 2 2h14c.55 0 1.052-.22 1.41-.59"/><path d="M21 15V5a2 2 0 0 0-2-2H9"/></svg>
    </a>
@endif