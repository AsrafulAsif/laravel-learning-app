<?php

namespace App\Services\Privilege;

use App\Jobs\Privilege\SoftDeletePermissionJob;
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
            'created_by' => auth()->user()->employee_id,
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
                    ->orWhere('method_namea', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->get();
    }

    public function update(array $data, int $permission_id): void
    {
        $data = array_filter($data, fn($value) => !is_null($value));

        Permission::where('id', $permission_id)
            ->where('is_deleted', false)
            ->firstOrFail()
        ->update([
            ...$data,
            'updated_by' => auth()->user()->id,
            'updated_at' => now(),
        ]);

        Log::info("Permission (ID: $permission_id) updated successfully");
    }

    public function delete(int $permission_id): void
    {
        $userId = auth()->user()->id;

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
            'created_by' => auth()->user()->id,
        ]);
        Log::info("{$data['permission_id']} assign to {$data['role_id']} role successfully']}");
    }

}
