<?php

use App\Http\Controllers\Privilege\PermissionController;
use App\Http\Controllers\Privilege\RoleController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;




Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});


Route::prefix('role')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/create', [RoleController::class, 'create']);
        Route::get('/all', [RoleController::class, 'getAllRoles']);
        Route::post('/assign-role', [RoleController::class, 'assignRole'])
            ->middleware('permission:role.assign.to.user');
    });
});

Route::prefix('permission')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/create', [PermissionController::class, 'create']);
        Route::get('/all', [PermissionController::class, 'getAllPermissions'])
            ->middleware('permission:permission.view.all');;
    });
});
