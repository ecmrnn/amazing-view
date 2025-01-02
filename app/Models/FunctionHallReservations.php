<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FunctionHallReservations extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 0;
    public const STATUS_CANCELLED = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_COMPLETED = 3;

    protected $guarded = [];
}
