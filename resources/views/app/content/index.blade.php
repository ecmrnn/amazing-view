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
        <x-app.page href="{{ route('app.contents.edit', ['content' => 1]) }}" :status="$statuses->get(0)">
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
            </x-slot:icon>

            <div>
                <p class="font-semibold text-md">Home</p>
                <p class="text-xs font-normal line-clamp-2">Manage your home page contents like headers, subtitles, images, featured services, and brief history.</p>
            </div>
        </x-app.page>

        <x-app.page href="{{ route('app.contents.edit', ['content' => 2]) }}" :status="$statuses->get(1)">
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-door-closed"><path d="M18 20V6a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v14"/><path d="M2 20h20"/><path d="M14 12v.01"/></svg>
            </x-slot:icon>

            <div>
                <p class="font-semibold text-md">Rooms</p>
                <p class="text-xs font-normal line-clamp-2">Manage your room page contents like headers, subtitles, images, and room types.</p>
            </div>
        </x-app.page>

        <x-app.page href="{{ route('app.contents.edit', ['content' => 3]) }}" :status="$statuses->get(2)">
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
            </x-slot:icon>

            <div>
                <p class="font-semibold text-md">About</p>
                <p class="text-xs font-normal line-clamp-2">Manage your about page contents like headers, subtitles, images, history, and milestones.</p>
            </div>
        </x-app.page>

        <x-app.page href="{{ route('app.contents.edit', ['content' => 4]) }}" :status="$statuses->get(3)">
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            </x-slot:icon>

            <div>
                <p class="font-semibold text-md">Contact</p>
                <p class="text-xs font-normal line-clamp-2">Manage your contact page contents like headers, subtitles, images, and phone numbers.</p>
            </div>
        </x-app.page>

        <x-app.page href="{{ route('app.contents.edit', ['content' => 5]) }}" :status="$statuses->get(4)">
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-laptop-minimal"><rect width="18" height="12" x="3" y="4" rx="2" ry="2"/><line x1="2" x2="22" y1="20" y2="20"/></svg>
            </x-slot:icon>

            <div>
                <p class="font-semibold text-md">Room Reservation</p>
                <p class="text-xs font-normal line-clamp-2">Manage your room reservation page contents like headers, subtitles, and images.</p>
            </div>
        </x-app.page>

        {{-- <x-app.page href="{{ route('app.contents.edit', ['content' => 6]) }}" :status="$statuses->get(5)">
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-landmark"><line x1="3" x2="21" y1="22" y2="22"/><line x1="6" x2="6" y1="18" y2="11"/><line x1="10" x2="10" y1="18" y2="11"/><line x1="14" x2="14" y1="18" y2="11"/><line x1="18" x2="18" y1="18" y2="11"/><polygon points="12 2 20 7 4 7"/></svg>
            </x-slot:icon>

            <div>
                <p class="font-semibold text-md">Function Hall Reservations</p>
                <p class="text-xs font-normal line-clamp-2">Manage your function hall reservation page contents like headers, subtitles, and images.</p>
            </div>
        </x-app.page> --}}
        
        <x-app.page href="{{ route('app.contents.edit', ['content' => 7]) }}" :status="$statuses->get(6)">
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-search"><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M4.268 21a2 2 0 0 0 1.727 1H18a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v3"/><path d="m9 18-1.5-1.5"/><circle cx="5" cy="14" r="3"/></svg>
            </x-slot:icon>

            <div>
                <p class="font-semibold text-md">Find Reservation</p>
                <p class="text-xs font-normal line-clamp-2">Manage your find reservation page contents like headers and subtitles.</p>
            </div>
        </x-app.page>
        
        <x-app.page href="{{ route('app.contents.edit', ['content' => 8]) }}">
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-globe"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
            </x-slot:icon>

            <div>
                <p class="font-semibold text-md">Global</p>
                <p class="text-xs font-normal line-clamp-2">Manage your contents that are accessible across the website.</p>
            </div>
        </x-app.page>
    </section>

    <div class="max-w-screen-lg mx-auto">
        <x-note>Select a page you want to edit.</x-note>
    </div>
</x-app-layout>