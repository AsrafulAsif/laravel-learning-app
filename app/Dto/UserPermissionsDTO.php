<?php

namespace App\Dto;

use App\Models\Auth\User;
use Illuminate\Support\Collection;

class UserPermissionsDTO
{

    public User $user;
    public Collection $permissions;

    public function __construct(User $user, Collection $permissions)
    {
        $this->user = $user;
        $this->permissions = $permissions;
    }

    public function toArray(): array
    {
        return [
            'user' => $this->user,
            'permissions' => $this->permissions->toArray(),
        ];
    }
}
