<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_AVAILABLE = 0;
    public const STATUS_UNAVAILABLE = 1;
    public const STATUS_OCCUPIED = 2;
    public const STATUS_RESERVED = 3;

    protected $guarded = [];

    public static function rules(array $excepts = []) {
        $rules = [
            'room_type' => 'required',
            'building' => 'nullable',
            'room_number' => 'required|unique:rooms,room_number',
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
        return $this->BelongsToMany(Reservation::class, 'room_reservations');
    }

    public function amenities(): BelongsToMany {
        return $this->belongsToMany(Amenity::class, 'room_amenities');
    }

    // Get all reserved rooms between a specific range of dates
    public function scopeReservedRooms($query, $date_in, $date_out) {
        return $query->whereHas('reservations', function ($query) use ($date_in, $date_out) {
            $query->where(function ($query) use ($date_in, $date_out) {
                $query->whereBetween('date_in', [$date_in, $date_out])
                    ->orWhereBetween('date_out', [$date_in, $date_out])
                    ->orWhere(function ($query) use ($date_in, $date_out) {
                        $query->where('date_in', '<', $date_in)
                            ->where('date_out', '>', $date_out);
                    });
            });
        });
    }
}
