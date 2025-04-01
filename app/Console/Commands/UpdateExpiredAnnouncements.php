<?php

namespace App\Console\Commands;

use App\Enums\AnnouncementStatus;
use App\Events\AnnouncementExpired;
use App\Models\Announcement;
use Illuminate\Console\Command;

class UpdateExpiredAnnouncements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'annoucement:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update expired announcements';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $announcement = Announcement::whereStatus(AnnouncementStatus::ACTIVE)
            ->whereDate('expires_at', '<', now()->format('Y-m-d'))
            ->update([
                'status' => AnnouncementStatus::INACTIVE,
            ]);

        if ($announcement > 0) {
            broadcast(new AnnouncementExpired);
        }
    }
}
