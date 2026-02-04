<?php

namespace App\Http\Middleware;

use App\Services\Privilege\PermissionService;
use App\Traits\ApiResponseTrait;
use Closure;
use Illuminate\Http\Request;

class CheckApiPermission
{
    use ApiResponseTrait;

    protected PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!$this->permissionService->userHasApiPermission(
            userId: auth()->user()->id,
            method: $request->method(),
            path: $request->route()->uri()
        )
        ) {
            return $this->errorResponse("You do not have permission to access this resource.", 403);
        }
        return $next($request);
    }
}
