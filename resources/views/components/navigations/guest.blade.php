<div class="fixed top-0 z-10 w-full">
    {{-- <x-banner /> --}}
    
    <nav class="px-5 text-sm font-semibold border-b bg-white/75 before:backdrop-blur-xl before:backdrop-hack">
        <div class="max-w-screen-xl py-3 mx-auto">
            {{-- Desktop --}}
            <div class="items-center justify-between hidden md:flex">
                <a href="/" class="block overflow-hidden border border-transparent rounded-lg focus:outline-none focus:ring-0 focus:border-blue-600" wire:navigate>
                    <img src="https://placehold.co/40x40" alt="Alternative Logo">
                </a>
    
                <div class="flex items-center gap-5">
                    <x-nav-link :active="Request::is('/')" href="{{ route('guest.home') }}">Home</x-nav-link>
                    <x-nav-link :active="Request::is('rooms*')" href="{{ route('guest.rooms') }}">Rooms</x-nav-link>
                    <x-nav-link :active="Request::is('about')" href="{{ route('guest.about') }}">About</x-nav-link>
                    <x-nav-link :active="Request::is('contact')" href="{{ route('guest.contact') }}">Contact</x-nav-link>
                    <x-nav-link :active="Request::is('reservation')  || Request::is('search')"  href="{{ route('guest.reservation') }}">Reservation</x-nav-link>

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

                <div x-show="open"
                    x-transition.opacity.duration.600ms 
                    x-on:click="open = false"
                    class="fixed inset-0 bg-gradient-to-b from-blue-800/75 to-blue-500/50 backdrop-blur-sm"></div>

                <div x-cloak x-show="open" x-on:click.outside="open = false"
                    x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700" 
                    x-transition:enter-start="translate-x-full" 
                    x-transition:enter-end="translate-x-0" 
                    x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700" 
                    x-transition:leave-start="translate-x-0" 
                    x-transition:leave-end="translate-x-full" 
                     class="fixed top-0 right-0 w-3/4 h-screen bg-white border-l md:hidden">
                    <div class="flex items-center justify-between p-5">
                        <div>
                            <p class="font-semibold">Main Menu</p>
                        </div>

                        <div>
                            <x-close-button x-on:click="open = false" />
                        </div>
                    </div>

                    <div class="flex flex-col items-start px-5">
                        <x-nav-link :active="Request::is('/')" href="{{ route('guest.home') }}">Home</x-nav-link>
                        <x-nav-link :active="Request::is('rooms*')" href="{{ route('guest.rooms') }}">Rooms</x-nav-link>
                        <x-nav-link :active="Request::is('about')" href="{{ route('guest.about') }}">About</x-nav-link>
                        <x-nav-link :active="Request::is('contact')" href="{{ route('guest.contact') }}">Contact</x-nav-link>
                        <x-nav-link :active="Request::is('reservation') || Request::is('search')" href="{{ route('guest.reservation') }}">Reservation</x-nav-link>
                    </div>

                    <div class="inline-flex w-full gap-1 p-5 mt-3 border-t border-slate-200">
                        <a href="{{ route('register') }}" wire:navigate>
                            <x-secondary-button>Sign up</x-secondary-button>
                        </a>
                        <a href="{{ route('login') }}" wire:navigate>
                            <x-primary-button>Sign in</x-primary-button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>