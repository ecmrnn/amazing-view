<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('reports.{user_id}', function ($user, $user_id) {
    return (int) $user->id === (int) $user_id;
});

Broadcast::channel('report', function ($user) {
    return (int) $user->role === User::ROLE_ADMIN;
});