<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable =[
        'room_type_id',
        'building_id',
        'room_number',
        'floor_number',
        'capacity',
        'image_1_path',
        'image_2_path',
        'image_3_path',
        'image_4_path',
        'status',
    ];

    public function building(): BelongsTo {
        return $this->belongsTo(Building::class);
    }
    
    public function type(): BelongsTo {
        return $this->BelongsTo(RoomType::class);
    }

    public function reservations(): BelongsToMany {
        return $this->BelongsToMany(Reservation::class, 'room_reservations');
    }

    public function amenities(): BelongsToMany {
        return $this->belongsToMany(Amenity::class, 'room_amenities');
    }
}
