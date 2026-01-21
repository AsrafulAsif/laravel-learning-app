<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\RecordNotFoundException;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class AuthController
{
    use ApiResponseTrait;

    protected AuthService $authService;
    public function __construct(AuthService $authService){
        $this->authService = $authService;
    }

    /**
     * @throws RecordNotFoundException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $responseData = $this->authService->login($request);
        return $this->successResponse($responseData);
    }
}
