<?php

use App\Http\Controllers\App\RoomController;
use App\Http\Controllers\App\DashboardController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomTypeController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::name('guest.')->group(function () {
    Route::view('/', 'index')->name('home');
    Route::view('/rooms', 'rooms')->name('rooms');
    Route::view('/about', 'about')->name('about');
    Route::view('/contact', 'contact')->name('contact');
    Route::view('/reservation', 'reservation')->name('reservation');
    Route::view('/search', 'search')->name('search');

    // Add route for specific room details
    // ...

    // Send an email
    Route::post('/email', function () {
        return 'hello email!';
    });
});

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Global Auth routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Frontdesk
    Route::middleware('role:frontdesk|admin')->prefix('app')->name('app.')->group(function () {
        // Guests type route
        Route::resource('/guests', GuestController::class);

        // Reservation type route
        Route::resource('/reservations', ReservationController::class);
        Route::patch('/reservation/update-note/{reservation}', [ReservationController::class, 'updateNote'])->name('reservation.update-note');

        // Room type route
        Route::resource('/rooms', RoomTypeController::class);

        // Specific rooms for a room type route
        Route::resource('/rooms/{type}/room', RoomController::class);

        // Room type route
        Route::resource('/billings', BillingController::class);

    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::fallback(function () {
    return view('error.404');
});

require __DIR__ . '/auth.php';
