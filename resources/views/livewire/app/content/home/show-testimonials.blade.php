<div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
    <div class="flex items-start justify-between gap-5">
        <hgroup>
            <h2 class="font-semibold">Testimonials</h2>
            <p class="text-xs">Manage your testimonials here</p>
        </hgroup>

        <button class="text-xs font-semibold text-blue-500" x-on:click="$dispatch('open-modal', 'add-testimony-modal')" type="button">Add Testimony</button>
    </div>

    <livewire:tables.testimonial-table/>

    {{-- Modals --}}
    <x-modal.full name='add-testimony-modal' maxWidth='sm'>
        <form wire:submit='addTestimonial' x-on:testimonial-added.window="show = false" class="p-5 space-y-5" x-data="{ rating: @entangle('rating')}">
            <hgroup>
                <h2 class="text-lg font-semibold">Add Testimony</h2>
                <p class="text-xs">Enter the guest details here and their thoughts about us</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for='name'>Guest's Name</x-form.input-label>
                <x-form.input-text id="name" name="name" label="Name" wire:model='name' />
                <x-form.input-error field="name" />
            </x-form.input-group>

            <x-form.input-group>
                <x-form.input-label for='testimonial'>Testimonial</x-form.input-label>
                <x-form.textarea id="testimonial" wire:model.live='testimonial' class="w-full" />
                <x-form.input-error field="testimonial" />
            </x-form.input-group>

            <x-form.input-group>
                <x-form.input-label for='rating'>Rating</x-form.input-label>
                <x-form.input-number x-model="rating" id="rating" name="rating" class="text-center" />
                <x-form.input-error field="rating" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='addTestimonial'>Creating testimonial, please wait</x-loading>

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button type="submit">Add</x-primary-button>
            </div>
        </form>
    </x-modal.full>
</div>