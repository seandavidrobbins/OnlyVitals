<?php

use App\Http\Controllers\Api\V1\HealthController;
use App\Http\Controllers\Api\V1\LoginController;
use App\Http\Controllers\Api\V1\LogoutController;
use App\Http\Controllers\Api\V1\MeController;
use App\Http\Controllers\Api\V1\RegisterController;
use App\Http\Controllers\Api\V1\WebsiteController;
use App\Http\Controllers\Api\V1\WebsiteHealthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/health', HealthController::class)->name('v1.health');
    Route::post('/register', RegisterController::class)->name('v1.register');
    Route::post('/login', LoginController::class)->name('v1.login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', MeController::class)->name('v1.me');
        Route::post('/logout', LogoutController::class)->name('v1.logout');
        Route::apiResource('websites', WebsiteController::class);
        Route::get('websites/{website}/health', [WebsiteHealthController::class, 'show'])
            ->name('v1.websites.health.show');
        Route::put('websites/{website}/health', [WebsiteHealthController::class, 'update'])
            ->name('v1.websites.health.update');
    });

});
