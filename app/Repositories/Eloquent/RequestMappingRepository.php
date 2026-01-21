<?php

namespace App\Repositories\Eloquent;

use App\Models\RequestMap\RequestMapping;
use App\Repositories\Contracts\RequestMappingRepositoryInterface;

class RequestMappingRepository implements RequestMappingRepositoryInterface
{

    public function getByRequestId($requestId): ?RequestMapping
    {
        return RequestMapping::query()
            ->where('request_id', $requestId)
            ->first();
    }
}
