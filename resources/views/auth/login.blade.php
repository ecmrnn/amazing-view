<x-auth-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <form method="POST" action="{{ route('login') }}" class="grid px-5 py-20 place-items-center md:min-h-screen">
        @csrf
        <div class="max-w-sm">
            <h1 class="mb-5 text-2xl font-semibold">Amazing View <br /> Mountain Resort</h1>
            <!-- Email Address -->
            <div>
                <x-form.input-text
                    label="Email"
                    id="email"
                    class="block w-full mt-1"
                    type="text"
                    name="email"
                    :value="old('email')"
                />
                <x-form.input-error field="email" class="mt-2" />
            </div>
            
            <!-- Password -->
            <div class="mt-4">
                <x-form.input-text
                    label="Password"
                    id="password"
                    class="block w-full mt-1"
                    type="password"
                    name="password"
                />
                <x-form.input-error field="password" class="mt-2" />
            </div>
            
            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center select-none">
                    <input id="remember_me" type="checkbox" class="text-blue-600 border-gray-300 rounded shadow-sm focus:outline-none focus:ring-0 focus:border-blue-600" name="remember">
                    <span class="text-sm text-gray-600 ms-2">{{ __('Remember me') }}</span>
                </label>
            </div>
            
            <x-primary-button class="block w-full mt-4">
                Sign in
            </x-primary-button>

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
    </form>
</x-auth-layout>