<?php

use App\Http\Controllers\AmenityController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\App\RoomController;
use App\Http\Controllers\App\DashboardController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AppendDefaultUserFilters;
use App\Http\Middleware\CheckPageStatus;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::middleware([CheckPageStatus::class])->name('guest.')->group(function () {
    Route::get('/', [PageController::class, 'index'])->name('home');
    Route::get('/rooms', [PageController::class, 'rooms'])->name('rooms');
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/contact', [PageController::class, 'contact'])->name('contact');
    Route::get('/reservation', [PageController::class, 'reservation'])->name('reservation');
    // Route::get('/function-hall', [PageController::class, 'functionHall'])->name('function-hall');
    Route::get('/search', [PageController::class, 'findReservation'])->name('search');
});

// Authenticated Routes 
Route::middleware(['auth', 'verified'])->group(function () {
    // Global authenticated routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/profile', ProfileController::class);

    // Guest Routes
    Route::middleware(['role:guest'])->prefix('app')->name('app.')->group(function () {
        Route::get('/reservation/{user}', [ReservationController::class, 'guestReservations'])->name('reservations.guest-reservations');
        Route::get('/reservation/{reservation}/view', [ReservationController::class, 'showGuestReservations'])->name('reservations.show-guest-reservations');
        Route::get('/billing/{user}', [BillingController::class, 'guestBillings'])->name('billings.guest-billings');
        Route::get('/billing/{billing}/view', [BillingController::class, 'showGuestBillings'])->name('billings.show-guest-billings');
    });

    // Frontdesk & Admin routes
    Route::middleware('role:receptionist|admin')->prefix('app')->name('app.')->group(function () {
        Route::resource('/guests', GuestController::class);
        Route::get('/guests/check-out/{reservation}', [ReservationController::class, 'checkOut'])->name('reservation.check-out');
        Route::resource('/reservations', ReservationController::class);
        Route::resource('/rooms', RoomTypeController::class); /* Room Types route */
        Route::resource('/rooms/{type}/room', RoomController::class); /* Specific room routes */
        Route::resource('/billings', BillingController::class);

        // Admin specific routes
        Route::middleware('role:admin')->group(function () {
            Route::resource('/users', UserController::class)->middleware([AppendDefaultUserFilters::class]);
            Route::resource('/reports', ReportController::class);
            Route::resource('/contents', ContentController::class);
            Route::resource('/buildings', BuildingController::class);
            Route::resource('/amenity', AmenityController::class);
            Route::resource('/services', ServicesController::class);
            Route::resource('/announcements', AnnouncementController::class);
            Route::get('/promos', [PromoController::class, 'index'])->name('promos.index');
        });
    });
});

Route::fallback(function () {
    return view('error.404');
});

require __DIR__ . '/auth.php';
