<?php

namespace App\Livewire\App\Announcement;

use App\Models\Announcement;
use App\Services\AnnouncementService;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class CreateAnnouncement extends Component
{
    use DispatchesToast, WithFilePond;
    
    public $min_date;
    #[Validate] public $title;
    #[Validate] public $description;
    #[Validate] public $image;
    #[Validate] public $expires_at;

    public function rules() {
        return [
            'title' => 'required',
            'description' => 'required|max:1000',
            'image' => 'required|image',
            'expires_at' => 'nullable|date|after_or_equal:today',
        ];
    }

    public function submit() {
        $validated = $this->validate();

        $service = new AnnouncementService;
        $announcement = $service->create($validated);

        if ($announcement) {
            $this->dispatch('announcement-created');
            $this->dispatch('pond-reset');
            $this->toast('Success', description: 'Announcement created!');
            $this->reset();
            return;
        }
    }

    public function render()
    {
        $this->min_date = now()->format('Y-m-d');
        
        return <<<'HTML'
        <x-modal.full name='add-announcement-modal' maxWidth='sm'>
            <form class="p-5 space-y-5" wire:submit="submit" x-on:announcement-created.window="show = false">
                <hgroup>
                    <h2 class="text-lg font-semibold">Create Announcement</h2>
                    <p class="text-xs">Enter announcement details here</p>
                </hgroup>

                <x-form.input-group>
                    <x-form.input-label for='title'>Title of your announcement</x-form.input-label>
                    <x-form.input-text wire:model.live="title" id="title" name="title" label="Amazing Announcement" />
                    <x-form.input-error field="title" />
                </x-form.input-group>

                <x-form.input-group>
                    <x-form.input-label for='description'>Description</x-form.input-label>
                    <x-form.textarea id="description" name="description" label="description" wire:model.live="description" max="1000" class="w-full" />
                    <x-form.input-error field="description" />
                </x-form.input-group>

                <x-form.input-group>
                    <x-form.input-label for="image">Upload an image about your annoncement</x-form.input-label>
                    <x-filepond::upload
                        wire:model.live="image"
                        placeholder="Drag & drop your image or <span class='filepond--label-action'> Browse </span>"
                    />
                    <x-form.input-error field="image" />
                </x-form.input-group>

                <x-form.input-group>
                    <x-form.input-label for='expires_at'>Date until announcement is active</x-form.input-label>
                    <x-form.input-date wire:model.live="expires_at" id="expires_at" name="expires_at" class="w-full" min="{{ $min_date }}" />
                    <x-form.input-error field="expires_at" />
                </x-form.input-group>

                <x-loading wire:loading wire:target='submit'>Creating announcement, please wait</x-loading>

                <div class="flex justify-end gap-1">
                    <x-secondary-button type='button' x-on:click="show = false">Close</x-secondary-button>
                    <x-primary-button>Create</x-primary-button>
                </div>
            </form>
        </x-modal.full>
        HTML;
    }
}
