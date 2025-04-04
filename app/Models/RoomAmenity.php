<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RoomAmenity extends Model
{
    use HasFactory;

    protected $fillable =[
        'room_id',
        'amenity_id',
    ];
}
