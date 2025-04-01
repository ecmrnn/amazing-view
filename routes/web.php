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
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\ServicesController;
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
    // Global authenticated routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

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
        });
    });
});

Route::fallback(function () {
    return view('error.404');
});

require __DIR__ . '/auth.php';
