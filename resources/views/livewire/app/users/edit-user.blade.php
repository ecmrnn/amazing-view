<form x-data="{
    first_name: @entangle('first_name'),
    last_name: @entangle('last_name'),
    phone: @entangle('phone'),
}"

class="grid gap-5 lg:grid-cols-2">
@csrf
<section>        
    {{-- Personal Details --}}
    <x-form.form-section>
        <x-form.form-header step="1" title="Personal Details" />

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
            </div>
        </x-form.form-body>
    </x-form.form-section>

    <x-line-vertical />

    {{-- Account Details --}}
    <x-form.form-section>
        <x-form.form-header step="2" title="Account Details" />

        <x-form.form-body>
            <div class="p-5 space-y-3">
                <hgroup>
                    <h3 class="text-sm font-semibold">Email &amp; Role</h3>
                    <p class="text-xs">Enter user&apos;s email and select the user&apos;s role</p>
                </hgroup>
                {{-- Email --}}
                <div class="grid items-start gap-3 sm:grid-cols-2">
                    <div class="space-y-1">
                        <x-form.input-text type="email" value="{{ $user->email }}"
                            id="email" label="Email" disabled />
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
            </div>
        </x-form.form-body>
    </x-form.form-section>

    <x-line-vertical />
    
    <div class="flex items-center gap-1">
        <x-primary-button type="button" wire:click="createUser">Save Changes</x-primary-button>
        <p class="max-w-xs text-xs font-semibold" wire:loading.delay wire:target="createUser">Please wait while we load the next form.</p>
    </div>
</section>

<section>
    <div class="flex items-center gap-5">
        <div class="p-5 text-white bg-blue-500 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lightbulb"><path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"/><path d="M9 18h6"/><path d="M10 22h4"/></svg>
        </div>

        <hgroup>
            <h2 class="font-semibold text-md">Reminder!</h2>
            <p class="max-w-sm text-xs">Kindly double check all information entered, this information will be used when a registered user process a reservation.</p>
        </hgroup>
    </div>
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
                <x-primary-button type="button" x-bind:disabled="!checked" class="text-xs" x-on:click="$wire.update(); checked = false; show = false;">
                    Save Changes
                </x-primary-button>
            </div>
        </section>
    </div>
</x-modal.full> 
</form>
