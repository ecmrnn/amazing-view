<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Building extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function rooms(): HasMany {
        return $this->hasMany(Room::class);
    }

    public function slots(): HasMany {
        return $this->hasMany(BuildingSlot::class);
    }

    public static function boot() {
        parent::boot();

        self::creating(function ($building) {
            $building->prefix = strtoupper($building->prefix);
        });

        self::updating(function ($building) {
            $building->prefix = strtoupper($building->prefix);
        });
    }
}
