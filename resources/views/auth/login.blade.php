<x-auth-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <form method="POST" action="{{ route('login') }}" class="grid place-items-center md:min-h-screen py-20 px-5">
        @csrf
        <div class="max-w-sm">
            <h1 class="font-semibold text-2xl mb-5">Amazing View <br /> Mountain Resort</h1>
            <!-- Email Address -->
            <div>
                <x-form.input-text label="Email" id="email" class="block mt-1 w-full"
                                type="email"
                                name="email"
                                :value="old('email')"
                                required autocomplete="username" />
                <x-form.input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            
            <!-- Password -->
            <div class="mt-4">
                <x-form.input-text label="Password" id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
                <x-form.input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            
            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:outline-none focus:ring-0 focus:border-blue-600" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>
            
            <x-primary-button class="w-full block mt-4">
                Sign in
            </x-primary-button>

            @if (Route::has('password.request'))
                <div class="text-sm text-center">
                    <x-nav-link class="inline-block" href="/forgot-password">I can't remember my password</x-nav-link>
                </div>
            @endif

            <div class="p-3 rounded-lg border flex gap-3">
                <p class="text-sm">Doesn&apos;t have an account yet? Click here to create one!</p>

                <div class="shrink-0">
                    <a href="/register" class="">
                        <x-secondary-button>Sign up</x-secondary-button>
                    </a>
                </div>
            </div>
        </div>
    </form>
</x-auth-layout>