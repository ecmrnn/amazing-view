<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('reports.{user_id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
