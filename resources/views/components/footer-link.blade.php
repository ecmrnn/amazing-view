<a {{ $attributes->merge(['class' => 'flex gap-5 items-center text-white/75 hover:text-white group']) }}>
    <x-line class="bg-white/75 group-hover:bg-white" />
    <span>{{ $slot }}</span>
</a>
