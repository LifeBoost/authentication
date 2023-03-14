<?php

declare(strict_types=1);

namespace App\Application\GenerateToken\RefreshTokenGrantType;

use App\Application\GenerateToken\GenerateTokenCommand;

final class GenerateTokenRefreshTokenGrantTypeCommand implements GenerateTokenCommand
{
    public function __construct(
        public readonly string $refreshToken,
    ){}
}
