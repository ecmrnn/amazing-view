<div>
    <section class="space-y-5">
        <!-- Hero Section -->
        <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
            <div class="flex items-start justify-between">
                <hgroup>
                    <h3 class="font-semibold">Hero Section</h3>
                    <p class="text-xs">Update your hero section here</p>
                </hgroup>

                <button type="button" class="text-xs font-semibold text-blue-500" x-on:click="$dispatch('open-modal', 'edit-hero-modal')">Edit Hero</button>
            </div>

            <div class="grid grid-cols-1 gap-3 md:grid-cols-2 md:gap-5">
                <x-img-lg src="{{ asset('storage/' . $medias['home_hero_image']) }}" />

                <div class="grid p-5 border rounded-md border-slate-200 place-items-center">
                    <div>
                        <p class="font-semibold text-center">{!! $contents['home_heading'] !!}</p>
                        <p class="text-sm text-center">{!! $contents['home_subheading'] !!}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Featured Services --}}
        <livewire:app.content.home.show-featured-services />

        {{-- Testimonials --}}
        <livewire:app.content.home.show-testimonials />
    </section>

    <!-- Modals -->
    <x-modal.full name='show-preview-modal' maxWidth='screen-xl'>
        <section class="hidden space-y-5 overflow-y-scroll xl:block aspect-video">
            <div class="p-5 space-y-1 min-w-[780px]">
                <header class="flex justify-between w-3/4 p-2 mx-auto rounded-md">
                    <!-- Logo -->
                    <div class="p-3 rounded-md bg-slate-200 aspect-square"></div>
                    <!-- Links -->
                    <div class="flex gap-2">
                        <div class="p-3 max-w-[100px] flex-grow rounded-md bg-slate-200"></div>
                        <div class="p-3 max-w-[100px] flex-grow rounded-md bg-slate-200"></div>
                        <div class="p-3 max-w-[100px] flex-grow rounded-md bg-slate-200"></div>
                        <div class="p-3 max-w-[100px] flex-grow rounded-md bg-slate-200"></div>
                    </div>
                </header>
                
                <div class="relative w-full rounded-lg before:contents[''] before:w-full before:h-full before:bg-black/35 before:absolute before:top-0 before:left-0 overflow-hidden"
                    style="background-image: url({{ asset('storage/' . $medias['home_hero_image']) }});
                    background-size: cover;
                    background-position: center;">
                    <section class="relative z-10 w-3/4 py-20 mx-auto space-y-3 text-white rounded-md">
                        <p class="mx-auto font-bold text-center text-md">{!! $contents['home_heading'] !!}</p>
                        <p class="max-w-xs mx-auto text-xs text-center">{!! $contents['home_subheading'] !!}</p>
                        <div class="flex justify-center gap-1">
                            <x-secondary-button type="button" class="text-xs">...</x-secondary-button>
                            <x-primary-button type="button" class="text-xs">...</x-primary-button>
                        </div>
                    </section>
                </div>
                
                <section class="w-3/4 py-20 mx-auto space-y-3 rounded-md">
                    <hgroup>
                        <svg class="mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles"><path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/><path d="M20 3v4"/><path d="M22 5h-4"/><path d="M4 17v2"/><path d="M5 18H3"/></svg>
                        <p class="text-xs font-bold text-center">Featured Services</p>
                        <p class="text-xs text-center">Experience out featured services</p>
                    </hgroup>

                    <div class="grid grid-cols-3 gap-5">
                        @foreach ($featured_services as $key => $featured_service)
                            <div class="space-y-1">
                                <x-img-lg src="{{ asset('storage/' . $featured_service->image) }}" />
                                
                                <hgroup>
                                    <span class="text-xxs">{{ sprintf("%02d", $key + 1) }}</span>
                                    <h3 class="text-sm font-semibold">{{ $featured_service->title }}</h3>
                                </hgroup>
        
                                <p class="text-xs text-justify line-clamp-3">{{ $featured_service->description }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="w-full py-10 mx-auto space-y-3 text-white bg-blue-800 rounded-md">
                    <hgroup>
                        <svg class="mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-book-open"><path d="M12 7v14"/><path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/></svg>
                        <p class="mx-auto font-bold text-center text-md">Amazing View Mountain Resort</p>
                        <p class="max-w-xs mx-auto text-xs text-center">Book your dream getaway!</p>
                    </hgroup>
                    
                    <div class="flex items-center justify-center gap-5">
                        <x-secondary-button class="text-xs">...</x-secondary-button>
                        <p>|</p>
                        <x-secondary-button class="text-xs">...</x-secondary-button>
                    </div>
                </section>

                <section class="w-3/4 py-20 mx-auto space-y-3 rounded-md">
                    <hgroup>
                        <svg class="mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle-heart"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/><path d="M15.8 9.2a2.5 2.5 0 0 0-3.5 0l-.3.4-.35-.3a2.42 2.42 0 1 0-3.2 3.6l3.6 3.5 3.6-3.5c1.2-1.2 1.1-2.7.2-3.7"/></svg>
                        <p class="text-xs font-bold text-center">Testimonials</p>
                        <p class="text-xs text-center">Read feedbacks from our previous guests!</p>
                    </hgroup>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 place-items-start">
                        {{-- 2 Chunks --}}
                        @forelse ($testimonials->chunk(ceil($testimonials->count() / 2)) as $testimonial_chunks)
                            <div class="space-y-4 lg:hidden">
                                @foreach ($testimonial_chunks as $testimonial)
                                    <div key="{{ $testimonial->id }}" class="p-4 border rounded-lg border-zinc-200">
                                        <p class="text-sm font-semibold text-center">{{ $testimonial->name }}</p>
                                        <div class="flex justify-center gap-1 mt-2">
                                            @for ($rating = 0; $rating < 5; $rating++)
                                                @if ($rating < $testimonial->rating)
                                                    <div class="text-amber-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/></svg>
                                                    </div>
                                                @else
                                                    <div class="text-zinc-800/50">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/></svg>
                                                    </div>
                                                @endif
                                            @endfor
                                        </div>
                                        <p class="mt-4 text-xs text-justify line-clamp-2 indent-4">{{ $testimonial->testimonial }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @empty
                            <div class="font-semibold text-center opacity-50">
                                No Testimonials Yet
                            </div>
                        @endforelse
            
                        {{-- 3 Chunks --}}
                        @forelse ($testimonials->chunk(ceil($testimonials->count() / 3)) as $testimonial_chunks)
                            <div class="hidden space-y-4 lg:block">
                                @foreach ($testimonial_chunks as $testimonial)
                                    <div key="{{ $testimonial->id }}" class="p-4 border rounded-lg border-zinc-200">
                                        <p class="text-sm font-semibold text-center">{{ $testimonial->name }}</p>
                                        <div class="flex justify-center gap-1 mt-2">
                                            @for ($rating = 0; $rating < 5; $rating++)
                                                @if ($rating < $testimonial->rating)
                                                    <div class="text-amber-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/></svg>
                                                    </div>
                                                @else
                                                    <div class="text-zinc-800/50">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/></svg>
                                                    </div>
                                                @endif
                                            @endfor
                                        </div>
                                        <p class="mt-4 text-xs text-justify line-clamp-2 indent-4">{{ $testimonial->testimonial }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @empty
                            <div class="font-semibold text-center opacity-50">
                                No Testimonials Yet
                            </div>
                        @endforelse
                    </div>
                </section>
                
                <footer class="w-full py-10 mx-auto space-y-3 text-white rounded-md bg-blue-950">
                    <div class="w-3/4 gap-10 mx-auto space-y-10 md:space-y-0 md:grid md:grid-cols-3 lg:grid-cols-4">
                        <div class="pr-5 space-y-5 border-dashed md:col-span-3 lg:col-span-1 lg:border-r border-white/50">
                            <h2 class="text-xs font-semibold">
                                <span>Amazing View</span><br />
                                <span>Mountain Resort</span>
                            </h2>
                            <p class="text-xxs">
                                Where every stay becomes a story,
                                welcome to your perfect escape!
                            </p>
                            <x-primary-button type="button" class="text-xs">...</x-primary-button>
                        </div>
                        <div class="space-y-3">
                            <h3 class="text-xs font-semibold">Navigate through our site</h3>
                            <div class="space-y-3">
                                <x-footer-link class="text-xxs" href="/">Home</x-footer-link>
                                <x-footer-link class="text-xxs" href="/rooms">Rooms</x-footer-link>
                                <x-footer-link class="text-xxs" href="/about">About</x-footer-link>
                                <x-footer-link class="text-xxs" href="/contact">Contact</x-footer-link>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <h3 class="text-xs font-semibold">Stay connected with us!</h3>
        
                            <div class="space-y-3">
                                <x-footer-link class="text-xxs" href="https://facebook.com" target="_blank">Facebook</x-footer-link>
                                <x-footer-link class="text-xxs" href="/">Instagram</x-footer-link>
                                <x-footer-link class="text-xxs" href="/">Twitter</x-footer-link>
                                <x-footer-link class="text-xxs" href="/">Youtube</x-footer-link>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <h3 class="text-xs font-semibold">Enjoy more of our content</h3>
        
                            <div class="space-y-3">
                                <x-footer-link class="text-xxs" href="/">Blogs</x-footer-link>
                                <x-footer-link class="text-xxs" href="/">Events</x-footer-link>
                                <x-footer-link class="text-xxs" href="/">Testimonials</x-footer-link>
                                <x-footer-link class="text-xxs" href="/">Announcements</x-footer-link>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </section>
    </x-modal.full>
    
    <x-modal.full name="create-service-modal" maxWidth="sm">
        <livewire:app.content.home.create-service />
    </x-modal.full> 
</form>