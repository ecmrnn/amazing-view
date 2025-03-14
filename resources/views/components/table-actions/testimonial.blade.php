@props([
    'width' => '16',
    'height' => '16',
])

<div wire:key="{{ $row->id }}" class="flex justify-end gap-1">
    <x-tooltip text="Edit" dir="top">
        <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'edit-testimonial-{{ $row->id}}')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" /><path d="m15 5 4 4" /></svg>
        </x-icon-button>
    </x-tooltip>

    @if ($row->status == App\Enums\TestimonialStatus::ACTIVE->value)
        <x-tooltip text="Deactivate" dir="top">
            <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'deactivate-testimonial-{{ $row->id}}')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ban"><circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/></svg>
            </x-icon-button>
        </x-tooltip>
    @else
        <x-tooltip text="Activate" dir="top">
            <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'activate-testimonial-{{ $row->id}}')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check"><path d="M20 6 9 17l-5-5"/></svg>
            </x-icon-button>
        </x-tooltip>    
    @endif
    
    <x-tooltip text="Delete" dir="top">
        <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'delete-testimonial-{{ $row->id }}')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
        </x-icon-button>
    </x-tooltip>

    {{-- Modals --}}
    <x-modal.full name='edit-testimonial-{{ $row->id}}' maxWidth='sm'>
        <form class="p-5 space-y-5"
            x-on:testimonial-edited.window="show = false"
            x-data="{
                name: @js($row->name),
                testimonial: @js($row->testimonial),
                rating: @js($row->rating),
                status: @js($row->status),
            }">
            <hgroup>
                <h2 class="text-lg font-semibold">Edit Testominial</h2>
                <p class="text-xs">Edit testimonial details here</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for='name-{{ $row->id }}'>Guest's Name</x-form.input-label>
                <x-form.input-text x-model="name" id="name-{{ $row->id }}" name="name-{{ $row->id }}" label="Name" />
                <x-form.input-error field="name" />
            </x-form.input-group>

            <x-form.input-group>
                <x-form.input-label for='testimonial-{{ $row->id }}'>Testimonial</x-form.input-label>
                <x-form.textarea x-model="testimonial" id="testimonial-{{ $row->id }}" class="w-full"></x-form.textarea>
                <x-form.input-error field="testimonial" />
            </x-form.input-group>

            <x-form.input-group>
                <x-form.input-label for='rating-{{ $row->id }}'>Rating</x-form.input-label>
                <x-form.input-number x-model="rating" id="rating-{{ $row->id }}" name="rating-{{ $row->id }}" class="text-center" />
                <x-form.input-error field="rating" />
            </x-form.input-group>

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button type="button" wire:click="editTestimonial({{ $row->id }}, {
                        'name' : name,
                        'testimonial' : testimonial,
                        'rating' : rating,
                        'status' : status,
                    })" wire:loading.attr='disabled'>Edit</x-primary-button>
            </div>
        </form>
    </x-modal.full>

    <x-modal.full name='delete-testimonial-{{ $row->id }}' maxWidth='sm'>
        <form class="p-5 space-y-5" x-on:testimonial-deleted.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold text-red-500">Delete Testimonial</h2>
                <p class="text-xs">Enter your password to delete this testimonial</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for='delete-testimonial-password-{{ $row->id }}'>Enter your password</x-form.input-label>
                <x-form.input-text type="password" wire:model='password' id="delete-testimonial-password-{{ $row->id }}" name="delete-testimonial-password-{{ $row->id }}" label="Password" />
                <x-form.input-error field="password" />
            </x-form.input-group>

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-danger-button type="button" wire:loading.attr='disabled' wire:click="deleteTestimonial({{ $row->id }})">Delete</x-danger-button>
            </div>
        </form>
    </x-modal.full>

    <x-modal.full name='deactivate-testimonial-{{ $row->id}}' maxWidth='sm'>
        <form class="p-5 space-y-5" x-on:testimonial-status-updated.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold text-red-500">Deactivate Testimonial</h2>
                <p class="text-xs">Enter your password to deactivate this testimonial</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for='deactivate-testimonial-password-{{ $row->id }}'>Enter your password</x-form.input-label>
                <x-form.input-text type="password" wire:model='password' id="deactivate-testimonial-password-{{ $row->id }}" name="deactivate-testimonial-password-{{ $row->id }}" label="Password" />
                <x-form.input-error field="password" />
            </x-form.input-group>

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-danger-button type="button" wire:loading.attr='disabled' wire:click="toggleStatus({{ $row->id }})">Deactivate</x-danger-button>
            </div>
        </form>
    </x-modal.full>

    <x-modal.full name='activate-testimonial-{{ $row->id}}' maxWidth='sm'>
        <form class="p-5 space-y-5" x-on:testimonial-status-updated.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold">Activate Testimonial</h2>
                <p class="text-xs">Enter your password to activate this testimonial</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for='deactivate-testimonial-password-{{ $row->id }}'>Enter your password</x-form.input-label>
                <x-form.input-text type="password" wire:model='password' id="deactivate-testimonial-password-{{ $row->id }}" name="deactivate-testimonial-password-{{ $row->id }}" label="Password" />
                <x-form.input-error field="password" />
            </x-form.input-group>

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button type="button" wire:loading.attr='disabled' wire:click="toggleStatus({{ $row->id }})">Activate</x-primary-button>
            </div>
        </form>
    </x-modal.full>
</div>
