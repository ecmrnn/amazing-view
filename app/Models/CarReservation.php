<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarReservation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
}
