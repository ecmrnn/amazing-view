<nav x-data="{ open: false }" class="bg-white border-r border-slate-100">
    <!-- Primary Navigation Menu -->
    <div class="h-full p-3">
        <div x-data="{ expanded: false }" class="justify-between hidden h-full sm:flex sm:flex-col">
            <div class="space-y-5">
                <!-- Logo -->
                <div class="flex self-center flex-shrink">
                    <x-application-logo class="mx-auto" />
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-y-2 sm:block">
                    <p class="text-xs font-bold text-center text-zinc-800/50">Menu</p>

                    <div class="p-1 border rounded-lg">
                        @includeIf('components.navigations.frontdesk', [Auth::user()->role => 1])
                    </div>
                </div>
            </div>

            {{-- Expand & Collapse Button --}}
            <div class="hidden p-1 border rounded-lg sm:block">
                {{-- Expand --}}
                <template x-if="!expanded">
                    <x-tooltip text="Expand" dir="right">
                        <button x-ref="content" x-on:click="expanded = !expanded" class="grid p-2 transition duration-150 ease-in-out border border-transparent rounded-lg place-items-center focus:outline-none text-zinc-800/50 hover:text-zinc-800 hover:bg-slate-50 hover:border-slate-200 focus:text-zinc-800 focus:border-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right-from-line"><path d="M3 5v14"/><path d="M21 12H7"/><path d="m15 18 6-6-6-6"/></svg>
                        </button>
                    </x-tooltip>
                </template>

                {{-- Collapse --}}
                <template x-if="expanded">
                    <x-tooltip text="Collapse" dir="right">
                        <button x-ref="content" x-on:click="expanded = !expanded" class="grid p-2 transition duration-150 ease-in-out border border-transparent rounded-lg place-items-center focus:outline-none text-zinc-800/50 hover:text-zinc-800 hover:bg-slate-50 hover:border-slate-200 focus:text-zinc-800 focus:border-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left-to-line"><path d="M3 19V5"/><path d="m13 6-6 6 6 6"/><path d="M7 12h14"/></svg>
                        </button>
                    </x-tooltip>
                </template>
            </div>

            <!-- Settings Dropdown -->
            {{-- <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md hover:text-gray-700 focus:outline-none">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div> --}}

            <!-- Hamburger -->
            <div class="flex items-center sm:hidden">
                <x-burger @click="open = ! open" />
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
