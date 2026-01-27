<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    # Rutas del modulo de usuarios
    Route::resource('/users', UserController::class)->names([
        'index' => 'users.index',
        'show' => 'users.show',
        'store' => 'users.new',
        'destroy' => 'users.delete'
    ]);

});

require __DIR__ . '/settings.php';
