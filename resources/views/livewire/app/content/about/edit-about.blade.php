<div>
    <section class="space-y-5">
        <!-- Heading and Subheading -->
        <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
            <div class="flex items-start justify-between">
                <hgroup>
                    <h3 class="font-semibold">Heading &amp; Subheading</h3>
                    <p class="text-xs">Update your hero section here</p>
                </hgroup>

                <button class="text-xs font-semibold text-blue-500" type="button" x-on:click="$dispatch('open-modal', 'edit-hero-modal')">Edit Hero</button>
            </div>

            <div class="grid grid-cols-1 gap-3 md:grid-cols-2 md:gap-5">
                <x-img-lg src="{{ asset('storage/' . $medias['about_hero_image']) }}" />

                <div class="grid p-5 border rounded-md border-slate-200 place-items-center">
                    <div>
                        <p class="font-semibold text-center">{!! $contents['about_heading'] !!}</p>
                        <p class="text-sm text-center">{!! $contents['about_subheading'] !!}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- History -->
        <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
            <div class="flex items-start justify-between">
                <hgroup>
                    <h3 class="font-semibold">Brief History</h3>
                    <p class="text-xs">Update your history here</p>
                </hgroup>

                <button class="text-xs font-semibold text-blue-500" type="button" x-on:click="$dispatch('open-modal', 'edit-history-modal')">Edit History</button>
            </div>

            <div class="grid grid-cols-1 gap-3 md:grid-cols-2 md:gap-5">
                <x-img-lg src="{{ asset('storage/' . $medias['about_history_image']) }}" />

                <p class="text-sm text-justify indent-16">{!! $contents['about_history'] !!}</p>
            </div>
        </div>

        <!-- Milestones -->
        <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
            <div class="flex items-start justify-between">
                <hgroup>
                    <h3 class="font-semibold">Milestones</h3>
                    <p class="text-xs">Manage your milestones</p>
                </hgroup>

                <button class="text-xs font-semibold text-blue-500" type="button" x-on:click="$dispatch('open-modal', 'create-milestone-modal')">Add Milestone</button>
            </div>

            <div class="grid gap-5 sm:grid-cols-2 md:grid-cols-3">
                @foreach ($milestones as $milestone)
                    <div wire:key="milestone-{{ $milestone->id }}" class="relative p-5 space-y-5 border rounded-md border-slate-200">
                        <div class="space-y-5">
                            @if (!empty($milestone->milestone_image))
                                <x-img-lg src="{{ asset('storage/' . $milestone->milestone_image) }}" class="w-full" />
                            @else
                                <x-img-lg src="https://picsum.photos/id/{{ $milestone->id + 100 }}/200/300?grayscale" class="w-full" />
                            @endif

                            <div>
                                <h4 class="mb-5 font-semibold">{{ $milestone->title }}</h4>
                                <p class="text-sm font-semibold">Achieved on: {{ date_format(date_create($milestone->date_achieved), 'F j, Y') }}</p>
                                <p class="text-sm text-justify line-clamp-3">{{ $milestone->description }}</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <x-status type="milestone" :status="$milestone->status" />
                            
                            <div class="flex justify-center gap-1">
                                <x-tooltip text="Edit" dir="bottom">
                                    <x-icon-button x-ref="content" type="button" x-on:click="$dispatch('open-modal', 'edit-milestone-modal-{{ $milestone->id }}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>
                                    </x-icon-button>
                                </x-tooltip>
                                @if ($milestone->status == App\Enums\MilestoneStatus::ACTIVE->value)
                                    <x-tooltip text="Deactivate" dir="top">
                                        <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'deactivate-milestone-{{ $milestone->id }}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ban"><circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/></svg>
                                        </x-icon-button>
                                    </x-tooltip>
                                @else
                                    <x-tooltip text="Activate" dir="top">
                                        <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'activate-milestone-{{ $milestone->id }}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check"><path d="M20 6 9 17l-5-5"/></svg>
                                        </x-icon-button>
                                    </x-tooltip>    
                                @endif
                                <x-tooltip text="Delete" dir="bottom">
                                    <x-icon-button x-ref="content" type="button" x-on:click="$dispatch('open-modal', 'delete-milestone-modal-{{ $milestone->id }}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                    </x-icon-button>
                                </x-tooltip>
                            </div>
                        </div>

                        <x-modal.full name="edit-milestone-modal-{{ $milestone->id }}" maxWidth="sm">
                            <livewire:app.content.about.edit-milestone wire:key="edit-{{ $milestone->id }}" :milestone="$milestone" />
                        </x-modal.full>

                        <x-modal.full name='deactivate-milestone-{{ $milestone->id }}' maxWidth='sm'>
                            <livewire:app.content.about.deactivate-milestone wire:key="deactivate-{{ $milestone->id }}" :milestone="$milestone" />
                        </x-modal.full>

                        <x-modal.full name='activate-milestone-{{ $milestone->id }}' maxWidth='sm'>
                            <livewire:app.content.about.activate-milestone wire:key="activate-{{ $milestone->id }}" :milestone="$milestone" />
                        </x-modal.full>

                        <x-modal.full name="delete-milestone-modal-{{ $milestone->id }}" maxWidth="sm">
                            <livewire:app.content.about.delete-milestone wire:key="delete-{{ $milestone->id }}" :milestone="$milestone" />
                        </x-modal.full>
                    </div>
                @endforeach
            </div>
        </div>
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
                    style="background-image: url({{ asset('storage/' . $medias['about_hero_image']) }});
                    background-size: cover;
                    background-position: center;">
                    <section class="relative z-10 w-3/4 py-20 mx-auto space-y-3 text-white rounded-md">
                        <p class="mx-auto font-bold text-center text-md">{!! $contents['about_heading'] !!}</p>
                        <p class="mx-auto text-xs text-center">{!! $contents['about_subheading'] !!}</p>
                        <div class="flex justify-center gap-1">
                            <x-secondary-button type="button" class="text-xs">...</x-secondary-button>
                            <x-primary-button type="button" class="text-xs">...</x-primary-button>
                        </div>
                    </section>
                </div>
                
                <section class="w-3/4 py-20 mx-auto space-y-3 rounded-md">
                    <hgroup>
                        <svg class="mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-book-open-text"><path d="M12 7v14"/><path d="M16 12h2"/><path d="M16 8h2"/><path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/><path d="M6 12h2"/><path d="M6 8h2"/></svg>
                        <p class="text-xs font-bold text-center">Amazing View Mountain Resort</p>
                        <p class="text-xs text-center">A gilmpse of our story</p>
                    </hgroup>

                    <div class="grid grid-cols-2 gap-3">
                        <p class="text-justify text-xxs indent-8">
                            {!! $contents['about_history'] !!}
                        </p>

                        <x-img-lg src="{{ asset('storage/' . $medias['about_history_image']) }}" />
                    </div>
                </section>

                <section class="w-3/4 py-20 mx-auto space-y-3 rounded-md">
                    <hgroup>
                        <svg class="mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trophy"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                        <h3 class="text-xs font-bold text-center">Our Milestones</h3>
                        <p class="text-xs text-center">Recent awards and achievements of our resort</p>
                    </hgroup>

                    <div class="grid grid-cols-3 gap-3">
                        @foreach ($milestones->filter(function ($milestone) { return $milestone->status == App\Enums\MilestoneStatus::ACTIVE->value; }) as $milestone)
                            <div class="space-y-2">
                                <x-img-lg src="{{ asset('storage/' . $milestone->milestone_image) }}" />

                                <h4 class="text-sm font-semibold line-clamp-1">{{ $milestone->title }}</h4>
                                <div>
                                    <p class="text-xs">Achieved on: {{ date_format(date_create($milestone->date_achieved), 'F j, Y' ) }}</p>
                                    <p class="text-xs text-justify line-clamp-2">{{ $milestone->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="w-3/4 py-20 mx-auto space-y-3 rounded-md">
                    <hgroup>
                        <svg class="mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pinned"><path d="M18 8c0 3.613-3.869 7.429-5.393 8.795a1 1 0 0 1-1.214 0C9.87 15.429 6 11.613 6 8a6 6 0 0 1 12 0"/><circle cx="12" cy="8" r="2"/><path d="M8.714 14h-3.71a1 1 0 0 0-.948.683l-2.004 6A1 1 0 0 0 3 22h18a1 1 0 0 0 .948-1.316l-2-6a1 1 0 0 0-.949-.684h-3.712"/></svg>
                        <h3 class="text-xs font-bold text-center">Our Location</h3>
                        <p class="text-xs text-center">Waze your way to Amazing View!</p>
                    </hgroup>

                    <div>
                        <x-laravel-map
                            :initialMarkers="$map['marker']"
                            :options="$map['option']"
                        />
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

    <x-modal.full name="create-milestone-modal" maxWidth="sm">
        <livewire:app.content.about.create-milestone />
    </x-modal.full> 

    <x-modal.full name="edit-history-modal" maxWidth="sm">
        <livewire:app.content.about.edit-history />
    </x-modal.full> 
</form>