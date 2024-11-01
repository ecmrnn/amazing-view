<?php

namespace App\Livewire\App\Content\Home;

use App\Models\FeaturedService;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use phpDocumentor\Reflection\Types\This;
use Spatie\LivewireFilepond\WithFilePond;

class AddService extends Component
{
    use WithFilePond, DispatchesToast;

    #[Validate] public $image;
    #[Validate] public $title;
    #[Validate] public $description = "Add a brief description of your service";
    public $description_length;

    public function mount() {
        $this->description_length = strlen($this->description);
    }

    public function rules() {
        return FeaturedService::rules();
    }

    public function messages() {
        return FeaturedService::messages();
    }

    public function submit() {
        $validated = $this->validate([
            'image' => 'required|mimes:jpg,jpeg,png|image|max:1000',
            'title' => $this->rules()['title'],
            'description' => $this->rules()['description'],
        ]);

        $image_filename = $this->image->store('featured-services', 'public');

        FeaturedService::create([
            'image' => $image_filename,
            'title' => $this->title,
            'description' => $this->description
        ]);

        $this->toast('Success!', 'success', 'Service added successfully!');
        $this->dispatch('service-added');
        
        $this->image = null;
        $this->title = null;
        $this->description = "Add a brief description of your service";
    }

    public function render()
    {

        return <<<'HTML'
            <div x-data="{ count : 200 - @js($description_length), max : 200 }" x-on:service-added.window="show = false; count = 200" class="block p-5 space-y-5 bg-white" wire:submit="submit">
                <hgroup>
                    <h2 class="font-semibold text-center capitalize">Add Service</h2>
                    <p class="max-w-sm text-sm text-center">Create a new service to feature here</p>
                </hgroup>

                <x-note>
                    Kindly fill up the form below to add a service, upload an image related to your service with a maximum size of 1024MB.
                </x-note>

                <div class="space-y-2">
                    <div>
                        <x-form.input-label for="image">Image</x-form.input-label>
                        <p class="text-xs">Upload an image describing your service here</p>
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
                
                <div class="flex items-center justify-center gap-1">
                    <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                    <x-primary-button type="button" wire:click="submit">Add Service</x-primary-button>
                </div>
            </div>
        HTML;
    }
}
