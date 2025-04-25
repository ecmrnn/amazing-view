<?php

namespace App\Livewire\App\Guest;

use App\Enums\ReservationStatus;
use App\Enums\RoomStatus;
use App\Models\Reservation;
use App\Models\Room;
use App\Services\ReservationService;
use App\Traits\DispatchesToast;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Nette\Utils\Random;

class CheckInGuest extends Component
{
    use DispatchesToast;

    protected $listeners = [
        'reservation-confirmed' => '$refresh',
    ];

    public $reservation;
    public $reservation_rid;
    public $placeholder;
    public $date_in;
    public $date_out;

    public function messages() {
        return [
            'reservation.required' => 'Enter the guest\'s Reservation ID'
        ];
    }

    public function getReservation() {
        $this->validate([
            'reservation_rid' => 'required'
        ]);

        $this->reservation = Reservation::where('rid', $this->reservation_rid)->first();
        
        if (empty($this->reservation)) {
            return $this->toast('Oof, not found!', 'warning', 'Reservation not found!');
        }
        
        $status = $this->reservation->status;
        
        $statusesWithMessages = [
            [ 
                'statuses' => [
                    ReservationStatus::AWAITING_PAYMENT->value,
                    ReservationStatus::PENDING->value,
                ],
                'message' => 'Reservation status must be confirmed'
            ],
            [
                'statuses' => [
                    ReservationStatus::EXPIRED->value,
                    ReservationStatus::CANCELED->value,
                    ReservationStatus::RESCHEDULED->value,
                    ReservationStatus::NO_SHOW->value,
                ],
                'message' => 'Reservation is problematic!'
            ],
            [
                'statuses' => [
                    ReservationStatus::COMPLETED->value,
                    ReservationStatus::CHECKED_OUT->value,
                ],
                'message' => 'Reservation is completed!'
            ],
            [
                'statuses' => [
                    ReservationStatus::CHECKED_IN->value,
                ],
                'message' => 'Guest is already in!'
            ],
        ];
        
        foreach ($statusesWithMessages as $group) {
            if (in_array($status, $group['statuses'])) {
                $this->reservation = null;
                $this->toast('Check-in Failed', 'info', $group['message']);
                return;
            }
        }
        
        // Passed all checks, now validate check-in date
        $this->date_in = $this->reservation->date_in;
        $this->date_out = $this->reservation->date_out;
        
        if ($this->date_in !== Carbon::now()->format('Y-m-d')) {
            $this->reservation = null;
            return $this->toast(
                'Check-in Failed',
                'warning',
                'Reservation check-in date is not until ' . Carbon::parse($this->date_in)->format('F j, Y') . '!'
            );
        }
    }

    public function checkIn() {
        // If a reservation was found
        if (!empty($this->reservation)) {
            $service = new ReservationService;
            $service->checkIn($this->reservation);

            $this->reset();
            $this->dispatch('pg:eventRefresh-GuestTable');
            $this->dispatch('guest-checked-in');
            $this->toast('Success!', description: 'Success, guest checked-in!');
        } else {
            $this->toast('Oof, not found!', 'warning', 'Reservation not found!');
        }
    }

    public function render()
    {
        $this->placeholder = 'R' . now()->format('ymd') . Random::generate(3, '0-9');
        
        return <<<'HTML'
            <form wire:submit="checkIn" class="p-5 space-y-5" x-on:guest-checked-in.window="show = false">
                <hgroup>
                    <h2 class="text-lg font-semibold">Check-in Guest</h2>
                    <p class="text-xs">Enter the <strong class="text-blue-500">Reservation ID</strong> of the guest you want to check-in</p>
                </hgroup>

                @if (!empty($reservation))
                    <div class="flex items-center w-full gap-3 px-3 py-2 text-xs text-green-800 border border-green-500 rounded-md bg-green-50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check"><path d="M20 6 9 17l-5-5"/></svg>
                        <p>Ready for check-in!</p>
                    </div>

                    <div class="p-5 space-y-3 bg-white border rounded-md border-slate-200">
                        {{-- Reservation ID --}}
                        <div class="flex items-center gap-3">
                            <x-icon>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-qr-code-icon lucide-qr-code"><rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3"/><path d="M21 21v.01"/><path d="M12 7v3a2 2 0 0 1-2 2H7"/><path d="M3 12h.01"/><path d="M12 3h.01"/><path d="M12 16v.01"/><path d="M16 12h1"/><path d="M21 12v.01"/><path d="M12 21v-1"/></svg>
                            </x-icon>
                            <div>
                                <p class="text-sm font-semibold">{{ $reservation->rid }}</p>
                                <p class="text-xs">Reservation ID</p>
                            </div>
                        </div>
                        {{-- Name --}}
                        <div class="flex items-center gap-3">
                            <x-icon>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-icon lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </x-icon>
                            <div>
                                <p class="text-sm font-semibold">{{ $reservation->user->name() }}</p>
                                <p class="text-xs">Name</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 space-y-3 bg-white border rounded-md border-slate-200">
                        {{-- Check-in date --}}
                        <div class="flex items-center gap-3">
                            <x-icon>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-arrow-up-icon lucide-calendar-arrow-up"><path d="m14 18 4-4 4 4"/><path d="M16 2v4"/><path d="M18 22v-8"/><path d="M21 11.343V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h9"/><path d="M3 10h18"/><path d="M8 2v4"/></svg>
                            </x-icon>
                            <div>
                                <p class="text-sm font-semibold">{{ date_format(date_create($reservation->date_in), 'F j, Y') . ' at ' . date_format(date_create($reservation->time_in), 'g:i A') }}</p>
                                <p class="text-xs">Check-in date and time</p>
                            </div>
                        </div>
                        {{-- Check-in date --}}
                        <div class="flex items-center gap-3">
                            <x-icon>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-arrow-down-icon lucide-calendar-arrow-down"><path d="m14 18 4 4 4-4"/><path d="M16 2v4"/><path d="M18 14v8"/><path d="M21 11.354V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h7.343"/><path d="M3 10h18"/><path d="M8 2v4"/></svg>
                            </x-icon>
                            <div>
                                <p class="text-sm font-semibold">{{ date_format(date_create($reservation->date_out), 'F j, Y') . ' at ' . date_format(date_create($reservation->time_out), 'g:i A') }}</p>
                                <p class="text-xs">Check-out date and time</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <x-loading wire:loading wire:target="checkIn">Checking-in the guest</x-loading>
                        
                        <div class="flex gap-1 ml-auto">
                            <x-secondary-button type="button" x-on:click="$wire.set('reservation', null)">Cancel</x-secondary-button>
                            <x-primary-button type="submit">Check-in</x-primary-button>
                        </div>
                    </div>
                @else
                    <x-form.input-text label="Reservation ID" wire:model="reservation_rid" id="reservation" class="w-full" placeholder="e.g. {{ $placeholder }}" />
                    <x-form.input-error field="reservation" />
                    
                    <x-loading wire:loading wire:target="getReservation">Finding reservation</x-loading>
                    
                    <div class="flex justify-end gap-1">
                        <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                        <x-primary-button type="button" wire:click="getReservation" wire:loading.attr="disabled">Find</x-primary-button>
                    </div>
                @endif
            </form>
        HTML;
    }
}
