<?php

namespace App\Livewire;

use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;

class EmailForm extends Component
{
    use DispatchesToast;

    #[Validate] public $email;
    #[Validate] public $subject;
    #[Validate] public $message;

    public function rules() {
        return [
            'email' => 'required|email:rfc,dns',
            'subject' => 'required|max:30',
            'message' => 'required|max:200',
        ];
    }

    public function sendEmail() {
        $this->validate([
            'email' => $this->rules()['email'],
            'subject' => $this->rules()['subject'],
            'message' => $this->rules()['message'],
        ]);

        $this->toast('Email Sent!', 'success', 'We have received your email.');
        $this->dispatch('client-email-sent');
        $this->reset();
    }

    public function render()
    {
        return <<<'HTML'
            <form x-data="{ count : 200, max : 200 }" x-on:client-email-sent.window="count = 200" class="mx-auto space-y-3 md:ml-auto md:mx-0" wire:submit="sendEmail">
                @csrf

                <h2 class="text-lg font-semibold">Send us an email here!</h2>

                <div class="space-y-1">
                    <x-form.input-text wire:model.live="email" label="Email Address" class="w-full" type="email" id="email" name="email" />
                    <x-form.input-error field="email" />
                </div>

                <div class="space-y-1">
                    <x-form.input-text wire:model.live="subject" label="Subject" class="w-full" type="text" id="subject" name="subject" />
                    <x-form.input-error field="subject" />
                </div>

                <div class="space-y-1">
                    <x-form.input-label for="message">Message</x-form.input-label>
                    <x-form.input-error field="message" />
                    <x-form.textarea wire:model.live="message" x-on:keyup="count = max - $el.value.length" x-bind:maxlength="max" name="message" id="message" class="max-h-[200px]"></x-form.textarea>
                </div>

                <p class="text-xs text-right">Remaining Characters: <span x-text="count"></span> / 200</p>

                <div class="flex justify-end">
                    <x-primary-button class="inline-block ml-auto">Send Email</x-primary-button>
                </div>
            </form>
        HTML;
    }
}
