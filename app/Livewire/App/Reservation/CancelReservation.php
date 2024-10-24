<?php

namespace App\Livewire\App\Reservation;

use App\Models\Reservation;
use Illuminate\Support\Carbon;
use Livewire\Component;

class CancelReservation extends Component
{
    public $reasons;
    public $reservation;

    public function mount(Reservation $reservation) {
        $this->reasons = collect();
        $this->reservation = $reservation;
    }

    public function cancelReservation() {
        $this->reservation->cancel_date = Carbon::now()->format('Y-m-d');   
        $this->reservation->status = Reservation::STATUS_CANCELED;
        $this->reservation->save();
        $this->dispatch('toast', json_encode(['message' => 'Cancellation successful', 'type' => 'success', 'description' => 'Reservation cancelled!']));
    }

    public function render()
    {
        $this->reasons->push(['name' => 'Road Closure']);
        $this->reasons->push(['name' => 'Inclement Weather']);

        return <<<'HTML'
        <section class="p-5 space-y-5 bg-white">
            <hgroup>
                <h2 class="text-sm font-semibold text-center capitalize">Reservation Cancellation</h2>
                <p class="max-w-sm text-xs text-center">Are you sure you really want to cancel this reservation?</p>
            </hgroup>

            <div class="px-3 py-2 border border-gray-300 rounded-md">
                <x-form.input-radio name="reason" x-model="reason" value="guest" id="guest" label="The guest want to cancel" />
                <x-form.input-radio name="reason" x-model="reason" value="management" id="management" label="The management want to cancel" />    
            </div>

            <div x-show="reason == 'management'" class="space-y-3">
                <x-form.input-label for="selectReason">Select a Reason</x-form.input-label>
                <x-form.select id="selectReason">
                    @foreach ($reasons as $reason)
                        <option>{{ $reason['name'] }}</option>
                    @endforeach
                </x-form.select>
            </div>
            
            <div class="flex items-center justify-center gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-danger-button type="button" x-on:click="$wire.cancelReservation(); show = false;">Cancel Reservation</x-danger-button>
            </div>
        </section>
        HTML;
    }
}
