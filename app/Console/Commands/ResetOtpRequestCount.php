<?php

namespace App\Console\Commands;

use App\Models\Otp;
use Illuminate\Console\Command;

class ResetOtpRequestCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otp:reset-count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset OTP request count daily';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Otp::query()->update(['request_count' => 0]);
        $this->info('OTP request count has been reset.');
    }
}
