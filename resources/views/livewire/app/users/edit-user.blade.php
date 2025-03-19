<div class="max-w-screen-lg mx-auto space-y-5">
    <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-5">
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
                    <x-action-button x-on:click="$dispatch('open-modal', 'reset-password'); dropdown = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                        <p>Reset Password</p>
                    </x-action-button>
                    @if ($session_status == \App\Enums\SessionStatus::ONLINE->value)
                        <x-action-button x-on:click="$dispatch('open-modal', 'force-logout'); dropdown = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                            <p>Force Logout</p>
                        </x-action-button>
                    @endif
                    @if ($user->status == \App\Enums\UserStatus::INACTIVE->value)
                        <x-action-button x-on:click="$dispatch('open-modal', 'activate-user'); dropdown = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock-keyhole-open"><circle cx="12" cy="16" r="1"/><rect width="18" height="12" x="3" y="10" rx="2"/><path d="M7 10V7a5 5 0 0 1 9.33-2.5"/></svg>
                            <p>Activate</p>
                        </x-action-button>
                    @else
                        <x-action-button x-on:click="$dispatch('open-modal', 'deactivate-user'); dropdown = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock-keyhole"><circle cx="12" cy="16" r="1"/><rect x="3" y="10" width="18" height="12" rx="2"/><path d="M7 10V7a5 5 0 0 1 10 0v3"/></svg>
                            <p>Deactivate</p>
                        </x-action-button>
                    @endif
                </div>
            </x-actions>
        </div>
    </div>
    
    <form x-data="{
            first_name: @entangle('first_name'),
            last_name: @entangle('last_name'),
            phone: @entangle('phone'),
        }"
        wire:submit='submit'>
        @csrf
        <section class="space-y-5">
            {{-- Personal Details --}}
            <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
                <hgroup>
                    <h3 class="font-semibold">Personal Information</h3>
                    <p class="text-xs">Update this user&apos;s personal information</p>
                </hgroup>
                
                <div class="w-full space-y-5 md:w-1/2">
                    {{-- First & Last name --}}
                    <x-form.input-group>
                        <x-form.input-label for="first_name">First &amp; Last Name</x-form.input-label>
                        <x-form.input-text class="capitalize" wire:model.live='first_name' id="first_name" label="First Name" />
                        <x-form.input-error field="first_name" />
                        <x-form.input-text class="capitalize" wire:model.live='last_name' id="last_name" label="Last Name" />
                        <x-form.input-error field="last_name" />
                    </x-form.input-group>
                    {{-- Contact Number --}}
                    <x-form.input-group>
                        <x-form.input-label for="phone">Contact Number</x-form.input-label>
                        <x-form.input-text wire:model.live='phone' maxlength="11" id="phone" label="Contact Number" />
                        <x-form.input-error field="phone" />
                    </x-form.input-group>
                    {{-- Address --}}
                    <x-form.input-group>
                        <x-form.input-label for='address'>Address</x-form.input-label>
                        <x-form.input-text id="address" wire:model.live='address' name="address" label="Address" />
                        <x-form.input-error field="address" />
                    </x-form.input-group>
                </div>
            </div>
    
            {{-- Account Details --}}
            <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
                <div class="flex items-start justify-between gap-5">
                    <hgroup>
                        <h3 class="font-semibold">Account Details</h3>
                        <p class="text-xs">Update this user&apos;s role</p>
                    </hgroup>

                    <x-status type="user" :status="$user->status" />
                </div>

                {{-- Email --}}
                <div class="w-full space-y-5 md:w-1/2">
                    <x-form.input-group>
                        <x-form.input-label for="email">Email Address</x-form.input-label>
                        <x-form.input-text type="email" wire:model='email' id="email" label="Email" disabled />
                        <x-form.input-error field="email" />
                    </x-form.input-group>

                    <x-form.input-group>
                        <x-form.input-label for="role">Assign a new role</x-form.input-label>
                        <x-form.select id="role" wire:model.live='role'>
                            <option value="">Select a Role</option>
                            <option value="1">Guest</option>
                            <option value="2">Receptionist</option>
                            <option value="3">Admin</option>
                        </x-form.select>
                        <x-form.input-error field="role" />
                    </x-form.input-group>
                </div>
            </div>
    
            <div class="flex items-center gap-5">
                <x-primary-button>Save Changes</x-primary-button>
                
                <div>
                    <x-loading wire:loading wire:target='createUser'>Checking details, please wait</x-loading>
                </div>
            </div>
        </section>
    
    </form>

    {{-- Modal for confirming invoice --}}
    <x-modal.full name="show-user-confirmation" maxWidth="sm">
        <form x-data="{ checked: false }" x-on:user-updated.window="show = false" wire:submit='update'>
            <section class="p-5 space-y-5">
                <hgroup>
                    <h2 class="text-lg font-semibold">User Confirmation</h2>
                    <p class="text-xs">Confirm that the user details entered are correct.</p>
                </hgroup>

                <div class="px-3 py-2 bg-white border rounded-md border-slate-200">
                    <x-form.input-checkbox x-model="checked" id="checked" label="The information I have provided is true and correct." />
                </div>

                <x-form.input-group>
                    <x-form.input-label for='password'>Enter your password</x-form.input-label>
                    <x-form.input-text id="password" wire:model.live='password' name="password" type="password" label="Password" />
                    <x-form.input-error field="password" />
                </x-form.input-group>

                <x-loading wire:loading wire:target='update'>Updating changes, please wait</x-loading>

                <div class="flex justify-end gap-1">
                    <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                    <x-primary-button x-bind:disabled="!checked">Save Changes</x-primary-button>
                </div>
            </section>
        </form>
    </x-modal.full>

    {{-- Deactivating user --}}
    <x-modal.full name='deactivate-user' maxWidth='sm'>
        <livewire:app.users.deactivate-user :user="$user" />
    </x-modal.full>

    {{-- Activating user --}}
    <x-modal.full name='activate-user' maxWidth='sm'>
        <livewire:app.users.activate-user :user="$user" />
    </x-modal.full>

    {{-- Resetting password --}}
    <x-modal.full name='reset-password' maxWidth='sm'>
        <livewire:app.users.reset-password :user="$user" />
    </x-modal.full>

    {{-- Force Logout --}}
    <x-modal.full name='force-logout' maxWidth='sm'>
        <livewire:app.users.force-logout :user="$user" />
    </x-modal.full>
</div>
