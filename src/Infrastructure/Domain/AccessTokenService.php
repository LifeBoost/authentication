<?php

declare(strict_types=1);

namespace App\Infrastructure\Domain;

use App\Domain\User;
use DateTimeImmutable;
use Firebase\JWT\JWT;

final class AccessTokenService
{
    private const EXPIRATION_TIME_IN_SECONDS = 300;

    public function __construct(
        private readonly string $secretKey,
        private readonly string $algorithm,
    ){}

    public function generate(User $user): Token
    {
        $expiresIn = (new DateTimeImmutable())
            ->modify(sprintf('+%d seconds', self::EXPIRATION_TIME_IN_SECONDS))
            ->getTimestamp();

        $token = JWT::encode(
            [
                'userId' => $user->getId()->toString(),
                'exp' => $expiresIn,
            ],
            $this->secretKey,
            $this->algorithm,
        );

        return new Token($token, $expiresIn);
    }
}
