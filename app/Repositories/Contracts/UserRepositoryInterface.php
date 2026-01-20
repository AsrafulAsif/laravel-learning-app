<?php

namespace App\Repositories\Contracts;

use App\Models\Auth\User;

interface UserRepositoryInterface
{
    public function findUserByName(String $name) : ?User;
    public function findById(int $id) : ?User;
}
