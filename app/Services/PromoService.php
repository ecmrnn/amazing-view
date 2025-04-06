<?php

namespace App\Services;

use App\Enums\PromoStatus;
use App\Models\Promo;
use Illuminate\Support\Facades\DB;

class PromoService
{
    public function create($data) {
        // Assuming $data is already validated at this point
        return DB::transaction(function () use ($data) {
            return Promo::create($data);
        });
    }

    public function update(Promo $promo, $data) {
        return DB::transaction(function () use ($promo, $data) {
            $promo->update($data);
            return $promo;
        });
    }

    public function toggleStatus(Promo $promo) {
        return DB::transaction(function () use ($promo) {
            if ($promo->status == PromoStatus::ACTIVE->value) {
                return $promo->update([
                    'status' => PromoStatus::INACTIVE->value
                ]);
            }

            return $promo->update([
                'status' => PromoStatus::ACTIVE->value
            ]);
        });
    }

    public function delete(Promo $promo) {
        return DB::transaction(function () use ($promo) {
            return $promo->delete();
        });
    }
}
