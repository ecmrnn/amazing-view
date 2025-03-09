<div>
    <section class="space-y-5">
        <div class="flex items-start justify-between gap-5 p-5 bg-white border rounded-lg border-slate-200">
            <x-note>Any update made on this page will be automatically applied to the website.</x-note>

            <x-status type="page" :status="$page->status" />
        </div>
        
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
                <x-img-lg src="{{ asset('storage/' . $home_hero_image) }}" />

                <div class="grid p-5 border rounded-md border-slate-200 place-items-center">
                    <div>
                        <p class="font-semibold text-center">{!! $heading !!}</p>
                        <p class="text-sm text-center">{!! $subheading !!}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Services -->
        <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
            <div class="flex items-start justify-between">
                <hgroup>
                    <h3 class="font-semibold">Featured Services</h3>
                    <p class="text-xs">Manage your featured services</p>
                </hgroup>

                <button type="button" class="text-xs font-semibold text-blue-500" type="button" x-on:click="$dispatch('open-modal', 'create-service-modal')">Add Service</button>
            </div>

            <div x-data="{ feature_count: @entangle('feature_count') }" class="space-y-1">
                @foreach ($featured_services as $featured_service)
                    <div key="{{ $featured_service->id }}"
                        @class(['relative p-3 border border-slate-200 rounded-md', 'bg-slate-100' => $featured_service->status == app\Models\FeaturedService::STATUS_INACTIVE])
                        >
                        <div class="flex flex-col gap-3 md:flex-row">
                            <x-img-lg src="{{ asset('storage/' . $featured_service->image) }}" class="w-full md:max-w-[150px]" />

                            <div>
                                <h4 class="text-sm font-semibold">{{ $featured_service->title }}</h4>
                                <p class="max-w-sm text-xs">{{ $featured_service->description }}</p>
                            </div>
                        </div>

                        <div class="absolute flex gap-1 top-5 right-5 md:top-3 md:right-3">
                            <x-tooltip text="Edit" dir="bottom">
                                <x-icon-button x-ref="content" type="button" x-on:click="$dispatch('open-modal', 'edit-service-modal-{{ $featured_service->id }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>
                                </x-icon-button>
                            </x-tooltip>

                            @if ($featured_service->status == app\Models\FeaturedService::STATUS_ACTIVE)
                                <x-tooltip text="Deactivate" dir="bottom">
                                    <x-icon-button x-bind:disabled="feature_count <= 3" x-ref="content" type="button" x-on:click="$dispatch('open-modal', 'deactivate-service-modal-{{ $featured_service->id }}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ban"><circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/></svg>
                                    </x-icon-button>
                                </x-tooltip>
                            @else
                                <x-tooltip text="Activate" dir="bottom">
                                    <x-icon-button x-ref="content" type="button" x-on:click="$dispatch('open-modal', 'activate-service-modal-{{ $featured_service->id }}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check"><path d="M20 6 9 17l-5-5"/></svg>
                                    </x-icon-button>
                                </x-tooltip>
                            @endif
                            
                            <x-tooltip text="Delete" dir="bottom">
                                <x-icon-button  x-bind:disabled="feature_count <= 3" x-ref="content" type="button" x-on:click="$dispatch('open-modal', 'delete-service-modal-{{ $featured_service->id }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                </x-icon-button>
                            </x-tooltip>
                        </div>

                        <x-modal.full name="edit-service-modal-{{ $featured_service->id }}" maxWidth="sm">
                            <livewire:app.content.home.edit-service wire:key="edit-{{ $featured_service->id }}" :service="$featured_service" />
                        </x-modal.full>

                        <x-modal.full name="deactivate-service-modal-{{ $featured_service->id }}" maxWidth="sm">
                            <livewire:app.content.home.deactivate-service wire:key="deactivate-{{ $featured_service->id }}" :service="$featured_service" />
                        </x-modal.full>

                        <x-modal.full name="activate-service-modal-{{ $featured_service->id }}" maxWidth="sm">
                            <livewire:app.content.home.activate-service wire:key="activate-{{ $featured_service->id }}" :service="$featured_service" />
                        </x-modal.full>

                        <x-modal.full name="delete-service-modal-{{ $featured_service->id }}" maxWidth="sm">
                            <livewire:app.content.home.delete-service wire:key="delete-{{ $featured_service->id }}" :service="$featured_service" />
                        </x-modal.full>
                    </div>
                @endforeach
            </div>
            @if ($feature_count <= 3)
                <x-note>
                    <p class="pr-2">Minimum of three (3) services must be featured</p>
                </x-note>
            @endif
        </div>

        <!-- History -->
        <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
            <div class="flex items-start justify-between">
                <hgroup>
                    <h3 class="font-semibold">Brief History</h3>
                    <p class="text-xs">Update your history here</p>
                </hgroup>

                <button type="button" class="text-xs font-semibold text-blue-500" type="button" x-on:click="$dispatch('open-modal', 'edit-history-modal')">Edit History</button>
            </div>

            <div class="grid grid-cols-1 gap-3 md:grid-cols-2 md:gap-5">
                <x-img-lg src="{{ asset('storage/' . $history_image) }}" />

                <p class="text-sm line-clamp-5 overflow-clip h-min">{!! $history !!}</p>
            </div>
        </div>
    </section>

    <!-- Visuals -->
    <section class="hidden space-y-5">
        <section class="overflow-y-scroll border rounded-lg border-slate-200 aspect-video">
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
                    style="background-image: url({{ asset('storage/' . $home_hero_image) }});
                    background-size: cover;
                    background-position: center;">
                    <section class="relative z-10 w-3/4 py-20 mx-auto space-y-3 text-white rounded-md">
                        <p class="mx-auto font-bold text-center text-md">{!! $heading !!}</p>
                        <p class="max-w-xs mx-auto text-xs text-center">{!! $subheading !!}</p>
                        <div class="flex justify-center gap-1">
                            <x-secondary-button type="button" class="text-xs">...</x-secondary-button>
                            <x-primary-button type="button" class="text-xs">...</x-primary-button>
                        </div>
                    </section>
                </div>
                
                <section class="w-3/4 py-20 mx-auto space-y-3 rounded-md">
                    <hgroup>
                        <p class="text-xs font-bold">Featured Services</p>
                        <p class="max-w-xs text-xs">Experience out featured services</p>
                    </hgroup>
                    <div class="grid grid-cols-3 gap-1">
                        @foreach ($active_featured_services as $key => $featured_service)
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
                <section class="w-3/4 py-20 mx-auto space-y-3 rounded-md">
                    <hgroup>
                        <p class="text-xs font-bold">Amazing View Mountain Resort</p>
                        <p class="max-w-xs text-xs">Book your dream getaway!</p>
                    </hgroup>
                    <div class="grid grid-cols-2 gap-3">
                        <x-img-lg src="{{ asset('storage/' . $history_image) }}" />
                        <div class="space-y-3">
                            <h3 class="text-xs font-bold">Be amaze by our story!</h3>
                            <p class="text-xs text-justify sm:text-left sm:max-w-sm line-clamp-3">
                                {!! $history !!}
                            </p>
                            <x-primary-button class="text-xs">...</x-primary-button>
                        </div>
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
    </section>

    <!-- Modals -->
    <x-modal.full name="create-service-modal" maxWidth="sm">
        <livewire:app.content.home.create-service />
    </x-modal.full> 

    <x-modal.full name="edit-history-modal" maxWidth="sm">
        <livewire:app.content.about.edit-history />
    </x-modal.full> 

    <x-modal.full name="edit-hero-modal" maxWidth="sm">
        <livewire:app.content.edit-hero page="{{ strtolower($page->title) }}" />
    </x-modal.full> 
</form>