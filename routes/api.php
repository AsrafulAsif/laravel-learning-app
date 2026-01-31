<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});


require __DIR__.'/Privilege/RoleRoute.php';
require __DIR__.'/Privilege/PermissionRoute.php';

//Route::prefix('permission')->group(function () {
//    Route::middleware('auth:sanctum')->group(function () {
//        Route::post('/create', [PermissionController::class, 'create']);
//        Route::get('/all', [PermissionController::class, 'getAllPermissions'])
//            ->middleware('permission:permission.view.all');;
//    });
//});
//
//Route::prefix('request-mapping')->group(function () {
//    Route::middleware('auth:sanctum')->group(function () {
//        Route::get('/{requestId}', [RequestMappingController::class, 'getByRequestId']);
//    });
//    Route::middleware('auth:sanctum')->group(function () {
//        Route::post('/{requestId}', [RequestMappingController::class, 'requestMapping']);
//    });
//});
