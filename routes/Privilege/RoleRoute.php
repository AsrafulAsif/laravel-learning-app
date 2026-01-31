<?php

use App\Http\Controllers\Privilege\RoleController;

Route::prefix('role')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/create', [RoleController::class, 'create']);
        Route::get('/all', [RoleController::class, 'getAllRoles']);
        Route::put('/update/{role_id}', [RoleController::class, 'update']);
        Route::delete('/delete/{role_id}', [RoleController::class, 'delete']);
    });
});


Route::prefix('role')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/assign-to-user', [RoleController::class, 'assignRoleToUser']);
        Route::put('/toggleUserRoleStatus', [RoleController::class, 'toggleUserRoleStatus']);
        Route::delete('/remove', [RoleController::class, 'removeRole']);
    });
});
