<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});


require __DIR__.'/Privilege/RoleRoute.php';
require __DIR__.'/Privilege/PermissionRoute.php';
require __DIR__.'/DataFlow/DataFlowRoute.php';
require __DIR__.'/DataFlow/WorkFlowRoute.php';
