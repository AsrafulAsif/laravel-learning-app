<?php

use App\Http\Controllers\Data\DataController;

Route::prefix('data-flow')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/create', [DataController::class, 'create']);
        Route::put('/update/{data_id}', [DataController::class, 'update']);
    });
});
