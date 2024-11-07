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
            {{-- <x-tooltip text="Buildings" dir="right">
                <x-app-nav-link x-ref="content" :active="Request::is('app/buildings*')" href="{{ route('app.users.index') }}" x-bind:class="expanded ? '' : '*:mx-auto'" class="flex items-center gap-3 p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hotel"><path d="M10 22v-6.57"/><path d="M12 11h.01"/><path d="M12 7h.01"/><path d="M14 15.43V22"/><path d="M15 16a5 5 0 0 0-6 0"/><path d="M16 11h.01"/><path d="M16 7h.01"/><path d="M8 11h.01"/><path d="M8 7h.01"/><rect x="4" y="2" width="16" height="20" rx="2"/></svg>
                    <span x-show="expanded" class="text-sm font-semibold">Buildings</span>
                </x-app-nav-link>
            </x-tooltip>

            <x-tooltip text="Amenities" dir="right">
                <x-app-nav-link x-ref="content" :active="Request::is('app/amenities*')" href="{{ route('app.users.index') }}" x-bind:class="expanded ? '' : '*:mx-auto'" class="flex items-center gap-3 p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-tv-minimal"><path d="M7 21h10"/><rect width="20" height="14" x="2" y="3" rx="2"/></svg>
                    <span x-show="expanded" class="text-sm font-semibold">Amenities</span>
                </x-app-nav-link>
            </x-tooltip> --}}

            <x-tooltip text="Users" dir="right">
                <x-app-nav-link x-ref="content" :active="Request::is('app/users*')" href="{{ route('app.users.index') }}" x-bind:class="expanded ? '' : '*:mx-auto'" class="flex items-center gap-3 p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-cog"><circle cx="18" cy="15" r="3"/><circle cx="9" cy="7" r="4"/><path d="M10 15H6a4 4 0 0 0-4 4v2"/><path d="m21.7 16.4-.9-.3"/><path d="m15.2 13.9-.9-.3"/><path d="m16.6 18.7.3-.9"/><path d="m19.1 12.2.3-.9"/><path d="m19.6 18.7-.4-1"/><path d="m16.8 12.3-.4-1"/><path d="m14.3 16.6 1-.4"/><path d="m20.7 13.8 1-.4"/></svg>
                    <span x-show="expanded" class="text-sm font-semibold">Users</span>
                </x-app-nav-link>
            </x-tooltip>
            
            <x-tooltip text="Reports" dir="right">
                <x-app-nav-link x-ref="content" :active="Request::is('app/reports*')" href="{{ route('app.reports.index') }}" x-bind:class="expanded ? '' : '*:mx-auto'" class="flex items-center gap-3 p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chart-area"><path d="M3 3v16a2 2 0 0 0 2 2h16"/><path d="M7 11.207a.5.5 0 0 1 .146-.353l2-2a.5.5 0 0 1 .708 0l3.292 3.292a.5.5 0 0 0 .708 0l4.292-4.292a.5.5 0 0 1 .854.353V16a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1z"/></svg>
                    <span x-show="expanded" class="text-sm font-semibold">Reports</span>
                </x-app-nav-link>
            </x-tooltip>

            <x-tooltip text="Content" dir="right">
                <x-app-nav-link x-ref="content" :active="Request::is('app/content*')" href="{{ route('app.contents.index') }}" x-bind:class="expanded ? '' : '*:mx-auto'" class="flex items-center gap-3 p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-monitor-smartphone"><path d="M18 8V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h8"/><path d="M10 19v-3.96 3.15"/><path d="M7 19h5"/><rect width="6" height="10" x="16" y="12" rx="2"/></svg>
                    <span x-show="expanded" class="text-sm font-semibold">Content</span>
                </x-app-nav-link>
            </x-tooltip>
        </div>
    @endif
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
        <x-nav-link :active="Request::is('app/users*')" href="{{ route('app.billings.index') }}">Users</x-nav-link>
        <x-nav-link :active="Request::is('app/reports*')" href="{{ route('app.billings.index') }}">Reports</x-nav-link>
        <x-nav-link :active="Request::is('app/content*')" href="{{ route('app.billings.index') }}">Content</x-nav-link>
    @endif

    <x-nav-link :active="Request::is('profile')" href="{{ route('profile.edit') }}">Profile</x-nav-link>
    <x-nav-link :active="Request::is('profile')" href="{{ route('profile.edit') }}">Settings</x-nav-link>
@endif