<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class RoomTypeService
{
    public function create($data) {
        DB::transaction(function () use ($data) {
            dd($data);  
        });
    }
}