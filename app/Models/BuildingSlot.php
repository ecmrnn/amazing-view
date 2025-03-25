<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BuildingSlot extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function building(): BelongsTo {
        return $this->belongsTo(Building::class);
    }

    public function room(): HasOne {
        return $this->hasOne(Room::class);
    }
}
