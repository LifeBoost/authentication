<?php

declare(strict_types=1);

namespace App\Domain;

use App\Application\GenerateToken\GeneratedToken;

interface TokenService
{
    public function generateNew(User $user): GeneratedToken;

    public function generateByRefreshToken(string $refreshToken): GeneratedToken;

    public function deleteAllTokens(User $user): void;
}
