<div class="w-full">
    {{-- <x-banner /> --}}
    
    <nav class="py-5 text-sm font-semibold bg-white/90 before:backdrop-blur-xl before:backdrop-hack">
        <div class="max-w-screen-xl mx-auto">
            {{-- Desktop --}}
            <div class="items-center justify-between hidden md:flex">
                <x-application-logo />
    
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

                    @guest
                        <div class="inline-flex w-full gap-1 p-5 mt-3 border-t border-slate-200">
                            <a href="{{ route('register') }}" wire:navigate>
                                <x-secondary-button>Sign up</x-secondary-button>
                            </a>
                            <a href="{{ route('login') }}" wire:navigate>
                                <x-primary-button>Sign in</x-primary-button>
                            </a>
                        </div>
                    @endguest

                    @auth
                        {{-- Settings --}}
                        <div class="m-5">
                            <div class="flex justify-between gap-3 p-3 text-white border border-blue-600 rounded-lg bg-gradient-to-r from-blue-500 to-blue-600">
                                <a href="{{ route('dashboard') }}" class="inline-block" wire:navigate>
                                    <p class="font-bold capitalize">{{ Auth::user()->first_name . " " . Auth::user()->last_name }}</p>
                                    <p class="text-xs text-white/50">{{ Auth::user()->email }}</p>
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
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</div>