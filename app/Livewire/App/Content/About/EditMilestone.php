<?php

namespace App\Livewire\App\Content\About;

use App\Models\FeaturedService;
use App\Models\Milestone;
use App\Services\MilestoneService;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class EditMilestone extends Component
{
    use WithFilePond, DispatchesToast;

    #[Validate] public $image;
    #[Validate] public $title;
    #[Validate] public $description = "Add a brief description of your milestone";
    #[Validate] public $date_achieved;
    public $description_length;
    public $milestone;

    public function mount(Milestone $milestone) {
        $this->title = $milestone->title;
        $this->description = $milestone->description;
        $this->date_achieved = $milestone->date_achieved;
        $this->description_length = strlen($this->description);
    }

    public function rules() {
        return Milestone::rules();
    }

    public function messages() {
        return Milestone::messages();
    }

    public function submit() {
        $validated = $this->validate([
            'image' => $this->rules()['image'],
            'title' => $this->rules()['title'],
            'description' => $this->rules()['description'],
            'date_achieved' => $this->rules()['date_achieved'],
        ]);

        $service = new MilestoneService;
        $service->edit($this->milestone, $validated);

        $this->toast('Success!', 'success', 'Milestone edited successfully!');
        $this->dispatch('milestone-edited');
        $this->dispatch('pond-reset');
    }

    public function render()
    {
        return <<<'HTML'
            <form wire:submit="submit" x-data="{ count: 200 - @js($description_length), max: 200 }" x-on:milestone-edited.window="show = false" class="p-5 space-y-5">
                <hgroup>
                    <h2 class="text-lg font-semibold">Edit Milestone</h2>
                    <p class="max-w-sm text-sm">Update milestone details here</p>
                </hgroup>

                <x-form.input-group>
                    <div>
                        <x-form.input-label for="edit-{{ $milestone->id }}-image">Image</x-form.input-label>
                        <p class="text-xs">Upload a new image here</p>
                    </div>

                    <x-filepond::upload
                        wire:model.live="image"
                        id="edit-{{ $milestone->id }}-image"
                        placeholder="<span class='text-xs'>Drag & drop your new image or <span class='filepond--label-action'> Browse </span></span>"
                    />

                    <x-form.input-error field="image" />
                </x-form.input-group>

                <x-form.input-group>
                    <div>
                        <x-form.input-label for="edit-{{ $milestone->id }}-title">Title &amp; Description</x-form.input-label>
                        <p class="text-xs">Enter the title and short description of your milestone</p>
                    </div>
                    
                    <x-form.input-text id="edit-{{ $milestone->id }}-title" name="title" label="Title" wire:model.live="title" />
                    <x-form.input-error field="title" />

                    <x-form.textarea id="edit-{{ $milestone->id }}-desription" name="description" wire:model.live="description" class="w-full" rows="5" x-on:keyup="count = max - $el.value.length" />
                    <x-form.input-error field="description" />
                </x-form.input-group>

                <x-form.input-group>
                    <div>
                        <x-form.input-label for="edit-{{ $milestone->id }}-date">Date Achieved</x-form.input-label>
                        <p class="text-xs">Enter the date you acheived this milestone</p>
                    </div>

                    <x-form.input-date id="edit-{{ $milestone->id }}-date" name="date_achieved" wire:model.live="date_achieved" class="w-full" />
                    <x-form.input-error field="date_achieved" />
                </x-form.input-group>

                <x-loading wire:loading wire:target='submit'>Editing milestone, please wait</x-loading>
                
                <div class="flex justify-end gap-1">
                    <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                    <x-primary-button type="submit">Edit</x-primary-button>
                </div>
            </form>
        HTML;
    }
}
