<?php

namespace App\Http\Controllers\Data;


use App\Http\Requests\Data\WorkFlowRequest;
use App\Services\Data\WorkFlowService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Throwable;

class WorkFlowController
{
    use ApiResponseTrait;
    protected WorkFlowService $workFlowService;
    public function __construct(WorkFlowService $workFlowService)
    {
        $this->workFlowService = $workFlowService;
    }

    /**
     * @throws Throwable
     */
    public function create(WorkFlowRequest $request ) : JsonResponse
    {
        $this->workFlowService->create($request->validated());
        return $this->successResponse(null,'Work Flow successfully created');
    }

    public function showWorkFlow(int $workflowId) : JsonResponse
    {
        $response = $this->workFlowService->showWorkflowV2($workflowId);
        return $this->successResponse($response,'Work Flow successfully created');
    }
}
