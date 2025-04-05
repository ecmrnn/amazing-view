<footer class="relative z-10 px-5 py-10 text-white bg-blue-950">
    <div class="max-w-screen-xl gap-10 mx-auto space-y-10 md:space-y-0 md:grid md:grid-cols-3 lg:grid-cols-4">
        <div class="p-5 space-y-5 rounded-lg bg-gradient-to-r from-blue-700/25 to-blue-700/0 md:col-span-3 lg:col-span-1 border-white/50">
            <h2 class="text-xl font-semibold">
                <span>Amazing View</span><br />
                <span>Mountain Resort</span>
            </h2>

            <p class="text-sm">
                Where every stay becomes a story,
                welcome to your perfect escape!
            </p>

            <div class="text-xs">
                <a href="/rooms" wire:navigate>
                    <x-primary-button>Book a Room</x-primary-button>
                </a>
            </div>
        </div>

        <div class="space-y-5">
            <h3 class="text-xl font-semibold">Navigate through our site</h3>

            <div class="space-y-1">
                <x-footer-link href="/">Home</x-footer-link>
                <x-footer-link href="/rooms">Rooms</x-footer-link>
                <x-footer-link href="/about">About</x-footer-link>
                <x-footer-link href="/contact">Contact</x-footer-link>
            </div>
        </div>
        <div class="space-y-5">
            <h3 class="text-xl font-semibold">Stay connected with us!</h3>
            
            <div class="space-y-1">
                <x-footer-link href="https://facebook.com" target="_blank">Facebook</x-footer-link>
                <x-footer-link href="/">Instagram</x-footer-link>
                <x-footer-link href="/">Twitter</x-footer-link>
                <x-footer-link href="/">Youtube</x-footer-link>
            </div>
        </div>
        <div class="space-y-5">
            <h3 class="text-xl font-semibold opacity-0 line-clamp-1">{{ $settings['site_title'] }}</h3>
            
            <div class="space-y-1">
                <p class="text-sm">Phone: {{ $settings['site_phone'] }}</p>
                <p class="text-sm break-words">Email: {{ $settings['site_email'] }}</p>
            </div>
        </div>
    </div>
</footer>