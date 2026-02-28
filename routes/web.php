<?php

use App\Http\Controllers\Admin\AvisController as AdminAvisController;
use App\Http\Controllers\Admin\ChambreController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HotelController as AdminHotelController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\TypeChambreController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AvisController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('hotels.index'))->name('home');

Route::get('/hotels', [HotelController::class, 'index'])->name('hotels.index');
Route::get('/hotels/{hotel}', [HotelController::class, 'show'])->name('hotels.show');

Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
Route::get('/reservations/confirmation/{reservation}', [ReservationController::class, 'confirmation'])->name('reservations.confirmation');

Route::post('/avis', [AvisController::class, 'store'])->name('avis.store');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::resource('hotels', AdminHotelController::class)->except(['show']);
    Route::resource('types-chambre', TypeChambreController::class)->parameters(['types-chambre' => 'typeChambre']);
    Route::resource('chambres', ChambreController::class)->parameters(['chambres' => 'chambre']);
    Route::resource('reservations', AdminReservationController::class)->only(['index', 'show', 'edit', 'update', 'destroy'])->parameters(['reservations' => 'reservation']);
    Route::get('avis', [AdminAvisController::class, 'index'])->name('avis.index');
});
