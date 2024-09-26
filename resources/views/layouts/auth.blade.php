<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-inter text-zinc-800">
        {{-- Back --}}
        <div class="fixed top-0 left-0 p-5 ml-2 text-xs font-semibold">
            <x-nav-link href="/">Back to Home</x-nav-link>
        </div>
        
        <main class="grid min-h-screen sm:grid-cols-2 lg:grid-cols-3">
            <div class="sm:col-span-2 md:col-span-1">
                {{ $slot }}
            </div>
            <!-- Image -->
            <div
                class="relative hidden md:block lg:col-span-2"
                 style="background-image: url(https://placehold.co/1000);
                        background-size: cover;
                        background-position: center;">
                <x-overlay />
            </div>
        </main>

        @filepondScripts
        @livewireScripts
    </body>
</html>