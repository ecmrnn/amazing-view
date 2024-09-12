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

    protected $guarded = [];

    public function building(): BelongsTo {
        return $this->belongsTo(Building::class);
    }
    
    public function type(): BelongsTo {
        return $this->BelongsTo(RoomType::class, 'room_type_id');
    }

    public function reservations(): BelongsToMany {
        return $this->BelongsToMany(Reservation::class, 'room_reservations');
    }

    public function amenities(): BelongsToMany {
        return $this->belongsToMany(Amenity::class, 'room_amenities');
    }
}
