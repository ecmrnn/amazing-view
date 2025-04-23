<section {{ $attributes->merge(['class' => 'py-10 lg:py-20 px-5']) }}>
    <div class="max-w-screen-xl mx-auto space-y-10">
        <hgroup>
            <h2 class="flex justify-center p-3 mx-auto mb-2 text-blue-800 rounded-full bg-blue-50 w-max">{{ $icon }}</h2>
            <h2 class="text-3xl font-semibold text-center text-blue-800">{{ $heading }}</h2>
            <p class="text-center">{{ $subheading }}</p>
        </hgroup>
        
        {{ $slot }}
    </div>
</section>