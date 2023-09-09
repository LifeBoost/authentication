<?php

declare(strict_types=1);

namespace App\Infrastructure\Domain;

use App\Application\GenerateToken\GeneratedToken;
use App\Domain\TokenService;
use App\Domain\User;
use App\Domain\UserRepository;
use App\SharedKernel\Exception\NotFoundException;
use Doctrine\DBAL\Exception;

final class OAuthTokenService implements TokenService
{
    private const JWT_TOKEN_TYPE = 'jwt';

    public function __construct(
        private readonly OAuthTokenRepository $repository,
        private readonly AccessTokenService $accessTokenService,
        private readonly RefreshTokenService $refreshTokenService,
        private readonly UserRepository $userRepository,
    ) {
    }

    /**
     * @throws Exception
     */
    public function generateNew(User $user): GeneratedToken
    {
        $accessToken = $this->accessTokenService->generate($user);
        $refreshToken = $this->refreshTokenService->generate($user);

        $generatedToken = new GeneratedToken(
            $accessToken->token,
            $refreshToken->token,
            $accessToken->expiresIn,
            $refreshToken->expiresIn,
            self::JWT_TOKEN_TYPE,
        );

        $this->repository->store($user->getId(), $generatedToken);

        return $generatedToken;
    }

    /**
     * @throws Exception
     * @throws NotFoundException
     */
    public function generateByRefreshToken(string $refreshToken): GeneratedToken
    {
        $this->refreshTokenService->validate($refreshToken);

        $user = $this->userRepository->getByRefreshToken($refreshToken);

        return $this->generateNew($user);
    }

    /**
     * @throws Exception
     */
    public function deleteAllTokens(User $user): void
    {
        $this->repository->delete($user->getId());
    }
}
