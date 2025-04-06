<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promo extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reservations(): HasMany {
        return $this->hasMany(Reservation::class);
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->code = strtoupper($model->code);
        });
    }
}
