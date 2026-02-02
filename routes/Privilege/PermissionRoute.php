<?php

use App\Http\Controllers\Privilege\PermissionController;

Route::prefix('permission')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/create', [PermissionController::class, 'create']);
        Route::get('/all', [PermissionController::class, 'getAllPermissions']);
        Route::get('/search', [PermissionController::class, 'searchPermissions']);
        route::put('/update/{permission_id}', [PermissionController::class, 'update']);
        route::delete('/delete/{permission_id}', [PermissionController::class, 'delete']);
        route::post('/assign-to-role', [PermissionController::class, 'assignPermissionToRole']);
        route::delete('/remove-from-role', [PermissionController::class, 'removePermissionFromRole']);
        route::get('/role-permissions-by-role-id/{role_id}', [PermissionController::class, 'getRolePermissions']);
        route::get('/role-permissions-by-user-id/{user_id}', [PermissionController::class, 'getUserPermissions']);
        route::post("/toggleRolePermissionStatus", [PermissionController::class, 'toggleRolePermissionStatus']);
    });
});

