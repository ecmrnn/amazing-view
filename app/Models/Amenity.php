<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable =[
        'name',
        'quantity',
        'price',
        'is_reservable',
    ];

    public function rooms(): BelongsToMany {
        return $this->belongsToMany(Room::class, 'room_amenities');
    }

    public function reservations(): BelongsToMany {
        return $this->belongsToMany(Reservation::class, 'reservation_amenities');
    }
}
