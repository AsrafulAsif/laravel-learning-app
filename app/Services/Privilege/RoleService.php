<?php

namespace App\Services\Privilege;

use App\Jobs\Privilege\SoftDeleteRoleJob;
use App\Models\Privilege\Role;
use App\Models\Privilege\UserRoles;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RoleService
{
    public function create(array $data): void
    {
        Role::create([
            ...$data,
            'is_active' => true,
            'is_deleted' => false,
            'created_by' => auth()->id(),
        ]);

        Log::info('Role added successfully');
    }


    public function getAllRoles(): Collection
    {
        return Role::where('is_deleted', false)
            ->latest()
            ->get();
    }

    public function update(int $role_id, array $data): void
    {
        $data = array_filter($data, fn($value) => !is_null($value));

        $role = Role::where('id', $role_id)
            ->where('is_deleted', false)
            ->firstOrFail();

        $role->update([
            ...$data,
            'updated_by' => auth()->id(),
            'updated_at' => now(),
        ]);

        Log::info("Role (ID: $role_id) updated successfully");
    }

    public function delete(int $role_id): void
    {
        $userId = auth()->id();

        // Return response immediately if this is in a controller
        SoftDeleteRoleJob::dispatch($role_id, $userId);

        Log::info("Soft delete job dispatched for Role ID: $role_id");
    }

    public function assignRoleToUser(array $data): void
    {
        $exists = UserRoles::where('user_id', $data['user_id'])
            ->where('role_id', $data['role_id'])
            ->where('is_active', true)
            ->where('is_deleted', false)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'role_id' => ['This user already has the selected role.'],
            ]);
        }
        UserRoles::create([
            ...$data,
            'is_active' => true,
            'is_deleted' => false,
            'created_by' => auth()->id(),
        ]);
        Log::info("Role assigned successfully for the user {$data['user_id']} and role {$data['role_id']}");
    }


    public function toggleUserRoleStatus(array $data): void
    {
        $userRole = UserRoles::where('user_id', $data['user_id'])
            ->where('role_id', $data['role_id'])
            ->where('is_deleted', false)
            ->firstOrFail();

        $newStatus = !$userRole->is_active;

        $userRole->update([
            'is_active'  => $newStatus,
            'updated_by' => auth()->id(),
            'updated_at' => now(),
        ]);

        Log::info(
            "Role {$data['role_id']} " .
            ($newStatus ? 'activated' : 'deactivated') .
            " for user {$data['user_id']}"
        );
    }


    public function removeRoleFromUser(array $data): void
    {
        $userRole = UserRoles::where('user_id', $data['user_id'])
            ->where('role_id', $data['role_id'])
            ->where('is_deleted', false)
            ->first();

        if (!$userRole) {
            throw ValidationException::withMessages([
                'role_id' => ['This role is not assigned to the user or already removed.'],
            ]);
        }

        $userRole->update([
            'is_active'  => false,
            'is_deleted' => true,
            'deleted_by' => auth()->id(),
            'deleted_at' => now(),
        ]);

        Log::info("Role removed successfully for user {$data['user_id']} and role {$data['role_id']}");
    }

    public function getUserRoles(int $user_id): Collection
    {
        return Role::join('user_roles', 'roles.id', '=', 'user_roles.role_id')
            ->where('user_roles.user_id', $user_id)
            ->where('roles.is_deleted', false)
            ->where('user_roles.is_deleted', false)
            ->orderBy('roles.id')
            ->select('roles.*')
            ->get();
    }

    public function getUserRolesName(int $user_id): array
    {
        return Role::join('user_roles', 'roles.id', '=', 'user_roles.role_id')
            ->where('user_roles.user_id', $user_id)
            ->where('roles.is_active', true)
            ->where('roles.is_deleted', false)
            ->where('user_roles.is_active', true)
            ->where('user_roles.is_deleted', false)
            ->orderBy('roles.id')
            ->pluck('roles.role_name')
            ->toArray();
    }

}
