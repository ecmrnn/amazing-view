<nav x-data="{
    navigationMenuOpen: false,
    navigationMenu: '',
    navigationMenuCloseDelay: 200,
    navigationMenuCloseTimeout: null,
    navigationMenuLeave() {
        let that = this;
        this.navigationMenuCloseTimeout = setTimeout(() => {
            that.navigationMenuClose();
        }, this.navigationMenuCloseDelay);
    },
    navigationMenuReposition(navElement) {
        this.navigationMenuClearCloseTimeout();
        this.$refs.navigationDropdown.style.left = navElement.offsetLeft + 'px';
        this.$refs.navigationDropdown.style.marginLeft = (navElement.offsetWidth/2) + 'px';
    },
    navigationMenuClearCloseTimeout(){
        clearTimeout(this.navigationMenuCloseTimeout);
    },
    navigationMenuClose(){
        this.navigationMenuOpen = false;
        this.navigationMenu = '';
    }
}"
class="relative z-10 w-auto">
<div class="relative">
    <ul class="flex items-center justify-center gap-5 list-none">
        <li>
            <x-nav-link :active="Request::is('/')" href="{{ route('guest.home') }}">Home</x-nav-link>
        </li>
        <li>
            <x-nav-link :active="Request::is('rooms*')" href="{{ route('guest.rooms') }}">Rooms</x-nav-link>
        </li>
        <li>
            <x-nav-link :active="Request::is('about')" href="{{ route('guest.about') }}">About</x-nav-link>
        </li>
        <li>
            <x-nav-link :active="Request::is('contact')" href="{{ route('guest.contact') }}">Contact</x-nav-link>
        </li>
        <li class="flex items-center gap-2">
            <x-nav-link :active="Request::is('reservation')  || Request::is('search') || Request::is('function-hall')"  href="{{ route('guest.reservation') }}" @mouseover="navigationMenuOpen=true; navigationMenuReposition($el); navigationMenu='reservation-menu'" @mouseleave="navigationMenuLeave()">Reservation</x-nav-link>
            <svg class="relative top-[1px] ml-1 h-3 w-3 ease-out duration-300" :class="{ '-rotate-180' : navigationMenuOpen==true && navigationMenu == 'reservation-menu' }" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down"><path d="m6 9 6 6 6-6"/></svg>
        </li>
    </ul>
</div>
<div x-ref="navigationDropdown" x-show="navigationMenuOpen"
    x-transition:enter="transition ease-out duration-100"
    x-transition:enter-start="opacity-0 scale-90"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90"
    @mouseover="navigationMenuClearCloseTimeout()" @mouseleave="navigationMenuLeave()"
    class="absolute top-0 pt-3 duration-200 ease-out -translate-x-1/2 translate-y-11" x-cloak>

    <div class="flex justify-center w-auto h-auto overflow-hidden bg-white border rounded-md shadow-sm border-zinc-200">

        <div x-show="navigationMenu == 'reservation-menu'" class="flex items-stretch justify-center w-full p-3">
            <div class="w-72">
                <a href="{{ route('guest.reservation') }}" @click="navigationMenuClose()" wire:navigate
                    class="px-3.5 py-3 rounded-t hover:bg-zinc-100 flex items-center gap-4 group border border-zinc-200">
                    <div class="p-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-door-closed"><path d="M18 20V6a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v14"/><path d="M2 20h20"/><path d="M14 12v.01"/></svg>
                    </div>

                    <div>
                        <p class="font-bold">Room</p>
                        <p class="text-xs font-normal">For overnight stays and daytour</p>
                    </div>
                </a>
                <a href="{{ route('guest.function-hall') }}" wire:navigate
                    class="px-3.5 py-3 hover:bg-zinc-100 flex items-center gap-4 group border border-zinc-200 border-y-0">
                    <div class="p-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                    </div>

                    <div>
                        <p class="font-bold">Function Hall</p>
                        <p class="text-xs font-normal">For events and seminars</p>
                    </div>
                </a>
                <a href="{{ route('guest.search') }}" wire:navigate @click="navigationMenuClose()"
                    class="px-3.5 py-3 rounded-b hover:bg-zinc-100 flex items-center gap-4 group border border-zinc-200">
                    <div class="p-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    </div>

                    <div>
                        <p class="font-bold">Find my Reservation</p>
                        <p class="text-xs font-normal">Track your reservation</p>
                    </div>
                </a>
            </div>
        </div>
    
    </div>
</div>
</nav>
