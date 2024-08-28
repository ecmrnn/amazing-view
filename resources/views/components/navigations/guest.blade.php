<div class="fixed w-full top-0 z-10">
    {{-- <x-banner /> --}}
    
    <nav class="px-5 border-b bg-white/75 before:backdrop-blur-xl before:backdrop-hack text-sm font-semibold">
        <div class="py-3 max-w-screen-xl mx-auto">
            {{-- Desktop --}}
            <div class="hidden md:flex items-center justify-between">
                <a href="/" class="block rounded-lg overflow-hidden border border-transparent focus:outline-none focus:ring-0 focus:border-blue-600" wire:navigate>
                    <img src="https://placehold.co/40x40" alt="Alternative Logo">
                </a>
    
                <div class="gap-5 flex items-center">
                    <x-nav-link wire:navigate :active="Request::is('/')" href="/">Home</x-nav-link>
                    <x-nav-link wire:navigate :active="Request::is('rooms*')" href="/rooms">Rooms</x-nav-link>
                    <x-nav-link wire:navigate :active="Request::is('about')" href="/about">About</x-nav-link>
                    <x-nav-link wire:navigate :active="Request::is('contact')" href="/contact">Contact</x-nav-link>
                    <x-nav-link wire:navigate :active="Request::is('reservation')"  href="/reservation">Reservation</x-nav-link>

                    <div class="w-[1px] h-4 bg-slate-200 inline-block"></div>

                    <div class="inline-flex gap-1 text-xs">
                        <a href="/register" wire:navigate>
                            <x-secondary-button>Sign up</x-secondary-button>
                        </a>
                        <a href="/login" wire:navigate>
                            <x-primary-button>Sign in</x-primary-button>
                        </a>
                    </div>
                </div>
            </div>
    
            {{-- Mobile --}}
            <div class="md:hidden flex justify-between items-center relative z-50">
                <a href="/" class="block rounded-lg overflow-hidden" wire:navigate>
                    <img src="https://placehold.co/40x40" alt="Alternative Logo">
                </a>
    
                {{-- Burger --}}
                <x-burger x-on:click="open = true" />

                <div x-cloak x-show="open" x-on:click.outside="open = false" x-transition class="fixed md:hidden top-0 right-0 border-l w-3/4 h-screen bg-white/80 backdrop-blur-xl">
                    <div class="p-5 flex items-center justify-between">
                        <div>
                            <p class="font-semibold">Main Menu</p>
                        </div>

                        <div>
                            <x-close-button x-on:click="open = false" />
                        </div>
                    </div>

                    <div class="px-5 flex flex-col items-start">
                        <x-nav-link wire:navigate :active="Request::is('/')" href="/">Home</x-nav-link>
                        <x-nav-link wire:navigate :active="Request::is('rooms*')" href="/rooms">Rooms</x-nav-link>
                        <x-nav-link wire:navigate :active="Request::is('about')" href="/about">About</x-nav-link>
                        <x-nav-link wire:navigate :active="Request::is('contact')" href="/contact">Contact</x-nav-link>
                        <x-nav-link wire:navigate :active="Request::is('reservation')" href="/reservation">Reservation</x-nav-link>
                    </div>

                    <div class="mt-3 p-5 inline-flex w-full gap-1 border-t">
                        <a href="/register" wire:navigate>
                            <x-secondary-button>Sign up</x-secondary-button>
                        </a>
                        <a href="/login" wire:navigate>
                            <x-primary-button>Sign in</x-primary-button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>