<?php

namespace App\Console\Commands;

use App\Enums\PromoStatus;
use App\Models\Promo;
use Illuminate\Console\Command;

class DisableExpiredPromo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promo:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Promo::where('end_date', '<', now())
            ->update(['status' => PromoStatus::EXPIRED]);
    }
}
