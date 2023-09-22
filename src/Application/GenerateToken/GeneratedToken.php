<?php

declare(strict_types=1);

namespace App\Application\GenerateToken;

final class GeneratedToken
{
    public function __construct(
        public readonly string $accessToken,
        public readonly string $refreshToken,
        public readonly int $expiresIn,
        public readonly int $refreshExpiresIn,
        public readonly string $tokenType,
    ) {}
}
