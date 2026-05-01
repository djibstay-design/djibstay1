<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/settings', [App\Http\Controllers\Api\SettingsController::class, 'index']);
Route::get('/payment-methods', [App\Http\Controllers\Api\SettingsController::class, 'paymentMethods']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password/forgot', [App\Http\Controllers\Api\ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [App\Http\Controllers\Api\ForgotPasswordController::class, 'reset']);

// Lecture publique (app mobile : navigation invité)
Route::get('/hotels', [HotelController::class, 'index']);
Route::get('/hotels/featured', [HotelController::class, 'featured']);
Route::get('/hotels/{hotel}', [HotelController::class, 'show']);
Route::get('/hotels/{hotel}/rooms', [HotelController::class, 'rooms']);
Route::get('/rooms/{room}', [HotelController::class, 'room']);

// Vérifier la disponibilité (public - pas besoin d'authentification)
Route::get('/rooms/{room_type_id}/available-dates', [ReservationController::class, 'getAvailabilityCalendar']);
Route::post('/availability/check', [ReservationController::class, 'checkAvailability']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user', [AuthController::class, 'updateProfile']);

    Route::get('/bookings', [ReservationController::class, 'index']);
    Route::post('/bookings', [ReservationController::class, 'store']);
    Route::get('/bookings/{reservation}', [ReservationController::class, 'show']);
    Route::post('/bookings/{reservation}/payment', [ReservationController::class, 'submitPayment']);
    Route::put('/bookings/{reservation}/cancel', [ReservationController::class, 'cancel']);

    Route::post('/reviews', [App\Http\Controllers\Api\ReviewController::class, 'store']);
});
