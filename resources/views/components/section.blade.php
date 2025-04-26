<section {{ $attributes->merge(['class' => 'py-10 lg:py-20 px-5']) }}>
    <div class="max-w-screen-xl mx-auto space-y-10">
        <hgroup>
            <div class="relative flex justify-center p-3 mx-auto mb-2 text-blue-500 rounded-full bg-blue-50 w-max">
                {{ $icon }}
                <svg class="absolute -bottom-2 fill-blue-500" width="16" height="16" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" id="wave" class="icon glyph"><path d="M16.5,14a4.06,4.06,0,0,1-2.92-1.25,2,2,0,0,0-3.17,0,4,4,0,0,1-5.83,0A2.1,2.1,0,0,0,3,12a1,1,0,0,1,0-2,4,4,0,0,1,2.91,1.25,2,2,0,0,0,3.17,0,4,4,0,0,1,5.83,0,2,2,0,0,0,3.17,0A4.06,4.06,0,0,1,21,10a1,1,0,0,1,0,2,2.12,2.12,0,0,0-1.59.75A4,4,0,0,1,16.5,14Z"></path></svg>
            </div>
            <h2 class="text-3xl font-semibold text-center text-blue-500">{{ $heading }}</h2>
            <p class="text-center">{{ $subheading }}</p>
        </hgroup>
        
        {{ $slot }}
    </div>
</section>