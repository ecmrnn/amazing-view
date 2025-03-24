<div class="max-w-screen-lg mx-auto space-y-5">
    <div class="flex items-center justify-between p-5 bg-white border rounded-lg border-slate-200">
        <div class="flex items-center gap-3 sm:gap-5">
            <x-tooltip text="Back" dir="bottom">
                <a x-ref="content" href="{{ route('app.users.index')}}" wire:navigate>
                    <x-icon-button>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    </x-icon-button>
                </a>
            </x-tooltip>
        
            <div class="flex items-center gap-5">
                <div>
                    <h2 class="text-lg font-semibold capitalize">{{ $user->first_name . ' ' . $user->last_name }}</h2>
                    <p class="max-w-sm text-xs">{{ $user->email }}</p>
                </div>

                <x-status type="session" :status="$session_status" />
            </div>
        </div>

        <x-actions>
            <div class="space-y-1">
                <a href="{{ route('app.users.edit', ['user' => $user->uid]) }}" wire:navigate>
                    <x-action-button>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings-2"><path d="M20 7h-9"/><path d="M14 17H5"/><circle cx="17" cy="17" r="3"/><circle cx="7" cy="7" r="3"/></svg>
                        <p>Edit</p>
                    </x-action-button>
                </a>
                @if ($session_status == \App\Enums\SessionStatus::ONLINE->value)
                    <x-action-button x-on:click="$dispatch('open-modal', 'force-logout'); dropdown = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                        <p>Force Logout</p>
                    </x-action-button>
                @endif
            </div>
        </x-actions>
    </div>

    <section class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
        <article class="space-y-3 sm:space-y-5">
            <hgroup>
                <h3 class="font-semibold">Personal Details</h3>
                <p class="text-xs">View user&apos;s personal details here</p>
            </hgroup>
    
            <div class="grid grid-cols-1 gap-3 p-5 border rounded-md border-slate-200 sm:grid-cols-2">
                {{-- Name --}}
                <div class="flex items-center gap-3">
                    <x-icon>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-signature"><path d="m21 17-2.156-1.868A.5.5 0 0 0 18 15.5v.5a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1c0-2.545-3.991-3.97-8.5-4a1 1 0 0 0 0 5c4.153 0 4.745-11.295 5.708-13.5a2.5 2.5 0 1 1 3.31 3.284"/><path d="M3 21h18"/></svg>
                    </x-icon>
                    <div>
                        <p class="text-sm font-semibold">{{ $user->name() }}</p>
                        <p class="text-xs">Name</p>
                    </div>
                </div>
                {{-- Phone --}}
                <div class="flex items-center gap-3">
                    <x-icon>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-smartphone"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/></svg>
                    </x-icon>
                    <div>
                        <p class="text-sm font-semibold">{{ substr($user->phone, 0, 4) . ' ' . substr($user->phone, 4, 3) . ' ' . substr($user->phone, 7) }}</p>
                        <p class="text-xs">Phone</p>
                    </div>
                </div>
                {{-- Address --}}
                @if ($user->address)
                    <div class="flex items-center gap-3 sm:col-span-2">
                        <x-icon>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pinned"><path d="M18 8c0 3.613-3.869 7.429-5.393 8.795a1 1 0 0 1-1.214 0C9.87 15.429 6 11.613 6 8a6 6 0 0 1 12 0"/><circle cx="12" cy="8" r="2"/><path d="M8.714 14h-3.71a1 1 0 0 0-.948.683l-2.004 6A1 1 0 0 0 3 22h18a1 1 0 0 0 .948-1.316l-2-6a1 1 0 0 0-.949-.684h-3.712"/></svg>
                        </x-icon>
                        <div>
                            <p class="text-sm font-semibold line-clamp-1 hover:line-clamp-none">{{ $user->address }}</p>
                            <p class="text-xs">Address</p>
                        </div>
                    </div>
                @endif
            </div>
        </article>

        <article class="space-y-3 sm:space-y-5">
            <hgroup>
                <h3 class="font-semibold">Account Details</h3>
                <p class="text-xs">View user credentials here</p>
            </hgroup>

            <div class="grid grid-cols-1 gap-3 p-5 border rounded-md border-slate-200 sm:grid-cols-2">
                {{-- User ID --}}
                <div class="flex items-center gap-3">
                    <x-icon>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-id-card"><path d="M16 10h2"/><path d="M16 14h2"/><path d="M6.17 15a3 3 0 0 1 5.66 0"/><circle cx="9" cy="11" r="2"/><rect x="2" y="5" width="20" height="14" rx="2"/></svg>
                    </x-icon>
                    <div class="overflow-hidden">
                        <p class="text-sm font-semibold break-words line-clamp-1 hover:line-clamp-2">{{ $user->uid }}</p>
                        <p class="text-xs">User ID</p>
                    </div>
                </div>
                {{-- Email --}}
                <div class="flex items-center gap-3">
                    <x-icon>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-at-sign"><circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"/></svg>
                    </x-icon>
                    <div class="overflow-hidden">
                        <a href="mailto:{{ $user->email }}" class="text-sm font-semibold break-words line-clamp-1 hover:line-clamp-2">{{ $user->email }}</a>
                        <p class="text-xs">Email</p>
                    </div>
                </div>
                {{-- Role --}}
                <div class="flex items-center gap-3">
                    <x-icon>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-key-round"><path d="M2.586 17.414A2 2 0 0 0 2 18.828V21a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h1a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h.172a2 2 0 0 0 1.414-.586l.814-.814a6.5 6.5 0 1 0-4-4z"/><circle cx="16.5" cy="7.5" r=".5" fill="currentColor"/></svg>
                    </x-icon>
                    <div>
                        <p class="text-sm font-semibold">{{ $user->role() }}</p>
                        <p class="text-xs">Role</p>
                    </div>
                </div>
            </div>
        </article>
    </section>

    @if ($user->reservations->count() > 0)
        <section class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
            <hgroup>
                <h3 class="font-semibold">Reservations</h3>
                <p class="text-xs">List of {{ ucwords($user->first_name) }}&apos;s reservations</p>
            </hgroup>

            <livewire:tables.guest-reservation-table :user="$user" />
        </section>
    @endif

    <section class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
        <hgroup>
            <h3 class="font-semibold">Recent Activities</h3>
            <p class="text-xs">List of {{ ucwords($user->first_name) }}&apos;s activities</p>
        </hgroup>

        {{-- Insert logged activities table here --}}
    </section>
    
    {{-- Force Logout --}}
    <x-modal.full name='force-logout' maxWidth='sm'>
        <livewire:app.users.force-logout :user="$user" />
    </x-modal.full>
</div>
