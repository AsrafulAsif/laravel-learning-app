<?php

namespace App\Services\Privilege;

use App\Exceptions\RecordNotFoundException;
use App\Models\Auth\User;
use App\Models\Privilege\Permission;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use App\Repositories\Eloquent\Privilege\PermissionRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PermissionService
{
    protected PermissionRepositoryInterface $permissionRepository;
    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function create(array $data): void
    {
        $user = Auth::user();

        $permission = new Permission();
        $permission->permission_name         = $data['permission_name'];
        $permission->permission_display_name = $data['permission_display_name'];
        $permission->permission_description  = $data['permission_description'];
        $permission->controller_name         = $data['controller_name'];
        $permission->is_active               = true;
        $permission->is_deleted              = false;
        $permission->created_at              = now();
        $permission->created_by              = $user['employee_id'];

        $this->permissionRepository->save($permission);

        Log::info('Permission added successfully');
    }

    public function getAllPermission(): Collection
    {
        return $this->permissionRepository->getAllPermission();
    }


    public function userHasPermission(int $userId, string $permissionSlug): bool
    {
        return User::where('id', $userId)
            ->whereHas('roles.permissions', function ($query) use ($permissionSlug) {
                $query->where('slug', $permissionSlug);
            })
            ->exists();
    }

}
