<?php

namespace App\Services\Privilege;

use App\Jobs\Privilege\SoftDeletePermissionJob;
use App\Models\Auth\User;
use App\Models\Privilege\Permission;
use App\Models\Privilege\RolePermissions;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PermissionService
{
    public function create(array $data): void
    {
        Permission::create([
            ...$data,
            'is_active' => true,
            'is_deleted' => false,
            'created_at' => now(),
            'created_by' => auth()->id(),
        ]);

        Log::info('Permission added successfully');
    }

    public function getAllPermission(): Collection
    {
        return Permission::where('is_deleted', false)
            ->orderBy('controller_name')
            ->orderBy('id')
            ->get()
            ->groupBy('controller_name');
    }


    public function search(string $search): Collection
    {
        return Permission::query()
            ->where('is_deleted', false)
            ->where(function ($query) use ($search) {
                $query->where('permission_name', 'LIKE', "%{$search}%")
                    ->orWhere('permission_display_name', 'LIKE', "%{$search}%")
                    ->orWhere('permission_description', 'LIKE', "%{$search}%")
                    ->orWhere('controller_name', 'LIKE', "%{$search}%")
                    ->orWhere('api_url', 'LIKE', "%{$search}%")
                    ->orWhere('method_name', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->get();
    }

    //Permission table active inactive means if we inactivate a permission and all role can not access the permission.
    public function update(array $data, int $permission_id): void
    {
        $data = array_filter($data, fn($value) => !is_null($value));

        Permission::where('id', $permission_id)
            ->where('is_deleted', false)
            ->firstOrFail()
            ->update([
                ...$data,
                'updated_by' => auth()->id(),
                'updated_at' => now(),
            ]);

        Log::info("Permission (ID: $permission_id) updated successfully");
    }

    public function delete(int $permission_id): void
    {
        $userId = auth()->id();

        // Return response immediately if this is in a controller
        SoftDeletePermissionJob::dispatch($permission_id, $userId);

        Log::info("Soft delete job dispatched for Role ID: $permission_id");
    }

    public function assignPermissionToRole(array $data): void
    {
        $exists = RolePermissions::where('role_id', $data['role_id'])
            ->where('permission_id', $data['permission_id'])
            ->where('is_active', true)
            ->where('is_deleted', false)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'role_id' => ['This role already has the selected permission.'],
            ]);
        }
        RolePermissions::create([
            ...$data,
            'is_active' => true,
            'is_deleted' => false,
            'created_at' => now(),
            'created_by' => auth()->id(),
        ]);
        Log::info("{$data['permission_id']} assign to {$data['role_id']} role successfully']}");
    }

    public function removePermissionFromRole(array $data): void
    {
        $rolePermission = RolePermissions::where('role_id', $data['role_id'])
            ->where('permission_id', $data['permission_id'])
            ->where('is_deleted', false)
            ->first();

        if (!$rolePermission) {
            throw ValidationException::withMessages([
                'role_id' => ['This role is not assigned to the permission or already removed.'],
            ]);
        }

        $rolePermission->update([
            'is_active' => false,
            'is_deleted' => true,
            'deleted_by' => auth()->id(),
            'deleted_at' => now(),
        ]);

        Log::info("Permission removed successfully for role {$data['role_id']} and permission {$data['permission_id']}");

    }
    public function getRolePermissions(int $role_id): Collection
    {
        return Permission::join('role_permissions', 'permissions.id', '=', 'role_permissions.permission_id')
            ->where('role_permissions.role_id', $role_id)
            ->where('permissions.is_deleted', false)
            ->where('role_permissions.is_deleted', false)
            ->orderBy('permissions.id')
            ->select('permissions.*')
            ->get();
    }

    public function getUserPermissions(int $user_id): array
    {
        $user = User::findOrFail($user_id);

        $permissions =  Permission::join('role_permissions', 'permissions.id', '=', 'role_permissions.permission_id')
            ->join('roles', 'roles.id', '=', 'role_permissions.role_id')
            ->join('user_roles', 'user_roles.role_id', '=', 'roles.id')
            ->where('user_roles.user_id', $user_id)
            ->where('permissions.is_deleted', false)
            ->where('role_permissions.is_deleted', false)
            ->where('roles.is_deleted', false)
            ->where('user_roles.is_deleted', false)
            ->orderBy('permissions.id')
            ->select('permissions.*')
            ->distinct() // (remove duplicates if a user has the same permission via multiple roles)
            ->get();
        return [
            'user' => $user,
            'permissions' => $permissions,
        ];
    }

    //this update for the role can not access the permission
    public function toggleRolePermissionStatus(array $data): void
    {
        $rolePermission = RolePermissions::where('role_id', $data['role_id'])
            ->where('permission_id', $data['permission_id'])
            ->where('is_deleted', false)
            ->first();

        if (!$rolePermission) {
            throw ValidationException::withMessages([
                'permission_id' => ['Permission not found for this role.'],
            ]);
        }

        $newStatus = !$rolePermission->is_active;

        $rolePermission->update([
            'is_active'  => $newStatus,
            'updated_by' => auth()->id(),
            'updated_at' => now(),
        ]);

        Log::info(
            "Permission {$data['permission_id']} " .
            ($newStatus ? 'activated' : 'deactivated') .
            " for role {$data['role_id']} by user " . auth()->user()->id
        );
    }

    public function userHasApiPermission(
        int $userId,
        string $method,
        string $path
    ): bool {
        Log::info(
            $userId.$method.$path
        );
        return Permission::query()
            ->join('role_permissions as rp', function ($join) {
                $join->on('permissions.id', '=', 'rp.permission_id')
                    ->where('rp.is_active', true)
                    ->where('rp.is_deleted', false);
            })
            ->join('roles as r', function ($join) {
                $join->on('r.id', '=', 'rp.role_id')
                    ->where('r.is_active', true)
                    ->where('r.is_deleted', false);
            })
            ->join('user_roles as ur', function ($join) {
                $join->on('ur.role_id', '=', 'r.id')
                    ->where('ur.is_active', true)
                    ->where('ur.is_deleted', false);
            })
            ->where('ur.user_id', $userId)
            ->where('permissions.is_active', true)
            ->where('permissions.is_deleted', false)
            ->where('permissions.method_name', $method)
            ->where('permissions.api_url', $path)
            ->exists();
    }

}
