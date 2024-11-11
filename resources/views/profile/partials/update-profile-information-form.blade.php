<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" wire:navigate class="space-y-5">
        @csrf
        @method('patch')

        <div class="grid grid-cols-2 gap-5">
            <div>
                <x-form.input-label for="first_name" :value="__('First Name')" />
                <x-form.input-text id="first_name" name="first_name" type="text" class="block w-full mt-1 capitalize" :value="old('first_name', $user->first_name)" required autofocus autocomplete="name" />
                <x-form.input-error field="first_name" />
            </div>
            <div>
                <x-form.input-label for="last_name" :value="__('Last Name')" />
                <x-form.input-text id="last_name" name="last_name" type="text" class="block w-full mt-1 capitalize" :value="old('last_name', $user->last_name)" required autofocus autocomplete="name" />
                <x-form.input-error field="last_name" />
            </div>
        </div>

        <div>
            <x-form.input-label for="email" :value="__('Email')" />
            <x-form.input-text id="email" name="email" type="email" class="block w-full mt-1" :value="old('email', $user->email)" required autocomplete="username" />
            <x-form.input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="mt-2 text-sm text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
