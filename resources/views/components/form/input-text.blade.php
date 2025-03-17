@props([
    'type' => 'text',
    'label' => '',
])

<div x-data="{ type: @js($type), hidden: true }" class="relative z-0 w-full">
    <input 
        {{ $attributes->merge(['class' => 'block p-3 py-2.5 w-full text-xs bg-white rounded-md border border-slate-200 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 z-0 transition-colors ease-in-out duration-200 font-semibold invalid:border-red-500 invalid:bg-red-50 focus:invalid:border-red-500 disabled:bg-slate-100 disabled:opacity-50']) }}
        x-bind:type="type" placeholder="{{ $label }}" />
    @if ($type == 'password')
        <button tabindex="-1" type="button" class="absolute p-2 top-0.5 right-2" x-show="hidden" x-on:click="hidden = ! hidden; type = 'text'">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        </button>
        <button tabindex="-1" type="button" class="absolute p-2 top-0.5 right-2" x-show="!hidden" x-on:click="hidden = ! hidden; type = 'password'">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock-open"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 9.9-1"/></svg>
        </button>
    @endif
</div>