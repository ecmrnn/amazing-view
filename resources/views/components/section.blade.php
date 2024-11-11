<section {{ $attributes->merge(['class' => 'py-10 lg:py-20 px-5']) }}>
    <div class="max-w-screen-xl mx-auto space-y-10">
        <hgroup>
            <h2 class="text-3xl font-semibold">{{ $heading }}</h2>
            <p class="flex items-center gap-3"><x-line></x-line>{{ $subheading  }}</p>
        </hgroup>
        {{ $slot }}
    </div>
</section>