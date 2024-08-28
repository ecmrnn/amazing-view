<x-auth-layout>
    <div class="grid place-items-center md:min-h-screen py-20 px-5">
        <div>
            <h1 class="font-semibold text-2xl mb-4">Forgot your Password?</h1>
            
            <div class="mb-4 text-sm text-gray-600 max-w-sm">
                {{ __('No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </div>
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <!-- Email Address -->
                <div>
                    <x-form.input-text label="Email" id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                    <x-form.input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <div class="text-sm">
                    <x-nav-link class="inline-block" href="/login">I remember my password!</x-nav-link>
                </div>
                <div class="mt-4">
                    <x-primary-button>
                        {{ __('Email Password Reset Link') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-auth-layout>
