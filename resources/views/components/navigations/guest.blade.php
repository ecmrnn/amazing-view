<div class="fixed w-full top-0 z-10">
    {{-- <x-banner /> --}}
    
    <nav class="px-5 border-b bg-white/75 before:backdrop-blur-xl before:backdrop-hack text-sm font-semibold">
        <div class="py-3 max-w-screen-xl mx-auto">
            {{-- Desktop --}}
            <div class="hidden md:flex items-center justify-between">
                <a href="/" class="block rounded-lg overflow-hidden" wire:navigate>
                    <img src="https://placehold.co/40x40" alt="Alternative Logo">
                </a>
    
                <div class="gap-5 flex items-center">
                    <livewire:nav-link :active="Request::is('/')" to="Home" href="/"/>
                    <livewire:nav-link :active="Request::is('rooms*')" to="Rooms" href="/rooms"/>
                    <livewire:nav-link :active="Request::is('about')" to="About" href="/about"/>
                    <livewire:nav-link :active="Request::is('contact')" to="Contact" href="/contact"/>
                    <livewire:nav-link :active="Request::is('reservation')" to="Reservation" href="/reservation"/>
                    <div class="w-[1px] h-4 bg-slate-200 inline-block"></div>
                    <div class="inline-flex gap-1 text-xs">
                        <livewire:button-link type="secondary" name="Sign up" href="/register" />
                        <livewire:button-link name="Sign in" href="/login" />
                    </div>
                </div>
            </div>
    
            {{-- Mobile --}}
            <div class="md:hidden flex justify-between items-center relative z-50">
                <a href="/" class="block rounded-lg overflow-hidden">
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
                        <livewire:nav-link :active="Request::is('/')" to="Home" href="/"/>
                        <livewire:nav-link :active="Request::is('rooms*')" to="Rooms" href="/rooms"/>
                        <livewire:nav-link :active="Request::is('about')" to="About" href="/about"/>
                        <livewire:nav-link :active="Request::is('contact')" to="Contact" href="/contact"/>
                        <livewire:nav-link :active="Request::is('reservation')" to="Reservation" href="/reservation"/>
                    </div>

                    <div class="mt-3 p-5 inline-flex w-full gap-1 border-t">
                        <livewire:button-link name="Sign in" href="/login" />
                        <livewire:button-link type="secondary" name="Sign up" href="/register" />
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>