<x-modal.full name='settings-modal' maxWidth='sm'>
    <section class="p-5 space-y-5">
        <hgroup>
            <h2 class="text-lg font-bold">Settings</h2>
            <p class="text-xs">Configure other aspects of the system here</p>
        </hgroup>
        
        <article class="space-y-5">
            @role('admin')
                <div class="p-1 bg-white border rounded-md border-slate-200">
                    <x-app-nav-link :active="false" href="{{ route('app.buildings.index') }}" class="flex items-center gap-3 p-3">
                        <div class="pl-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hotel"><path d="M10 22v-6.57"/><path d="M12 11h.01"/><path d="M12 7h.01"/><path d="M14 15.43V22"/><path d="M15 16a5 5 0 0 0-6 0"/><path d="M16 11h.01"/><path d="M16 7h.01"/><path d="M8 11h.01"/><path d="M8 7h.01"/><rect x="4" y="2" width="16" height="20" rx="2"/></svg>
                        </div>
                        <div class="pl-1">
                            <p class="text-sm font-semibold">Buildings</p>
                            <p class="text-xs">Manage your buildings here</p>
                        </div>
                    </x-app-nav-link>
                    <x-app-nav-link :active="false" href="{{ route('app.amenity.index') }}" class="flex items-center gap-3 p-3">
                        <div class="pl-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-tv-minimal"><path d="M7 21h10"/><rect width="20" height="14" x="2" y="3" rx="2"/></svg>
                        </div>
                        <div class="pl-1">
                            <p class="text-sm font-semibold">Amenities</p>
                            <p class="text-xs">Manage your amenities here</p>
                        </div>
                    </x-app-nav-link>
                    <x-app-nav-link :active="false" href="{{ route('app.services.index') }}" class="flex items-center gap-3 p-3">
                        <div class="pl-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-coffee-icon lucide-coffee"><path d="M10 2v2"/><path d="M14 2v2"/><path d="M16 8a1 1 0 0 1 1 1v8a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V9a1 1 0 0 1 1-1h14a4 4 0 1 1 0 8h-1"/><path d="M6 2v2"/></svg>
                        </div>
                        <div class="pl-1">
                            <p class="text-sm font-semibold">Services</p>
                            <p class="text-xs">Manage your services here</p>
                        </div>
                    </x-app-nav-link>
                </div>

                <p class="text-sm font-semibold">Others</p>
            @endrole

            <div class="p-1 bg-white border rounded-md border-slate-200">
                @role('admin')
                    <x-app-nav-link :active="false" href="{{ route('app.announcements.index') }}" class="flex items-center gap-3 p-3">
                        <div class="pl-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-newspaper-icon lucide-newspaper"><path d="M15 18h-5"/><path d="M18 14h-8"/><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-4 0v-9a2 2 0 0 1 2-2h2"/><rect width="8" height="4" x="10" y="6" rx="1"/></svg>
                        </div>
                        <div class="pl-1">
                            <p class="text-sm font-semibold">Announcements</p>
                            <p class="text-xs">Your amazing announcements</p>
                        </div>
                    </x-app-nav-link>
                    <x-app-nav-link :active="false" href="{{ route('app.promos.index') }}" class="flex items-center gap-3 p-3">
                        <div class="pl-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-percent"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="m15 9-6 6"/><path d="M9 9h.01"/><path d="M15 15h.01"/></svg>
                        </div>
                        <div class="pl-1">
                            <p class="text-sm font-semibold">Promos</p>
                            <p class="text-xs">Manage your promos here</p>
                        </div>
                    </x-app-nav-link>
                @endrole
                <x-app-nav-link :active="false" href="{{ route('guest.home') }}" class="flex items-center gap-3 p-3">
                    <div class="pl-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-globe"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                    </div>
                    <div class="pl-1">
                        <p class="text-sm font-semibold">Website</p>
                        <p class="text-xs">amazingview.com</p>
                    </div>
                </x-app-nav-link>
            </div>

            <div class="flex justify-between gap-3 p-3 text-white border border-blue-600 rounded-lg bg-gradient-to-r from-blue-500 to-blue-600">
                <a href="{{ route('dashboard') }}" class="inline-block" wire:navigate>
                    <p class="font-semibold capitalize">{{ Auth::user()->first_name . " " . Auth::user()->last_name }}</p>
                    <p class="text-xs text-white">{{ Auth::user()->email }}</p>
                </a>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="grid p-2 text-white transition duration-150 ease-in-out border border-transparent rounded-lg place-items-center focus:outline-none hover:bg-white/25 hover:border-white/50 focus:text-white/50 focus:border-white/25">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                    </button>
                </form>
            </div>
        </article>
    </section>
</x-modal.full>