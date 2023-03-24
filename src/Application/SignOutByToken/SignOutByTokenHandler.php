<?php

declare(strict_types=1);

namespace App\Application\SignOutByToken;

use App\Domain\TokenService;
use App\Domain\UserRepository;
use App\Infrastructure\Domain\OAuthTokenRepository;
use App\SharedKernel\Exception\NotFoundException;
use App\SharedKernel\Messenger\CommandHandlerInterface;

final class SignOutByTokenHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly TokenService $tokenService,
    ){}

    public function __invoke(SignOutByTokenCommand $command): void
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
