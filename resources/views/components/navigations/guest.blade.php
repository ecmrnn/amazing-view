<div class="w-full">
    {{-- <x-banner /> --}}
    
    <nav class="py-2 text-sm font-semibold bg-white/90 before:backdrop-blur-xl before:backdrop-hack">
        <div class="max-w-screen-xl mx-auto">
            {{-- Desktop --}}
            <div class="items-center justify-between hidden md:flex">
                <x-application-logo />
    
                <div class="flex items-center gap-5">
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
                <x-application-logo />
    
                {{-- Burger --}}
                <x-burger x-on:click="open = true" />
            </div>
        </div>
    </nav>
</div>