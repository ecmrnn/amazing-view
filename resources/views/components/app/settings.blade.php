<x-modal.full name='settings-modal' maxWidth='sm'>
    <section class="p-5 space-y-5 bg-white rounded-lg">
        <hgroup>
            <h2 class="text-lg font-bold">Settings</h2>
            <p class="text-sm">Configure other aspects of the system here</p>
        </hgroup>

        <article class="space-y-1">
            <div class="p-1 border border-gray-300 rounded-lg">
                <x-app-nav-link :active="false" href="{{ route('app.buildings.index') }}" class="flex items-center gap-3 p-3">
                    <div class="pl-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hotel"><path d="M10 22v-6.57"/><path d="M12 11h.01"/><path d="M12 7h.01"/><path d="M14 15.43V22"/><path d="M15 16a5 5 0 0 0-6 0"/><path d="M16 11h.01"/><path d="M16 7h.01"/><path d="M8 11h.01"/><path d="M8 7h.01"/><rect x="4" y="2" width="16" height="20" rx="2"/></svg>
                    </div>
                    <div class="pl-1">
                        <p class="text-sm font-semibold">Buildings</p>
                        <p class="text-xs">Add, edit, or delete buildings</p>
                    </div>
                </x-app-nav-link>
                <x-app-nav-link :active="false" href="{{ route('app.users.index') }}" class="flex items-center gap-3 p-3">
                    <div class="pl-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-tv-minimal"><path d="M7 21h10"/><rect width="20" height="14" x="2" y="3" rx="2"/></svg>
                    </div>
                    <div class="pl-1">
                        <p class="text-sm font-semibold">Amenities</p>
                        <p class="text-xs">Manage your amenities here</p>
                    </div>
                </x-app-nav-link>
            </div>

            <div class="p-1 border border-gray-300 rounded-lg">
                <x-app-nav-link :active="false" href="{{ route('app.users.index') }}" class="flex items-center gap-3 p-3">
                    <div class="pl-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-volume-2"><path d="M11 4.702a.705.705 0 0 0-1.203-.498L6.413 7.587A1.4 1.4 0 0 1 5.416 8H3a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h2.416a1.4 1.4 0 0 1 .997.413l3.383 3.384A.705.705 0 0 0 11 19.298z"/><path d="M16 9a5 5 0 0 1 0 6"/><path d="M19.364 18.364a9 9 0 0 0 0-12.728"/></svg>
                    </div>
                    <div class="pl-1">
                        <p class="text-sm font-semibold">Announcements</p>
                        <p class="text-xs">Your amazing announcements</p>
                    </div>
                </x-app-nav-link>
                <x-app-nav-link :active="false" href="{{ route('app.users.index') }}" class="flex items-center gap-3 p-3">
                    <div class="pl-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-percent"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="m15 9-6 6"/><path d="M9 9h.01"/><path d="M15 15h.01"/></svg>
                    </div>
                    <div class="pl-1">
                        <p class="text-sm font-semibold">Discounts</p>
                        <p class="text-xs">PWDs, Senior Discounts, and etc...</p>
                    </div>
                </x-app-nav-link>
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
        </article>
    </section>
</x-modal.full>