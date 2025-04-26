<?php

namespace App\Livewire\App\Reservation;

use App\Mail\Reservation\Cancelled;
use App\Mail\Reservation\Confirmed;
use App\Mail\Reservation\Expire;
use App\Mail\Reservation\NoShow;
use App\Mail\Reservation\Received;
use App\Mail\Reservation\Reminder;
use App\Mail\Reservation\ThankYou;
use App\Mail\Reservation\Updated;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Validate;
use Livewire\Component;

class SendReservationEmail extends Component
{
    use DispatchesToast;

    public $reservation;
    #[Validate] public $email;
    #[Validate] public $email_type = 'received';

    public function mount($reservation)
    {
        $this->reservation = $reservation;
        $this->email = $reservation->user->email;
    }

    public function rules() {
        return [
            'email' => 'required|email',
            'email_type' => 'required',
        ];
    }

    public function sendEmail() {
        $this->validate([
            'email' => $this->rules()['email'],
            'email_type' => $this->rules()['email_type'],
        ]);

        switch ($this->email_type) {
            case 'confirmed':
                Mail::to($this->email)->queue(new Confirmed($this->reservation));
                break;
            case 'reminder':
                Mail::to($this->email)->queue(new Reminder($this->reservation));
                break;
            case 'updated':
                Mail::to($this->email)->queue(new Updated($this->reservation));
                break;
            case 'received':
                Mail::to($this->email)->queue(new Received($this->reservation));
                break;
            case 'thank you':
                Mail::to($this->email)->queue(new ThankYou($this->reservation));
                break;
            case 'expired':
                Mail::to($this->email)->queue(new Expire($this->reservation));
                break;
            case 'no show':
                Mail::to($this->email)->queue(new NoShow($this->reservation));
                break;
            case 'cancelled':
                Mail::to($this->email)->queue(new Cancelled($this->reservation));
                break;
        }
        
        $this->dispatch('email-sent');
        $this->toast('Request Success!', description: 'Sending email, stay online!');
    }

    public function render()
    {
        return <<<'HTML'
            <form wire:submit="sendEmail" class="p-5 space-y-5" x-on:email-sent.window="show = false">
                <hgroup>
                    <h2 class="text-lg font-semibold">Send Email</h2>
                    <p class="text-xs">Choose which email you want to send.</p>
                </hgroup>

                <x-form.input-group>
                    <x-form.select wire:model.live="email_type" id="email_type" name="email_type">
                        <optgroup label="In Progress">
                            @if($reservation->status == App\Enums\ReservationStatus::CONFIRMED->value)
                                <option value="confirmed">Confirmed Reservation Email</option>
                                <option value="reminder">Reservation Reminder</option>
                            @endif
                            <option value="updated">Updated Reservation Email</option>
                            <option value="received">Welcome Email</option>
                        </optgroup>
                            
                        @if($reservation->status == App\Enums\ReservationStatus::CHECKED_OUT->value)
                            <optgroup label="Finalized">
                                <option value=""></option>
                                <option value="thank you">Thank You Email</option>
                            </optgroup>
                        @endif
                    
                        @if(in_array($reservation->status, [
                                App\Enums\ReservationStatus::EXPIRED->value,
                                App\Enums\ReservationStatus::NO_SHOW->value,
                                App\Enums\ReservationStatus::CANCELED->value,
                            ]))
                            <optgroup label="Problematic">
                                <option value=""></option>
                                @if($reservation->status == App\Enums\ReservationStatus::EXPIRED->value)
                                    <option value="expired">Expired Reservation Email</option>
                                @endif
                                @if($reservation->status == App\Enums\ReservationStatus::NO_SHOW->value)
                                    <option value="no show">No Show Email</option>
                                @endif
                                @if($reservation->status == App\Enums\ReservationStatus::CANCELED->value)
                                    <option value="cancelled">Cancellation Email</option>
                                @endif
                            </optgroup>
                        @endif
                    </x-form.select>
                    <x-form.input-error field="email_type" />
                </x-form.input-group>

                <x-form.input-group>
                    <x-form.input-label for='email'>Guest&apos;s email address</x-form.input-label>
                    <x-form.input-text wire:model.live="email" id="email" name="email" label="Email" />
                    <x-form.input-error field="email" />
                </x-form.input-group>

                <x-loading wire:loading wire:target="sendEmail">Processing your request, please wait</x-loading>

                <div class="flex justify-end gap-1">
                    <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                    <x-primary-button type="submit">Send</x-primary-button>
                </div>
            </form>
        HTML;
    }
}
