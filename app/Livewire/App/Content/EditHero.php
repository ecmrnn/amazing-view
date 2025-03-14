<?php

namespace App\Livewire\App\Content;

use App\Models\Content;
use App\Models\MediaFile;
use App\Models\PageContent;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class EditHero extends Component
{
    use DispatchesToast, WithFilePond;

    #[Validate] public $hero_image;
    #[Validate] public $heading;
    #[Validate] public $subheading;
    public $page;

    public function rules() {
        return [
            'hero_image' => 'nullable|mimes:jpg,jpeg,png|image',
            'heading' => 'required',
            'subheading' => 'required',
        ];
    }

    public function mount($page) {
        $this->page = $page;
        $this->heading = PageContent::where('key', $this->page . '_heading')->pluck('value')->first();
        $this->subheading = PageContent::where('key', $this->page . '_subheading')->pluck('value')->first();
    }

    public function submit() {
        // Validate
        $this->validate([
            'hero_image' => $this->rules()['hero_image'],
            'heading' => $this->rules()['heading'],
            'subheading' => $this->rules()['subheading'],
        ]);

        // If may inupload na image
        if (!empty($this->hero_image)) {
            // delete saved image para di magdoble doble
            $hero_image = MediaFile::where('key', $this->page . '_hero_image')->first();
            
            if (!empty($hero_image->path)) {
                Storage::disk('public')->delete($hero_image->path);
            }
            
            $hero_image->path = $this->hero_image->store('hero', 'public');
            $hero_image->save();
        }

        // Store to database
        $heading = PageContent::where('key', $this->page . '_heading')->first();
        $heading->value = $this->heading;
        $heading->save();

        $subheading = PageContent::where('key', $this->page . '_subheading')->first();
        $subheading->value = $this->subheading;
        $subheading->save();

        $this->toast('Hero Edited!', 'success', 'Hero edited successfully');
        $this->dispatch('hero-edited');
        $this->dispatch('pond-reset');
    }

    public function render()
    {
        return <<<'HTML'
            <form x-on:hero-edited.window="show = false; count = 0;" class="p-5 space-y-5 bg-white" wire:submit="submit">
                <hgroup>
                    <h2 class="text-lg font-semibold capitalize">Edit Hero</h2>
                    <p class="max-w-sm text-sm">Update hero details here</p>
                </hgroup>

                <x-form.input-group>
                    <div>
                        <x-form.input-label for="hero_image">Image</x-form.input-label>
                        <p class="text-xs">Upload a new image here</p>
                    </div>

                    <x-filepond::upload
                        wire:model.live="hero_image"
                        id="hero_image"
                        placeholder="<span class='text-xs'>Drag & drop your new image or <span class='filepond--label-action'> Browse </span></span>"
                    />

                    <x-form.input-error field="hero_image" />
                </x-form.input-group>

                <x-form.input-group>
                    <div>
                        <x-form.input-label for="heading">Heading &amp; Subheading</x-form.input-label>
                        <p class="text-xs">Write an amazing story here</p>
                    </div>
                    <x-form.input-text id="heading" name="heading" label="Heading" wire:model.live="heading" />
                    <x-form.input-error field="heading" />
                </x-form.input-group>

                <x-form.input-group>
                    <!-- <x-form.input-label for='subheading'></x-form.input-label> -->
                    <x-form.input-text id="subheading" name="subheading" label="Subheading" wire:model.live="subheading" />
                    <x-form.input-error field="subheading" />
                </x-form.input-group>
                
                <x-loading wire:loading wire:target='submit'>Editing hero, please wait</x-loading>

                <div class="flex items-center justify-end gap-1">
                    <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                    <x-primary-button type="submit">Edit</x-primary-button>
                </div>
            </form>
        HTML;
    }
}
