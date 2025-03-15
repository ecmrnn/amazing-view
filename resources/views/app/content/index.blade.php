<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between gap-3">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">
                    Contents
                </h1>
                <p class="text-xs">Manage your contents here</p>
            </hgroup>
        </div>
    </x-slot:header>

    <section class="grid max-w-screen-lg gap-5 mx-auto md:grid-cols-2 lg:grid-cols-3">
        <x-app.page href="{{ route('app.contents.edit', ['content' => 1]) }}" page="Home">
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
            </x-slot:icon>

            <x-slot:description>
                Manage your home page contents like headers, subtitles, images, featured services, and brief history.
            </x-slot:description>
        </x-app.page>

        <x-app.page href="{{ route('app.contents.edit', ['content' => 2]) }}" page="Rooms">
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-door-closed"><path d="M18 20V6a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v14"/><path d="M2 20h20"/><path d="M14 12v.01"/></svg>
            </x-slot:icon>

            <x-slot:description>
                Manage your room page contents like headers, subtitles, images, and room types.
            </x-slot:description>
        </x-app.page>

        <x-app.page href="{{ route('app.contents.edit', ['content' => 3]) }}" page="About">
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
            </x-slot:icon>

            <x-slot:description>
                Manage your about page contents like headers, subtitles, images, history, and milestones.
            </x-slot:description>
        </x-app.page>

        <x-app.page href="{{ route('app.contents.edit', ['content' => 4]) }}" page="Contact">
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            </x-slot:icon>

            <x-slot:description>
                Manage your contact page contents like headers, subtitles, images, and phone numbers.
            </x-slot:description>
        </x-app.page>

        <x-app.page href="{{ route('app.contents.edit', ['content' => 5]) }}" page="Reservation">
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-laptop-minimal"><rect width="18" height="12" x="3" y="4" rx="2" ry="2"/><line x1="2" x2="22" y1="20" y2="20"/></svg>
            </x-slot:icon>

            <x-slot:description>
                Manage your reservation page contents like headers, subtitles, and images.
            </x-slot:description>
        </x-app.page>
        
        <x-app.page href="{{ route('app.contents.edit', ['content' => 7]) }}" page="Global">
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-globe"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
            </x-slot:icon>

            <x-slot:description>
                Manage your contents that are accessible across the website.
            </x-slot:description>
        </x-app.page>
    </section>

    <div class="max-w-screen-lg mx-auto">
        <x-note>Select a page you want to edit.</x-note>
    </div>
</x-app-layout>