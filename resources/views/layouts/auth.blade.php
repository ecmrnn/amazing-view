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
    <body class="antialiased font-inter text-zinc-800">
        {{-- Back --}}
        
        <main class="grid min-h-screen grid-cols-1 lg:grid-cols-2">
            <div class="relative">
                {{ $slot }}

                <div class="absolute top-0 p-5 text-xs font-semibold -translate-x-1/2 left-1/2">
                    <x-nav-link href="/">Back to Home</x-nav-link>
                </div>
            </div>
            <!-- Image -->
            <div
                class="relative hidden m-4 overflow-hidden rounded-2xl lg:block"
                 style="background-image: url({{ asset('storage/global/login.jpg') }});
                        background-size: cover;
                        background-position: center;">

                <div class="absolute z-50 px-4 py-3 bg-white top-4 left-4 rounded-xl">
                    <hgroup class="space-y-1">
                        <h2 class="font-bold leading-none">Amazing View Mountain Resort</h2>
                        <p class="text-xs leading-none">Book a room now, amazing experience awaits!</p>
                    </hgroup>
                </div>

                <x-overlay />
            </div>
        </main>

        @filepondScripts
        @livewireScripts
    </body>
</html>