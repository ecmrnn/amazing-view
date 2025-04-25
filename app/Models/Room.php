<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use App\Enums\RoomStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

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

    public function slot(): HasOne {
        return $this->hasOne(BuildingSlot::class);
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

    public function attachments(): HasMany {
        return $this->hasMany(RoomAttachment::class);
    }

    public function inclusions(): HasMany {
        return $this->hasMany(RoomInclusion::class);
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
        $time_in = '';
        $time_out = '';

        if ($date_in == $date_out) {
            $time_in = '08:00:00';
            $time_out = '18:00:00';
        } else {
            $time_in = '14:00:00';
            $time_out = '12:00:00';
        }

        if ($date_in == $date_out) {
            return $query->whereIn('rooms.status', [
                    RoomStatus::RESERVED->value,
                    RoomStatus::OCCUPIED->value,
                    RoomStatus::UNAVAILABLE->value,
                ])
                ->whereHas('reservations', function ($query) use ($date_in, $date_out) {
                    $query->where(function ($q) use ($date_in, $date_out)  {
                        $q->whereBetween('date_in', [$date_in, $date_out])
                        ->orWhereBetween('date_out', [$date_in, $date_out]);
                    })
                    ->orWhere(function ($q) use ($date_in, $date_out) {
                        $q->whereDate('date_in', '<', $date_out)
                            ->whereDate('date_out', '<', $date_in);
                    })
                    ->whereIn('reservations.status', [
                        ReservationStatus::AWAITING_PAYMENT->value,
                        ReservationStatus::PENDING->value,
                        ReservationStatus::CONFIRMED->value,
                        ReservationStatus::CHECKED_IN->value,
                    ]);
            });
        }
        
        $query = $query->whereIn('rooms.status', [
            RoomStatus::RESERVED->value,
            RoomStatus::OCCUPIED->value,
            RoomStatus::UNAVAILABLE->value,
        ])
        ->whereHas('reservations', function ($query) use ($date_in, $date_out, $time_in, $time_out) {
            $query->where(function ($q) use ($date_in, $date_out, $time_in, $time_out) {
                $q->whereRaw('CONCAT(`date_in`, " ", `time_in`) < ?', [$date_out . ' ' . $time_out])
                  ->whereRaw('CONCAT(`date_out`, " ", `time_out`) > ?', [$date_in . ' ' . $time_in]);
            })
            ->whereIn('reservations.status', [
                ReservationStatus::AWAITING_PAYMENT->value,
                ReservationStatus::PENDING->value,
                ReservationStatus::CONFIRMED->value,
                ReservationStatus::CHECKED_IN->value,
            ]);
        });    

        return $query;
    }   

    public static function boot() {
        parent::boot();

        self::creating(function ($room) {
            $room->room_number = $room->building->prefix . ' ' . $room->room_number;
        });

        self::updating(function ($room) {
            $room_number = trim(Str::after($room->room_number, $room->building->prefix));
            $room->room_number = $room->building->prefix . ' ' . $room_number;
        });
    }
}