<?php

declare(strict_types=1);

namespace App\Infrastructure\Domain;

use App\Application\GenerateToken\GeneratedToken;
use App\Domain\TokenService;
use App\Domain\User;
use Doctrine\DBAL\Exception;

final class OAuthTokenService implements TokenService
{
    private const JWT_TOKEN_TYPE = 'jwt';

    public function __construct(
        public readonly OAuthTokenRepository $repository,
        public readonly AccessTokenService $accessTokenService,
        public readonly RefreshTokenService $refreshTokenService,
    ){}

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
}
