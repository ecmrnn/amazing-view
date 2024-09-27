<x-tooltip text="Dashboard" dir="right">
    <x-app-nav-link x-ref="content" :active="Request::is('dashboard')" href="{{ route('dashboard') }}" class="flex items-center gap-2 p-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chart-pie"><path d="M21 12c.552 0 1.005-.449.95-.998a10 10 0 0 0-8.953-8.951c-.55-.055-.998.398-.998.95v8a1 1 0 0 0 1 1z"/><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/></svg>
        <span x-show="expanded" class="text-xs font-bold">Dashboard</span>
    </x-app-nav-link>
</x-tooltip>

<x-tooltip text="Guests" dir="right">
    <x-app-nav-link x-ref="content" :active="Request::is('rooms')" href="{{ route('dashboard') }}" class="flex items-center gap-2 p-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-round"><path d="M18 21a8 8 0 0 0-16 0"/><circle cx="10" cy="8" r="5"/><path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"/></svg>
        <span x-show="expanded" class="text-xs font-bold">Guests</span>
    </x-app-nav-link>
</x-tooltip>

<x-tooltip text="Reservations" dir="right">
    <x-app-nav-link x-ref="content" :active="Request::is('rooms')" href="{{ route('dashboard') }}" class="flex items-center gap-2 p-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-folder"><path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/></svg>
        <span x-show="expanded" class="text-xs font-bold">Reservations</span>
    </x-app-nav-link>
</x-tooltip>

<x-tooltip text="Rooms" dir="right">
    <x-app-nav-link x-ref="content" :active="Request::is('rooms')" href="{{ route('dashboard') }}" class="flex items-center gap-2 p-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-door-closed"><path d="M18 20V6a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v14"/><path d="M2 20h20"/><path d="M14 12v.01"/></svg>                            
        <span x-show="expanded" class="text-xs font-bold">Rooms</span>
    </x-app-nav-link>
</x-tooltip>