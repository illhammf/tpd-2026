<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\KontakMasukController;

/* NOTE: Do Not Remove
/ Livewire asset handling if using sub folder in domain
*/

Livewire::setUpdateRoute(function ($handle) {
    return Route::post(config('app.asset_prefix') . '/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(config('app.asset_prefix') . '/livewire/livewire.js', $handle);
});
/*
/ END
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthUserController::class, 'login'])->name('login');
    Route::post('/login', [AuthUserController::class, 'loginStore'])->name('login.store');

    Route::get('/register', [AuthUserController::class, 'register'])->name('register');
    Route::post('/register', [AuthUserController::class, 'registerStore'])->name('register.store');
});

Route::post('/logout', [AuthUserController::class, 'logout'])->name('logout')->middleware('auth');

Route::post('/kontak', [KontakMasukController::class, 'store'])->name('kontak.store');