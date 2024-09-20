<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Room extends Model
{
    use HasFactory;

    public const STATUS_AVAILABLE = 0;
    public const STATUS_UNAVAILABLE = 1;
    public const STATUS_OCCUPIED = 2;
    public const STATUS_RESERVED = 3;

    protected $guarded = [];

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
}
