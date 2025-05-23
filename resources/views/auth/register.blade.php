<x-auth-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="max-w-sm">
            <h1 class="text-2xl font-semibold">Create an Account</h1>
            <p class="mb-5 text-sm">Fill the required input fields below before submitting the form to create your account.</p>

            <div class="flex items-center gap-5">
                <h2 class="text-lg font-semibold">Personal Details</h2>
            </div>

            <div class="mt-5 space-y-3">
                <!-- First Name -->
                <div class="space-y-2">
                    <x-form.input-text
                        label="First Name"
                        id="first_name"
                        class="block w-full mt-1"
                        type="text"
                        name="first_name"
                        :value="old('first_name')"
                    />
                    <x-form.input-error field="first_name" />
                    </div>
                    <!-- Last Name -->
                <div class="space-y-2">
                    <x-form.input-text
                        label="Last Name"
                        id="last_name"
                        class="block w-full mt-1"
                        type="text"
                        name="last_name"
                        :value="old('last_name')"
                    />
                    <x-form.input-error field="last_name" />
                </div>
            </div>

            <div class="flex items-center gap-5 my-5">
                <h2 class="text-lg font-semibold">Account Details</h2>
            </div>

            <div class="space-y-3">
                <!-- Email Address -->
                <div class="space-y-2">
                    <x-form.input-text 
                        label="Email"
                        id="email"
                        class="block w-full mt-1"
                        type="email"
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
                <!-- Confirm Password -->
                <div class="space-y-2">
                    <x-form.input-text
                        label="Confirm Password"
                        id="password_confirmation"
                        class="block w-full mt-1"
                        type="password"
                        name="password_confirmation" />
                    <x-form.input-error field="password_confirmation" />
                </div>

                <x-note>Your password must contain atleast 8 characters, including at least 1 uppercase letter, 1 lowercase letter, 1 number and 1 special character.</x-note>
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
