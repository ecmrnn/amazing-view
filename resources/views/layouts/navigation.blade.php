<nav class="sticky top-0 z-10 max-h-screen bg-white border-b border-r sm:border-b-0 border-slate-200">
    <!-- Primary Navigation Menu -->
    <div class="h-full">
        <div x-data="{ expanded: $persist(false) }" class="justify-between hidden h-full sm:flex sm:flex-col"
            x-bind:class="expanded ? 'w-56' : ''">
            <div>
                <!-- Logo -->
                <div class="flex self-center flex-shrink p-3 border-b border-slate-200">
                    <x-application-logo x-bind:class="expanded ? '' : 'mx-auto'" />
                </div>

                <!-- Navigation Links -->
                <div class="hidden p-5 space-y-2 sm:block">
                    <p class="text-xs font-semibold" x-bind:class="expanded ? '' : 'text-center'">Menu</p>

                    @include('components.app.navigation')
                </div>
            </div>

            {{-- Expand & Collapse Button --}}
            <div class="hidden w-full p-5 space-y-2 sm:block">
                {{-- Expand --}}
                <template x-if="!expanded">
                    <x-tooltip text="Expand" dir="right">
                        <button x-ref="content" x-on:click="expanded = !expanded"
                            class="grid p-3 transition duration-150 ease-in-out border border-transparent rounded-lg place-items-center focus:outline-none hover:text-zinc-800 hover:bg-slate-50 hover:border-slate-200 focus:text-zinc-800 focus:border-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right-from-line"><path d="M3 5v14"/><path d="M21 12H7"/><path d="m15 18 6-6-6-6"/></svg>
                        </button>
                    </x-tooltip>
                </template>

                {{-- Collapse --}}
                <template x-if="expanded">
                    <x-tooltip text="Collapse" dir="right">
                        <button x-ref="content" x-on:click="expanded = !expanded"
                            class="grid p-3 transition duration-150 ease-in-out border border-transparent rounded-lg place-items-center focus:outline-none hover:bg-slate-50 hover:border-slate-200 focus:text-zinc-800 focus:border-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left-from-line"><path d="m9 6-6 6 6 6"/><path d="M3 12h14"/><path d="M21 19V5"/></svg>
                        </button>
                    </x-tooltip>
                </template>
            </div>

            <!-- Hamburger -->
            <div class="flex items-center sm:hidden">
                <x-burger @click="open = ! open" />
            </div>
        </div>

        {{-- Mobile --}}
        <div x-data="{ open: false }"  class="relative flex items-center justify-between px-5 py-2 sm:hidden">
            <x-application-logo />

            {{-- Burger --}}
            <x-burger x-on:click="open = true" />

            <div x-show="open" x-transition.opacity.duration.600ms x-on:click="open = false"
                class="fixed inset-0 bg-gradient-to-b from-blue-800/75 to-blue-500/50 backdrop-blur-sm"></div>

            <div x-cloak x-show="open" x-on:click.outside="open = false"
                x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                class="fixed top-0 right-0 w-3/4 h-screen overflow-auto bg-white border-l md:hidden">
                {{-- Mobile Links --}}
                <div class="flex flex-col justify-between h-full">
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
                            @include('components.app.navigation', ['screen' => 'mobile'])
                        </div>
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
