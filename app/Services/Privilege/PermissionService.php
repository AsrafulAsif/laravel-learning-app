<?php

namespace App\Services\Privilege;

use App\Models\Privilege\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class PermissionService
{
    public function create(array $data): void
    {
        Permission::create([
            ...$data,
            'is_active'  => true,
            'is_deleted' => false,
            'created_at' => now(),
            'created_by' => auth()->user()->employee_id,
        ]);

        Log::info('Permission added successfully');
    }

    public function getAllPermission(): Collection
    {
        return Permission::where('is_deleted', false)->latest()->get();
    }

}
