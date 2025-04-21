<?php

namespace App\Livewire\App\Amenity;

use App\Enums\ReservationStatus;
use App\Models\Amenity;
use App\Models\RoomAmenity;
use Livewire\Component;

class AmenityCards extends Component
{
    protected $listeners = [
        'amenity-updated' => '$refresh',
        'amenity-deleted' => '$refresh',
        'amenity-created' => '$refresh',
    ];

    public $reserved_amenities;
    public $popular_amenity;
    public $critical_amenities;
    public $amenity_sales;

    public function render()
    {
        $this->reserved_amenities = RoomAmenity::join('reservations', 'reservations.id', '=', 'room_amenities.reservation_id')
            ->whereIn('status', [ReservationStatus::CONFIRMED, ReservationStatus::CHECKED_IN])
            ->sum('quantity');
        $this->popular_amenity = RoomAmenity::selectRaw('amenities.name, SUM(room_amenities.quantity) as total_quantity')
            ->join('amenities', 'room_amenities.amenity_id', '=', 'amenities.id')
            ->groupBy('amenities.name')
            ->orderByRaw('total_quantity desc')
            ->limit(1)
            ->first();
        $this->critical_amenities = Amenity::where('quantity', '<=', 10)
            ->count();

        $this->amenity_sales = 0;
        $finalized_amenities = RoomAmenity::join('reservations', 'reservations.id', '=', 'room_amenities.reservation_id')
            ->whereIn('reservations.status', [ReservationStatus::CHECKED_OUT->value, ReservationStatus::COMPLETED->value])
            ->get();
            
        if ($finalized_amenities->count() > 0) {
            foreach ($finalized_amenities as $amenity) {
                $this->amenity_sales += ($amenity->price * $amenity->quantity);
            }
        }
        
        return view('livewire.app.amenity.amenity-cards');
    }
}
