<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AdditionalServices extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reservations(): BelongsToMany {
        return $this->belongsToMany(Reservation::class, 'additional_service_reservations')->withPivot('price');
    }
}
