<?php

namespace App\Repositories\Contracts;

interface RequestMappingRepositoryInterface
{
    public function getByRequestId($requestId);

}
