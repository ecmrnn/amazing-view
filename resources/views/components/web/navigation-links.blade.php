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
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90"
    @mouseover="navigationMenuClearCloseTimeout()" @mouseleave="navigationMenuLeave()"
    class="absolute top-0 pt-3 duration-200 ease-out w-max -translate-x-3/4 translate-y-11" x-cloak>

    <div class="flex justify-center w-full h-auto bg-white border rounded-md shadow-sm border-slate-200">
        <div x-show="navigationMenu == 'reservation-menu'" class="flex justify-center w-full">
            <div class="w-full p-2 border-r border-slate-200">
                <a href="{{ route('guest.reservation') }}" @click="navigationMenuClose()" wire:navigate
                    class="flex items-center gap-4 px-3 py-2 rounded-md group">
                    <div class="p-3 border rounded-md border-slate-200 bg-slate-50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-door-closed"><path d="M18 20V6a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v14"/><path d="M2 20h20"/><path d="M14 12v.01"/></svg>
                    </div>

                    <div class="flex items-center justify-between w-full">
                        <div>
                            <p class="font-bold">Room</p>
                            <p class="text-xs font-normal transition-all duration-200 ease-in-out opacity-50 group-hover:opacity-100">For overnight stays and daytour</p>
                        </div>

                        <div class="transition-all duration-200 ease-in-out scale-0 group-hover:scale-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>
                <a href="{{ route('guest.function-hall') }}" wire:navigate
                    class="flex items-center gap-4 px-3 py-2 rounded-md group">
                    <div class="p-3 border rounded-md border-slate-200 bg-slate-50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-heart"><path d="M3 10h18V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h7"/><path d="M8 2v4"/><path d="M16 2v4"/><path d="M21.29 14.7a2.43 2.43 0 0 0-2.65-.52c-.3.12-.57.3-.8.53l-.34.34-.35-.34a2.43 2.43 0 0 0-2.65-.53c-.3.12-.56.3-.79.53-.95.94-1 2.53.2 3.74L17.5 22l3.6-3.55c1.2-1.21 1.14-2.8.19-3.74Z"/></svg>
                    </div>

                    <div class="flex items-center justify-between w-full">
                        <div>
                            <p class="font-bold">Function Hall</p>
                            <p class="text-xs font-normal transition-all duration-200 ease-in-out opacity-50 group-hover:opacity-100">For events and seminars</p>
                        </div>

                        <div class="transition-all duration-200 ease-in-out scale-0 group-hover:scale-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>
                <a href="{{ route('guest.search') }}" wire:navigate @click="navigationMenuClose()"
                    class="flex items-center gap-4 px-3 py-2 rounded-md group">
                    <div class="p-3 border rounded-md border-slate-200 bg-slate-50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-text-search"><path d="M21 6H3"/><path d="M10 12H3"/><path d="M10 18H3"/><circle cx="17" cy="15" r="3"/><path d="m21 19-1.9-1.9"/></svg>
                    </div>

                    <div class="flex items-center justify-between w-full">
                        <div>
                            <p class="font-bold">Find my Reservation</p>
                            <p class="text-xs font-normal transition-all duration-200 ease-in-out opacity-50 group-hover:opacity-100">Track your room reservation</p>
                        </div>

                        <div class="transition-all duration-200 ease-in-out scale-0 group-hover:scale-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>
            </div>
            <div class="flex flex-col justify-between p-5 bg-slate-50 rounded-e-lg">
                @auth
                    <hgroup>
                        <h2 class="font-semibold capitalize">Hello, {{ Auth::user()->first_name }}!</h2>
                        <p class="text-xs font-normal">Ready to book your reservation?</p>
                    </hgroup>
                    
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-waves"><path d="M2 6c.6.5 1.2 1 2.5 1C7 7 7 5 9.5 5c2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M2 12c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M2 18c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/></svg>
                @endauth

                @guest
                    <hgroup>
                        <h2 class="font-semibold capitalize">Amazing awaits!</h2>
                        <p class="text-xs font-normal">We are excited to see you, book now!</p>
                    </hgroup>

                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sun"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
                @endguest
            </div>
        </div>
    
    </div>
</div>
</nav>
