<?php

namespace App\Services\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\Auth\User;
use DateTimeInterface;
use Illuminate\Validation\UnauthorizedException;

class AuthService
{
    protected int $sanctumExpirationMinutes = 1440;

    public function login(LoginRequest $request): array
    {
        $user = User::whereName($request->name)->firstOrFail();

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


    protected function createToken(User $user, DateTimeInterface $expiresAt): string
    {
        return $user->createToken(
            'api-token',
            ['*'],
            $expiresAt
        )->plainTextToken;
    }
}
