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
    public $discount;

    public function mount(Reservation $reservation) {
        $this->reservation = $reservation;
        $this->discount = null;
        
        if ($this->reservation->discounts->first() != null) {
            $this->discount = $this->reservation->discounts->first()->description;
        }

        $date_in = $reservation->date_in;
        $date_out = $reservation->date_out;
        $billing_service = new BillingService;
        
        $this->night_count = Carbon::parse((string) $date_in)->diffInDays($date_out);
        
        if ($this->night_count == 0) {
            $this->night_count = 1;
        }

        $this->breakdown = $billing_service->breakdown($reservation);
    }

    public function render()
    {
        return <<<'HTML'
        <div class="space-y-5">
            <div class="space-y-2">
                <p class="text-xs"><strong>Note:</strong> Quantity on rooms are the total nights the guest will stay.</p>
                <!-- Table -->
                <x-table.table headerCount="6">
                    <x-slot:headers>
                        <p>No.</p>
                        <p>Item</p>
                        <p>Type</p>
                        <p class="text-center">Quantity</p>
                        <p class="text-right">Price</p>
                        <p class="text-right">Total</p>
                    </x-slot:headers>

                    <div>
                        <?php $counter = 0; ?>
                        <!-- Rooms -->
                        @if ($reservation->rooms->count() > 0)
                            @foreach ($reservation->rooms as $room)
                                <?php $counter++ ?>
                                <div class="grid grid-cols-6 px-5 py-3 text-sm border-t border-solid first:border-t-0 hover:bg-slate-50 border-slate-200">
                                    <p class="font-semibold opacity-50">{{ $counter }}</p>
                                    <p>{{ $room->room_number}}</p>
                                    <p>Room</p>
                                    <p class="text-center">{{ $night_count }}</p>
                                    <p class="text-right"><x-currency />{{ number_format($room->pivot->rate, 2) }}</p>
                                    <p class="text-right"><x-currency />{{ number_format($room->pivot->rate * $night_count, 2) }}</p>
                                </div>
                            @endforeach
                        @endif
                        <!-- Services -->
                        @if ($reservation->services->count() > 0)
                            @foreach ($reservation->services as $service)
                                <?php $counter++ ?>
                                <div class="grid grid-cols-6 px-5 py-3 text-sm border-t border-solid hover:bg-slate-50 border-slate-200">
                                    <p class="font-semibold opacity-50">{{ $counter }}</p>
                                    <p>{{ $service->name }}</p>
                                    <p>Service</p>
                                    <p class="text-center">1</p>
                                    <p class="text-right"><x-currency />{{ number_format($service->pivot->price, 2) }}</p>
                                    <p class="text-right"><x-currency />{{ number_format($service->pivot->price, 2) }}</p>
                                </div>
                            @endforeach
                        @endif
                        <!-- Amenities -->
                        @foreach ($reservation->rooms as $room)
                            @foreach ($room->amenitiesForReservation($reservation->id)->get() as $amenity)
                                <?php $counter++ ?>
                                <div class="grid grid-cols-6 px-5 py-2.5 items-center text-sm border-t border-solid hover:bg-slate-50 border-slate-200">
                                    <p class="font-semibold opacity-50">{{ $counter }}</p>
                                    <div class="flex items-center gap-3">
                                        <p>{{ $amenity->name }}</p>
                                        <p class="px-2 py-1 text-xs font-semibold border rounded-md bg-slate-50 border-slate-200">{{ $room->room_number }}</p>
                                    </div>
                                    <p>Amenity</p>
                                    <p class="text-center">{{ $amenity->pivot->quantity }}</p>
                                    <p class="text-right"><x-currency />{{ number_format($amenity->pivot->price, 2) }}</p>
                                    <p class="text-right"><x-currency />{{ number_format($amenity->pivot->price * $amenity->pivot->quantity, 2) }}</p>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </x-table.table>

                @if ($reservation->invoice->items->count() > 0)
                    <h2 class="text-xs font-semibold">Other Charges</h2>
                    <div class="overflow-auto border rounded-md border-slate-200">
                        <div class="min-w-[600px]">
                            <div>
                                <?php $counter = 0 ?>
                                @foreach ($reservation->invoice->items as $item)
                                    <?php $counter++ ?>
                                    <div class="grid grid-cols-6 px-5 py-3 text-sm border-b last:border-b-0 border-slate-200">
                                        <p class="font-semibold opacity-50">{{ $counter }}</p>
                                        <div class="flex items-center gap-3 w-max">
                                            <p>{{ $item->name }}</p>
                                            <p class="px-2 py-1 text-xs font-semibold border rounded-md bg-slate-50 border-slate-200">{{ $item->room->room_number }}</p>
                                        </div>
                                        <p></p>
                                        <p class="text-center">{{ $item->quantity }}</p>
                                        <p class="text-right"><x-currency />{{ number_format($item->price, 2) }}</p>
                                        <p class="text-right"><x-currency />{{ number_format($item->quantity * $item->price, 2) }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Taxes -->
            <div class="flex justify-end text-sm">
                <table class="w-max">
                    <tr>
                        <td class="pr-5 font-semibold text-right">Subtotal</td>
                        <td class="text-right"><x-currency />{{ number_format($breakdown['sub_total'], 2) }}</td>
                    </tr>
                    <tr>
                        <td class="pt-5 pr-5 text-right">Vatable Sales</td>
                        <td class="pt-5 text-right"><x-currency />{{ number_format($breakdown['taxes']['vatable_sales'], 2) }}</td>
                    </tr>
                    @if ($breakdown['taxes']['vatable_exempt_sales'] > 0)
                        <tr>
                            <td class="pr-5 text-right">Vatable Exempt Sales</td>
                            <td class="text-right"><x-currency />{{ number_format($breakdown['taxes']['vatable_exempt_sales'], 2) }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="pr-5 text-right">VAT</td>
                        <td class="text-right"><x-currency />{{ number_format($breakdown['taxes']['vat'], 2) }}</td>
                    </tr>
                    @if ($breakdown['taxes']['other_charges'] > 0)
                        <tr>
                            <td class="pr-5 text-right">Other Charges</td>
                            <td class="text-right"><x-currency />{{ number_format($breakdown['taxes']['other_charges'], 2) }}</td>
                        </tr>
                    @endif
                    @if ($breakdown['taxes']['discount'] > 0)
                        <tr>
                            <td class="pr-5 text-right">LESS: {{ $discount }}</td>
                            <td class="text-right"><x-currency />&lpar;{{ number_format($breakdown['taxes']['discount'], 2) }}&rpar;</td>
                        </tr>
                    @endif
                    @if ($breakdown['taxes']['promo_discount'] > 0)
                        <tr>
                            <td class="pr-5 text-right">LESS: Promo Discount</td>
                            <td class="text-right"><x-currency />&lpar;{{ number_format($breakdown['taxes']['promo_discount'], 2) }}&rpar;</td>
                        </tr>
                    @endif

                    <tr>
                        <td class="pt-5 pr-5 font-semibold text-right text-blue-500">Net Total</td>
                        <td class="pt-5 font-semibold text-right text-blue-500"><x-currency />{{ number_format($breakdown['taxes']['net_total'], 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>
        HTML;
    }
}
