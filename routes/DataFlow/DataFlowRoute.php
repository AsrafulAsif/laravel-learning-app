<?php

use App\Http\Controllers\Data\DataFlowController;

Route::prefix('data-flow')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/create', [DataFlowController::class, 'create']);
        Route::put('/update/{data_id}', [DataFlowController::class, 'update']);
    });
});
