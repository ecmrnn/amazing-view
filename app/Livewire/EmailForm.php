<?php

namespace App\Livewire;

use App\Mail\SendAmazingEmail;
use App\Models\Settings;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\Mail;
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

    public function messages() {
        return [
            'email.required' => 'Enter your email address',
            'subject.required' => 'Enter the subject of your email',
            'message' => 'Write something amazing',
        ];
    }

    public function sendEmail() {
        $validated = $this->validate([
            'email' => $this->rules()['email'],
            'subject' => $this->rules()['subject'],
            'message' => $this->rules()['message'],
        ]);

        $amazing = Settings::where('key', 'site_email')->pluck('value')->first();

        Mail::to($amazing)->send(new SendAmazingEmail(
            $validated['email'],
            $validated['subject'],
            $validated['message']
        ));

        $this->toast('Email Sent!', 'success', 'We have received your email.');
        $this->reset();
    }

    public function render()
    {
        return <<<'HTML'
            <form wire:submit="sendEmail" class="p-5 mx-auto space-y-5 border rounded-lg bg-slate-50 border-slate-200 md:ml-auto text-zinc-800 md:mx-0">
                <hgroup>
                    <h2 class="text-lg font-semibold text-left">Send us an email here!</h2>
                    <p class="text-xs">Enter your amazing details below</p>
                </hgroup>

                <x-form.input-group>
                    <x-form.input-text wire:model.live="email" label="Email Address" class="w-full" type="email" id="email" name="email" />
                    <x-form.input-error field="email" />
                </x-form.input-group>

                <x-form.input-group>
                    <x-form.input-text wire:model.live="subject" label="Subject" class="w-full" type="text" id="subject" name="subject" />
                    <x-form.input-error field="subject" />
                </x-form.input-group>

                <x-form.input-group>
                    <x-form.input-label for="message">Message</x-form.input-label>
                    <x-form.textarea wire:model.live="message" max="200" name="message" id="message" rows="4" class="w-full" />
                    <x-form.input-error field="message" />
                </x-form.input-group>

                <x-loading wire:loading wire:target='sendEmail'>Sending email, please wait</x-loading>

                <div class="flex justify-end">
                    <x-primary-button class="inline-block ml-auto">Send Email</x-primary-button>
                </div>
            </form>
        HTML;
    }
}
