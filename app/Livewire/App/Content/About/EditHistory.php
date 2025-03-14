<?php

namespace App\Livewire\App\Content\About;

use App\Models\MediaFile;
use App\Models\PageContent;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class EditHistory extends Component
{
    use DispatchesToast, WithFilePond;
    
    #[Validate] public $history;
    #[Validate] public $history_image;
    #[Validate] public $history_length;

    public function mount() {
        $this->history = PageContent::where('key', 'about_history')->pluck('value')->first();
        $this->history_length = strlen($this->history);
    }

    public function rules() {
        return [
            'history' => 'required|max:1000',
            'history_image' => 'nullable|mimes:jpg,jpeg,png|image',
        ];
    }

    public function messages() {
        return [
            'history.required' => 'History is required',
            'history.max' => 'Maximum characters is 1000',

        ];
    }

    public function submit() {
        $this->validate([
            'history' => $this->rules()['history'],
            'history_image' => $this->rules()['history_image'],
        ]);

        if (!empty($this->history_image)) {
            // delete saved image para di magdoble doble
            $history_image = MediaFile::where('key', 'about_history_image')->first();
            
            if (!empty($history_image->path)) {
                Storage::disk('public')->delete($history_image->path);
            }
            
            $history_image->path = $this->history_image->store('about', 'public');
            $history_image->save();
        }

        $history = PageContent::where('key', 'about_history')->first();
        $history->value = $this->history;
        $history->save();

        $this->toast('History Updated', 'success', 'History updated successfully!');
        $this->dispatch('history-edited');
        $this->dispatch('pond-reset');
    }

    public function render()
    {
        return <<<'HTML'
            <form x-data="{ count : 1000 - @js($history_length), max : 1000 }" x-on:history-edited.window="show = false; count = 0;" class="p-5 space-y-5" wire:submit="submit">
                <hgroup>
                    <h2 class="text-lg font-semibold capitalize">Edit History</h2>
                    <p class="max-w-sm text-sm">Update history details here</p>
                </hgroup>

                <x-form.input-group>
                    <div>
                        <x-form.input-label for="edit-history-image">Image</x-form.input-label>
                        <p class="text-xs">Upload a new image here</p>
                    </div>

                    <x-filepond::upload
                        wire:model.live="history_image"
                        id="edit-history-image"
                        placeholder="<span class='text-xs'>Drag & drop your new image or <span class='filepond--label-action'> Browse </span></span>"
                    />

                    <x-form.input-error field="history_image" />
                </x-form.input-group>

                <x-form.input-group>
                    <div>
                        <x-form.input-label for="edit-history">History</x-form.input-label>
                        <p class="text-xs">Write an amazing story here</p>
                    </div>

                    <x-form.textarea id="edit-history" name="history" wire:model.live="history" class="w-full" x-on:keyup="count = max - $el.value.length" />
                    
                    <div class="flex justify-between">
                        <span><x-form.input-error field="history" /></span>
                        <p class="text-xs text-right">Remaining Characters: <span x-text="count"></span> / 1000</p>
                    </div>
                </x-form.input-group>

                <x-loading wire:loading wire:target='submit'>Editing history, please wait</x-loading>
                
                <div class="flex items-center justify-end gap-1">
                    <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                    <x-primary-button type="submit">Edit</x-primary-button>
                </div>
            </form>
        HTML;
    }
}
