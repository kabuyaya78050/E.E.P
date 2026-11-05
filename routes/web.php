<?php

use App\Http\Controllers\DonationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PageController;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');

Route::get('/a-propos', [PageController::class, 'about'])->name('about');
Route::get('/a-propos/vision', [PageController::class, 'vision'])->name('vision');
Route::get('/a-propos/mission-et-devise', [PageController::class, 'mission'])->name('mission');
Route::get('/a-propos/historique', [PageController::class, 'history'])->name('history');

Route::get('/evenements', [EventController::class, 'index'])->name('events.index');
Route::get('/actualites', [EventController::class, 'category'])->defaults('type', 'actualite')->name('events.actualites');
Route::get('/retraites', [EventController::class, 'category'])->defaults('type', 'retraite')->name('events.retraites');
Route::get('/seminaires', [EventController::class, 'category'])->defaults('type', 'seminaire')->name('events.seminaires');
Route::get('/evenements/{event}', [EventController::class, 'show'])->name('events.show');

Route::get('/media', [MediaController::class, 'index'])->name('media');
Route::get('/direct', [MediaController::class, 'live'])->name('live');
Route::get('/nous-trouver', [PageController::class, 'location'])->name('location');

Route::get('/don', [DonationController::class, 'create'])->name('don.create');
Route::post('/don', [DonationController::class, 'store'])->middleware('throttle:10,1')->name('don.store');
Route::post('/don/callback/{reference}', [DonationController::class, 'callback'])
    ->middleware('throttle:20,1')
    ->withoutMiddleware(PreventRequestForgery::class)
    ->name('don.callback');
Route::get('/don/{donation}', [DonationController::class, 'status'])->name('don.status');
Route::post('/don/{donation}/verifier', [DonationController::class, 'verify'])->middleware('throttle:20,1')->name('don.verify');

require __DIR__.'/admin.php';
