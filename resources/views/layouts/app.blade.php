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

        <!-- Icons -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,200,0,0" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-inter text-zinc-800">
        <div class="flex flex-col min-h-screen bg-slate-100 sm:flex-row">
            @include('layouts.navigation')

            <div class="w-full p-3 space-y-3 sm:p-10 sm:space-y-5">
                <!-- Page Heading -->
                @isset($header)
                    <header>
                        {{ $header }}
                    </header>
                @endisset
                <!-- Page Content -->
                <main class="space-y-3 sm:space-y-5">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @filepondScripts
        @livewireChartsScripts
        @livewireScripts
        @livewireStyles
    </body>
</html>
