<?php

namespace App\Repositories\Eloquent;

use App\Models\Privilege\Permission;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PermissionRepository implements PermissionRepositoryInterface
{
    public function save(Permission $permission): void
    {
        $permission->save();
    }

    public function getAllPermission(): Collection
    {
        return Permission::all();
    }

}
