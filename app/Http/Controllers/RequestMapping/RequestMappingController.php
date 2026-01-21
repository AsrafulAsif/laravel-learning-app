<?php

namespace App\Http\Controllers\RequestMapping;

use App\Services\RequestMapping\RequestMappingService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RequestMappingController
{
    use ApiResponseTrait;
    protected RequestMappingService $service;
    public function __construct(RequestMappingService $service){
        $this->service = $service;
    }
    public function getByRequestId($requestId){
        $data = $this->service->getByRequestId($requestId);
        return $this->successResponse($data,"Request mapping retrieved");
    }

    public function requestMapping(int $requestId, Request $request):JsonResponse
    {
        $data = $this->service->requestMapping($requestId,$request);
        return $this->successResponse($data,"Request mapping retrieved");
    }
}
