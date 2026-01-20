<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiPermission
{
    use ApiResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {

        if (!$request->user()->hasPermission($permission)) {
             return $this->errorResponse(
                 'Forbidden. You do not have the required permission.',
                 Response::HTTP_FORBIDDEN
             );
        }

        return $next($request);
    }
}
