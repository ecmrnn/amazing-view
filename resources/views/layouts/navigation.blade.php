<nav class="sticky top-0 max-h-screen bg-white border-b border-r sm:border-b-0 border-slate-100">
    <!-- Primary Navigation Menu -->
    <div class="h-full p-5">
        <div x-data="{ expanded: $persist(false) }" class="justify-between hidden h-full sm:flex sm:flex-col">
            <div class="space-y-5">
                <!-- Logo -->
                <div class="flex self-center flex-shrink">
                    <x-application-logo x-bind:class="expanded ? '' : 'mx-auto'" />
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-y-2 sm:block">
                    <p class="text-xs font-semibold" x-bind:class="expanded ? '' : 'text-center'">Menu</p>

                    @include('components.app.navigation')
                </div>
            </div>

            {{-- Expand & Collapse Button --}}
            <div class="hidden w-full space-y-2 sm:block">
                <div class="p-1 border rounded-lg">        
                    <x-tooltip text="Profile" dir="right">
                        <x-app-nav-link x-ref="content" :active="Request::is('profile')" href="{{ route('profile.edit') }}" x-bind:class="expanded ? '' : '*:mx-auto'" class="flex items-center gap-3 p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-user-round"><path d="M18 20a6 6 0 0 0-12 0"/><circle cx="12" cy="10" r="4"/><circle cx="12" cy="12" r="10"/></svg>
                            <span x-show="expanded" class="text-sm font-semibold">Profile</span>
                        </x-app-nav-link>
                    </x-tooltip>
            
                    <x-tooltip text="Settings" dir="right">
                        <x-app-nav-link x-ref="content" :active="Request::is('settings')" x-on:click="$dispatch('open-modal', 'settings-modal')" x-bind:class="expanded ? '' : '*:mx-auto'" class="flex items-center gap-3 p-3 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                            <span x-show="expanded" class="text-sm font-semibold">Settings</span>
                        </x-app-nav-link>
                    </x-tooltip>
                </div>
                {{-- Expand --}}
                <template x-if="!expanded">
                    <div class="p-1 border rounded-lg">
                        <x-tooltip text="Expand" dir="right">
                            <button x-ref="content" x-on:click="expanded = !expanded"
                                class="grid p-3 transition duration-150 ease-in-out border border-transparent rounded-lg place-items-center focus:outline-none text-zinc-600 hover:text-zinc-800 hover:bg-slate-50 hover:border-slate-200 focus:text-zinc-800 focus:border-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-arrow-right-from-line">
                                    <path d="M3 5v14" />
                                    <path d="M21 12H7" />
                                    <path d="m15 18 6-6-6-6" />
                                </svg>
                            </button>
                        </x-tooltip>
                    </div>
                </template>

                {{-- Collapse --}}
                <template x-if="expanded">
                    <div class="p-1 border rounded-lg">
                        <x-tooltip text="Collapse" dir="right">
                            <button x-ref="content" x-on:click="expanded = !expanded"
                                class="grid p-3 transition duration-150 ease-in-out border border-transparent rounded-lg place-items-center focus:outline-none text-zinc-800/50 hover:text-zinc-800 hover:bg-slate-50 hover:border-slate-200 focus:text-zinc-800 focus:border-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-arrow-left-to-line">
                                    <path d="M3 19V5" />
                                    <path d="m13 6-6 6 6 6" />
                                    <path d="M7 12h14" />
                                </svg>
                            </button>
                        </x-tooltip>
                    </div>
                </template>
            </div>

            <!-- Hamburger -->
            <div class="flex items-center sm:hidden">
                <x-burger @click="open = ! open" />
            </div>
        </div>

        {{-- Mobile --}}
        <div x-data="{ open: false }"  class="relative z-[999] flex items-center justify-between sm:hidden">
            <a href="{{ route('guest.home') }}" class="block overflow-hidden rounded-lg" wire:navigate>
                <img src="https://placehold.co/40x40" alt="Alternative Logo">
            </a>

            {{-- Burger --}}
            <x-burger x-on:click="open = true" />

            <div x-show="open" x-transition.opacity.duration.600ms x-on:click="open = false"
                class="fixed inset-0 bg-gradient-to-b from-blue-800/75 to-blue-500/50 backdrop-blur-sm z-[999]"></div>

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
                    <div class="flex flex-col items-start px-5 border-b border-slate-200">
                        @include('components.app.navigation', ['screen' => 'mobile'])
                    </div>

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
                </div>
            </div>
        </div>
    </div>
</nav>
