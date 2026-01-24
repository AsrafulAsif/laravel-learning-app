<?php

namespace App\Services\Privilege;

use App\Models\Privilege\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class RoleService
{
    public function create(array $data): void
    {
        Role::create([
            ...$data,
            'is_active'  => true,
            'is_deleted' => false,
            'created_by' => auth()->user()->employee_id,
        ]);

        Log::info('Role added successfully');
    }


    public function getAllRoles(): Collection
    {
        return Role::where('is_deleted', false)
            ->latest()
            ->get();
    }
}
