<div class="w-full">
    {{-- <x-banner /> --}}
    
    <nav class="py-2 text-sm font-semibold bg-white/90 before:backdrop-blur-xl before:backdrop-hack">
        <div class="max-w-screen-xl mx-auto">
            {{-- Desktop --}}
            <div class="items-center justify-between hidden md:flex">
                <x-application-logo />
    
                <div class="flex items-center gap-5">
                    {{-- <x-nav-link :active="Request::is('/')" href="{{ route('guest.home') }}">Home</x-nav-link>
                    <x-nav-link :active="Request::is('rooms*')" href="{{ route('guest.rooms') }}">Rooms</x-nav-link>
                    <x-nav-link :active="Request::is('about')" href="{{ route('guest.about') }}">About</x-nav-link>
                    <x-nav-link :active="Request::is('contact')" href="{{ route('guest.contact') }}">Contact</x-nav-link>
                    <div x-data="{ dropdown: false }" x-on:mouseover="dropdown = true" class="relative" x-on:mouseout="dropdown = false">
                        <x-nav-link :active="Request::is('reservation')  || Request::is('search')"  href="{{ route('guest.reservation') }}">Reservation</x-nav-link>

                        <div x-show="dropdown" class="absolute bottom-0 right-0 p-4 translate-y-full bg-white border rounded-lg w-max border-zinc-200">
                            <p>Find Reservation</p>
                            <p>Reserve a Room</p>
                            <p>Function Hall</p>
                        </div>
                    </div> --}}
                    <x-web.navigation-links />

                    <div class="w-[1px] h-4 bg-slate-200 inline-block"></div>

                    @guest
                        <div class="inline-flex gap-1 text-xs">
                            <a href="{{ route('register') }}" wire:navigate>
                                <x-secondary-button>Sign up</x-secondary-button>
                            </a>
                            <a href="{{ route('login') }}" wire:navigate>
                                <x-primary-button>Sign in</x-primary-button>
                            </a>
                        </div>
                    @endguest

                    @auth
                        <a href="{{ route('login') }}" wire:navigate>
                            <x-primary-button><span class="uppercase">{{ Auth::user()->first_name[0] . Auth::user()->last_name[0] }}</span></x-primary-button>
                        </a>
                    @endauth
                </div>
            </div>
    
            {{-- Mobile --}}
            <div class="relative z-50 flex items-center justify-between md:hidden">
                <a href="{{ route('guest.home') }}" class="block overflow-hidden rounded-lg" wire:navigate>
                    <img src="https://placehold.co/40x40" alt="Alternative Logo">
                </a>
    
                {{-- Burger --}}
                <x-burger x-on:click="open = true" />
            </div>
        </div>
    </nav>
</div>