<div {{ $attributes->merge(['class' => 'flex items-center border-b bg-slate-50']) }}>
    <div class="m-2 rounded-full w-[30px] aspect-square grid place-items-center bg-gradient-to-r border border-blue-600 from-blue-500 to-blue-600">
        <span class="text-xs font-semibold text-white">{{ $step }}</span>
    </div>

    <h2 class="font-semibold text-md">{{ html_entity_decode($title) }}</h2>
</div>