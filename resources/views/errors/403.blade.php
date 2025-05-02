<x-error-layout>
    <div class="space-y-5">
        <div>
            <svg class="-rotate-6" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock-keyhole-icon lucide-lock-keyhole"><circle cx="12" cy="16" r="1"/><rect x="3" y="10" width="18" height="12" rx="2"/><path d="M7 10V7a5 5 0 0 1 10 0v3"/></svg>
        </div>

        <x-h1>Unauthorized Access!</x-h1>
        <p class="text-sm">Oof! You are not permitted to view the resource you requested.</p>
        <a href="{{ url()->previous() }}" wire:navigate class="inline-flex items-center gap-5">
            <x-primary-button class="text-xs">Take me back</x-primary-button>
        </a>
    </div>
</x-error-layout>