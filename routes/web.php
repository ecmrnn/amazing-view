<?php

use App\Http\Controllers\AmenityController;
use App\Http\Controllers\App\RoomController;
use App\Http\Controllers\App\DashboardController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AppendDefaultUserFilters;
use App\Http\Middleware\CheckPageStatus;
use Illuminate\Support\Facades\Route;
use League\CommonMark\Extension\DefaultAttributes\ApplyDefaultAttributesProcessor;

// Public Routes
Route::middleware([CheckPageStatus::class])->name('guest.')->group(function () {
    Route::get('/', [PageController::class, 'index'])->name('home');
    Route::get('/rooms', [PageController::class, 'rooms'])->name('rooms');
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/contact', [PageController::class, 'contact'])->name('contact');
    Route::get('/reservation', [PageController::class, 'reservation'])->name('reservation');
    Route::get('/function-hall', [PageController::class, 'functionHall'])->name('function-hall');
    Route::get('/search', [PageController::class, 'findReservation'])->name('search');

    // Add route for specific room details
    // ...
});

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Global Auth routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Frontdesk & Admin
    Route::middleware('role:receptionist|admin')->prefix('app')->name('app.')->group(function () {
        // Guests type route
        Route::resource('/guests', GuestController::class);

        // Reservation type route
        Route::resource('/reservations', ReservationController::class);

        // Room type route
        Route::resource('/rooms', RoomTypeController::class);

        // Specific rooms for a room type route
        Route::resource('/rooms/{type}/room', RoomController::class);

        // Billing route
        Route::resource('/billings', BillingController::class);

        // Admin specific routes
        Route::middleware('role:admin')->group(function () {
            // User route
            Route::resource('/users', UserController::class)->middleware([AppendDefaultUserFilters::class]);

            // Report route
            Route::resource('/reports', ReportController::class);

            // Content route
            Route::resource('/contents', ContentController::class);

            // Building
            Route::resource('/buildings', BuildingController::class);

            // Building
            Route::resource('/amenity', AmenityController::class);
        });
    });



    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::fallback(function () {
    return view('error.404');
});

require __DIR__ . '/auth.php';
