<?php

namespace App\Services\Privilege;

use App\Exceptions\RecordNotFoundException;
use App\Models\Privilege\Role;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoleService
{
    protected RoleRepositoryInterface $roleRepository;
    protected UserRepositoryInterface $userRepository;

    public function __construct(RoleRepositoryInterface $roleRepository, UserRepositoryInterface $userRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->userRepository = $userRepository;
    }

    public function create(array $data): void
    {
        $user = Auth::user();
        $role = new Role();
        $role->role_name         = $data['role_name'];
        $role->role_display_name = $data['role_display_name'];
        $role->role_description  = $data['role_description'];
        $role->is_active         = true;
        $role->is_deleted        = false;
        $role->created_at        = now();
        $role->created_by        = $user['employee_id'];

        $this->roleRepository->save($role);

        Log::info('Role added successfully');
    }

    public function getAllRoles(): Collection
    {
        return $this->roleRepository->getAllRoles();
    }

    /**
     * @throws RecordNotFoundException
     */
    public function assignRole(Request $request): \Illuminate\Http\JsonResponse
    {
        $userId = $request->input('user_id');
        $roleId = $request->input('role_id');

        // Make sure both are provided
        if (!$userId || !$roleId) {
            return response()->json(['error' => 'Missing parameters'], 400);
        }

        $this->roleService->assignRoleToUser($userId, $roleId);

        return response()->json(['message' => 'Role assigned successfully']);
    }

}
