<div class="relative flex flex-col gap-5 md:flex-row">
    <aside class="space-y-5 shrink-0 min-w-52">
        <p class="text-xs font-semibold">Filter User Status</p>
        
        <ul>
            <li>
                <x-side-nav-link :status="\App\Enums\UserStatus::ACTIVE->value" href="{{ route('app.users.index', ['role' => $role, 'status' => \App\Enums\UserStatus::ACTIVE->value]) }}">
                    <div class="flex items-center gap-1">
                        <span>Active Users</span>
                        @if ($user_by_status['active'] > 0)
                            <div class="text-xs">( {{ $user_by_status['active'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
            <li>
                <x-side-nav-link :status="\App\Enums\UserStatus::INACTIVE->value" href="{{ route('app.users.index', ['role' => $role, 'status' => \App\Enums\UserStatus::INACTIVE->value]) }}">
                    <div class="flex items-center gap-1">
                        <span>Inactive Users</span>
                        @if ($user_by_status['inactive'] > 0)
                            <div class="text-xs">( {{ $user_by_status['inactive'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
            <li class="flex items-center gap-2 py-3 text-xs font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-key-round"><path d="M2.586 17.414A2 2 0 0 0 2 18.828V21a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h1a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h.172a2 2 0 0 0 1.414-.586l.814-.814a6.5 6.5 0 1 0-4-4z"/><circle cx="16.5" cy="7.5" r=".5" fill="currentColor"/></svg>
                <span>Roles</span>
            </li>
            <li>
                <x-side-nav-link :role="\App\Enums\UserRole::ALL->value" href="{{ route('app.users.index', ['role' => \App\Enums\UserRole::ALL->value, 'status' => $status]) }}">
                    <div class="flex items-center gap-1">
                        <span>All</span>
                        @if ($user_by_role['all'] > 0)
                            <div class="text-xs">( {{ $user_by_role['all'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
            <li>
                <x-side-nav-link :role="\App\Enums\UserRole::GUEST->value" href="{{ route('app.users.index', ['role' => \App\Enums\UserRole::GUEST->value, 'status' => $status]) }}">
                    <div class="flex items-center gap-1">
                        <span>Guests</span>
                        @if ($user_by_role['guest'] > 0)
                            <div class="text-xs">( {{ $user_by_role['guest'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
            <li>
                <x-side-nav-link :role="\App\Enums\UserRole::RECEPTIONIST->value" href="{{ route('app.users.index', ['role' => \App\Enums\UserRole::RECEPTIONIST->value, 'status' => $status]) }}">
                    <div class="flex items-center gap-1">
                        <span>Receptionist</span>
                        @if ($user_by_role['receptionist'] > 0)
                            <div class="text-xs">( {{ $user_by_role['receptionist'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
            <li>
                <x-side-nav-link :role="\App\Enums\UserRole::ADMIN->value" href="{{ route('app.users.index', ['role' => \App\Enums\UserRole::ADMIN->value, 'status' => $status]) }}">
                    <div class="flex items-center gap-1">
                        <span>Admin</span>
                        @if ($user_by_role['admin'] > 0)
                            <div class="text-xs">( {{ $user_by_role['admin'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
        </ul>
    </aside>

    <div class="w-full space-y-5 overflow-x-hidden">
        {{-- Cards --}}
        <livewire:app.cards.user-cards />
        
        {{-- Room  Table --}}
        <div class="p-5 bg-white border rounded-lg border-slate-200">
            @if ($user_count > 0)
                <livewire:tables.user-table />
            @else
                <div class="font-semibold text-center">
                    <x-table-no-data.user />
                </div>
            @endif
        </div>
    </div>
</div>