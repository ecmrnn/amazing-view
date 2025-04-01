<?php

namespace App\Services;

use App\Enums\AnnouncementStatus;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AnnouncementService
{
    public function create($data) {
        return DB::transaction(function () use ($data) {
            $data['user_id'] = Auth::user()->id;
            $data['image'] = $data['image']->store('announcements', 'public');
            $data['status'] = AnnouncementStatus::INACTIVE->value;

            return Announcement::create($data);
        });
    }

    public function update(Announcement $announcement, $data) {
        return DB::transaction(function () use ($announcement, $data) {
            $announcement->title = $data['title'];
            $announcement->description = $data['description'];

            if ($data['image']) {
                if (Storage::exists('public/' . $announcement->image)) {
                    Storage::disk('public')->delete($announcement->image);
                }

                $announcement->image = $data['image']->store('announcements', 'public');
            }

            return $announcement->save();
        });
    }

    public function disable(Announcement $announcement) {
        return DB::transaction(function () use ($announcement) {
            return $announcement->update([
                'status' => AnnouncementStatus::INACTIVE,
            ]);
        });
    }

    public function enable(Announcement $announcement) {
        return DB::transaction(function () use ($announcement) {
            Announcement::whereStatus(AnnouncementStatus::ACTIVE)->update([
                'status' => AnnouncementStatus::INACTIVE,
            ]);

            return $announcement->update([
                'status' => AnnouncementStatus::ACTIVE,
            ]);
        });
    }
}
