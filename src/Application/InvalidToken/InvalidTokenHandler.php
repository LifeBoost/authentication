<?php

declare(strict_types=1);

namespace App\Application\InvalidToken;

use App\Domain\TokenService;
use App\Domain\UserRepository;
use App\Infrastructure\Domain\OAuthTokenRepository;
use App\SharedKernel\Exception\NotFoundException;
use App\SharedKernel\Messenger\CommandHandlerInterface;

final class InvalidTokenHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly TokenService $tokenService,
    ){}

    public function __invoke(InvalidTokenCommand $command): void
    {
        try {
            $user = $this->userRepository->getByAccessToken($command->accessToken);

            $this->tokenService->deleteAllTokens($user);
        } catch (NotFoundException) {
            // user already logged off

            return;
        }

    }
}
