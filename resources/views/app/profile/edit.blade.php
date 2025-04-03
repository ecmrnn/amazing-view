<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between w-full">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">Profile</h1>
                <p class="text-xs">Edit your profile here</p>
            </hgroup>
        </div>
    </x-slot:header>

    <livewire:app.profile.edit-profile :user="$user" />
</x-app-layout>