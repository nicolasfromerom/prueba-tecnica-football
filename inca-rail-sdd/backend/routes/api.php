<?php

use App\Modules\Auth\Presentation\LoginController;
use App\Modules\Auth\Presentation\LogoutController;
use App\Modules\Auth\Presentation\MeController;
use App\Modules\Users\Presentation\UserController;
use App\Modules\Roles\Presentation\RoleController;
use Illuminate\Support\Facades\Route;

Route::post('/login', LoginController::class);
Route::middleware(['auth:api', 'active'])->group(function () {
    Route::post('/logout', LogoutController::class);
    Route::get('/me', MeController::class);
    Route::get('/users', [UserController::class, 'index'])->middleware('permission:read,User');
    Route::get('/users/{id}', [UserController::class, 'show'])->middleware('permission:read,User');
    Route::post('/users', [UserController::class, 'store'])->middleware('permission:create,User');
    Route::put('/users/{id}', [UserController::class, 'update'])->middleware('permission:update,User');
    Route::patch('/users/{id}/status', [UserController::class, 'status'])->middleware('permission:status,User');
    Route::put('/users/{id}/roles', [UserController::class, 'assignRoles'])->middleware('permission:assign,UserRole');
    Route::get('/roles', [RoleController::class, 'index'])->middleware('permission:read,Role');
    Route::get('/roles/{id}', [RoleController::class, 'show'])->middleware('permission:read,Role');
    Route::post('/roles', [RoleController::class, 'store'])->middleware('permission:create,Role');
});
