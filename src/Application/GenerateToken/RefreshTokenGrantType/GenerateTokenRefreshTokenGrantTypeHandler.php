<?php

declare(strict_types=1);

namespace App\Application\GenerateToken\RefreshTokenGrantType;

use App\Application\GenerateToken\GeneratedToken;
use App\Domain\TokenService;
use App\SharedKernel\Messenger\CommandHandlerInterface;

final class GenerateTokenRefreshTokenGrantTypeHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly TokenService $tokenService,
    ) {}

    public function __invoke(GenerateTokenRefreshTokenGrantTypeCommand $command): GeneratedToken
    {
        return $this->tokenService->generateByRefreshToken($command->refreshToken);
    }
}
