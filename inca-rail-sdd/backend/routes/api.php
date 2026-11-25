<?php

use App\Modules\Auth\Presentation\LoginController;
use Illuminate\Support\Facades\Route;

Route::post('/login', LoginController::class);
