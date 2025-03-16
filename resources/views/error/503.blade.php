<x-error-layout>
    <div class="space-y-5">
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wrench"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
        </div>

        <x-h1>Working on it!</x-h1>
        <p class="text-sm">The page you requested is under maintenance.</p>
        <a href="{{ route('guest.home') }}" wire:navigate class="inline-flex items-center gap-5">
            <x-primary-button class="text-xs">Home</x-primary-button>
        </a>
    </div>
</x-error-layout>