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
                position: 'top-right',
            })"
        >
        <div @class([
                'sticky top-0 flex flex-col px-5 pb-5',
                'h-screen' => ! Request::is('reservation') && ! Request::is('search'),
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
            <div class="absolute left-0 w-full h-5 bg-white -top-5"></div>
            
            {{ $slot }}
        </main>

        <x-footer />
        
        {{-- Toast --}}
        <x-toast />
        
        @filepondScripts
        @livewireScripts
        @livewireStyles
    </body>
</html>
