<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('redketchup/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('redketchup/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('redketchup/favicon-16x16.png') }}">
        <link rel="manifest" href="/site.webmanifest">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Icons -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,200,0,0" />
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body x-data="{ open : false, toast_details: {} }" class="antialiased font-inter text-zinc-800"
        x-init="
        window.toast = function(message, options = {}){
            let description = '';
            let type = 'default';
            let position = 'top-center';
            let html = '';
            if(typeof options.description != 'undefined') description = options.description;
            if(typeof options.type != 'undefined') type = options.type;
            if(typeof options.position != 'undefined') position = options.position;
            if(typeof options.html != 'undefined') html = options.html;
            
            window.dispatchEvent(new CustomEvent('toast-show', { detail : { type: type, message: message, description: description, position : position, html: html }}));
        }"
        x-on:toast="toast_details = JSON.parse($event.detail);
            toast(toast_details.message, {
                type: toast_details.type,
                description: toast_details.description,
                position: 'top-center',
            })"
        >
        <div @class([
                'sticky top-0 flex flex-col px-5 pb-5',
                'h-screen' => ! Request::is('reservation') && ! Request::is('search') && ! Request::is('function-hall'), 
            ])>
            <x-navigations.guest />
            <section class="relative flex-grow">
                {{ $hero }}
            </section>
        </div>
        
        <main class="relative">
            {{--  --}}
            <div class="absolute w-5 bg-white left-2 -top-7 inv-rad inv-rad-t-r-2 aspect-square"></div>
            <div class="absolute w-5 bg-white right-2 -top-7 inv-rad inv-rad-t-l-2 aspect-square"></div>
            <div class="absolute left-0 z-0 w-full h-5 bg-white -top-5"></div>
            
            {{ $slot }}

            {{-- Scroll to Top --}}
             <div x-data="{ scroll_height: window.scrollY }" x-on:scroll.window="scroll_height = window.scrollY"
                x-show="scroll_height > 200"
                x-transition.duration.200ms
                class="fixed bottom-5 right-5">
                <x-primary-button x-on:click="window.scroll(0,0)" class="flex items-center gap-3 text-xs ">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up"><path d="m5 12 7-7 7 7"/><path d="M12 19V5"/></svg>
                    <p>Scroll to Top</p>
                </x-primary-button>
            </div>
        </main>

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
                class="fixed top-0 right-0 z-[500] w-3/4 h-screen bg-white border-l md:hidden">
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

        <x-footer />
        
        {{-- Toast --}}
        <x-toast />
        
        @filepondScripts
        @livewireScripts
        @livewireStyles
    </body>
</html>
