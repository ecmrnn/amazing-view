<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    return view('index');
});

Route::get('/rooms', function () {
    return view('rooms');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/reservation', function () {
    return view('reservation');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Front-desk
        // Dashboard
        // Reservations
        // Guests 
        // Rooms
        // Invoices
        // Profile
    
    // Admin
        // Dashboard
        // Users
        // Inventory (Amenities)
        // Reports
        // Billings
        // Website
        // Profile

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::fallback(function () {
    return view('error.404');
});

require __DIR__.'/auth.php';
