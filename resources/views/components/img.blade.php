@props([
    'src' => null,
    'position' => 'center',
    'alt' => 'Amazing view'
])

@php
    // Check if provided image source exists
    if ($src) {
        Storage::exists('public/' . $src) ?: $src = null;
    }
@endphp

@if ($src)
    <img src="{{ asset('storage/' . $src) }}" alt="{{ $alt }}" {{ $attributes->merge(['class' => 'object-cover object-center rounded-md overflow-hidden aspect-video']) }}>
@else
    <div {{ $attributes->merge(['class' => 'grid border border-dashed rounded-md text-slate-200 aspect-video border-slate-200 bg-slate-50 place-items-center']) }}>
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-image-off"><line x1="2" x2="22" y1="2" y2="22"/><path d="M10.41 10.41a2 2 0 1 1-2.83-2.83"/><line x1="13.5" x2="6" y1="13.5" y2="21"/><line x1="18" x2="21" y1="12" y2="15"/><path d="M3.59 3.59A1.99 1.99 0 0 0 3 5v14a2 2 0 0 0 2 2h14c.55 0 1.052-.22 1.41-.59"/><path d="M21 15V5a2 2 0 0 0-2-2H9"/></svg>
    </div>
@endif