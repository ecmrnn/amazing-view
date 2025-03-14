<?php

namespace App\Livewire\App\Content\About;

use App\Models\Milestone;
use App\Services\MilestoneService;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class CreateMilestone extends Component
{
    use WithFilePond, DispatchesToast;

    #[Validate] public $image;
    #[Validate] public $title;
    #[Validate] public $description = "Add a brief description of your milestone";
    #[Validate] public $date_achieved;
    public $description_length;

    public function mount() {
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
            'image' => 'required|mimes:jpg,jpeg,png|image',
            'title' => $this->rules()['title'],
            'description' => $this->rules()['description'],
            'date_achieved' => $this->rules()['date_achieved'],
        ]);

        $service = new MilestoneService;
        $service->create($validated);

        $this->toast('Success!', 'success', 'Milestone added successfully!');
        $this->dispatch('milestone-added');
        $this->dispatch('pond-reset');
        
        $this->image = null;
        $this->title = null;
        $this->description = "Add a brief description of your milestone";
    }

    public function render()
    {

        return <<<'HTML'
            <form x-data="{ count : 200 - @js($description_length), max : 200 }" x-on:milestone-added.window="show = false; count = 200" class="p-5 space-y-5" wire:submit="submit">
                <hgroup>
                    <h2 class="font-semibold capitalize">Create Milestone</h2>
                    <p class="max-w-sm text-sm">Create a new milestone to feature here</p>
                </hgroup>

                <x-note>
                    Kindly fill up the form below to add a milestone and upload an image related to your milestone.
                </x-note>

                <div class="space-y-2">
                    <div>
                        <x-form.input-label for="image">Image</x-form.input-label>
                        <p class="text-xs">Upload an image highlighting your milestone here</p>
                    </div>

                    <x-filepond::upload
                        wire:model.live="image"
                        id="image"
                        placeholder="Drag & drop your image or <span class='filepond--label-action'> Browse </span>"
                    />
                    <x-form.input-error field="image" />
                </div>

                <div class="space-y-2">
                    <div>
                        <x-form.input-label for="title">Title &amp; Description</x-form.input-label>
                        <p class="text-xs">Enter the title and short description of your service</p>
                    </div>
                    
                    <x-form.input-text id="title" name="title" label="Title" wire:model.live="title" />
                    <x-form.input-error field="title" />
                    
                    <x-form.textarea id="desription" name="description" wire:model.live="description" class="w-full" rows="5" x-on:keyup="count = max - $el.value.length" />
                    <div class="flex justify-between">
                        <span><x-form.input-error field="description" /></span>
                        <p class="text-xs text-right">Remaining Characters: <span x-text="count"></span> / 200</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <div>
                        <x-form.input-label for="date_achieved">Date Achieved</x-form.input-label>
                        <p class="text-xs">Enter the date you acheived this milestone</p>
                    </div>

                    <x-form.input-date id="date_achieved" name="date_achieved" wire:model.live="date_achieved" class="w-full" />
                    <x-form.input-error field="date_achieved" />
                </div>

                <x-loading wire:loading wire:target='submit'>Creating milestone, please wait</x-loading>
                
                <div class="flex justify-end gap-1">
                    <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                    <x-primary-button type="submit">Add</x-primary-button>
                </div>
            </form>
        HTML;
    }
}
