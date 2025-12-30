<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TwoFactorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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

// 2FA Verification Routes (no auth middleware)
Route::middleware('guest')->group(function () {
    Route::get('/two-factor/verify', [TwoFactorController::class, 'showVerificationForm'])->name('2fa.verify');
    Route::post('/two-factor/verify', [TwoFactorController::class, 'verifyTwoFactor'])->name('2fa.verify.post');
});

require __DIR__.'/auth.php';