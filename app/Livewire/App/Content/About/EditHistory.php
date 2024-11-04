<?php

namespace App\Livewire\App\Content\About;

use App\Models\Content;
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
        $this->history = Content::whereName('about_history')->pluck('long_value')->first();
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
            $history_image = Content::whereName('about_history_image')->first();
            Storage::disk('public')->delete($history_image->value);
            
            $history_image->value = $this->history_image->store('about', 'public');
            $history_image->save();
        }

        $history = Content::whereName('about_history')->first();
        $history->long_value = $this->history;
        $history->save();

        $this->toast('History Updated', 'success', 'History updated successfully!');
        $this->dispatch('history-edited');
        $this->dispatch('pond-reset');
    }

    public function render()
    {
        return <<<'HTML'
            <div x-data="{ count : 1000 - @js($history_length), max : 1000 }" x-on:history-edited.window="show = false; count = 0;" class="block p-5 space-y-5 bg-white" wire:submit="submit">
                <hgroup>
                    <h2 class="font-semibold text-center capitalize">Edit History</h2>
                    <p class="max-w-sm text-sm text-center">Update history details here</p>
                </hgroup>

                <div class="space-y-2">
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
                </div>

                <div class="space-y-2">
                    <div>
                        <x-form.input-label for="edit-history">History</x-form.input-label>
                        <p class="text-xs">Write an amazing story here</p>
                    </div>

                    <x-form.textarea id="edit-history" name="history" wire:model.live="history" class="w-full" x-on:keyup="count = max - $el.value.length" />
                    
                    <div class="flex justify-between">
                        <span><x-form.input-error field="history" /></span>
                        <p class="text-xs text-right">Remaining Characters: <span x-text="count"></span> / 1000</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-center gap-1">
                    <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                    <x-primary-button type="button" wire:click="submit">Edit History</x-primary-button>
                </div>
            </div>
        HTML;
    }
}
