<div>
    <section class="space-y-5">
        <!-- Hero Section -->
        <livewire:app.content.edit-hero page="{{ strtolower($page->title) }}" />

        <!-- Contact Details -->
        <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
            <div class="flex items-start justify-between">
                <hgroup>
                    <h3 class="font-semibold">Contact Details</h3>
                    <p class="text-xs">Update your contact details here</p>
                </hgroup>

                <button class="text-xs font-semibold text-blue-500" type="button" x-on:click="$dispatch('open-modal', 'create-contact-modal')">Add Contact</button>
            </div>

            <div class="grid gap-5 md:grid-cols-3 sm:grid-cols-2">
                @foreach ($contact_details as $contact_detail)
                    <div class="flex items-center justify-between p-5 border rounded-md border-slate-200">
                        <p class="text-sm">{{ substr($contact_detail->value, 0, 4) . ' ' . substr($contact_detail->value, 4, 3) . ' ' . substr($contact_detail->value, 7) }}</p>

                        <div class="flex gap-1">
                            <x-tooltip text="Edit" dir="bottom">
                                <x-icon-button x-ref="content" type="button" x-on:click="$dispatch('open-modal', 'edit-contact-modal-{{ $contact_detail->id }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>
                                </x-icon-button>
                            </x-tooltip>
                            <x-tooltip text="Delete" dir="bottom">
                                <x-icon-button x-ref="content" type="button" x-on:click="$dispatch('open-modal', 'delete-contact-modal-{{ $contact_detail->id }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                </x-icon-button>
                            </x-tooltip>
                        </div>

                        <x-modal.full name="edit-contact-modal-{{ $contact_detail->id }}" maxWidth="sm">
                            <livewire:app.content.contact.edit-contact-details wire:key="edit-{{ $contact_detail->id }}" :contact_detail="$contact_detail" />
                        </x-modal.full>                             
                        <x-modal.full  name="delete-contact-modal-{{ $contact_detail->id }}" maxWidth="sm">
                            <livewire:app.content.contact.delete-contact wire:key="delete-{{ $contact_detail->id }}" :contact_detail="$contact_detail" />
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
                    style="background-image: url({{ asset('storage/' . $medias['contact_hero_image']) }});
                    background-size: cover;
                    background-position: center;">
                    <section class="relative z-10 grid w-3/4 py-20 mx-auto text-white rounded-md place-items-center">
                        <div class="flex justify-between w-full px-2">
                            <div class="space-y-3">
                                <p class="font-bold text-md">{!! $contents['contact_heading'] !!}</p>
                                <p class="max-w-xs text-xs">{!! $contents['contact_subheading'] !!}</p>
                                
                                <ul class="mx-auto space-y-1 w-max md:mx-0">
                                    @foreach ($contact_details as $contact)
                                        <li class="flex items-center gap-3 p-2 text-xs tracking-wider rounded-md bg-white/25 backdrop-blur-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-smartphone"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/></svg>
                                            {{ $contact->value }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="w-32 p-3 space-y-3 bg-white rounded-md text-zinc-800">
                                <h2 class="font-bold text-md">Send Email</h2>

                                <div class="space-y-1">
                                    <div class="p-2 rounded-md bg-slate-100"></div>
                                    <div class="p-2 rounded-md bg-slate-100"></div>
                                </div>

                                <p class="text-xs font-semibold">Message</p>
                                <div class="p-2 rounded-md bg-slate-100"></div>
                                <div class="flex justify-end">
                                    <x-primary-button type="button" class="ml-auto text-xs">...</x-primary-button>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                
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

    <x-modal.full name="create-contact-modal" maxWidth="sm">
        <livewire:app.content.contact.create-contact />
    </x-modal.full> 
</form>