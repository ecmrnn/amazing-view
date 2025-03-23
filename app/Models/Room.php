<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use App\Enums\RoomStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public static function rules(array $excepts = []) {
        $rules = [
            'room_type' => 'required',
            'building' => 'required',
            'room_number' => 'required',
            'floor_number' => 'required|numeric|min:1|lte:max_floor_number',
            'min_capacity' => 'required|numeric|min:1',
            'max_capacity' => 'required|numeric|gte:min_capacity',
            'rate' => 'required|numeric|min:1000',
            'image_1_path' => 'nullable|image|mimes:jpeg,jpg,png',
        ];

        if (!empty($excepts)) {
            foreach ($excepts as $field) {
                unset($rules[$field]);
            }
        } 

        return $rules;
    }

    public function building(): BelongsTo {
        return $this->belongsTo(Building::class);
    }
    
    public function roomType(): BelongsTo {
        return $this->BelongsTo(RoomType::class, 'room_type_id');
    }

    public function reservations(): BelongsToMany {
        return $this->BelongsToMany(Reservation::class, 'room_reservations')->withPivot(['rate', 'status']);
    }

    public function amenities(): BelongsToMany {
        return $this->belongsToMany(Amenity::class, 'room_amenities')->withPivot('quantity', 'price');
    }

    public function amenitiesForReservation($reservation) {
        return $this->belongsToMany(Amenity::class, 'room_amenities')->where('room_amenities.reservation_id', $reservation)->withPivot('quantity', 'price');
    }
    
    public function items(): HasMany {
        return $this->hasMany(InvoiceItem::class);
    }

    public function itemsForInvoice($invoice) {
        return $this->hasMany(InvoiceItem::class)->where('invoice_id', $invoice);
    }

    // Get all reserved rooms between a specific range of dates
    public function scopeReservedRooms($query, $date_in, $date_out)
    {
        return $query->whereIn('rooms.status', [
                RoomStatus::RESERVED->value,
                RoomStatus::OCCUPIED->value,
                RoomStatus::UNAVAILABLE->value,
            ])
            ->whereHas('reservations', function ($query) use ($date_in, $date_out) {
                $query->where(function ($q) use ($date_in, $date_out) {
                    $q->where('date_in', '<=', $date_out)  // Starts before end of range
                    ->where('date_out', '>=', $date_in); // Ends after start of range
                })->whereIn('reservations.status', [
                    ReservationStatus::AWAITING_PAYMENT->value,
                    ReservationStatus::PENDING->value,
                    ReservationStatus::CONFIRMED->value,
                    ReservationStatus::CHECKED_IN->value,
                ]);
        });
    }   

    public static function boot() {
        parent::boot();

        self::creating(function ($room) {
            $room->room_number = $room->building->prefix . ' ' . $room->room_number;
        });
    }
}
