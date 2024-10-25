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

    @if (Auth::user()->role == \App\Models\User::ROLE_ADMIN)
        <div class="p-1 border rounded-lg">
            <x-tooltip text="Users" dir="right">
                <x-app-nav-link x-ref="content" :active="Request::is('users')" href="{{ route('app.users.index') }}" x-bind:class="expanded ? '' : '*:mx-auto'" class="flex items-center gap-3 p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-cog"><circle cx="18" cy="15" r="3"/><circle cx="9" cy="7" r="4"/><path d="M10 15H6a4 4 0 0 0-4 4v2"/><path d="m21.7 16.4-.9-.3"/><path d="m15.2 13.9-.9-.3"/><path d="m16.6 18.7.3-.9"/><path d="m19.1 12.2.3-.9"/><path d="m19.6 18.7-.4-1"/><path d="m16.8 12.3-.4-1"/><path d="m14.3 16.6 1-.4"/><path d="m20.7 13.8 1-.4"/></svg>
                    <span x-show="expanded" class="text-sm font-semibold">Users</span>
                </x-app-nav-link>
            </x-tooltip>
            <x-tooltip text="Reports" dir="right">
                <x-app-nav-link x-ref="content" :active="Request::is('profile')" href="{{ route('app.reports.index') }}" x-bind:class="expanded ? '' : '*:mx-auto'" class="flex items-center gap-3 p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chart-area"><path d="M3 3v16a2 2 0 0 0 2 2h16"/><path d="M7 11.207a.5.5 0 0 1 .146-.353l2-2a.5.5 0 0 1 .708 0l3.292 3.292a.5.5 0 0 0 .708 0l4.292-4.292a.5.5 0 0 1 .854.353V16a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1z"/></svg>
                    <span x-show="expanded" class="text-sm font-semibold">Reports</span>
                </x-app-nav-link>
            </x-tooltip>
            <x-tooltip text="Content" dir="right">
                <x-app-nav-link x-ref="content" :active="Request::is('profile')" href="{{ route('app.contents.index') }}" x-bind:class="expanded ? '' : '*:mx-auto'" class="flex items-center gap-3 p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-monitor-smartphone"><path d="M18 8V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h8"/><path d="M10 19v-3.96 3.15"/><path d="M7 19h5"/><rect width="6" height="10" x="16" y="12" rx="2"/></svg>
                    <span x-show="expanded" class="text-sm font-semibold">Content</span>
                </x-app-nav-link>
            </x-tooltip>
        </div>
    @endif

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
    @can('read guests')
        <x-nav-link :active="Request::is('app/guests*')" href="{{ route('app.guests.index') }}">Guests</x-nav-link>
    @endcan
    @can('read reservations')
        <x-nav-link :active="Request::is('app/reservations*')" href="{{ route('app.reservations.index') }}">Reservations</x-nav-link>
    @endcan
    @can('read rooms')
        <x-nav-link :active="Request::is('app/rooms*')" href="{{ route('app.rooms.index') }}">Rooms</x-nav-link>
    @endcan
    @can('read billings')
        <x-nav-link :active="Request::is('app/billings*')" href="{{ route('app.billings.index') }}">Billing</x-nav-link>
    @endcan

    @if (Auth::user()->role == \App\Models\User::ROLE_ADMIN)
        <x-nav-link :active="Request::is('app/billings*')" href="{{ route('app.billings.index') }}">Users</x-nav-link>
        <x-nav-link :active="Request::is('app/billings*')" href="{{ route('app.billings.index') }}">Reports</x-nav-link>
        <x-nav-link :active="Request::is('app/billings*')" href="{{ route('app.billings.index') }}">Content</x-nav-link>
    @endif

    <x-nav-link :active="Request::is('profile')" href="{{ route('profile.edit') }}">Profile</x-nav-link>
    <x-nav-link :active="Request::is('profile')" href="{{ route('profile.edit') }}">Settings</x-nav-link>
@endif