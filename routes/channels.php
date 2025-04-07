<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('reports.{user_id}', function (User $user, User $user_id) {
    return (int) $user->id === (int) $user_id;
});

Broadcast::channel('invoices.{invoice_id}', function (User $user) {
    return (int) $user->role === UserRole::ADMIN->value || (int) $user->role === UserRole::RECEPTIONIST->value;
});

Broadcast::channel('admin', function (User $user) {
    return (int) $user->role === UserRole::ADMIN->value;
});