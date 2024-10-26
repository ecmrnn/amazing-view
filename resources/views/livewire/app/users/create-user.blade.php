<form x-data="{
        first_name: @entangle('first_name'),
        last_name: @entangle('last_name'),
        phone: @entangle('phone'),
        account_details: @entangle('account_details'),
    }"

    class="grid gap-5 lg:grid-cols-2">
    @csrf
    <section>        
        {{-- Personal Details --}}
        <x-form.form-section>
            <x-form.form-header step="1" title="Personal Details" />

            <div x-show="!account_details" x-collapse.duration.1000ms>
                <x-form.form-body>
                    <div class="p-5 space-y-3">
                        <hgroup>
                            <h3 class="text-sm font-semibold">First &amp; Last Name</h3>
                            <p class="text-xs">Enter user&apos;s name</p>
                        </hgroup>
                        {{-- First & Last name --}}
                        <div class="grid items-start gap-3 sm:grid-cols-2">
                            <div class="space-y-1">
                                <x-form.input-text class="capitalize" wire:model.live='first_name'
                                    id="first_name" label="First Name" />
                                <x-form.input-error field="first_name" />
                            </div>
                            <div class="space-y-1">
                                <x-form.input-text class="capitalize" wire:model.live='last_name'
                                    id="last_name" label="Last Name" />
                                <x-form.input-error field="last_name" />
                            </div>
                        </div>
                        {{-- Contact Number --}}
                        <div class="space-y-3">
                            <hgroup>
                                <h3 class="text-sm font-semibold">Contact Number</h3>
                                <p class="max-w-xs text-xs">Enter user&apos;s contact number</p>
                            </hgroup>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <div class="space-y-1">
                                    <x-form.input-text wire:model.live='phone' maxlength="11" id="phone" label="Contact Number" />
                                    <x-form.input-error field="phone" />
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-1">
                            <x-primary-button type="button" x-on:click="$wire.accountDetails()">Account Details</x-primary-button>
                            <p class="max-w-xs text-xs font-semibold" wire:loading.delay wire:target="accountDetails()">Please wait while we load the next form.</p>
                        </div>
                    </div>
                </x-form.form-body>
            </div>
        </x-form.form-section>

        <x-line-vertical />

        {{-- Account Details --}}
        <x-form.form-section>
            <x-form.form-header step="2" title="Account Details" />

            <div x-show="account_details" x-collapse.duration.1000ms>
                <x-form.form-body>
                    <div class="p-5 space-y-3">
                        <hgroup>
                            <h3 class="text-sm font-semibold">Email &amp; Role</h3>
                            <p class="text-xs">Enter user&apos;s email and select the user&apos;s role</p>
                        </hgroup>
                        {{-- Email --}}
                        <div class="grid items-start gap-3 sm:grid-cols-2">
                            <div class="space-y-1">
                                <x-form.input-text type="email" wire:model.live='email'
                                    id="email" label="Email" />
                                <x-form.input-error field="email" />
                            </div>
                            <div class="h-full space-y-1">
                                <x-form.select class="h-full" wire:model.live='role'>
                                    <option value="">Select a Role</option>
                                    <option value="0">Guest</option>
                                    <option value="1">Receptionist</option>
                                    <option value="2">Admin</option>
                                </x-form.select>
                                <x-form.input-error field="role" />
                            </div>
                        </div>
                        {{-- Password and Confirmation Password --}}
                        <div class="space-y-3">
                            <hgroup>
                                <h3 class="text-sm font-semibold">Password</h3>
                                <p class="max-w-xs text-xs">Enter user&apos;s password</p>
                            </hgroup>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <div class="space-y-1">
                                    <x-form.input-text label="Password" id="password" type="password" name="password" wire:model.live='password' />
                                    <x-form.input-error field="password" />
                                </div>
                                <div class="space-y-1">
                                    <x-form.input-text label="Confirm Password" id="password_confirmation" type="password" name="password_confirmation" wire:model.live='password_confirmation' />
                                    <x-form.input-error field="password_confirmation" />
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-1">
                            <x-primary-button type="button" wire:click="createUser">Create User</x-primary-button>
                            <p class="max-w-xs text-xs font-semibold" wire:loading.delay wire:target="createUser">Please wait while we load the next form.</p>
                        </div>
                    </div>
                </x-form.form-body>
            </div>
        </x-form.form-section>

    </section>

    <section>
    </section>
    
    {{-- Modal for confirming invoice --}}
    <x-modal.full name="show-user-confirmation" maxWidth="sm">
        <div x-data="{ checked: false }">
            <section class="p-5 space-y-5 bg-white">
                <hgroup>
                    <h2 class="text-sm font-semibold text-center capitalize">User Confirmation</h2>
                    <p class="max-w-sm text-xs text-center text-zinc-800">Confirm that the user details entered are correct.</p>
                </hgroup>

                <div class="px-3 py-2 border border-gray-300 rounded-md">
                    <x-form.input-checkbox x-model="checked" id="checked" label="The information I have provided is true and correct." />
                </div>
                
                <div class="flex items-center justify-center gap-1">
                    <x-secondary-button type="button" class="text-xs" x-on:click="show = false">Cancel</x-secondary-button>
                    <x-primary-button type="button" x-bind:disabled="!checked" class="text-xs" x-on:click="$wire.store(); show = false;">
                        Create User
                    </x-primary-button>
                </div>
            </section>
        </div>
    </x-modal.full> 
</form>
