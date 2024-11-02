<?php

namespace App\Livewire\App\Content;

use App\Models\Content;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class EditHero extends Component
{
    use DispatchesToast, WithFilePond;

    #[Validate] public $home_hero_image;
    #[Validate] public $heading;
    #[Validate] public $subheading;
    public $page;

    public function rules() {
        return [
            'home_hero_image' => 'nullable|mimes:jpg,jpeg,png|image',
            'heading' => 'required',
            'subheading' => 'required',
        ];
    }

    public function mount($page) {
        $this->page = $page;
        $this->heading = Content::whereName($this->page . '_heading')->pluck('value')->first();
        $this->subheading = Content::whereName($this->page . '_subheading')->pluck('value')->first();
    }

    public function submit() {
        // Validate
        $this->validate([
            'home_hero_image' => $this->rules()[$this->page . '_hero_image'],
            'heading' => $this->rules()['heading'],
            'subheading' => $this->rules()['subheading'],
        ]);

        // If may inupload na image
        if (!empty($this->home_hero_image)) {
            // delete saved image para di magdoble doble
            $home_hero_image = Content::whereName($this->page . '_hero_image')->first();
            Storage::disk('public')->delete($home_hero_image->value);
            
            $home_hero_image->value = $this->home_hero_image->store('hero', 'public');
            $home_hero_image->save();
        }

        // Store to database
        $heading = Content::whereName($this->page . '_heading')->first();
        $heading->value = $this->heading;
        $heading->save();

        $subheading = Content::whereName($this->page . '_subheading')->first();
        $subheading->value = $this->subheading;
        $subheading->save();

        $this->toast('Hero Edited!', 'success', 'Hero edited successfully');
        $this->dispatch('hero-edited');
        $this->dispatch('pond-reset');
    }

    public function render()
    {
        return <<<'HTML'
            <div x-on:hero-edited.window="show = false; count = 0;" class="block p-5 space-y-5 bg-white" wire:submit="submit">
                <hgroup>
                    <h2 class="font-semibold text-center capitalize">Edit Hero</h2>
                    <p class="max-w-sm text-sm text-center">Update hero details here</p>
                </hgroup>

                <div class="space-y-2">
                    <div>
                        <x-form.input-label for="home_hero_image">Image</x-form.input-label>
                        <p class="text-xs">Upload a new image here</p>
                    </div>

                    <x-filepond::upload
                        wire:model.live="home_hero_image"
                        id="home_hero_image"
                        placeholder="<span class='text-xs'>Drag & drop your new image or <span class='filepond--label-action'> Browse </span></span>"
                    />

                    <x-form.input-error field="home_hero_image" />
                </div>

                <div class="space-y-2">
                    <div>
                        <x-form.input-label for="heading">Heading &amp; Subheading</x-form.input-label>
                        <p class="text-xs">Write an amazing story here</p>
                    </div>

                    <x-form.input-text id="heading" name="heading" label="Heading" wire:model.live="heading" />
                    <x-form.input-error field="heading" />
                    <x-form.input-text id="subheading" name="subheading" label="Subheading" wire:model.live="subheading" />
                    <x-form.input-error field="subheading" />
                </div>
                
                <div class="flex items-center justify-center gap-1">
                    <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                    <x-primary-button type="button" wire:click="submit">Edit Service</x-primary-button>
                </div>
            </div>
        HTML;
    }
}
