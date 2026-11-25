<?php

use App\Modules\Auth\Presentation\LoginController;
use App\Modules\Auth\Presentation\LogoutController;
use Illuminate\Support\Facades\Route;

Route::post('/login', LoginController::class);
Route::middleware('auth:api')->post('/logout', LogoutController::class);
