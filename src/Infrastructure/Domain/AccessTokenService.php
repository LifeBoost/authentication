<?php

declare(strict_types=1);

namespace App\Infrastructure\Domain;

use App\Domain\User;
use App\SharedKernel\Exception\NotFoundException;
use DateTimeImmutable;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Throwable;

final class AccessTokenService
{
    public function __construct(
        private readonly string $secretKey,
        private readonly string $algorithm,
        private readonly int $expirationTimeInSeconds,
    ) {}

    public function generate(User $user): Token
    {
        $expiresIn = (new DateTimeImmutable())
            ->modify(sprintf('+%d seconds', $this->expirationTimeInSeconds))
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

    /**
     * @throws NotFoundException
     */
    public function validate(string $token): void
    {
        try {
            JWT::decode($token, new Key($this->secretKey, $this->algorithm));
        } catch (Throwable) {
            throw NotFoundException::userNotFound();
        }
    }
}
