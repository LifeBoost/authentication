<?php

declare(strict_types=1);

namespace App\Infrastructure\Domain;

use App\Domain\SignInTokenService;
use App\Domain\User;
use Firebase\JWT\JWT;

final class JWTTokenService implements SignInTokenService
{
    public function __construct(
        public readonly string $jwtSecretKey,
        public readonly string $jwtAlgorithm,
    ){}

    public function generateToken(User $user): string
    {
        return JWT::encode(['userId' => $user->getId()->toString()], $this->jwtSecretKey, $this->jwtAlgorithm);
    }
}
