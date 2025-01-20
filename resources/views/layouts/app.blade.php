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
        @filepondScripts
        @livewireChartsScripts
        <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="overflow-hidden antialiased font-inter text-zinc-800"
        x-init="
        window.toast = function(message, options = {}){
            let description = '';
            let type = 'default';
            let position = 'top-right';
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
            })"
        >
        @stack('modals')
        
        <div class="flex flex-col bg-slate-50 sm:flex-row">
            @include('layouts.navigation')

            <div class="relative flex flex-col w-full h-screen min-h-screen overflow-y-auto">
                <!-- Page Heading -->
                @isset($header)
                    <header class="sticky top-0 z-10 flex items-center justify-between w-full max-h-full px-5 py-3 bg-white border-b border-slate-200">
                        {{ $header }}

                        <div class="flex items-center">
                            <x-tooltip text="Profile" dir="bottom">
                                <a x-ref='content' href="{{ route('profile.edit') }}" wire:navigate class="grid w-10 my-1 rounded-md aspect-square place-items-center hover:bg-slate-50 focus:text-zinc-800 focus:border-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-user"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="10" r="3"/><path d="M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662"/></svg>
                                </a>
                            </x-tooltip>

                            <x-tooltip text="Settings" dir="bottom">
                                <a x-ref='content' x-on:click="$dispatch('open-modal', 'settings-modal')" wire:navigate class="grid w-10 my-1 rounded-md aspect-square place-items-center hover:bg-slate-50 focus:text-zinc-800 focus:border-blue-500 hover:cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                            </x-tooltip>

                            <div class="grid w-10 my-1 ml-2 font-bold text-white uppercase bg-blue-500 rounded-md aspect-square place-items-center">
                                {{ Auth::user()->first_name[0] }}
                            </div>
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="relative p-5 space-y-3 grow sm:space-y-5">
                    {{ $slot }}
                </main>
            </div>
        </div>
    
        {{-- Toast --}}
        <x-toast />

        {{-- Settings Modal --}}
        <x-app.settings />

        @livewireScripts
        @livewireStyles
    </body>
</html>
