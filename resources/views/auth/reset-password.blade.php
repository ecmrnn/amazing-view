<x-auth-layout>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <div class="max-w-sm space-y-5">
            <hgroup class="flex items-start justify-between gap-5">
                <hgroup>
                    <h1 class="text-2xl font-semibold">Amazing View <br /> Mountain Resort</h1>
                    <p class="text-xs">Enter your new password here to reset or create your password.</p>
                </hgroup>
                <x-application-logo />
            </hgroup>

            <x-note>Your password must contain atleast 8 characters, including at least 1 uppercase letter, 1 lowercase letter, 1 number and 1 special character.</x-note>
            
            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
    
            <!-- Email Address -->
            <div>
                <x-form.input-label for="email" :value="__('Email')" />
                <x-form.input-text id="email" class="block w-full mt-1" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                <x-form.input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
    
            <!-- Password -->
            <div class="mt-4">
                <x-form.input-label for="password" :value="__('Password')" />
                <x-form.input-text id="password" label="••••••••" class="block w-full mt-1" type="password" name="password" required autocomplete="new-password" />
                <x-form.input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
    
            <!-- Confirm Password -->
            <div class="mt-4">
                <x-form.input-label for="password_confirmation" :value="__('Confirm Password')" />
    
                <x-form.input-text id="password_confirmation" class="block w-full mt-1"
                                    label="••••••••"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" />
    
                <x-form.input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
    
            <div class="flex items-center justify-end mt-4">
                <x-primary-button>
                    {{ __('Reset Password') }}
                </x-primary-button>
            </div>
        </div>
    </form>
</x-auth-layout>
