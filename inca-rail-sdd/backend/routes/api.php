<?php

use App\Modules\Auth\Presentation\LoginController;
use App\Modules\Auth\Presentation\LogoutController;
use App\Modules\Auth\Presentation\MeController;
use Illuminate\Support\Facades\Route;

Route::post('/login', LoginController::class);
Route::middleware(['auth:api', 'active'])->group(function () {
    Route::post('/logout', LogoutController::class);
    Route::get('/me', MeController::class);
});
