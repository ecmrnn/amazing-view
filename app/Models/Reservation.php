<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable =[
        'room_id',
        'user_id',
        'date_in',
        'date_out',
        'adult_count',
        'children_count',
        'status',
    ];

    public function room(): BelongsTo {
        return $this->BelongsTo(Room::class);
    }

    public function user(): BelongsTo {
        return $this->BelongsTo(User::class);
    }
}
