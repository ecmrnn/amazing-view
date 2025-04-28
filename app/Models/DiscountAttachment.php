<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscountAttachment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function discount(): BelongsTo {
        return $this->belongsTo(Discount::class);
    }
}
