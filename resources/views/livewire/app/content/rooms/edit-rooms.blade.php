<div class="grid grid-cols-1 gap-5 bg-white xl:grid-cols-2">
    <section>
        <!-- Heading and Subheading -->
        <x-form.form-section>
            <x-form.form-header step="1" title="Hero Section" />

            <x-form.form-body>
                <div class="p-3 space-y-3 sm:p-5 sm:space-y-5">
                    <div class="flex items-start justify-between">
                        <hgroup>
                            <h3 class="font-semibold">Heading &amp; Subheading</h3>
                            <p class="text-xs">Update your hero section here</p>
                        </hgroup>

                        <x-primary-button class="text-xs" type="button" x-on:click="$dispatch('open-modal', 'edit-hero-modal')">Edit Hero</x-primary-button>
                    </div>

                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2 md:gap-5">
                        <x-img-lg src="{{ asset('storage/' . $rooms_hero_image) }}" />

                        <div class="grid p-5 border border-gray-300 rounded-md place-items-center">
                            <div>
                                <p class="font-semibold text-center">{!! $heading !!}</p>
                                <p class="text-sm text-center">{!! $subheading !!}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </x-form.form-body>
        </x-form.form-section>

        <x-line-vertical />

        <!-- Contact Details -->
        <x-form.form-section>
            <x-form.form-header step="2" title="Room Types" />

            <x-form.form-body>
                <div class="p-3 space-y-3 sm:p-5 sm:space-y-5">
                    <div class="flex items-start justify-between">
                        <hgroup>
                            <h3 class="font-semibold">Room Types</h3>
                            <p class="text-xs">Update your room types here</p>
                        </hgroup>

                        <a href="{{ route('app.rooms.create') }}" wire:navigate.hover>
                            <x-primary-button class="text-xs" type="button" x-on:click="$dispatch('open-modal', 'create-contact-modal')">Add Room</x-primary-button>
                        </a>
                    </div>

                    <div class="space-y-1">
                        @foreach ($room_types as $room_type)
                            <div x-data="{ room_count: @js($room_type->rooms->count())}" class="relative flex gap-3 p-5 border border-gray-300 rounded-md">
                                <x-img-lg src="{{ asset('storage/' . $room_type->image_1_path) }}" class="w-full md:max-w-[150px]" /> {{-- Fix --}}

                                <div>
                                    <h4 class="text-sm font-semibold">{{ $room_type->name }}</h4>
                                    <p class="max-w-sm text-xs">{{ $room_type->description }}</p>
                                </div>

                                <div class="absolute flex gap-1 top-5 right-5 md:top-3 md:right-3">
                                    <x-tooltip text="Edit" dir="bottom">
                                        <a href="{{ route('app.rooms.edit', ['room' => $room_type->id]) }}" wire.navigate.hover>
                                            <x-icon-button x-ref="content" type="button">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>
                                            </x-icon-button>
                                        </a>
                                    </x-tooltip>
                                    
                                    <x-tooltip text="Delete" dir="bottom">
                                        <x-icon-button x-bind:disabled="room_count > 0" x-ref="content" type="button" x-on:click="$dispatch('open-modal', 'delete-room-type-{{ $room_type->id }}-modal')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                        </x-icon-button>
                                    </x-tooltip>
                                </div>

                                {{-- Delete Modal --}}
                                <x-modal.full name='delete-room-type-{{ $room_type->id }}-modal' maxWidth='sm'>
                                    <livewire:app.room-type.delete-room-type :room_type="$room_type" />
                                </x-modal.full>
                            </div>
                        @endforeach
                    </div>
                </div>
            </x-form.form-body>
        </x-form.form-section>

        <x-line-vertical />

        <div class="mt-5">
            <!-- Status Change -->
            <section class="p-3 space-y-5 rounded-lg bg-red-200/50 sm:p-5">
                <hgroup>
                    <h3 class="font-semibold text-red-500">Change Page Visibility</h3>
                    <p class="max-w-sm text-xs">If you need to prevent users from accessing this page, click the button below.</p>
                </hgroup>
    
                <div>
                    <x-danger-button type="button" x-on:click="$dispatch('open-modal', 'disable-page-modal')">Hide this Page</x-danger-button>
                </div>
            </section>
        </div>
    </section>
    
    <!-- Visuals -->
    <section class="hidden space-y-5 xl:block">
        <section class="overflow-y-scroll border border-gray-300 rounded-lg aspect-video">
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
                    style="background-image: url({{ asset('storage/' . $rooms_hero_image) }});
                    background-size: cover;
                    background-position: center;">
                    <section class="relative z-10 grid w-3/4 py-20 mx-auto text-white rounded-md place-items-center">
                        <div class="flex flex-col justify-center gap-3">
                            <p class="font-bold text-center text-md">{!! $heading !!}</p>
                            <p class="max-w-xs text-xs text-center">{!! $subheading !!}</p>
                            <x-primary-button class="mx-auto text-xs">...</x-primary-button>
                        </div>
                    </section>
                </div>

                <section class="w-3/4 py-20 mx-auto space-y-3 rounded-md">
                    <hgroup>
                        <p class="text-xs font-bold">Amazing View Mountain Resort</p>
                        <p class="max-w-xs text-xs">A gilmpse of our story</p>
                    </hgroup>

                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($room_types as $room_type)
                            <div class="space-y-2">
                                <div>
                                    <x-img-lg src="{{ asset('storage/' . $room_type->image_1_path) }}" />
                                    
                                    <div class="grid grid-cols-3 gap-1 mt-1">
                                        <x-img-lg src="{{ asset('storage/' . $room_type->image_2_path) }}" />
                                        <x-img-lg src="{{ asset('storage/' . $room_type->image_3_path) }}" />
                                        <x-img-lg src="{{ asset('storage/' . $room_type->image_4_path) }}" />
                                    </div>
                                </div>
                                
                                <hgroup>
                                    <h3 class="text-sm font-semibold leading-none">{{ $room_type->name }}</h3>
                                    <span class="text-sm font-semibold leading-none"><x-currency />{{ number_format($room_type->max_rate, 2) }} / night</span>
                                </hgroup>
                                
                                <p class="text-xs text-justify indent-8 line-clamp-3">{{ $room_type->description }}</p>
                                
                                <x-primary-button type="button" class="text-xs">...</x-primary-button>
                            </div>
                        @endforeach
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

        <x-note>
            <p class="max-w-sm">Any update made on this page will be automatically applied to the website. You may view what your changes may look like using the preview above. <strong>Proceed with caution</strong>!</p>
        </x-note>
    </section>

    <!-- Modals -->
    <x-modal.full name="create-contact-modal" maxWidth="sm">
        <livewire:app.content.contact.create-contact />
    </x-modal.full> 

    <x-modal.full name="edit-hero-modal" maxWidth="sm">
        <livewire:app.content.edit-hero page="rooms" />
    </x-modal.full> 

    <x-modal.full name="disable-page-modal" maxWidth="sm">
        <livewire:app.content.disable-page :page="$page" />
    </x-modal.full> 
</form>
