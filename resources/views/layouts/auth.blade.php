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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="relative grid min-h-screen antialiased font-inter text-zinc-800 place-items-center">
        <div class="absolute z-50 px-5 text-xs font-semibold -translate-x-1/2 bg-white rounded-lg top-10 sm:top-5 left-1/2">
            <x-nav-link href="{{ route('guest.home') }}">Back to Home</x-nav-link>
        </div>
        
        <div class="fixed top-0 z-0">
            <img src="{{ asset('storage/global/login.jpg') }}" alt="" class="object-cover object-center w-screen h-screen blur-md">
            <x-overlay />
        </div>

        <main class="relative w-full h-full p-5 sm:w-auto sm:h-auto">
            <div class="grid h-full p-5 bg-white rounded-lg sm:h-auto place-items-center">
                {{ $slot }}
            </div>
        </main>

        @filepondScripts
        @livewireScripts
    </body>
</html>