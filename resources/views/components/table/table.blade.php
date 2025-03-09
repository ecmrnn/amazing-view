@props(['headerCount' => 0])

<div class="overflow-auto border rounded-md border-slate-200">
    <div class="min-w-[600px] divide-y divide-slate-200">
        {{-- Header --}}
        <div class="grid px-5 py-3 text-sm font-semibold bg-slate-50 text-zinc-800/60 grid-cols-{{ $headerCount }}">
            {{ $headers }}
        </div>
        
        {{ $slot }}
    </div>
</div>