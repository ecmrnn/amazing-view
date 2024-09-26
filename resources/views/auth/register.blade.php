<x-auth-layout>
    <form method="POST" action="{{ route('register') }}" class="grid px-5 py-20 place-items-center md:min-h-screen">
        @csrf

        <div class="max-w-sm">
            <h1 class="text-2xl font-semibold">Create an Account</h1>
            <p class="mb-5">Fill the required input fields below before submitting the form to create your account.</p>

            <div class="flex items-center gap-5">
                <h2 class="text-lg font-semibold">Your Details</h2>
                <x-line class="bg-gray-300" />
            </div>

            <div class="mt-5 space-y-3">
                <!-- First Name -->
                <div>
                    <x-form.input-text
                        label="First Name"
                        id="first_name"
                        class="block w-full mt-1"
                        type="text"
                        name="first_name"
                        :value="old('first_name')"
                    />
                    <x-form.input-error field="first_name" class="mt-2" />
                    </div>
                    <!-- Last Name -->
                <div>
                    <x-form.input-text
                        label="Last Name"
                        id="last_name"
                        class="block w-full mt-1"
                        type="text"
                        name="last_name"
                        :value="old('last_name')"
                    />
                    <x-form.input-error field="last_name" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center gap-5 my-5">
                <h2 class="text-lg font-semibold">Account Details</h2>
                <x-line class="bg-gray-300" />
            </div>

            <div class="space-y-3">
                <!-- Email Address -->
                <div>
                    <x-form.input-text 
                        label="Email"
                        id="email"
                        class="block w-full mt-1"
                        type="email"
                        name="email"
                    />
                    <x-form.input-error field="email" class="mt-2" />
                </div>
                <!-- Password -->
                <div>
                    <x-form.input-text
                        label="Password"
                        id="password"
                        class="block w-full mt-1"
                        type="password"
                        name="password"
                    />
                    <x-form.input-error field="password" class="mt-2" />
                </div>
                <!-- Confirm Password -->
                <div>
                    <x-form.input-text
                        label="Confirm Password"
                        id="password_confirmation"
                        class="block w-full mt-1"
                        type="password"
                        name="password_confirmation" />
                    <x-form.input-error field="password_confirmation" class="mt-2" />
                </div>
            </div>

            <x-primary-button class="w-full mt-4">
                {{ __('Register') }}
            </x-primary-button>

            <div class="text-sm text-center">
                <x-nav-link class="inline-block" href="/login">Already have an account?</x-nav-link>
            </div>
        </div>
    </form>
</x-auth-layout>
