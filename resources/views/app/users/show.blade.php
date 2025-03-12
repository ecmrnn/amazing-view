<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between gap-3">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">
                    {{ __('Users') }}
                </h1>
                <p class="text-xs">Manage your users here</p>
            </hgroup>
        </div>
    </x-slot:header>

    {{-- User  Table --}}
    <div class="p-5 space-y-5 bg-white rounded-lg">
        <div class="flex items-center gap-3 sm:gap-5">
            <x-tooltip text="Back" dir="bottom">
                <a x-ref="content" href="{{ route('app.users.index')}}" wire:navigate>
                    <x-icon-button>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    </x-icon-button>
                </a>
            </x-tooltip>
        
            <div>
                <h2 class="text-lg font-semibold">{{ $user->name() }}</h2>
                <p class="max-w-sm text-xs">{{ $user->email }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 xl:grid-cols-3">
            <div class="space-y-1">
                <section class="p-3 space-y-5 border rounded-lg border-slate-200 sm:p-5">
                
                    <article class="space-y-3 sm:space-y-5">
                        <h3 class="font-semibold">Personal Details</h3>
                
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            {{-- Name --}}
                            <div class="flex items-center gap-3">
                                <div class="p-2 border rounded-lg border-slate-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-signature"><path d="m21 17-2.156-1.868A.5.5 0 0 0 18 15.5v.5a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1c0-2.545-3.991-3.97-8.5-4a1 1 0 0 0 0 5c4.153 0 4.745-11.295 5.708-13.5a2.5 2.5 0 1 1 3.31 3.284"/><path d="M3 21h18"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold">{{ $user->name() }}</p>
                                    <p class="text-xs">Name</p>
                                </div>
                            </div>
                            {{-- Phone --}}
                            <div class="flex items-center gap-3">
                                <div class="p-2 border rounded-lg border-slate-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-smartphone"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold">{{ $user->phone }}</p>
                                    <p class="text-xs">Phone</p>
                                </div>
                            </div>
                            {{-- Address --}}
                            <div class="flex items-center gap-3">
                                <div class="p-2 border rounded-lg border-slate-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pinned"><path d="M18 8c0 3.613-3.869 7.429-5.393 8.795a1 1 0 0 1-1.214 0C9.87 15.429 6 11.613 6 8a6 6 0 0 1 12 0"/><circle cx="12" cy="8" r="2"/><path d="M8.714 14h-3.71a1 1 0 0 0-.948.683l-2.004 6A1 1 0 0 0 3 22h18a1 1 0 0 0 .948-1.316l-2-6a1 1 0 0 0-.949-.684h-3.712"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold line-clamp-1 hover:line-clamp-none">{{ $user->address }}</p>
                                    <p class="text-xs">Address</p>
                                </div>
                            </div>
                        </div>
                    </article>
                    <article class="space-y-3 sm:space-y-5">
                        <h3 class="font-semibold">Account Details</h3>
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            {{-- User ID --}}
                            <div class="flex items-center gap-3">
                                <div class="p-2 border rounded-lg border-slate-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-id-card"><path d="M16 10h2"/><path d="M16 14h2"/><path d="M6.17 15a3 3 0 0 1 5.66 0"/><circle cx="9" cy="11" r="2"/><rect x="2" y="5" width="20" height="14" rx="2"/></svg>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-sm font-semibold break-words line-clamp-1 hover:line-clamp-2">{{ $user->uid }}</p>
                                    <p class="text-xs">User ID</p>
                                </div>
                            </div>
                            {{-- Email --}}
                            <div class="flex items-center gap-3">
                                <div class="p-2 border rounded-lg border-slate-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-at-sign"><circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"/></svg>
                                </div>
                                <div class="overflow-hidden">
                                    <a href="mailto:{{ $user->email }}" class="text-sm font-semibold break-words line-clamp-1 hover:line-clamp-2">{{ $user->email }}</a>
                                    <p class="text-xs">Email</p>
                                </div>
                            </div>
                            {{-- Role --}}
                            <div class="flex items-center gap-3">
                                <div class="p-2 border rounded-lg border-slate-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings-2"><path d="M20 7h-9"/><path d="M14 17H5"/><circle cx="17" cy="17" r="3"/><circle cx="7" cy="7" r="3"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold">{{ $user->role() }}</p>
                                    <p class="text-xs">Role</p>
                                </div>
                            </div>
                        </div>
                    </article>
                </section>

                @if ($user->status == App\Models\User::STATUS_INACTIVE)
                    <x-note>
                        <p class="max-w-xs">This user is deactivated and does not have access to any resources of the system.</p>
                    </x-note>
                @endif
            </div>

            <div class="space-y-5 xl:col-span-2 xl:row-span-2">
                <hgroup>
                    <h3 class="font-semibold">Reservations</h3>
                    <p class="text-xs">List of {{ ucwords($user->first_name) }}&apos;s reservations</p>
                </hgroup>

                <livewire:tables.guest-reservation-table :user="$user" />
            </div>

            @if ($user->status == App\Models\User::STATUS_ACTIVE)
                {{-- Deactivate --}}
                <section class="p-3 space-y-5 rounded-lg bg-red-200/50 sm:p-5">
                    <hgroup>
                        <h3 class="font-semibold text-red-500">Deactivate User</h3>
                        <p class="max-w-sm text-xs">To remove access from this user account, click the button below.</p>
                    </hgroup>

                    <div>
                        <x-danger-button type="button" x-on:click="$dispatch('open-modal', 'deactivate-user')">Deactivate User</x-danger-button>
                    </div>
                </section>
            @endif
        </div>
    </div>

    {{-- Deactivate User Modal --}}
    <x-modal.full name="deactivate-user" maxWidth="sm">
        <div x-data="{ checked: false }">
            <livewire:app.users.deactivate-user :user="$user" />
        </div>
    </x-modal.full> 
</x-app-layout>