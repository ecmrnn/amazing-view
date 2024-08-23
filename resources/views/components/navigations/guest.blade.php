<nav class="border-b bg-white/75 backdrop-blur-xl fixed w-full top-0 text-sm font-semibold">
    {{-- <div class="bg-blue-500 text-white text-center py-2 text-xs">
        <p class="tracking-wide">Promo/Advertisement goes here...</p>
    </div> --}}
   
    {{-- Main --}}
    <div class="py-3 max-w-screen-xl mx-auto">
        {{-- Desktop --}}
        <div class="flex items-center justify-between">
            <a href="/" class="block rounded-lg overflow-hidden">
                <img src="https://placehold.co/40x40" alt="Alternative Logo">
            </a>
        
            <div>
                <x-nav-link :active="true" href="/">Home</x-nav-link>
                <x-nav-link href="/rooms">Rooms</x-nav-link>
                <x-nav-link href="/about">About</x-nav-link>
                <x-nav-link href="/contact">Contact</x-nav-link>
                <x-nav-link href="/reservation">Reservation</x-nav-link>
                <x-link-button href="/login">Login</x-link-button>
            </div>
        </div>

        {{-- Mobile --}}
    </div>
</nav>