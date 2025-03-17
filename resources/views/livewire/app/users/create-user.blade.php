<div class="max-w-screen-lg mx-auto space-y-5">
    <border class="flex items-center justify-between p-5 bg-white border rounded-lg border-slate-200">
        <div class="flex items-center gap-3 sm:gap-5">
            <x-tooltip text="Back" dir="bottom">
                <a x-ref="content" href="{{ route('app.users.index', ['role' => \App\Enums\UserRole::ALL->value, 'status' => \App\Enums\UserStatus::ACTIVE->value]) }}" wire:navigate>
                    <x-icon-button>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    </x-icon-button>
                </a>
            </x-tooltip>
        
            <div>
                <h2 class="text-lg font-semibold">Create User</h2>
                <p class="max-w-sm text-xs">Create a new user here.</p>
            </div>
        </div>

        <x-actions>
            Hello
        </x-actions>
    </border>

    <form x-data="{
            first_name: @entangle('first_name'),
            last_name: @entangle('last_name'),
            phone: @entangle('phone'),
            account_details: @entangle('account_details'),
        }"
        wire:submit='store'
        >
        @csrf
        <section class="space-y-5"> 
            {{-- Personal Details --}}
            <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
                <hgroup>
                    <h3 class="font-semibold">Personal and Contact Information</h3>
                    <p class="text-xs">Enter your first and last name as well as your phone number</p>
                </hgroup>

                <div class="w-full space-y-5 md:w-1/2">
                    <x-form.input-group>
                        <x-form.input-label for="first_name">Enter your first and last name</x-form.input-label>
                        <x-form.input-text class="capitalize" wire:model.live='first_name' id="first_name" label="First Name" />
                        <x-form.input-error field="first_name" />
                        <x-form.input-text class="capitalize" wire:model.live='last_name' id="last_name" label="Last Name" />
                        <x-form.input-error field="last_name" />
                    </x-form.input-group>

                    <x-form.input-group>
                        <x-form.input-label for="phone">Enter your phone number</x-form.input-label>
                        <x-form.input-text wire:model.live='phone' maxlength="11" id="phone" label="Contact Number" />
                        <x-form.input-error field="phone" />
                    </x-form.input-gro>
                </div>
            </div>

            {{-- Account Details --}}
            <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
                <hgroup>
                    <h3 class="font-semibold">Account Details</h3>
                    <p class="text-xs">Enter user credentials that will be used for logging in</p>
                </hgroup>

                <div class="w-full space-y-5 md:w-1/2">
                    <x-form.input-group>
                        <x-form.input-label for="email">Enter email address</x-form.input-label>
                        <x-form.input-text type="email" wire:model.live='email' id="email" label="Email" />
                        <x-form.input-error field="email" />
                    </x-form.input-group>

                    <x-form.input-group>
                        <x-form.input-label for="role">Select a role</x-form.input-label>
                        <x-form.select wire:model.live='role' id="role">
                            <option value="">Select a Role</option>
                            <option value="{{ \App\Enums\UserRole::GUEST->value }}">Guest</option>
                            <option value="{{ \App\Enums\UserRole::RECEPTIONIST->value }}">Receptionist</option>
                            <option value="{{ \App\Enums\UserRole::ADMIN->value }}">Admin</option>
                        </x-form.select>
                        <x-form.input-error field="role" />
                    </x-form.input-group>

                    <x-form.input-group>
                        <x-form.input-label for="password">Enter password</x-form.input-label>
                        
                        <x-form.input-text label="Password" id="password" type="password" name="password" wire:model.defer='password' wire:keydown.debounce.150ms='validatePassword' />
                        <x-form.input-text label="Confirm Password" id="password_confirmation" type="password" name="password_confirmation" wire:model.defer='password_confirmation' />
                        
                        <x-form.input-error field="password" />
                        <x-form.input-error field="password_confirmation" />

                        {{-- Checklist for password --}}
                        <div class="p-3 space-y-5 border rounded-md border-slate-200">
                            <p class="text-xs font-semibold">Password must include the following</p>
    
                            <div class="space-y-1">
                                <div class="flex items-center gap-3 text-xs">
                                    @if ($checks['min'])
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-dashed"><path d="M10.1 2.182a10 10 0 0 1 3.8 0"/><path d="M13.9 21.818a10 10 0 0 1-3.8 0"/><path d="M17.609 3.721a10 10 0 0 1 2.69 2.7"/><path d="M2.182 13.9a10 10 0 0 1 0-3.8"/><path d="M20.279 17.609a10 10 0 0 1-2.7 2.69"/><path d="M21.818 10.1a10 10 0 0 1 0 3.8"/><path d="M3.721 6.391a10 10 0 0 1 2.7-2.69"/><path d="M6.391 20.279a10 10 0 0 1-2.69-2.7"/></svg>
                                    @endif
                                    <p @class(['transition-all ease-in-out duration-200', 'opacity-100' => $checks['min'], 'opacity-50' => !$checks['min']])>Minimum of 8 characters</p>
                                </div>
                                <div class="flex items-center gap-3 text-xs">
                                    @if ($checks['uppercase'])
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-dashed"><path d="M10.1 2.182a10 10 0 0 1 3.8 0"/><path d="M13.9 21.818a10 10 0 0 1-3.8 0"/><path d="M17.609 3.721a10 10 0 0 1 2.69 2.7"/><path d="M2.182 13.9a10 10 0 0 1 0-3.8"/><path d="M20.279 17.609a10 10 0 0 1-2.7 2.69"/><path d="M21.818 10.1a10 10 0 0 1 0 3.8"/><path d="M3.721 6.391a10 10 0 0 1 2.7-2.69"/><path d="M6.391 20.279a10 10 0 0 1-2.69-2.7"/></svg>
                                    @endif
                                    <p @class(['transition-all ease-in-out duration-200', 'opacity-100' => $checks['uppercase'], 'opacity-50' => !$checks['uppercase']])>One uppercase letter</p>
                                </div>
                                <div class="flex items-center gap-3 text-xs">
                                    @if ($checks['lowercase'])
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-dashed"><path d="M10.1 2.182a10 10 0 0 1 3.8 0"/><path d="M13.9 21.818a10 10 0 0 1-3.8 0"/><path d="M17.609 3.721a10 10 0 0 1 2.69 2.7"/><path d="M2.182 13.9a10 10 0 0 1 0-3.8"/><path d="M20.279 17.609a10 10 0 0 1-2.7 2.69"/><path d="M21.818 10.1a10 10 0 0 1 0 3.8"/><path d="M3.721 6.391a10 10 0 0 1 2.7-2.69"/><path d="M6.391 20.279a10 10 0 0 1-2.69-2.7"/></svg>
                                    @endif
                                    <p @class(['transition-all ease-in-out duration-200', 'opacity-100' => $checks['lowercase'], 'opacity-50' => !$checks['lowercase']])>One lowercase letter</p>
                                </div>
                                <div class="flex items-center gap-3 text-xs">
                                    @if ($checks['numbers'])
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-dashed"><path d="M10.1 2.182a10 10 0 0 1 3.8 0"/><path d="M13.9 21.818a10 10 0 0 1-3.8 0"/><path d="M17.609 3.721a10 10 0 0 1 2.69 2.7"/><path d="M2.182 13.9a10 10 0 0 1 0-3.8"/><path d="M20.279 17.609a10 10 0 0 1-2.7 2.69"/><path d="M21.818 10.1a10 10 0 0 1 0 3.8"/><path d="M3.721 6.391a10 10 0 0 1 2.7-2.69"/><path d="M6.391 20.279a10 10 0 0 1-2.69-2.7"/></svg>
                                    @endif
                                    <p @class(['transition-all ease-in-out duration-200', 'opacity-100' => $checks['numbers'], 'opacity-50' => !$checks['numbers']])>One number</p>
                                </div>
                                <div class="flex items-center gap-3 text-xs">
                                    @if ($checks['symbols'])
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-dashed"><path d="M10.1 2.182a10 10 0 0 1 3.8 0"/><path d="M13.9 21.818a10 10 0 0 1-3.8 0"/><path d="M17.609 3.721a10 10 0 0 1 2.69 2.7"/><path d="M2.182 13.9a10 10 0 0 1 0-3.8"/><path d="M20.279 17.609a10 10 0 0 1-2.7 2.69"/><path d="M21.818 10.1a10 10 0 0 1 0 3.8"/><path d="M3.721 6.391a10 10 0 0 1 2.7-2.69"/><path d="M6.391 20.279a10 10 0 0 1-2.69-2.7"/></svg>
                                    @endif
                                    <p @class(['transition-all ease-in-out duration-200', 'opacity-100' => $checks['symbols'], 'opacity-50' => !$checks['symbols']])>One special character </p>
                                </div>
                            </div>
                        </div>
                    </x-form.input-group>

                </div>
            </div>

            <x-primary-button type="button" wire:click="createUser">Create User</x-primary-button>
        </section>

        {{-- Modal for confirming invoice --}}
        <x-modal.full name="show-user-confirmation" maxWidth="sm">
            <div x-data="{ checked: false }" x-on:user-created.window="show = false">
                <section class="p-5 space-y-5">
                    <hgroup>
                        <h2 class="text-lg font-semibold">User Confirmation</h2>
                        <p class="text-xs">Confirm that the user details entered are correct.</p>
                    </hgroup>

                    <div class="px-3 py-2 bg-white border rounded-md border-slate-200">
                        <x-form.input-checkbox x-model="checked" id="checked" label="The information I have provided is true and correct." />
                    </div>
                    
                    <x-loading wire:loading wire:target='store'>Creating user, please wait</x-loading>
                    
                    <div class="flex justify-end gap-1">
                        <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                        <x-primary-button x-bind:disabled="!checked">Create User</x-primary-button>
                    </div>
                </section>
            </div>
        </x-modal.full> 
    </form>
</div>
