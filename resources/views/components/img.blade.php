@props([
    'src' => null,
    'position' => 'center',
    'alt' => 'Amazing view',
    'aspect' => 'video',
    'zoomable' => false,
])

@php
    // Check if provided image source exists
    if ($src) {
        Storage::exists('public/' . $src) ?: $src = null;
    }
    $id = uniqid();
@endphp

@if ($src)
    <div class="relative group">
        <img id="{{ $id }}" src="{{ asset('storage/' . $src) }}" alt="{{ $alt }}" {{ $attributes->merge(['class' => 'object-cover object-center rounded-md overflow-hidden aspect-' . $aspect]) }}>

        @if ($zoomable)
            <button type="button" x-on:click="$dispatch('open-modal', 'zoom-{{ $id }}')" class="absolute inset-0 inline-grid w-full h-full transition-all duration-200 ease-in-out rounded-md opacity-0 bg-zinc-800/50 group-hover:opacity-100 place-items-center hover:cursor-pointer">
                <div class="space-y-5">
                    <svg class="mx-auto text-white" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-image-icon lucide-image"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>

                    <span class="text-xs font-semibold text-center text-white">View Image</span>
                </div>
            </button>

            <x-modal.full name='zoom-{{ $id }}' maxWidth='screen-xl'>
                <div class="p-5"><img src="{{ asset('storage/' . $src) }}" alt="{{ $alt }}" class="'object-cover object-center rounded-md overflow-hidden aspect-video w-full"></div>
            </x-modal.full>
        @endif
    </div>
@else
    <div {{ $attributes->merge(['class' => 'grid border border-dashed rounded-md text-slate-200 border-slate-200 bg-slate-50 place-items-center aspect-' . $aspect]) }}>
        <svg class="w-1/4 max-w-12" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-image-off"><line x1="2" x2="22" y1="2" y2="22"/><path d="M10.41 10.41a2 2 0 1 1-2.83-2.83"/><line x1="13.5" x2="6" y1="13.5" y2="21"/><line x1="18" x2="21" y1="12" y2="15"/><path d="M3.59 3.59A1.99 1.99 0 0 0 3 5v14a2 2 0 0 0 2 2h14c.55 0 1.052-.22 1.41-.59"/><path d="M21 15V5a2 2 0 0 0-2-2H9"/></svg>
    </div>
@endif