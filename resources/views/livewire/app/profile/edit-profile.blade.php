<div class="max-w-screen-lg mx-auto space-y-5">
    <div class="flex items-center gap-5 p-5 bg-white border rounded-lg border-slate-200">
        <x-tooltip text="Back" dir="bottom">
            <a x-ref="content" href="{{ route('profile.index') }}" wire:navigate>
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
        </div>
    </div>

    <div class="p-5 bg-white border rounded-lg border-slate-200">
        <form class="max-w-xl space-y-5" wire:submit='saveProfile'>
            <hgroup>
                <h2 class='font-semibold'>Personal Details</h2>
                <p class='text-xs'>Edit personal details here</p>
            </hgroup>

            <div class="grid gap-5 md:grid-cols-2">
                <x-form.input-group>
                    <x-form.input-label for='first_name'>First Name</x-form.input-label>
                    <x-form.input-text id="first_name" name="first_name" label="First Name" wire:model.live='first_name' class="capitalize" />
                    <x-form.input-error field="first_name" />
                </x-form.input-group>

                <x-form.input-group>
                    <x-form.input-label for='last_name'>Last Name</x-form.input-label>
                    <x-form.input-text id="last_name" name="last_name" label="Last Name" wire:model.live='last_name' class="capitalize" />
                    <x-form.input-error field="last_name" />
                </x-form.input-group>

                <x-form.input-group>
                    <x-form.input-label for='phone'>Contact Number</x-form.input-label>
                    <x-form.input-text id="phone" name="phone" label="09xxxxxxxxx" wire:model.live='phone' maxlength="11" />
                    <x-form.input-error field="phone" />
                </x-form.input-group>

                <x-form.input-group>
                    <x-form.input-label for='address'>Home Address</x-form.input-label>
                    <x-form.input-text id="address" name="address" label="Address" wire:model.live='address' />
                    <x-form.input-error field="address" />
                </x-form.input-group>
            </div>

            <x-primary-button>Save</x-primary-button>
        </form>
    </div>

    <div class="p-5 bg-white border rounded-lg border-slate-200">
        <form class="max-w-xl space-y-5" wire:submit='saveAccount'>
            <hgroup>
                <h2 class='font-semibold'>Account Details</h2>
                <p class='text-xs'>Edit account details here</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for='email'>Email Address</x-form.input-label>
                <x-form.input-text id="email" name="email" label="Last Name" wire:model.live='email' disabled />
                <x-form.input-error field="email" />
            </x-form.input-group>

            <x-form.input-group>
                <x-form.input-label for="password">Change your password</x-form.input-label>
                
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


            <x-primary-button>Save</x-primary-button>
        </form>
    </div>
<div>