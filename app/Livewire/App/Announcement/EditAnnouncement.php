<?php

namespace App\Livewire\App\Announcement;

use App\Models\Announcement;
use App\Services\AnnouncementService;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class EditAnnouncement extends Component
{
    use DispatchesToast, WithFilePond;

    public $announcement;
    #[Validate] public $title;
    #[Validate] public $description;
    #[Validate] public $image;

    public function rules() {
        return [
            'title' => 'required',
            'description' => 'required|max:1000',
        ];
    }

    public function mount(Announcement $announcement) {
        $this->title = $announcement->title;
        $this->description = $announcement->description;
    }

    public function submit() {
        $validated = $this->validate([
            'title' => $this->rules()['title'],
            'description' => $this->rules()['description'],
            'image' => 'nullable|image'
        ]);

        $service = new AnnouncementService;
        $announcement = $service->update($this->announcement, $validated);

        if ($announcement) {
            $this->dispatch('announcement-updated');
            $this->dispatch('pond-reset');
            $this->toast('Success', description: 'Announcement updated!');
            return;
        }
    }
    
    public function render()
    {
        return <<<'HTML'
        <form class="p-5 space-y-5" x-on:announcement-updated.window="show = false" wire:submit="submit">
            <hgroup>
                <h2 class="text-lg font-semibold">Edit Announcement</h2>
                <p class="text-xs">Update your announcement here</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for='title-{{ $announcement->id }}'>Title of your announcement</x-form.input-label>
                <x-form.input-text wire:model.live="title" id="title-{{ $announcement->id }}" name="title-{{ $announcement->id }}" label="Amazing Announcement" />
                <x-form.input-error field="title-{{ $announcement->id }}" />
            </x-form.input-group>

            <x-form.input-group>
                <x-form.input-label for='description-{{ $announcement->id }}'>Description</x-form.input-label>
                <x-form.textarea id="description-{{ $announcement->id }}" name="description-{{ $announcement->id }}" label="description" wire:model.live="description" max="1000" class="w-full" />
                <x-form.input-error field="description" />
            </x-form.input-group>

            <x-form.input-group>
                <x-form.input-label for="image-{{ $announcement->id }}">Upload an image about your annoncement</x-form.input-label>
                <x-img src="{{ $announcement->image }}" />
                <x-filepond::upload
                    wire:model.live="image"
                    placeholder="Drag & drop your image or <span class='filepond--label-action'> Browse </span>"
                />
                <x-form.input-error field="image" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='submit'>Updating announcement, please wait</x-loading>

            <div class="flex justify-end gap-1">
                <x-secondary-button type='button' x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button>Edit</x-primary-button>
            </div>
        </form>
        HTML;
    }
}
