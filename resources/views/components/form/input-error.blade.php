@props(['field' => ''])

@error($field)
    <div class="flex items-center gap-3 px-3 py-2 text-xs font-semibold text-red-500 border border-red-500 rounded-md bg-red-50 max-w-max">
        <svg class="flex-shrink-0" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-alert"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
        <p {{ $attributes->merge(['class' => 'text-xs font-semibold text-red-500']) }}>{{ $message }}</p>
    </div>
@enderror