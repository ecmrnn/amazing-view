<div {{ $attributes->merge() }}>
    <div class="flex items-center gap-3">
        <svg class="animate-spin" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-loader-circle"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
        
        <div class="text-xs font-semibold">{{ $slot }}</div>
    </div>
</div>