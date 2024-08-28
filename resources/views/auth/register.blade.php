<x-auth-layout>
    <form method="POST" action="{{ route('register') }}" class="grid place-items-center md:min-h-screen py-20 px-5">
        @csrf

        <div class="max-w-sm">
            <h1 class="font-semibold text-2xl">Create an Account</h1>
            <p class="mb-5">Fill the required input fields below before submitting the form to create your account.</p>

            <div class="flex gap-5 items-center">
                <h2 class="font-semibold text-lg">Your Details</h2>
                <x-line class="bg-gray-300" />
            </div>

            <div class="mt-5 space-y-3">
                <!-- First Name -->
                <div>
                    <x-form.input-text label="First Name" id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="first_name" />
                    <x-form.input-error :messages="$errors->get('first_name')" class="mt-2" />
                    </div>
                    <!-- Last Name -->
                <div>
                    <x-form.input-text label="Last Name" id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required autofocus autocomplete="last_name" />
                    <x-form.input-error :messages="$errors->get('last_name')" class="mt-2" />
                </div>
            </div>

            <div class="flex gap-5 items-center my-5">
                <h2 class="font-semibold text-lg">Account Details</h2>
                <x-line class="bg-gray-300" />
            </div>

            <div class="space-y-3">
                <!-- Email Address -->
                <div>
                    <x-form.input-text label="Email" id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-form.input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <!-- Password -->
                <div>
                    <x-form.input-text label="Password" id="password" class="block mt-1 w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="new-password" />
                    <x-form.input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                <!-- Confirm Password -->
                <div>
                    <x-form.input-text label="Confirm Password" id="password_confirmation" class="block mt-1 w-full"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" />
                    <x-form.input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            </div>

            <x-primary-button class="mt-4 w-full">
                {{ __('Register') }}
            </x-primary-button>

            <div class="text-sm text-center">
                <livewire:nav-link href="/login" to="Already have an account?" />
            </div>
        </div>
    </form>
</x-auth-layout>
