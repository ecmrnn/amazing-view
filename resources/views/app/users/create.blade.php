<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between gap-3">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">
                    {{ __('Users') }}
                </h1>
                <p class="text-xs">Manage your users here</p>
            </hgroup>
        </div>
    </x-slot:header>

    <livewire:app.users.create-user />
</x-app-layout>
