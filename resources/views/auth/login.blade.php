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
            <div class="space-y-2">
                <x-form.input-text
                    label="Email"
                    id="email"
                    class="block w-full mt-1"
                    type="text"
                    name="email"
                    :value="old('email')"
                />
                <x-form.input-error field="email" />
            </div>
            
            <!-- Password -->
            <div class="space-y-2">
                <x-form.input-text
                    label="Password"
                    id="password"
                    class="block w-full mt-1"
                    type="password"
                    name="password"
                />
                <x-form.input-error field="password" />
            </div>
            
            <!-- Remember Me -->
            <div class="block">
                <x-form.input-checkbox id="remember_me" label="Remember me" />
            </div>
            
            <x-primary-button class="block w-full">
                Sign in
            </x-primary-button>

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