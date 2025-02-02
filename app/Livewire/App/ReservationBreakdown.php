<?php

namespace App\Livewire\App;

use App\Models\Reservation;
use Illuminate\Support\Carbon;
use Livewire\Component;

class ReservationBreakdown extends Component
{
    public $reservation;
    public $number = 0;
    public $night_count = 1;

    public function mount(Reservation $reservation) {
        $this->reservation = $reservation;
        $date_in = $reservation->date_in;
        $date_out = $reservation->date_out;

        if (!empty($reservation->resched_date_in)) {
            $date_in = $reservation->resched_date_in;
        }
        if (!empty($reservation->resched_date_out)) {
            $date_out = $reservation->resched_date_out;
        }

        $this->night_count = Carbon::parse($date_in)->diffInDays($date_out);
    }

    public function render()
    {
        return <<<'HTML'
        <div class="overflow-auto border rounded-md border-slate-200">
            <div class="min-w-[600px]">
                <div class="grid grid-cols-6 px-5 py-3 text-sm font-semibold border-b bg-slate-50 text-zinc-800/60 border-slate-200">
                    <p>No.</p>
                    <p>Item</p>
                    <p>Type</p>
                    <p class="text-center">Quantity</p>
                    <p class="text-center">Price</p>
                    <p class="text-center">Total</p>
                </div>
                
                <div>
                    <?php $counter = 0; ?>

                    <!-- Rooms -->
                    @forelse ($reservation->rooms as $room)
                        <?php $counter++ ?>
                        <div class="grid grid-cols-6 px-5 py-3 text-sm border-b border-dashed hover:bg-slate-50 border-slate-200">
                            <p class="font-semibold opacity-50">{{ $counter }}</p>
                            <p>{{ $room->building->prefix . ' ' . $room->room_number}}</p>
                            <p>Room</p>
                            <p class="text-center">{{ $night_count }}</p>
                            <p class="text-center"><x-currency /> {{ number_format($room->pivot->rate, 2) }}</p>
                            <p class="text-center"><x-currency /> {{ number_format($room->pivot->rate * $night_count, 2) }}</p>
                        </div>
                    @empty
                        <div class="col-span-6 text-xs font-semibold text-left">
                            No rooms selected...
                        </div>
                    @endforelse

                    <!-- Services -->
                    @forelse ($reservation->services as $service)
                        <?php $counter++ ?>
                        <div class="grid grid-cols-6 px-5 py-3 text-sm border-b border-dashed hover:bg-slate-50 border-slate-200">
                            <p class="font-semibold opacity-50">{{ $counter }}</p>
                            <p>{{ $service->name }}</p>
                            <p>Service</p>
                            <p class="text-center">1</p>
                            <p class="text-center"><x-currency /> {{ number_format($service->pivot->price, 2) }}</p>
                            <p class="text-center"><x-currency /> {{ number_format($service->pivot->price, 2) }}</p>
                        </div>
                    @empty
                        <div class="col-span-6 text-xs font-semibold text-left">
                            No service selected...
                        </div>
                    @endforelse

                    <!-- Amenities -->
                    @forelse ($reservation->amenities as $amenity)
                        <?php $counter++ ?>
                        <div class="grid grid-cols-6 px-5 py-3 text-sm border-b border-dashed hover:bg-slate-50 last:border-b-0 border-slate-200">
                            <p class="font-semibold opacity-50">{{ $counter }}</p>
                            <p>{{ $amenity->name }}</p>
                            <p>Amenity</p>
                            <p class="text-center">{{ $amenity->pivot->quantity }}</p>
                            <p class="text-center"><x-currency /> {{ number_format($amenity->pivot->price, 2) }}</p>
                            <p class="text-center"><x-currency /> {{ number_format($amenity->pivot->price * $amenity->pivot->quantity, 2) }}</p>
                        </div>
                    @empty
                        <div class="col-span-6 text-xs font-semibold text-left">
                            No amenity selected...
                        </div>
                    @endforelse
                </div>
            </div>
        </div>       
        HTML;
    }
}
