<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationAmenity extends Model
{
    use HasFactory;

    protected $fillable =[
        'reservation_id',
        'amenity_id',
        'quantity',
    ];
}
