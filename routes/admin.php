<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DonationController as AdminDonationController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\SermonController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/connexion', [AuthController::class, 'create'])->middleware('guest')->name('login');
Route::post('/connexion', [AuthController::class, 'store'])->middleware(['guest', 'throttle:5,1']);
Route::post('/deconnexion', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.events.index'))->name('home');
    Route::resource('evenements', AdminEventController::class)
        ->parameters(['evenements' => 'event'])
        ->names('events')
        ->except('show');
    Route::resource('predications', SermonController::class)
        ->parameters(['predications' => 'sermon'])
        ->names('sermons')
        ->except('show');
    Route::resource('administrateurs', UserController::class)
        ->parameters(['administrateurs' => 'user'])
        ->names('users')
        ->except('show');
    Route::get('reglages', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('reglages', [SettingController::class, 'update'])->name('settings.update');
    Route::get('dons', [AdminDonationController::class, 'index'])->name('donations.index');
});
