<div class="fixed w-full top-0">
    {{-- <x-banner /> --}}
    
    <nav class="px-5 border-b bg-white/75 backdrop-blur-xl text-sm font-semibold">
        <div class="py-3 max-w-screen-xl mx-auto">
            {{-- Desktop --}}
            <div class="hidden md:flex items-center justify-between">
                <a href="/" class="block rounded-lg overflow-hidden">
                    <img src="https://placehold.co/40x40" alt="Alternative Logo">
                </a>
    
                <div class="space-x-5">
                    <x-nav-link :active="Request::is('/')" href="/">Home</x-nav-link>
                    <x-nav-link :active="Request::is('rooms*')" href="/rooms">Rooms</x-nav-link>
                    <x-nav-link :active="Request::is('about')" href="/about">About</x-nav-link>
                    <x-nav-link :active="Request::is('contact')" href="/contact">Contact</x-nav-link>
                    <x-nav-link :active="Request::is('reservation')" href="/reservation">Reservation</x-nav-link>
                    <x-link-button href="/login">Sign In</x-link-button>
                </div>
            </div>
    
            {{-- Mobile --}}
            <div x-data="{ open : false }" class="md:hidden flex justify-between items-center relative">
                <a href="/" class="block rounded-lg overflow-hidden">
                    <img src="https://placehold.co/40x40" alt="Alternative Logo">
                </a>
    
                {{-- Burger --}}
                <x-burger x-on:click="alert(open)" />

            </div>
        </div>
    </nav>
</div>