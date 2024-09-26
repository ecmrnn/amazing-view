<x-auth-layout>
    <div class="grid px-5 py-20 place-items-center md:min-h-screen">
        <div>
            <h1 class="mb-4 text-2xl font-semibold">Forgot your Password?</h1>
            
            <div class="max-w-sm mb-4 text-sm text-gray-600">
                {{ __('No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </div>
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <!-- Email Address -->
                <div>
                    <x-form.input-text label="Email" id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" />
                    <x-form.input-error field="email" class="mt-2" />
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
