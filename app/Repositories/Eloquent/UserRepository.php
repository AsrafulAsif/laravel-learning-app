<?php

namespace App\Repositories\Eloquent;

use App\Models\Auth\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{

    public function findUserByName(string $name): ?User
    {
        return User::query()
            ->where('name', $name)
            ->first();
    }

    public function findById(int $id): ?User
    {
        return User::query()
            ->where('id', $id)
            ->first();
    }
}
