<?php

namespace App\Services\Auth;

use App\Exceptions\RecordNotFoundException;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Auth\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use DateTimeInterface;
use Illuminate\Validation\UnauthorizedException;

class AuthService
{
    protected UserRepositoryInterface $userRepository;
    protected int $sanctumExpirationMinutes = 1440;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws RecordNotFoundException
     */
    public function login(LoginRequest $request): array
    {
        $user = $this->userRepository->findUserByName($request->name);

        if ($user === null) {
            throw new RecordNotFoundException("User not found.");
        }

        if ($user->password != $request->password) {
            throw new UnauthorizedException("Wrong password.");
        }

        $expiresAt = now()->addMinutes($this->sanctumExpirationMinutes);
        $expirationTime = $expiresAt->diffInSeconds(now());
        return [
            'apiUser' => $user,
            'token' => $this->createToken($user, $expiresAt),
            'token_type' => 'Bearer',
            'expires_in' => $expirationTime,
            'expires_at' => $expiresAt->toIso8601String(),
        ];
    }

    /*
     * ---------------------------------
     * private function to create token.
     * ---------------------------------
     */
    protected function createToken(User $user, DateTimeInterface $expiresAt): string
    {
//        $user->tokens()->delete();

        return $user->createToken(
            'api-token',
            ['*'],
            $expiresAt
        )->plainTextToken;
    }
}
