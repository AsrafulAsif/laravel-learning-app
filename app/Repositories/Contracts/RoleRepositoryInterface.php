<?php

namespace App\Repositories\Contracts;

use App\Models\Privilege\Role;

interface RoleRepositoryInterface
{
    public function save(Role $role);
    public function getAllRoles();
}
