<?php

namespace App\Http\Controllers\Privilege;

use App\Http\Requests\Privilege\RoleRequest;
use App\Http\Requests\Privilege\UserRoleRequest;
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

    public function create(RoleRequest $request): JsonResponse
    {
        $this->roleService->create($request->validated());
        return $this->successResponse(null,"Role created",201);
    }
    public function update(RoleRequest $request, int $role_id): JsonResponse
    {
        $this->roleService->update($role_id, $request->validated());
        return $this->successResponse(null,"Role updated");
    }
    public function delete(int $role_id): JsonResponse
    {
        $this->roleService->delete($role_id);
        return $this->successResponse(null,"Role deleted");
    }

    public function assignRoleToUser(UserRoleRequest $request): JsonResponse
    {
        $this->roleService->assignRoleToUser($request->validated());
        return $this->successResponse(null,"Role assigned");
    }

    public function toggleUserRoleStatus(UserRoleRequest $request): JsonResponse
    {
        $this->roleService->toggleUserRoleStatus($request->validated());
        return $this->successResponse(null,"User Role status change.");
    }
    public function removeRoleFromUser(UserRoleRequest $request): JsonResponse
    {
        $this->roleService->removeRoleFromUser($request->validated());
        return $this->successResponse(null,"Role removed");
    }
}
