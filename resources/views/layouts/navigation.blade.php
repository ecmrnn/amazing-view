<nav x-data="{ open: false }" class="sticky top-0 max-h-screen bg-white border-b border-r sm:border-b-0 border-slate-100">
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

                    @includeWhen(Auth::user()->role == 1, 'components.navigations.frontdesk', ['screen' => 'desktop'])
                </div>
            </div>

            {{-- Expand & Collapse Button --}}
            <div class="hidden p-1 border rounded-lg sm:block">
                {{-- Expand --}}
                <template x-if="!expanded">
                    <x-tooltip text="Expand" dir="right">
                        <button x-ref="content" x-on:click="expanded = !expanded"
                            class="grid p-2 transition duration-150 ease-in-out border border-transparent rounded-lg place-items-center focus:outline-none text-zinc-800/50 hover:text-zinc-800 hover:bg-slate-50 hover:border-slate-200 focus:text-zinc-800 focus:border-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-arrow-right-from-line">
                                <path d="M3 5v14" />
                                <path d="M21 12H7" />
                                <path d="m15 18 6-6-6-6" />
                            </svg>
                        </button>
                    </x-tooltip>
                </template>

                {{-- Collapse --}}
                <template x-if="expanded">
                    <x-tooltip text="Collapse" dir="right">
                        <button x-ref="content" x-on:click="expanded = !expanded"
                            class="grid p-2 transition duration-150 ease-in-out border border-transparent rounded-lg place-items-center focus:outline-none text-zinc-800/50 hover:text-zinc-800 hover:bg-slate-50 hover:border-slate-200 focus:text-zinc-800 focus:border-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-arrow-left-to-line">
                                <path d="M3 19V5" />
                                <path d="m13 6-6 6 6 6" />
                                <path d="M7 12h14" />
                            </svg>
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
        <div class="relative z-50 flex items-center justify-between sm:hidden">
            <a href="{{ route('guest.home') }}" class="block overflow-hidden rounded-lg" wire:navigate>
                <img src="https://placehold.co/40x40" alt="Alternative Logo">
            </a>

            {{-- Burger --}}
            <x-burger x-on:click="open = true" />

            <div x-show="open" x-transition.opacity.duration.600ms x-on:click="open = false"
                class="fixed inset-0 bg-gradient-to-b from-blue-800/75 to-blue-500/50 backdrop-blur-sm"></div>

            <div x-cloak x-show="open" x-on:click.outside="open = false"
                x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                class="fixed top-0 right-0 w-3/4 h-screen bg-white border-l md:hidden">
                {{-- Mobile Links --}}
                <div>
                    <div class="flex items-center justify-between p-5">
                        <div>
                            <p class="font-semibold">Menu</p>
                        </div>
                        <div>
                            <x-close-button x-on:click="open = false" />
                        </div>
                    </div>

                    {{-- Navigation Links --}}
                    <div class="flex flex-col items-start px-5">
                        @includeWhen(Auth::user()->role == 1, 'components.navigations.frontdesk', ['screen' => 'mobile'])
                    </div>

                    {{-- Settings --}}
                    <div class="p-5 border-t border-slate-200">
                        <div class="flex justify-between gap-3 p-3 border rounded-lg">
                            <div>
                                <p class="font-bold capitalize">{{ Auth::user()->first_name . " " . Auth::user()->last_name }}</p>
                                <p class="text-xs text-zinc-800/50">{{ Auth::user()->email }}</p>
                            </div>

                            {{-- Logout --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
    
                                <button
                                    type="submit"
                                    class="grid p-2 text-red-500 transition duration-150 ease-in-out border border-transparent rounded-lg place-items-center focus:outline-none hover:text-red-600 hover:bg-red-50 hover:border-red-200 focus:text-red-600 focus:border-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
