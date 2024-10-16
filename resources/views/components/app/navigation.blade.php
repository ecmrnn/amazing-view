@props(['screen' => 'desktop'])

@if ($screen == 'desktop')
    <div class="p-1 border rounded-lg">
        <x-tooltip text="Dashboard" dir="right">
            <x-app-nav-link x-ref="content" :active="Request::is('dashboard')" href="{{ route('dashboard') }}" x-bind:class="expanded ? '' : '*:mx-auto'" class="flex items-center gap-3 p-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chart-no-axes-combined"><path d="M12 16v5"/><path d="M16 14v7"/><path d="M20 10v11"/><path d="m22 3-8.646 8.646a.5.5 0 0 1-.708 0L9.354 8.354a.5.5 0 0 0-.707 0L2 15"/><path d="M4 18v3"/><path d="M8 14v7"/></svg>
                <span x-show="expanded" class="text-sm font-semibold">Dashboard</span>
            </x-app-nav-link>
        </x-tooltip>

        @can('read guests')
            <x-tooltip text="Guests" dir="right">
                <x-app-nav-link x-ref="content" :active="Request::is('app/guests*')" href="{{ route('app.guests.index') }}" x-bind:class="expanded ? '' : '*:mx-auto'" class="flex items-center gap-3 p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-concierge-bell"><path d="M3 20a1 1 0 0 1-1-1v-1a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v1a1 1 0 0 1-1 1Z"/><path d="M20 16a8 8 0 1 0-16 0"/><path d="M12 4v4"/><path d="M10 4h4"/></svg>
                    <span x-show="expanded" class="text-sm font-semibold">Guests</span>
                </x-app-nav-link>
            </x-tooltip>
        @endcan

        @can('read reservations')
            <x-tooltip text="Reservations" dir="right">
                <x-app-nav-link x-ref="content" :active="Request::is('app/reservations*')" href="{{ route('app.reservations.index') }}" x-bind:class="expanded ? '' : '*:mx-auto'" class="flex items-center gap-3 p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-folder"><path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/></svg>
                    <span x-show="expanded" class="text-sm font-semibold">Reservations</span>
                </x-app-nav-link>
            </x-tooltip>
        @endcan


        @can('read rooms')
            <x-tooltip text="Rooms" dir="right">
                <x-app-nav-link x-ref="content" :active="Request::is('app/rooms*')" href="{{ route('app.rooms.index') }}" x-bind:class="expanded ? '' : '*:mx-auto'" class="flex items-center gap-3 p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-door-closed"><path d="M18 20V6a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v14"/><path d="M2 20h20"/><path d="M14 12v.01"/></svg>                            
                    <span x-show="expanded" class="text-sm font-semibold">Rooms</span>
                </x-app-nav-link>
            </x-tooltip>
        @endcan

        @can('read billings')
            <x-tooltip text="Billings" dir="right">
                <x-app-nav-link x-ref="content" :active="Request::is('app/billings*')" href="{{ route('app.billings.index') }}" x-bind:class="expanded ? '' : '*:mx-auto'" class="flex items-center gap-3 p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-receipt-text"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M14 8H8"/><path d="M16 12H8"/><path d="M13 16H8"/></svg>
                    <span x-show="expanded" class="text-sm font-semibold">Billings</span>
                </x-app-nav-link>
            </x-tooltip>
        @endcan
    </div>

    <div class="p-1 border rounded-lg">        
        <x-tooltip text="Profile" dir="right">
            <x-app-nav-link x-ref="content" :active="Request::is('profile')" href="{{ route('profile.edit') }}" x-bind:class="expanded ? '' : '*:mx-auto'" class="flex items-center gap-3 p-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/></svg>
                <span x-show="expanded" class="text-sm font-semibold">Profile</span>
            </x-app-nav-link>
        </x-tooltip>

        <x-tooltip text="Settings" dir="right">
            <x-app-nav-link x-ref="content" :active="Request::is('settings')" href="{{ route('profile.edit') }}" x-bind:class="expanded ? '' : '*:mx-auto'" class="flex items-center gap-3 p-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                <span x-show="expanded" class="text-sm font-semibold">Profile</span>
            </x-app-nav-link>
        </x-tooltip>
    </div>
@elseif ($screen == 'mobile')
    <x-nav-link :active="Request::is('dashboard')" href="{{ route('dashboard') }}">Dashboard</x-nav-link>
    <x-nav-link :active="Request::is('app/guests*')" href="{{ route('app.guests.index') }}">Guests</x-nav-link>
    <x-nav-link :active="Request::is('app/reservations*')" href="{{ route('app.reservations.index') }}">Reservations</x-nav-link>
    <x-nav-link :active="Request::is('app/rooms*')" href="{{ route('app.rooms.index') }}">Rooms</x-nav-link>
    <x-nav-link :active="Request::is('app/billings*')" href="{{ route('app.billings.index') }}">Billing</x-nav-link>
    <x-nav-link :active="Request::is('profile')" href="{{ route('profile.edit') }}">Profile</x-nav-link>
    <x-nav-link :active="Request::is('profile')" href="{{ route('profile.edit') }}">Settings</x-nav-link>
@endif