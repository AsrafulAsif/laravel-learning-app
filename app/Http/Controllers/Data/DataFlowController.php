<?php

namespace App\Http\Controllers\Data;

use App\Http\Requests\Data\DataItemRequest;
use App\Services\Data\DataFlowService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class DataFlowController
{
    use ApiResponseTrait;

    protected DataFlowService $dataFlowService;
    public function __construct(DataFlowService $dataFlowService){
        $this->dataFlowService = $dataFlowService;
    }
    public function create(DataItemRequest $request) :JsonResponse
    {
        $this->dataFlowService->create($request->validated());
        return $this->successResponse(null, 'Data Flow successfully created');
    }

    public function update(DataItemRequest $request, $id) :JsonResponse
    {
        $updatedOrApproved =  $this->dataFlowService->update($request->validated(),$id);
        return $this->successResponse(null, $updatedOrApproved ?'Data Flow successfully updated' : 'Already Approved Data Flow');
    }
}
