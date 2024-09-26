<?php

use App\Http\Controllers\ProfileController;
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
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Frontdesk
    Route::middleware('frontdesk')->name('frontdesk.')->group(function () {
        // frontdesk routes goes here...
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::fallback(function () {
    return view('error.404');
});

require __DIR__.'/auth.php';
