<?php

use App\Http\Controllers\Data\WorkFlowController;

Route::prefix('work-flow')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/create', [WorkFlowController::class, 'create']);
        Route::get('/{workflow_id}', [WorkFlowController::class, 'showWorkFlow']);
    });
});

