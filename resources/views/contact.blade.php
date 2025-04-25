<x-guest-layout>
    <x-slot:hero>
        <div class="grid h-full max-w-screen-xl mx-auto rounded-lg place-items-center">
            <div class="flex flex-col items-center justify-between w-full p-5 text-center text-white md:items-start md:flex-row md:text-left">
                <div class="space-y-5">
                    <x-h1>
                        {!! nl2br(e($contents['contact_heading'] ?? '')) !!}
                    </x-h1>
                    <p class="max-w-sm mx-auto md:mx-0">
                        {!! $contents['contact_subheading'] ?? '' !!}
                        <ul class="mx-auto space-y-2 w-max md:mx-0">
                            @foreach ($contact_details as $contact)
                                <li class="flex items-center gap-3 px-3 py-2 tracking-wider rounded-lg trac bg-white/25 backdrop-blur-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-smartphone"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/></svg>
                                    {{ substr($contact->value, 0, 4) . ' ' . substr($contact->value, 4, 3) . ' ' . substr($contact->value, 7) }}
                                </li>
                            @endforeach
                        </ul>
                    </p>
                </div>

                <div class="hidden md:block">
                    <livewire:email-form />
                </div>
            </div>

            <div class="absolute w-full h-full rounded-lg -z-10 before:contents[''] before:w-full before:h-full before:bg-black/35 before:absolute before:top-0 before:left-0 overflow-hidden"
                style="background-image: url({{ asset('storage/' . $medias['contact_hero_image']) }});
                background-size: cover;
                background-position: center;">
            </div>
        </div>
    </x-slot:hero>
</x-guest-layout>