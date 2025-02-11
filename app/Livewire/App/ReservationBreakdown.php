<?php

namespace App\Livewire\App;

use App\Models\Reservation;
use App\Services\BillingService;
use Illuminate\Support\Carbon;
use Livewire\Component;

class ReservationBreakdown extends Component
{
    public $reservation;
    public $number = 0;
    public $night_count = 1;
    public $breakdown = [];

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

        $billing_service = new BillingService;
        $this->night_count = Carbon::parse($date_in)->diffInDays($date_out);

        if ($this->night_count == 0) {
            $this->night_count = 1;
        }

        $this->breakdown = $billing_service->breakdown($reservation);
    }

    public function render()
    {
        return <<<'HTML'
        <div class="space-y-5">
            <x-note>Quantity on rooms are the total nights the guest will stay.</x-note>

            <!-- Table -->
            <div class="overflow-auto border rounded-md border-slate-200">
                <div class="min-w-[600px]">
                    <div class="grid grid-cols-6 px-5 py-3 text-sm font-semibold border-b bg-slate-50 text-zinc-800/60 border-slate-200">
                        <p>No.</p>
                        <p>Item</p>
                        <p>Type</p>
                        <p class="text-center">Quantity</p>
                        <p class="text-right">Price</p>
                        <p class="text-right">Total</p>
                    </div>
            
                    <div>
                        <?php $counter = 0; ?>

                        <!-- Rooms -->
                        @if ($reservation->rooms->count() > 0)
                            @foreach ($reservation->rooms as $room)
                                <?php $counter++ ?>
                                <div class="grid grid-cols-6 px-5 py-3 text-sm border-b border-dashed hover:bg-slate-50 border-slate-200">
                                    <p class="font-semibold opacity-50">{{ $counter }}</p>
                                    <p>{{ $room->building->prefix . ' ' . $room->room_number}}</p>
                                    <p>Room</p>
                                    <p class="text-center">{{ $night_count }}</p>
                                    <p class="text-right"><x-currency /> {{ number_format($room->pivot->rate, 2) }}</p>
                                    <p class="text-right"><x-currency /> {{ number_format($room->pivot->rate * $night_count, 2) }}</p>
                                </div>
                            @endforeach
                        @endif

                        <!-- Services -->
                        @if ($reservation->services->count() > 0)
                            @foreach ($reservation->services as $service)
                                <?php $counter++ ?>
                                <div class="grid grid-cols-6 px-5 py-3 text-sm border-b border-dashed hover:bg-slate-50 border-slate-200">
                                    <p class="font-semibold opacity-50">{{ $counter }}</p>
                                    <p>{{ $service->name }}</p>
                                    <p>Service</p>
                                    <p class="text-center">1</p>
                                    <p class="text-right"><x-currency /> {{ number_format($service->pivot->price, 2) }}</p>
                                    <p class="text-right"><x-currency /> {{ number_format($service->pivot->price, 2) }}</p>
                                </div>
                            @endforeach
                        @endif

                        <!-- Amenities -->
                        @if ($reservation->amenities->count() > 0)
                            @foreach ($reservation->amenities as $amenity)
                                <?php $counter++ ?>
                                <div class="grid grid-cols-6 px-5 py-3 text-sm border-b border-dashed hover:bg-slate-50 last:border-b-0 border-slate-200">
                                    <p class="font-semibold opacity-50">{{ $counter }}</p>
                                    <p>{{ $amenity->name }}</p>
                                    <p>Amenity</p>
                                    <p class="text-center">{{ $amenity->pivot->quantity }}</p>
                                    <p class="text-right"><x-currency /> {{ number_format($amenity->pivot->price, 2) }}</p>
                                    <p class="text-right"><x-currency /> {{ number_format($amenity->pivot->price * $amenity->pivot->quantity, 2) }}</p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Taxes -->
            <div class="flex justify-end text-sm">
                <table class="w-max">
                    <tr>
                        <td class="pr-5 font-semibold text-right">Subtotal</td>
                        <td class="text-right"><x-currency /> {{ number_format($breakdown['sub_total'], 2) }}</td>
                    </tr>
                    <tr>
                        <td class="pr-5 text-right">Vatable Sales</td>
                        <td class="text-right"><x-currency /> {{ number_format($breakdown['taxes']['vatable_sales'], 2) }}</td>
                    </tr>
                    @if ($breakdown['taxes']['vatable_exempt_sales'] > 0)
                        <tr>
                            <td class="pr-5 text-right">Vatable Exempt Sales</td>
                            <td class="text-right"><x-currency /> {{ number_format($breakdown['taxes']['vatable_exempt_sales'], 2) }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="pr-5 text-right">VAT</td>
                        <td class="text-right"><x-currency /> {{ number_format($breakdown['taxes']['vat'], 2) }}</td>
                    </tr>
                    @if ($breakdown['taxes']['discount'] > 0)
                        <tr>
                            <td class="pr-5 text-right">Discount</td>
                            <td class="text-right"><x-currency /> &lpar;{{ number_format($breakdown['taxes']['discount'], 2) }}&rpar;</td>
                        </tr>
                    @endif

                    <tr>
                        <td class="pt-5 pr-5 font-semibold text-right text-blue-500">Net Total</td>
                        <td class="pt-5 font-semibold text-right text-blue-500"><x-currency /> {{ number_format($breakdown['taxes']['net_total'], 2) }}</td>
                    </tr>
                </table>
                <!-- <div class="flex gap-5 justify-self-end">
                    <div class="text-right">
                        <p class="font-semibold">Subtotal</p>
                        <p>Vatable Sales</p>
                        <p>Vat</p>
                        <p class="font-semibold"><br />Net Total</p>
                    </div>
                    <div class="text-right">
                        <p>{{ number_format($breakdown['sub_total'], 2) }}    </p>
                        <p>{{ number_format($breakdown['taxes']['vatable_sales'], 2) }}</p>
                        <p>{{ number_format($breakdown['taxes']['vat'], 2) }}</p>

                        <p><br />{{ number_format($breakdown['taxes']['net_total'], 2) }}</p>
                    </div>
                </div> -->
            </div>
        </div>
        HTML;
    }
}
