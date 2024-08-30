<footer class="px-5 py-10 text-white bg-blue-950">
    <div class="max-w-screen-xl gap-10 mx-auto space-y-10 md:space-y-0 md:grid md:grid-cols-3 lg:grid-cols-4">
        <div class="space-y-5 border-dashed md:col-span-3 lg:col-span-1 lg:border-r border-white/50">
            <h2 class="text-3xl font-semibold">
                <span>Amazing View</span><br />
                <span>Mountain Resort</span>
            </h2>

            <p>
                Where every stay becomes a story, <br />
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

            <div class="space-y-3">
                <x-footer-link href="/">Home</x-footer-link>
                <x-footer-link href="/rooms">Rooms</x-footer-link>
                <x-footer-link href="/about">About</x-footer-link>
                <x-footer-link href="/contact">Contact</x-footer-link>
            </div>
        </div>
        <div class="space-y-5">
            <h3 class="text-xl font-semibold">Stay connected with us!</h3>
            
            <div class="space-y-3">
                <x-footer-link href="https://facebook.com" target="_blank">Facebook</x-footer-link>
                <x-footer-link href="/">Instagram</x-footer-link>
                <x-footer-link href="/">Twitter</x-footer-link>
                <x-footer-link href="/">Youtube</x-footer-link>
            </div>
        </div>
        <div class="space-y-5">
            <h3 class="text-xl font-semibold">Enjoy more of our content</h3>
            
            <div class="space-y-3">
                <x-footer-link href="/">Blogs</x-footer-link>
                <x-footer-link href="/">Events</x-footer-link>
                <x-footer-link href="/">Testimonials</x-footer-link>
                <x-footer-link href="/">Announcements</x-footer-link>
            </div>
        </div>
    </div>
</footer>