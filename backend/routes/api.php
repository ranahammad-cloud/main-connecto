<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\MarketplaceController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\WalletController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('{provider}/redirect', [AuthController::class, 'redirect'])->whereIn('provider', ['google', 'linkedin']);
    Route::get('{provider}/callback', [AuthController::class, 'callback'])->whereIn('provider', ['google', 'linkedin']);
});

Route::post('payments/webhook', [PaymentController::class, 'webhook']);
Route::get('marketplace/interviewers', [MarketplaceController::class, 'index']);
Route::get('marketplace/interviewers/{user}', [MarketplaceController::class, 'show']);

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::apiResource('profiles', ProfileController::class)->only(['show', 'store', 'update']);
    Route::apiResource('bookings', BookingController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::post('bookings/{booking}/accept', [BookingController::class, 'accept']);
    Route::post('bookings/{booking}/reject', [BookingController::class, 'reject']);
    Route::post('bookings/{booking}/feedback', [BookingController::class, 'feedback']);
    Route::post('bookings/{booking}/payment-intent', [PaymentController::class, 'createIntent']);
    Route::get('sessions/{booking}', [SessionController::class, 'show']);
    Route::post('reviews', [ReviewController::class, 'store']);
    Route::get('wallet', [WalletController::class, 'show']);
    Route::post('wallet/withdrawals', [WalletController::class, 'withdraw']);

    Route::prefix('admin')->middleware('can:admin')->group(function () {
        Route::get('users', [AdminController::class, 'users']);
        Route::post('interviewers/{user}/approve', [AdminController::class, 'approveInterviewer']);
        Route::get('bookings', [AdminController::class, 'bookings']);
        Route::get('transactions', [AdminController::class, 'transactions']);
        Route::post('disputes/{booking}/resolve', [AdminController::class, 'resolveDispute']);
    });
});
