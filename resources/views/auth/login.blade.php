<x-auth-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="max-w-sm space-y-5">
            <hgroup class="flex items-start justify-between">
                <h1 class="text-2xl font-semibold">Amazing View <br /> Mountain Resort</h1>
                <x-application-logo />
            </hgroup>
            <!-- Email Address -->
            <x-form.input-group>
                <x-form.input-label for="email">Enter your email</x-form.input-label>
                <x-form.input-text label="amazing@view.com" id="email" class="block w-full mt-1" type="text" name="email" :value="old('email')" />
                <x-form.input-error field="email" />
            </x-form.input-group>
            
            <!-- Password -->
            <x-form.input-group>
                <x-form.input-label for="password">Enter your password</x-form.input-label>
                <x-form.input-text label="••••••••" id="password" class="block w-full mt-1" type="password" name="password" />
                <x-form.input-error field="password" />
            </x-form.input-group>
            
            <!-- Remember Me -->
            <div class="block">
                <x-form.input-checkbox id="remember_me" label="Remember me" />
            </div>
            
            <x-primary-button class="block w-full">Sign in</x-primary-button>

            <div>
                @if (Route::has('password.request'))
                    <div class="text-sm text-center">
                        <x-nav-link class="inline-block" href="/forgot-password">I can't remember my password</x-nav-link>
                    </div>
                @endif

                <div class="flex gap-3 p-3 border rounded-lg">
                    <p class="text-sm">Doesn&apos;t have an account yet? Click here to create one!</p>
                    <div class="shrink-0">
                        <a href="/register" wire:navigate>
                            <x-secondary-button>Sign up</x-secondary-button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-auth-layout>