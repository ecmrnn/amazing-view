<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturedService extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 0;
    public const STATUS_INACTIVE = 1;

    protected $guarded = [];
}
