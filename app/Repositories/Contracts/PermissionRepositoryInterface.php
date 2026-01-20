<?php

namespace App\Repositories\Contracts;

use App\Models\Privilege\Permission;
use Illuminate\Database\Eloquent\Collection;

interface PermissionRepositoryInterface
{
    public function save(Permission $permission): void;
    public function getAllPermission(): Collection;
}
