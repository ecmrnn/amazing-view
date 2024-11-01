<?php

namespace App\Livewire\App\Content\Home;

use App\Models\FeaturedService;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class EditService extends Component
{
    use WithFilePond, DispatchesToast;

    #[Validate] public $image;
    #[Validate] public $title;
    #[Validate] public $description = "Add a brief description of your service";
    public $description_length;
    public $service;

    public function mount(FeaturedService $service) {
        $this->service = $service;
        $this->title = $service->title;
        $this->description = $service->description;
        $this->description_length = strlen($service->description);
    }

    public function rules() {
        return FeaturedService::rules();
    }

    public function messages() {
        return FeaturedService::messages();
    }

    public function submit() {
        $this->validate([
            'image' => $this->rules()['image'],
            'title' => $this->rules()['title'],
            'description' => $this->rules()['description'],
        ]);

        if (!empty($this->image)) {
            $this->service->image = $this->image->store('featured-services', 'public');
        }
        
        $this->service->title = $this->title;        
        $this->service->description = $this->description;        
        $this->service->save();

        $this->toast('Success!', 'success', 'Service edited successfully!');
        $this->dispatch('service-edited');
    }

    public function render()
    {
        return <<<'HTML'
            <div x-data="{ count : 200 - @js($description_length), max : 200 }" x-on:service-edited.window="show = false; count = 0;" class="block p-5 space-y-5 bg-white" wire:submit="submit">
                <hgroup>
                    <h2 class="font-semibold text-center capitalize">Edit Service</h2>
                    <p class="max-w-sm text-sm text-center">Update service details here</p>
                </hgroup>

                <div class="space-y-2">
                    <div>
                        <x-form.input-label for="edit-{{ $service->id }}-image">Image</x-form.input-label>
                        <p class="text-xs">Upload a new image here</p>
                    </div>

                    <x-filepond::upload
                        wire:model.live="image"
                        id="edit-{{ $service->id }}-image"
                        placeholder="<span class='text-xs'>Drag & drop your new image or <span class='filepond--label-action'> Browse </span></span>"
                    />

                    <x-form.input-error field="image" />
                </div>

                <div class="space-y-2">
                    <div>
                        <x-form.input-label for="edit-{{ $service->id }}-title">Title &amp; Description</x-form.input-label>
                        <p class="text-xs">Enter the title and short description of your service</p>
                    </div>
                    
                    <x-form.input-text id="edit-{{ $service->id }}-title" name="title" label="Title" wire:model.live="title" />
                    <x-form.input-error field="title" />

                    <x-form.textarea id="edit-{{ $service->id }}-desription" name="description" wire:model.live="description" class="w-full" rows="5" x-on:keyup="count = max - $el.value.length" />
                    
                    <div class="flex justify-between">
                        <span><x-form.input-error field="description" /></span>
                        <p class="text-xs text-right">Remaining Characters: <span x-text="count"></span> / 200</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-center gap-1">
                    <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                    <x-primary-button type="button" wire:click="submit">Edit Service</x-primary-button>
                </div>
            </div>
        HTML;
    }
}
