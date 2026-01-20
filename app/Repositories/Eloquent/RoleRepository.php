<?php

namespace App\Repositories\Eloquent;

use App\Models\Privilege\Role;
use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RoleRepository implements RoleRepositoryInterface
{

    public function save(Role $role): void
    {
        $role->save();
    }

    public function getAllRoles(): Collection
    {
        return Role::all();
    }
}
