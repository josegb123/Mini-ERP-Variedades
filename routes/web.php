<?php

use App\Enums\PermissionEnum;
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
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UserController::class, 'index'])
            ->middleware('permission:' . PermissionEnum::USER_READ->value)
            ->name('users.index');

        Route::post('/', [UserController::class, 'store'])
            ->middleware('permission:' . PermissionEnum::USER_CREATE->value)
            ->name('users.store');

        Route::put('/{user}', [UserController::class, 'update'])
            ->middleware('permission:' . PermissionEnum::USER_UPDATE->value)
            ->name('users.update');

        Route::delete('/{user}', [UserController::class, 'destroy'])
            ->middleware('permission:' . PermissionEnum::USER_DELETE->value)
            ->name('users.destroy');
    });
});

require __DIR__ . '/settings.php';
