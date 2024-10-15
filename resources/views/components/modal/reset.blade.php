<x-modal.full name="show-reset-confirmation" maxWidth="xs">
    <div>
        <section class="p-5 space-y-5 bg-white">
            <hgroup>
                <h2 class="font-semibold text-center capitalize">Reset Form</h2>
                <p class="max-w-sm text-xs text-center text-zinc-800/50">You are about to reset this form, your progress will be gone. Proceed?</p>
            </hgroup>

            <div class="flex items-center justify-center gap-1">
                <x-secondary-button type="button" x-on:click="show = false">No, cancel</x-secondary-button>
                <x-danger-button type="button" x-on:click="$dispatch('reset-form')">Yes, reset</x-danger-button>
            </div>
        </section>
    </div>
</x-modal.full> 