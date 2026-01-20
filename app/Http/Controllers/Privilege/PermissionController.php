<?php

namespace App\Http\Controllers\Privilege;

use App\Http\Requests\Privilege\PermissionAddRequest;
use App\Services\Privilege\PermissionService;
use App\Traits\ApiResponseTrait;

use Illuminate\Http\JsonResponse;

class PermissionController
{
    use ApiResponseTrait;
    protected PermissionService $permissionService;
    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function getAllPermissions() : JsonResponse
    {
        $response = $this->permissionService->getAllPermission();
        return $this->successResponse($response);
    }

    public function create(PermissionAddRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->permissionService->create($data);
        return $this->successResponse(null,"Permission created",201);
    }
}
