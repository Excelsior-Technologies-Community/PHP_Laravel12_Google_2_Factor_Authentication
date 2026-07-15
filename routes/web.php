<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get(
    '/dashboard',
    [DashboardController::class, 'index']
)

    ->middleware(['auth', 'verified'])

    ->name('dashboard');



Route::post(
    '/user-status/{id}',
    [DashboardController::class, 'updateStatus']
)

    ->middleware('auth')

    ->name('user.status');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 2FA Routes
    Route::get('/two-factor/setup', [TwoFactorController::class, 'showSetupForm'])->name('2fa.setup');
    Route::post('/two-factor/enable', [TwoFactorController::class, 'enableTwoFactor'])->name('2fa.enable');
    Route::post('/two-factor/disable', [TwoFactorController::class, 'disableTwoFactor'])->name('2fa.disable');
    Route::get('/two-factor/recovery', [TwoFactorController::class, 'showRecoveryCodes'])->name('2fa.recovery');
    Route::post('/two-factor/recovery/generate', [TwoFactorController::class, 'generateNewRecoveryCodes'])->name('2fa.recovery.generate');
});

Route::middleware('auth')->group(function () {


    Route::delete(
        '/users/{id}',
        [UserController::class, 'destroy']
    )
        ->name('users.delete');



    Route::get(
        '/users-export',
        [UserController::class, 'export']
    )
        ->name('users.export');
});

// 2FA Verification Routes (no auth middleware)
Route::middleware('guest')->group(function () {
    Route::get('/two-factor/verify', [TwoFactorController::class, 'showVerificationForm'])->name('2fa.verify');
    Route::post('/two-factor/verify', [TwoFactorController::class, 'verifyTwoFactor'])->name('2fa.verify.post');
});

require __DIR__ . '/auth.php';
