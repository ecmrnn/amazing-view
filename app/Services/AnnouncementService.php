<?php

namespace App\Services;

use App\Enums\AnnouncementStatus;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
}
