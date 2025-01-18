@props(['screen' => 'desktop'])

@if ($screen == 'desktop')
    <div>
        <x-tooltip text="Dashboard" dir="right">
            <x-app-nav-link x-ref="content" :active="Request::is('dashboard')" href="{{ route('dashboard') }}" x-bind:class="expanded ? '' : '*:mx-auto'" class="flex items-center gap-3 p-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                <span x-show="expanded" class="text-xs font-semibold">Dashboard</span>
            </x-app-nav-link>
        </x-tooltip>

        @can('read guests')
            <x-tooltip text="Guests" dir="right">
                <x-app-nav-link x-ref="content" :active="Request::is('app/guests*')" href="{{ route('app.guests.index') }}" x-bind:class="expanded ? '' : '*:mx-auto'" class="flex items-center gap-3 p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-concierge-bell"><path d="M3 20a1 1 0 0 1-1-1v-1a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v1a1 1 0 0 1-1 1Z"/><path d="M20 16a8 8 0 1 0-16 0"/><path d="M12 4v4"/><path d="M10 4h4"/></svg>
                    <span x-show="expanded" class="text-xs font-semibold">Guests</span>
                </x-app-nav-link>
            </x-tooltip>
        @endcan

        @can('read reservations')
            <x-tooltip text="Reservations" dir="right">
                <x-app-nav-link x-ref="content" :active="Request::is('app/reservations*')" href="{{ route('app.reservations.index') }}" x-bind:class="expanded ? '' : '*:mx-auto'" class="flex items-center gap-3 p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-folder"><path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/></svg>
                    <span x-show="expanded" class="text-xs font-semibold">Reservations</span>
                </x-app-nav-link>
            </x-tooltip>
        @endcan


        @can('read rooms')
            <x-tooltip text="Rooms" dir="right">
                <x-app-nav-link x-ref="content" :active="Request::is('app/rooms*')" href="{{ route('app.rooms.index') }}" x-bind:class="expanded ? '' : '*:mx-auto'" class="flex items-center gap-3 p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-door-closed"><path d="M18 20V6a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v14"/><path d="M2 20h20"/><path d="M14 12v.01"/></svg>
                    <span x-show="expanded" class="text-xs font-semibold">Rooms</span>
                </x-app-nav-link>
            </x-tooltip>
        @endcan

        @can('read billings')
            <x-tooltip text="Billings" dir="right">
                <x-app-nav-link x-ref="content" :active="Request::is('app/billings*')" href="{{ route('app.billings.index') }}" x-bind:class="expanded ? '' : '*:mx-auto'" class="flex items-center gap-3 p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wallet"><path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/></svg>
                    <span x-show="expanded" class="text-xs font-semibold">Billings</span>
                </x-app-nav-link>
            </x-tooltip>
        @endcan
    </div>

    @if (Auth::user()->role == \App\Models\User::ROLE_ADMIN)
        <div class="pt-2 border-t border-slate-200">
            <x-tooltip text="Users" dir="right">
                <x-app-nav-link x-ref="content" :active="Request::is('app/users*')" href="{{ route('app.users.index') }}" x-bind:class="expanded ? '' : '*:mx-auto'" class="flex items-center gap-3 p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <span x-show="expanded" class="text-xs font-semibold">Users</span>
                </x-app-nav-link>
            </x-tooltip>
            
            <x-tooltip text="Reports" dir="right">
                <x-app-nav-link x-ref="content" :active="Request::is('app/reports*')" href="{{ route('app.reports.index') }}" x-bind:class="expanded ? '' : '*:mx-auto'" class="flex items-center gap-3 p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chart-area"><path d="M3 3v16a2 2 0 0 0 2 2h16"/><path d="M7 11.207a.5.5 0 0 1 .146-.353l2-2a.5.5 0 0 1 .708 0l3.292 3.292a.5.5 0 0 0 .708 0l4.292-4.292a.5.5 0 0 1 .854.353V16a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1z"/></svg>
                    <span x-show="expanded" class="text-xs font-semibold">Reports</span>
                </x-app-nav-link>
            </x-tooltip>

            <x-tooltip text="Content" dir="right">
                <x-app-nav-link x-ref="content" :active="Request::is('app/content*')" href="{{ route('app.contents.index') }}" x-bind:class="expanded ? '' : '*:mx-auto'" class="flex items-center gap-3 p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-monitor-smartphone"><path d="M18 8V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h8"/><path d="M10 19v-3.96 3.15"/><path d="M7 19h5"/><rect width="6" height="10" x="16" y="12" rx="2"/></svg>
                    <span x-show="expanded" class="text-xs font-semibold">Content</span>
                </x-app-nav-link>
            </x-tooltip>
        </div>
    @endif
@elseif ($screen == 'mobile')
    <div class="grid w-full">
        <x-app-nav-link x-ref="content" :active="Request::is('dashboard')" href="{{ route('dashboard') }}" class="flex items-center gap-3 p-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
            <span class="text-xs font-semibold">Dashboard</span>
        </x-app-nav-link>

        @can('read guests')
            <x-app-nav-link x-ref="content" :active="Request::is('app/guests*')" href="{{ route('app.guests.index') }}" class="flex items-center gap-3 p-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-concierge-bell"><path d="M3 20a1 1 0 0 1-1-1v-1a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v1a1 1 0 0 1-1 1Z"/><path d="M20 16a8 8 0 1 0-16 0"/><path d="M12 4v4"/><path d="M10 4h4"/></svg>
                <span class="text-xs font-semibold">Guests</span>
            </x-app-nav-link>
        @endcan
        @can('read reservations')
            <x-app-nav-link x-ref="content" :active="Request::is('app/reservations*')" href="{{ route('app.reservations.index') }}" class="flex items-center gap-3 p-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-folder"><path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/></svg>
                <span class="text-xs font-semibold">Reservations</span>
            </x-app-nav-link>
        @endcan
        @can('read rooms')
            <x-app-nav-link x-ref="content" :active="Request::is('app/rooms*')" href="{{ route('app.rooms.index') }}" class="flex items-center gap-3 p-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-door-closed"><path d="M18 20V6a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v14"/><path d="M2 20h20"/><path d="M14 12v.01"/></svg>
                <span class="text-xs font-semibold">Rooms</span>
            </x-app-nav-link>
        @endcan
        @can('read billings')
            <x-app-nav-link x-ref="content" :active="Request::is('app/billings*')" href="{{ route('app.billings.index') }}" class="flex items-center gap-3 p-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wallet"><path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/></svg>
                <span class="text-xs font-semibold">Billings</span>
            </x-app-nav-link>
        @endcan
    </div>

    @if (Auth::user()->role == \App\Models\User::ROLE_ADMIN)
        <div class="grid w-full pt-2 my-2 border-t border-slate-200">
            <x-app-nav-link x-ref="content" :active="Request::is('app/users*')" href="{{ route('app.users.index') }}" class="flex items-center gap-3 p-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span class="text-xs font-semibold">Users</span>
            </x-app-nav-link>
            
            <x-app-nav-link x-ref="content" :active="Request::is('app/reports*')" href="{{ route('app.reports.index') }}" class="flex items-center gap-3 p-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chart-area"><path d="M3 3v16a2 2 0 0 0 2 2h16"/><path d="M7 11.207a.5.5 0 0 1 .146-.353l2-2a.5.5 0 0 1 .708 0l3.292 3.292a.5.5 0 0 0 .708 0l4.292-4.292a.5.5 0 0 1 .854.353V16a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1z"/></svg>
                <span class="text-xs font-semibold">Reports</span>
            </x-app-nav-link>

            <x-app-nav-link x-ref="content" :active="Request::is('app/content*')" href="{{ route('app.contents.index') }}" class="flex items-center gap-3 p-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-monitor-smartphone"><path d="M18 8V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h8"/><path d="M10 19v-3.96 3.15"/><path d="M7 19h5"/><rect width="6" height="10" x="16" y="12" rx="2"/></svg>
                <span class="text-xs font-semibold">Content</span>
            </x-app-nav-link>

            <x-app-nav-link x-ref="content" :active="Request::is('profile*')" href="{{ route('profile.edit') }}" class="flex items-center gap-3 p-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-user"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="10" r="3"/><path d="M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662"/></svg>
                <span class="text-xs font-semibold">Profile</span>
            </x-app-nav-link>
        </div>
    @endif
@endif