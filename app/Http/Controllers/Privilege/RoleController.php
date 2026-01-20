<?php

namespace App\Http\Controllers\Privilege;

use App\Exceptions\RecordNotFoundException;
use App\Http\Requests\Privilege\RoleAddRequest;
use App\Services\Privilege\RoleService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class RoleController
{
    use ApiResponseTrait;
    protected RoleService $roleService;
    public function __construct(RoleService $roleService){
        $this->roleService = $roleService;
    }

    public function getAllRoles() : JsonResponse
    {
        $response = $this->roleService->getAllRoles();
        return $this->successResponse($response);
    }

    public function create(RoleAddRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->roleService->create($data);
        return $this->successResponse(null,"Role created",201);
    }

    /**
     * @throws RecordNotFoundException
     */
    public function assignRole(int $userId, int $roleId): JsonResponse
    {
        $this->roleService->assignRoleToUser($userId, $roleId);
        return $this->successResponse(null,"Role assigned",201);
    }
}
