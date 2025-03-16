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
    public $current_hero_image;
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
        $this->heading = PageContent::where('key', str_replace(' ', '_', $this->page) . '_heading')->pluck('value')->first();
        $this->subheading = PageContent::where('key', str_replace(' ', '_', $this->page) . '_subheading')->pluck('value')->first();
        $this->current_hero_image = MediaFile::where('key', str_replace(' ', '_', $this->page) . '_hero_image')->pluck('path')->first();
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
            $hero_image = MediaFile::where('key', str_replace(' ', '_', $this->page) . '_hero_image')->first();
            if (!empty($hero_image->path)) {
                Storage::disk('public')->delete($hero_image->path);
            }
            
            $hero_image->path = $this->hero_image->store('hero', 'public');
            $hero_image->save();
        }

        // Store to database
        $heading = PageContent::where('key', str_replace(' ', '_', $this->page) . '_heading')->first();
        $heading->value = $this->heading;
        $heading->save();

        $subheading = PageContent::where('key', str_replace(' ', '_', $this->page) . '_subheading')->first();
        $subheading->value = $this->subheading;
        $subheading->save();

        $this->current_hero_image = MediaFile::where('key', str_replace(' ', '_', $this->page) . '_hero_image')->pluck('path')->first();
        $this->toast('Hero Edited!', 'success', 'Hero edited successfully');
        $this->dispatch('hero-edited');
        $this->dispatch('pond-reset');
    }

    public function render()
    {
        return <<<'HTML'
            <form x-on:hero-edited.window="show = false; count = 0;" class="p-5 space-y-5 bg-white border rounded-lg border-slate-200" wire:submit="submit">
                <hgroup>
                    <h2 class="text-lg font-semibold capitalize">{{ $page }} - Edit Hero Section</h2>
                    <p class="max-w-sm text-sm">Update hero details here</p>
                </hgroup>

                <div class="grid gap-5 md:grid-cols-2">
                    <div class="p-5 border rounded-md border-slate-200">
                        <x-form.input-group>
                            <div class="flex items-start justify-between mb-5">
                                <div>
                                    <x-form.input-label for='hero_image'>Upload a new hero background</x-form.input-label>
                                    <p class="text-xs">Click the button on the right to view current image</p>
                                </div>

                                @if (!empty($current_hero_image))
                                    <button class="text-xs font-semibold text-blue-500" type="button" x-on:click="$dispatch('open-modal', 'show-current-hero-{{ $page }}')">View Image</button>
                                @endif
                            </div>
                            <x-filepond::upload
                                        wire:model.live="hero_image"
                                        id="hero_image"
                                        placeholder="Drag & drop your image or <span class='filepond--label-action'> Browse </span>"
                                    />
                            <x-form.input-error field="hero_image" />
                        </x-form.input-group>
                    </div>

                    <div class="p-5 space-y-5 border rounded-md border-slate-200">
                        <x-form.input-group>
                            <div class="mb-5">
                                <x-form.input-label for='heading'>Heading</x-form.input-label>
                                <p class="text-xs">Enter an eye catching tagline</p>
                            </div>
                            <x-form.textarea wire:model.live='heading' id="heading" name="heading" label="Heading" class="w-full" rows="2" />
                            <x-form.input-error field="heading" />
                        </x-form.input-group>
                        
                        <x-form.input-group>
                            <div class="mb-5">
                                <x-form.input-label for='subheading'>Subheading</x-form.input-label>
                                <p class="text-xs">This will appear below the heading</p>
                            </div>
                            <x-form.input-text wire:model.live='subheading' id="subheading" name="subheading" label="Subheading" class="w-1/2" />
                            <x-form.input-error field="subheading" />
                        </x-form.input-group>
                    </div>
                </div>
                
                <div class="flex items-center justify-between gap-1">
                    <x-primary-button>Save</x-primary-button>
                    <x-loading wire:loading wire:target='submit'>Editing hero, please wait</x-loading>
                </div>

                <x-modal.full name='show-current-hero-{{ $page }}' maxWidth='sm'>
                    <div class="p-5 space-y-5">
                        <img src="{{ asset('storage/' . $current_hero_image) }}" alt="Hero Background" class="bg-white border rounded-md border-slate-200">

                        <div class="flex justify-end">
                            <x-secondary-button type="button" x-on:click="show = false">Close</x-secondary-button>
                        </div>
                    </div>
                </x-modal.full>
            </form>
        HTML;
    }
}
